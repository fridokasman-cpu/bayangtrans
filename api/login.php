<?php
session_start();
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
require_once '../config/database.php';

$method = $_SERVER['REQUEST_METHOD'];

// POST - Login
if ($method === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $username = $input['username'] ?? '';
    $password = $input['password'] ?? '';
    
    try {
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
    } catch(PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
}

// GET - Cek status login
if ($method === 'GET') {
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

// DELETE - Logout
if ($method === 'DELETE') {
    session_destroy();
    echo json_encode(['success' => true, 'message' => 'Logout berhasil']);
}
?>