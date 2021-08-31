<div class='content table-responsive'>
	<h2 class='page-header'>KUISIONER</h2>

    <?php 
    @$act = $_GET['act'];
    if($act=='tambah-soal'){
        include("proses/kuis/soal.php");

    }
    else{
        include("proses/kuis/list.php");
    }
    ?>
	
</div>
<!-- Button trigger modal -->
