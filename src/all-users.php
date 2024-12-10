<?php
include_once('db_connection.php');
$conn = getDatabaseConnection();
include('checkSession.php');

$sql = "
SELECT 
u.email,
u.password,
u.photo,
u.user_type
FROM user_credentials u
";
$result = $conn->query($sql);

include('_header.php');
?>

<main class="py-5">
    <div class="container" style="display: grid; grid-template-columns: 300px auto; column-gap: 10px; background-color: var(--darkgrey);">
        <div><?php include('_sidenav.php'); ?></div>           
        <div class="p-4">
            <h2 class="page-title">All Userss</h2>
            <br>
            <table id="usersTable" class="table table-dark table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Email</th>
                        <th>Password</th>
                        <th>Photo</th>
                        <th>User Type</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while($users = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo ($users['email']); ?></td>
                                <td><?php echo ($users['password']); ?></td>
                                <td><img id="profilePreview" 
                                    src="<?php echo ($users['photo'] == NULL) ? 'img/user.png' : 'data:image/jpeg;base64,' . base64_encode($users['photo']); ?>" 
                                    alt="Profile Picture" 
                                    style="width: 30px; height:30px; object-fit:cover; object-position:center; border-radius:100px;">
                                </td>
                                <td><?php echo ($users['user_type']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center">No userss Found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>    
    </div>
</main>

<script>
    $(document).ready(function() {
        $('#usersTable').DataTable({
            // Additional configurations (optional)
            paging: true,
            searching: true,
            ordering: true,
            info: true,
        });
    });
</script>

<?php include('_footer.php'); ?>
