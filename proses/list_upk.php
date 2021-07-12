

<br />
<div id="detail_center">
    
</div>
</br>
<table class='table table-bordered'>
    <thead>
        <th>No</th>
        <th>Tanggal</th>
        <th>Staff</th>
        <th>Center</th>
        <th>Hari/Jam</th>
        <th>Total UPK</th>
        <th>Detail</th>

    </thead>
    <tbody>
        <?php
        $sql = "SELECT * FROM upk where id_cabang ='$id_cabang' and tgl_upk >= '$tglawal' and tgl_upk <= '$tglakhir' order by tgl_upk,id_karyawan asc ";
        $query  = mysqli_query($con,$sql);
        while ($upk = mysqli_fetch_array($query)) {
            $cari = mysqli_query($con, "select * from center where id_cabang='$id_cabang' AND no_center='" . $upk['no_center'] . "'");
            $cari = mysqli_fetch_array($cari);
        ?>
            <tr>
                <td><?=$no++?></td>
                <td><?=format_hari_tanggal($upk['tgl_upk'])?></td>
                <td><?=detail_karyawan($con,$upk['id_karyawan'])['nama_karyawan']?></td>
                <td><?=$cari['no_center']?></td>
                <td><?=$cari['hari']?>/<?=$cari['jam_center']?></td>
                <td><?=$total = $upk['anggota_upk']?></td>
                <td>
                
                    <a href="#" onclick="detail_center('<?=$cari['no_center']?>')">Detail</a>
                
                </td>
            </tr>
        <?php
        }
        ?>
    </tbody>
</table>


<script>
    function detail_center(center) {
        $(document).ready(function() {
            var url_link = "<?php $url ?>";
            var cab = "<?= $id_cabang ?>";
            $.get(url_link + "api/detail_center.php?center=" + center + "&cab=" + cab, function(data, status) {
                $("#detail_center").html(data);
            });
            
        });
    }
    function close_center(){
        $("#detail_center").html("");
    }
</script>