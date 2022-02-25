<div class='content table-responsive'>
	<h2 class='page-header'>FILE PDF MDISMO</h2>
    
	<form method="post"  enctype="multipart/form-data">
        <div class="col-md-4">
            <label for="formFile" class="form-label">SILAHKAN PILIH FILE</label>
            <input class="form-control" type="file" name='file[]' accept=".zip" multiple id="formFile">
            <input type="submit" value="Proses" class='btn btn-danger' name='preview'>
        </div>
    </form>
	<?php
	if(isset($_POST['preview'])){
		ini_set('upload_max_filesize', '64M');
		ini_set('post_max_size', '64M');
		ini_set('max_input_time', 300);
ini_set('max_execution_time', 300);



		$file = $_FILES['file'];
		$hitung = count($file);
		$count = 0 ;
		foreach ($_FILES['file']['name'] as $filename) 
		{
			// $temp=$target;
			$folder = "file/";
			$nama_zip = $folder.$filename;
			// echo $folder;
			$tmp=$_FILES['file']['tmp_name'][$count];
			$count=$count + 1;
			move_uploaded_file($tmp,$folder.$filename);
			$path = pathinfo(realpath($nama_zip), PATHINFO_DIRNAME).'/'."$singkatan_cabang";
			// echo $path;
			$zip = new ZipArchive;
			$res = $zip->open($nama_zip);
			if ($res === TRUE) {
			// extract it to the path we determined above
			$zip->extractTo($path);
			$zip->close();
			// echo var_dump($zip->extractTo($path));
			pindah($url.$menu."rapih_file&folder=".urlencode($path));
			} else {
			echo "Doh! I couldn't open $file";
			}
			unlink($nama_zip);
			// rmdir($path);

		
			
		}
	}
	if(isset($_GET['folder'])){
		$folder = urldecode($_GET['folder']);
		$dir = $folder;

		if (is_dir($dir)) {
			if ($dh = opendir($dir)) {
				while (($file = readdir($dh)) !== false) {
					if (trim($file) == '..' || trim($file) == '.') {
					} else {

						$pecah =  explode("_",$file);
						$nik = $pecah[2].'/'.$pecah[3];
						$tgl = str_replace(".pdf","", $pecah[4]);
						$kode_file = $pecah[0];
						$kode = $pecah[1];
						$cek = mysqli_query($con,"select * from file_mdis where nama_file='$file' and tanggal='$tgl' and id_cabang='$id_cabang'");
						if(mysqli_num_rows($cek)){
							// echo "ada";
						}
						else{
							mysqli_query($con,"INSERT INTO file_mdis 
							(id_cabang,nama_file,status,nik,tanggal,kode_cabang,kode_file) VALUES
							($id_cabang,'$file','pending','$nik','$tgl','$kode','$kode_file')
							");
							echo mysqli_error($con);
						}
		
					}
				}
				closedir($dh);
			}
		}

		pindah($url.$menu."rapih_file&proses");
		
	}

	if(isset($_GET['proses'])){
		$tgl_rowq= mysqli_query($con,"SELECT distinct tanggal from file_mdis where id_cabang='$id_cabang' and status='pending' order by tanggal desc");
		while($tgl_row = mysqli_fetch_array($tgl_rowq)){
			$pathdir = "$tgl_row[tanggal]/"; 
			$dir = ($pathdir);

			$folder_baru = "file/$singkatan_cabang/$tgl_row[tanggal]";
				mkdir($folder_baru);

				
			$cek =  mysqli_query($con,"select distinct nik from file_mdis where id_cabang='$id_cabang' and tanggal='$tgl_row[tanggal]' and status='pending' order by nik asc ");
			while($cek_nik = mysqli_fetch_array($cek)){
				$nik =  str_replace("/",'_', $cek_nik['nik']);
				$cek_nik1 = mysqli_query($con,"select * from karyawan k join cabang c on k.id_cabang=c.id_cabang where nik_karyawan='$cek_nik[nik]' ");
				$cek_nik1 = mysqli_fetch_array($cek_nik1);
				$nama_kar =  $cek_nik1['nama_karyawan'];
				if($nama_kar==null){
					$nama_kar = $nik;
				}
				else{
					$nama_kar = $nama_kar;
				}
				

				$cek_file = mysqli_query($con,"SELECT * from file_mdis where id_cabang='$id_cabang' and tanggal='$tgl_row[tanggal]' and nik='$cek_nik[nik]'");
				// echo "SELECT * from file_mdis where id_cabang='$id_cabang' and tanggal='$tgl_row[tanggal]' and nik='$cek_nik[nik]'";
				$folder_nama = $folder_baru.'/'.$nama_kar;
				mkdir($folder_nama);
				while($files = mysqli_fetch_array($cek_file)){
					$file  = "file/$singkatan_cabang/". $files['nama_file'];
					$nama_file =  $files['nama_file'];
					$new_file = $folder_nama.'/'.$nama_file;
					copy($file,$new_file);
					rename($new_file,$folder_nama.'/'.$tipe_file . ' - ' . $nama_kar.'.pdf');
					unlink($file);
					$tipe_file = $files['kode_file'];
					$path_file = $pathdir.$nama_kar.'/'.$tipe_file.' - '. $nama_kar.'.pdf';
					// $zip -> addFile($new_file, $tipe_file.'- '.$nama_kar.'.pdf');
					// $zip->addFromString($path_file, 'Contoh file txt zip2'.$nik);

				}

			}
			$folder_baru = "file/$singkatan_cabang/";
			$rootPath = realpath($folder_baru);

			// Initialize archive object
			$zip = new ZipArchive();
			$zip->open('file/'.$singkatan_cabang.'-arsip.zip', ZipArchive::CREATE | ZipArchive::OVERWRITE);

			// Create recursive directory iterator
			/** @var SplFileInfo[] $files */
			$files = new RecursiveIteratorIterator(
				new RecursiveDirectoryIterator($rootPath),
				RecursiveIteratorIterator::LEAVES_ONLY
			);

			foreach ($files as $name => $file)
			{
				// Skip directories (they would be added automatically)
				if (!$file->isDir())
				{
					// Get real and relative path for current file
					$filePath = $file->getRealPath();
					$relativePath = substr($filePath, strlen($rootPath) + 1);

					// Add current file to archive
					$zip->addFile($filePath, $relativePath);
				}
			}

			// Zip archive will be created only after closing object
			$zip->close();

			
			// $dir = $folder_baru;
			// $it = new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS);
			// $files = new RecursiveIteratorIterator($it,
			// 			RecursiveIteratorIterator::CHILD_FIRST);
			// foreach($files as $file) {
			// 	if ($file->isDir()){
			// 		rmdir($file->getRealPath());
			// 	} else {
			// 		unlink($file->getRealPath());
			// 	}
			// }
			// rmdir($dir);

					

		}
		// header("location:".p");
		// copy('file/'.$singkatan_cabang.'-arsip.zip','file/download'.$singkatan_cabang.'-arsip.zip');
		pindah("$url/file/$singkatan_cabang-arsip.zip");
	}
	 ?>
</div>