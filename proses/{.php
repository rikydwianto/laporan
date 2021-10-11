<div class="container-fluid">

	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header">Qoutes</h1>
			<br>
		</div>
	</div>


	<form method='post'>
		<table class='table'>
			<tr>
				<td>NAMA</td>
				<td><?php echo strtoupper($d['nama_karyawan'])?></td>
			</tr>
			<tr>
				<td>QOUTES</td>
				<td>
                    <textarea name="qoutes" class='form-control' id="" cols="30" rows="5"></textarea>
                </td>
			</tr>
			<tr>
				<td></td>
				<td><input type='submit' value='TAMBAH' name='simpan' class='btn btn-danger'></td>
			</tr>
		</table>
	</form>
    <table class='table'>
        <thead>
            <tr>
                <th>No</th>
                <th>Qoutes</th>
                <th>#</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sqou = mysqli_query($con,"select * from quotes");
            while($qoutes = mysqli_fetch_array($sqou)){
                ?>
            <tr>
                <td><?=$no++?></td>
                <td><?=$qoutes['quotes']?></td>
                <td>
                    <a href="<?=$url.$menu?>quotes&hapus&id=<?=$qoutes['id_quotes']?>"><i class="fa fa-times"></i></a>
                </td>
            </tr>
                <?php
            }
            ?>
        </tbody>
    </table>

</div>
<?php 
if(isset($_GET['hapus']) && !empty($_GET['id']))
?>