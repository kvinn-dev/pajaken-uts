<?php
// cekpajak.php
// File ini HANYA untuk meresponse fetch dari JavaScript
// Tidak boleh ada HTML apapun!

$plat = $_GET['plat'] ?? '';

// Daftar plat yang terdaftar (simulasi database)
$registeredPlates = ['B 1234 ABC', 'DK 4567 BCD', 'B 7890 XYZ'];

if (in_array($plat, $registeredPlates)) {
    echo "ada";
} else {
    echo "tidak_ada";
}
?>