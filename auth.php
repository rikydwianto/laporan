<?php
require_once "config/seting.php";
require_once "config/koneksi.php";
require_once("proses/fungsi.php");
require_once("model/model.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta http-equiv="Referrer-Policy" content="no-referrer, strict-origin-when-cross-origin">

    <meta name="author" content="">

    <title>KOPERASI MITRA DHUAFA</title>
    <link rel="icon" type="image/png" sizes="16x16" href="<?= $url ?>assets/logo-motif.png">
    <!-- Bootstrap Core CSS -->
    <link href="<?= $url ?>assets/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="<?= $url ?>assets/css/metisMenu.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="<?= $url ?>assets/css/startmin.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="<?= $url ?>assets/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
</head>

<body>

    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="login-panel panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Silahkan Login !</h3>
                        
                    </div>
                    <div class="panel-body">
                        <form role="form" method="post">
                            <?php
                            $text = "";
                            if (isset($_POST['login'])) {
                                $user = aman($con, $_POST['username']);
                                $pass = aman($con, md5($_POST['password']));
                                $q = mysqli_query($con, "select * from karyawan where nik_karyawan='$user'  ");
                                if (mysqli_num_rows($q)) {
                                    $cek = mysqli_fetch_array($q);
                                    if ($cek['status_karyawan'] == 'aktif') {
                                        if ($cek['password'] == $pass) {
                                            $_SESSION['id'] = $cek['id_karyawan'];
                                            $_SESSION['nama_karyawan'] = $cek['nama_karyawan'];
                                            $_SESSION['id_cabang'] = $cek['id_cabang'];
                                            $_SESSION['cabang'] = $cek['id_cabang'];
                                            $_SESSION['su'] = $cek['super_user'];
                                            $_SESSION['informasi_login'] = 'ya';
                                            // var_dump($_SESSION);
                                            pesan("BERHASIL LOGIN", 'success');
                                            $menu_asal = $_GET['url'];
                                            $menu_asal1 = explode("=", $menu_asal)[1];
                                            // echo $menu_asal;

                                            $d = detail_karyawan($con, $cek['id_karyawan']);
                                            pindah("$url");
                                            $text = "login @user  : $user  $cek[nama_karyawan] cabang : $d[nama_cabang]";
                                        } else {
                                            $text = "$user percobaan login password salah";
                                            pesan("Kombinasi USER/PASSWORD SALAH", 'danger');
                                        }
                                    } else {
                                        $text = "@user $user tidak aktif mencoba login ";
                                        pesan("STATUS ANDA DINONAKTIKAN, SILAHKAN HUBUNGI ATASAN ANDA", 'danger');
                                    }
                                } else {
                                    pesan("Kombinasi USER/PASSWORD SALAH 2", 'danger');
                                    $text = "Percobaan login @user $user tidak ditemukan";
                                }
                                $url = "https://api.telegram.org/$token/sendMessage?parse_mode=html&chat_id=1185334687&text=$text&reply_message_id=214&force_reply=true";
                                file_get_contents($url);
                            }

                            ?>
                            <fieldset>
                                <div class="form-group">
                                    <input type="text" name="username" class="form-control"
                                        placeholder="contoh 3729/2017" autofocus="">
                                </div>
                                <div class="form-group">
                                    <input type="password" class="form-control" required name="password">
                                </div>
                                <div class="checkbox">
                                    <label>
                                    </label>
                                </div>
                                <!-- Change this to a button or input when using this as a form -->
                                <button type=submit name='login' class='btn btn-lg btn-success btn-block'>Login</button>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="<?php echo $url ?>assets/js/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="<?php echo $url ?>assets/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="<?php echo $url ?>assets/js/metisMenu.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="<?php echo $url ?>assets/js/startmin.js"></script>

</body>

</html>