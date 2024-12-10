<?php
include_once('db_connection.php');
$conn = getDatabaseConnection();
include('checkSession.php');

$login_email = $_SESSION['email'];

    $sql = "
    Select 
    s.id stuID,
    s.fname,
    s.mname,
    s.lname,
    p.id parentID,
    p.email
    From test_Students s
    left join parents p on p.id = s.parentid
    WHERE p.email = '$login_email'
    ";
$result = $conn->query($sql);

include('_header.php');
?>

<main class="py-5">
    <div class="container" style="display: grid;grid-template-columns: 300px auto; column-gap: 10px;background-color: var(--darkgrey);">
        <div><?php include('_sidenav.php'); ?></div>           
        <div class="p-4">
            <h2 class="page-title">My Child/Children</h2>
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
                                    <a class="btn btn-info" href="student-grades.php?id=<?php echo $student['stuID']?>">View Grades</a>
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
