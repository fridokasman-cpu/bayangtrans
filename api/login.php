<?php
session_start();
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once '../config/database.php';

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $input = json_decode(file_get_contents('php://input'), true);
        $username = $input['username'] ?? '';
        $password = $input['password'] ?? '';
        
        $stmt = $pdo->prepare("SELECT * FROM admin WHERE username = ?");
        $stmt->execute([$username]);
        $admin = $stmt->fetch();
        
        if ($admin && password_verify($password, $admin['password'])) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_username'] = $admin['username'];
            $_SESSION['admin_name'] = $admin['nama'];
            
            echo json_encode([
                'success' => true,
                'message' => 'Login berhasil',
                'admin' => [
                    'username' => $admin['username'],
                    'nama' => $admin['nama']
                ]
            ]);
        } else {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Username atau password salah']);
        }
    }
    
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        if (isset($_SESSION['admin_logged_in'])) {
            echo json_encode([
                'logged_in' => true,
                'admin' => [
                    'username' => $_SESSION['admin_username'],
                    'nama' => $_SESSION['admin_name']
                ]
            ]);
        } else {
            echo json_encode(['logged_in' => false]);
        }
    }
    
    if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
        session_destroy();
        echo json_encode(['success' => true, 'message' => 'Logout berhasil']);
    }
} catch(PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>