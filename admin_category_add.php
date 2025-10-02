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

    <div class="content-duplicate" data-aos="fade-up-left" id="mainContentDuplicate">
        <div class="w-100 p-3">

            <form method="get">
                <button class="button m-2" name="add_cat">Add Category</button>
                <button class="button m-2" name="view">View Record </button>
                <button class="button m-2" name="add_club">Add Club</button>
                <button class="button m-2" name="view_club">View Record </button>
            </form>

            <!-- Add Category -->
            <?php
            if (isset($_GET['add_cat'])) {
            ?>
                <div class="main my-5 p-2">
                    <h2>Add Category</h2>
                    <form class="mt-3" id="form" method="post">
                        <div class="mb-3">
                            <input type="text" name="name" class="form-control" placeholder="Category Name">
                        </div>
                        <input type="submit" name="cat" value="Add Category" class="button"><br><br>
                    </form>
                </div>
            <?php
            }

            if (isset($_POST['cat'])) {
                $name = $_POST['name'];
                if (!empty($name)) {
                    $insert = "INSERT INTO categories (name) VALUES ('$name')";
                    $conn->query($insert);
                }
            }
            ?>

            <!-- Record Of Category -->
            <?php
            if (isset($_GET['view'])) {
            ?>
                <div class="main my-5 p-2">
                    <h2>Record</h2>
                    <table id="myTable" class="table">
                        <thead>
                            <tr>
                                <th scope="col">Sr no.</th>
                                <th scope="col">Category Name</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT * FROM categories";
                            $res = $conn->query($sql);
                            while ($row = $res->fetch_assoc()) {
                            ?>
                                <tr>
                                    <th scope="row"><?= $row['id'] ?></th>
                                    <?php
                                    if (isset($_GET['edit_cat']) && $_GET['edit_cat'] == $row['id']) {
                                    ?><form action="" method="post">
                                            <td><input type="text" name="name" class="form-control" value="<?= $row['name'] ?>"></td>
                                            <td><input type="submit" class="button" name="upd" value="Update"></td>
                                        </form>
                                        <?php
                                        if (isset($_POST['upd'])) {
                                            $name = $_POST['name'];

                                            $conn->query("UPDATE `categories` SET `name`='$name' WHERE id = '{$row['id']}' ");
                                            echo "<script>window.location.href = 'admin_category_add.php?view='</script>";
                                        }
                                    } elseif (isset($_GET['delete_cat']) && $_GET['delete_cat'] == $row['id']) {

                                        $conn->query("DELETE FROM categories WHERE id = '{$row['id']}'");
                                        echo "<script>window.location.href = 'admin_category_add.php?view='</script>";
                                    } else {
                                        ?>
                                        <td><?= $row['name'] ?></td>
                                        <td>

                                            <a href="?view=&edit_cat=<?= $row['id'] ?>"><button class="button">Edit</button></a>
                                            <a href="?view=&delete_cat=<?= $row['id'] ?>"><button class="button">Delete</button></a>
                                        </td>
                                    <?php
                                    }

                                    ?>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            <?php
            }

            if (isset($_GET['add_club'])) {
            ?>
                <div class="main my-5 p-2">
                    <h2>Add Club</h2>
                    <form class="mt-3" id="form" method="post" enctype="multipart/form-data">
                        <div class="mb-3">
                            <input type="text" name="name" class="form-control" placeholder="Club Name">
                        </div>
                        <div class="mb-3">
                            <input type="file" name="img" class="form-control">
                        </div>
                        <div class="mb-3">
                            <input type="text" name="des" class="form-control" placeholder="Club Description">
                        </div>
                        <div class="mb-3">
                            <select id="club" class="form-control" name="club">
                                <option value="select">Select Type</option>
                                <?php
                                $sel = "SELECT * FROM categories";
                                $res = $conn->query($sel);
                                while ($row = $res->fetch_assoc()) {
                                ?>
                                    <option value="<?= $row['id'] ?>"><?= $row['name'] ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <input type="text" name="head_name" class="form-control" placeholder="Club Coach/Head Name">
                        </div>
                        <div class="mb-3">
                            <input type="email" name="email" class="form-control" placeholder="Club Coach/Head Email">
                        </div>
                        <input type="submit" value="Add Club" name="adding" class="button"><br><br>
                    </form>
                </div>
            <?php
            }


            if (isset($_POST['adding'])) {
                // Debug: check if the form is submitted
                // // Collecting form data
                $name = $_POST['name'];
                $description = $_POST['des'];
                $category_id = $_POST['club'];
                $head_name = $_POST['head_name'];
                $email = $_POST['email'];
                $img = $_FILES['img']['name'];
                $img_tmp = $_FILES['img']['tmp_name'];
                $img_path = "club_Images/" . $img;
                $random = sprintf('%03d', rand(0, 999));
                $password = 'Head@' . $random;
                $token = uniqid() . uniqid();

                if (!empty($name) && !empty($description) && !empty($category_id) && !empty($head_name) && !empty($email) && !empty($img)) {
                    // echo "Okey Pooooo";
                    if (!is_dir('club_Images/')) {
                        mkdir('club_Images/'); // Create directory with write permissions
                    } else {
                        $img_path = 'club_Images/' . $_FILES['img']['name'];
                        $img_tmp = $_FILES['img']['tmp_name'];
                    }
                    // Move the uploaded file to the 'uploads' folder
                    if (move_uploaded_file($img_tmp, $img_path)) {
                        echo "Upload successful!";
                    } else {
                        echo "Failed to upload the file.";
                    }

                    $insert = "INSERT INTO `club`(`category_id`, `club_name`, `club_img`, `club_des`, `head_name`, `head_email`, `password`,`token`) VALUES ('$category_id','$name','$img','$description','$head_name','$email','$password','$token')";
                    $global = "INSERT INTO `global_people`(`email`, `password`, `role`) VALUES ('$email','$password','Head')";

                    if ($conn->query($insert) && $conn->query($global)) {

                        $sel = "SELECT `id` FROM `club` ORDER BY `id` DESC LIMIT 1";
                        $res = $conn->query($sel);
                        $row = $res->fetch_assoc();

                        $club_id = $row['id'];

                        $club_detail = "INSERT INTO `club_details`(`club_id`, `coordinators`, `Rules & Guidelines`, `Achievements`, `Criteria to Join organization`) VALUES ('$club_id','Enter Coordinators','Enter Rules & Guidelines','Enter Achievements','Enter Criteria to Join organization') ";
                        $conn->query($club_detail);

                        $mail->isSMTP();
                        $mail->Host = 'smtp.gmail.com';
                        $mail->SMTPAuth = true;
                        $mail->Username = 'stank729@rku.ac.in'; // Replace with your Gmail address
                        $mail->Password = 'PASSWORD IS UPDATED'; // Replace with your app password
                        $mail->SMTPSecure = 'ssl'; // Use 'tls' for Port 587
                        $mail->Port = 465;

                        // Recipients
                        $mail->setFrom('stank729@rku.ac.in');
                        $mail->addAddress($email);

                        // Content
                        $mail->isHTML(true);
                        $mail->Subject = 'Registaion';
                        $mail->Body = "
                        <h2>Congratulations $head_name,</h2>
                        <p>Your $name Club has been created with the following credentials:</p>
                        <ul>
                            <li>Club Name : $name</li>
                            <li>Head Name : $head_name</li>
                            <li>Email: $email</li>
                            <li>Password: $password</li>
                        </ul>
                        <p style='color: red;'>Note: Please change your account password after logging in.</p>
                        <p>Click the link below to verify your account:</p>
                        <p>http://localhost/SOAC/verify.php?head_email=$email&token=$token</p>";
                        // Send email
                        $mail->send();
                        echo "Mail is Sent";
                    }
                }
            }
            ?>


            <!-- Record Of Club -->
            <?php
            if (isset($_GET['view_club'])) {
            ?>
                <div class="main my-5 p-2">
                    <h2>Record</h2>
                    <div style="overflow-x: auto;">
                        <table id="myTable" class="table">
                            <thead>
                                <tr>
                                    <th scope="col">Sr no.</th>
                                    <th>Category ID</th>
                                    <th>Club Name</th>
                                    <th>Club Image</th>
                                    <th>Club Description</th>
                                    <th>Head/Coach Name</th>
                                    <th>Head/Coach Email</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sql = "SELECT * FROM club"; // Assuming a `clubs` table exists
                                $res = $conn->query($sql);
                                while ($row = $res->fetch_assoc()) {
                                    $data_email = $row['head_email'];
                                    if (isset($_GET['edit_club_id']) && $_GET['edit_club_id'] == $row['id']) {
                                ?>
                                        <tr>
                                            <form action="" method="post" enctype="multipart/form-data">
                                                <th scope="row"><?= $row['id'] ?>
                                                    <input type="hidden" name="club_edit" value="<?= $row['id'] ?>">
                                                </th>
                                                <td><?= $row['category_id'] ?></td>
                                                <td><input type="text" name="name" value="<?= $row['club_name'] ?>" class="form-control" id=""></td>
                                                <td><input type="file" name="img" value="<?= $row['club_img'] ?>" id=""><img style="height: 150px; width:200px;" src="club_Images/<?= $row['club_img'] ?>" alt="<?= $row['club_name'] ?>" class="form-control"></td>
                                                <td><textarea name="des" id="" class="form-control"><?= $row['club_des'] ?></textarea></td>
                                                <td><input type="text" name="head_name" id="" value="<?= $row['head_name'] ?>" class="form-control"></td>
                                                <td> <input type="email" name="email" id="" value="<?= $row['head_email'] ?>" class="form-control" readonly> </td>
                                                <td><input type="submit" class="button" name="enter" value="Save Changes"></td>
                                            </form>
                                        </tr>
                                    <?php
                                    } else {

                                    ?>
                                        <tr>
                                            <th scope="row"><?= $row['id'] ?></th>
                                            <td><?= $row['category_id'] ?></td>
                                            <td><?= $row['club_name'] ?></td>
                                            <td><img style="height: 150px; width:200px;" src="club_Images/<?= $row['club_img'] ?>" alt="<?= $row['club_name'] ?>"></td>
                                            <td><?= $row['club_des'] ?></td>
                                            <td><?= $row['head_name'] ?></td>
                                            <td><?= $row['head_email'] ?></td>
                                            <td>
                                                <a href="?view_club&edit_club_id=<?= $row['id'] ?>"><button class="button my-2">Edit</button></a> |
                                                <a href="?view_club&delete_club_id=<?= $row['id'] ?>"><button class="button my-2">Delete</button></a>

                                            </td>
                                        </tr>
                                <?php
                                    }
                                }
                                ?>
                            </tbody>

                        </table>
                    </div>
                </div>
            <?php
            }
            ?>
        </div>
    </div>

</body>

</html>

<?php

if (isset($_POST['enter'])) {
    $club_edit = $_POST['club_edit'];
    $name = $_POST['name'];
    $head_email = $_POST['email'];
    $head_name = $_POST['head_name'];
    $img = $_FILES['img']['name'];
    $des = $_POST['des'];

    $fetch_old_img = $conn->query("SELECT `club_img` FROM club WHERE id = $club_edit");
    $old_img_data = $fetch_old_img->fetch_assoc();
    $old_img = $old_img_data['club_img'];

    // Image handling
    if (!empty($img)) {
        $img_path = 'club_Images/' . basename($img);
        $img_tmp = $_FILES['img']['tmp_name'];

        if (move_uploaded_file($img_tmp, $img_path)) {
            if (!empty($old_img) && file_exists('club_Images/' . $old_img)) {
                unlink('club_Images/' . $old_img); // Delete old image
            }
        } else {
            echo "<script>alert('Image upload failed. Try again.')</script>";
            exit;
        }
    } else {
        $img = $old_img; // Retain the old image if no new one is uploaded
    }

    // Update database
    $conn->query("UPDATE `club` SET `club_name`='$name',`club_img`='$img',`club_des`='$des',`head_name`='$head_name',`head_email`='$head_email' WHERE id = $club_edit");
    echo "<script>window.location.href = 'admin_category_add.php?view_club'</script>";
    
}
if(isset($_GET['delete_club_id']))
{
    $id = $_GET['delete_club_id'];
    
    $conn->query("DELETE FROM club WHERE id = $id");
    echo "<script>window.location.href = 'admin_category_   add.php?view_club'</script>";

}

?>