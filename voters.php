<?php
session_start();
require 'db.php';

if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}

$search = $_GET['search'] ?? '';

if ($search) {
    $searchTerm = '%' . $search . '%';
    $stmt = $pdo->prepare("SELECT * FROM voters WHERE voter_id LIKE ? OR first_name LIKE ? OR last_name LIKE ? ORDER BY last_name ASC");
    $stmt->execute([$searchTerm, $searchTerm, $searchTerm]);
    $voters = $stmt->fetchAll();
} else {
    $voters = $pdo->query("SELECT * FROM voters ORDER BY last_name ASC")->fetchAll();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Voter List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .profile-pic {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 50%;
        }
    </style>
</head>
<body class="bg-light">
<div class="container py-5">
    <h2 class="mb-4">👥 Voter List</h2>
    <a href="dashboard.php" class="btn btn-secondary mb-3">← Back to Dashboard</a>

    <form method="get" class="mb-4">
        <div class="input-group">
            <input
                type="text"
                name="search"
                class="form-control"
                placeholder="Search by Voter ID, First or Last Name"
                value="<?= htmlspecialchars($search) ?>"
            >
            <button class="btn btn-primary" type="submit">Search</button>
            <a href="voters.php" class="btn btn-outline-secondary">Clear</a>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Photo</th>
                    <th>Voter ID</th>
                    <th>Name</th>
                    <th>Gender</th>
                    <th>Birth Date</th>
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
                            <?php if ($v['profile_pic']): ?>
                                <img src="<?= htmlspecialchars($v['profile_pic']) ?>" class="profile-pic" alt="Profile Picture">
                            <?php else: ?>
                                <span class="text-muted">No photo</span>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($v['voter_id']) ?></td>
                        <td><?= htmlspecialchars($v['last_name'] . ', ' . $v['first_name']) ?></td>
                        <td><?= htmlspecialchars($v['gender']) ?></td>
                        <td><?= htmlspecialchars($v['birth_date']) ?></td>
                        <td><?= htmlspecialchars($v['barangay']) ?></td>
                        <td><?= htmlspecialchars($v['precinct_number']) ?></td>
                        <td><?= htmlspecialchars($v['contact_number']) ?></td>
                        <td>
                            <a href="edit_voter.php?id=<?= $v['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                            <a href="delete_voter.php?id=<?= $v['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this voter?')">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="9" class="text-center">No voters found.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
