<?php
//panggil koneksi.php
require_once "../config/seting.php";
require_once "../config/koneksi.php";
require_once("../proses/fungsi.php");
require_once("../model/model.php");

if (isset($_GET['id'])) {

    $id  = aman($con,$_GET['id']);
    $q = mysqli_query($con, "select * from pinjaman left join karyawan on karyawan.id_karyawan=pinjaman.id_karyawan where pinjaman.id_pinjaman='$id'  order by karyawan.nama_karyawan asc");
    if(mysqli_num_rows($q)<1){
        echo "<h1>Data Tidak ditemukan</h1>";
    }
    else{
        $data  = mysqli_fetch_array($q);
        ?>
        <table class='table'>
            <tr>
                <td>
                    <table>
                        <tr>
                            <td>ID ANGGOTA</td>
                            <td>:</td>
                            <td><?=$data['id_detail_nasabah']?></td>
                        </tr>
                    
                        <tr>
                            <td>NO. PINJAMAN</td>
                            <td>:</td>
                            <td><?=$data['id_detail_pinjaman']?></td>
                        </tr>
                    
                        <tr>
                            <td>NAMA ANGGOTA</td>
                            <td>:</td>
                            <td><b><?=$data['nama_nasabah']?></b></td>
                        </tr>
                    
                        <tr>
                            <td>CENTER</td>
                            <td>:</td>
                            <td><?=$data['center']?></td>
                        </tr>
                        <tr>
                            <td>NO HP</td>
                            <td>:</td>
                            <td><?=$data['no_hp']?></td>
                        </tr>
                        <tr>
                            <td>Status Monitoring</td>
                            <td>:</td>
                            <td><?=$data['monitoring']?></td>
                        </tr>
                        
                        
                    </table>
                </td>
                <td>
                    <table>
                        <tr>
                            <td>PINJAMAN KE</td>
                            <td>:</td>
                            <td><?=$data['pinjaman_ke']?></td>
                        </tr>
                        <tr>
                            <td>PRODUK</td>
                            <td>:</td>
                            <td><?=$data['produk']?></td>
                        </tr>
                        <tr>
                            <td>TUJUAM</td>
                            <td>:</td>
                            <td><?=$data['tujuan_pinjaman']?></td>
                        </tr>
                        <tr>
                            <td>JUMLAH PINJAMAN</td>
                            <td>:</td>
                            <td><?=$data['jumlah_pinjaman']?></td>
                        </tr>
                        <tr>
                            <td>JANGKA WAKTU</td>
                            <td>:</td>
                            <td><?=$data['jk_waktu']?></td>
                        </tr>
                        <tr>
                            <td>TGL CAIR</td>
                            <td>:</td>
                            <td><?=$data['tgl_pencairan']?></td>
                        </tr>
                       
                    </table>
    

                </td>
            </tr>
            <tr>
                <td></td>
                <td><h3>Officer,<br/><br/><br/><?=$data['nama_karyawan']?></h3></td>
            </tr>
        </table>
        <?php
    }
}
