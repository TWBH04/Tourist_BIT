<?php
require  __DIR__.'/Bootstrap.php';

$where  = [];
$paramits = [];

#filters
if(!empty($_GET['category'])){
    $ids=array_filter(array_map('intval', explode(',', (string)$_GET['category'])),
        fn($n) => $n > 0
    );
    if($ids){
        $where[] = 'p.category_id IN ('.implode(',', array_fill(0,count($ids),'?')).')';
        $paramits=array_merge($paramits,array_values($ids));
    }
}
#search 
$q = isset($_GET['q']) ? trim((string)$_GET['q']) : '';
if ($q !== '') {
    $q = mb_substr($q, 0, 100);
    $like = '%' . str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $q) . '%';
    $where[]  = '(p.name LIKE ? OR p.description LIKE ?)';
    $paramits[] = $like;
    $paramits[] = $like;
}
#order for out
$order=(isset($_GET['order'])&& strtolower((string)$_GET['order'])==='desc')?'DESC':'ASC';

#datapull query
$sql = 'SELECT p.place_id, p.name, p.category_id, c.category_name, c.is_outdoor,
               p.description, p.visit_duration, 
               p.latitude, p.longitude,
               p.entrance_fee, p.distance_from_panadura, p.thumbnail
        FROM place p
        JOIN category c ON c.category_id = p.category_id'. ($where ? ' WHERE ' . implode(' AND ', $where) : '')
        . " ORDER BY p.distance_from_panadura $order, p.name ASC";

#pull exec
try {
    $st = dbc()->prepare($sql);
    $st->execute($paramits);
    $places = array_map('cast_place', $st->fetchAll());
    helperJsonEndpoint(['count' => count($places), 'places' => $places]);
} catch (PDOException $e) {
    error_log('DayQuest places.php: ' . $e->getMessage());
    handleError('Could not load places', 500);
}