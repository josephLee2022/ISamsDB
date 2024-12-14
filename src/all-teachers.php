<?php
include_once('db_connection.php');
$conn = getDatabaseConnection();
include('checkSession.php');


$sql = "SELECT * FROM teachers WHERE `status` = 1";
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
            <table id="teachersTable" class="table table-dark table-bordered table-hover">
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
                                    <button class="btn btn-danger delete-teacher" data-id="<?php echo $teacher['id']; ?>">Delete</button>
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

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function () {
        // Event listener for the delete button
        $(document).on('click', '.delete-teacher', function () {
            const teacherId = $(this).data('id'); // Get the teacher ID from the data attribute

            // Confirm deletion using SweetAlert2
            Swal.fire({
                title: 'Are you sure?',
                text: 'This action is irreversible. The teacher and all related records will be permanently deleted.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel',
            }).then((result) => {
                if (result.isConfirmed) {
                    // Perform AJAX request to delete the teacher
                    $.ajax({
                        url: 'delete_teacher.php', // PHP file to handle the delete request
                        type: 'POST',
                        data: { id: teacherId },
                        success: function (response) {
                            if (response === 'success') {
                                // Show success alert after deletion
                                Swal.fire({
                                    title: 'Deleted!',
                                    text: 'The teacher has been successfully deleted.',
                                    icon: 'success',
                                    confirmButtonColor: '#3085d6',
                                }).then(() => {
                                    // Remove the teacher's row from the table
                                    $(`button[data-id="${teacherId}"]`).closest('tr').remove();
                                });
                            } else {
                                // Show error alert if the delete failed
                                Swal.fire({
                                    title: 'Error!',
                                    text: 'Unable to delete the teacher. Please try again later.',
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
        $('#teachersTable').DataTable({
            // Additional configurations (optional)
            paging: true,
            searching: true,
            ordering: true,
            info: true,
        });
    });
</script>

<?php include('_footer.php'); ?>
