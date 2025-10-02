<?php
include_once('admin_header.php');

// // Check if admin_id is provided
// if (isset($_GET['admin_id'])) {
//     $admin_id = intval($_GET['admin_id']); // Sanitize input
//     echo "Admin ID to delete: " . $admin_id;

//     // Perform delete operation
//     $delete_sql = "DELETE FROM `admin` WHERE `admin_id` = $admin_id";
//     if ($conn->query($delete_sql)) {
//         echo "Admin deleted successfully.";
//     } else {
//         echo "Error deleting admin: " . $conn->error;
//     }
// } else {
//     echo "Error: No admin ID provided.";
// }
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <div class="content-duplicate" id="mainContentDuplicate">
        <?php $id = $_REQUEST['admin_id'];

        echo $id;

        $del = "DELETE FROM `admin` WHERE admin_id = '$id'";
        if ($conn->query($del)) {
        ?>

            <script>
                window.location.href = "add_admin.php?view=view";
            </script>

        <?php
        }
        ?>
    </div>
</body>

</html>