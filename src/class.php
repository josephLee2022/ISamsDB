<?php

include_once('db_connection.php');
$conn = getDatabaseConnection();
include('checkSession.php');
include('AlertManager.php');

$id="";
$grade="";
$classcode="";
$teacherid="";
$teacher="";
$teacherEmpID="";


if($_SERVER['REQUEST_METHOD'] == 'GET'){
    
    if(!isset($_GET['id'])) {
        header('location:/all-classes.php');
        exit;
    }
    $id = $_GET['id'];
    $class_sql="select * from classes where id = $id";
    $class_result = $conn->query($class_sql);
    $class_row = $class_result->fetch_assoc();

    $grade=$class_row['grade'];
    $classcode=$class_row['classcode'];
    $teacherid=$class_row['teacherid'];

    $teach_sql="select * from teachers where id = $teacherid";
    $teach_result = $conn->query($teach_sql);
    $teach_row = $teach_result->fetch_assoc();

    $teacher=$teach_row['fname'] . ' '. substr($teach_row['mname'],0,1). ' '.$teach_row['lname'];
    $teacherEmpID=$teach_row['teacherEmpId'];

    if(!$class_row){
        header('location:/all-classes.php');
    }

}
else{

    $id = $_POST["id"];
    $grade = $_POST['grade'];
    $classcode = $_POST['classcode'];
    $teacherid = $_POST['teacherid'];

    do {
        // Validate required fields
        if (empty($id) || empty($grade) || empty($classcode) || empty($teacherid)) {
            $errormessage = "All fields are required.";
            break;
        }

        // Update the classes table
        $update_sql = "UPDATE `classes` 
                       SET `grade` = '$grade', `classcode` = '$classcode', `teacherid` = '$teacherid' 
                       WHERE `id` = $id";

        $update_result = $conn->query($update_sql);

        if (!$update_result) {
            $errormessage = "Invalid query: " . $conn->error;
            break;
        }

        // Redirect to the list of all classes after a successful update
        header("location:/all-classes.php");
        exit;

    } while (false);
}

include('_header.php');
?>

<main class="py-5">
    <div class="container" style="display: grid;grid-template-columns: 300px auto; column-gap: 10px;background-color: var(--darkgrey);">
        <div><?php include('_sidenav.php'); ?></div>           
        <div class="p-4">
            <?php 
                AlertManager::displayError($errormessage);
            ?>            
            <form method="post">
                <input type="hidden" name="id" value="<?php echo $id?>">
                <div class="tab-window row w-auto m-0">
                    <div class="info-heading col-3 text-end">Grade</div>
                    <div class="info-details col">
                        <input type="text" id="grade" name="grade" value="<?php echo $grade?>"></div>
                </div>
                <div class="tab-window row w-auto m-0">
                    <div class="info-heading col-3 text-end">Class</div>
                    <div class="info-details col">
                        <input type="text" id="classode" name="classcode" value="<?php echo $classcode?>"></div>
                </div>
                <div class="tab-window row w-auto m-0">
                    <div class="info-heading col-3 text-end">Teacher</div>
                    <div class="info-details col d-flex">
                        <input type="hidden" id="teacherid-hidden" name="teacherid" value="<?php echo $teacherid ?>">
                        <select id="teacherid" name="teacherid" class="form-control">
                            <?php
                            // Fetch all teachers from the database
                            $teachers_sql = "SELECT id, fname, mname, lname FROM teachers";
                            $teachers_result = $conn->query($teachers_sql);

                            while ($row = $teachers_result->fetch_assoc()) {
                                $full_name = $row['fname'] . ' ' . substr($row['mname'], 0, 1) . ' ' . $row['lname'];
                                // Check if this is the selected teacher
                                $selected = ($row['id'] == $teacherid) ? 'selected' : '';
                                echo "<option value='{$row['id']}' $selected>{$full_name}</option>";
                            }
                            ?>
                        </select>
                        <a class="btn btn-primary w-25" id="teacher-details-btn" target="_blank" href="teacher.php?id=<?php echo $teacherid ?>">View Details</a>
                    </div>
                </div>
                <br>
                <button type="submit" class="btn btn-success w-100">SUBMIT</button>
            </form>
        </div>    
    </div>
</main>

<script>
    // Get references to the dropdown and the button
    const teacherDropdown = document.getElementById('teacherid');
    const teacherDetailsButton = document.getElementById('teacher-details-btn');

    // Add an event listener to update the button's href when the dropdown selection changes
    teacherDropdown.addEventListener('change', function () {
        const selectedTeacherId = teacherDropdown.value; // Get the selected teacher's ID
        teacherDetailsButton.href = `teacher.php?id=${selectedTeacherId}`; // Update the button's href
    });
</script>
<?php include('sweets.php'); ?>
<?php include('_footer.php'); ?>
