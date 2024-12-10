<?php
include_once('db_connection.php');
$conn = getDatabaseConnection();
include('checkSession.php');

$id = "";
$subject = "";

if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    if (!isset($_GET['id'])) {
        header('location:/subjects.php');
        exit;
    }

    $id = $_GET['id'];
    
    // Use a prepared statement to retrieve the subject
    $sql = "SELECT * FROM subjects WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id); // Bind the parameter as an integer
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if (!$row) {
        header('location:/all-subjects.php');
        exit;
    }
    $subject = $row["subject"];
    $stmt->close();

} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $id = $_POST["id"];
    $subject = $_POST['subject'];

    do {
        if (empty($id) || empty($subject)) {
            $errormessage = "Subject cannot be empty are required.";
            break;
        }

        // Use a prepared statement to update the subject
        $sql = "UPDATE `subjects` SET `subject` = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $subject, $id);
        $result = $stmt->execute();
        header("location:/all-subjects.php");
        exit;
        if (!$result) {
            $errormessage = "Invalid query: " . $conn->error;
            break;
        }
        $subject = "";

    } while (false);    
}

include('_header.php');
?>

<main class="py-5">
    <div class="container" style="display: grid;grid-template-columns: 300px auto; column-gap: 10px;background-color: var(--darkgrey);">
        <div><?php include('_sidenav.php'); ?></div>           
        <div class="p-4">
            <?php 
                if (!empty($errormessage)) {
                    echo "
                    <div class='alert alert-warning alert-dismissible fade show' role='alert'>
                        <strong>$errormessage</strong>
                        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                    </div>";
                }
            ?>
            <form method="post">
                <div class="d-flex fex-row justify-content-center align-items-center" style="column-gap: 5px;">
                    <label for="subject">Subject</label>
                    <input type="hidden" id="id" name="id" value="<?php echo($id); ?>">
                    <input name="subject" id="subject" class="border" type="text" value="<?php echo($subject); ?>">
                    <span><button type="submit" class="btn btn-success">Submit</button>
                    <a href="all-subjects.php" class="btn btn-secondary">Cancel</a></span>
                </div>
            </form>
        </div>    
    </div>
</main>

<?php include('_footer.php'); ?>
