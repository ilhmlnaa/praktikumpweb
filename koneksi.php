<?php
$host = 'localhost';
$port = '5432'; 
$dbname = 'praktikumpweb';
$user = 'postgres';
$password = 'ilham';

$koneksi = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

if (!$koneksi) {
    die("Koneksi ke PostgreSQL gagal: " . pg_last_error());
}
?>
