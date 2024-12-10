<?php
// Include database connection
include_once('db_connection.php');
$login_conn = getDatabaseConnection();

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header('Location: login.php');
    exit();
}
$login_fname = '';
$login_lname = '';
$login_photo = '';
$LoginID = '';

$admin_menu='none';
$teacher_menu='none';
$parent_menu='none';

// Retrieve the user's email from the session
$login_email = $_SESSION['email'];
$login_user = $_SESSION['user_type'];

if($login_user=='Admin'){
    $login_fname = 'Admin';
    $login_lname = 'User';
    $admin_menu='block';
}
elseif($login_user=='Teacher'){
    $loginsql = "Select * from teachers where email = '$login_email'";
    $loginresults = $login_conn->query($loginsql);
    $loginrow= $loginresults->fetch_assoc();
    $login_fname = $loginrow['fname'];
    $login_lname = $loginrow['lname'];
    $login_photo = $loginrow['photo'];
    $LoginID = $loginrow['id'];
    $teacher_menu='block';
}
elseif($login_user=='Parent'){
    $loginsql = "Select * from parents where email = '$login_email'";
    $loginresults = $login_conn->query($loginsql);
    $loginrow= $loginresults->fetch_assoc();
    $login_fname = $loginrow['fname'];
    $login_lname = $loginrow['lname'];
    $login_photo = $loginrow['photo'];
    $LoginID = $loginrow['id'];
    $parent_menu='block';
}

// Fetch the user's login_photo from the database

// Convert BLOB data to Base64-encoded string
if (!empty($login_photo)) {
    $profilePic = 'data:image/jpeg;base64,' . base64_encode($login_photo);
} else {
    // Use a default image if no login_photo exists
    $profilePic = 'img/user.png';
}
$login_conn->close();
?>

<div class="p-4">
    <div class="text-center">
        <!-- Dynamically render the user's profile picture -->
        <img class="profile-pic" src="<?php echo $profilePic; ?>" alt="Profile Picture">
        <h3><?php echo $login_fname.' '.$login_lname?></h3>
    </div>
</div>    
<div style="display: <?php echo $admin_menu?>;" class="side-nav">
    <a href="admin-dashboard.php">
        <img src="img/dashboard.png" alt="">
        <span>admin Dashboard</span>                      
    </a>
    <a href="years.php">
        <img src="img/clock.png" alt="">
        <span>Year Group</span>                            
    </a>
    <a href="all-subjects.php">
        <img src="img/books.png" alt="">
        <span>All Subjects</span>
    </a>
    <a href="all-classes.php">
        <img src="img/training.png" alt="">
        <span>All Classes</span>
    </a>
    <a href="Enrollments.php">
        <img src="img/training.png" alt="">
        <span>Enrollments</span>
    </a>
    <a href="all-students.php">
        <img src="img/reading.png" alt="">
        <span>All Students</span>
    </a>
    <a href="all-teachers.php">
        <img src="img/teacher.png" alt="">
        <span>All Teachers</span>
    </a>
    <a href="all-parents.php">
        <img src="img/family.png" alt="">
        <span>All Parents</span>
    </a>
    <a href="all-users.php">
        <img src="img/people.png" alt="">
        <span>All Users</span>
    </a>
    <a href="logout.php">
        <img src="img/user.png" alt="">
        <span>Logout</span>
    </a>                
</div>
<div style="display: <?php echo $teacher_menu?>;" class="side-nav">
    <a href="teacher-dashboard.php">
        <img src="img/dashboard.png" alt="">
        <span>teacher Dashboard</span>                      
    </a>
    <a href="mystudents.php">
        <img src="img/clock.png" alt="">
        <span>My Students</span>                            
    </a>
    <a href="attendance.php">
        <img src="img/clock.png" alt="">
        <span>Mark Attendance</span>                            
    </a>
    <a href="view-attendance.php">
        <img src="img/clock.png" alt="">
        <span> View Attendance</span>                            
    </a>
    <a href="teacher.php?id=<?php echo $LoginID; ?>">
        <img src="img/user.png" alt="">
        <span>Profile</span>
    </a>
    
    <a href="logout.php">
        <img src="img/user.png" alt="">
        <span>Logout</span>
    </a>                
</div>
<div style="display: <?php echo $parent_menu?>;" class="side-nav">
    <a href="parent-dashboard.php">
        <img src="img/dashboard.png" alt="">
        <span>Parent Dashboard</span>                      
    </a>
    
    <a href="mychildren.php">
        <img src="img/people.png" alt="">
        <span>My Child/Children</span>
    </a>
    <a href="parent.php?id=<?php echo $LoginID; ?>">
        <img src="img/user.png" alt="">
        <span>Profile</span>
    </a>
    <a href="logout.php">
        <img src="img/user.png" alt="">
        <span>Logout</span>
    </a>                
</div>