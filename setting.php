<?php
include_once('admin_header.php');

if (isset($_POST['about_save'])) {
    $about_id = $_POST['about_id'];
    $task_force = $_POST['task_force'];

    // Handle file upload
    if (!empty($_FILES['img']['name'])) {
        $img = $_FILES['img']['name'];
        $target_dir = "gallery/";
        $target_file = $target_dir . basename($img);
        move_uploaded_file($_FILES['img']['tmp_name'], $target_file);

        // Update query with image
        $update = "UPDATE `about` SET task_force='$task_force', img='$img' WHERE id=$about_id";
    } else {
        // Update query without image
        $update = "UPDATE `about` SET task_force='$task_force' WHERE id=$about_id";
    }

    if ($conn->query($update) === TRUE) {
        echo "<script>window.location.href='?index=';</script>";
    } else {
        echo "Error : Not Updated";
    }
}
if (isset($_POST['index_save'])) {
    $index_id = $_POST['index_id'];
    $text = $_POST['text'];

    $update = "UPDATE `index_edit` SET `text`='$text' WHERE id=$index_id";

    if ($conn->query($update) === TRUE) {
        echo "<script>window.location.href='?index=';</script>";
    } else {
        echo "Error : Not Updated";
    }
}
if (isset($_POST['add_img'])) {

    $uploadDir = "slider_Images/";

    $img = $_FILES['img']['name'];

    if (!is_dir($uploadDir)) mkdir($uploadDir);

    if (!empty($img)) {
        $img_path = $uploadDir . basename($img);
        $img_tmp = $_FILES['img']['tmp_name'];

        if (move_uploaded_file($img_tmp, $img_path)) {
            $conn->query("INSERT INTO slider VALUES ('','$img')");

            $result = $conn->query("SELECT * FROM slider ORDER BY id ASC");
            $images = $result->fetch_all(MYSQLI_ASSOC);

            if (count($images) > 4) {
                $oldestImage = $images[0]['img']; // Oldest image file name
                $oldestImageId = $images[0]['id']; // Oldest image ID

                // Delete from database
                $conn->query("DELETE FROM slider WHERE id = $oldestImageId");

                // Delete from folder
                if (file_exists($uploadDir . $oldestImage)) {
                    unlink($uploadDir . $oldestImage);
                }
            }
        } else {
            echo "<script>alert('Image upload failed. Try again.')</script>";
            exit;
        }
    }

    echo "<script>window.location.href = 'setting.php?slider=1&remove_img='</script>";
    exit;
}
if (isset($_GET['slider_id'])) {
    $delete_id = intval($_GET['slider_id']); // Sanitize input
    
    $uploadDir = "slider_Images/";
    
    // Use prepared statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT img FROM slider WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc(); // Fetch a single row
    $stmt->close();
    
    if ($res) {
        $imgName = $res['img'];
        
        // Delete record from database
        $stmt = $conn->prepare("DELETE FROM slider WHERE id = ?");
        $stmt->bind_param("i", $delete_id);
        $stmt->execute();
        $stmt->close();
        
        // Delete image from directory
        if (file_exists($uploadDir . $imgName)) {
            unlink($uploadDir . $imgName);
        }
        echo "<script>window.location.href = 'setting.php?slider=1&remove_img='</script>";
    }
    echo "<script>window.location.href = 'setting.php?slider=1&remove_img='</script>";
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit SOAC</title>
</head>

<body>
    <div class="content-duplicate" data-aos="fade-up-left" id="mainContentDuplicate">
        <div class="w-100 p-3">
            <form method="get">
                <button class="button" name="slider">Edit Slider</button>
                <button class="button" name="index">Edit Index</button>
                <button class="button" name="about">Edit About SOAC</button>
            </form>

            <?php
            if (isset($_GET['slider'])) {
            ?>
                <div class="main my-5 p-2">
                    <form method="get">
                        <!-- Preserve slider parameter -->
                        <input type="hidden" name="slider" value="1">
                        <button class="button" name="add_img">Add Image</button>
                        <button class="button" name="remove_img">View Image Record</button>
                    </form>

                    <?php
                    if (isset($_GET['add_img'])) { ?>
                        <div class="main my-5 p-2">
                            <h2>Add Image</h2>
                            <form action="" method="post" enctype="multipart/form-data" id="form">
                                <div class="mb-3">
                                    <input type="file" name="img" id="" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <input type="submit" name="add_img" value="Add Images" class="button">
                                </div>
                            </form>
                        </div>
                    <?php

                    }
                    if (isset($_GET['remove_img'])) {
                    ?>
                        <div class="main my-5 p-2">
                            <h2>Gallery Records</h2>
                            <table id="myTable" class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">Sr No.</th>
                                        <th>Image Name</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $select = "SELECT * FROM `slider` ORDER BY `id` ASC";
                                    $res = $conn->query($select);

                                    while ($row = $res->fetch_assoc()) {
                                    ?>
                                        <tr>
                                            <th scope="row"><?= $row['id'] ?></th>
                                            <td> <img src="slider_Images/<?= $row['img'] ?>" alt="<?= $row['img'] ?>" style="height: 200px;width:500px;"></td>
                                            <td>
                                                <a href="?slider_id=<?php echo $row['id']; ?>">
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
            <?php
            }

            if (isset($_GET['index'])) {
            ?>
                <div class="main my-5 p-2">
                    <h2>Edit About Records</h2>
                    <table id="myTable" class="table">
                        <thead>
                            <tr>
                                <th scope="col">Sr No.</th>
                                <th>Text</th>
                                <th>Category</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $select = "SELECT * FROM `index_edit`";
                            $res = $conn->query($select);

                            while ($row = $res->fetch_assoc()) {
                                if (isset($_GET['index_id']) && $_GET['index_id'] == $row['id']) {
                            ?>
                                    <tr>
                                        <form action="" method="post" enctype="multipart/form-data">
                                            <input type="hidden" name="index_id" value="<?= $row['id'] ?>">
                                            <th scope="row"><?= $row['id'] ?></th>
                                            <td><textarea name="text" id="editor" class="form-control"><?= $row['text'] ?></textarea></td>
                                            <td>
                                                <input type="text" name="category" value="<?= $row['category'] ?>" class="form-control" readonly>
                                            </td>
                                            <td><input type="submit" name="index_save" class="button" value="Save Changes"></td>
                                        </form>
                                    </tr>
                                <?php } else { ?>
                                    <tr>
                                        <th scope="row"><?= $row['id'] ?></th>
                                        <td><?= $row['text'] ?></td>
                                        <td><?= $row['category'] ?></td>
                                        <td>
                                            <a href="?index=&index_id=<?= $row['id']; ?>">
                                                <button class="button">Edit</button>
                                            </a>
                                        </td>
                                    </tr>
                            <?php
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            <?php  }
            ?>

            <?php
            if (isset($_GET['about'])) {
            ?>
                <div class="main my-5 p-2">
                    <h2>Edit About Records</h2>
                    <table id="myTable" class="table">
                        <thead>
                            <tr>
                                <th scope="col">Sr No.</th>
                                <th>Task Force</th>
                                <th>Image</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $select = "SELECT * FROM `about`";
                            $res = $conn->query($select);

                            while ($row = $res->fetch_assoc()) {
                                if (isset($_GET['about_id']) && $_GET['about_id'] == $row['id']) {
                            ?>
                                    <tr>
                                        <form action="" method="post" enctype="multipart/form-data">
                                            <input type="hidden" name="about_id" value="<?= $row['id'] ?>">
                                            <th scope="row"><?= $row['id'] ?></th>
                                            <td><textarea name="task_force" id="editor" class="form-control"><?= $row['task_force'] ?></textarea></td>
                                            <td>
                                                <input type="file" name="img">
                                                <br>
                                                <img src="gallery/<?= $row['img'] ?>" alt="" style="height: 150px;width:200px" class="form-control">
                                            </td>
                                            <td><input type="submit" name="about_save" class="button" value="Save Changes"></td>
                                        </form>
                                    </tr>
                                <?php } else { ?>
                                    <tr>
                                        <th scope="row"><?= $row['id'] ?></th>
                                        <td><?= $row['task_force'] ?></td>
                                        <td><img src="gallery/<?= $row['img'] ?>" alt="" style="height: 150px;width:200px"></td>
                                        <td>
                                            <a href="?about&about_id=<?= $row['id']; ?>">
                                                <button class="button">Edit</button>
                                            </a>
                                        </td>
                                    </tr>
                            <?php
                                }
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

    <?php if (isset($_GET['about_id'])) { ?>
        <script>
            CKEDITOR.replace('editor', {
                font_defaultLabel: 'Gill Sans',
                font_names: 'Gill Sans/Gill Sans, sans-serif;' +
                    'Arial/Arial, Helvetica, sans-serif;' +
                    'Courier New/Courier New, Courier, monospace;' +
                    'Times New Roman/Times New Roman, Times, serif;',
            });
        </script>
    <?php } ?>
    <?php if (isset($_GET['index_id'])) { ?>
        <script>
            CKEDITOR.replace('editor', {
                font_defaultLabel: 'Gill Sans',
                font_names: 'Gill Sans/Gill Sans, sans-serif;' +
                    'Arial/Arial, Helvetica, sans-serif;' +
                    'Courier New/Courier New, Courier, monospace;' +
                    'Times New Roman/Times New Roman, Times, serif;',
            });
        </script>
    <?php } ?>
</body>

</html>