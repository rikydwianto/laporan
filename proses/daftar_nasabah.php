<div class='content table-responsive'>
    <h2 class='page-header'>DAFTAR NASABAH </h2>
    <i>DAFTAR NASABAH </i>
    <hr />
    <a href="<?= $url . $menu ?>daftar_nasabah&duplikat" class="btn btn-danger"> Nasabah Duplikat</a>
    <a href="<?= $url . $menu ?>daftar_nasabah&sinkron" class="btn btn-success"> Synchron</a>
    <a href="<?= $url . $menu ?>cek_nik" class="btn btn-info"> CEK NIK KTP</a>
    <br/>
    <br/>
    <br/>
    <form method="post"  enctype="multipart/form-data">
            <div class="col-md-4">
                <label for="formFile" class="form-label">SILAHKAN PILIH FILE : DETAIL NASABAH SRSS</label>
                <input class="form-control" type="file" name='file' accept=".xls,.xlsx,.csv,.xml" id="formFile">
                <input type="submit" value="Proses" onclick="return confirm('Apakah Sudah yakin? ')" class='btn btn-danger' name='preview'>
                <input type="submit" value="Proses XML" onclick="return confirm('Apakah Sudah yakin? ')" class='btn btn-info' name='xml_preview'>
            </div>

    <?php
    $id_kumpul = array();
    ini_set('max_execution_time', '0');
    //XML
    if(isset($_POST['xml_preview'])){
        ?>
        <table border=1>
        <?php
        //set_time_limit(5000);
        $file = $_FILES['file']['tmp_name'];
        $xml = simplexml_load_file($file);
        $xml = $xml->Tablix1->Details_Collection;
        $last_row = 23;
    //    mysqli_query($con,"delete from daftar_nasabah where id_cabang='$id_cabang'");
        $no_input=0;
        foreach($xml->Details as $r){
            
            $id_nasabah = $r['ClientID'];
            $no_id  = explode("-",$id_nasabah)[1];
            $no_id = sprintf("%0d",$no_id);
            $nasabah =  aman($con,htmlspecialchars( ganti_karakter($r['ClientName']),ENT_QUOTES));
            $suami =  aman($con,htmlspecialchars(ganti_karakter($r['NamaPasangan']),ENT_QUOTES));
           $no_center = ganti_karakter($r['CenterID']);
           $kelompok = ganti_karakter1($r['GroupID']);
           $hp = ganti_karakter1($r['Textbox52']);
           $ktp = ganti_karakter1($r['JoinDate2']);
           $rt = ganti_karakter1($r['Textbox44']);
           $rw = ganti_karakter1($r['Textbox48']);
           $id_kumpul[]=$no_id;
           $alamat = "RT $rt / RW. $rw ". ganti_karakter1($r['address']);
           $staff = ganti_karakter1($r['OfficerName']);
           $hari = ganti_karakter1($r['HariMinggon']);
           $tgl_bergabung = explode("T",ganti_karakter1($r['JoinDate']))[0];
          
           $q = mysqli_query($con,"select id_detail_nasabah from daftar_nasabah where id_detail_nasabah='$id_nasabah' and id_cabang='$id_cabang'");
           if(mysqli_num_rows($q)){
            // $ket="ada di db";   
            //tidak usah di insert
            mysqli_query($con,"update daftar_nasabah set alamat_nasabah='$alamat',kelompok='$kelompok', hp_nasabah='$hp',no_center='$no_center',suami_nasabah='$suami', nama_nasabah='$nasabah',staff='$staff',id_karyawan=null, hari='$hari',no_ktp='$ktp' where id_detail_nasabah='$id_nasabah' and id_cabang='$id_cabang'");
           }
           else{
               
                    // $ket = "harus di insert nih";
                    $no_input++;
                    mysqli_query($con,"
                    INSERT INTO `daftar_nasabah` 
                    ( `id_nasabah`, `no_center`,kelompok, `id_detail_nasabah`, `nama_nasabah`, `suami_nasabah`, `no_ktp`, `alamat_nasabah`, `tgl_bergabung`, `hp_nasabah`, `staff`, `hari`, `id_cabang`) VALUES 
                    ( '$no_id', '$no_center','$kelompok', '$id_nasabah', '$nasabah', '$suami', '$ktp', '$alamat', '$tgl_bergabung', '$hp', '$staff', '$hari', '$id_cabang'); 

                    ");

                
                
           }
          

           
        }
        
         alert("Sebanyak ". ($no_input) . " telah diinput, silahkan sinkron");
         pindah($url.$menu."daftar_nasabah&sinkron");
        ?>
        </table> 
        <?php
        $id_gabung = implode("','",$id_kumpul);
        mysqli_query($con,"DELETE FROM daftar_nasabah where id_cabang='$id_cabang' and id_nasabah not in('$id_gabung')");
    }

    //EXCEL
    if(isset($_POST['preview'])){
        //set_time_limit(5000);
        // alert("tunggu ya proses ini akan memakan waktu agak lama, karena banyak nya data, jangan diclose sampe proses selesai!!");
            

        $file = $_FILES['file']['tmp_name'];
        $path = $file;
        $reader = PHPExcel_IOFactory::createReaderForFile($path);
        $objek = $reader->load($path);
        $ws = $objek->getActiveSheet();
        $last_row = $ws->getHighestDataRow();
    //    mysqli_query($con,"delete from daftar_nasabah where id_cabang='$id_cabang'");
        $no_input=0;
        for($row = 2;$row<=$last_row;$row++){
            $id_nasabah =  $ws->getCell("F" . $row)->getValue();
            if($id_nasabah==null){
                
            }
            else{
                $agt = (substr(ganti_karakter($id_nasabah),0,3));
        
                if( $agt=="AGT" || $agt=="NSB"){
                    $id_nasabah = ganti_karakter1($ws->getCell("F".$row)->getValue());
                    $no_id  = explode("-",$id_nasabah)[1];
                    $no_id = sprintf("%0d",$no_id);
                    $nasabah =  aman($con,htmlspecialchars( ganti_karakter($ws->getCell("G".$row)->getValue()),ENT_QUOTES));
                    $suami =  aman($con,htmlspecialchars(ganti_karakter($ws->getCell("I".$row)->getValue()),ENT_QUOTES));
                   $no_center = ganti_karakter($ws->getCell("D".$row)->getValue());
                   $kelompok = ganti_karakter1($ws->getCell("E".$row)->getValue());
                   $hp = ganti_karakter1($ws->getCell("S".$row)->getValue());
                   $ktp = ganti_karakter1($ws->getCell("L".$row)->getValue());
                   $rt = ganti_karakter1($ws->getCell("Q".$row)->getValue());
                   $rw = ganti_karakter1($ws->getCell("R".$row)->getValue());
                   
                   $alamat = "RT $rt / RW. $rw ". ganti_karakter1($ws->getCell("J".$row)->getValue());
                   $staff = ganti_karakter1($ws->getCell("U".$row)->getValue());
                   $hari = ganti_karakter1($ws->getCell("T".$row)->getValue());
                   $tgl_bergabung = str_replace("/","-",ganti_karakter1($ws->getCell("K".$row)->getValue()));
                   $tgl_bergabung =  date("Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($tgl_bergabung));
                   $q = mysqli_query($con,"select id_detail_nasabah from daftar_nasabah where id_detail_nasabah='$id_nasabah' and id_cabang='$id_cabang'");
                   if(mysqli_num_rows($q)){
                    // $ket="ada di db";   
                    //tidak usah di insert
                    mysqli_query($con,"update daftar_nasabah set  hp_nasabah='$hp', staff='$staff',id_karyawan=null, hari='$hari',no_ktp='$ktp' where id_detail_nasabah='$id_nasabah'");
                   }
                   else{
                       
                            // $ket = "harus di insert nih";
                            $no_input++;
                            mysqli_query($con,"
                            INSERT INTO `daftar_nasabah` 
                            ( `id_nasabah`, `no_center`, `id_detail_nasabah`, `nama_nasabah`, `suami_nasabah`, `no_ktp`, `alamat_nasabah`, `tgl_bergabung`, `hp_nasabah`, `staff`, `hari`, `id_cabang`) VALUES 
                            ( '$no_id', '$no_center', '$id_nasabah', '$nasabah', '$suami', '$ktp', '$alamat', '$tgl_bergabung', '$hp', '$staff', '$hari', '$id_cabang'); 
        
                            ");
        
                        
                        
                   }
                  
        
                   
            
                }
                
                   
                    
                
            }
        }
         alert("Sebanyak ". ($no_input) . " telah diinput, silahkan sinkron");
         pindah($url.$menu."daftar_nasabah&sinkron");
    }


    
   

    if (isset($_GET['duplikat'])) {
    ?>
        <table class='table'>
            <thead>
                <tr>
                    <th>NO</th>
                    <th>ID</th>
                    <th>KEL</th>
                    <th>CENTER</th>
                    <th>NAMA</th>
                    <th>SUAMI</th>
                    <th>KTP</th>
                    <th>TGL BERGABUNG</th>
                    <th>HARI</th>
                    <th>ALAMAT</th>
                    <th>STAFF</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = mysqli_query($con, "SELECT * from daftar_nasabah where no_ktp in (SELECT daftar_nasabah.no_ktp FROM `daftar_nasabah` where id_cabang='$id_cabang'
                 GROUP BY  no_ktp HAVING count(*) > 1) and id_cabang='$id_cabang' order by no_ktp,nama_nasabah asc
                ");
                while ($dup = mysqli_fetch_array($query)) {
                ?>
                    <tr>
                        <td><?=$no++?></td>
                        <td><?=$dup['id_nasabah']?></td>
                        <td><?=$dup['kelompok']?></td>
                        <td><?=$dup['no_center']?></td>
                        <td><?=$dup['nama_nasabah']?></td>
                        <td><?=$dup['suami_nasabah']?></td>
                        <td><?=$dup['no_ktp']?></tdd>
                        <td><?=$dup['tgl_bergabung']?></td>
                        <td><?=$dup['hari']?></td>
                        <td><?=$dup['alamat_nasabah']?></td>
                        <td><?=$dup['staff']?></td>
                    </tr>
                <?php

                }
                ?>


            </tbody>
        </table>
    <?php
    }
    elseif(isset($_GET['sinkron'])){


        if (isset($_POST['ganti'])) {
            $karyawan = $_POST['karyawan'];
            $mdis = $_POST['nama_mdis'];
            $total_nasabah = $_POST['total_nasabah'];
            for ($i = 0; $i < count($mdis); $i++) {
                if (!empty($karyawan[$i])) {
                    $cek_total_anggota = mysqli_num_rows(mysqli_query($con,"select * from total_nasabah where id_cabang='$id_cabang' and id_karyawan='$karyawan[$i]'"));
                    if($cek_total_anggota>0){
                        mysqli_query($con,"UPDATE `total_nasabah` SET `total_nasabah` = '$total_nasabah[$i]' WHERE `id_karyawan` = '$karyawan[$i]';");
                    } else{
                        mysqli_query($con,"insert into total_nasabah(id_karyawan,total_nasabah,id_cabang) values('$karyawan[$i]','$total_nasabah[$i]','$id_cabang')");
                    }
                    $text = " UPDATE `daftar_nasabah` SET  id_karyawan='$karyawan[$i]' WHERE `staff` = '$mdis[$i]' and id_cabang='$id_cabang'; ";
                    $q = mysqli_query($con, "$text");
                }
            }
            mysqli_query($con,"DELETE from center WHERE no_center NOT IN (SELECT DISTINCT no_center FROM daftar_nasabah WHERE id_cabang='$id_cabang') AND id_cabang='$id_cabang'");
            $qcek_center = mysqli_query($con,"select *,count(*) as total_anggota from daftar_nasabah where id_cabang='$id_cabang'  group by no_center");
            while($cek_center =mysqli_fetch_array($qcek_center)){
                $hitung_center = mysqli_fetch_array(mysqli_query($con,"select * from center where no_center='$cek_center[no_center]' and id_cabang='$id_cabang'"));
                if($hitung_center){
                    $hari = strtolower($cek_center['hari']);
                    mysqli_query($con,"UPDATE `center` SET `member_center` = '$cek_center[total_anggota]',hari='$hari', id_karyawan='$cek_center[id_karyawan]' WHERE `no_center` = '$cek_center[no_center]' and id_cabang='$id_cabang'; ");
                    // echo $cek_center['no_center'].' - '.$cek_center['id_karyawan']."|".$cek_center['total_anggota']."<br/>";
                }
                else{
                    // echo "center tida k ditemukan di table center<br/>";
                    center($con, $cek_center['no_center'], 'y', 'hijau', $cek_center['total_anggota'], $cek_center['total_anggota'], $cek_center['total_anggota'], $id_cabang, $cek_center['id_karyawan'], strtolower($cek_center['hari']), '0', '00:00:00', 't',0);

                }
            }
            $gabung_kar = implode(",",$karyawan);
            $sql = mysqli_query($con,"delete from total_nasabah where id_cabang='$id_cabang' and id_karyawan not in($gabung_kar)");
            alert("DAFTAR NASABAH DAN TOTAL ANGGOTA BERHASIL DIUPDATE");
            pindah($url.$menu."daftar_nasabah&sinkron&update_nasabah");

        }


        ?>
        <div class="col-md-12">
            <table class='table'>
                <tr>
                    <th>NO</th>
                    <th>NAMA MDIS</th>
                    <th>TOTAL AGT </th>
                    <th>GANTI </th>
                </tr>
                <?php 
                $total_n = 0;
                $q_nama =mysqli_query($con,"select count(id_nasabah) as total, staff from daftar_nasabah where id_cabang='$id_cabang' and id_karyawan is null group by staff");
                while($nama = mysqli_fetch_array($q_nama)){
                    $total_n +=$nama['total'];
                    ?>
                    <tr>
                        <td><?=$no++?></td>
                        <td><?=$nama['staff']?></td>
                        <td><?=$nama['total']?></td>
                        <td>
                        <input type="hidden" name="nama_mdis[]" value="<?= $nama['staff'] ?>">
                        <input type="hidden" name="total_nasabah[]" value="<?= $nama['total'] ?>">
                             <select name="karyawan[]" id="" required class='form-control'>
                                    <option value="">Pilih Staff</option>
                                    <?php $data_karyawan  = (karyawan($con, $id_cabang)['data']);
                                    for ($i = 0; $i < count($data_karyawan); $i++) {
                                        $nama_karyawan = $data_karyawan[$i]['nama_karyawan'];
                                        if (strtolower($nama_karyawan) == strtolower($nama['staff'])) {
                                            echo "<option selected value='" . $data_karyawan[$i]['id_karyawan'] . "'>" . $nama_karyawan . "</option>";
                                        } else {
                                            echo "<option value='" . $data_karyawan[$i]['id_karyawan'] . "'>" . $nama_karyawan . "</option>";
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
                        <td colspan="1"><?=$total_n?></td>
                        <td>
                            <input type="submit" class='btn btn-success' value='KONFIRMASI' name='ganti' />
                        </td>
                    </tr>
                
            </table>
        </div>
        <?php
    }
    ?>
</div>