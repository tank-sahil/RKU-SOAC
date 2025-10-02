<?php
include_once('header.php');
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In</title>
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css"> -->
    <!-- <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script> -->
</head>

<body>
    <div class="container d-flex justify-content-center align-items-center my-5">
        <div class="w-100">
            <div class="main p-5 border shadow-sm mx-auto" style="max-width: 700px;">
                <h2>Sign In</h2>
                <form class="mt-3" method="post" id="form">
                    <div class="mb-3">
                        <input type="email" name="email" id="email" class="form-control" placeholder="Enter Email Address">
                    </div>
                    <div class="mb-3">
                        <input type="password" id="password" name="password" class="form-control" placeholder="Enter Password">
                    </div>
                    <input type="submit" name="submit" value="Sign In" class="button"><br><br>
                    <p>
                        <a href="#" class="a p-1">Forgot Password</a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</body>
<script src="css/validation.js"></script>

</html>

<?php

if (isset($_POST['submit'])) {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (!empty($email) && !empty($password)) {
        $sql = "SELECT * FROM global_people WHERE email = ? AND (role = 'Head' OR role = 'admin')";

        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("s", $email);

            if ($stmt->execute()) {
                $result = $stmt->get_result();

                if ($row = $result->fetch_assoc()) {
                    $db_password = $row['password'];
                    $role = $row['role'];

                    // ✅ Secure password verification
                    if ($password === $db_password) {
                        if ($row['status'] == 'active') {

                            $_SESSION['email'] = $email;
                            $_SESSION['role'] = $role;

                            // Redirect based on role
                            if ($role === 'Admin') {
                                echo "Welcome Admin";
                                echo ' <script>
                                window.location.href = "admin_dashboard.php";
                            </script>';
                            } elseif ($role === 'Head') {
                                echo "Welcome Head";
                                echo ' <script>
                                window.location.href = "admin_dashboard.php";
                            </script>';
                            } else {
                                echo "Invalid Role";
                            }
                            exit();
                        } else {
                            echo "Your Account is Not Active.";
                        }
                    } else {
                        echo "Invalid password!";
                    }
                } else {
                    echo "No user found with this email.";
                }
            } else {
                echo "Execution failed: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "Query preparation failed: " . $conn->error;
        }
    } else {
        echo "Please enter both email and password.";
    }
    exit();
}
?>