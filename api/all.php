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
                $data = detail_karyawan($con,$id);
                
            }else{
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
