<?php
include_once('db_connection.php');
$conn = getDatabaseConnection();
include('checkSession.php');


$sql = "SELECT * FROM teachers";
$result = $conn->query($sql);

include('_header.php');
?>

<main class="py-5">
    <div class="container" style="display: grid;grid-template-columns: 300px auto; column-gap: 10px;background-color: var(--darkgrey);">
        <div><?php include('_sidenav.php'); ?></div>           
        <div class="p-4">
            <h2 class="page-title">all Teachers</h2>
            <br>
            <p class="text-end">
                <a class="btn btn-primary" href="add-teacher.php" >Add</a>
            </p>
            <table id="subjectsTable" class="table table-dark table-bordered table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>photo</th>
                        <th>EmployeeID</th>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows>0) :?>
                        <?php while($teacher=$result->fetch_assoc()) :?>
                            <tr>
                                <td><?php echo ($teacher['id']); ?></td>
                                <td><img id="profilePreview" 
                                    src="<?php echo ($teacher['photo']==NULL) ? 'img/user.png' : 'data:image/jpeg;base64,' . base64_encode($teacher['photo']); ?>" 
                                    alt="Profile Picture" 
                                    style="width: 30px; height:30px; object-fit:cover; object-position:center; border-radius:100px;">
                                </td>
                                <td><?php echo ($teacher['teacherEmpId']); ?></td>
                                <td><?php echo ($teacher['fname']. ' '.$teacher['lname']); ?></td>
                                <td><?php echo ($teacher['phone']); ?></td>
                                <td>
                                    <a class="btn btn-info" href="teacher.php?id=<?php echo $teacher['id'];?>">Edit</a>
                                    <a class="btn btn-danger">Delete</a>
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



<?php include('_footer.php'); ?>
