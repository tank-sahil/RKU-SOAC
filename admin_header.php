<?php

session_start();

if (isset($_SESSION['role']) != 'Admin' && isset($_SESSION['role']) != 'Head') {
?>
    <script>
        window.location.href = 'user_sign_in.php';
    </script>

<?php
}

$conn = new mysqli("localhost", "root", "", "soac");

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RKU | SOAC</title>
    <link rel="icon" href="images/logo.png">

    <!-- Stylesheets -->
    <link rel="stylesheet" href="css/admin_style.css">
    <link rel="stylesheet" href="Links/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css">

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="Links/jquery.validate.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
    <script src="Links/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <!-- Custom Scripts -->
    <script src="css/validation.js"></script>

    <script src="ckeditor/ckeditor.js"></script>
</head>


<body>

    <div class="container-fluid">
        <!-- Sidebar -->
        <div class="sidebar bg-white text-center mb-3" data-aos="fade-down-right" id="sidebar">
            <div class="arc text-center">

                <!-- <h5 class="h5"> -->
                <span class="brand-rku"> SOE | </span>
                <span class="brand-soac">CARE DESK</span>
                <!-- </h5> -->

            </div>
            <hr>
            <?php
            $email = $_SESSION['email'];
            echo $_SESSION['role'];

            if ($_SESSION['role'] == 'Admin') {
            ?>
                <hr>
                <a href="admin_dashboard.php" class="a"><i></i> Dashboard</a>
                <a href="admin_edit_page.php?club=" class="a"><i></i> Edit Pages</a>
                <a href="admin_gallery.php?records=" class="a"><i></i> Edit Gallery</a>
                <a href="admin_category_add.php?view=" class="a"><i></i> Add category</a>
                <a href="admin_festival.php?view=" class="a"><i></i> Festival</a>
                <a href="add_admin.php?view=view" class="a"><i></i> Add Admin</a>
                <a href="admin_event.php?view=" class="a"><i></i> Add Event</a>
                <a href="admin_userApproval.php?approval=" class="a"><i></i> User Approval</a>
                <a href="setting.php" class="a"><i></i> Setting</a>
            <?php
            } elseif ($_SESSION['role'] == 'Head') {

                $sql = "SELECT * FROM club WHERE head_email = '$email' ";
                $res = $conn->query($sql);

                if ($res->num_rows > 0) {
                    while ($row = $res->fetch_assoc()) {
                        $_SESSION['club_id'] = $row['id'];

                        echo $_SESSION['club_id'];
                    }
                } else {
                    echo "No club found for this email.";
                }
            ?>
                <hr>
                <a href="admin_edit_page.php" class="a"><i></i> Edit Pages</a>
                <a href="admin_event.php" class="a"><i></i> Add Event</a>
                <a href="admin_userApproval.php" class="a"><i></i> User Approval</a>
            <?php
            } else {
                echo "Invalid Role";
            } ?>
        </div>

        <!-- Content -->
        <div class="content" data-aos="fade-left" id="mainContent">
            <!-- Navbar -->

            <!-- Navbar -->
            <nav class="navbar navbar-light bg-light">
                <div class="container-fluid d-flex align-items-center">
                    <!-- Sidebar toggle button -->
                    <button class="button me-3" id="toggleSidebar">☰</button>

                    <!-- Search bar -->
                    <form class="d-flex flex-grow-1 me-3">
                        <div class="btn-group" role="group" aria-label="Basic example">

                            <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">

                        </div>
                    </form>

                    <!-- Icons on the right -->
                    <div class="d-flex align-items-center my-3 ">

                        <div class="d-flex align-items-center">
                            <!-- Bell Icon with Count -->
                            <div class="position-relative me-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-bell-fill" viewBox="0 0 16 16">
                                    <path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2m.995-14.901a1 1 0 1 0-1.99 0A5 5 0 0 0 3 6c0 1.098-.5 6-2 7h14c-1.5-1-2-5.902-2-7 0-2.42-1.72-4.44-4.005-4.901" />
                                </svg>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    5
                                </span>
                            </div>

                            <!-- Message Icon with Count -->
                            <div class="position-relative">
                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-envelope-fill" viewBox="0 0 16 16">
                                    <path d="M.05 3.555A2 2 0 0 1 2 2h12a2 2 0 0 1 1.95 1.555L8 8.414.05 3.555zM0 4.697v7.104l5.803-3.969L0 4.697zm6.761 4.396L0 12h16l-6.761-2.907L8 10.586l-1.239-.493zm4.436-.66L16 11.801V4.697l-5.803 3.736z" />
                                </svg>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    8
                                </span>
                            </div>
                        </div>

                        <div class="dropdown">
                            <a class="nav-link dropdown-toggle p-0 mx-3" href="#" id="profileDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-person-fill" viewBox="0 0 16 16">
                                    <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6" />
                                </svg>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                                <li>
                                    <a class="dropdown-item" href="admin_profile.php">Edit Profile</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="admin_logout.php">Logout</a>
                                </li>
                            </ul>
                        </div>

                    </div>
                </div>
            </nav>
            <?php

            use PHPMailer\PHPMailer\PHPMailer;
            use PHPMailer\PHPMailer\SMTP;
            use PHPMailer\PHPMailer\Exception;

            require('C:\xampp\htdocs\SOAC\PHPMailer-20250124T110156Z-001\PHPMailer\PHPMailer.php');
            require('C:\xampp\htdocs\SOAC\PHPMailer-20250124T110156Z-001\PHPMailer\SMTP.php');
            require('C:\xampp\htdocs\SOAC\PHPMailer-20250124T110156Z-001\PHPMailer\Exception.php');

            $mail = new PHPMailer(); // Enable exceptions


            ?>
            <nav aria-label="breadcrumb" class="d-none d-lg-block">
                <ol class="breadcrumb mb-0">
                    <!-- Breadcrumb content will be dynamically inserted here -->
                </ol>
            </nav>
        </div>
    </div>
    <!-- Add Bootstrap JS -->
    <script>
        document.getElementById('toggleSidebar').addEventListener('click', function() {
            const sidebar = document.getElementById('sidebar');
            const content = document.getElementById('mainContent');
            const contentDuplicate = document.getElementById('mainContentDuplicate');
            const isMobile = window.innerWidth <= 576; // Check if it's a mobile screen

            if (isMobile) {
                // Mobile behavior: hide/show the sidebar
                if (sidebar.classList.contains('open')) {
                    sidebar.classList.remove('open');
                    sidebar.style.left = '-125px';
                    content.style.marginLeft = '0';
                    contentDuplicate.style.marginLeft = '0';
                } else {
                    sidebar.classList.add('open');
                    sidebar.style.left = '0';
                    content.style.marginLeft = '125px';
                    contentDuplicate.style.marginLeft = '125px';
                }
            } else {
                // Laptop/Desktop behavior: toggle between full and compact width
                if (sidebar.classList.contains('closed')) {
                    sidebar.classList.remove('closed');
                    content.style.marginLeft = '250px';
                    contentDuplicate.style.marginLeft = '250px';
                } else {
                    sidebar.classList.add('closed');
                    content.style.marginLeft = '125px';
                    contentDuplicate.style.marginLeft = '125px';
                }
            }
        });

        // Optional: Handle window resize to reset styles
        window.addEventListener('resize', function() {
            const sidebar = document.getElementById('sidebar');
            const content = document.getElementById('mainContent');
            const contentDuplicate = document.getElementById('mainContentDuplicate');
            const isMobile = window.innerWidth <= 576;

            if (isMobile) {
                sidebar.classList.remove('closed'); // Remove compact state on mobile
                sidebar.style.left = '-125px';
                sidebar.classList.remove('open'); // Ensure it's hidden by default
                content.style.marginLeft = '0';
                contentDuplicate.style.marginLeft = '0';
            } else {
                sidebar.style.left = '0'; // Ensure sidebar is visible on larger screens
                sidebar.classList.add('closed'); // Start in compact mode
                content.style.marginLeft = '125px';
                contentDuplicate.style.marginLeft = '125px';
            }
        });
    </script>
    <script>
        AOS.init();
    </script>
    <script>
        $(document).ready(function() {
            var currentPage = window.location.pathname.split("/").pop();

            // Sidebar & Navbar Active State
            $('.sidebar a').each(function() {
                var linkPage = $(this).attr('href').split("/").pop();
                if (linkPage === currentPage) {
                    $(this).addClass('active'); // Add 'active' class to highlight the current page
                }
            });

            // Breadcrumb Navigation (SOAC / Page Name)
            var breadcrumbHtml = '<li class="breadcrumb-item"><a href="admin_dashboard.php">SOAC</a></li>';
            var pageName = currentPage || "Dashboard"; // Default to Dashboard if empty
            var formattedCrumb = pageName.replace(/[-_]/g, ' ').replace(/.php$/, '').replace(/\b\w/g, c => c.toUpperCase());

            breadcrumbHtml += '<li class="breadcrumb-item active" aria-current="page">' + formattedCrumb + '</li>';

            $(".breadcrumb").html(breadcrumbHtml);
        });
    </script>



</body>

</html>