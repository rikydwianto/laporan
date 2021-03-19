

<div class="row">

    <div class="col-lg-12">
    	<div class="card shadow mb-4">
	        <div class="card-header py-3">
	            <h6 class="m-0 font-weight-bold text-primary">Data Karyawan Keseluruhan</h6>
	        </div>
	        <div class="card-body table-responsive">
	        	<table class='table ' id="karyawan">
	        		<thead>
	        			<tr>
	        				<th>No</th>
		        			<th>NIK</th>
		        			<th>Nama</th>
		        			<th>Jabatan</th>
		        			<th>Cabang</th>
		        			<th>#</th>
	        			</tr>
	        		</thead>
	        		<tbody>
	        			<?php 
	        			$no=1;
	        			foreach ($karyawan as $kar):?>
	        			<tr>
	        				<td><?=$no++?></td>
		        			<td><?=$kar['nik_karyawan']?></td>
		        			<td><?=$kar['nama_karyawan']?></td>
		        			<td><?=$kar['nama_jabatan']?></td>
		        			<td><?=$kar['nama_cabang']?></td>
		        			<td>
		        				<a href='<?=base_url("home/karyawan/".$kar['id_karyawan'])?>' class="btn btn-circle btn-info btn-sm">
		        					<i class="fa fa-eye"></i>
		        				</a>


		        			</td>
	        			</tr>
	        			<?php endforeach; ?>        			
	        		</tbody>
	        		<tfoot>
	        			<tr>
	        				<td>No</td>
		        			<td>NIK</td>
		        			<td>Nama</td>
		        			<td>Cabang</td>
		        			<td>Jabatan</td>
		        			<td>#</td>
	        			</tr>
	        		</tfoot>
	        	</table>

	        </div>
	    </div>


    </div>

</div>