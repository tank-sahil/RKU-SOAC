<?php
include_once('admin_header.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Events</title>
</head>

<body>
    <div class="content-duplicate" data-aos="fade-up-left" id="mainContentDuplicate">
        <div class="w-100 p-3">
            <form method="get">
                <button class="button" name="add_event">Add Event</button>
                <button class="button" name="view">View Record</button>
            </form>

            <?php if (isset($_GET['add_event'])): ?>
                <div class="main my-5 p-2">
                    <h2>Add Event</h2>
                    <form class="mt-3" method="post" id="form" enctype="multipart/form-data">
                        <div class="mb-3">
                            <input type="text" name="name" placeholder="Enter Event Name" class="form-control">
                        </div>
                        <div class="mb-3">
                            <input type="text" name="des" placeholder="Enter Event Description" class="form-control">
                        </div>
                        <div class="mb-3">
                            <select name="club" class="form-control" id="clubSelect">
                                <option value="">Select Club Name</option>
                                <?php
                                $result = $conn->query("SELECT * FROM club");
                                while ($row = $result->fetch_assoc()): ?>
                                    <option value="<?= $row['club_name'] ?>"> <?= $row['club_name'] ?> </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <input type="email" id="emailInput" name="email" placeholder="Enter email id" class="form-control" readonly>
                        </div>
                        <div class="mb-3">
                            <input type="date" name="date" class="form-control">
                        </div>
                        <div class="mb-3">
                            <input type="text" name="url" placeholder="Enter URL of Form Links" class="form-control">
                        </div>
                        <div class="mb-3">
                            <input type="file" name="img" class="form-control" accept="image/*">
                        </div>
                        <input type="submit" value="Add Event" class="button" name="event">
                    </form>
                </div>

                <script>
                    document.getElementById("clubSelect").addEventListener("change", function() {
                        var clubName = this.value;
                        if (clubName) {
                            fetch("get_club_email.php?club=" + clubName)
                                .then(response => response.text())
                                .then(data => {
                                    document.getElementById("emailInput").value = data;
                                })
                                .catch(error => console.error("Error:", error));
                        } else {
                            document.getElementById("emailInput").value = "";
                        }
                    });
                </script>

            <?php endif; ?>

            <?php if (isset($_GET['view'])): ?>
                <div class="main my-5 p-2">
                    <h2>Event Records</h2>
                    <table class="table" id="myTable">
                        <?php
                        if (isset($_GET['edit_id'])) {
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
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $result = $conn->query("SELECT * FROM events ORDER BY date ASC");
                                while ($row = $result->fetch_assoc()) {
                                ?>

                                    <tr>
                                        <form action="" id="form" method="post" enctype="multipart/form-data">
                                            <th><?= $row['id'] ?>
                                            <input type="hidden" name="event_edit" value="<?= $row['id'] ?>">
                                        </th>
                                            <td><input type="text" name="name" id="" value="<?= $row['e_name'] ?>" class="form-control"></td>
                                            <td><textarea name="des" id="" class="form-control"><?= $row['e_des'] ?></textarea></td>
                                            <td><input type="text" name="c_name" id="" value="<?= $row['club_name'] ?>" class="form-control"></td>
                                            <td><input type="email" name="email" id="" value="<?= $row['head_email'] ?>" class="form-control" readonly></td>
                                            <td><input type="text" name="link" id="" value="<?= $row['link'] ?>" class="form-control"></td>
                                            <td><input type="date" name="date" id="" value="<?= $row['date'] ?>" class="form-control"></td>
                                            <td><input type="file" name="img" id=""><img src="event_Images/<?= $row['img'] ?>" style="height: 100px; width: 150px;" class="form-control"></td>
                                            <td><input type="submit" value="Save Changes" name="update" class="button" class="form-control"></td>
                                        </form>

                                    </tr>
                            </tbody>
                        <?php
                                }
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

                                $result = $conn->query("SELECT * FROM events ORDER BY date ASC");
                                while ($row = $result->fetch_assoc()) { ?>
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
                                    <td><img src="event_Images/<?= $row['img'] ?>" style="height: 100px; width: 150px;"></td>
                                    <td><?= $row['status'] ?></td>
                                    <td>
                                        <a href="?view&edit_id=<?= $row['id'] ?>"><button class="button my-2">Edit</button></a> |
                                        <a href="?view&delete_id=<?= $row['id'] ?>"><button class="button my-2">Delete</button></a>
                                    </td>
                                </tr>
                        </tbody>
                <?php
                                }
                            }
                ?>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>

<?php
if (isset($_POST['event'])) {
    $name = $_POST['name'];
    $des = $_POST['des'];
    $club = $_POST['club'];
    $email = $_POST['email'];
    $url = $_POST['url'];
    $date = $_POST['date'];
    $img = $_FILES['img']['name'];
    $img_tmp = $_FILES['img']['tmp_name'];
    $img_path = "event_Images/" . basename($img);

    if (!is_dir('event_Images')) mkdir('event_Images');

    if (move_uploaded_file($img_tmp, $img_path)) {
        $status = ($_SESSION['role'] == 'Head') ? 'inactive' : 'active';
        $stmt = $conn->prepare("INSERT INTO events (e_name, e_des, club_name, head_email, link, date, status, img) VALUES (?, ?, ?, ?, ?, ?, ?,?)");
        $stmt->bind_param("ssssssss", $name, $des, $club, $email, $url, $date, $status, $img);
        $stmt->execute();
        // $insert = "INSERT INTO events (e_name, e_des, club_name, head_email, link, date, status, img) VALUES ($name, $des, $club, $email, $url, $date, ?)"

        echo "<script>window.location.href = 'admin_event.php?view';</script>";
    } else {
        echo "Failed to upload image.";
    }
}

if (isset($_POST['update'])) {

    $event_id = $_POST['event_edit'];
    $name = $_POST['name'];
    $des = $_POST['des'];
    $club = $_POST['c_name'];
    $email = $_POST['email'];
    $link = $_POST['link'];
    $date = $_POST['date'];
    $img = $_FILES['img']['name'];

    $fetch_old_img = $conn->query("SELECT img FROM events WHERE id = $event_id");
    $old_img_data = $fetch_old_img->fetch_assoc();
    $old_img = $old_img_data['img'];

    if (!is_dir('event_Images')) {
        mkdir('event_Images');
    }
    // Image handling
    if (!empty($img)) {
        $img_path = 'event_Images/' . basename($img);
        $img_tmp = $_FILES['img']['tmp_name'];

        if (move_uploaded_file($img_tmp, $img_path)) {
            if (!empty($old_img) && file_exists('event_Images/' . $old_img)) {
                unlink('event_Images/' . $old_img); // Delete old image
            }
        } else {
            echo "<script>alert('Image upload failed. Try again.')</script>";
            exit;
        }
    } else {
        $img = $old_img; // Retain the old image if no new one is uploaded
    }
    $conn->query("UPDATE `events` SET `e_name`='$name',`e_des`='$des',`club_name`='$club',`link`='$link',`date`='$date',`img`='$img' WHERE id = $event_id");
}

if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    $stmt = $conn->prepare("DELETE FROM events WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    echo "<script>window.location.href = 'admin_event.php?view';</script>";
}


?>