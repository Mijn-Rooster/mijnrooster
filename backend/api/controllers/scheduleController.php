<?php

require_once __DIR__ . '/../utils/curlHelper.php';

$user = 'vis';
$start = 1725832800;
$end = 1730304470;
$type = 'lesson,exam,oralExam,activity,talk,mixed,meeting,interlude';
$fields = 'id,appointmentInstance,start,end,startTimeSlotName,endTimeSlotName,locations,teachers,subjects';

$params = http_build_query([
  'valid' => true,
  'cancelled' => false,
  'user' => $user,
  'start' => $start,
  'end' => $end,
  'type' => $type,
  'fields' => $fields
]);

$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://partner-7206.zportal.nl/api/v3/appointments?' . $params,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
  CURLOPT_HTTPHEADER => array(
    'Authorization: Bearer ' . ZERMELO_API_TOKEN,
  ),
));

$response = curl_exec($curl);
curl_close($curl);

checkResponse($curl, $response);
echo $response;
