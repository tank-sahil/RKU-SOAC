<?php

include_once('admin_header.php');

if (isset($_REQUEST['edit_id'])) {
    $id = $_REQUEST['edit_id'];
    $select = "SELECT * FROM `club_details` WHERE id = $id";
    $row = $conn->query($select)->fetch_assoc();
}

if (isset($_REQUEST['delete_id'])) {
    $id = $_REQUEST['delete_id'];
    $delete = "DELETE FROM `club_details` WHERE id = $id";
    $conn->query($delete);

?>
    <script>
        window.location.href = "admin_edit_page.php?club= ";
    </script>
<?php
}
if (isset($_REQUEST['delete_img_id'])) {
    $id = $_REQUEST['delete_img_id'];
    $delete = "DELETE FROM `clubgallery` WHERE id = $id";
    $conn->query($delete);

?>
    <script>
        window.location.href = "admin_edit_page.php?view-gallery=";
    </script>
    <?php
}


if (isset($_POST['edit'])) {
    $coordinator = $_POST['coordinator'];
    $rules = $_POST['rules'];
    $achivements = $_POST['achievements'];
    $criteria = $_POST['criteria'];

    if (!empty($coordinator) && !empty($rules) && !empty($achivements) && !empty($criteria)) {
        $upd = "UPDATE `club_details` SET `coordinators`='$coordinator',`Rules & Guidelines`='$rules',`Achievements`='$achivements',`Criteria to Join organization`='$criteria' WHERE id = '$id'";

        if ($conn->query($upd)) {
            echo "Data Is Updated";
    ?>
            <script>
                window.location.href = "http://localhost/SOAC/admin_edit_page.php?club= ";
            </script>
<?php
        } else {
            echo "Error : Data is Not Updated";
        }
    } else {
        echo "Error : Null value Not inserted";
    }
}

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


            <div class="main my-5 p-2">
                <h2>Edit Content</h2>
                <form class="mt-3" id="form" method="post" action="">
                    <div class="mb-3">
                        <input type="number" name="id" id="id" placeholder="Admin Name" class="form-control" value="<?= $row['id']; ?>" readonly>
                    </div>
                    <div class="mb-3">
                        <input type="text" name="coordinator" id="coordinator" placeholder="Coordinator Name" class="form-control" value="<?= $row['coordinators']; ?>">
                    </div>
                    <div class="mb-3">
                        <textarea name="rules" id="editor" class="form-control"><?= $row['Rules & Guidelines']; ?></textarea>
                        <span class="error">Note : Make sure Use this format <br>
                            - Rules & Guidelines Sentence
                        </span>
                    </div>
                    <div class="mb-3">
                        <textarea name="achievements" id="editor1" class="form-control"><?= $row['Achievements']; ?></textarea>
                        <span class="error">Note : Make sure Use this format <br>
                            - Achievements Sentence
                        </span>
                    </div>
                    <div class="mb-3">
                        <textarea name="criteria" id="editor2" class="form-control"><?= $row['Criteria to Join organization']; ?></textarea>
                        <span class="error">Note : Make sure Use this format <br>
                            - Criteria to Join organization Sentence
                        </span>
                    </div>
                    <div class="my-3">
                        <input type="submit" name="edit" value="Edit" class="button">
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>
<script>
    CKEDITOR.replace('editor', {
        font_defaultLabel: 'Gill Sans',
        font_names: 'Gill Sans/Gill Sans, sans-serif;' +
            'Arial/Arial, Helvetica, sans-serif;' +
            'Courier New/Courier New, Courier, monospace;' +
            'Times New Roman/Times New Roman, Times, serif;',
    });
    CKEDITOR.replace('editor1', {
        font_defaultLabel: 'Gill Sans',
        font_names: 'Gill Sans/Gill Sans, sans-serif;' +
            'Arial/Arial, Helvetica, sans-serif;' +
            'Courier New/Courier New, Courier, monospace;' +
            'Times New Roman/Times New Roman, Times, serif;',
    });
    CKEDITOR.replace('editor2', {
        font_defaultLabel: 'Gill Sans',
        font_names: 'Gill Sans/Gill Sans, sans-serif;' +
            'Arial/Arial, Helvetica, sans-serif;' +
            'Courier New/Courier New, Courier, monospace;' +
            'Times New Roman/Times New Roman, Times, serif;',
    });
</script>