<?php
$conn = mysqli_connect("localhost", "root", "", "batarasura_db");
ob_start();
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=Laporan_Stok_Material.xls");

$query = "SELECT m.nama_material, 
          COALESCE(s.stok_sekarang, 0) as stok_sisa,
          (SELECT SUM(jumlah) FROM stok_mutasi WHERE material_id = m.id AND tipe = 'masuk') as stok_awal,
          COALESCE(s.stok_minimum, 0) as min
          FROM data_material m 
          LEFT JOIN stok_material s ON m.id = s.material_id";
$res = mysqli_query($conn, $query);
?>
<table border="1">
    <tr style="background:#10b981; color:white; font-weight:bold;">
        <th>No</th><th>Nama Material</th><th>Stok Awal</th><th>Stok Sisa</th><th>Status</th>
    </tr>
    <?php $n=1; while($r=mysqli_fetch_assoc($res)): 
        $st = ($r['stok_sisa'] <= $r['min']) ? 'CRITICAL' : 'STABLE';
    ?>
    <tr>
        <td><?= $n++ ?></td>
        <td><?= $r['nama_material'] ?></td>
        <td><?= $r['stok_awal'] ?? 0 ?></td>
        <td><?= $r['stok_sisa'] ?></td>
        <td><?= $st ?></td>
    </tr>
    <?php endwhile; ?>
</table>
<?php ob_end_flush(); ?>