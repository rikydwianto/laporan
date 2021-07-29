<div class='content table-responsive'>
    <h2 class='page-header'>MONITORING </h2>
    <i>Tambah, kumpulkan monitoring, </i> <br />
    <a href="<?= $url . $menu ?>monitoring" class='btn btn-success'> <i class="fa fa-eye"></i> Lihat</a>
    <a href="<?= $url . $menu ?>monitoring&tambah" class='btn btn-info'> <i class="fa fa-plus"></i> Tambah</a>
    <a href="#" class='btn btn-success'> <i class="fa fa-refresh"></i> Refresh Data</a>
    <a href="<?= $url . $menu ?>monitoring&staff" class='btn btn-danger'> <i class="fa fa-users"></i> Daftar Staff</a>
    <hr />

    <?php
    if (isset($_GET['tambah'])) {
    ?>
        <form action="" method="post">
            <textarea name="query" class='form-control' id="" cols="50" rows="20"></textarea>
            <input type="submit" value="Execute" name='ekse' />
            <?php
            if (isset($_POST['ekse'])) {
                $query = mysqli_multi_query($con, "$_POST[query]");
                if ($query) {
                    alert("Berhasil");
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
                <td>STAFF</td>
                <td>NIK</td>
            </tr>
            <?php
            $cek_ka=mysqli_query($con,"SELECT * FROM karyawan,jabatan,cabang where karyawan.id_jabatan=jabatan.id_jabatan and karyawan.id_cabang=cabang.id_cabang and karyawan.id_cabang='$cabang' and jabatan.singkatan_jabatan='SL' and karyawan.status_karyawan='aktif' order by karyawan.nama_karyawan asc");
            while($karyawan = mysqli_fetch_array($cek_ka)){
                ?>
            <tr>
                <td><?=$karyawan['id_karyawan']?></td>
                <td><?=$karyawan['nama_karyawan']?></td>
                <td><?=$karyawan['nik_karyawan']?></td>
            </tr>
                <?php
            }
            ?>
        </table>
        <?php

    }
    else {
        

        if(isset($_POST['mtr'])){
            
        }



    ?>
        <form action="" method="post">
            <!-- <input type="submit" value="SIMPAN" name='mtr' class='btn btn-danger'> -->
            <TABLE class='table' id='data_karyawan'>
                <thead>
                    <tr>
                        <!-- <th>no</th> -->
                        <th>NO Pinjaman</th>
                        <th>Nama</th>
                        <th>Jumlah Pinjaman</th>
                        <th>Produk</th>
                        <th>KE</th>
                        <th>Staff</th>
                        <th>#</th>
                        <th>#</th>
                    </tr>
                </thead>
                <tbody>


                    <?php
                    $q = mysqli_query($con, "select * from pinjaman where id_cabang='$id_cabang'");
                    while ($pinj = mysqli_fetch_array($q)) {
                    ?>
                        <tr>
                            <!-- <td><?= $no++ ?></td> -->
                            <td><?= $pinj['id_detail_pinjaman'] ?></td>
                            <td><?= $pinj['nama_nasabah'] ?></td>
                            <td><?= $pinj['jumlah_pinjaman'] ?></td>
                            <td><?= $pinj['produk'] ?></td>
                            <td><?= $pinj['pinjaman_ke'] ?></td>
                            <td><?= $pinj['staff'] ?></td>
                            <td>
                                <?php 
                                if($pinj['monitoring']=='belum'){
                                    $tombol = "btn-danger";
                                }
                                elseif($pinj['monitoring']=='sudah'){
                                    
                                    $tombol = "btn-info";
                                }
                                ?>
                                <span class="pull-right" id='loading_<?=$pinj['id_pinjaman']?>' class="badge rounded-pill bg-danger"></span>
                            </td>
                            <td>
                                
                                <input type="button" id="cek_<?=$pinj['id_pinjaman']?>" class='btn <?=$tombol?>' value='<?=$pinj['monitoring']?>' onclick="monitoring('<?=$pinj['id_pinjaman']?>')" id="">

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