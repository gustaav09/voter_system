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
            if (count($row) < 9) continue; // Skip incomplete rows

            list($voter_id, $first_name, $last_name, $gender, $birth_date, $barangay, $precinct_number, $address, $contact_number) = $row;

            // Check for duplicates by voter_id
            $check = $pdo->prepare("SELECT id FROM voters WHERE voter_id = ?");
            $check->execute([$voter_id]);
            if ($check->rowCount() > 0) continue;

            $stmt = $pdo->prepare("INSERT INTO voters 
                (voter_id, first_name, last_name, gender, birth_date, barangay, precinct_number, address, contact_number) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $voter_id, $first_name, $last_name, $gender, $birth_date,
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
</head>
<body class="bg-light">
<div class="container py-5">
    <h2 class="mb-4">📁 Import Voter CSV</h2>
    <a href="dashboard.php" class="btn btn-secondary mb-3">← Back to Dashboard</a>

    <?php if ($success): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data" class="border p-4 bg-white shadow-sm rounded">
        <div class="mb-3">
            <label class="form-label">Upload CSV File</label>
            <input type="file" name="csv" class="form-control" accept=".csv" required>
            <div class="form-text">CSV must have 9 columns: voter_id, first_name, last_name, gender, birth_date, barangay, precinct_number, address, contact_number</div>
        </div>
        <button type="submit" class="btn btn-primary">Import</button>
    </form>
</div>
</body>
</html>
