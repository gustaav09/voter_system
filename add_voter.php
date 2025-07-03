<?php
session_start();
require 'db.php';

$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Handle file upload
    $profilePic = null;
    if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] === UPLOAD_ERR_OK) {
        $ext = pathinfo($_FILES['profile_pic']['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . "." . $ext;
        $destination = "assets/uploads" . $filename;
        move_uploaded_file($_FILES['profile_pic']['tmp_name'], $destination);
        $profilePic = $destination;
    }

    // Insert into DB
    $stmt = $pdo->prepare("INSERT INTO voters 
        (voter_id, first_name, last_name, gender, birth_date, barangay, precinct_number, address, contact_number, profile_pic) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $_POST['voter_id'], $_POST['first_name'], $_POST['last_name'], $_POST['gender'],
        $_POST['birth_date'], $_POST['barangay'], $_POST['precinct_number'],
        $_POST['address'], $_POST['contact_number'], $profilePic
    ]);

    $success = "✅ Voter added successfully!";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Voter</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <h2 class="mb-4">➕ Add New Voter</h2>

    <?php if ($success): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data" class="row g-3">
        <div class="col-md-6">
            <label class="form-label">Voter ID</label>
            <input type="text" name="voter_id" class="form-control" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">First Name</label>
            <input type="text" name="first_name" class="form-control" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">Last Name</label>
            <input type="text" name="last_name" class="form-control" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">Gender</label>
            <select name="gender" class="form-select">
                <option>Male</option>
                <option>Female</option>
            </select>
        </div>
        <div class="col-md-6">
            <label class="form-label">Birth Date</label>
            <input type="date" name="birth_date" class="form-control" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">Barangay</label>
            <input type="text" name="barangay" class="form-control" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">Precinct #</label>
            <input type="text" name="precinct_number" class="form-control" required>
        </div>
        <div class="col-md-12">
            <label class="form-label">Address</label>
            <textarea name="address" class="form-control" rows="2" required></textarea>
        </div>
        <div class="col-md-6">
            <label class="form-label">Contact Number</label>
            <input type="text" name="contact_number" class="form-control">
        </div>
        <div class="col-md-6">
            <label class="form-label">Profile Picture</label>
            <input type="file" name="profile_pic" class="form-control" accept="image/*">
        </div>
        <div class="col-12">
            <button type="submit" class="btn btn-primary">Add Voter</button>
            <a href="dashboard.php" class="btn btn-secondary">Back</a>
        </div>
    </form>
</div>
</body>
</html>
