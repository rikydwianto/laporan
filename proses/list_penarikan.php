
<?php
	 if(isset($_GET['tgl']))
	{
		$qtgl=$_GET['tgl'];
	}
	else{
		$qtgl=date("Y-m-d");
	}
?>
<div class='content table-responsive'>
    <h2 class='page-header'>Penarikan Simpanan oleh Manager</h2>
    <a href="<?=$url.$menu?>penarikan_simpanan&list=cari&tglawal=<?=$tglawal?>&tglakhir=<?=$tglakhir?>&cari=FILTER" class='btn btn-success'>
        <i class="fa fa-plus"></i> Tambah
    </a>
    <a href="<?=$url."export/penarikan_simpanan.php?"?>upk&tgl=<?=$qtgl?>" class='btn btn-danger'>
        <i class="fa fa-file-excel-o"></i> EXPORT
    </a>
    <br>
    <form method='get' action='<?php echo $url.$menu ?>list_penarikan'>
		<input type=hidden name='menu' value='list_penarikan' />
		<input type=date name='tgl' value='<?php echo isset($_GET['tgl']) ? $_GET['tgl'] : date("Y-m-d") ?>' onchange="submit()" />
		<input type=submit name='cari' value='CARI' />
		</form>
    <table class='table table-bordered'>
        <tr>
            <th>No</th>
            <th>STAFF</th>
            <th>ID Nasabah</th>
            <th>ID</th>
            <th>Group</th>
            <th>Center</th>
            <th>Nasabah</th>
            <th >Nominal</th>
        </tr>
        
        <?php 
        $tgl =date("Y-m-d");
        $total_penarikan=0;
        $penarikan = mysqli_query($con,"SELECT * FROM penarikan_simpanan left JOIN daftar_nasabah ON daftar_nasabah.`id_nasabah`=penarikan_simpanan.`id_anggota` join karyawan on karyawan.id_karyawan=penarikan_simpanan.id_karyawan where penarikan_simpanan.tgl_penarikan='$qtgl' and penarikan_simpanan.id_cabang='$id_cabang'  order by karyawan.nama_karyawan asc");
        while($simp = mysqli_fetch_array($penarikan))
        {
            $total_penarikan = $total_penarikan + $simp['nominal_penarikan'];
            $kel = $simp['id_detail_nasabah'];
            $kel = explode("/",$kel);
            $kel = $kel[2];
            ?>
        <tr>
            <td><?=$no++?>.</td>
            <td><?=$simp['nama_karyawan']?></td>
            <td><?=$simp['id_detail_nasabah']?></td>
            <td><?=$simp['id_anggota']?></td>

            <td> <?=$kel?></td>
            <td><?=$simp['no_center']?></td>
            
            <td><?=$simp['nama_nasabah']?></td>
            <td ><?=rupiah($simp['nominal_penarikan'])?></td>
        </tr>
            <?php
        }
        ?>
        <tr>
            <th colspan="7">Total Penarikan</th>
            <th><?=rupiah($total_penarikan)?></th>
        </tr>
    </table>


</div>