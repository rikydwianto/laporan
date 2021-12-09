<div class='content table-responsive'>
	<h2 class='page-header'>KETERANGAN TURUN PAR </h2>
	<i>Anggota Turun PAR</i><hr/>
	<div class="col-md-12">
        <form action="" method="post">
            
            <textarea name="keterangan_lain" class='form-control'   id="" cols="30" rows="10">
nama agt : 
ctr: 
total bayar: 
keterangan: </textarea>
            <a href="<?=$url?>" class="btn btn-danger">Tidak Ada</a>
            <input type="submit" class='btn btn-success' value="SIMPAN" name='simpan_ket'>
        </form>
            <?php 
        if(isset($_POST['simpan_ket'])){
            $id = aman($con,$_GET['id_laporan']);
            $ket =  $_POST['keterangan_lain'];
            $q = mysqli_query($con,"UPDATE laporan set keterangan_lain='$ket' where id_laporan='$id'");
            if($q){
                alert("Terima Kasih...");
                pindah($url);
            }
            else{
                echo mysqli_error($con);
            }
        }
        ?>
    </div>
</div>