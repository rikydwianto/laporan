<?php 
$bln1 = ($_POST['bln']=="" ? date("n") : $_POST['bln'] );
$tahun = ($_POST['tahun']=="" ? date("Y") : $_POST['tahun'] );

?>
<div class="col-md-6" >
      <div class="panel panel-default ">


    <div class="panel-body post-body ">
        
        <h2 class='page-header'>Lihat Laporan</h2><hr/>
		<form method=post >
			pilih bulan 
			<select name='bln'>

				<?php
				$bulan_no=1;
				
				foreach($bulan as $bln){
					if($bulan_no==date("n") || $bulan_no==$bln1) $check="selected";
					else $check="";
					echo"<option value='".$bulan_no++."' $check > $bln</option>";
				}
				?>
			</select>
			<input type=text name='tahun' value='<?php echo date("Y") ?>'/>
			<input type=submit name='cari' value='Cari'/><hr/>
			<div class='table-responsive'>
				<table class="table ">
					<tr>
						<td colspan=4 style="text-align:center"><h3><?php echo bulan_indo($bln1)?> 
					</tr>
					</tr>
					<tr>
						<th>No</th>
						<th>Tanggal</th>
						<th>Keterangan</th>
						<th>%</th>
						<th>Status</th>
					</tr>
					<?php 
					$bln2 =sprintf("%02d",$bln1);

					$cq=mysqli_query($con,"select * from laporan where id_karyawan='$id_karyawan' and tgl_laporan LIKE '%$tahun-$bln2%' order by tgl_laporan desc");
					if(!mysqli_num_rows($cq)){
						echo "<tr>
							<td colspan=5><i>Belum ada laporan ".bulan_indo($bln1)."</i></td>
						</tr>";
					}
					else{
						$no=1;
						while($am=mysqli_fetch_array($cq)){
							$byr=mysqli_query($con,"select * from bayar where id_laporan='$am[id_laporan]'");
							$byr=mysqli_fetch_array($byr);
						?>
						<tr>
							<td><?php echo $no++ ?></td>
							<td>
							<?php
							if($am['status_laporan']=='sukses'){
								?>
								<a href="<?php echo"$url$menu"."laporan&id_laporan=$am[id_laporan]" ?>"><?php echo format_hari_tanggal($am['tgl_laporan'] )?></a>
							<?php
							}
							else{
								?>
								<?php echo format_hari_tanggal($am['tgl_laporan'] )?>
								<?php
							}
							?>
							</td>
							<td><?php echo $am['keterangan_laporan'] ?></td>
							<td><?php echo $byr['total_agt'] ?>|<?php echo $byr['total_bayar'] ?>| <?php echo round(($byr['total_bayar']/$byr['total_agt'])*100) ?> %</td>
							<td>
							<?php
							if($am['status_laporan']=='pending'){
								?>
								<a href="<?php echo "$url$menu"."tmb_laporan&id_laporan=".$am['id_laporan']?>"><?php echo $am['status_laporan'] ?></a>
							
								<?php
							}
							else{
								echo"<i>dikonfirmasi</i>";
							}
							?>
							
							</td>

						</tr>
						<?php
						}


					}
					?>
					
					
				</table>
			</div>
		</form>
		</div>

	</div>
	  
	  
</div>
<div class="col-md-6" >
      <div class="panel panel-default ">

<?php 
if(isset($_GET['id_laporan']))
{
	$id_det=$_GET['id_laporan'];
	$q1="and detail_laporan.id_laporan='$id_det'";
	$q2="and id_laporan='$id_det'";
}
else 
{
	$q1="and laporan.tgl_laporan=curdate()";
	$q2="and tgl_laporan=curdate()";
}
	?>
		<div class="panel-body post-body " style="overflow: auto;">
			<h2 class='page-header'>Detail Laporan Center</h2><hr/>
			<?php 
			$qq=mysqli_query($con,"select * from laporan where id_karyawan='$id_karyawan' $q2");
			if(!mysqli_num_rows($qq)){
				//echo"tidak ada data!";
			}
			else{
				$ket=mysqli_fetch_array($qq);
				?>
				Nama : <?php echo $nama_karyawan ?> <br/>
				Tanggal : <?php echo format_hari_tanggal($ket['tgl_laporan']) ?> <br/>
				Keterangan : <?php echo ($ket['keterangan_laporan']) ?> <br/>

				<?php
			}
			$q=mysqli_query($con,"select * from laporan,detail_laporan where laporan.id_laporan=detail_laporan.id_laporan and laporan.id_karyawan='$id_karyawan' $q1 ");
		if(mysqli_num_rows($q)){
			
		?>
		<div class="table-responsive">
			<table class="table table-bordered table-responsive">
				<tr>
					<td>NO</td>
					<td>CENTER</td>
					<td>STATUS</td>
					<td>DOA</td>
					<td>ANGGOTA</td>
					<td>BAYAR</td>
					<td>TIDAK BAYAR</td>
					<td>%</td>
				</tr>
				<?php 
				$no=1;
				$hitung_agt=0;
				$hitung_bayar=0;
				$hitung_tdk_bayar=0;
				while($ambil=mysqli_fetch_array($q))
				{
				?>
					<tr>
						<td><?php echo $no++ ?></td>
						<td><?php echo $ambil['no_center']?> </td>
						<td><?php echo $ambil['status']?></td>
						<td><?php echo ($ambil['doa']=="t" ? "T" : "Y")?></td>
						<td><?php echo $ambil['total_agt']?></td>
						<td><?php echo $ambil['total_bayar']?> </td>
						<td><?php echo $ambil['total_tidak_bayar']?></td>
						<td><?php echo round((($ambil['total_bayar']/$ambil['total_agt'])*100),2)?>%</td>
					</tr>
				<?php
					$hitung_agt = $hitung_agt + $ambil['total_agt'];
					$hitung_bayar = $hitung_bayar + $ambil['total_bayar'];
					$hitung_tdk_bayar = $hitung_tdk_bayar + $ambil['total_tidak_bayar'];
				}
				?>
						
				<tr>
					<th colspan=4>Total</th>
					<th colspan=1><?php echo $hitung_agt ?></th>
					<th ><?php echo $hitung_bayar ?></th>
					<th>
						<?php echo  $hitung_tdk_bayar ?>
					</th>
					<th><?php echo round(($hitung_bayar/$hitung_agt)*100,2)?>%</th>
				</tr>
				<tr>
					<th colspan=8 style="text-align:center">
						Prosentase pembayaran <?php echo round(($hitung_bayar/$hitung_agt)*100,2)?>% <br/>
						<?php 
						if($cek_laporan['status_laporan']=='pending'){
							echo"<i>laporan belum di konfirmasi, silahkan selesaikan laporan</i>";
							?>
							<a href="<?php echo ("$url$menu"."tmb_laporan&id_laporan=".$cek_laporan['id_laporan']);?>" class=" ">tambah</a>
							<?php
						}
						?>
					</th>

				</tr>
			</table>
		</div>
		<?php
		}
		else{
			echo"<i>anda belum buat laporan hari ini, silah kan dibuat dahulu</i>";
			echo"<a href='$url$menu".""."tmb_laporan'>disini</a>";
			
		} 
		?>
		
		</div>

	</div>
	  
	  
</div>


<div class="col-md-12 col-lg-12 "   >
      <div class="panel panel-default ">

    <div class="panel-body post-body  "  >
        <h2>Rekap pembayaran anggota</h2><hr/>
		<div class='table-responsive'>
		<table class='table table-bordered' >
			<?php
			$tahun = date('Y'); //Mengambil tahun saat ini
			$bulan = date('n'); //Mengambil bulan saat ini
			$bulan = ($_POST['bln']=="" ? date("n") : $_POST['bln'] );
			$tahun = ($_POST['tahun']=="" ? date("Y") : $_POST['tahun'] );
			
			$tanggal = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);
			echo"<tr>";
			echo"<th colspan=$tanggal style='text-align:center'>BULAN ".strtoupper(bulan_indo($bulan))."</th>";
			echo"</tr>";
			echo"<tr><th>TGL</th>";
			for ($i=1; $i < $tanggal+1; $i++) { 
			  ?>
			  
				<th><?php echo $i?></th>
			  <?php
			}
			 
			?>
		  </tr>
		  <?php 
		  $qcek=mysqli_query($con,"select * from rekap_center where id_karyawan='$id_karyawan' order by no_center asc");
		  $bulan1=sprintf("%02d",$bulan);
		  $no=1;
		  $hitung_total_bayar=0;
		  while($bawah=mysqli_fetch_array($qcek))
		  {
			  echo"<tr>";
			  echo"<td>$bawah[no_center]</td>";
			  for ($i=1; $i < $tanggal+1; $i++) { 
				   $i=sprintf("%02d",$i);
				  $cari_cek=mysqli_query($con,"select * from laporan,detail_laporan where laporan.id_laporan=detail_laporan.id_laporan and laporan.tgl_laporan='$tahun-$bulan1-$i' and detail_laporan.no_center='$bawah[no_center]'");
				  $cek=mysqli_fetch_array($cari_cek);
				  $btgl = $cek['tgl_laporan'];
				  $btgl = explode("-",$btgl);
				  $btgl=$btgl[2];
				  if($cek['status']=='hijau') $warna='rgb(102, 245, 73)';
				  else if($cek['status']=='kuning') $warna='rgb(231, 245, 73)';
				  else if($cek['status']=='merah') $warna='rgb(245, 73, 73)';
				  else if($cek['status']=='hitam') $warna='rgb(168, 168, 162)';
					
					$hitung_total_bayar = $hitung_total_bayar + $cek['total_bayar'];
					if($i==$btgl)
					{
						
						echo"<td style='text-align:center;;background-color: $warna'><small>$cek[total_bayar]</small></td>";
					}
					else{
						echo "<td>&nbsp;</td>";
					}
			  }
		  echo"</tr>";
		  $no++;


		  }
		  ?>
		  
		</table>
		</div>
	</div>

	</div>
	  
	  
</div>
