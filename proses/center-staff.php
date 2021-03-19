<div class='content table-responsive'>
	<h2 class='page-header'>CENTER</h2>
	<i>Center otomatis dibuat ketika Staff membuat laporan</i><hr/>
	  <!-- Button to Open the Modal -->
  <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalku">
    <i class="fa fa-plus"></i> Center
  </button>


  <?php 
  if(isset($_GET['del']))
  {
  	$iddet= $_GET['iddet'];
  	$del = mysqli_query($con,"delete from center where id_center='$iddet'");
  	if($del){
  		pesan("Center Berhasil dihapus",'success');
  	}
  }

  ?>

	<table id='data_center'>
		<thead>
			<tr>
				<th>NO</th>
				<th>CENTER</th>
				<th>HARI</th>
				<th>ANGGOTA</th>
				<th>BAYAR</th>
				<th>DOA</th>
				<th>STATUS</th>

				<th>#</th>
			</tr>
		</thead>
		<tbody>
		<?php 
		$q=mysqli_query($con,"select * from center where id_cabang='$id_cabang' and id_karyawan='$id_karyawan' order by no_center asc");
		while($center=mysqli_fetch_assoc($q))
		{
			
			?>
			<tr>
				<td><?=$no++;?></td>
				<td><?=$center['no_center'];?></td>
				<td><?=$center['hari'];?></td>
				<td><?=$center['anggota_center'];?></td>
				<td><?=$center['center_bayar'];?></td>
				<td><?=$center['doa_center'];?></td>
				<td><?=$center['status_center'];?></td>

				<td>
					<a href="<?=$url.$menu?>center-staff&del&iddet=<?=$center['id_center']?>" onclick="return window.confirm('Apakah yakin menghapus center ini')"> <i class='fa fa-times'></i> Hapus</a>


				</td>
			</tr>
			<?php
		}
		?>
		</tbody>
	</table>
</div>
<!-- Button trigger modal -->


  <!-- The Modal TAMBAH -->
  <div class="modal fade" id="modalku">
    <div class="modal-dialog">
      <div class="modal-content">
      
        <!-- Ini adalah Bagian Header Modal -->
        <div class="modal-header">
          <h4 class="modal-title">TAMBAH CENTER</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Ini adalah Bagian Body Modal -->
        <div class="modal-body">
          <i>Center otomatis dibuat ketika Staff membuat laporan</i><hr/>


        </div>
        
        <!-- Ini adalah Bagian Footer Modal -->
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
        
      </div>
    </div>
  </div>
