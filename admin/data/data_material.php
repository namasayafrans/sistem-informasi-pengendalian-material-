<?php
// Query diperbarui: Mengurutkan berdasarkan nama_material (A-Z)
$query_mat = "SELECT * FROM data_material ORDER BY nama_material ASC";
$result_mat = mysqli_query($conn, $query_mat);
?>

<div class="bg-white rounded-[2.5rem] border border-slate-200 shadow-sm overflow-hidden transition-all duration-500 hover:shadow-xl">
    <div class="p-10 border-b border-slate-50 flex justify-between items-center bg-gradient-to-r from-white to-slate-50/50">
   
        
        <a href="?page=tambah-data" class="group relative flex items-center gap-3 bg-slate-900 text-white px-8 py-4 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-blue-600 transition-all duration-300">
            <span class="relative z-10">Tambah Material</span>
            <i class="fas fa-plus relative z-10 group-hover:rotate-90 transition-transform"></i>
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-50/50 text-slate-400 font-black uppercase text-[9px] tracking-[0.2em]">
                <tr>
                    <th class="p-6">No</th>
                    <th class="p-6">Material & Part</th>
                    <th class="p-6">Spec</th>
                    <th class="p-6">Size</th>
                    <th class="p-6 text-right">Standart</th> <th class="p-6">Satuan</th>
                    <th class="p-6">Dibuat</th>
                    <th class="p-6 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php 
                $no = 1;
                while($row = mysqli_fetch_assoc($result_mat)): ?>
                <tr class="group hover:bg-slate-50/80 transition-all duration-300">
                    <td class="p-6 text-slate-400 font-bold"><?= $no++ ?></td>
                    <td class="p-6">
                        <div class="flex flex-col">
                            <span class="font-bold text-slate-700 text-base group-hover:text-blue-600 transition-colors"><?= $row['nama_material'] ?></span>
                            <span class="text-[10px] font-mono text-blue-600 uppercase tracking-tighter"><?= $row['no_part'] ?></span>
                        </div>
                    </td>
                    <td class="p-6 italic text-slate-500"><?= $row['material_spec'] ?></td>
                    <td class="p-6 font-bold text-slate-700 uppercase text-xs"><?= $row['material_size'] ?></td>
                    
                    <td class="p-6 text-slate-700 font-mono text-right font-bold">
                        <?= number_format($row['standard'], 2, ',', '.') ?>
                    </td>
                    <td class="p-6">
                        <span class="bg-slate-100 px-2 py-1 rounded text-[12px] font-black lowercase text-slate-500"><?= $row['satuan'] ?></span>
                    </td>
                    <td class="p-6 text-slate-400 text-[11px]">
                        <?= date('d M Y', strtotime($row['created_at'])) ?>
                    </td>
                    <td class="p-6">
                        <div class="flex justify-center items-center gap-2">
                            <a href="?page=ubah-data&id=<?= $row['id'] ?>" class="w-10 h-10 flex items-center justify-center rounded-xl bg-white border border-slate-100 text-slate-400 hover:border-blue-500 hover:bg-blue-500 hover:text-white transition-all shadow-sm">
                                <i class="fas fa-edit text-[10px]"></i>
                            </a>
                            <a href="?page=delete-data&id=<?= $row['id'] ?>" onclick="return confirm('Hapus material ini?')" class="w-10 h-10 flex items-center justify-center rounded-xl bg-white border border-slate-100 text-slate-400 hover:border-red-500 hover:bg-red-500 hover:text-white transition-all shadow-sm">
                                <i class="fas fa-trash-can text-[10px]"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>