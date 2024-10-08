<?php
// error_reporting(0);
// error_reporting()
require_once "config/seting.php";
require_once "config/koneksi.php";
require_once("proses/fungsi.php");
require_once("model/model.php");
require("vendor/PHPExcel/Classes/PHPExcel.php");
$id_karyawan = $_SESSION['id'];
$d = detail_karyawan($con, $id_karyawan); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Akses Ditolak</title>
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
</head>


<body class="bg-light">
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-danger text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="white">
                            <path
                                d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm0-13h-2v6h2zm0 2a.75.75 0 0 0-.75.75v6.5a.75.75 0 1 0 1.5 0v-6.5a.75.75 0 0 0-.75-.75z" />
                        </svg>
                        Cabang <?= $d['nama_cabang'] ?> Di Lock
                    </div>
                    <div class="card-body text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="150" height="150"
                            fill="#ccc">
                            <path
                                d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm0-13h-2v6h2zm0 2a.75.75 0 0 0-.75.75v6.5a.75.75 0 1 0 1.5 0v-6.5a.75.75 0 0 0-.75-.75z" />
                        </svg>
                        <p>Maaf, cabang <?= $d['nama_cabang'] ?> di Lock, silahkan hubungi administrator untuk
                            mengaktifkan!.</p>
                        <?php
                        echo '<a href="https://wa.me/6281214657370?text=Hallo%20pak%2C%20saya%20..%20dari%20cabang%20...%0A%0Aterimakasih%20ya" class="btn btn-success m-3">Kirim pesan ke pembuat langsung</a>';
                        echo "<h1>Terima kasih semua</h1>";

                        ?>
                    </div>
                    <div class="card-footer text-muted">
                        <a
                            href="https://wa.me/6281214657370?text=Hallo%20pak%2C%20saya%20..%20dari%20cabang%20...%0A%0Aterimakasih%20ya">hubungi
                            Nomor ya untuk aktifin kembali</a>
                        <a href="<?= $url . "auth.php" ?>">Login Ulang</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and Popper.js -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>

</html>