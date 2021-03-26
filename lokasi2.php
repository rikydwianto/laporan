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
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet.locatecontrol@0.73.0/dist/L.Control.Locate.min.css" />


    <!-- Custom CSS -->
    <link href="<?=$url ?>assets/css/startmin.css" rel="stylesheet">
    <link href="<?=$url ?>assets/js/leaflet/leaflet.css" rel="stylesheet">

    <!-- Morris Charts CSS -->
    <link href="<?=$url ?>assets/css/morris.css" rel="stylesheet">
    <link href="<?=$url ?>assets/style.css" rel="stylesheet">
    <!-- Custom Fonts -->
    <link href="<?=$url ?>assets/css/font-awesome.min.css" rel="stylesheet" type="text/css">
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.23/datatables.min.css"/>
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
	
		<link rel="stylesheet" href="<?=$url ?>assets/js/cluster/MarkerCluster.css" />
	<link rel="stylesheet" href="<?=$url ?>assets/js/cluster/MarkerCluster.Default.css" />
	<link rel="stylesheet" href="<?=$url ?>assets/js/search/leaflet-search.min.css" />
    
	<style type="text/css">
    #info {
    display: block;
    position: relative;
    margin: 0px auto;
    width: 50%;
    padding: 10px;
    border: none;
    border-radius: 3px;
    font-size: 12px;
    text-align: center;
    color: #222;
    background: #fff;
    }
</style>
</head>
<body>

<div id="wrapper">

    <!-- Navigation -->
    <?php include"view/navbar.php"; ?>

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


                

                <div class="col-md-3">

    <div class="col-12 mb-4">
        Legenda <br>
        <img class="img" src="<?=$url ?>assets/img/icon/hijau.png" style='width:30px'> Center Hijau <br/>
        <img class="img" src="<?=$url ?>assets/img/icon/kuning.png" style='width:30px'> Center Kuning <br/>
        <img class="img" src="<?=$url ?>assets/img/icon/center.png" style='width:30px'> Center Merah<br/>
        <img class="img" src="<?=$url ?>assets/img/icon/hitam.png" style='width:30px'> Center Hitam <br/>
        <img class="img" src="<?=$url ?>assets/img/icon/anggota.png" style='width:30px'> ANGGOTA <br/>
        <img class="img" src="<?=$url ?>assets/img/icon/informasi.png" style='width:30px'> INFORMASI LAIN <br/>
        <img class="img" src="<?=$url ?>assets/img/icon/kantor.png" style='width:30px'> KANTOR CABANG <br/>
        

    </div>
                    <!-- <a href="<?=$url."input-lokasi.php"?>" class="btn btn-danger">Tambah Lokasi</a> 
                    <input type="hidden" name="coba" id="coba" class="form-control">
                    <input type="hidden" name="lat" id="lat" class="form-control">
                    <input type="hidden"  id="latitude" name="latitude" class='form-control'>
                    <input type="hidden" id="longitude" name="longitude" class='form-control'>
                    <ul class="list-group" id="link">
                      <li class="list-group-item " >MASUKAN LOKASI</li>
                      <li class="list-group-item "  ><a id="center" data-link="<?=$url.$menu ?>lokasi&pilih=center" href="<?=$url.$menu ?>lokasi&pilih=center" >CENTER</a></li>
                      <li class="list-group-item "><a id="anggota" data-link="<?=$url.$menu ?>lokasi&pilih=anggota"  href="<?=$url.$menu ?>lokasi&pilih=anggota">ANGOTA</a></li>
                      <li class="list-group-item "><a data-link="<?=$url.$menu ?>lokasi&pilih=pu" id="pu"  href="<?=$url.$menu ?>lokasi&pilih=pu">PU</a></li>
                      <li class="list-group-item "><a id='getLokasi'>GET DIRECTION</a></li>
                    </ul> -->
                </div>
<div class="col-md-9">
                    <h3 class="page-header">LOKASI</h3>


                    <div id='map' style='width: 100%; height: 500px;'></div>
                    <script type="text/javascript">
                       

                    </script>
                </div>
                <?php
			}
			?>
        </div>
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
<script src="<?=$url ?>assets/js/leaflet/leaflet.js"></script>

<script src="<?=$url ?>assets/js/popper.min.js"></script>
<script src="<?=$url ?>assets/js/morris.min.js"></script>
<!-- <script src="<?=$url ?>assets/js/morris.data.js"></script> -->
<script src="https://cdn.jsdelivr.net/npm/leaflet.locatecontrol@0.73.0/dist/L.Control.Locate.min.js" charset="utf-8"></script>

<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.23/datatables.min.js"></script>
<script src="<?=$url ?>assets/js/cluster/leaflet.markercluster-src.js"></script>
<script src="<?=$url ?>assets/js/search/leaflet-search.src.js"></script>
<script type="text/javascript">
      
    var latdb = '<?=$d['latitude'];?>';
    var lngdb = '<?=$d['longitude'];?>';

    

     if(latdb == '' || lngdb == ''){
        latdb = '-6.449471595334012';
        lngdb = '107.81619415504505';
    }
    else
    {
        
    } 

    var url_link = "<?=$url ?>";
    

</script>


<script src="<?=$url ?>assets/js/lokasi.js"></script>
</body>
</html>
