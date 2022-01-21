<div class='content table-responsive'>
	<h2 class='page-header'>LAPORAN HARIAN MANUAL</h2>
	<hr/>
	  <!-- Button to Open the Modal -->
  <!-- <a href='<?=$url.$menu?>cabang&tambah' class="btn btn-success" >
    <i class="fa fa-plus"></i> Tambah Cabang
  </a>
  <a href='<?=$url.$menu?>cabang&tambah_wilayah' class="btn btn-info" >
    <i class="fa fa-plus"></i> Tambah Wilayah
  </a> -->
  <form method='get' action='<?php echo $url . $menu ?>laporan_manual'>
        <input type=hidden name='menu' value='laporan_manual' />
        <input type=date name='tgl' value='<?php echo isset($_GET['tgl']) ? $_GET['tgl'] : date("Y-m-d") ?>' onchange="submit()" />
        <input type=submit name='cari' value='CARI' />
    </form>
<br>


<form action="" name='laporan_manual[]' method="post">
    <table class='table table-bordered'>
<?php 
if (isset($_GET['tgl'])) {
    $tgl = $_GET['tgl'];
} else {
    $tgl = date("Y-m-d");
}
// $tgl = date("Y-m-d");
$hari = format_hari_tanggal($tgl);
$hari = explode(',', $hari);
$hari = strtolower($hari[0]);
// $hari = "senin";
$qkar = mysqli_query($con,"SELECT distinct k.nama_karyawan, k.id_karyawan from center c join karyawan k on k.id_karyawan=c.id_karyawan where c.id_cabang='$id_cabang' and c.hari='$hari' order by k.nama_karyawan asc");
while($kar = mysqli_fetch_array($qkar)){
    $qcenter = mysqli_query($con,"SELECT * from center where id_cabang='$id_cabang' and hari='$hari' and id_karyawan='$kar[id_karyawan]'");
   $cek = mysqli_query($con,"SELECT * from laporan where id_karyawan='$kar[id_karyawan]' and tgl_laporan='$tgl' and status_laporan='sukses'");
//    echo "SELECT * from laporan where id_karyawan='$kar[id_karyawan]' and id_cabang='$id_cabang' and tgl_laporan='$tgl' and status_laporan='sukses'";
//    echo print_r($cek);
   if(mysqli_num_rows($cek)>0){
    //    echo"ada";
   }
   else
   {
       ?>
       <tr>
            <th rowspan="1">
                <?=$kar['nama_karyawan'] ?>
                <input type="hidden" value="<?=$kar['id_karyawan']?>" name='id_karyawan[]'>
            </th>
            <th>KETERANGAN</th>
            <th>TANGGAL</th>
        </tr>
        <tr>
            <td rowspan="1" style="text-align: center; vertical-align: middle;">LAPORAN</td>
            <td rowspan="1">
                <table border="1" class="table">
                    <tr>
                        <th>Center</th>
                        <th>AGT</th>
                        <th>Client</th>
                        <th>Bayar</th>
                    </tr>
                    <?php
                    $tc = 0;
                while($center = mysqli_fetch_array($qcenter)){
                    ?>
                    <tr>
                        <td>
                            <input type="text" class='form-control' style="width: 50px;" name="center[<?=$kar['id_karyawan']?>][]" value="<?=$center['no_center']?>" id="">
                        </td>
                        <td>
                             <input type="text" class='form-control' style="width: 50px;" name="agt[<?=$kar['id_karyawan']?>][]" value="<?=$center['member_center']?>" id="">
                            </td>
                        <td>
                            <input type="text" class='form-control' style="width: 50px;" name="client[<?=$kar['id_karyawan']?>][]" value="<?=$center['anggota_center']?>" id="">
                                
                        </td>
                        <td>
                        <input type="text" class='form-control' style="width: 50px;" name="bayar[<?=$kar['id_karyawan']?>][]" value="<?=$center['center_bayar']?>" id="">
                            
                        </td>
                    </tr>
                    <?php }
                    ?>
                    </table>    
            </td>
            <td>
                <input type="date" value="<?=$tgl?>" style="width: 200px;" class='form-control' name='tanggal[]'/> <br/>
                <textarea name="keterangan[]" class='form-control' id="" cols="15" rows="2" >Report by <?=$nama_karyawan?></textarea>
           
            </td>
        </tr>
       <?php
   }
   ?>

        
       
        
           
            <?php
}
?>
<tr>
            <td colspan="2"></td>
            <td colspan="1">
                <input type="submit"name="laporan" value="KONFIRMASI" class='btn btn-lg btn-danger'>
            </td>
        </tr>
</table>
</form>
  
</div>
<?php 
if(isset($_POST['laporan'])){
    $hitung = $_POST['id_karyawan'];
    // print_r($_POST);
    for($i=0;$i<count($hitung);$i++){
        $idk = $_POST['id_karyawan'][$i];
        $tgl = $_POST['tanggal'][$i];
        $ket = $_POST['keterangan'][$i];
        // $center = $_POST['center'];
        $param = "center";
         $center =  ($_POST["center"][$idk]);
         $agt =  ($_POST["agt"][$idk]);
         $client =  ($_POST["client"][$idk]);
         $bayar =  ($_POST["bayar"][$idk]);
         $tmb_laporan = mysqli_query($con,"
         INSERT INTO `laporan` (`id_laporan`, `id_karyawan`, `tgl_laporan`, `keterangan_laporan`, `status_laporan`, `keterangan_lain`) VALUES 
         (NULL, '$idk', '$tgl', '$ket', 'pending', NULL); 

         ");
         $id_laporan = mysqli_insert_id($con);
        $hitung_center  = count($center);
        for($a=0;$a<$hitung_center;$a++){
            $tidak_bayar = $client[$a] - $bayar[$a];
            mysqli_query($con,"
            INSERT INTO `detail_laporan` (`id_detail_laporan`, `id_laporan`, `no_center`, `status`, `doa`, `member`, `total_agt`, `total_bayar`, `total_tidak_bayar`, `status_detail_laporan`, `doortodoor`) 
            VALUES (NULL, '$id_laporan', '$center[$a]', 'hijau', 'y', '$agt[$a]', '$client[$a]', '$bayar[$a]', '$tidak_bayar[$a]', 'sukses', 't'); 

            ");
            
        }
        mysqli_query($con,"update laporan set status_laporan='sukses' where id_laporan='$id_laporan'");
        alert("BERHASIL MEMBUAT SEMUA LAPORAN");
        pindah($url);
        
                
    }
}
?>


