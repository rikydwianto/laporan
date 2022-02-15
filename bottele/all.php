<?php
	
//error_reporting(0);

// ==== BEGIN / variabel must be adjusted ==== bot1848914315:AAHECZ7cmUAFgsNGvU72spTw9-jv5ubUHWE

$token = "bot"."5176960664:AAH6NoR0pb_gEhRi5vLVzmC-q-4RBciwfic";
$proxy = "";
$mysql_host = "localhost";
$mysql_user = "komw2213";
$mysql_pass = "8dzX23xCGDxi41";
$mysql_dbname = "komw2213_komida";
session_start();
// ==== END / variabel must be adjusted ====

function angka($angka){
  $hasil =  number_format($angka,0,',','.');
  return $hasil;
}

$conn = mysqli_connect($mysql_host, $mysql_user, $mysql_pass);
if(! $conn ) {
  die('Could not connect: ' . mysql_error());
}

$db_selected = mysqli_select_db($conn,$mysql_dbname);
if (!$db_selected) {
  die ('Can \'t use foo : ' . mysqli_error($conn) .'<br>');
}


$updates = file_get_contents("php://input");

$updates = json_decode($updates,true);
$updates1 = json_decode($updates,true);
$lokasi = $updates['message']['location'];
$reply = $updates['message']['message_id'];
$balasan = $updates['message']['reply_to_message'];
$gambar = $updates['message']['photo'];

$pesan = $updates['message']['text'];
$chat_id = $updates['message']['chat']['id'];
$nama-"";
$nik="";
$id=$chat_id;
if (strpos($pesan, "/start") === 0) {
	$location = substr($pesan, 5);
	$pesan_balik[]= "Hallo Selamat datang di Bot Mitra dhuafa report";
	
}
else if (strpos($pesan, "/login") === 0) {
	$data=explode(" ",$pesan)[1];
	$user = explode("_",$data)[0];
	$password = (explode("_",$data)[1]);
	$pesan_balik[]="Sedang dicek.... harap tunggu";
	$cek  =mysqli_query($conn,"select * from karyawan where nik_karyawan='$user' and password=md5('$password')");
	if(!mysqli_num_rows($cek))
	{
		$pesan_balik[]="Data Tidak Ditemukan, Silahkan cek dan Ulangi lagi ...";
	}
	else{
		$data_login = mysqli_fetch_array($cek);
		$nama = $data_login['nama_karyawan'];
		$nik = $data_login['nik_karyawan'];
		$id = $data_login['id_karyawan'];
		$pesan_balik[]= " Login Berhasil ID anda :  $id, Nama : $nama, NIK : $nik"; 
		mysqli_query($conn,"UPDATE `karyawan` SET `id_telegram` = '$chat_id' WHERE `karyawan`.`id_karyawan` = $id;");
	}
}


$ceklogin = mysqli_query($conn,"select * from karyawan join cabang on cabang.id_cabang=karyawan.id_cabang where id_telegram='$chat_id' and status_karyawan='aktif'");
	if(mysqli_num_rows($ceklogin)){
		$data= mysqli_fetch_array($ceklogin);
		$nama = $data['nama_karyawan'];
		$nik = $data['nik_karyawan'];
		$id = $data['id_karyawan'];
		$idcabang = $data['id_cabang'];
		$id_cabang = $idcabang;
		$nama_cabang = strtoupper($data['nama_cabang']);
		//$pesan_balik[]=$nama_cabang;
		$perintah = explode(" ",$pesan)[0];
		$isi_pesan = explode(" ",$pesan)[1];
		if($pesan!=null){
			if($pesan=='/start' || strpos($pesan, "/login") === 0){
				//$pesan_balik[]="Sharelock Lokasi center, lalu masukan nomor center /center no_center";
			}
			else if($pesan=='/help' || $pesan =="/bantu"){
				$pesan_balik[]="Sharelock Lokasi center, lalu Reply Sharelock Tadi masukan no_center. Untuk mencari detail nasabah gunakan /anggota IDanggota";
				$pesan_balik[]=urlencode("start - Memulai Bot restart bot
/login - /login nik_password
/cek_absen - Untuk melihat Absen HRIS
/anggota - cari anggota /anggota noid
Update Lokasi - Sharelock lokasi lalu reply sharelock tersebut masukan no center
gambar - silahkan untuk sisipkan gambar lalu beri keterngan di pisahdengan ( _)
/bantu - Bantuan
/help - bantuan
");
			}
			else if(strpos($pesan, "/anggota") === 0){
				$idnasabah=explode(" ",$pesan)[1];
				$idnasabah = sprintf("%0d",$idnasabah);
				if($idnasabah!=null){
					$pesan_balik[]="mencari id  ..";
					$cari_nasabah = mysqli_query($conn,"SELECT * FROM `daftar_nasabah` WHERE `id_nasabah` = '$idnasabah' and id_cabang='$id_cabang' ORDER BY `id` DESC limit 0,1");
					if(mysqli_num_rows($cari_nasabah)){
						while($dNasabah  =mysqli_fetch_array($cari_nasabah)){
							$date=date_create($dNasabah['tgl_bergabung']);
							$date = date_format($date,"d/m/Y");
$cek_detail = mysqli_query($conn,"select * from detail_simpanan where id_cabang='$id_cabang' and id_nasabah='$idnasabah'");
if(mysqli_num_rows($cek_detail)){
	$pinj = mysqli_fetch_array($cek_detail);
	$json = $pinj['detail_simpanan'];
	$json = json_decode($json,true);
	$wajib = angka($json['wajib']);
	$sukarela = angka($json['sukarela']);
	$pensiun = angka($json['pensiun']);
	$sihara = angka($json['hari_raya']);
	$sisa_os = angka($json['sisa_saldo']);
	$margin = angka($json['margin']);
	$pokok = angka($json['pokok']);
	$pinjaman = angka($json['pinjaman']*1000);
	$pemb = ($json['kode']);
	$rill = $json['rill'];
	$ke = $json['ke'];
	$total_simpanan = angka( $json['wajib'] + $json['sukarela'] + $json['pensiun'] + $json['hari_raya']);
	$tambahan = "
	Simpanan sampai <strong>$pinj[update_simpanan]</strong>
	Wajib <strong>$wajib</strong>
	Sukarela <strong>$sukarela</strong>
	Pensiun  <strong>$pensiun</strong>
	Sihara  <strong>$sihara</strong>
	------------------------- +
	TOTAL   <strong>$total_simpanan</strong>
	
	$pemb <strong>$pinjaman</strong>
	
	Sisa pinjaman <strong>$sisa_os</strong>
	Pokok <strong>$pokok</strong>
	Margin <strong>$margin</strong>
	Ke <strong>$ke</strong>
	Rill  <strong>$rill</strong>
";
	
}
else{
	$tambahan='tidak ada data simpanan';
}
$pesan_balik[]=urlencode("Cabang : <strong>$nama_cabang</strong>
ID : <strong>$dNasabah[id_detail_nasabah]</strong>
HARI : <strong>$dNasabah[hari]</strong> 
Nama Anggota : <strong>$dNasabah[nama_nasabah]</strong> 
Suami Anggota : <strong>$dNasabah[suami_nasabah]</strong> 
NIK KTP : <strong>$dNasabah[no_ktp]</strong> 
No HP : <strong>$dNasabah[hp_nasabah]</strong> 
Bergabung : <strong>$date</strong> 
Alamat : <strong>$dNasabah[alamat_nasabah]</strong> 
Petugas : <strong>$dNasabah[staff]</strong>
$tambahan
							");							
						}
					}
					else{
						$pesan_balik[]="ID $idnasabah tidak ditemukan... mohon periksa kembali ";
					}
				}
				else{
					$pesan_balik[]="Harap memasukan format dengan benar <b>/anggota 0001</b>";
				}
			}
			else if(strpos($pesan, "/cek_laporan") === 0){
				
				//$pesan_balik[]="Cek yang belum Laporan";
				$cek_laporan = mysqli_query($conn,"SELECT * FROM karyawan,jabatan,cabang where karyawan.id_jabatan=jabatan.id_jabatan and karyawan.id_cabang=cabang.id_cabang and karyawan.id_cabang='$id_cabang' and karyawan.status_karyawan ='aktif' and jabatan.singkatan_jabatan='SL' order by karyawan.nama_karyawan asc");
				$total_laporan =0;
				$total_laporan_pending =0;
				$total_selesai_lapora = 0;
				while($cek_staff = mysqli_fetch_array($cek_laporan))
				{
					$cek_l=mysqli_query($conn,"SELECT * from laporan where tgl_laporan=curdate() and id_karyawan='$cek_staff[id_karyawan]'");
					$laporan = mysqli_fetch_array($cek_l);
					if($laporan['status_laporan']=='sukses'){
						$total_laporan++;
						$total_selesai_lapora++;
					}
					elseif($laporan['status_laporan']=='pending')
					{
						$total_laporan_pending++;
						//$pesan_balik[]=urlencode($cek_staff['nama_karyawan']." - Pending");
						$staff_lapang[]=$cek_staff['nama_karyawan']." - Pending";
					}
					else
					{
						//$pesan_balik[]=urlencode($cek_staff['nama_karyawan']." - Belum");
						$staff_lapang[]=$cek_staff['nama_karyawan']." - Belum ";
					}

				}
				
				$q_upk = mysqli_query($conn,"select * from upk join karyawan on karyawan.id_karyawan=upk.id_karyawan where upk.tgl_upk=curdate() and upk.id_cabang='$id_cabang' and upk.status='pending'");
				
				if(mysqli_num_rows($q_upk)){
					while($upk = mysqli_fetch_array($q_upk)){
						$stf[] =  $upk['nama_karyawan']." - ".$upk['no_center']."/".$upk['status'];
					}
					$blm_upk  = ( implode("\n",$stf));
				}
				
				
				$nama_staff  = ( implode("\n",$staff_lapang));
				$total_sudah_absen = $total_laporan;
				$total_staff = mysqli_num_rows($cek_laporan);
				
				$total_belum_laporan = $total_staff - $total_sudah_absen - $total_laporan_pending;
				$total_semua_laporan = $total_sudah_absen + $total_laporan_pending;
				$nama_staff=("$nama_cabang\nBelum Laporan Dan Pending : \n". $nama_staff);
				$pesan_balik[]=urlencode("<pre>$nama_staff \n
Total Staff Lapang : ". $total_staff ."
Total Sudah laporan : ".$total_semua_laporan  ."
 - laporan pending : ".$total_laporan_pending ."
 - laporan Selesai : ".$total_laporan."
Total Belum Laporan : ".$total_belum_laporan ."
=============================
Belum Konfirmasi UPK \n
$blm_upk
				</pre>" );
				
				
				
			}
			else if(strpos($pesan, "/cek_absen") === 0){
				$content=file_get_contents("https://komida.co.id/hris/load_belumabsen2.php");
					$pesan_balik[]="<i>Sedang Mencari ....</i>";
				  //mengubah standar encoding
				  $content=utf8_encode($content);
				//echo count($content);
				  //mengubah data json menjadi data array asosiatif
				  $result=(json_decode($content,true));
				  //print_r($result['data']);
				  $no = 0;
					foreach( $result['data'] as $key)
					{
						if(strtoupper($key['cabang'])==$nama_cabang)
						{
							$hitung[]=$key['no'];;
						}
					}
					
					foreach($hitung as $val){
						$belum_absen[] =  ($result['data'][$val-1]['nama']);
					}
					$belum_absen_ = implode("\n",$belum_absen);
					$pesan_balik[]=urlencode("<pre>$nama_cabang\nYang belum absen masuk HRIS : \n$belum_absen_ </pre>
					\n<b>".count($hitung)." Belum Absen</b>");
					
			}
			else if(strpos($pesan, "/cek_monitoring") === 0){
				$cek_mtr = mysqli_query($conn,"select * from pinjaman where monitoring='belum' and id_karyawan='$id'");
				$num = mysqli_num_rows($cek_mtr);
				if($num){
					/* $pesan_balik[]='Ditemukan monitoring'; */
					while($detail_mtr =mysqli_fetch_array($cek_mtr)){
						$monitoring[]="$detail_mtr[nama_nasabah] - $detail_mtr[center]";
						
					}
					$gabung_mtr = implode("\n",$monitoring);
					$pesan_balik[] = urlencode("<pre>MONITORING : \n$gabung_mtr </pre>");
				}
				else{
					$pesan_balik[]="tidak ditemukan";
				}
				
			}
			else if(strpos($pesan, "/center") === 0){
				$center=explode(" ",$pesan)[1];
				//TIDAK DIGUNAKAN
				if(empty($center)){
					$pesan_balik[]="Format salah, gunakan format /center no_center";
				}
				else{
					//$pesan_balik[]="center $center sedang dicari  ....";
					$center = sprintf("%03d",$center);
					
					 //query tabel produk
					 $sql="SELECT no_center,hari,jam_center,status_center,anggota_center,member_center,karyawan.nama_karyawan,
					 center.latitude,center.longitude,cabang.nama_cabang
					  FROM center 
					  join karyawan on karyawan.id_karyawan=center.id_karyawan 
					  join cabang on cabang.id_cabang = karyawan.id_cabang
					  where  center.no_center = '$center' and center.id_cabang='$id_cabang' and karyawan.id_cabang='$id_cabang'
					  ";
					 // echo $sql;
					 $query=mysqli_query($conn,$sql);
					if(mysqli_num_rows($query)){
						$data = mysqli_fetch_array($query);
						$lat = $data['latitude'];
						$lng = $data['longitude'];
					if(empty($lat) || empty($lng)){
						$maps ="";
					}
					else{
						$maps ="<a href='https://www.google.com/maps/place/" . $lat . "," . $lng . "/" . $lat . "," . $lng . ",17z/data=!3m1!4b1'>https://www.google.com/maps/place/" . $lat . "," . $lng . "/" . $lat . "," . $lng . ",17z/data=!3m1!4b1</a>";
					}
					$cek_anggota = mysqli_query($conn,"select * from daftar_nasabah where no_center='$center' and id_cabang='$id_cabang'");
					$hitung_agt = mysqli_num_rows($cek_anggota);
						$pesan_balik[]=urlencode("<pre>NO Center : $data[no_center]
HARI		: $data[hari]
JAM			: $data[jam_center]
STATUS		: $data[status_center]
ANGGOTA		: $hitung_agt
Staff		: $data[nama_karyawan]
Lat,Lng		: $data[latitude],$data[longitude]
						</pre>
$maps						
						");
						
						$cek_alamat = mysqli_query($conn,"select * from data_center where no_center='$center' and id_cabang = '$id_cabang'");
						if(mysqli_num_rows($cek_alamat)){
						   $alamat = mysqli_fetch_array($cek_alamat);
							$pesan_balik[]=urlencode("<pre>ALAMAT CENTER $center
$alamat[alamat]
$alamat[rt]/$alamat[rw], $alamat[desa], $alamat[kecamatan], 
Keterangan : $alamat[keterangan]
							</pre>
							
							");
						}
						else{
							//$pesan_balik[]="Alamat tidak ditemukan";
						}
						
						
						while($anggota = mysqli_fetch_array($cek_anggota)){
							$nama_anggota[]=$anggota['nama_nasabah']." - ". $anggota['id_nasabah'];
						$nama_anggota1[]=$anggota['id_nasabah']."|".$anggota['nama_nasabah']." | ". $anggota['suami_nasabah']." | ". $anggota['tgl_bergabung']." | ". $anggota['hp_nasabah']." | ". $anggota['no_ktp'];
							
						}
						$nama_anggotas=implode("\n",$nama_anggota);
						/* $pesan_balik[]=urlencode("<pre>Daftar Anggota Center $center
$nama_anggotas
</pre>
						"); */
						
						$output = "gambar_center/center_$center"."_".urlencode($data['nama_karyawan']).".jpg";// lokasi gambar disimpan
						
						$x = 800;
						$y = 800;
						$gambar = imagecreate($x, $y);// buat lebar dan tinggi gambar
						//warna
						$white = imagecolorallocate($gambar, 255,255,255);// ganti warna background gambar
						$black = imagecolorallocate($gambar, 0, 0, 0);
						$font = 'arial.ttf';

						// seting data textnya
						imagestring($gambar, 30, 250, 40,  "DAFTAR NASABAH CENTER $center", $black);
						$batas = 100;
					imagestring($gambar, 10, 40, 70,  "ID  | NAMA  |  SUAMI  . |  BERGABUNG  |  HP  |  NIK", $black);
						for($i=0;$i<count($nama_anggota);$i++){
							
							imagestring($gambar, 10, 40, $batas = $batas + 25,  $nama_anggota1[$i], $black);
							imagestring($gambar, 10, 40, $batas = $batas + 2,  "___________________________________________________________________________", $black);
							
						}


						imagejpeg($gambar,$output);
						imagedestroy($gambar);
						sleep(3);
						$ada='ada';

						$link_web = "https://telegram.mitradhuafa.online/$output";
						$pesan_balik[]=urlencode($link_web);
					}
					else{
						$pesan_balik[]="Center Tidak ditemukan ";
					}

				}
			}
			else{
				if(empty($balasan))
				{
				
					$pesan_balik[]="Perintah tidak ditemukan ketik help / bantu untuk bantuan!";
				}
				else{
					//Jika user mention lokasi
					if(!empty($balasan['location'])){
						$lokasi_balasan = $balasan['location'];
						
						$center=explode(" ",$pesan)[0];
						if(is_numeric($center))
						{
							$center = sprintf("%03d",$center);
							$cari_center = mysqli_query($conn,"SELECT * FROM `center` where `no_center` ='$center' and id_cabang='$id_cabang'");
							if(mysqli_num_rows($cari_center)>0){
								$cek_lok = mysqli_fetch_array($cek_lok);
								mysqli_query($conn,"UPDATE `center` SET `latitude` = '$lokasi_balasan[latitude]',`longitude` = '$lokasi_balasan[longitude]' WHERE `no_center` = $center and id_cabang='$id_cabang' ");
								$pesan_balik[]="Lokasi Center $center Berhasil diperbaharui... thx ";
							}
							else
							{
								$pesan_balik[]="Center $center Tidak ditemukan! Silahkan cek kembali dan ulangi kembali";
							}
							
						}
						else
							$pesan_balik[]="anda memasukan format salah";
					}
					else{
						
						$pesan_balik[]="Perintah  $reply tidak ditemukan ketik help / bantu untuk bantuan !";
					}
				}
		
				
			}
			
		}
		else if($lokasi!=null){
			$pesan_balik[]="Silahkan balas sharelock tadi lalu masukan nomor center";
			//mysqli_query($conn,"INSERT INTO `data_telegram` (`id`, `longitude`, `latitude`, `id_telegram`) VALUES (NULL, '$lokasi[longitude]', '$lokasi[latitude]', '$chat_id');");
		}
		else if($gambar!=null){
			$banyak = count($gambar) -1 ;
			$gambar=$gambar[$banyak];
			$path_gambar = "https://api.telegram.org/$token/getFile?file_id=$gambar[file_id]";
			$cari_path =  file_get_contents($path_gambar);
			$cari_path= json_decode($cari_path,true);
			$cari_path = $cari_path['result'];
			$cari_path = $cari_path['file_path'];
			$url_gambar = "https://api.telegram.org/file/$token/$cari_path";
			$img_detail = $updates['message'];
			$caption = $img_detail['caption'];
			$caption1 = explode("_",$caption)[0];
			$caption2= explode("_",$caption)[1];
			$sql = "INSERT INTO `image` (`id_images`, `url_images`, `kategori_images`, `id_karyawan`, `id_cabang`, `dekripsi_images`,`tanggal_images`) VALUES (NULL,  '$gambar[file_id].jpg ',  '$caption1 ',  '$id ',  '$idcabang ',  '$caption2 ',curdate())";
			mysqli_query($conn,$sql);
			$pesan_balik[]=urlencode("Gambar telah di upload, Kategori $caption1 Deskripsi : $caption2");
			//$pesan_balik[]='berhasil disimpan';
			 $data = file_get_contents($url_gambar);
			 $new = "../laporan-test/assets/img/gambar/$gambar[file_id].jpg";
			 file_put_contents($new, $data);

		}
		
		
	}
	else{
		$pesan_balik[]="ID anda belum terdaftar di database, Silahkan Login terlebih dahulu";
		$pesan_balik[]="Untuk login ketik /login NIK_PASSWORD contoh 1234/2018_sandi123";
		
		
	}
	//$pesan_balik[]=urlencode("<b>news : Anggota telah diupdate sampai tgl 05 Oktober</b>");
	
	for ($i=0; $i <= count($pesan_balik); $i++) { 
	$url = "https://api.telegram.org/$token/sendMessage?parse_mode=html&chat_id=$chat_id&text=$pesan_balik[$i]&reply_message_id=$reply&force_reply=true";
	file_get_contents($url);
	
}
if($ada=='ada'){
	
	
	$url1 = "https://api.telegram.org/$token/sendPhoto";
	$file = $link_web;
	$param = array('chat_id'=>$chat_id,'caption'=>'Daftar Nasabah center '.$center,'photo'=>$file);
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch, CURLOPT_URL,$url1);
	curl_setopt($ch, CURLOPT_POST,1);
	curl_setopt($ch, CURLOPT_POSTFIELDS,$param);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: multipart/form-data'));
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST,'POST');
	return curl_exec($ch);
	curl_close($ch);
	$output="";
}
?>