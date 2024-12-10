<?php
include_once('db_connection.php');
require('lib/fpdf.php'); // Include the FPDF library

$conn = getDatabaseConnection();

// Redirect if no ID is passed
if (!isset($_GET['id'])) {
    header('location:/mystudents.php');
    exit;
}

$id = $_GET['id'];

// Fetch student grades and student details
$sql = "
    SELECT 
        m.`id`, 
        m.`enrollmentid`, 
        m.`subjectid`, 
        m.`term1`, 
        m.`term2`, 
        m.`term3`, 
        m.`Exam`,
        e.studentid,
        s.fname, 
        s.lname, 
        sub.subject
    FROM `student_grades` m
    LEFT JOIN enrollments e ON e.id = m.enrollmentid
    LEFT JOIN test_Students s ON s.id = e.studentid
    LEFT JOIN subjects sub ON sub.id = m.subjectid
    WHERE m.enrollmentid = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();

$grades = [];
$fname = '';
$lname = '';

if ($result->num_rows > 0) {
    $grades = $result->fetch_all(MYSQLI_ASSOC);
    $fname = $grades[0]['fname'];
    $lname = $grades[0]['lname'];
}

// Create the PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 14);

// Title
$pdf->Cell(0, 10, "Student Grades Report", 0, 1, 'C');
$pdf->Ln(5);

// Student Details
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(40, 10, "Name: " . $fname . ' ' . $lname);
$pdf->Ln(10);

// Table Header
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(60, 10, 'Subject', 1);
$pdf->Cell(30, 10, 'Term 1', 1);
$pdf->Cell(30, 10, 'Term 2', 1);
$pdf->Cell(30, 10, 'Term 3', 1);
$pdf->Cell(20, 10, 'Exam', 1);
$pdf->Ln();

// Table Content
$pdf->SetFont('Arial', '', 12);
foreach ($grades as $grade) {
    $pdf->Cell(60, 10, $grade['subject'], 1);
    $pdf->Cell(30, 10, $grade['term1'] ?? 'N/A', 1);
    $pdf->Cell(30, 10, $grade['term2'] ?? 'N/A', 1);
    $pdf->Cell(30, 10, $grade['term3'] ?? 'N/A', 1);
    $pdf->Cell(20, 10, $grade['Exam'] ?? 'N/A', 1);
    $pdf->Ln();
}

// Output PDF
$pdf->Output('D', $fname . '_' . $lname . '_Grades.pdf');
exit;
?>
