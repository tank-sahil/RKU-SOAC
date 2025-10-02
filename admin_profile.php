<?php
include_once('admin_header.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
</head>

<body>
    <div class="content-duplicate" data-aos="fade-up-left" id="mainContentDuplicate">
        <div class="w-100 p-3">
            <div class="row">
                <div class="col-md-6 text-center">
                    <?php
                    if ($_SESSION['role'] == 'Admin') {
                        $sql = "SELECT * FROM `admin` WHERE admin_email = '$email' ";
                        $res = $conn->query($sql)->fetch_assoc();
                        $img = $res['image'];

                    ?>
                        <img src="images/<?php echo $img ?>" style="object-fit: cover; height: 300px; width: 300px; border-radius:50px" alt="images/<?php echo $img ?>">

                    <?php
                    } elseif ($_SESSION['role'] == 'Head') {
                        $sql = "SELECT * FROM club WHERE head_email = '$email' AND id = {$_SESSION['club_id']} ";
                        $res_head = $conn->query($sql)->fetch_assoc();
                        $img = $res_head['image'];
                    ?>
                        <img src="images/<?php echo $img ?>" style="object-fit: cover; height: 300px; width: 300px; border-radius:50px" alt="images/<?php echo $img ?>">

                    <?php
                    } else {
                        echo "Invalide Role";
                    }
                    ?>
                </div>

                <div class="col-md-6 my-3">
                    <?php
                    if (isset($_GET['edit'])) {
                        if ($_SESSION['role'] == 'Admin') {
                    ?>
                            <form method="POST" action="" enctype="multipart/form-data">
                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <td>
                                                <h5>Name</h5>
                                            </td>
                                            <td><input type="text" name="name" value="<?= $res['admin_name'] ?>" class="form-control"></td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <h5>Profile Image</h5>
                                            </td>
                                            <td><input type="file" name="img" class="form-control" value="<?= $res['image'] ?>"></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <br>
                                <button type="submit" class="button" name="save">Save Changes</button>
                            </form>
                        <?php
                        } elseif ($_SESSION['role'] == 'Head') {
                        ?>

                            <form method="POST" action="" enctype="multipart/form-data">
                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <td>
                                                <h5>Name</h5>
                                            </td>
                                            <td><input type="text" name="name" value="<?= $res_head['head_name'] ?>" class="form-control"></td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <h5>Profile Image</h5>
                                            </td>
                                            <td><input type="file" name="img" class="form-control" value="<?= $res_head['image'] ?>"></td>
                                        </tr>
                                    </tbody>
                                </table>
                                <br>
                                <button type="submit" class="button" name="save">Save Changes</button>
                            </form>
                        <?php
                        }
                    }
                    // Change Password 
                    elseif (isset($_GET['CP'])) {

                        $check = "SELECT * FROM global_people WHERE email = '$email'";
                        $res_CP = $conn->query($check)->fetch_assoc();

                        ?>
                        <form method="POST" action="">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <td>
                                            <h5>Old Password</h5>
                                        </td>
                                        <td><input type="text" name="Opassword" class="form-control"></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <h5>New Password</h5>
                                        </td>
                                        <td><input type="text" name="Npassword" class="form-control"></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <h5>New Confirm Password</h5>
                                        </td>
                                        <td><input type="text" name="NCpassword" class="form-control"></td>
                                    </tr>
                                </tbody>
                            </table>
                            <br>
                            <button type="submit" class="button" name="Change">Change Password</button>
                        </form>
                        <?php

                        if (isset($_POST['Change'])) {
                            $OPass = $_POST['Opassword'];
                            $NPass = $_POST['Npassword'];
                            $NCPass = $_POST['NCpassword'];
                            if ($res_CP['password'] === $OPass) {

                                if (in_array($_SESSION['role'], ['Admin', 'Head'])) {

                                    $upd_pass1 = ($_SESSION['role'] == 'Head')
                                        ? "UPDATE `club` SET `password`='$NPass' WHERE head_email = '$email'"
                                        : "UPDATE `admin` SET `admin_password`='$NPass' WHERE admin_email = '$email'";
                                } else {
                                    echo "Invalid Role";
                                }
                                $upd_pass = "UPDATE `global_people` SET `password`='$NPass' WHERE email = '$email'";
                                $conn->query($upd_pass);
                                $conn->query($upd_pass1);
                                echo '<script>window.location.href = "admin_profile.php";</script>';
                            } else {
                                echo "Error : Your Old Password is Wrong";
                            }
                        }
                    } else {
                        ?>
                        <table class="table">
                            <?php
                            if ($_SESSION['role'] == 'Admin') {
                                $sql = "SELECT * FROM `admin` WHERE admin_email = '$email' ";
                                $res = $conn->query($sql)->fetch_assoc();
                            ?>
                                <tbody>
                                    <tr>
                                        <td>
                                            <h5>Name</h5>
                                        </td>
                                        <td>
                                            <h5><?= $res['admin_name'] ?></h5>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <h5>Email</h5>
                                        </td>
                                        <td>
                                            <h5><?= $res['admin_email'] ?></h5>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <h5>Role</h5>
                                        </td>
                                        <td>
                                            <h5>Admin</h5>
                                        </td>
                                    </tr>
                                </tbody>
                            <?php

                            } elseif ($_SESSION['role'] == 'Head') {

                                $sql = "SELECT * FROM club WHERE head_email = '$email' AND id = {$_SESSION['club_id']} ";
                                $res_head = $conn->query($sql)->fetch_assoc();
                            ?>
                                <tbody>
                                    <tr>
                                        <td>
                                            <h5>Name</h5>
                                        </td>
                                        <td>
                                            <h5><?php echo $res_head['head_name'] ?></h5>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <h5>Email</h5>
                                        </td>
                                        <td>
                                            <h5><?php echo $res_head['head_email'] ?></h5>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <h5>Category Name</h5>
                                        </td>
                                        <td>
                                            <?php
                                            $select = "SELECT * FROM categories WHERE id = {$res_head['category_id']}";
                                            $res_category = $conn->query($select)->fetch_assoc();
                                            ?>
                                            <h5><?php echo $res_category['name'] ?></h5>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <h5>Club Name</h5>
                                        </td>
                                        <td>
                                            <h5><?php echo $res_head['club_name'] ?></h5>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <h5>Role</h5>
                                        </td>
                                        <td>
                                            <h5>Head</h5>
                                        </td>
                                    </tr>
                                </tbody>
                            <?php
                            } else {
                                echo "Invalid Role";
                            }
                            ?>


                        </table>
                        <br>
                        <form action="" method="get">
                            <button class="button" name="edit" value="1">Edit Profile</button>
                            <button class="button" name="CP" value="2">Change Password</button>
                        </form>
                    <?php

                    }
                    ?>
                </div>


            </div>
        </div>
    </div>
</body>

</html>

<?php

if (isset($_POST['save'])) {
    $name = $_POST['name'];
    $img = $_FILES['img']['name'];
    $img_tmp = $_FILES['img']['tmp_name'];

    // Image Upload
    $target_dir = "images/";
    $target_file = $target_dir . basename($img);

    if (move_uploaded_file($img_tmp, $target_file)) {
        if ($_SESSION['role'] == 'Head') {
            $upt = $conn->prepare("UPDATE `club` SET `head_name`=?, `image`=? WHERE id = ?");
            $upt->bind_param("ssi", $name, $img, $_SESSION['club_id']);
            echo '<script>window.location.href = "admin_profile.php";</script>';
        } else {
            $upt = $conn->prepare("UPDATE `admin` SET `admin_name`=?, `image`=? WHERE admin_email = ?");
            $upt->bind_param("sss", $name, $img, $email);
            echo '<script>window.location.href = "admin_profile.php";</script>';
        }

        if ($upt->execute()) {
            echo "Data Updated Successfully";
        } else {
            echo "Error updating data.";
        }
    } else {
        echo "Image upload failed.";
    }
}
?>