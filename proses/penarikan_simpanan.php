<div class='content table-responsive'>
    <h2 class='page-header'>Penarikan Simpanan oleh Manager</h2>

    <?php
    if (isset($_POST['simpan'])) {
        $id = $_POST['id'];
        $nominal = $_POST['nominal'];
        $tgl = $_POST['tgl'];
        for ($a = 0; $a < count($id); $a++) {
            if (($id[$a] != "") && ($nominal[$a] != null)) {
                if($jabatan!="SL"){
                    $id_karyawan = $_POST['staff'][$a];
                }
                else $id_karyawan = $id_karyawan;
                $id_zero = sprintf("%00d",$id[$a]);
                $query = mysqli_query($con, "INSERT INTO `penarikan_simpanan` (`id_anggota`, `tgl_penarikan`, `nominal_penarikan`, `id_karyawan`,`id_cabang`) VALUES ('$id_zero', '$tgl[$a]', '$nominal[$a]', '$id_karyawan','$id_cabang'); ");
                if ($query) {
                    alert("Berhasil disimpan");
                    pindah($url . $menu . "penarikan_simpanan");
                } else {
                    alert("gagal disimnpan");
                }
            }
        }
    }


    if ($jabatan == "SL") {
    ?>
        <table class='table'>
            <tr>
                <th>No</th>
                <th>ID</th>
                <th>ID Nasabah</th>
                <th>Nasabah</th>
                <th>Nominal</th>
            </tr>

            <?php
            $tgl = date("Y-m-d");
            $total_penarikan = 0;
            $penarikan = mysqli_query($con, "SELECT * FROM penarikan_simpanan left JOIN daftar_nasabah ON daftar_nasabah.`id_nasabah`=penarikan_simpanan.`id_anggota` where penarikan_simpanan.tgl_penarikan='$tgl' and penarikan_simpanan.id_karyawan='$id_karyawan' ");
            while ($simp = mysqli_fetch_array($penarikan)) {
                $total_penarikan = $total_penarikan + $simp['nominal_penarikan'];
            ?>
                <tr>
                    <td><?= $no++ ?>.</td>
                    <td><?= $simp['id_anggota'] ?></td>
                    <td><?= $simp['id_detail_nasabah'] ?></td>
                    <td><?= $simp['nama_nasabah'] ?></td>
                    <td><?= rupiah($simp['nominal_penarikan']) ?></td>
                </tr>
            <?php
            }
            ?>
            <tr>
                <th colspan="4">Total Penarikan</th>
                <th><?= rupiah($total_penarikan) ?></th>
            </tr>
        </table>
    <?php

    }
    else{
        ?>
            <a href="<?=$url.$menu?>penarikan_simpanan&list=cari&cari=FILTER" class='btn btn-success'>
                <i class="fa fa-plus"></i> Tambah
            </a>
            <!-- tglawal=<?=$tglawal?>&tglakhir=<?=$tglakhir?>& -->
            <a href="<?=$url.$menu?>list_penarikan&list=cari&cari=FILTER" class='btn btn-info'>
        <i class="fa fa-eye"></i> Lihat data
    </a>
        <?php
    }
    ?>

<form action="" method="post">
        jangan gunakan titik(.)  masukan angka saja contoh Rp. 100.000 input (100000) <br>
        <div class="col-md-8">
            <table class="table">
                <tr>
                    <th>No.</th>
                    <th>ID angggota</th>
                    <th>Nominal</th>
                    <th>Tanggal</th>
                </tr>
                <?php
                
                for ($i = 1; $i <= 15; $i++) {
                ?>
                    <tr>
                        <td><?= $i ?></td>
                        <td>
                            <input type="number" class='form-control' name='id[]' />
                        </td>
                        <td>
                            <input type="number" class='form-control' name='nominal[]' id='nominal' style="min-width: 200px;" pattern="\d+"/>
                        </td>
                        <td>
                            <input type="date" class='form-control' name='tgl[]' value="<?= date("Y-m-d") ?>" id='tgl' />
                        </td>
                        <?php 
                        if($jabatan !='SL'){
                            ?>
                            <td>
                                <select name="staff[]" class='form-control' id="">
                                    <option>Pilih Staff</option>
                                    <?php 
                                    $k = mysqli_query($con,"select * from karyawan where id_cabang='$id_cabang' and status_karyawan ='aktif' order by nama_karyawan asc");
                                    while($staff = mysqli_fetch_array($k)){
                                        echo "<option value='$staff[id_karyawan]'>$staff[nama_karyawan]</option>";
                                    }
                                    ?>
                                </select>
                            </td>
                            <?php
                        }
                        ?>
                    </tr>
                <?php
                }
                ?>
                <tr>
                    <td></td>
                    <td>
                        <input type="submit" name="simpan" value="SIMPAN" class="btn btn-success" />
                    </td>
                </tr>
            </table>
        </div>
    </form>
</div>