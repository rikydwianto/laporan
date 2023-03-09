<?php 
$tgl = $_GET['tgl_cair'];
?>
<h1>LIST PINJAMAN BELUM POSTING KE MONITORING</h1>
<TABLE class='table' id='data_karyawan'>
    <thead>
        <tr>
            <th>no</th>
            <th>STAFF</th>
            <th>NO Pinjaman</th>
            <th>ID NASABAH</th>
            <th>NASABAH</th>
            <th>CTR</th>
            <th>PINJAMAN</th>
            <th>PRODUK</th>
            <th>#</th>
        </tr>
    </thead>
    <tbody>
        <?php  $q = mysqli_query($con, "select *,DATEDIFF(CURDATE(), tgl_cair) as total_hari from pinjaman left 
                        join karyawan on karyawan.id_karyawan=pinjaman.id_karyawan 
        
        where pinjaman.id_cabang='$id_cabang' and input_mtr='belum' and pinjaman.tgl_cair='$tgl' order by karyawan.nama_karyawan asc");
    while ($pinj = mysqli_fetch_array($q)) {
?>
    <tr>
        <td><?=$no++?></td>
        <td><?=$pinj['nama_karyawan']?></td>
        <td><?=$pinj['id_detail_pinjaman']?></td>
        <td><?=$pinj['id_detail_nasabah']?></td>
        <td><?=$pinj['nama_nasabah']?></td>
        <td><?=$pinj['center']?></td>
        <td><?=$pinj['produk']?></td>
        <td><?=$pinj['tujuan_pinjaman']?></td>
        <td>
            <?php 
            $ref= urlencode("list_bagi&tgl_cair=$tgl&data");
            ?>
            <a href="<?=$url.$menu?>monitoring&hapus&id=<?=$pinj['id_pinjaman']?>&ref=<?=$ref?>" class="btn btn-danger">Hapus Pinjaman</a>
            <a href="<?=$url.$menu?>monitoring&hapus_tpk&id=<?=$pinj['id_detail_nasabah']?>&ref=<?=$ref?>" class="btn btn-primary">HAPUS TPK</a>
        </td>
    </tr>
<?php

    } ?>
    </tbody>
</TABLE>