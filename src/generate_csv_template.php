<?php
include_once('db_connection.php');
$conn = getDatabaseConnection();
include('checkSession.php');

// Define the columns for the CSV template
$columns = ['fname', 'mname', 'lname', 'genderid', 'photo', 'address', 'dob', 'parentid'];

// Create the CSV content
$filename = "students_template.csv"; // Name of the CSV file
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . $filename . '"');

// Open output stream
$output = fopen('php://output', 'w');

// Write the column headers to the CSV
fputcsv($output, $columns, ',', '"', '\\');

// Close the output stream
fclose($output);

exit;
?>
