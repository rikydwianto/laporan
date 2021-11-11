<div class='content table-responsive'>
	<h2 class='page-header'>PERBAIKAN DATA </h2>
	<i></i>
    <a href="<?=$url.$menu?>perbaikan" class="btn btn-info"> Lihat Data</a>
    <hr/>
	  <!-- Button to Open the Modal -->


	<?php 
   @ $id_perbaikan = aman($con,$_GET['id_perbaikan']);
    if(isset($_GET['belum'])){
        mysqli_query($con,"UPDATE perbaikan set status='belum' where id_perbaikan='$id_perbaikan'") ;   
        pindah($url.$menu."perbaikan");
    }
    else{
        ?>
        <table id='data_center' class='table-bordered'> 
		<thead>
			<tr>
				<th>ID</th>
				<th>NAMA</th>
				<th>KESALAHAN</th>
				<th>NO HP</th>
				<th>KETERANGAN</th>
				<th>KETERANGAN LAIN</th>
				<th>STAFF</th>

				<th>#</th>
			</tr>
		</thead>
		<tbody>
		<?php 
        $q = mysqli_query($con,"SELECT * from perbaikan 
        JOIN karyawan on perbaikan.id_karyawan=karyawan.id_karyawan
        JOIN center on perbaikan.no_center=center.no_center where perbaikan.status='sudah' ");
        while($kes = mysqli_fetch_array($q)){
            ?>
            <tr>
				<td><?=$kes['id_detail_nasabah']?></td>
				<td><?=$kes['nama_nasabah']?></td>
				<td><?=$kes['kesalahan']?></td>
				<td><?=$kes['no_hp']?></td>
				<td><b>
                    <?php 
                    echo ($kes['nama_ibu_kandung']=== null ? "" : "Ibu : $kes[nama_ibu_kandung]<br/>");
                    echo ($kes['nik_ktp']=== null ? "" : "ktp : $kes[nik_ktp]<br/>");
                    echo ($kes['status_pernikahan']=== null ? "" : "status : $kes[status_pernikahan]<br/>");
                    echo ($kes['tgl_lahir']=== null ? "" : "lahir : $kes[tgl_lahir]<br/>");
                    echo ($kes['alamat']=== null ? "" : "alamat : $kes[alamat]<br/>");
                    ?>
                    </b>
                </td>
				<td>
                    <?=($kes['keterangan_lain'])?>
                </td>
				<td><?=($kes['nama_karyawan'])?></td>

				<td>
                    <a href="<?=$url.$menu?>perbaikan&belum&id_perbaikan=<?=$kes['id_perbaikan']?>" class="btn btn-danger">balikan</a>

                </td>
            </tr>
           
            <?php
        }
        ?>
		</tbody>
	</table>
   
        <?php
    }
    ?>
</div>
