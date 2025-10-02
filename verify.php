<?php
include_once('admin_header.php'); // Database connection
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Verification</title>
</head>

<body>
    <div class="content-duplicate p-5" id="mainContentDuplicate">
        <?php
        if (!empty($_REQUEST['admin_email']) && !empty($_REQUEST['token']) || !empty($_REQUEST['head_email']) && !empty($_REQUEST['token'])) {
            
            if ($_REQUEST['admin_email']) {
                $email = $_REQUEST['admin_email'];
            }else{
                $email = $_REQUEST['head_email'];
            }
            
            $token = $_REQUEST['token'];

            // Prepared statement to prevent SQL injection
            $stmt = $conn->prepare("SELECT * FROM `admin` WHERE `admin_email` = ? AND `token` = ?");
            $stmt->bind_param("ss", $email, $token);
            $stmt->execute();
            $admin_result = $stmt->get_result();

            $stmt = $conn->prepare("SELECT * FROM `club` WHERE `head_email` = ? AND `token` = ?");
            $stmt->bind_param("ss", $email, $token);
            $stmt->execute();
            $club_result = $stmt->get_result();

            $stmt = $conn->prepare("SELECT * FROM `global_people` WHERE `email` = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $global_result = $stmt->get_result();

            if (($admin_result->num_rows > 0 || $club_result->num_rows > 0) && $global_result->num_rows > 0) {
                $global_row = $global_result->fetch_assoc();
                $role = $global_row['role'];
                $status_global = $global_row['status'];
                
                if ($admin_result->num_rows > 0) {
                    $admin_row = $admin_result->fetch_assoc();
                    $status_admin = $admin_row['status'];
                } elseif ($club_result->num_rows > 0) {
                    $club_row = $club_result->fetch_assoc();
                    $status_admin = $club_row['status'];
                }
                
                if ($status_admin === 'inactive' && $status_global === 'inactive') {
                    if ($role === 'Head') {
                        $update_stmt = $conn->prepare("UPDATE `club` SET `status` = 'active', `token` = NULL WHERE `head_email` = ?");
                    } else {
                        $update_stmt = $conn->prepare("UPDATE `admin` SET `status` = 'active', `token` = NULL WHERE `admin_email` = ?");
                    }
                    $update_stmt->bind_param("s", $email);
                    
                    $update_global_stmt = $conn->prepare("UPDATE `global_people` SET `status` = 'active' WHERE `email` = ?");
                    $update_global_stmt->bind_param("s", $email);
                    
                    if ($update_stmt->execute() && $update_global_stmt->execute()) {
                        echo "Account successfully verified!";
                    } else {
                        echo "Error verifying account. Please try again.";
                    }
                } else {
                    echo "This account is already verified.";
                }
            } else {
                echo "Invalid verification link or token.";
            }
        } else {
            echo "Invalid request. Email or token missing.";
        }
        
        $conn->close();
        ?>
    </div>
</body>

</html>
