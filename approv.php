<?php

include_once('admin_header.php');

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
        <div class="w-100 p-3">
            <?php
            if (isset($_REQUEST['approv_id'])) {
                $row = $conn->query("SELECT * FROM user WHERE id = {$_REQUEST['approv_id']}")->fetch_assoc();
                $club_row = $conn->query("SELECT * FROM club WHERE id = {$row['club']}")->fetch_assoc();

                if ($row['status'] != 'Approv') {
                    if ($conn->query("UPDATE `user` SET `status`='Approv' WHERE id = {$_REQUEST['approv_id']}")) {


                        $mail->isSMTP();
                        $mail->Host = 'smtp.gmail.com';
                        $mail->SMTPAuth = true;
                        $mail->Username = 'stank729@rku.ac.in'; // Replace with your Gmail address
                        $mail->Password = 'PASSWORD IS UPDATED'; // Replace with your app password
                        $mail->SMTPSecure = 'ssl'; // Use 'tls' for Port 587
                        $mail->Port = 465;

                        // Recipients
                        $mail->setFrom('stank729@rku.ac.in');
                        $mail->addAddress($row['email']);

                        // Content
                        $mail->isHTML(true);
                        $mail->Subject = 'Approval';
                        $mail->Body = "<h2>Congratulations, {$row['fname']} {$row['lname']},</h2>
                        <h3>You are approved for {$club_row['club_name']} Team!</h3>";

                        // Send email
                        $mail->send();
                        echo "Mail is Sent";
                    }
            ?>
                    <script>
                        window.location.href = "admin_userApproval.php?approval=";
                    </script>
                <?php
                }
            } else {
                echo "Error: An User with this Club already Approved.";
                ?>
                <a href="admin_userApproval.php"><button class="button"><- GO BACK</button></a>
                <?php
            }


            if (isset($_REQUEST['reject_id'])) {

                $row = $conn->query("SELECT * FROM user WHERE id = {$_REQUEST['reject_id']}")->fetch_assoc();
                $club_row = $conn->query("SELECT * FROM club WHERE id = {$row['club']}")->fetch_assoc();

                if ($row['status'] != 'Reject') {
                    if ($conn->query("UPDATE `user` SET `status`='Reject' WHERE id = {$_REQUEST['reject_id']}")) {

                        $mail->isSMTP();
                        $mail->Host = 'smtp.gmail.com';
                        $mail->SMTPAuth = true;
                        $mail->Username = 'stank729@rku.ac.in'; // Replace with your Gmail address
                        $mail->Password = 'PASSWORD IS UPDATED'; // Replace with your app password
                        $mail->SMTPSecure = 'ssl'; // Use 'tls' for Port 587
                        $mail->Port = 465;

                        // Recipients
                        $mail->setFrom('stank729@rku.ac.in');
                        $mail->addAddress($row['email']);

                        // Content
                        $mail->isHTML(true);
                        $mail->Subject = 'Approval';
                        $mail->Body = "<h2>Sorry , {$row['fname']} {$row['lname']},</h2>
                        <h3>You are Not Approved for {$club_row['club_name']} Team!</h3>";
                        // Send email
                        $mail->send();
                    }
                ?>
                    <script>
                        window.location.href = "admin_userapprovalal.php?reject=";
                    </script>
                <?php
                }
            } else {
                echo "Error: An User with this Club already Rejected.";

                ?>
                <a href="admin_userApproval.php"><button class="button"><- GO BACK</button></a>
            <?php
            }
            ?>
        </div>
    </div>
</body>

</html>