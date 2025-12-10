<?php 
if(isset($_GET['tambah'])){
   include("./proses/kuis/tambah_kuis.php");
}
elseif(isset($_GET['edit'])){
   include("./proses/kuis/edit_kuis.php");
}
elseif(isset($_GET['hapus'])){
   // include("./proses/kuis/tambah_kuis.php");
   $id = aman($con,$_GET['idkuis']);
   $delete = mysqli_query($con,"DELETE FROM kuis where  id_kuis='$id'");
   if($delete)
   {
       pesan("Berhasil Menghapus KUIS",'success');
       pindah("$url$menu".'kuis');
   }
   else{
       pesan("Gagal menghapus : ". mysqli_error($con),'danger');
   }

}
?>