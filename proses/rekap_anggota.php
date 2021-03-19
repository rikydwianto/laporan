
<?php
 if(isset($_GET['tglawal']) || isset($_GET['tglakhir']))
{
	$tglawal = $_GET['tglawal'];
	$tglakhir = $_GET['tglakhir'];
}
else{
	$tglawal = date("Y-m-d",strtotime ( '-4 day' , strtotime ( date("Y-m-d")))) ;
	$tglakhir = date("Y-m-d");
}
	 
?>
<div class="row">
	
	<h3 class="page-header">REKAP ANGGOTA DAN PEMBIAYAAN LAINYA</h3>
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
							
							echo "<option value='$jab1[id_cabang]' $select>".strtoupper($jab1[nama_cabang])."</option>";
							
						}
						?>
					  </select>
					
				<input type="hidden" name='menu' value='rekap_anggota'/>
				<input type="date" name='tglawal' value="<?=(isset($_GET['tglawal']) ?  $_GET['tglawal'] : date("Y-m-d",(strtotime ( '-4 day' , strtotime ( date("Y-m-d")) ) )) )?>" class=""/>
				<input type="date" name='tglakhir' value="<?=(isset($_GET['tglakhir']) ?  $_GET['tglakhir'] : date("Y-m-d"))?>" class=""/>
				<input type='submit' class="btn btn-info" name='cari' value='FILTER'/>
			</div>
			<a href="<?=$url?>/export/rekap_agt_lainnya.php?&tglawal=<?=$tglawal?>&tglakhir=<?=$tglakhir?>&cari=FILTER" class='btn btn-success'>
			<i class="fa fa-file-excel-o"></i> Export To Excel
		</a>
		</form>
		<?php
		if(isset($_GET['cari']))
		{
			$tglawal = $_GET['tglawal'];
			$tglakhir = $_GET['tglakhir'];
			$data = new Hitung();
			 $data_ = $data->cari_anggota($con,$id_cabang,$tglawal,$tglakhir);
			 ?>
		 <table class="table">
		 	<tr>
		 		<th colspan=9 class='text-center'>
		 			<?php echo $tglawal.' s/d '. $tglakhir?>
		 		</th>
		 	</tr>
		 	<tr>
		 		<th>NO.</th>
		 		<th>Nama</th>
		 		<th>Masuk</th>
		 		<th>Keluar</th>
		 		<th>Nett</th>
		 		<th>PSA</th>
		 		<th>PPD</th>
		 		<th>PRR</th>
		 		<th>ARTA</th>
		 	</tr>
		 	<?php 
		 	$masuk=0;
		 	$keluar=0;
		 	$nett = 0;
		 	$psa = 0;
		 	$ppd = 0;
		 	$prr = 0;
		 	$arta = 0;
		 	foreach ($data_ as $key => $val ) {
		 		
		 		?>

		 	<tr>
		 		<td><?=$no++ ?></td>
		 		<td><?=$val['nama_karyawan'] ?></td>
		 		<td><?=$masuk1 = $val['masuk'] ?></td>
		 		<td><?=$keluar1 =$val['keluar'] ?></td>
		 		<td><?=$nett1= $val['nett'] ?></td>
		 		<td><?=$psa1= $val['psa'] ?></td>
		 		<td><?=$ppd1= $val['ppd'] ?></td>
		 		<td><?=$prr1= $val['prr'] ?></td>
		 		<td><?=$arta1= $val['arta'] ?></td>
		 	</tr>
		 		<?php
		 		$masuk=$masuk1+$masuk;
		 		$keluar=$keluar1+$keluar;
		 		$nett=$nett1+$nett;
		 		$psa=$psa1+$psa;
		 		$ppd=$ppd1+$ppd;
		 		$prr=$prr1+$prr;
		 		$arta=$arta1+$arta;
		 	}
		 	?>
		 	<tr>
		 		<th colspan=2>Total</th>
		 		<th><?=$masuk?></th>
		 		<th><?=$keluar?></th>
		 		<th><?=$nett?></th>
		 		<th><?=$psa?></th>
		 		<th><?=$ppd?></th>
		 		<th><?=$prr?></th>
		 		<th><?=$arta?></th>
		 	</tr>
		 </table>
			 <?php
		}
		?>
</div>