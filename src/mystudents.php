<?php
include_once('db_connection.php');
$conn = getDatabaseConnection();
include('checkSession.php');

$login_email = $_SESSION['email'];


$query = "SELECT * FROM user_credentials WHERE email = '$login_email'";
$result = $conn->query($query);
$row = $result->fetch_assoc();

    $sql = "
    SELECT e.`id`, e.`yeargroupid`, e.`classid`, e.`studentid`, y.year, c.classcode, c.teacherid, s.fname, s.lname, s.mname, t.email
    FROM `enrollments` e
    LEFT JOIN year_groups y ON y.id = e.yeargroupid
    LEFT JOIN classes c ON c.id = e.classid
    LEFT JOIN test_Students s ON s.id = e.studentid
    LEFT JOIN teachers t ON t.id = c.teacherid
    WHERE t.email = '$login_email'
    ";
$result = $conn->query($sql);

include('_header.php');
?>

<main class="py-5">
    <div class="container" style="display: grid;grid-template-columns: 300px auto; column-gap: 10px;background-color: var(--darkgrey);">
        <div><?php include('_sidenav.php'); ?></div>           
        <div class="p-4">
            <h2 class="page-title">My Students</h2>
            <br>
            <table id="subjectsTable" class="table table-dark table-bordered table-hover">
                <thead>
                    <tr>
                        <th>First Name</th>
                        <th>Middle Initial</th>
                        <th>Last Name</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows>0) :?>
                        <?php while($student=$result->fetch_assoc()) :?>
                            <tr>
                                <td><?php echo $student['fname']?></td>
                                <td><?php echo substr($student['mname'],0,1)?></td>
                                <td><?php echo $student['lname']?></td>                                
                                <td>
                                    <a class="btn btn-info" href="student-grades.php?id=<?php echo $student['id']?>">View Grades</a>
                                </td>
                            </tr>
                        <?php endwhile;?>
                    <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center">No Students Found</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>    
    </div>
</main>

<?php include('_footer.php'); ?>
