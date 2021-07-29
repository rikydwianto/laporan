<div class='content table-responsive'>
	<h2 class='page-header'>CENTER</h2>
	<i>Center otomatis dibuat ketika Staff membuat laporan</i><hr/>
	  <!-- Button to Open the Modal -->
  <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalku">
    <i class="fa fa-plus"></i> Center
  </button>  
  <a href='<?=$url.$menu?>center&ganti_staff' class="btn btn-success" >
    <i class="fa fa-plus"></i> Ganti Staff
  </a>
  <a href="<?=$url?>/export/center.php" class='btn btn-success'>
			<i class="fa fa-file-excel-o"></i> Export To Excel
		</a>
<br>
  <?php 
  if(isset($_GET['del']))
  {
  	$iddet= $_GET['iddet'];
  	$del = mysqli_query($con,"delete from center where id_center='$iddet'");
  	if($del){
  		pesan("Center Berhasil dihapus",'success');
  	}
  }

  if(isset($_POST['edit_center']))
  {
		$iddet= $_GET['iddet'];
		$jam = $_POST['jam'];
		$hari = $_POST['hari'];
		$staff = $_POST['staff'];
		$q= mysqli_query($con,"UPDATE `center` SET `jam_center` = '$jam',hari='$hari',id_karyawan='$staff' WHERE `id_center` = '$iddet';  ");
		
		if($q){
			alert("Berhasil");
		}else{
			echo "gagal";
		}
		
	}


  if(isset($_POST['pindah_staff']))
  {
  	$idlama = $_POST['staf_lama'];
  	$idbaru = $_POST['staf_baru'];
  	$query = mysqli_query($con,"UPDATE `anggota` SET `id_karyawan` = '$idbaru' WHERE `id_karyawan` = '$idlama';  ");
  	$query1 = mysqli_query($con,"UPDATE `laporan` SET `id_karyawan` = '$idbaru' WHERE `id_karyawan` = '$idlama';  ");
  	$query2 = mysqli_query($con,"UPDATE `center` SET `id_karyawan` = '$idbaru' WHERE `id_karyawan` = '$idlama';  ");
  	$query3 = mysqli_query($con,"UPDATE `group_user` SET `id_karyawan` = '$idbaru' WHERE `id_karyawan` = '$idlama';  ");
  	$query4 = mysqli_query($con,"UPDATE `cashflow` SET `id_karyawan` = '$idbaru' WHERE `id_karyawan` = '$idlama';  ");
  	$query4 = mysqli_query($con,"UPDATE `image` SET `id_karyawan` = '$idbaru' WHERE `id_karyawan` = '$idlama';  ");

  }

  
  if(isset($_GET['edit']))
  {
	  $iddet = $_GET['iddet'];
	  $cek_center = mysqli_query($con,"select * from center where id_center='$iddet'");
	  $cek_center = mysqli_fetch_array($cek_center);
  	?>
  		<div class="col-md-7">
		  <form method="post">
		  	<h3 class="page-header">EDIT CENTER <?=$cek_center['no_center']?></h3>
		  	<hr>
			 <table class='table'>
				<tr>
					<td>No Center</td>
					<td><input type="number" disabled class='form-control' name="center" value="<?=$cek_center['no_center']?>" id=""></td>
				</tr>
				<tr>
					<td>Status</td>
					<td><input type="text" disabled class='form-control'  value="<?=$cek_center['status_center']?>" id=""></td>
				</tr>
				<tr>
					<td>Staff</td>
					<td>
						<select name='staff' class='form-control form-select' required>
								<option value="">Silahkan Pilih Staff</option>
								<?php 
								$qk=mysqli_query($con,"select * from karyawan where id_cabang='$id_cabang' and status_karyawan='aktif' and id_jabatan=(select id_jabatan from jabatan where singkatan_jabatan='SL') order by nama_karyawan asc");
								while($cek_ka=mysqli_fetch_array($qk))
								{
									if($cek_ka['id_karyawan']==$cek_center['id_karyawan']){
										?>
										<option value='<?=$cek_ka['id_karyawan']?>' selected><?=$cek_ka['nama_karyawan']?></option>
										<?php
									}
									else{
										?>
										<option value='<?=$cek_ka['id_karyawan']?>'><?=$cek_ka['nama_karyawan']?></option>
										<?php
									}
									?>
									
									<?php
									
								}
								?>
							</select>

					</td>
				</tr>
				<tr>
					<td>JAM</td>
					<td><input type="text"  class='form-control' name="jam" value="<?=$cek_center['jam_center']?>" id=""></td>
				</tr>
				<tr>
					<td>HARI</td>
					<td>
						<select name='hari' class='form-control'>
						<?php $hari = hari();
							for($i=0;$i<count($hari);$i++){
								if(strtolower($hari[$i])==$cek_center['hari']){
									echo"<option value='".strtolower($hari[$i])."' selected >$hari[$i]</option>";
								}
								else{
									echo"<option value='".strtolower($hari[$i])."' >$hari[$i]</option>";
								}
								
							}
						?>
						</select>
					</td>
				</tr>
				<tr>
					<td></td>
					<td>
						<input type="submit" name='edit_center' class='btn btn-success' value="EDIT">
					</td>
				</tr>
			 </table>
		  	<br/>
		  	
		  </form>
	  	
	  </div>
  	<?php
  }


  if(isset($_GET['ganti_staff']))
  {
  	?>
  		<div class="col-md-6">
		  <form method="post">
		  	<h3 class="page-header">Pindahkan SEMUA CENTER  Ke STAFF BARU</h3>
		  	<small class="page-header">memindahkan center berarti memindahkan semua data laporan dan data center ke staff baru</small>
		  	<hr>
		  	<table>
		  		<tr>
		  			<td> Staff Lama</td>
		  		<td><select name='staf_lama' class='form-control form-select' required>
						<option value="">Silahkan Pilih Staff</option>
						<?php 
						$qk=mysqli_query($con,"select * from karyawan where id_cabang='$id_cabang' and status_karyawan='aktif' and id_jabatan=(select id_jabatan from jabatan where singkatan_jabatan='SL') order by nama_karyawan asc");
						while($cek_ka=mysqli_fetch_array($qk))
						{
							?>
							<option value='<?=$cek_ka['id_karyawan']?>'><?=$cek_ka['nama_karyawan']?></option>
							<?php
							
						}
						?>
					</select></td>

				</tr>
		  		<tr>
		  			<td> Staff BARU</td>
		  		<td><select name='staf_baru' class='form-control form-select' required>
						<option value="">Silahkan Pilih Staff</option>
						<?php 
						$qk=mysqli_query($con,"select * from karyawan where id_cabang='$id_cabang' and status_karyawan='aktif' and id_jabatan=(select id_jabatan from jabatan where singkatan_jabatan='SL') order by nama_karyawan asc");
						while($cek_ka=mysqli_fetch_array($qk))
						{
							?>
							<option value='<?=$cek_ka['id_karyawan']?>'><?=$cek_ka['nama_karyawan']?></option>
							<?php
							
						}
						?>
					</select></td></tr>
					<tr>
						<td></td>
						<td>
							
							<input type='submit' class='btn btn-danger' onclick="window.confirm('Apakah anda yakin memindahkan, semua laporan juga akan diganti oleh staff baru')" name='pindah_staff' value="PINDAHKAN CENTER"/>
						</td>
					</tr>
		  	</table>
		  	 
		  	

		  </form>
	  	
	  </div>
  	<?php
  }

  ?>
  <br>
  <br>
  <br>
	<table id='data_karyawan'>
		<thead>
			<tr>
				<!-- <th>NO</th> -->
				<th>CENTER</th>
				<th>ANGGOTA</th>
				<th>CLIENT</th>
				<th>HARI</th>
				<th>JAM</th>
				<th>DOA</th>
				<th>STATUS</th>
				<th>STAFF</th>
				<th>Lat,Lng</th>

				<th>#</th>
			</tr>
		</thead>
		<tbody>
		<?php 
		$q=mysqli_query($con,"select * from center where id_cabang='$id_cabang' order by no_center asc");
		while($center=mysqli_fetch_assoc($q))
		{
			$data=detail_karyawan($con,$center['id_karyawan']);
			?>
			<tr>
				<!-- <td><?=$no++;?></td> -->
				<td><?=$center['no_center'];?></td>
				<td><?=$center['member_center'];?></td>
				<td><?=$center['anggota_center'];?></td>
				<td><?=$center['hari'];?></td>
				<td><?=$center['jam_center'];?></td>
				<td><?=$center['doa_center'];?></td>
				<td><?=$center['status_center'];?></td>
				<td><?=$data['nama_karyawan'];?></td>
				<td>
				<?php if($center['latitude']!= null || $center['longitude'] !=NULL) : ?>
					<a href="<?=link_maps($center['latitude'],$center['longitude'])?>">Arahkan</a>
					<?php endif; ?>
				</td>

				<td>
				
					<a href="<?=$url.$menu?>center&del&iddet=<?=$center['id_center']?>" onclick="return window.confirm('Apakah yakin menghapus center ini')"> <i class='fa fa-times'></i> Hapus</a>
					<a href="<?=$url.$menu?>center&edit&iddet=<?=$center['id_center']?>"> <i class='fa fa-edit'></i> Edit</a>
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
