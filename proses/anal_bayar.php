<?php @$tgl_awal  = $_GET['tgl_awal'];
        @$tgl_akhir = $_GET['tgl_akhir']; ?>

    <div class='content table-responsive'>
	<h2 class='page-header'>ANALISIS ANGGOTA TIDAK BAYAR</h2>
	  <!-- Button to Open the Modal -->

      <form action="" method="get" id=''>
    <input type="hidden" name="menu" value="anal_bayar">
        <div class="col-md-4">
            <h3>DARI</h3>
            <select name='tgl_awal' class='form-control' required>
                
                <option value="">PILIH MINGGU SEBELUM</option>
                <?php 
                error_reporting(0);
                $q_tgl = mysqli_query($con,"SELECT DISTINCT tanggal_blk FROM blk where id_cabang='$id_cabang'  order by tanggal_blk desc");
                while($tgl_ = mysqli_fetch_array($q_tgl)){
                    ?>
                    <option value="<?=$tgl_['tanggal_blk']?>" <?=($_GET['tgl_awal']===$tgl_['tanggal_blk']?"selected":"")?>><?=format_hari_tanggal($tgl_['tanggal_blk'])?></option>
                    <?php
                }
                ?>

            </select>
            
            
        </div>
        <div class="col-md-4">
            <h3>SAMPAI</h3>
           
            <select name='tgl_akhir' class='form-control' required>
                
                <option value="">PILIH MINGGU PEMBANDING</option>
                <?php 
                // error_reporting(0);
                $q_tgl = mysqli_query($con,"SELECT DISTINCT tanggal_blk FROM blk where id_cabang='$id_cabang'  order by tanggal_blk desc");
                while($tgl_ = mysqli_fetch_array($q_tgl)){
                    ?>
                    <option value="<?=$tgl_['tanggal_blk']?>" <?=($_GET['tgl_akhir']===$tgl_['tanggal_blk']?"selected":"")?>><?=format_hari_tanggal($tgl_['tanggal_blk'])?></option>
                    <?php
                }
                ?>

            </select>
            <input type="submit" value="BANDINGKAN" name='cek_anal_bayar' class='btn btn btn-md btn-info'>

        </div>
    </form>


    <?php 
    if(isset($_GET['cek_anal_bayar'])){
        ?>
        <table class='table'>
            <tr>
                <th>NO</th>
                <th>CENTER</th>
                <th>ID</th>
                <th>NASABAH</th>
                <th>OS <br/><?=$tgl_awal?></th>
                <th>OS <br/><?=$tgl_akhir?></th>
                <th>TOTAL SAVING <br/><?=$tgl_awal?></th>
                <th>TOTAL SAVING <br/><?=$tgl_akhir?></th>
                <th>SELISIH</th>
                <th>PAR?</th>
                <th>STAFF</th>
            </tr>
            <?php 
            $cek_q1 = mysqli_query($con,"select * from blk b join karyawan k on k.id_karyawan=b.id_karyawan where b.id_cabang='$id_cabang' and tanggal_blk='$tgl_awal' order by k.nama_karyawan,b.center asc");
            while($row1 = mysqli_fetch_array($cek_q1)){
                $cek_q2 = mysqli_query($con,"select * from blk b join karyawan k on k.id_karyawan=b.id_karyawan where b.id_anggota='$row1[id_anggota]' and b.id_cabang='$id_cabang' and tanggal_blk='$tgl_akhir' order by k.nama_karyawan asc");
                $row2 = mysqli_fetch_array($cek_q2);
                $os1 = $row1['outstanding'];
                $os2 = $row2['outstanding'];
                $sukarela1 = $row1['sukarela'];
                $wajib1 = $row1['wajib'];
                $pensiun1 = $row1['pensiun'];
                $sukarela2 = $row2['sukarela'];
                $wajib2 = $row2['wajib'];
                $pensiun2 = $row2['pensiun'];

                $simpanan1 = $sukarela1 + $pensiun1 + $wajib1;
                $simpanan2 = $sukarela2 + $pensiun2 + $wajib2;
                $selisih = $sukarela2 - $sukarela1;
                if($os1==$os2 && ($sukarela1 == $sukarela2 || $selisih>5000 && $selisih<10000)){
                    mysqli_query($con,"UPDATE blk set sinkron_sl='sudah' where id='$row1[id]' and id_cabang='$id_cabang'");
                    echo mysqli_error($con);
                    // echo "UPDATE blk set sinkron_sl='sudah' where id='$row1[id]' and id_cabang='$id_cabang'";
                }



                if($os1==$os2 ){
                    // && ($sukarela1 == $sukarela2 || $selisih>5000 && $selisih<10000)
                    ?>
                    <tr>
                        <td><?=$no++?></td>
                        <td><?=$row1['center']?></td>
                        <td><?=$row1['id_anggota']?></td>
                        <td><?=$row1['nama_nasabah']?></td>
                        <td><?=angka($os1)?></td>
                        <td><?=angka($os2)?></td>
                        <td><?=angka($simpanan1)?></td>
                        <td><?=angka($simpanan2)?></td>
                        <td><?=angka($selisih)?></td>
                        <td><?=($row1['par'])?></td>
                        <td><?=$row1['nama_karyawan']?></td>
                    </tr>

                    <?php 
                }
                else{
                    mysqli_query($con,"delete from blk where id='$row1[id]'");
                }
            }
            ?>

        </table>
        <?php
    }
    ?>


</div>
