<?php
session_start();

// already loin check
if (isset($_SESSION['admin_id'])) {
    header('Location: places.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    // login validation
    if ($username === '' || $password === '') {
        $error = 'Please enter both username and password.';
    } else {
        $config = require __DIR__. '/../config/config.php';
        $pdo = new PDO(
            "mysql:host={$config['host']};dbname={$config['dbname']};charset=utf8mb4",
            $config['user'],
            $config['pass'],
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );

        $stmt = $pdo->prepare('SELECT admin_id, username, password_hash,failed_attempts, locked_until FROM admin WHERE username = ?');
        $stmt->execute([$username]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($admin) {
            $isLocked = $admin['locked_until'] && strtotime($admin['locked_until']) > time();

            if ($isLocked) {
                $minutesLeft = ceil((strtotime($admin['locked_until']) - time()) / 60);
                $error = "Account locked due to too many failed attempts. Try again in {$minutesLeft} minute(s).";
            } elseif (password_verify($password, $admin['password_hash'])) {
                // success: reset attempts, log in
                $reset = $pdo->prepare('UPDATE admin SET failed_attempts = 0, locked_until = NULL WHERE admin_id = ?');
                $reset->execute([$admin['admin_id']]);

                $_SESSION['admin_id'] = $admin['admin_id'];
                $_SESSION['admin_username'] = $admin['username'];
                header('Location: places.php');
                exit;
            } else {
                // wrong password: increment attempts
                $newAttempts = $admin['failed_attempts'] + 1;

                if ($newAttempts >= 5) {
                    $lockUntil = date('Y-m-d H:i:s', time() + 15 * 60);
                    $update = $pdo->prepare('UPDATE admin SET failed_attempts = ?, locked_until = ? WHERE admin_id = ?');
                    $update->execute([$newAttempts, $lockUntil, $admin['admin_id']]);
                    $error = 'Account locked due to too many failed attempts. Try again in 15 minutes.';
                } else {
                    $update = $pdo->prepare('UPDATE admin SET failed_attempts = ? WHERE admin_id = ?');
                    $update->execute([$newAttempts, $admin['admin_id']]);
                    $error = 'Invalid username or password.';
                }
            }
                } else {
                    $error = 'Invalid username or password.';
                }
                    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>DayQuest Admin — Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark">
    <div class="container" style="max-width: 400px; margin-top: 100px;">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-3">DayQuest Admin</h4>

                <?php if ($error): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" required autofocus>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-dark w-100">Log In</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>