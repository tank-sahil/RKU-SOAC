<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RKU | SOAC</title>

    <link rel="icon" href="images/logo.png">

    <!-- Style css -->
    <link rel="stylesheet" href="css/style.css">

    <link rel="stylesheet" href="Links/bootstrap.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <script src="Links/bootstrap.bundle.min.js"></script>
    <script src="Links/jquery-3.6.4.min.js"></script>
    <script src="Links/jquery.validate.min.js"></script>
    <script src="css/validation.js"></script>

</head>

<body data-bs-spy="scroll" data-bs-target="#navbarNav" data-bs-offset="100" tabindex="0">

    <?php

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    require('C:\xampp\htdocs\SOAC\PHPMailer-20250124T110156Z-001\PHPMailer\PHPMailer.php');
    require('C:\xampp\htdocs\SOAC\PHPMailer-20250124T110156Z-001\PHPMailer\SMTP.php');
    require('C:\xampp\htdocs\SOAC\PHPMailer-20250124T110156Z-001\PHPMailer\Exception.php');

    $mail = new PHPMailer(); // Enable exceptions
    $conn = new mysqli("localhost", "root", "", "soac");

    ?>


    <nav class="navbar sticky-top navbar-expand-lg navbar-light bg-light w-100">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <h3 class="my-2">
                    <span class="brand-rku">RKU | </span>
                    <span class="brand-soac">SOAC</span>
                </h3>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item mx-3">
                        <a class="nav-link active" href="index.php#main">Home</a>
                    </li>
                    <?php
                    $result = $conn->query("SELECT COUNT(*) AS events_count FROM events WHERE status = 'active'");

                    $row = $result->fetch_assoc();
                    if ($row['events_count'] != 0) {
                    ?>
                        <li class="nav-item mx-3">
                            <a class="nav-link" href="index.php#events">Events</a>
                        </li>
                    <?php
                    }
                    ?>

                    <li class="nav-item mx-3">
                        <a class="nav-link" href="index.php#blog">Gallery</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="index.php#sport" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            Clubs
                        </a>
                        <ul class="dropdown-menu">
                            <?php
                            $sel = "SELECT * FROM categories";
                            $res = $conn->query($sel);
                            while ($row = $res->fetch_assoc()) {
                                echo '<li><a class="dropdown-item" href="club.php#' . $row['name'] . '">' . $row['name'] . '</a></li>';
                            }
                            ?>
                        </ul>
                    </li>
                    <li class="nav-item mx-3">
                        <a class="nav-link" href="index.php#about">About SOAC</a>
                    </li>
                    <li class="nav-item mx-3">
                        <a class="nav-link" href="index.php#festival">Festivals</a>
                    </li>
                    <li class="nav-item mx-3">
                        <a class="nav-link" href="index.php#signin">Register</a>
                    </li>
                    <li class="nav-item mx-3">
                        <a class="nav-link" href="index.php#contactus">Contact Us</a>
                    </li>
                </ul>
            </div>
            <ol class="breadcrumb">
                <!-- Breadcrumbs will be dynamically inserted here by JavaScript -->
            </ol>
        </div>

    </nav>

    <!--Spinner Loader -->
    <div id="loader" class="loader-wrapper">
        <div class="spinner"></div>
    </div>

    <script>
        window.addEventListener('load', function() {
            document.body.classList.add('loaded');
        });


        $(document).ready(function() {
            // Close the navbar when a link is clicked (for mobile view)
            $('.navbar-nav a').on('click', function() {
                $('.navbar-collapse').collapse('hide');
            });

            // Remove active class from all links
            $('.navbar-nav .nav-link').removeClass('active');

            // Add active class to the clicked link
            $('.navbar-nav').on('click', '.nav-link', function() {
                $('.navbar-nav .nav-link').removeClass('active');
                $(this).addClass('active');
            });

            // Highlight the active page based on the current URL
            var currentPage = window.location.pathname.split("/").pop();

            $('.navbar-nav .nav-link').each(function() {
                var linkPage = $(this).attr('href').split("/").pop();
                if (linkPage === currentPage) {
                    $(this).addClass('active'); // Highlight the current page
                }
            });

            if (currentPage === "about_club.php") {
                $('.navbar-nav .nav-link[href*="#about"]').addClass('active');
            }
            if (currentPage === "user_Register.php") {
                $('.navbar-nav .nav-link[href*="#signin"]').addClass('active');
            }

        });
    </script>
    <script>
        $(document).ready(function() {
            var currentPage = window.location.pathname.split("/").pop();
            $('.navbar-nav .nav-link[href$="' + currentPage + '"]').addClass('active');

            // Breadcrumb navigation
            var crumbs = window.location.pathname.split("/").filter(Boolean);
            var breadcrumbHtml = '';
            var path = "";

            for (var i = 0; i < crumbs.length; i++) {
                path += "/" + crumbs[i];
                var formattedCrumb = crumbs[i].replace(/[-_]/g, ' ').replace(/.php$/, '').toUpperCase();
                if (i === crumbs.length - 1) {
                    breadcrumbHtml += '<li class="breadcrumb-item active" aria-current="page">' + formattedCrumb + '</li>';
                } else {
                    breadcrumbHtml += '<li class="breadcrumb-item"><a href="' + path + '">' + formattedCrumb + '</a></li>';
                }
            }

            $(".breadcrumb").html(breadcrumbHtml);
        });
    </script>
</body>

</html>