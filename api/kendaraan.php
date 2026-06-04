<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once '../config/database.php';

try {
    $stmt = $pdo->query("SELECT * FROM kendaraan WHERE status = 'Tersedia' ORDER BY tipe, nama");
    $kendaraan = $stmt->fetchAll();
    
    // Format fitur dari string ke array
    foreach ($kendaraan as &$item) {
        $item['fitur'] = explode(',', $item['fitur']);
        $item['harga'] = (int)$item['harga'];
        $item['rating'] = (float)$item['rating'];
        $item['id'] = (int)$item['id'];
    }
    
    echo json_encode($kendaraan);
} catch(PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>