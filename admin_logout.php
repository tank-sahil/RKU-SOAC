<?php
session_unset(); // Clear all session variables
session_destroy(); // Destroy the session
?>
<script>
    window.location.href = "user_sign_in.php";
</script>
<?php
exit();

?>