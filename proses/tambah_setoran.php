<div class="row table-responsive">
	<h3 class="page-header">REKAP SETORAN FO</h3>
	<hr />
    <form method="post"  enctype="multipart/form-data">
        <div class="col-md-4">
            <label for="formFile" class="form-label">SILAHKAN PILIH FILE REKAP SETORAN FO SETELAH BALANCE(XML)</label>
            <input class="form-control" type="file" name='file' accept=".xml" id="formFile">
            <input class="form-control" type="date" name='tgl' value="<?=date("Y-m-d")?>"  >
            <input type="submit" value="Proses" class='btn btn-danger' name='xml_preview'>
        </div>
    </form>
    <?php

if(isset($_POST['xml_preview'])){
    set_time_limit(5000);
    $tanggal = $_POST['tgl'];
    mysqli_query($con,"DELETE FROM pengembalian where tgl_pengembalian='$tanggal' and id_cabang='$id_cabang'");
    mysqli_query($con,"DELETE FROM detail_pengembalian join pengembalian on pengembalian.id=detail_pengembalian.id_pengembalian where pengembalian.tgl_pengembalian='$tanggal' and id_cabang='$id_cabang'");
    $hari = format_hari_tanggal($tanggal);
    $hari = explode(",",$hari)[0];
    $hari = strtolower($hari);
    // exit;
    $file = $_FILES['file']['tmp_name'];
    $xml = simplexml_load_file($file);
    $xml = $xml->Tablix1->BranchName_Collection;
    foreach($xml->BranchName->OfficerName_Collection->OfficerName as $row){

        $row1 = $row->Textbox104;
            $pokok = int_xml($row1['Textbox106']);
            $nisbah = int_xml( $row1['Textbox107']);
            echo $row['OfficerName'].'<br/>';
            $json_detail =  json_encode($row->Textbox104);
            $json_detail = str_replace("@attributes","attribute",$json_detail);
           

        $cek_ = mysqli_query($con,"select * from pengembalian where  tgl_pengembalian='$tanggal' and nama_karyawan='$row[OfficerName]' and id_cabang='$id_cabang' ");
        if(mysqli_num_rows($cek_)){
            $ada  = mysqli_fetch_array($cek_);
            mysqli_query($con,"UPDATE pengembalian set pokok='$pokok',nisbah='$nisbah', json_pengembalian='$json_detail' , id_karyawan=null, hari_pengembalian='$hari' where id='$ada[id]'");
        }
        else{
            $pokok = int_xml($row1['Textbox106']);
            $nisbah = int_xml( $row1['Textbox107']);
            echo $row['OfficerName'].'<br/>';
            $json_detail =  json_encode($row->Textbox104);
            $json_detail = str_replace("@attributes","attribute",$json_detail);
            
            
            $q = mysqli_query($con,"INSERT INTO `pengembalian`
            (`tgl_pengembalian`, `pokok`, `nisbah`, `id_cabang`, `nama_karyawan`, `json_pengembalian`,`hari_pengembalian`) 
            VALUES ('$tanggal', '$pokok', '$nisbah', '$id_cabang', '$row[OfficerName]', '$json_detail','$hari'); 
            ");
            $id_last = mysqli_insert_id($con);
            foreach($row->CenterID_Collection->CenterID as $det){
              $det_pokok = $det['Pokok'];
              $det_nisbah = $det['NISBAH'];
              $json = json_encode($det);
              $json =  str_replace("@attributes","attribute",$json);
                mysqli_query($con,"INSERT INTO `detail_pengembalian`
                 (`id_pengembalian`, `no_center`, `pokok`, `nisbah`, `id_cabang`, `json_detail_pengembalian`) 
                 VALUES ('$id_last', '$det[CenterID1]', '$det_pokok', '$det_nisbah', '$id_cabang', '$json'); 
                ");
            }
        }
    }
    pindah($url.$menu."tambah_setoran&sinkron&tgl=$tanggal");

}
if(isset($_GET['hapus'])){
    $date = $_GET['tgl'];
    mysqli_query($con,"DELETE FROM pengembalian where tgl_pengembalian='$date' and id_cabang='$id_cabang'");
    mysqli_query($con,"DELETE FROM pengembalian where tgl_pengembalian='$date' and id_cabang='$id_cabang'");
    mysqli_query($con,"DELETE FROM detail_pengembalian where detail_tgl_pengembalian='$date' and id_cabang='$id_cabang'");
    pindah($url.$menu."tambah_setoran");
}

if(isset($_GET['sinkron'])){
    $date = $_GET['tgl'];
    
    if (isset($_POST['ganti'])) {
        $karyawan = $_POST['karyawan'];
        $mdis = $_POST['nama_mdis'];
        $topup = $_POST['sisa_topup'];
        $uang  = $_POST['pendapatan'];
        $json_ganti = $_POST['json'];
        $total_nasabah = $_POST['total_nasabah'];
        for ($i = 0; $i < count($mdis); $i++) {
            if (!empty($karyawan[$i])) {
                $ganti = str_replace('"Textbox117":"'.$uang[$i],'"Textbox117":"'.($uang[$i] - $topup[$i]),$json_ganti[$i]);
                $pengembalian = $uang[$i] - $topup[$i];
                mysqli_query($con,"UPDATE pengembalian set id_karyawan='$karyawan[$i]',total_topup=pokok+$topup[$i] ,  json_pengembalian='$ganti', total_pengembalian = '$pengembalian' where tgl_pengembalian='$date' and id_cabang='$id_cabang' and nama_karyawan='$mdis[$i]'");
            }
        }
        $hari = format_hari_tanggal($date);
        $hari = explode(",",$hari)[0];
        $hari = strtolower($hari);
        $qcen =mysqli_query($con,"select * from center where id_cabang='$id_cabang' and hari='$hari'");
        while($center = mysqli_fetch_array($qcen)){
           $dcen = mysqli_query($con,"SELECT * FROM pengembalian,detail_pengembalian WHERE pengembalian.id=detail_pengembalian.`id_pengembalian` AND tgl_pengembalian='$date'  AND pengembalian.`id_cabang`='$id_cabang' AND detail_pengembalian.`no_center`='$center[no_center]'");
           $data_center = mysqli_num_rows($dcen);
           if($data_center==0){
               $cek_kosong = mysqli_query($con,"select * from center_kosong where id_cabang='$id_cabang' and no_center='$center[no_center]'");
               if(mysqli_num_rows($cek_kosong)==0){
                   mysqli_query($con,"insert into center_kosong(no_center,id_cabang,id_karyawan,tgl_transaksi) 
                   values('$center[no_center]','$id_cabang','$center[id_karyawan]','$date')");
                  }

           }
        }


        // alert("DAFTAR PENGEMBALIAN MARGIN DAN POKOK TELAH DI UPDATE");
        // pindah($url.$menu."rekap_setoran&tgl=$date");

    }

    ?>
    <br>
    <br>
    <br>
    <br>
    <br>
    <small>ISI OS TOPUP DENGAN 0 jika TIDAK ADA TOPUP <br> JIKA ADA ISI TOTAL OS POKOK TOPUP (PERMINTAAN DISBURSE)</small>
        <form method="post"  enctype="multipart/form-data">
        <div class="col-md-4">
            <label for="formFile" class="form-label">FILE PERMINTAAN DISBURSE SUM XML</label>
            <input class="form-control" type="file" name='file' accept=".xml" id="formFile">
            <input class="form-control" type="date" name='tgl' value="<?=$date?>"  >
            <input type="submit" value="UPLOAD TOPUP" class='btn btn-danger' name='topup'>
        </div>
    </form>

    <?php 
    if(isset($_POST['topup'])){
        $file = $_FILES['file']['tmp_name'];
        $xml = simplexml_load_file($file);
        $xml = $xml->Tablix1->Details_Collection;
        mysqli_query($con,"DELETE FROM topup where id_cabang='$id_cabang' and tgl_topup='$date'");
        
        foreach($xml->Details  as $sum){
            
                        mysqli_query($con,"INSERT INTO 
                `topup` (`os_topup`, `sisa_topup`, `tgl_topup`, `nama_karyawan`, `id_cabang`) 
                VALUES ('$sum[OsPokokTopUP]', '$sum[NetDisburse]', '$date', '$sum[OfficerName]', '$id_cabang'); 
                ");
        }
        
    }
    ?>
    <form action="" method="post">
        <br>
    <table class='table'>
        <tr>
            <th>NO</th>
            <th>NAMA MDIS</th>
            <th>OS TOPUP </th>
            <th>GANTI </th>
        </tr>
        <?php 
        $total_n = 0;
        $q_nama =mysqli_query($con,"select * from pengembalian where id_cabang='$id_cabang' and tgl_pengembalian='$date' and id_karyawan is null ");
        echo mysqli_error($con);
        while($nama = mysqli_fetch_array($q_nama)){
            $json = json_decode($nama['json_pengembalian']);
            $uang = int_xml($json->attribute->Textbox117);
            $cair = int_xml($json->attribute->Textbox105);
            $cari_dis = mysqli_query($con,"select sum(os_topup) as topup, topup.* from topup where id_cabang='$id_cabang' and tgl_topup='$date' and nama_karyawan='$nama[nama_karyawan]' group by nama_karyawan");
            $cari_dis = mysqli_fetch_array($cari_dis);
            $cari_dis = $cari_dis['topup'];
            ?>
            <tr>
                <td><?=$no++?></td>
                <td><?=$nama['nama_karyawan']?></td>
                <td>
                    <input type="number" style='width:300px' name="sisa_topup[]" required value="<?=($cari_dis===null?"0":$cari_dis)?>" class="form-control">
                    <input type="hidden" style='width:300px' name="pendapatan[]" required value="<?= $uang ?>" class="form-control">
                    
                </td>
                <td>
                <input type="hidden" name="nama_mdis[]" value="<?= $nama['nama_karyawan'] ?>">
                <input type="hidden" name="total_nasabah[]" value="<?= $nama['total'] ?>">
                        <select name="karyawan[]" id="" required class='form-control'>
                            <option value="">Pilih Staff</option>
                            <?php $data_karyawan  = (karyawan($con, $id_cabang)['data']);
                            for ($i = 0; $i < count($data_karyawan); $i++) {
                                $nama_karyawan = $data_karyawan[$i]['nama_karyawan'];
                                if (strtolower($nama_karyawan) == strtolower($nama['nama_karyawan'])) {
                                    echo "<option selected value='" . $data_karyawan[$i]['id_karyawan'] . "'>" . $nama_karyawan . "</option>";
                                } else {
                                    echo "<option value='" . $data_karyawan[$i]['id_karyawan'] . "'>" . $nama_karyawan . "</option>";
                                }
                            }
                            ?>
                        </select>    
                        <textarea  style='width:0px;height:0px' readonly name="json[]" required value="<?= $nama['json_pengembalian'] ?>" class="form-control"><?= $nama['json_pengembalian'] ?></textarea>
                </td>
            </tr>
            <?php
        }
        ?>
        <tr>
                <td colspan="2"></td>
                <!-- <td colspan="1"><?=$total_n?></td> -->
                <td>
                    <input type="submit" class='btn btn-success' value='KONFIRMASI' name='ganti' />
                    <a href="<?=$url.$menu?>tambah_setoran&hapus&tgl=<?=$date?>" class="btn btn-danger">HAPUS</a>
                </td>
            </tr>
        
    </table>
    </form>
    <?php
}

?>
    
</div>


