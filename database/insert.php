<?php
include "connection.php";

// ==========================================
// INSERT ADDRESS
// ==========================================
$sql_address = "INSERT INTO alamat (user_id, province, city, district, village, address_detail) VALUES 
    (1, 'Jawa Barat', 'Bekasi', 'Mustika Jaya', 'Mustika Jaya', 'Jl. Angsana No. 123, RT 01/RW 02'),
    (2, 'DKI Jakarta', 'Jakarta Pusat', 'Tanah Abang', 'Kebon Melati', 'Jl. Mawar No. 45, RT 03/RW 05'),
    (3, 'Jawa Timur', 'Surabaya', 'Wonokromo', 'Darmo', 'Jl. Kenanga No. 67, RT 02/RW 04')";

if ($conn->query($sql_address)) {
    echo "Address inserted successfully<br>";
} else {
    echo "Error: " . $conn->error . "<br>";
}

echo "<br><strong>All data inserted successfully!</strong>";
$conn->close();
?>