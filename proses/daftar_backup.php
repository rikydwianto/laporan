<div class='content table-responsive'>

    <form>
        <?php
        if (isset($_GET['ganti_status'])) {
            $id_nasabah = $_GET['id'];
            $backup = $_GET['backup'];
            mysqli_query($con, "update daftar_nasabah set backup='$backup' where id_cabang='$id_cabang' and id_detail_nasabah='$id_nasabah' ");
        } else if (isset($_GET['list'])) {
        ?>
            <h2 class='page-header'>LIST ANGGOTA BACKUP/PIHAK KETIGA</h2>
            <a href="<?= $url . $menu ?>daftar_backup" class='btn btn-danger'><i class='fa fa-plus'></i> TAMBAH ANGGOTA BACKUP</a>
            <table class='table table-bordered'>
                <thead>
                    <tr>
                        <th>NO</th>
                        <th>CENTER</th>
                        <th>KELOMPOK</th>
                        <th>CLIENTID</th>
                        <th>NAMA</th>
                        <th>HARI</th>
                        <th>STATUS BACKUP</th>
                        <th>STAFF</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $qkar = mysqli_query($con, "select * from daftar_nasabah d join karyawan k on k.id_karyawan=d.id_karyawan where d.id_cabang='$id_cabang' and backup='ya' order by k.nama_karyawan asc,hari desc, no_center");
                    echo mysqli_error($con);
                    while ($rkar = mysqli_fetch_assoc($qkar)) {
                    ?>
                        <tr>
                            <td><?= $no ?></td>
                            <td><?= $rkar['no_center'] ?></td>
                            <td><?= $rkar['kelompok'] ?></td>
                            <td><?= $rkar['id_detail_nasabah'] ?></td>
                            <td><?= $rkar['nama_nasabah'] ?></td>
                            <td><?= $rkar['nama_karyawan'] ?></td>
                            <td><?= $rkar['hari'] ?></td>
                            <td>
                                <?php
                                if ($rkar['backup'] == 'ya') {
                                ?>
                                    DIBACKUP
                                <?php
                                } else {
                                ?>
                                    TIDAK DIBACKUP
                                <?php

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
        <?php
        } else {
        ?>
            <h2 class='page-header'>TAMBAH DAFTAR ANGGOTA BACKUP/PIHAK KETIGA</h2>
            <a href="<?= $url . $menu ?>daftar_backup&list" class='btn btn-primary'><i class='fa fa-list'></i>LIST ANGGOTA BACKUP</a>
            <table class='table table-bordered' id='data_karyawan'>
                <thead>
                    <tr>
                        <th>NO</th>
                        <th>CENTER</th>
                        <th>KELOMPOK</th>
                        <th>CLIENTID</th>
                        <th>NAMA</th>
                        <th>HARI</th>
                        <th>STAFF</th>
                        <th>STATUS BACKUP</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $qkar = mysqli_query($con, "select * from daftar_nasabah d join karyawan k on k.id_karyawan=d.id_karyawan where d.id_cabang='$id_cabang' order by k.nama_karyawan asc,hari desc, no_center");
                    echo mysqli_error($con);
                    while ($rkar = mysqli_fetch_assoc($qkar)) {
                    ?>
                        <tr>
                            <td><?= $no ?></td>
                            <td><?= $rkar['no_center'] ?></td>
                            <td><?= $rkar['kelompok'] ?></td>
                            <td><?= $rkar['id_detail_nasabah'] ?></td>
                            <td><?= $rkar['nama_nasabah'] ?></td>
                            <td><?= $rkar['hari'] ?></td>
                            <td><?= $rkar['nama_karyawan'] ?></td>
                            <td>
                                <?php
                                if ($rkar['backup'] == 'ya') {
                                ?>
                                    <a onclick="status_backup('<?= $rkar['id_detail_nasabah'] ?>','ya','<?= $no ?>')" id='status-<?= $no ?>' class="btn btn-danger">dibackup</a>
                                <?php
                                } else {
                                ?>
                                    <a onclick="status_backup('<?= $rkar['id_detail_nasabah'] ?>','tidak','<?= $no ?>')" id='status-<?= $no ?>' class="btn btn-primary">tidak</a>
                                <?php

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
    </form>
<?php
        }
?>
</div>



<script>
    function status_backup(id, backup, no) {
        // e.prefentDefault();
        let lokasi = "<?= $url . $menu ?>daftar_backup_sl&ganti_status&id=";
        // alert(lokasi)
        let status = $("#status-" + no);
        $(document).ready(function() {

            if (backup == 'ya') {


                $.get(lokasi + id + "&backup=tidak", function() {
                    status.addClass("btn-primary").removeClass("btn-danger");
                    status.html("tidak")
                });
            } else {
                $.get(lokasi + id + "&backup=ya", function() {
                    status.html("dibackup")
                    status.addClass("btn-danger").removeClass("btn-primary");
                });


            }
        })

    }
</script>