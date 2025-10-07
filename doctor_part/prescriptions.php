<?php
$conn = new mysqli("localhost", "root", "", "medicore");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// DELETE
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM prescriptions WHERE prescription_id = $id");
    header("Location: prescriptions.php");
    exit();
}

// ADD or UPDATE
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['prescription_id']) ? intval($_POST['prescription_id']) : 0;
    $patient_id = $conn->real_escape_string($_POST['patient_id']);
    $doctor_id = $conn->real_escape_string($_POST['doctor_id']);
    $patient = $conn->real_escape_string($_POST['patient_name']);
    $doctor = $conn->real_escape_string($_POST['doctor_name']);
    $date = $conn->real_escape_string($_POST['date']);
    $medicine = $conn->real_escape_string($_POST['medicine']);
    $dosage = $conn->real_escape_string($_POST['dosage']);
    $duration = $conn->real_escape_string($_POST['duration']);
    $notes = $conn->real_escape_string($_POST['notes']);

    $imageFileName = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $imageTmp = $_FILES['image']['tmp_name'];
        $imageName = basename($_FILES['image']['name']);
        $imageExt = strtolower(pathinfo($imageName, PATHINFO_EXTENSION));

        if (in_array($imageExt, ['jpg', 'jpeg', 'png'])) {
            $imageFileName = time() . '_' . $imageName;
            move_uploaded_file($imageTmp, "uploads/prescriptions/$imageFileName");
        }
    }

    if ($id > 0) {
        // UPDATE
        if (!empty($imageFileName)) {
            $conn->query("UPDATE prescriptions SET patient_id='$patient_id',doctor_id='$doctor_id',patient_name='$patient', doctor_name='$doctor', date='$date', medicine='$medicine', dosage='$dosage', 
            duration='$duration',notes='$notes', image='$imageFileName' WHERE prescription_id=$id");
        } else {
            $conn->query("UPDATE prescriptions SET patient_id='$patient_id',doctor_id='$doctor_id', patient_name='$patient', doctor_name='$doctor', date='$date', medicine='$medicine', dosage='$dosage', duration='$duration', notes='$notes' WHERE prescription_id=$id");
        }
    } else {
        // ADD
        $conn->query("INSERT INTO prescriptions (patient_id,doctor_id,patient_name, doctor_name, date, medicine, dosage,duration, notes, image) VALUES ('$patient_id','$doctor_id','$patient', '$doctor', '$date', '$medicine', '$dosage','$duration', '$notes', '$imageFileName')");
    }

    header("Location: prescriptions.php");
    exit();
}


// EDIT (prefill form)
$edit = null;
if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $result = $conn->query("SELECT * FROM prescriptions WHERE prescription_id = $id");
    $edit = $result->fetch_assoc();
}

// FETCH ALL
$prescriptions = $conn->query("SELECT * FROM prescriptions ORDER BY prescription_id DESC");
?>

<!DOCTYPE html>
<html>

<head>
    <title>Manage Prescriptions</title>
    <link rel="icon" href="../images/doctor_portal.png" type="image/x-icon">
    <style>
        /* Global */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f4f7f8;
            color: #333;
            margin: 0;
            padding: 20px;
        }

        h2 {
            color: #2c3e50;
            margin-bottom: 20px;
            font-weight: 600;
        }

        /* Form Styling */
        form {
            background: #fff;
            padding: 25px 30px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            margin-bottom: 40px;
            transition: all 0.3s ease;
        }

        form:hover {
            box-shadow: 0 12px 28px rgba(0, 0, 0, 0.15);
        }

        label {
            display: block;
            font-weight: 500;
            margin: 12px 0 6px;
            color: #34495e;
        }

        input[type="text"],
        input[type="date"],
        textarea,
        input[type="file"] {
            width: 100%;
            padding: 10px 12px;
            border-radius: 6px;
            border: 1px solid #ccc;
            font-size: 14px;
            transition: all 0.2s ease;
        }

        input:focus,
        textarea:focus {
            border-color: #3498db;
            outline: none;
            box-shadow: 0 0 5px rgba(52, 152, 219, 0.3);
        }

        textarea {
            resize: vertical;
            min-height: 60px;
        }

        button {
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: white;
            padding: 12px 22px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 15px;
            transition: all 0.3s ease;
            margin-top: 15px;
        }

        button:hover {
            background: linear-gradient(135deg, #2980b9, #1c5980);
        }

        /* Top bar */
        .top-bar {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 20px;
        }

        .top-bar a button {
            background: linear-gradient(135deg, #2ecc71, #27ae60);
        }

        .top-bar a button:hover {
            background: linear-gradient(135deg, #27ae60, #1e8449);
        }

        /* Table Styling */
        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
        }

        th,
        td {
            padding: 12px 15px;
            text-align: left;
        }

        th {
            background: #3498db;
            color: #fff;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 13px;
        }

        tr:nth-child(even) {
            background: #f9f9f9;
        }

        tr:hover {
            background: #e8f0fe;
            transition: all 0.2s ease;
        }

        .actions a {
            margin-right: 8px;
            font-weight: 500;
            text-decoration: none;
            padding: 6px 10px;
            border-radius: 6px;
            color: #fff;
            background: #3498db;
            transition: all 0.2s ease;
            font-size: 13px;
        }

        .actions a.delete {
            background: #e74c3c;
        }

        .actions a:hover {
            opacity: 0.85;
        }

        /* Image in table */
        td img {
            border-radius: 6px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease;
        }

        td img:hover {
            transform: scale(1.05);
        }

        /* Responsive */
        @media (max-width: 1000px) {

            table,
            thead,
            tbody,
            th,
            td,
            tr {
                display: block;
            }

            th {
                background: #3498db;
                color: #fff;
                font-weight: 600;
                position: sticky;
                top: 0;
            }

            td {
                padding: 10px;
                position: relative;
                padding-left: 50%;
                border-bottom: 1px solid #ddd;
            }

            td::before {
                position: absolute;
                top: 10px;
                left: 15px;
                width: 45%;
                white-space: nowrap;
                font-weight: 600;
                content: attr(data-label);
                color: #555;
            }
        }
    </style>

</head>

<body>

    <h2><?= $edit ? 'Update Prescription' : 'Add Prescription' ?></h2>
    <div class="top-bar">
        <a href="newdoctor-dashboard.php">
            <button>← Back </button>
        </a>
    </div>

    <form method="POST" action="" enctype="multipart/form-data">

        <input type="hidden" name="prescription_id" value="<?= $edit['prescription_id'] ?? '' ?>">

        <label>Patient ID:</label>
        <input type="text" name="patient_id" required value="<?= $edit['patient_id'] ?? '' ?>">

        <label>Doctor ID:</label>
        <input type="text" name="doctor_id" required value="<?= $edit['doctor_id'] ?? '' ?>">

        <label>Patient Name:</label>
        <input type="text" name="patient_name" required value="<?= $edit['patient_name'] ?? '' ?>">

        <label>Doctor Name:</label>
        <input type="text" name="doctor_name" required value="<?= $edit['doctor_name'] ?? '' ?>">

        <label>Date:</label>
        <input type="date" name="date" required value="<?= $edit['date'] ?? '' ?>">

        <label>Medicine:</label>
        <textarea name="medicine" required><?= $edit['medicine'] ?? '' ?></textarea>

        <label>Dosage:</label>
        <input type="text" name="dosage" value="<?= $edit['dosage'] ?? '' ?>">

        <label>Duration:</label>
        <input type="text" name="duration" value="<?= $edit['duration'] ?? '' ?>">

        <label>Notes:</label>
        <textarea name="notes"><?= $edit['notes'] ?? '' ?></textarea>

        <label>Prescription Image:</label>
        <input type="file" name="image">
        <?php if (!empty($edit['image'])): ?>
            <p>Current Image: <img src="uploads/prescriptions/<?= htmlspecialchars($edit['image']) ?>" width="100"></p>
        <?php endif; ?>


        <button type="submit"><?= $edit ? 'Update' : 'Add' ?> Prescription</button>
    </form>

    <h2>All Prescriptions</h2>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Patient ID</th>
                <th>Doctor ID</th>
                <th>Patient</th>
                <th>Doctor</th>
                <th>Date</th>
                <th>Medicine</th>
                <th>Dosage</th>
                <th>Duration</th>
                <th>Notes</th>
                <th>Image</th>
                <th>Actions</th>
            </tr>

        </thead>
        <tbody>
            <?php while ($row = $prescriptions->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['prescription_id'] ?></td>
                    <td><?= htmlspecialchars($row['patient_id']) ?></td>
                    <td><?= htmlspecialchars($row['doctor_id']) ?></td>
                    <td><?= htmlspecialchars($row['patient_name']) ?></td>
                    <td><?= htmlspecialchars($row['doctor_name']) ?></td>
                    <td><?= $row['date'] ?></td>
                    <td><?= htmlspecialchars($row['medicine']) ?></td>
                    <td><?= htmlspecialchars($row['dosage']) ?></td>
                    <td><?= htmlspecialchars($row['duration']) ?></td>
                    <td><?= htmlspecialchars($row['notes']) ?></td>
                    <td>
                        <?php if (!empty($row['image'])): ?>
                            <a href="uploads/prescriptions/<?= htmlspecialchars($row['image']) ?>" target="_blank">
                                <img src="uploads/prescriptions/<?= htmlspecialchars($row['image']) ?>" width="80">
                            </a><br>
                            <a href="uploads/prescriptions/<?= htmlspecialchars($row['image']) ?>" download>Download</a>

                        <?php else: ?>
                            No image
                        <?php endif; ?>
                    </td>

                    <td class="actions">
                        <a href="?edit=<?= $row['prescription_id'] ?>">Edit</a>
                        <a href="?delete=<?= $row['prescription_id'] ?>" class="delete" onclick="return confirm('Delete this prescription?')">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

</body>

</html>