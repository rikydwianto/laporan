<?php
$id_kuis = aman($con, $_GET['idkuis']);
$q = mysqli_query($con, "select * from kuis where kuis.id_kuis='$id_kuis' order by tgl_kuis asc");
$kuis = mysqli_fetch_assoc($q);
$total_soal = mysqli_query($con, "SELECT COUNT(*) AS soal FROM kuis_soal WHERE id_kuis='$id_kuis'");
$total_soal = mysqli_fetch_assoc($total_soal)['soal'];

?>
<h3 style="text-align:"> HASIL KUIS : <?= $kuis['nama_kuis'] ?></h3>
<h3 style="text-align:"> JUMLAH SOAL : <?= $total_soal ?></h3>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>URUTAN</th>
            <th>NAMA</th>
            <th>POSISI</th>
            <th>JAWABAN BENAR</th>
            <th>SCORE</th>
            <th>WAKTU PENGERJAAN</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $q = mysqli_query($con, "SELECT *,k.nama_karyawan as nama FROM karyawan  k left join   kuis_jawab kj on kj.id_karyawan=k.id_karyawan 
        join jabatan j on j.id_jabatan=k.id_jabatan
        where  k.id_cabang='$id_cabang' ORDER BY score DESC, selisih_waktu ASC");
        $bg = '';
        echo mysqli_error($con);
        while ($isi = mysqli_fetch_assoc($q)) {
            $id_jawab = $isi['id_jawab'];
            if ($no <= 3) {
                $bg = '#b5f7a8';
            } else $bg = '';
        ?>
            <tr style="background-color: <?= $bg ?> ;">
                <td><?= $no ?></td>
                <td><?= $isi['nama'] ?></td>
                <td><?= $isi['singkatan_jabatan'] ?></td>
                <td><?= $isi['score'] ?></td>
                <td><?= round(($isi['score'] / $total_soal) * 100, 0) ?></td>
                <td><?= $isi['selisih_waktu'] ?> menit</td>
                <td>
                    <?php
                    if ($isi['status'] == '') {
                        echo "tidak mengisi";
                    } else {
                        echo $isi['status'];
                    }
                    ?>
                </td>
            </tr>

        <?php
            $no++;
        }
        ?>
    </tbody>
</table>