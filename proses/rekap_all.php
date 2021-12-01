<?php $tgl_awal  = $_GET['sebelum'];
        $tgl_banding = $_GET['minggu_ini']; ?>
<div class="col-md-12">
        <form action="" method="get">
            <input type="hidden" name="menu" value="rekap_all">
                <div class="col-md-4">
                    <h3>MINGGU SEBELUM NYA</h3>
                    <input type="date" value="<?=($tgl_awal==""?date("Y-m-d"):$tgl_awal)?>" required name="sebelum" class='form-control' id="">
                    
                    
                </div>
                <div class="col-md-4">
                    <h3>MINGGU INI</h3>
                    <input type="date" required value="<?=($tgl_banding==""?date("Y-m-d"):$tgl_banding)?>" name="minggu_ini" class='form-control' id="">
                    <input type="submit" value="REKAP SEMUA" name='rekap_semua' class='btn btn-danger btn-md btn-info'>

                </div>
            </form>
        </div>
<?php 
if(isset($_GET['rekap_semua'])){
    include("./proses/rekap_semua.php");
}
?>