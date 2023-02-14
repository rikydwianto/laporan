<!DOCTYPE html>
<html lang="en">
<?php require_once "../config/seting.php";
require_once "../config/koneksi.php";
require_once("../proses/fungsi.php");

require_once("../model/model.php");
require("../vendor/PHPExcel/Classes/PHPExcel.php");
$id_karyawan = $_SESSION['id'];
//$tgl = aman($con,$_GET['tgl']);
$sepat = 'titik';
error_reporting(0);
$nama_karyawan = $_SESSION['nama_karyawan'];
$jabatan = $_SESSION['jabatan'];
$cabang = $_SESSION['cabang'];
$id_cabang = $_SESSION['cabang'];
$tgl_awal = $_SESSION['tgl_awal'];
$tgl_to = $_SESSION['tgl_to'];

$su = $_SESSION['su'];
$d = detail_karyawan($con, $id_karyawan);
$nama_jabatan = $d['singkatan_jabatan'];
$_SESSION['kode_cabang']=$d['kode_cabang'];?>
<head>
    <style>
        table{
            border-collapse: collapse;
            white-space: nowrap;
            /* border: solid 1px black; */
        }
       *{
        font-family: Arial;
        
       }
       .kertas {
           margin-left: 1cm;
       }
    </style>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simpanan hari Raya</title>
</head>
<body>
<div class='kertas'>
    <div id='header'>

        <table border="0">
            <tr>
            <td width="100">
                <div >
                    <img src="<?=$url?>assets/md.png" style="width:80px" alt="">
                    <h3 style="margin-top:0px;font-family:'Times New Roman', Times, serif">KOMIDA</h3>
                </div>
            </td>
            <td style="width:50%">
                <h1 style="font-weight: normal; text-align:center;font-size:18px">
                    Koperasi Mitra Dhuafa (KOMIDA) <br/>
        Detail Rekening Simpanan Hari Raya <br/>
        CABANG <?=strtoupper($d['nama_cabang'])?> <br/>
        From : <?=$tgl_awal?>  To : <?=$tgl_to?>
        
                </h1>
            </td>
            <td width="100">
                &nbsp;
            </td>
        </tr>
        <tr>
            <td colspan="3" >
                <div id='daftar'>
                    
                    <table border="1" style="width:100%;border:solid 2px black" >
                        <thead style="text-align:center;margin:2px;font-weight: bolder;font-size:10px">
                            <tr>
                                <td rowspan="2" style="padding: 4px;" >No</td>
                                <td rowspan="2" style="padding: 4px;">Center</td>
                                <td rowspan="2" style="padding: 4px;">Group</td>
                                <td rowspan="2" style="padding: 4px;">ClientID</td>
                                <td rowspan="2" style="padding: 4px;">Name</td>
                                <td rowspan="2" style="padding: 4px;">Account No</td>
                                <td rowspan="2" style="padding: 4px;">Open Date</td>
                                <td rowspan="2" style="padding: 4px;">Deposit <br/>Standar</td>
                                <td rowspan="2" style="padding: 4px;">Field Officer</td>
                                <td colspan="3" >Perubahan Paket (Rp.)</td>
                                <td rowspan="2" style="padding-right:10px;padding-left:10px" >Tanggal <br/> Penutupan</td>
                            </tr>
                            <tr>
                                <td colspan="1">1</td>
                                <td colspan="1">2</td>
                                <td colspan="1">3</td>
                            </tr>
                        </thead>
                    <tbody>
                    <?php
                        $file = "../file/exx/".$_SESSION['nama_file_sihara'];
                        $path = $file;
                        $xml=simplexml_load_file($path) or die("Error: Cannot create object");
                        // $xml = new SimpleXMLElement($path,0,true);
                        $title = $xml->Tablix2['Textbox48'];
                    $xml = ($xml->Tablix2->Details1_Collection);
                    $pecah = explode(" : ",$title);
                    
                    $tgl_awal = trim(explode(" ",$pecah[1])[0]);
                    $tgl_to = trim($pecah[2]);
                        //    echo $tgl_to;
                        
                        $_SESSION['tgl_awal']=$tgl_awal;
                        $_SESSION['tgl_to']=$tgl_to;
                    foreach($xml->Details1 as $siraya){
                        $tgl_open = explode("T",$siraya['ApplyDate1'])[0];
                        
                    
                    ?>
                    <tr style="font-size:10px;height:0.5cm">
                            <td><?=$no++?></td>
                            <td style="text-align: center;"><?=sprintf("%03d",$siraya['CenterID'])?></td>
                            <td style="text-align: center;"><?=sprintf("%03d",$siraya['GroupID'])?></td>
                            <td style="padding-right:10px"><?=$siraya['ClientID1']?></td>
                            <td style="padding-left:10px"><?=$siraya['ClientName1']?></td>
                            <td style="white-space: nowrap;padding-right:10px"><?=$siraya['AccountNo1']?></td>
                            <td style="padding-left:10px;padding-right:10px"><?=$tgl_open?></td>
                            <td style="text-align: right;padding-right:10px"><?=angka(int_xml($siraya['SpecificDepositAmount1']))?></td>
                            <td><?=$siraya['OfficerName']?></td>
                            <td style="padding-left:10px;padding-right:10px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                            <td style="padding-left:10px;padding-right:10px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                            <td style="padding-left:10px;padding-right:10px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                            <td style="padding-left:10px;padding-right:10px">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                        </tr>
                        <?php 
                    }
                        ?>
                    </tbody>
                    
                </table>
            </div>
        </td>
    </tr>
</table>
</div>

    </div>
</body>
<style>
    /* table { page-break-inside:auto }
    tr    { page-break-inside:avoid; page-break-after:auto }
    thead { display:table-header-group }
    tfoot { display:table-footer-group } */
</style>
</html>
