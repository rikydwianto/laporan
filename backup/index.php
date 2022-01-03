<?php
include("../config/seting.php");
include("../config/koneksi.php");
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require '../vendor/autoload.php';
// Database configuration
$host = "localhost"; //host database
$username = $username; //user database
$password = $password; //password database
$database_name = $db_name; //nama database
 error_reporting(0);
// Get connection object and set the charset
$conn = $con;
$conn->set_charset("utf8");
 
 
// Get All Table Names From the Database
$tables = array();
$sql = "SHOW TABLES";
$result = mysqli_query($conn, $sql);
// echo  mysqli_error($conn);
$larang =['daftar_wilayah','daftar_nasabah','deliquency','bayar','rekap_bayar','rekap_center'];
while ($row = mysqli_fetch_row($result)) {
    if(in_array($row[0],$larang)){
        // echo"ada";
    }
    else{
        // echo"tidak ada";
        $tables[] = $row[0];
    }
    
    // exit;
}
// exit;
?>
<?php
$sqlScript = "";
foreach ($tables as $table) {
     
    // Prepare SQLscript for creating table structure
    $query = "SHOW CREATE TABLE $table";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_row($result);
//    echo  mysqli_error($conn);
     
    $sqlScript .= "\n\n" . $row[1] . ";\n\n";
     
    $query = "SELECT * FROM $table";
    $result = mysqli_query($conn, $query);
     
    $columnCount = mysqli_num_fields($result);
     
    // Prepare SQLscript for dumping data for each table
    for ($i = 0; $i < $columnCount; $i ++) {
        while ($row = mysqli_fetch_row($result)) {
            $sqlScript .= "INSERT INTO $table VALUES(";
            for ($j = 0; $j < $columnCount; $j ++) {
                $row[$j] = $row[$j];
                 
                if (isset($row[$j])) {
                    $sqlScript .= '"' . htmlspecialchars($row[$j]) . '"';
                } else {
                    $sqlScript .= '""';
                }
                if ($j < ($columnCount - 1)) {
                    $sqlScript .= ',';
                }
            }
            $sqlScript .= ");\n";
        }
    }
    $sqlScript .= "\n"; 
}
// echo $sqlScript;
?>
<?php
if(!empty($sqlScript))
{
    // Save the SQL script to a backup file
    $backup_file_name = $database_name . '_backup_' . time() . '.txt';
    $fileHandler = fopen($backup_file_name, 'w+');
    $number_of_lines = fwrite($fileHandler, $sqlScript);
    fclose($fileHandler); 
 
    // Download the SQL backup file to the browser
    // header('Content-Description: File Transfer');
    // header('Content-Type: application/octet-stream');
    // header('Content-Disposition: attachment; filename=' . basename($backup_file_name));
    // header('Content-Transfer-Encoding: binary');
    // header('Expires: 0');
    // header('Cache-Control: must-revalidate');
    // header('Pragma: public');
    // header('Content-Length: ' . filesize($backup_file_name));
    // ob_clean();
    // flush();
    // readfile($backup_file_name);
    // exec('rm ' . $backup_file_name); 
    
    
//Create an instance; passing 'true` enables exceptions
$mail = new PHPMailer(true);

try {
    //Server settings
    // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'rikydwianto04@gmail.com';                     //SMTP username
    $mail->Password   = 'bandosriky';                               //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
    $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom('rikydwianto04@gmail.com', 'RIKY');
    $mail->addAddress('rikydwianto04@gmail.com', 'RIKY BACKUP');     //Add a recipient
    // $mail->addAddress('ellen@example.com');               //Name is optional
    // $mail->addReplyTo('info@example.com', 'Information');
    $mail->addCC('rikydwianto08@gmail.com');
    // $mail->addBCC('bcc@example.com');

    //Attachments
    $file=__DIR__.'/'.$backup_file_name;

    $mail->addAttachment($file);         //Add attachments
    // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = 'BACKUP HARIAN '. date("Y-m-d");
    $mail->Body    = 'BACKUP HARIAN  '. date("Y-m-d")." <br/><br/><br/><br/><br/><br/><br/><br/> Terima kasih tela membackup";
    // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    $mail->send();
    echo 'Message has been sent';

    unlink($file);
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
}
?>