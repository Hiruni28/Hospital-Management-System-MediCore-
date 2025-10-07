<?php
include('../config/constant.php'); // Make sure this defines $conn = new mysqli(...)

// If your constant.php used $con, map it to $conn for compatibility
if (!isset($conn) && isset($con)) {
    $conn = $con;
}

// Basic guard: fail early if DB connection isn't ready
if (!($conn instanceof mysqli)) {
    die('Database connection not initialized. Ensure constant.php sets $conn = new mysqli(...).');
}

$success = isset($_GET['saved']) && $_GET['saved'] === '1';
$error = '';
// Keep form values on error so the user doesn't lose input
$old = [
    'id'            => '',
    'Patient_name'  => '',
    'Service_name'  => '',
    'Doctor_name'   => '',
    'Doctor_fees'   => '',
    'Room_number'   => '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    // Collect & trim inputs
    foreach ($old as $k => $_) {
        $old[$k] = trim($_POST[$k] ?? '');
    }

    // Minimal validation
    if ($old['id'] === '') {
        $error = 'Patient ID is required.';
    }

    if ($error === '') {
        // Use a prepared statement (prevents SQL injection)
        $stmt = $conn->prepare(
            "INSERT INTO tbl_receipt
             (id, Patient_name, Service_name, Doctor_name, Doctor_fees, Room_number)
             VALUES (?, ?, ?, ?, ?, ?)"
        );
        if ($stmt) {
            $stmt->bind_param(
                'ssssss',
                $old['id'],
                $old['Patient_name'],
                $old['Service_name'],
                $old['Doctor_name'],
                $old['Doctor_fees'],
                $old['Room_number']
            );
            if ($stmt->execute()) {
                // PRG pattern: redirect to avoid resubmission on refresh
                header('Location: receipt.php?saved=1');
                exit;
            } else {
                $error = 'Insert failed: ' . $stmt->error;
            }
            $stmt->close();
        } else {
            $error = 'Prepare failed: ' . $conn->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Receipt Card</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>

    <style>
        body {
            background: #e9f7ef; /* Light green background */
            font-family: Arial, sans-serif;
        }
        .modal-content {
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            padding: 15px;
        }
        .modal-header {
            background: #28a745; /* Green header */
            color: #fff;
            border-radius: 10px 10px 0 0;
            text-align: center;
        }
        .modal-header img {
            display: block;
            margin: 0 auto;
        }
        .form-label { font-weight: 600; }
        .form-control, .form-select {
            border-radius: 8px;
            border: 1px solid #ccc;
        }
        .btn-success {
            background: #28a745;
            border: none;
            border-radius: 8px;
            padding: 10px 20px;
        }
        .btn-success:hover { background: #1e7e34; }
        .btn-secondary { border-radius: 8px; }
    </style>
</head>
<body>

<div class="container mt-4">
    <?php if ($success): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            Receipt saved successfully.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($error); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
</div>

<!-- Trigger Modal Button -->
<div class="container mt-3 text-center">
    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#receiptModal">
        Activate New Card
    </button>
</div>

<!-- Modal -->
<div class="modal fade" id="receiptModal" tabindex="-1" aria-labelledby="receiptModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <!-- Header -->
      <div class="modal-header flex-column">
        <img src="https://tse3.mm.bing.net/th/id/OIP.sAZZOie8vAny3b-WNtFaRAAAAA?r=0&w=390&h=129&rs=1&pid=ImgDetMain&o=7&rm=3" width="250" height="70" alt="Logo">
        <h4 class="mt-2" id="receiptModalLabel">Activate New Card</h4>
      </div>

      <!-- Form -->
      <form action="" method="POST" class="p-3">

        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Patient ID</label>
                <input type="text" class="form-control" name="id" maxlength="10" required
                       placeholder="Enter Patient ID"
                       value="<?php echo htmlspecialchars($old['id']); ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">Patient Name</label>
                <input type="text" class="form-control" name="Patient_name" placeholder="Enter Patient Name"
                       value="<?php echo htmlspecialchars($old['Patient_name']); ?>">
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Service Name</label>
                <select class="form-select" name="Service_name">
                    <?php
                    $res = $conn->query("SELECT title FROM tbl_programCategories WHERE active='Yes' ORDER BY title ASC");
                    if ($res && $res->num_rows > 0) {
                        while ($row = $res->fetch_assoc()) {
                            $title = $row['title'];
                            $selected = ($old['Service_name'] === $title) ? 'selected' : '';
                            echo "<option value=\"" . htmlspecialchars($title) . "\" $selected>" . htmlspecialchars($title) . "</option>";
                        }
                    } else {
                        echo "<option value=''>No categories available</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Doctor Name</label>
                <input type="text" class="form-control" name="Doctor_name" placeholder="Enter Doctor Name"
                       value="<?php echo htmlspecialchars($old['Doctor_name']); ?>">
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Doctor Fees</label>
                <input type="text" class="form-control" name="Doctor_fees" placeholder="Enter Doctor Fees"
                       value="<?php echo htmlspecialchars($old['Doctor_fees']); ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">Room Number</label>
                <input type="text" class="form-control" name="Room_number" placeholder="Enter Room Number"
                       value="<?php echo htmlspecialchars($old['Room_number']); ?>">
            </div>
        </div>

        <!-- Buttons -->
        <div class="text-center">
            <button type="submit" name="submit" class="btn btn-success">Submit</button>
            <button type="button" class="btn btn-secondary ms-2" data-bs-dismiss="modal">Close</button>
        </div>
      </form>

    </div>
  </div>
</div>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<?php if ($error): // auto-open modal again if there was an error ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var modal = new bootstrap.Modal(document.getElementById('receiptModal'));
        modal.show();
    });
</script>
<?php endif; ?>

</body>
</html>





