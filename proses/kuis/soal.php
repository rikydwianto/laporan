<?php
$id_kuis = aman($con, $_GET['idkuis']);
$q = mysqli_query($con, "select * from kuis left join karyawan on kuis.id_karyawan=karyawan.id_karyawan where kuis.id_kuis='$id_kuis' order by tgl_kuis asc");
$kuis = mysqli_fetch_array($q);
?>
<h3 style="text-align:center"> NAMA KUIS : <?= $kuis['nama_kuis'] ?></h3>
<?php 
echo var_dump($_POST['pilihan']);
?>
<form method="post" action="">

    <input type="submit" name='simpan' class='btn btn-success btn-lg ' value='SIMPAN'>
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
                        <textarea style='width:100%' class='form-control' name='soal[]' id='soal[]'><?= $soal['soal'] ?></textarea>


                    </td>
                    <td><input type="number" value="<?= $soal['point'] ?>" style='width:70px' name="point" class='form-control' id=""></td>
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
                            <input type="text" name="pilihan[<?=$baris++?>][]" style="width:300px" class='form-control' value="<?= $pilihan['jawaban'] ?>" id="pilihan"><br />

                        <?php
                        }
                        ?>

                    </td>
                    <td>

                    </td>
                    <td></td>
                </tr>

            <?php
                $no++;
            }
            ?>
        </tbody>
    </table>
    <table id='baris' CLASS='table table-bordered '>
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


            ?>
            <tr>
                <td></td>
                <td>
                    <textarea style='width:100%' class='form-control' name='soal[]' id='soal[]'></textarea>


                </td>
                <td><input type="number" value="" style='width:70px' name="point[]" class='form-control' id=""></td>
                <td>


                </td>
            </tr>
            <tr>
                <td>PILIHAN:</td>
                <td>

                    <?php
                    for ($a = 1; $a < 5; $a++) {
                    ?>
                        <input type="text" name="pilihan[]" style="width:300px" class='form-control' value="<?= $pilihan['jawaban'] ?>" id="pilihan"><br />
                    <?php
                    }
                    ?>

                </td>
                <td>

                </td>
                <td></td>
            </tr>


        </tbody>
        <tfoot>

        </tfoot>
    </table>
</form>
<table class='table'>
    <tr>
        <td></td>
        <td></td>
        <td></td>


        <td>
            <button id='tambah_form' class='btn btn-lg btn-danger'>+ Tambah Soal</button>
        </td>
    </tr>
</table>


<script>
    var input = $('#baris');

    $('#tambah_form').on('click', function(e) {
        $('#baris').before(input.clone());

        e.preventDefault();

    });
</script>