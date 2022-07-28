<?php
// error_reporting(0);
// error_reporting()
require_once "config/seting.php";
require_once "config/koneksi.php";
require_once("proses/fungsi.php");
require_once("model/model.php");
// require("vendor/PHPExcel/Classes/PHPExcel.php");
$id_karyawan = aman($con,base64_decode(base64_decode($_GET['idk'])));
$id_cabang = aman($con,base64_decode(base64_decode($_GET['cab'])));
$id_kuis = aman($con,base64_decode(base64_decode($_GET['kuis'])));
// echo $id_karyawan;
// $url="http://192.168.100.6/laporan/";
$su = $_SESSION['su'];
$d = detail_karyawan($con, $id_karyawan);
$jam = date("H:i:s");
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
    <link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet">
    <script src="<?php echo $url ?>assets/js/jquery.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.23/datatables.min.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<style>
    .checkmark {
        font-family: system-ui, sans-serif;
  font-size: 2rem;
  font-weight: bold;
  line-height: 1.1;
  display: grid;
  grid-template-columns: 1em auto;
  gap: 0.5em;

}
</style>
</head>

<body>

    <div id="wrapper">
            <div class="container">
                <div class="col-lg-12">
                    <?php
                    //PROSES JAAWAB 
                    if(isset($_POST['jawab'])){
                       @ $id_jawab = $_POST['id_jawab'];
                       @ $id_soal = $_POST['idsoal'];
                       @ $pilihan = $_POST['pilihan'];
                        if(!empty($id_jawab) && !empty($id_soal) && !empty($pilihan)){

                        
                         $benar = mysqli_query($con,"SELECT pilihan_benar as benar from kuis_soal where id_kuis='$id_kuis' and id='$id_soal'");
                        echo mysqli_error($con);
                         $benar = mysqli_fetch_array($benar)['benar'];
                        //  echo $benar.$pilihan;
                         if($pilihan==$benar){
                            $score = 1;
                         }
                         else{
                            $score = 0;
                         }
                        //  exit;
                         $insert = mysqli_query($con,"INSERT INTO kuis_jawab_soal(id_jawab,id_soal,id_kuis,jawaban,jawaban_benar,score)
                                                                       values ('$id_jawab','$id_soal','$id_kuis','$pilihan','$benar','$score')
                                ");
                                echo mysqli_error($con);
                        
                        $hitung_soal = mysqli_query($con,"SELECT * from kuis_soal where id_kuis='$id_kuis' and id not in(select id_soal from kuis_jawab_soal where id_jawab='$id_jawab') order by rand() limit 0,1");
   
                        if(mysqli_num_rows($hitung_soal)==0){
                           $beres = date("H:i:s");
                           $update = mysqli_query($con,"UPDATE kuis_jawab set status='selesai', waktu_selesai='$beres', selisih_waktu=TIMESTAMPDIFF(MINUTE,waktu_mulai,'$beres') where id_jawab='$id_jawab'");
                           $update_benar = mysqli_query($con,"SELECT COUNT(*) AS benar FROM kuis_jawab_soal WHERE jawaban=jawaban_benar and id_jawab='$id_jawab'");
                            $update_benar = mysqli_fetch_array($update_benar)['benar'];
                           $update1 = mysqli_query($con,"UPDATE kuis_jawab set status='selesai', selisih_waktu=TIMESTAMPDIFF(MINUTE,waktu_mulai,waktu_selesai), score='$update_benar' where id_jawab='$id_jawab'");

                        }
                        }

                    }
                    ?>
                    <form method="post">

                    <?php 
                    $qkuis = mysqli_query($con,"SELECT * from kuis where id_kuis='$id_kuis' and status='aktif'");
                    $kuis = mysqli_fetch_array($qkuis);
                    if(mysqli_num_rows($qkuis)==0){
                        echo "<h1>KUIS TIDAK AKTIF ATAU TIDAK DITEMUKAN!</h1>";
                        exit;
                    }
                    $total_soal = mysqli_query($con,"select count(*) as total from kuis_soal where id_kuis='$id_kuis'");
                    $total_soal = mysqli_fetch_array($total_soal)['total'];
                    
                   

                    $id_karyawan =base64_encode(base64_encode($id_karyawan));
                    $id_cabang =base64_encode(base64_encode($id_cabang));
                    $id_kuis =base64_encode(base64_encode($kuis['id_kuis']));
                    $link_kuis=$url."isi_kuis.php?idk=$id_karyawan&cab=$id_cabang&kuis=$id_kuis";


                    $id_karyawan = aman($con,base64_decode(base64_decode($_GET['idk'])));
                    $id_cabang = aman($con,base64_decode(base64_decode($_GET['cab'])));
                    $id_kuis = aman($con,base64_decode(base64_decode($_GET['kuis'])));
                    ?>
                    <h2>KUIS : <?=$kuis['nama_kuis']?></h2>
                    <!-- <h2>JUMLAH SOAL : <?=$total_soal?></h2>-->
                    
                    <?php 
                    if(isset($_GET['mulai'])){
                        $cek_jawab = mysqli_query($con,"SELECT * FROM kuis_jawab where id_cabang='$id_cabang' and id_karyawan='$id_karyawan' and id_kuis='$id_kuis'");
                        if(mysqli_num_rows($cek_jawab)>0){
                            $id_jawab = mysqli_fetch_array($cek_jawab)['id_jawab'];
                        }
                        else{
                            mysqli_query($con,"INSERT INTO kuis_jawab(id_kuis,nama_karyawan,id_karyawan,id_cabang,waktu_mulai,tgl_jawab,status)
                            values('$id_kuis','$d[nama_karyawan]','$id_karyawan','$id_cabang','$jam',curdate(),'sedang mengisi')
                            ");
                            $id_jawab=mysqli_insert_id($con);
                        }
                        // echo $id_jawab;
                        $soal = mysqli_query($con,"SELECT * from kuis_soal where id_kuis='$id_kuis' and id not in(select id_soal from kuis_jawab_soal where id_jawab='$id_jawab') order by rand() limit 0,1");
                        echo mysqli_error($con);
                        $pilihan = [0=>'a',1=>'b',2=>'c',3=>'d'];
                        function acak($min, $max, $quantity) {
                            $numbers = range($min, $max);
                            shuffle($numbers);
                            return array_slice($numbers, 0, $quantity);
                        }
                        //
                        $soal_dijawab =   mysqli_query($con,"select count(*) as total from kuis_jawab_soal where id_jawab='$id_jawab'");
                        $soal_dijawab = mysqli_fetch_array($soal_dijawab)['total'];
                        echo mysqli_error($con);
                        ?>
                        <h3>TELAH DIJAWAB : <?=$soal_dijawab?>/<?=$total_soal?></h3> 
                        <?php

                        
                        // mysqli_close($con);
                        $pilihan = ['a','b','c','d'];
                        while($soal_ = mysqli_fetch_array($soal)){
                            $acak =  acak(0,3,4);
                            $a[]= 0;//$acak[0];
                            $a[]= 1;//$acak[1];
                            $a[]= 2;//$acak[2];
                            $a[]= 3//$acak[3];
                            ?>
                            <h2><?=$soal_['soal']?></h2>
                            <input type="hidden" name="idsoal" value='<?=$soal_['id']?>' id="">
                            <input type="hidden" name="id_jawab" value='<?=$id_jawab?>' id="">
                            <?php
                           foreach($a as $urut){
                            $pilih = $pilihan[$urut];
                            ?>
                            <div class="form-check" style="font-size: 25px ;" class='checkmark'>
                                <input class="form-check-input"  required type="radio" style="width: 40px;height: 40px;" value="<?=$pilih?>" name="pilihan" id="pilihan<?=$pilih?>">
                                <label class="form-check-label" for="pilihan<?=$pilih?>">
                                    <?=strtoupper($pilih)?>. <?=$soal_['pilihan_'.$pilih]?>
                                </label>
                                </div>
                            <?php
                           }
                            // $benar = $soal_['benar'];
                        }

                        if(mysqli_num_rows($soal)>0){
                            ?>
                                <input type="submit" value='JAWAB' class='btn  btn-primary' name='jawab'>
                                <?php
                        }
                        else{
                            ?>
                            <!-- Soal terkahir -->
                            <input type="submit" value='LIHAT SCORE' class='btn btn-primary' name='jawab'>
                            <a href="<?=$url?>" class="btn btn-danger">HALAMAN AWAL</a>
                            <?php
                        }
                      
                        ?>
                        <?php

                        





                    }
                    else{
                        ?>
                    <h3>
                        <a href="<?=$link_kuis?>&mulai" class="btn btn-danger">MULAI?</a>
                       
                    </h3>
                        <?php
                    }


                    if((isset($_POST['jawab']) && $_POST['jawab']=='LIHAT SCORE') || isset($_GET['jawab']) && $_GET['jawab']=='LIHAT SCORE'){
                        // echo $id_jawab;
                        $jawab=mysqli_fetch_array(mysqli_query($con,"SELECT * FROM kuis_jawab where id_jawab='$id_jawab'"));

                        // $time_diff = 
                        $selisih =  $jawab['selisih_waktu'];
                        $benar = mysqli_query($con,"SELECT COUNT(*) AS benar FROM kuis_jawab_soal WHERE jawaban=jawaban_benar and id_jawab='$id_jawab'");
                        $benar = mysqli_fetch_array($benar)['benar'];
                        // $benar=2;
                        echo mysqli_error($con);
                        ?>

                        <div class="page-wrapper">
                            <div class="container">
                                <hr>
                                <table class='table table-bordered'>
                                    <tr>
                                        <td>Nama Kuis</td>
                                        <th><?=$kuis['nama_kuis']?></th>
                                        <th style="text-align: center ;">NILAI</th>
                                    </tr>
                                    <tr>
                                    <td>Jumlah Soal</td>
                                        <td><?=$total_soal?></td>
                                        <td rowspan="3" style="text-align: center; font-weight: bold ;vertical-align: middle;font-size: xx-large;"><?=round(($benar/$total_soal)*100,1)?></td>                              
                                    </tr>
                                    <tr>
                                        <td>BENAR</td>
                                        <td><?=$benar?> | Pengerjaan : <?=$selisih?> menit</td>
                                    </tr>
                                </table>
                            <h1 class="display-1">SOAL & JAWABAN</h1>
                            <?php 
                            $qsoal = mysqli_query($con,"SELECT * from kuis_soal where id_kuis='$id_kuis'");
                            $pilihan = ['a','b','c','d'];
                            while($soal = mysqli_fetch_array($qsoal)){
                                ?>
                                <h4><?=$no++?>. <?=$soal['soal']?></h4>
                                <?php
                                $jb = $soal['pilihan_benar'];
                                foreach($pilihan as $pil){
                                    if($pil==$jb){
                                        $bold="font-weight: bold ;font-size: large;";
                                        // $ket = "Benar";
                                    }
                                    else{
                                         $bold = "font-weight: lighter ;";
                                    }
                                    ?>
                                    <p style="margin-left:20px;<?=$bold?>"><?=strtoupper($pil)?>. <?=$soal['pilihan_'.$pil]?></p>
                                    <?php
                                } 
                                
                                $jawab = mysqli_fetch_array(mysqli_query($con,"select * from kuis_jawab_soal where id_soal='$soal[id]'"))['jawaban'];
                                // $jawab = 'c';
                                if($jawab==$jb){
                                    $ket = "benar";
                                }
                                else $ket="salah";
                                ?>

                                Anda Pilih : <b><?=strtoupper($jawab)?>(<?=$ket?>)</b>
                                <hr>
                                <?php
                            }
                            ?>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                    </form>

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


</script>
<script src="<?= $url ?>assets/js/script_wilayah.js"></script>
<script src="<?= $url ?>assets/js/grafik.js"></script>
<script src="<?= $url ?>assets/js/script.js"></script>

</html>
<?php mysqli_close($con)?>