<form method='get' action='<?php echo $url . $menu ?>monitoring'>
            <input type=hidden name='menu' value="monitoring" />
            <input type=hidden name='staff' />
            Sampai Dengan <input type=date name='tgl' value='<?php echo isset($_GET['tgl']) ? $_GET['tgl'] : date("Y-m-d") ?>' />
            <input type=submit name='cari' value='CARI' />
        </form>
        <br>
        <table class="table table-bordered">
            <tr>
                <th>
                    NO
                </th>
                <th>STAFF</th>
                <th class='tengah'>P.U</th>
                <th class='tengah'>PMB</th>
                <th class='tengah'>PPD</th>
                <th class='tengah'>PSA</th>
                <th class='tengah'>ARTA</th>
                <th class='tengah'>PRR</th>
                <th class='tengah'>LAIN-LAIN</th>
                <th class='tengah'>TOTAL MONITORING</th>
                <th class='tengah'>AGT</th>
                <th class='tengah'>3%</th>
                <th class='tengah'>Ket</th>
                <th class='tengah'></th>
            </tr>
            <?php
            $total_monitoring = 0;
            $total_pu = 0;
            $total_pmb = 0;
            $total_ppd = 0;
            $total_prr = 0;
            $total_psa = 0;
            $total_arta = 0;
            $total_lain = 0;
            @$tgl = $_GET['tgl'];
            if (empty($tgl)) {
                $tgl = date("Y-m-d");
            } else {
                $tgl = $tgl;
            }
            $cek_ka = mysqli_query($con, "SELECT * FROM karyawan,jabatan,cabang where karyawan.id_jabatan=jabatan.id_jabatan and karyawan.id_cabang=cabang.id_cabang and karyawan.id_cabang='$cabang' and jabatan.singkatan_jabatan='SL' and karyawan.status_karyawan='aktif' order by karyawan.nama_karyawan asc");
            while ($karyawan = mysqli_fetch_array($cek_ka)) {
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
                
            FROM pinjaman where id_karyawan=$karyawan[id_karyawan] and monitoring='belum'
                and tgl_cair <='$tgl'
            GROUP BY id_karyawan ");
                $pemb = mysqli_fetch_array($q);
                $total = $pemb['total'];
                $pu = ($pemb['pu'] == null ? 0 : $pemb['pu']);
                $pmb = ($pemb['pmb'] == null ? 0 : $pemb['pmb']);;
                $psa = ($pemb['psa'] == null ? 0 : $pemb['psa']);;
                $ppd = ($pemb['ppd'] == null ? 0 : $pemb['ppd']);;
                $arta = ($pemb['arta'] == null ? 0 : $pemb['arta']);;
                $lain = ($pemb['lain_lain'] == null ? 0 : $pemb['lain_lain']);
                $prr = ($pemb['prr'] == null ? 0 : $pemb['prr']);
                $total_pu  = $total_pu   + $pu;
                $total_pmb = $total_pmb + $pmb;
                $total_psa = $total_psa + $psa;
                $total_ppd = $total_ppd + $ppd;
                $total_arta = $total_arta + $arta;
                $total_lain = $total_lain + $lain;
                $total_prr = $total_prr + $prr;

                $total_monitoring = $total + $total_monitoring;
                $hitung_agt = mysqli_query($con, "SELECT member FROM statistik JOIN spl ON statistik.`id_statistik`=spl.`id_statistik` WHERE id_karyawan='$karyawan[id_karyawan]'  ORDER BY statistik.tgl_statistik DESC ");
                $hitung_agt = mysqli_fetch_array($hitung_agt);
                $hitung_agt = $hitung_agt['member'];
                $tiga_persen = ($hitung_agt == null ? "" : round($hitung_agt * 3 / 100));
              
                
                if($tiga_persen==$total){
                    $lebih='Pas';
                    $warna='';
                }
                elseif($tiga_persen>=$total){
                    
                    $warna ='';
                    $lebih="kurang";
                }
                elseif($total>=($tiga_persen*3)){
                    $lebih ="lebih dari 9%";
                    $warna="#b0b0b0";
                }
                else{
                    $lebih = "Lebih";
                   
                    $warna = '#fcc0c0';
                   
                }
                if($total==0){
                    $warna = '#50ff4a';
                    $lebih="NOL";

                }
            ?>
                <tr style='background:<?=$warna?>;'>
                    <td><?= $no++ ?></td>
                    <td><?= $karyawan['nama_karyawan'] ?></td>
                    <td class='tengah'><?= $pu ?></td>
                    <td class='tengah'><?= $pmb ?></td>
                    <td class='tengah'><?= $ppd ?></td>
                    <td class='tengah'><?= $psa ?></td>
                    <td class='tengah'><?= $arta ?></td>
                    <td class='tengah'><?= $prr ?></td>
                    <td class='tengah'><?= $lain ?></td>
                    
                    <td class='tengah'>
                        <?= ($total == NULL ? 0 : $total) ?>
                    </td>
                    <td class='tengah'><?= $hitung_agt ?></td>
                    <td class='tengah'><?= $tiga_persen ?></td>
                    <td class='tengah'><?= $lebih ?></td>
                    <td><a href="<?= $url . $menu ?>monitoring&id=<?= $karyawan['id_karyawan'] ?>"> Detail</a> </td>
                </tr>
            <?php
            }
            ?>
            <tr style='background:#c8c9cc'>
                <td colspan="2" class='tengah'>TOTAL</td>
                <td class='tengah'><?= $total_pu ?></td>
                <td class='tengah'><?= $total_pmb ?></td>
                <td class='tengah'><?= $total_ppd ?></td>
                <td class='tengah'><?= $total_psa ?></td>
                <td class='tengah'><?= $total_arta ?></td>
                <td class='tengah'><?= $total_prr ?></td>
                <td class='tengah'><?= $total_lain ?></td>
                <td class='tengah'>
                    <?= ($total_monitoring) ?>
                </td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        </table>