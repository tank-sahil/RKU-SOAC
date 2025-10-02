<?php
include_once('admin_header.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Admin</title>
</head>

<body>
    <div class="content-duplicate" data-aos="fade-up-left" id="mainContentDuplicate">
        <div class="w-100 p-3">
            <!-- Navigation Form -->
            <form method="get">
                <button class="button" name="add_fes" value="add">Add Admin</button>
                <button class="button" name="view" value="view">View Record</button>
            </form>

            <?php
            // Add Admin Section
            if (isset($_GET['add_fes'])) {
            ?>
                <div class="main my-5 p-2">
                    <h2>Add Admin</h2>
                    <form class="mt-3" id="form" method="post" action="">
                        <div class="mb-3">
                            <input type="text" name="name" id="name" placeholder="Admin Name" class="form-control">
                        </div>
                        <div class="mb-3">
                            <input type="email" name="email" id="email" placeholder="Admin Email" class="form-control">
                        </div>
                        <input type="submit" name="admin_add" value="Add Admin" class="button">
                    </form>
                </div>
            <?php
            }

            if (isset($_POST['admin_add'])) {

                $name = $_POST['name'];
                $email = $_POST['email'];
                $random = sprintf('%03d', rand(0, 999));
                $password = 'Admin@' . $random;
                $token = uniqid() . uniqid();
                // echo $name . $email . $password . $token;


                if (!empty($name) && !empty($email)) {

                    $check_duplicate = "SELECT * FROM `admin` WHERE `admin_email` = '$email'";
                    $result = $conn->query($check_duplicate);


                    if ($result->num_rows > 0) {
                        echo "Error: An admin with this email already exists.";
                    } else {

                        $insert = "INSERT INTO `admin`( `admin_name`, `admin_email`, `admin_password`,`token`) VALUES ('$name','$email','$password','$token')";
                        $insert_gp = "INSERT INTO `global_people`( `email`,`password`,`role`) VALUES ('$email','$password','Admin')";

                        if ($conn->query($insert) && $conn->query($insert_gp)) {
                            echo "Data Inserted";


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
                        <h2>Congratulations $name,</h2>
                        <p>Your account has been created with the following credentials:</p>
                        <ul>
                            <li>Email: $email</li>
                            <li>Password: $password</li>
                        </ul>
                        <p style='color: red;'>Note: Please change your account password after logging in.</p>
                        <p>Click the link below to verify your account:</p>
                        <p>http://localhost/SOAC/verify.php?admin_email=$email&token=$token</p>";
                            // Send email
                            $mail->send();
                            echo "Mail is Sent";
                        }
                    }
                }
            }
            // View Records Section
            if (isset($_GET['view'])) {
            ?>
                <div class="main my-5 p-2">
                    <h2>Admin Records</h2>
                    <table id="myTable" class="table">
                        <thead>
                            <tr>
                                <th scope="col">Sr No.</th>
                                <th>Admin Name</th>
                                <th>Admin Email</th>
                                <th>Admin Password</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $select = "SELECT * FROM `admin`";
                            $res = $conn->query($select);

                            while ($row = $res->fetch_assoc()) {
                            ?>
                                <tr>
                                    <th scope="row"><?= $row['admin_id'] ?></th>
                                    <td><?= $row['admin_name'] ?></td>
                                    <td><?= $row['admin_email'] ?></td>
                                    <td><?= $row['admin_password'] ?></td>
                                    <td><?= $row['status'] ?></td>
                                    <td>
                                        <a href="delete_admin.php?admin_id=<?php echo $row['admin_id']; ?>">
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