<div class='content table-responsive'>
    <h2 class='page-header'>Monitoring</h2>
    <i>//Yang bertanda merah berarti monitoring lebih dari 14 hari</i>
    <hr />
    <!-- <input type="submit" value="SIMPAN" name='mtr' class='btn btn-danger'> -->
    <a href="<?= $url . $menu ?>list-monitoring" class='btn btn-success'> <i class="fa fa-eye"></i> Lihat yang belum</a>
    <a href="<?= $url . $menu ?>list-monitoring&filter" class='btn btn-danger'> <i class="fa fa-eye"></i> Lihat Semua Data</a> <br /><br />

    <form method='get' action='<?php echo $url . $menu ?>monitoring'>
            <input type=hidden name='menu' value="list-monitoring" />
            <!-- <input type=hidden name='staff' /> -->
            Sampai Dengan <input type=date name='tgl' value='<?php echo isset($_GET['tgl']) ? $_GET['tgl'] : date("Y-m-d") ?>' />
            <input type=submit name='cari' value='CARI' />
        </form>
    <form action="" method="post">
        <?php
        if (isset($_POST['lapor'])) {
            $lapor = $_POST['sudah'];
        ?>
            <h3>Lapor kesalahan</h3>
            <table class='table-bordered'>
                <tr>
                    <th colspan=3>INFORMASI</th>
                    <th>Keluhan</th>
                </tr>
                <?php
                for ($i = 0; $i < count($lapor); $i++) {
                    $qnasabah = mysqli_query($con, "select * from pinjaman where id_detail_pinjaman ='$lapor[$i]'");
                    $nasabah  = mysqli_fetch_array($qnasabah);
                ?>
                    <tr>
                        <td>
                            <input type="hidden" name="id_pinjaman[]" value='<?= $lapor[$i] ?>'>
                            <input type="text" class='form-group' disabled value='<?= $lapor[$i] ?>'><br>
                            <input type="text" class='form-group' disabled value='<?= $nasabah['nama_nasabah'] ?>'>
                        </td>
                        <td><input type="text" class='form-group' disabled value='<?= $nasabah['jumlah_pinjaman'] ?>'>
                            <br><input type="text" class='form-group' disabled value='<?= ganti_karakter($nasabah['produk']) ?>(<?= $nasabah['pinjaman_ke'] ?>)'>
                        </td>
                        <td>
                            <input type="date" value='<?=date("Y-m-d")?>' name="tanggal[]" id="" class='form-control'>
                        </td>
                        <td>
                            <textarea name="keluhan[]" id="" cols="40" rows="3" class='form-control'></textarea>
                        </td>
                    </tr>
                <?php
                }
                ?>
                <tr>
                    <th colspan=3></th>
                    <th>
                        <input type="submit" name='ajukan' value="AJUKAN" class='btn btn-danger'>
                    </th>
                </tr>
            </table>
            <hr>
            <br>
        <?php
        }

        if(isset($_POST['ajukan'])){
            $id_de = $_POST['id_pinjaman'];
            $tanggal = $_POST['tanggal'];
           
            for($i=0;$i<count($id_de);$i++){
                $keluhan = htmlspecialchars($_POST['keluhan'][$i]);
                $q = mysqli_query($con,"INSERT INTO `banding_monitoring` (`id_pinjaman`, `id_detail_pinjaman`, `tgl_banding`, `keterangan_banding`, `id_karyawan`, `id_cabang`)
                 VALUES (null, '$id_de[$i]', '$tanggal[$i]', '$keluhan', '$id_karyawan', '$id_cabang'); ");
            }
            alert("Berhasil disimpan, silahkan tunggu konfirmasi dari adm");
        }
        ?>



        <TABLE class='table' id='data_monitoring'>
            <thead>
                <tr>
                    <!-- <th>no</th> -->
                    <!-- <th>Staff</th> -->
                    <th>NASABAH</th>
                    <th>Center</th>
                    <th>NO Pinjaman</th>
                    <th>Jumlah Pinjaman</th>
                    <th>Produk</th>
                    <th>Pesan</th>
                    <th>KE</th>

                    <th>#</th>
                    <th>
                        <input type="submit" class='btn btn-sm btn-info center' value="LAPORKAN" name='lapor'>
                    </th>
                </tr>
            </thead>
            <tbody>


                <?php
                if (isset($_GET['filter'])) {
                    $q_tambah = "";
                } else {
                    $q_tambah = "and pinjaman.monitoring ='belum'";
                }
                @$tgl = $_GET['tgl'];

                if (empty($tgl)) {
                    $tgl = date("Y-m-d");
                } else {
                    $tgl = $tgl;
                }
                $q_id = "and pinjaman.id_karyawan = '$id_karyawan'";


                $q = mysqli_query($con, "select *,pinjaman.id_detail_pinjaman as detail,DATEDIFF(CURDATE(), tgl_cair) as total_hari,
                pinjaman.id_pinjaman as id_pinjaman 
                from pinjaman left join karyawan on karyawan.id_karyawan=pinjaman.id_karyawan
                
                    where pinjaman.id_cabang='$id_cabang' $q_tambah $q_id and tgl_cair <='$tgl' and input_mtr='sudah' order by pinjaman.nama_nasabah,pinjaman.id_detail_pinjaman asc");
                while ($pinj = mysqli_fetch_array($q)) {
                    if ($pinj['total_hari'] > 14) {
                        $tr = "#ffd4d4";
                    }
                    else if ($pinj['total_hari'] >= 0 && $pinj['total_hari']<=3) {
                        $tr = "#42f554";
                    }
                     else $tr = "#fffff";

                    $qbanding = mysqli_query($con,"select * from banding_monitoring where id_detail_pinjaman='$pinj[id_detail_pinjaman]'");
                    

                ?>

                    <tr style="background:<?= $tr ?>">
                        <!-- <td><?= $no ?></td> -->
                        <!-- <td><?= $pinj['nama_karyawan'] ?></td> -->
                        <td><?= $pinj['nama_nasabah'] ?></td>
                        <td><?= $pinj['center'] ?></td>
                        <td><?= ($pinj['detail']) ?></td>
                        <td><?= $pinj['jumlah_pinjaman'] ?></td>
                        <td>
                            <?php 
                            $produk = strtolower($pinj['produk']); 
                            if($produk=="pinjaman umum") $kode = "P.U";
                            else if($produk=="pinjaman sanitasi") $kode = "PSA";
                            else if($produk=="pinjaman mikrobisnis") $kode = "PMB";
                            else if($produk=="pinjaman arta") $kode = "ARTA";
                            else if($produk=="pinjaman dt. pendidikan") $kode = "PPD";
                            else if($produk=="pinjaman renovasirumah") $kode = "PRR";
                            else $kode="LL";
                             
                             echo $kode;
                            ?>    
                        </td>
                        <td>
                        <?php
                        if(mysqli_num_rows($qbanding)){
                            $banding = mysqli_fetch_array($qbanding);
                            echo $banding['pesan']." - ";
                            echo ($banding['status']==="belum"?"onproses":"selesai");
                        } 
                        ?>    
                        </td>
                        <td><?= $pinj['pinjaman_ke'] ?></td>

                        <td>
                            <?php
                            if ($pinj['monitoring'] == 'belum') {
                                $tombol = "btn-danger";
                                $icon = "";
                            } elseif ($pinj['monitoring'] == 'sudah') {

                                $tombol = "btn-info";
                                $icon = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check" viewBox="0 0 16 16">
                                    <path d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.267.267 0 0 1 .02-.022z"/>
                                  </svg>';
                            } else $tombol = "btn-danger";
                            ?>
                            <span class="pull-right" id='loading_<?= $pinj['id_pinjaman'] ?>' class="badge rounded-pill bg-danger"></span>
                            <?= $icon ?>
                            <a href="#modalku1" id="custId" data-toggle="modal" data-id="<?= $pinj['id_pinjaman'] ?>">Detail</a>
                        </td>
                        <td>
                            <input type="checkbox" name="sudah[]" class='form-control' id="centang-<?= $no ?>" onclick="centang('<?= $no ?>','<?= $pinj['detail'] ?>')">
                        </td>
                    </tr>
                <?php
                    $no++;
                }
                ?>
            </tbody>
    </form>
    </TABLE>


</div>
<!-- Button trigger modal -->





<script>
    var url = "<?= $url ?>";
    var cabang = "<?= $id_cabang ?>";

    function centang(no, id) {
        let cek = $('#centang-' + no).val();
        $('#centang-' + no).change(function() {
            if (this.checked) {
                $('#centang-' + no).attr('value', id);
            } else {
                $('#centang-' + no).val('');
                $('#centang-' + no).find('[value]').remove();

            }
        });
        // alert(cek)





    }
</script>

<div class="modal fade" id="modalku1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <!-- Ini adalah Bagian Header Modal -->
            <div class="modal-header">
                <h4 class="modal-title">DETAIL MONITORING</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Ini adalah Bagian Body Modal -->
            <div class="modal-body">

                <div id="isi_detail"></div>
                <br><br>

            </div>

            <!-- Ini adalah Bagian Footer Modal -->
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">close</button>
            </div>

        </div>
    </div>
</div>