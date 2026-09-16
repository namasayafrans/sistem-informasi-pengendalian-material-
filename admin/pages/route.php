<?php
$page = isset($_GET['page']) ? $_GET['page'] : 'overview';

switch($page) {
    // --- A. DATA MATERIAL ---
    case 'daftar-material': 
        include 'data/data_material.php'; 
        break;
    case 'tambah-data':   
        include 'data/tambah_data.php'; 
        break;
    case 'ubah-data':     
        include 'data/ubah_data.php'; 
        break;
    case 'delete-data':   
        include 'data/delete_data.php'; 
        break;

    // --- B. STOK MATERIAL ---
    case 'stok-material': 
        include 'stok/stok_material.php'; 
        break;
    case 'tambah-stok':   
        include 'stok/tambah_stok.php'; 
        break;
    case 'edit-stok':     
        include 'stok/edit_stok.php'; 
        break;
    case 'delete-stok':   
        include 'stok/delete_stok.php'; 
        break;

    // --- C. SIMULASI PRODUKSI ---
    case 'proses-produksi':      
        include 'simulasi_produksi.php'; 
        break;

    // --- D. LAPORAN (Perbaikan di Sini) ---
    case 'laporan-stok':
		include 'laporan/stok_material.php';
        break;
    case 'laporan-produksi':
       include 'laporan/produksi.php';
        break;

    // --- DEFAULT (OVERVIEW) ---
    case 'overview': 
    default: 
        include 'overview.php'; 
        break;
}
?>