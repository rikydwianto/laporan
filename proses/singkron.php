<?php 
  $arrContextOptions=array(
	"ssl"=>array(
		"verify_peer"=>false,
		"verify_peer_name"=>false,
	),
); 

// $url 		= "https://www.komida.co.id/mdismo/dev_syn_loan.php?cab=$cab&nikof=$nik";
// // $file 		= file_get_contents("$url");
// $file 		= file_get_contents("$url", false, stream_context_create($arrContextOptions));
	
// $url 		= "https://www.komida.co.id/mdismo/dev_syn_sav.php?cab=$cab&nikof=$nik";
// // $file 		= file_get_contents("$url");
// $file 		= file_get_contents("$url", false, stream_context_create($arrContextOptions));
 $data_karyawan  = (karyawan($con, $id_cabang)['data']);
    for ($i = 0; $i < 1; $i++) {
        $nama_karyawan = $data_karyawan[$i]['nama_karyawan'];
        $nik =  $data_karyawan[$i]['nik_karyawan'];
        $kode_cabang = $data_karyawan[$i]['kode'];
        $url 		= "$url".'api/lain/coba.php';
        $file 		= file_get_contents("$url", false, stream_context_create($arrContextOptions));
        $ser		= "$file";
        $json 		= json_decode($ser);
        $json 		= json_decode($ser);
	    $data		= $json[0]->data;
        foreach($data as $r){
            echo $r->ClientID.'-'.$r->AccountNo.'  = '.$r->Balance ."<br/>";
        }
    }
?>