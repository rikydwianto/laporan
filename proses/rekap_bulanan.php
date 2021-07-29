
<?php
$bln1 = ($_GET['bln']=="" ? date("n") : $_GET['bln'] );
$tahun = ($_GET['tahun']=="" ? date("Y") : $_GET['tahun'] );
$tanggal = cal_days_in_month(CAL_GREGORIAN, $bln1, $tahun);
?>
<div class="table-responsive" style=";">
	<div class="panel-body post-body " >
		<h3 class="pager-header">
			REKAP LAPORAN BAYAR
		</h3>
		<form method='get' action='<?php echo $url.$menu ?>laporan_bulanan'>
		<input type=hidden name='menu' value='laporan_bulanan' />
		 
			<select name='bln'>

				<?php
				$bulan_no=1;

				foreach($bulan as $bln){
					if($bulan_no==date("n") || sprintf("%01d",$bulan_no)==$bln1) $check="selected";
					else $check="";
					echo"<option value='".$bulan_no++."' $check > $bln</option>";
				}
				?>
			</select>
			<input type=text name='tahun' value='<?php echo date("Y") ?>'/>
		<input type=submit name='cari' value='CARI' />
		</form>
	
	<table class='table'>
		<tr>					
			<th rowspan=3 style="vertical-align: middle;">NO</th>
			<th rowspan=3 style="vertical-align: middle;">NAMA</th>
			<th colspan="<?php echo $tanggal ?>" style="text-align:center">HANYA LAPORAN TOTAL BAYAR <?php echo bulan_indo($bln1)." - ". $tahun;?></th>
		</tr>
		<tr>					
			<?php 
			
			for ($i=1; $i < $tanggal+1; $i++) { 
			echo"<td class='kotak_table'>$i</td>";
			}
			?>
		</tr>
		<tr>					
			<?php 
			
			for ($i=1; $i < $tanggal+1; $i++) { 
				$cek_tgl  = "$tahun-$bln1-$i";
				$cek_tgl = hari_biasa($cek_tgl);
				$hari = explode("-", $cek_tgl);
				$hari = $hari[0]; 
				if($hari=="Jumat" || $hari=="Sabtu" || $hari=="Minggu" )
					echo"<td class='hari merah'>$hari</td>";
				else
					echo"<td class='hari'>$hari</td>";
			}
			?>
		</tr>
		<?php 
		
		$cek_ka=mysqli_query($con,"SELECT * FROM karyawan,jabatan,cabang where karyawan.id_jabatan=jabatan.id_jabatan and karyawan.id_cabang=cabang.id_cabang and karyawan.id_cabang='$cabang' and jabatan.singkatan_jabatan='SL' and karyawan.status_karyawan='aktif' order by karyawan.nama_karyawan asc");
		$hitung_agt = 0; 
		$hitung_bayar = 0; 
		$total_bayar = 0;
		$hitung_tdk_bayar= 0; 
		$hitung_perhari = 0;
		while($tampil=mysqli_fetch_array($cek_ka)){
		?>
		<tr>
			<td><?php echo $no++?></td>
			<td><?php echo $tampil['nama_karyawan']?></td>
			<?php 
			$bln12=sprintf("%02d",$bln1);
			for ($i=1; $i < $tanggal+1; $i++) { 
			   $i=sprintf("%02d",$i);
			  $cari_cek=mysqli_query($con,"select * from rekap_bayar where id_karyawan='$tampil[id_karyawan]' and tgl_laporan='$tahun-$bln12-$i'");
			  if(mysqli_num_rows($cari_cek))
			  {
				  $buka = mysqli_fetch_array($cari_cek);
				  $cari_tgl=$buka['tgl_laporan'];
				  $cari_tgl = explode("-",$cari_tgl);
				  $cari_tgl = $cari_tgl[2];
				  if($cari_tgl==$i)
				  {
				  	$isi = $buka['persen'];
				  	$isi = ($isi=='NULL' ? 0 : $isi);
				  	$hitung_bayar = $isi + $hitung_bayar;
				  	$hitung_perhari = $hitung_perhari + $isi;
					  echo"<td style='text-align:center;'><small>$isi %</small></td>";
				  }

			  }
			  else{
				  echo "<td>&nbsp;</td>";
			  }
			 
		  }
		   ?>
			  <td>
			  	<?=$hitung_bayar?>
			  </td>
			  <?php
			  $total_bayar = $hitung_bayar + $total_bayar;	
			  $hitung_bayar=0;
			?>

		</tr>
		<?php
			
		}
		?>
		<tr>
			<th colspan="<?php echo $tanggal +2 ?>" style="text-align:center">TOTAL</th>
			<th>
				<?=$total_bayar?>
			</th>
		</tr>
	</table>
	</div>
</div>
			
			