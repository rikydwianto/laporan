<div class='content table-responsive'>
    <h2 class='page-header'>CENTER yang di tutup</h2>
    <a href="<?=$url."export/center-blacklist.php?"?>" class='btn btn-danger'>
			<i class="fa fa-file-excel-o"></i> EXPORT
		</a>

    <form action="" method="post">
        

        
        <table id='data_center1' class='table'>
            <thead>
                <tr>
                    <th>NO</th>
                    <th>STAFF</th>
                    <th>CENTER</th>
                    <th>HARI</th>
                    <th>JAM</th>
                    <th>ANGGOTA</th>
                    <th>STATUS</th>
                    <th>MAPS</th>

                </tr>
            </thead>
            <tbody>
                <?php
                $q = mysqli_query($con, "select * from center where id_cabang='$id_cabang' and  blacklist!='t' order by FIELD(blacklist,'y','r') desc");
                while ($center = mysqli_fetch_assoc($q)) {
                    $data=detail_karyawan($con,$center['id_karyawan']);
                            if ($center['blacklist'] == 'y') {
                                $status_bl = "Tutup Total";
                                $aktif_tutup = "selected";
                                $merah ="'";
                            }
                            else if ($center['blacklist'] == 'r'){
                                $status_bl = "Tutup ada pemasukan";
                                $aktif_r='seleceted';
                                $merah ="'";
                            } 
                            else{
                                $status_bl = "Tidak Ditutup";
                                $aktif_tidak = 'selected';
                                $merah='';
                            }
                ?>
                    <tr <?=$merah?>>
                        <td><?= $no++; ?></td>
                        <td><?= $data['nama_karyawan']; ?></td>
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
                        </td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </form>
</div>