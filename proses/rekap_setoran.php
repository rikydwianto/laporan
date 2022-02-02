<?php
$tgl = $_GET['tgl']; 
$tgl1 = $tglawal = date("Y-m-d",strtotime ( '-7 day' , strtotime ( date($tgl)))) ;

?>
<div class='content table-responsive'>
  <a href='<?=$url.$menu."tambah_setoran"?>' class="btn btn-primary">
    <i class="fa fa-plus"></i> TAMBAH</a>  
    <h2 class='page-header'>REKAP SETORAN POKOK + MARGIN</h2>
	<!-- <i>Center otomatis dibuat ketika Staff membuat laporan</i><hr/> -->
	  <!-- Button to Open the Modal -->
     <div class="col-md-12">
     <div class='col-md-4'>
        <h2>HARIAN</h2>
      <form method='get' action='<?php echo $url.$menu ?>rekap_setoran'>
      <input type=hidden name='menu' value='rekap_setoran' />
      <input type=date name='tgl' value='<?php echo isset($_GET['tgl']) ? $_GET['tgl'] : date("Y-m-d") ?>' onchange="submit()" />
      <input type=submit name='cari' value='CARI' />
      </form>
      </div>

      <div class='col-md-8'>
        <h2>Berdasarkan tanggal</h2>
        <form action="">
        <input type="hidden" name='menu' value='rekap_setoran_banding'/>
				<input type="date" name='tglawal' value="<?=(isset($_GET['tglawal']) ?  $_GET['tglawal'] : date("Y-m-d",(strtotime ( '-4 day' , strtotime ( date("Y-m-d")) ) )) )?>" class=""/>
				<input type="date" name='tglakhir' value="<?=(isset($_GET['tglakhir']) ?  $_GET['tglakhir'] : date("Y-m-d"))?>" class=""/>
				<input type='submit' class="btn btn-info" name='cari' value='FILTER'/>
        </form>
      </div>
     </div>
<!-- </div> -->
     <br>
     <br>
     <hr>
        <h3>Rekap pengembalian pokok  <?=format_hari_tanggal($tgl)?></h3>
      <div class="col-lg-10">
      <table class='table table-bordered' >
          <thead>
              
              <tr>
                  <th>NO</th>
                  <th>STAFF</th>
                  <th>POKOK <br> Tanpa Topup</th>
                  <th>POKOK <br> <?=$tgl1?></th>
                  <th>PENDAPATAN<br> <?=$tgl1?></th>
                  <th>POKOK</th>
                  <th>TANPA TOPUP</th>
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
                    <td style='background:#69d676'><?=angka($kemarin_tanpa_topup)?></td>
                    <td style='background:#69d676'><?=angka($tanpa_pokok)?></td>
                    <td style='background:#69d676'><?=angka($kemarin_uang)?></td>
                    <td style='background:<?=$tr?>'><?=angka($pokok)?></td>
                    <td style='background:<?=$tr?>'><?=angka($tanpa_topup)?></td>
                    <td ><?=angka($nisbah)?></td>
                    <td style='background:<?=$tr2?>'><?=angka($total)?></td>
                    <td style='background:<?=$tr1?>'><?=angka($uang)?></td>
                </tr>
                  <?php
              }
              ?>
          </tbody>
          <tfoot>
                <tr>
                    <th colspan="2">TOTAL PENDAPATAN</th>

                    <th><?=angka( $total_kemarin_tanpa_topup)?></th>
                    <th><?=angka($total_kemarin_pokok)?></th>
                    <th><?=angka($total_kemarin_uang)?></th>
                    <th><?=angka($total_pokok)?></th>
                    <th><?=angka($total_tanpa_topup)?></th>
                    <th><?=angka($total_nisbah)?></th>
                    <th><?=angka($total_semua)?></th>
                    <th><?=angka($total_uang)?></th>
                </tr>
          </tfoot>
      </table>
      </div>

</div>
<!-- Button trigger modal -->

