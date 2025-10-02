<?php
include 'admin_header.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <div class="content-duplicate p-3" data-aos="fade-up-left" id="mainContentDuplicate">
        <h2>Welcome , </h2>

        <div class="row">
            <div class="col-md-4 my-3">
                <div class="card text-center">
                    <div class="card-body d-flex justify-content-around align-items-center">
                        <i class="bi bi-list-task fs-3"></i>
                        <h1>|</h1>
                        <h5 class="card-title mb-0">Categories</h5>
                        <?php
                        $sql = "SELECT COUNT(*) AS category_count FROM categories"; // Change 'categories' to your table name
                        $result = $conn->query($sql);
                        $row = $result->fetch_assoc();
                        $categoryCount = $row['category_count'];
                        ?>
                    </div>
                    <p class="card-text">There are <?= $categoryCount ?> different categories.</p>
                </div>
            </div>
            <div class="col-md-4 my-3">
                <div class="card text-center">
                    <div class="card-body d-flex justify-content-around align-items-center">
                        <i class="bi bi-people fs-3"></i>
                        <h1>|</h1>
                        <h5 class="card-title mb-0">Clubs</h5>
                        <?php
                        $sql1 = "SELECT COUNT(*) AS club_count FROM club"; // Change 'categories' to your table name
                        $result1 = $conn->query($sql1);
                        $row1 = $result1->fetch_assoc();
                        $clubCount = $row1['club_count'];
                        ?>
                    </div>
                    <p class="card-text">There are a total of <?= $clubCount ?> clubs.</p>
                </div>
            </div>
            <div class="col-md-4 my-3">
                <div class="card text-center">
                    <div class="card-body d-flex justify-content-around align-items-center">
                        <i class="bi bi-person-check fs-3"></i>
                        <h1>|</h1>
                        <h5 class="card-title mb-0">Users</h5>
                    </div>
                    <?php
                    $sql = "SELECT COUNT(*) AS user_count FROM user"; // Change 'categories' to your table name
                    $result = $conn->query($sql);
                    $row = $result->fetch_assoc();
                    $userCount = $row['user_count'];
                    ?>
                    <p class="card-text">There are <?= $userCount ?> registered users.</p>
                </div>
            </div>
            <div class="col-md-4 my-3">
                <div class="card text-center">
                    <div class="card-body d-flex justify-content-around align-items-center">
                        <i class="bi bi-calendar-event fs-3"></i>
                        <h1>|</h1>
                        <h5 class="card-title mb-0">Event Requests</h5>
                    </div>
                    <?php
                    $sql1 = "SELECT COUNT(*) AS events_count FROM events WHERE status = 'active'"; // Change 'categories' to your table name
                    $result1 = $conn->query($sql1);
                    $row1 = $result1->fetch_assoc();
                    $eventCount = $row1['events_count'];
                    ?>
                    <p class="card-text"> There Are total <?= $eventCount ?> new Approved event .</p>
                </div>
            </div>
            
            <div class="w-100 p-3">
                <div class="main my-5 p-2">
                    <h2>Event Approval</h2>
                    <table class="table" id="myTable">
                        <?php
                        if (isset($_GET['reject_id'])) {
                        ?>
                            <thead>
                                <tr>
                                    <th>Sr no.</th>
                                    <th>Event Name</th>
                                    <th>Reason of Rejection</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $result = $conn->query("SELECT * FROM events WHERE `status` = 'inactive' ");
                                while ($row = $result->fetch_assoc()) {
                                ?>
                                    <tr>
                                        <form action="" method="post">
                                            <td><?= $row['id'] ?></td>
                                            <td><?= $row['e_name'] ?></td>
                                            <td><textarea name="reason" id="" class="form-control"></textarea></td>
                                            <td><input type="submit" name="reject" value="Submit" class="button"></td>
                                        </form>
                                    </tr>
                                <?php
                                }
                                ?>
                            </tbody>
                        <?php
                        } else {

                        ?>
                            <thead>
                                <tr>
                                    <th>Sr no.</th>
                                    <th>Event Name</th>
                                    <th>Description</th>
                                    <th>Club Name</th>
                                    <th>Head Email</th>
                                    <th>Link</th>
                                    <th>Date</th>
                                    <th>Image</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $result = $conn->query("SELECT * FROM events WHERE `status` = 'inactive' ");

                                while ($row = $result->fetch_assoc()) {
                                ?>
                                    <tr>
                                        <td><?= $row['id'] ?></td>
                                        <td><?= $row['e_name'] ?></td>
                                        <td><?= $row['e_des'] ?></td>
                                        <td><?= $row['club_name'] ?></td>
                                        <td><?= $row['head_email'] ?></td>
                                        <td>
                                            <a href="<?= (strpos($row['link'], 'http') === 0) ? $row['link'] : 'https://' . $row['link']; ?>"
                                                target="_blank" class="a">
                                                <?= $row['link'] ?>
                                            </a>
                                        </td>
                                        <td><?= date("d-m-Y", strtotime($row['date'])) ?></td>
                                        <td><img src="uploads/<?= $row['img'] ?>" style="height: 100px; width: 150px;"></td>
                                        <td><?= $row['status'] ?></td>
                                        <td>
                                            <a href="?approve_id=<?= $row['id'] ?>"><button class="button my-2">Accept</button></a> |
                                            <a href="?reject_id=<?= $row['id'] ?>"><button class="button my-2">Reject</button></a>
                                        </td>
                                    </tr>
                            <?php }
                            }
                            ?>
                            </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>

</html>

<?php

if (isset($_GET['approve_id'])) {
    $id = $_GET['approve_id'];
    $upd  = "UPDATE `events` SET `status`='active' WHERE id = '$id' ";
    if ($conn->query($upd)) {

        $sql = "SELECT * FROM events WHERE id = $id";

        $res = $conn->query($sql)->fetch_assoc();

        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'stank729@rku.ac.in'; // Replace with your Gmail address
        $mail->Password = 'PASSWORD IS UPDATED'; // Replace with your app password
        $mail->SMTPSecure = 'ssl'; // Use 'tls' for Port 587
        $mail->Port = 465;

        // Recipients
        $mail->setFrom('stank729@rku.ac.in');
        $mail->addAddress($res['head_email']);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Event Approval';
        $mail->Body = "
<h2>Congratulations </h2>
<p>Your Event approval Request is successfully Accepted:</p>

<p>Event Name: <strong>{$res['e_name']} </strong></p>
<p>Club Name: <strong>{$res['club_name']} </strong></p>
<p>Link: <strong>{$res['link']} </strong></p>
<p>Date: <strong>{$res['date']} </strong></p>

<h2>Thank You! </h2>
";

        $mail->send();
    }
    echo "Mail is Sent";
    echo "<script>window.location.href = 'admin_dashboard.php'</script>";
}

if (isset($_POST['reject'])) {
    $id = $_GET['reject_id'];
    $reason = $_POST['reason'];

    $sql = "SELECT * FROM events WHERE id = $id";
    $res = $conn->query($sql)->fetch_assoc();

    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'stank729@rku.ac.in'; // Replace with your Gmail address
    $mail->Password = 'PASSWORD IS UPDATED'; // Replace with your app password
    $mail->SMTPSecure = 'ssl'; // Use 'tls' for Port 587
    $mail->Port = 465;

    // Recipients
    $mail->setFrom('stank729@rku.ac.in');
    $mail->addAddress($res['head_email']);

    // Content
    $mail->isHTML(true);
    $mail->Subject = 'Event Approval';
    $mail->Body = "
<h2>Sorry </h2>
<p>Your Event approval Request is Rejected:</p>

<p>Event Name: <strong>{$res['e_name']} </strong></p>
<p>Club Name: <strong>{$res['club_name']} </strong></p>
<p>Link: <strong>{$res['link']} </strong></p>
<p>Date: <strong>{$res['date']} </strong></p>

<h3>Because of  <strong>$reason</strong></h3>

<h2>Thank You! </h2>
";
    $mail->send();

    $del = "DELETE FROM events WHERE id = $id";
    $conn->query($del);
    $file = 'uploads/' . $res['img'];
    unlink($file);
    echo "<script>window.location.href = 'admin_dashboard.php'</script>";
    echo $reason . $reason . $reason . $reason . $reason . $reason . $id;
}


?>