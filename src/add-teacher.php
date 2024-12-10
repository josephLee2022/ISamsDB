<?php
include('checkSession.php');
include_once('alertManager.php');
include_once('db_connection.php');
include_once('SweetAlert.php'); // Include SweetAlert class

$conn = getDatabaseConnection();

// Retrieve all year groups and genders
$gendersql = "SELECT * FROM genders";
$genderresult = $conn->query($gendersql);

// Initialize form fields and error messages
$fname = "";
$mname = "";
$lname = "";
$genderid = "";
$email = "";
$dob = "";
$phone = "";
$photo = "";
$password = "";
$status = "";
$errormessage = "";

// Form submission handling
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get POST data
    $fname = $_POST['fname'];
    $mname = $_POST['mname']; // Handle optional field
    $lname = $_POST['lname'];
    $genderid = $_POST['genderid'];
    $email = $_POST['email']; // Handle optional field
    $phone = $_POST['phone']; // Handle optional field
    $dob = $_POST['dob'];
    $password = $_POST['password']; // Handle optional field
    $status = $_POST['status']; // Handle optional field

    // Handle photo upload
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        // Check if the uploaded file is an image
        $fileTmpPath = $_FILES['photo']['tmp_name'];
        $fileType = mime_content_type($fileTmpPath);

        if (strpos($fileType, 'image') !== false) {
            // Get the uploaded file content as binary data
            $photo = file_get_contents($fileTmpPath);
        } else {
            $errormessage = "Please upload a valid image file.";
        }
    } else {
        $photo = null; // Set photo as null if no file was uploaded
    }

    if (empty($errormessage)) {
        // Prepare the SQL query with placeholders
        $sql = "insert into `teachers` ( `fname`, `mname`, `lname`, `genderid`, `dob`, `phone`, `email`, `password`, `status`,`photo`) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        // Prepare the statement
        $stmt = $conn->prepare($sql);

        // Bind the parameters
        $stmt->bind_param("sssissssis", $fname, $mname, $lname, $genderid, $dob, $phone, $email, $password,$status,$photo);

        // Execute the statement
        if ($stmt->execute()) {
            $_SESSION['alert'] = [
                'type' => 'success',
                'message' => 'Teacher details have been successfully inserted!'
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
            <h2 class="page-title">New Teacher</h2>
            <br>
            <form class="registration-form" method="POST" enctype="multipart/form-data">
                <input type="hidden" id="status" name="status" value="1">
                <div class="row">
                    <div class=col>
                        <label for="genderid">Gender:</label>
                        <select id="genderid" name="genderid" required>
                            <?php
                            // Fetch genders from the database and display them as options
                            while ($row = $genderresult->fetch_assoc()) {
                                $selected = ($row['id'] == $genderid) ? 'selected' : '';
                                echo "<option value='{$row['id']}' $selected>{$row['gender']}</option>";
                            }
                            ?>
                        </select><br><br>
                    </div>
                </div><div class="row">
                    <div class=col>
                        <label for="fname">First Name:</label>
                        <input type="text" id="fname" name="fname" maxlength="50" required><br><br>
                    </div>
                    <div class=col>
                        <label for="mname">Middle Name:</label>
                        <input type="text" id="mname" name="mname" maxlength="50"><br><br>
                    </div>
                    <div class=col>
                        <label for="lname">Last Name:</label>
                        <input type="text" id="lname" name="lname" maxlength="50" required><br><br>
                    </div>
                </div>

                <div class="row">
                    <div class=col>
                        <label for="address">Email:</label>
                        <input type="email" id="email" name="email" maxlength="100"><br><br>
                    </div>
                    <div class=col>
                        <label for="phone">Phone:</label>
                        <input type="phone" id="phone" name="phone" maxlength="10"><br><br>
                    </div>
                </div>

                <div class="row">
                    <div class=col>
                        <label for="dob">Date of Birth:</label>
                        <input type="date" id="dob" name="dob" required><br><br>
                    </div>
                    <div class=col>
                        <label for="photo">Photo:</label>
                        <input type="file" id="photo" name="photo" accept="image/*"><br><br>
                    </div>                    
                </div>

                <div class="row">
                    <div class=col-auto>
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" maxlength="50" required><br><br>
                    </div>
                    <div class=col>
                        <label for="confirm-password">Confirm Password</label>
                        <input type="password" id="confirm-password" name="confirm-password" maxlength="50" required><br><br>
                    </div>

                </div>
                <button type="submit" class="btn btn-success w-100">Submit</button>
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
