<?php
include 'koneksi.php';

$npm = $_GET['npm']; 
$query = "DELETE FROM mahasiswa WHERE npm = $1";

if (pg_query_params($koneksi, $query, array($npm))) {
    header('Location: index.php');
} else {
    echo "Gagal menghapus data: " . pg_last_error($koneksi);
}
?>
