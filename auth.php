<?php
require_once "config/seting.php";
require_once "config/koneksi.php";
require_once("proses/fungsi.php");
require_once("model/model.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta http-equiv="Referrer-Policy" content="no-referrer, strict-origin-when-cross-origin">

    <meta name="author" content="">

    <title>Login - KOPERASI MITRA DHUAFA</title>
    <link rel="icon" type="image/png" sizes="16x16" href="<?= $url ?>assets/logo-motif.png">
    <!-- Bootstrap Core CSS -->
    <link href="<?= $url ?>assets/css/bootstrap.min.css" rel="stylesheet">
    <!-- MetisMenu CSS -->
    <link href="<?= $url ?>assets/css/metisMenu.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="<?= $url ?>assets/css/startmin.css" rel="stylesheet">
    <!-- Custom Fonts -->
    <link href="<?= $url ?>assets/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: #f5f7fa;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        }

        .container {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        .login-container {
            width: 100%;
            max-width: 600px;
            padding: 20px;
        }

        .login-panel {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            border: 1px solid #e8e8e8;
            overflow: hidden;
        }

        .panel-heading {
            background: white !important;
            padding: 40px 30px 20px;
            border: none;
            text-align: center;
        }

        .panel-heading h3 {
            color: #1a1a1a;
            font-size: 24px;
            font-weight: 600;
            margin: 15px 0 5px;
        }

        .logo-container img {
            width: 60px;
            height: 60px;
            border-radius: 12px;
        }

        .welcome-text {
            color: #666;
            font-size: 14px;
            margin-bottom: 0;
        }

        .panel-body {
            padding: 20px 30px 40px;
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-control {
            height: 48px;
            border-radius: 8px;
            border: 1px solid #e0e0e0;
            padding: 0 16px;
            font-size: 15px;
            transition: all 0.2s;
            background: #fafafa;
        }

        .form-control:focus {
            border-color: #4285f4;
            box-shadow: 0 0 0 3px rgba(66, 133, 244, 0.1);
            background: white;
            outline: none;
        }

        .form-control::placeholder {
            color: #999;
        }

        .form-control.error {
            border-color: #f44336;
            background: #fff5f5;
        }

        .error-message {
            color: #f44336;
            font-size: 13px;
            margin-top: 6px;
            display: none;
        }

        .error-message.show {
            display: block;
        }

        .btn-login {
            height: 48px;
            border-radius: 8px;
            background: #4285f4;
            border: none;
            font-size: 15px;
            font-weight: 500;
            color: white;
            transition: all 0.2s;
            margin-top: 8px;
        }

        .btn-login:hover {
            background: #3367d6;
            box-shadow: 0 1px 3px rgba(0,0,0,0.12);
        }

        .btn-login:active {
            transform: scale(0.98);
        }

        .btn-login:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .btn-login.loading {
            position: relative;
            color: transparent;
        }

        .btn-login.loading::after {
            content: "";
            position: absolute;
            width: 18px;
            height: 18px;
            top: 50%;
            left: 50%;
            margin-left: -9px;
            margin-top: -9px;
            border: 2px solid #ffffff;
            border-radius: 50%;
            border-top-color: transparent;
            animation: spinner 0.6s linear infinite;
        }

        @keyframes spinner {
            to { transform: rotate(360deg); }
        }

        .footer-text {
            text-align: center;
            margin-top: 30px;
            color: #999;
            font-size: 13px;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="login-container">
                    <div class="login-panel panel panel-default">
                        <div class="panel-heading">
                            <div class="logo-container">
                                <img src="<?php echo $url ?>assets/logo-motif.png" alt="Logo">
                            </div>
                            <h3>--- &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; New Report &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ---</h3>
                            <p class="welcome-text">Silakan login untuk melanjutkan</p>
                        </div>
                        <div class="panel-body">
                            <form role="form" method="post" id="loginForm">
                                <fieldset>
                                    <div class="form-group">
                                        <input type="text" name="username" id="username" class="form-control" placeholder="Username" autofocus>
                                        <div class="error-message" id="username-error">Username tidak boleh kosong</div>
                                    </div>
                                    <div class="form-group">
                                        <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                                        <div class="error-message" id="password-error">Password tidak boleh kosong</div>
                                    </div>
                                    <button type="submit" id="btnLogin" class="btn btn-primary btn-block btn-login">Login</button>
                                </fieldset>
                            </form>
                            <div class="footer-text">
                                © <?= date('Y') ?> Koperasi Mitra Dhuafa
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="<?php echo $url ?>assets/js/jquery.min.js"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="<?php echo $url ?>assets/js/bootstrap.min.js"></script>
    <!-- Metis Menu Plugin JavaScript -->
    <script src="<?php echo $url ?>assets/js/metisMenu.min.js"></script>
    <!-- Custom Theme JavaScript -->
    <script src="<?php echo $url ?>assets/js/startmin.js"></script>
    <!-- SweetAlert2 JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Form validation and AJAX submission
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const username = document.getElementById('username');
            const password = document.getElementById('password');
            const usernameError = document.getElementById('username-error');
            const passwordError = document.getElementById('password-error');
            const btnLogin = document.getElementById('btnLogin');
            
            let isValid = true;
            
            // Reset errors
            username.classList.remove('error');
            password.classList.remove('error');
            usernameError.classList.remove('show');
            passwordError.classList.remove('show');
            
            // Validate username
            if (username.value.trim() === '') {
                username.classList.add('error');
                usernameError.classList.add('show');
                isValid = false;
            }
            
            // Validate password
            if (password.value.trim() === '') {
                password.classList.add('error');
                passwordError.classList.add('show');
                isValid = false;
            }
            
            // If validation fails
            if (!isValid) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Perhatian',
                    text: 'Mohon lengkapi semua field yang diperlukan'
                });
                return;
            }
            
            // Show loading state
            btnLogin.disabled = true;
            btnLogin.classList.add('loading');
            
            // Prepare form data
            const formData = new FormData();
            formData.append('username', username.value.trim());
            formData.append('password', password.value.trim());
            
            // Send AJAX request
            fetch('<?= $url ?>api/process_login.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                // Remove loading state
                btnLogin.disabled = false;
                btnLogin.classList.remove('loading');
                
                if (data.success) {
                    // Success
                    Swal.fire({
                        icon: 'success',
                        title: 'Login Berhasil',
                        text: data.message,
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        window.location.href = data.data.redirect_url;
                    });
                } else {
                    // Error
                    const iconType = data.message.includes('tidak aktif') ? 'warning' : 'error';
                    const titleText = data.message.includes('tidak aktif') ? 'Akun Tidak Aktif' : 'Login Gagal';
                    
                    Swal.fire({
                        icon: iconType,
                        title: titleText,
                        text: data.message
                    });
                }
            })
            .catch(error => {
                // Remove loading state
                btnLogin.disabled = false;
                btnLogin.classList.remove('loading');
                
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Terjadi Kesalahan',
                    text: 'Tidak dapat terhubung ke server. Silahkan coba lagi.'
                });
            });
        });
        
        // Remove error on input
        document.getElementById('username').addEventListener('input', function() {
            this.classList.remove('error');
            document.getElementById('username-error').classList.remove('show');
        });
        
        document.getElementById('password').addEventListener('input', function() {
            this.classList.remove('error');
            document.getElementById('password-error').classList.remove('show');
        });
        
        // Allow Enter key on password field
        document.getElementById('password').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                document.getElementById('loginForm').dispatchEvent(new Event('submit'));
            }
        });
    </script>

</body>

</html>