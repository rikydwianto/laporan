<div class='content table-responsive'>
    <h2 class='page-header'>SYNC NEW </h2>
 
    <div class="col-lg-12">
      <?php 
      if(isset($_GET['sync'])){
        //TAMPIL SYNC
        $id=aman($con,$_GET['id']);

        $cek = mysqli_query($con,"select * from sync_center where kode_cabang='$kode_cabang' and jenis='center' and id='$id' order by waktu desc");
        $data = mysqli_fetch_array($cek)['data_json'];
        
         $data = json_decode($data,true);
    $data = ($data['data_center']);
    foreach($data as $val){
        if($val['member']>0 && $val['client']>0){
           $q = "update center set member_center='$val[member]',anggota_center='$val[client]',nama_center='$val[centername]' where no_center='$val[centerid]' and id_cabang='$id_cabang'";
           mysqli_query($con,$q);

        }
    }
    alert("CLIENT & MEMBER Berhasil di update!");
    pindah($url);
      }
      else{
        $cek = mysqli_query($con,"select * from sync_center where kode_cabang='$kode_cabang' and jenis='center' order by waktu desc");
            if(!mysqli_num_rows($cek)){
                echo"Tidak ada data";
            }
            else{
                ?>
                <table class='table'>
                    <tr>
                        <th>NO</th>
                        <th>KODE CABANG</th>
                        <th>TANGGAL</th>
                        <th>STATUS</th>
                        <th>UPLOAD</th>
                        <th>#</th>
                    </tr>
                
                <?php
                while($r=mysqli_fetch_array($cek)){
                    ?>
                    <tr>
                    <td><?=$no++?></td>
                        <td><?=$r['kode_cabang']?></td>
                        <td><?=$r['tgl']?></td>
                        <td><?=$r['status_sync']?></td>
                        <td><?=$r['waktu']?></td>
                        <td>
                        <?php 
                        if(empty($r['status_sync'])){
                        ?>
                         <a href="<?=$url.$menu?>sync_center&sync&id=<?=$r['id']?>" class='btn btn-success'>
                            <i class="fa fa-gears"></i>  SYNC NEW
                        </a>
                        <?php
                        }
                        ?>    
                        </td>
                    </tr>
                    <?php
                }
                ?>
                </table>
                <?php
            }
      }
      ?>
    </div>

    
</div>
