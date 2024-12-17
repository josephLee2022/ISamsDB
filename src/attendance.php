<?php
include_once('db_connection.php');
$conn = getDatabaseConnection();
include('checkSession.php');

// Ensure the teacher is logged in and the email is stored in session
if (!isset($_SESSION['email'])) {
    header('Location: login.php'); // Redirect to login page if not logged in
    exit;
}

$login_email = $_SESSION['email']; // Get the logged-in teacher's email

// Step 1: Fetch the students associated with the logged-in teacher
$sql = "
    SELECT e.`id`, e.`yeargroupid`, e.`classid`, e.`studentid`, y.year, c.classcode, c.teacherid, 
           s.fname, s.lname, s.mname, t.email
    FROM `enrollments` e
    LEFT JOIN year_groups y ON y.id = e.yeargroupid
    LEFT JOIN classes c ON c.id = e.classid
    LEFT JOIN test_Students s ON s.id = e.studentid
    LEFT JOIN teachers t ON t.id = c.teacherid
    WHERE t.email = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $login_email);  // Bind teacher's email to the query
$stmt->execute();
$studentsResult = $stmt->get_result();

// Step 2: Handle attendance marking and storing in database
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $attendance = $_POST['attendance']; // Get the attendance data from the form
    $attendance_date = $_POST['attendance_date']; // Get the selected date

    foreach ($attendance as $studentId => $status) {
        // Check if the attendance already exists for this student and date
        $checkSql = "SELECT id FROM attendance WHERE studentid = ? AND date = ?";
        $checkStmt = $conn->prepare($checkSql);
        $checkStmt->bind_param('is', $studentId, $attendance_date);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();

        if ($checkResult->num_rows > 0) {
            // Update existing attendance record
            $updateSql = "UPDATE attendance SET status = ? WHERE studentid = ? AND date = ?";
            $updateStmt = $conn->prepare($updateSql);
            $updateStmt->bind_param('sis', $status, $studentId, $attendance_date);
            $updateStmt->execute();
        } else {
            // Insert new attendance record
            $insertSql = "INSERT INTO attendance (studentid, date, status) VALUES (?, ?, ?)";
            $insertStmt = $conn->prepare($insertSql);
            $insertStmt->bind_param('iss', $studentId, $attendance_date, $status);
            $insertStmt->execute();
        }
    }

    // Redirect to a confirmation page or display success message
    header('Location: view-attendance.php');
    exit;
}

include('_header.php');
?>

<main class="py-5">
    <div class="container" style="display: grid;grid-template-columns: 300px auto; column-gap: 10px;background-color: var(--darkgrey);">
        <div><?php include('_sidenav.php'); ?></div>           
        <div class="p-4">
            <h2 class="page-title">Mark Attendance</h2>
            <br>
            <form method="POST">
                <div class="form-group">
                    <div class="form-group">
                        <label for="attendance_date">Attendance Date</label>
                        <input type="date" id="attendance_date" name="attendance_date" required>
                    </div>
                    <label for="classid">Class</label>
                    <select id="classid" name="classid" class="form-control">
                        <option value="" disabled selected>Select Class</option>
                        <?php
                        // Fetch classes for the logged-in teacher
                        $classSql = "SELECT id, classcode FROM classes WHERE teacherid = (SELECT id FROM teachers WHERE email = ?)";
                        $classStmt = $conn->prepare($classSql);
                        $classStmt->bind_param('s', $login_email);
                        $classStmt->execute();
                        $classResult = $classStmt->get_result();

                        while ($class = $classResult->fetch_assoc()) {
                            echo "<option value='" . $class['id'] . "'>" . $class['classcode'] . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <!-- Step 4: Display students and allow attendance marking -->
                <div class="form-group">
                    <h4>Students</h4>
                    <table class="table table-dark table-bordered">
                        <?php if ($studentsResult->num_rows > 0): ?>
                            <?php while ($student = $studentsResult->fetch_assoc()): ?>
                                <tr>
                                    <td>
                                        <!-- Present radio button -->
                                        <input class="form-check-input" type="radio" name="attendance[<?php echo $student['studentid']; ?>]" value="Present" id="present-<?php echo $student['studentid']; ?>" required>
                                        <label class="form-check-label" for="present-<?php echo $student['studentid']; ?>">
                                            <?php echo htmlspecialchars($student['fname'] . ' ' . $student['lname']); ?> - Present
                                        </label>
                                    </td>
                                    <td>
                                        <!-- Absent radio button -->
                                        <input class="form-check-input" type="radio" name="attendance[<?php echo $student['studentid']; ?>]" value="Absent" id="absent-<?php echo $student['studentid']; ?>">
                                        <label class="form-check-label" for="absent-<?php echo $student['studentid']; ?>">
                                            <?php echo htmlspecialchars($student['fname'] . ' ' . $student['lname']); ?> - Absent
                                        </label>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <p>No students found for your class.</p>
                        <?php endif; ?>
                    </table>
                </div>
                <button type="submit" class="btn btn-success">Submit Attendance</button>
            </form>
        </div>    
    </div>
</main>

<?php include('_footer.php'); ?>
