<?php
require __DIR__ . '/auth.php';

$config = require __DIR__ . '/../config/config.php';
$pdo = new PDO(
    "mysql:host={$config['host']};dbname={$config['dbname']};charset=utf8mb4",
    $config['user'],
    $config['pass'],
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
);

$allPlaces = $pdo->query('SELECT place_id, name FROM place ORDER BY name')->fetchAll(PDO::FETCH_ASSOC);

$editId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$isEdit = $editId !== null && $editId !== false;

$templateName = '';
$audience = '';
$selectedPlaceIds = [];

if ($isEdit) {
    $stmt = $pdo->prepare('SELECT template_name, audience FROM template WHERE template_id = ?');
    $stmt->execute([$editId]);
    $existing = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($existing) {
        $templateName = $existing['template_name'];
        $audience = $existing['audience'];

        $stmt = $pdo->prepare('SELECT place_id FROM template_place WHERE template_id = ? ORDER BY order_index');
        $stmt->execute([$editId]);
        $selectedPlaceIds = array_column($stmt->fetchAll(PDO::FETCH_ASSOC), 'place_id');
    } else {
        $isEdit = false;
    }
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $templateName = trim($_POST['template_name'] ?? '');
    $audience = trim($_POST['audience'] ?? '');
    $submittedPlaceIds = array_filter($_POST['place_ids'] ?? [], fn($v) => $v !== '');
    $submittedPlaceIds = array_map('intval', $submittedPlaceIds);

    if ($templateName === '') $errors[] = 'Template name is required.';
    if ($audience === '') $errors[] = 'Audience is required.';

    // check for duplicate place selections
    if (count($submittedPlaceIds) !== count(array_unique($submittedPlaceIds))) {
        $errors[] = 'The same place was selected more than once. Each place can only appear once in a template.';
    }

    if (empty($errors)) {
        if ($isEdit) {
            $stmt = $pdo->prepare('UPDATE template SET template_name = ?, audience = ? WHERE template_id = ?');
            $stmt->execute([$templateName, $audience, $editId]);
            $templateId = $editId;

            $pdo->prepare('DELETE FROM template_place WHERE template_id = ?')->execute([$templateId]);
        } else {
            $stmt = $pdo->prepare('INSERT INTO template (template_name, audience) VALUES (?, ?)');
            $stmt->execute([$templateName, $audience]);
            $templateId = $pdo->lastInsertId();
        }

        $insertLink = $pdo->prepare('INSERT INTO template_place (template_id, place_id, order_index) VALUES (?, ?, ?)');
        foreach (array_values($submittedPlaceIds) as $index => $placeId) {
            $insertLink->execute([$templateId, $placeId, $index + 1]);
        }

        header('Location: templates.php');
        exit;
    } else {
        $selectedPlaceIds = $submittedPlaceIds;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>DayQuest Admin — <?= $isEdit ? 'Edit' : 'Add' ?> Template</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-dark bg-dark mb-4">
        <div class="container">
            <span class="navbar-brand">DayQuest Admin</span>
            <a href="templates.php" class="btn btn-outline-light btn-sm">&larr; Back to Templates</a>
        </div>
    </nav>

    <div class="container" style="max-width: 700px;">
        <h3><?= $isEdit ? 'Edit Template' : 'Add New Template' ?></h3>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach ($errors as $err): ?>
                        <li><?= htmlspecialchars($err) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Template name</label>
                <input type="text" name="template_name" class="form-control" value="<?= htmlspecialchars($templateName) ?>">
            </div>

            <div class="mb-3">
                <label class="form-label">Audience</label>
                <input type="text" name="audience" class="form-control" value="<?= htmlspecialchars($audience) ?>" placeholder="e.g. Solo Traveler, Family, Couple">
            </div>

            <hr>

            <label class="form-label">Places in this template, in order</label>
            <div id="place-rows">
                <?php foreach ($selectedPlaceIds as $placeId): ?>
                    <div class="input-group mb-2 place-row">
                        <select name="place_ids[]" class="form-select">
                            <option value="">-- Select a place --</option>
                            <?php foreach ($allPlaces as $p): ?>
                                <option value="<?= $p['place_id'] ?>" <?= (int)$placeId === (int)$p['place_id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($p['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <button type="button" class="btn btn-outline-danger remove-row-btn">&times;</button>
                    </div>
                <?php endforeach; ?>
            </div>

            <button type="button" class="btn btn-outline-secondary btn-sm mb-3" id="add-row-btn">+ Add Place</button>
            <br>

            <button type="submit" class="btn btn-success"><?= $isEdit ? 'Save Changes' : 'Create Template' ?></button>
        </form>
    </div>

    <template id="row-template">
        <div class="input-group mb-2 place-row">
            <select name="place_ids[]" class="form-select">
                <option value="">-- Select a place --</option>
                <?php foreach ($allPlaces as $p): ?>
                    <option value="<?= $p['place_id'] ?>"><?= htmlspecialchars($p['name']) ?></option>
                <?php endforeach; ?>
            </select>
            <button type="button" class="btn btn-outline-danger remove-row-btn">&times;</button>
        </div>
    </template>

    <script>
        document.getElementById('add-row-btn').addEventListener('click', () => {
            const templateContent = document.getElementById('row-template').content;
            const clone = templateContent.cloneNode(true);
            document.getElementById('place-rows').appendChild(clone);
        });

        document.getElementById('place-rows').addEventListener('click', (event) => {
            if (event.target.classList.contains('remove-row-btn')) {
                event.target.closest('.place-row').remove();
            }
        });
    </script>
</body>
</html>