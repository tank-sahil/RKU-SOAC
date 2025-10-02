<?php
include_once('header.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <!-- Centering the form -->
    <div class="container d-flex justify-content-center align-items-center my-3">
        <div class="w-100">
            <div class="main p-5 border shadow-sm mx-auto" style="max-width: 700px;">
                <h2>Register</h2>
                <form class="mt-3" id="form" method="post">
                    <div class="mb-3">
                        <input type="text" name="fname" class="form-control" placeholder="First Name">
                    </div>
                    <div class="mb-3">
                        <input type="text" name="lname" class="form-control" placeholder="Last Name">
                    </div>
                    <div class="mb-3">
                        <input type="text" name="dept" class="form-control" placeholder="Enter Department Name">
                    </div>
                    <div class="mb-3">
                        <input type="email" name="email" class="form-control" placeholder="Enter Email Address">
                    </div>
                    <div class="mb-3">
                        <select id="club" class="form-control" name="club">
                            <option value="select">Select</option>
                            <?php
                            $sel = "SELECT * FROM club";
                            $res = $conn->query($sel);
                            while ($row = $res->fetch_assoc()) {
                            ?>
                                <option value="<?= $row['id'] ?>"><?= $row['club_name'] ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>

                    <input type="submit" name="user" value="Register" class="button">
                    <input type="reset"  value="Reset" class="button">
                    <br><br>
                </form>

            </div>
        </div>
    </div>
</body>

</html>

<?php

if (isset($_POST['user'])) {
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $dept = $_POST['dept'];
    $email = $_POST['email'];
    $club = $_POST['club'];

    if (!empty($fname) && !empty($lname) && !empty($dept) && !empty($email) && !empty($club)) {
        $email = trim($email); // Remove unnecessary spaces

        $check_duplicate = "SELECT * FROM `user` WHERE `email` = '$email'";
        $result = $conn->query($check_duplicate);

        if (!$result) {
            die("Query Failed: " . $conn->error);
        }

        if ($result->num_rows > 0) {
            echo "Error: An admin with this email already exists.";
        } else {
            $insert = "INSERT INTO `user`(`club`,`fname`, `lname`,`email`,`dept`) VALUES ('$club','$fname','$lname','$email','$dept')";

            if ($conn->query($insert)) {
                echo "Data Inserted Successfully";
                echo "<pre>";
                print_r($_POST);
                echo "</pre>";
            } else {
                echo "Error inserting data: " . $conn->error;
            }
        }
    }
}



?>