<?php @$tgl_awal  = $_GET['tgl_awal'];
        @$tgl_akhir = $_GET['tgl_akhir']; ?>
<form action="" method="get">
    <input type="hidden" name="menu" value="monitoring">
        <div class="col-md-4">
            <h3>DARI</h3>
            <input type="date" value="<?=($tgl_awal==""?date("Y-m-d"):$tgl_awal)?>" required name="tgl_awal" class='form-control' id="">
            
            
        </div>
        <div class="col-md-4">
            <h3>SAMPAI</h3>
            <input type="date" required value="<?=($tgl_akhir==""?date("Y-m-d"):$tgl_akhir)?>" name="tgl_akhir" class='form-control' id="">
            <input type="submit" value="REKAP SEMUA" name='pengumpulan_mtr' class='btn btn-danger btn-md btn-info'>

        </div>
    </form>
<div class="col-md-12">
    <?php 
    if(isset($_GET['tgl_awal'])){
        ?>
            <table class='table'>
                <tr>
                    <th>NO</th>
                    <th>NIK</th>
                    <th>STAFF</th>
                    <th>MONITORING <br/> DIKUMPULKAN</th>
                    <th>KETERANGAN</th>
                </tr>
                
                <?php 
                $total_mtr = 0;
                $cek_ka = mysqli_query($con, "SELECT * FROM karyawan k 
                join jabatan j on  j.id_jabatan=k.id_jabatan 
                where j.singkatan_jabatan='SL' and k.id_cabang='$id_cabang' and k.status_karyawan='aktif'
                order by k.nama_karyawan asc
                ");
                while($r = mysqli_fetch_array($cek_ka)){
                    $total = mysqli_fetch_array(mysqli_query($con,"SELECT count(*) as total_mtr from monitoring m 
                    join pinjaman p on p.id_detail_pinjaman=m.id_detail_pinjaman 
                    where p.id_karyawan='$r[id_karyawan]' and p.monitoring='sudah' and m.tgl_monitoring >= '$tgl_awal' and  m.tgl_monitoring <= '$tgl_akhir'
                    "));
                    $total = $total['total_mtr'];
                    $total_mtr +=$total;
                    if($total<1){
                        $ket='Tidak Mengumpulkan Monitoring';
                    }
                    else{
                        $ket="";
                    }
                    ?>
                <tr>
                    <td><?=$no++?></td>
                    <td><?=$r['nik_karyawan']?></td>
                    <td><?=$r['nama_karyawan']?></td>
                    <td><?=$total?></td>
                    <td><?=$ket?></td>
                </tr>
                <?php
                }
                ?>
                <tr>
                    <td colspan="3">Total Monitoring Dikumpulkan</td>
                    <td><?=$total_mtr?></td>
                    <td></td>
                </tr>
            </table>
        <?php
    }
    ?>
</div>