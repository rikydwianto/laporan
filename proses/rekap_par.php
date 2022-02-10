<?php $tgl_awal  = $_GET['sebelum'];
    $tgl_banding = $_GET['minggu_ini']; ?>
    <a href="#rekap" onclick="printPageArea('rekap_tambah_par')" class="btn btn-success"> PRINT PENAMBAHAN <i class="fa fa-print"></i></a>
    <a href="#rekap" onclick="printPageArea('rekap_penurunan_par')" class="btn btn-success"> PRINT PENURUNAN <i class="fa fa-print"></i></a>
    <h2>REKAP DELIQUENCY</h2><hr/>
<div class='content table-responsive'>
<div class='col-lg-6' id='rekap_penurunan_par'>
    <table class="table table-bordered">
        <tr>
            <th colspan="4"><h3>REKAP PENURUNAN PAR</h3></th>
        </tr>
        <tr>
            <th>NO</th>
            <th>STAFF</th>
            <th>TOTAL </th>
            <th>OUTSTANDING </th>
        </tr>
        <?php
        $no=1; 
        $total_minus = 0;
        $total_minus_agt = 0;
        $query = mysqli_query($con," SELECT count(d.id) as total, sum(d.sisa_saldo) as balance,k.nama_karyawan FROM deliquency d 
        JOIN center c ON c.`no_center`=d.`no_center` 
        JOIN karyawan k ON k.`id_karyawan`=c.`id_karyawan`
        where d.loan not in (select loan from deliquency where tgl_input='$tgl_banding') and d.tgl_input='$tgl_awal' and c.id_cabang='$id_cabang' and d.id_cabang='$id_cabang' group by k.id_karyawan order by k.nama_karyawan asc");
        while($minus = mysqli_fetch_array($query)){
            $total_minus +=$minus['balance'];
            $total_minus_agt +=$minus['total'];
            ?>
            <tr>
                <th><?=$no++?></th>
                <th><?=$minus['nama_karyawan']?></th>
                <th><?=$minus['total']?> </th>
                <th><?=angka($minus['balance'])?> </th>
                <!-- <th>OUTSTANDING </th> -->
            </tr>
            <?php
        }
        ?>
        <tr>
            <th colspan="2"></th>
            <th><?=$total_minus_agt?> </th>
            <th><?=angka($total_minus)?></th>
        </tr>
    </table>

</div>
<div class='col-lg-6' id='rekap_tambah_par'>
    <table class="table table-bordered">
        <tr>
            <th colspan="4"><h3>REKAP PENAMBAHAN PAR</h3></th>
        </tr>
        <tr>
            <th>NO</th>
            <th>STAFF</th>
            <th>TOTAL </th>
            <th>OUTSTANDING </th>
        </tr>
        <?php
        $no=1; 
        $total_minus = 0;
        $total_minus_agt = 0;
        $query = mysqli_query($con," SELECT count(d.id) as total, sum(d.sisa_saldo) as balance,k.nama_karyawan FROM deliquency d 
        JOIN center c ON c.`no_center`=d.`no_center` 
        JOIN karyawan k ON k.`id_karyawan`=c.`id_karyawan`
        where d.loan not in (select loan from deliquency where tgl_input='$tgl_awal' and id_cabang='$id_cabang') and  d.tgl_input='$tgl_banding' and c.id_cabang='$id_cabang' and d.id_cabang='$id_cabang' group by k.id_karyawan order by k.nama_karyawan asc");
        while($minus = mysqli_fetch_array($query)){
            $total_minus +=$minus['balance'];
            $total_minus_agt +=$minus['total'];
            ?>
            <tr>
                <th><?=$no++?></th>
                <th><?=$minus['nama_karyawan']?></th>
                <th><?=$minus['total']?> </th>
                <th><?=angka($minus['balance'])?> </th>
                <!-- <th>OUTSTANDING </th> -->
            </tr>
            <?php
        }
        ?>
        <tr>
            <th colspan="2"></th>
            <th><?=$total_minus_agt?> </th>
            <th><?=angka($total_minus)?></th>
        </tr>
    </table>

</div>




<div class='col-lg-6' id='rekap_turun_os'>
<a href="#rekap" onclick="printPageArea('rekap_turun_os')" class="btn btn-success">print <i class="fa fa-print"></i></a>
    <table class="table table-bordered">
        <tr>
            <th colspan="5"><h3>REKAP PENURUNAN OUTSTANDING PAR</h3></th>
        </tr>
        <tr>
            <th>NO</th>
            <th>STAFF</th>
            <!-- <th>TOTAL </th> -->
            <th>OUTSTANDING <br/> <?=$tgl_awal?></th>
            <th>OUTSTANDING PAR <br/> <?=$tgl_banding?> </th>
            <th>OUTSTANDING PAR <br/> BERKURANG </th>
        </tr>
        <?php
        $no=1; 
        $total_minus_os = 0;
        $total_minus_os_agt = 0;
        $total_pengurangan = 0;
        $query = mysqli_query($con,"
    SELECT sum(sisa_saldo) as total_turun,count(d.id) as hitung,k.id_karyawan,k.nama_karyawan FROM deliquency d 
	JOIN center c ON c.`no_center`=d.`no_center` 
	JOIN karyawan k ON k.`id_karyawan`=c.`id_karyawan` 
    where d.loan  in (select loan from deliquency where tgl_input='$tgl_banding' and id_cabang='$id_cabang') and d.tgl_input='$tgl_awal' and c.id_cabang='$id_cabang' and d.id_cabang='$id_cabang' group by k.id_karyawan order by k.nama_karyawan asc");
        
        while($minus = mysqli_fetch_array($query)){
            $query2 = mysqli_query($con,"
    SELECT sum(sisa_saldo) as total_turun,count(d.id) as total FROM deliquency d 
	JOIN center c ON c.`no_center`=d.`no_center` 
	JOIN karyawan k ON k.`id_karyawan`=c.`id_karyawan` 
    where d.loan  in (select loan from deliquency where tgl_input='$tgl_awal' and id_cabang='$id_cabang') and d.tgl_input='$tgl_banding' and c.id_cabang='$id_cabang' and k.id_karyawan='$minus[id_karyawan]' and d.id_cabang='$id_cabang' group by k.id_karyawan order by k.nama_karyawan asc");

    // $query3 = mysqli_query($con,"
    // SELECT d.sisa_saldo total FROM deliquency d 
	// JOIN center c ON c.`no_center`=d.`no_center` 
	// JOIN karyawan k ON k.`id_karyawan`=c.`id_karyawan` 
    // where d.loan  in (select loan from deliquency where tgl_input='$tgl_awal') and d.tgl_input='$tgl_banding' and c.id_cabang='$id_cabang' and k.id_karyawan='$minus[id_karyawan]' 
    //  order by k.nama_karyawan asc");

    $mingguini1 = mysqli_fetch_array($query2);       
    $mingguini = $mingguini1['total_turun'];
    $total_minus_os +=$minus['total_turun'];
    $pengurangan = $minus['total_turun'] - $mingguini;
    $total_pengurangan += $pengurangan;
            $total_minus_os_agt +=$minus['hitung'];
            ?>
            <tr>
                <th><?=$no++?></th>
                <th><?=$minus['nama_karyawan']?></th>
                <th><?=angka($minus['total_turun'])?> </th>
                <th><?=angka($mingguini)?> </th>
                <th><?=angka($pengurangan)?> </th>
            </tr>
            <?php
        }
        ?>
        <tr>
            <th colspan="2"></th>
            <th><?=angka($total_minus_os)?></th>
            <th> </th>
            <th><?=angka($total_pengurangan)?></th>
        </tr>
    </table>

</div>




<div class='col-lg-6' id='rekap_total_os'>
    <a href="#rekap" onclick="printPageArea('rekap_total_os')" class="btn btn-success">print <i class="fa fa-print"></i></a>
    <table class="table table-bordered">
        <tr>
            <th colspan="4"><h3>REKAP TOTAL OUTSTANDING PAR</h3></th>
        </tr>
        <tr>
            <th>NO</th>
            <th>STAFF</th>
            <th>TOTAL </th>
            <th>OUTSTANDING </th>
        </tr>
        <?php
        $no=1; 
        $total_minus = 0;
        $total_minus_agt = 0;
        $query = mysqli_query($con," SELECT count(d.id) as total, sum(d.sisa_saldo) as balance,k.nama_karyawan FROM deliquency d 
        JOIN center c ON c.`no_center`=d.`no_center` 
        JOIN karyawan k ON k.`id_karyawan`=c.`id_karyawan`
        where   d.tgl_input='$tgl_banding' and c.id_cabang='$id_cabang' and d.id_cabang='$id_cabang' group by k.id_karyawan order by k.nama_karyawan asc");
        while($minus = mysqli_fetch_array($query)){
            $total_minus +=$minus['balance'];
            $total_minus_agt +=$minus['total'];
            ?>
            <tr>
                <th><?=$no++?></th>
                <th><?=$minus['nama_karyawan']?></th>
                <th><?=$minus['total']?> </th>
                <th><?=angka($minus['balance'])?> </th>
                <!-- <th>OUTSTANDING </th> -->
            </tr>
            <?php
        }
        ?>
        <tr>
            <th colspan="2"></th>
            <th><?=$total_minus_agt?> </th>
            <th><?=angka($total_minus)?></th>
        </tr>
    </table>

</div>







<div class='col-lg-12' id='rekap'>
    <a href="#rekap" onclick="printPageArea('rekap')" class="btn btn-success">print <i class="fa fa-print"></i></a>
    <table class="table table-bordered" style="border: 1px;">
        <tr>
            <th colspan="9" style="text-align: center;font-weight:bold"><h3>REKAP SEMUA PAR</h3></th>
        </tr>
        <tr>
            <th>NO</th>
            <th>STAFF</th>
            <th>TOTAL O.S PAR <br/> <?=$tgl_awal?> </th>
            <th>PENURUNAN AGT </th>
            <th>PENGURANGAN O.S </th>
            <th>TOTAL PENURUNAN O.S</th>
            <th>PENAMBAHAN AGT </th>
            <th>PERUBAHAN </th>
            <th>TOTAL O.S PAR <br/> <?=$tgl_banding?></th>
        </tr>
        <?php 
        $no=1;
        $total_minggu_sebelumnya    =0 ;
            $total_turun            =0;
            $total_turun_os         =0;
            $total_tambah            =0;
            $total_balance          =0;
         $query = mysqli_query($con," SELECT count(d.id) as total, sum(d.sisa_saldo) as balance,k.nama_karyawan,k.id_karyawan FROM deliquency d 
         JOIN center c ON c.`no_center`=d.`no_center` 
         JOIN karyawan k ON k.`id_karyawan`=c.`id_karyawan`
         where   d.tgl_input='$tgl_banding' and c.id_cabang='$id_cabang' and d.id_cabang='$id_cabang' group by k.id_karyawan order by k.nama_karyawan asc");
         while($staff = mysqli_fetch_array($query)){
            $query1 = mysqli_query($con," SELECT  sum(d.sisa_saldo) as balance FROM deliquency d 
            JOIN center c ON c.`no_center`=d.`no_center` 
            JOIN karyawan k ON k.`id_karyawan`=c.`id_karyawan`
            where d.loan not in (select loan from deliquency where tgl_input='$tgl_banding' and id_cabang='$id_cabang') and d.tgl_input='$tgl_awal' and c.id_karyawan='$staff[id_karyawan]' and d.id_cabang='$id_cabang' group by k.id_karyawan ");
            $turun = mysqli_fetch_array($query1);
            //PENAMBAHAN 
            $query2 = mysqli_query($con," SELECT count(d.id) as total, sum(d.sisa_saldo) as balance FROM deliquency d 
            JOIN center c ON c.`no_center`=d.`no_center` 
            JOIN karyawan k ON k.`id_karyawan`=c.`id_karyawan`
            where d.loan not in (select loan from deliquency where tgl_input='$tgl_awal' and id_cabang='$id_cabang' ) and  d.tgl_input='$tgl_banding' and c.id_karyawan='$staff[id_karyawan]' and id_cabang='$id_cabang' group by k.id_karyawan ");
            $tambah = mysqli_fetch_array($query2);
            $tambah = $tambah['balance'];

            //OS TURUN
            $query3 = mysqli_query($con,"
            SELECT sum(sisa_saldo) as total_turun,count(d.id) as hitung,k.id_karyawan,k.nama_karyawan FROM deliquency d 
            JOIN center c ON c.`no_center`=d.`no_center` 
            JOIN karyawan k ON k.`id_karyawan`=c.`id_karyawan` 
            where d.loan  in (select loan from deliquency where tgl_input='$tgl_banding' and id_cabang='$id_cabang') and d.tgl_input='$tgl_awal' and k.id_karyawan='$staff[id_karyawan]' and d.id_cabang='$id_cabang' group by k.id_karyawan order by k.nama_karyawan asc");
        

            $query4 = mysqli_query($con,"
            SELECT sum(sisa_saldo) as total_turun FROM deliquency d 
            JOIN center c ON c.`no_center`=d.`no_center` 
            JOIN karyawan k ON k.`id_karyawan`=c.`id_karyawan` 
            where d.loan  in (select loan from deliquency where tgl_input='$tgl_awal' and id_cabang='$id_cabang') and d.tgl_input='$tgl_banding'  and k.id_karyawan='$staff[id_karyawan]' and d.id_cabang='$id_cabang' group by k.id_karyawan order by k.nama_karyawan asc");
            
            $query5 = mysqli_query($con," SELECT count(d.id) as total, sum(d.sisa_saldo) as balance,k.nama_karyawan,k.id_karyawan FROM deliquency d 
         JOIN center c ON c.`no_center`=d.`no_center` 
         JOIN karyawan k ON k.`id_karyawan`=c.`id_karyawan`
         where   d.tgl_input='$tgl_awal' and k.id_karyawan='$staff[id_karyawan]' and d.id_cabang='$id_cabang' group by k.id_karyawan order by k.nama_karyawan asc");
         $minggu_kemarin  = mysqli_fetch_array($query5);
         $minggu_kemarin = $minggu_kemarin['balance'];


            $mingguini1 = mysqli_fetch_array($query3);       
            $minggu_sebelumnya = $mingguini1['total_turun'];
            
            $perubahan = mysqli_fetch_array($query4);
            $perubahan = $perubahan['total_turun'];
            
            
            $balance = $staff['balance'];
            $turun_os = $minggu_sebelumnya - $perubahan;
            $turun = $turun['balance'];
            
            
            $perubahan_minggu_ini =  $tambah - ($turun + $turun_os);


            $total_minggu_sebelumnya += $minggu_kemarin;
            $total_turun             += $turun;
            $total_turun_os          += $turun_os;
            $total_tambah            += $tambah;
            $total_balance           += $balance;
            $total_perubahan        += $perubahan_minggu_ini;


            if($perubahan_minggu_ini < 0 ){
                $warna = "green";
                $icon = "<i class='fa fa-2 fa-sort-desc'></i> ";
            }
            elseif($perubahan_minggu_ini==0){
                $warna ="black";
                $icon = "<i class='fa fa-2 fa-thumbs-up'></i> ";
            }
            else{
                $warna ="red";
                $icon = "<i class='fa fa-2 fa-sort-asc'></i> ";
            }



            // $anggota  = mysqli_query($con,"select * from ");
            
             ?>
              <tr>
                <td><?=$no++?></td>
                <td><?=$staff['nama_karyawan']?></td>
                <td><?=($minggu_kemarin==null?"-":rupiah($minggu_kemarin))?> </td>
                <td><?=($turun==null?"-":rupiah($turun))?> </td>
                <td><?=($turun_os==null?"-":rupiah($turun_os))?> </td>
                <td><?=rupiah($turun + $turun_os)?></td>
                <td><?=($tambah==null?"-":rupiah($tambah))?></td>
                <td style="color:<?=$warna?>"><?=$icon.rupiah($perubahan_minggu_ini)?></td>
                <td><?=($balance==null?"-":rupiah($balance))?></td>
            </tr>
             <?php
             if($total_perubahan < 0 ){
                $warna_total = "green";
                $icon_total = "<i class='fa fa-2 fa-sort-desc'></i> ";
            }
            else{
                $warna_total ="red";
                $icon_total = "<i class='fa fa-2 fa-sort-asc'></i> ";
            }
         }
        ?>
         <tr>
                <th colspan="2">TOTAL SEMUA</th>
                <th><?=($total_minggu_sebelumnya==null?"-":rupiah($total_minggu_sebelumnya))?> </th>
                <th><?=($total_turun==null?"-":rupiah($total_turun))?> </th>
                <th><?=($total_turun_os==null?"-":rupiah($total_turun_os))?> </th>
                <th><?=rupiah($total_turun_os + $total_turun)?> </th>
                <th><?=($total_tambah==null?"-":rupiah($total_tambah))?></th>
                <td style="color:<?=$warna_total?>"><?=$icon_total.rupiah($total_perubahan)?></td>
                <th><?=($total_balance==null?"-":rupiah($total_balance))?></th>
            </tr>
    </table>
</div>


</div>