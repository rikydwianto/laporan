<h2>REKAP MONITORING PER BULAN </h2>
<table class='table'>
    <thead>
        <tr>
            <th>NO</th>
            <th>BULAN - TAHUN</th>
            <th>TOTAL</th>
        </tr>
    </thead>
    <tbody>
        
        <?php 
        $hitung_total=0;
$qbulan = mysqli_query($con,"SELECT YEAR(tgl_cair) AS tahun,MONTH(tgl_cair) AS bulan FROM pinjaman p  WHERE p.id_cabang='$id_cabang' GROUP BY MONTH(tgl_cair),YEAR(tgl_cair) ORDER BY p.tgl_cair DESC");
while($bulan = mysqli_fetch_array($qbulan)){
    $mon = $bulan['bulan'];
    $tahun=$bulan['tahun'];
    $mon = sprintf("%02d",$mon);
    $hitung_monitoring = mysqli_query($con,"select count(*) as total from pinjaman where id_cabang='$id_cabang' and tgl_cair like '$tahun-$mon-%' and monitoring='belum' and input_mtr='sudah'");
    echo"";
    echo mysqli_error($con);
    $hitung_monitoring=mysqli_fetch_array($hitung_monitoring)['total'];
    $hitung_total += $hitung_monitoring;
    ?>
    <tr>
        <td><?=$no++?></td>
        <td><?=bulan_indo($bulan['bulan'])?> - <?=$tahun?></td>
        <td><?=$hitung_monitoring?></td>
    </tr>
    <?php
}
?>
    </tbody>
    <tfoot>
        <tr>
            <th  colspan="2"></th>
            <th  colspan="1"><?=$hitung_total?></th>
        </tr>
    </tfoot>
</table>
