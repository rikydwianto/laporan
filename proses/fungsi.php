<?php
function alert($isi){
  ?>
  <script>
    alert('<?php echo $isi?>')
  </script>
  
  <?php
}
function pindah($url){
  ?>
  <script>
    window.location.href = "<?php echo $url?>";
  </script>
  <?php
  
}
function kembali(){
  ?>
  <script>
    window.history.back(-2);
  </script>
  <?php
  
}


function format_hari_tanggal($waktu)
{
    $hari_array = array(
        'Minggu',
        'Senin',
        'Selasa',
        'Rabu',
        'Kamis',
        'Jumat',
        'Sabtu'
    );
    $hr = date('w', strtotime($waktu));
    $hari = $hari_array[$hr];
    $tanggal = date('j', strtotime($waktu));
    $bulan_array = array(
        1 => 'Januari',
        2 => 'Februari',
        3 => 'Maret',
        4 => 'April',
        5 => 'Mei',
        6 => 'Juni',
        7 => 'Juli',
        8 => 'Agustus',
        9 => 'September',
        10 => 'Oktober',
        11 => 'November',
        12 => 'Desember',
    );
    $bl = date('n', strtotime($waktu));
    $bulan = $bulan_array[$bl];
    $tahun = date('Y', strtotime($waktu));
    $jam = date( 'H:i:s', strtotime($waktu));
    
    //untuk menampilkan hari, tanggal bulan tahun jam
    //return "$hari, $tanggal $bulan $tahun $jam";

    //untuk menampilkan hari, tanggal bulan tahun
    return "$hari, $tanggal $bulan $tahun";
}

function hari_biasa($waktu)
{
    $hari_array = array(
        'Minggu',
        'Senin',
        'Selasa',
        'Rabu',
        'Kamis',
        'Jumat',
        'Sabtu'
    );
    $hr = date('w', strtotime($waktu));
    $hari = $hari_array[$hr];
    $tanggal = date('j', strtotime($waktu));
    $bulan_array = array(
        1 => 'Januari',
        2 => 'Februari',
        3 => 'Maret',
        4 => 'April',
        5 => 'Mei',
        6 => 'Juni',
        7 => 'Juli',
        8 => 'Agustus',
        9 => 'September',
        10 => 'Oktober',
        11 => 'November',
        12 => 'Desember',
    );
    $bl = date('n', strtotime($waktu));
    $bulan = $bulan_array[$bl];
    $tahun = date('Y', strtotime($waktu));
    $jam = date( 'H:i:s', strtotime($waktu));
    
    //untuk menampilkan hari, tanggal bulan tahun jam
    //return "$hari, $tanggal $bulan $tahun $jam";

    //untuk menampilkan hari, tanggal bulan tahun
    return "$hari-$tanggal-$bulan-$tahun";
}

$bulan = array(1=>'Januari', 
      'Februari', 
      'Maret', 
      'April', 
      'Mei', 
      'Juni', 
      'Juli', 
      'Agustus', 
      'September', 
      'Oktober', 
      'November', 
      'Desember'
     );
   
   
 function bulan_indo($bulan_angka) {
   $bulan = array(1=>'Januari', 
      'Februari', 
      'Maret', 
      'April', 
      'Mei', 
      'Juni', 
      'Juli', 
      'Agustus', 
      'September', 
      'Oktober', 
      'November', 
      'Desember'
     );

   return $bulan[$bulan_angka];
  }
function center($con,$no_center,$doa,$status,$member,$anggota_center,$bayar,$id_cabang,$id_karyawan,$hari,$idlaporan,$jam,$dtd)
{
  $no_center = sprintf("%03d",$no_center);
  $cari = mysqli_query($con,"select * from center where no_center='$no_center' and id_cabang='$id_cabang'");
  if(mysqli_num_rows($cari)){
    $d = mysqli_query($con,"UPDATE center SET doortodoor='$dtd', hari='$hari',doa_center='$doa',status_center = '$status', member_center = '$member' , anggota_center = '$anggota_center' , center_bayar = '$bayar' , id_karyawan = '$id_karyawan', id_laporan='$idlaporan' , jam_center='$jam'  WHERE no_center = '$no_center' and id_cabang=$id_cabang; ");
  // echo "jalankan perintah edit";  
  }
  else{
  // echo "jalankan perintah tambah";  
    $d = mysqli_query($con,"INSERT INTO center (id_center, no_center, hari, doa_center, status_center, member_center,anggota_center, center_bayar, id_cabang, id_karyawan,id_laporan,jam_center,doortodoor) VALUES (NULL, '$no_center','$hari', '$doa', '$status', '$member', '$anggota_center','$bayar', '$id_cabang', '$id_karyawan','$idlaporan','$jam','$dtd')");
  }
  //INSERT INTO center (id_center, no_center, doa_center, status_center, anggota_center, id_cabang, id_karyawan) VALUES (NULL, '107', 't', 'kuning', '18', '1', '1');
}
function detail_karyawan($con,$id)
{
  $query= mysqli_query($con,"SELECT * FROM karyawan,jabatan,cabang,wilayah where karyawan.id_jabatan=jabatan.id_jabatan and karyawan.id_cabang=cabang.id_cabang 
    and cabang.id_wilayah=wilayah.id_wilayah
    and karyawan.id_karyawan='$id' ");
  $karyawan = mysqli_fetch_array($query);
  return $karyawan;
}

function pesan($pesan,$warna='primary')
{
  ?>
  <div class="alert alert-<?=$warna?>" role="alert">
    <?=$pesan ?>
  </div>
  <?php
}


function aman($con,$string){
  return mysqli_escape_string($con,$string);
}



function hari(){
  $hari_array = array(
        'Minggu',
        'Senin',
        'Selasa',
        'Rabu',
        'Kamis',
        'Jumat',
        'Sabtu'
    );
    return ($hari_array);


}


function cabang($con,$wilayah){

  $q = "select * from cabang where id_wilayah = $wilayah ";
  $query=mysqli_query($con,$q);
  $arra = array();
  $arra['data']=array();

  while($data = mysqli_fetch_array($query)){
    $h['id_cabang'][]= $data['id_cabang'];
    $h['cabang'] = $data['nama_cabang'];
    $h['kodecabang'] = $data['kode_cabang'];
    $h['wilayah'] = $data['id_wilayah'];
    array_push($arra["data"], $h);

  }
  return ($arra);
    // $arra['id'] = $data['id_cabang'];
    // $arra['cabang'] = $data['nama_cabang'];
    // $arra['kode_cabang'] = $data['kode_cabang'];
    // $arra['wilayah'] = $data['id_wilayah'];
}


function link_maps($lat,$lng){
  return "https://www.google.com/maps/place/".$lat.",".$lng."/".$lat.",".$lng.",17z/data=!3m1!4b1";
}




function wilayah($con,$kode){
  $wilaya  = mysqli_query($con, "SELECT nama FROM daftar_wilayah WHERE kode='$kode' limit 0,1");
  $wilaya = mysqli_fetch_array($wilaya);
  return $wilaya['nama'];
}



function daftar_wilayah($con,$kode){
  $wilaya  = mysqli_query($con, "SELECT * FROM daftar_wilayah_cabang WHERE  id_daftar_wilayah='$kode' limit 0,1");
  $wilaya = mysqli_fetch_array($wilaya);
  return $wilaya;
}


function cek_center($con,$center){
  if(empty($center))
  {
    echo "-";
  }
  else{
    $q= mysqli_query($con,"select * from center left join karyawan on center.id_karyawan=karyawan.id_karyawan where center.no_center='$center'");

    $cen = mysqli_fetch_array($q);
    if($cen['no_center']!="")
    {
      echo "<small>$center/$cen[hari]/$cen[nama_karyawan]</small>";
    }
    else
    {
      echo "<i><small>center tidak ditemukan</small></i>";
    }
  }
 
}
