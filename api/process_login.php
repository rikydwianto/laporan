<?php
header('Content-Type: application/json');
require_once "../config/seting.php";
require_once "../config/koneksi.php";
require_once "../proses/fungsi.php";
require_once "../model/model.php";

// Response array
$response = [
    'success' => false,
    'message' => '',
    'data' => null
];

// Check if request is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $response['message'] = 'Invalid request method';
    echo json_encode($response);
    exit;
}

// Validate input
if (empty($_POST['username']) || empty($_POST['password'])) {
    $response['message'] = 'Username dan password tidak boleh kosong';
    echo json_encode($response);
    exit;
}

try {
    $user = ($_POST['username']);
    $pass = trim(( md5($_POST['password'])));
    
    $q = mysqli_query($con, "SELECT * FROM karyawan WHERE nik_karyawan='$user'");
    
    if (mysqli_num_rows($q)) {
        //debugging
        
        $cek = mysqli_fetch_array($q);
       
        // Check if account is active
        if ($cek['status_karyawan'] !== 'aktif') {
            $response['message'] = 'Akun Anda tidak aktif. Silahkan hubungi atasan Anda';
            $text = "@user $user tidak aktif mencoba login";
            
            // Send telegram notification
            $url_telegram = "https://api.telegram.org/$token/sendMessage?parse_mode=html&chat_id=1185334687&text=$text&reply_message_id=214&force_reply=true";
            @file_get_contents($url_telegram);
            
            echo json_encode($response);
            exit;
        }
        // Validate password
        if ($cek['password'] !== $pass) {
            $response['message'] = 'Username atau password salah';
            $text = "$user percobaan login password salah";
            
            // Send telegram notification
            $url_telegram = "https://api.telegram.org/$token/sendMessage?parse_mode=html&chat_id=1185334687&text=$text&reply_message_id=214&force_reply=true";
            @file_get_contents($url_telegram);
            
            echo json_encode($response);
            exit;
        }
        
        // Login successful - Set session
        $_SESSION['id'] = $cek['id_karyawan'];
        $_SESSION['nama_karyawan'] = $cek['nama_karyawan'];
        $_SESSION['id_cabang'] = $cek['id_cabang'];
        $_SESSION['cabang'] = $cek['id_cabang'];
        $_SESSION['su'] = $cek['super_user'];
        $_SESSION['informasi_login'] = 'ya';
        
        $id_karyawan = trim($cek['id_karyawan']);
        // Get employee details
        $d = detail_karyawan($con, $id_karyawan);
        if(!$d){
            $response['message'] = 'Data karyawan tidak ditemukan. Silahkan hubungi atasan Anda';
            echo json_encode($response);
            exit;
        }
        // Send telegram notification
        $text = "login @user : $user {$cek['nama_karyawan']} cabang : {$d['nama_cabang']}";
        $url_telegram = "https://api.telegram.org/$token/sendMessage?parse_mode=html&chat_id=1185334687&text=$text&reply_message_id=214&force_reply=true";
        @file_get_contents($url_telegram);
        
        // Success response
        $response['success'] = true;
        $response['message'] = 'Login berhasil! Selamat datang, ' . $cek['nama_karyawan'];
        $response['data'] = [
            'id_karyawan' => $cek['id_karyawan'],
            'nama_karyawan' => $cek['nama_karyawan'],
            'id_cabang' => $cek['id_cabang'],
            'nama_cabang' => $d['nama_cabang'],
            'super_user' => $cek['super_user'],
            'redirect_url' => $url
        ];
        
        echo json_encode($response);
        exit;
        
    } else {
        // User not found
        $response['message'] = 'Username atau password salah';
        $text = "Percobaan login @user $user tidak ditemukan";
        
        // Send telegram notification
        $url_telegram = "https://api.telegram.org/$token/sendMessage?parse_mode=html&chat_id=1185334687&text=$text&reply_message_id=214&force_reply=true";
        @file_get_contents($url_telegram);
        
        echo json_encode($response);
        exit;
    }
    
} catch (Exception $e) {
    $response['message'] = 'Terjadi kesalahan sistem. Silahkan coba lagi';
    $response['data'] = ['error' => $e->getMessage()];
    echo json_encode($response);
    exit;
}
