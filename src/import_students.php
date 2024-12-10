<?php
include_once('db_connection.php');
$conn = getDatabaseConnection();
include('checkSession.php');
include_once('AlertManager.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['csv_file']) && $_FILES['csv_file']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['csv_file']['tmp_name'];
        $fileName = $_FILES['csv_file']['name'];

        // Open and read the CSV file
        $file = fopen($fileTmpPath, 'r');
        $header = fgetcsv($file); // Read the first row as the header

        // Expected columns
        $expectedColumns = ['fname', 'mname', 'lname', 'genderid', 'photo', 'address', 'dob', 'parentid'];

        // Validate header
        if ($header !== $expectedColumns) {
            $errorMessage = 'CSV file does not match the required template. Please double-check the file and try again.';
            header('Location: all-students.php');
            exit;
        }

        // Read and insert data
        $rowCount = 0;
        while (($row = fgetcsv($file)) !== false) {
            $fname = $row[0];
            $mname = $row[1];
            $lname = $row[2];
            $genderid = $row[3];
            $photo = $row[4];
            $address = $row[5];
            $dob = $row[6];
            $parentid = $row[7];

            // Validate data types
            if (empty($fname) || empty($lname) || !strtotime($dob)) {
                $errorMessage = "Invalid data in row $rowCount. Please ensure all fields meet the required format.";
                header('Location: all-students.php');
                exit;
            }

            // Insert data into the replica table (Students)
            $stmt = $conn->prepare("INSERT INTO Students ( fname, mname, lname, genderid, photo, address, dob, parentid) 
                                    VALUES ( ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param('sssisssi', $fname, $mname, $lname, $genderid, $photo, $address, $dob, $parentid);
            $stmt->execute();
            $rowCount++;
        }

        fclose($file);
        $successMessage = "$rowCount students have been successfully imported.";
        header('Location: all-students.php');
    } else {
        $successMessage = 'File upload failed. Please try again.';
        header('Location: all-students.php');
    }
    exit;
}
?>
