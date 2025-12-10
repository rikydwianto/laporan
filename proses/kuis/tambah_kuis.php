<h2 style="text-align:center ;">TAMBAH KUIS</h2>
<?php 
if(isset($_POST['buat_kuis'])){
    $nama_kuis = aman($con,$_POST['nama_kuis']);
    $pembuat = aman($con,$_POST['pembuat']);
    $tgl = aman($con,$_POST['tgl']);
    $lama = aman($con,$_POST['lama']);
    $status = aman($con,$_POST['status']);
    $insert = mysqli_query($con,"INSERT INTO `kuis` (`nama_kuis`, `nama_karyawan`, `tgl_kuis`, `waktu`, `status`, `acak`, `id_cabang`) VALUES ('$nama_kuis', '$pembuat', '$tgl', '$lama', '$status', 'ya', '$id_cabang'); 
    ");
    if($insert)
    {
        pesan("Berhasil menambahkan soal",'success');
        pindah("$url$menu".'kuis');
    }
    else{
        pesan("Gagal menghapus : ". mysqli_error($con),'danger');
    }
}
?>
<form method="post">

    <table class='table table-bordered'>
        <tr>
            <td>NAMA KUIS</td>
            <td><input type="text" name="nama_kuis" class="form-control"></td>
        </tr>
        <tr>
            <td>PEMBUAT</td>
            <td><input readonly type="text" value='<?=$d['nama_karyawan']?>' name="pembuat" class="form-control"></td>
        </tr>
        <tr>
            <td>TANGGAL KUIS</td>
            <td><input type="date" value='<?=date('Y-m-d')?>' name="tgl" class="form-control"></td>
        </tr>
        <tr>
            <td>LAMA(MENIT)</td>
            <td><input type="number"  value="20" name="lama" class="form-control"></td>
        </tr>
        <tr>
            <td>STATUS</td>
            <td>
                <select name="status" required id="" class='form-control'>
                    <option value="tidakaktif">TIDAK AKTIF</option>
                    <option value="aktif">AKTIF</option>
                </select>
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>
                <input type="submit" value="BUAT" name="buat_kuis" class='btn btn-danger'>
            </td>
        </tr>
    </table>
</form>