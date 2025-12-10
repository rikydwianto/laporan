<?php
if (isset($_GET['tglawal']) || isset($_GET['tglakhir'])) {
  $tglawal = $_GET['tglawal'];
  $tglakhir = $_GET['tglakhir'];
} else {
  $tglawal = date("Y-m-d", strtotime('-4 day', strtotime(date("Y-m-d"))));
  $tglakhir = date("Y-m-d");
}

?>
<div class='content table-responsive'>
  <h2 class='page-header'>REKAP SETORAN POKOK + MARGIN</h2>
  <!-- <i>Center otomatis dibuat ketika Staff membuat laporan</i><hr/> -->
  <!-- Button to Open the Modal -->
  <div class="col-md-12">
    <div class='col-md-4'>
      <h2>HARIAN</h2>
      <form method='get' action='<?php echo $url . $menu ?>rekap_setoran'>
        <input type=hidden name='menu' value='rekap_setoran' />
        <input type=date name='tgl' value='<?php echo isset($_GET['tgl']) ? $_GET['tgl'] : date("Y-m-d") ?>' onchange="submit()" />
        <input type=submit name='cari' value='CARI' />
      </form>
    </div>

    <div class='col-md-8'>
      <h2>Berdasarkan tanggal</h2>
      <form action="">
        <input type="hidden" name='menu' value='rekap_setoran_banding' />
        <input type="date" name='tglawal' value="<?= (isset($_GET['tglawal']) ?  $_GET['tglawal'] : date("Y-m-d", (strtotime('-4 day', strtotime(date("Y-m-d")))))) ?>" class="" />
        <input type="date" name='tglakhir' value="<?= (isset($_GET['tglakhir']) ?  $_GET['tglakhir'] : date("Y-m-d")) ?>" class="" />
        <input type='submit' class="btn btn-info" name='cari' value='FILTER' />
      </form>
    </div>
  </div>
  <a href='<?= $url . $menu . "tambah_setoran" ?>' class="btn btn-primary">
    <i class="fa fa-plus"></i> TAMBAH</a>
  <h3>Rekap pengembalian pokok <?= format_hari_tanggal($tglawal) ?> s/d <?= format_hari_tanggal($tglakhir) ?></h3>
  <div class="col-lg-12">
    <table class='table table-bordered'>
      <thead>
        <tr>
          <th>NO</th>
          <th>STAFF</th>
          <?php
          $qtgl  = mysqli_query($con, "SELECT DISTINCT tgl_pengembalian FROM pengembalian WHERE id_cabang='$id_cabang' AND tgl_pengembalian BETWEEN '$tglawal' AND '$tglakhir' order by FIELD(hari_pengembalian,'senin','selasa','rabu','kamis','jumat') asc");
          while ($tgl = mysqli_fetch_assoc($qtgl)) {
            $hari = explode(",", format_hari_tanggal($tgl['tgl_pengembalian']))[0];
            // $tgl = explode(",",format_hari_tanggal($tgl['tgl_pengembalian']))[0];
          ?>
            <th> <?= $tgl['tgl_pengembalian'] ?> <br>
              <?= $hari ?></th>
          <?php
          }
          ?>
          <th>TOTAL</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $qkar =  mysqli_query($con, "SELECT DISTINCT k.nama_karyawan, k.* FROM pengembalian p join karyawan k on k.id_karyawan=p.id_karyawan WHERE k.id_cabang='$id_cabang' AND p.id_cabang='$id_cabang' AND tgl_pengembalian BETWEEN '$tglawal' AND '$tglakhir' order by k.nama_karyawan asc");
        while ($staff = mysqli_fetch_assoc($qkar)) {
        ?>
          <tr>
            <td><?= $no++ ?></td>
            <td><?= $staff['nama_karyawan'] ?></td>
            <?php
            echo mysqli_error($con);
            $qtgl  = mysqli_query($con, "SELECT DISTINCT tgl_pengembalian FROM pengembalian WHERE id_cabang='$id_cabang' AND tgl_pengembalian BETWEEN '$tglawal' AND '$tglakhir' order by FIELD(hari_pengembalian,'senin','selasa','rabu','kamis','jumat') asc");
            while ($tgl = mysqli_fetch_assoc($qtgl)) {
              $hari = explode(",", format_hari_tanggal($tgl['tgl_pengembalian']))[0];
              $peng = mysqli_query($con, "SELECT * FROM pengembalian where id_cabang='$id_cabang' and id_karyawan='$staff[id_karyawan]' and tgl_pengembalian='$tgl[tgl_pengembalian]'");
              $peng = mysqli_fetch_assoc($peng);
              $pokok = $peng['pokok'];
            ?>
              <td><?= angka($pokok) ?></td>
            <?php
            }

            $peng1 = mysqli_query($con, "SELECT SUM(pokok) as total_pokok FROM pengembalian where id_cabang='$id_cabang' and id_karyawan='$staff[id_karyawan]' AND tgl_pengembalian BETWEEN '$tglawal' AND '$tglakhir' ");
            $peng1 = mysqli_fetch_assoc($peng1);
            $total_pokok1 = $peng1['total_pokok'];
            ?>
            <td><?= angka($total_pokok1) ?></td>
          </tr>
        <?php
        }
        ?>
      </tbody>
      <tfoot>
        <tr>
          <th colspan="2"></th>
          <?php
          $total_pokok_semua = 0;
          $qtgl  = mysqli_query($con, "SELECT DISTINCT tgl_pengembalian FROM pengembalian WHERE id_cabang='$id_cabang' AND tgl_pengembalian BETWEEN '$tglawal' AND '$tglakhir' order by FIELD(hari_pengembalian,'senin','selasa','rabu','kamis','jumat') asc");
          while ($tgl = mysqli_fetch_assoc($qtgl)) {
            $peng = mysqli_query($con, "SELECT SUM(pokok) as total_pokok FROM pengembalian where id_cabang='$id_cabang' and tgl_pengembalian='$tgl[tgl_pengembalian]' ");
            $peng = mysqli_fetch_assoc($peng);
            $total_pokok = $peng['total_pokok'];
            $total_pokok_semua += $total_pokok;
            $hari = explode(",", format_hari_tanggal($tgl['tgl_pengembalian']))[0];
            // $tgl = explode(",",format_hari_tanggal($tgl['tgl_pengembalian']))[0];
          ?>
            <th>
              <?= angka($total_pokok) ?>
            </th>
          <?php
          }
          ?>
          <th> <?= angka($total_pokok_semua) ?></th>
        </tr>
      </tfoot>

    </table>
  </div>

</div>
<!-- Button trigger modal -->