<?php
function getDatabaseConnection() {
    $servername = "db";
    $username = "php_docker";
    $password = "password";
    $dbname = "SchoolManagementSystem";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

function markAttendance($studentId, $date, $status, $remarks = null) {
    $conn = getDatabaseConnection();

    $sql = "
        INSERT INTO attendance (student_id, date, status, remarks)
        VALUES (?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE status = VALUES(status), remarks = VALUES(remarks), updated_at = CURRENT_TIMESTAMP
    ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('isss', $studentId, $date, $status, $remarks);

    if ($stmt->execute()) {
        return true; // Success
    } else {
        return false; // Failure
    }
}

?>
