
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
							
							echo "<option value='$jab1[id_cabang]' $select>".strtoupper($jab1['nama_cabang'])."</option>";
							
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
			<a href="<?=$url.$menu?>list_ak&tglawal=<?=$tglawal?>&tglakhir=<?=$tglakhir?>&cari=FILTER" class='btn btn-info'>
			<i class="fa fa-list"></i> LIST A.K
		</a>
		<a href="<?=$url.$menu?>rekap_anggota&tglawal=<?=date("Y-m-d",(strtotime ( '-1 day' , strtotime ( date("Y-m-d"))  )))?>&tglakhir=<?=date("Y-m-d",(strtotime ( '-1 day' , strtotime ( date("Y-m-d"))  )))?>&cari=FILTER" class="btn btn-danger">KEMARIN</a>
		<a href="<?=$url.$menu?>rekap_anggota&tglawal=<?=date("Y-m-d")?>&tglakhir=<?=date("Y-m-d")?>&cari=FILTER" class="btn btn-danger">HARI INI</a>
		</form>
		<?php
		if(isset($_GET['cari']))
		{
			$tglawal = $_GET['tglawal'];
			$tglakhir = $_GET['tglakhir'];
			if(isset($_GET['cabang']) || $_GET['cabang']!=null){
				$id_cabang = $_GET['cabang'];

			}
			$data = new Hitung();
			 $data_ = $data->cari_anggota($con,$id_cabang,$tglawal,$tglakhir);
			 ?>
		 <div class='col-md-6'>
		 <table class="table table-bordered">
		 	<tr>
		 		<th colspan=10 class='text-center'>
		 			<?php echo $tglawal.' s/d '. $tglakhir?>
		 		</th>
		 	</tr>
		 	<tr>
		 		<th>NO</th>
		 		<th >Nama</th>
		 		<th id='tengah'>AM</th>
		 		<th id='tengah'>AK</th>
		 		<th id='tengah'>NETT</th>
		 		<th id='tengah'>PMB</th>
		 		<th id='tengah'>PSA</th>
		 		<th id='tengah'>PPD</th>
		 		<th id='tengah'>PRR</th>
		 		<th id='tengah'>ARTA</th>
		 	</tr>
		 	<?php 
		 	$masuk=0;
		 	$keluar=0;
		 	$nett = 0;
		 	$psa = 0;
		 	$pmb = 0;
		 	$ppd = 0;
		 	$prr = 0;
		 	$arta = 0;
		 	foreach ($data_ as $key => $val ) {
				 //SEMENTARA
				//  if($val['masuk']==0 && $val['keluar']==0 && $val['nett']==0 )
		 		// {

				//  }
				//  else{
					 if($val['nett']>0){
						 $bg_color = "#4cae4c";
					 }
					 elseif($val['nett']==0){
						 $bg_color="#ffff";
					 }
					 else{
						 $bg_color="#d9534f";
					 }
					 if($val['keluar']>0){
						 $bg_keluar = "#FF4D4D";
					 }
					 else $bg_keluar="";
				 
		 		?>

		 	<tr>
		 		<td><?=$no++ ?></td>
		 		<td><?=$val['nama_karyawan'] ?></td>
		 		<td id='tengah'><?=$masuk1 = $val['masuk'] ?></td>
		 		<td id='tengah' style='background-color:<?=$bg_keluar?> '><?=$keluar1 =$val['keluar'] ?></td>
		 		<td id='tengah' style="background-color: <?=$bg_color?>;"><?=$nett1= $val['nett'] ?></td>
		 		<td id='tengah'><?=$pmb1= $val['pmb'] ?></td>
		 		<td id='tengah'><?=$psa1= $val['psa'] ?></td>
		 		<td id='tengah'><?=$ppd1= $val['ppd'] ?></td>
		 		<td id='tengah'><?=$prr1= $val['prr'] ?></td>
		 		<td id='tengah'><?=$arta1= $val['arta'] ?></td>
		 	</tr>
		 		<?php
		 		$masuk=$masuk1+$masuk;
		 		$keluar=$keluar1+$keluar;
		 		$nett=$nett1+$nett;
		 		$pmb=$pmb1+$pmb;
		 		$psa=$psa1+$psa;
		 		$ppd=$ppd1+$ppd;
		 		$prr=$prr1+$prr;
		 		$arta=$arta1+$arta;
		 	// }
		}

		 	?>
		 	<tr>
		 		<th ></th>
		 		<th >Total</th>
		 		<th id='tengah'><?=$masuk?></th>
		 		<th id='tengah'><?=$keluar?></th>
		 		<th id='tengah'><?=$nett?></th>
		 		<th id='tengah'><?=$pmb?></th>
		 		<th id='tengah'><?=$psa?></th>
		 		<th id='tengah'><?=$ppd?></th>
		 		<th id='tengah'><?=$prr?></th>
		 		<th id='tengah'><?=$arta?></th>
		 	</tr>
		 </table>
		 </div>
			 <?php
			 
		}
		?>
</div>

<style>
	#tengah{
		text-align: center;
		width: 50px;
	}
</style>