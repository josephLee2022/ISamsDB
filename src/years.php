<?php
include_once('db_connection.php');
$conn = getDatabaseConnection();
include('checkSession.php');

$sql = "SELECT * FROM year_groups";
$result = $conn->query($sql);

include('_header.php');
?>

<main class="py-5">
    <div class="container" style="display: grid;grid-template-columns: 300px auto; column-gap: 10px;background-color: var(--darkgrey);">
        <div><?php include('_sidenav.php'); ?></div>           
        <div class="p-4">
            <h2 class="page-title">Year Groups</h2>
            <br>
            <p class="text-end">
                <a class="btn btn-primary" href="add-year.php" >Add</a>
            </p>
            <table id="yearsTable" class="table table-dark table-bordered table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Year</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows>0) :?>
                        <?php while($year=$result->fetch_assoc()) :?>
                            <tr>
                                <td><?php echo ($year['id']); ?></td>
                                <td><?php echo ($year['year']); ?></td>
                                <td>
                                    <a class="btn btn-info" href="year.php?id=<?php echo $year['id'];?>">Edit</a>
                                    <button class="btn btn-danger delete-year" data-id="<?php echo $year['id']; ?>">Delete</button>
                                </td>
                            </trr>
                        <?php endwhile;?>
                    <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center">No Years Found</td>
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
        $(document).on('click', '.delete-year', function () {
            const yearID = $(this).data('id'); // Get the student ID from the data attribute

            // Confirm deletion using SweetAlert2
            Swal.fire({
                title: 'Are you sure?',
                text: 'This action is irreversible. The student and all related records will be permanently deleted.',
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
                        url: 'delete_yearGroup.php', // PHP file to handle the delete request
                        type: 'POST',
                        data: { id: yearID },
                        success: function (response) {
                            if (response === 'success') {
                                // Show success alert after deletion
                                Swal.fire({
                                    title: 'Deleted!',
                                    text: 'The year group has been successfully deleted.',
                                    icon: 'success',
                                    confirmButtonColor: '#3085d6',
                                }).then(() => {
                                    // Remove the student's row from the table
                                    $(`button[data-id="${yearID}"]`).closest('tr').remove();
                                });
                            } else {
                                // Show error alert if the delete failed
                                Swal.fire({
                                    title: 'Error!',
                                    text: 'Unable to delete the year_group. Please try again later.',
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>



<?php include('_footer.php'); ?>
