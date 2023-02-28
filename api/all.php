<?php
header("Content-Type: application/json; charset=UTF-8");
error_reporting(0);
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
                $pesan="Detail";
                $query = mysqli_query($con, "SELECT kode_cabang,nama_karyawan,nama_jabatan,nik_karyawan,nama_cabang,singkatan_jabatan,singkatan_cabang,status_karyawan FROM karyawan,jabatan,cabang,wilayah where karyawan.id_jabatan=jabatan.id_jabatan and karyawan.id_cabang=cabang.id_cabang 
                            and cabang.id_wilayah=wilayah.id_wilayah
                            and karyawan.id_karyawan='$id' ");
                    $data = mysqli_fetch_assoc($query);
                $url_p="https://laporan-baru.site/rikydwianto.jpg";
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
               
                $q="SELECT $table.nama_nasabah,$table.suami_nasabah,$table.no_center,$table.kelompok,$table.id,$table.id_detail_nasabah,$table.no_ktp,$table.hp_nasabah from $table  where $berdasarkan like '$cari%'and $table.id_cabang='$id_cabang' group by id_detail_nasabah order by nama_nasabah asc limit 0,50";
                $query = mysqli_query($con, "$q");
                $array=array();
                while($data=mysqli_fetch_assoc($query)) $array[]=$data; 
               
                    
                $data = $array;
                }
            }
            else if($menu=="statistik"){
                $kode ="200";
                $pesan="Statistik Cabbang";
                //INFORMASI PAR
                $q="SELECT COUNT(*) AS total_par,tgl_input, SUM(`sisa_saldo`) AS total_balance FROM `deliquency` WHERE id_cabang=$id_cabang AND tgl_input=(SELECT MAX(`tgl_input`) FROM deliquency WHERE id_cabang=$id_cabang)";
                $q = mysqli_fetch_assoc((mysqli_query($con,$q)));
                //MEMBER
                $q1=mysqli_query($con,"select sum(total_nasabah) as member from total_nasabah where  id_cabang=$id_cabang");
                $hit1 = mysqli_fetch_array($q1);

                //INFORMASI CENTER
                $q2=mysqli_query($con,"select count(no_center) as center from center where id_cabang=$id_cabang");
                $hit2 = mysqli_fetch_array($q2);
                	
                $qpin = mysqli_query($con,"SELECT id_karyawan,COUNT(*) as total
                    FROM pinjaman WHERE monitoring='belum' and id_cabang='$id_cabang' and input_mtr='sudah' GROUP BY id_cabang ");
                $mon = mysqli_fetch_array($qpin);
                $mon1 = $mon['total'];


                //AM AK DAN NETT
                $anggota="SELECT sum(anggota.anggota_masuk) as masuk,sum(anggota.anggota_keluar) as keluar,sum(anggota.net_anggota) as nett,sum(anggota.psa) as psa,sum(anggota.ppd) as ppd,sum(anggota.prr) as prr,sum(anggota.arta) as arta,sum(anggota.pmb) as pmb,karyawan.nama_karyawan FROM `anggota`,karyawan where anggota.id_karyawan=karyawan.id_karyawan and karyawan.id_cabang=$id_cabang and anggota.tgl_anggota >= '".date("Y-m-01")."' and anggota.tgl_anggota <= '".date("Y-m-31")."' ";

                $stak=mysqli_query($con,$anggota);
                $stak = mysqli_fetch_assoc($stak);
                

                $data=$q;
                $data['total_member']=$hit1['member'];
                $data['total_center']= $hit2['center'];
                $data['total_monitoring']= $mon1;
                $data['total_am']=$stak['masuk']+0;
                $data['total_ak']=$stak['keluar']+0;
                $data['total_nett']=$stak['nett']+0;

            }
            else if($menu=="detail_nasabah"){
                $pesan="detail nasabah";
                $kode=200;
                $id_nsb = aman($con,$_POST['id_nsb']);
                $table="daftar_nasabah";
                $q="SELECT * from $table  where id = '$id_nsb' and id_cabang='$id_cabang' ";
                $query = mysqli_query($con, "$q");
                $array=array();
                $data=mysqli_fetch_assoc($query);
                 $array=$data; 
                 $text = "
CTR/KELP            : $data[no_center]/$data[kelompok]
ID Detail           : $data[id_detail_nasabah]
Nama Nasabah        : $data[nama_nasabah]
Nama Suami          : $data[suami_nasabah]
NO HP               : $data[hp_nasabah]
NO KTP              : $data[no_ktp]
TGL Bergabung       : $data[tgl_bergabung]
Alamat              : $data[alamat_nasabah]

STAFF        : $data[staff]
Hari Minggon : $data[hari]

                 ";
                
               
                
                $data = $array;
                $data['text']=$text;

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
