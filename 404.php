<?php
error_reporting(0);
require_once "config/seting.php";
require_once "config/koneksi.php";
require_once("proses/fungsi.php");
require_once("model/model.php");
require("vendor/PHPExcel/Classes/PHPExcel.php");

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>MITRA DHUAFA | <?= strtoupper($d['nama_cabang']) ?></title>
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


        <!-- Navigation -->
        <div class="row">
		
		<div class="jumbotron text-center">

			<h1 class="page-header devider">404</h1><hr/>
			<h2 class="page-header">Halaman Tidak ditemukan</h2>
			<p>Silahkan klik <a href="<?=$url?>">disini</a> untuk ke halaman utama</p>
			<img scr="<?=$url?>assets/403.png" class='img' >

		</div>	

        <!-- Page Content -->
        
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
<script type="text/javascript">
    var url_link = "<?= $url ?>";
    var idcab = "<?php echo $cabang ?>";

    <?php
    if ($_SESSION['informasi'] <= 2) {
        echo '$("#modalku").modal(); ';
        $_SESSION['informasi'] = $_SESSION['informasi'] + 1;
    } else {
    }



    ?>


    $(document).ready(function() {
        $("#tutup_pesan").on('click', function() {
            $("#hadis").modal();
        });
        $("#tutup_pesan1").on('click', function() {
            $("#hadis").modal();
        });
        $('#modalku1').on('show.bs.modal', function(e) {
            var rowid = $(e.relatedTarget).data('id');
            $.get(url + "api/detail_monitoring.php?id=" + rowid, function(data, status) {

                setTimeout(function() {
                    $("#isi_detail").html("Mohon Tunggu");
                }, 0);
                setTimeout(function() {
                    $("#isi_detail").html(data);
                }, 800);
            });
        });
    });
    function salin(text) {
            var sampleTextarea = document.createElement("textarea");
            document.body.appendChild(sampleTextarea);
            sampleTextarea.value = text; //save main text in it
            sampleTextarea.select(); //select textarea contenrs
            document.execCommand("copy");
            document.body.removeChild(sampleTextarea);
        }
</script>
<script src="<?= $url ?>assets/js/script_wilayah.js"></script>
<script src="<?= $url ?>assets/js/grafik.js"></script>
<script src="<?= $url ?>assets/js/script.js"></script>

</html>