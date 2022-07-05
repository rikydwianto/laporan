
    <div class="col-md-12">
            <div class="col-md-12">

            <h3>DATA PAR</h3>
            <table class='table table-bordered'>
                <tr>
                    <th>NO</th>
                    <th>Tanggal</th>
                    <th>Hari</th>
                    <th>TOTAL AGT PAR</th>
                    <th>CTR PAR</th>
                    <th>OS PAR</th>
                    <th>#</th>
                </tr>
                <?php 
                $q_tgl1 = mysqli_query($con,"SELECT  tgl_input,count(*) as hitung, sum(sisa_saldo) as total_par FROM deliquency where id_cabang='$id_cabang' group by tgl_input order by tgl_input desc");
                while($cari = mysqli_fetch_array($q_tgl1)){
                    $total_center  = mysqli_query($con,"SELECT COUNT(DISTINCT no_center) as total_center FROM deliquency WHERE id_cabang = '$id_cabang' and tgl_input='$cari[tgl_input]' #GROUP BY tgl_input ");
                    $total_center = mysqli_fetch_array($total_center);
                    ?>
                    <tr>
                        <td><?=$no++?></td>
                        <td><?=$cari['tgl_input']?></td>
                        <td><?=format_hari_tanggal($cari['tgl_input'])?></td>
                        <td><?=($cari['hitung'])?></td>
                        <td><?=($total_center['total_center'])?></td>
                        <td><?=rupiah($cari['total_par'])?></td>
                        <td>
                            <a href="<?=$url.$menu?>par&list&munculkan&tgl=<?=$cari['tgl_input']?>"  class="btn btn-success"> <i class="fa fa-eye"></i> Tampilkan Detail</a>
                            <a href="#" onclick="buka('popup/par.php?tgl=<?=$cari['tgl_input']?>')"  class="btn btn-primary"> <i class="fa fa-list"></i> Tampilkan Semua</a>
                            <a href="<?=$url.$menu?>par&delete&tgl=<?=$cari['tgl_input']?>" onclick="return window.confirm('apakah yakin menghapus semua data <?=format_hari_tanggal($cari['tgl_input'])?>')" class="btn btn-danger"> <i class="fa fa-times"></i> </a>
                        </td>
                    </tr>
                    
                            <?php 
                            $qminggu = mysqli_query($con,"SELECT DISTINCT minggu as minggu1 from deliquency where tgl_input='$cari[tgl_input]' and id_cabang='$id_cabang' order by minggu asc");
                            $qminggu1 = mysqli_query($con,"SELECT DISTINCT minggu as minggu1 from deliquency where tgl_input='$cari[tgl_input]' and id_cabang='$id_cabang' order by minggu asc");
                            
                            if(isset($_GET['munculkan'])){
                                $tgl = $_GET['tgl'];
                                if($tgl == $cari['tgl_input']){

                                
                                ?>
                    <tr>
                        <td colspan="7">
                                <table class='table table-bordered'>
                                    <tr>
                                        <th>MINGGU</th>
                                        <?php 
                                        while($rminggu = mysqli_fetch_array($qminggu)){
                                            ?>
                                            <th>
                                            <a href="#" onclick="buka('popup/par.php?tgl=<?=$cari['tgl_input']?>&minggu=<?=$rminggu['minggu1']?>')" target="_blank">
                                                <?=$rminggu['minggu1']?></th>
                                            </a>    
                                            <?php
                                        }
                                        ?>
                                        
                                    </tr>
                                    <tr>
                                        <td>JML</td>
                                        <?php 
                                        while($rminggu = mysqli_fetch_array($qminggu1)){
                                            $total_minggu = mysqli_query($con,"select count(id) as total from deliquency where id_cabang='$id_cabang' and tgl_input='$cari[tgl_input]' and minggu='$rminggu[minggu1]'  ");
                                            $total_minggu = mysqli_fetch_array($total_minggu)['total'];
                                            ?>
                                            <td><?=$total_minggu?></td>
                                            <?php
                                        }
                                        ?>
                                    </tr>
                                </table>

                                <table class='table table-bordered'>
                                    <tr>
                                        <th>NO</th>
                                        <th>TAHUN</th>
                                        <th>BULAN</th>
                                        <th>TOTAL</th>
                                        <th>TOTAL OS</th>
                                        <th>#</th>
                                    </tr>
                                    <?php
                                    $no=1;
                                    $qt = mysqli_query($con,"select tgl_disburse,sum(sisa_saldo) as total_os, count(*) as total,year(tgl_disburse) as tahun,month(tgl_disburse) as bulan from deliquency where id_cabang='$id_cabang' and tgl_input='$cari[tgl_input]' group by year(tgl_disburse),month(tgl_disburse) order by tgl_disburse desc"); 
                                    while($rtgl_dis = mysqli_fetch_array($qt)){
                                     ?>
                                     <tr>
                                        <td><?=$no++?></td>
                                        <td><?=$rtgl_dis['tahun']?></td>
                                        <td><?=bulan_indo($rtgl_dis['bulan'])?></td>
                                        <td><?=$rtgl_dis['total']?></td>
                                        <td><?=rupiah($rtgl_dis['total_os'])?></td>
                                        <td>
                                            <a href="#" onclick="buka('popup/par.php?tgl=<?=$cari['tgl_input']?>&bulan=<?=$rtgl_dis['tahun'].'-'.sprintf('%02d',$rtgl_dis['bulan'])?>')" class="btn btn-success"> Detail </a>
                                        </td>
                                    </tr>
                                     <?php   
                                    }
                                    ?>
                                </table>

                                <br>
                                </td>
                    </tr>
                                <?php
                            }
                        }
                            ?>
                            
                   
                    <?php
                }
                ?>
            </table>
            </div>

       </div>
    