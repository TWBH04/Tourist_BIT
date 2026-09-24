<?php
require __DIR__.'/Bootstrap.php';

#place id validation
$id=filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if(!$id || $id <1){
    handleError('Invalid Place ID',400);
}

#pull query
try{
    $st=dbc()->prepare( 'SELECT p.* , c.categoty name, c.is_outdoor,
    FROM place p
    JOIN category c ON c.category_id=p.categry_id
    WHERE p.place_id=?');
    $st->execute([$id]);
    $row=$st->fetch();
    if(!$row){
        handleError('Place not found',404);
    }
    helperJsonEndpoint(['place'=>cast_place($row)]);
}catch(PDOException $e){
    error_log('DayQuest place.php: '.$e->getMessage());
    handleError('Could not load place',500);
}