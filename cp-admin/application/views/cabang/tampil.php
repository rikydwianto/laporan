

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
		        			<th>CABANG</th>
		        			<th>WILAYAH</th>
		        			<th>#</th>
	        			</tr>
	        		</thead>
	        		<tbody>
	        			<?php 
	        			$no=1;
	        			foreach ($cabang as $cab):?>
	        			<tr>
	        				<td><?=$no++?></td>
		        			<td><?=$cab['nama_cabang']?></td>
		        			<td><?=$cab['wilayah']?></td>
		        			<td>
		        				<a href='<?=base_url("home/karyawan/".$cab['id_cabang'])?>' class="btn btn-circle btn-info btn-sm">
		        					<i class="fa fa-eye"></i>
		        				</a>


		        			</td>
	        			</tr>
	        			<?php endforeach; ?>        			
	        		</tbody>
	        	</table>

	        </div>
	    </div>


    </div>

</div>