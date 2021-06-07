<div class='content table-responsive'>
    <h2 class='page-header'>DAFTAR WILAYAH </h2>
    <i>Menambahkan KECAMATAN untuk cabang ini</i>
    <form action="" method="get">
        <input type="hidden" name="menu" value='daftar_wilayah'>
        <table class='table'>
            <tr>
                <td>Provinsi</td>
                <td>
                    <select name="prov" id="" class='form-control' onchange="this.form.submit()">
                        <option value="">Silahkan pilih Provinsi</option>

                        <?php
                        $idprov = $_GET['prov'];
                        $qprov  = mysqli_query($con, "SELECT kode,nama FROM daftar_wilayah WHERE CHAR_LENGTH(kode)=2 ORDER BY nama");
                        while ($prov = mysqli_fetch_array($qprov)) {
                            if ($prov['kode'] == $idprov)
                                echo "<option selected value='$prov[kode]'>$prov[nama]</option>";
                            else
                                echo "<option  value='$prov[kode]'>$prov[nama]</option>";
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Kabupaten</td>
                <td>
                    <select name="kab" id="" class='form-control' onchange="this.form.submit()">
                        <option value="">Silahkan pilih Kabupaten</option>

                        <?php
                        $idkab = $_GET['kab'];
                        $qkab  = mysqli_query($con, "SELECT * FROM daftar_wilayah WHERE LEFT(kode,2)=$idprov AND CHAR_LENGTH(kode)=5 ORDER BY nama");

                        while ($Kab = mysqli_fetch_array($qkab)) {
                            if ($Kab['kode'] == $idkab)
                                echo "<option selected value='$Kab[kode]'>  $Kab[nama]</option>";
                            else
                                echo "<option  value='$Kab[kode]'>$Kab[nama]</option>";
                        }
                        ?>
                    </select>
                </td>
            </tr>

            <tr>
                <td>Kecamatan</td>
                <td>
                    <select name="kec" id="" class='form-control' onchange="this.form.submit()">
                        <option value="">Silahkan pilih Kecamatan</option>

                        <?php
                        $idkec = $_GET['kec'];
                        $qkec  = mysqli_query($con, "SELECT * FROM daftar_wilayah WHERE LEFT(kode,5)='$idkab' AND CHAR_LENGTH(kode)=8 ORDER BY nama");

                        while ($kec = mysqli_fetch_array($qkec)) {
                            if ($kec['kode'] == $idkec)
                                echo "<option selected value='$kec[kode]'>  $kec[nama]</option>";
                            else
                                echo "<option  value='$kec[kode]'>$kec[nama]</option>";
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>DESA</td>
                <td>
                    <select name="desa" id="" class='form-control' onchange="this.form.submit()">
                        <option value="">Silahkan pilih DESA</option>

                        <?php
                        $iddesa = $_GET['desa'];
                        $qdesa  = mysqli_query($con, "SELECT * FROM daftar_wilayah WHERE LEFT(kode,8)='$idkec' AND CHAR_LENGTH(kode)=13 ORDER BY nama");

                        while ($desa = mysqli_fetch_array($qdesa)) {
                            if ($desa['kode'] == $iddesa)
                                echo "<option selected value='$desa[kode]'>  $desa[nama]</option>";
                            else
                                echo "<option  value='$desa[kode]'>$desa[nama]</option>";
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <?php
            if (!empty($iddesa)) {
            ?>
                <tr>
                    <td>Tambahkan 1 Desa saja </td>
                    <td><button type="submit" class='btn btn-info' name='satu_desa'>Tambahkan Desa <?= wilayah($con, $iddesa) ?></button></td>
                </tr>
            <?php }
            ?>
        </table>
        <?php
        if (!empty($idkec)) {
        ?>
            <div class="card" style="width: 18rem;">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item"> <b>
                            <center>Kecamatan <?= wilayah($con, $idkec) ?></center>
                        </b></li>
                    <?php
                    $qdesa1  = mysqli_query($con, "SELECT * FROM daftar_wilayah WHERE LEFT(kode,8)='$idkec' AND CHAR_LENGTH(kode)=13 ORDER BY nama");
                    while ($desa1 = mysqli_fetch_array($qdesa1)) {

                        echo "<li class='list-group-item'>$desa1[nama]</li>";
                    }
                    ?>
                    <li class='list-group-item'> <button type="submit" class='btn btn-danger' name='kecamatan_desa'>Tambahkan Semua </button></li>

                </ul>
            </div>
        <?php
        }
        ?>
    </form>
</div>


<?php
if (isset($_GET['satu_desa'])) {
    $desa = wilayah($con, $iddesa);
    $keca = wilayah($con, $idkec);
    $wilaya  = mysqli_query($con, "SELECT * FROM daftar_wilayah_cabang WHERE desa='$desa' and kecamatan='$keca' and id_cabang='$id_cabang' limit 0,1");
    $wilaya = mysqli_fetch_array($wilaya);
    if ($wilaya['desa'] != '') {
        $desa_T[] = $wilaya['desa'];
        alert(" Tidak bisa ditambahkan $desa Telah diinput di Database sebelumnya");
    } else {
        $q = mysqli_query($con, "INSERT INTO `daftar_wilayah_cabang` (`kecamatan`, `desa`, `id_cabang`) VALUES ('$keca', '$desa', '$cabang');");
        alert(" Desa $desa  Berhasil Ditambahkan $keterangan");
    }

    

   
    kembali();
}
if (isset($_GET['kecamatan_desa'])) {
    $qdesa2  = mysqli_query($con, "SELECT * FROM daftar_wilayah WHERE LEFT(kode,8)='$idkec' AND CHAR_LENGTH(kode)=13 ORDER BY nama");
    $keca = wilayah($con, $idkec);
    while ($desa2 = mysqli_fetch_array($qdesa2)) {
        $desa = wilayah($con, $desa2['kode']);

        $wilaya  = mysqli_query($con, "SELECT * FROM daftar_wilayah_cabang WHERE desa='$desa' and kecamatan='$keca' and id_cabang='$id_cabang' limit 0,1");
        $wilaya = mysqli_fetch_array($wilaya);
        if ($wilaya['desa'] != '') {
            $desa_T[] = $wilaya['desa'];
        } else {
            $q = mysqli_query($con, "INSERT INTO `daftar_wilayah_cabang` (`kecamatan`, `desa`, `id_cabang`) VALUES ('$keca', '$desa', '$cabang');");
        }
    }
    if (count($desa_T) > 0) $keterangan = "Kecuali " . implode(", ", $desa_T,) . "Sudah Pernah di input";
    else $keterangan = "";

    alert("Semua Desa Di Kecamatan : $keca  Berhasil Ditambahkan $keterangan");
    kembali();
}
?>