<?php
$path = "./RAHASIA/blk_selasa.xlsx";
$reader = PHPExcel_IOFactory::createReaderForFile($path);
$objek = $reader->load($path);
$ws = $objek->getActiveSheet();
$last_row = $ws->getHighestDataRow();
?>
<h2>BLK </h2>
<table id='data_blk' class='table-bordered'>
    <thead>
        <tr>
            <th>NO</th>
            <th>ID</th>
            <th>CTR</th>
            <th>NAMA</th>
            <th> </th>
            <th>KE</th>
            <th>RILL</th>
            <th>AMOUNT</th>
            <th>O.S</th>
            <th>CICILAN</th>
            <th>WAJIB</th>
            <th>SUKARELA</th>
            <th>PENSIUN</th>
            <th>PAR</th>
            <th>1 angsuran</th>
            <th>tanpa Margin</th>
            <th>Warna</th>

            <th>#</th>
        </tr>
    </thead>
    <tbody>
<?php
for($row = 7;$row<=$last_row;$row++){
    $kode_pemb =  $ws->getCell("C" . $row)->getValue();
    if($kode_pemb==null){
        
    }
    else{
        $agt = (substr($kode_pemb,0,3));
        // echo $agt;
        $ket1="";
        $kode_pemb = ganti_karakter($kode_pemb);
        if($kode_pemb=='PU' || $kode_pemb=='PMB' || $kode_pemb=='PSA' || $kode_pemb=='PRR' || $kode_pemb=='PPD'  ){
            $id_nasabah =  ganti_karakter($ws->getCell("A" . $row)->getValue());
           if($id_nasabah!=null){
            $nasabah =  ganti_karakter($ws->getCell("B".$row)->getValue());
            $pensiun =  (int)ganti_karakter(str_replace(",","",$ws->getCell("S".$row)->getValue()));
            $sukarela = (int)ganti_karakter(str_replace(",","",$ws->getCell("P".$row)->getValue()));
            $wajib = (int)ganti_karakter(str_replace(",","",$ws->getCell("M".$row)->getValue()));
            
           }
           else {
              
                
                    $baris_baru = $row-1;
                    $nasabah =  ganti_karakter($ws->getCell("B".$baris_baru)->getValue());
                    // $pensiun =  (int)ganti_karakter(str_replace(",","",$ws->getCell("S".$baris_baru)->getValue()));
                    $id_nasabah =  ganti_karakter($ws->getCell("A" . $baris_baru)->getValue());
                    // $sukarela = (int)ganti_karakter(str_replace(",","",$ws->getCell("P".$baris_baru)->getValue()));
                    // $wajib = (int)ganti_karakter(str_replace(",","",$ws->getCell("M".$baris_baru)->getValue()));
                if($nasabah==null || $nasabah==" "){
                    $baris_baru = $row - 1;
                    $id_nasabah =  ganti_karakter($ws->getCell("A" . $baris_baru)->getValue());
                }
                    
                
           }
         
           $ID = sprintf("%0d",$id_nasabah);
            
            $pokok =    (int)ganti_karakter(str_replace(",","",$ws->getCell("I".$row)->getValue()));
            $margin =   (int)ganti_karakter(str_replace(",","",$ws->getCell("J".$row)->getValue()));
            $amount =   (int)ganti_karakter(str_replace(",","",$ws->getCell("F".$row)->getValue()));
            $os =       (int)ganti_karakter(str_replace(",","",$ws->getCell("G".$row)->getValue()));
            $ke =       (int)ganti_karakter(str_replace(",","",$ws->getCell("D".$row)->getValue()));
            $rill =     (int)ganti_karakter(str_replace(",","",$ws->getCell("E".$row)->getValue()));
            // $tgl = $ws->getCell("I".$row)->getValue();
            // $tgl =  date("Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($tgl));
            $q = mysqli_query($con,"SELECT a.`status_center`,b.`nama_karyawan`,a.`no_center`
            , YEAR(CURDATE()) - YEAR(c.`tgl_bergabung`) - (DATE_FORMAT(CURDATE(), '%m%d') < DATE_FORMAT(c.`tgl_bergabung`, '%m%d')) AS lama
            FROM center a 
            JOIN karyawan b ON b.id_karyawan=a.id_karyawan
            JOIN daftar_nasabah c ON c.no_center=a.`no_center` 
            WHERE c.`id_nasabah`='$ID' AND a.`id_cabang`='$id_cabang'");
            $nama = mysqli_fetch_array($q);
            $warna="";
            $cicilan = $pokok + $margin;
            $selisih = $ke - $rill;
            $ket='';
            $satu_angsuran=0;
            $warna_baris ="";
            $pensiun_tiga=0;
            $tanpa_margin=0;
            if($selisih == 0)
            {
                // echo 'double 1';
            }
            elseif($selisih>1){
                if($selisih>1 && $selisih <4){
                    
                    $ket =  $selisih - 1 ." tunggakan";
                    if($nama['status_center']=='hijau' ){
                        $warna_baris="#79ff54";
                        $warna = "hijau";
                    }
                    elseif( $nama['status_center']=='kuning')
                    {
                        $warna_baris="yellow";
                        $warna = "kuning";
                        
                    }
                    
                    if($nama['lama']>=3){
                        if($pensiun < $pensiun_tiga + 10000){
                            $pensiun_tiga  = 0;
                        }
                        else{
                            $pensiun_tiga  = ($amount * 1/100) * 1000;
                        }
                        
                        $satu_angsuran = (($sukarela - 2000) + ($pensiun - $pensiun_tiga) ) -$cicilan;
                    }
                    else{
                        $satu_angsuran = ($sukarela - 2000) -$cicilan;
                    }
                    $tanpa_margin = $os - (($wajib-2000) + ($pensiun-2000) + ($sukarela-2000));
                    // $satu_angsuran = ($sukarela - 2000) -$cicilan;
                }
                else{
                    $ket = 'par '.($selisih - 1);
                    $tanpa_margin = $os - (($wajib-2000) + ($pensiun-2000) + ($sukarela-2000));

                }
            }
            elseif($selisih<0){
                // $ket = "double ".$selisih;
            }
            ?>
        
            <tr style="background-color: <?=$warna_baris?>">
                <td><?=$no++?></td>
                <td><?=$id_nasabah?></td>
                <td><?=$nama['no_center']?></td>
                <td><?=$nasabah?></td>
                <td><?=$kode_pemb?></td>
                <td><?=$ke?></td>
                <td><?=$rill?></td>
                <td><?=angka($amount)?></td>
                <td><?=angka($os)?></td>
                <td><?=angka($cicilan)?></td>
                <td><?=angka($wajib)?></td>
                <td><?=angka($sukarela)?></td>
                <td><?=angka($pensiun)?></td>
                <td>
                    <?=$ket?>
                </td>
                <td>
                    <?=($satu_angsuran==0?"":angka($satu_angsuran))?>
                </td>
                <td>
                    <?=($tanpa_margin==0?"":angka($tanpa_margin))?>
                </td>
                <TD><?=$warna?></TD>
                <td>
                    <?=$nama['nama_karyawan']?>
                </td>
            </tr>
        
         <?php   
        }
    }
}
?>
</tbody>
<!-- <tfoot>
        <tr>
            <th>NO</th>
            <th>ID</th>
            <th>CTR</th>
            <th>NAMA</th>
            <th> </th>
            <th>KE</th>
            <th>RILL</th>
            <th>AMOUNT</th>
            <th>O.S</th>
            <th>CICILAN</th>
            <th>WAJIB</th>
            <th>SUKARELA</th>
            <th>PENSIUN</th>
            <th>PAR</th>
            <th>1 angsuran</th>
            <th>tanpa Margin</th>
            <th>Warna</th>

            <th>#</th>
        </tr>
    </tfoot> -->
</table>
