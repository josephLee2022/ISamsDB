<?php
include_once('db_connection.php');
$conn = getDatabaseConnection();
include('checkSession.php');


$sql = "SELECT parents.id ID, `fname`, `mname`, `lname`, g.gender gender, `email`, `address`, `password`, `photo`, `date_created` FROM `parents` left join genders g on g.id = parents.genderid;";
$result = $conn->query($sql);

include('_header.php');
?>

<main class="py-5">
    <div class="container" style="display: grid;grid-template-columns: 300px auto; column-gap: 10px;background-color: var(--darkgrey);">
        <div><?php include('_sidenav.php'); ?></div>           
        <div class="p-4">
            <h2 class="page-title">all Parents/Guardians</h2>
            <br>
            <p class="text-end">
                <a class="btn btn-primary" href="add-parent.php" >Add</a>
            </p>
            <table id="parentsTable" class="table table-dark table-bordered table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Photo</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Gender</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows>0) :?>
                        <?php while($parent=$result->fetch_assoc()) :?>
                            <tr>
                                <td><?php echo ($parent['ID']); ?></td>
                                <td class="text-center">
                                <img id="profilePreview" 
                                    src="<?php echo ($parent['photo']==NULL) ? 'img/user.png' : 'data:image/jpeg;base64,' . base64_encode($parent['photo']); ?>" 
                                    alt="Profile Picture" 
                                    style="width: 30px; height:30px; object-fit:cover; object-position:center; border-radius:100px;">
                                </td>
                                <td>
                                    <?php echo(
                                        $parent['fname'].' '.
                                        substr($parent['mname'],0,1).'. '.
                                        $parent['lname']); ?>
                                </td>
                                <td><?php echo ($parent['email']); ?></td>
                                <td><?php echo ($parent['gender']); ?></td>
                                <td>
                                    <a class="btn btn-info" href="parent.php?id=<?php echo $parent['ID'];?>">View</a>
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


<script>
    $(document).ready(function() {
        $('#parentsTable').DataTable({
            // Additional configurations (optional)
            paging: true,
            searching: true,
            ordering: true,
            info: true,
        });
    });
</script>
<?php include('_footer.php'); ?>
