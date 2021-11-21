<table border=1>
    <tr>
        <td>no</td>
        <td>ID NASABAH</td>
        <td>NO CENTER</td>
        <td>DETAIL NASABAH</td>
        <td>NAMA</td>
        <td>SUAMI</td>
        <td>NO KTP</td>
        <td>ALAMAT</td>
        <td>TGL BERGABUNG</td>
        <td>HP NASABAH</td>
        <td>STAFF</td>
        <td>HARI</td>
        <td>CABANG</td>
    </tr>


<?php 
require_once "../config/seting.php";
require_once "../config/koneksi.php";
require_once("../proses/fungsi.php");
require_once("../model/model.php");
$id_karyawan = $_SESSION['id'];
$nama_karyawan = $_SESSION['nama_karyawan'];
$jabatan= $_SESSION['jabatan'];
$cabang= $_SESSION['cabang'];
$id_cabang= $_SESSION['cabang'];
$su= $_SESSION['su'];
require("../vendor/PHPExcel/Classes/PHPExcel.php");
$path = "../RAHASIA/daftar_nasabah.xlsx";
$reader = PHPExcel_IOFactory::createReaderForFile($path);
$objek = $reader->load($path);
$ws = $objek->getActiveSheet();
$last_row = $ws->getHighestDataRow();
echo $last_row;
$no_input=0;
for($row = 5;$row<=$last_row;$row++){
    $id_nasabah =  $ws->getCell("F" . $row)->getValue();
    if($id_nasabah==null){
        
    }
    else{
        $agt = (substr(ganti_karakter($id_nasabah),0,3));

        if( $agt=="AGT"){
            $id_nasabah = ganti_karakter1($ws->getCell("F".$row)->getValue());
            $no_id  = explode("-",$id_nasabah)[1];
            $no_id = sprintf("%0d",$no_id);
            $nasabah =  ganti_karakter($ws->getCell("G".$row)->getValue());
            $suami =  ganti_karakter($ws->getCell("I".$row)->getValue());
           $no_center = ganti_karakter($ws->getCell("D".$row)->getValue());
           $kelompok = ganti_karakter1($ws->getCell("E".$row)->getValue());
           $hp = ganti_karakter1($ws->getCell("S".$row)->getValue());
           $ktp = ganti_karakter1($ws->getCell("L".$row)->getValue());
           $rt = ganti_karakter1($ws->getCell("Q".$row)->getValue());
           $rw = ganti_karakter1($ws->getCell("R".$row)->getValue());
           
           $alamat = "RT $rt / RW. $rw ". ganti_karakter1($ws->getCell("J".$row)->getValue());
           $staff = ganti_karakter1($ws->getCell("U".$row)->getValue());
           $hari = ganti_karakter1($ws->getCell("T".$row)->getValue());
           $tgl_bergabung = str_replace("/","-",ganti_karakter1($ws->getCell("K".$row)->getValue()));
           $tgl_bergabung =  date("Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($tgl_bergabung));
           $q = mysqli_query($con,"select id_detail_nasabah from daftar_nasabah where id_detail_nasabah='$id_nasabah' and id_cabang='$id_cabang'");
           if(mysqli_num_rows($q)){
            $ket="ada di db";   
            //tidak usah di insert
            mysqli_query($con,"update daftar_nasabah set  hp_nasabah='$hp', staff='$staff', hari='$hari',no_ktp='$ktp' where id_detail_nasabah='$id_nasabah'");
           }
           else{
                $cari_mantan = mysqli_num_rows(mysqli_query($con,"select id_detail_nasabah from daftar_nasabah_mantan where id_detail_nasabah='$id_nasabah'"));
                if($cari_mantan){
                    $ket = "harus di insert nih";
                    $no_input++;
                    mysqli_query($con,"
                    INSERT INTO `daftar_nasabah` 
                    ( `id_nasabah`, `no_center`, `id_detail_nasabah`, `nama_nasabah`, `suami_nasabah`, `no_ktp`, `alamat_nasabah`, `tgl_bergabung`, `hp_nasabah`, `staff`, `hari`, `id_cabang`) VALUES 
                    ( '$no_id', '$no_center', '$id_nasabah', '$nasabah', '$suami', '$ktp', '$alamat', '$tgl_bergabung', '$hp', '$staff', '$hari', '$id_cabang'); 

                    ");

                }   
                else{
                    //keluar tidak ada ditable mantan
                    mysqli_query($con,"
                    INSERT INTO `daftar_nasabah_mantan` 
                    ( `id_nasabah`, `no_center`, `id_detail_nasabah`, `nama_nasabah`, `suami_nasabah`, `no_ktp`, `alamat_nasabah`, `tgl_bergabung`, `hp_nasabah`, `staff`, `hari`, `id_cabang`) VALUES 
                    ( '$no_id', '$no_center', '$id_nasabah', '$nasabah', '$suami', '$ktp', '$alamat', '$tgl_bergabung', '$hp', '$staff', '$hari', '$id_cabang'); 

                    ");
                }
           }
           ?>
           <tr>
                <td><?=$no++?></td>
                <td><?=$no_id?></td>
                <td><?=$no_center?></td>
                <td><?=$id_nasabah?></td>
                <td><?=$nasabah?></td>
                <td><?=$suami?></td>
                <td><?=$ktp?></td>
                <td><?=$alamat?></td>
                <td><?=$tgl_bergabung?></td>
                <td><?=$hp?></td>
                <td><?=$staff?></td>
                <td><?=$hari?></td>
                <td><?=$id_cabang?></td>
                <td><?=$ket?></td>
                
           </tr>
           <?php

           
    
        }
        
           
            
        
    }
}
 alert("Sebanyak ". ($no_input) . " telah diinput, silahkan sinkron");
?>
</table>