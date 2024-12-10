<?php
include_once('db_connection.php');
$conn = getDatabaseConnection();
include('checkSession.php');

// Ensure the teacher is logged in
if (!isset($_SESSION['email'])) {
    header('Location: login.php');
    exit;
}

$login_email = $_SESSION['email']; // Get the logged-in teacher's email

// Fetch classes associated with the logged-in teacher
$classSql = "
    SELECT c.id, c.classcode 
    FROM classes c
    INNER JOIN teachers t ON c.teacherid = t.id
    WHERE t.email = ?
";
$classStmt = $conn->prepare($classSql);
$classStmt->bind_param('s', $login_email);
$classStmt->execute();
$classResult = $classStmt->get_result();

// Handle attendance viewing form submission
$attendanceRecords = [];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $selectedClassId = $_POST['classid'];
    $selectedDate = $_POST['attendance_date'];

    // Fetch attendance records for the selected class and date
    $attendanceSql = "
        SELECT a.studentid, a.date, a.status, s.fname, s.lname, s.mname
        FROM attendance a
        INNER JOIN test_Students s ON a.studentid = s.id
        left join enrollments e on e.studentid = a.studentid
        left join classes c on c.id = e.classid
        WHERE a.date = ? AND c.classcode = ?
    ";
    $attendanceStmt = $conn->prepare($attendanceSql);
    $attendanceStmt->bind_param('si', $selectedDate, $selectedClassId);
    $attendanceStmt->execute();
    $attendanceRecords = $attendanceStmt->get_result();
}

include('_header.php');
?>

<main class="py-5">
    <div class="container" style="display: grid;grid-template-columns: 300px auto; column-gap: 10px;background-color: var(--darkgrey);">
        <div><?php include('_sidenav.php'); ?></div>           
        <div class="p-4">
            <h2 class="page-title">Edit Grades</h2>
            <br>
            <form method="POST">
                <div class="form-group">
                    <label for="classid">Class</label>
                    <select id="classid" name="classid" class="form-control" required>
                        <option value="" disabled selected>Select Class</option>
                        <?php while ($class = $classResult->fetch_assoc()): ?>
                            <option value="<?php echo $class['id']; ?>">
                                <?php echo htmlspecialchars($class['classcode']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="attendance_date">Date</label>
                    <input type="date" id="attendance_date" name="attendance_date" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary">View Attendance</button>
            </form>

            <?php if (!empty($attendanceRecords)): ?>
                <h3 class="mt-4">Attendance Records</h3>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Student Name</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($record = $attendanceRecords->fetch_assoc()): ?>
                            <tr>
                                <td>
                                    <?php 
                                    echo htmlspecialchars($record['fname'] . ' ' . $record['mname'] . ' ' . $record['lname']); 
                                    ?>
                                </td>
                                <td><?php echo htmlspecialchars($record['status']); ?></td>
                                <td><?php echo htmlspecialchars($record['date']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php elseif ($_SERVER['REQUEST_METHOD'] == 'POST'): ?>
                <p>No attendance records found for the selected class and date.</p>
            <?php endif; ?>
        </div>    
    </div>
</main>

<?php include('_footer.php'); ?>
