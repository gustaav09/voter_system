<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}
require 'db.php';

$success = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $hhlsl = $_POST['hhlsl'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $gender = $_POST['gender'];
    $birth_date = $_POST['birth_date'];
    $barangay = $_POST['barangay'];
    $precinct_number = $_POST['precinct_number'];
    $address = $_POST['address'];
    $contact_number = $_POST['contact_number'];

    $profilePic = null;
    if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] === UPLOAD_ERR_OK) {
        $ext = pathinfo($_FILES['profile_pic']['name'], PATHINFO_EXTENSION);
        $profilePic = uniqid() . "." . $ext;
        $uploadDir = 'uploads/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
        move_uploaded_file($_FILES['profile_pic']['tmp_name'], $uploadDir . $profilePic);
    }

    $stmt = $pdo->prepare("INSERT INTO voters 
        (hhlsl, first_name, last_name, gender, birth_date, barangay, precinct_number, address, contact_number, profile_pic, created_at) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");

    $stmt->execute([$hhlsl, $first_name, $last_name, $gender, $birth_date, $barangay, $precinct_number, $address, $contact_number, $profilePic]);

    $success = "Voter added successfully!";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Voter</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .sidebar {
            min-height: 100vh;
            background-color: #343a40;
        }
        .sidebar a {
            color: #fff;
            display: block;
            padding: 12px 20px;
            text-decoration: none;
        }
        .sidebar a:hover, .sidebar a.active {
            background-color: #495057;
        }
        .form-card {
            border-radius: 10px;
        }
        .form-section-title {
            font-weight: bold;
            font-size: 1.2rem;
            margin-bottom: 15px;
            border-bottom: 2px solid #ccc;
            padding-bottom: 5px;
        }
    </style>
</head>
<body class="bg-light">

<!-- Navbar -->
<nav class="navbar navbar-dark" style="background-color: #FF8C00;">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="#">Lapasan Profiling DB</a>
        <a href="logout.php" class="btn btn-outline-light">Logout</a>
    </div>
</nav>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-2 p-0 sidebar">
            <a href="dashboard.php">🏠 Dashboard</a>
            <a href="add_voter.php" class="active">➕ Add Voter</a>
            <a href="voters.php">👥 View Voters</a>
            <a href="import.php">📁 Import CSV</a>
            <a href="export.php">⬇️ Auto Export CSV</a>
        </div>

        <!-- Main Content -->
        <div class="col-md-10 p-5">
            <h2 class="mb-4 fw-semibold">📝 Voter Profiling Form</h2>

            <?php if ($success): ?>
                <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>

            <div class="card shadow-sm p-4 form-card bg-white">
                <div class="form-section-title">Personal Information</div>
                <form method="POST" enctype="multipart/form-data">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">HHL/SL ID</label>
                            <input type="text" name="hhlsl" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Profile Picture</label>
                            <input type="file" name="profile_pic" class="form-control">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">First Name</label>
                            <input type="text" name="first_name" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Last Name</label>
                            <input type="text" name="last_name" class="form-control" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Gender</label>
                            <select name="gender" class="form-select" required>
                                <option value="">Select gender</option>
                                <option>Male</option>
                                <option>Female</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Birth Date</label>
                            <input type="date" name="birth_date" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Contact Number</label>
                            <input type="text" name="contact_number" class="form-control">
                        </div>
                    </div>

                    <div class="form-section-title">Address Details</div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Barangay</label>
                            <input type="text" name="barangay" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Precinct Number</label>
                            <input type="text" name="precinct_number" class="form-control" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Full Address</label>
                        <textarea name="address" class="form-control" rows="2" required></textarea>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg"> Submit Registration</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

</body>
</html>
