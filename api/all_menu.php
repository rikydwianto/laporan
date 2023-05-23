<?php
// header("Content-Type: application/json; charset=UTF-8");
error_reporting(0);
//panggil koneksi.php
require_once "../config/seting.php";
require_once "../config/koneksi.php";
require_once("../proses/fungsi.php");
require_once("../model/model.php");
$kode = '400';
$TOKEN_TELE = $token;
@$token  = aman($con,$_POST['token']);

@$id  = aman($con,$_POST['id']);
@$id_cabang  = aman($con,$_POST['id_cabang']);
@$menu  = aman($con,$_POST['menu']);
@$token_fcm  = aman($con,$_POST['token_fcm']);
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
            if($menu=="stok"){
                
                $pesan ="STOK";
                $q= mysqli_query($con,"SELECT * FROM cetakan c where c.`id_cabang`='$id_cabang'");
                while($data=mysqli_fetch_assoc($q)) {$array[]=$data; }
               
                    
                $data = $array;

            }
            else if($menu=="cart_stok"){
                $cek_cart = mysqli_query($con,"select * from ambil_stok where id_karyawan ='$id' and id_cabang='$id_cabang' and tanggal=curdate() and status='pending'");
                if(mysqli_num_rows($cek_cart)){
                   $cart = mysqli_fetch_array($cek_cart);
                   $id_cart = $cart['id_ambil'];
                }
                else{
                    $insert = mysqli_query($con,"INSERT INTO `ambil_stok` (`id_karyawan`, `id_cabang`, `tanggal`, `status`) VALUES ('$id', '$id_cabang', curdate(), 'pending') ");
                    $id_cart = mysqli_insert_id($con);
                }
                $nama =aman($con,$_POST['nama_cetakan']);
                $qty =aman($con,$_POST['qty']);
                $id_cetakan =aman($con,$_POST['id_cetakan']);

                $cek_item_cart = mysqli_query($con,"select * from detail_ambil_stok where id_ambil='$id_cart' and id_cetakan='$id_cetakan'");
                if(!mysqli_num_rows($cek_item_cart)){
                    $insert_item = mysqli_query($con,"INSERT INTO `detail_ambil_stok` (`id_ambil`, `id_cetakan`, `qty`, `nama_cetakan`) VALUES ('$id_cart', '$id_cetakan', '$qty', '$nama'); ");
                }
                else{
                    $update = mysqli_query($con,"update detail_ambil_stok set qty=qty+$qty where id_cetakan='$id_cetakan' and id_ambil='$id_cart'");
                }

                
                $pesan = "Berhasil menambahkan kedalam cart";
                $data = array(
                    'id'=>$id_cart,
                    'nama_cetakan'=>$nama,
                    'qty'=>$qty

                );


            }
            else{
                $kode="404";
                $pesan="menu tidak ditemukan";
            }
        }
        else
        {
            $pesan="TOKEN TIDAK SESUAI!";
        }
    }
}


echo json_encode(array("kode"=>$kode,"pesan"=>"$pesan","data"=>$data));



