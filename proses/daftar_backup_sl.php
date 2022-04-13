<div class='content table-responsive'>
    <h2 class='page-header'>DAFTAR ANGGOTA BACKUP/PIHAK KETIGA</h2>

    <form>
    <?php 
if(isset($_GET['ganti_status'])){
    $id_nasabah = $_GET['id'];
    $backup = $_GET['backup'];
    mysqli_query($con,"update daftar_nasabah set backup='$backup' where id_cabang='$id_cabang' and id_detail_nasabah='$id_nasabah' ");
}
else{
    ?>
    
        <table class='table table-bordered'> 
            <thead>
                <tr>
                    <th>NO</th>
                    <th>CENTER</th>
                    <th>KELOMPOK</th>
                    <th>CLIENTID</th>
                    <th>NAMA</th>
                    <th>HARI</th>
                    <th>STATUS BACKUP</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $qkar = mysqli_query($con,"select * from daftar_nasabah where id_cabang='$id_cabang' and id_karyawan='$id_karyawan' order by hari desc, no_center");
                while($rkar = mysqli_fetch_array($qkar)){
                    ?>
                    <tr>
                        <td><?=$no?></td>
                        <td><?=$rkar['no_center']?></td>
                        <td><?=$rkar['kelompok']?></td>
                        <td><?=$rkar['id_detail_nasabah']?></td>
                        <td><?=$rkar['nama_nasabah']?></td>
                        <td><?=$rkar['hari']?></td>
                        <td>
                            <?php 
                            if($rkar['backup']=='ya'){
                                ?>
                                    <a  onclick="status_backup('<?=$rkar['id_detail_nasabah']?>','ya','<?=$no?>')" id='status-<?=$no?>' class="btn btn-danger">dibackup</a>    
                                <?php
                            }
                            else{
                                ?>
                                    <a  onclick="status_backup('<?=$rkar['id_detail_nasabah']?>','tidak','<?=$no?>')" id='status-<?=$no?>'  class="btn btn-primary">tidak</a>    
                                <?php

                            }
                            ?>
                        </td>
                    </tr>
                    <?php
                    $no++;
                }
                ?>
            </tbody>
        </table>
    </form>
    <?php
}
?>
</div>



<script>
    function status_backup(id,backup,no){
        // e.prefentDefault();
        let lokasi = "<?=$url.$menu?>daftar_backup_sl&ganti_status&id=";
        // alert(lokasi)
        let status = $("#status-" + no);
        $(document).ready(function(){
            
            if(backup=='ya'){

               
                $.get(lokasi+id+"&backup=tidak",function(){
                    status.addClass("btn-primary").removeClass("btn-danger");
                    status.html("tidak")
                });
            }
            else{
                $.get(lokasi+id+"&backup=ya",function(){
                    status.html("dibackup")
                     status.addClass("btn-danger").removeClass("btn-primary");
                });
               

            }
        })

    }
</script>