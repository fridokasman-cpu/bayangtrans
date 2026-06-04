<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
require_once '../config/database.php';

$method = $_SERVER['REQUEST_METHOD'];

// POST - Tambah booking baru
if ($method === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    
    try {
        $stmt = $pdo->prepare("
            INSERT INTO booking 
            (nama, instagram, facebook, nohp1, nowa2, norek, pekerjaan, 
             tgl_mulai, jam_mulai, lokasi_mulai, tgl_selesai, jam_selesai, 
             lokasi_selesai, kendaraan_id, jumlah, identitas1, identitas2, identitas3)
            VALUES 
            (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        
        $stmt->execute([
            $input['nama'],
            $input['instagram'],
            $input['facebook'],
            $input['nohp1'],
            $input['nowa2'],
            $input['norek'],
            $input['pekerjaan'],
            $input['tgl_mulai'],
            $input['jam_mulai'],
            $input['lokasi_mulai'],
            $input['tgl_selesai'],
            $input['jam_selesai'],
            $input['lokasi_selesai'],
            $input['kendaraan_id'],
            $input['jumlah'],
            $input['identitas1'],
            $input['identitas2'],
            $input['identitas3']
        ]);
        
        echo json_encode(['success' => true, 'message' => 'Booking berhasil disimpan']);
    } catch(PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
}

// GET - Ambil semua booking (untuk admin)
if ($method === 'GET') {
    session_start();
    if (!isset($_SESSION['admin_logged_in'])) {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }
    
    try {
        $stmt = $pdo->query("
            SELECT b.*, k.nama as kendaraan_nama 
            FROM booking b 
            LEFT JOIN kendaraan k ON b.kendaraan_id = k.id 
            ORDER BY b.created_at DESC
        ");
        $bookings = $stmt->fetchAll();
        echo json_encode($bookings);
    } catch(PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
}
?>