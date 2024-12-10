<?php
include_once('db_connection.php');
$conn = getDatabaseConnection();
include('checkSession.php');

$subject="";
$errormessage="";


if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $subject= trim($_POST['subject']);

    do{
        if(empty($subject)){
            $errormessage = "A subject is required";
            break;
        }

        try {
            // Prepare and execute UPDATE query
            $sql = "INSERT INTO  `subjects`(`subject`) VALUES (?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $subject);
            $stmt->execute();

            $successMessage = "subject group added successfully!";
            $stmt->close();

            // Redirect on success
            header("location:/all-subjects.php");
            exit;
        } catch (mysqli_sql_exception $e) {
            $errorMessage = "Error: " . $e->getMessage();
        }
        
    }while(false);
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
                <div class="d-flex flex-row justify-content-center align-items-center" style="column-gap: 5px;">
                    <label for="subject">subject</label>
                    <input name="subject" id="subject" class="border" type="text" value="<?php echo ($subject);?>">
                    <span><button type="submit" class="btn btn-success">Submit</button>
                    <a href="all-subjects.php" class="btn btn-secondary">Cancel</a></span>
                </div>
            </form>
        </div>    
    </div>
</main>

<?php include('_footer.php'); ?>
