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
    echo "Voter not found.";
    exit();
}

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $hhlsl = $_POST['hhlsl'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $gender = $_POST['gender'];
    $birth_date = $_POST['birth_date'];
    $barangay = $_POST['barangay'];
    $precinct_number = $_POST['precinct_number'];
    $address = $_POST['address'];
    $contact_number = $_POST['contact_number'];

    $profile_pic = $voter['profile_pic']; // Keep old pic if not replaced

    if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] === UPLOAD_ERR_OK) {
        $ext = pathinfo($_FILES['profile_pic']['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . "." . $ext;
        $destination = 'uploads/' . $filename;
        move_uploaded_file($_FILES['profile_pic']['tmp_name'], $destination);
        $profile_pic = $filename;
    }

    try {
        $stmt = $pdo->prepare("UPDATE voters SET hhlsl = ?, first_name = ?, last_name = ?, gender = ?, birth_date = ?, barangay = ?, precinct_number = ?, address = ?, contact_number = ?, profile_pic = ? WHERE id = ?");
        $stmt->execute([$hhlsl, $first_name, $last_name, $gender, $birth_date, $barangay, $precinct_number, $address, $contact_number, $profile_pic, $id]);

        $success = "Voter updated successfully!";
        // Refresh voter data
        $stmt = $pdo->prepare("SELECT * FROM voters WHERE id = ?");
        $stmt->execute([$id]);
        $voter = $stmt->fetch();
    } catch (PDOException $e) {
        $error = "Error updating voter: " . $e->getMessage();
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
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid #6c757d;
        }
        .form-section {
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            padding: 25px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>📝 Edit Voter</h2>
        <a href="voters.php" class="btn btn-outline-secondary">← Back to Voter List</a>
    </div>

    <?php if ($success): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data">
        <div class="form-section">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">HHL/SL (Household/Sitio Leader)</label>
                    <input type="text" name="hhlsl" value="<?= htmlspecialchars($voter['hhlsl']) ?>" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Precinct #</label>
                    <input type="text" name="precinct_number" value="<?= htmlspecialchars($voter['precinct_number']) ?>" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">First Name</label>
                    <input type="text" name="first_name" value="<?= htmlspecialchars($voter['first_name']) ?>" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Last Name</label>
                    <input type="text" name="last_name" value="<?= htmlspecialchars($voter['last_name']) ?>" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Gender</label>
                    <select name="gender" class="form-select" required>
                        <option <?= $voter['gender'] == 'Male' ? 'selected' : '' ?>>Male</option>
                        <option <?= $voter['gender'] == 'Female' ? 'selected' : '' ?>>Female</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Birth Date</label>
                    <input type="date" name="birth_date" value="<?= htmlspecialchars($voter['birth_date']) ?>" class="form-control" required>
                </div>
                <div class="col-md-12">
                    <label class="form-label">Barangay</label>
                    <input type="text" name="barangay" value="<?= htmlspecialchars($voter['barangay']) ?>" class="form-control" required>
                </div>
                <div class="col-md-12">
                    <label class="form-label">Address</label>
                    <textarea name="address" class="form-control" rows="2" required><?= htmlspecialchars($voter['address']) ?></textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Contact Number</label>
                    <input type="text" name="contact_number" value="<?= htmlspecialchars($voter['contact_number']) ?>" class="form-control">
                </div>
            </div>
        </div>

        <div class="form-section">
            <div class="row g-3 align-items-center">
                <div class="col-md-4 text-center">
                    <label class="form-label d-block">Current Profile Picture</label>
                    <?php if ($voter['profile_pic'] && file_exists('uploads/' . $voter['profile_pic'])): ?>
                        <img src="uploads/<?= htmlspecialchars($voter['profile_pic']) ?>" class="profile-pic mb-2" alt="Profile Picture">
                    <?php else: ?>
                        <p class="text-muted">No profile picture uploaded.</p>
                    <?php endif; ?>
                </div>
                <div class="col-md-8">
                    <label class="form-label">Upload New Picture</label>
                    <input type="file" name="profile_pic" class="form-control" accept="image/*">
                    <div class="form-text">Upload to replace the current picture.</div>
                </div>
            </div>
        </div>

        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
            <button type="submit" class="btn btn-success px-4">💾 Update Voter</button>
            <a href="voters.php" class="btn btn-outline-secondary px-4">Cancel</a>
        </div>
    </form>
</div>
</body>
</html>
