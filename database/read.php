<?php
    include "connection.php";

    $sql = "SELECT * FROM users";
    $result = $conn->query($sql);

    while($row = $result->fetch_assoc()) {
        echo "NIK: " . $row["nik"]. " | Name: " . $row["first_name"] . " " . $row["last_name"]. " | Email: " . $row["email"]. " | Phone: " . $row["phone"]. "<br>";
    }   

    $conn->close();
?>