<?php
require __DIR__ . '/auth.php';

$config = require __DIR__ . '/../config/config.php';
$pdo = new PDO(
    "mysql:host={$config['host']};dbname={$config['dbname']};charset=utf8mb4",
    $config['user'],
    $config['pass'],
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
);

$templates = $pdo->query(
    'SELECT t.template_id, t.template_name, t.audience, COUNT(tp.place_id) AS place_count
     FROM template t
     LEFT JOIN template_place tp ON tp.template_id = t.template_id
     GROUP BY t.template_id, t.template_name, t.audience
     ORDER BY t.template_name'
)->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>DayQuest Admin — Templates</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-dark bg-dark mb-4">
        <div class="container">
            <span class="navbar-brand">DayQuest Admin</span>
            <span class="text-white">
                <a href="places.php" class="text-white">Places</a> &middot; Templates
                &middot; Logged in as <?= htmlspecialchars($_SESSION['admin_username']) ?>
                &middot; <a href="logout.php" class="text-white">Log out</a>
            </span>
        </div>
    </nav>

    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>Templates</h3>
            <a href="template-form.php" class="btn btn-success">Add New Template</a>
        </div>

        <table class="table table-bordered bg-white">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Audience</th>
                    <th>Places</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($templates as $t): ?>
                    <tr>
                        <td><?= htmlspecialchars($t['template_name']) ?></td>
                        <td><?= htmlspecialchars($t['audience']) ?></td>
                        <td><?= $t['place_count'] ?></td>
                        <td>
                            <a href="template-form.php?id=<?= $t['template_id'] ?>" class="btn btn-sm btn-primary">Edit</a>
                            <a href="delete-template.php?id=<?= $t['template_id'] ?>"
                               class="btn btn-sm btn-danger"
                               onclick="return confirm('Delete this template? This cannot be undone.');">
                                Delete
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>