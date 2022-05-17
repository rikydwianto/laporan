<?php
$sepat = $_GET['tipe'];
if(isset($_GET['bandingkan'])){
    
    $tgl_awal  = $_GET['sebelum'];
    $tgl_banding = $_GET['minggu_ini'];
    ?>
    <a href="#" onclick="buka('popup/par.php?tgl=<?=$tgl_awal?>')"  class="btn btn-primary"> <i class="fa fa-list"></i> Tampilkan Semua AGT PAR TGL <?=$tgl_awal?></a>
    <a href="#" onclick="buka('popup/par.php?tgl=<?=$tgl_banding?>')"  class="btn btn-danger"> <i class="fa fa-list"></i> Tampilkan Semua AGT PAR TGL <?=$tgl_banding?></a>
    <a href="#rekap" onclick="printPageArea('turun_par')" class="btn btn-success">print <i class="fa fa-print"></i></a>
    <div id='turun_par'>
        <h3>PENURUNAN ANGGOTA PAR</h3>
        <table class='table table-bordered'>
            <tr>
                <th>NO</th>
                <th>LOAN</th>
                <th>CENTER</th>
                <th>ID AGT</th>
                <th>ANGGOTA</th>
                <th>TANGGAL</th>
                <th>DISBURSE</th>
                <th>BALANCE</th>
                <th>ARREAS</th>
                <th>WEEK PAS</th>
                <th>STATUS</th>
                <th>STAFF</th>
            </tr>
        
        <?php
        $total_os = 0;
        $query = mysqli_query($con," SELECT d.*,k.nama_karyawan FROM deliquency d 
        JOIN center c ON c.`no_center`=d.`no_center` 
        JOIN karyawan k ON k.`id_karyawan`=c.`id_karyawan`
        where d.loan not in (select loan from deliquency where tgl_input='$tgl_banding' and id_cabang='$id_cabang') and d.tgl_input='$tgl_awal' and c.id_cabang='$id_cabang' and d.id_cabang='$id_cabang' order by k.nama_karyawan asc");
        while($data = mysqli_fetch_array($query)){
            $total_os+=$data['sisa_saldo'];
            $par = mysqli_num_rows(mysqli_query($con,"select * from anggota_par where id_detail_nasabah='$data[id_detail_nasabah]' and id_cabang='$id_cabang'"));
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
                <td><?=$data['tgl_disburse']?></td>
                <td><?=angka($data['amount'],$sepat)?></td>
                <td><?=angka($data['sisa_saldo'],$sepat)?></td>
                <td><?=angka($data['tunggakan'],$sepat)?></td>
                <td><?=$data['minggu']?></td>
                <td><?=$baris['ket']?></td>
                <td><?=$data['nama_karyawan']?></td>
            </tr>
            <?php
        }?>
        <tr>
            <th colspan="7">TOTAL OUTSTANDING BERKURANG</th>
            <th>-<?=angka($total_os,$sepat)?></th>
        </tr>
        </table>
    </div>



    
    <a href="#rekap" onclick="printPageArea('tambah_par')" class="btn btn-success">print <i class="fa fa-print"></i></a>
    
    <div id='tambah_par'>
        <h3>PENAMBAHAN ANGGOTA PAR</h3>
        <table class='table' >
            <tr>
                <th>NO</th>
                <th>LOAN</th>
                <th>CENTER</th>
                <th>ID AGT</th>
                <th>ANGGOTA</th>
                <th>TANGGAL</th>
                <th>DISBURSE</th>
                <th>BALANCE</th>
                <th>ARREAS</th>
                <th>WEEK PAS</th>
                <th>STATUS</th>
                <th>STAFF</th>
            </tr>
        
        <?php
        $no=1;
        $total_tambah=0;
        $query1 = mysqli_query($con,"
        SELECT d.*,k.nama_karyawan FROM deliquency d 
        JOIN center c ON c.`no_center`=d.`no_center` 
        JOIN karyawan k ON k.`id_karyawan`=c.`id_karyawan`
        where d.loan not in (select loan from deliquency where tgl_input='$tgl_awal' and id_cabang='$id_cabang') and d.tgl_input='$tgl_banding' and c.id_cabang='$id_cabang' and d.minggu=1 and d.id_cabang='$id_cabang' order by k.nama_karyawan asc");
        while($data = mysqli_fetch_array($query1)){
            $par = mysqli_num_rows(mysqli_query($con,"select * from anggota_par where id_detail_nasabah='$data[id_detail_nasabah]' and id_cabang='$id_cabang'"));
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
            $total_tambah+=$data['sisa_saldo'];
            ?>
            <tr style="background-color:<?=$baris['baris']?>;color:<?=$baris['text']?>">
                <td><?=$no++?></td>
                <td><?=$data['loan']?></td>
                <td><?=$data['no_center']?></td>
                <td><?=$data['id_detail_nasabah']?></td>
                <td><?=$data['nasabah']?></td>
                <td><?=$data['tgl_disburse']?></td>
                <td><?=angka($data['amount'],$sepat)?></td>
                <td><?=angka($data['sisa_saldo'],$sepat)?></td>
                <td><?=angka($data['tunggakan'],$sepat)?></td>
                <td><?=$data['minggu']?></td>
                <td><?=$baris['ket']?></td>
                <td><?=$data['nama_karyawan']?></td>
            </tr>
            <?php
        }?>
        <tr>
            <th colspan="7">TOTAL OUTSTANDING BERTAMBAH</th>
            <th>+<?=angka($total_tambah,$sepat)?></th>
        </tr>
        </table>
    </div>


    <!-- PENGURANGAN OUTSTANDING PAR -->
    <!-- PENGURANGAN OUTSTANDING PAR -->
    <!-- PENGURANGAN OUTSTANDING PAR -->
    <!-- PENGURANGAN OUTSTANDING PAR -->
    <a href="#rekap" onclick="printPageArea('turun_os')" class="btn btn-success">print <i class="fa fa-print"></i></a>
   <div id='turun_os'>
   <h3> PENGURANGAN OUTSTANDING PAR</h3>
        <table class='table'>
            <tr>
                <th>NO</th>
                <th>LOAN</th>
                <th>CENTER</th>
                <th>ID AGT</th>
                <th>ANGGOTA</th>
                <th>DISBURSE</th>
                <th>BALANCE</th>
                <th>BALANCE </th>
                <th>MINUS</th>
                <th>WEEK</th>
                <th>STATUS</th>
                <th>STAFF</th>
            </tr>
        
        <?php
        $no=1;
        $total_minus=0;
        $query_s = mysqli_query($con,"
        SELECT d.*,k.nama_karyawan FROM deliquency d 
        JOIN center c ON c.`no_center`=d.`no_center` 
        JOIN karyawan k ON k.`id_karyawan`=c.`id_karyawan` 
        where d.loan  in (select loan from deliquency where tgl_input='$tgl_banding' and id_cabang='$id_cabang') and d.tgl_input='$tgl_awal' and c.id_cabang='$id_cabang' and d.id_cabang='$id_cabang' order by k.nama_karyawan asc");
        while($data = mysqli_fetch_array($query_s)){
            
            $loan = $data['loan'];
            $banding = mysqli_query($con,"select sisa_saldo from deliquency where loan='$loan' and tgl_input='$tgl_banding' and id_cabang='$id_cabang'");
            $banding = mysqli_fetch_array($banding);
            $saldo_awal=$data['sisa_saldo'];
            $saldo_akhir = $banding['sisa_saldo'];
            $total = $saldo_awal - $saldo_akhir;
            
            if($total>0){
                $total_minus +=  $total;
            
                $par = mysqli_num_rows(mysqli_query($con,"select * from anggota_par where id_detail_nasabah='$data[id_detail_nasabah]' and id_cabang='$id_cabang'"));
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
                    <td><?=angka($data['amount'],$sepat)?></td>
                    <td><?=angka($saldo_awal,$sepat)?></td>
                    <td><?=angka($saldo_akhir,$sepat)?></td>
                    <td>-<?=angka($total,$sepat)?></td>
                    <td><?=$data['minggu']?></td>
                    <td><?=$baris['ket']?></td>
                    <td><?=$data['nama_karyawan']?></td>
                </tr>
                <?php
            }
        }?>
        <tr>
            <th colspan="8">TOTAL OUTSTANDING BERKURANG</th>
            <th>-<?=angka($total_minus,$sepat)?></th>
        </tr>
        </table>
   </div>










<?php
} ?>