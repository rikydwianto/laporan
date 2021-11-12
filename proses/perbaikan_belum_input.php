<h2>DATA BELUM DIINPUT SL</h2>
<table id='data_center' class='table-bordered'>
    <thead>
        <tr>
            <th>ID</th>
            <th>NAMA</th>
            <th>KESALAHAN</th>
            <th>NO HP</th>
            <th>KETERANGAN</th>
            <th>KETERANGAN LAIN</th>
            <th>STAFF</th>

            <th>#</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if(isset($_GET['fil'])){
            $fil =$_GET['fil'];
            $sqt = "and perbaikan.kesalahan='$fil'";
        }
        else{
            $sqt="";
        }
        $q = mysqli_query($con, "SELECT * from perbaikan 
JOIN karyawan on perbaikan.id_karyawan=karyawan.id_karyawan
JOIN center on perbaikan.no_center=center.no_center where  karyawan.id_cabang='$id_cabang' and status_input is NULL $sqt ");
        while ($kes = mysqli_fetch_array($q)) {
        ?>
            <tr id='ganti-<?= $kes['id_perbaikan'] ?>'>
                <td><?= $kes['id_detail_nasabah'] ?></td>
                <td><?= $kes['nama_nasabah'] ?></td>
                <td><?= $kes['kesalahan'] ?></td>
                <td><?= $kes['no_hp'] ?></td>
                <td><b>
                        <?php
                        echo ($kes['nama_ibu_kandung'] === null ? "" : "Ibu : $kes[nama_ibu_kandung]<br/>");
                        echo ($kes['nik_ktp'] === null ? "" : "ktp : $kes[nik_ktp]<br/>");
                        echo ($kes['status_pernikahan'] === null ? "" : "status : $kes[status_pernikahan]<br/>");
                        echo ($kes['tgl_lahir'] === null ? "" : "lahir : $kes[tgl_lahir]<br/>");
                        echo ($kes['alamat'] === null ? "" : "alamat : $kes[alamat]<br/>");
                        if ($kes['status_input'] == 'sudah') {
                            $tmb = "btn-success";
                        } else $tmb = 'btn-danger';
                        ?>
                    </b>
                </td>
                <td>
                    <?= ($kes['keterangan_lain']) ?>
                </td>
                <td><?= ($kes['nama_karyawan']) ?></td>

                <td>
                    <a href="<?= $url . $menu ?>perbaikan&belum&id_perbaikan=<?= $kes['id_perbaikan'] ?>" class="btn btn-danger">balikan</a>
                    <a href="#" id='simpan_mdis-<?= $kes['id_perbaikan'] ?>' onclick="sudah(<?= $kes['id_perbaikan'] ?>)" data-id="<?= $kes['id_perbaikan'] ?>" class="btn <?= $tmb ?>">Sudah</a>
                </td>
            </tr>

        <?php
        }
        ?>
    </tbody>
</table>