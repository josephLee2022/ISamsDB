<?php
include_once('db_connection.php');
$conn = getDatabaseConnection();

// Check if the parent ID is provided
if (isset($_POST['id'])) {
    $id = $_POST['id'];

    // Prepare the SQL statement to delete the parent
    //$sql = "DELETE FROM parents WHERE id = ?";
    $sql = "UPDATE parents SET `status` = 0 WHERE id = ?";

    if ($stmt = $conn->prepare($sql)) {
        // Bind the parent ID to the statement
        $stmt->bind_param("i", $id);

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
    // If no parent ID was provided
    echo "error";
}
?>
