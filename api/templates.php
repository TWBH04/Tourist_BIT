<?php
require __DIR__.'/Bootstrap.php';

#template query
try{
    $rows=dbc()->query('SELECT template_id, template_name,
    p.place_id,p.place_name,p.visit-duration,p.latitiude,p.longtitde,
    tp.oreder_index
    FROM template t
    LEFT JOIN template_place tp ON tp.template_id=t.template_id
    LEFT JOIN place p ON p.place_id=tp.place_id
    ORDER BY t.template_id,tp.order_index'
    )->fetchAll();

    $templates=[];
    foreach($rows as $r){
        $tid =(int)$r['template_id'];
        if(!isset($templates[$tid])){
            $templates[$tid]=
            ['template-id'=>$tid,
            'template_name'=>$r['template_name'],
            'places'=>[]];
            
        }
        if($r['place_id']!==null){
            $templates[$tid]['places'][] = [
                'place_id'       => (int)$r['place_id'],
                'name'           => $r['name'],
                'visit_duration' => (int)$r['visit_duration'],
                'latitude'       => (float)$r['latitude'],
                'longitude'      => (float)$r['longitude'],
                'order_index'    => (int)$r['order_index'],
            ];
        }
    }
    helperJsonEndpoint(['templates'=>array_values($templates)]);
}catch(PDOException $e){
    error_log('DayQuest templates.php: '.$e->getMessage());
    handleError('Could not load templates',500);
}