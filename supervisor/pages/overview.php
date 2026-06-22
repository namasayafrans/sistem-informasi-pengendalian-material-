<?php
// 1. Logika Filter & Data
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'minggu';
$list_tanggal = [];

switch ($filter) {
    case 'tahun':
        $interval = "1 YEAR"; 
        $label_format = 'M Y'; 
        $query_group = "DATE_FORMAT(tanggal, '%Y-%m')";
        for ($i = 11; $i >= 0; $i--) { 
            $date = date('Y-m', strtotime("-$i month")); 
            $list_tanggal[$date] = ['masuk' => 0, 'keluar' => 0]; 
        }
        break;
    case 'bulan':
        $interval = "30 DAY"; 
        $label_format = 'd M'; 
        $query_group = "tanggal";
        for ($i = 29; $i >= 0; $i--) { 
            $date = date('Y-m-d', strtotime("-$i day")); 
            $list_tanggal[$date] = ['masuk' => 0, 'keluar' => 0]; 
        }
        break;
    default:
        $interval = "6 DAY"; 
        $label_format = 'd M'; 
        $query_group = "tanggal";
        for ($i = 6; $i >= 0; $i--) { 
            $date = date('Y-m-d', strtotime("-$i day")); 
            $list_tanggal[$date] = ['masuk' => 0, 'keluar' => 0]; 
        }
        break;
}

// Hitung Saldo Awal & Data Mutasi
$query_saldo_awal = "SELECT SUM(CASE WHEN tipe = 'masuk' THEN jumlah ELSE 0 END) - SUM(CASE WHEN tipe = 'keluar' THEN jumlah ELSE 0 END) as saldo_awal FROM stok_mutasi WHERE tanggal < DATE_SUB(CURDATE(), INTERVAL $interval)";
$res_saldo = mysqli_query($conn, $query_saldo_awal);
$running_balance = (float)(mysqli_fetch_assoc($res_saldo)['saldo_awal'] ?? 0);

$query_grafik = "SELECT $query_group as tgl_group, SUM(CASE WHEN tipe = 'masuk' THEN jumlah ELSE 0 END) as total_masuk, SUM(CASE WHEN tipe = 'keluar' THEN jumlah ELSE 0 END) as total_keluar FROM stok_mutasi WHERE tanggal >= DATE_SUB(CURDATE(), INTERVAL $interval) GROUP BY tgl_group ORDER BY tgl_group ASC";
$result_grafik = mysqli_query($conn, $query_grafik);

while ($row = mysqli_fetch_assoc($result_grafik)) {
    if (isset($list_tanggal[$row['tgl_group']])) {
        $list_tanggal[$row['tgl_group']]['masuk'] = (float)$row['total_masuk'];
        $list_tanggal[$row['tgl_group']]['keluar'] = (float)$row['total_keluar'];
    }
}

$labels = []; $data_masuk = []; $data_keluar = []; $data_sisa = [];
foreach ($list_tanggal as $tgl => $val) {
    $labels[] = date($label_format, strtotime($filter == 'tahun' ? $tgl . '-01' : $tgl));
    
    $stok_tersedia = $running_balance + $val['masuk'];
    $sisa_hari_ini = $stok_tersedia - $val['keluar'];
    
    $data_masuk[] = $stok_tersedia;
    $data_keluar[] = $val['keluar'];
    $data_sisa[] = $sisa_hari_ini;
    
    $running_balance = $sisa_hari_ini;
}

// Data Material Kritis
$query_sync_kritis = "SELECT m.nama_material, COALESCE(s.stok_sekarang, 0) as stok_sekarang, COALESCE(s.stok_minimum, 0) as stok_minimum FROM data_material m LEFT JOIN stok_material s ON m.id = s.material_id WHERE s.stok_sekarang <= s.stok_minimum OR s.stok_sekarang IS NULL OR s.stok_sekarang = 0 ORDER BY stok_sekarang ASC LIMIT 5";
$res_detail = mysqli_query($conn, $query_sync_kritis);
$total_kritis = mysqli_num_rows($res_detail);
?>

<div class="p-4 md:p-8 bg-[#F8FAFC] min-h-screen font-sans text-slate-900">
    <div class="max-w-7xl mx-auto grid lg:grid-cols-3 gap-8">
        
        <!-- Chart Card -->
        <div class="lg:col-span-2 bg-white rounded-[2rem] border border-slate-200 p-8 shadow-sm flex flex-col">
            <!-- Header: Judul & Filter (Lebih Clean) -->
            <div class="flex justify-between items-center mb-10">
                <div class="flex items-center gap-3">
                    <div class="w-1.5 h-6 bg-blue-600 rounded-full"></div>
                    <h3 class="font-black uppercase tracking-tighter text-base text-slate-800">Statistik Alur Barang</h3>
                </div>
                
                <!-- Filter Waktu -->
                <div class="flex bg-slate-100 p-1 rounded-xl border border-slate-200">
                    <?php foreach(['minggu' => 'Minggu', 'bulan' => 'Bulan', 'tahun' => 'Tahun'] as $key => $lbl): ?>
                        <a href="?page=overview&filter=<?= $key ?>" 
                           class="px-4 py-1.5 rounded-lg text-[10px] font-black uppercase transition-all <?= $filter == $key ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-400 hover:text-slate-600' ?>">
                            <?= $lbl ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <!-- Area Grafik -->
            <div class="h-[280px] w-full mb-6">
                <canvas id="mainChart"></canvas>
            </div>

            <!-- Footer: Legenda (Di bawah grafik agar rapi) -->
            <div class="flex justify-center gap-8 pt-4 border-t border-slate-50">
                <div class="flex items-center gap-2 text-[10px] font-bold uppercase text-slate-400">
                    <div class="w-2.5 h-2.5 rounded-full bg-[#3B82F6]"></div> Masuk
                </div>
                <div class="flex items-center gap-2 text-[10px] font-bold uppercase text-slate-400">
                    <div class="w-2.5 h-2.5 rounded-full bg-[#94A3B8]"></div> Keluar
                </div>
                <div class="flex items-center gap-2 text-[10px] font-bold uppercase text-slate-400">
                    <div class="w-2.5 h-2.5 rounded-full bg-[#10B981]"></div> Sisa
                </div>
            </div>
        </div>

        <!-- Sidebar Info -->
        <div class="space-y-6">
            <div class="bg-white rounded-[2.5rem] border border-slate-200 p-8 shadow-sm">
                <h3 class="text-xs font-black uppercase tracking-[0.2em] text-slate-400 mb-6">Status Material</h3>
                
                <?php if ($total_kritis > 0): ?>
                    <div class="space-y-3">
                        <?php while ($item = mysqli_fetch_assoc($res_detail)): ?>
                            <div class="flex items-center justify-between p-4 rounded-2xl bg-slate-50 border border-slate-100">
                                <div>
                                    <p class="text-[11px] font-black uppercase text-slate-700 truncate w-32"><?= $item['nama_material'] ?></p>
                                    <p class="text-[9px] font-bold text-slate-400">MIN: <?= (float)$item['stok_minimum'] ?></p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-black text-slate-900"><?= (float)$item['stok_sekarang'] ?></p>
                                    <p class="text-[8px] font-bold text-blue-500 uppercase">Stok</p>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                <?php else: ?>
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-emerald-50 border border-emerald-100">
                        <span class="text-sm font-bold text-emerald-700">Semua Stok Aman</span>
                    </div>
                <?php endif; ?>

                <a href="?page=stok-material" class="mt-8 block w-full py-4 bg-slate-900 text-white rounded-2xl text-center font-black text-[10px] uppercase tracking-widest hover:bg-blue-600 transition-all">
                    Kelola Database
                </a>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('mainChart').getContext('2d');
    
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?= json_encode($labels) ?>,
            datasets: [
                { 
                    label: 'Masuk', 
                    data: <?= json_encode($data_masuk) ?>, 
                    backgroundColor: '#3B82F6', 
                    borderRadius: 5,
                    barPercentage: 0.6,
                },
                { 
                    label: 'Keluar', 
                    data: <?= json_encode($data_keluar) ?>, 
                    backgroundColor: '#94A3B8', 
                    borderRadius: 5,
                    barPercentage: 0.6,
                },
                { 
                    label: 'Sisa', 
                    data: <?= json_encode($data_sisa) ?>, 
                    backgroundColor: '#10B981', 
                    borderRadius: 5,
                    barPercentage: 0.6,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            // Delay dipercepat agar lebih responsif
            animation: {
                duration: 1000,
                easing: 'easeOutQuart',
                delay: (context) => context.dataIndex * 50 // Delay antar batang lebih cepat (50ms)
            },
            plugins: { 
                legend: { display: false },
                tooltip: {
                    padding: 12,
                    backgroundColor: '#1E293B',
                    titleFont: { size: 12, weight: 'bold' },
                    cornerRadius: 8
                }
            },
            scales: {
                y: { 
                    beginAtZero: true, 
                    grid: { color: '#F1F5F9', drawBorder: false }, 
                    ticks: { 
                        color: '#94A3B8', 
                        font: { size: 10, weight: '600' },
                        callback: function(value) { return value.toLocaleString(); }
                    } 
                },
                x: { 
                    grid: { display: false }, 
                    ticks: { color: '#64748B', font: { size: 10, weight: '700' } } 
                }
            }
        }
    });
</script>