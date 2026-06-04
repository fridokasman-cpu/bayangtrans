<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, PUT, OPTIONS');
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
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database connection failed: ' . $e->getMessage()]);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        $stmt = $pdo->query("SELECT * FROM kendaraan ORDER BY tipe ASC, nama ASC");
        $kendaraan = $stmt->fetchAll();
        
        foreach ($kendaraan as &$item) {
            $item['fitur'] = explode(',', $item['fitur']);
            $item['harga'] = (int)$item['harga'];
            $item['rating'] = (float)$item['rating'];
            $item['id'] = (int)$item['id'];
            $item['tersedia'] = (bool)$item['tersedia'];
        }
        
        echo json_encode($kendaraan);
        
    } catch(PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    try {
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!isset($input['id'])) {
            http_response_code(400);
            echo json_encode(['error' => 'ID diperlukan']);
            exit();
        }
        
        $id = (int)$input['id'];
        
        if (isset($input['tersedia'])) {
            $tersedia = $input['tersedia'] ? 1 : 0;
            $stmt = $pdo->prepare("UPDATE kendaraan SET tersedia = ? WHERE id = ?");
            $stmt->execute([$tersedia, $id]);
            echo json_encode(['success' => true]);
        }
        elseif (isset($input['harga'])) {
            $harga = (int)$input['harga'];
            $stmt = $pdo->prepare("UPDATE kendaraan SET harga = ? WHERE id = ?");
            $stmt->execute([$harga, $id]);
            echo json_encode(['success' => true]);
        }
        
    } catch(PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
}
?>