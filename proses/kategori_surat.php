<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalku">
    <i class="fa fa-plus"></i> Kategori
</button>
<?php 
if(isset($_GET['hapus'])){
    $id = aman($con,$_GET['id']);
    $hapus = mysqli_query($con,"DELETE from kategori_surat where id='$id' and id_cabang='$id_cabang'");
    if($hapus){
        pesan("Berhasil dihapus",'success');
    }
    else{
        pesan("Gagal disimpan : ". mysqli_error($con),'danger');
    }
}
else if(isset($_GET['edit'])){
    $id = aman($con,$_GET['id']);
    $qkat=mysqli_query($con,"select * from kategori_surat where id_cabang='$id_cabang' and id='$id'");
    $kat =mysqli_fetch_array($qkat);
    if(isset($_POST['edit_kategori'])){
        $kategori = $_POST['kategori'];
         $singkat = $_POST['singkat'];
         $template = $_POST['template'];
         $edit = mysqli_query($con,"UPDATE `kategori_surat` SET `template_surat` = '$template', kategori_surat='$kategori', kode_kategori='$singkat' WHERE `id` = '$id' and id_cabang='$id_cabang'; ");
         if($edit){
             pesan("Berhasil diupdate",'success');
             pindah($url.$menu."surat&kategori");
         }
    }
    ?>
     <form action="" method="post">
        <table>
            <tr>
                <td>Kategori</td>
                <td><input type="text" value="<?=$kat['kategori_surat']?>" class="form-control" name="kategori" id=""></td>
            </tr>
            <tr>
                <td>Singkatan</td>
                <td><input type="text" value="<?=$kat['kode_kategori']?>" class="form-control" name="singkat" id=""></td>
            </tr>
            </tr>
            <tr>
                <td>Tamplate</td>
                <td><textarea name="template"  id="" cols="30" rows="10"><?=$kat['template_surat']?></textarea></td>
            </tr>
            <tr>
                <td></td>
                <td><input type="submit" class='btn btn-danger' value="SIMPAN" name='edit_kategori'></td>
            </tr>
        </table>
       
        
    </form>
    <?php
}
?>
<table class='table'>
    <tr>
        <th>NO</th>
        <th>KATEGORI</th>
        <th>KODE</th>
        <th>TAMPLATE</th>
        <th>#</th>
    </tr>
    <?php 
    $qkat = mysqli_query($con,"select * from kategori_surat where id_cabang='$id_cabang'");
    while($kat = mysqli_fetch_array($qkat)){
        ?>
        <tr>
            <td><?=$no++?></td>
            <td><?=$kat['kategori_surat']?></td>
            <td><?=$kat['kode_kategori']?></td>
            <td><?=$kat['template_surat']?></td>
            <td>
                <a href="<?=$url.$menu?>surat&kategori&edit&id=<?=$kat['id']?>" class="btn btn-info"> <i class="fa fa-pencil"></i></a>
                <a href="<?=$url.$menu?>surat&kategori&hapus&id=<?=$kat['id']?>" onclick="return window.confirm('Apakah anda yakin menghapus kategori <?=$kat['kategori_surat']?> ini?')" class="btn btn-danger"> <i class="fa fa-times"></i></a>
            </td>
        </tr>
        <?php
    }
    ?>
</table>