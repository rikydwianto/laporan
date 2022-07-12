<div class='content table-responsive'>
	<h3 class='page-header'>REKAP TABUNGAN</h3>
	<!-- <i>Center otomatis dibuat ketika Staff membuat laporan</i><hr/> -->
	  <!-- Button to Open the Modal -->
  <!-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalku">
    <i class="fa fa-plus"></i> Center
  </button> -->
  

  <?php
 if(isset($_GET['tglawal']) || isset($_GET['tglakhir']))
{
	$tglawal = $_GET['tglawal'];
	$tglakhir = $_GET['tglakhir'];
}
else{
    $tgl = date("Y-m-d");
	$tglawal = date("Y-m-d",strtotime('last monday', strtotime("$tgl")));
	$tglakhir = date("Y-m-d");
}
	 
?>
    <form action="">
        <input type="hidden" name='menu' value='rekap_tabungan'/>
        <input type="date" name='tglawal' value="<?=(isset($_GET['tglawal']) ?  $_GET['tglawal'] : date("Y-m-d",(strtotime ( '-4 day' , strtotime ( date("Y-m-d")) ) )) )?>" class=""/>
        <input type="date" name='tglakhir' value="<?=(isset($_GET['tglakhir']) ?  $_GET['tglakhir'] : date("Y-m-d"))?>" class=""/>
        <input type='submit' class="btn btn-info" name='cari' value='FILTER'/>
        <!-- <a href="<?=$url.$menu?>rekap_kegiatan&tglawal=<?=date("Y-m-d",(strtotime ( '-1 day' , strtotime ( date("Y-m-d"))  )))?>&tglakhir=<?=date("Y-m-d",(strtotime ( '-1 day' , strtotime ( date("Y-m-d"))  )))?>&cari=FILTER" class="btn btn-danger">KEMARIN</a>
                <a href="<?=$url.$menu?>rekap_kegiatan&tglawal=<?=date("Y-m-d")?>&tglakhir=<?=date("Y-m-d")?>&cari=FILTER" class="btn btn-danger">HARI INI</a> -->
    </form>
    <hr>
    <div class="col-md-12">
        <h2 style='text-align:center'>REKAP TABUNGA DARI DELINSAVING<br> 
        <?=format_hari_tanggal($tglawal)?> s/d <?=format_hari_tanggal($tglakhir)?>
        </h2>
    <?php 
    $TGL   = array();
    $qtgl = mysqli_query($con,"SELECT tgl_input from deliquency where id_cabang='$id_cabang' and (tgl_input between '$tglawal' and '$tglakhir') group by tgl_input order by tgl_input asc");
    while($tgl = mysqli_fetch_array($qtgl)){
        $TGL[]=$tgl['tgl_input'];
    }
    ?>
    <table class='table table-bordered'>
        <tr>
            <th>NO</th>
            <th>NAMA</th>
            <th>CTR</th>
            <?php 
            foreach($TGL as $tgl){
                ?>
                <th><?=$tgl?></th>
                <?php
            }
            ?>
        </tr>
        <?php 
        $q = mysqli_query($con,"SELECT * from deliquency where id_cabang='$id_cabang' group by id_detail_nasabah ");

        while($delin = mysqli_fetch_array($q)){
            ?>
        <tr>
            <td><?=$no++?></td>
            <td><?=$delin['nasabah']?></td>
            <td><?=$delin['no_center']?></td>
            <?php 
            $a=1;

            foreach($TGL as $tgl){
                if($a < count($TGL)){

                    $tgl_next = $TGL[$a];
                    // if(!$tgl_next){
                        // }
                // ?>
                <td><?=angka(hitung_tabungan($con,$id_cabang,$delin['id_detail_nasabah'],$tgl,$tgl_next,"sukarela"))?></td>
                <?php
                }
                else{
                    ?>
                    <td><?=angka(hitung_sekarang($con,$id_cabang,$delin['id_detail_nasabah'],$tgl,"sukarela"))?></td>

                    <?php
                }
                $a++;
            }
            ?>
        </tr>
            <?php
        }
        ?>
    </table>
    </div>

  
</div>
