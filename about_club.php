<?php

include_once('header.php');

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>

    </style>
</head>

<body>
    <!-- About Us Section -->
    <section class="club_details">
        <div class="container my-5">
            <div class="row">
                <!-- Image Section -->
                <?php
                $result = $conn->query("SELECT img FROM about");
                while ($row2 = $result->fetch_assoc()) {
                ?>
                    <div class="col-md-6">
                        <div class="card h-100">
                            <img src="gallery/<?= $row2['img'] ?>" class="img-fluid rounded shadow h-100" alt="Sport Image">
                        </div>
                    </div>
                <?php
                } ?>

                <!-- Details Section -->
                <div class="col-md-6">
                    <p>
                    <h3>Introduction</h3>
                    <p>RKU believes that organizations under the leadership of students can enhance a student’s education by providing additional opportunities beyond the curriculum for personal development and growth. The University also benefits from the variety of services and activities provided by student organizations. RKU plans to initiate and sustain student participation in organizations that are open to all members of the community and whose activities do not interfere with the policies or programs of the University. Student Organizations Advisory Council is being established to assist students in developing organizations and planning events, in some cases provide financial advice and assistance, clarify University policies and procedures, and authorize the use of University resources and facilities. We are forming a task force to deliberate on the establishment of this council.

                    </p>
                    </p>
                </div>
                <br>
                <p> <br>Student organizations are an essential part of RK University. They provide students with a variety of opportunities to explore academic, cultural & recreational, sports & outdoor, social and community service interests. Student organizations allow students to develop interpersonal, organizational and leadership skills in a supportive yet challenging environment.
                </p>
            </div>
            <hr>

            <div class="mt-3">
                <h3>
                    <span class="brand-rku">About </span>
                    <span class="brand-soac"> SOAC</span>
                </h3>
                <p>
                    Student organizations at RK University are defined as those student-run organizations that have received university recognition.
                </p>
                <p>
                    Recognized student organizations are all student organizations that are formed by currently enrolled students sharing a common goal or interest and have properly completed the necessary forms on time and agree to adhere to guidelines established by the University.
                </p>
                <p>
                    The University reserves the right to recognize all student organizations. The purpose of recognition is for RKU to acknowledge an organization’s presence on campus and assist in the formation and overall operation of student organizations. University authority maintains all the current information about each organization.
                </p>
            </div>

            <div class="mt-3">
                <h3>Objetives </h3>
                <p>
                    To provide opportunities for the enhancement of academic, cultural & recreational , social, sports and outdoor activity aspects of students’ life through participation in group programs and activitiesTo encourage all students to be in the student organizations of their choice and assist students to increase their knowledge and skill in functioning a particular organization (i.e. planning, delegating, decision making).To assist students to develop a more positive and realistic attitude toward themselves, their peers and the university.To promote community awareness and responsibility through professional conferences, chapter activities, and community services.
                </p>
            </div>

            <div class="mt-3">
                <h3>
                    <span class="brand-soac">SOAC</span>
                    <span class="brand-rku">Task Force</span>
                </h3>
                <?php
                $res = $conn->query("SELECT task_force FROM about");
                while ($row = $res->fetch_assoc()) {
                ?>
                    <?= $row['task_force'] ?>
                <?php
                }
                ?>

            </div>
    </section>
</body>
<?php

include_once('footer.php');

?>

</html>