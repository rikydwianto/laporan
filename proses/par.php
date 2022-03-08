
<div class="row">
	<h3 class="page-header">INPUT ANGGOTA PAR</h3>
    <form method="post" enctype="multipart/form-data">
        <div class="col-md-4">
            <label for="formFile" class="form-label">SILAHKAN PILIH FILE</label>
            <input class="form-control" required type="file" name='file' accept=".xls,.xlsx,.csv" id="formFile">
            <input type="date" required name="tgl" class='form-control' id="">
            <input type="submit" onclick="return confirm('yakin sudah benar?/')" value="KONFIRMASI" class='btn btn-danger' name='preview'>
        </div>
    </form>
        <div class="col-md-8">
        <form action="" method="get">
            <input type="hidden" name="menu" value="par">
                <div class="col-md-4">
                    <h3>MINGGU SEBELUM NYA</h3>
                    <select name='sebelum' class='form-control' required>
                        
                        <option value="">PILIH MINGGU SEBELUM</option>
                        <?php 
                        error_reporting(0);
                        $q_tgl = mysqli_query($con,"SELECT DISTINCT tgl_input FROM deliquency where id_cabang='$id_cabang'  order by tgl_input desc");
                        while($tgl_ = mysqli_fetch_array($q_tgl)){
                            ?>
                            <option value="<?=$tgl_['tgl_input']?>" <?=($_GET['sebelum']===$tgl_['tgl_input']?"selected":"")?>><?=format_hari_tanggal($tgl_['tgl_input'])?></option>
                            <?php
                        }
                        ?>

                    </select>
                    <?=separator($_GET['tipe'])?>
                    
                </div>
                <div class="col-md-4">
                    <h3>MINGGU INI</h3>
                    <select name='minggu_ini' class='form-control' required>
                        
                        <option value="">PILIH MINGGU INI</option>
                        <?php 
                        $q_tgl = mysqli_query($con,"SELECT DISTINCT tgl_input FROM deliquency where id_cabang='$id_cabang' order by tgl_input desc");
                        while($tgl_ = mysqli_fetch_array($q_tgl)){
                            ?>
                            <option value="<?=$tgl_['tgl_input']?>" <?=($_GET['minggu_ini']===$tgl_['tgl_input']?"selected":"")?>><?=format_hari_tanggal($tgl_['tgl_input'])?></option>
                            <?php
                        }
                        ?>

                    </select>
                   
                    <input type="submit" value="BANDINGKAN" name='bandingkan' class='btn btn-md btn-danger'>
                    <input type="submit" value="REKAP" name='rekap' class='btn btn-md btn-info'>
                    <input type="submit" value="REKAP SEMUA" name='rekap_semua' class='btn btn-danger btn-md btn-info'>
                    <input type="submit" value="ANALISA TOPUP" name='anal_topup' class='btn btn- btn-md btn-info'>

                </div>
            </form>
        </div>
       


<?php 
$no=1;
if(isset($_POST['preview'])){
    ?>
    <table class='table'>
        <tr>
            <td>no</td>
            <td>loan</td>
            <td>no_center</td>
            <td>id_nasabah</td>
            <td>nasabah</td>
            <td>amount</td>
            <td>balance</td>
            <td>tunggakan</td>
            <td>minggu</td>
            <td>tgl dis</td>
        </tr>
    <?php
    $file = $_FILES['file']['tmp_name'];
    $path = $file;
    $tgl = $_POST['tgl'];
    $reader = PHPExcel_IOFactory::createReaderForFile($path);
    $objek = $reader->load($path);
    $ws = $objek->getActiveSheet();
    $last_row = $ws->getHighestDataRow();

    for($row = 7;$row<=$last_row;$row++){
        $id_nasabah =  $ws->getCell("D" . $row)->getValue();
        if($id_nasabah==null){
            
        }
        else{
            $agt = (substr(ganti_karakter($id_nasabah),0,3));

            if( $agt=="AGT" || $agt=="NSB"){
                $nasabah =  aman($con, ganti_karakter($ws->getCell("E".$row)->getValue()));
            $loan = ganti_karakter($ws->getCell("B".$row)->getValue());
            $no_center = ganti_karakter($ws->getCell("C".$row)->getValue());
            $id_nasabah = ganti_karakter1($ws->getCell("D".$row)->getValue());
            $tgl_dis = ganti_karakter1($ws->getCell("H".$row)->getValue());
            $tgl_dis = explode('/',$tgl_dis);
            $tgl_dis = $tgl_dis[2].'-'.$tgl_dis[1].'-'.$tgl_dis[0];
            $bm = ($ws->getCell("C4")->getValue());
            // echo "sssss".$bm;
            if($bm=="Ctr ID"){
                $amount = (int)ganti_karakter(str_replace(",","",$ws->getCell("H".$row)->getValue()));
                $balance = (int)ganti_karakter(str_replace(",","",$ws->getCell("M".$row)->getValue()));
                $tunggakan = (int)ganti_karakter(str_replace(",","",$ws->getCell("N".$row)->getValue()));
                $minggu = (int)ganti_karakter(str_replace(",","",$ws->getCell("O".$row)->getValue()));

            }
            else{
                $amount = (int)ganti_karakter(str_replace(",","",$ws->getCell("F".$row)->getValue()));
                $balance = (int)ganti_karakter(str_replace(",","",$ws->getCell("K".$row)->getValue()));
                $tunggakan = (int)ganti_karakter(str_replace(",","",$ws->getCell("L".$row)->getValue()));
                $minggu = (int)ganti_karakter(str_replace(",","",$ws->getCell("M".$row)->getValue()));

            }
            ?>
            <tr>
                <td><?=$no++?></td>
                <td><?=$loan?></td>
                <td><?=$no_center?></td>
                <td><?=$id_nasabah?></td>
                <td><?=$nasabah?></td>
                <td><?=$amount?></td>
                <td><?=$balance?></td>
                <td><?=$tunggakan?></td>
                <td><?=$minggu?></td>
                <td><?=$tgl_dis?></td>
            </tr>
            <?php
               //INSERT INTO `deliquency` (`id`, `loan`, `no_center`, `id_detail_nasabah`, `nasabah`, `amount`, `sisa_saldo`, `tunggakan`, `minggu`, `tgl_input`, `id_cabang`) VALUES (NULL, 'PU-072-21-01-000216', '003', 'AGT/072/01/003-000034', 'RUMNASIH', '6', '2', '1', '8', NULL, NULL); 
            mysqli_query($con,"INSERT INTO `deliquency` 
            ( id,`loan`, `no_center`, `id_detail_nasabah`, `nasabah`, `amount`, `sisa_saldo`, `tunggakan`, `minggu`, `tgl_input`, `id_cabang`,tgl_disburse) VALUES 
            (NULL, '$loan', '$no_center', '$id_nasabah', '$nasabah', '$amount', '$balance', '$tunggakan', '$minggu', '$tgl', '$id_cabang','$tgl_dis'); 
            ");
            }
            
            
                
            
        }
    }
    alert("Berhasil ditambahkan!");
    pindah($url.$menu."par");
    ?>
    </table>
    <?php
}
$sepat = $_GET['tipe'];
if(isset($_GET['bandingkan'])){
    
    $tgl_awal  = $_GET['sebelum'];
    $tgl_banding = $_GET['minggu_ini'];
    ?>
    <a href="#rekap" onclick="printPageArea('turun_par')" class="btn btn-success">print <i class="fa fa-print"></i></a>
    <div id='turun_par'>
        <h3>PENURUNAN ANGGOTA PAR</h3>
        <table class='table table-bordered'>
            <tr>
                <th>NO</th>
                <th>LOAN</th>
                <th>CENTER</th>
                <th>ID AGT</th>
                <th>ANGGOTA</th>
                <th>DISBURSE</th>
                <th>BALANCE</th>
                <th>ARREAS</th>
                <th>WEEK PAS</th>
                <th>STATUS</th>
                <th>STAFF</th>
            </tr>
        
        <?php
        $total_os = 0;
        $query = mysqli_query($con," SELECT d.*,k.nama_karyawan FROM deliquency d 
        JOIN center c ON c.`no_center`=d.`no_center` 
        JOIN karyawan k ON k.`id_karyawan`=c.`id_karyawan`
        where d.loan not in (select loan from deliquency where tgl_input='$tgl_banding' and id_cabang='$id_cabang') and d.tgl_input='$tgl_awal' and c.id_cabang='$id_cabang' and d.id_cabang='$id_cabang' order by k.nama_karyawan asc");
        while($data = mysqli_fetch_array($query)){
            $total_os+=$data['sisa_saldo'];
            $par = mysqli_num_rows(mysqli_query($con,"select * from anggota_par where id_detail_nasabah='$data[id_detail_nasabah]' and id_cabang='$id_cabang'"));
            if($par){
                $baris['baris']= "#c9c7c1";
                $baris['text']= "red";
                $baris['ket']='RE/DTD';
            }
            else{
                $baris['baris'] = "#ffff";
                $baris['text'] = "#black";
                $baris['ket']='';

            } 
            ?>
            <tr style="background-color:<?=$baris['baris']?>;color:<?=$baris['text']?>">
                <td><?=$no++?></td>
                <td><?=$data['loan']?></td>
                <td><?=$data['no_center']?></td>
                <td><?=$data['id_detail_nasabah']?></td>
                <td><?=$data['nasabah']?></td>
                <td><?=angka($data['amount'],$sepat)?></td>
                <td><?=angka($data['sisa_saldo'],$sepat)?></td>
                <td><?=angka($data['tunggakan'],$sepat)?></td>
                <td><?=$data['minggu']?></td>
                <td><?=$baris['ket']?></td>
                <td><?=$data['nama_karyawan']?></td>
            </tr>
            <?php
        }?>
        <tr>
            <th colspan="7">TOTAL OUTSTANDING BERKURANG</th>
            <th>-<?=angka($total_os,$sepat)?></th>
        </tr>
        </table>
    </div>



    
    <a href="#rekap" onclick="printPageArea('tambah_par')" class="btn btn-success">print <i class="fa fa-print"></i></a>
    
    <div id='tambah_par'>
        <h3>PENAMBAHAN ANGGOTA PAR</h3>
        <table class='table' >
            <tr>
                <th>NO</th>
                <th>LOAN</th>
                <th>CENTER</th>
                <th>ID AGT</th>
                <th>ANGGOTA</th>
                <th>DISBURSE</th>
                <th>BALANCE</th>
                <th>ARREAS</th>
                <th>WEEK PAS</th>
                <th>STATUS</th>
                <th>STAFF</th>
            </tr>
        
        <?php
        $no=1;
        $total_tambah=0;
        $query1 = mysqli_query($con,"
        SELECT d.*,k.nama_karyawan FROM deliquency d 
        JOIN center c ON c.`no_center`=d.`no_center` 
        JOIN karyawan k ON k.`id_karyawan`=c.`id_karyawan`
        where d.loan not in (select loan from deliquency where tgl_input='$tgl_awal' and id_cabang='$id_cabang') and d.tgl_input='$tgl_banding' and c.id_cabang='$id_cabang' and d.minggu=1 and d.id_cabang='$id_cabang' order by k.nama_karyawan asc");
        while($data = mysqli_fetch_array($query1)){
            $par = mysqli_num_rows(mysqli_query($con,"select * from anggota_par where id_detail_nasabah='$data[id_detail_nasabah]' and id_cabang='$id_cabang'"));
            if($par){
                $baris['baris']= "#c9c7c1";
                $baris['text']= "red";
                $baris['ket']='RE/DTD';
            }
            else{
                $baris['baris'] = "#ffff";
                $baris['text'] = "#black";
                $baris['ket']='';

            } 
            $total_tambah+=$data['sisa_saldo'];
            ?>
            <tr style="background-color:<?=$baris['baris']?>;color:<?=$baris['text']?>">
                <td><?=$no++?></td>
                <td><?=$data['loan']?></td>
                <td><?=$data['no_center']?></td>
                <td><?=$data['id_detail_nasabah']?></td>
                <td><?=$data['nasabah']?></td>
                <td><?=angka($data['amount'],$sepat)?></td>
                <td><?=angka($data['sisa_saldo'],$sepat)?></td>
                <td><?=angka($data['tunggakan'],$sepat)?></td>
                <td><?=$data['minggu']?></td>
                <td><?=$baris['ket']?></td>
                <td><?=$data['nama_karyawan']?></td>
            </tr>
            <?php
        }?>
        <tr>
            <th colspan="7">TOTAL OUTSTANDING BERTAMBAH</th>
            <th>+<?=angka($total_tambah,$sepat)?></th>
        </tr>
        </table>
    </div>


    <!-- PENGURANGAN OUTSTANDING PAR -->
    <!-- PENGURANGAN OUTSTANDING PAR -->
    <!-- PENGURANGAN OUTSTANDING PAR -->
    <!-- PENGURANGAN OUTSTANDING PAR -->
    <a href="#rekap" onclick="printPageArea('turun_os')" class="btn btn-success">print <i class="fa fa-print"></i></a>
   <div id='turun_os'>
   <h3> PENGURANGAN OUTSTANDING PAR</h3>
        <table class='table'>
            <tr>
                <th>NO</th>
                <th>LOAN</th>
                <th>CENTER</th>
                <th>ID AGT</th>
                <th>ANGGOTA</th>
                <th>DISBURSE</th>
                <th>BALANCE</th>
                <th>BALANCE </th>
                <th>MINUS</th>
                <th>WEEK</th>
                <th>STATUS</th>
                <th>STAFF</th>
            </tr>
        
        <?php
        $no=1;
        $total_minus=0;
        $query = mysqli_query($con,"
        SELECT d.*,k.nama_karyawan FROM deliquency d 
        JOIN center c ON c.`no_center`=d.`no_center` 
        JOIN karyawan k ON k.`id_karyawan`=c.`id_karyawan` 
        where d.loan  in (select loan from deliquency where tgl_input='$tgl_banding' and id_cabang='$id_cabang') and d.tgl_input='$tgl_awal' and c.id_cabang='$id_cabang' and d.id_cabang='$id_cabang' order by k.nama_karyawan asc");
        while($data = mysqli_fetch_array($query)){
            
            $loan = $data['loan'];
            $banding = mysqli_query($con,"select sisa_saldo from deliquency where loan='$loan' and tgl_input='$tgl_banding' and id_cabang='$id_cabang'");
            $banding = mysqli_fetch_array($banding);
            $saldo_awal=$data['sisa_saldo'];
            $saldo_akhir = $banding['sisa_saldo'];
            $total = $saldo_awal - $saldo_akhir;
            
            if($total>0){
                $total_minus +=  $total;
            
                $par = mysqli_num_rows(mysqli_query($con,"select * from anggota_par where id_detail_nasabah='$data[id_detail_nasabah]' and id_cabang='$id_cabang'"));
            if($par){
                $baris['baris']= "#c9c7c1";
                $baris['text']= "red";
                $baris['ket']='RE/DTD';
            }
            else{
                $baris['baris'] = "#ffff";
                $baris['text'] = "#black";
                $baris['ket']='';

            } 
            ?>
            <tr style="background-color:<?=$baris['baris']?>;color:<?=$baris['text']?>">
                    <td><?=$no++?></td>
                    <td><?=$data['loan']?></td>
                    <td><?=$data['no_center']?></td>
                    <td><?=$data['id_detail_nasabah']?></td>
                    <td><?=$data['nasabah']?></td>
                    <td><?=angka($data['amount'],$sepat)?></td>
                    <td><?=angka($saldo_awal,$sepat)?></td>
                    <td><?=angka($saldo_akhir,$sepat)?></td>
                    <td>-<?=angka($total,$sepat)?></td>
                    <td><?=$data['minggu']?></td>
                    <td><?=$baris['ket']?></td>
                    <td><?=$data['nama_karyawan']?></td>
                </tr>
                <?php
            }
        }?>
        <tr>
            <th colspan="8">TOTAL OUTSTANDING BERKURANG</th>
            <th>-<?=angka($total_minus,$sepat)?></th>
        </tr>
        </table>
   </div>













    <h3> ANGGOTA PAR <?=$tgl_banding?></h3>
    <table class='table'>
        <tr>
            <td>NO</td>
            <td>LOAN</td>
            <td>CENTER</td>
            <td>ID AGT</td>
            <td>ANGGOTA</td>
            <td>DISBURSE</td>
            <td>BALANCE</td>
            <td>ARREAS</td>
            <td>WEEK PAS</td>
            <td>STATUS</td>
            <td>STAFF</td>
        </tr>
    
    <?php
    $no=1;
    $total_bermasalah=0;
    $query = mysqli_query($con,"
    SELECT d.*,k.nama_karyawan FROM deliquency d 
	JOIN center c ON c.`no_center`=d.`no_center` 
	JOIN karyawan k ON k.`id_karyawan`=c.`id_karyawan` where d.tgl_input='$tgl_banding' and c.id_cabang='$id_cabang' and d.id_cabang='$id_cabang' order by k.nama_karyawan asc");
    while($data = mysqli_fetch_array($query)){
        $total_bermasalah+=$data['sisa_saldo'];
        $par = mysqli_num_rows(mysqli_query($con,"select * from anggota_par where id_detail_nasabah='$data[id_detail_nasabah]' and id_cabang='$id_cabang'"));
        if($par){
            $baris['baris']= "#c9c7c1";
            $baris['text']= "red";
            $baris['ket']='RE/DTD';
        }
        else{
            $baris['baris'] = "#ffff";
            $baris['text'] = "#black";
            $baris['ket']='';

        } 
        ?>
        <tr style="background-color:<?=$baris['baris']?>;color:<?=$baris['text']?>">
            <td><?=$no++?></td>
            <td><?=$data['loan']?></td>
            <td><?=$data['no_center']?></td>
            <td><?=$data['id_detail_nasabah']?></td>
            <td><?=$data['nasabah']?></td>
            <td><?=angka($data['amount'],$sepat)?></td>
            <td><?=angka($data['sisa_saldo'],$sepat)?></td>
            <td><?=angka($data['tunggakan'],$sepat)?></td>
            <td><?=$data['minggu']?></td>
            <td><?=$baris['ket']?></td>
            <td><?=$data['nama_karyawan']?></td>
        </tr>
        <?php
    }?>
    <tr>
        <th colspan="7">TOTAL OUTSTANDING BERMASALAH</th>
        <th><?=angka($total_bermasalah,$sepat)?></th>
    </tr>
    </table>
<?php
}
elseif(isset($_GET['rekap'])){
    include("./proses/rekap_par.php");
}
elseif(isset($_GET['rekap_semua'])){
    include("./proses/rekap_semua.php");
}
elseif(isset($_GET['anal_topup'])){
    include("./proses/anal_topup.php");
}
else{
    ?>
    <div class="col-md-12">
            <div class="col-md-8">

            <h3>DATA PAR</h3>
            <table class='table'>
                <tr>
                    <th>NO</th>
                    <th>Tanggal</th>
                    <th>Hari</th>
                    <th>TOTAL AGT PAR</th>
                    <th>#</th>
                </tr>
                <?php 
                $q_tgl1 = mysqli_query($con,"SELECT  tgl_input,count(*) as hitung FROM deliquency where id_cabang='$id_cabang' group by tgl_input order by tgl_input desc");
                while($cari = mysqli_fetch_array($q_tgl1)){
                    
                    ?>
                    <tr>
                        <td><?=$no++?></td>
                        <td><?=$cari['tgl_input']?></td>
                        <td><?=format_hari_tanggal($cari['tgl_input'])?></td>
                        <td><?=($cari['hitung'])?></td>
                        <td>
                            <a href="<?=$url.$menu?>par&delete&tgl=<?=$cari['tgl_input']?>" onclick="return window.confirm('apakah yakin menghapus semua data <?=format_hari_tanggal($cari['tgl_input'])?>')" class="btn btn-danger"> <i class="fa fa-times"></i> </a>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </table>
            </div>

       </div>
    <?php
    if(isset($_GET['delete'])){
        $tgl = $_GET['tgl'];
        $hapus = mysqli_query($con,"delete from deliquency where tgl_input='$tgl' and id_cabang='$id_cabang'");

        if($hapus){
            alert("Data Berhasil dihapus");
        }
        else alert("Data gagal dihapus");

        pindah($url.$menu."par");
    }
}
?>

</div>

<style>
    /* style sheet for "A4" printing */
    @page {
    size: auto;
    size: F4;
    margin: 0mm;
}

</style>
<script>
    function printPageArea(areaID){
    var printContent = document.getElementById(areaID);
    var WinPrint = window.open('', '', 'width=900,height=650');
    WinPrint.document.write(printContent.innerHTML);
    WinPrint.document.close();
    WinPrint.focus();
    WinPrint.print();
    WinPrint.close();
}
</script>