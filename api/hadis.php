<?php
$hadis = file_get_contents("https://ahadith-api.herokuapp.com/api/ahadith/all/en");
$hadis = json_decode($hadis,TRUE);
$hitung_hadis = rand(0,1896);//count($hadis['AllChapters']);
$hadiss =  $hadis['AllChapters'][$hitung_hadis]['En_Text'];
$arr = array(
        'en' => $hadiss,
        'in' => "tejerf"
);
echo json_encode($arr);