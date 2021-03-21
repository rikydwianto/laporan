<?php 
require_once "config/seting.php";
require_once "config/koneksi.php";
require_once("proses/fungsi.php");
require_once("model/model.php");
$id_karyawan = $_SESSION['id'];
$nama_karyawan = $_SESSION['nama_karyawan'];
$jabatan= $_SESSION['jabatan'];
$cabang= $_SESSION['cabang'];
$id_cabang= $_SESSION['cabang'];
$su= $_SESSION['su'];
$d = detail_karyawan($con,$id_karyawan);
$nama_jabatan=$d['singkatan_jabatan'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>KOMIDA PAGADEN | Lokasi</title>
	<link rel="icon" type="image/png" sizes="16x16" href="<?=$url?>assets/logo.png">
    <!-- Bootstrap Core CSS -->
    <link href="<?=$url ?>assets/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="<?=$url ?>assets/css/metisMenu.min.css" rel="stylesheet">

    <!-- Timeline CSS -->
    <link href="<?=$url ?>assets/css/timeline.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="<?=$url ?>assets/css/startmin.css" rel="stylesheet">

    <!-- Morris Charts CSS -->
    <link href="<?=$url ?>assets/css/morris.css" rel="stylesheet">
    <link href="<?=$url ?>assets/style.css" rel="stylesheet">
<link href='https://api.mapbox.com/mapbox-gl-js/v2.1.1/mapbox-gl.css' rel='stylesheet' />
    <script src='https://api.mapbox.com/mapbox-gl-js/v2.1.1/mapbox-gl.js'></script>
    <!-- Custom Fonts -->
    <link href="<?=$url ?>assets/css/font-awesome.min.css" rel="stylesheet" type="text/css">
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.23/datatables.min.css"/>
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>

<div id="wrapper">

    <!-- Navigation -->
    <?php //include"view/navbar.php"; ?>

    <!-- Page Content -->
	<div id="page-wrapper">
        <div class="container-fluid">
		<div class="card">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">KOMIDA <?=strtoupper($d['nama_cabang'])?></h1>
                </div>
            </div>

            <!-- ... Your content goes here ... -->
			<?php

			if( !isset($_SESSION['id'])){
				pindah("auth.php");
			}
			else {
				?>


                <div class="col-md-12">
                    <h3 class="page-header">LOKASI</h3>


                    <div id='map' style='width: 100%; height: 500px;'></div>
                    <script> mapboxgl.accessToken = 'pk.eyJ1IjoicmlreWR3aWFudG8iLCJhIjoiY2ttaXR2M3MwMGtsODJxbXVpaHZraWI0MiJ9.M-fgWx9RsxU5lFoWiDRRNA';
                        var map = new mapboxgl.Map({
                        container: 'map', // container ID
                        style: 'mapbox://styles/mapbox/streets-v11', // style URL
                        center: [ 107.789884 , -6.510135], // starting position [lng, lat]
                        zoom: 16 // starting zoom
                        });
                        map.addControl(
                        new mapboxgl.GeolocateControl({
                        positionOptions: {
                        enableHighAccuracy: true
                        },
                        trackUserLocation: true
                        })
                        );
            </script>

                </div>
                <?php
			}
			?>
        </div>
    </div>


</div>
<!-- jQuery -->
<script src="<?=$url ?>assets/js/jquery.min.js"></script>

<!-- Bootstrap Core JavaScript -->
<script src="<?=$url ?>assets/js/bootstrap.min.js"></script>

<!-- Metis Menu Plugin JavaScript -->
<script src="<?=$url ?>assets/js/metisMenu.min.js"></script>

<!-- Custom Theme JavaScript -->
<script src="<?=$url ?>assets/js/startmin.js"></script>
<script src="<?=$url ?>assets/js/popper.min.js"></script>
<script src="<?=$url ?>assets/js/morris.min.js"></script>
<script src="<?=$url ?>assets/js/morris.data.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.23/datatables.min.js"></script>


<script src="<?=$url ?>assets/js/script.js"></script>
</body>
</html>