<div class='content table-responsive'>
    <h2 class='page-header'>FILE SOP </h2>
    <i>SOP ATAU PROSEDUR</i>
    <hr />
    <!-- Button to Open the Modal -->


    <?php 
    if(isset($_SESSION['login_baru'])){
        $dir = "RAHASIA/";
        if(isset($_GET['file'])){
            $filename  = $_GET['file'];
            ?>
             <a href='<?=$url.$menu?>file_folder' class='btn btn-danger'>TUTUP PDF</a>
           <div style="height:1000px">
           <iframe src = "assets/ViewerJS/#../../<?=$dir.$filename?>" width='100%' height='100%' allowfullscreen webkitallowfullscreen></iframe>
           </div>
           <br/>
           <br/>

            <?php
        }
        ?>
        <table id='data_center'>
            <thead>
                <tr>
                    <th>NO</th>
                    <th>NAMA FILE</th>
                    <th>#</th>
                </tr>
            </thead>
            <tbody>

                <?php
                

                // Open a directory, and read its contents
                if (is_dir($dir)) {
                    if ($dh = opendir($dir)) {
                        while (($file = readdir($dh)) !== false) {
                            if (trim($file) == '..' || trim($file) == '.') {
                            } else {


                ?>

                    <tr>
                        <td><?= $no++; ?></td>
                        <td>
                            <a href="<?=$url.$menu."file_folder&file=$file"?>"><?=$file?></a> 
                                
                        </td>

                        <td>
                        <a class='btn btn-success' target="_blank" href="<?=$url?>assets/ViewerJS/#../../<?=$file?>" ><i class="fa fa-eye"></i></a>
                        <a class='btn btn-info'  href="<?=$url.$dir?><?=$file?>" ><i class="fa fa-download"></i></a>

                        </td>
                    </tr>
                <?php
                            }
                        }
                        closedir($dh);
                    }
                }
                
                ?>
            </tbody>
        </table>
        <?php
    }
    else{
                    
        ?>
        <h1>Untuk menjaga privasi silahkan masukan password  <br/>
    </h1>
        <div class='col-md-6'>

            <form action="" method="post" autocomplete="off">
                <table class='table'>
                    <tr>
                        <td>PASSWORD</td>
                        <td><input type="password" autocomplete="false"  class='form-control' name='password_file'/></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td><input type="submit"  class='btn btn-danger' name='login_file' value='MASUK'/></td>
                    </tr>
                </table>
                
            </form>
        </div>
            <?php
            if(isset($_POST['login_file'])){
                $passlama = detail_karyawan($con,$id_karyawan)['password'];
                $passbaru = md5($_POST['password_file']);
                if($passlama ==  $passbaru)
                {
                    $_SESSION['login_baru']=$id_karyawan;
                    pindah("$url$menu"."file_folder");
                }
                else{
                    echo "Password tidak sama";
                }

            }
    }
    ?>

    

    
</div>
<!-- Button trigger modal -->