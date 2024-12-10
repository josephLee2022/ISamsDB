<?php
include_once('alertManager.php');
include_once('db_connection.php');
$conn = getDatabaseConnection();
include('checkSession.php');

if($_SERVER['REQUEST_METHOD'] == 'GET'){
    
    if(!isset($_GET['id'])) {
        header('location:/all-students.php');
        exit;
    }

    $id = $_GET['id'];
    $sql="SELECT e.`id`, y.year,s.id stuid,s.fname sfname,s.mname smname,s.lname slname,s.genderid, g.gender,c.classcode,t.id teachid, t.fname,t.lname,t.mname FROM `enrollments` e
    left join year_groups y on y.id = e.yeargroupid
    left join classes c on c.id = e.classid
    left JOIN teachers t on t.id = c.teacherid
    left join test_Students s on s.id = e.studentid
    left join genders g on g.id = s.genderid
    where e.id = $id;";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();

    if(!$row){
        header('location:/all-students.php');
    }
    $studentid = $row['stuid'];
    $teacherid = $row['teachid'];
    $year = $row["year"];
    $fname = $row["sfname"];
    $mname = $row["smname"];
    $lname = $row["slname"];
    $genderid = $row["genderid"];
    $class= $row['classcode'];
    $teacher = $row['fname']. ' '.$row['lname'];
    $teacher_M_initial = $row['mname'];
    $gender = $row['gender'];
}

include('_header.php');
?>

<main class="py-5">
    <div class="container" style="display: grid;grid-template-columns: 300px auto; column-gap: 10px;background-color: var(--darkgrey);">
        <div><?php include('_sidenav.php'); ?></div>           
        <div class="p-4">
            <h2 class="page-title">Enrollment id=<?php echo $id?></h2>
            <br>
            <div class="tab-window row w-auto m-0">
                        <div class="info-heading col-3 text-end">Class</div>
                        <div class="info-details col"><?php echo $class?></div>
            </div>
            <div class="tab-window row w-auto m-0">
                        <div class="info-heading col-3 text-end">Year</div>
                        <div class="info-details col"><?php echo $year?></div>
            </div>
            <hr>
            <h3>Student</h3>
            <div class="tab-window row w-auto m-0">
                        <div class="info-heading col-3 text-end">First Name</div>
                        <div class="info-details col"><?php echo $fname?></div>
            </div>
            <div class="tab-window row w-auto m-0">
                        <div class="info-heading col-3 text-end">Middle Name</div>
                        <div class="info-details col"><?php echo $mname?></div>
            </div>
            <div class="tab-window row w-auto m-0">
                        <div class="info-heading col-3 text-end">Last Name</div>
                        <div class="info-details col"><?php echo $lname?></div>
            </div>
            <div class="tab-window row w-auto m-0">
                        <div class="info-heading col-3 text-end">Gender</div>
                        <div class="info-details col"><?php echo $gender?></div>
            </div>
            <a class="btn btn-primary" href="student.php?id=<?php echo $studentid?>">View Student</a>
            <hr>
            <h3>Teacher</h3>
            <div class="tab-window row w-auto m-0">
                        <div class="info-heading col-3 text-end">Name</div>
                        <div class="info-details col"><?php echo $teacher?></div>
            </div>
            <div class="tab-window row w-auto m-0">
                        <div class="info-heading col-3 text-end">Middle Initial</div>
                        <div class="info-details col"><?php echo substr($teacher_M_initial,0,1)?></div>
            </div>
            <a class="btn btn-primary" href="teacher.php?id=<?php echo $teacherid?>">View Teacher</a>


        </div>    
    </div>
</main>



<?php include('_footer.php'); ?>
