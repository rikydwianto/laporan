<?php
header("Content-Type: application/json; charset=UTF-8");

//panggil koneksi.php
require_once "../config/seting.php";
require_once "../config/koneksi.php";
require_once("../proses/fungsi.php");
require_once("../model/model.php");
$kode = '400';
@$token  = aman($con,$_POST['token']);
@$id  = aman($con,$_POST['id']);
@$id_cabang  = aman($con,$_POST['id_cabang']);
@$menu  = aman($con,$_POST['menu']);

$data =null;
$text=null;
if($id==""){
    $pesan="ID KOSONG";
}
else{
    if($token=="" ){
        $kode ="402";
        $pesan ="TOKEN KOSONG!";
    }
    else{
    
        if($token==$TOKEN){
           
            if($menu=="detail_login"){
                $kode="200";
                $pesan="berhasil";
                $query = mysqli_query($con, "SELECT kode_cabang,nama_karyawan,nama_jabatan,nik_karyawan,nama_cabang,singkatan_jabatan,singkatan_cabang,status_karyawan FROM karyawan,jabatan,cabang,wilayah where karyawan.id_jabatan=jabatan.id_jabatan and karyawan.id_cabang=cabang.id_cabang 
                            and cabang.id_wilayah=wilayah.id_wilayah
                            and karyawan.id_karyawan='$id' ");
                    $data = mysqli_fetch_assoc($query);
                $url_p="https://lh3.googleusercontent.com/jDa12L7bcZC3cstQA5kK9qBSoNCAhynP8mP6Xm3u4Dt_z6pQhXGCYQygfbrBimgJjkp3gIMymacJiqg4PH-Le5ypbSQchERnxgoNP3sPlP2i02hkdpWX-KTXBb3KfCQmUyBgbHMGhojf1rCIanAhgLPXjiEtGE3PuF9rQ4ToLVTqIYP8KiFcPKp491MwlBLJAJqKAwZqx1DGp1Bn6BOqJMAljVMbEqQjog1hYrHdkrPWFeoJTVvPU4ps16QFEK1IP9bDmMaJ_N3GJGV_40df9jS1YJ7fXcgUIvLikgnalRgSjD9IWfkNZxJVM4aegq57WbvZsG3muw6EaNp9GC3lJtVErvzfI8r9hBaydAn0P13y6uMSR4QwcVgjm1Qwd_vw0rZ_JShNxjuD3JEnAbP0jeGrJbhiV5rZt0PFi302PMRpvfubKljgkTB3fS4mcKdUwqOOPXSFH1c37rVNEbd8cHik-IyQrjVtHw2dU4lK9rsz7pQbE4ciujH76H87__1Xaymyqr9ANGl0jOedVhvttvKjAMMWfo3nkbDcJgHFpVHvNgw6quoVBf5mEoKf-tlTSuF9YP4evO8Hb2CjeCeohGPHiZfYmf-HZAb44XUgOhHrOUVJo-Squ9zrfvg4XZ36A4y-2zc9g1YhvKmcG0yiJTEsktFCqwqCmH-apTmsiwxgSVkMKC8ZFSAZujtYSEBysy3tZPIv--5EATBje68gA_UTBORg3ejD4OSr09IHKpjlGqZAoaHIyiqiKmZtU2A5ht44CBI9bAauqLIXotcI1bcLJus5oYu1G7JilUSclC4D3eklimbWAxDtijvof1_VnUiMXUzhlrgmrx12-7BEK8c89qFkvAuXnk3HlCs3IrIBhJGW1hdZ3OjidOp8M1SmmR68mMptzbwOvXMb6Qm5Yh9zn1NTte5nXzGSE-cg-MBrqTLz2qyBqa8kkw5W6jgznp69gzrm8bEepAMHZ-_Yl3RXhcM6BhANqBlRq3bFXK4IzXAkP9wiM1M8FlhYfp3E3pEF4x8WfIdlV0mFw6GKbxUgdXy2LML3ZOPdbOihUZo093ByhsgUuQ-TOvr5ez3u5RcQ5dLGruVTVzl3O6mS6UNpduTYEDTjlEA=w1366-h616-s-no?authuser=0";
                $data['photo']="$url_p";
            }
            else if($menu=="cari_nasabah"){
                $table = "daftar_nasabah";
                $cari = aman($con,$_POST['cari']);
                if($cari==null || $cari ==""){
                    $kode="200";
                    $pesan="tidak ditemukan";
                    $data = "[]";
                }
                else{
                    $kode="200";
                $pesan="ditemukan";
                @$berdasarkan  = aman($con,$_POST['kategori']);
               
                $q="SELECT $table.nama_nasabah,$table.suami_nasabah,$table.no_center,$table.kelompok,$table.id,$table.id_detail_nasabah,$table.no_ktp,$table.hp_nasabah from $table  where $berdasarkan like '$cari%'and $table.id_cabang='$id_cabang' group by id_detail_nasabah order by nama_nasabah asc limit 0,20";
                $query = mysqli_query($con, "$q");
                $arra = array();
                $array=array();
                while($data=mysqli_fetch_assoc($query)) $array[]=$data; 
               
                    
                $data = $array;
                }
            }
            else{
                $kode='404';
                $pesan="Permintaan tidak jelas";
            }
            
        }
        else
        {
            $pesan="TOKEN TIDAK SESUAI!";
        }
    }
}


echo json_encode(array("kode"=>$kode,"pesan"=>"$pesan","data"=>$data));
