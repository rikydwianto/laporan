<div class="row">
	<h3 class="page-header">CASHFLOW STAFF LAPANG</h3>
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


			for ($x = 0; $x < count($idk); $x++) {
				$query_cari=mysqli_query($con,"select * from cashflow where id_karyawan='$idk[$x]' ");
				$nett = $masuk[$x] - $keluar[$x];
				if(mysqli_num_rows($query_cari))
				{
					$edit = mysqli_fetch_array($query_cari);
					mysqli_query($con,"
						UPDATE `cashflow` SET `tahun_cashflow` = YEAR(CURDATE()), `cashflow_masuk` = '$masuk[$x]', `cashflow_keluar` = '$keluar[$x]', `net_cashflow` = '$nett', `psa` = '$psa[$x]', `prr` = '$prr[$x]', `arta` = '$arta[$x]' WHERE `id_cashflow` = $edit[id_cashflow]; 
						");

				}
				else
				{
					$editt = "
				INSERT INTO `cashflow` (`id_cashflow`, `id_karyawan`, `tahun_cashflow`, `cashflow_masuk`, `cashflow_keluar`, `net_cashflow`, `psa`, `prr`, `ppd`, `arta`, `id_cabang`) VALUES (NULL, '$idk[$x]', YEAR(CURDATE()), '$masuk[$x]', '$keluar[$x]', '$nett', '$psa[$x]', '$prr[$x]', '$ppd[$x]', '$arta[$x]', '$id_cabang'); 
					";
					mysqli_query($con,$editt);				
				}
				
			}
			
			pindah("$url$menu".'cashflow');
		}
		?>

		isi 0 jika tidak ada 
		<table class="table">
			<input type='hidden' name='menu' class="form-control" value='cashflow'></input>
			<tr>
				<th>NO</th>
				<th>Staff</th>
				<th>Anggota Masuk</th>
				<th>Anggota Keluar</th>
				<th>NETT</th>

				<th>PSA</th>
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
						<input type='number' class="form-control" style="width: 100px" name='masuk[]' id='masuk<?=$cek_ka['id_karyawan']?>' value='<?=$cash['cashflow_masuk']?>'></input>
						<input type='hidden' name='idk[]' class="form-control" value='<?=$cek_ka['id_karyawan']?>'></input>

					</td>
					<td>
						<input  type='number' class="form-control" style="width: 100px" name='keluar[]' id='keluar<?=$cek_ka['id_karyawan']?>' onkeyup="ganti_net('<?=$cek_ka['id_karyawan']?>')" value='<?=$cash['cashflow_keluar']?>'></input>

					</td>
					<td>
						<input  type='number' class="form-control" readonly style="width: 100px" name='nett[]' id='nett<?=$cek_ka['id_karyawan']?>' value='<?=$cash['net_cashflow']?>'></input>

					</td>
					<td>
						<input  type='number' class="form-control" style="width: 100px" name='psa[]'  value='<?=$cash['psa']?>'></input>
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
</div>