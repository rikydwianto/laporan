<div class="row">
	<h3 class="page-header">ANGGOTA PAR</h3>
    <h3>DETAIL NASABAH PAR</h3>
    <a href="<?=$url.$menu?>deliquency_sl" class="btn btn-success"> Data Terbaru</a>
    <a href="<?=$url.$menu?>deliquency_sl&semua" class="btn btn-danger">Lihat Semua Data</a>
	<hr />
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
            </tr>
            <?php
            $total_sisa_saldo = 0;
            if(isset($_GET['semua'])){
                $q_q['group']="group by d.loan";
                $q_q['tgl']="";
            }
            else{
                $q_q['group']="";
                $q_q['tgl']="tgl_input IN(SELECT MAX(tgl_input) FROM deliquency where id_cabang='$id_cabang') AND";
            }
            $q = mysqli_query($con,"SELECT *,c.`id_karyawan` FROM deliquency d JOIN center c ON c.`no_center`=d.`no_center`
            JOIN karyawan k ON k.`id_karyawan`=c.`id_karyawan`
            WHERE $q_q[tgl] k.`id_cabang`='$id_cabang' AND k.`id_karyawan`='$id_karyawan' $q_q[group] order by d.no_center,d.nasabah asc");
            while($row = mysqli_fetch_array($q)){
                $produk = $row['loan'];
                $produk = explode("-",$produk)[0];
                $par = mysqli_num_rows(mysqli_query($con,"select * from anggota_par where id_detail_nasabah='$row[id_detail_nasabah]'"));
                $sisa_saldo = $row['sisa_saldo'];
                $total_sisa_saldo += $sisa_saldo;
                if($par){
                    $baris['baris']= "#c9c7c1";
                    $baris['text']= "red";
                }
                else{
                    $baris['baris'] = "#ffff";
                    $baris['text'] = "#black";

                } 
                ?>
                <tr style="background-color:<?=$baris['baris']?>;color:<?=$baris['text']?>">   
                    <td><?=$no++?>. 
                    <input type="hidden" name="nasabah[]" value="<?=$row['id_detail_nasabah']?>">
                    <input type="hidden" name="loan[]" value="<?=$row['loan']?>">
                    <select name='bermasalah[]'>
                        <option value='ya'  <?=($par>0?"selected":"")?>>RE</option>
                        <option value='tidak' <?=($par<1?"selected":"")?>>TIDAK RE</option>
                    </select>
                </td>
                <td><?=$row['no_center']?></td>
                <td><?=$produk?></td>
                    <td><?=$row['id_detail_nasabah']?></td>
                    <td><?=$row['nasabah']?></td>
                    <td><?=angka($sisa_saldo)?></td>
                    <td><?=$row['minggu']?></td>
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
</div>
<?php 
if(isset($_POST['kirim'])){
    
    $bermasalah = $_POST['bermasalah'];
    $id_nasabah = $_POST['nasabah'];
    $loan = $_POST['loan'];
    $hitung=count($bermasalah);
    for($i=0;$i<$hitung;$i++){
        if($bermasalah[$i]=='ya'){
            $update = mysqli_query($con,"INSERT INTO `anggota_par` (`id_karyawan`, `id_detail_nasabah`,loan, `id_cabang`) VALUES ('$id_karyawan', '$id_nasabah[$i]','$loan[$i]', '$id_cabang'); 
            ");
        }
    }
    if($update){
        alert("Terima Kasih, Data telah diupdate!");
        pindah($url.$menu."deliquency_sl");
    }
}
?>