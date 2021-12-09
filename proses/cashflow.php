<div class="row">
	<h3 class="page-header">CASHFLOW STAFF LAPANG !</h3>
	<a href="<?=$url.$menu?>cashflow&disburse" class="btn btn-danger"> Disburse</a>
	<a href="<?=$url.$menu?>cashflow" class="btn btn-info"> Anggota & Pemb Lain</a>
	<?php 
	if(isset($_GET['disburse'])){
		?>
		<?php 
			if(isset($_POST['simpan_disburse']))
			{
				$idk=$_POST['idk'];
				$tahun=$_POST['tahun'];
				$bulan=$_POST['bulanan'];
				$minggu=$_POST['mingguan'];
				

				for ($x = 0; $x < count($idk); $x++) {
					if($tahun[$x]!=null || $bulan[$x]!=null){
						$query_cari=mysqli_query($con,"select * from target_disburse where id_karyawan='$idk[$x]' ");
						
						if(mysqli_num_rows($query_cari))
						{
							$edit = mysqli_fetch_array($query_cari);
							mysqli_query($con,"
								UPDATE `target_disburse` SET `target_tahun` = '$tahun[$x]', `target_bulan` = '$bulan[$x]', `target_minggu` = '$minggu[$x]' WHERE `id` = '$edit[id]'; 
								");
								echo mysqli_error($con);
						}
						else
						{
							$editt = "
							INSERT INTO `komida_backup`.`target_disburse` (`target_tahun`, `target_bulan`, `target_minggu`, `id_karyawan`, `id_cabang`) VALUES ('$tahun[$x]', '$bulan[$x]', '$minggu[$x]', '$idk[$x]', '$id_cabang'); 						";
							mysqli_query($con,$editt);				
						}
					}
					
					
				}
				
				// pindah("$url$menu".'cashflow');
			}
			?>
		<form action="" method="post">
		<table class="table">
				<input type='hidden' name='menu' class="form-control" value='cashflow'></input>
				<tr>
					<th>NO</th>
					<th>STAFF</th>
					<th>TAHUN</th>
					<th>BULAN <br/> TAHUN/12</th>
					<th>MINGGU  <br/> BULAN/4</th>

				</tr>
				<?php 
				$qk=mysqli_query($con,"select * from karyawan where id_cabang='$id_cabang' and status_karyawan='aktif' and id_jabatan=(select id_jabatan from jabatan where singkatan_jabatan='SL') order by nama_karyawan asc");
				while($cek_ka=mysqli_fetch_array($qk))
				{
					$cekc=mysqli_query($con,"select * from target_disburse where id_karyawan='$cek_ka[id_karyawan]' and id_cabang='$id_cabang'");
					$cash = mysqli_fetch_array($cekc);
					?>
					<tr>
						<td><?=$no++?></td>
						<td><?=$cek_ka['nama_karyawan']?></td>
						<td>
							<input type='number' onkeyup="bulan_minggu('<?=$cek_ka['id_karyawan']?>')" class="form-control" style="" name='tahun[]' id='tahun<?=$cek_ka['id_karyawan']?>' value='<?=$cash['target_tahun']?>'></input>
							<input type='hidden' name='idk[]' class="form-control" value='<?=$cek_ka['id_karyawan']?>'></input>

						</td>
						<td>
							<input  type='number' class="form-control" style="" name='bulanan[]' id='bulanan<?=$cek_ka['id_karyawan']?>' value='<?=$cash['target_bulan']?>'></input>

						</td>
						<td>
							<input  type='number' class="form-control"  style="" name='mingguan[]' id='mingguan<?=$cek_ka['id_karyawan']?>' value='<?=$cash['target_minggu']?>'></input>

						</td>
					
					</tr>
					<?php
					
				}
				?>
				<tr>
					<td colspan="4"></td>
					<td >
						
						<input type='submit' name='simpan_disburse' class="btn btn-info " value='SIMPAN'></input>
					</td>
				</tr>
			</table>
		</form>
		<?php
	}
	else{
		?>
		<form method="post">
			<?php 
			if(isset($_POST['simpan_cashflow']))
			{
				$idk=$_POST['idk'];
				$masuk=$_POST['masuk'];
				$keluar=$_POST['keluar'];
				$tgl=$_POST['tgl'];
				$ppd=$_POST['ppd'];
				$psa=$_POST['psa'];
				$prr=$_POST['prr'];
				$arta=$_POST['arta'];
				$pmb=$_POST['pmb'];


				for ($x = 0; $x < count($idk); $x++) {
					$query_cari=mysqli_query($con,"select * from cashflow where id_karyawan='$idk[$x]' ");
					$nett = $masuk[$x] - $keluar[$x];
					if(mysqli_num_rows($query_cari))
					{
						$edit = mysqli_fetch_array($query_cari);
						mysqli_query($con,"
							UPDATE `cashflow` SET `tahun_cashflow` = YEAR(CURDATE()), `cashflow_masuk` = '$masuk[$x]', `cashflow_keluar` = '$keluar[$x]', `net_cashflow` = '$nett', `psa` = '$psa[$x]', `ppd` = '$ppd[$x]', `prr` = '$prr[$x]', `arta` = '$arta[$x]', `pmb` = '$pmb[$x]' WHERE `id_cashflow` = $edit[id_cashflow]; 
							");

					}
					else
					{
						$editt = "
					INSERT INTO `cashflow` (`id_cashflow`, `id_karyawan`, `tahun_cashflow`, `cashflow_masuk`, `cashflow_keluar`, `net_cashflow`, `psa`, `prr`, `ppd`, `arta`, `pmb`, `id_cabang`) VALUES (NULL, '$idk[$x]', YEAR(CURDATE()), '$masuk[$x]', '$keluar[$x]', '$nett', '$psa[$x]', '$prr[$x]', '$ppd[$x]', '$arta[$x]', '$pmb[$x]', '$id_cabang'); 
						";
						mysqli_query($con,$editt);				
					}
					
				}
				
				pindah("$url$menu".'cashflow');
			}
			?>

			isi 0 jika tidak ada 
			<br/>
			<table class="table">
				<input type='hidden' name='menu' class="form-control" value='cashflow'></input>
				<tr>
					<th>NO</th>
					<th>Staff</th>
					<th>Anggota Masuk</th>
					<th>Anggota Keluar</th>
					<th>NETT</th>

					<th>PSA</th>
					<th>PMB</th>
					<th>PPD</th>
					<th>PRR</th>
					<th>ARTA</th>
				</tr>
				<?php 
				$qk=mysqli_query($con,"select * from karyawan where id_cabang='$id_cabang' and status_karyawan='aktif' and id_jabatan=(select id_jabatan from jabatan where singkatan_jabatan='SL') order by nama_karyawan asc");
				while($cek_ka=mysqli_fetch_array($qk))
				{
					$cekc=mysqli_query($con,"select * from cashflow where id_karyawan='$cek_ka[id_karyawan]'");
					$cash = mysqli_fetch_array($cekc);
					?>
					<tr>
						<td><?=$no++?></td>
						<td><?=$cek_ka['nama_karyawan']?></td>
						<td>
							<input type='number'  class="form-control" style="width: 100px" name='masuk[]' id='masuk<?=$cek_ka['id_karyawan']?>' onchange="ganti_net('<?=$cek_ka['id_karyawan']?>')" value='<?=$cash['cashflow_masuk']?>'></input>
							<input type='hidden' name='idk[]' class="form-control" value='<?=$cek_ka['id_karyawan']?>'></input>

						</td>
						<td>
							<input  type='number' class="form-control" style="width: 100px" name='keluar[]' id='keluar<?=$cek_ka['id_karyawan']?>' onchange="ganti_net('<?=$cek_ka['id_karyawan']?>')" value='<?=$cash['cashflow_keluar']?>'></input>

						</td>
						<td>
							<input  type='number' class="form-control" readonly style="width: 100px" name='nett[]' id='nett<?=$cek_ka['id_karyawan']?>' value='<?=$cash['net_cashflow']?>'></input>

						</td>
						<td>
							<input  type='number' class="form-control" style="width: 100px" name='psa[]'  value='<?=$cash['psa']?>'></input>
						</td>
						<td>
							<input  type='number' class="form-control" style="width: 100px" name='pmb[]'  value='<?=$cash['pmb']?>'></input>
						</td>
						<td>
							<input  type='number' class="form-control" style="width: 100px" name='ppd[]'  value='<?=$cash['ppd']?>'></input>

						</td>
						<td>
							<input  type='number' class="form-control" style="width: 100px" name='prr[]'  value='<?=$cash['prr']?>'></input>

						</td>
						<td>
							<input  type='number' class="form-control" style="width: 100px" name='arta[]'  value='<?=$cash['arta']?>'></input>

						</td>
					</tr>
					<?php
					
				}
				?>
				<tr>
					<td colspan="4"></td>
					<td >
						
						<input type='submit' name='simpan_cashflow' class="btn btn-info " value='SIMPAN'></input>
					</td>
				</tr>
			</table>
		</form>
		<?php
	}
	?>
</div>

<script>
	function bulan_minggu(id){
		let tahun = $("#tahun"+id).val();
		let bulan = tahun/12;
		
		// $("#tahun"+id).val(dollarUS.format(tahun));
		$("#bulanan"+id).val(Math.ceil(bulan));
		
		$("#mingguan"+id).val(Math.ceil(bulan/4));
	}
</script>