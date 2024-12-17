<?php
ob_start();
session_start();
include_once('db_connection.php');
$conn = getDatabaseConnection();
include('_header.php'); // Include header

// Initialize variables for SweetAlert feedback
$sweetAlert = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Regular query without prepared statements
    $query = "SELECT * FROM user_credentials WHERE email = '$email' LIMIT 1";
    $result = $conn->query($query);

    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Check if the passwords match (assuming plain text password storage)
        if ($password === $user['password']) {
            $_SESSION['email'] = $user['email'];
            $_SESSION['user_type'] = $user['user_type'];

            // Redirect based on user type
            switch ($user['user_type']) {
                case 'Admin':
                    $sweetAlert = "Swal.fire('Welcome!', 'Redirecting to Admin Dashboard', 'success').then(() => { window.location.href = 'admin-dashboard.php'; });";
                    break;
                case 'Teacher':
                    $sweetAlert = "Swal.fire('Welcome!', 'Redirecting to Teacher Dashboard', 'success').then(() => { window.location.href = 'teacher-dashboard.php'; });";
                    break;
                case 'Parent':
                    $sweetAlert = "Swal.fire('Welcome!', 'Redirecting to Parent Dashboard', 'success').then(() => { window.location.href = 'parent-dashboard.php'; });";
                    break;
                default:
                    $sweetAlert = "Swal.fire('Error!', 'Invalid user type.', 'error');";
            }
        } else {
            $sweetAlert = "Swal.fire('Error!', 'Invalid email or password.', 'error');";
        }
    } else {
        $sweetAlert = "Swal.fire('Error!', 'User not found.', 'error');";
    }
}
ob_end_flush();
?>

<div style="background-color: hsla(0, 0%, 0%, 0.8);position: absolute;min-height: 100%;min-width: 100%;z-index: 0;">    
</div>

<div style="background: none; display: flex; height: 100%;">
    <form id="login-form" method="POST" style="width: 400px; margin: auto; z-index: 10;">
        <div class="p-5 border border-dark rounded-4" style="box-shadow: 0px 0px 20px -3px rgba(0,207,24,1);">
            <div class="d-flex flex-column mx-auto row-gap-3 w-auto" id="login-fields">
                <div class="d-flex flex-row align-items-center">
                    <span><img src="img/email.png"></span>                    
                    <span><input id="email" name="email" type="text" placeholder="Email"></span>
                </div>
                <div class="d-flex flex-row align-items-center rounded">
                    <span><img src="img/padlock.png"></span>
                    <span>
                        <input id="password" name="password" type="password" placeholder="Password">
                        <a href="javascript:void(0);" onclick="togglePassword()" style="position: absolute; right: 10px;">
                            <img src="img/hidden.png" alt="Toggle Password" id="toggle-icon">
                        </a>
                    </span>
                </div>
                <div style="width: fit-content; margin: auto auto;padding-top: 20px;">
                    <button type="submit" class="btn btn-success">Login</button>
                    <a href="" class="btn btn-secondary">Reset Password</a> 
                </div>
            </div>
            <hr>
            <div class="d-flex justify-content-around">
                <button type="button" class="btn btn-outline-primary" onclick="populateLogin('Andre.D_Williams@school.org', 'Password2')">Teacher</button>
                <button type="button" class="btn btn-outline-success" onclick="populateLogin('admin@example.com', 'adminpassword')">Admin</button>
                <button type="button" class="btn btn-outline-info" onclick="populateLogin('john.doe@example.com', 'Password1')">Parent</button>
            </div>
        </div>            
    </form>   
</div>    

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="js/HeaderFooterManager.js"></script>
<script>
    function togglePassword() {
        const passwordField = document.getElementById('password');
        const toggleIcon = document.getElementById('toggle-icon');
        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            toggleIcon.src = 'img/eye.png'; // Update icon for "visible"
        } else {
            passwordField.type = 'password';
            toggleIcon.src = 'img/hidden.png'; // Update icon for "hidden"
        }
    }

    function populateLogin(email, password) {
        document.getElementById('email').value = email;
        document.getElementById('password').value = password;
    }

    // SweetAlert feedback
    <?php if (!empty($sweetAlert)) echo $sweetAlert; ?>
</script>

<?php include('_footer.php'); ?>
