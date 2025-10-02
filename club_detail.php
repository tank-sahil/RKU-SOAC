<?php
include_once('header.php');

$id = $_REQUEST['id'];

$sel = "SELECT * FROM club WHERE id = $id";
$row = $conn->query($sel)->fetch_assoc();

$sql = "SELECT * FROM categories WHERE id = {$row['category_id']}";
$cat_row = $conn->query($sql)->fetch_assoc();

$select = "SELECT * FROM club_details WHERE `club_id` = '$id'";
$club_row = $conn->query($select)->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <section class="club_details">
        <div class="container my-5">
            <div class="row">
                <!-- Image Section -->
                <div class="col-md-6">
                    <img src="club_images/<?= $row['club_img']; ?>" style="height: 400px;width: 750px;" class="img-fluid rounded shadow" >
                </div>

                <!-- Details Section -->
                <div class="col-md-6">
                    <h4 class="mb-3">Organization Name : <strong><?= $row['club_name']; ?></strong></h4>
                    <h4 class="text-muted">Organization Type : <b> <?= $cat_row['name']; ?></b></h4>

                    <h4 class="text-muted">Organization Objectives :
                        <p class="lead">
                            <?= $row['club_des']; ?> </p>
                        </p>
                        <h4 class="text-muted">Faculty Advisor : <b> <?= $row['head_name']; ?></b></h4>
                        <h4 class="text-muted">Coordinator :
                            <b> <?= $club_row['coordinators']; ?></b><br>
                        </h4>

                        <a href="user_Register.php"><button class="button">Register</button></a>
                </div>
            </div>
            <br>
            <hr>
            <!-- Additional Information -->
            <div class="mt-5">

            </div>

            <!-- Gallery -->

            <!-- Achievemets  and Criteria-->

            <div class="container my-5">
                <h3>Rules and Guidelines</h3>

                <p><?= $club_row['Rules & Guidelines'] ?? 'Not Available'; ?></p>
                </p>
                <div class="row">
                    <!-- Image Section -->
                    <div class="col-md-6">
                        <h3>Achievements</h3>
                        <p><?= $club_row['Achievements'] ?? 'No achievements listed.'; ?></p>

                    </div>

                    <!-- Details Section -->
                    <div class="col-md-6">
                        <h3>Criteria to Join organization</h3>

                        <p><?= $club_row['Criteria to Join organization'] ?? 'No criteria specified.'; ?></p>
                        </p>
                    </div>
                </div>

                <div class="mt-5">
                    <h3>Gallery</h3>
                    <div class="row">
                        <?php
                        $sel = "SELECT * FROM clubgallery WHERE club_id = '$id'";
                        $res = $conn->query($sel);
                        while ($row = $res->fetch_assoc()) {
                        ?>
                            <div class="col-md-2">
                                <img src="gallery/<?= $row['file_name']  ?>" class="img-fluid rounded mb-3" style="height: 128px;width: 300px;"
                                    alt="<?= $row['file_name']  ?>">
                            </div>
                        <?php
                        }
                        ?>
                    </div>
                </div>
                <!-- Team member -->
                <div class="mt-5">
                    <h3>Team Member</h3>
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">First Name</th>
                                <th scope="col">Last Name</th>
                                <th scope="col">Email Id</th>
                                <th scope="col">Department</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                            $user = "SELECT * FROM `user` WHERE club = $id";
                            while ($user_row = $conn->query($user)->fetch_array()) {
                            ?>
                                <tr>
                                    <th scope="row">1</th>
                                    <td><?= $user_row['fname'] ?> </td>
                                    <td><?= $user_row['lname'] ?></td>
                                    <td><?= $user_row['email'] ?></td>
                                    <td><?= $user_row['dept'] ?></td>
                                </tr>
                            <?php
                            } ?>
                        </tbody>
                    </table>
                </div>
            </div>


    </section>
</body>

</html>

<?php
include_once('footer.php');
?>