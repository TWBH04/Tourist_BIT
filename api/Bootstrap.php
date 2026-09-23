<?php
declare(strict_types=1);
header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');

#Helper json endpoint
function helperJsonEndpoint($data, int $status = 200): void
{
    http_response_code($status);
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}
#error handling
function handleError($message, int $status): void{
    helperJsonEndpoint(['error' => $message], $status);
}
#req method
if($_SERVER['REQUEST_METHOD'] !== 'GET'){
    header('Allow: GET');
    handleError('Method not allowed', 405);
}
#databse conection
function dbc(): PDO{
    static $pdo=null;
    if($pdo===null){
        $clocal=__dir__.'/config/config.php';
        if(!file_exists($clocal)){
            error_log('DayQuest: config/config.php missing');
            handleError('Config  not found', 500);
        }    
    $con=require $clocal;
    try{
        $pdo=new PDO(
            "mysql:host={$con['host']};dbname={$con['dbname']};charset=utf8mb4",
            $con['user'],
            $con['pass'],
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]
        );
    }catch(PDOException $e){
        error_log('DB connect: '.$e->getMessage());
        handleError('Database unavailable', 500);
    }
}return $pdo;
}
#int conversion 
function cast_place(array $r): array {
    foreach (['place_id', 'category_id', 'visit_duration'] as $k) {
        if (isset($r[$k])) $r[$k] = (int)$r[$k];
    }
    foreach (['latitude', 'longitude', 'distance_from_panadura'] as $k) {
        if (isset($r[$k])) $r[$k] = (float)$r[$k];
    }
    if (isset($r['is_outdoor'])) $r['is_outdoor'] = (bool)$r['is_outdoor'];
    return $r;
}