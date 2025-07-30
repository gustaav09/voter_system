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
    <style>
        body {
            min-height: 100vh;
            background-color: #f1f3f5;
        }
        .sidebar {
            min-height: 100vh;
            background-color: #343a40;
        }
        .sidebar a {
            color: #fff;
            display: block;
            padding: 12px 20px;
            text-decoration: none;
            transition: background-color 0.3s;
        }
        .sidebar a:hover, .sidebar a.active {
            background-color: #495057;
        }
        .card {
            border: none;
            border-radius: 10px;
        }
    </style>
</head>
<body>

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
            <a href="dashboard.php" class="active">🏠 Dashboard</a>
            <a href="add_voter.php">➕ Add Voter</a>
            <a href="voters.php">👥 View Voters</a>
            <a href="import.php">📁 Import CSV</a>
            <a href="export.php">⬇️ Auto Export CSV</a>
        </div>

        <!-- Main Content -->
        <div class="col-md-10 p-5">
            <h2 class="mb-4 fw-semibold">Dashboard</h2>

            <!-- Dashboard Stats -->
            <div class="row g-4 mb-5">
                <div class="col-md-4">
                    <div class="card bg-light shadow-sm p-3">
                        <h5>Total Voters</h5>
                        <p class="fs-3 fw-bold text-primary">
                            <?php
                            require 'db.php';
                            $stmt = $pdo->query("SELECT COUNT(*) FROM voters");
                            echo $stmt->fetchColumn();
                            ?>
                        </p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-light shadow-sm p-3">
                        <h5>Last Import</h5>
                        <p class="fs-5 text-muted">
                            <?php
                            $stmt = $pdo->query("SELECT MAX(created_at) FROM voters");
                            echo $stmt->fetchColumn() ?? 'N/A';
                            ?>
                        </p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-light shadow-sm p-3">
                        <h5>Admin</h5>
                        <p class="fs-5 text-muted">You are logged in</p>
                    </div>
                </div>
            </div>

            <p>Use the left menu to manage the Lapasan voter records including adding, editing, importing, and exporting data.</p>
        </div>
    </div>
</div>

</body>
</html>
