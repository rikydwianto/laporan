<?php
$hitung = new Hitung();
$status = ( $hitung->hitung_status($con,$id_cabang));
?>
<div class="row">
    <div class="col-lg-3 col-md-6">
        <div class="panel panel-red">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-odnoklassniki fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge"><?=$hitung->hitung_staff($con,$id_cabang);?></div>
                        <div>Staff Lapang</div>
                    </div>
                </div>
            </div>
            <a href="<?=$url.$menu?>karyawan">
                <div class="panel-footer">
                    <span class="pull-left">Total Staff Lapang</span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>

                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="panel panel-yellow">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-file-excel-o fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge"><?=$hitung->hitung_laporan($con,$id_cabang);?></div>
                        <div>Staff</div>
                    </div>
                </div>
            </div>
            <a href="<?=$url.$menu?>rekap_laporan">
                <div class="panel-footer">
                    <span class="pull-left">Laporan & Sudah Konfirmasi</span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>

                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-building fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge"><?=$hitung->hitung_center($con,$id_cabang);?></div>
                        <div>Total Center </div>
                    </div>
                </div>
            </div>
            <a href="<?=$url.$menu?>center">
                <div class="panel-footer">
                    <span class="pull-left">Center</span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>

                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="panel panel-green">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-users fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge"><?=$hitung->hitung_member($con,$id_cabang);?></div>
                        <div>Member</div>
                    </div>
                </div>
            </div>
            <a href="<?=$url.$menu?>center">
                <div class="panel-footer">
                    <span class="pull-left">Total Anggota</span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>

                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>
    
    
</div>