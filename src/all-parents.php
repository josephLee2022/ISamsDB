<?php
include_once('db_connection.php');
$conn = getDatabaseConnection();
include('checkSession.php');


$sql = "SELECT parents.id ID, `fname`, `mname`, `lname`, g.gender gender, `email`, `address`, `password`, `photo`, `date_created`, `status` FROM `parents` left join genders g on g.id = parents.genderid WHERE status =1;";
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
                                    <button class="btn btn-danger delete-parent" data-id="<?php echo $parent['ID']; ?>">Delete</button>
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
    $(document).ready(function () {
        // Event listener for the delete button
        $(document).on('click', '.delete-parent', function () {
            const parentId = $(this).data('id'); // Get the student ID from the data attribute

            // Confirm deletion using SweetAlert2
            Swal.fire({
                title: 'Are you sure?',
                text: 'This action is irreversible. The Parent and all related records will be permanently deleted.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel',
            }).then((result) => {
                if (result.isConfirmed) {
                    // Perform AJAX request to delete the student
                    $.ajax({
                        url: 'delete_parent.php', // PHP file to handle the delete request
                        type: 'POST',
                        data: { id: parentId },
                        success: function (response) {
                            if (response === 'success') {
                                // Show success alert after deletion
                                Swal.fire({
                                    title: 'Deleted!',
                                    text: 'The parent has been successfully deleted.',
                                    icon: 'success',
                                    confirmButtonColor: '#3085d6',
                                }).then(() => {
                                    // Remove the student's row from the table
                                    $(`button[data-id="${parentId}"]`).closest('tr').remove();
                                });
                            } else {
                                // Show error alert if the delete failed
                                Swal.fire({
                                    title: 'Error!',
                                    text: 'Unable to parent the student. Please try again later.',
                                    icon: 'error',
                                    confirmButtonColor: '#3085d6',
                                });
                            }
                        },
                        error: function () {
                            // Show error alert if AJAX fails
                            Swal.fire({
                                title: 'Error!',
                                text: 'An error occurred while processing the request. Please try again.',
                                icon: 'error',
                                confirmButtonColor: '#3085d6',
                            });
                        },
                    });
                }
            });
        });
    });
</script>

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
