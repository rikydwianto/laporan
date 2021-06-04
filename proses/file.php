<div class='content table-responsive'>
	<h2 class='page-header'>FILE </h2>
	<i>File ini diupload dari Telegram User</i><hr/>
	  <!-- Button to Open the Modal -->


	<table id='data_center'>
		<thead>
			<tr>
				<th>NO</th>
				<th>Cabang</th>
				<th>Staff</th>
				<th>Thubmnail</th>
				<th>Kategori</th>
				<th>Deskripsi</th>
				<th>Tanggal</th>

				<th>#</th>
			</tr>
		</thead>
		<tbody>
		<?php 
		$q=mysqli_query($con,"select * from image where id_cabang='$id_cabang' order by id_images desc");
		while($images=mysqli_fetch_assoc($q))
		{
			$detail = detail_karyawan($con,$images['id_karyawan']);
            $url_gambar = $url."export/file.php?gambar=".$images['url_images'];
            
        ?>
			<tr>
				<td><?=$no++;?></td>
				<td><?=$detail['nama_cabang'];?></td>
				<td><?=$detail['nama_karyawan'];?></td>
				<td><img src="<?=$url?>assets/img/gambar/<?=$images['url_images'];?>" alt="" width="50px" height="50px"></td>
				<td><?=$images['kategori_images'];?></td>
				<td><?=$images['dekripsi_images'];?></td>
				<td><?=$images['tanggal_images'];?></td>

				<td>
					<a href="#" onclick='pop_up("<?=$url_gambar ?>")' target="_parent" class='btn btn-success ' > <i class='fa fa-eye'></i> </a>
					<a href="<?=$url?>/assets/img/gambar/<?=$images['url_images'] ?>" class='btn btn-info' > <i class='fa fa-download'></i> </a>


				</td>
			</tr>
			<?php
		}
		?>
		</tbody>
	</table>
</div>
<!-- Button trigger modal -->


<script language="javascript">
function pop_up(url){
 window.open(url, '', "width=1200,height=1200,toolbar=NO,scrollbars=yes,resizable=yes");
}
</script>



