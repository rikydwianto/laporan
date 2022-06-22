<h3>INPUT ID TOPUP KHUSUS</h3>
<form action="" method="post">
    <textarea name="tpk" id="" cols="30" placeholder="AGT/051/03/367-009570
AGT/051/03/367-009569
" class='form-control' rows="10"></textarea>
    <input type="submit" value="SIMPAN" name='input_tpk' class='btn btn-danger btn-lg'>
</form>
<?php
if(isset($_POST['input_tpk'])){
    $text = $_POST['tpk'];
    $pecah = explode(PHP_EOL,$text);
    foreach($pecah as $i){
       $id_nasabah = trim($i);
    if($id_nasabah!=""){
        $ID = (int)explode("-",$id_nasabah)[1];
        $del =mysqli_query($con,"delete from tpk where id_cabang='$id_cabang' and id_nasabah='$ID' and id_detail_nasabah='$id_nasabah'");
           $q= mysqli_query($con,"INSERT INTO `tpk` (`id_nasabah`, `id_detail_nasabah`, `id_cabang`) VALUES ('$ID', '$id_nasabah', '$id_cabang'); ");
    }
    }

    if($q){
        pesan("Berhasil disimpan",'success');
    }
}