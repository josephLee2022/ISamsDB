<?php
// Enable strict error reporting for mysqli
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

include_once('db_connection.php');
$conn = getDatabaseConnection();
include('checkSession.php');

$id = "";
$year = "";
$successMessage = "";
$errorMessage = "";
$redirectUrl = "/years.php"; // Define the URL to redirect after the alert

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Validate `id` parameter
    if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
        header('location: /years.php');
        exit;
    }

    $id = $_GET['id'];

    // Prepare and execute SELECT query
    $sql = "SELECT * FROM year_groups WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id); // "i" indicates the parameter is an integer
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if (!$row) {
        header('location: /years.php');
        exit;
    }

    $year = $row["year"];
    $stmt->close();
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate input
    $id = $_POST["id"] ?? "";
    $year = $_POST['year'] ?? "";

    if (empty($id) || empty($year) || !is_numeric($id) || !is_numeric($year)) {
        $errorMessage = "Invalid input. Please provide valid year and ID.";
    } else {
        try {
            // Prepare and execute UPDATE query
            $sql = "UPDATE `year_groups` SET `year` = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ii", $year, $id);
            $stmt->execute();

            $successMessage = "Year group updated successfully!";
            $stmt->close();

            // Redirect on success
            header("Location: $redirectUrl");
            exit;
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
                <div class="d-flex flex-row justify-content-center align-items-center" style="column-gap: 5px;">
                    <label for="year">Year</label>
                    <input type="hidden" id="id" name="id" value="<?php echo htmlspecialchars($id); ?>">
                    <input name="year" id="year" class="border" type="number" 
                           value="<?php echo htmlspecialchars($year); ?>" 
                           min="<?php echo date('Y'); ?>" max="<?php echo date('Y') + 5; ?>">
                    <span>
                        <button type="submit" class="btn btn-success">Submit</button>
                        <a href="years.php" class="btn btn-secondary">Cancel</a>
                    </span>
                </div>
            </form>
        </div>
    </div>
</main>

<?php include('sweets.php'); ?>
<?php include('_footer.php'); ?>
