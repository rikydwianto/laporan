<div class="row">
		<h3 class="page-header">REKAP LAPORAN HARIAN</h3>
		<form>
			<div>
				FILTER
				<?php 
				if($su!='y'){
					$tamb="where id_cabang='$id_cabang' ";
				}
				?>
					<select name='cabang'  class="btn" aria-label="Default select example "id='jabatan'>
						<option value=''> -- Silahkan CABANG --</option>
						<?php 
						$jab = mysqli_query($con,"select * from cabang $tamb");
						while($jab1=mysqli_fetch_assoc($jab)){
							if($jab1['id_cabang']==$id_cabang)
								$select = 'selected';
							else $select='';
							if($_GET['cabang']==$jab1['id_cabang'])
								$select='selected';
							else $select='';
							
							echo "<option value='$jab1[id_cabang]' $select>".strtoupper($jab1['nama_cabang'])."</option>";
							
						}
						?>
					  </select>
					
				<input type="hidden" name='menu' value='rekap_bayar'/>
				<input type="date" name='tglawal' value="<?=(isset($_GET['tglawal']) ?  $_GET['tglawal'] : date("Y-m-d",(strtotime ( '-4 day' , strtotime ( date("Y-m-d")) ) )) )?>" class=""/>
				<input type="date" name='tglakhir' value="<?=(isset($_GET['tglakhir']) ?  $_GET['tglakhir'] : date("Y-m-d"))?>" class=""/>
				<input type='submit' class="btn btn-info" name='cari' value='FILTER'/>
			</div>
			
		</form>
		<?php 
		if(isset($_GET['cari']))
		{
			error_reporting(0);
			$id_filter = $_GET['cabang'];
			$tglawal = $_GET['tglawal'];
			$tglakhir = $_GET['tglakhir'];
			echo"Laporan <br>". format_hari_tanggal($tglawal). " - ". format_hari_tanggal($tglakhir) .'<br>';
			$data = new Hitung();
			$rekapp= $data->rekap_laporan($con,$id_cabang,$tglawal,$tglakhir,$su,$id_filter);
			// echo json_encode(($rekapp));
			//ANGGOTA MASUK
			
			$qc="SELECT sum(anggota.anggota_masuk) as masuk,
			sum(anggota.anggota_keluar) as keluar,
			sum(anggota.net_anggota) as nett,
			sum(anggota.psa) as psa,
			sum(anggota.ppd) as ppd,
			sum(anggota.prr) as prr,
			sum(anggota.arta) as arta,
			sum(anggota.pmb) as pmb FROM `anggota`
			where
			 anggota.tgl_anggota >= '$tglawal' and anggota.tgl_anggota <= '$tglakhir' and anggota.id_cabang='$id_cabang'
			";
			$upk = mysqli_query($con,$qc);
			$upk = mysqli_fetch_array($upk);
			$am = $upk['masuk'];
			$ak = $upk['keluar'];
			$nett = $am - $ak;
			$center_kosong = mysqli_query($con,"select count(*) as total_kosong from center_kosong where id_cabang='$id_cabang' and tgl_transaksi>='$tglawal' and tgl_transaksi<='$tglakhir'");
			$center_kosong = mysqli_fetch_array($center_kosong)['total_kosong'];


			$qpin = mysqli_query($con,"SELECT id_karyawan,
			SUM(CASE WHEN (DATEDIFF('$tgl1', tgl_cair)) >=0 AND (DATEDIFF('$tgl1', tgl_cair)) <=2 THEN 1 ELSE 0 END) AS tiga_hari,
			SUM(CASE WHEN (DATEDIFF('$tgl1', tgl_cair)) >2 AND (DATEDIFF('$tgl1', tgl_cair)) <=14 THEN 1 ELSE 0 END) AS normal,
			SUM(CASE WHEN (DATEDIFF('$tgl1', tgl_cair)) >14  THEN 1 ELSE 0 END) AS kurang_normal,
			COUNT(*) as total
				 FROM pinjaman WHERE monitoring='belum' and id_cabang='$id_cabang' and input_mtr='sudah' GROUP BY id_cabang ");
			$mon = mysqli_fetch_array($qpin);
			$total_monitoring = $mon['total'];
			$par = mysqli_query($con,"SELECT  tgl_input,count(*) as hitung, sum(sisa_saldo) as total_par FROM deliquency where id_cabang='$id_cabang' and tgl_input='$tglakhir' group by tgl_input order by tgl_input desc");
			$par = mysqli_fetch_array($par);

			foreach ($rekapp as $key => $value) {
				
					$cab = $data->cek_cabang($con,$value['id_cabang']);
				?>
					<div class="col-md-6">
						<table class='table table-bordered'>
							<tr>
								<th colspan="3" class='text-center'>
									<?php echo $tglawal.' s/d '. $tglakhir?>
								</th>
								
							</tr><tr>
								<td><b><?=strtoupper($cab['nama_cabang'])?></b></td>
								<td>Total</td>
								<td>Persen</td>
							</tr>
							<tr>
								<td>- Center Doa</td>
								<td><?=$value['doa']?></td>
								<td><?=round(($value['doa']/$value['hitung_center'])*100)?>%</td>
							</tr>
							<tr>
								<td>- Center Tidak Doa</td>
								<td><?=$value['tidak_doa']?></td>
								<td><?=round(($value['tidak_doa']/$value['hitung_center'])*100)?>%</td>
							</tr>
							<tr>
								<td>Center Hijau</td>
								<td><?=$value['hijau']?></td>
								<td><?=round(($value['hijau']/$value['hitung_center'])*100)?>%</td>
							</tr>
							<tr>
								<td>Center Kuning</td>
								<td><?=$value['kuning']?></td>
								<td><?=round(($value['kuning']/$value['hitung_center'])*100)?>%</td>
							</tr>
							<tr>
								<td>Center Merah</td>
								<td><?=$value['merah']?></td>
								<td><?=round(($value['merah']/$value['hitung_center'])*100)?>%</td>
							</tr>
							<tr>
								<td>Center Hitam</td>
								<td><?=$value['hitam']?></td>
								<td><?=round(($value['hitam']/$value['hitung_center'])*100)?>%</td>
							</tr>
							

							<!-- DOORTODOR -->
							<tr>
								<td>- Kumpulan</td>
								<td><?=$value['tidak']?></td>
								<td><?=round(($value['tidak']/$value['hitung_center'])*100)?>%</td>
							</tr>
							<tr>
								<td>- Setengah Kumpul</td>
								<td><?=$value['ragu']?></td>
								<td><?=round(($value['ragu']/$value['hitung_center'])*100)?>%</td>
							</tr>
							<tr>
								<td>- Door To Door</td>
								<td><?=$value['iya']?></td>
								<td><?=round(($value['iya']/$value['hitung_center'])*100)?>%</td>
							</tr>



							<tr>
								<td>ANGGOTA BAYAR</td>
								<td><?=$value['anggota_bayar']?></td>
								<td><?=round(($value['anggota_bayar']/$value['anggota'])*100)?>%</td>
							</tr>
							<tr>
								<td>TIDAK BAYAR</td>
								<td><?=$agt = $value['anggota']-$value['anggota_bayar']?></td>
								<td><?=round(($agt/$value['anggota'])*100)?>%</td>
							</tr>
							<tr>
								<td>ANGGOTA MASUK - KELUAR</td>
								<td><?=$am?> - <?=$ak?> = <?=$nett?></td>
								<td></td>
							</tr>

							<tr>
								<td><b>CENTER KOSONG</b></td>
								<td><b><?=$center_kosong?></b></td>
								<td></td>
							</tr>

							<tr>
								<td><b>TOTAL CENTER</b></td>
								<td><b><?=$value['hitung_center']?></b></td>
								<td>Entry : <?=$value['hitung_center'] - $center_kosong?></td>
							</tr>
							<tr>
								<td><b>TOTAL ANGGOTA</b></td>
								<td><b><?=$value['member']?></b></td>
								<td></td>
							</tr>
							<tr>
								<td><b>TOTAL CLIENT</b></td>
								<td><b><?=$value['anggota']?></b></td>
								<td></td>
							</tr>
							<tr>
								<td><b>TOTAL MONITORING</b></td>
								<td><b><?=$total_monitoring?></b></td>
								<td></td>
							</tr>
							
							<tr>
								<td><b>TOTAL PAR <?=$tglakhir?></b></td>
								<td><b><?=$par['hitung']?></b></td>
								<td></td>
							</tr>
							
						</table>	
					</div>
				<?php

				}
			
			
		}
		?>
		
</div>
