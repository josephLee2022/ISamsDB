<?php
include('checkSession.php');
include_once('AlertManager.php');
include_once('db_connection.php');
include_once('SweetAlert.php'); // Include SweetAlert class

$conn = getDatabaseConnection();

// Retrieve all year groups and genders
$sql = "SELECT * FROM year_groups";
$result = $conn->query($sql);

$gendersql = "SELECT * FROM genders";
$genderresult = $conn->query($gendersql);

$parentsql = "SELECT * FROM parents";
$parentresult = $conn->query($parentsql);

// Initialize form fields and error messages
$fname = "";
$mname = "";
$lname = "";
$genderid = "";
$address = "";
$dob = "";
$parentid = "";
$errormessage = "";

// Form submission handling
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get POST data
    $fname = trim($_POST['fname']);
    $mname = trim($_POST['mname']); // Handle optional field
    $lname = trim($_POST['lname']);
    $genderid = trim($_POST['genderid']);
    $address = trim($_POST['address']); // Handle optional field
    $dob = trim($_POST['dob']);
    $parentid = trim($_POST['parentid']); // Handle optional field

    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['photo']['tmp_name'];
        $fileType = mime_content_type($fileTmpPath);

        if (strpos($fileType, 'image') !== false) {
            $photo = file_get_contents($fileTmpPath);
        } else {
            $errormessage = "Please upload a valid image file.";
        }
    } else {
        $photo = null; // Set photo as null if no file was uploaded
    }

    if (empty($errormessage)) {
        // Prepare the SQL query with placeholders
        $sql = "INSERT INTO `test_Students` (fname, mname, lname, genderid, photo, address, dob, parentid,status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $status = 1;
        $stmt->bind_param("sssisssii", $fname, $mname, $lname, $genderid, $photo, $address, $dob, $parentid,$status);
        if ($stmt->execute()) {
            $_SESSION['alert'] = [
                'type' => 'success',
                'message' => 'Student details have been successfully inserted!'
            ];
        } else {
            $_SESSION['alert'] = [
                'type' => 'error',
                'message' => $errormessage
            ];
        }
        header("Location: " . $_SERVER['PHP_SELF']); // Redirect to avoid resubmission on page reload
        exit;
    }
}

include('_header.php');
?>

<main class="py-5">
    <div class="container" style="display: grid; grid-template-columns: 300px auto; column-gap: 10px; background-color: var(--darkgrey);">
        <div><?php include('_sidenav.php'); ?></div>
        <div class="p-4">
            <h2 class="page-title">New Student</h2>
            <br>
            <?php 
                AlertManager::displayError($errormessage);
            ?>  
            <form class="registration-form" method="POST" enctype="multipart/form-data">
                <div class="row">
                    <div class=col-3>
                        <label for="genderid">Gender:</label>
                        <select id="genderid" name="genderid" required>
                            <?php
                            // Fetch genders from the database and display them as options
                            while ($row = $genderresult->fetch_assoc()) {
                                $selected = ($row['id'] == $genderid) ? 'selected' : '';
                                echo "<option value='{$row['id']}' $selected>{$row['gender']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div><div class="row">
                    <div class=col>
                        <label for="fname">First Name:</label>
                        <input type="text" id="fname" name="fname" maxlength="50" required><br><br>
                    </div>
                    <div class=col>
                        <label for="mname">Middle Name:</label>
                        <input type="text" id="mname" name="mname" maxlength="50" required><br><br>
                    </div>
                    <div class=col>
                        <label for="lname">Last Name:</label>
                        <input type="text" id="lname" name="lname" maxlength="50" required><br><br>
                    </div>
                </div>
                <div class="row">
                    <div class=col>
                        <label for="address">Address:</label>
                        <input type="text" id="address" name="address" maxlength="50" ><br><br>
                    </div>
                </div>
                <div class="row">
                    <div class=col-3>
                        <label for="dob">Date of Birth:</label>
                        <input type="date" id="dob" name="dob" required><br><br>
                    </div>
                    <div class=col>
                        <label for="photo">Photo:</label>
                        <input type="file" id="photo" name="photo" accept="image/*">
                    </div>
                    <div class=col>
                        <label for="parentid">Parent ID:</label>
                        <select id="parentid" name="parentid" required>
                            <?php
                            // Fetch genders from the database and display them as options
                            while ($row = $parentresult->fetch_assoc()) {
                                $full_name = $row['fname'] . ' ' . substr($row['mname'], 0, 1) . ' ' . $row['lname'];
                                $selectedparent = ($row['id'] == $parentid) ? 'selected' : '';
                                echo "<option value='{$row['id']}' $selected>{$full_name}</option>";
                            }
                            ?>
                        </select><br><br>    
                    </div>
                </div>
                <div class="d-flex flex-row column-gap-2">
                    <button type="submit" class="btn btn-success w-50">Submit</button>
                    <button type="button" class="btn btn-secondary w-50" onclick="history.back();">Cancel</button>
                </div>
                
            </form>
        </div>
    </div>
</main>
<script>
    $(document).ready(function() {
        // Check if there is a session alert
        <?php if (isset($_SESSION['alert'])): ?>
            const alert = <?php echo json_encode($_SESSION['alert']); ?>;
            if (alert.type === 'success') {
                Swal.fire({
                    title: 'Success!',
                    text: alert.message,
                    icon: 'success',
                    confirmButtonText: 'Okay'
                });
            } else if (alert.type === 'error') {
                Swal.fire({
                    title: 'Error!',
                    text: alert.message,
                    icon: 'error',
                    confirmButtonText: 'Try Again'
                });
            }
            // Clear the session alert after displaying it
            <?php unset($_SESSION['alert']); ?>
        <?php endif; ?>
    });
</script>

<?php include('_footer.php'); ?>
