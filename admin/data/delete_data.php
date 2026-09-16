<?php
if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);

    // Hapus mutasi dan stok dulu agar tidak eror (SINKRON)
    mysqli_query($conn, "DELETE FROM stok_mutasi WHERE material_id = '$id'");
    mysqli_query($conn, "DELETE FROM stok_material WHERE material_id = '$id'");

    // Baru hapus master data
    $delete = mysqli_query($conn, "DELETE FROM data_material WHERE id = '$id'");

    if ($delete) {
        echo "<script>alert('Data Material Berhasil Dihapus!'); window.location='?page=daftar-material';</script>";
    }
}
?>