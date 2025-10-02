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
            <!-- Navigation Form -->
            <form method="get">
                <button class="button" name="approval">User Approval</button>
                <button class="button" name="approv">Approv</button>
                <button class="button" name="reject">Reject</button>
            </form>

            <?php

            if (isset($_GET['approval'])) {
            ?>
                <div class="main my-5 p-2">
                    <h2>User Approval Records</h2>
                    <table id="myTable" class="table">
                        <thead>
                            <tr>
                                <th scope="col">Sr No.</th>
                                <th>Club Id</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Department</th>
                                <th>Email</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            
                            if (in_array($_SESSION['role'], ['Admin', 'Head'])) {
                                $select = ($_SESSION['role'] == 'Head')
                                    ? "SELECT * FROM user WHERE club = {$_SESSION['club_id']} AND status IS NULL"
                                    : "SELECT * FROM user WHERE status IS NULL";
                            } else {
                                exit("Invalid Role");
                            }

                            $res = $conn->query($select);
                            while ($row = $res->fetch_assoc()) {
                            ?>
                                <tr>
                                    <th scope="row"><?= $row['id'] ?></th>
                                    <td><?= $row['club'] ?></td>
                                    <td><?= $row['lname'] ?></td>
                                    <td><?= $row['fname'] ?></td>
                                    <td><?= $row['dept'] ?></td>
                                    <td><?= $row['email'] ?></td>
                                    <td>
                                        <a href="approv.php?approv_id=<?php echo $row['id']; ?>">
                                            <button class="button">Approv</button>
                                        </a>

                                        <a href="approv.php?reject_id=<?php echo $row['id']; ?>">
                                            <button class="button">Reject</button>
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

            if (isset($_GET['approv'])) {
            ?>

                <div class="main my-5 p-2">
                    <h2>Approv Records</h2>
                    <table id="myTable" class="table">
                        <thead>
                            <tr>
                                <th scope="col">Sr No.</th>
                                <th>Club Id</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Department</th>
                                <th>Email</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php

                            if (in_array($_SESSION['role'], ['Admin', 'Head'])) {
                                $select = ($_SESSION['role'] == 'Head')
                                    ? "SELECT * FROM user WHERE club = {$_SESSION['club_id']} AND status = 'Approv'"
                                    : "SELECT * FROM user WHERE status = 'Approv'";
                            } else {
                                exit("Invalid Role");
                            }

                            $res = $conn->query($select);

                            while ($row = $res->fetch_assoc()) {
                            ?>
                                <tr>
                                    <th scope="row"><?= $row['id'] ?></th>
                                    <td><?= $row['club'] ?></td>
                                    <td><?= $row['lname'] ?></td>
                                    <td><?= $row['fname'] ?></td>
                                    <td><?= $row['dept'] ?></td>
                                    <td><?= $row['email'] ?></td>

                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            <?php
            }
            if (isset($_GET['reject'])) {
            ?>

                <div class="main my-5 p-2">
                    <h2>Reject Records</h2>
                    <table id="myTable" class="table">
                        <thead>
                            <tr>
                                <th scope="col">Sr No.</th>
                                <th>Club Id</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Department</th>
                                <th>Email</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (in_array($_SESSION['role'], ['Admin', 'Head'])) {
                                $select = ($_SESSION['role'] == 'Head')
                                    ? "SELECT * FROM user WHERE club = {$_SESSION['club_id']} AND status = 'Reject'"
                                    : "SELECT * FROM user WHERE status = 'Reject'";
                            } else {
                                exit("Invalid Role");
                            }
                            $res = $conn->query($select);

                            while ($row = $res->fetch_assoc()) {
                            ?>
                                <tr>
                                    <th scope="row"><?= $row['id'] ?></th>
                                    <td><?= $row['club'] ?></td>
                                    <td><?= $row['lname'] ?></td>
                                    <td><?= $row['fname'] ?></td>
                                    <td><?= $row['dept'] ?></td>
                                    <td><?= $row['email'] ?></td>

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