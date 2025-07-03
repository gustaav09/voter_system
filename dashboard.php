<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">
        <span class="navbar-brand">Lapasan Voter DB</span>
        <div class="d-flex">
            <a href="logout.php" class="btn btn-outline-light">Logout</a>
        </div>
    </div>
</nav>

<div class="container py-5">
    <h2 class="mb-4">Admin Dashboard</h2>

    <div class="row g-3">
        <div class="col-md-3">
            <a href="add_voter.php" class="btn btn-success w-100">➕ Add Voter</a>
        </div>
        <div class="col-md-3">
            <a href="voters.php" class="btn btn-info w-100">👥 View Voters</a>
        </div>
        <div class="col-md-3">
            <a href="import.php" class="btn btn-warning w-100">📁 Import CSV</a>
        </div>
        <div class="col-md-3">
            <a href="export.php" class="btn btn-secondary w-100">⬇️ Export CSV</a>
        </div>
    </div>
</div>

</body>
</html>
