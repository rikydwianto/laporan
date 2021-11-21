<div class="row">
	<h3 class="page-header">ANALISA PAR DAN ANGGOTA</h3>
	<hr />
    <form method="post" action="<?=$url?>blk.php" enctype="multipart/form-data">
        <div class="col-md-4">
            <label for="formFile" class="form-label">SILAHKAN PILIH FILE</label>
            <input class="form-control" type="file" name='file' accept=".xls,.xlsx,.csv" id="formFile">
            <input type="submit" value="Proses" class='btn btn-danger' name='preview'>
        </div>
    </form>
</div>
