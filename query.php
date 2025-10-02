<?php

$conn = new mysqli("localhost", "root", "", "soac");

$result = $conn->query("SELECT * FROM festival ORDER BY fes_date ASC");

while ($row = $result->fetch_assoc()) {
    echo "<pre>";
    print_r($row);
    echo "</pre>";
}

?>