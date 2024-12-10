<?php
include('checkSession.php');
include_once('alertManager.php');
include_once('db_connection.php');
$conn = getDatabaseConnection();


$id = '';
$photo = '';
$teacherEmpId = '';
$fname = '';
$mname = '';
$lname = '';
$genderId = '';
$gender = '';
$dob = '';
$phone = '';
$email = '';
$password = '';
$photo = '';
$status = '';
$classcode = '';
$current_password = '';
$new_password = '';
$confirm_password = '';


if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    // Redirect if no ID is passed
    if (!isset($_GET['id'])) {
        header('location:/all-parents.php');
        exit;
    }
    
    $id = $_GET['id'];
    
    // Define SQL query
    $sql = "SELECT t.`id`, t.`photo`, t.`teacherEmpId`, t.`fname`, t.`mname`, t.`lname`, t.`genderid`, g.gender, t.`dob`, t.`phone`,t.email, t.`password`, t.`status`, c.classcode FROM `teachers` t
    left join genders g on g.id = t.genderid
    left join classes c on c.teacherid = t.id
    where t.id = $id";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();    

    // Execute query
    $result = $conn->query($sql);

    // Check if data is retrieved
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Map database fields to PHP variables
        $photo = $row['photo'];
        $teacherEmpId = $row['teacherEmpId'];
        $fname = $row['fname'];
        $mname = $row['mname'];
        $lname = $row['lname'];
        $genderId = $row['genderid'];
        $gender = $row['gender'];
        $dob = $row['dob'];
        $phone = $row['phone'];
        $photo = $row['photo'];
        $email = $row['email'];
        $password = $row['password'];
        $status = $row['status']; 
        $classcode = $row['classcode']; 
    }    
}

else{
    $formType = $_POST['form_type'] ?? '';

    if ($formType === 'profile_update') {
        // Handle profile update logic
        $id = $_POST["id"];
        $fname = $_POST["fname"];
        $mname = $_POST["mname"];
        $lname = $_POST["lname"];
        $email = $_POST["email"];
        $phone = $_POST["phone"];
        $photo = null;
        $teacherEmpId = $_POST["EmpID"];
        $genderid = $_POST["genderid"];
        $dob = $_POST["dob"];
        $classcode = $_POST["classcode"];

        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            $photo = file_get_contents($_FILES['photo']['tmp_name']);
        } else {
            // If no new photo is uploaded, retain the old value
            $photo = $_POST["photo"] ?? null;
        }

        do {
            if (empty($id) || empty($fname) || empty($mname) || empty($lname) || empty($phone)) {
                $errormessage = "All fields are required: " . $conn->error;
                break;
            }
            $sql = "UPDATE `teachers` 
                    SET `fname` = '$fname', `mname` = '$mname', `lname` = '$lname', `phone` = '$phone' 
                    WHERE `id` = $id";
            $result = $conn->query($sql);

            if (!$result) {
                $errormessage = "Invalid query: " . $conn->error;
                break;
            }
            $successmessage = "Profile updated successfully.";
        } while (false);
        header("Location: " . $_SERVER['PHP_SELF'] . "?id=$id");
    } elseif ($formType === 'password_update') {
        // Handle password update logic
        $id = $_POST["id"];
        $current_password = $_POST["current_password"];
        $new_password = $_POST["new_password"];
        $confirm_password = $_POST["confirm_password"];

        do {
            if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
                $passwordErrorMessage = "All fields are required.";
                break;
            }
            if ($new_password !== $confirm_password) {
                $passwordErrorMessage = "New password and confirm password do not match.";
                break;
            }
            // Verify current password
            $sql = "SELECT `password` FROM `teachers` WHERE `id` = $id";
            $result = $conn->query($sql);

            if (!$result || $result->num_rows === 0) {
                $passwordErrorMessage = "Invalid user.";
                break;
            }
            $row = $result->fetch_assoc();
            if ($current_password !== $row["password"]) { // Replace with password hashing logic for production
                $passwordErrorMessage = "Current password is incorrect.";
                break;
            }
            // Update password
            $hashedPassword = $new_password; // Replace with password_hash for production
            $sql = "UPDATE `teachers` SET `password` = '$hashedPassword' WHERE `id` = $id";
            $result = $conn->query($sql);

            if (!$result) {
                $passwordErrorMessage = "Failed to update password: " . $conn->error;
                break;
            }
            $passwordSuccessMessage = "Password updated successfully.";
        } while (false);
    }   
}
include('_header.php');
?>

<main class="py-5">
    <div class="container" style="display: grid;grid-template-columns: 300px auto; column-gap: 10px;background-color: var(--darkgrey);">
        <div><?php include('_sidenav.php'); ?></div>           
        <div class="p-4">
            <div class="text-center">
                <img id="profilePreview" 
                src="<?php echo ($photo == NULL) ? 'img/person.png' : 'data:image/jpeg;base64,' . base64_encode($photo); ?>" 
                alt="Profile Picture" style="width: 200px; height:200px; object-fit:cover; object-position:center; border-radius:100px; margin-bottom: 10px;">
                <h1><?php echo $fname.' '.$lname?></h1>
            </div>
            <div>
                <div class="tab">
                    <button class="tablinks" onclick="openCity(event, 'info')" id="defaultOpen">Info</button>
                    <button class="tablinks" onclick="openCity(event, 'profile')" >Update Profile</button>
                    <button class="tablinks" onclick="openCity(event, 'password')">Change Password</button>
                </div>
                <br>
                <?php 
                    AlertManager::displayError($errormessage);
                ?>
                <!-- Tab content -->
                <div id="info" class="tabcontent" style="display: block;">
                    <div class="tab-window row w-auto m-0">
                        <div class="info-heading col-3 text-end">Employee #</div>
                        <div class="info-details col"><?php echo $teacherEmpId?></div>
                    </div>
                    <div class="tab-window row w-auto m-0">
                        <div class="info-heading col-3 text-end">Name</div>
                        <div class="info-details col"><?php echo $fname.' '. substr($mname,0,1).' '.$lname?></div>
                    </div>
                    <div class="tab-window row w-auto m-0">
                        <div class="info-heading col-3 text-end">Phone</div>
                        <div class="info-details col"><?php echo $phone?></div>
                    </div>
                    <div class="tab-window row w-auto m-0">
                        <div class="info-heading col-3 text-end">Email</div>
                        <div class="info-details col"><?php echo $email?></div>
                    </div>
                    <div class="tab-window row w-auto m-0">
                        <div class="info-heading col-3 text-end">D.O.B</div>
                        <div class="info-details col"><?php echo date("d/M/Y", strtotime($dob))?></div>
                    </div>
                    <div class="tab-window row w-auto m-0">
                        <div class="info-heading col-3 text-end">Class</div>
                        <div class="info-details col"><?php echo $classcode?></div>
                    </div>
                </div>
                
                <div id="profile" class="tabcontent">
                    <form method="post" enctype="multipart/form-data">
                        <input type="hidden" name="form_type" value="profile_update">
                        <input hidden id="id" name="id" value="<?php echo $id?>">
                        <input hidden id="email" name="email" value="<?php echo $email?>">
                        <input hidden id="EmpID" name="EmpID" value="<?php echo $teacherEmpId?>">
                        <input hidden id="dob" name="dob" value="<?php echo $dob?>">
                        <input type="hidden" name="form_type" value="profile_update">
                        <input hidden id="classcode" name="classcode" value="<?php echo $classcode?>">
                        <input hidden id="genderid" name="genderid" value="<?php echo $genderid?>">
                        <div class="tab-window row w-auto m-0">
                            <div class="info-heading col-3 text-end">First Name</div>
                            <div class="info-details col">
                                <input type="text" id="fname" name="fname" value="<?php echo $fname?>"></div>
                        </div>
                        <div class="tab-window row w-auto m-0">
                            <div class="info-heading col-3 text-end">Middle Name</div>
                            <div class="info-details col">
                                <input type="text" id="mname" name="mname" value="<?php echo $mname?>"></div>
                        </div>
                        <div class="tab-window row w-auto m-0">
                            <div class="info-heading col-3 text-end">Last Name</div>
                            <div class="info-details col">
                                <input type="text" id="lname" name="lname" value="<?php echo $lname?>"></div>
                        </div>
                        <div class="tab-window row w-auto m-0">
                            <div class="info-heading col-3 text-end">Phone</div>
                            <div class="info-details col">
                                <input type="tel" id="mobile" name="phone" value="<?php echo $phone?>"></div>
                        </div>
                        <div class="tab-window row w-auto m-0">
                            <div class="info-heading col-3 text-end">Profile Pic</div>
                            <div class="info-details col">
                            <input type="file" name="photo">
                        </div>
                        <br>
                        <button type="submit" class="btn btn-success w-100">SUBMIT</button>
                    </form>
                </div>
                
                <div id="password" class="tabcontent">
                    <form id="password-form">
                        <input hidden id="id" name="id" value="<?php echo $id ?>">
                        <div class="tab-window row w-auto m-0">
                            <div class="info-heading col-3 text-end">Current Password</div>
                            <div class="info-details col">
                                <input type="password" id="current-password" name="current-password" value="">
                            </div>
                        </div>
                        <div class="tab-window row w-auto m-0">
                            <div class="info-heading col-3 text-end">New Password</div>
                            <div class="info-details col">
                                <input type="password" id="new-password" name="new-password" value="">
                            </div>
                        </div>
                        <div class="tab-window row w-auto m-0">
                            <div class="info-heading col-3 text-end">Confirm Password</div>
                            <div class="info-details col">
                                <input type="password" id="confirm-password" name="confirm-password" value="">
                            </div>
                        </div>
                        <br>
                        <button type="submit" class="btn btn-success w-100">SUBMIT</button>
                    </form>
                    <div id="password-message" class="mt-2"></div>
                </div>
                <br>
                <div>
                    <a href="all-teachers.php" class="btn btn-warning">Back</a>
                </div>  
                
                <script>
                    // Function to switch between tabs
                    function openCity(evt, cityName) {
                        var i, tabcontent, tablinks;
            
                        // Hide all tab contents
                        tabcontent = document.getElementsByClassName("tabcontent");
                        for (i = 0; i < tabcontent.length; i++) {
                            tabcontent[i].style.display = "none";
                        }
            
                        // Remove the "active" class from all tab links
                        tablinks = document.getElementsByClassName("tablinks");
                        for (i = 0; i < tablinks.length; i++) {
                            tablinks[i].className = tablinks[i].className.replace(" active", "");
                        }
            
                        // Show the content of the clicked tab and add "active" class to the button
                        document.getElementById(cityName).style.display = "block";
                        evt.currentTarget.className += " active";
                    }            
                    // Open the first tab (London) by default
                    document.getElementById("defaultOpen").click();
                </script>
                <script>
                    $(document).ready(function () {
                        $('#password-form').on('submit', function (e) {
                            e.preventDefault(); // Prevent the default form submission

                            // Serialize form data
                            const formData = $(this).serialize();

                            // Send an AJAX POST request
                            $.ajax({
                                url: 'update_teacher_password.php', // Server-side script to handle the password update
                                type: 'POST',
                                data: formData,
                                success: function (response) {
                                    // Handle success response
                                    $('#password-message').html(`<div class="alert alert-success">${response}</div>`);
                                    $('#password-form')[0].reset(); // Reset the form fields
                                },
                                error: function (xhr, status, error) {
                                    // Handle error response
                                    const errorMessage = xhr.responseText ? xhr.responseText : "An error occurred";
                                    $('#password-message').html(`<div class="alert alert-danger">${errorMessage}</div>`);
                                }
                            });
                        });
                    });
                </script>
            </div>   
        </div>    
    </div>
</main>



<?php include('_footer.php'); ?>
