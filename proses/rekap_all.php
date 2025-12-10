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
                    <input type="submit" value="REKAP CASHFLOW" name='rekap_cashflow' class='btn btn-danger btn-md btn-success'>

                </div>
            </form>
        </div>
<!-- <div class='content table-responsive'> -->
<?php 
if(isset($_GET['rekap_semua'])){
    include("./proses/rekap_semua.php");
}
elseif(isset($_GET['rekap_cashflow'])){
    include("./proses/rekap_cashflow.php");
}
?>
<!-- </div> -->
<script>
    function printPageArea(areaID){
    var printContent = document.getElementById(areaID);
    var WinPrint = window.open('', '', 'width=900,height=650');
    WinPrint.document.write(printContent.innerHTML);
    WinPrint.document.close();
    WinPrint.focus();
    WinPrint.print();
    WinPrint.close();
}
</script>