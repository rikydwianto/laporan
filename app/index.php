<?php
// error_reporting(0);
// error_reporting()
require_once "../config/seting.php";
require_once "../config/koneksi.php";
require_once("../proses/fungsi.php");
require_once("../model/model.php");
require_once '../vendor/autoload.php';
if (!isset($_SESSION)) {
  exit;
}
$id_karyawan = $_SESSION['id'];
// $url="http://192.168.100.6/laporan/";
$nama_karyawan = $_SESSION['nama_karyawan'];
$jabatan = $_SESSION['jabatan'];
$cabang = $_SESSION['cabang'];
$id_cabang = $_SESSION['cabang'];
$d = detail_karyawan($con, $id_karyawan);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <link rel="apple-touch-icon" sizes="76x76" href="assets/img/apple-icon.png" />
    <link rel="icon" type="image/png" href="assets/img/favicon.png" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>APP MOBILE</title>
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no"
        name="viewport" />
    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" rel="stylesheet" />
    <!-- CSS Files -->
    <!-- <link href="assets/css/bootstrap.min.css" rel="stylesheet" /> -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <link href="assets/css/paper-dashboard.css?v=2.0.1" rel="stylesheet" />
    <!-- CSS Just for demo purpose, don't include it in your project -->
    <link href="assets/demo/demo.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.3/css/dataTables.dataTables.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.3/css/dataTables.bootstrap.css" />
    <style>
    .navbar-sticky {
        position: -webkit-sticky;
        /* Untuk Safari */
        position: fixed;
        top: 0;
        z-index: 1000;
        /* Pastikan navbar di atas konten lain */
    }

    .floating-btn {
        position: fixed;
        /* Membuat tombol tetap berada di tempat yang ditentukan */
        bottom: 20px;
        /* Jarak dari bawah layar */
        right: 20px;
        /* Jarak dari kanan layar */
        z-index: 1000;
        /* Menjaga tombol tetap di atas konten lain */
        border-radius: 50%;
        /* Membuat tombol bulat */
        width: 60px;
        /* Lebar tombol */
        height: 60px;
        /* Tinggi tombol */
        display: flex;
        /* Menyusun konten di dalam tombol */
        justify-content: center;
        /* Memusatkan konten secara horizontal */
        align-items: center;
        /* Memusatkan konten secara vertikal */
        background-color: #007bff;
        /* Warna latar belakang tombol */
        color: #fff;
        /* Warna teks */
        border: none;
        /* Menghilangkan border default */
        font-size: 24px;
        /* Ukuran font */
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        /* Bayangan tombol */
        cursor: pointer;
        /* Mengubah kursor saat hover */
        transition: background-color 0.3s;
        /* Efek transisi untuk perubahan warna */
    }

    .floating-btn:hover {
        background-color: #0056b3;
        /* Warna latar belakang saat hover */
    }

    .offcanvas-body {
        padding: 1rem;
    }

    .menu-item {
        display: flex;
        align-items: center;
        padding: 1rem;
        margin-bottom: 1rem;
        border: 1px solid #ddd;
        /* border-radius: 8px; */
        /* Rounded corners for boxes */
        background-color: #fff;
        /* Light background color */
        cursor: pointer;
        transition: background-color 0.2s, box-shadow 0.2s;
    }

    .menu-item:hover {
        background-color: #e9ecef;
        /* Slightly darker background on hover */
        /* box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); */
        /* Add shadow on hover */
    }

    .menu-item .icon {
        font-size: 1.5rem;
        margin-right: 1rem;
        color: #007bff;
    }

    .menu-item .text {
        font-size: 1rem;
    }

    .offcanvas-header {
        border-bottom: 1px solid #ddd;
    }

    .offcanvas-bottom.custom-height {
        height: 50vh;
        /* Tinggi 80% dari viewport height */
    }
    </style>
</head>

<body>
    <div id="progress-bar-container">
        <div id="progress-bar"></div>
    </div>
    <button class="floating-btn" data-bs-toggle="offcanvas" data-bs-target="#offcanvasWithBothOptions"
        aria-controls="offcanvasWithBothOptions">
        <i class="nc-icon nc-tile-56 text-white"></i>
    </button>


    <div class="wrapper">


        <div class="main-panel">
            <!-- Navbar -->
            <nav class="navbar navbar-expand-lg navbar-absolute fixed-top navbar-transparent navbar-sticky">
                <div class="container-fluid">
                    <div class="navbar-wrapper">

                        <a class="navbar-brand" href="index.php"><?= $d['nama_cabang'] ?></a>
                    </div>
                </div>
            </nav>
            <br>
            <!-- End Navbar -->
            <div class="content">
                <?php
        if (isset($_GET['menu'])) {
          $menu = $_GET['menu'];
          if ($menu == 'pinjaman') {
            include "./proses/pinjaman.php";
          } else if ($menu == 'staff') {
            include "./proses/staff.php";
          } else {
            include "layout/statistik.php";
          }
        } else {
          include "layout/statistik.php";
        }
        ?>
            </div>
            <div class="offcanvas offcanvas-bottom custom-height" data-bs-scroll="true" tabindex="-1"
                id="offcanvasWithBothOptions" aria-labelledby="offcanvasWithBothOptionsLabel">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title" id="offcanvasWithBothOptionsLabel">Menu Aplikasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    <a href="index.php" style="text-decoration: none;">
                        <div class="menu-item">
                            <i class="fa  fa-home icon"></i><span class="text">Home</span>
                        </div>
                    </a>
                    <a href="index.php?menu=staff" style="text-decoration: none;">
                        <div class="menu-item">
                            <i class="nc-icon icon nc-single-02 text-warning"></i><span class="text">Monitoring Per
                                Staff</span>
                        </div>
                    </a>
                    <a href="index.php?menu=pinjaman" style="text-decoration: none;">
                        <div class="menu-item">
                            <i class="nc-icon icon nc-money-coins text-success"></i><span class="text">Semua
                                Monitoring</span>
                        </div>
                    </a>
                </div>
            </div>


            <footer class="footer footer-black footer-white">
                <div class="container-fluid">
                    <div class="row">
                        <div class="credits ml-auto">
                            <span class="copyright">
                                ©
                                <script>
                                document.write(new Date().getFullYear());
                                </script>
                                , made with <i class="fa fa-heart heart"></i> by Comdev
                            </span>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <!--   Core JS Files   -->
    <script src="assets/js/core/jquery.min.js"></script>
    <script src="assets/js/core/popper.min.js"></script>
    <script src="assets/js/core/bootstrap.min.js"></script>
    <script src="assets/js/plugins/perfect-scrollbar.jquery.min.js"></script>
    <!--  Google Maps Plugin    -->
    <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_KEY_HERE"></script>
    <!-- Chart JS -->
    <script src="assets/js/plugins/chartjs.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>

    <!--  Notifications Plugin    -->
    <script src="assets/js/plugins/bootstrap-notify.js"></script>
    <!-- Control Center for Now Ui Dashboard: parallax effects, scripts for the example pages etc -->
    <script src="assets/js/paper-dashboard.min.js?v=2.0.1" type="text/javascript"></script>
    <!-- Paper Dashboard DEMO methods, don't include it in your project! -->
    <script src="assets/demo/demo.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <script>
    const url = "<?= $url ?>";
    </script>

    <script src="assets/js/main.js?v=<?= time() ?>"></script>
    <script src="https://cdn.datatables.net/2.1.3/js/dataTables.js"></script>

</body>

</html>