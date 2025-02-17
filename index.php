<?php
ob_start();
require_once "config/seting.php";
require_once "config/koneksi.php";
require_once("proses/fungsi.php");
require_once("model/model.php");
require_once 'vendor/autoload.php';
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
$_SESSION['kode_cabang'] = $d['kode_cabang'];
$kode_cabang = $_SESSION['kode_cabang'];

$singkatan_cabang = $d['singkatan_cabang'];
$status_cabang = $d['status_cabang'];
// pindah("maintanance.php");
if ($su != 'y') {
    if ($status_cabang == 'nonaktif') {
        pindah("lock.php");
    }
}
set_time_limit(3000000);


// echo $su;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="robots" content="noindex">
    <meta name="robots" content="nofollow">
    <meta name="googlebot" content="noindex">


    <meta name="author" content="RIKY DWIANTO">
    <meta http-equiv="Referrer-Policy" content="no-referrer, strict-origin-when-cross-origin">


    <title>LAPORAN | <?= strtoupper($d['nama_cabang']) ?></title>
    <link rel="icon" type="image/png" sizes="16x16" href="<?= $url ?>assets/logo-motif.png">
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
    <link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet">
    <script src="<?php echo $url ?>assets/js/jquery.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.23/datatables.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
</head>

<body>

    <div id="wrapper">

        <!-- Navigation -->
        <?php include "view/navbar.php"; ?>

        <!-- Page Content -->
        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="card">
                    <div class="row">
                        <div class="col-lg-12">
                            <h1 class="page-header">
                                <!--KOMIDA <?= strtoupper($d['nama_cabang']) ?>-->
                            </h1>
                        </div>
                    </div>

                    <!-- ... Your content goes here ... -->
                    <?php

                    if (!isset($_SESSION['id'])) {
                        $refer = urlencode($_SERVER['HTTP_REFERER']);
                        pindah("auth.php?url=$refer");
                    } else {
                        // if ($status_cabang != "aktif") {
                        //     if ($su != 'y') {

                        //         pindah($url . "lock.php");
                        //     }
                        // }
                        $hitung_banding = mysqli_query($con, "select count(id_detail_pinjaman) as banding from  banding_monitoring where id_cabang='$id_cabang' and status='belum'  ");
                        $hitung_banding = mysqli_fetch_array($hitung_banding);
                        $hitung_banding = $hitung_banding['banding'];
                        if ($d['password'] == md5(123456)) {
                            include("proses/setting.php");
                        } else {
                            if ($su == 'y' || $jabatan == 'BC') {
                                $qu = "";
                                if ($jabatan == 'BC') {
                                    $qu = "where id_wilayah='$d[id_wilayah]'";
                                }

                    ?>
                                <form action="" method="post">
                                    <select name="nama_cabang" id="">

                                        <?php
                                        $q = mysqli_query($con, "SELECT * FROM cabang $qu order by nama_cabang asc");
                                        while ($r = mysqli_fetch_array($q)) {
                                            if ($id_cabang == $r['id_cabang'])
                                                $sel = 'selected';
                                            else $sel = '';
                                        ?>
                                            <option value="<?= $r['id_cabang'] ?>" <?= $sel ?>><?= strtoupper($r['nama_cabang']) ?>
                                            </option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                    <input type="submit" name='submit_cabang' value='GANTI CABANG' />
                                </form>

                    <?php
                                if (isset($_POST['submit_cabang']) && $_POST['submit_cabang'] == 'GANTI CABANG') {
                                    $id_cabang = $_POST['nama_cabang'];
                                    $cab = mysqli_fetch_array(mysqli_query($con, "select * from cabang where id_cabang='$id_cabang'"));
                                    $_SESSION['cabang'] = $_POST['nama_cabang'];
                                    $_SESSION['id_cabang'] = $_POST['nama_cabang'];
                                    $_SESSION['kode_cabang'] = $cab['kode_cabang'];
                                    // echo print_r($cab);
                                    // pindah($url);
                                }
                            }
                            // echo  $_SESSION['kode_cabang']; ;
                            include "menu.php";
                        }
                    }
                    ?>

                    <?php
                    //  if(isset($_SESSION['informasi'])){
                    ?>


                    <!-- The Modal TAMBAH -->
                    <div class="modal fade" id="modalku">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">

                                <!-- Ini adalah Bagian Header Modal -->
                                <div class="modal-header">
                                    <h4 class="modal-title">INFORMASI</h4>
                                    <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
                                </div>

                                <!-- Ini adalah Bagian Body Modal -->
                                <div class="modal-body">
                                    <div class="col-md-12">
                                        <div class="col-md-6">
                                            <h2 style="text-align: center;">DATA PAR</h2>
                                            <canvas id="donat"></canvas>
                                        </div>
                                        <div class="col-md-6">
                                            <p>

                                            <h2 style="text-align: center;">Sistem Informasi Cabang
                                                <?= strtoupper($d['nama_cabang']) ?></h2><br>
                                            <ul>
                                                <?php
                                                $number = cal_days_in_month(CAL_GREGORIAN, date("m"), date("Y"));
                                                $tgl_akhir = date("Y-m-$number");
                                                $tgl_depan = date("Y-m-" . ($number - 6));

                                                ?>

                                                <!-- <li> Selasa, 10 Agutus 2021 Tidak Libur tidak dipindah hari/NORMAL </li> -->
                                                <li> <b> PENARIKAN SIMPANAN OLEH MANAJER </b> Berlaku pada
                                                    <?= format_hari_tanggal($tgl_depan) ?> s/d
                                                    <?= format_hari_tanggal($tgl_akhir) ?> </li>
                                                <li> Untuk rekap bisa dilihat di menu "PENARIKAN SIMPANAN" hanya
                                                    memasukan id anggota dan nominal penarikan </li>
                                                <li>
                                                    <?php
                                                    $qmax = mysqli_query($con, "SELECT MAX(tgl_cair) AS cair FROM pinjaman WHERE id_cabang='$id_cabang' LIMIT 0,1");
                                                    $info =  mysqli_fetch_array($qmax);
                                                    $sq = mysqli_query($con, "SELECT karyawan.nama_karyawan,COUNT(id_detail_nasabah) AS total_monitoring 
                                                    FROM pinjaman JOIN karyawan ON karyawan.`id_karyawan`=pinjaman.`id_karyawan`
                                                    WHERE pinjaman.id_cabang='$id_cabang' and pinjaman.monitoring='belum' and pinjaman.input_mtr='sudah'
                                                    GROUP BY pinjaman.id_karyawan
                                                    ORDER BY COUNT(id_detail_nasabah) DESC LIMIT 0,5");

                                                    ?>
                                                    Monitoring diupdate sampai dengan
                                                    <b><?= format_hari_tanggal($info['cair']) ?></b>

                                                </li>
                                                <li>
                                                    Monitoring Teratas
                                                    <ul>
                                                        <?php
                                                        while ($max = mysqli_fetch_array($sq)) {
                                                            echo "<li>" . $max['nama_karyawan'] . ' - ' . $max['total_monitoring'] . "</li>";
                                                        }
                                                        ?>
                                                    </ul>
                                                </li>

                                            </ul>

                                            </p>

                                        </div>

                                    </div>







                                    <!-- Ini adalah Bagian Footer Modal -->

                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger" id='tutup_pesan'
                                        data-dismiss="modal">close</button>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="hadis">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">

                                <!-- Ini adalah Bagian Header Modal -->
                                <div class="modal-header">
                                    <h4 class="modal-title">RANDOM QUOTES</h4>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>

                                <!-- Ini adalah Bagian Body Modal -->
                                <div class="modal-body">


                                    <?php
                                    $quote = mysqli_query($con, "SELECT * FROM quotes where prioritas='y' ORDER BY RAND() LIMIT 1");
                                    $quote = mysqli_fetch_array($quote);
                                    $quotes = $quote['quotes'];


                                    ?>

                                    <p>
                                    <h3 style="text-align: center;">"Random Quotes"</h3><br>
                                    </p>
                                    <p style="font-size: 25px;">
                                        "<?= $quotes ?>"
                                        <br>
                                    </p>

                                    <br><br>
                                    <h4>Sekiranya bersedia untuk mengisi survey
                                        <a href="https://docs.google.com/forms/d/e/1FAIpQLSc15fGnUlQ_zmt7UVVbSYsx2D19MGRv2ehhmgeyl_8c99eT0A/viewform"
                                            target="_blank" class="btn">KLIK DISINI</a>
                                    </h4>
                                    <a href="<?= $url . $menu ?>quotes">Tambah Quote Disini</a>

                                </div>

                                <!-- Ini adalah Bagian Footer Modal -->
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger" data-dismiss="modal">close</button>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- <a href="#" id='tutup_pesan1' class="btn">hadis</a> -->
                    <?php

                    //  }

                    ?>


                </div>
            </div>


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
    var oilCanvas = document.getElementById("oilChart");

    var oilData = {
        labels: [
            "Saudi Arabia",
            "Russia",
            "Iraq",
            "United Arab Emirates",
            "Canada"
        ],
        datasets: [{
            data: [133.3, 86.2, 52.2, 51.2, 50.2],
            backgroundColor: [
                "#FF6384",
                "#63FF84",
                "#84FF63",
                "#8463FF",
                "#6384FF"
            ]
        }]
    };

    var pieChart = new Chart(oilCanvas, {
        type: 'pie',
        data: oilData
    });





    function addCommas(nStr) {
        nStr += '';
        x = nStr.split('.');
        x1 = x[0];
        x2 = x.length > 1 ? '.' + x[1] : '';
        var rgx = /(\d+)(\d{3})/;
        while (rgx.test(x1)) {
            x1 = x1.replace(rgx, '$1' + ',' + '$2');
        }
        return x1 + x2;
    }
</script>
<script src="<?= $url ?>assets/js/script_wilayah.js"></script>
<script src="<?= $url ?>assets/js/grafik.js"></script>
<script src="<?= $url ?>assets/js/script.js"></script>

</html>
<?php mysqli_close($con) ?>