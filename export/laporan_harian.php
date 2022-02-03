<?php 
require_once "../config/seting.php";
require_once "../config/koneksi.php";
require_once("../proses/fungsi.php");
require_once("../model/model.php");
$id_karyawan = $_SESSION['id'];
$nama_karyawan = $_SESSION['nama_karyawan'];
$jabatan= $_SESSION['jabatan'];
$cabang= $_SESSION['cabang'];
$id_cabang= $_SESSION['cabang'];
$su= $_SESSION['su'];
$d = detail_karyawan($con,$id_karyawan);
$nama_jabatan=$d['singkatan_jabatan'];
 if(isset($_GET['tgl']))
	{
		$qtgl=$_GET['tgl'];
	}
	else{
		$qtgl=date("Y-m-d");
	}
	$hari = hari_biasa($qtgl);
// header("Content-type: application/vnd-ms-excel");
// header("Content-Disposition: attachment; filename=laporan harian $hari .xlsx");
?>
<!DOCTYPE html>
<html>
<head>
	<style type="text/css">
	th, td {
	  padding: 5px;
	  text-align: left;
	}
	</style>
	<title></title>
</head>
<body>
<table border="1">
			<tr>					
				<th rowspan=2>NO</th>
				<th rowspan=2>NAMA</th>
				<th colspan=8 style="text-align:center">LAPORAN</th>
			</tr>
			<tr>					

				<td >CTR</td>
				<td >AGT</td>
				<td >Client</td>
				<td >Bayar</td>
				<td >Tdk Bayar</td>
				<td >%</td>
				<td >Change</td>
				<td >Keterangan</td>
				<td >#</td>
			</tr>
			<?php 
			$tgl1 = date("Y-m-d");// pendefinisian tanggal awal
			$tgl2 = date('Y-m-d', strtotime('-7 days', strtotime($tgl1))); //operasi penjumlahan tanggal sebanyak 6 hari

			$cek_ka=mysqli_query($con,"SELECT * FROM karyawan,jabatan,cabang where karyawan.id_jabatan=jabatan.id_jabatan and karyawan.id_cabang=cabang.id_cabang and karyawan.id_cabang='$cabang' and jabatan.singkatan_jabatan='SL' and karyawan.status_karyawan='aktif' order by karyawan.nama_karyawan asc");
			$hitung_member = 0; 
			$hitung_agt = 0; 
			$hitung_bayar = 0; 
			$hitung_tdk_bayar= 0; 
			$hitung_center= 0; 
			$hitung_chg = 0;
			while($tampil=mysqli_fetch_array($cek_ka)){
				$cek_l1 = mysqli_query($con,"select * from laporan where id_karyawan='$tampil[id_karyawan]' and tgl_laporan=curdate()");

				$cek_l=mysqli_query($con,"SELECT sum(detail_laporan.total_agt)as anggota, sum(detail_laporan.member)as member, sum(detail_laporan.total_bayar)as bayar,sum(detail_laporan.total_tidak_bayar)as tidak_bayar,count(no_center) as hitung_center, laporan.* FROM laporan,detail_laporan where laporan.id_laporan=detail_laporan.id_laporan and laporan.tgl_laporan=curdate() and laporan.id_karyawan='$tampil[id_karyawan]'");

				// echo "SELECT sum(detail_laporan.total_agt)as anggota, sum(detail_laporan.total_bayar)as bayar,sum(detail_laporan.total_tidak_bayar)as tidak_bayar,count(no_center) as hitung_center, laporan.* FROM laporan,detail_laporan where laporan.id_laporan=detail_laporan.id_laporan and laporan.tgl_laporan=curdate() and laporan.id_karyawan='$tampil[id_karyawan]'";
				if(mysqli_num_rows($cek_l) ){
					$tampil_lapor=mysqli_fetch_array($cek_l);
					if($tampil_lapor['anggota']!=NULL){
						$hitung_member = $hitung_member + $tampil_lapor['member']; 
						$hitung_agt = $hitung_agt + $tampil_lapor['anggota']; 
						$hitung_bayar = $hitung_bayar + $tampil_lapor['bayar']; 
						$hitung_tdk_bayar= $hitung_tdk_bayar + $tampil_lapor['tidak_bayar']; 
						$hitung_center= $hitung_center + $tampil_lapor['hitung_center']; 


						
						$qchg = mysqli_query($con,"SELECT SUM(detail_laporan.`total_bayar`) AS bayar,
						SUM(detail_laporan.`total_tidak_bayar`) AS tidak_bayar,
						(SUM(detail_laporan.`total_bayar`)/SUM(detail_laporan.`total_agt`) *100) AS persen
						 FROM laporan JOIN detail_laporan ON laporan.`id_laporan`=detail_laporan.`id_laporan` 
						
						WHERE laporan.`tgl_laporan`='$tgl2' AND laporan.id_karyawan='$tampil[id_karyawan]'
						 GROUP BY laporan.`id_karyawan`
						 
						");
						$persen = round(($tampil_lapor['bayar']/$tampil_lapor['anggota'] *100  ),2);
						$chg = mysqli_fetch_array($qchg);
						$chg = round($chg['persen'],2);
						$rubah = $persen - $chg ;
						$hitung_chg = $rubah + $hitung_chg;
						if($rubah>0){
							$warna_chg = "#52eb34";
						}
						else{
							$warna_chg = "#e4544d";
							
						}
				?>
					<tr>
						<td><?php echo $no++ ?>.</td>

						<td><?php echo $tampil['nama_karyawan'] ?></td>
						<td><?php echo $tampil_lapor['hitung_center'] ?></td>
						<td><?php echo $tampil_lapor['member'] ?></td>
						<td><?php echo $tampil_lapor['anggota'] ?></td>
						<td><?php echo $tampil_lapor['bayar'] ?></td>
						<td><?php echo $tampil_lapor['tidak_bayar'] ?></td>
						<td><?php echo $persen ?>%</td>
						<td style="color:<?=$warna_chg?>">
							<?php echo ($rubah==null?"0":$rubah."%") ?>

						</td>

						<td><?php echo $tampil_lapor['keterangan_laporan'] ?></td>
						<td><small><i><?php echo $tampil_lapor['status_laporan'] ?></i></small></td>
						
					</tr>
						<?php
					}
					else{
						if(mysqli_num_rows($cek_l1))
						{
							$tampil_lapor1 = mysqli_fetch_array($cek_l1);
							?>
							<tr>
								
								<td><?php echo $no++ ?>.</td>

								<td><?php echo $tampil['nama_karyawan'] ?></td>
								<td>0</td>
								<td>0</td>
								<td>0</td>
								<td>0</td>
								<td>0</td>
								<td>0%</td>

								<td><?php echo $tampil_lapor1['keterangan_laporan'] ?></td>
								<td><small><i><?php echo $tampil_lapor1['status_laporan'] ?></i></small></td>
							</tr>
							<?php
						}
						else
						{

						?>
						<tr>
							<td><?php echo $no++ ?>.</td>

							<td><?php echo $tampil['nama_karyawan'] ?></td>
							<td colspan=9><i>belum membuat laporan</td>
							
						</tr>
						<?php
						}
					}
				}
				else {

				?>
					<tr>
						<td colspan=5>Belum bikin laporan </td>
					</tr>
				<?php
				}
			?>
			
			<?php
				
			}
			
			$total__cgh = mysqli_query($con,"SELECT SUM(detail_laporan.`total_bayar`) AS bayar,
						SUM(detail_laporan.`total_tidak_bayar`) AS tidak_bayar,
						(SUM(detail_laporan.`total_bayar`)/SUM(detail_laporan.`total_agt`) *100) AS persen
						 FROM laporan JOIN detail_laporan ON laporan.`id_laporan`=detail_laporan.`id_laporan` 
						
						WHERE laporan.`tgl_laporan`='$tgl2' 
						 GROUP BY laporan.`id_karyawan`
						 
						");
			$total_chg_persen = mysqli_fetch_array($total__cgh);
			$persen = round(($hitung_bayar/$hitung_agt)*100,2);
			$hitung_chg = $total_chg_persen['persen'] - $persen;
			if($hitung_chg>0){
				$warna_chg = "#52eb34";
			}
			else{
				$warna_chg = "#e4544d";
				
			}
			?>
			<tr>
				<th colspan=2>Total</th>
				<th ><?php echo $hitung_center ?></th>
				<th ><?php echo $hitung_member ?></th>
				<th ><?php echo $hitung_agt ?></th>
				<th ><?php echo $hitung_bayar ?></th>
				<th ><?php echo $hitung_tdk_bayar ?></th>
				<th ><?php echo $persen ?>%</th>
				<th style="color:<?=$warna_chg?>" ><?php echo round($hitung_chg,2) ?></th>
			</tr>
		</table>

		<?php
$tgl = $_GET['tgl']; 
$tgl1 = $tglawal = date("Y-m-d",strtotime ( '-7 day' , strtotime ( date($tgl)))) ;

?>
<div class='content table-responsive'>
  
     <hr>
        <h3>Rekap pengembalian pokok  <?=format_hari_tanggal($tgl)?></h3>
      <div class="col-lg-10">
      <table border="1" >
          <thead>
              
              <tr>
                  <th>NO</th>
                  <th>STAFF</th>
                  <th>POKOK</th>
                  <!-- <th>TANPA TOPUP</th> -->
                  <th>MARGIN</th>
                  <th>POKOK + NISBAH</th>
                  <th>TOTAL PENDAPATAN</th>
              </tr>
          </thead>
          <tbody>
              <?php 
              $total_pokok = 0;
              $total_nisbah = 0;
              $total_uang = 0;
              $total_semua = 0;
              $total_kemarin_pokok = 0;
              $total_kemarin_uang =0 ;
              $total_tanpa_topup=0;
              $total_tanpa_pokok =0 ;
              $total_kemarin_tanpa_topup =0;

              $q = mysqli_query($con,"SELECT * from pengembalian p join karyawan k on p.id_karyawan=k.id_karyawan   where p.tgl_pengembalian='$tgl' and p.id_cabang='$id_cabang' and k.id_cabang='$id_cabang' order by k.nama_karyawan");
              // echo "SELECT * from pengembalian p join karyawan k on p.id_karyawan=k.id_karyawan   where p.tgl_pengembalian='$tgl' and p.id_cabang='$id_cabang' and k.id_cabang='$id_cabang' order by k.nama_karyawan";
              echo mysqli_error($con);
              while($row = mysqli_fetch_array($q)){
                $qkemarin = mysqli_query($con,"SELECT * from pengembalian p join karyawan k on p.id_karyawan=k.id_karyawan   where p.tgl_pengembalian='$tgl1' and p.id_cabang='$id_cabang' and k.id_cabang='$id_cabang' and k.id_karyawan='$row[id_karyawan]' and p.id_karyawan='$row[id_karyawan]' order by k.nama_karyawan");
               
              //  echo "SELECT * from pengembalian p join karyawan k on p.id_karyawan=k.id_karyawan   where p.tgl_pengembalian='$tgl1' and p.id_cabang='$id_cabang' and k.id_cabang='$id_cabang' and k.id_karyawan='$row[id_karyawan]' and p.id_karyawan='$row[id_karyawan]' order by k.nama_karyawan";
                $kemarin = mysqli_fetch_array($qkemarin);
                $kemarin_pokok = $kemarin['total_topup'];
                $kemarin_uang = $kemarin['total_pengembalian'];
                $kemarin_pokok_margin = $kemarin_pokok ;

                $kemarin_tanpa_topup=$kemarin['pokok'];
                $total_kemarin_tanpa_topup += $kemarin_tanpa_topup;

                $total_kemarin_pokok += $kemarin_pokok;
                $total_kemarin_uang += $kemarin_uang;

                $json = json_decode($kemarin['json_pengembalian']);
            $tanpa_pokok = $kemarin['total_topup'];
            $total_tanpa_pokok += $tanpa_pokok;

                  $pokok = $row['total_topup'];
                  $tanpa_topup = $row['pokok'];

                  $total_tanpa_topup += $tanpa_topup;
                  $nisbah = $row['nisbah'];
                  $total = $pokok + $nisbah;
                  $json = json_decode($row['json_pengembalian']);
                  $uang = $row['total_pengembalian'];
                  $total_pokok += $pokok;
                  $total_nisbah += $nisbah;
                  $total_uang += $uang;
                  $total_semua += $total;

                  if($pokok>$kemarin_pokok){
                    $tr = "#70ff81";
                  }
                  else $tr="#ff7570";
                  if($uang>$kemarin_uang)
                  {
                    $tr1 = "#70ff81";
                  }
                  else $tr1="#ff7570";
                  if($total>$kemarin_pokok_margin)
                  {
                    $tr2 = "#70ff81";
                  }
                  else $tr2="#ff7570";
                  ?>
                <tr>
                    <td><?=$no++?></td>
                    <td><?=$row['nama_karyawan']?></td>
                    <td ><?=angka($pokok)?></td>
                    <!-- <td ><?=angka($tanpa_topup)?></td> -->
                    <td ><?=angka($nisbah)?></td>
                    <td ><?=angka($total)?></td>
                    <td ><?=angka($uang)?></td>
                </tr>
                  <?php
              }
              ?>
          </tbody>
          <tfoot>
                <tr>
                    <th colspan="2">TOTAL PENDAPATAN</th>

                    <th><?=angka($total_pokok)?></th>
                    <!-- <th><?=angka($total_tanpa_topup)?></th> -->
                    <th><?=angka($total_nisbah)?></th>
                    <th><?=angka($total_semua)?></th>
                    <th><?=angka($total_uang)?></th>
                </tr>
          </tfoot>
      </table>
      </div>

</div>
<!-- Button trigger modal -->


</body>
</html>
	

