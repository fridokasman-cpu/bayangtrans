<?php
// Set headers
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Access-Control-Max-Age: 86400');

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Database configuration
$host = 'localhost';
$dbname = 'bayangtrans_db';
$username = 'root';
$password = '';

// Connect to database
try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
} catch(PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Database connection failed',
        'message' => $e->getMessage()
    ]);
    exit();
}

// Route requests
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        handleGet($pdo);
        break;
    
    case 'PUT':
        handlePut($pdo);
        break;
    
    case 'POST':
        handlePost($pdo);
        break;
    
    case 'DELETE':
        handleDelete($pdo);
        break;
    
    default:
        http_response_code(405);
        echo json_encode([
            'success' => false,
            'error' => 'Method not allowed',
            'allowed_methods' => ['GET', 'PUT', 'POST', 'DELETE']
        ]);
        break;
}

/**
 * GET - Fetch all kendaraan
 */
function handleGet($pdo) {
    try {
        $stmt = $pdo->query("SELECT * FROM kendaraan ORDER BY tipe ASC, nama ASC");
        $kendaraan = $stmt->fetchAll();
        
        // Format data
        foreach ($kendaraan as &$item) {
            $item['fitur'] = !empty($item['fitur']) ? explode(',', $item['fitur']) : [];
            $item['harga'] = (int)$item['harga'];
            $item['rating'] = (float)$item['rating'];
            $item['id'] = (int)$item['id'];
            $item['tersedia'] = (bool)$item['tersedia'];
        }
        
        echo json_encode([
            'success' => true,
            'count' => count($kendaraan),
            'data' => $kendaraan
        ]);
        
    } catch(PDOException $e) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'error' => 'Failed to fetch data',
            'message' => $e->getMessage()
        ]);
    }
}

/**
 * PUT - Update ketersediaan or harga
 */
function handlePut($pdo) {
    try {
        // Get JSON input
        $input = json_decode(file_get_contents('php://input'), true);
        
        // Validate input
        if (!$input) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'error' => 'Invalid JSON input'
            ]);
            return;
        }
        
        if (!isset($input['id'])) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'error' => 'ID kendaraan diperlukan'
            ]);
            return;
        }
        
        $id = (int)$input['id'];
        
        // Check if kendaraan exists
        $checkStmt = $pdo->prepare("SELECT id FROM kendaraan WHERE id = ?");
        $checkStmt->execute([$id]);
        if (!$checkStmt->fetch()) {
            http_response_code(404);
            echo json_encode([
                'success' => false,
                'error' => 'Kendaraan tidak ditemukan',
                'id' => $id
            ]);
            return;
        }
        
        // Update ketersediaan
        if (isset($input['tersedia'])) {
            $tersedia = $input['tersedia'] ? 1 : 0;
            $stmt = $pdo->prepare("UPDATE kendaraan SET tersedia = ? WHERE id = ?");
            $stmt->execute([$tersedia, $id]);
            
            echo json_encode([
                'success' => true,
                'message' => 'Ketersediaan berhasil diperbarui',
                'data' => [
                    'id' => $id,
                    'tersedia' => (bool)$tersedia
                ]
            ]);
            return;
        }
        
        // Update harga
        if (isset($input['harga'])) {
            $harga = (int)$input['harga'];
            
            if ($harga < 0) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'error' => 'Harga tidak boleh negatif'
                ]);
                return;
            }
            
            $stmt = $pdo->prepare("UPDATE kendaraan SET harga = ? WHERE id = ?");
            $stmt->execute([$harga, $id]);
            
            echo json_encode([
                'success' => true,
                'message' => 'Harga berhasil diperbarui',
                'data' => [
                    'id' => $id,
                    'harga' => $harga
                ]
            ]);
            return;
        }
        
        // No valid field to update
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'error' => 'Field yang akan diupdate tidak valid. Gunakan "tersedia" atau "harga"'
        ]);
        
    } catch(PDOException $e) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'error' => 'Failed to update data',
            'message' => $e->getMessage()
        ]);
    }
}

/**
 * POST - Create new kendaraan
 */
function handlePost($pdo) {
    try {
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!$input) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'error' => 'Invalid JSON input'
            ]);
            return;
        }
        
        // Validate required fields
        $required = ['nama', 'tipe', 'harga'];
        foreach ($required as $field) {
            if (!isset($input[$field])) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'error' => "Field '$field' diperlukan"
                ]);
                return;
            }
        }
        
        // Validate tipe
        if (!in_array($input['tipe'], ['Motor', 'Mobil'])) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'error' => 'Tipe harus "Motor" atau "Mobil"'
            ]);
            return;
        }
        
        $stmt = $pdo->prepare("
            INSERT INTO kendaraan (nama, tipe, harga, gambar, fitur, rating, tersedia) 
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        
        $stmt->execute([
            $input['nama'],
            $input['tipe'],
            (int)$input['harga'],
            $input['gambar'] ?? null,
            is_array($input['fitur'] ?? null) ? implode(',', $input['fitur']) : ($input['fitur'] ?? ''),
            (float)($input['rating'] ?? 4.5),
            (int)($input['tersedia'] ?? 1)
        ]);
        
        http_response_code(201);
        echo json_encode([
            'success' => true,
            'message' => 'Kendaraan berhasil ditambahkan',
            'data' => [
                'id' => (int)$pdo->lastInsertId()
            ]
        ]);
        
    } catch(PDOException $e) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'error' => 'Failed to create data',
            'message' => $e->getMessage()
        ]);
    }
}

/**
 * DELETE - Delete kendaraan
 */
function handleDelete($pdo) {
    try {
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!isset($input['id'])) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'error' => 'ID kendaraan diperlukan'
            ]);
            return;
        }
        
        $id = (int)$input['id'];
        
        $stmt = $pdo->prepare("DELETE FROM kendaraan WHERE id = ?");
        $stmt->execute([$id]);
        
        if ($stmt->rowCount() === 0) {
            http_response_code(404);
            echo json_encode([
                'success' => false,
                'error' => 'Kendaraan tidak ditemukan'
            ]);
            return;
        }
        
        echo json_encode([
            'success' => true,
            'message' => 'Kendaraan berhasil dihapus',
            'data' => ['id' => $id]
        ]);
        
    } catch(PDOException $e) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'error' => 'Failed to delete data',
            'message' => $e->getMessage()
        ]);
    }
}
?>