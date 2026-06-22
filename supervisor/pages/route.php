<?php
// Ambil variabel $page yang sudah didefinisikan di index.php
switch ($page) {
    case 'overview':
        include 'overview.php'; // Pastikan file overview.php ada di folder pages
        break;
        
    case 'monitoring-stok':
        include 'monitoring_stok.php';
        break;

    default:
        include 'overview.php';
        break;
}
?>