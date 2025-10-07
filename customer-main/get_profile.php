<?php
session_start();
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../config/constant.php';
include_once '../classes/Patient.php';


$db = $database->getConnection();
$patient = new Patient($db);

// Check if patient is logged in
if(!isset($_SESSION['patient_id'])) {
    http_response_code(401);
    echo json_encode(array("message" => "Access denied. Please login."));
    exit();
}

$patient_id = $_SESSION['patient_id'];

if($patient->getPatientById($patient_id)) {
    $profile_data = array(
        "patient_id" => $patient->patient_id,
        "full_name" => $patient->full_name,
        "gender" => $patient->gender,
        "dob" => $patient->dob,
        "nic" => $patient->nic,
        "tel_no" => $patient->tel_no,
        "email" => $patient->email,
        "emergency_contact_name" => $patient->emergency_contact_name,
        "emergency_contact_no" => $patient->emergency_contact_no,
        "blood_group" => $patient->blood_group,
        "allergies" => $patient->allergies,
        "username" => $patient->username,
        "registered_on" => $patient->registered_on,
        "profile_image" => $patient->profile_image
    );

    http_response_code(200);
    echo json_encode($profile_data);
} else {
    http_response_code(404);
    echo json_encode(array("message" => "Patient not found."));
}
?>