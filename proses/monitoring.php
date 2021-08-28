
<div class='content table-responsive'>
    <h2 class='page-header'>MONITORING </h2>
    <i>Tambah, kumpulkan monitoring, </i> <br />
    <a href="<?= $url . $menu ?>monitoring" class='btn btn-success'> <i class="fa fa-eye"></i> Lihat</a>
    <a href="<?= $url . $menu ?>monitoring&tambah" class='btn btn-info'> <i class="fa fa-plus"></i> Tambah</a>
    <a href="<?= $url . $menu ?>monitoring&ganti" class='btn btn-success'> <i class="fa fa-refresh"></i> Refresh Data</a>
    <a href="<?= $url . $menu ?>monitoring&staff" class='btn btn-danger'> <i class="fa fa-users"></i> Daftar Staff</a>
    <a href="<?= $url . $menu ?>monitoring"   target="popup" 
  onclick="window.open('<?= $url ?>export/monitoring.php','popup',''); return false;" class='btn btn-info'> <i class="fa fa-file-excel-o"></i> Export</a>
    <hr />

    <?php
    if (isset($_GET['tambah'])) {
    ?>
        <form action="" method="post">
            <textarea name="query" class='form-control' id="" cols="50" rows="20"></textarea>
            <input type="submit" value="Execute" name='ekse' />
            <?php
            if (isset($_POST['ekse'])) {
                $text = ganti_karakter($_POST['query']);
                $text = str_replace("mytable","pinjaman",$text);
                $query = mysqli_multi_query($con, $text);
                if ($query) {
                    alert("Berhasil");
                }else{
                    pesan("Gagal <br/> $text",'danger');
                }
            }
            ?>
        </form>
    <?php
    }
    elseif(isset($_GET['staff'])){
        ?>
        <table class="table table-bordered">
            <tr>
                <td>
                    NO Staff
                </td>
                <td>NIK</td>
                <td>STAFF</td>
                <td>SISA MONITORING</td>
                <td></td>
            </tr>
            <?php
            $total_monitoring = 0;
            $cek_ka=mysqli_query($con,"SELECT * FROM karyawan,jabatan,cabang where karyawan.id_jabatan=jabatan.id_jabatan and karyawan.id_cabang=cabang.id_cabang and karyawan.id_cabang='$cabang' and jabatan.singkatan_jabatan='SL' and karyawan.status_karyawan='aktif' order by karyawan.nama_karyawan asc");
            while($karyawan = mysqli_fetch_array($cek_ka)){
                ?>
            <tr>
                <td><?=$karyawan['id_karyawan']?></td>
                <td><?=$karyawan['nik_karyawan']?></td>
                <td><?=$karyawan['nama_karyawan']?></td>
                <td>
                    <?php 
                    $q = mysqli_query($con,"select count(id_detail_nasabah) as total from pinjaman where monitoring='belum' and id_karyawan='$karyawan[id_karyawan]' and id_cabang='$id_cabang'");
                    $total = mysqli_fetch_array($q);
                    $total = $total['total'];
                    $total_monitoring =$total + $total_monitoring;
                    echo $total ;
                    ?>
                    
                </td>
                <td><a href="<?= $url . $menu ?>monitoring&id=<?=$karyawan['id_karyawan']?>" > Detail</a> </td>
            </tr>
                <?php
            }
            ?>
            <tr>
                <td colspan="3"></td>
                <td><?=$total_monitoring?></td>
            </tr>
        </table>
        <?php

    }
    else if(isset($_GET['ganti'])){
        ?>
        <form action="" method="post">
            <!-- <input type="submit" value="SIMPAN" name='mtr' class='btn btn-danger'> -->
            <TABLE class='table' >
                <thead>
                    <tr>
                        <!-- <th>no</th> -->
                        <th>NO </th>
                        <th>NAMA MDIS</th>
                        <th>NAMA </th>
                    </tr>
                </thead>
                <tbody>


                    <?php
                    if(isset($_POST['ganti'])){
                        $karyawan = $_POST['karyawan'];
                        $mdis = $_POST['nama_mdis'];
                        for($i=0;$i<count($mdis);$i++){
                            if(!empty($karyawan[$i]))
                           {
                           $text = " UPDATE `pinjaman` SET `staff` = null , id_karyawan='$karyawan[$i]' WHERE `staff` = '$mdis[$i]'; ";
                            $q = mysqli_query($con, "$text");

                           }
                            
                        }
                    }


                    $q = mysqli_query($con, "select staff from pinjaman where id_karyawan is  null group by staff order by staff asc ");
                    while ($pinj = mysqli_fetch_array($q)) {
                    ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= $pinj['staff'] ?>
                            <input type="hidden" name="nama_mdis[]" value="<?= $pinj['staff'] ?>">
                        </td>
                            <td>
                               
                                <select name="karyawan[]" id=""  class='form-control'>
                                    <option value="">Pilih Staff</option>
                                    <?php  $data_karyawan  = (karyawan($con,$id_cabang)['data']);
                                    for($i=0;$i<count($data_karyawan);$i++){
                                        $nama_karyawan = $data_karyawan[$i]['nama_karyawan'];
                                      if(strtolower($nama_karyawan)==strtolower($pinj['staff'])){
                                          echo "<option selected value='".$data_karyawan[$i]['id_karyawan']."'>".$nama_karyawan."</option>";
                                        }
                                        else{
                                            echo "<option value='".$data_karyawan[$i]['id_karyawan']."'>".$nama_karyawan."</option>";
                                            
                                      }
                                    }
                                    ?>
                                </select>    
                        </td>
                        </tr>
                    <?php
                    }
                    ?>
                    <tr>
                        <td colspan="2"></td>
                        <td>
                            <input type="submit" class='btn btn-success' value='KONFIRMASI' name='ganti' />
                        </td>
                    </tr>
                </tbody>
            </TABLE>
        </form>
        <?php
    }
    else {
        

        if(isset($_POST['mtr'])){
            
        }



    ?>
    
        <form action="" method="post">
            <!-- <input type="submit" value="SIMPAN" name='mtr' class='btn btn-danger'> -->
            <a href="<?= $url . $menu ?>monitoring" class='btn btn-success'> <i class="fa fa-eye"></i> Lihat yang belum</a> 
            <a href="<?= $url . $menu ?>monitoring&filter" class='btn btn-danger'> <i class="fa fa-eye"></i> Lihat Semua Data</a> <br/><br/>
            <TABLE class='table' id='data_karyawan'>
                <thead>
                    <tr>
                        <!-- <th>no</th> -->
                        <th>Staff</th>
                        <th>NO Pinjaman</th>
                        <th>Nama</th>
                        <th>Jumlah Pinjaman</th>
                        <th>Produk</th>
                        <th>KE</th>
                        
                        <th>#</th>
                        <th>#</th>
                    </tr>
                </thead>
                <tbody>


                    <?php
                    if(isset($_GET['filter'])){
                        $q_tambah ="";
                    }
                    else{
                        $q_tambah = "and pinjaman.monitoring ='belum'";
                    }

                    if(isset($_GET['id'])){
                        $id=aman($con,$_GET['id']);
                        $q_id ="and pinjaman.id_karyawan = '$id'";
                    }
                    else{
                        $q_id = "";
                    }
                    $q = mysqli_query($con, "select * from pinjaman left join karyawan on karyawan.id_karyawan=pinjaman.id_karyawan where pinjaman.id_cabang='$id_cabang' $q_tambah $q_id order by karyawan.nama_karyawan asc");
                    while ($pinj = mysqli_fetch_array($q)) {
                    ?>
                        <tr>
                            <!-- <td><?= $no++ ?></td> -->
                            <td><?= $pinj['nama_karyawan'] ?></td>
                            <td><?= ganti_karakter($pinj['id_detail_pinjaman']) ?></td>
                            <td><?= $pinj['nama_nasabah'] ?></td>
                            <td><?= $pinj['jumlah_pinjaman'] ?></td>
                            <td><?= ganti_karakter($pinj['produk']) ?></td>
                            <td><?= $pinj['pinjaman_ke'] ?></td>
                           
                            <td>
                                <?php 
                                if($pinj['monitoring']=='belum'){
                                    $tombol = "btn-danger";
                                    $icon="";
                                }
                                elseif($pinj['monitoring']=='sudah'){
                                    
                                    $tombol = "btn-info";
                                    $icon = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check" viewBox="0 0 16 16">
                                    <path d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.267.267 0 0 1 .02-.022z"/>
                                  </svg>';
                                }
                                else $tombol="btn-danger";
                                ?>
                                <span class="pull-right" id='loading_<?=$pinj['id_pinjaman']?>' class="badge rounded-pill bg-danger"></span>
                                <?=$icon?>
                                <a href="#modalku1"  id="custId" data-toggle="modal"  data-id="<?= $pinj['id_pinjaman'] ?>">Detail</a>
                            </td>
                            <td>
                                <?php 
                                if($pinj['id_karyawan']!=null){
                                ?>
                                <input type="button" id="cek_<?=$pinj['id_pinjaman']?>" class='btn <?=$tombol?>' value='<?=$pinj['monitoring']?>' onclick="monitoring('<?=$pinj['id_pinjaman']?>')" id="">
                                <?php } ?>
                            </td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
        </form>
        </TABLE>
    <?php

    }
    ?>

</div>
<div class="modal fade" id="modalku1">
            <div class="modal-dialog modal-lg">
            <div class="modal-content">
            
                <!-- Ini adalah Bagian Header Modal -->
                <div class="modal-header">
                <h4 class="modal-title">DETAIL MONITORING</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                
                <!-- Ini adalah Bagian Body Modal -->
                <div class="modal-body">
                
                <div id="isi_detail"></div>
                    <br><br>

                </div>
                
                <!-- Ini adalah Bagian Footer Modal -->
                <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">close</button>
                </div>
                
            </div>
            </div>
        </div>


<script>
    var url = "<?=$url?>";
    var cabang  = "<?=$id_cabang?>";



    function monitoring(id){
        var cek = $("#cek_"+id).val();
        if(cek=='belum'){
            
            
            $.get(url + "api/monitoring.php?mtr=sudah&id="+id, function(data, status){
                $("#loading_" + id).html("Proses");
                setTimeout(function(){ 
                    $("#loading_"+id).html("<i class='fa fa-check'></i>");
                    $("#cek_"+id).val('sudah');
                    $("#cek_"+id).removeClass("btn-danger");
                    $("#cek_"+id).addClass("btn-info");
                 }, 1000);

            });

        }
        else{
            $.get(url + "api/monitoring.php?mtr=belum&id="+id, function(data, status){

                setTimeout(function(){ 
                    $("#cek_"+id).val('belum');
                    $("#cek_"+id).removeClass("btn-info");
                    $("#cek_"+id).addClass("btn-danger");
                    $("#loading_" + id).html("<i class='fa fa-times'></i>");
                },500);
                
            });
        }
    }

    
</script>