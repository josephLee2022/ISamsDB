<?php
include_once('db_connection.php');
$conn = getDatabaseConnection();
include('checkSession.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $current_password = $_POST['current-password'];
    $new_password = $_POST['new-password'];
    $confirm_password = $_POST['confirm-password'];

    if (empty($id) || empty($current_password) || empty($new_password) || empty($confirm_password)) {
        http_response_code(400);
        echo "All fields are required.";
        exit;
    }

    if ($new_password !== $confirm_password) {
        http_response_code(400);
        echo "New password and confirm password do not match.";
        exit;
    }

    // Fetch current password from database
    $sql = "SELECT password FROM teachers WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        http_response_code(404);
        echo "Parent not found.";
        exit;
    }

    $row = $result->fetch_assoc();
    if ($current_password !== $row['password']) {
        http_response_code(400);
        echo "Current password is incorrect.";
        exit;
    }

    // Update password
    $update_sql = "UPDATE teachers SET password = ? WHERE id = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param('si', $new_password, $id);

    if ($stmt->execute()) {
        echo "Password updated successfully.";
    } else {
        http_response_code(500);
        echo "Failed to update password: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
?>
