<h1>TOTAL NASABAH PER STAFF</h1>
<div class="container">
    <div class="row">
        
        <div class="col-4">
        </div>
        <div class="col-4">
            <form action="" method="post">

                <table class='table'>
                    <tr>
                        <td>NO</td>
                        <td>STAFF</td>
                        <td>NASABAH</td>
                    </tr>
                    <?php
                    $data_karyawan  = (karyawan($con, $id_cabang)['data']);
                    for ($i = 0; $i < count($data_karyawan); $i++) {
                        $nama_karyawan = $data_karyawan[$i]['nama_karyawan'];
                        $idk = $data_karyawan[$i]['id_karyawan'];
                        $cek_nas =  mysqli_query($con,"select *,sum(total_nasabah) as total from total_nasabah where id_cabang='$id_cabang' and id_karyawan='$idk'");
                        $cek_nasabah = mysqli_fetch_array($cek_nas);

                    ?>
                        <tr>
                            <td><?= $no++ ?><input type="text" name='idk[]' value='<?= $idk ?>' style="visibility: hidden;width:0px"/></td>
                            <td><?= $nama_karyawan ?>
                           
                        </td>
                            <td>
                            
                            <input type="number" name="anggota[]" min="0" id="" value='<?=($cek_nasabah['total'] === null ? "0" : $cek_nasabah['total'] )?>' class='form-control' style="width:100px">
                            </td>
                        </tr>

                    <?php
                    }
                    ?>
                    <tr>
                        <td></td>
                        <td></td>
                        <td><input type="submit" value="SIMPAN" name='simpan' class='btn btn-danger'></td>
                    </tr>
                </table>
            </form>
        </div>
    </div>

    <?php
    if(isset($_POST['simpan'])){
        
        $idk = $_POST['idk'];
        // echo count($idk);
        $total = $_POST['anggota'];
        for($i=0;$i<count($idk);$i++){
            $cek_nas =  mysqli_query($con,"select * from total_nasabah where id_cabang='$id_cabang' and id_karyawan='$idk[$i]'");
            if(mysqli_num_rows($cek_nas)){
                $cek_nasabah = mysqli_fetch_array($cek_nas);
                mysqli_query($con,"UPDATE `total_nasabah` SET `total_nasabah` = '$total[$i]' WHERE `id_total_nasabah` = '$cek_nasabah[id_total_nasabah]'; ");
            }
            else{
                mysqli_query($con,"INSERT INTO  `total_nasabah` (`id_karyawan`, `total_nasabah`, `id_cabang`) VALUES ('$idk[$i]', '$total[$i]', '$id_cabang'); ");
            }
        }
        alert("Berhasil diupdate");
        pindah($url.$menu."monitoring");
    }