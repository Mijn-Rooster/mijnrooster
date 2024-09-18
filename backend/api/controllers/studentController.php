<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$schoolInSchoolYear = 1001702;
$filterFields = 'student,firstName,prefix,lastName,mainGroupName,mainGroup,mentorGroup,departmentOfBranch';
$studentId = 138563;

$params = http_build_query([
    'schoolInSchoolYear' => $schoolInSchoolYear,
    'fields' => $filterFields,
    'student' => $studentId
]);

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://partner-7206.zportdddddal.nl/api/v3/studentsindepartments?' . $params,
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