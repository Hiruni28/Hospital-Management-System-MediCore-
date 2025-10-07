<?php
class Patient
{
    private $conn;
    private $table_name = "tbl_patient";

    // Patient properties
    public $patient_id;
    public $full_name;
    public $gender;
    public $dob;
    public $nic;
    public $tel_no;
    public $address;
    public $email;
    public $emergency_contact_name;
    public $emergency_contact_no;
    public $blood_group;
    public $allergies;
    public $username;
    public $password;
    public $registered_on;
    public $profile_image;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Get patient by ID
    public function getPatientById($patient_id)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE patient_id = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        if (!$stmt) return false;
        $stmt->bind_param("i", $patient_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();

        if ($row) {
            foreach ($row as $key => $value) {
                $this->$key = $value;
            }
            return true;
        }
        return false;
    }

    // Get patient by email
    public function getPatientByEmail($email)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE email = ? LIMIT 1";
        $stmt = $this->conn->prepare($query);
        if (!$stmt) return false;
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();

        if ($row) {
            foreach ($row as $key => $value) {
                $this->$key = $value;
            }
            return true;
        }
        return false;
    }

    // Create new patient
    public function create()
    {
        $query = "INSERT INTO " . $this->table_name . " 
                 (full_name, gender, dob, nic, tel_no, address, email, emergency_contact_name, emergency_contact_no, blood_group, allergies, username, password)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($query);
        if (!$stmt) return false;

        // Sanitize inputs
        $this->sanitizeProperties();

        // Hash password (assuming $this->password is plain text)
        $hashedPassword = password_hash($this->password, PASSWORD_DEFAULT);

        $stmt->bind_param(
            "sssssssssssss",
            $this->full_name,
            $this->gender,
            $this->dob,
            $this->nic,
            $this->tel_no,
            $this->address,
            $this->email,
            $this->emergency_contact_name,
            $this->emergency_contact_no,
            $this->blood_group,
            $this->allergies,
            $this->username,
            $hashedPassword
        );

        $result = $stmt->execute();
        $stmt->close();

        return $result;
    }

    // Update patient profile
    public function update()
    {
        $query = "UPDATE " . $this->table_name . " SET 
                     full_name=?, gender=?, dob=?, nic=?, tel_no=?, address=?, email=?, emergency_contact_name=?, emergency_contact_no=?, blood_group=?, allergies=?, username=? ,profile_image=?
                  WHERE patient_id=?";

        $stmt = $this->conn->prepare($query);
        if (!$stmt) return false;

        $this->sanitizeProperties();

        $stmt->bind_param(
            "sssssssssssssi",
            $this->full_name,
            $this->gender,
            $this->dob,
            $this->nic,
            $this->tel_no,
            $this->address,
            $this->email,
            $this->emergency_contact_name,
            $this->emergency_contact_no,
            $this->blood_group,
            $this->allergies,
            $this->username,
            $this->profile_image,
            $this->patient_id
        );

        $result = $stmt->execute();
        $stmt->close();

        return $result;
    }

    // Sanitize all properties used in DB
    private function sanitizeProperties()
    {
        $this->full_name = htmlspecialchars(strip_tags($this->full_name));
        $this->gender = htmlspecialchars(strip_tags($this->gender));
        $this->dob = htmlspecialchars(strip_tags($this->dob));
        $this->nic = htmlspecialchars(strip_tags($this->nic));
        $this->tel_no = htmlspecialchars(strip_tags($this->tel_no));
        $this->address = htmlspecialchars(strip_tags($this->address));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->emergency_contact_name = htmlspecialchars(strip_tags($this->emergency_contact_name));
        $this->emergency_contact_no = htmlspecialchars(strip_tags($this->emergency_contact_no));
        $this->blood_group = htmlspecialchars(strip_tags($this->blood_group));
        $this->allergies = htmlspecialchars(strip_tags($this->allergies));
        $this->username = htmlspecialchars(strip_tags($this->username));
        $this->profile_image = htmlspecialchars(strip_tags($this->profile_image));
        $this->patient_id = (int)$this->patient_id;
    }

    // Validate patient data
    public function validate()
    {
        $errors = [];

        if (empty($this->full_name)) {
            $errors[] = "Full name is required";
        }

        if (empty($this->gender)) {
            $errors[] = "Gender is required";
        }

        if (empty($this->dob)) {
            $errors[] = "Date of birth is required";
        }

        if (empty($this->nic)) {
            $errors[] = "NIC is required";
        } elseif (!preg_match('/^[0-9]{9}[vVxX]$|^[0-9]{12}$/', $this->nic)) {
            $errors[] = "Invalid NIC format";
        }

        if (empty($this->tel_no)) {
            $errors[] = "Phone number is required";
        } elseif (!preg_match('/^[0-9]{10}$/', $this->tel_no)) {
            $errors[] = "Invalid phone number format";
        }

        if (empty($this->address)) {
            $errors[] = "Address is required";
        }

        if (empty($this->email)) {
            $errors[] = "Email is required";
        } elseif (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email format";
        }

        if (!empty($this->emergency_contact_no) && !preg_match('/^[0-9]{10}$/', $this->emergency_contact_no)) {
            $errors[] = "Invalid emergency contact number format";
        }

        return $errors;
    }

    // Check if email exists (for new registrations)
    public function emailExists()
    {
        $query = "SELECT patient_id FROM " . $this->table_name . " WHERE email = ?";
        $stmt = $this->conn->prepare($query);
        if (!$stmt) return false;
        $stmt->bind_param("s", $this->email);
        $stmt->execute();
        $stmt->store_result();
        $exists = $stmt->num_rows > 0;
        $stmt->close();
        return $exists;
    }

    // Check if NIC exists (for new registrations)
    public function nicExists()
    {
        $query = "SELECT patient_id FROM " . $this->table_name . " WHERE nic = ?";
        $stmt = $this->conn->prepare($query);
        if (!$stmt) return false;
        $stmt->bind_param("s", $this->nic);
        $stmt->execute();
        $stmt->store_result();
        $exists = $stmt->num_rows > 0;
        $stmt->close();
        return $exists;
    }

    // Update password
    public function updatePassword($newPassword)
    {
        $query = "UPDATE " . $this->table_name . " SET password = ? WHERE patient_id = ?";
        $stmt = $this->conn->prepare($query);
        if (!$stmt) return false;

        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        $stmt->bind_param("si", $hashedPassword, $this->patient_id);

        $result = $stmt->execute();
        $stmt->close();

        return $result;
    }
}
