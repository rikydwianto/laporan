

<div class="row">

    <div class="col-lg-6">
    	<div class="card shadow mb-4">
	        <div class="card-header py-3">
	            <h6 class="m-0 font-weight-bold text-primary">Data Karyawan : <?=$karyawan['nama_karyawan'] ?></h6>
	        </div>
	        <div class="card-body table-responsive">
	        	<table class="table">
	        		<tr>
	        			<td>NIK</td>
	        			<td><?=$karyawan['nik_karyawan']?></td>
	        		</tr>
	        		<tr>
	        			<td>NAMA</td>
	        			<td><?=$karyawan['nama_karyawan']?></td>
	        		</tr>
	        		<tr>
	        			<td>JABATAN</td>
	        			<td><?=$karyawan['nama_jabatan']?></td>
	        		</tr>
	        		<tr>
	        			<td>CABANG</td>
	        			<td><?=$karyawan['nama_cabang']?></td>
	        		</tr>
	        		<tr>
	        			<td>STATUS KARYAWAN </td>
	        			<td><?=$karyawan['status_karyawan']?></td>
	        		</tr>
	        		<tr>
	        			<td>&nbsp; </td>
	        			<td>
	        				<a href='<?=base_url('home/karyawan') ?>' class="btn btn-sm btn-danger"><i class="fa fa-backspace"></i> Kembali</a>
	        			</td>
	        		</tr>
	        	</table>
	        </div>
	    </div>


    </div>
    <div class="col-lg-6">
    	<div class="card shadow mb-4">
	        <div class="card-header py-3">
	            <h6 class="m-0 font-weight-bold text-primary">EDIT Karyawan : <?=$karyawan['nama_karyawan'] ?></h6>
	        </div>
	        <div class="card-body table-responsive">
	        	<?php if($this->session->flashdata('pesan')==true)
	        		{
	        			echo pesan($this->session->flashdata('pesan','success'));
	        		}
	        	?>
	        	<?php echo validation_errors(); ?>
	        	<form method="post" action="<?=base_url('home/editkaryawan')?>">
	        		<table class="table">
		        		<tr>
		        			<td>NIK</td>
		        			<td>
		        				<input type="hidden" name="id" class='form-control' value='<?=$karyawan['id_karyawan']?>'><input type="text" name="nik" class='form-control' value='<?=$karyawan['nik_karyawan']?>'>
		        			</td>
		        		</tr>
		        		<tr>
		        			<td>NAMA</td>
		        			<td>
		        				<input type="text" name="nama" class='form-control' value='<?=$karyawan['nama_karyawan']?>'>
		        			</td>
		        		</tr>
		        		<tr>
		        			<td>JABATAN</td>
		        			<td>
		        				<select class="form-control" required="" name='jabatan'>
		        					<option value=''>Silakan Pilih</option>
		        					<?php foreach ($jabatan as $jab ): 
		        						if($jab['id_jabatan']==$karyawan['id_jabatan'])
		        							$jab_select="selected";
		        						else $jab_select="";

	        						
	        						?>
	        						<option value='<?=$jab['id_jabatan']?>' <?=$jab_select?>><?=$jab['nama_jabatan']?></option>
		        					<?php endforeach;?>
		        				</select>
		        			</td>
		        		</tr>
		        		<tr>
		        			<td>CABANG</td>
		        			<td>
		        				<select class="form-control" required="" name='cabang'>
		        					<option value=''>Silakan Pilih</option>
		        					<?php foreach ($cabang as $cab ): 
		        						if($cab['id_cabang']==$karyawan['id_cabang'])
		        							$cab_select="selected";
		        						else $cab_select="";

	        						
	        						?>
	        						<option value='<?=$cab['id_cabang']?>' <?=$cab_select?>><?=strtoupper($cab['nama_cabang'])?></option>
		        					<?php endforeach;?>
		        				</select>

		        			</td>
		        		</tr>
		        		<tr>
		        			<td>STATUS KARYAWAN </td>
		        			<td>
	        				<?php 
							if($karyawan['status_karyawan']=='aktif') 
							{
								$ch='checked';
								$ch1='';
							}
							else{

								$ch1='checked';
								$ch='';
							}
							?>
								<input type="radio" name='status' value='aktif' id='status' <?=$ch?>/> <label for="status" class="form-label">AKTIF</label>
								<input type="radio" name='status' value='tidakaktif' id='status1' <?=$ch1?>/> <label for="status1" class="form-label">TIDAK AKTIF</label>
		        			</td>
		        		</tr>
		        		<tr>
		        			<td>&nbsp; </td>
		        			<td>
		        				<button type="submit"  class="btn btn-sm btn-info"><i class="fa fa-save"></i> Simpan </button>
		        			</td>
		        		</tr>
		        	</table>
	        	</form>
	        </div>
	    </div>


    </div>

</div>