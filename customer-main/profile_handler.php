<?php
include(__DIR__ . '/../config/constant.php');
;

class ProfileHandler {
    private $conn;
    
    public function __construct($connection) {
        $this->conn = $connection;
    }
    
    // Get user profile by ID
    public function getProfile($patient_id) {
        $sql = "SELECT * FROM tbl_patient WHERE patient_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $patient_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return null;
    }
    
    // Update user profile
    public function updateProfile($patient_id, $data) {
        $sql = "UPDATE tbl_patient SET 
                full_name = ?, 
                gender = ?, 
                dob = ?, 
                nic = ?, 
                tel_no = ?, 
                email = ?, 
                emergency_contact_name = ?, 
                emergency_contact_no = ?, 
                blood_group = ?, 
                allergies = ? 
                WHERE patient_id = ?";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssssssssssi", 
            $data['full_name'],
            $data['gender'],
            $data['dob'],
            $data['nic'],
            $data['tel_no'],
            $data['email'],
            $data['emergency_contact_name'],
            $data['emergency_contact_no'],
            $data['blood_group'],
            $data['allergies'],
            $patient_id
        );
        
        return $stmt->execute();
    }
    
    // Create new profile
    public function createProfile($data) {
        $sql = "INSERT INTO tbl_patient (
                full_name, gender, dob, nic, tel_no, email, 
                emergency_contact_name, emergency_contact_no, 
                blood_group, allergies, username, password
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssssssssssss", 
            $data['full_name'],
            $data['gender'],
            $data['dob'],
            $data['nic'],
            $data['tel_no'],
            $data['email'],
            $data['emergency_contact_name'],
            $data['emergency_contact_no'],
            $data['blood_group'],
            $data['allergies'],
            $data['username'],
            $data['password']
        );
        
        return $stmt->execute();
    }
    
    // Validate user credentials
    public function validateUser($username, $password) {
        $sql = "SELECT patient_id, username, password FROM tbl_patient WHERE username = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                return $user['patient_id'];
            }
        }
        return false;
    }
}

// Handle AJAX requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $profileHandler = new ProfileHandler($conn);
    $response = array();
    
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'get_profile':
                if (isset($_SESSION['patient_id'])) {
                    $profile = $profileHandler->getProfile($_SESSION['patient_id']);
                    if ($profile) {
                        $response['success'] = true;
                        $response['data'] = $profile;
                    } else {
                        $response['success'] = false;
                        $response['message'] = 'Profile not found';
                    }
                } else {
                    $response['success'] = false;
                    $response['message'] = 'User not logged in';
                }
                break;
                
            case 'update_profile':
                if (isset($_SESSION['patient_id'])) {
                    $data = array(
                        'full_name' => $_POST['full_name'],
                        'gender' => $_POST['gender'],
                        'dob' => $_POST['dob'],
                        'nic' => $_POST['nic'],
                        'tel_no' => $_POST['tel_no'],
                        'email' => $_POST['email'],
                        'emergency_contact_name' => $_POST['emergency_contact_name'],
                        'emergency_contact_no' => $_POST['emergency_contact_no'],
                        'blood_group' => $_POST['blood_group'],
                        'allergies' => $_POST['allergies']
                    );
                    
                    if ($profileHandler->updateProfile($_SESSION['patient_id'], $data)) {
                        $response['success'] = true;
                        $response['message'] = 'Profile updated successfully';
                    } else {
                        $response['success'] = false;
                        $response['message'] = 'Failed to update profile';
                    }
                } else {
                    $response['success'] = false;
                    $response['message'] = 'User not logged in';
                }
                break;
                
            case 'login':
                $username = $_POST['username'];
                $password = $_POST['password'];
                
                $patient_id = $profileHandler->validateUser($username, $password);
                if ($patient_id) {
                    $_SESSION['patient_id'] = $patient_id;
                    $_SESSION['username'] = $username;
                    $response['success'] = true;
                    $response['message'] = 'Login successful';
                } else {
                    $response['success'] = false;
                    $response['message'] = 'Invalid credentials';
                }
                break;
                
            case 'logout':
                session_destroy();
                $response['success'] = true;
                $response['message'] = 'Logged out successfully';
                break;
        }
    }
    
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}
?>