<?php
include_once('db_connection.php');
$conn = getDatabaseConnection();

// Check if the student ID is provided
if (isset($_POST['id'])) {
    $teacherId = $_POST['id'];

    // Prepare the SQL statement to delete the student
    //$sql = "DELETE FROM test_Students WHERE id = ?";
    $sql = "UPDATE teachers SET `status` = 0 WHERE id = ?";

    if ($stmt = $conn->prepare($sql)) {
        // Bind the student ID to the statement
        $stmt->bind_param("i", $teacherId);

        // Execute the statement
        if ($stmt->execute()) {
            // Respond with success
            echo "success";
        } else {
            // Respond with failure
            echo "error";
        }

        // Close the prepared statement
        $stmt->close();
    } else {
        // If the statement preparation failed
        echo "error";
    }
} else {
    // If no student ID was provided
    echo "error";
}
?>
