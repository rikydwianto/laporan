<?php
$tgl = $_GET['tgl']; 
$tgl1 = $tglawal = date("Y-m-d",strtotime ( '-7 day' , strtotime ( date($tgl)))) ;

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
        <h3>Rekap pengembalian pokok  <?=format_hari_tanggal($tgl)?></h3>
      <div class="col-lg-8">
      <table class='table table-bordered' >
          <thead>
              
              <tr>
                  <th>NO</th>
                  <th>STAFF</th>
                  <th>POKOK <br> <?=$tgl1?></th>
                  <th>PENDAPATAN<br> <?=$tgl1?></th>
                  <th>POKOK</th>
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

              $q = mysqli_query($con,"SELECT * from pengembalian p join karyawan k on p.id_karyawan=k.id_karyawan   where p.tgl_pengembalian='$tgl' and p.id_cabang='$id_cabang' and k.id_cabang='$id_cabang' order by k.nama_karyawan");
              // echo "SELECT * from pengembalian p join karyawan k on p.id_karyawan=k.id_karyawan   where p.tgl_pengembalian='$tgl' and p.id_cabang='$id_cabang' and k.id_cabang='$id_cabang' order by k.nama_karyawan";
              echo mysqli_error($con);
              while($row = mysqli_fetch_array($q)){
                $qkemarin = mysqli_query($con,"SELECT * from pengembalian p join karyawan k on p.id_karyawan=k.id_karyawan   where p.tgl_pengembalian='$tgl1' and p.id_cabang='$id_cabang' and k.id_cabang='$id_cabang' and k.id_karyawan='$row[id_karyawan]' and p.id_karyawan='$row[id_karyawan]' order by k.nama_karyawan");
               
              //  echo "SELECT * from pengembalian p join karyawan k on p.id_karyawan=k.id_karyawan   where p.tgl_pengembalian='$tgl1' and p.id_cabang='$id_cabang' and k.id_cabang='$id_cabang' and k.id_karyawan='$row[id_karyawan]' and p.id_karyawan='$row[id_karyawan]' order by k.nama_karyawan";
                $kemarin = mysqli_fetch_array($qkemarin);
                $kemarin_pokok = $kemarin['pokok'];
                $kemarin_uang = $kemarin['total_pengembalian'];

                $total_kemarin_pokok += $kemarin_pokok;
                $total_kemarin_uang += $kemarin_uang;

                  $pokok = $row['pokok'];
                  $nisbah = $row['nisbah'];
                  $total = $pokok + $nisbah;
                  $json = json_decode($row['json_pengembalian']);
                  $uang = $row['total_pengembalian'];
                  $total_pokok += $pokok;
                  $total_nisbah += $nisbah;
                  $total_uang += $uang;
                  $total_semua += $total;

                  
                  ?>
                <tr>
                    <td><?=$no++?></td>
                    <td><?=$row['nama_karyawan']?></td>
                    <td style='background:#81ff70'><?=angka($kemarin_pokok)?></td>
                    <td style='background:#81ff70'><?=angka($kemarin_uang)?></td>
                    <td style=''><?=angka($pokok)?></td>
                    <td><?=angka($nisbah)?></td>
                    <td><?=angka($total)?></td>
                    <td><?=angka($uang)?></td>
                </tr>
                  <?php
              }
              ?>
          </tbody>
          <tfoot>
                <tr>
                    <th colspan="2">TOTAL PENDAPATAN</th>

                    <th><?=angka($total_kemarin_pokok)?></th>
                    <th><?=angka($total_kemarin_uang)?></th>
                    <th><?=angka($total_pokok)?></th>
                    <th><?=angka($total_nisbah)?></th>
                    <th><?=angka($total_semua)?></th>
                    <th><?=angka($total_uang)?></th>
                </tr>
          </tfoot>
      </table>
      </div>

</div>
<!-- Button trigger modal -->

