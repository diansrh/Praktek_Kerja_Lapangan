<?php
require_once "config/database.php";
require_once "config/auth.php";

if (isset($_SESSION['user'])) { header("Location: dashboard.php"); exit; }

$error = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && hash('sha256', $password) === $user['password']) {
        $_SESSION['user'] = [
            'id' => $user['id'],
            'nama' => $user['nama'],
            'username' => $user['username'],
            'role' => $user['role']
        ];
        header("Location: dashboard.php");
        exit;
    }
    $error = "Username atau password salah.";
}
?>
<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Login - Sistem Kearsipan DPRD</title>
<link rel="stylesheet" href="assets/style.css">
</head>
<body class="login-page">
<div class="login-card">
    <div class="brand">SIKAD</div>
    <h1>Sistem Kearsipan Digital</h1>
    <p class="muted">Komisi IV DPRD Kota Medan</p>
    <?php if ($error): ?><div class="alert danger"><?=htmlspecialchars($error)?></div><?php endif; ?>
    <form method="post">
        <label>Username</label>
        <input name="username" required>
        <label>Password</label>
        <input type="password" name="password" required>
        <button class="btn primary full">Masuk</button>
    </form>
    <small>Demo: admin / admin123</small>
</div>
</body>
</html>
