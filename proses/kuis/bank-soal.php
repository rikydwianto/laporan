<h1>KUMPULAN SOAL UNTUK KUISIONER</h1>
<?php 
if(isset($_GET['edit'])){
    include("./proses/kuis/edit-soal.php");
}
elseif(isset($_GET['hapus'])){
   $idsoal= aman($con,$_GET['idsoal']);
   $delete = mysqli_query($con,"DELETE FROM kuis_bank_soal where id='$idsoal'");
   if($delete)
   {
    pesan("Berhasil Menghaspus soal",'success');
    pindah("$url$menu".'kuis&act=bank-soal');
   }
   else{
    pesan("Gagal menghapus : ". mysqli_error($con),'danger');
   }
}
elseif(isset($_GET['tambah'])){
   include("./proses/kuis/tambah-soal.php");
}
else{
$tampil=false;
$q_tampil = "";
if(isset($_GET['salin_soal']) && isset($_GET['idkuis'])){
    $tampil = true;
    $id = $_GET['idkuis'];
    $q_tampil="where id not in(select id_bank_soal from kuis_soal where id_kuis='$id') ";
}
?>
<a href="<?=$url.$menu?>kuis&act=bank-soal&tambah" class="btn btn-danger"><i class="fa fa-plus"></i> Tambah Soal</a>
<form method="post">

    <table id='' CLASS='table table-bordered '>
        <thead>
            <tr>
                <th>NO</th>
                <th>SOAL</th>
                <th>
                    Pembuat
                </th>
                <th>#</th>
            </tr>
        </thead>

        <tbody>
            <?php
            $q = mysqli_query($con, "select * from kuis_bank_soal $q_tampil ");
            while ($soal = mysqli_fetch_array($q)) {
            ?>
                <tr>
                    <td><?= $no ?></td>
                    <td>
                       <?= $soal['soal'] ?>

                    </td>
                    
                    <td>
                        <?=$soal['pembuat']?>
                        <!-- <input type="number" disabled value="<?= $soal['point'] ?>" style='width:70px' name="point" class='form-control' id=""> -->
                </td>
                    <td>
                        

                    </td>
                </tr>
                <tr>
                    <td>PILIHAN: <br>
                        <?php 
                        if($tampil){
                            ?>
                            <input type="checkbox" name="id_soal[]" value='<?=$soal['id']?>' id="" style="width: 50px;height: 50px;">
                           
                            <?php
                        }
                        ?>

                    </td>
                    <td>
                        <?php 
                        $pilihan = ['a','b','c','d'];
                        $benar = $soal['pilihan_benar'];
                        foreach($pilihan as $pilih){
                            if($benar==$pilih){
                                // echo "benar";
                                echo "<b style='font-weight:bold;font-size: large;'> ".strtoupper($pilih). " - ". $soal["pilihan_".$pilih].'</b><br/>';

                            }
                            else{
                                echo strtoupper($pilih). " - ". $soal["pilihan_".$pilih].'<br/>';

                            }
                        }
                        echo "<b>JAWABAN BENAR : " . strtoupper($benar).' - '. $soal['pilihan_'.$benar]."</b>";
                        ?>
                        
                            <!-- <input type="text" disabled name="pilihan[]" style="width:300px" class='form-control' value="<?= $pilihan['jawaban'] ?><?php echo $pilihan['jawaban_benar'] =='y' ? " - benar" : null  ?>" id="pilihan">  -->
                            <!-- <br> -->
                            
                        

                    </td>
                    <td>
                    <?php
                    $raw = explode(",",$soal['kategori']);
                    foreach($raw as $kat){
                        ?>
                    <span class="badge badge-success" style="padding: 8px;margin:3px"><?=$kat?></span><br/>
                        <?php
                    }
                    ?>

                       
                    </td>
                    <td>
                    <a href="<?=$url.$menu?>kuis&act=bank-soal&edit&idsoal=<?=$soal['id']?>" class="btn btn-lg btn-info"> <i class="fa fa-edit"></i> Edit </a><br><br>
                        <a href="<?=$url.$menu?>kuis&act=bank-soal&hapus&idsoal=<?=$soal['id']?>" class="btn btn-lg btn-danger" onclick="return window.confirm('Apa yakin untuk dihapus?')"> <i class="fa fa-times"></i> Hapus </a>
                    </td>
                </tr>

            <?php
                $no++;
            }
            ?>
        </tbody>
        <tfoot>
            <tr>
                <th><input type="submit" name='salin' value="SIMPAN">
                <th></th>
            </th>
            </tr>
        </tfoot>
    </table>
    <?php 
    if(isset($_POST['salin'])){
        $id_soal = $_POST['id_soal'];
        $id_kuis = $_GET['idkuis'];
        foreach($id_soal as $id){
            $salin = mysqli_query($con,"INSERT INTO `kuis_soal` (`soal`, `pilihan_a`, `pilihan_b`, `pilihan_c`, `pilihan_d`, `pilihan_benar`,`id_kuis`,`id_bank_soal`) 
            (SELECT soal,pilihan_a,pilihan_b,pilihan_c,pilihan_d,pilihan_benar,'$id_kuis',id from kuis_bank_soal where id='$id')");
        }
        echo mysqli_error($con);
    }
    ?>
</form>
<?php  

}
?>