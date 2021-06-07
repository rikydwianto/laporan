<?php
require_once "../config/seting.php";
require_once "../config/koneksi.php";
require_once("../proses/fungsi.php");
require_once("../model/model.php");

$dbhost =$host ;
$dbuser =$username;
$dbpass =$password;
$dbname =$db_name;
$dbdsn = "mysql:dbname=$dbname;host=$dbhost";
try {
  $db = new PDO($dbdsn, $dbuser, $dbpass);
} catch (PDOException $e) {
  echo 'Connection failed: '.$e->getMessage();
}
$wil=array(
	2=>array(5,'Kota/Kabupaten','kab'),
	5=>array(8,'Kecamatan','kec'),
	8=>array(13,'Kelurahan','kel')
);
if (isset($_GET['id']) && !empty($_GET['id'])){
	$n=strlen($_GET['id']);
	$query = $db->prepare("SELECT * FROM wilayah_2020 WHERE LEFT(kode,:n)=:id AND CHAR_LENGTH(kode)=:m ORDER BY nama");
	$query->execute(array(':n'=>$n,':id'=>$_GET['id'],':m'=>$wil[$n][0]));
	echo"<option value=''>Pilih {$wil[$n][1]} {$wil[$n][0]}</option>";
	while($d = $query->fetchObject())
		echo "<option value='{$d->kode}'>{$d->nama}</option>";
}else{
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Data Daerah</title>
		<style>
			td,select {width:240px;}
			#kab_box,#kec_box,#kel_box{display:none;}
		 </style>
		<script>
		var my_ajax=do_ajax();
		var ids;
		var wil=new Array('kab','kec','kel');
		function ajax(id){
			if(id.length<13){
				ids=id;
				var url="?id="+id+"&sid="+Math.random();
				my_ajax.onreadystatechange=stateChanged;
				my_ajax.open("GET",url,true);
				my_ajax.send(null);
			}
		}
		function do_ajax(){
			if (window.XMLHttpRequest) return new XMLHttpRequest();
			if (window.ActiveXObject) return new ActiveXObject("Microsoft.XMLHTTP");
			return null;
		}
		function stateChanged(){
			var n=ids.length;
			var w=(n==2?wil[0]:(n==5?wil[1]:wil[2]));
			var data;
			if (my_ajax.readyState==4){
				data=my_ajax.responseText;
				document.getElementById(w).innerHTML = data.length>=0 ? data:"<option selected>Pilih Kota/Kabfff</option>";
				<?php foreach($wil as $k=>$w):?>
					document.getElementById("<?php echo $w[2];?>_box").style.display=(n><?php echo $k-1;?>)?'table-row':'none';
				<?php endforeach;?>
			}
		}
		</script>
	</head>
	<body>
		<table>
			<tr>
			<td>Provinsi</td>
			<td>
				<select id="prov" onchange="ajax(this.value)">
					<option value="">Provinsi</option>
					<?php 
					$query=$db->prepare("SELECT kode,nama FROM wilayah_2020 WHERE CHAR_LENGTH(kode)=2 ORDER BY nama");
					$query->execute();
					while ($data=$query->fetchObject())
						echo '<option value="'.$data->kode.'">'.$data->nama.'</option>';
					?>
				<select>
			</td>
		</tr>
		<?php foreach($wil as $w):?>
		<tr id='<?php echo $w[2];?>_box'>
			<td><?php echo $w[1];?></td>
			<td>
				<select id="<?php echo $w[2];?>" onchange="ajax(this.value)">
					<option value="">Pilih <?php echo $w[1];?></option>
				</select>
			</td>
		</tr>
		<?php endforeach;?>
<?php } ?>
