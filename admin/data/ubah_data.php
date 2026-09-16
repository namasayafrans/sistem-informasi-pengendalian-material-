<?php
// Ambil ID dan data lama
$id = mysqli_real_escape_string($conn, $_GET['id']);
$query_ambil = mysqli_query($conn, "SELECT * FROM data_material WHERE id = '$id'");
$data = mysqli_fetch_assoc($query_ambil);

// Jika tombol update ditekan
if (isset($_POST['update_data'])) {
    $nama   = mysqli_real_escape_string($conn, $_POST['nama_material']);
    $part   = mysqli_real_escape_string($conn, $_POST['no_part']);
    $spec   = mysqli_real_escape_string($conn, $_POST['material_spec']);
    $size   = mysqli_real_escape_string($conn, $_POST['material_size']);
    $std    = mysqli_real_escape_string($conn, $_POST['standard']);
    $satuan = $_POST['satuan'];

    // Sinkronkan nama kolom database: material_spec, material_size, standard
    $update = mysqli_query($conn, "UPDATE data_material SET 
                nama_material = '$nama', 
                no_part = '$part', 
                material_spec = '$spec', 
                material_size = '$size', 
                standard = '$std', 
                satuan = '$satuan' 
                WHERE id = '$id'");
    
    if($update) {
        echo "<script>alert('Perubahan Berhasil Disimpan!'); window.location='?page=daftar-material';</script>";
    } else {
        echo "<script>alert('Gagal Update: " . mysqli_error($conn) . "');</script>";
    }
}
?>

<div class="max-w-3xl mx-auto mt-10 px-4">
    <div class="bg-white rounded-[2.5rem] border border-slate-200 shadow-sm overflow-hidden">
        <div class="bg-blue-600 p-8 text-white flex justify-between items-center">
            <div>
                <h3 class="font-bold uppercase italic tracking-tighter text-xl">Ubah Master Data</h3>
                <p class="text-[10px] text-blue-100 font-bold uppercase tracking-widest mt-1">ID Material: #<?= $data['id'] ?></p>
            </div>
            <i class="fas fa-edit text-3xl opacity-20"></i>
        </div>

        <form method="POST" class="p-10 grid grid-cols-2 gap-6">
            <div class="col-span-2">
                <label class="text-[10px] font-black uppercase text-blue-600 block mb-2 tracking-widest">Nama Material</label>
                <input type="text" name="nama_material" value="<?= $data['nama_material'] ?>" required class="w-full p-4 bg-slate-50 border border-slate-100 rounded-2xl font-bold outline-none focus:ring-2 focus:ring-blue-500 transition">
            </div>
            <div>
                <label class="text-[10px] font-black uppercase text-blue-600 block mb-2 tracking-widest">Nomor Part</label>
                <input type="text" name="no_part" value="<?= $data['no_part'] ?>" required class="w-full p-4 bg-slate-50 border border-slate-100 rounded-2xl font-bold outline-none focus:ring-2 focus:ring-blue-500 transition">
            </div>
            <div>
                <label class="text-[10px] font-black uppercase text-blue-600 block mb-2 tracking-widest">Satuan</label>
                <select name="satuan" class="w-full p-4 bg-slate-50 border border-slate-100 rounded-2xl font-bold outline-none focus:ring-2 focus:ring-blue-500 transition">
                    <option value="pcs" <?= ($data['satuan'] == 'pcs') ? 'selected' : '' ?>>pcs</option>
                    <option value="gr" <?= ($data['satuan'] == 'gr') ? 'selected' : '' ?>>gr</option>
                    <option value="ml" <?= ($data['satuan'] == 'ml') ? 'selected' : '' ?>>ml</option>
                </select>
            </div>
            <div>
                <label class="text-[10px] font-black uppercase text-blue-600 block mb-2 tracking-widest">Specification</label>
                <input type="text" name="material_spec" value="<?= $data['material_spec'] ?>" class="w-full p-4 bg-slate-50 border border-slate-100 rounded-2xl font-bold outline-none">
            </div>
            <div>
                <label class="text-[10px] font-black uppercase text-blue-600 block mb-2 tracking-widest">Standard</label>
                <input type="text" name="standard" value="<?= $data['standard'] ?>" class="w-full p-4 bg-slate-50 border border-slate-100 rounded-2xl font-bold outline-none">
            </div>
            <div class="col-span-2">
                <label class="text-[10px] font-black uppercase text-blue-600 block mb-2 tracking-widest">Size</label>
                <input type="text" name="material_size" value="<?= $data['material_size'] ?>" class="w-full p-4 bg-slate-50 border border-slate-100 rounded-2xl font-bold outline-none">
            </div>
            
            <div class="col-span-2 flex gap-4 mt-4">
                <button type="submit" name="update_data" class="flex-[2] py-5 bg-slate-900 text-white rounded-2xl font-black uppercase text-xs shadow-xl shadow-slate-200 hover:bg-blue-600 transition-all transform hover:-translate-y-1">Simpan Perubahan</button>
                <a href="?page=data-material" class="flex-1 py-5 bg-slate-100 text-slate-400 text-center rounded-2xl font-black uppercase text-xs">Batal</a>
            </div>
        </form>
    </div>
</div>