

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
        <th>Status</th>
        <th>Detail</th>

    </thead>
    <tbody>
        <?php
        $sql = "SELECT * FROM upk where id_cabang ='$id_cabang' and tgl_upk >= '$tglawal' and tgl_upk <= '$tglakhir' order by tgl_upk,id_karyawan asc ";
        $query  = mysqli_query($con,$sql);
        $total_anggota = 0;
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
                <td><?=$upk['status']?></td>
                <td>
                
                    <a href="#" onclick="detail_center('<?=$cari['no_center']?>')">Detail</a>
                    <?php 
                    if($upk['status']!='jadi'){

                    
                    ?> | 
                    <a href="<?=$url . $menu?>upk&hapus&id_upk=<?=$upk['id_upk']?>&tglawal=<?=$tglawal?>&tglakhir=<?=$tglakhir?>&cari=FILTER" class='btn'><i class='fa fa-times'></i></a>
                    <a href="<?=$url . $menu?>upk&edit&id_upk=<?=$upk['id_upk']?>&tglawal=<?=$tglawal?>&tglakhir=<?=$tglakhir?>&cari=FILTER" class='btn'><i class='fa fa-edit'></i></a>
                    <?php }
                    ?>
                </td>
            </tr>
        <?php
        $total_anggota = $total_anggota + $total;
        }
        ?>
    </tbody>
    <?php 
    if(mysqli_num_rows($query)){
        ?>
    <tfoot>
        <tr>
            <th colspan=5>Total Anggota UPK</th>
            <th align="center"><?=$total_anggota?></th>
            <th></th>

        </tr>

    </tfoot>
        <?php
    }
    else{
        ?>
        <tfoot>
            <tr>
                <th colspan=8><center>Tidak ada data!</center></th>
                

            </tr>

        </tfoot>
        <?php
    }
    ?>
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