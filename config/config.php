<?php

$host     = "sql101.infinityfree.com";
$username = "if0_42251939";
$password = "QwertyuioP13";
$database = "if0_42251939_batarasura_db";

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Koneksi database gagal : " . mysqli_connect_error());
}

?>