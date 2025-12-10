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
    <title>Akses Ditolak - Cabang <?= $d['nama_cabang'] ?></title>
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .main-card {
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            border: none;
            overflow: hidden;
            animation: fadeInUp 0.6s ease-out;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .card-header {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%) !important;
            padding: 25px;
            border: none;
        }
        
        .card-header h4 {
            margin: 0;
            font-weight: 600;
            font-size: 24px;
        }
        
        .lock-icon {
            animation: shake 2s infinite;
            filter: drop-shadow(0 4px 8px rgba(0,0,0,0.2));
        }
        
        @keyframes shake {
            0%, 100% { transform: rotate(0deg); }
            10%, 30%, 50%, 70%, 90% { transform: rotate(-5deg); }
            20%, 40%, 60%, 80% { transform: rotate(5deg); }
        }
        
        .alert-custom {
            background: linear-gradient(135deg, #ffeaa7 0%, #fdcb6e 100%);
            border: none;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(253, 203, 110, 0.3);
            padding: 25px;
            margin: 25px 0;
        }
        
        .alert-custom h5 {
            color: #d63031;
            font-weight: 700;
            font-size: 20px;
        }
        
        .btn-whatsapp {
            background: linear-gradient(135deg, #25D366 0%, #128C7E 100%);
            border: none;
            padding: 15px 35px;
            font-size: 18px;
            font-weight: 600;
            border-radius: 50px;
            transition: all 0.3s ease;
            box-shadow: 0 8px 20px rgba(37, 211, 102, 0.4);
            color: white;
        }
        
        .btn-whatsapp:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 30px rgba(37, 211, 102, 0.6);
            color: white;
        }
        
        .btn-whatsapp i {
            margin-right: 10px;
            animation: pulse 1.5s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }
        
        .info-text {
            font-size: 16px;
            line-height: 1.8;
            color: #2d3436;
        }
        
        .card-footer {
            background-color: #f8f9fa;
            padding: 20px;
            border-top: 2px solid #e9ecef;
        }
        
        .card-footer a {
            color: #667eea;
            font-weight: 600;
            text-decoration: none;
            margin: 0 15px;
            transition: all 0.3s ease;
        }
        
        .card-footer a:hover {
            color: #764ba2;
            transform: translateX(5px);
        }
        
        .thank-you {
            color: #6c5ce7;
            font-weight: 600;
            margin-top: 20px;
            font-size: 22px;
        }
    </style>
</head>


<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-9">
                <div class="card main-card">
                    <div class="card-header text-white text-center">
                        <i class="fas fa-lock lock-icon" style="font-size: 35px; margin-right: 15px;"></i>
                        <h4 class="d-inline">Cabang <?= $d['nama_cabang'] ?> Sedang Di-Lock</h4>
                    </div>
                    <div class="card-body text-center p-5">
                        <div class="mb-4">
                            <i class="fas fa-lock lock-icon" style="font-size: 120px; color: #dfe6e9;"></i>
                        </div>
                        
                        <p class="info-text mb-4">
                            <strong>Mohon Maaf,</strong><br>
                            Akses untuk cabang <strong><?= $d['nama_cabang'] ?></strong> sementara dinonaktifkan.<br>
                            Silahkan hubungi administrator untuk mengaktifkan kembali.
                        </p>
                        
                        <?php 
                        $ttesx = urlencode("Hallo Admin, saya dari cabang " . $d['nama_cabang'] . ".\n\nMohon informasi terkait pendataan ulang dan migrasi sistem.\n\nTerimakasih");
                        ?>
                        
                        <div class="alert-custom">
                            <h5>
                                <i class="fas fa-bell" style="margin-right: 10px;"></i>
                                Pemberitahuan Penting
                            </h5>
                            <hr style="border-color: rgba(214, 48, 49, 0.3);">
                            <p class="info-text mb-3">
                                <i class="fas fa-info-circle" style="color: #d63031;"></i>
                                <strong>Akan ada pendataan ulang dan migrasi sistem</strong> dalam beberapa hari ke depan.
                            </p>
                            <p class="info-text mb-4">
                                Untuk informasi lebih lanjut dan aktivasi akses, silahkan hubungi admin:
                            </p>
                            <a href="https://wa.me/6281214657370?text=<?= $ttesx ?>" 
                               class="btn btn-whatsapp">
                                <i class="fab fa-whatsapp"></i> 
                                Hubungi Admin WhatsApp
                            </a>
                            <div class="mt-3" style="color: #128C7E; font-weight: 600;">
                                <i class="fas fa-phone-alt"></i> 0812-1465-7370
                            </div>
                        </div>
                        
                        <h2 class="thank-you">
                            <i class="fas fa-heart" style="color: #e74c3c;"></i>
                            Terima Kasih Atas Perhatiannya
                        </h2>
                    </div>
                    <div class="card-footer text-center">
                        <div class="d-flex justify-content-center align-items-center flex-wrap">
                            <a href="https://wa.me/6281214657370?text=<?= $ttesx ?>">
                                <i class="fab fa-whatsapp"></i> Chat Admin
                            </a>
                            <span style="color: #ddd; margin: 0 10px;">|</span>
                            <a href="<?= $url . "auth.php" ?>">
                                <i class="fas fa-sign-in-alt"></i> Login Ulang
                            </a>
                        </div>
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