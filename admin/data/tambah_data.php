<?php
if (isset($_POST['simpan_data'])) {
    $nama   = mysqli_real_escape_string($conn, $_POST['nama_material']);
    $part   = mysqli_real_escape_string($conn, $_POST['no_part']);
    $spec   = mysqli_real_escape_string($conn, $_POST['material_spec']);
    $size   = mysqli_real_escape_string($conn, $_POST['material_size']);
    $std    = mysqli_real_escape_string($conn, $_POST['standard']);
    $satuan = $_POST['satuan'];

    $query = "INSERT INTO data_material (nama_material, no_part, material_spec, material_size, standard, satuan) 
              VALUES ('$nama', '$part', '$spec', '$size', '$std', '$satuan')";
    
    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Data Berhasil Disimpan!'); window.location='?page=daftar-material';</script>";
    }
}
?>

<div class="max-w-3xl mx-auto mt-10">
    <div class="bg-white rounded-[2.5rem] border border-slate-200 shadow-2xl overflow-hidden">
        <div class="bg-slate-900 p-8 text-white flex justify-between items-center">
            <h3 class="font-bold uppercase italic tracking-tighter">Tambah Master Data</h3>
            <i class="fas fa-cube text-blue-500"></i>
        </div>
        <form method="POST" class="p-10 grid grid-cols-2 gap-6">
            <div class="col-span-2">
                <label class="text-[10px] font-black uppercase text-blue-600 block mb-2 tracking-widest">Nama Material</label>
                <input type="text" name="nama_material" required class="w-full p-4 bg-slate-50 border border-slate-100 rounded-2xl font-bold outline-none focus:ring-2 focus:ring-blue-500 transition">
            </div>
            <div>
                <label class="text-[10px] font-black uppercase text-blue-600 block mb-2 tracking-widest">Nomor Part</label>
                <input type="text" name="no_part" required class="w-full p-4 bg-slate-50 border border-slate-100 rounded-2xl font-bold outline-none focus:ring-2 focus:ring-blue-500 transition">
            </div>
            <div>
                <label class="text-[10px] font-black uppercase text-blue-600 block mb-2 tracking-widest">Satuan</label>
                <select name="satuan" class="w-full p-4 bg-slate-50 border border-slate-100 rounded-2xl font-bold outline-none">
                    <option value="pcs">pcs</option>
                    <option value="gr">gr</option>
                    <option value="ml">ml</option>
                </select>
            </div>
            <div>
                <label class="text-[10px] font-black uppercase text-blue-600 block mb-2 tracking-widest">Spec (Material Spec)</label>
                <input type="text" name="material_spec" class="w-full p-4 bg-slate-50 border border-slate-100 rounded-2xl font-bold outline-none">
            </div>
            <div>
                <label class="text-[10px] font-black uppercase text-blue-600 block mb-2 tracking-widest">Standard</label>
                <input type="text" name="standard" class="w-full p-4 bg-slate-50 border border-slate-100 rounded-2xl font-bold outline-none">
            </div>
            <div class="col-span-2">
                <label class="text-[10px] font-black uppercase text-blue-600 block mb-2 tracking-widest">Size (Material Size)</label>
                <input type="text" name="material_size" class="w-full p-4 bg-slate-50 border border-slate-100 rounded-2xl font-bold outline-none">
            </div>
            <div class="col-span-2 pt-4">
                <button type="submit" name="simpan_data" class="w-full py-5 bg-blue-600 text-white rounded-2xl font-black uppercase text-xs shadow-lg shadow-blue-200">Simpan Ke Master Data</button>
            </div>
        </form>
    </div>
</div>