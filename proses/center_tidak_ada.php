<h1>CENTER TIDAK ADA TAPI ADA DI DELIN</h1>

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
<form action="">
    <input type="hidden" name='menu' value='center_tidak_ada'/>
    <input type='submit' class="btn btn-info" name='cari' value='CEK CENTER TIDAK ADA DELIN'/>

</form>
	<hr>
<?php 
//PROSES GANTI CENTER
if(isset($_POST['ganti_center'])){
    $id = $_POST['id_detail'];
    $center = $_POST['center'];
    $i = 0;
    foreach($id as $idk){
        // echo $idk;
        $no_center= $center[$i];
        mysqli_query($con,"update deliquency set no_center='$no_center' where id_detail_nasabah='$idk' and id_cabang='$id_cabang'");
        pesan("berhasil update, terima kasih",'success');
        $i++;
    }
}



if(isset($_GET['cari']))
{

    ?>
    <div class="col-8">
<form method="post">

    <table class='table'>
        <tr>
			<th>NO</th>
			<th>CENTER LAMA</th>
			<th>CENTER BARU</th>
			<th>ID</th>
			<th>NAMA</th>
			<th>STAFF</th>
			<th>HARI</th>
		</tr>
        <?php 
       

        $q = mysqli_query($con,"select * from deliquency where no_center not in (select no_center from center where id_cabang='$id_cabang') and id_cabang='$id_cabang' group by id_detail_nasabah order by tgl_input desc        ");
        while($r=mysqli_fetch_array($q)){
            $cek_c = mysqli_query($con,"select * from daftar_nasabah where id_detail_nasabah='$r[id_detail_nasabah]' and id_cabang='$id_cabang'");
            $client = mysqli_fetch_array($cek_c);
            ?>
            <tr>
                <td><?=$no++?></td>
                <td><?=$r['no_center']?></td>
                <td>
                    <input type="hidden" value='<?=$r['id_detail_nasabah']?>' name='id_detail[]'>
                    <select name="center[]" required  class='form-control' id="">
                    <option value="">pilih center</option>    
                        <?php 
                     $cen = mysqli_query($con,"select * from center where id_cabang='$id_cabang' order by no_center");
                     while($center = mysqli_fetch_array($cen) ){
                        if($center['no_center']==$client['no_center']){

                            ?>
                         <option  value='<?=$center['no_center']?>' selected><?=$center['no_center']?>-<?=$center['hari']?></option>
                         <?php
                        }
                        else{
                            ?>

                            <option  value='<?=$center['no_center']?>'><?=$center['no_center']?>-<?=$center['hari']?></option>
                            <?php
                        }
                    }
                    ?>
                    </select>
            </td>
                <td><?=$r['id_detail_nasabah']?></td>
                <td><?=$r['nasabah']?></td>
                <td><?=$r['staff']?></td>
                <td><?=$r['hari']?></td>
            </tr>
            <?php
        }
        ?>   
            <tr>
                <td colspan="5"><?=$no++?></td>
                <td ><input type="submit" class='btn btn-danger' name='ganti_center' value='Synchron'></td>
            </tr>
        </table>
</form>
        
    </div>
    <?php
}
?>