<?php
$cek_upk = mysqli_query($con,"select * from upk join center on center.no_center=upk.no_center where upk.id_karyawan='$id_karyawan' and upk.tgl_upk=curdate() and upk.status='pending' ");

?>
<h2 class="page-header">
    <?php echo format_hari_tanggal(date("Y-m-d")) . "<hr/>"; ?>
</h2>
<div class='table-responsive'>
    
    <?php 
    if(mysqli_num_rows($cek_upk)){
        ?>
        UPK Hari INI<br/>
        <small>Harap konfirmasi UPK, Tidk bisa di esok harikan</small>
        <table >
            <tr>
                <td>No</td>
                <td>Center</td>
                <td>JAM</td>
                <td>UPK</td>
                <td>Status</td>
                <td>#</td>
            </tr>
            <?php 
            $no1 = 1;
            while($upk = mysqli_fetch_array($cek_upk)){
                
                    ?>
                    <tr>
                        <td><?=$no1++?></td>
                        <td><?=$upk['no_center']?>/<?=$upk['hari']?></td>
                        <td><?=$upk['jam_center']?></td>
                        <td><input type='number' min="1" max="<?=$upk['anggota_upk']?>" id='anggota-<?=$no1?>' value='<?=$anggota = $upk['anggota_upk']?>' class='form-control' style="width: 80px;;"></td>
                        <td>
                            <select name='status' id='status-<?=$no1?>' onchange="cek_pending(<?=$no1?>)">
                                    <option value="jadi">JADI</option>
                                    <option value="pending">Pending</option>
                                    <option value="batal">Batal</option>
                            </select>
                            <br/>
                            <input type="hidden" value="<?=date("Y-m-d",strtotime ( '+7 day' , strtotime ( date("Y-m-d"))))?>"  name="tgl" id='tgl-<?=$no1?>' />
                        </td>
                        <td>
                            <input type='hidden' id='id_upk_<?=$no1?>' value='<?=$upk['id_upk']?>' class='form-control'>
                            <a href="<?=$url?>?kirim&id_upk=<?=$upk['id_upk']?>&status=jadi&anggota=<?=$anggota?>" id='link_upk' onclick="cek_upk(<?=$no1?>)" class='btn btn-danger'>KONFIRMASI</a>
                        </td>
                    </tr>

                    <?php
                
            
            }
            ?>
        </table>
        <?php
        if(isset($_GET['kirim'])){
            $id_upk = $_GET['id_upk'];
            $status = $_GET['status'];
            $anggota = $_GET['anggota'];
            $tgl = $_GET['tgl'];
            if($status == 'jadi'){
               $q_status =  mysqli_query($con,"UPDATE `upk` SET `status` = 'jadi' WHERE `id_upk` = '$id_upk'");
            //    $insert = mysqli_query($con,"INSERT INTO `anggota` (`id_anggota`, `id_karyawan`, `tgl_anggota`, `anggota_masuk`, `anggota_keluar`, `net_anggota`, `psa`, `prr`, `ppd`, `arta`, `pmb`, `id_cabang`) VALUES (NULL, '$id_karyawan', curdate(), '$anggota', '0', '$anggota', 0, 0, 0, 0, 0, '$id_cabang');
                // ");

            }
             if($status=='pending'){
                $q = mysqli_query($con,"UPDATE upk set `status`='pending', tgl_upk='$tgl', keterangan='pending minggu kemarin'  WHERE id_upk ='$id_upk'");
            }
             if($status =='batal'){
               $q1= mysqli_query($con,"UPDATE `upk` SET `status` = 'batal' WHERE `id_upk` = '$id_upk';");
            }
            pindah("$url");
        }
        ?>
        <script>
            function cek_upk(no){
                
                var link = "<?=$url?>";
                var id_upk  = $("#id_upk_" + no).val();
                var anggota  = $("#anggota-" + no).val();
                var status  = $("#status-" + no).val();
                var tgl  = $("#tgl-" + no).val();
                var url_upk = link + "?kirim&id_upk=" + id_upk +"&anggota="+ anggota+"&status="+status+"&tgl="+tgl;
                $("#link_upk").attr("href",url_upk);
                // alert(url_upk);

            }
            function cek_pending(no){
                
                var status = $("#status-" + no).val();
                if(status =='pending'){
                    $("#tgl-"+no).attr('type','date');
                }
                else{
                    $("#tgl-"+no).attr('type','hidden');
                }

            }
        </script>

<hr/>
        <?php
    }
    ?>
    <!-- <a href="<?=$url.$menu."ket_laporan&id_laporan=$cek_laporan[id_laporan]&id=$cek_laporan[id_karyawan]"?>" class="btn">LINK PENURUNAN PAR</a> -->
    <table class='table'>
        <tr>
            <th>No. CTR</th>
            <th>Status</th>
            <th>Doa</th>
            <th>Anggota</th>
            <th>Client</th>
            <th>Bayar</th>
            <th>Tidak Bayar</th>

        </tr>
        <?php

        if (!mysqli_num_rows($cek_laporan1)) {
            echo "
        <tr>
            <td style='text-align:center' colspan=6>
            <i>
            Anda belum membuat laporan hari ini <br/>
            silahkan 
            <a href='$url$menu" . "tmb_laporan" . "'>disini </a>
            
            untuk menambahkan laporan hari ini!
        <i>
            </td>
        </tr>
        ";
        } else {
            $cq = mysqli_query($con, "select * from detail_laporan where id_laporan='$cek_laporan[id_laporan]'");
            if (!mysqli_num_rows($cq)) {
                echo "<tr>
                <td colspan=7><i>data kosong!</i></td>
            </tr>";
            } else {
                $no1 = 1;
                $hitung_member = 0;
                $hitung_agt = 0;
                $hitung_bayar = 0;
                $hitung_tdk_bayar = 0;
                while ($ambil = mysqli_fetch_array($cq)) {
        ?>
                    <tr>
                        <td><?php echo $no1++ . ". " . $ambil['no_center'] ?> (<?php echo round((($ambil['total_bayar'] / $ambil['total_agt']) * 100), 2) ?>%)</td>
                        <td><?php echo $ambil['status'] ?></td>
                        <td><?php echo ($ambil['doa'] == "t" ? "T" : "Y") ?></td>
                        <td><?php echo $ambil['member'] ?></td>
                        <td><?php echo $ambil['total_agt'] ?></td>
                        <td><?php echo $ambil['total_bayar'] ?></td>
                        <td><?php echo $ambil['total_tidak_bayar'] ?>

                        </td>
                    </tr>

        <?php
                    $hitung_member = $hitung_member + $ambil['member'];
                    $hitung_agt = $hitung_agt + $ambil['total_agt'];
                    $hitung_bayar = $hitung_bayar + $ambil['total_bayar'];
                    $hitung_tdk_bayar = $hitung_tdk_bayar + $ambil['total_tidak_bayar'];
                }
            }
        }
        ?>
        <tr>
            <th colspan=3>Total</th>
            <th colspan=1><?php echo $hitung_member ?></th>
            <th colspan=1><?php echo $hitung_agt ?></th>
            <th><?php echo $hitung_bayar ?></th>
            <th>
                <?php echo  $hitung_tdk_bayar ?>
            </th>
        </tr>
        <tr>
            <th colspan=5 style="text-align:center">
                Prosentase pembayaran <?php echo round(($hitung_bayar / $hitung_agt) * 100, 2) ?>% <br />
                <?php
                if ($cek_laporan['status_laporan'] == 'pending') {
                    echo "<i>laporan belum di konfirmasi, silahkan selesaikan laporan</i>";
                ?>
                    <a href="<?php echo ("$url$menu" . "tmb_laporan&id_laporan=" . $cek_laporan['id_laporan']); ?>" class=" ">tambah</a>
                <?php
                }
                ?>
            </th>
            <th>

            </th>
        </tr>
    </table>
    Keterangan : <?= $cek_laporan['keterangan_laporan'] ?><br/>
    Penurunan PAR : <pre><?= $cek_laporan['keterangan_lain'] ?></pre>
</div>