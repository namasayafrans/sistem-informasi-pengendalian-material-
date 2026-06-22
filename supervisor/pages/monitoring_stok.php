<?php
// 1. Ambil Statistik Ringkasan
$query_stats = "SELECT 
    COUNT(m.id) as total_item,
    SUM(CASE WHEN s.stok_sekarang <= s.stok_minimum THEN 1 ELSE 0 END) as total_kritis,
    SUM(CASE WHEN s.stok_sekarang > s.stok_minimum THEN 1 ELSE 0 END) as total_aman
    FROM data_material m
    LEFT JOIN stok_material s ON m.id = s.material_id";
$res_stats = mysqli_fetch_assoc(mysqli_query($conn, $query_stats));

// 2. Query Detail Stok
$query_stok = "SELECT 
                    m.nama_material, 
                    m.no_part, 
                    m.satuan,
                    COALESCE(s.stok_sekarang, 0) as stok_sekarang, 
                    COALESCE(s.stok_minimum, 0) as stok_minimum, 
                    s.last_update 
               FROM data_material m
               LEFT JOIN stok_material s ON m.id = s.material_id 
               ORDER BY (s.stok_sekarang <= s.stok_minimum) DESC, m.nama_material ASC";

$result_stok = mysqli_query($conn, $query_stok);
?>

<div class="space-y-8 animate-in fade-in duration-500">
    <!-- Bagian 1: Quick Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-[2rem] border border-slate-200 shadow-sm flex items-center gap-5">
            <div class="w-14 h-14 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600">
                <i class="fas fa-boxes-stacked text-xl"></i>
            </div>
            <div>
                <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Total Material</p>
                <h4 class="text-2xl font-black text-slate-800"><?= $res_stats['total_item'] ?></h4>
            </div>
        </div>
        <div class="bg-white p-6 rounded-[2rem] border border-slate-200 shadow-sm flex items-center gap-5">
            <div class="w-14 h-14 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-600">
                <i class="fas fa-check-circle text-xl"></i>
            </div>
            <div>
                <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Stok Aman</p>
                <h4 class="text-2xl font-black text-slate-800"><?= $res_stats['total_aman'] ?? 0 ?></h4>
            </div>
        </div>
        <div class="bg-white p-6 rounded-[2rem] border border-slate-200 shadow-sm flex items-center gap-5">
            <div class="w-14 h-14 <?= ($res_stats['total_kritis'] > 0) ? 'bg-red-50 text-red-600' : 'bg-slate-50 text-slate-400' ?> rounded-2xl flex items-center justify-center">
                <i class="fas fa-triangle-exclamation text-xl"></i>
            </div>
            <div>
                <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Material Shortage</p>
                <h4 class="text-2xl font-black <?= ($res_stats['total_kritis'] > 0) ? 'text-red-600' : 'text-slate-800' ?>">
                    <?= $res_stats['total_kritis'] ?? 0 ?>
                </h4>
            </div>
        </div>
    </div>

    <!-- Bagian 2: Main Monitoring Table -->
    <div class="bg-white rounded-[3rem] border border-slate-200 shadow-xl shadow-slate-100/50 overflow-hidden">
        <div class="p-8 flex justify-between items-center bg-white border-none">
            <div>
                <h3 class="font-black text-slate-800 uppercase tracking-tighter">Inventaris Real-Time</h3>
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">PT BATARA SURA MULIA</p>
            </div>
            <button onclick="exportToExcel()" class="px-5 py-2.5 bg-emerald-600 text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-emerald-700 transition-all flex items-center gap-2 shadow-lg shadow-emerald-100">
                <i class="fas fa-file-excel text-xs"></i> EXPORT MS EXCEL
            </button>
        </div>

        <div class="overflow-x-auto">
            <table id="tableMonitor" class="w-full text-center border-none">
                <thead class="bg-slate-50/50 text-slate-400 font-black text-[10px] uppercase tracking-[0.2em]">
                    <tr>
                        <th class="px-6 py-5 border-none text-center w-16">No.</th>
                        <th class="px-8 py-5 border-none text-left">Info Material</th>
                        <th class="px-8 py-5 border-none text-center">Stok Saat Ini</th>
                        <th class="px-8 py-5 border-none text-center">Status Keamanan</th>
                        <th class="px-8 py-5 border-none text-center">Update Terakhir</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php if (mysqli_num_rows($result_stok) > 0): ?>
                        <?php 
                        $no = 1;
                        while($row = mysqli_fetch_assoc($result_stok)): 
                            $is_critical = ($row['stok_sekarang'] <= $row['stok_minimum']);
                            $tanggal_format = $row['last_update'] ? date('d-m-Y', strtotime($row['last_update'])) : '-';
                        ?>
                        <tr class="hover:bg-slate-50/80 transition-all group">
                            <td class="px-6 py-6 border-none text-center font-bold text-slate-400 text-xs">
                                <?= $no++ ?>
                            </td>
                            <td class="px-8 py-6 border-none text-left">
                                <div class="font-black text-slate-800 text-sm tracking-tight uppercase"><?= $row['nama_material'] ?></div>
                                <div class="text-[10px] text-slate-400 font-bold tracking-widest"><?= $row['no_part'] ?></div>
                            </td>
                            <td class="px-8 py-6 border-none font-black text-slate-800 text-center">
                                <span class="text-base"><?= number_format($row['stok_sekarang'], 0, ',', '.') ?></span> 
                                <span class="text-[9px] text-slate-400 uppercase font-bold ml-1"><?= $row['satuan'] ?></span>
                            </td>
                            <td class="px-8 py-6 border-none text-center">
                                <span class="text-[10px] font-black uppercase px-4 py-1.5 rounded-full <?= $is_critical ? 'bg-red-50 text-red-600' : 'bg-emerald-50 text-emerald-600' ?>">
                                    <?= $is_critical ? 'Kritis' : 'Aman' ?>
                                </span>
                            </td>
                            <td class="px-8 py-6 border-none text-center text-[11px] font-bold text-slate-500">
                                <?= $tanggal_format ?>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function exportToExcel() {
    const table = document.getElementById("tableMonitor");
    
    const now = new Date();
    const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
    const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    
    const dayName = days[now.getDay()];
    const date = now.getDate().toString().padStart(2, '0');
    const monthNum = (now.getMonth() + 1).toString().padStart(2, '0');
    const year = now.getFullYear();
    const time = now.toLocaleTimeString('id-ID');
    
    const fullDate = `${dayName}, ${date}-${monthNum}-${year} Pukul ${time}`;

    let excelTemplate = `
        <table border="1">
            <thead>
                <tr><th colspan="5" style="font-size:18px; text-align:center; font-weight:bold;">PT BATARA SURA MULIA</th></tr>
                <tr><th colspan="5" style="font-size:14px; text-align:center; font-weight:bold;">LAPORAN MONITORING STOK MATERIAL</th></tr>
                <tr><th colspan="5" style="text-align:center; font-style:italic;">Waktu Download: ${fullDate}</th></tr>
                <tr></tr>
                <tr style="background-color: #f2f2f2; font-weight:bold;">
                    <th style="text-align:center;">No.</th>
                    <th style="text-align:center;">Info Material</th>
                    <th style="text-align:center;">Stok Saat Ini</th>
                    <th style="text-align:center;">Status Keamanan</th>
                    <th style="text-align:center;">Update Terakhir</th>
                </tr>
            </thead>
            <tbody>
                ${Array.from(table.rows).slice(1).map(row => {
                    return `<tr>
                        <td style="text-align:center;">${row.cells[0].innerText}</td>
                        <td style="text-align:center;">${row.cells[1].innerText.replace(/\n/g, ' ')}</td>
                        <td style="text-align:center;">${row.cells[2].innerText}</td>
                        <td style="text-align:center;">${row.cells[3].innerText}</td>
                        <td style="text-align:center;">${row.cells[4].innerText}</td>
                    </tr>`;
                }).join('')}
            </tbody>
        </table>
    `;

    const blob = new Blob([excelTemplate], { type: "application/vnd.ms-excel" });
    const url = URL.createObjectURL(blob);
    const a = document.createElement("a");
    a.href = url;
    a.download = "Monitoring_Stok_BataraSura.xls";
    a.click();
    URL.revokeObjectURL(url);
}
</script>