<div class="row">
	<h3 class="page-header">SETTING CABANG PERTAMA KALI</h3>
    <h3>Sebelumnya silahkan import DETAIL NASABAH SRSS dimenu "MASTER DATA -> Daftar Nasabah"</h3>
	<hr />
    <?php 
    if(isset($_GET['center'])){
        ?>
        <form  method="post"></form>
        <table >
                <tr>
                <td>NO</td>
                <td>ID STAFF</td>
                <td>STAFF</td>
                <td>TOTAL ANGGOTA</td>
            </tr>
            <?php 
            $q = mysqli_query($con,"select  no_center,count(id) as anggota,staff from daftar_nasabah where id_cabang='$id_cabang' group by no_center");
            while($staff = mysqli_fetch_array($q)){
                $kary = mysqli_fetch_array(mysqli_query($con,"select id_karyawan from karyawan where id_cabang='$id_cabang' and nama_karyawan='$staff[staff]'"));
                ?>
                <tr>
                    <td><?=$no++?></td>
                    <td><input type="text" name="no_center[]" value="<?=$staff['no_center']?>" id=""></td>
                    <td><input type="text" name="id_karyawan[]" value="<?=$kary['id_karyawan']?>" id=""><input type="text"  value="<?=$staff['staff']?>" id=""></td>
                    <td><input type="text" name="anggota[]" value="<?=$staff['anggota']?>" id=""></td>
                </tr>
                <?php
            }
            ?>
            <tr>
                <td colspan="3">KONFIRMASI</td>
                <td><input type="submit" value="TAMBAH CENTER" class='btn btn-success' name='tambah_center'></td>
            </tr>

            </table>
            </form>
        <?php
    }
    else{
        ?>
        <form action="" method="post">

            <table class='table'>
                <tr>
                <td>NO</td>
                <td>NAMA STAFF</td>
                <td>NIK</td>
                <td>PASSWORD</td>
            </tr>
            <?php 
            $q1 = mysqli_query($con,"select distinct staff from daftar_nasabah where id_cabang='$id_cabang' and id_karyawan is not null");
            while($staff = mysqli_fetch_array($q1)){
                ?>
                <tr>
                    <td><?=$no++?></td>
                    <td><input type="text" class='form-control' name="nama_karyawan[]" value="<?=$staff['staff']?>" id=""></td>
                    <td><input type="text" class='form-control' name="nik[]" required value="" id=""></td>
                    <td><input type="text" class='form-control' name="password[]" value="123456" id=""></td>
                </tr>
                <?php
            }
            ?>
            <tr>
                <td colspan="3">KONFIRMASI</td>
                <td><input type="submit" value="TAMBAH" class='btn btn-danger' name='tambah_karyawan'></td>
            </tr>

            </table>
            </form>
        <?php
    }
    
    ?>
</div>
<?php 
if(isset($_POST['tambah_karyawan']))
{
    $nama1 = $_POST['nama_karyawan'];
    $hitung = count($nama1);
    for($i=0;$i<$hitung;$i++){
        
        $nik=$_POST['nik'][$i];
        $nama=$_POST['nama_karyawan'][$i];
        $jabatan=1;
        $pass=$_POST['password'][$i];
        $cek_nik=mysqli_query($con,"select * from karyawan where nama_karyawan='$nama' and id_cabang='$id_cabang'");
        $cek_nik1=mysqli_fetch_assoc($cek_nik);
        // echo $cek_nik1['nama_karyawan']." - " . $nama."<br/>";
        if($cek_nik1['nama_karyawan']==$nama){
            // alert(" staff $nama suda ada di db!");
            $tambah="";
        }
        else
        {
            $tambah = "INSERT INTO karyawan (id_karyawan, nik_karyawan, nama_karyawan, id_jabatan, status_karyawan, password, id_cabang) VALUES (NULL, '$nik', '$nama', '$jabatan', 'aktif', MD5('$pass'), '$id_cabang'); ";
            $tambah = mysqli_query($con,$tambah);

        }
        
        // echo $nama."<br/>";
        
            
        
    }
    $no_input = 0;
    $q = mysqli_query($con,"select  no_center,count(id) as anggota,staff,hari from daftar_nasabah where id_cabang='$id_cabang' group by no_center");
            while($staff = mysqli_fetch_array($q)){
                $no_input++;
                $hari = strtolower($staff['hari']);
                $kary = mysqli_fetch_array(mysqli_query($con,"select id_karyawan from karyawan where id_cabang='$id_cabang' and nama_karyawan='$staff[staff]'"));
                mysqli_query($con,"
                INSERT INTO `center` 
                ( `no_center`, `doa_center`, `hari`, `status_center`, `member_center`, `anggota_center`, `center_bayar`, `id_cabang`, `id_karyawan`, `id_laporan`, `jam_center`, `latitude`, `longitude`, `doortodoor`, `blacklist`, `konfirmasi`) VALUES
                ( '$staff[no_center]', 'y', '$hari', 'hijau', '$staff[anggota]', '$staff[anggota]', '$staff[anggota]', '$id_cabang', '$kary[id_karyawan]', '0', '09:00:00', 'null', 'null', 't', 't', 't'); 

                ");
            }
    if($tambah){
        alert("18 staff berhasil ditambahkan!\n $no_input Center telah dimasukan ke DB");
    }
    else
    {
        $_SESSION['pesan']="GAGAL DISIMPAN";
    }
    pindah($url);
    
    
}
	if(isset($_POST['tambah_center'])){
        $center = $_POST['no_center'];
        echo count($center);
    }
			
    ?>
