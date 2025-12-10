<div class='content table-responsive'>
    <h2 class='page-header' style="font-style: italic;">ATUR TIM/KELOMPOK </h2>
    <a href="<?php echo "$url$menu" . "crud_kelompok" ?>" class='btn btn-danger'><i class='fa fa-eyes'></i> List Kelompok</a>
    <form method="post" action="">

        <table class="table">
            <tr>
                <th>No.</th>
                <th>Nama</th>
                <th>Kelompok</th>
                <th>#</th>
            </tr>
            <?php
            $no = 1;
            $qk = mysqli_query($con, "select * from karyawan where id_cabang='$id_cabang' and status_karyawan='aktif' and id_jabatan=(select id_jabatan from jabatan where singkatan_jabatan='SL') order by nama_karyawan asc");
            while ($tampilStaff = mysqli_fetch_assoc($qk)) {
                $cek = mysqli_fetch_assoc(mysqli_query($con, "select  * from `group_user` where id_karyawan='$tampilStaff[id_karyawan]'"));
                $idgroup = $cek['id_group'];
            ?>
                <tr>
                    <td><?= $no++ ?>.</td>
                    <td><?= $tampilStaff['nama_karyawan'] ?></td>
                    <td>
                        <input type="hidden" name="id[]" value="<?= $tampilStaff['id_karyawan'] ?>">
                        <select name='group[]'>
                            <option value="">Siilahkan Isi Kelompok</option>
                            <?php
                            $cekgroup = mysqli_query($con, "select * from `group` where id_cabang='$id_cabang'");
                            while ($tampilGroup = mysqli_fetch_assoc($cekgroup)) {
                                if ($tampilGroup['id_group'] == $idgroup) {
                            ?>
                                    <option value="<?= $tampilGroup['id_group'] ?>" selected><?= $tampilGroup['nama_group'] ?></option>
                                <?php
                                } else {
                                ?>
                                    <option value="<?= $tampilGroup['id_group'] ?>"><?= $tampilGroup['nama_group'] ?></option>
                                <?php
                                }
                                ?>

                            <?php

                            }
                            ?>
                        </select>
                    </td>
                    <td></td>
                </tr>
            <?php
            }
            ?>
            <tr>
                <td colspan="3"></td>
                <td colspan="1">
                    <input type="submit" value="Simpan Data" name='simpan_kelompok' class="btn btn-info">
                </td>
            </tr>
        </table>
    </form>
</div>
<?php
if (isset($_POST['simpan_kelompok'])) {
    $id_staff = $_POST['id'];
    $group = $_POST['group'];
    for ($i = 0; $i <= count($id_staff); $i++) {
        if (empty($group[$i])) {
            //alert('dont act anything');
        } else {
            $cek_group = mysqli_query($con, "select id_group from group_user where id_karyawan='$id_staff[$i]' ");
            if (mysqli_num_rows($cek_group)) {
                //UPDATES
                $query = mysqli_query($con, "UPDATE `group_user` SET `id_group` = '$group[$i]' WHERE id_karyawan='$id_staff[$i]'");
            } else {
                $query = mysqli_query($con, "INSERT INTO `group_user` (`id_group_user`, `id_group`, `id_karyawan`, `id_cabang`) VALUES (null, '$group[$i]', '$id_staff[$i]', '$id_cabang'); 
            ");
            }
        }
    }
    if ($query) {
        echo alert("data kelompok berhasil disimpan");
        pindah("$url$menu" . "setting_kelompok");
    }
    //INSERT INTO `komida`.`data_center` (`kecamatan`, `desa`, `rw`, `rt`, `alamat`, `no_center`, `keterangan`, `id_cabang`) VALUES ('pagaden', 'majasari', '01', '02', 'jl. majasari', '012', 'ctr doortodoor ', '1'); 
}
?>