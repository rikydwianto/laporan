<div class='content table-responsive'>
    <h2 class='page-header'>Penarikan Simpanan oleh Manager</h2>

    <?php
    if (isset($_POST['simpan'])) {
        $id = $_POST['id'];
        $nominal = clean_angka($_POST['nominal']);
        $wajib = clean_angka($_POST['wajib']);
        $alasan = ($_POST['alasan']);
        $pemb = ($_POST['pemb']);
        $masuk = ($_POST['masuk']);
        $angsuran = ($_POST['angsuran']);
        $sukarela = clean_angka($_POST['sukarela']);
        $pensiun = clean_angka($_POST['pensiun']);
        $hariraya = clean_angka($_POST['hariraya']);
        
        $sisa_hariraya = clean_angka($_POST['sisa_hariraya']);
        $sisa_sukarela = clean_angka($_POST['sisa_sukarela']);
        $sisa_wajib = clean_angka($_POST['sisa_wajib']);
        $sisa_pensiun = clean_angka($_POST['sisa_pensiun']);

        $sisa_semua = clean_angka($_POST['sisa']);
        $tgl = $_POST['tgl'];
        for ($a = 0; $a < count($id); $a++) {
            if (($id[$a] != "") && ($nominal[$a] != null)) {
                if($jabatan!="SL"){
                    $id_karyawan = $_POST['staff'][$a];
                }
                else $id_karyawan = $id_karyawan;

                $id_zero = sprintf("%00d",$id[$a]);
                $S_sukarela  = $sisa_sukarela[$a] - $sukarela[$a];
                $S_pensiun  = $sisa_pensiun[$a] - $pensiun[$a];
                $S_wajib  = $sisa_wajib[$a] - $wajib[$a];
                $S_hariraya  = $sisa_hariraya[$a] - $hariraya[$a];
                $query = mysqli_query($con, "INSERT INTO `penarikan_simpanan` 
                (`id_anggota`, `tgl_penarikan`, `nominal_penarikan`,`wajib`,`sukarela`,`pensiun`,`hariraya`,`alasan`,`angsuran_masuk`, `cicilan`,`id_karyawan`,`id_cabang`,`kode_pemb`,`sisa_semua`,`sisa_wajib`,`sisa_sukarela`,`sisa_pensiun`,`sisa_hariraya`) VALUES 
                ('$id_zero', '$tgl[$a]', '$nominal[$a]','$wajib[$a]','$sukarela[$a]','$pensiun[$a]','$hariraya[$a]','$alasan[$a]','$masuk[$a]','$angsuran[$a]', '$id_karyawan','$id_cabang','$pemb[$a]','$sisa_semua[$a]','$S_wajib','$S_sukarela','$S_pensiun','$S_hariraya'); ");
                if ($query) {
                    alert("Berhasil disimpan");
                    pindah($url . $menu . "penarikan_simpanan");
                } else {
                    alert("gagal disimnpan");
                }
            }
        }
    }


    if ($jabatan == "SL") {
    ?>
        <table class='table'>
            <tr>
                <th>No</th>
                <th>ID</th>
                <th>ID Nasabah</th>
                <th>Nasabah</th>
                <th>Nominal</th>
            </tr>

            <?php
            $tgl = date("Y-m-d");
            $total_penarikan = 0;
            $penarikan = mysqli_query($con, "SELECT * FROM penarikan_simpanan left JOIN daftar_nasabah ON daftar_nasabah.`id_nasabah`=penarikan_simpanan.`id_anggota` where penarikan_simpanan.tgl_penarikan='$tgl' and penarikan_simpanan.id_karyawan='$id_karyawan' and daftar_nasabah.id_cabang='$id_cabang' and penarikan_simpanan.id_cabang='$id_cabang' ");
            while ($simp = mysqli_fetch_array($penarikan)) {
                $total_penarikan = $total_penarikan + $simp['nominal_penarikan'];
            ?>
                <tr>
                    <td><?= $no++ ?>.</td>
                    <td><?= $simp['id_anggota'] ?></td>
                    <td><?= $simp['id_detail_nasabah'] ?></td>
                    <td><?= $simp['nama_nasabah'] ?></td>
                    <td><?= rupiah($simp['nominal_penarikan']) ?></td>
                </tr>
            <?php
            }
            ?>
            <tr>
                <th colspan="4">Total Penarikan</th>
                <th><?= rupiah($total_penarikan) ?></th>
            </tr>
        </table>
    <?php

    }
    else{
        ?>
            <a href="<?=$url.$menu?>penarikan_simpanan&list=cari&cari=FILTER" class='btn btn-success'>
                <i class="fa fa-plus"></i> Tambah
            </a>
            <!-- tglawal=<?=$tglawal?>&tglakhir=<?=$tglakhir?>& -->
            <a href="<?=$url.$menu?>list_penarikan&list=cari&cari=FILTER" class='btn btn-info'>
        <i class="fa fa-eye"></i> Lihat data
    </a>
        <?php
    }
    ?>

<form method="post">
        <!-- jangan gunakan titik(.)  masukan angka saja contoh Rp. 100.000 input (100000) <br> -->
        <div class="col-md-12">
            <table class="table">
                <tr>
                    <th>No.</th>
                    <th>ID angggota</th>
                    <th>SIMPANAN</th>
                   
                    <th>Tanggal</th>
                </tr>
                <?php
                
                for ($i = 1; $i <= 20; $i++) {
                ?>
                    <tr>
                        <td><?= $i ?></td>
                        <td>
                            
                        <input type="number" style="width: 120px;" class='form-control' id='id-<?=$i?>' onchange="alasan(<?=$i?>)" name='id[]' />
                    </td>
                        <td >
                            <p id='alasan-<?=$i?>'><a href="javascript:void(0)" style='float:right' class="btn btn-primary btn-sm"><i class="fa fa-search"></i></a></p>
                            <p id='alasan1-<?=$i?>'></p>
                        </td>
                        <td>
                            <input type="date" class='form-control' id='tgl-<?=$i?>' onchange="ganti_total(<?=$i?>)" name='tgl[]' value="<?= date("Y-m-d") ?>" id='tgl' />
                        </td>
                        <?php 
                        if($jabatan !='SL'){
                            ?>
                            <td>
                                <select name="staff[]"  class='form-control' id="karyawan-<?=$i?>">
                                    <option value="">Pilih Staff</option>
                                    <?php 
                                    $k = mysqli_query($con,"select * from karyawan where id_cabang='$id_cabang' and status_karyawan ='aktif' order by nama_karyawan asc");
                                    while($staff = mysqli_fetch_array($k)){
                                        echo "<option value='$staff[id_karyawan]'>$staff[nama_karyawan]</option>";
                                    }
                                    ?>
                                </select>
                            </td>
                            <?php
                        }
                        ?>
                    </tr>
                <?php
                }
                ?>
                <tr>
                    <td></td>
                    <td>
                        <input type="submit"  name="simpan" value="SIMPAN" class="btn btn-success" />
                    </td>
                </tr>
            </table>
        </div>
    </form>
</div>

<script>
    function ganti_total(nomor){
        let wajib = parseInt($("#wajib-"+nomor).val())
        let sukarela =parseInt($("#sukarela-"+nomor).val())
        let pensiun =parseInt($("#pensiun-"+nomor).val())
        let hariraya =parseInt($("#hariraya-"+nomor).val())
        let total = wajib + sukarela + pensiun + hariraya
        
        let saldo = $("#saldo-"+nomor).val();
        let semua = parseInt($("#semua-"+nomor).val())
        let total_simpanan = semua - total;
        $("#nominal-"+nomor).val(total);
        $("#total-"+nomor).html("Total:"+total);
        $("#total1-"+nomor).html(total_simpanan.toLocaleString('en-US')+"<br>"+semua.toLocaleString('en-US'));
        $("#saldo-"+nomor).val(total_simpanan);
    }
    let url="<?=$url?>/api/";
    let cab="<?=$id_cabang?>";
    function alasan(nomor,pemb=""){
        let id= $("#id-"+nomor).val()
        let tgl= $("#tgl-"+nomor).val()
       if(id>0){
           $("#karyawan-"+nomor).attr("required","required");
       }
       else{
        $("#karyawan-"+nomor).removeAttr("required","required");
       }
        
        // alert(id)
        $.get(url+'cek_pembiayaan.php?cab='+cab+"&urut="+nomor+"&id="+id,function(data){
            // alert(data.trim());
            if(data.trim()==2){
                // alert("ada dua pembiayaan");
                $.get(url+'cek_pembiayaan.php?CEKPEM&cab='+cab+"&urut="+nomor+"&id="+id,function(data){
                    $("#alasan-"+nomor).html(data)

                });
            }
            else{
                $.get(url+'alasan_input.php?cab='+cab+"&urut="+nomor+"&id="+id+"&tgl="+tgl,function(data){
                    $("#alasan-"+nomor).html(data)
                    
                });

            }
        });
    }
    function ganti_pem(nomor){
        let isi = $("#pemb-"+nomor).val();
        let id= $("#id-"+nomor).val()
        let tgl= $("#tgl-"+nomor).val()
        $.get(url+'alasan_input.php?pemb='+isi+'&cab='+cab+"&urut="+nomor+"&id="+id+"&tgl="+tgl,function(data){
            $("#alasan-"+nomor).html(data)
            
        });

    }
</script>