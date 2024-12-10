<?php
include_once('db_connection.php');
$conn = getDatabaseConnection();
include('checkSession.php');


$sql = "SELECT * FROM subjects";
$result = $conn->query($sql);

include('_header.php');
?>

<main class="py-5">
    <div class="container" style="display: grid;grid-template-columns: 300px auto; column-gap: 10px;background-color: var(--darkgrey);">
        <div><?php include('_sidenav.php'); ?></div>           
        <div class="p-4">
            <h2 class="page-title">all subjects</h2>
            <br>
            <p class="text-end">
                <a class="btn btn-primary" href="add-subject.php" >Add</a>
            </p>
            <table id="subjectsTable" class="table table-dark table-bordered table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Subjects</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows>0) :?>
                        <?php while($subject=$result->fetch_assoc()) :?>
                            <tr>
                                <td><?php echo ($subject['id']); ?></td>
                                <td><?php echo ($subject['subject']); ?></td>
                                <td>
                                    <a class="btn btn-info" href="edit-subject.php?id=<?php echo $subject['id'];?>">Edit</a>
                                    <a class="btn btn-danger">Delete</a>
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
