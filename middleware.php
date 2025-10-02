<?php

include_once('admin_header.php');

if (isset($_SESSION['role']) != 'Admin' && isset($_SESSION['role']) != 'Head') {
?>
    <script>
        window.location.href = 'user_sign_in.php';
    </script>

<?php
}

?>