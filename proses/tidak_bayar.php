<h1>Anggota tidak  Bayar</h1>

<?php
 if(isset($_GET['tglawal']) || isset($_GET['tglakhir']))
{
	$tglawal = $_GET['tglawal'];
	$tglakhir = $_GET['tglakhir'];
}
else{
	$tglawal = date("Y-m-d",strtotime ( '-4 day' , strtotime ( date("Y-m-d")))) ;
	$tglakhir = date("Y-m-d");
}
	 
?>
<form action="">
    <input type="hidden" name='menu' value='tidak_bayar'/>
    <input type="date" name='tglawal' value="<?=(isset($_GET['tglawal']) ?  $_GET['tglawal'] : date("Y-m-d"))?>" class=""/>
    <input type="date" name='tglakhir' value="<?=(isset($_GET['tglakhir']) ?  $_GET['tglakhir'] : date("Y-m-d"))?>" class=""/>
    <input type='submit' class="btn btn-info" name='cari' value='FILTER'/>

</form>
	<hr>
<?php 
if(isset($_GET['cari']))
{
    $tglawal = $_GET['tglawal'];
    $tglakhir = $_GET['tglakhir'];

    ?>
    <div class="col-8">

        <table class='table'>
        <tr>
			<th>NO</th>
			<th>CENTER</th>
			<th>ID</th>
			<th>STAFF</th>
			<th>NAMA</th>
			<th>OS</th>
			<th>ANGSURAN</th>
			<th>SUKARELA</th>
			<th>KETERANGAN</th>
		</tr>
        <?php 
        $qmax= mysqli_query($con,"SELECT MAX(tgl_input) AS tgl_max FROM deliquency where id_cabang='$id_cabang'");
        $max = mysqli_fetch_array($qmax)['tgl_max'];
        $q = mysqli_query($con,"select * from tidak_bayar tb join cabang c on c.kode_cabang=tb.kode_cabang join center ctr on ctr.no_center=tb.no_center join karyawan k on k.id_karyawan=ctr.id_karyawan where c.id_cabang='$id_cabang' and ctr.id_cabang='$id_cabang' and (tanggal between '$tglawal' and '$tglakhir' ) ");
        while($r=mysqli_fetch_array($q)){
            ?>
            <tr>
                <td><?=$no++?></td>
                <td><?=$r['no_center']?></td>
                <td><?=$r['id_detail_nasabah']?></td>
                <td><?=$r['nama_karyawan']?></td>
                <td><?=$r['nama']?></td>
                <td><?=angka($r['balance'])?></td>
                <td><?=angka($r['angsuran'])?></td>
                <td><?=angka($r['sukarela'])?></td>
                <td><?=$r['keterangan']?></td>
            </tr>
            <?php
        }
        ?>   
        </table>
    </div>
    <?php
}
?>