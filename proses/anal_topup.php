<?php $tgl_awal  = $_GET['sebelum'];
    $tgl_banding = $_GET['minggu_ini'];
    
    ?>
    <h3> ANGGOTA PAR <?=$tgl_banding?></h3>
    <table class='table'>
        <tr>
            <td>NO</td>
            <td>LOAN</td>
            <td>CENTER</td>
            <td>ID AGT</td>
            <td>ANGGOTA</td>
            <td>DISBURSE</td>
            <td>BALANCE</td>
            <td>TOPUP</td>
            <td>ANGSURAN<br/>50mg</td>
            <td></td>
            <td>STAFF</td>
        </tr>
    
    <?php
    $no=1;
    $total_bermasalah=0;
    $query = mysqli_query($con,"
    SELECT d.*,k.nama_karyawan FROM deliquency d 
	JOIN center c ON c.`no_center`=d.`no_center` 
	JOIN karyawan k ON k.`id_karyawan`=c.`id_karyawan` where d.tgl_input in (select max(tgl_input) from deliquency where id_cabang='$id_cabang') and c.id_cabang='$id_cabang' order by d.sisa_saldo asc");
    while($data = mysqli_fetch_array($query)){
        $total_bermasalah+=$data['sisa_saldo'];
        $par = mysqli_num_rows(mysqli_query($con,"select * from anggota_par where id_detail_nasabah='$data[id_detail_nasabah]'"));
        if($par){
            $baris['baris']= "#c9c7c1";
            $baris['text']= "red";
            $baris['ket']='RE/DTD';
        }
        else{
            $baris['baris'] = "#ffff";
            $baris['text'] = "#black";
            $baris['ket']='';

        } 
        ?>
        <tr style="background-color:<?=$baris['baris']?>;color:<?=$baris['text']?>">
            <td><?=$no++?></td>
            <td><?=$data['loan']?></td>
            <td><?=$data['no_center']?></td>
            <td><?=$data['id_detail_nasabah']?></td>
            <td><?=$data['nasabah']?></td>
            <td><?=angka($data['amount'])?></td>
            <td><?=angka(round($data['sisa_saldo']))?></td>
            <td><?=angka(round($data['sisa_saldo']),1,PHP_ROUND_HALF_UP)?></td>
            <td><?=angka($data['sisa_saldo']/50)?></td>
            <td></td>
            <td><?=$data['nama_karyawan']?></td>
        </tr>
        <?php
    }?>
    <tr>
        <th colspan="7">TOTAL OUTSTANDING BERMASALAH</th>
        <th><?=angka($total_bermasalah)?></th>
    </tr>
    </table>