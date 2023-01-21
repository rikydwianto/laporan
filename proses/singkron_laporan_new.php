<?php 
$tgl = isset($_GET['tgl']) ? $_GET['tgl'] : date("Y-m-d");
?>
<div class='content table-responsive'>
	<h2 class='page-header'>LAPORAN HARIAN MANUAL</h2>
    <form method='get' action='<?php echo $url . $menu ?>singkron_laporan_new'>
        <input type=hidden name='menu' value='singkron_laporan_new' />
        <input type=date name='tgl' value='<?php echo isset($_GET['tgl']) ? $_GET['tgl'] : date("Y-m-d") ?>' onchange="submit()" />
        <input type=submit name='cari' value='CARI' />
    </form>

<br>

<?php 
if(isset($_GET['idk']) && isset($_GET['id_temp'])){
    $idk = $_GET['idk'];
    $id_temp = $_GET['id_temp'];
     $q = mysqli_query($con,"select * from temp_bayar_json tb left join karyawan on karyawan.nik_karyawan=tb.nik where id_karyawan='$idk' and tb.id='$id_temp'");
    $data = mysqli_fetch_array($q);
    $data_json = $data['json'];
    $data_json = json_decode($data_json,true)['data_center'];
    
    ?>
   <form method="post">
   <div class="col-md-5">
        <table class='table table-bordered'>
            <tr>
                <td>STAFF</td>
                <td><?=$data['nama_karyawan']?></td>
            </tr>
            <tr>
                <td>TANGGAL</td>
                <td><input name='tgl' type="date" value="<?=$tgl?>"></td>
            </tr>
            <tr>
                <td>KETERANGAN</td>
                <td><textarea name="ket" class='form-control' id="" cols="30" rows="4">laporan otomatis</textarea></td>
            </tr>
            <tr>
                <td colspan="2">
                <table border="1" class="table">
                    <tr>
                        <th>Center</th>
                        <th>AGT</th>
                        <th>Client</th>
                        <th>Bayar</th>
                        <th>Tdk Bayar</th>
                        <th>Hadir</th>
                        
                    </tr>
                    <?php 
                    foreach($data_json as $dc){
                        $persen_bayar = round(($dc['bayar']/$dc['client'])*100,2);
                        $kehadiran = round(($dc['absensi']/$dc['client'])*100,2);
                        if ($persen_bayar >= 90 && $persen_bayar <= 100){
                            if($kehadiran>=80 && $kehadiran <= 100)
                                $status = "hijau";
                            else
                                $status = warna_center($kehadiran);
            
                        } 
                        else if ($persen_bayar > 50 && $persen_bayar < 90){
                            if($kehadiran>=50 && $kehadiran < 91)
                                $status = "kuning";
                            else
                                $status = warna_center($kehadiran);
            
                        }
                        else if ($persen_bayar >= 0 && $persen_bayar < 50){
                            if($kehadiran>=0 && $persen_bayar < 50)
                                $status = "merah";
                            else
                                $status = warna_center($kehadiran);
            
                        } 
                        else{
                            $status='merah';
                        }
                        if($persen_bayar>50 && $persen_bayar<=100){
                            if($kehadiran>=50){
                                $dtd='t';
                            }
                            else{
                                $dtd='y';
                            }
                        }
                        elseif($persen_bayar>=0 && $persen_bayar<=50){
                            $dtd='y';
                        }

                        ?>
                        <tr>
                            <td><input type="text" name="center[]" value="<?=$dc['center']?>" class='form-control' style='width:70px'id=""></td>
                            <td><input type="text" name="member[]" value="<?=$dc['member']?>" class='form-control' style='width:70px'id=""></td>
                            <td><input type="text" name="client[]" value="<?=$dc['client']?>" class='form-control' style='width:70px'id=""></td>
                            <td><input type="text" name="bayar[]" value="<?=$dc['bayar']?>" class='form-control' style='width:70px'id=""></td>
                            <td><input type="text" name="tdk_bayar[]" value="<?=$dc['tdk_bayar']?>" class='form-control' style='width:70px'id=""></td>
                            <td><input type="text" name="absensi[]" value="<?=$dc['absensi']?>" class='form-control' style='width:70px'id=""></td>
                            <td><input type="text" name="persen_bayar[]" value="<?=$persen_bayar?>" class='form-control' style='width:70px'id=""></td>
                            <td><input type="text" name="status[]" value="<?=$status?>" class='form-control' style='width:70px'id=""></td>
                            <td><input type="text" name="dtd[]" value="<?=$dtd?>" class='form-control' style='width:70px'id=""></td>
                            
                        </tr>
                        <?php
                    }
                    ?>
                    
                    </table>    
                </td>
            </tr>
            <tr>
                        <td colspan="2">
                            <input type="submit" name='kirim' class='btn btn-danger ' value="BUAT">
                        </td>
                    </tr>

        </table>
    </div>
   </form>
    <br>
    <?php
}
?>

<table class='table'>
    <tr>
        <th>NO</th>
        <th>NIK</th>
        <th>NAMA</th>
        <th>CENTER</th>
        <th>LAPORAN</th>
        <th>LAPORAN</th>
        <th>act</th>
    </tr>
    <?php 
    $q = mysqli_query($con,"select * from temp_bayar_json tb left join karyawan on karyawan.nik_karyawan=tb.nik where kode_cabang='$kode_cabang' and tgl='$tgl'");
    while($r= mysqli_fetch_array($q)){

        $idk = $r['id_karyawan'];
        $id_temp = $r['id'];
        $json  = $r['json'];
        $data = json_decode($json,true)['data_center'];
        $hitung_center = count($data);
        $cek_laporan = mysqli_query($con,"select * from laporan where id_karyawan='$idk' and tgl_laporan='$tgl'");
        if(mysqli_num_rows($cek_laporan)){
            $keterangan = "sudah laporan";
            $link_laporan = "";
        }
        else{
            $keterangan ="belum laporan";
            $link_laporan = "<a href='$url$menu".'singkron_laporan_new&idk='.$idk."&id_temp=".$id_temp."&tgl=$tgl' class='btn btn-danger'>Buat</a>";
        }
        ?>
        
        <tr>
            <td><?=$no++?></td>
            <td><?=$r['nik']?></td>
            <td><?=$r['nama_karyawan']?></td>
            <td><?=$hitung_center?></td>
            <td><?=$keterangan?></td>
            <td><?=$link_laporan?></td>
            <td>act</td>
        </tr>
        <?php
    }
    ?>
</table>

</div>
<?php 
if(isset($_POST['kirim'])){
    $idk = $_GET['idk'];
    $ket = $_POST['ket'];
    $tgl = $_POST['tgl'];
    $tmb_laporan = mysqli_query($con,"
    INSERT INTO `laporan` (`id_laporan`, `id_karyawan`, `tgl_laporan`, `keterangan_laporan`, `status_laporan`, `keterangan_lain`) VALUES 
    (NULL, '$idk', '$tgl', '$ket', 'sukses', NULL); 

    ");
    echo mysqli_error($con);
    $id_laporan = mysqli_insert_id($con);

    $center = $_POST['center'];
    for($i=0;$i<count($center);$i++){
        $ctr = $_POST['center'][$i];
        $member = $_POST['member'][$i];
        $client = $_POST['client'][$i];
        $bayar = $_POST['bayar'][$i];
        $tdk_bayar = $_POST['tdk_bayar'][$i];
        $absensi = $_POST['absensi'][$i];
        $warna = $_POST['status'][$i];
        $dtd = $_POST['dtd'][$i];
        mysqli_query($con,"
            INSERT INTO `detail_laporan` (`id_detail_laporan`, `id_laporan`, `no_center`, `status`, `doa`, `member`, `total_agt`, `total_bayar`, `total_tidak_bayar`, `status_detail_laporan`, `doortodoor`,`anggota_hadir`) 
            VALUES (NULL, '$id_laporan', '$ctr', '$warna', 'y', '$member', '$client', '$bayar', '$tdk_bayar', 'sukses', '$dtd','$absensi'); 

            ");


            $d = mysqli_query($con, "UPDATE center SET doortodoor='$dtd',status_center = '$warna',
            member_center = '$member' , anggota_center = '$client' , center_bayar = '$bayar' , 
             id_laporan='$id_laporan'  , anggota_hadir='$absensi' WHERE no_center = '$ctr' and id_cabang=$id_cabang; ");
            echo mysqli_error($con);
  //no_center  status  doa     member  anggota_hadir  total_agt  total_bayar  total_tidak_bayar  status_detail_laporan  doortodoor  

    }

    alert("Berhasil disimpan");
    pindah($url.$menu."singkron_laporan_new&tgl=".$tgl);
}