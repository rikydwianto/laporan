<div class='content table-responsive'>
    <h2 class='page-header'>CENTER yang di tutup</h2>

    <?php
    if (isset($_POST['simpan'])) {
        $idcenter = $_POST['id_center'];
        $bl = $_POST['bl'];
        for($i=0;$i<count($idcenter);$i++)
        {
            $del = mysqli_query($con,"UPDATE `center` SET `blacklist` = '$bl[$i]' WHERE `id_center` ='$idcenter[$i]'");
        }
        if ($del) {
            pesan("Center Berhasil Disimpan", 'success');
            pindah($url.$menu."center-blacklist");
        }
    }




    ?>
    <form action="" method="post">
        

        
        <table id='data_center1' class='table'>
            <thead>
                <tr>
                    <th>NO</th>
                    <th>CENTER</th>
                    <th>HARI</th>
                    <th>JAM</th>
                    <th>ANGGOTA</th>
                    <th>STATUS</th>
                    <th>MAPS</th>

                    <th><input type="submit" class='btn btn-info' value="Simpan" name='simpan' /></th>
                </tr>
            </thead>
            <tbody>
                <?php
                $q = mysqli_query($con, "select * from center where id_cabang='$id_cabang' and id_karyawan='$id_karyawan'  order by FIELD(blacklist,'y','r') desc");
                while ($center = mysqli_fetch_assoc($q)) {
                            if ($center['blacklist'] == 'y') {
                                $status_bl = "Tutup Total";
                                $aktif_tutup = "selected";
                                $merah ="style='background-color:#f0e3e1'";
                            }
                            else if ($center['blacklist'] == 'r'){
                                $status_bl = "Tutup ada pemasukan";
                                $aktif_r='seleceted';
                                $merah ="style='background-color:#f0e3e1'";
                            } 
                            else{
                                $status_bl = "Tidak Ditutup";
                                $aktif_tidak = 'selected';
                                $merah='';
                            }
                ?>
                    <tr <?=$merah?>>
                        <td><?= $no++; ?></td>
                        <td><?= $center['no_center']; ?></td>
                        <td><?= $center['hari']; ?></td>
                        <td><?= $center['jam_center']; ?></td>
                        <td><?= $center['anggota_center']; ?></td>
                        <td>

                            <?php
                            echo $status_bl;
                            ?>

                        </td>
                        <td>
                            <?php if ($center['latitude'] != null || $center['longitude'] != NULL) : ?>
                                <a href="<?= link_maps($center['latitude'], $center['longitude']) ?>">Arahkan</a>
                            <?php endif; ?>
                        </td>

                        <td>
                            <input type="hidden" name="id_center[]" value="<?= $center['id_center']; ?>">
                            <select name="bl[]" id="" class=''>
                                <?php 
                                     if ($center['blacklist'] == 'y') {
                                      echo'<option value="y" selected>diTutup</option>
                                      <option value="r" >Tutup ada pemasukan</option>
                                      <option value="t" >Tidak Tutup</option>';
                                    }
                                    else if ($center['blacklist'] == 'r'){
                                        
                                        echo'<option value="y">diTutup</option>
                                        <option value="r" selected>Tutup ada pemasukan</option>
                                        <option value="t" >Tidak Tutup</option>';
                                    } 
                                    else{
                                        echo'<option value="y">diTutup</option>
                                        <option value="r" >Tutup ada pemasukan</option>
                                        <option value="t"selected >Tidak Tutup</option>';
                                    }
                                ?>
                                
                            </select>
                        
                        </td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </form>
</div>