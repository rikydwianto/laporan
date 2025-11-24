<?php
// query tanpa SELECT *
$query = "SELECT 
            id,
            id_nasabah,
            nama,
            center,
            kelompok,
            nominal,
            lokasi,
            latitude,
            longitude,
            foto,
            id_karyawan,
            staff,
            created_at,
            keterangan,
            tanggal
          FROM pembayaran_nasabah where id_cabang='$id_cabang' ORDER BY created_at DESC";

$result = mysqli_query($con, $query);
?>

<div class="card">
    <div class="card-header bg-primary text-white">
        <h4>Data Nasabah Bayar</h4>
    </div>

    <div class="card-body">
        <table class="table table-bordered table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>NO</th>
                    <th>ID Nasabah</th>
                    <th>Nama</th>
                    <th>Center</th>
                    <th>Kelompok</th>
                    <th>Nominal</th>
                    <th>Lokasi</th>
                    <th>Latitude</th>
                    <th>Longitude</th>
                    <th>Foto</th>
                    <th>Staff</th>
                    <th>Keterangan</th>
                    <th>Tanggal</th>
                    <th>Created At</th>
                </tr>
            </thead>

            <tbody>
                <?php if(mysqli_num_rows($result) > 0){ ?>
                    <?php while($row = mysqli_fetch_assoc($result)){ ?>
                        <tr>
                            <td><?=$no++?></td>
                            <td><?= $row['id_nasabah']; ?></td>
                            <td><?= $row['nama']; ?></td>
                            <td><?= $row['center']; ?></td>
                            <td><?= $row['kelompok']; ?></td>
                            <td><?= rupiah($row['nominal']); ?></td>
                            <td><?= $row['lokasi']; ?></td>
                            <td><?= $row['latitude']; ?></td>
                            <td><?= $row['longitude']; ?></td>
                            <td>
                                <!-- photo adalah base64 -->
                                <?php if(!empty($row['foto'])){ ?>
                                    <img src="data:image/jpeg;base64,<?= $row['foto']; ?>" alt="Foto" width="100" />
                                <?php } else { ?>
                                    -
                                <?php } ?>
                            </td>
                            <td><?= $row['staff']; ?></td>
                            <td><?= $row['keterangan']; ?></td>
                            <td><?= $row['tanggal']; ?></td>
                            <td><?= $row['created_at']; ?></td>
                        </tr>
                    <?php } ?>
                <?php } else { ?>
                    <tr>
                        <td colspan="15" class="text-center">Tidak ada data.</td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>
