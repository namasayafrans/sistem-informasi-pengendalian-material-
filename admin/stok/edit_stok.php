<?php
// 1. Ambil ID Stok dari URL
$id = mysqli_real_escape_string($conn, $_GET['id']);

// 2. Ambil data lama berdasarkan ID tabel stok_material
$query_old = "SELECT s.*, m.nama_material 
              FROM stok_material s 
              JOIN data_material m ON s.material_id = m.id 
              WHERE s.id = '$id'";
$result_old = mysqli_query($conn, $query_old);
$old_data = mysqli_fetch_assoc($result_old);

// Proteksi jika ID tidak valid
if (!$old_data) {
    echo "<script>alert('Data tidak ditemukan!'); window.location='?page=stok-material';</script>";
    exit;
}

// 3. Proses Update
if (isset($_POST['update_stok'])) {
    $stok_baru = mysqli_real_escape_string($conn, $_POST['stok_sekarang']);
    $stok_min  = mysqli_real_escape_string($conn, $_POST['stok_min']);
    $material_id = $old_data['material_id']; // Mengambil ID material asli untuk mutasi
    $tanggal = date('Y-m-d');
    
    // Hitung selisih untuk record mutasi (Koreksi)
    $selisih = $stok_baru - $old_data['stok_sekarang'];

    // Update Tabel Stok Utama
    $update = mysqli_query($conn, "UPDATE stok_material SET 
                                    stok_sekarang = '$stok_baru', 
                                    stok_minimum = '$stok_min',
                                    last_update = NOW() 
                                   WHERE id = '$id'");
    
    if ($update) {
        // Jika ada perubahan jumlah stok, catat di mutasi
        if ($selisih != 0) {
            $ket = "Koreksi Stok Manual (ID: $id)";
            mysqli_query($conn, "INSERT INTO stok_mutasi (material_id, tipe, jumlah, tanggal, keterangan) 
                                 VALUES ('$material_id', 'masuk', '$selisih', '$tanggal', '$ket')");
        }
        echo "<script>alert('Stok Berhasil Diperbarui!'); window.location='?page=stok-material';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui stok: " . mysqli_error($conn) . "');</script>";
    }
}
?>

<div class="max-w-xl mx-auto mt-10">
    <div class="bg-white rounded-[2.5rem] border border-slate-200 shadow-sm overflow-hidden">
        <div class="bg-slate-900 p-8 text-white text-center">
            <h3 class="font-bold uppercase italic tracking-tighter text-xl">Upgrade & Koreksi</h3>
            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">
                <?= htmlspecialchars($old_data['nama_material']) ?>
            </p>
        </div>
        
        <form method="POST" class="p-10 space-y-6">
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="text-[10px] font-black uppercase text-blue-600 block mb-2">Stok Saat Ini</label>
                    <input type="number" name="stok_sekarang" 
                           value="<?= $old_data['stok_sekarang'] ?>" required 
                           class="w-full p-4 bg-slate-50 border border-slate-100 rounded-2xl font-bold outline-none focus:border-blue-500 transition-all">
                </div>
                <div>
                    <label class="text-[10px] font-black uppercase text-blue-600 block mb-2">Batas Minimum</label>
                    <input type="number" name="stok_min" 
                           value="<?= $old_data['stok_minimum'] ?>" required 
                           class="w-full p-4 bg-slate-50 border border-slate-100 rounded-2xl font-bold outline-none focus:border-blue-500 transition-all">
                </div>
            </div>
            
            <div class="flex gap-4">
                <a href="?page=stok-material" class="flex-1 py-4 bg-slate-100 text-slate-400 text-center rounded-2xl font-black uppercase text-xs">Batal</a>
                <button type="submit" name="update_stok" class="flex-1 py-4 bg-blue-600 text-white rounded-2xl font-black uppercase text-xs shadow-lg shadow-blue-200 hover:bg-blue-700 transition-all">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>