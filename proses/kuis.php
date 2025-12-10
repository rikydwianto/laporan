<div class='content table-responsive'>
	<h2 class='page-header'>KUISIONER <br>
        <a href="<?=$url.$menu?>kuis" style="text-align:right" class="btn btn-success">KUISIONER</a>
        <a href="<?=$url.$menu?>kuis&act=kuis&tambah" style="text-align:right" class="btn btn-danger"> <fa class="fa fa-plus"></fa> KUISIONER</a>
        <a href="<?=$url.$menu?>kuis&act=bank-soal" style="text-align:right" class="btn btn-primary">BANK SOAL</a>
    </h2>

    <?php 
    @$act = $_GET['act'];
    if($act=='tambah-soal'){
        include("proses/kuis/soal.php");

    }
    elseif($act=='hasil'){
        include("proses/kuis/hasil_kuis.php");

    }elseif($act=='kuis'){
        include("proses/kuis/kuis.php");

    }
    elseif($act=='bank-soal'){
        include("proses/kuis/bank-soal.php");

    }
    else{
        include("proses/kuis/list.php");
    }
    ?>
	
</div>
<!-- Button trigger modal -->
