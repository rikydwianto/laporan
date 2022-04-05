<?php
        if(isset($_GET['lebih'])){
            $lebih = $_GET['lebih'];
        }
        else $lebih=7; ?>
<div class='content table-responsive'>
    <h1>CEK KELOMPOK LEBIH DARI <?=$lebih?></h1>
    <?php 
    for($i=7;$i<=15;$i++){
        ?>
        <a href="<?=$url.$menu?>cek_kelompok&lebih=<?=$i?>" class="btn"><?=$i?></a>
        <?php
    }
    ?>
    <table id='' class='table'>
        <thead>
            <tr>
                <th>NO</th>
                <th>CENTER</th>
                <th>KELOMPOK</th>
                <th>ANGGOTA</th>
                <th>STAFF</th>
            </tr>

        </thead>
        <tbody>

            <?php
        $q= mysqli_query($con,"select no_center, kelompok, count(kelompok) as total,k.nama_karyawan from daftar_nasabah d join karyawan k on k.id_karyawan=d.id_karyawan where d.id_cabang='$id_cabang' group by no_center,kelompok order by nama_karyawan,no_center,kelompok       ");
        echo mysqli_error($con);
        while($r=mysqli_fetch_array($q)){
            $qhitung = mysqli_query($con,"select count(kelompok) as total_anggota FROM daftar_nasabah where no_center='$r[no_center]' and kelompok='$r[kelompok]' and id_cabang='$id_cabang'
            ");
            $hitung = mysqli_fetch_array($qhitung)['total_anggota'];
            if($hitung>=$lebih){

                ?>
            <tr>
                <td><?=$no++?></td>
                <td><?=$r['no_center']?></td>
                <td><?=$r['kelompok']?></td>
                <td><?=$hitung?></td>
                <td><?=$r['nama_karyawan']?></td>
            </tr>
            <?php
             }
        }
        ?>
        </tbody>
        <tbody>
            <tr>
                <th>NO</th>
                <th>CENTER</th>
                <th>KELOMPOK</th>
                <th>ANGGOTA</th>
                <th>STAFF</th>
            </tr>

        </tbody>
    </table>
</div>



<script>
    $(document).ready(function() {
    $('#kelompok').DataTable( {
        initComplete: function () {
            this.api().columns().every( function () {
                var column = this;
                var select = $('<select><option value=""></option></select>')
                    .appendTo( $(column.footer()).empty() )
                    .on( 'change', function () {
                        var val = $.fn.dataTable.util.escapeRegex(
                            $(this).val()
                        );
 
                        column
                            .search( val ? '^'+val+'$' : '', true, false )
                            .draw();
                    } );
 
                column.data().unique().sort().each( function ( d, j ) {
                    select.append( '<option value="'+d+'">'+d+'</option>' )
                } );
            } );
        }
    } );
} );
</script>