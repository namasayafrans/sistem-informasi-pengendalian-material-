<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// 1. PROSES EKSEKUSI PRODUKSI (Sama seperti sebelumnya)
if (isset($_POST['execute_produksi'])) {
    $target = (float)$_POST['target_unit'];
    if ($target <= 0) {
        echo "<script>alert('Target produksi tidak valid!');</script>";
    } else {
        $sql_check = "SELECT m.id, m.nama_material, m.standard, COALESCE(s.stok_sekarang, 0) as stok_sekarang 
                      FROM data_material m 
                      LEFT JOIN stok_material s ON m.id = s.material_id 
                      WHERE m.standard > 0";
        $res_check = mysqli_query($conn, $sql_check);
        
        $cukup = true;
        $bahan_list = [];
        $material_kurang = "";

        while ($m = mysqli_fetch_assoc($res_check)) {
            $butuh = $target * (float)$m['standard'];
            if ($butuh > (float)$m['stok_sekarang']) {
                $cukup = false;
                $material_kurang .= "- " . $m['nama_material'] . "\\n";
            }
            $bahan_list[] = ['id' => $m['id'], 'jumlah' => $butuh];
        }

        if ($cukup) {
            try {
                mysqli_begin_transaction($conn);
                foreach ($bahan_list as $bahan) {
                    $m_id = (int)$bahan['id'];
                    $jml = (float)$bahan['jumlah'];
                    mysqli_query($conn, "UPDATE stok_material SET stok_sekarang = stok_sekarang - $jml, last_update = NOW() WHERE material_id = $m_id");
                    mysqli_query($conn, "INSERT INTO stok_mutasi (material_id, jumlah, tipe, tanggal) VALUES ($m_id, $jml, 'keluar', CURDATE())");
                }
                mysqli_query($conn, "INSERT INTO rencana_produksi (unit_produksi, tanggal_eksekusi) VALUES ($target, NOW())");
                mysqli_commit($conn);
                echo "<script>alert('Produksi Berhasil Dieksekusi!'); window.location='?page=simulasi-produksi';</script>";
            } catch (Exception $e) {
                mysqli_rollback($conn);
                echo "<script>alert('SISTEM ERROR: " . addslashes($e->getMessage()) . "');</script>";
            }
        } else {
            echo "<script>alert('Gagal! Stok tidak mencukupi:\\n$material_kurang');</script>";
        }
    }
}

// 2. DAftarMATERIAL UNTUK JS
$query_bom = "SELECT m.id, m.nama_material, m.standard, COALESCE(s.stok_sekarang, 0) as stok_sekarang 
              FROM data_material m 
              LEFT JOIN stok_material s ON m.id = s.material_id 
              WHERE m.standard > 0";
$result_bom = mysqli_query($conn, $query_bom);
$materials = [];
while ($row = mysqli_fetch_assoc($result_bom)) { 
    $materials[] = [
        'nama_material' => $row['nama_material'],
        'standard' => (float)$row['standard'],
        'stok_sekarang' => (float)$row['stok_sekarang']
    ];
}
?>

<form method="POST" class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
    <div class="bg-white p-8 rounded-[2rem] border border-slate-200 shadow-sm">
        <label class="block text-[10px] font-black text-slate-400 uppercase mb-2 tracking-widest">Target Unit Produksi</label>
        <input type="number" name="target_unit" id="inputTarget" oninput="hitungSimulasi()" step="any" placeholder="Masukkan angka..." required
               class="w-full px-6 py-4 rounded-xl bg-slate-50 border border-slate-200 font-black text-lg text-slate-800 focus:ring-2 focus:ring-blue-500 outline-none">
        
        <button type="submit" name="execute_produksi" onclick="return confirm('Eksekusi produksi sekarang?')"
                class="w-full mt-6 bg-blue-600 text-white py-4 rounded-xl font-black text-xs hover:bg-blue-700 transition shadow-lg shadow-blue-200 uppercase">
            Produksi Sekarang
        </button>
    </div>

    <div id="cardKapasitas" class="lg:col-span-2 p-8 rounded-[2rem] text-white transition-all duration-500 bg-slate-800 shadow-xl flex flex-col justify-center">
        <h4 class="text-[9px] font-black uppercase text-white/50 tracking-widest">Proses Produksi</h4>
        <div class="text-6xl font-black italic my-2">
            <span id="maxProduksi">-</span> <span class="text-2xl text-white/30">UNIT</span>
        </div>
        <div id="bottleneckInfo" class="text-[11px] font-bold text-white/80">
            Silakan masukkan target unit untuk mulai menghitung kebutuhan material.
        </div>
    </div>
</form>

<div class="bg-white rounded-[2rem] border border-slate-200 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-xs">
            <thead class="bg-slate-50 text-slate-400 font-black uppercase">
                <tr>
                    <th class="p-6">Nama Material</th>
                    <th class="p-6 text-center">Standar</th>
                    <th class="p-6 text-center">Total Kebutuhan</th>
                    <th class="p-6 text-center">Stok Tersedia</th>
                    <th class="p-6">Status</th>
                </tr>
            </thead>
            <tbody id="simulasiTable" class="divide-y divide-slate-100 text-slate-600">
                <tr>
                    <td colspan="5" class="p-10 text-center text-slate-400 italic font-medium">
                        Belum ada data simulasi. Masukkan target produksi di atas.
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script>
const materials = <?= json_encode($materials) ?>;

function hitungSimulasi() {
    const inputField = document.getElementById('inputTarget');
    const target = parseFloat(inputField.value);
    const card = document.getElementById('cardKapasitas');
    const info = document.getElementById('bottleneckInfo');
    const tableBody = document.getElementById('simulasiTable');
    const maxLabel = document.getElementById('maxProduksi');

    // Jika input kosong atau 0, kembalikan ke tampilan awal
    if (!target || target <= 0) {
        maxLabel.innerText = "-";
        card.className = "lg:col-span-2 p-8 rounded-[2rem] text-white shadow-xl bg-slate-800 transition-all duration-500 flex flex-col justify-center";
        info.innerText = "Silakan masukkan target unit untuk mulai menghitung kebutuhan material.";
        tableBody.innerHTML = `<tr><td colspan="5" class="p-10 text-center text-slate-400 italic font-medium">Belum ada data simulasi. Masukkan target produksi di atas.</td></tr>`;
        return;
    }

    // 1. Hitung Kapasitas Maksimal (Bottleneck)
    let minProduksi = Infinity;
    materials.forEach(m => {
        const kapasitas = Math.floor(m.stok_sekarang / m.standard);
        if (kapasitas < minProduksi) minProduksi = kapasitas;
    });
    
    maxLabel.innerText = minProduksi.toLocaleString('id-ID');
    
    // 2. Logika Warna Card
    if (target > minProduksi) {
        card.className = "lg:col-span-2 p-8 rounded-[2rem] text-white shadow-xl bg-red-600 transition-all duration-500 flex flex-col justify-center";
        info.innerHTML = `⚠️ <strong>STOK TIDAK CUKUP!</strong> Target melebihi kapasitas maksimal stok.`;
    } else {
        card.className = "lg:col-span-2 p-8 rounded-[2rem] text-white shadow-xl bg-emerald-600 transition-all duration-500 flex flex-col justify-center";
        info.innerText = "✅ STOK TERCUKUPI. Produksi aman untuk dijalankan.";
    }

    // 3. Update Tabel Detail secara Real-time
    let html = '';
    materials.forEach(m => {
        const butuh = target * m.standard;
        const isKurang = butuh > m.stok_sekarang;

        html += `
        <tr class="hover:bg-slate-50 transition">
            <td class="p-6 font-bold text-slate-700">${m.nama_material}</td>
            <td class="p-6 text-center text-slate-500">${m.standard.toFixed(2)}</td>
            <td class="p-6 text-center font-black ${isKurang ? 'text-red-600' : 'text-slate-800'}">
                ${butuh.toLocaleString('id-ID', {minimumFractionDigits: 2})}
            </td>
            <td class="p-6 text-center font-bold text-slate-500">${m.stok_sekarang.toLocaleString('id-ID')}</td>
            <td class="p-6">
                <span class="px-3 py-1 rounded-full font-black text-[10px] uppercase ${isKurang ? 'bg-red-100 text-red-600' : 'bg-emerald-100 text-emerald-600'}">
                    ${isKurang ? 'Kurang' : 'Terpenuhi'}
                </span>
            </td>
        </tr>`;
    });
    tableBody.innerHTML = html;
}
</script>