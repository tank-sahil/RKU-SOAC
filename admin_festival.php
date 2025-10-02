<?php
include_once('admin_header.php');

if (isset($_POST['festival'])) {
    $name = $_POST['name'];
    $date = $_POST['date'];
    $img = $_FILES['img']['name'];

    if (is_dir('uploads/')) {
        $img_path = 'uploads/' . basename($img);
        $img_tmp = $_FILES['img']['tmp_name'];
        if (move_uploaded_file($img_tmp, $img_path)) {
            $insert = "INSERT INTO `festival`(`fes_name`, `date`, `fes_img`) VALUES ('$name','$date','$img')";
            if ($conn->query($insert)) {
                echo "Data inserted";
            } else {
                echo "Error : Failed to Insert Data";
            }
        } else {
            echo "<script>alert('Image upload failed. Try again.')</script>";
            exit;
        }
    } else {
        mkdir('uploads/');
    }
}

if (isset($_POST['upd'])) {
    $edit_id = $_POST['edit_id'];
    $name = $_POST['name'];
    $date = $_POST['date'];
    $img = $_FILES['img']['name'];

    // Fetch the old image
    $fetch_old_img = $conn->query("SELECT fes_img FROM festival WHERE id = $edit_id");
    $old_img_data = $fetch_old_img->fetch_assoc();
    $old_img = $old_img_data['fes_img'];

    // Image handling
    if (!empty($img)) {
        $img_path = 'uploads/' . basename($img);
        $img_tmp = $_FILES['img']['tmp_name'];

        if (move_uploaded_file($img_tmp, $img_path)) {
            if (!empty($old_img) && file_exists('uploads/' . $old_img)) {
                unlink('uploads/' . $old_img); // Delete old image
            }
        } else {
            echo "<script>alert('Image upload failed. Try again.')</script>";
            exit;
        }
    } else {
        $img = $old_img; // Retain the old image if no new one is uploaded
    }

    // Update database
    $conn->query("UPDATE festival SET fes_name='$name', date='$date', fes_img='$img' WHERE id = $edit_id");

    echo "<script>window.location.href = 'admin_festival.php?view='</script>";
    exit;
}
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];

    if ($conn->query("DELETE FROM festival WHERE id='$delete_id'")) {
        echo "<script>window.location.href = 'admin_festival.php?view='</script>";
        # code...
    }
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Festival</title>
</head>

<body>
    <div class="content-duplicate" data-aos="fade-up-left" id="mainContentDuplicate">
        <div class="w-100 p-3">
            <form method="get">
                <button class="button" name="add_fes">Add Festival</button>
                <button class="button" name="view">View Record</button>
            </form>

            <?php if (isset($_GET['add_fes'])): ?>
                <div class="main my-5 p-2">
                    <h2>Add Festival</h2>
                    <form class="mt-3" method="post" id="form" enctype="multipart/form-data">
                        <div class="mb-3">
                            <input type="text" name="name" id="name" placeholder="Enter Festival Name" class="form-control">
                        </div>
                        <div class="mb-3">
                            <input type="date" name="date" id="date" class="form-control">
                        </div>
                        <div class="mb-3">
                            <input type="file" name="img" id="img" class="form-control">
                        </div>
                        <input type="submit" value="Add Festival" class="button" name="festival">
                    </form>
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['view'])): ?>
                <div class="main my-5 p-2">
                    <h2>Festival Records</h2>
                    <table class="table" id="myTable">
                        <thead>
                            <tr>
                                <th>Sr no.</th>
                                <th>Festival Name</th>
                                <th>Date</th>
                                <th>Image</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $result = $conn->query("SELECT * FROM festival ORDER BY `date` ASC");

                            while ($row = $result->fetch_assoc()) {
                                // $imgPath = "uploads/" . $row['fes_img'];

                                // // Check if the image file exists and delete it
                                // if (!empty($row['fes_img']) && file_exists($imgPath)) {
                                //     unlink($imgPath);
                                // }
                                // $conn->query("DELETE FROM festival WHERE `date` = CURDATE()");

                                if (isset($_GET['edit_id']) && $_GET['edit_id'] == $row['id']) {
                            ?>
                                    <tr>
                                        <form action="" id="form" method="post" enctype="multipart/form-data">
                                            <td><input type="number" class="form-control" value="<?= $row['id'] ?>" readonly></td>
                                            <td><input type="text" name="name" id="name" class="form-control" value="<?= $row['fes_name'] ?>"></td>
                                            <td><input type="date" name="date" id="date" class="form-control" value="<?= $row['date'] ?>"></td>
                                            <td>
                                                <input type="file" name="img" id="img" class="form-control"><br>
                                                <img src="uploads/<?= $row['fes_img'] ?>" alt="" style="height: 70px; width:100px; object-fit:contain">
                                            </td>
                                            <td>
                                                <input type="hidden" name="edit_id" value="<?= $row['id'] ?>">
                                                <input type="submit" class="button" name="upd" value="Save Changes">
                                            </td>
                                        </form>
                                    </tr>
                                <?php } else {
                                ?>
                                    <tr>
                                        <td><?= $row['id'] ?></td>
                                        <td><?= $row['fes_name'] ?></td>
                                        <td><?= date("d-m-Y", strtotime($row['date'])) ?></td>
                                        <td><img src="uploads/<?= $row['fes_img'] ?>" style="height: 100px; width: 150px;"></td>
                                        <td>
                                            <a href="?view&edit_id=<?= $row['id'] ?>"><button class="button">Edit</button></a> |
                                            <a href="?view&delete_id=<?= $row['id'] ?>"><button class="button">Delete</button></a>
                                        </td>
                                    </tr>
                                <?php }
                                ?>
                        </tbody>
                    <?php
                            }
                    ?>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>