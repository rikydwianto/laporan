<table>
    <tr>
        <td>no</td>
        <td>loan</td>
        <td>no_center</td>
        <td>id_nasabah</td>
        <td>nasabah</td>
        <td>amount</td>
        <td>balance</td>
        <td>tunggakan</td>
        <td>minggu</td>
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
$path = "../RAHASIA/deliq.xlsx";
$reader = PHPExcel_IOFactory::createReaderForFile($path);
$objek = $reader->load($path);
$ws = $objek->getActiveSheet();
$last_row = $ws->getHighestDataRow();

for($row = 7;$row<=$last_row;$row++){
    $id_nasabah =  $ws->getCell("D" . $row)->getValue();
    if($id_nasabah==null){
        
    }
    else{
        $agt = (substr(ganti_karakter($id_nasabah),0,3));

        if( $agt=="AGT"){
            $nasabah =  ganti_karakter($ws->getCell("E".$row)->getValue());
           $loan = ganti_karakter($ws->getCell("B".$row)->getValue());
           $no_center = ganti_karakter($ws->getCell("C".$row)->getValue());
           $id_nasabah = ganti_karakter1($ws->getCell("D".$row)->getValue());
           $amount = (int)ganti_karakter(str_replace(",","",$ws->getCell("F".$row)->getValue()));
           $balance = (int)ganti_karakter(str_replace(",","",$ws->getCell("K".$row)->getValue()));
           $tunggakan = (int)ganti_karakter(str_replace(",","",$ws->getCell("L".$row)->getValue()));
           $minggu = (int)ganti_karakter(str_replace(",","",$ws->getCell("M".$row)->getValue()));
           ?>
           <tr>
               <td><?=$no++?></td>
               <td><?=$loan?></td>
               <td><?=$no_center?></td>
               <td><?=$id_nasabah?></td>
               <td><?=$nasabah?></td>
               <td><?=$amount?></td>
               <td><?=$balance?></td>
               <td><?=$tunggakan?></td>
               <td><?=$minggu?></td>
           </tr>
           <?php
        //    INSERT INTO `deliquency` (`id`, `loan`, `no_center`, `id_detail_nasabah`, `nasabah`, `amount`, `sisa_saldo`, `tunggakan`, `minggu`, `tgl_input`, `id_cabang`) VALUES (NULL, 'PU-072-21-01-000216', '003', 'AGT/072/01/003-000034', 'RUMNASIH', '6', '2', '1', '8', NULL, NULL); 

        }
        
           
            
        
    }
}
?>
</table>