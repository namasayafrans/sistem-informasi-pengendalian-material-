<?php
require '../../config/config.php';

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=Laporan_Produksi_Final.xls");

// Ambil rasio dulu
$q_rasio = mysqli_query($conn, "SELECT SUM(unit_produksi) as tu, (SELECT SUM(jumlah) FROM stok_mutasi WHERE tipe = 'keluar') as tm FROM rencana_produksi");
$d_rasio = mysqli_fetch_assoc($q_rasio);
$rasio = ($d_rasio['tu'] > 0) ? ($d_rasio['tm'] / $d_rasio['tu']) : 0;

$res = mysqli_query($conn, "SELECT * FROM rencana_produksi ORDER BY tanggal_eksekusi DESC");
$tp=0; $tm=0;
?>
<table border="1">
    <tr style="background:#2563eb; color:white; font-weight:bold;">
        <th>No</th><th>Unit Produksi</th><th>Material yang Digunakan</th><th>Tanggal</th>
    </tr>
    <?php $n=1; while($r=mysqli_fetch_assoc($res)): 
        $m_use = $r['unit_produksi'] * $rasio;
        $tp += $r['unit_produksi']; 
        $tm += $m_use; 
    ?>
    <tr>
        <td><?= $n++ ?></td>
        <td><?= $r['unit_produksi'] ?> Unit</td>
        <td><?= number_format($m_use, 0, '', '') ?> Pcs</td>
        <td><?= date('d-m-Y', strtotime($r['tanggal_eksekusi'])) ?></td>
    </tr>
    <?php endwhile; ?>
    <tr style="font-weight:bold; background:#f1f5f9;">
        <td>TOTAL</td>
        <td><?= $tp ?> UNIT</td>
        <td><?= number_format($tm, 0, '', '') ?> PCS</td>
        <td></td>
    </tr>
</table>