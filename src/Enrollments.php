<?php
include_once('db_connection.php');
$conn = getDatabaseConnection();
include('checkSession.php');


$sql = "
SELECT e.`id`, y.year,s.fname sfname,s.mname smname,s.lname slname ,c.classcode, t.fname,t.lname FROM `enrollments` e
left join year_groups y on y.id = e.yeargroupid
left join classes c on c.id = e.classid
left JOIN teachers t on t.id = c.teacherid
left join test_Students s on s.id = e.studentid;
";
$result = $conn->query($sql);

include('_header.php');
?>

<main class="py-5">
    <div class="container" style="display: grid;grid-template-columns: 300px auto; column-gap: 10px;background-color: var(--darkgrey);">
        <div><?php include('_sidenav.php'); ?></div>           
        <div class="p-4">
            <h2 class="page-title">Current Enrollments</h2>
            <br>
            <p class="text-end">
                <a class="btn btn-primary" href="add-student.php" >Add</a>
            </p>
            <table id="enrollments" class="table table-dark table-bordered table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>year</th>
                        <th>Student Name</th>
                        <th>class</th>
                        <th>Teacher</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows>0) :?>
                        <?php while($e=$result->fetch_assoc()) :?>
                            <tr>
                                <td><?php echo ($e['id']); ?></td>

                                <td><?php echo ($e['year']); ?></td>
                                <td><?php echo ($e['sfname'].' '. substr($e['smname'],0,1).'. '.$e['slname']); ?></td>
                                <td><?php echo ($e['classcode']); ?></td>
                                <td><?php echo ($e['fname']. ' ' .$e['lname']); ?></td>
                                <td>
                                    <a class="btn btn-info" href="enrollment.php?id=<?php echo $e['id'];?>">View</a>
                                    <a class="btn btn-warning">Remove</a>
                                </td>
                            </tr>
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

<script>
    $(document).ready(function() {
        $('#enrollments').DataTable({
            // Additional configurations (optional)
            paging: true,
            searching: true,
            ordering: true,
            info: true,
        });
    });
</script>
<?php include('_footer.php'); ?>
