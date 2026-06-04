<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
require_once '../config/database.php';

$method = $_SERVER['REQUEST_METHOD'];

// GET - Ambil semua kendaraan
if ($method === 'GET') {
    try {
        $stmt = $pdo->query("SELECT * FROM kendaraan ORDER BY tipe, nama");
        $kendaraan = $stmt->fetchAll();
        
        // Format fitur dari string ke array
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

// PUT - Update ketersediaan atau harga
if ($method === 'PUT') {
    session_start();
    if (!isset($_SESSION['admin_logged_in'])) {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }
    
    $input = json_decode(file_get_contents('php://input'), true);
    
    try {
        if (isset($input['tersedia'])) {
            // Update ketersediaan
            $stmt = $pdo->prepare("UPDATE kendaraan SET tersedia = ? WHERE id = ?");
            $stmt->execute([$input['tersedia'] ? 1 : 0, $input['id']]);
            echo json_encode(['success' => true, 'message' => 'Ketersediaan diperbarui']);
        } elseif (isset($input['harga'])) {
            // Update harga
            $stmt = $pdo->prepare("UPDATE kendaraan SET harga = ? WHERE id = ?");
            $stmt->execute([$input['harga'], $input['id']]);
            echo json_encode(['success' => true, 'message' => 'Harga diperbarui']);
        }
    } catch(PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
}
?>