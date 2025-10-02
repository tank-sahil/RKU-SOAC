<?php
// include "admin_header.php"; // Ensure database connection
$conn = new mysqli("localhost", "root", "", "soac");

if (isset($_GET['club'])) {
    $club = $_GET['club'];
    $stmt = $conn->prepare("SELECT head_email FROM club WHERE club_name = ?");
    $stmt->bind_param("s", $club);
    $stmt->execute();
    $stmt->bind_result($email);
    $stmt->fetch();
    echo $email; // Output the email
    $stmt->close();
}
?>
