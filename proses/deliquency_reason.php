<div class="row">
	<h3 class="page-header">ANGGOTA PAR</h3>
    <h3>DETAIL NASABAH PAR</h3>
    <a href="<?=$url.$menu?>deliquency_sl" class="btn btn-success"> Data Terbaru</a>
    <a href="<?=$url.$menu?>deliquency_sl&semua" class="btn btn-danger">Lihat Semua Data</a>
    <a href="<?=$url.$menu?>deliquency_reason" class="btn btn-danger">Alasan Anggota PAR</a>
	<hr />


    <?php 
if(isset($_POST['kirim'])){
    
    $id_nasabah = $_POST['nasabah'];
    $alasan = $_POST['alasan'];
    $loan = $_POST['loan'];
    $hitung=count($id_nasabah);
    for($i=0;$i<$hitung;$i++){
        $cek = mysqli_query($con,"select * from alasan_par where id_cabang='$id_cabang' and id_loan='$loan[$i]' ");
        if(mysqli_num_rows($cek)){
           $update = mysqli_query($con,"update alasan_par set alasan='$alasan[$i]' where id_cabang='$id_cabang' and id_loan='$loan[$i]'");
        }
        else{
        $update = mysqli_query($con,"INSERT INTO `alasan_par` (`alasan`, `id_loan`, `id_detail_nasabah`, `id_cabang`, `id_karyawan`) 
            VALUES ('$alasan[$i]', '$loan[$i]', '$id_nasabah[$i]', '$id_cabang', '$id_karyawan') 
            ");
            echo "insert";
        }

    }
    echo mysqli_error($con);
    if($update){
        pesan("Terima Kasih, Data telah diupdate!",'success');
        // pindah($url.$menu."deliquency_sl");
    }
}?>
    <div class="col-md-10">
        <div class="col-md-6">
        <form method="get" >
        <input type="hidden" name="menu" value='deliquency_reason'>
    
            <select name="ctr" class='form-control' required id="">
                <option value="">PILIH CTR</option>
                <?php
                $ctr = $_GET['ctr'];
                $tgl = mysqli_fetch_array(mysqli_query($con,"select max(tgl_input) as tgl from deliquency where id_cabang='$id_cabang'"))['tgl'];
                $q = mysqli_query($con,"SELECT  d.no_center, count(*) as total,c.hari from deliquency d join center c on d.no_center=c.no_center  where d.id_cabang='$id_cabang' and c.id_karyawan='$id_karyawan' and d.tgl_input='$tgl' group by d.no_center order by no_center asc "); 
                while($ce = mysqli_fetch_array($q)){
                    if($ce['no_center']==$ctr) $ak='selected';
                    else $ak='';
                    ?>
                    <option <?=$ak?> value="<?=$ce['no_center']?>">CENTER <?=$ce['no_center']?> -  <?=$ce['hari']?> || <?=$ce['total']?> client</option>
                    <?php
                }
                ?>
            </select>

        <input type="submit" value="CARI" class='btn btn-danger'>
        <a href="<?=$url.$menu?>deliquency_reason&filter" class="btn btn-danger">Lihat yang belum</a>
    </form>
        </div>
    </div>
    <?php 
    if(isset($_GET['ctr']) || isset($_GET['filter'])){
        if( isset($_GET['filter'])){
            $filter = "and d.loan NOT IN(SELECT id_loan FROM alasan_par WHERE id_cabang='$id_cabang' AND id_karyawan='$id_karyawan')";
        }
        else{
            $filter="and  d.no_center='$ctr'";
        }
        ?>
        <form action="" method="post">

                
            <table class='table'>
                <tr>   
                    <th>NO</th>
                    <th>CENTER</th>
                    <th>PRODUK</th>
                    <th>ID</th>
                    <th>NAMA</th>
                    <th>OS PAR</th>
                    <th>WEEK PAS</th>
                    <th>ALASAN</th>
                    <th>INPUT</th>
                </tr>
                <?php
                $total_sisa_saldo = 0;
                $q_q['tgl']="tgl_input IN(SELECT MAX(tgl_input) FROM deliquency where id_cabang='$id_cabang') AND";
                $q = mysqli_query($con,"SELECT *,c.`id_karyawan` FROM deliquency d JOIN center c ON c.`no_center`=d.`no_center`
                JOIN karyawan k ON k.`id_karyawan`=c.`id_karyawan`
                WHERE $q_q[tgl] k.`id_cabang`='$id_cabang' and d.id_cabang='$id_cabang' AND k.`id_karyawan`='$id_karyawan' $filter order by d.no_center,d.nasabah asc");
                while($row = mysqli_fetch_array($q)){
                    $produk = $row['loan'];
                    $produk = explode("-",$produk)[0];
                    $sisa_saldo = $row['sisa_saldo'];
                    $total_sisa_saldo += $sisa_saldo;
                    
                    $qr = mysqli_query($con,"select * from alasan_par where id_cabang='$id_cabang' and id_loan='$row[loan]'");
                    if(mysqli_num_rows($qr)){
                        $reason = mysqli_fetch_array($qr);
                        $ket= $reason['alasan'];
                        $a=$ket;
                    }
                    else{
                        $ket="belum diisi alasan!";
                        $a="";
                    }
                    echo mysqli_error($con);
                    ?>
                    <tr >   
                        <td><?=$no++?>. 
                        <input type="hidden" name="nasabah[]" value="<?=$row['id_detail_nasabah']?>">
                        <input type="hidden" name="loan[]" value="<?=$row['loan']?>">
                    
                    </td>
                    <td><?=$row['no_center']?></td>
                    <td><?=$produk?></td>
                        <td><?=explode("-",$row['id_detail_nasabah'])[1]?></td>
                        <td><?=$row['nasabah']?></td>
                        <td><?=angka($sisa_saldo)?></td>
                        <td><?=$row['minggu']?></td>
                        <td><?=$ket?></td>
                        <td><input type="text" class='form-control' name="alasan[]" required value="<?=$a?>" id=""></td>
                    </tr>
                    <?php
                }
                ?>
                <tr>   
                    <th colspan="1">
                        
                    </th>
                    <th colspan="4">TOTAL O.S PAR</th>
                    <th><?=angka($total_sisa_saldo) ?></th>
                </tr>
                
            </table>
            <input type="submit" value="KONFIRMASI" name='kirim' class='btn btn-danger'>
            </form>
        <?php
    }
    ?>
    
</div>
