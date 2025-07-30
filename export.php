<?php
require 'db.php';

// Set filename
$filename = "voters_export.csv";

// Set headers to force file download
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="' . $filename . '"');

// Open output stream
$output = fopen('php://output', 'w');

// Output CSV column headers
fputcsv($output, ['Voter ID', 'HHL/SL ID', 'First Name', 'Last Name', 'Gender', 'Birth Date', 'Barangay', 'Precinct Number', 'Address', 'Contact Number']);

// Query voter data
$stmt = $pdo->query("SELECT id, hhlsl, first_name, last_name, gender, birth_date, barangay, precinct_number, address, contact_number FROM voters");

// Write each row to the CSV
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    fputcsv($output, [
        $row['id'],
        $row['hhlsl'],
        $row['first_name'],
        $row['last_name'],
        $row['gender'],
        $row['birth_date'],
        $row['barangay'],
        $row['precinct_number'],
        $row['address'],
        $row['contact_number']
    ]);
}

// Close output stream
fclose($output);
exit;
?>
