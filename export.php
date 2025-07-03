<?php
require 'db.php';
$filename = "voters_export.csv";
header('Content-Type: text/csv');
header('Content-Disposition: attachment;filename=' . $filename);
$output = fopen('php://output', 'w');
fputcsv($output, ['Voter ID', 'First Name', 'Last Name', 'Gender', 'Birth Date', 'Barangay', 'Precinct', 'Address', 'Contact']);
$stmt = $pdo->query("SELECT * FROM voters");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    fputcsv($output, $row);
}
fclose($output);
?>