<?php
include_once('db_connection.php');
$conn = getDatabaseConnection();
include('checkSession.php');
include_once('AlertManager.php');

$sql = "
SELECT s.`id` ID, s.`fname` fname, s.`mname` mname, s.`lname` lname, g.gender gender, s.`photo` photo, s.`address` address, TIMESTAMPDIFF(YEAR, s.`dob`, CURDATE()) age, 
       CONCAT(p.fname, ' ', p.lname) parent_name, s.`date_created` , s.`status`
FROM test_Students s
LEFT JOIN genders g ON s.genderid = g.id
LEFT JOIN parents p ON s.parentid = p.id
where status = 1;
";
$result = $conn->query($sql);

include('_header.php');
?>

<?php
// Your existing code here (SQL query, etc.)
?>

<main class="py-5">
    <div class="container" style="display: grid; grid-template-columns: 300px auto; column-gap: 10px; background-color: var(--darkgrey);">
        <div><?php include('_sidenav.php'); ?></div>           
        <div class="p-4">
            <h2 class="page-title">All Students</h2>
            <br>
            <p class="text-end">
                <a class="btn btn-primary" href="add-student.php">Add</a>
                <button id="importBtn" class="btn btn-secondary">Import Students</button>
            </p>
            <table id="studentsTable" class="table table-dark table-bordered table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Photo</th>
                        <th>Name</th>
                        <th>Gender</th>
                        <th>Parent</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while($student = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo ($student['ID']); ?></td>
                                <td><img id="profilePreview" 
                                    src="<?php echo ($student['photo'] == NULL) ? 'img/user.png' : 'data:image/jpeg;base64,' . base64_encode($student['photo']); ?>" 
                                    alt="Profile Picture" 
                                    style="width: 30px; height:30px; object-fit:cover; object-position:center; border-radius:100px;">
                                </td>
                                <td><?php echo ($student['fname'] . ' ' . $student['lname']); ?></td>
                                <td><?php echo ($student['gender']); ?></td>
                                <td><?php echo ($student['parent_name']); ?></td>
                                <td>
                                    <a class="btn btn-info" href="student.php?id=<?php echo $student['ID']; ?>">Edit</a>
                                    <button class="btn btn-danger delete-student" data-id="<?php echo $student['ID']; ?>">Delete</button>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center">No Students Found</td>
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
        $(document).on('click', '.delete-student', function () {
            const studentId = $(this).data('id'); // Get the student ID from the data attribute

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
                        url: 'delete_student.php', // PHP file to handle the delete request
                        type: 'POST',
                        data: { id: studentId },
                        success: function (response) {
                            if (response === 'success') {
                                // Show success alert after deletion
                                Swal.fire({
                                    title: 'Deleted!',
                                    text: 'The student has been successfully deleted.',
                                    icon: 'success',
                                    confirmButtonColor: '#3085d6',
                                }).then(() => {
                                    // Remove the student's row from the table
                                    $(`button[data-id="${studentId}"]`).closest('tr').remove();
                                });
                            } else {
                                // Show error alert if the delete failed
                                Swal.fire({
                                    title: 'Error!',
                                    text: 'Unable to delete the student. Please try again later.',
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
        $('#studentsTable').DataTable({
            // Additional configurations (optional)
            paging: true,
            searching: true,
            ordering: true,
            info: true,
        });
    });
</script>
<?php include('_footer.php'); ?>
