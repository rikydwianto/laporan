<?php
function alert($isi)
{
?>
  <script>
    alert('<?php echo $isi ?>')
  </script>

<?php
}
function pindah($url)
{
?>
  <script>
    window.location.href = "<?php echo $url ?>";
  </script>
<?php

}
function kembali()
{
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
  $jam = date('H:i:s', strtotime($waktu));

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
  $jam = date('H:i:s', strtotime($waktu));

  //untuk menampilkan hari, tanggal bulan tahun jam
  //return "$hari, $tanggal $bulan $tahun $jam";

  //untuk menampilkan hari, tanggal bulan tahun
  return "$hari-$tanggal-$bulan-$tahun";
}

$bulan = array(
  1 => 'Januari',
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


function bulan_indo($bulan_angka)
{
  $bulan = array(
    1 => 'Januari',
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
function center($con, $no_center, $doa, $status, $member, $anggota_center, $bayar, $id_cabang, $id_karyawan, $hari, $idlaporan, $jam, $dtd,$hadir)
{
  $no_center = sprintf("%03d", $no_center);
  $cari = mysqli_query($con, "select * from center where no_center='$no_center' and id_cabang='$id_cabang'");
  if (mysqli_num_rows($cari)) {
    $d = mysqli_query($con, "UPDATE center SET anggota_hadir='$hadir',doortodoor='$dtd', hari='$hari',doa_center='$doa',status_center = '$status', member_center = '$member' , anggota_center = '$anggota_center' , center_bayar = '$bayar' , id_karyawan = '$id_karyawan', id_laporan='$idlaporan' , jam_center='$jam'  WHERE no_center = '$no_center' and id_cabang=$id_cabang; ");
    // echo "jalankan perintah edit";  
  } else {
    // echo "jalankan perintah tambah";  
    $d = mysqli_query($con, "INSERT INTO center (id_center, no_center, hari, doa_center, status_center, member_center,anggota_center, center_bayar, id_cabang, id_karyawan,id_laporan,jam_center,doortodoor) VALUES (NULL, '$no_center','$hari', '$doa', '$status', '$member', '$anggota_center','$bayar', '$id_cabang', '$id_karyawan','$idlaporan','$jam','$dtd')");
  }
  //INSERT INTO center (id_center, no_center, doa_center, status_center, anggota_center, id_cabang, id_karyawan) VALUES (NULL, '107', 't', 'kuning', '18', '1', '1');
}
function detail_karyawan($con, $id)
{
  $query = mysqli_query($con, "SELECT * FROM karyawan,jabatan,cabang,wilayah where karyawan.id_jabatan=jabatan.id_jabatan and karyawan.id_cabang=cabang.id_cabang 
    and cabang.id_wilayah=wilayah.id_wilayah
    and karyawan.id_karyawan='$id' ");
  $karyawan = mysqli_fetch_array($query);
  return $karyawan;
}

function pesan($pesan, $warna = 'primary')
{
?>
  <div class="alert alert-<?= $warna ?>" role="alert">
    <?= $pesan ?>
  </div>
<?php
}
function int_xml($angka){
  $pecah = explode('.',$angka)[0];
  return (int) $pecah;
  }


function aman($con, $string)
{
  return htmlspecialchars(mysqli_escape_string($con, $string));
}



function hari()
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
  return ($hari_array);
}


function cabang($con, $wilayah)
{

  $q = "select * from cabang where id_wilayah = $wilayah ";
  $query = mysqli_query($con, $q);
  $arra = array();
  $arra['data'] = array();

  while ($data = mysqli_fetch_array($query)) {
    $h['id_cabang'][] = $data['id_cabang'];
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


function link_maps($lat, $lng)
{
  return "https://www.google.com/maps/place/" . $lat . "," . $lng . "/" . $lat . "," . $lng . ",17z/data=!3m1!4b1";
}


function rupiah($angka){
  $hasil = "Rp. " . number_format($angka,2,',','.');
  return $hasil;
}
function angka_mentah($angka){
  $angka1 =  str_replace(",","",$angka);
  $angka1 = (int)$angka1;
  return $angka1;
}

function angka($angka,$separator=null){
  if($separator == 'titik'){
    $sepat = '.';
  } 
  else if($separator == 'tanpa_titik')
  {
    $sepat = '';
  }
  else if($separator == 'koma'){
    $sepat = ',';
  }
  else if($separator == 'strip') {
    $sepat = '-';
  }
  else {
    $sepat = '.';
  }
  $hasil =  number_format($angka,0,',',"$sepat");
  return $hasil;
}
function separator($tipe='titik'){
  ?>
  <select name="tipe" id="" class='form-control'>
        <option <?=($tipe==="titik"?"selected":"");?> value="titik">Titik</option>
        <option <?=($tipe==="tanpa_titik"?"selected":"");?>  value="tanpa_titik">Tanpa Titik</option>
        <option <?=($tipe==="koma"?"selected":"");?>  value="koma">Koma</option>
        <option <?=($tipe==="strip"?"selected":"");?>  value="strip">Strip</option>
    </select>
  <?php
}

function wilayah($con, $kode)
{
  $wilaya  = mysqli_query($con, "SELECT nama FROM daftar_wilayah WHERE kode='$kode' limit 0,1");
  $wilaya = mysqli_fetch_array($wilaya);
  return $wilaya['nama'];
}



function daftar_wilayah($con, $kode)
{
  $wilaya  = mysqli_query($con, "SELECT * FROM daftar_wilayah_cabang WHERE  id_daftar_wilayah='$kode' limit 0,1");
  $wilaya = mysqli_fetch_array($wilaya);
  return $wilaya;
}


function cek_center($con, $center)
{
  if (empty($center)) {
    echo "-";
  } else {
    $q = mysqli_query($con, "select * from center left join karyawan on center.id_karyawan=karyawan.id_karyawan where center.no_center='$center'");

    $cen = mysqli_fetch_array($q);
    if ($cen['no_center'] != "") {
      echo "<small>$center/$cen[hari]/$cen[nama_karyawan]</small>";
    } else {
      echo "<i><small>center tidak ditemukan</small></i>";
    }
  }
}


function status_upk()
{
  $status = array(
    'jadi', 'pending', 'batal'
  );
  return ($status);
}


function karyawan($con,$id_cabang){
  $cek_ka=mysqli_query($con,"SELECT * FROM karyawan,jabatan,cabang where karyawan.id_jabatan=jabatan.id_jabatan and karyawan.id_cabang=cabang.id_cabang and karyawan.id_cabang='$id_cabang' and jabatan.singkatan_jabatan='SL' and karyawan.status_karyawan='aktif' order by karyawan.nama_karyawan asc");
  $arra = array();
  $arra['data'] = array();

  while ($data = mysqli_fetch_array($cek_ka)) {
    $h['id_karyawan'] = $data['id_karyawan'];
    $h['nama_karyawan'] = $data['nama_karyawan'];
    array_push($arra["data"], $h);}
    return $arra;
}

function ganti_karakter($text){
 return   preg_replace('/[^a-zA-Z0-9()_ .,"\'\\-;]/', '', $text);
}

function ganti_karakter1($text){
 return   preg_replace('/[^a-zA-Z0-9()_ .,"\'-;]/', '', $text);
}


function hitung_monitoring($con,$id){
  $sql = "SELECT  id_karyawan,
	SUM(CASE WHEN produk = 'PINJAMAN UMUM' THEN 1 ELSE 0 END) AS pu,
	SUM(CASE WHEN produk = 'PINJAMAN MIKROBISNIS' THEN 1 ELSE 0 END) AS pmb,
	SUM(CASE WHEN produk = 'PINJAMAN SANITASI' THEN 1 ELSE 0 END) AS psa,
	SUM(CASE WHEN produk = 'PINJAMAN DT. PENDIDIKAN' THEN 1 ELSE 0 END) AS ppd,
	SUM(CASE WHEN produk = 'PINJAMAN ARTA' THEN 1 ELSE 0 END) AS arta,
	SUM(CASE WHEN 
	produk != 'PINJAMAN UMUM' AND  
	produk != 'PINJAMAN SANITASI' AND
	produk != 'PINJAMAN MIKROBISNIS' AND
	produk != 'PINJAMAN DT. PENDIDIKAN' AND
	produk != 'PINJAMAN ARTA' THEN 1 ELSE 0 END) AS lain_lain,
	COUNT(*) AS total
	
FROM pinjaman GROUP BY id_karyawan";
}


function hapus_nama_belakang($nama){
  $input = $nama;
  // $output = substr($input, 0, strrpos($input, " "));
  $pecah = explode(" ",$input);
  $total = count($pecah);
  if($total<3){
    $output = $input;
  }
  else{
    $output =$pecah[0]." ".$pecah[1]." ".$pecah[2][0].".";
  }
  // echo $
  return $output;
}

function warna_center($persen){
  if($persen>=90 )
    {
      $status="hijau";
    }
    elseif($persen>=50 && $persen<90)
    {
      $status="kuning";
    }
    elseif($persen>=0 && $persen<50){
      $status='merah';
    }
    else{
      $status='hitam';
    }
    return $status;
}