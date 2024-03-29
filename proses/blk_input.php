<div class="row">
	<h3 class="page-header">ANALISA PAR DAN ANGGOTA</h3>
	<hr />
    <form method="post" action="<?=$url?>blk.php" enctype="multipart/form-data">
        <div class="col-md-4">
            <label for="formFile" class="form-label">SILAHKAN PILIH FILE</label>
            <input class="form-control" type="file" name='file' required accept=".xls,.xlsx,.csv" id="formFile">
            <input class="form-control" type="date" name='tgl' required >
            <input type="submit" value="Proses" class='btn btn-danger' name='preview'>
        </div>
    </form>
</div>

<div class="row">
	<h3 class="page-header">ANALISA PAR YANG BISA DITUTUP OLEH SIMP SUKARELA</h3>
	<hr />
    <form method="post"  enctype="multipart/form-data">
        <div class="col-md-4">
            <label for="formFile" class="form-label">SILAHKAN PILIH FILE</label>
            <input class="form-control" type="file" name='file' accept=".xls,.xlsx,.csv" id="formFile">
            <input type="submit" value="Proses" class='btn btn-danger' name='preview_sukarela'>
        </div>
    </form>
</div>
<div class="row">
	<h3 class="page-header">ANALISA ANGGOTA TIDAK BAYAR</h3>
	<hr />
    <form method="post" action="<?=$url.$menu?>blk_tidak_bayar" enctype="multipart/form-data">
        <div class="col-md-4">
            <label for="formFile" class="form-label">SILAHKAN PILIH BTC</label>
            <input class="form-control" type="file" name='file' accept=".xls,.xlsx,.csv" id="formFile">
            <input type="submit" value="Proses" class='btn btn-danger' name='preview'>
        </div>
    </form>
</div>
<div class="row">
	<h3 class="page-header">ANALISA PAR NEW</h3>
	<hr />
    <a href="<?=$url.$menu?>blk_new" class="btn btn-success">DOWNLOAD ANALISA PAR</a>
    <a href="<?=$url.$menu?>blk_simpanan" class="btn btn-primary">REKAP SIMPANAN</a>
    <a href="<?=$url.$menu?>delinsaving" class="btn btn-info">DELIN SAVING & SUKARELA UNTUK ANGSURAN </a>
    <!-- <a href="<?=$url.$menu?>export_simpanan_wajib" class="btn btn-info">DOWNLOAD ALASAN PAR</a> -->
</div>

<div class="row">
	<h3 class="page-header">RINCIAN PENARIKAN SIMPANAN</h3>
	<hr />
    <form method="post" action="<?=$url.$menu?>export_simpanan_wajib" enctype="multipart/form-data">
        <div class="col-md-4">
            <label for="formFile" class="form-label">SILAHKAN PILIH BTC</label>
            <input class="form-control" type="file" name='file' accept=".xls,.xlsx,.csv" id="formFile">
            <input type="submit" value="Proses" class='btn btn-danger' name='simpanan'>
        </div>
    </form>
</div>
<?php
if(isset($_POST['preview_sukarela'])){
    include("./proses/blk_sukarela.php");
}
if(isset($_POST['preview_new'])){
    include("./proses/blk_new.php");
}
?>