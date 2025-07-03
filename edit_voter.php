<?php
session_start();
require 'db.php';

if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}

$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: voters.php');
    exit();
}

// Fetch current voter data
$stmt = $pdo->prepare("SELECT * FROM voters WHERE id = ?");
$stmt->execute([$id]);
$voter = $stmt->fetch();

if (!$voter) {
    header('Location: voters.php');
    exit();
}

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Handle profile picture upload
    $profilePic = $voter['profile_pic']; // default to existing

    if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = "uploads/";
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Delete old pic if exists
        if ($profilePic && file_exists($profilePic)) {
            unlink($profilePic);
        }

        $ext = pathinfo($_FILES['profile_pic']['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . "." . $ext;
        $destination = $uploadDir . $filename;

        if (move_uploaded_file($_FILES['profile_pic']['tmp_name'], $destination)) {
            $profilePic = $destination;
        } else {
            $error = "Failed to upload new profile picture.";
        }
    }

    if (!$error) {
        $stmt = $pdo->prepare("UPDATE voters SET voter_id=?, first_name=?, last_name=?, gender=?, birth_date=?, barangay=?, precinct_number=?, address=?, contact_number=?, profile_pic=? WHERE id=?");
        $stmt->execute([
            $_POST['voter_id'], $_POST['first_name'], $_POST['last_name'], $_POST['gender'],
            $_POST['birth_date'], $_POST['barangay'], $_POST['precinct_number'],
            $_POST['address'], $_POST['contact_number'], $profilePic, $id
        ]);

        $success = "✅ Voter updated successfully!";
        // Refresh voter data after update
        $stmt = $pdo->prepare("SELECT * FROM voters WHERE id = ?");
        $stmt->execute([$id]);
        $voter = $stmt->fetch();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Voter</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .profile-pic {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 50%;
            border: 1px solid #ccc;
        }
    </style>
</head>
<body class="bg-light">
<div class="container py-5">
    <h2 class="mb-4">✏️ Edit Voter</h2>
    <a href="voters.php" class="btn btn-secondary mb-3">← Back to Voter List</a>

    <?php if ($success): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data" class="row g-3">
        <div class="col-md-6">
            <label class="form-label">Voter ID</label>
            <input type="text" name="voter_id" class="form-control" required value="<?= htmlspecialchars($voter['voter_id']) ?>">
        </div>
        <div class="col-md-6">
            <label class="form-label">First Name</label>
            <input type="text" name="first_name" class="form-control" required value="<?= htmlspecialchars($voter['first_name']) ?>">
        </div>
        <div class="col-md-6">
            <label class="form-label">Last Name</label>
            <input type="text" name="last_name" class="form-control" required value="<?= htmlspecialchars($voter['last_name']) ?>">
        </div>
        <div class="col-md-6">
            <label class="form-label">Gender</label>
            <select name="gender" class="form-select">
                <option <?= $voter['gender'] == 'Male' ? 'selected' : '' ?>>Male</option>
                <option <?= $voter['gender'] == 'Female' ? 'selected' : '' ?>>Female</option>
            </select>
        </div>
        <div class="col-md-6">
            <label class="form-label">Birth Date</label>
            <input type="date" name="birth_date" class="form-control" required value="<?= htmlspecialchars($voter['birth_date']) ?>">
        </div>
        <div class="col-md-6">
            <label class="form-label">Barangay</label>
            <input type="text" name="barangay" class="form-control" required value="<?= htmlspecialchars($voter['barangay']) ?>">
        </div>
        <div class="col-md-6">
            <label class="form-label">Precinct #</label>
            <input type="text" name="precinct_number" class="form-control" required value="<?= htmlspecialchars($voter['precinct_number']) ?>">
        </div>
        <div class="col-md-12">
            <label class="form-label">Address</label>
            <textarea name="address" class="form-control" rows="2" required><?= htmlspecialchars($voter['address']) ?></textarea>
        </div>
        <div class="col-md-6">
            <label class="form-label">Contact Number</label>
            <input type="text" name="contact_number" class="form-control" value="<?= htmlspecialchars($voter['contact_number']) ?>">
        </div>
        <div class="col-md-6">
            <label class="form-label">Current Profile Picture</label><br>
            <?php if ($voter['profile_pic'] && file_exists($voter['profile_pic'])): ?>
                <img src="<?= htmlspecialchars($voter['profile_pic']) ?>" class="profile-pic" alt="Profile Picture">
            <?php else: ?>
                <p class="text-muted">No profile picture uploaded.</p>
            <?php endif; ?>
        </div>
        <div class="col-md-6">
            <label class="form-label">Change Profile Picture</label>
            <input type="file" name="profile_pic" class="form-control" accept="image/*">
            <div class="form-text">Upload to replace the current picture.</div>
        </div>
        <div class="col-12">
            <button type="submit" class="btn btn-primary">Update Voter</button>
            <a href="voters.php" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
</body>
</html>
