<?php

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://partner-7206.zportal.nl/api/v3/appointments?valid=true&cancelled=false&user=vis&start=1725832800&end=1726264799&type=lesson%2Cexam%2CoralExam%2Cactivity%2Ctalk%2Cmixed%2Cmeeting%2Cinterlude&fields=id%2CappointmentInstance%2Cstart%2Cend%2CstartTimeSlotName%2CendTimeSlotName%2Clocations%2Cteachers%2Csubjects',
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
echo $response;


?>