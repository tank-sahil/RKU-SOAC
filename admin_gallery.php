<?php
include_once('admin_header.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gallery Upload</title>

    <script>
        $(document).ready(function() {
            $("#image").change(function(event) {
                var file = event.target.files[0]; // Get the uploaded file

                if (!file) {
                    $("#error-message").text("Error: No file selected.");
                    $("#submitBtn").prop("disabled", true).addClass("disabled");
                    return;
                }

                // File type validation
                var fileType = file.type;
                var validTypes = ["image/jpeg", "image/jpg", "image/png", "image/webp"];
                if (!validTypes.includes(fileType)) {
                    $("#error-message").text("Error: Only JPG, JPEG, and PNG files are allowed.");
                    $("#submitBtn").prop("disabled", true).addClass("disabled");
                    return;
                }

                var img = new Image();
                img.src = URL.createObjectURL(file);

                img.onload = function() {
                    var width = this.width;
                    var height = this.height;

                    if (width >= 1980 && height >= 736) { // Ensure exact match
                        $("#error-message").text(""); // Clear error message
                        $("#submitBtn").prop("disabled", false).removeClass("disabled"); // Enable submit
                    } else {
                        $("#error-message").text("Error: Image must be exactly 1980x809 pixels.");
                        $("#submitBtn").prop("disabled", true).addClass("disabled"); // Disable submit
                    }
                };
            });
        });
    </script>
</head>

<body>

    <div class="content-duplicate" data-aos="fade-up-left" id="mainContentDuplicate">
        <div class="w-100 p-3">

            <form method="get">
                <button class="button" name="gallery">Gallery</button>
                <button class="button" name="records">Gallery Record</button>
            </form>

            <?php if (isset($_GET['gallery'])) { ?>
                <div class="card mt-4">
                    <div class="card-body">
                        <h2 class="card-title">Gallery</h2>
                        <form action="" method="post" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="image" class="form-label">Upload Image</label>
                                <input type="file" name="image" id="image" class="form-control" required>
                                <p id="error-message" class="text-danger mt-2"></p>
                            </div>
                            <div class="mb-3">
                                <input type="text" name="name" id="name" class="form-control" placeholder="Name of Image">
                            </div>
                            <button type="submit" name="sub" id="submitBtn" class="button disabled" disabled>Upload</button>
                        </form>
                    </div>
                </div>
            <?php } ?>

            <?php
            if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['sub'])) {
                if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                    $file = $_FILES['image'];

                    // File details
                    $fileName = $file['name'];
                    $fileTmpName = $file['tmp_name'];
                    $fileSize = $file['size'];
                    $fileError = $file['error'];
                    $fileType = $file['type'];

                    // Allowed file types
                    $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];

                    if (in_array($fileType, $allowedTypes)) {
                        // Upload directory
                        $uploadDir = "gallery/";
                        if (!is_dir($uploadDir)) {
                            mkdir($uploadDir, 0777, true);
                        }

                        // Move file to upload directory
                        $fileDestination = $uploadDir . basename($fileName);
                        if (move_uploaded_file($fileTmpName, $fileDestination)) {

                            $insert = "INSERT INTO `gallery`(`img_name`, `img_type`) VALUES ('$fileName','$fileType') ";
                            $conn->query($insert);

                            $result = $conn->query("SELECT * FROM gallery ORDER BY id ASC");
                            $images = $result->fetch_all(MYSQLI_ASSOC);

                            if (count($images) > 6) {
                                $oldestImage = $images[0]['img_name']; // Oldest image file name
                                $oldestImageId = $images[0]['id']; // Oldest image ID

                                // Delete from database
                                $conn->query("DELETE FROM gallery WHERE id = $oldestImageId");

                                // Delete from folder
                                if (file_exists($uploadDir . $oldestImage)) {
                                    unlink($uploadDir . $oldestImage);
                                }
                            }

                            echo "Image uploaded successfully!";
                        } else {
                            echo "Error: Uploading Image";
                        }
                    } else {
                        echo "Error : Invalid file type. Only JPG, JPEG, and PNG are allowed.";
                    }
                } else {
                    echo "Error: No file selected.";
                }
            }
            if (isset($_GET['records'])) {
            ?>
                <div class="main my-5 p-2">
                    <h2>Gallery Records</h2>
                    <table id="myTable" class="table">
                        <thead>
                            <tr>
                                <th scope="col">Sr No.</th>
                                <th>Image Name</th>
                                <th>Image Text</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $select = "SELECT * FROM `gallery`";
                            $res = $conn->query($select);

                            while ($row = $res->fetch_assoc()) {
                            ?>
                                <tr>
                                    <th scope="row"><?= $row['id'] ?></th>
                                    <td> <img src="gallery/<?= $row['img_name'] ?>" alt="<?= $row['img_name'] ?>" style="height: 200px;width:500px;"></td>
                                    <td><?= $row['img_text'] ?></td>
                                    <td>
                                        <a href="delete_admin.php?admin_id=<?php echo $row['id']; ?>">
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