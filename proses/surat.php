<div class='content table-responsive'>
	<h2 class='page-header'>SURAT</h2>
    
<?php 
$urut = mysqli_fetch_array(mysqli_query($con,"select max(no_urut) as no_urut from surat where id_cabang='$id_cabang' AND YEAR(tgl_surat) = YEAR(curdate()) "));
$urut = ($urut['no_urut']==NULL?0:$urut['no_urut']);

?>
<?php 
$kode='';
if(isset($_POST['simpan_kategori'])){
    $kategori = $_POST['kategori'];
    $singkat = $_POST['singkat'];
    $cek_kategori =mysqli_num_rows(mysqli_query($con,"select * from kategori_surat where kategori_surat='$kategori' and kode_kategori='$singkat' and id_cabang='$id_cabang'"));
    // echo $cek_kategori;
    if($cek_kategori<1){
        $input_kategori = mysqli_query($con,"INSERT into kategori_surat(`kategori_surat`,`kode_kategori`,`id_cabang`) values('$kategori','$singkat','$id_cabang')");
        
    }
    else{
        // echo "Sudah ada diDB";
    }
    $selected="selected";
    $kode="/".$singkat;
    
}
$romawi = ['','I','II','III','IV','V','VI','VII','VIII','IX','X','XI','XII',''];
?>
<a href="<?=$url.$menu?>surat" class="btn btn-danger"> <i class="fa fa-plus" ></i> Surat</a>
<a href="<?=$url.$menu?>surat&lihat" class="btn btn-info">Lihat Data</a>
<a href="<?=$url.$menu?>surat&kategori" class="btn btn-success">Kategori</a>

<br/>
<br/>
<br/>

   <?php 
   if(isset($_GET['kategori'])){
       include"proses/kategori_surat.php";
   }
   elseif(isset($_GET['lihat'])){
       include"proses/lihat_surat.php";
   }
   else{
       ?>
        <div class='col-md-12'>
            <form action="" method="post">

                    <table class='table'>
                        <tr>
                            <td colspan="2"><h2 style="text-align:center">Tambah Surat</h2></td>
                            
                        </tr>
                        <tr>
                            <td>NO SURAT/TANGAL</td>
                            <td>
                                <input type="text" class='form-control' onchange="surat()"  name="no_urut" value="<?=sprintf("%03d", $urut+1)?>" style='width:100px; padding:auto;margin-right:50px;float:left; ' id="no_urut"> 
                                <input type="text" readonly id='no_surat'  title="KLIK UNTUK MENYALIN KODE" value="<?=sprintf("%03d", $urut+1)?>/<?=$kode_cabang?>-<?=$singkatan_cabang?><?=$kode?>/<?=$romawi[sprintf("%0d",date('m'))]?>/<?=date("Y")?>" class='form-control' name="no_surat"  style='width:500px; float:left;margin-right:10px; ' > 
                                <a href="" id='copi'  class="btn btn-danger btn-sm" style=' float:left;margin-right:50px; ' >salin</a>
                                <input type="date" class='form-control' name='tgl' onchange="surat()"  id='tgl' value='<?=date("Y-m-d")?>' style='width:200px;'>
                            </td>
                        </tr>
                        <tr>
                            <td>PERIHAL </td>
                            <td>
                                <input type="text"  class='form-control' name="perihal" id="">
                            </td>
                        </tr>
                        <tr>
                            <td>KATEGORI</td>
                            <td>
                                <select name='kategori' onchange="surat()" id='kategori' required class='form-control' style='float:left;width:400px'>
                                    <option value="">Pilih kategori</option>
                                    <?php 
                                    $qkat = mysqli_query($con,"select * from kategori_surat where id_cabang='$id_cabang'");
                                    while($kat = mysqli_fetch_array($qkat)){
                                        if($_POST['singkat']==$kat['kode_kategori']){
                                            ?>
                                            <option value="<?=$kat['kode_kategori']?>" selected><?=$kat['kode_kategori']?> - <?=$kat['kategori_surat']?></option>
                                            <?php
                                        }
                                        else{
                                        ?>
                                    <option value="<?=$kat['kode_kategori']?>"><?=$kat['kode_kategori']?> - <?=$kat['kategori_surat']?></option>
                                        <?php

                                        }
                                    }
                                    
                                    ?>
                                </select>
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalku">
                                    <i class="fa fa-plus"></i> Kategori
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td>ISI SURAT</td>
                            <td>
                            <textarea name="isi_surat" id="" cols="30" rows="20" class="form-control"></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td>TYPE</td>
                            <td>
                            <select name='kategori_surat'  class='form-control'>
                                <option value="keluar">Surat Keluar</option>
                                    <option value="masuk">Surat Masuk</option>
                                    
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>
                                <input type="submit" name='tambah' onclick="surat()" value="SIMPAN" class='btn btn-danger btn-lg'>
                            </td>
                        </tr>
                    </table>
                </form>
                

        </div>
       <?php
   }

   if(isset($_POST['tambah'])){
       $no_urut = $_POST['no_urut'];
       $no_surat=$_POST['no_surat'];
       $perihal = $_POST['perihal'];
       $kategori = $_POST['kategori'];
       $isi_surat = $_POST['isi_surat'];
       $tgl = $_POST['tgl'];
       $type = $_POST['kategori_surat'];
    $tmb = mysqli_query($con,"INSERT INTO `surat` (`no_urut`, `no_surat`, `perihal_surat`, `kategori_surat`, `tgl_surat`, `isi_surat`, `type_surat`, `id_cabang`, `id_karyawan`)
    VALUES ('$no_urut', '$no_surat', '$perihal', '$kategori', '$tgl', '$isi_surat', '$type', '$id_cabang', '$id_karyawan');"); 
    if($tmb){
        alert("Berhasil DIsimpan dengan Nomor Surat : $no_surat");
        pindah($url.$menu."surat");
    }
    else{
        pesan("Gagal : ", mysqli_error($con),"danger");
    }
   }
   ?>
	
</div>
<div class="modal fade" id="modalku">
    <div class="modal-dialog">
      <div class="modal-content">
      
        <!-- Ini adalah Bagian Header Modal -->
        <div class="modal-header">
          <h4 class="modal-title">TAMBAH KATEGORI SURAT</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Ini adalah Bagian Body Modal -->
        <div class="modal-body">
          <i>
              <form action="" method="post">
                  Kategori
                  <input type="text" class="form-control" name="kategori" id="">
                  singkatan
                  <input type="text" class="form-control" name="singkat" id="">
                  <input type="submit" class='btn btn-danger' value="TAMBAH" name='simpan_kategori'>
              </form>
          </i><hr/>


        </div>
        
        <!-- Ini adalah Bagian Footer Modal -->
        <div class="modal-footer">
          <!-- <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button> -->
        </div>
        
      </div>
    </div>
  </div>

<script>
   
   
       function surat(){
        $(document).ready(function(){
           
          
               let kode_cabang = "<?=$kode_cabang?>";
               let singkatan = "<?=$singkatan_cabang?>";
               
               let kategori= $("#kategori").val();
               let no_urut = $("#no_urut").val();
               let tgl = $("#tgl").val(); 
               let tahun = tgl.split("-",1);
               let bulan=tgl.split("-");
               bulan = parseInt( bulan[1]);
               // alert(bulan);
               let isi;
               if(kategori==""){
                   isi="";
               }
               else{
                   isi = "/"+kategori;
               }
               
              let romawi = ['','I','II','III','IV','V','VI','VII','VIII','IX','X','XI','XII',''];
               $("#no_surat").val(no_urut+"/"+kode_cabang+"-"+singkatan+isi+"/"+romawi[bulan]+"/"+tahun);
           }); 
       
       }

       function copyToClipboard(text) {
            var sampleTextarea = document.createElement("textarea");
            document.body.appendChild(sampleTextarea);
            sampleTextarea.value = text; //save main text in it
            sampleTextarea.select(); //select textarea contenrs
            document.execCommand("copy");
            document.body.removeChild(sampleTextarea);
        }

        $("#copi").on("click",function(e){
            e.preventDefault();
            copyToClipboard($("#no_surat").val());
            $(this).html('tersalin');
            setTimeout(function(){
                $("#copi").html('salin');
            },3000);
        });
    
</script>
