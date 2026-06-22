<?php
// Query Gabungan
$query_stok = "SELECT 
                    m.id as id_material_asli, 
                    s.id as id_stok, 
                    m.nama_material, 
                    m.no_part, 
                    m.satuan,
                    COALESCE(s.stok_sekarang, 0) as stok_sekarang, 
                    COALESCE(s.stok_minimum, 0) as stok_minimum, 
                    s.last_update 
               FROM data_material m
               LEFT JOIN stok_material s ON m.id = s.material_id 
               ORDER BY m.nama_material ASC";

$result_stok = mysqli_query($conn, $query_stok);
?>

<div class="bg-white rounded-[2.5rem] border border-slate-200 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-50 text-slate-400 font-black uppercase tracking-[0.2em]">
                <tr>
                    <th class="px-6 py-5">No</th>
                    <th class="px-6 py-5">Material Info</th>
                    <th class="px-6 py-5 text-center">Stok</th>
                    <th class="px-6 py-5 text-center">Min</th>
                    <th class="px-6 py-5">Status</th>
                    <th class="px-6 py-5 text-center text-nowrap">Last Update</th>
                    <th class="px-6 py-5 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php 
                if (mysqli_num_rows($result_stok) > 0): 
                    $no = 1;
                    while($row = mysqli_fetch_assoc($result_stok)): 
                        $has_record = ($row['id_stok'] != null);
                        $is_critical = $has_record && ($row['stok_sekarang'] <= $row['stok_minimum']);
                ?>
                <tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="px-6 py-5 text-slate-500 font-bold"><?= $no++ ?></td>
                    <td class="px-6 py-5">
                        <div class="font-bold text-slate-800"><?= $row['nama_material'] ?></div>
                        <div class="text-[11px] text-slate-400 font-mono"><?= $row['no_part'] ?></div>
                    </td>
                    <td class="px-6 py-5 text-center font-black text-base <?= $is_critical ? 'text-red-500' : 'text-slate-800' ?>">
                        <?= number_format($row['stok_sekarang'], 0, ',', '.') ?>
                        <span class="text-[11px] text-slate-400 ml-1 uppercase font-normal"><?= $row['satuan'] ?></span>
                    </td>
                    <td class="px-6 py-5 text-center text-slate-500 font-bold italic">
                        <?= number_format($row['stok_minimum'], 0, ',', '.') ?>
                    </td>
                    <td class="px-6 py-5">
                        <?php if (!$has_record): ?>
                            <span class="text-[10px] font-black uppercase bg-slate-100 text-slate-400 px-3 py-1 rounded">No Data</span>
                        <?php elseif ($is_critical): ?>
                            <span class="text-[10px] font-black uppercase text-red-500 bg-red-50 px-3 py-1 rounded-full border border-red-100 italic text-nowrap">Kritis</span>
                        <?php else: ?>
                            <span class="text-[10px] font-black uppercase text-blue-600 bg-blue-50 px-3 py-1 rounded-full border border-blue-100 italic text-nowrap">Stabil</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-5 text-center font-mono text-slate-500 text-xs text-nowrap">
                        <?= $row['last_update'] ? date('d-m-Y', strtotime($row['last_update'])) : '-' ?>
                    </td>
                    <td class="px-6 py-5">
                        <div class="flex justify-center">
                            <?php if (!$has_record): ?>
                                <a href="?page=tambah-stok&id=<?= $row['id_material_asli'] ?>" 
                                   class="flex items-center gap-2 px-4 py-2 rounded-xl bg-blue-600 text-white hover:bg-blue-700 transition-all shadow-lg shadow-blue-100">
                                    <i class="fas fa-plus text-[10px]"></i>
                                    <span class="text-[10px] font-black uppercase tracking-wider">Tambah Stok</span>
                                </a>
                            <?php else: ?>
                                <div x-data="{ open: false }" class="relative">
                                    <button @click="open = !open" @click.away="open = false" 
                                            class="w-10 h-10 flex items-center justify-center rounded-xl bg-slate-100 text-slate-600 hover:bg-slate-200 transition-all">
                                        <i class="fas fa-ellipsis-v text-xs"></i>
                                    </button>

                                    <div x-show="open" 
                                         x-transition:enter="transition ease-out duration-100"
                                         class="absolute right-0 mt-2 w-44 bg-white border border-slate-100 rounded-2xl shadow-xl z-50 overflow-hidden"
                                         style="display: none;">
                                        <div class="p-2 space-y-1">
                                              <a href="?page=tambah-stok&id=<?= $row['id_material_asli'] ?>" class="flex items-center gap-3 px-3 py-2.5 text-xs font-bold text-slate-600 hover:bg-blue-50 hover:text-blue-600 rounded-xl transition-all">
                                               <i class="fas fa-plus text-[10px]"></i>
                                    <span class="text-[10px] font-black uppercase tracking-wider">Tambah Stok</span>

                                </a>
                                            <!-- PERBAIKAN DISINI: Menggunakan id_stok -->
                                            <a href="?page=edit-stok&id=<?= $row['id_stok'] ?>" class="flex items-center gap-3 px-3 py-2.5 text-xs font-bold text-slate-600 hover:bg-blue-50 hover:text-blue-600 rounded-xl transition-all">
                                                <i class="fas fa-arrows-rotate w-4 text-center"></i>
                                                Edit Stok
                                            </a>
                                            <div class="h-px bg-slate-50 my-1"></div>
                                            <a href="?page=delete-stok&id=<?= $row['id_stok'] ?>" 
                                               onclick="return confirm('Hapus data stok ini?')" 
                                               class="flex items-center gap-3 px-3 py-2.5 text-xs font-bold text-red-500 hover:bg-red-50 rounded-xl transition-all">
                                                <i class="fas fa-trash-can w-4 text-center"></i>
                                                Hapus Data
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endwhile; ?>
                <?php else: ?>
                <tr>
                    <td colspan="7" class="p-10 text-center text-slate-500 italic">Data material belum diinput di master data.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>