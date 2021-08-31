<?php
$id_kuis = aman($con, $_GET['idkuis']);
$q = mysqli_query($con, "select * from kuis left join karyawan on kuis.id_karyawan=karyawan.id_karyawan where kuis.id_kuis='$id_kuis' order by tgl_kuis asc");
$kuis = mysqli_fetch_array($q);
?>
<h3 style="text-align:center"> NAMA KUIS : <?= $kuis['nama_kuis'] ?></h3>

<?php 
if(isset($_GET['idsoal'])){
    include("proses/kuis/edit-soal.php");

}
?>


    <table id='' CLASS='table table-bordered '>
        <thead>
            <tr>
                <th>NO</th>
                <th>SOAL</th>
                <th>POINT</th>
                <th>#</th>
            </tr>
        </thead>

        <tbody>
            <?php
            $q = mysqli_query($con, "select * from soal where id_kuis='$id_kuis' ");
            while ($soal = mysqli_fetch_array($q)) {
            ?>
                <tr>
                    <td><?= $no ?></td>
                    <td>
                       <?= $soal['soal'] ?>

                    </td>
                    <td><input type="number" disabled value="<?= $soal['point'] ?>" style='width:70px' name="point" class='form-control' id=""></td>
                    <td>


                    </td>
                </tr>
                <tr>
                    <td>PILIHAN:</td>
                    <td>

                        <?php
                        $baris = 1;
                        $qjawab = mysqli_query($con, "select * from jawaban where id_soal ='$soal[id_soal]'");
                        while ($pilihan = mysqli_fetch_array($qjawab)) {
                        ?>
                            <input type="text" disabled name="pilihan[]" style="width:300px" class='form-control' value="<?= $pilihan['jawaban'] ?><?php echo $pilihan['jawaban_benar'] =='y' ? " - benar" : null  ?>" id="pilihan"> 
                            <br>
                            
                        <?php
                        }
                        ?>

                    </td>
                    <td>
                        <a href="<?=$url.$menu?>kuis&act=tambah-soal&idkuis=<?=$id_kuis?>&idsoal=<?=$soal['id_soal']?>" class="btn btn-lg btn-info"> <i class="fa fa-edit"></i> Edit </a><br><br>
                        <a href="" class="btn btn-lg btn-danger"> <i class="fa fa-times"></i> Hapus </a>
                    </td>
                    <td></td>
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