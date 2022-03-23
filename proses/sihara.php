<div  class='content table-responsive'>
<div class="row">
	<h3 class="page-header">ANALISA PAR DAN ANGGOTA</h3>
	<hr />
    <form method="post"  enctype="multipart/form-data">
        <div class="col-md-4">
            <label for="formFile" class="form-label">SILAHKAN PILIH FILE</label>
            <input class="form-control" type="file" name='file' accept=".xml" id="formFile">
            <input type="submit" value="Proses" class='btn btn-danger' name='preview'>
        </div>
    </form>
</div>



<?php

if(isset($_POST['preview'])){
    ?>
    <a href="#" onclick="buka('<?=$url?>popup/sihara.php')" class="btn btn-success">COBA</a>
    <table id="table_export" class="display nowrap" style="width:100%">
        <thead>
            <tr>
                <th>NO</th>
                <th>CENTER</th>
                <th>GROUP</th>
                <th>CLIENT ID</th>
                <th>NAME</th>
                <th>ACCOUNT NO</th>
                <th>OPEN DATE</th>
                <th>DEPOSITE STANDAR</th>
                <th>FIELD OFFICER</th>
                <th>PERUBAHAN</th>
                <th>PENUTUPAN</th>
            </tr>
        </thead>
        <tbody> 
            
    <?php
    $file = $_FILES['file']['tmp_name'];
    $path = $file;
    $xml=simplexml_load_file($path) or die("Error: Cannot create object");
    // $xml = new SimpleXMLElement($path,0,true);
    $title = $xml->Tablix2['Textbox48'];
   $xml = ($xml->Tablix2->Details1_Collection);
   $pecah = explode(" : ",$title);
   
   $tgl_awal = trim(explode(" ",$pecah[1])[0]);
   $tgl_to = trim($pecah[2]);
    //    echo $tgl_to;
    
    $_SESSION['tgl_awal']=$tgl_awal;
    $_SESSION['tgl_to']=$tgl_to;
foreach($xml->Details1 as $siraya){
    $tgl_open = explode("T",$siraya['ApplyDate1'])[0];
    
   
   ?>
   
   <tr>
                <td><?=$no++?></td>
                <td><?=sprintf("%03d",$siraya['CenterID'])?></td>
                <td><?=sprintf("%03d",$siraya['GroupID'])?></td>
                <td><?=$siraya['ClientID1']?></td>
                <td><?=$siraya['ClientName1']?></td>
                <td><?=$siraya['AccountNo1']?></td>
                <td><?=$tgl_open?></td>
                <td><?=angka(int_xml($siraya['SpecificDepositAmount1']))?></td>
                <td><?=$siraya['OfficerName']?></td>
                <td>PERUBAHAN</td>
                <td>PENUTUPAN</td>
            </tr>
           <?php
         } 
         $_SESSION['nama_file_sihara']= "$id_aryawan".$_FILES['file']['name'];
         move_uploaded_file($file,"file/exx/".$_SESSION['nama_file_sihara']);
         ?>
        </tbody>
        
    </table>
   <?php
}

?>
</div>

<script>
    $(document).ready(function() {
    $('#table_export').DataTable( {
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    } );
} );

    function printPageArea(areaID){
    var printContent = document.getElementById(areaID);
    var WinPrint = window.open('', '', 'width=900,height=650');
    WinPrint.document.write(printContent.innerHTML);
    WinPrint.document.close();
    WinPrint.focus();
    WinPrint.print();
    WinPrint.close();
}
function buka(url) {
    let l = "<?=$url?>";
  var myWindow = window.open(url, "DATA PAR", "width=1000,height=1000");
//   myWindow.document.write("<p>This is 'MsgWindow'. I am 200px wide and 100px tall!</p>");
}
</script>
