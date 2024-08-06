<div class='content table-responsive'>
    <h2 class='page-header'>Singkron Laporan dari DTC</h2>

    <div class='col-md-4'>
        <form action="" method="get">
            Pilih Tanggal :
            <input type="hidden" name="menu" value="laporan_dtc">
            <input type="date" value="<?= isset($_GET['tgl']) ? $_GET['tgl'] : date("Y-m-d") ?>" name="tanggal" id=""
                class='form-control'> <br>
            <input type="submit" value="PILIH" name='filter' class='btn btn-danger'>
        </form>
    </div>
    <div class="col-md-12">

        <?php
        if (isset($_GET['filter'])) {
            $tgl = $_GET['tanggal'];
        ?>

        <div class="table-responsive">

            <form action="" method="post">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>NO</th>
                            <th>CABANG</th>
                            <th>NIK</th>
                            <th>NAMA</th>
                            <th>TGL TRANSAKSI</th>
                            <th>DETAIL</th>
                            <th>SINGKRON</th>
                            <th>#</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php

                            $cab  = getCabang($id_cabang);
                            $nama_cabang = strtoupper($cab['nama_cabang']);
                            $q_cek  = mysqli_query($con, "SELECT * from temp_laporan_dtc where cabang='$nama_cabang' and tanggal='$tgl'");
                            if (mysqli_num_rows($q_cek)) {
                                while ($r = mysqli_fetch_assoc($q_cek)) {
                            ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= $r['cabang'] ?></td>
                            <td><?= $r['nik'] ?></td>
                            <td><?= $r['nama_staff'] ?></td>
                            <td><?= $r['tanggal'] ?></td>
                            <td>


                            </td>
                            <td><?= $r['singkron_laporan'] ?></td>
                            <td>#</td>
                        </tr>

                        <?php
                                    $json = json_decode($r['json_laporan'], true);
                                    foreach ($json as $data) {
                                    ?>
                        <tr>
                            <th>Center : </th>
                            <td colspan="7"></td>
                        </tr>
                        <?php
                                    }
                                    ?>
                        <?php
                                }
                            } else {
                                ?>
                        <tr>
                            <td colspan="8" align="center">Tidak ada Data!</td>
                        </tr>
                        <?php
                            }
                            ?>
                    </tbody>

                </table>
            </form>
        </div>
        <?php
        }
        ?>
    </div>
</div>