<?php
require __DIR__.'/Bootstrap.php';
#
try {
    $rows = dbc()->query('SELECT category_id, category_name, is_outdoor FROM category ORDER BY category_name')->fetchAll();
    foreach ($rows as &$r) {
        $r['category_id'] = (int)$r['category_id'];
        $r['is_outdoor']  = (bool)$r['is_outdoor'];
    }
    helperJsonEndpoint(['categories' => $rows]);
} catch (PDOException $e) {
    error_log('DayQuest categories.php: ' . $e->getMessage());
    handleError('Could not load categories', 500);
}