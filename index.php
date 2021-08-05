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

    <title>KOMIDA PAGADEN | LAPORAN HARIAN!</title>
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

    <!-- Custom Fonts -->
    <link href="<?=$url ?>assets/css/font-awesome.min.css" rel="stylesheet" type="text/css">
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.23/datatables.min.css"/>
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
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
                    <h1 class="page-header"><!--KOMIDA <?=strtoupper($d['nama_cabang'])?>--></h1>
                </div>
            </div>

            <!-- ... Your content goes here ... -->
			<?php

			if( !isset($_SESSION['id'])){
				pindah("auth.php");
			}
			else {
                if( $d['password'] == md5(123456)){
                    include("proses/setting.php");
                }
                else{
                    include"menu.php";
                  
                }
				
			}
			?>

 <?php
//  if(isset($_SESSION['informasi'])){
     ?>
      <!-- The Modal TAMBAH -->
        <div class="modal fade" id="modalku">
            <div class="modal-dialog">
            <div class="modal-content">
            
                <!-- Ini adalah Bagian Header Modal -->
                <div class="modal-header">
                <h4 class="modal-title">INFORMASI</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                
                <!-- Ini adalah Bagian Body Modal -->
                <div class="modal-body">
                
                <p>
                <h2 style="text-align: center;">Sistem Informasi Cabang <?=strtoupper($d['nama_cabang'])?></h2><br>
                    <ul>
                        <li> Selasa, 10 Agutus 2021 Tidak Libur tidak dipindah hari/NORMAL </li>
                        <li> <b> Selasa, 17 Agutus 2021 </b> Libur Nasional Pindah Hari </li>
                        <li>Jika ada center yang ditutup oleh aparat bisa diisi di menu -> "Center Blacklist"</li>
                        <li>Untuk melihat Capaian Anggota Masuk dan Anggota Keluar bisa dilihat di menu Cashflow</li>
                        <li>Anggota Keluar Update sampai Tanggal  <b>Kamis, 29 Juli 2021</b> </li>
                    </ul>

                </p>
                    <br><br>

                </div>
                
                <!-- Ini adalah Bagian Footer Modal -->
                <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">x</button>
                </div>
                
            </div>
            </div>
        </div>
     <?php
     
//  }
 
 ?>


        </div>
    </div>


</div>
</body>
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
<script type="text/javascript">
    var url_link = "<?=$url ?>";
    var idcab = "<?php echo $cabang ?>";
    
    <?php 
    if($_SESSION['informasi']<=2){
        echo '$("#modalku").modal(); ';
        $_SESSION['informasi']=$_SESSION['informasi']+1;
    }
    else{
        
    }
    
    ?>
</script>
<script src="<?=$url ?>assets/js/script_wilayah.js"></script>
<script src="<?=$url ?>assets/js/grafik.js"></script>
<script src="<?=$url ?>assets/js/script.js"></script>

</html>
