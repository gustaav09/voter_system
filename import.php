<?php
session_start();
require 'db.php';

if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['csv'])) {
    $file = $_FILES['csv']['tmp_name'];

    if ($_FILES['csv']['type'] !== 'text/csv' && pathinfo($_FILES['csv']['name'], PATHINFO_EXTENSION) !== 'csv') {
        $error = "Only .csv files are allowed.";
    } else {
        $handle = fopen($file, 'r');
        $rowCount = 0;

        while (($row = fgetcsv($handle)) !== FALSE) {
            if (count($row) < 10) continue;

            list($hhlsl, $first_name, $middle_name, $last_name, $gender, $birth_date, $barangay, $precinct_number, $address, $contact_number) = $row;

            $check = $pdo->prepare("SELECT id FROM voters WHERE hhlsl = ?");
            $check->execute([$hhlsl]);
            if ($check->rowCount() > 0) continue;

            $stmt = $pdo->prepare("INSERT INTO voters 
                (hhlsl, first_name, middle_name, last_name, gender, birth_date, barangay, precinct_number, address, contact_number) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $hhlsl, $first_name, $middle_name, $last_name, $gender, $birth_date,
                $barangay, $precinct_number, $address, $contact_number
            ]);
            $rowCount++;
        }

        fclose($handle);
        $success = "$rowCount voters imported successfully.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Import Voters</title>
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
            <a href="add_voter.php">➕ Add Voter</a>
            <a href="voters.php">👥 View Voters</a>
            <a href="import.php" class="active">📁 Import CSV</a>
            <a href="export.php">⬇️ Auto Export CSV</a>
        </div>

        <!-- Main Content -->
        <div class="col-md-10 p-4">
            <h2 class="mb-4 fw-semibold">📁 Import Voter CSV</h2>

            <?php if ($success): ?>
                <div class="alert alert-success"><?= $success ?></div>
            <?php endif; ?>

            <?php if ($error): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>

            <form method="post" enctype="multipart/form-data" class="border p-4 bg-white shadow-sm rounded">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Upload CSV File</label>
                    <input type="file" name="csv" class="form-control" accept=".csv" required>
                    <div class="form-text">
                        CSV must have 10 columns: HHL/SL, First Name, Middle Name, Last Name, Gender, Birth Date, Barangay, Precinct Number, Address, Contact Number
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Import</button>
            </form>
        </div>
    </div>
</div>

</body>
</html>
