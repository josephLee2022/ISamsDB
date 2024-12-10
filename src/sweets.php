<?php if (!empty($successMessage)): ?>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: '<?php echo $successMessage; ?>',
            confirmButtonText: 'OK'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '<?php echo $redirectUrl; ?>';  // Redirect after SweetAlert
            }
        });
    </script>
<?php elseif (!empty($errorMessage)): ?>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: '<?php echo $errorMessage; ?>',
            confirmButtonText: 'OK'
        }).then((result) => {
            if (result.isConfirmed) {
                window.history.back();
            }
        });
    </script>
<?php endif; ?>