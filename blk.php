<?php
require_once "config/seting.php";
require_once "config/koneksi.php";
require_once("proses/fungsi.php");
require_once("model/model.php");
require("vendor/PHPExcel/Classes/PHPExcel.php");
$id_karyawan = $_SESSION['id'];
// $url="http://192.168.100.6/laporan/";
$nama_karyawan = $_SESSION['nama_karyawan'];
$jabatan = $_SESSION['jabatan'];
$cabang = $_SESSION['cabang'];
$id_cabang = $_SESSION['cabang'];
$su = $_SESSION['su'];
$d = detail_karyawan($con, $id_karyawan);
$nama_jabatan = $d['singkatan_jabatan'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="robots" content="noindex">
    <meta name="robots" content="nofollow">
    <meta name="googlebot" content="noindex">
    <title>LAPORAN | BLK</title>
    <link rel="icon" type="image/png" sizes="16x16" href="<?= $url ?>assets/logo.png">
    <!-- Bootstrap Core CSS -->
    <link href="<?= $url ?>assets/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="<?= $url ?>assets/css/metisMenu.min.css" rel="stylesheet">

    <!-- Timeline CSS -->
    <link href="<?= $url ?>assets/css/timeline.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="<?= $url ?>assets/css/startmin.css" rel="stylesheet">

    <!-- Morris Charts CSS -->
    <link href="<?= $url ?>assets/css/morris.css" rel="stylesheet">
    <link href="<?= $url ?>assets/style.css" rel="stylesheet">
    <style>
        table.fixedHeader-floating{background-color:grey}table.fixedHeader-floating.no-footer{border-bottom-width:0}table.fixedHeader-locked{position:absolute !important;background-color:white}@media print{table.fixedHeader-floating{display:none}}
    </style>

    <!-- Custom Fonts -->
    <link href="<?= $url ?>assets/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.23/datatables.min.css" />
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script src="<?php echo $url ?>assets/js/jquery.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
</head>

<body>

    <div id="wrapper">

        <?php

        if (!isset($_SESSION['id'])) {
            pindah("auth.php?url");
        } else {
            include("proses/blk.php");
            
        }
        ?>

        


        </div>
</body>
<!-- jQuery -->
<script src="<?= $url ?>assets/js/jquery.min.js"></script>

<!-- Bootstrap Core JavaScript -->
<script src="<?= $url ?>assets/js/bootstrap.min.js"></script>

<!-- Metis Menu Plugin JavaScript -->
<script src="<?= $url ?>assets/js/metisMenu.min.js"></script>

<!-- Custom Theme JavaScript -->
<script src="<?= $url ?>assets/js/startmin.js"></script>
<script src="<?= $url ?>assets/js/popper.min.js"></script>
<script src="<?= $url ?>assets/js/morris.min.js"></script>
<script src="<?= $url ?>assets/js/morris.data.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.23/datatables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/fixedheader/3.2.0/js/dataTables.fixedHeader.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/fixedcolumns/4.0.1/js/dataTables.fixedColumns.min.js"></script>
<script type="text/javascript">
    var url_link = "<?= $url ?>";
    var idcab = "<?php echo $cabang ?>";



  

$(document).ready(function() {
    $('#data_blk').DataTable( {
        fixedHeader: true,
    } );
} );
    
    
</script>
<script src="<?= $url ?>assets/js/script_wilayah.js"></script>
<script src="<?= $url ?>assets/js/grafik.js"></script>
<script src="<?= $url ?>assets/js/script.js"></script>

</html>