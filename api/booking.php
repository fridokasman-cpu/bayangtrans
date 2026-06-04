<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

$host = 'localhost';
$dbname = 'bayangtrans_db';
$username = 'root';
$password = '';

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
} catch(PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Database connection failed: ' . $e->getMessage()]);
    exit();
}

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        handleGet($pdo);
        break;
    case 'POST':
        handlePost($pdo);
        break;
    case 'PUT':
        handlePut($pdo);
        break;
    case 'DELETE':
        handleDelete($pdo);
        break;
    default:
        http_response_code(405);
        echo json_encode(['success' => false, 'error' => 'Method not allowed']);
}

// GET - Ambil semua booking (untuk admin)
function handleGet($pdo) {
    try {
        $stmt = $pdo->query("SELECT * FROM booking ORDER BY created_at DESC");
        $bookings = $stmt->fetchAll();
        
        foreach ($bookings as &$item) {
            $item['id'] = (int)$item['id'];
            $item['jumlah'] = (int)$item['jumlah'];
        }
        
        echo json_encode([
            'success' => true,
            'count' => count($bookings),
            'data' => $bookings
        ]);
    } catch(PDOException $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
}

// POST - Tambah booking baru (dari form pelanggan)
function handlePost($pdo) {
    try {
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!$input) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Invalid JSON']);
            return;
        }
        
        // Validasi field wajib
        $required = ['nama', 'nohp1', 'nowa2', 'pekerjaan', 'tgl_mulai', 'jam_mulai', 
                     'lokasi_mulai', 'tgl_selesai', 'jam_selesai', 'lokasi_selesai', 
                     'kendaraan_nama', 'identitas1', 'identitas2', 'identitas3'];
        
        foreach ($required as $field) {
            if (!isset($input[$field]) || empty($input[$field])) {
                http_response_code(400);
                echo json_encode(['success' => false, 'error' => "Field '$field' wajib diisi"]);
                return;
            }
        }
        
        $stmt = $pdo->prepare("
            INSERT INTO booking 
            (nama, instagram, facebook, nohp1, nowa2, norek, pekerjaan,
             tgl_mulai, jam_mulai, lokasi_mulai, tgl_selesai, jam_selesai, lokasi_selesai,
             kendaraan_nama, jumlah, identitas1, identitas2, identitas3, catatan)
            VALUES 
            (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        
        $stmt->execute([
            $input['nama'],
            $input['instagram'] ?? '',
            $input['facebook'] ?? '',
            $input['nohp1'],
            $input['nowa2'],
            $input['norek'] ?? '',
            $input['pekerjaan'],
            $input['tgl_mulai'],
            $input['jam_mulai'],
            $input['lokasi_mulai'],
            $input['tgl_selesai'],
            $input['jam_selesai'],
            $input['lokasi_selesai'],
            $input['kendaraan_nama'],
            $input['jumlah'] ?? 1,
            $input['identitas1'],
            $input['identitas2'],
            $input['identitas3'],
            $input['catatan'] ?? ''
        ]);
        
        http_response_code(201);
        echo json_encode([
            'success' => true,
            'message' => 'Booking berhasil disimpan',
            'data' => [
                'id' => (int)$pdo->lastInsertId()
            ]
        ]);
        
    } catch(PDOException $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
}

// PUT - Update status booking (oleh admin)
function handlePut($pdo) {
    try {
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!isset($input['id'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'ID booking diperlukan']);
            return;
        }
        
        $id = (int)$input['id'];
        $updates = [];
        $values = [];
        
        // Field yang bisa diupdate
        $allowedFields = ['status', 'dp_admin', 'pelunasan_admin', 'plat_kendaraan', 'wa_pengantar', 'catatan'];
        
        foreach ($allowedFields as $field) {
            if (isset($input[$field])) {
                $updates[] = "$field = ?";
                $values[] = $input[$field];
            }
        }
        
        if (empty($updates)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Tidak ada data untuk diupdate']);
            return;
        }
        
        $values[] = $id;
        $sql = "UPDATE booking SET " . implode(', ', $updates) . " WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($values);
        
        echo json_encode([
            'success' => true,
            'message' => 'Booking berhasil diupdate'
        ]);
        
    } catch(PDOException $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
}

// DELETE - Hapus booking
function handleDelete($pdo) {
    try {
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!isset($input['id'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'ID booking diperlukan']);
            return;
        }
        
        $stmt = $pdo->prepare("DELETE FROM booking WHERE id = ?");
        $stmt->execute([$input['id']]);
        
        echo json_encode(['success' => true, 'message' => 'Booking dihapus']);
        
    } catch(PDOException $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
}
?>