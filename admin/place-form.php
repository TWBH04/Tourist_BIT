<?php
require __DIR__ . '/auth.php';

$config = require __DIR__ . '/../config/config.php';
$pdo = new PDO(
    "mysql:host={$config['host']};dbname={$config['dbname']};charset=utf8mb4",
    $config['user'],
    $config['pass'],
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
);

$categories = $pdo->query('SELECT category_id, category_name FROM category ORDER BY category_name')->fetchAll(PDO::FETCH_ASSOC);

$editId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$isEdit = $editId !== null && $editId !== false;

$place = [
    'name' => '', 'category_id' => '', 'description' => '', 'opening_hours' => '',
    'visit_duration' => 60, 'latitude' => '', 'longitude' => '', 'entrance_fee' => '',
    'distance_from_panadura' => '', 'travel_tips' => '', 'accessibility_info' => ''
];

if ($isEdit) {
    $stmt = $pdo->prepare('SELECT * FROM place WHERE place_id = ?');
    $stmt->execute([$editId]);
    $existing = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($existing) {
        $place = $existing;
    } else {
        $isEdit = false; // bad id, fall back to create mode
    }
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $place['name'] = trim($_POST['name'] ?? '');
    $place['category_id'] = $_POST['category_id'] ?? '';
    $place['description'] = trim($_POST['description'] ?? '');
    $place['opening_hours'] = trim($_POST['opening_hours'] ?? '');
    $place['visit_duration'] = $_POST['visit_duration'] ?? '';
    $place['latitude'] = $_POST['latitude'] ?? '';
    $place['longitude'] = $_POST['longitude'] ?? '';
    $place['entrance_fee'] = trim($_POST['entrance_fee'] ?? '');
    $place['distance_from_panadura'] = $_POST['distance_from_panadura'] ?? '';
    $place['travel_tips'] = trim($_POST['travel_tips'] ?? '');
    $place['accessibility_info'] = trim($_POST['accessibility_info'] ?? '');

    // FR-030: required fields
    if ($place['name'] === '') $errors[] = 'Name is required.';
    if ($place['category_id'] === '') $errors[] = 'Category is required.';
    if ($place['latitude'] === '' || !is_numeric($place['latitude'])) $errors[] = 'A valid latitude is required.';
    if ($place['longitude'] === '' || !is_numeric($place['longitude'])) $errors[] = 'A valid longitude is required.';
    
    // image validation
    $newThumbnail = null;

    if (!empty($_FILES['thumbnail']['name'])) {
        $file = $_FILES['thumbnail'];

        if ($file['error'] !== UPLOAD_ERR_OK) {
            $errors[] = 'There was a problem uploading the photo.';
        } else {
            $imageInfo = getimagesize($file['tmp_name']);
            $allowedTypes = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];

            if ($imageInfo === false || !isset($allowedTypes[$imageInfo['mime']])) {
                $errors[] = 'The uploaded file is not a valid image (JPG, PNG, or WebP only).';
            } elseif ($file['size'] > 2 * 1024 * 1024) {
                $errors[] = 'Image must be under 2MB.';
            } else {
                $ext = $allowedTypes[$imageInfo['mime']];
                $newThumbnail = 'place-' . uniqid() . '.' . $ext;
                $destination = __DIR__ . '/../assets/img/' . $newThumbnail;
                move_uploaded_file($file['tmp_name'], $destination);
            }
        }
    }

    if (empty($errors)) {
        $thumbnailToSave = $newThumbnail ?? $place['thumbnail'];
        if ($isEdit) {
            $stmt = $pdo->prepare(
                'UPDATE place SET name=?, category_id=?, description=?, opening_hours=?, visit_duration=?,
                 latitude=?, longitude=?, entrance_fee=?, distance_from_panadura=?, travel_tips=?, accessibility_info=?, thumbnail=?
                 WHERE place_id=?'
            );
            $stmt->execute([
                $place['name'], $place['category_id'], $place['description'], $place['opening_hours'], $place['visit_duration'],
                $place['latitude'], $place['longitude'], $place['entrance_fee'], $place['distance_from_panadura'],
                $place['travel_tips'], $place['accessibility_info'], $thumbnailToSave, $editId
            ]);
        } else {
            $stmt = $pdo->prepare(
                'INSERT INTO place (name, category_id, description, opening_hours, visit_duration,
                 latitude, longitude, entrance_fee, distance_from_panadura, travel_tips, accessibility_info, thumbnail)
                 VALUES (?,?,?,?,?,?,?,?,?,?,?,?)'
            );
            $stmt->execute([
                $place['name'], $place['category_id'], $place['description'], $place['opening_hours'], $place['visit_duration'],
                $place['latitude'], $place['longitude'], $place['entrance_fee'], $place['distance_from_panadura'],
                $place['travel_tips'], $place['accessibility_info'] ,$thumbnailToSave
            ]);
        }

        header('Location: places.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>DayQuest Admin — <?= $isEdit ? 'Edit' : 'Add' ?> Place</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-dark bg-dark mb-4">
        <div class="container">
            <span class="navbar-brand">DayQuest Admin</span>
            <a href="places.php" class="btn btn-outline-light btn-sm">&larr; Back to Places</a>
        </div>
    </nav>

    <div class="container" style="max-width: 700px;">
        <h3><?= $isEdit ? 'Edit Place' : 'Add New Place' ?></h3>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach ($errors as $err): ?>
                        <li><?= htmlspecialchars($err) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Name</label>
                <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($place['name']) ?>">
            </div>

            <div class="mb-3">
                <label class="form-label">Category</label>
                <select name="category_id" class="form-select">
                    <option value="">-- Select --</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['category_id'] ?>" <?= (string)$place['category_id'] === (string)$cat['category_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['category_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="3"><?= htmlspecialchars($place['description']) ?></textarea>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Photo</label>
                <?php if (!empty($place['thumbnail'])): ?>
                    <div class="mb-2">
                        <img src="../assets/img/<?= htmlspecialchars($place['thumbnail']) ?>" style="width:100px; height:100px; object-fit:cover; border-radius:8px;">
                    </div>
                <?php endif; ?>
                <input type="file" name="thumbnail" class="form-control" accept="image/jpeg,image/png,image/webp">
                <div class="form-text">JPG, PNG or WebP. Leave empty to keep the current photo.</div>
            </div>

            <div class="mb-3">
                <label class="form-label">Opening hours</label>
                <input type="text" name="opening_hours" class="form-control" value="<?= htmlspecialchars($place['opening_hours']) ?>">
            </div>

            <div class="row mb-3">
                <div class="col">
                    <label class="form-label">Visit duration (minutes)</label>
                    <input type="number" name="visit_duration" class="form-control" value="<?= htmlspecialchars($place['visit_duration']) ?>">
                </div>
                <div class="col">
                    <label class="form-label">Distance from Panadura (km)</label>
                    <input type="text" name="distance_from_panadura" class="form-control" value="<?= htmlspecialchars($place['distance_from_panadura']) ?>">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col">
                    <label class="form-label">Latitude</label>
                    <input type="text" name="latitude" class="form-control" value="<?= htmlspecialchars($place['latitude']) ?>">
                </div>
                <div class="col">
                    <label class="form-label">Longitude</label>
                    <input type="text" name="longitude" class="form-control" value="<?= htmlspecialchars($place['longitude']) ?>">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Entrance fee</label>
                <input type="text" name="entrance_fee" class="form-control" value="<?= htmlspecialchars($place['entrance_fee']) ?>">
            </div>

            <div class="mb-3">
                <label class="form-label">Travel tips</label>
                <textarea name="travel_tips" class="form-control" rows="2"><?= htmlspecialchars($place['travel_tips']) ?></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Accessibility info</label>
                <textarea name="accessibility_info" class="form-control" rows="2"><?= htmlspecialchars($place['accessibility_info']) ?></textarea>
            </div>

            <button type="submit" class="btn btn-success"><?= $isEdit ? 'Save Changes' : 'Create Place' ?></button>
        </form>
    </div>
</body>
</html>