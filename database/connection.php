<?php
    $hostname = "localhost";

    $user_db = "root";
    $password_db = "";
    $db_name = "pajaken_uts";

    $conn = new mysqli ($hostname, $user_db, $password_db, $db_name);

    if ($conn -> connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
?>