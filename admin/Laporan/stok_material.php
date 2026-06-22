<?php
// Koneksi manual karena lu gak pake file koneksi.php
$conn = mysqli_connect("localhost", "root", "", "batarasura_db");

$query_stok = "SELECT 
                m.nama_material, m.no_part, m.satuan,
                COALESCE(s.stok_sekarang, 0) as stok_sisa,
                COALESCE(s.stok_minimum, 0) as min,
                -- Stok Awal = Stok Sisa + Total Mutasi Keluar (Material yang sudah terpakai)
                (COALESCE(s.stok_sekarang, 0) + COALESCE((SELECT SUM(jumlah) FROM stok_mutasi WHERE material_id = m.id AND tipe = 'keluar'), 0)) as stok_awal
               FROM data_material m
               LEFT JOIN stok_material s ON m.id = s.material_id
               ORDER BY m.nama_material ASC";
$result_stok = mysqli_query($conn, $query_stok);
?>

<div class="p-8">
    <div class="flex justify-between items-center mb-10">
        <a href="laporan/export_stok.php" class="bg-emerald-600 text-white px-6 py-3 rounded-2xl font-black text-xs uppercase hover:shadow-lg transition flex items-center gap-2">
            <i class="fas fa-file-excel"></i> Export Excel
        </a>
    </div>

    <div class="bg-white rounded-[2.5rem] border border-slate-200 shadow-sm overflow-hidden">
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-50 text-slate-400 font-black uppercase tracking-widest text-[10px]">
                <tr>
                    <th class="px-8 py-6">No</th>
                    <th class="px-8 py-6">Nama Material</th>
                    <th class="px-8 py-6 text-center">Stok Awal</th>
                    <th class="px-8 py-6 text-center">Stok Sisa</th>
                    <th class="px-8 py-6 text-center">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php $no=1; while($row = mysqli_fetch_assoc($result_stok)): 
                    $is_kritis = $row['stok_sisa'] <= $row['min'];
                ?>
                <tr class="hover:bg-slate-50/50 transition">
                    <td class="px-8 py-6 font-bold text-slate-400"><?= $no++ ?></td>
                    <td class="px-8 py-6">
                        <div class="font-bold text-slate-700"><?= $row['nama_material'] ?></div>
                        <div class="text-[9px] text-slate-400 font-black uppercase"><?= $row['no_part'] ?></div>
                    </td>
                    <td class="px-8 py-6 text-center font-bold text-slate-400"><?= number_format($row['stok_awal'], 0, ',', '.') ?></td>
                    <td class="px-8 py-6 text-center font-black text-slate-800 text-lg"><?= number_format($row['stok_sisa'], 0, ',', '.') ?></td>
                    <td class="px-8 py-6 text-center">
                        <span class="px-4 py-1.5 rounded-xl font-black text-[9px] uppercase <?= $is_kritis ? 'bg-red-100 text-red-600' : 'bg-emerald-100 text-emerald-600' ?>">
                            <?= $is_kritis ? 'Critical' : 'Stable' ?>
                        </span>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>