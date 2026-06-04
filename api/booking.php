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
    echo json_encode(['success' => false, 'error' => 'Database error: ' . $e->getMessage()]);
    exit();
}

$method = $_SERVER['REQUEST_METHOD'];

try {
    switch ($method) {
        case 'GET':
            $stmt = $pdo->query("SELECT * FROM booking ORDER BY created_at DESC");
            $bookings = $stmt->fetchAll();
            foreach ($bookings as &$item) {
                $item['id'] = (int)$item['id'];
                $item['jumlah'] = (int)$item['jumlah'];
            }
            echo json_encode(['success' => true, 'count' => count($bookings), 'data' => $bookings]);
            break;
            
        case 'POST':
            $input = json_decode(file_get_contents('php://input'), true);
            if (!$input) {
                http_response_code(400);
                echo json_encode(['success' => false, 'error' => 'Invalid JSON']);
                exit();
            }
            
            $stmt = $pdo->prepare("
                INSERT INTO booking 
                (nama, instagram, facebook, nohp1, nowa2, norek, pekerjaan,
                 tgl_mulai, jam_mulai, lokasi_mulai, tgl_selesai, jam_selesai, lokasi_selesai,
                 kendaraan_nama, jumlah, identitas1, identitas2, identitas3, catatan)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $input['nama'] ?? '',
                $input['instagram'] ?? '',
                $input['facebook'] ?? '',
                $input['nohp1'] ?? '',
                $input['nowa2'] ?? '',
                $input['norek'] ?? '',
                $input['pekerjaan'] ?? '',
                $input['tgl_mulai'] ?? date('Y-m-d'),
                $input['jam_mulai'] ?? date('H:i'),
                $input['lokasi_mulai'] ?? '',
                $input['tgl_selesai'] ?? date('Y-m-d'),
                $input['jam_selesai'] ?? date('H:i'),
                $input['lokasi_selesai'] ?? '',
                $input['kendaraan_nama'] ?? '',
                $input['jumlah'] ?? 1,
                $input['identitas1'] ?? '',
                $input['identitas2'] ?? '',
                $input['identitas3'] ?? '',
                $input['catatan'] ?? ''
            ]);
            
            http_response_code(201);
            echo json_encode(['success' => true, 'message' => 'Booking tersimpan', 'data' => ['id' => (int)$pdo->lastInsertId()]]);
            break;
            
        case 'PUT':
            $input = json_decode(file_get_contents('php://input'), true);
            if (!isset($input['id'])) {
                http_response_code(400);
                echo json_encode(['success' => false, 'error' => 'ID diperlukan']);
                exit();
            }
            
            $id = (int)$input['id'];
            $updates = [];
            $values = [];
            $allowed = ['status', 'dp_admin', 'pelunasan_admin', 'plat_kendaraan', 'wa_pengantar', 'catatan'];
            
            foreach ($allowed as $field) {
                if (isset($input[$field])) {
                    $updates[] = "$field = ?";
                    $values[] = $input[$field];
                }
            }
            
            if (empty($updates)) {
                http_response_code(400);
                echo json_encode(['success' => false, 'error' => 'Tidak ada data untuk diupdate']);
                exit();
            }
            
            $values[] = $id;
            $stmt = $pdo->prepare("UPDATE booking SET " . implode(', ', $updates) . " WHERE id = ?");
            $stmt->execute($values);
            echo json_encode(['success' => true, 'message' => 'Booking diupdate']);
            break;
            
        default:
            http_response_code(405);
            echo json_encode(['success' => false, 'error' => 'Method not allowed']);
    }
} catch(PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>