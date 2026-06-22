<?php
if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);

    // 1. Ambil material_id sebelum data stok dihapus
    $res = mysqli_query($conn, "SELECT material_id FROM stok_material WHERE id = '$id'");
    $data = mysqli_fetch_assoc($res);
    
    if ($data) {
        $mat_id = $data['material_id'];

        // 2. HAPUS SEMUA LOG MUTASI material ini 
        // Agar grafik Overview sinkron (Data material yang sudah tidak ada tidak boleh muncul di grafik)
        mysqli_query($conn, "DELETE FROM stok_mutasi WHERE material_id = '$mat_id'");

        // 3. Hapus Data Stok Utama
        $delete = mysqli_query($conn, "DELETE FROM stok_material WHERE id = '$id'");
        
        if ($delete) {
            echo "<script>alert('Data stok & riwayat mutasi berhasil dibersihkan!'); window.location='?page=stok-material';</script>";
        }
    }
} else {
    echo "<script>window.location='?page=stok-material';</script>";
}
?>