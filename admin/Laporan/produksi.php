<?php
require '../config/config.php';


// Ambil total seluruh produksi dan total material keluar untuk menghitung rata-rata pemakaian
$q_total = mysqli_query($conn, "SELECT 
    SUM(unit_produksi) as total_u, 
    (SELECT SUM(jumlah) FROM stok_mutasi WHERE tipe = 'keluar') as total_m 
    FROM rencana_produksi");
$d_total = mysqli_fetch_assoc($q_total);

$total_seluruh_unit = $d_total['total_u'] ?? 1; // hindari pembagian nol
$total_seluruh_mat = $d_total['total_m'] ?? 0;

// Rasio: 1 unit butuh berapa material?
$rasio = $total_seluruh_mat / $total_seluruh_unit;

$query_prod = "SELECT * FROM rencana_produksi ORDER BY tanggal_eksekusi DESC";
$result_prod = mysqli_query($conn, $query_prod);

$grand_unit = 0;
$grand_mat = 0;
?>

<div class="p-8">
    <div class="flex justify-between items-center mb-5">
        <a href="laporan/export_produksi.php" class="bg-blue-600 text-white px-6 py-3 rounded-2xl font-black text-[10px] uppercase">Export Excel</a>
    </div>

    <div class="bg-white rounded-[2.5rem] border border-slate-200 overflow-hidden mb-6">
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-50 text-[10px] font-black uppercase text-slate-400">
                <tr>
                    <th class="px-8 py-6">No</th>
                    <th class="px-8 py-6">Unit Produksi</th>
                    <th class="px-8 py-6 text-center">Material yang Digunakan</th>
                    <th class="px-8 py-6 text-center">Tgl Produksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php $no=1; while($row = mysqli_fetch_assoc($result_prod)): 
                    // HITUNG DISINI: Unit Produksi x Rasio Pemakaian
                    $mat_digunakan = $row['unit_produksi'] * $rasio;
                    
                    $grand_unit += $row['unit_produksi'];
                    $grand_mat += $mat_digunakan;
                ?>
                <tr>
                    <td class="px-8 py-6 font-bold text-slate-400 text-center"><?= $no++ ?></td>
                    <td class="px-8 py-6 font-black text-xl text-slate-800"><?= $row['unit_produksi'] ?> <span class="text-xs font-normal text-slate-400 italic">Unit</span></td>
                    <td class="px-8 py-6 text-center font-bold text-blue-600">
                        <?= number_format($mat_digunakan, 0, ',', '.') ?> <span class="text-[10px]"></span>
                    </td>
                    <td class="px-8 py-6 text-center font-medium text-slate-600"><?= date('d M Y', strtotime($row['tanggal_eksekusi'])) ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div class="bg-slate-900 p-8 rounded-[2rem] text-white">
            <p class="text-[10px] font-black uppercase text-slate-500 tracking-widest mb-1">Jumlah Produksi</p>
            <h4 class="text-4xl font-black italic"><?= number_format($grand_unit) ?> <span class="text-sm not-italic text-slate-600">UNIT</span></h4>
        </div>
        <div class="bg-blue-600 p-8 rounded-[2rem] text-white">
            <p class="text-[10px] font-black uppercase text-blue-200 tracking-widest mb-1">Jumlah Material</p>
            <h4 class="text-4xl font-black italic"><?= number_format($grand_mat) ?> <span class="text-sm not-italic text-blue-300">PCS</span></h4>
        </div>
    </div>
</div>