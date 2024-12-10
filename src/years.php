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
                                    <a class="btn btn-danger">Delete</a>
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



<?php include('_footer.php'); ?>
