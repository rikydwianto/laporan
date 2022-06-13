<?php $tgl_awal  = $_GET['sebelum'];
    $tgl_banding = $_GET['minggu_ini'];
    
    ?>
    <h3> ANGGOTA PAR <?=$tgl_banding?></h3>
    <div class='content table-responsive'>
    <form action="" method="post">
        <select name="id" id="karyawan"  class='form-control'>
            <option value="">PILIH SEMUA STAFF</option>
            <?php 
            $id = aman($con,$_GET['id']);
            $data_karyawan  = (karyawan($con, $id_cabang)['data']);
            
            for ($i = 0; $i < count($data_karyawan); $i++) {
                $nama_karyawan = $data_karyawan[$i]['nama_karyawan'];
                $idk = $data_karyawan[$i]['id_karyawan'];
                if ($idk == $id) {
                    echo "<option selected value='" . $data_karyawan[$i]['id_karyawan'] . "'>" . $nama_karyawan . "</option>";
                } else {
                    echo "<option value='" . $data_karyawan[$i]['id_karyawan'] . "'>" . $nama_karyawan . "</option>";
                }
            }
            ?>
        </select>
        
    </form>
    <table class='table'>
        <tr>
            <td>NO</td>
            <td>LOAN</td>
            <td>CENTER</td>
            <td>ID AGT</td>
            <td>ANGGOTA</td>
            <td>DISBURSE</td>
            <td>BALANCE</td>
            <td>TOPUP <br/> BAL + 5%</td>
            <td>ANGSURAN<br/>50mg + margin</td>
            <td></td>
            <td>STAFF</td>
        </tr>
    
    <?php
    $no=1;
    $total_bermasalah=0;

    if(isset($_GET['id'])){
        if($_GET['id']!=null){
            $id = aman($con,$_GET['id']);
            $q_tambah = "and k.id_karyawan='$id'";
        }
        else $q_tambah="";
    }
    else $q_tambah="";
    $query = mysqli_query($con,"
    SELECT d.*,k.nama_karyawan FROM deliquency d 
	JOIN center c ON c.`no_center`=d.`no_center` 
	JOIN karyawan k ON k.`id_karyawan`=c.`id_karyawan` where d.tgl_input in (select max(tgl_input) from deliquency where id_cabang='$id_cabang') and c.id_cabang='$id_cabang' and d.id_cabang='$id_cabang' and k.id_cabang='$id_cabang' $q_tambah order by d.sisa_saldo asc");
    
    echo mysqli_error($con);
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
        $bagi=1000000;
        $saldo=$data['sisa_saldo']/$bagi;
       $saldo  = round(round($saldo,2,PHP_ROUND_HALF_UP)*$bagi,2) ;
       $saldo = $saldo +  ($saldo*0.05);
        ?>
        <tr style="background-color:<?=$baris['baris']?>;color:<?=$baris['text']?>">
            <td><?=$no++?></td>
            <td><?=$data['loan']?></td>
            <td><?=$data['no_center']?></td>
            <td><?=$data['id_detail_nasabah']?></td>
            <td><?=$data['nasabah']?></td>
            <td><?=angka($data['amount'])?></td>
            <td><?=angka(round($data['sisa_saldo']))?></td>
            <td><?=angka($saldo)?></td>
            <td><?=angka(round((($saldo*0.24)+$saldo)/50)/1000,2)*1000?></td>
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
    </div>
<script>
    $("#karyawan").on("change",function(){
        var url = "<?=$url.$menu?>par&anal_topup=ANALISA+TOPUP";
        let id=$(this).val();
        // alert(id);
        if(id==null){
            window.location.replace(url);

        }
        else{
            window.location.replace(url+"&id="+id);

        }
    });
</script>