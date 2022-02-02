
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
<div class='content table-responsive'>
	<h2 class='page-header'>REKAP SETORAN POKOK + MARGIN</h2>
	<!-- <i>Center otomatis dibuat ketika Staff membuat laporan</i><hr/> -->
	  <!-- Button to Open the Modal -->
      <form method='get' action='<?php echo $url.$menu ?>rekap_setoran'>
      <input type=hidden name='menu' value='rekap_setoran' />
      <input type=date name='tgl' value='<?php echo isset($_GET['tgl']) ? $_GET['tgl'] : date("Y-m-d") ?>' onchange="submit()" />
      <input type=submit name='cari' value='CARI' />
      </form>
      <a href='<?=$url.$menu."tambah_setoran"?>' class="btn btn-primary">
    <i class="fa fa-plus"></i> TAMBAH</a>  
        <h3>Rekap pengembalian pokok  <?=format_hari_tanggal($tglawal)?> s/d <?=format_hari_tanggal($tglakhir)?></h3>
      <div class="col-lg-12">
      <table class='table'>
        <thead>
          <tr>
            <th>NO</th>
            <th>STAFF</th>
            <?php 
            // $
            ?>
          </tr>
        </thead>

      </table>
      </div>

</div>
<!-- Button trigger modal -->

