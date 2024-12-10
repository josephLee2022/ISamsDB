<?php
include_once('alertManager.php');
include_once('db_connection.php');
$conn = getDatabaseConnection();
include('checkSession.php');

// Initialize variables
$id = "";
$fname = "";
$mname = "";
$lname = "";
$genderid = "";
$gender = "";
$photo = "";
$address = "";
$dob = "";
$parentid = "";
$parent = "";
$status = "";
$classid = "";
$classcode = "";
$teacherid = "";
$teachfname = "";
$teachmname = "";
$teachlname = "";
$teachEmpId = "";
$errormessage = "";
$successmessage = "";

if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    // Redirect if no ID is passed
    if (!isset($_GET['id'])) {
        header('location:/all-students.php');
        exit;
    }
    
    $id = $_GET['id'];
    
    
    // Define SQL query
    $sql = "
    SELECT 
        e.id AS enrollmentID,  
        y.year AS yearGroup, 
        s.id AS studentID, 
        s.fname AS fname, 
        s.mname AS mname, 
        s.lname AS lname, 
        s.address AS address, 
        s.genderid AS genderID, 
        g.gender AS gender, 
        c.id AS classid, 
        c.classcode AS classcode, 
        t.id AS teacherID, 
        t.fname AS teachfname, 
        t.mname AS teachmname, 
        t.lname AS teachlname, 
        t.teacherEmpId AS teachEmpId,
        s.photo AS photo,
        s.dob AS dob,
        s.parentid AS parentid,
        p.fname AS parentfname,
        p.mname AS parentmname,
        p.lname AS parentlname,
        s.status AS status
    FROM enrollments e
    LEFT JOIN year_groups y ON y.id = e.yeargroupid
    LEFT JOIN classes c ON c.id = e.classid
    LEFT JOIN teachers t ON t.id = c.teacherid
    LEFT JOIN test_Students s ON s.id = e.studentid
    LEFT JOIN genders g ON g.id = s.genderid
    LEFT JOIN parents p ON p.id = s.parentid
    WHERE s.id = $id";

    

    // Execute query
    $result = $conn->query($sql);

    // Check if data is retrieved
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Map database fields to PHP variables
        $fname = $row["fname"];
        $mname = $row["mname"];
        $lname = $row["lname"];
        $genderid = $row["genderID"];
        $gender = $row["gender"];
        $photo = $row["photo"];
        $address = $row["address"];
        $dob = $row["dob"];
        $parentid = $row["parentid"];
        $parentfname = $row["parentfname"];
        $parentmname = $row["parentmname"];
        $parentlname = $row["parentlname"];
        $status = $row["status"];
        $classid = $row["classid"];
        $classcode = $row["classcode"];
        $teacherid = $row["teacherID"];
        $teachfname = $row["teachfname"];
        $teachmname = $row["teachmname"];
        $teachlname = $row["teachlname"];
        $teachEmpId = $row["teachEmpId"];
    }
    
}
else{
    $id = $_POST["id"];
    $fname = $_POST["fname"];
    $mname = $_POST["mname"];
    $lname = $_POST["lname"];
    $address = $_POST["address"];

    do{
        if (empty($id) || empty($fname) || empty($mname) || empty($lname) || empty($address)) 
            {$errormessage = "all fields are required:".$conn->error;
            break;
        }        

        $sql = "UPDATE `test_Students` 
        SET `fname` = '$fname', `mname` = '$mname', `lname` = '$lname', `address` = '$address' WHERE `id` = $id";
        $result = $conn->query($sql);

        if(!$result){
            $errormessage = "Invalid query:".$conn->error;
            break;
        }
        
        // Close connection
        $conn->close();

        header("location:/student.php?id=$id");
        exit;
        
    }while(false);    
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
                <h1><?php echo $fname .' ' .$lname?></h1>





                <?php echo "Entered id: $id<br>";?>





                
            </div>
            <div>
                <div class="tab">
                    <button class="tablinks" onclick="openCity(event, 'info')" id="defaultOpen">Info</button>
                    <button class="tablinks" onclick="openCity(event, 'profile')">Update Profile</button>
                </div>
                <br>
                <!-- Tab content -->
                <div id="info" class="tabcontent" style="display: block;">
                    <div class="tab-window row w-auto m-0">
                        <div class="info-heading col-3 text-end">Name</div>
                        <div class="info-details col"><?php echo $fname. ' ' . $lname?></div>
                    </div>
                    <div class="tab-window row w-auto m-0">
                        <div class="info-heading col-3 text-end">Class</div>
                        <div class="info-details col"><?php echo $classcode?></div>
                    </div>
                    <div class="tab-window row w-auto m-0">
                        <div class="info-heading col-3 text-end">Teacher</div>
                        <div class="info-details col"><?php echo $teachfname.' '.$teachlname?></div>
                    </div>
                    <div class="tab-window row w-auto m-0">
                        <div class="info-heading col-3 text-end">Gender</div>
                        <div class="info-details col"><?php echo $gender?></div>
                    </div>
                    <div class="tab-window row w-auto m-0">
                        <div class="info-heading col-3 text-end">D.O.B</div>
                        <div class="info-details col"><?php echo date("d/M/Y", strtotime($dob));?></div>
                    </div>
                </div>
                
                <div id="profile" class="tabcontent">
                    <?php 
                        // Example for displaying an error message
                        if (!empty($errormessage)) {
                            AlertManager::displayWarning($errormessage);
                        }

                        // Example for displaying a success message
                        if (!empty($successmessage)) {
                            AlertManager::displaySuccess($successmessage);
                        }
                    ?>
                    <form method="post">
                        <input type="hidden" name="id" value="<?php echo $id?>">

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
                            <div class="info-heading col-3 text-end">Parent</div>
                            <div class="info-details col">
                                <input hidden type="text" id="parentid" name="parentid" value="<?php echo $parentid?>">
                                <input disabled type="text" id="parent" name="parent" value="<?php echo $parentfname.' '.$parentlname?>">
                                <a class="btn btn-primary" href="parent.php?id=<?php echo $parentid?>">View Parent</a>
                            </div>
                        </div>
                        <div class="tab-window row w-auto m-0">
                            <div class="info-heading col-3 text-end">Address</div>
                            <div class="info-details col">
                                <input type="text" id="address" name="address" value="<?php echo $address?>"></div>
                        </div>
                        <br>
                        <button type="submit" class="btn btn-success w-100">SUBMIT</button>
                    </form>
                </div>
                <br>

                <div>
                    <a href="all-students.php" class="btn btn-warning">Back</a>
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
            </div>   
        </div>    
    </div>
</main>

<?php include('_footer.php'); ?>