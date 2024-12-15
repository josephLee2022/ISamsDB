<?php
// Enable strict error reporting for mysqli
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

include_once('db_connection.php');
$conn = getDatabaseConnection();
include('checkSession.php');

$id = "";
$subject = "";
$successMessage = "";
$errorMessage = "";
$redirectUrl = "/all-subjects.php"; // Define the URL to redirect after the alert

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Validate `id` parameter
    if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
        
    }
    else{
        $id = $_GET['id'];

        // Prepare and execute SELECT query
        $sql = "SELECT * FROM subjects WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id); // "i" indicates the parameter is an integer
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if (!$row) {
            header('location: /all-subjects.php');
            exit;
        }
        $subject = $row["subject"];
        $stmt->close();

    }   
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate input
    $id = $_POST["id"] ?? "";
    $subject = $_POST['subject'] ?? "";

    if (empty($subject)) {
        $errorMessage = "Invalid input. Please provide a valid subject.";
    } else {
        try {
            if (empty($id) || !is_numeric($id)) {
                // Perform INSERT operation if `id` is null or empty
                $sql = "INSERT INTO `subjects` (`subject`) VALUES (?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("s", $subject);
                $stmt->execute();

                $successMessage = "subject added successfully!";
            } else {
                // Perform UPDATE operation if `id` is provided
                $sql = "UPDATE `subjects` SET `subject` = ? WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("si", $subject, $id);
                $stmt->execute();

                $successMessage = "subject updated successfully!";
            }

            $stmt->close();
        } catch (mysqli_sql_exception $e) {
            $errorMessage = "Error: " . $e->getMessage();
        }
    }
}

include('_header.php');
?>


<main class="py-5">
    <div class="container" style="display: grid; grid-template-columns: 300px auto; column-gap: 10px; background-color: var(--darkgrey);">
        <div><?php include('_sidenav.php'); ?></div>
        <div class="p-4" style="padding-top: 100px !important;">
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
                <div class="d-flex flex-column m-auto w-50" style="column-gap: 5px;">
                    <div class="d-flex justify-content-center align-items-center column-gap-2">
                        <label for="subject">subject</label>
                        <input type="hidden" id="id" name="id" value="<?php echo $id; ?>">
                        <input name="subject" id="subject" class="border" type="text" 
                            value="<?php echo ($subject); ?>" >
                    </div>
                    <br>
                    <span class="d-flex column-gap-2">
                        <button type="submit" class="btn btn-success w-50">Submit</button>
                        <a href="all-subjects.php" class="btn btn-secondary w-50">Cancel</a>
                    </span>
                </div>
            </form>
        </div>
    </div>
</main>

<?php include('sweets.php'); ?>
<?php include('_footer.php'); ?>
