<div class='content table-responsive'>
    <h2 class='page-header'>DAFTAR NASABAH </h2>
    <i>DAFTAR NASABAH </i>
    <hr />
    <a href="<?= $url . $menu ?>daftar_nasabah&duplikat" class="btn btn-danger"> Nasabah Duplikat</a>
    <?php
    if (isset($_GET['duplikat'])) {
    ?>
        <table class='table'>
            <thead>
                <tr>
                    <th>NO</th>
                    <th>ID</th>
                    <th>ID Detail</th>
                    <th>NAMA</th>
                    <th>SUAMI</th>
                    <th>KTP</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = mysqli_query($con, "SELECT * from daftar_nasabah where no_ktp in (SELECT daftar_nasabah.no_ktp FROM `daftar_nasabah`
                 GROUP BY nama_nasabah, no_ktp HAVING count(*) > 1) and id_cabang='$id_cabang' order by no_ktp asc
                ");
                while ($dup = mysqli_fetch_array($query)) {
                ?>
                    <tr>
                        <td><?=$no++?></td>
                        <td><?=$dup['id_nasabah']?></td>
                        <td><?=$dup['id_detail_nasabah']?></td>
                        <td><?=$dup['nama_nasabah']?></td>
                        <td><?=$dup['suami_nasabah']?></td>
                        <td><?=$dup['no_ktp']?></td>
                    </tr>
                <?php

                }
                ?>


            </tbody>
        </table>
    <?php
    }
    ?>
</div>