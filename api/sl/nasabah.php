<?php 
require_once "../../config/seting.php";
require_once "../../config/koneksi.php";
require_once("../../proses/fungsi.php");
require_once("../../model/model.php");
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

header('Access-Control-Allow-Methods: GET, POST');

header("Access-Control-Allow-Headers: X-Requested-With");
// $cari = aman($con,$_GET['cari']);
$id_cabang = aman($con,$_GET['cabang']);
// $kategori = aman($con,$_GET['kategori']);
// $berdasarkan = aman($con,$_GET['berdasarkan']);
// $berdasarkan_hasil = aman($con,$_GET['berdasarkan_hasil']);
// $id_cabang = $_GET['cabang'];
// if($cari!=null){
$table='daftar_nasabah';
//$berdasarkan like '$cari%'  
        $query = mysqli_query($con, "SELECT daftar_nasabah.*, karyawan.nama_karyawan from $table  join karyawan on karyawan.id_karyawan=daftar_nasabah.id_karyawan where 
        
        
         $table.id_cabang='$id_cabang' #group by id_detail_nasabah order by nama_nasabah asc 
        ");
        
        echo mysqli_error($con);
      $data=array();
        while($nasabah = mysqli_fetch_array($query)){ $data['nasabah'][]= $nasabah;
        }
        echo json_encode($data);
        
// }
?>