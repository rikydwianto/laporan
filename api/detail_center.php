<?php
 //panggil koneksi.php
require_once "../config/seting.php";
require_once "../config/koneksi.php";
require_once("../proses/fungsi.php");
require_once("../model/model.php");
$center = sprintf("%03d",$_GET['center']);
$id_cabang = $_GET['cab'];
 //query tabel produk
 $sql="SELECT no_center,hari,jam_center,status_center,anggota_center,member_center,karyawan.nama_karyawan,
 center.latitude,center.longitude,cabang.nama_cabang
  FROM center 
  join karyawan on karyawan.id_karyawan=center.id_karyawan 
  join cabang on cabang.id_cabang = karyawan.id_cabang
  where  center.no_center = '$center' and center.id_cabang='$id_cabang'
  ";
 // echo $sql;
 $query=mysqli_query($con,$sql);
if(mysqli_num_rows($query)){    
    $data = mysqli_fetch_array($query);
    ?>
    <h3>Detail Center <?=$data['no_center']?></h3>
    <table class='table-bordered'>
        <tr valign=top style='margin:5px;'>
            <td>
                Staff : <?=$data['nama_karyawan']?> <br/>
                Hari : <?=$data['hari']?> <br/>
                Jam : <?=$data['jam_center']?> <br/>
                Anggota : <?=$data['member_center']?> <br/>
                Status : <?=$data['status_center']?> <br/>
                <?php if($data['latitude']!= null || $data['longitude'] !=NULL) : ?>
                <a href="<?=link_maps($data['latitude'],$data['longitude'])?>" class='btn btn-info'> <i class='fa fa'></i> Arahkan</a>
                <?php endif; ?>
            </td>
            <td>
                <?php
                $cek_alamat = mysqli_query($con,"select * from data_center where no_center='$center' and id_cabang='$id_cabang'");
                if(mysqli_num_rows($cek_alamat)){
                   $alamat = mysqli_fetch_array($cek_alamat);
                   ?>
                    Kecamatan  : <?=$alamat['kecamatan']?> </br>
                    Desa  : <?=$alamat['desa']?> </br>
                    RT/RW  : <?=$alamat['rt']?>/<?=$alamat['rw']?> </br>
                    
                    Alamat  : <?=$alamat['alamat']?> </br>
                    Keterangan  : <?=$alamat['keterangan']?> </br>
                    Longitude  : <?=$data['longitude']?> </br>
                    Latitude  : <?=$data['latitude']?> </br>
                    <?php
                }
                else{
                    echo "&nbsp;<small><u>Tidak Ada Alamat Tersimpan</u></small>";
                }
                ?>

            </td>
        </tr>
    </table>
    


    <a href="#" onclick="close_center()" class='btn btn-danger'>Close</a>
    <?php
}
else{
    echo "Center Tidak ditemukan";
}
//data array
 
//mengubah data array menjadi json
?>
