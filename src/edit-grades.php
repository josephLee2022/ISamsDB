<?php
include_once('db_connection.php');
$conn = getDatabaseConnection();
include('checkSession.php');

// Check if ID is passed
if (!isset($_GET['id'])) {
    header('location:/student-grades.php');
    exit;
}

$id = $_GET['id'];
$gradeData = null;
$error = '';
$studentid = '';

// Fetch the grade data
$sql = "
    SELECT 
        m.id, 
        m.enrollmentid, 
        m.subjectid, 
        m.term1, 
        m.term2, 
        m.term3, 
        m.Exam, 
        s.id as stuid, 
        s.fname, 
        s.lname, 
        sub.subject
    FROM `student_grades` m
    LEFT JOIN enrollments e ON e.id = m.enrollmentid
    LEFT JOIN test_Students s ON s.id = e.studentid
    LEFT JOIN subjects sub ON sub.id = m.subjectid
    WHERE m.id = ?
";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();


if ($result->num_rows > 0) {
    $gradeData = $result->fetch_assoc();
    $studentid = $gradeData['enrollmentid'];
} else {
    $error = "No grade record found!";
}

// Handle form submission to update the grades
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $term1 = $_POST['term1'];
    $term2 = $_POST['term2'];
    $term3 = $_POST['term3'];
    $exam = $_POST['exam'];

    // Update grades in the database
    $updateSql = "
        UPDATE `student_grades`
        SET term1 = ?, term2 = ?, term3 = ?, Exam = ?
        WHERE id = ?
    ";
    $updateStmt = $conn->prepare($updateSql);
    $updateStmt->bind_param('dddii', $term1, $term2, $term3, $exam, $id);
    if ($updateStmt->execute()) {
        header('location:student-grades.php?id='.$studentid); // Redirect to the original page
        exit;
    } else {
        $error = "Failed to update the grades!";
    }
}

include('_header.php');
?>

<main class="py-5">
    <div class="container" style="display: grid;grid-template-columns: 300px auto; column-gap: 10px;background-color: var(--darkgrey);">
        <div><?php include('_sidenav.php'); ?></div>           
        <div class="p-4">
            <h2 class="page-title">Edit Grades</h2>
            <br>
            <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo ($error); ?></div>
            <?php endif; ?>
            <?php if ($gradeData): ?>
                <form method="POST">
                    <div>
                        <label for="term1" class="form-label">Term 1</label>
                        <input type="text" id="term1" name="term1" value="<?php echo ($gradeData['term1']); ?>" >
                    </div>
                    <hr>
                    <div>
                        <label for="term2" class="form-label">Term 2</label>
                        <input type="text" id="term2" name="term2" value="<?php echo ($gradeData['term2']); ?>" >
                    </div>
                    <hr>
                    <div>
                        <label for="term3" class="form-label">Term 3</label>
                        <input type="text" id="term3" name="term3" value="<?php echo ($gradeData['term3']); ?>" >
                    </div>
                    <hr>
                    <div>
                        <label for="exam" class="form-label">Exam</label>
                        <input type="text" id="exam" name="exam" value="<?php echo ($gradeData['Exam']); ?>" >
                    </div>
                    <hr>
                    <button type="submit" class="btn btn-success" onclick="return validateGrades()">Update</button>
                    <a href="student-grades.php?id=<?php echo $studentid ?>" class="btn btn-secondary">Cancel</a>
                </form>
            <?php endif; ?>
        </div>    
    </div>
</main>

<script>
    function validateGrades() {
    // Get form input values
    const term1 = document.getElementById('term1').value.trim();
    const term2 = document.getElementById('term2').value.trim();
    const term3 = document.getElementById('term3').value.trim();
    const exam = document.getElementById('exam').value.trim();

    // Regex to validate numbers between 0-100, including decimals
    const numberRegex = /^(100(\.0{1,2})?|[0-9]{1,2}(\.[0-9]{1,2})?)$/;

    // Validate each field
    if (term1 !== '' && !numberRegex.test(term1)) {
        alert('Term 1 must be a number between 0 and 100, including decimals (e.g., 95 or 95.5).');
        return false;
    }
    if (term2 !== '' && !numberRegex.test(term2)) {
        alert('Term 2 must be a number between 0 and 100, including decimals (e.g., 95 or 95.5).');
        return false;
    }
    if (term3 !== '' && !numberRegex.test(term3)) {
        alert('Term 3 must be a number between 0 and 100, including decimals (e.g., 95 or 95.5).');
        return false;
    }
    if (exam !== '' && !numberRegex.test(exam)) {
        alert('Exam must be a number between 0 and 100, including decimals (e.g., 95 or 95.5).');
        return false;
    }

    // If all validations pass
    return true;
}
</script>

<?php include('_footer.php'); ?>
