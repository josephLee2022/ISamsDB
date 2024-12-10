<?php
include_once('db_connection.php');
$conn = getDatabaseConnection();

include('checkSession.php');


// Now you can use session variables
$user_type = $_SESSION['user_type'];

$sql = "SELECT 
c.id ID,
c.grade Grade,
c.classcode Class,
t.fname `First Name`,
t.mname `Middle Name`,
t.lname `Last Name`
FROM classes c
LEFT JOIN teachers t ON t.id = c.teacherid;";
$result = $conn->query($sql);

include('_header.php');
?>

<main class="py-5">
    <div class="container" style="display: grid;grid-template-columns: 300px auto; column-gap: 10px;background-color: var(--darkgrey);">
        <div><?php include('_sidenav.php'); ?></div>           
        <div class="p-4">
            <h2 class="page-title">all Classes</h2>
            <br>

            <table id="subjectsTable" class="table table-dark table-bordered table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Grade</th>
                        <th>ClassCode</th>
                        <th>Teacher</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows>0) :?>
                        <?php while($class=$result->fetch_assoc()) :?>
                            <tr>
                                <td><?php echo ($class['ID']); ?></td>
                                <td><?php echo ($class['Grade']); ?></td>
                                <td><?php echo ($class['Class']); ?></td>
                                <td><?php echo ($class['First Name']. ' '. substr($class['Middle Name'],0,1).'. '.$class['Last Name']); ?></td>
                                <td>
                                    <a class="btn btn-info" href="class.php?id=<?php echo $class['ID'];?>">Edit</a>
                                </td>
                            </trr>
                        <?php endwhile;?>
                    <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center">No subjects Found</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>    
    </div>
</main>



<?php include('_footer.php'); ?>
