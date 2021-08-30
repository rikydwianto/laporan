<table id='data_center'>
		<thead>
			<tr>
				<th>NO</th>
				<th>Nama Kuis</th>
				<th>Penerbit</th>
				<th>Tanggal</th>
				<th>Lama Pengerjaan</th>
				<th>status</th>
				<th>acak</th>
				<th>#</th>
				
			</tr>
		</thead>
        
		<tbody>
            <?php 
            $q=mysqli_query($con,"select * from kuis left join karyawan on kuis.id_karyawan=karyawan.id_karyawan where kuis.id_cabang='$id_cabang' order by tgl_kuis asc");
            while($kuis = mysqli_fetch_array($q)){
                ?>
            <tr>
                <td><?=$no++?></td>
                <td><?=$kuis['nama_kuis']?></td>
                <td><?=$kuis['nama_karyawan']?></td>
                <td><?=$kuis['tgl_kuis']?></td>
                <td><?=$kuis['waktu_pengerjaan']?> menit</td>
                <td><?=$kuis['status']?></td>
                <td><?=$kuis['acak']?></td>
                <td>
                    <a href="<?=$url.$menu?>kuis&act=tambah-soal&idkuis=<?=$kuis['id_kuis']?>" class="btn btn-danger"> kelola soal </a>
                    <a href="" class="btn"> <i class="fa fa-gears"></i> </a>

                </td>
            </tr>
                <?php
            }
            ?>
            
        </tbody>
	</table>