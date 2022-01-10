
<style>
    #rekap_semua table thead tr th{vertical-align: middle; text-align: center;
}
</style>

<!-- <div class='content table-responsive'> -->
<div class='col-lg-12' id='rekap_semua'>
    <a href="#rekap" onclick="printPageArea('rekap_semua')" class="btn btn-success">print <i class="fa fa-print"></i></a>
    <table class="table table-bordered" style="border: 1px;">
        <thead>
            <tr>
                <th colspan="13" style="text-align: center;font-weight:bold"><h3>REKAP ANGGOTA, MONITORING , PAR<br/>
                    <?=format_hari_tanggal($tgl_awal)?> s/d <?= format_hari_tanggal( $tgl_banding)?>
                </h3></th>
            </tr>
            
            <tr >
                <th rowspan="2">NO</th>
                <th rowspan="2">STAFF</th>
                <th rowspan="2">CENTER</th>
                <th rowspan="2">MEMBER</th>
                <th colspan="2">MONITORING</th>
                
                <th style="text-align:center" colspan="3">ANGGOTA</th>
                <th style="text-align:center" colspan="2">ANGSURAN</th>
                <!-- <th rowspan="2">TOTAL <br/>PENURUNAN O.S</th>
                <th rowspan="2">PENAMBAHAN <br> OS BERMASALAH </th>
                <th rowspan="2">PERUBAHAN </th> -->
                <th rowspan="2">TOTAL O.S PAR <br/> <?=$tgl_banding?></th>
            </tr>
            <tr>
                <th colspan="1">TOTAL</th>
                <th colspan="1">%</th>
                <th colspan="1">MASUK</th>
                <th colspan="1">KELUAR</th>
                <th colspan="1">NETT</th>
                <th colspan="1">BAYAR</th>
                <th colspan="1">TIDAK</th>
                
            </tr>
        </thead>
        <?php 
        $tgl_awal  = $_GET['sebelum'];
        $tgl_banding = $_GET['minggu_ini'];
        $no=1;
        $total_minggu_sebelumnya    =0 ;
            $total_turun            =0;
            $total_turun_os         =0;
            $total_tambah            =0;
            $total_balance          =0;
            $total_masuk =0;
            $total_keluar =0;
            $total_nett =0;
            $total_monitoring =0;
            $total_member =0;
            $total_center =0;
            $total_bayar =0;
            $total_tidak_bayar =0;

         $query = mysqli_query($con,"SELECT * FROM karyawan,jabatan,cabang where karyawan.id_jabatan=jabatan.id_jabatan and karyawan.id_cabang=cabang.id_cabang and karyawan.id_cabang='$id_cabang' and jabatan.singkatan_jabatan='SL' and karyawan.status_karyawan='aktif' order by karyawan.nama_karyawan asc");
         while($staff = mysqli_fetch_array($query)){
            $query1 = mysqli_query($con," SELECT  sum(d.sisa_saldo) as balance FROM deliquency d 
            JOIN center c ON c.`no_center`=d.`no_center` 
            JOIN karyawan k ON k.`id_karyawan`=c.`id_karyawan`
            where d.loan not in (select loan from deliquency where tgl_input='$tgl_banding') and d.tgl_input='$tgl_awal' and c.id_karyawan='$staff[id_karyawan]' group by k.id_karyawan ");
            $turun = mysqli_fetch_array($query1);
            //PENAMBAHAN 
            $query2 = mysqli_query($con," SELECT count(d.id) as total, sum(d.sisa_saldo) as balance FROM deliquency d 
            JOIN center c ON c.`no_center`=d.`no_center` 
            JOIN karyawan k ON k.`id_karyawan`=c.`id_karyawan`
            where d.loan not in (select loan from deliquency where tgl_input='$tgl_awal') and  d.tgl_input='$tgl_banding' and c.id_karyawan='$staff[id_karyawan]' group by k.id_karyawan ");
            $tambah = mysqli_fetch_array($query2);
            $tambah = $tambah['balance'];

            //OS TURUN
            $query3 = mysqli_query($con,"
            SELECT sum(sisa_saldo) as total_turun,count(d.id) as hitung,k.id_karyawan,k.nama_karyawan FROM deliquency d 
            JOIN center c ON c.`no_center`=d.`no_center` 
            JOIN karyawan k ON k.`id_karyawan`=c.`id_karyawan` 
            where d.loan  in (select loan from deliquency where tgl_input='$tgl_banding') and d.tgl_input='$tgl_awal' and k.id_karyawan='$staff[id_karyawan]' group by k.id_karyawan order by k.nama_karyawan asc");
        

            $query4 = mysqli_query($con,"
            SELECT sum(sisa_saldo) as total_turun FROM deliquency d 
            JOIN center c ON c.`no_center`=d.`no_center` 
            JOIN karyawan k ON k.`id_karyawan`=c.`id_karyawan` 
            where d.loan  in (select loan from deliquency where tgl_input='$tgl_awal') and d.tgl_input='$tgl_banding'  and k.id_karyawan='$staff[id_karyawan]' group by k.id_karyawan order by k.nama_karyawan asc");
            
            $query5 = mysqli_query($con," SELECT count(d.id) as total, sum(d.sisa_saldo) as balance,k.nama_karyawan,k.id_karyawan FROM deliquency d 
         JOIN center c ON c.`no_center`=d.`no_center` 
         JOIN karyawan k ON k.`id_karyawan`=c.`id_karyawan`
         where   d.tgl_input='$tgl_awal' and k.id_karyawan='$staff[id_karyawan]' group by k.id_karyawan order by k.nama_karyawan asc");
         $minggu_kemarin  = mysqli_fetch_array($query5);
         $minggu_kemarin = $minggu_kemarin['balance'];


            $mingguini1 = mysqli_fetch_array($query3);       
            $minggu_sebelumnya = $mingguini1['total_turun'];
            
            $perubahan = mysqli_fetch_array($query4);
            $perubahan = $perubahan['total_turun'];
            


            //TOTAL OS
            $query10 = mysqli_query($con," SELECT count(d.id) as total, sum(d.sisa_saldo) as balance,k.nama_karyawan FROM deliquency d 
        JOIN center c ON c.`no_center`=d.`no_center` 
        JOIN karyawan k ON k.`id_karyawan`=c.`id_karyawan`
        where   d.tgl_input='$tgl_banding' and c.id_cabang='$id_cabang' and k.id_karyawan='$staff[id_karyawan]'  group by k.id_karyawan order by k.nama_karyawan asc");
            $bal = mysqli_fetch_array($query10);
            $balance = $bal['balance'];

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



             $anggota  = mysqli_query($con,"SELECT SUM(anggota_masuk) AS masuk, SUM(anggota_keluar) AS keluar, SUM(anggota_masuk) - SUM(anggota_keluar) AS nett FROM anggota where tgl_anggota between '$tgl_awal' and '$tgl_banding' and id_karyawan='$staff[id_karyawan]' group by id_karyawan ");
            $anggota=mysqli_fetch_array($anggota);
            $masuk = $anggota['masuk'];
            $keluar = $anggota['keluar'];
            $nett = $anggota['nett'];

            $total_masuk += $masuk;
            $total_keluar += $keluar;
            $total_nett += $nett;


            $q = mysqli_query($con, "
                SELECT  id_karyawan,
                SUM(CASE WHEN produk = 'PINJAMAN UMUM' THEN 1 ELSE 0 END) AS pu,
                SUM(CASE WHEN produk = 'PINJAMAN MIKROBISNIS' THEN 1 ELSE 0 END) AS pmb,
                SUM(CASE WHEN produk = 'PINJAMAN SANITASI' THEN 1 ELSE 0 END) AS psa,
                SUM(CASE WHEN produk = 'PINJAMAN DT. PENDIDIKAN' THEN 1 ELSE 0 END) AS ppd,
                SUM(CASE WHEN produk = 'PINJAMAN ARTA' THEN 1 ELSE 0 END) AS arta,
                SUM(CASE WHEN produk = 'PINJAMAN RENOVASIRUMAH' THEN 1 ELSE 0 END) AS prr,
                    SUM(CASE WHEN 
                produk != 'PINJAMAN UMUM' AND  
                produk != 'PINJAMAN SANITASI' AND
                produk != 'PINJAMAN MIKROBISNIS' AND
                produk != 'PINJAMAN DT. PENDIDIKAN' AND
                produk != 'PINJAMAN ARTA' AND produk != 'PINJAMAN RENOVASIRUMAH'
                
                THEN 1 ELSE 0 END) AS lain_lain,
                COUNT(*) AS total
                
            FROM pinjaman where id_karyawan=$staff[id_karyawan] and monitoring='belum'
                and tgl_cair <='$tgl_banding'
            GROUP BY id_karyawan ");
                $pemb = mysqli_fetch_array($q);
                $monitoring = $pemb['total'];
                $total_monitoring += $monitoring;



                $member = mysqli_query($con,"select * from total_nasabah where id_cabang='$id_cabang' and id_karyawan='$staff[id_karyawan]'");
                $member = mysqli_fetch_array($member);
                $member = $member['total_nasabah'];
                
                $center = mysqli_query($con,"select count(no_center) as total_center from center where id_cabang='$id_cabang' and id_karyawan='$staff[id_karyawan]' ");
                $center = mysqli_fetch_array($center);
                $center = $center['total_center'];
                $total_center += $center;
                $total_member += $member;

                $cek_l=mysqli_query($con,"SELECT sum(detail_laporan.total_agt)as anggota,sum(detail_laporan.member)as member, sum(detail_laporan.total_bayar)as bayar,sum(detail_laporan.total_tidak_bayar)as tidak_bayar, laporan.* FROM laporan,detail_laporan where laporan.id_laporan=detail_laporan.id_laporan and laporan.id_karyawan='$staff[id_karyawan]'  and laporan.tgl_laporan >= '$tgl_awal' and laporan.tgl_laporan <= '$tgl_banding'");
                $bayar_f = mysqli_fetch_array($cek_l);
                $bayar = $bayar_f['bayar'];
                $tidak_bayar =  $bayar_f['tidak_bayar'];


                $total_tidak_bayar += $tidak_bayar;
                $total_bayar += $bayar;
             ?>
              <tr>
                <td><?=$no++?></td>
                <td><?=$staff['nama_karyawan']?></td>
                <td style="text-align:center"><?=($center==null?"0":$center)?></td>
                <td style="text-align:center"><?=($member==null?"0":$member)?></td>
                <td style="text-align:center"><?=($monitoring==null?"0":$monitoring)?></td>
                <td style="text-align:center"><?=($monitoring==null?"0":round($monitoring/$member,3)*100)?>%</td>
                
                <td style="text-align:center"><?=($masuk==null?"0":$masuk)?></td>
                <td style="text-align:center"><?=($keluar==null?"0":$keluar)?></td>
                <td style="text-align:center"><?=($nett==null?"0":$nett)?></td>
                <td style="text-align:center"><?=($bayar==null?"0":$bayar)?></td>
                <td style="text-align:center"><?=($tidak_bayar==null?"0":$tidak_bayar)?></td>
                
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
                <th style="text-align:center"><?=($total_center==null?"-":($total_center))?> </th>
                <th style="text-align:center"><?=($total_member==null?"-":($total_member))?> </th>
                <th style="text-align:center"><?=($total_monitoring==null?"-":($total_monitoring))?> </th>
                <th style="text-align:center"><?=($total_monitoring==null?"0":round($total_monitoring/$total_member,3)*100)?>%</th>
                <th style="text-align:center"><?=($total_masuk==null?"-":($total_masuk))?> </th>
                <th style="text-align:center"><?=($total_keluar==null?"-":($total_keluar))?> </th>
                <th style="text-align:center"><?=($total_nett==null?"-":($total_nett))?> </th>
                <th style="text-align:center"><?=($total_bayar==null?"-":($total_bayar))?> </th>
                <th style="text-align:center"><?=($total_tidak_bayar==null?"-":($total_tidak_bayar))?> </th>
                
                <th><?=($total_balance==null?"-":rupiah($total_balance))?></th>
            </tr>
    </table>
</div>



<!-- </div> -->