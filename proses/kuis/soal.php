<?php
$id_kuis = aman($con, $_GET['idkuis']);

if(isset($_GET['hapus-soal-kuis'])){
    $idsoal= aman($con,$_GET['idsoal']);
    
    $delete = mysqli_query($con,"DELETE FROM kuis_soal where id='$idsoal' and id_kuis='$id_kuis'");
    if($delete)
    {
        pesan("Berhasil Menghapus soal",'success');
        pindah("$url$menu".'kuis&act=tambah-soal&idkuis='.$id_kuis);
    }
    else{
        pesan("Gagal menghapus : ". mysqli_error($con),'danger');
    }
}

$q = mysqli_query($con, "select * from kuis where kuis.id_kuis='$id_kuis' order by tgl_kuis asc");
$kuis = mysqli_fetch_array($q);

?>
<h3 style="text-align:center"> NAMA KUIS : <?= $kuis['nama_kuis'] ?></h3>
**jawaban benar menggunakan huruf tebal
<br>
Tambah soal dari bank soal <a href="<?=$url.$menu?>kuis&act=bank-soal&salin_soal&idkuis=<?=$id_kuis?>" class="btn btn-danger">Disini</a>
    <table id='' CLASS='table table-bordered '>
        <thead>
            <tr>
                <th>NO</th>
                <th>SOAL</th>
                <th>
                    
                </th>
                <th>#</th>
            </tr>
        </thead>

        <tbody>
            <?php
            $q = mysqli_query($con, "select * from kuis_soal where id_kuis='$id_kuis' ");
            while ($soal = mysqli_fetch_array($q)) {
            ?>
                <tr>
                    <td><?= $no ?></td>
                    <td>
                       <?= $soal['soal'] ?>

                    </td>
                    <td>
                        <!-- <input type="number" disabled value="<?= $soal['point'] ?>" style='width:70px' name="point" class='form-control' id=""> -->
                </td>
                    <td>


                    </td>
                </tr>
                <tr>
                    <td>PILIHAN:</td>
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
                        ?>
                        
                            
                        

                    </td>
                    <td>
                        <?php 
                        if($kuis['status']!='aktif'){
                            ?>
                                <a href="<?=$url.$menu?>kuis&act=tambah-soal&hapus-soal-kuis&idkuis=<?=$kuis['id_kuis']?>&idsoal=<?=$soal['id']?>" class="btn btn-lg btn-danger" onclick="return window.confirm('Apa yakin untuk dihapus?')"> <i class="fa fa-times"></i> Hapus </a>
                            <?php
                        }
                        ?>
                    </td>
                    <td>
                        
                    </td>
                </tr>

            <?php
                $no++;
            }
            ?>
        </tbody>
    </table>

    <!-- <table class='table'>
    <tr>
        <td></td>
        <td></td>
        <td></td>


        <td>
            <button id='tambah_form' class='btn btn-lg btn-danger'>+ Tambah Soal</button>
        </td>
    </tr>
</table> -->


    <script>
        var input = $('#baris');

        $('#tambah_form').on('click', function(e) {
            $('#baris').before(input.clone());

            e.preventDefault();

        });
    </script>