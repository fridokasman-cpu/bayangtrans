<?php
session_start();
require_once '../config/database.php';

// Cek login
if (!isset($_SESSION['admin_logged_in'])) {
    // Proses login
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        
        $stmt = $pdo->prepare("SELECT * FROM admin WHERE username = ?");
        $stmt->execute([$username]);
        $admin = $stmt->fetch();
        
        if ($admin && password_verify($password, $admin['password'])) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_name'] = $admin['nama'];
            header('Location: index.php');
            exit;
        } else {
            $error = "Username atau password salah!";
        }
    }
    
    // Tampilkan form login
    ?>
    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login Admin - BayangTrans</title>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <style>
            * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
            body { background: linear-gradient(135deg, #2563eb, #1e40af); min-height: 100vh; display: flex; align-items: center; justify-content: center; }
            .login-box { background: white; padding: 40px; border-radius: 20px; box-shadow: 0 20px 60px rgba(0,0,0,0.3); width: 100%; max-width: 400px; }
            .login-box h1 { text-align: center; color: #2563eb; margin-bottom: 10px; }
            .login-box p { text-align: center; color: #6b7280; margin-bottom: 30px; }
            .form-group { margin-bottom: 20px; }
            .form-group label { display: block; margin-bottom: 8px; font-weight: 600; color: #1f2937; }
            .form-group input { width: 100%; padding: 12px; border: 2px solid #e5e7eb; border-radius: 10px; font-size: 1rem; }
            .form-group input:focus { outline: none; border-color: #2563eb; }
            .btn-login { width: 100%; padding: 14px; background: #2563eb; color: white; border: none; border-radius: 50px; font-size: 1rem; font-weight: 600; cursor: pointer; }
            .btn-login:hover { background: #1e40af; }
            .error { background: #fee2e2; color: #dc2626; padding: 10px; border-radius: 10px; margin-bottom: 20px; text-align: center; }
        </style>
    </head>
    <body>
        <div class="login-box">
            <h1><i class="fas fa-lock"></i> Admin BayangTrans</h1>
            <p>Silakan login untuk mengelola website</p>
            <?php if (isset($error)): ?>
                <div class="error"><?= $error ?></div>
            <?php endif; ?>
            <form method="POST">
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" required>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" required>
                </div>
                <button type="submit" name="login" class="btn-login">Login</button>
            </form>
            <p style="margin-top: 20px; font-size: 0.85rem; text-align: center; color: #6b7280;">
                Default: admin / admin123
            </p>
        </div>
    </body>
    </html>
    <?php
    exit;
}

// Jika sudah login, tampilkan dashboard
$statKendaraan = $pdo->query("SELECT COUNT(*) FROM kendaraan")->fetchColumn();
$statBooking = $pdo->query("SELECT COUNT(*) FROM booking")->fetchColumn();
$statPending = $pdo->query("SELECT COUNT(*) FROM booking WHERE status = 'Pending'")->fetchColumn();
$statAktif = $pdo->query("SELECT COUNT(*) FROM booking WHERE status = 'Aktif'")->fetchColumn();

$recentBookings = $pdo->query("
    SELECT b.*, k.nama as kendaraan_nama 
    FROM booking b 
    JOIN kendaraan k ON b.kendaraan_id = k.id 
    ORDER BY b.created_at DESC 
    LIMIT 5
")->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - BayangTrans</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { background: #f3f4f6; }
        .sidebar { position: fixed; top: 0; left: 0; width: 250px; height: 100vh; background: #1f2937; color: white; padding: 20px; }
        .sidebar h2 { color: #f59e0b; margin-bottom: 30px; }
        .sidebar a { display: block; color: white; padding: 12px; margin-bottom: 5px; border-radius: 8px; text-decoration: none; transition: 0.3s; }
        .sidebar a:hover, .sidebar a.active { background: #2563eb; }
        .main { margin-left: 250px; padding: 30px; }
        .header { background: white; padding: 20px; border-radius: 12px; margin-bottom: 30px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
        .stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px; }
        .stat-card { background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
        .stat-card h3 { color: #6b7280; font-size: 0.9rem; margin-bottom: 10px; }
        .stat-card .number { font-size: 2.5rem; font-weight: 700; color: #2563eb; }
        .stat-card.motor .number { color: #10b981; }
        .stat-card.pending .number { color: #f59e0b; }
        .stat-card.aktif .number { color: #ef4444; }
        .content { background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
        .content h2 { margin-bottom: 20px; color: #1f2937; }
        table { width: 100%; border-collapse: collapse; }
        table th, table td { padding: 12px; text-align: left; border-bottom: 1px solid #e5e7eb; }
        table th { background: #f9fafb; font-weight: 600; color: #374151; }
        .badge { padding: 4px 12px; border-radius: 50px; font-size: 0.8rem; font-weight: 600; }
        .badge-pending { background: #fef3c7; color: #92400e; }
        .badge-aktif { background: #dbeafe; color: #1e40af; }
        .badge-selesai { background: #d1fae5; color: #065f46; }
        .logout { background: #ef4444; color: white; padding: 8px 16px; border-radius: 8px; text-decoration: none; }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2><i class="fas fa-motorcycle"></i> BayangTrans</h2>
        <a href="index.php" class="active"><i class="fas fa-home"></i> Dashboard</a>
        <a href="kendaraan.php"><i class="fas fa-car"></i> Kelola Kendaraan</a>
        <a href="booking.php"><i class="fas fa-clipboard-list"></i> Kelola Booking</a>
        <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>
    
    <div class="main">
        <div class="header">
            <h1>Dashboard Admin</h1>
            <p>Selamat datang, <strong><?= $_SESSION['admin_name'] ?></strong> 👋</p>
        </div>
        
        <div class="stats">
            <div class="stat-card">
                <h3>Total Kendaraan</h3>
                <div class="number"><?= $statKendaraan ?></div>
            </div>
            <div class="stat-card motor">
                <h3>Total Booking</h3>
                <div class="number"><?= $statBooking ?></div>
            </div>
            <div class="stat-card pending">
                <h3>Booking Pending</h3>
                <div class="number"><?= $statPending ?></div>
            </div>
            <div class="stat-card aktif">
                <h3>Booking Aktif</h3>
                <div class="number"><?= $statAktif ?></div>
            </div>
        </div>
        
        <div class="content">
            <h2>Booking Terbaru</h2>
            <table>
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Nama</th>
                        <th>Kendaraan</th>
                        <th>Periode</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($recentBookings)): ?>
                        <tr><td colspan="5" style="text-align:center;">Belum ada booking</td></tr>
                    <?php else: ?>
                        <?php foreach ($recentBookings as $b): ?>
                        <tr>
                            <td><?= date('d/m/Y', strtotime($b['created_at'])) ?></td>
                            <td><?= htmlspecialchars($b['nama']) ?></td>
                            <td><?= htmlspecialchars($b['kendaraan_nama']) ?></td>
                            <td><?= date('d/m/Y', strtotime($b['tgl_mulai'])) ?> - <?= date('d/m/Y', strtotime($b['tgl_selesai'])) ?></td>
                            <td><span class="badge badge-<?= strtolower($b['status']) ?>"><?= $b['status'] ?></span></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>