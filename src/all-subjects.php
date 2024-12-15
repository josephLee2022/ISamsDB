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
                <a class="btn btn-primary" href="subject.php" >Add</a>
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
                                    <a class="btn btn-info" href="subject.php?id=<?php echo $subject['id'];?>">Edit</a>
                                    <button class="btn btn-danger delete-subject" data-id="<?php echo $subject['id']; ?>">Delete</button>
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

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function () {
        // Event listener for the delete button
        $(document).on('click', '.delete-subject', function () {
            const subjectId = $(this).data('id'); // Get the student ID from the data attribute

            // Confirm deletion using SweetAlert2
            Swal.fire({
                title: 'Are you sure?',
                text: 'This action is irreversible. The subject and all related records will be permanently deleted.',
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
                        url: 'delete_subject.php', // PHP file to handle the delete request
                        type: 'POST',
                        data: { id: subjectId },
                        success: function (response) {
                            if (response === 'success') {
                                // Show success alert after deletion
                                Swal.fire({
                                    title: 'Deleted!',
                                    text: 'The subject has been successfully deleted.',
                                    icon: 'success',
                                    confirmButtonColor: '#3085d6',
                                }).then(() => {
                                    // Remove the student's row from the table
                                    $(`button[data-id="${subjectId}"]`).closest('tr').remove();
                                });
                            } else {
                                // Show error alert if the delete failed
                                Swal.fire({
                                    title: 'Error!',
                                    text: 'Unable to delete the subject. Please try again later.',
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

<?php include('_footer.php'); ?>
