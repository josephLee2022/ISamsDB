<?php
include_once('db_connection.php');
$conn = getDatabaseConnection();
include('checkSession.php');

$user = $_SESSION['user_type'];

// Redirect if no ID is passed
if (!isset($_GET['id'])) {
    header('location:/mystudents.php');
    exit;
}

$id = $_GET['id'];

// Initialize variables
$fname = '';
$lname = '';
$grades = [];

// Fetch student grades and student details
$sql = "
    SELECT 
        m.`id`, 
        m.`enrollmentid`, 
        m.`subjectid`, 
        m.`term1`, 
        m.`term2`, 
        m.`term3`, 
        m.`Exam`,
        e.studentid,
        s.fname, 
        s.lname, 
        sub.subject
    FROM `student_grades` m
    LEFT JOIN enrollments e ON e.id = m.enrollmentid
    LEFT JOIN test_Students s ON s.id = e.studentid
    LEFT JOIN subjects sub ON sub.id = m.subjectid
    WHERE m.enrollmentid = ?
";

;
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $grades = $result->fetch_all(MYSQLI_ASSOC);
    $fname = $grades[0]['fname'];
    $lname = $grades[0]['lname'];
} else {
    // Handle case where no grades are found (optional: log or notify user)
}

include('_header.php');
?>

<main class="py-5">
    <div class="container" style="display: grid; grid-template-columns: 300px auto; column-gap: 10px; background-color: var(--darkgrey);">
        <!-- Sidebar -->
        <div><?php include('_sidenav.php'); ?></div>

        <!-- Main Content -->
        <div class="p-4">
            <h2 class="page-title"><?php echo htmlspecialchars($fname . ' ' . $lname); ?></h2>
            <br>

            <!-- Grades Table -->
            <h3>Grades:</h3>
            <table id="subjectsTable" class="table table-dark table-bordered table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Subject</th>
                        <th>Term 1</th>
                        <th>Term 2</th>
                        <th>Term 3</th>
                        <th>Exam</th>
                        <th>
                            <?php if ($user == 'Teacher'): ?>
                            Action
                            <?php endif; ?>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $totalSum = 0; // To sum up all valid grades
                    $totalCount = 0; // To count all valid grade entries

                    if (!empty($grades)): ?>
                        <?php foreach ($grades as $grade): 
                            // Filter out null values from the grades array
                            $subjectGrades = array_filter([$grade['term1'], $grade['term2'], $grade['term3'], $grade['Exam']], function($value) {
                                return $value !== null;
                            });

                            // Calculate the average for this subject if there are valid grades
                            $subjectAverage = !empty($subjectGrades) ? array_sum($subjectGrades) / count($subjectGrades) : 0;

                            // Add to total sum and count for overall average
                            $totalSum += array_sum($subjectGrades);
                            $totalCount += count($subjectGrades);
                        ?>
                            <tr>
                                <td><?php echo ($grade['id']); ?></td>
                                <td><?php echo ($grade['subject']); ?></td>
                                <td><?php echo ($grade['term1'] ?? ''); ?></td>
                                <td><?php echo ($grade['term2'] ?? ''); ?></td>
                                <td><?php echo ($grade['term3'] ?? ''); ?></td>
                                <td><?php echo ($grade['Exam'] ?? ''); ?></td>
                                <td>
                                    <?php if ($user == 'Teacher'): ?>
                                        <a class="btn btn-info" href="edit-grades.php?id=<?php echo $grade['id'];?>">View Grades</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center">No Grades Found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <!-- Overall Average Calculation -->
            <h3>Average:</h3>
            <p>
                <?php 
                if ($totalCount > 0) {
                    $overallAverage = $totalSum / $totalCount;
                    echo "The overall average of all grades is: " . number_format($overallAverage, 2);
                } else {
                    echo "No valid grades available to calculate the average.";
                }
                ?>
            </p>
            <!-- Add PDF generation button -->
            <a href="generate-pdf.php?id=<?php echo $id; ?>" class="btn btn-primary">Download Grades PDF</a>

        </div>
    </div>
</main>



<?php include('_footer.php'); ?>
