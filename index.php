<?php

include_once('header.php');

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <section id="main">
        <?php

        // Fetch images from the database
        $query = "SELECT img FROM slider ORDER BY id ASC";
        $result = $conn->query($query);
        ?>

        <div id="carouselExampleFade" class="carousel slide carousel-fade" data-bs-ride="carousel">
            <div class="carousel-inner" data-aos="zoom-in-up">
                <?php
                if ($result->num_rows > 0) {
                    $isFirst = true; // Track first image to set as active
                    while ($row = $result->fetch_assoc()) {
                        $imgPath = "slider_Images/" . $row['img']; // Adjust path as per your directory
                ?>
                        <div class="carousel-item <?= $isFirst ? 'active' : '' ?>">
                            <img src="<?= $imgPath ?>" class="d-block" style="width: 1519px; height: 556px;" alt="Slider Image">
                        </div>
                <?php
                        $isFirst = false; // After first iteration, set `active` false
                    }
                } else {
                    echo '<div class="carousel-item active">
                    <img src="https://picsum.photos/1519/556?random=1" class="d-block" style="width: 1519px; height: 556px;" alt="Default Image">
                </div>';
                }
                ?>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleFade" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleFade" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>

      
    </section>

    <!-- Hero Section -->
    <?php
    $sql = "SELECT * FROM index_edit WHERE category = 'welcome'";
    $res = $conn->query($sql)->fetch_assoc();
    ?>
    <section id="hero" class="hero text-black text-center py-3">
        <div class="container">
            <h2 data-aos="zoom-in-up"><?= $res['text'] ?></h2>
        </div>
    </section>

    <!-- Events -->

    <?php
    $result = $conn->query("SELECT COUNT(*) AS events_count FROM events WHERE status = 'active'");

    $row = $result->fetch_assoc();
    if ($row['events_count'] != 0) {

        $res = $conn->query("SELECT * FROM events ORDER BY date ASC");
        $row = $res->fetch_assoc();
    ?>
        <section id="events" class="events">
            <div class="container text-center py-5">
                <h2 class="mb-4" data-aos="zoom-in-up">Events</h2>
                <div class="row text-center">
                    <div class="col-md-7 ">
                        <div class="card">
                            <img data-aos="zoom-in-up" src="uploads/<?= $row['img'] ?>" class="card-img-top" alt="<?= $row['img'] ?>" style="height: 350px; object-fit :fit;">
                        </div>
                    </div>
                    <div class="col-md-5 text-center" data-aos="zoom-in-up">
                        <div data-aos="zoom-in-up">
                            <h2 data-aos="zoom-in-up"> <?= $row['e_name'] ?></h2><br>
                            <p data-aos="zoom-in-up"><?= $row['e_des'] ?> </p><br>
                            <h4 data-aos="zoom-in-up">Event organiza By <?= $row['club_name'] ?> club </h4><br>
                            <h5 data-aos="zoom-in-up">Event Date : <?= date("d-m-Y", strtotime($row['date'])) ?> </h5><br>

                            <a class="button" href="<?= (strpos($row['link'], 'http') === 0) ? $row['link'] : 'https://' . $row['link']; ?>"
                                target="_blank" data-aos="zoom-in-up">
                                Appropriat Link
                            </a>
                        </div>
                    </div>


                </div>
        </section>
    <?php
    }
    ?>
    <!-- Auto Scroller Section -->
    <?php
    $images = $conn->query("SELECT * FROM gallery ORDER BY create_at DESC")->fetch_all();

    ?>
    <section id="blog" class="blog">
        <div class="container text-center py-5">
            <h2 class="mb-4" data-aos="zoom-in-up">Blogs</h2>
            <div class="row">

                <div class="col-md-4 " data-aos="zoom-in-up">
                    <h2 data-aos="zoom-in-up" class="m-3"> About Gallery</h2>

                    <div class="d-flex align-items-center justify-content-center text-center mt-2">
                        <?php
                        $sql1 = "SELECT * FROM index_edit WHERE category = 'blog'";
                        $res1 = $conn->query($sql1)->fetch_assoc();
                        ?>
                        <p data-aos="zoom-in-up">
                            <?= $res1['text'] ?>
                        </p>
                    </div>

                </div>

                <div class="col-md-8">
                    <div class="row g-3">
                        <?php
                        // Fetch images from database
                        $sql = "SELECT * FROM gallery ORDER BY create_at DESC";
                        $result = $conn->query($sql);

                        // Image placeholders (mapping images dynamically to layout)
                        $layout = [
                            ["col-md-7"],
                            ["col-md-5"],
                            ["col-md-3"],
                            ["col-md-5"],
                            ["col-md-4"],
                            ["col-md-5"],
                            ["col-md-7"]
                        ];

                        $i = 0;
                        while ($row = $result->fetch_assoc()) {
                            $col_class = $layout[$i][0]; // Get predefined column class
                        ?>
                            <div class="<?php echo $col_class; ?>">
                                <div class="img-box" data-bs-toggle="modal" data-bs-target="#imageModal" data-aos="zoom-in-up"
                                    data-bs-image="gallery/<?php echo $row['img_name']; ?>">
                                    <img src="gallery/<?php echo $row['img_name']; ?>" alt="<?php echo $row['img_text']; ?>">
                                    <div class="overlay">
                                        <h3><?php echo $row['img_text']; ?></h3>
                                    </div>
                                </div>
                            </div>
                        <?php
                            $i++;
                            if ($i >= count($layout)) break; // Stop if all layout slots are filled
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Image Modal -->
        <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <img id="modalImage" src="" alt="Large Image">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        // JavaScript to update modal image dynamically
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll(".img-box").forEach(function(imgBox) {
                imgBox.addEventListener("click", function() {
                    let imgSrc = imgBox.getAttribute("data-bs-image");
                    document.getElementById("modalImage").src = imgSrc;
                });
            });
        });
    </script>

    <!-- Sports Categories -->
    <section id="sport" class="sports-categories py-3">
        <div class="container text-center">
            <h2 class="mb-4">
                <span class="brand-rku">Explore Our </span>
                <span class="brand-soac"> SOAC</span>
            </h2>

            <div class="row">
                <?php
                $sel = "SELECT * FROM club ORDER BY id DESC LIMIT 3";
                $res = $conn->query($sel);
                while ($row = $res->fetch_assoc()) {

                ?>
                    <div class="col-md-4" data-aos="zoom-in-up">
                        <div class="card">
                            <img src="club_Images/<?= $row['club_img'] ?>" class="card-img-top" alt="Football" style="height: 300px; object-fit:cover">
                            <div class="card-body">
                                <h5 class="card-title"><?= $row['club_name'] ?></h5>
                                <p class="card-text"><?= $row['club_des'] ?></p>
                            </div>
                        </div>
                    </div>
                <?php
                }
                ?>

            </div>
            <a href="club.php"><button data-aos="zoom-in-up" class="btn m-3">Explore More</button></a>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="about py-3">
        <div class="container text-center my-5">
            <h2 class="mb-4" data-aos="zoom-in-up">
                <span class="brand-rku">About </span>
                <span class="brand-soac"> SOAC</span>
            </h2>
            <div class="row text-center">
                <div class="col-md-6 text-center">
                    <div data-aos="zoom-in-up">
                        <?php
                        $sql2 = "SELECT * FROM index_edit WHERE category = 'about'";
                        $res2 = $conn->query($sql2)->fetch_assoc();
                        ?>
                        <p><?= $res2['text'] ?></p>
                        <a href="about_club.php"><button class="btn">Read More</button></a>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <?php
                        $result = $conn->query("SELECT img FROM about");
                        while ($row2 = $result->fetch_assoc()) {

                        ?>
                            <img src="gallery/<?= $row2['img'] ?>" data-aos="zoom-in-up" class="card-img-top" alt="Football">
                        <?php
                        }
                        ?>
                    </div>
                </div>

            </div>
    </section>

    <section id="festival" class="py-3">
        <div class="container text-center">
            <h2 class="mb-4" data-aos="zoom-in-up">Upcoming Festivals</h2>
            <div class="row">
                <?php
                $result = $conn->query("SELECT * FROM festival ORDER BY date ASC LIMIT 3");
                while ($row = $result->fetch_assoc()) {
                ?>
                    <div class="col-md-4">
                        <div class="card" data-aos="zoom-in-up">
                            <img src="uploads/<?= $row['fes_img'] ?>" class="card-img-top" style="height: 200px;width:415px" alt="<?= $row['fes_img'] ?>">
                            <div class="card-body">
                                <h5 class="card-title"><?= $row['fes_name'] ?></h5>
                                <h6><?= date("d-m-Y", strtotime($row['date'])) ?></h6>
                            </div>
                        </div>
                    </div>
                <?php
                }
                ?>
            </div>
        </div>
    </section>

    <section id="signin" class="signin py-3" style="background-image: url('https://picsum.photos/1600/500?random=13'); background-size: cover; color: white; background-attachment: fixed; ">
        <div class="container text-center">
            <h2 class="mb-4" data-aos="zoom-in-up">Become a Member</h2>
            <p data-aos="zoom-in-up">Join one of our clubs to unleash your potential!</p>
            <a href="user_Register.php"><button class="btn btn-light" data-aos="zoom-in-up">Register</button></a>
        </div>
    </section>

    <?php

    include_once('footer.php');
    ?>
    <script src="css/script.js"></script>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init();
    </script>
</body>

</html>