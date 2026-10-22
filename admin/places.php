<?php
require __DIR__ . '/auth.php';

$config = require __DIR__ . '/../config/config.php';
$pdo = new PDO(
    "mysql:host={$config['host']};dbname={$config['dbname']};charset=utf8mb4",
    $config['user'],
    $config['pass'],
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
);

$places = $pdo->query(
    'SELECT p.place_id, p.name, c.category_name, p.distance_from_panadura
     FROM place p
     JOIN category c ON c.category_id = p.category_id
     ORDER BY p.name'
)->fetchAll(PDO::FETCH_ASSOC);

// search
$search = trim($_GET['search'] ?? '');

$sql = 'SELECT p.place_id, p.name, c.category_name, p.distance_from_panadura
        FROM place p
        JOIN category c ON c.category_id = p.category_id';
$params = [];

if ($search !== '') {
    $sql .= ' WHERE p.name LIKE ?';
    $params[] = '%' . $search . '%';
}

$sql .= ' ORDER BY p.name';

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$places = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>DayQuest Admin — Places</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-dark bg-dark mb-4">
        <div class="container">
            <span class="navbar-brand">DayQuest Admin</span>
            <span class="text-white">
                Places &middot; <a href="templates.php" class="text-white">Templates</a>
                Logged in as <?= htmlspecialchars($_SESSION['admin_username']) ?>
                &middot; <a href="logout.php" class="text-white">Log out</a>
            </span>
        </div>
    </nav>

    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>Places</h3>
            <a href="place-form.php" class="btn btn-success">Add New Place</a>
        </div>
        <form method="GET" class="mb-3">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Search by name..." value="<?= htmlspecialchars($search) ?>">
                <button type="submit" class="btn btn-outline-secondary">Search</button>
                <?php if ($search !== ''): ?>
                    <a href="places.php" class="btn btn-outline-danger">Clear</a>
                <?php endif; ?>
            </div>
        </form>
        <table class="table table-bordered bg-white">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Distance (km)</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($places as $place): ?>
                    <tr>
                        <td><?= htmlspecialchars($place['name']) ?></td>
                        <td><?= htmlspecialchars($place['category_name']) ?></td>
                        <td><?= htmlspecialchars($place['distance_from_panadura']) ?></td>
                        <td>
                            <a href="place-form.php?id=<?= $place['place_id'] ?>" class="btn btn-sm btn-primary">Edit</a>
                            <a href="delete-place.php?id=<?= $place['place_id'] ?>"
                               class="btn btn-sm btn-danger"
                               onclick="return confirm('Delete this place? This cannot be undone.');">
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