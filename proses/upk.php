<style>
    .merah {
        background-color: red;
        color:white;
    }
</style>

<?php
if (isset($_GET['tglawal']) || isset($_GET['tglakhir'])) {
    $tglawal = $_GET['tglawal'];
    $tglakhir = $_GET['tglakhir'];
} else {
    $tglawal = date("Y-m-d");
    $tglakhir = date("Y-m-d", strtotime('+4 day', strtotime(date("Y-m-d"))));
}

?>
<div class="container-fluid">

    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">DAFTAR UPK NON REGULER</h1>
            <br>
        </div>
    </div>
    <form method='get' action='<?php echo $url . $menu ?>upk'>
        <input type="hidden" name='menu' value='upk' />
        <input type="hidden" name='list' value='cari' />
        <input type="date" name='tglawal' value="<?= (isset($_GET['tglawal']) ?  $_GET['tglawal'] : date("Y-m-d")) ?>" class="" />
        <input type="date" name='tglakhir' value="<?= (isset($_GET['tglakhir']) ?  $_GET['tglakhir'] : date("Y-m-d", (strtotime('+4 day', strtotime(date("Y-m-d")))))) ?>" class="" />
        <input type='submit' class="btn btn-info" name='cari' value='FILTER' />
    </form> <br/>
    <a href="<?=$url.$menu?>upk" class='btn btn-success'>
			<i class="fa fa-plus"></i> Tambah
		</a>
        <a href="<?=$url.$menu?>upk&list=cari&tglawal=<?=date("Y-m-d")?>&tglakhir=<?=date("Y-m-d")?>&cari=FILTER" class='btn btn-info'>
			<i class="fa fa-eye"></i> Lihat Hari Ini
		</a>
        <a href="<?=$url."export/upk.php?"?>upk&list=cari&tglawal=<?=$tglawal?>&tglakhir=<?=$tglakhir?>&cari=FILTER" class='btn btn-danger'>
			<i class="fa fa-file-excel-o"></i> EXPORT
		</a>
        <br/>
    <?php
    if (isset($_GET['list'])) {
        include "list_upk.php";
    }
    else if (isset($_GET['hapus'])) {
        $id=$_GET['id_upk'];
        $sql = mysqli_query($con,"delete from upk where id_upk='$id'");
        if($sql){
            alert("Berhasil dihapus!");
        }
        else
        {
            alert("gagal disimpan");
        }
        pindah("$url$menu"."upk&list=cari&tglawal=$tglawal&tglakhir=$tglakhir&cari=FILTER");
    }
    else if(isset($_GET['edit']))
    {
        $id_upk = $_GET['id_upk'];
        $cari = mysqli_query($con, "select * from upk where id_upk='$id_upk' ");
            $cari = mysqli_fetch_array($cari);
     ?>
     <form action="" method="post">
        <table class='table'>
            <tr>
                <td>Staff</td>
                <td>
                    <input class='form-control' type="text" name="" value='<?=detail_karyawan($con,$cari['id_karyawan'])['nama_karyawan']?>' disabled id="">
                    <input class='form-control' type="hidden" name="staff" value='<?=$cari['id_karyawan']?>'  id="">
                </td>
            </tr>
            <tr>
                <td>NO CENTER</td>
                <td><input disabled class='form-control' type="text" name="center" value='<?=$cari['no_center']?>'  id=""></td>
            </tr>
            <tr>
                <td>Total Anggota</td>
                <td><input class='form-control' type="text" name="anggota" value='<?=$cari['anggota_upk']?>'  id=""></td>
            </tr>
            </tr>
            <tr>
                <td>Tanggal</td>
                <td><input class='form-control' type="date" name="tgl" value='<?=$cari['tgl_upk']?>'  id=""></td>
            </tr>
            <tr>
                <td>Status</td>
                <td>
                    <select CLASS='form-control' name="status" id="">
                    <?php 
                    $status = status_upk();
                        foreach($status as $st){
                            if($st == $cari['status'])
                                echo "<option value='$st' selected>".strtoupper($st)."</option>";
                            else
                                echo "<option value='$st'>".strtoupper($st)."</option>";
                        }
                    ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <input type="submit" class='btn btn-danger' name="edit_upk" value="SIMPAN" />
                </td>
            </tr>
        </table>
    </form>
     <?php
        if(isset($_POST['edit_upk'])){
            $anggota  = $_POST['anggota'];
            $staff  = $_POST['staff'];
            $tanggal  = $_POST['tgl'];
            $status  = $_POST['status'];
            $qEdit = mysqli_query($con,"update upk set tgl_upk='$tanggal',status='$status',anggota_upk='$anggota' where id_upk='$id_upk'");
            if($qEdit)
            alert("Berhasiln Di rubah");
            else alert("gagal di rubah");

            if($status=='jadi'){
                // $insert = mysqli_query($con,"INSERT INTO `anggota` (`id_anggota`, `id_karyawan`, `tgl_anggota`, `anggota_masuk`, `anggota_keluar`, `net_anggota`, `psa`, `prr`, `ppd`, `arta`, `pmb`, `id_cabang`) VALUES (NULL, '$staff', '$tanggal', '$anggota', '0', '$anggota', 0, 0, 0, 0, 0, '$id_cabang');
                // ");
            }


            pindah("$url$menu"."upk&list=cari&tglawal=$tglawal&tglakhir=$tglakhir&cari=FILTER");
            
        }
    }
        else {
            
    ?>
        <form method='post'>
            <table class='table'>
                <tr>
                    <th>No. </th>
                    <th>Center</th>
                    <th>Total UPK</th>
                    <th>Tanggal UPK</th>
                    <th>Detail Center</th>
                </tr>
                <?php
                for ($i = 1; $i <= 15; $i++) {
                ?>
                    <tr id='baris_<?= $i ?>' class=''>
                        <td><?= $i ?></td>
                        <td><input type="number" name='center[]' id="center_<?= $i ?>" onkeyup=" cek_center(<?= $i ?>)" style="width:100px" class='form-control'></td>
                        <td><input type="number" name='total[]' id="total_<?= $i ?>" style="width:100px" class='form-control'></td>
                        <td><input type="DATE" name='tgl[]' id='tgl_<?= $i ?>' style="width:140px" class='form-control'></td>
                        <td>
                            <small>
                                <div id='detail_<?= $i ?>'></div>
                            </small>
                        </td>
                    </tr>
                <?php
                }
                ?>
                <tr>
                    <td colspan=4>
                        &nbsp;
                    </td>
                    <td>
                        <input type="submit" class="btn btn-success" name='simpan_upk' value='Simpan'>
                    </td>
                </tr>
            </table>
        </form>
    <?php } ?>
</div>

<?php
if (isset($_POST['simpan_upk'])) {
    $center = $_POST['center'];
    $hit =  count($center);
    $total = $_POST['total'];
    $tgl  = $_POST['tgl'];
    for ($i = 0; $i < $hit; $i++) {
        if ($center[$i] != null) {
            $cari = mysqli_query($con, "select * from center where id_cabang='$id_cabang' AND no_center='" . sprintf("%03d", $center[$i]) . "'");
            $cari = mysqli_fetch_array($cari);
            $id = $cari['id_karyawan'];
            $sql = "INSERT INTO `upk` (`id_upk`, `no_center`, `tgl_upk`, `anggota_upk`, `id_karyawan`, `id_cabang`) VALUES (NULL, '". sprintf("%03d", $center[$i]) ."', '$tgl[$i]', '$total[$i]', '$id', '$id_cabang')
            ";
            $query = mysqli_query($con, $sql);

            // INSERT INTO `upk` (`id_upk`, `no_center`, `tgl_upk`, `anggota_upk`, `id_karyawan`, `id_cabang`) VALUES (NULL, '001', '2021-07-12', '2', '1', '1')
        }
    }
    if ($query) {
        alert("Berhasil disimpan!");
        
    } else {
        alert("Gagal disimpan,");
    }
    pindah("$url$menu" . "upk");
}

?>

<script>
    function cek_center(no) {
        $(document).ready(function() {
            let center = $("#center_" + no).val();
            var url_link = "<?php $url ?>";
            var cab = "<?= $id_cabang ?>";
            $.get(url_link + "api/cek_center.php?center=" + center + "&cab=" + cab, function(data, status) {
                $("#detail_" + no).html(data);
                var kon  = data;
                kon = kon.trim();
                    if(kon=="Center Tidak ditemukan"){
                        $("#baris_"+no).addClass("merah");
                    }
                    else{
                        $("#baris_"+no).removeClass("merah");
                    }
                
            });
            
            if (center == null) {

            } else {
                $("#tgl_" + no).attr('required', 'required');
                $("#total_" + no).attr('required', 'required');
            }
           
        });
    }
</script>