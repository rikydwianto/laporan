<form method="post" action="" enctype="multipart/form-data">
        <div class="col-md-4">
            <label for="formFile" class="form-label">SILAHKAN PILIH FILE</label>
            <input class="form-control" type="file" name='file' accept=".xls,.xlsx,.csv" id="formFile">
            <input type="submit" value="Proses" class='btn btn-danger' name='ekse'>
        </div>
    </form>
        <!-- <form action="" method="post">
            <textarea name="query" class='form-control' id="" cols="50" rows="20"></textarea>
            <input type="submit" value="Execute" name='ekse' /> -->
            <?php
            if (isset($_POST['ekse'])) {
                
                // $text = ganti_karakter($_POST['query']);
                // $text = str_replace("mytable", "pinjaman", $text);
                // $query = mysqli_multi_query($con, $text);
                // if ($query) {
                //     sleep(10);
                //     alert("Terima Kasih telah menunggu, Data berhasil input ...");
                // } else {
                //     pesan("Gagal <br/> $text", 'danger');
                // }
                ?>
                <table class='table'>
                    <tr>
                        <td>no</td>
                        <td>ID NASABAH</td>
                        <td>LOAN</td>
                        <td>NASABAH</td>
                        <td>NO HP</td>
                        <td>CENTER</td>
                        <td>KELOMPOK</td>
                        <td>PRODUK</td>
                        <td>PINJAMAN</td>
                        <td>OUTSTANDING</td>
                        <td>J. WAKTU</td>
                        <td>ANGSURAN</td>
                        <td>TUJUAN</td>
                        <td>PIN. KE</td>
                        <td>STAFF</td>
                        <td>TGL PENGAJUAN</td>
                        <td>TGL PENCAIRAN</td>
                        <td>TGL ANGSURAN</td>
                    </tr>


                <?php 
                $no_input = 0;
                $file = $_FILES['file']['tmp_name'];
                $path = $file;
                // $path = "../RAHASIA/monitoring.xlsx";
                $reader = PHPExcel_IOFactory::createReaderForFile($path);
                $objek = $reader->load($path);
                $ws = $objek->getActiveSheet();
                $last_row = $ws->getHighestDataRow();

                $ket_excel =  $ws->getCell("A3")->getValue();
                echo $ket_excel;
                if($ket_excel=='DAFTAR PINJAMAN '){
                    for($row = 7;$row<=$last_row;$row++){
                        $id_nasabah =  $ws->getCell("B" . $row)->getValue();
                        if($id_nasabah==null){
                            
                        }
                        else{
                            $agt = (substr(ganti_karakter($id_nasabah),0,3));
    
                            if( $agt=="AGT" || $agt=="NSB"){
                                $nasabah =  aman($con,ganti_karakter($ws->getCell("D".$row)->getValue()));
                            $loan = ganti_karakter($ws->getCell("C".$row)->getValue());
                            $no_center = ganti_karakter($ws->getCell("F".$row)->getValue());
                            $id_nasabah =aman($con, ganti_karakter1($ws->getCell("B".$row)->getValue()));
                            $kelompok = ganti_karakter1($ws->getCell("G".$row)->getValue());
                            $hp = ganti_karakter1($ws->getCell("E".$row)->getValue());
                            $produk = ganti_karakter1($ws->getCell("H".$row)->getValue());
                            $tujuan = ganti_karakter1($ws->getCell("N".$row)->getValue());
                            $pinj_ke = ganti_karakter1($ws->getCell("O".$row)->getValue());
                            $staff = ganti_karakter1($ws->getCell("P".$row)->getValue());
                            $tgl_pengajuan = str_replace("/","-",ganti_karakter1($ws->getCell("Q".$row)->getValue()));
                            $tgl_pencairan = str_replace("/","-",ganti_karakter1($ws->getCell("R".$row)->getValue()));
                            $tgl_angsuran = str_replace("/","-",ganti_karakter1($ws->getCell("S".$row)->getValue()));
                            $margin = ganti_karakter1($ws->getCell("L".$row)->getValue());
    
    
    
                            $pinjaman = (int)ganti_karakter(str_replace(",","",$ws->getCell("I".$row)->getValue()));
                            $outstanding = (int)ganti_karakter(str_replace(",","",$ws->getCell("J".$row)->getValue()));
                            $jk = (int)ganti_karakter(str_replace(",","",$ws->getCell("K".$row)->getValue()));
                            $angsuran = (int)ganti_karakter(str_replace(",","",$ws->getCell("M".$row)->getValue()));
                            $tunggakan = (int)ganti_karakter(str_replace(",","",$ws->getCell("L".$row)->getValue()));
                            $minggu = (int)ganti_karakter(str_replace(",","",$ws->getCell("M".$row)->getValue()));
    
                            
                            ?>
                            <tr>
                                    <td><?=$no++?></td>
                                    <td><?=$id_nasabah?></td>
                                    <td><?=$loan?></td>
                                    <td><?=$nasabah?></td>
                                    <td><?=$hp?></td>
                                    <td><?=$no_center?></td>
                                    <td><?=$kelompok?></td>
                                    <td><?=$produk?></td>
                                    <td><?=$pinjaman?></td>
                                    <td><?=$outstanding?></td>
                                    <td><?=$jk?></td>
                                    <td><?=$angsuran?></td>
                                    <td><?=$tujuan?></td>
                                    <td><?=$pinj_ke?></td>
                                    <td><?=$staff?></td>
                                    <td><?=$tgl_pengajuan?></td>
                                    <td><?=$tgl_pencairan?></td>
                                    <td><?=$tgl_angsuran?></td>
                                    
                                    
                            </tr>
                            <?php
                            
                            $cari_loan = mysqli_query($con,"select id_detail_pinjaman from pinjaman where id_cabang='$id_cabang' and id_detail_pinjaman='$loan'");
                            $cari_loan = mysqli_fetch_array($cari_loan);
                            $cari_loan = $cari_loan['id_detail_pinjaman'];
                            if($cari_loan == $loan)
                            {
                                $ket="ada";
                            }
                            else {
                                $no_input++;
                                
                                mysqli_query($con,"INSERT INTO `pinjaman` 
                                        (`id_detail_nasabah`, `id_detail_pinjaman`, `nama_nasabah`, `no_hp`, `center`, `kelompok`, `produk`, `jumlah_pinjaman`, `outstanding`, `jk_waktu`, `margin`, `angsuran`, `tujuan_pinjaman`, `pinjaman_ke`, `staff`, `tgl_pengajuan`, `tgl_pencairan`, `tgl_angsuran`,  `id_cabang`)
                                VALUES ('$id_nasabah', '$loan', '$nasabah', '$hp', '$no_center', '$kelompok', '$produk', '$pinjaman', '$outstanding', '$jk', '$margin', '$angsuran', '$tujuan', '$pinj_ke', '$staff', '$tgl_pengajuan', '$tgl_pencairan', '$tgl_angsuran',  '$id_cabang'); ");
    
                            }
    
                        
                            }
                            
                            
                                
                            
                        }
                    }
                }
                else{
                    alert('DITOLAK, BUKAN FILE DAFTAR PINJAMAN');
                }
                alert("Sebanyak ". ($no_input) . " telah diinput, silahkan sinkron");

                pindah($url.$menu."monitoring&ganti");
                ?>
                </table>
                <?php 

            }
            ?>
        <!-- </form> -->