<?php
session_start();
require 'db.php';

if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}

$search = $_GET['search'] ?? '';
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$records_per_page = 10;
$offset = ($page - 1) * $records_per_page;

$params = [];
$sql = "SELECT * FROM voters";
$countSql = "SELECT COUNT(*) FROM voters";

if ($search) {
    $searchTerm = '%' . $search . '%';
    $sql .= " WHERE hhlsl LIKE ? OR first_name LIKE ? OR last_name LIKE ?";
    $countSql .= " WHERE hhlsl LIKE ? OR first_name LIKE ? OR last_name LIKE ?";
    $sql .= " ORDER BY last_name ASC LIMIT $records_per_page OFFSET $offset";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$searchTerm, $searchTerm, $searchTerm]);
    $voters = $stmt->fetchAll();

    $countStmt = $pdo->prepare($countSql);
    $countStmt->execute([$searchTerm, $searchTerm, $searchTerm]);
    $total_voters = $countStmt->fetchColumn();
} else {
    $sql .= " ORDER BY last_name ASC LIMIT $records_per_page OFFSET $offset";
    $voters = $pdo->query($sql)->fetchAll();
    $total_voters = $pdo->query("SELECT COUNT(*) FROM voters")->fetchColumn();
}

$total_pages = ceil($total_voters / $records_per_page);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Voters</title>
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
        .profile-pic {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid #ddd;
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
            <a href="voters.php" class="active">👥 View Voters</a>
            <a href="import.php">📁 Import CSV</a>
            <a href="export.php">⬇️ Auto Export CSV</a>
        </div>

        <!-- Main Content -->
        <div class="col-md-10 p-4">
            <h2 class="mb-4 fw-semibold">👥 Voter's List</h2>

            <form method="get" class="mb-3">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Search by Name or HHL/SL" value="<?= htmlspecialchars($search) ?>">
                    <button class="btn btn-primary" type="submit">Search</button>
                    <a href="voters.php" class="btn btn-outline-secondary">Clear</a>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-striped align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Picture</th>
                            <th>Name</th>
                            <th></th>
                            <th>Gender</th>
                            <th>Birthdate</th>
                            <th>Barangay</th>
                            <th>Precinct</th>
                            <th>Contact</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($voters) > 0): ?>
                            <?php foreach ($voters as $v): ?>
                                <tr>
                                    <td>
                                        <?php if (!empty($v['profile_pic'])): ?>
                                            <img src="uploads/<?= htmlspecialchars($v['profile_pic']) ?>" class="profile-pic" alt="Profile">
                                        <?php else: ?>
                                            <span class="text-muted">N/A</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= htmlspecialchars($v['last_name'] . ', ' . $v['first_name']) ?></td>
                                    <td><?= htmlspecialchars($v['hhlsl']) ?></td>
                                    <td><?= htmlspecialchars($v['gender']) ?></td>
                                    <td><?= htmlspecialchars($v['birth_date']) ?></td>
                                    <td><?= htmlspecialchars($v['barangay']) ?></td>
                                    <td><?= htmlspecialchars($v['precinct_number']) ?></td>
                                    <td><?= htmlspecialchars($v['contact_number']) ?></td>
                                    <td>
                                        <a href="view_voter.php?id=<?= $v['id'] ?>" class="btn btn-sm btn-info">View</a>
                                        <a href="edit_voter.php?id=<?= $v['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                                        <a href="delete_voter.php?id=<?= $v['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this voter?')">Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="9" class="text-center text-muted">No voters found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <?php if ($total_pages > 1): ?>
                <nav>
                    <ul class="pagination justify-content-center">
                        <?php if ($page > 1): ?>
                            <li class="page-item"><a class="page-link" href="?search=<?= urlencode($search) ?>&page=<?= $page - 1 ?>">Previous</a></li>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                                <a class="page-link" href="?search=<?= urlencode($search) ?>&page=<?= $i ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>

                        <?php if ($page < $total_pages): ?>
                            <li class="page-item"><a class="page-link" href="?search=<?= urlencode($search) ?>&page=<?= $page + 1 ?>">Next</a></li>
                        <?php endif; ?>
                    </ul>
                </nav>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>
</html>
