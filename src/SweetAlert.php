<?php
class SweetAlert {
    public static function success($message) {
        echo "<script>
            Swal.fire({
                title: 'Success!',
                text: '$message',
                icon: 'success',
                confirmButtonText: 'OK'
            });
        </script>";
    }

    public static function error($message) {
        echo "<script>
            Swal.fire({
                title: 'Error!',
                text: '$message',
                icon: 'error',
                confirmButtonText: 'Try Again'
            });
        </script>";
    }

    public static function info($message) {
        echo "<script>
            Swal.fire({
                title: 'Information',
                text: '$message',
                icon: 'info',
                confirmButtonText: 'OK'
            });
        </script>";
    }
}

?>