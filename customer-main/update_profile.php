<?php
session_start();
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");

include_once '../config/constant.php';
include_once '../classes/Patient.php';

// Check if patient is logged in
if (!isset($_SESSION['patient_id'])) {
    http_response_code(401);
    echo json_encode(array("message" => "Access denied. Please login."));
    exit();
}


$db = $database->getConnection();
$patient = new Patient($db);

// Get posted data
$data = json_decode(file_get_contents("php://input"));

if (
    !empty($data->full_name) && !empty($data->gender) && !empty($data->dob) &&
    !empty($data->nic) && !empty($data->tel_no) && !empty($data->email)
) {

    // Set patient properties
    $patient->patient_id = $_SESSION['patient_id'];
    $patient->full_name = $data->full_name;
    $patient->gender = $data->gender;
    $patient->dob = $data->dob;
    $patient->nic = $data->nic;
    $patient->tel_no = $data->tel_no;
    $patient->email = $data->email;
    $patient->emergency_contact_name = $data->emergency_contact_name ?? '';
    $patient->emergency_contact_no = $data->emergency_contact_no ?? '';
    $patient->blood_group = $data->blood_group ?? '';
    $patient->allergies = $data->allergies ?? '';
    $patient->username = $data->username ?? '';
    $patient->profile_image = $data->profile_image ?? '';

    // Validate data
    $validation_errors = $patient->validate();
    if (!empty($validation_errors)) {
        http_response_code(400);
        echo json_encode(array("message" => "Validation failed", "errors" => $validation_errors));
        exit();
    }

    // Check if email already exists for other patients
    $check_patient = new Patient($db);
    if ($check_patient->getPatientByEmail($patient->email) && $check_patient->patient_id != $patient->patient_id) {
        http_response_code(409);
        echo json_encode(array("message" => "Email already exists for another patient."));
        exit();
    }

    // Update patient
    if ($patient->update()) {
        http_response_code(200);
        echo json_encode(array("message" => "Profile updated successfully."));
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "Unable to update profile."));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Incomplete data. Unable to update profile."));
}
