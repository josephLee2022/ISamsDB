<?php
include_once('alertManager.php');
include_once('db_connection.php');
$conn = getDatabaseConnection();
include('checkSession.php');

$id = "";
$fname = "";
$mname = "";
$lname = "";
$genderid = "";
$gender = "";
$photo = "";
$address = "";
$email = "";        
$tel = ""; 
$current_password = ""; 
$new_password = "";
$message="";
$confirm_password="";
$sturesults;

if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    // Redirect if no ID is passed
    if (!isset($_GET['id'])) {
        header('location:/all-parents.php');
        exit;
    }
    
    $id = $_GET['id'];    
    
    // Define SQL query
    $sql = "SELECT
    p.`id`,
    p.`fname`,
    p.`mname`,
    p.`lname`,
    p.`genderid`,
    g.gender gender,
    p.`email`,
    p.`tel`,
    p.`address`,
    p.`password`,
    p.`photo`,
    p.`date_created`
    FROM parents p
    left join genders g on g.id = p.genderid
    where p.id = $id";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();


    // Execute query
    $result = $conn->query($sql);

    // Check if data is retrieved
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Map database fields to PHP variables
        $fname = $row["fname"];
        $mname = $row["mname"];
        $lname = $row["lname"];
        $genderid = $row["genderid"];
        $gender = $row["gender"];
        $photo = $row["photo"];
        $address = $row["address"];
        $email = $row["email"];        
        $tel = $row["tel"]; 
        $current_password = $row["password"]; 
        
        $stusql="
        Select * from test_Students where parentid = $id";
        $sturesults = $conn->query($stusql);
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
        $tel = $_POST["tel"];
        $email = $_POST["email"];
        $address = $_POST["address"];

        do {
            if (empty($id) || empty($fname) || empty($mname) || empty($lname) || empty($tel) || empty($email) || empty($address)) {
                $errormessage = "All fields are required: " . $conn->error;
                break;
            }

            $sql = "UPDATE `parents` 
                    SET `fname` = '$fname', `mname` = '$mname', `lname` = '$lname', `tel` = '$tel', `email` = '$email', `address` = '$address' 
                    WHERE `id` = $id";
            $result = $conn->query($sql);

            if (!$result) {
                $errormessage = "Invalid query: " . $conn->error;
                break;
            }
            $message = 'Profile updated successfully.';
            header("Location: " . $_SERVER['PHP_SELF'] . "?id=$id");
            exit;
        } while (false);
    } elseif ($formType === 'password_update') {
        // Handle password update logic
        $id = $_POST["id"];
        $current_password = $_POST["current_password"];
        $new_password = $_POST["new_password"];
        $confirm_password = $_POST["confirm_password"];

        do {
            if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
                $message = "All fields are required.";
                break;
            }

            if ($new_password !== $confirm_password) {
               
                break;
            }

            // Verify current password
            $sql = "SELECT `password` FROM `parents` WHERE `id` = $id";
            $result = $conn->query($sql);

            if (!$result || $result->num_rows === 0) {
              
                break;
            }
            $row = $result->fetch_assoc();
            if ($current_password !== $row["password"]) { // Replace with password hashing logic for production
                
                break;
            }
            // Update password
            $hashedPassword = $new_password; // Replace with password_hash for production
            $sql = "UPDATE `parents` SET `password` = '$hashedPassword' WHERE `id` = $id";
            $result = $conn->query($sql);
            if (!$result) {
               
                break;
            }
           
            header("Location: " . $_SERVER['PHP_SELF'] . "?id=$id");
            exit;
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
                <!-- Tab content -->
                <div id="info" class="tabcontent" style="display: block;">
                    <div class="tab-window row w-auto m-0">
                        <div class="info-heading col-3 text-end">Name</div>
                        <div class="info-details col"><?php echo $fname.' '. substr($mname,0,1).' '.$lname?></div>
                    </div>
                    <div class="tab-window row w-auto m-0">
                        <div class="info-heading col-3 text-end">Phone</div>
                        <div class="info-details col"><?php echo $tel?></div>
                    </div>
                    <div class="tab-window row w-auto m-0">
                        <div class="info-heading col-3 text-end">Address</div>
                        <div class="info-details col"><?php echo $address?></div>
                    </div>
                    <div class="tab-window row w-auto m-0">
                        <div class="info-heading col-3 text-end">Email</div>
                        <div class="info-details col"><?php echo $email?></div>
                    </div>
                    <div class="tab-window row w-auto m-0">
                        <div class="info-heading col-3 d-flex justify-content-end align-items-center">Child/Children</div>
                        <div class="info-details col">
                            <div class="d-flex flex-column row-gap-2">
                                <?php if ($sturesults->num_rows>0) :?>
                                    <?php while($child=$sturesults->fetch_assoc()) :?>
                                        <span><?php echo $child['fname'].' '.$child['lname'] ?> <a href="student.php?id=<?php echo $child['id']?>" class="btn btn-primary">View Child</a></span>
                                    <?php endwhile;?>
                                <?php else: ?>
                                <?php endif; ?>  
                            </div>                            
                        </div>
                    </div>
                </div>
                
                <div id="profile" class="tabcontent">
                    <form method="post">
                        <input type="hidden" name="form_type" value="profile_update">
                        <input hidden id="id" name="id" value="<?php echo $id?>">
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
                                <input type="tel" id="mobile" name="tel" value="<?php echo $tel?>"></div>
                        </div>
                        <div class="tab-window row w-auto m-0">
                            <div class="info-heading col-3 text-end">Email</div>
                            <div class="info-details col">
                                <input type="email" id="email" name="email" value="<?php echo $email?>"></div>
                        </div>
                        <div class="tab-window row w-auto m-0">
                            <div class="info-heading col-3 text-end">Address</div>
                            <div class="info-details col">
                                <input type="text" id="fname" name="address" value="<?php echo $address?>"></div>
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
                                url: 'update_password.php', // Server-side script to handle the password update
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
