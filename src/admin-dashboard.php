<?php
include_once('db_connection.php');
include('checkSession.php');

include('_header.php');
?>

<main class="py-5">
    <div class="container" style="display: grid;grid-template-columns: 300px auto; column-gap: 10px;background-color: var(--darkgrey);">
        <div><?php include('_sidenav.php'); ?></div>           
        <div class="p-4">
            <div class="p-4 text-center">
                <h2 class="page-title">Welcome Back <?php echo $login_fname.'!'?></h2>
                <img style="opacity: .5;width:70%" src="img/Attendum Logo wide.png">
            </div>
        </div>    
    </div>
</main>

<?php include('_footer.php'); ?>
