<div class="container-fluid">
<?php 
$tglawal = $_GET['tglawal'];
$tglakhir = $_GET['tglakhir'];
$title_tpk = "Rekap TPK per Staff ". format_hari_tanggal($tglawal). " - ". format_hari_tanggal($tglakhir) .'<br>';
?>
	<div class="row">
		<div class="col-lg-8">
			<h1 class="page-header">REKAP PINJAMAN TOPUP KHUSUS</h1>
            
			<br>
            <form>
			<div>
				
					
				<input type="hidden" name='menu' value='rekap_tpk'/>
				<input type="date" name='tglawal' value="<?=(isset($_GET['tglawal']) ?  $_GET['tglawal'] : date("Y-m-d",(strtotime ( '-5 day' , strtotime ( date("Y-m-d")) ) )) )?>" class=""/>
				<input type="date" name='tglakhir' value="<?=(isset($_GET['tglakhir']) ?  $_GET['tglakhir'] : date("Y-m-d"))?>" class=""/>
				<input type='submit' class="btn btn-info" name='cari' value='FILTER'/>
			</div>
			
		</form>
        <h2><?=$title_tpk?></h2>
			<table class='table table-bordered'>
            <?php
            $qtgl = mysqli_query($con,"SELECT distinct tgl_cair as tgl FROM pinjaman p JOIN tpk t ON p.`id_detail_nasabah`=t.`id_detail_nasabah` where p.id_cabang='$id_cabang' and t.id_cabang='$id_cabang'  and p.tgl_cair>='$tglawal' and p.tgl_cair<='$tglakhir'"); 
            ?>    
                <thead>
                    <tr>
                        <th>NO</th>
                        <th>STAFF</th>
                        <?php 
                        while($tgl = mysqli_fetch_array($qtgl)){
                            ?>
                            <th><?=$tgl['tgl']?></th>
                            <?php
                        }
                        ?>
                        <th>TOTAL</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    if(isset($_GET['cari'])){
                        $total_tpk = 0;
                        $q = mysqli_query($con,"SELECT * FROM karyawan JOIN jabatan ON jabatan.`id_jabatan`=karyawan.`id_jabatan` WHERE id_cabang='$id_cabang' AND singkatan_jabatan='SL' AND status_karyawan='aktif' order by nama_karyawan asc");
                        while($kar = mysqli_fetch_array($q)){
                            $tpk = mysqli_query($con,"SELECT COUNT(*) as total FROM pinjaman p JOIN tpk t ON p.`id_detail_nasabah`=t.`id_detail_nasabah` where p.id_cabang='$id_cabang' and t.id_cabang='$id_cabang'  and p.tgl_cair>='$tglawal' and p.tgl_cair<='$tglakhir' and p.id_karyawan='$kar[id_karyawan]'");
                            $tpk = mysqli_fetch_array($tpk)['total'];
                            $total_tpk+=$tpk;
                            ?>
                            <tr>
                                <td><?=$no++?></td>
                                <td><?=$kar['nama_karyawan']?></td>
                                <td><?=$tpk?></td>
                            </tr>
                            <?php
                        }
                    }
                    ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="2">TOTAL</th>
                        <th colspan="1"><?=$total_tpk?></th
                    </tr>
                </tfoot>
            </table>
		</div>
	</div>

	
</div>
