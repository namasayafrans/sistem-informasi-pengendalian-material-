<?php
// 1. Ambil ID dari URL jika ada untuk fitur auto-select
$selected_id = isset($_GET['id']) ? $_GET['id'] : '';

// 2. Ambil daftar material untuk dropdown
$list_material = mysqli_query($conn, "SELECT id, nama_material, no_part FROM data_material ORDER BY nama_material ASC");

if (isset($_POST['simpan_stok'])) {
    $material_id = mysqli_real_escape_string($conn, $_POST['material_id']);
    $jumlah      = mysqli_real_escape_string($conn, $_POST['jumlah']);
    $stok_min    = mysqli_real_escape_string($conn, $_POST['stok_min']);
    $tanggal     = date('Y-m-d');

    // Cek apakah data sudah ada di stok_material
    $cek = mysqli_query($conn, "SELECT id FROM stok_material WHERE material_id = '$material_id'");
    
    if (mysqli_num_rows($cek) > 0) {
        // Jika ada, tambahkan ke stok yang sudah ada
        $query_utama = "UPDATE stok_material SET stok_sekarang = stok_sekarang + $jumlah, stok_minimum = '$stok_min' WHERE material_id = '$material_id'";
    } else {
        // Jika belum ada, buat baris stok baru
        $query_utama = "INSERT INTO stok_material (material_id, stok_sekarang, stok_minimum) VALUES ('$material_id', '$jumlah', '$stok_min')";
    }

    if (mysqli_query($conn, $query_utama)) {
        // Catat ke tabel mutasi tanpa kolom keterangan
        mysqli_query($conn, "INSERT INTO stok_mutasi (material_id, tipe, jumlah, tanggal) VALUES ('$material_id', 'masuk', '$jumlah', '$tanggal')");
        echo "<script>alert('Stok Berhasil Diperbarui!'); window.location='?page=stok-material';</script>";
    }
}
?>

<div class="max-w-2xl mx-auto relative pt-12 pb-12">
    <a href="?page=stok-material" 
       class="absolute top-16 right-6 bg-blue-500 hover:bg-red-500 text-white w-8 h-8 rounded-full flex items-center justify-center backdrop-blur-md z-30 transition-all duration-300 shadow-sm group">
        <span class="font-bold text-xl leading-none group-hover:scale-110">×</span>
    </a>

    <div class="bg-white rounded-[3.5rem] shadow-2xl border border-slate-100 overflow-hidden relative">
       
        
        <form method="POST" class="p-12 space-y-8">
            <div>
                <label class="text-[10px] font-black uppercase text-blue-600 tracking-[0.2em] block mb-3 ml-1">Pilih Material</label>
                <select name="material_id" required 
                        class="w-full p-5 bg-slate-50 border border-slate-100 rounded-[1.5rem] font-bold text-slate-700 outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all appearance-none cursor-pointer">
                    <option value="">-- Cari Material --</option>
                    <?php while($m = mysqli_fetch_assoc($list_material)): ?>
                        <option value="<?= $m['id'] ?>" <?= ($selected_id == $m['id']) ? 'selected' : '' ?>>
                            <?= $m['nama_material'] ?> (<?= $m['no_part'] ?>)
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="grid grid-cols-2 gap-8">
                <div>
                    <label class="text-[10px] font-black uppercase text-blue-600 tracking-[0.2em] block mb-3 ml-1">Jumlah Masuk</label>
                    <input type="number" name="jumlah" required placeholder="0" 
                           class="w-full p-5 bg-slate-50 border border-slate-100 rounded-[1.5rem] font-black text-slate-700 outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all">
                </div>
                <div>
                    <label class="text-[10px] font-black uppercase text-blue-600 tracking-[0.2em] block mb-3 ml-1">Batas Minimum</label>
                    <input type="number" name="stok_min" value="10" required 
                           class="w-full p-5 bg-slate-50 border border-slate-100 rounded-[1.5rem] font-black text-slate-700 outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all">
                </div>
            </div>

            <div class="pt-4">
                <div class="flex justify-center w-full">
            <button type="submit" name="simpan_stok" 
                class="py-6 px-10 bg-blue-600 text-white rounded-[1.5rem] font-black uppercase text-sm tracking-widest shadow-xl shadow-blue-200 hover:bg-blue-700 hover:-translate-y-1 active:scale-95 transition-all duration-300">
                Simpan Stok 
            </button>
</div>
            </div>
        </form>
    </div>
</div>