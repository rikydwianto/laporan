<div class='content table-responsive'>
	<h3 class='page-header'>LAPORAN KEGIATAN HARIAN</h3>
	<!-- <i>Center otomatis dibuat ketika Staff membuat laporan</i><hr/> -->
	  <!-- Button to Open the Modal -->
  <!-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalku">
    <i class="fa fa-plus"></i> Center
  </button> -->
  <?php 
  $tgl = $_GET['tgl'];
  $cek = mysqli_query($con,"SELECT * from laporan_harian where id_cabang='$id_cabang' and id_karyawan='$id_karyawan' and tgl_laporan='$tgl'");
  if(mysqli_num_rows($cek)==0){
    mysqli_query($con,"INSERT INTO `laporan_harian` 
    (`kunjungan_dtd`, `kunjungan_biasa`, `capres`, `uji_kelayakan`, `kartu_kuning`, `monitoring`, `tgl_laporan`, `id_karyawan`, `id_cabang`,`status`) 
    VALUES ('0', '0', '0', '0', '0', '0', '$tgl', '$id_karyawan', '$id_cabang','pending'); 
    ");
   $id_kegiatan = mysqli_insert_id($con);
   pindah($url.$menu."laporan_kegiatan&tgl=".$tgl);
  }
  $cek1 = mysqli_query($con,"SELECT * from laporan_harian where id_cabang='$id_cabang' and id_karyawan='$id_karyawan' and tgl_laporan='$tgl'");

  $r1 = mysqli_fetch_array($cek1);
    $id_kegiatan = $r1['id'];
    
  
  if(isset($_POST['simpan_kunjungan'])){    
      $kunjungan_dtd = $_POST['kunj_dtd'];
      $kunjungan_biasa = $_POST['kunj_biasa'];
      $capres = $_POST['capres'];
      $uk = $_POST['uk'];
      $mtr = $_POST['mtr'];
      $kk = $_POST['kartu_kuning'];
      $q = mysqli_query($con,"UPDATE laporan_harian set kunjungan_dtd='$kunjungan_dtd',kunjungan_biasa='$kunjungan_biasa',
      capres='$capres',uji_kelayakan='$uk', monitoring='$mtr', kartu_kuning='$kk', status='sukses' where id_cabang='$id_cabang' and id = '$id_kegiatan' ");
      if($q){
          pesan("BERHASIL DISIMPAN");
      }
      else{
          echo mysqli_error($con);
      }
  }
  $cek2 = mysqli_query($con,"SELECT * from laporan_harian where id_cabang='$id_cabang' and id='$id_kegiatan'");

  $r2 = mysqli_fetch_array($cek2);
    $id_kegiatan = $r2['id'];
    
  ?>
  <form action="" method="post">
      <table class='table'>
      <tr>
              <td>TGL KEGIATAN</td>
              <td><input type="date" class='form-control' id='tgl' onchange="tgl_ganti('<?=$r2['tgl_laporan']?>')" value='<?=$r2['tgl_laporan']?>' name='tgl'></td>
          </tr>
          <tr>
              <td>KUNJUNGAN AGT MASALAH</td>
              <td><input type="number" class='form-control' min="0" value='<?=$r2['kunjungan_dtd']?>' name='kunj_dtd'></td>
          </tr>
          <tr>
              <td>KUNJUNGAN BUKAN MASALAH</td>
              <td><input type="number"  class='form-control' min="0" value='<?=$r2['kunjungan_biasa']?>' name='kunj_biasa'></td>
          </tr>
          <tr>
              <td>CAPRES</td>
              <td><input type="number" class='form-control' min="0" value='<?=$r2['capres']?>' name='capres'></td>
          </tr>
          <tr>
              <td>UJI KELAYAKAN</td>
              <td><input type="number" class='form-control' min="0" value='<?=$r2['uji_kelayakan']?>' name='uk'></td>
          </tr>
          <tr>
              <td>MONITORING</td>
              <td><input type="number" class='form-control' min="0" value='<?=$r2['monitoring']?>' name='mtr'></td>
          </tr>
          <tr>
              <td>KARTU KUNING</td>
              <td><input type="number" class='form-control' min="0" value='<?=$r2['kartu_kuning']?>' name='kartu_kuning'></td>
          </tr>
          
          <tr>
              <td>&nbsp;</td>
              <td><input type="submit" name='simpan_kunjungan' value='SIMPAN' class='btn btn-success'></td>
          </tr>
      </table>

  </form>

</div>
<script>
    function tgl_ganti(tgl){
        var tgl = $("#tgl").val();
        const url = "<?=$url.$menu?>laporan_kegiatan&tgl=" + tgl ;
        location.href= url;
        // alert(a)
    }
</script>

  