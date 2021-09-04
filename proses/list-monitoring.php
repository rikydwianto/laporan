<h2 style='text-align:center'>Monitoring <br><small>//Yang bertanda merah berarti monitoring lebih dari 14 hari</small></h2>

<form action="" method="post">
            <!-- <input type="submit" value="SIMPAN" name='mtr' class='btn btn-danger'> -->
            <a href="<?= $url . $menu ?>list-monitoring" class='btn btn-success'> <i class="fa fa-eye"></i> Lihat yang belum</a> 
            <a href="<?= $url . $menu ?>list-monitoring&filter" class='btn btn-danger'> <i class="fa fa-eye"></i> Lihat Semua Data</a> <br/><br/>
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

                        $q_id ="and pinjaman.id_karyawan = '$id_karyawan'";
                    
                   
                    $q = mysqli_query($con, "select *,DATEDIFF(CURDATE(), tgl_cair) as total_hari from pinjaman left join karyawan on karyawan.id_karyawan=pinjaman.id_karyawan where pinjaman.id_cabang='$id_cabang' $q_tambah $q_id order by karyawan.nama_karyawan asc");
                    while ($pinj = mysqli_fetch_array($q)) {
                        if($pinj['total_hari']>14)
                        {
                            $tr = "#ffd4d4";
                        }
                        else $tr="#fffff";

                    ?>
                        <tr style="background:<?=$tr?>">
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
                              
                            </td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
        </form>
        </TABLE>
        <script>
    var url = "<?=$url?>";
    var cabang  = "<?=$id_cabang?>";
    </script>

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