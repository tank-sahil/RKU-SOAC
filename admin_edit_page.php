<?php
include_once('admin_header.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <div class="content-duplicate" data-aos="fade-up-left" id="mainContentDuplicate">
        <div class="w-100 p-3">

            <form method="get">
                <button class="button" name="club">Club Detail</button>
                <button class="button" name="gallery">Add Club Gallery</button>
                <button class="button" name="view-gallery">View Club Gallery</button>
            </form>

            <?php
            if (isset($_GET['club'])) {

            ?>
                <div class="main my-5 p-2">
                    <h2>Club Details</h2>
                    <table id="myTable" class="table">
                        <thead>
                            <tr>

                                <?php
                                if (in_array($_SESSION['role'], ['Admin', 'Head'])) {

                                    $select = ($_SESSION['role'] == 'Head')
                                        ? "SELECT * FROM club_details WHERE club_id = {$_SESSION['club_id']}"
                                        : "SELECT * FROM club_details";
                                } else {
                                    echo "Invalid Role";
                                }

                                $res = $conn->query($select);

                                if ($res) {
                                    $fields = $res->fetch_fields();
                                    foreach ($fields as $field) {
                                        echo "<th>{$field->name}</th>";
                                    }
                                }
                                ?>

                                <th>Edit</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php

                            if (in_array($_SESSION['role'], ['Admin', 'Head'])) {
                                $select = "SELECT * FROM `club_details`" . ($_SESSION['role'] == 'Head' ? " WHERE club_id = {$_SESSION['club_id']}" : "");
                            } else {
                                echo "Invalid Role";
                            }
                            $res = $conn->query($select);

                            while ($row = $res->fetch_assoc()) {
                            ?>
                                <tr>
                                    <th scope="row"><?= $row['id'] ?></th>
                                    <td><?= $row['club_id'] ?></td>
                                    <td><?= $row['coordinators'] ?></td>
                                    <td><?= $row['Rules & Guidelines'] ?></td>
                                    <td><?= $row['Achievements'] ?></td>
                                    <td><?= $row['Criteria to Join organization'] ?></td>

                                    <td>
                                        <a href="admin_edit_content.php?edit_id=<?php echo $row['id']; ?>">
                                            <button class="button">Edit</button>
                                        </a>
                                    </td>
                                    <td>
                                        <a href="admin_edit_content.php?delete_id=<?php echo $row['id']; ?>">
                                            <button class="button">Delete</button>
                                        </a>
                                    </td>

                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            <?php
            }
            ?>

            <!-- CLub Gallery Secction -->

            <?php
            if (isset($_GET['gallery'])) {

            ?>

                <div class="main my-5 p-2">
                    <h2>Add Club Gallery </h2>
                    <form class="mt-3" id="form" method="post" enctype="multipart/form-data">
                        <div class="mb-3">
                            <input type="file" name="img" class="form-control">
                        </div>

                        <div class="mb-3">
                            <select id="club" class="form-control" name="club">
                                <option value="select">Select Club Name</option>
                                <?php
                                $sel = ($_SESSION['role'] == 'Head')
                                    ? "SELECT * FROM club WHERE id = {$_SESSION['club_id']}"
                                    : "SELECT * FROM club";

                                    $res = $conn->query($sel);
                                while ($row = $res->fetch_assoc()) {
                                ?>
                                    <option value="<?= $row['id'] ?>"><?= $row['club_name'] ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <input type="submit" class="button" value="Submit" name="submit">
                        </div>
                    </form>
                </div>
            <?php
                if (isset($_POST['submit'])) {

                    $clubId = $_POST['club'];
                    $img = $_FILES['img']['name'];

                    $target_dir = "gallery/";
                    $target_file = $target_dir . basename($_FILES["img"]["name"]);

                    // Move uploaded file to the folder
                    if (move_uploaded_file($_FILES["img"]["tmp_name"], $target_file)) {
                        $insert = "INSERT INTO `clubgallery`(`club_id`, `file_name`) VALUES ('$clubId','$img') ";
                        $conn->query($insert);
                    } else {
                        echo "File upload failed!";
                    }
                }
            }
            ?>


            <?php
            if (isset($_GET['view-gallery'])) {

            ?>
                <div class="main my-5 p-2">
                    <h2>Club Gallery</h2>
                    <table id="myTable" class="table">
                        <thead>
                            <tr>
                                <th>Sr No.</th>
                                <th>Club Id</th>
                                <th>Image</th>
                                <th>Image Name</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php

                            if (in_array($_SESSION['role'], ['Admin', 'Head'])) {
                                $select = "SELECT * FROM `clubgallery`" . ($_SESSION['role'] == 'Head' ? " WHERE club_id = {$_SESSION['club_id']}" : "");
                            } else {
                                echo "Invalid Role";
                            }
                            $res = $conn->query($select);

                            while ($row = $res->fetch_assoc()) {
                            ?>
                                <tr>
                                    <th scope="row"><?= $row['id'] ?></th>
                                    <td><?= $row['club_id'] ?></td>
                                    <td><img src="gallery/<?= $row['file_name'] ?>" style="height: 125px; width:200px;" alt=""></td>
                                    <td><?= $row['file_name'] ?></td>
                                    <td>
                                        <a href="admin_edit_content.php?delete_img_id=<?php echo $row['id']; ?>">
                                            <button class="button">Delete</button>
                                        </a>
                                    </td>

                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            <?php
            }
            ?>


        </div>
    </div>
</body>

</html>