<?php 
if(isset($_POST['edit-soal'])){
    echo var_dump($_POST);  
}
?>
<form method="post" action="">
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
            $idsoal = aman($con,$_GET['idsoal']);
            $q1 = mysqli_query($con, "select * from soal where id_soal='$idsoal' ");
            while ($soal1 = mysqli_fetch_array($q1)) {
            ?>
                <tr>
                    <td><?= $no ?></td>
                    <td>
                        <textarea style='width:100%' class='form-control' name='soal[]' id='soal[]'><?= $soal1['soal'] ?></textarea>


                    </td>
                    <td><input type="number" value="<?= $soal1['point'] ?>" style='width:70px' name="point" class='form-control' id=""></td>
                    <td>


                    </td>
                </tr>
                <tr>
                    <td>PILIHAN:</td>
                    <td>

                        <?php
                        $baris = 1;
                        $qjawab = mysqli_query($con, "select * from jawaban where id_soal ='$soal1[id_soal]'");
                        while ($pilihan = mysqli_fetch_array($qjawab)) {
                        ?>
                            <label >
                            <input type="radio" name="jawaban_benar[]"  <?=($pilihan['jawaban_benar']=='y'?'checked':null)?> value=""> jawaban benar
                            </label> 
                            <input type="text" name="pilihan[]" style="width:300px" class='form-control' value="<?= $pilihan['jawaban'] ?>" id="pilihan"><br />

                        <?php
                        }
                        ?>

                    </td>
                    <td>
                        <input type="submit" value="SIMPAN" name='edit-soal' class='btn btn-success'>
                    </td>
                    <td></td>
                </tr>

            <?php
                $no++;
            }
            ?>
        </tbody>
    </table>
</form>