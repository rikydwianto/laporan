<?php 
$tgl = $_GET['tgl'];
if(isset($_GET['id'])){
    $id  = aman($con,$_GET['id']);
    $q_tambah['karyawan'] = "and p.id_karyawan='$id'";
}
else $q_tambah['karyawan']="";
$qcek = mysqli_query($con,"SELECT * FROM pengembalian p JOIN detail_pengembalian d ON p.id=d.id_pengembalian where p.tgl_pengembalian='$tgl' and p.id_cabang='$id_cabang'  $q_tambah[karyawan]");
echo mysqli_error($con);
?>
<div class='content table-responsive'>
    <a href="<?=$url.$menu?>rekap_setoran&tgl=<?=$tgl?>" class="btn btn-success">kembali</a>
    
    <h2 class='page-header'>Detail Setoran <?=format_hari_tanggal($tgl)?> <hr></h2>
       
        <div class='col-md-8'>
            <a href='<?=$url.$menu.'detail_setoran&tgl='.$tgl?>' class='btn btn-success'>Lihat Semua Center</a>
            <form method='get' >
                <input type=hidden name='menu' value='detail_setoran' />
                <input type=date name='tgl' value='<?php echo isset($_GET['tgl']) ? $_GET['tgl'] : date("Y-m-d") ?>' onchange="submit()" />
                <div class='col-md-5'>
                <select name="id" required id="karyawan"  class='form-control'>
                    <option value="">PILIH SEMUA STAFF</option>
                    <?php 
                    $id = aman($con,$_GET['id']);
                    $data_karyawan  = (karyawan($con, $id_cabang)['data']);
                    
                    for ($i = 0; $i < count($data_karyawan); $i++) {
                        $nama_karyawan = $data_karyawan[$i]['nama_karyawan'];
                        $idk = $data_karyawan[$i]['id_karyawan'];
                        if ($idk == $id) {
                            echo "<option selected value='" . $data_karyawan[$i]['id_karyawan'] . "'>" . $nama_karyawan . "</option>";
                        } else {
                            echo "<option value='" . $data_karyawan[$i]['id_karyawan'] . "'>" . $nama_karyawan . "</option>";
                        }
                    }
                    ?>
                </select>
                </div>
                <input type=submit name='cari' value='CARI' />
		</form> 
            <table class='table  table-bordered'>
                <thead>
                    <tr>
                        <th>NO.</th>
                        <th>STAFF</th>
                        <th>CENTER</th>
                        <th>POKOK</th>
                        <th>MARGIN</th>
                        <th>POKOK + MARGIN</th>
                    </tr>
                </thead>
                <tbody>
                <?php 
                 $total_margin=0;
                 $total_pokok =0;
                 $total_total =0;
                while($r=mysqli_fetch_array($qcek)){
                    $detil = json_decode($r['json_detail_pengembalian']);
                $detil = $detil->attribute;
                $pokok = int_xml($detil->Pokok);
                $center = $r['no_center'];
                $margin = int_xml($detil->NISBAH);
                $total = $pokok + $margin;
               
                $total_margin += $margin;
                $total_pokok += $pokok;
                $total_total += $total;
                ?>
                <tr>
                        <td><?=$no++?></td>
                        <td><?=$r['nama_karyawan']?></td>
                        <td style='text-align:center'><?=$center?></td>
                        <td align='right'><?=angka($pokok)?></td>
                        <td align='right'><?=angka($margin)?></td>
                        <td align='right'><?=angka($total)?></td>
                    </tr>
                <?php 
                }
                ?>
                    
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="3">TOTAL</th>
                        <th style="text-align:right"><?=angka($total_pokok)?></th>
                        <th style="text-align:right"><?=angka($total_margin)?></th>
                        <th style="text-align:right"><?=angka($total_total)?></th>
                    </tr>

                </tfoot>
        
        
            </table>
        </div>
    
</div>
