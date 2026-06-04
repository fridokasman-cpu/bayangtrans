<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

try {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $stmt = $pdo->query("SELECT * FROM kendaraan ORDER BY tipe, nama");
        $kendaraan = $stmt->fetchAll();
        
        foreach ($kendaraan as &$item) {
            $item['fitur'] = explode(',', $item['fitur']);
            $item['harga'] = (int)$item['harga'];
            $item['rating'] = (float)$item['rating'];
            $item['id'] = (int)$item['id'];
            $item['tersedia'] = (bool)$item['tersedia'];
        }
        
        echo json_encode($kendaraan);
    }
} catch(PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>