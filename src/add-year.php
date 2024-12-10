<?php
include_once('db_connection.php');
$conn = getDatabaseConnection();
include('checkSession.php');

$id="";
$year="";
$errormessage="";


if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $id=$_POST["id"];
    $year=$_POST['year'];

    do{
        if(empty($year)){
            $errormessage = "A year is required";
            break;
        }

        try {
            // Prepare and execute UPDATE query
            $sql = "INSERT INTO `year_groups`(`year`) VALUES (?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $year);
            $stmt->execute();

            $successMessage = "Year group added successfully!";
            $stmt->close();

            // Redirect on success
            header("Location: $redirectUrl");
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
                <div class="d-flex fex-row justify-content-center align-items-center" style="column-gap: 5px;">
                    <label for="year">Year</label>
                    <input type="hidden" id="id" name="id" value="<?php echo $id;?>">
                    <input name="year" id="year" class="border" type="number" value="<?php echo ($year);?>">
                    <span><button type="submit" class="btn btn-success">Submit</button>
                    <a href="years.php" class="btn btn-secondary">Cancel</a></span>
                </div>
            </form>
        </div>    
    </div>
</main>



<?php include('_footer.php'); ?>
