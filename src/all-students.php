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
LEFT JOIN parents p ON s.parentid = p.id;
";
$result = $conn->query($sql);

include('_header.php');
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
                                    <a class="btn btn-danger">Delete</a>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.getElementById('importBtn').addEventListener('click', function () {
        Swal.fire({
            title: 'Do you already have the template?',
            showDenyButton: true,
            showCancelButton: true,
            confirmButtonText: 'Yes, I have it',
            denyButtonText: 'No, download it',
        }).then((result) => {
            if (result.isConfirmed) {
                // Prompt user to upload the file
                Swal.fire({
                    title: 'Upload CSV File',
                    html: '<form id="uploadForm" action="import_students.php" method="POST" enctype="multipart/form-data">' +
                        '<input type="file" name="csv_file" class="form-control" accept=".csv" required>' +
                        '</form>',
                    showCancelButton: true,
                    confirmButtonText: 'Upload',
                    preConfirm: () => {
                        document.getElementById('uploadForm').submit();
                    }
                });
            } else if (result.isDenied) {
                // Redirect to download the template
                window.location.href = 'generate_csv_template.php';
            }
        });
    });
</script>

<?php include('_footer.php'); ?>
