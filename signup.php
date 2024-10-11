<?php
ob_start();
session_start();

require_once 'include/config.php'; // Menghubungkan ke database
require_once 'include/functions.php'; // Menghubungkan fungsi tambahan
$config = conn($host, $username, $password, $database);

if (isset($_POST['register'])) {
    // Mengambil data inputan dan mengamankannya
    $username = trim(htmlspecialchars(mysqli_real_escape_string($config, $_POST['username'])));
    $password = trim(htmlspecialchars(mysqli_real_escape_string($config, $_POST['password'])));
    $nama = trim(htmlspecialchars(mysqli_real_escape_string($config, $_POST['nama'])));
    $nip = trim(htmlspecialchars(mysqli_real_escape_string($config, $_POST['nip'])));
    $admin = (int)$_POST['admin']; // Menyimpan jenis admin

    // Cek apakah username sudah digunakan
    $query_check_username = mysqli_query($config, "SELECT username FROM tbl_user WHERE username='$username'");
    if (mysqli_num_rows($query_check_username) > 0) {
        $_SESSION['errSignUp'] = '<center>Username sudah digunakan, pilih username lain.</center>';
    } else {
        // Cek apakah NIP sudah digunakan
        $query_check_nip = mysqli_query($config, "SELECT nip FROM tbl_user WHERE nip='$nip'");
        if (mysqli_num_rows($query_check_nip) > 0) {
            $_SESSION['errSignUp'] = '<center>NIP sudah digunakan, pilih NIP lain.</center>';
        } else {
            // Masukkan data pengguna baru ke database
            $query = mysqli_query($config, "INSERT INTO tbl_user (username, password, nama, nip, admin) VALUES ('$username', MD5('$password'), '$nama', '$nip', '$admin')");

            if ($query) {
                $_SESSION['successSignUp'] = '<center>Akun berhasil dibuat, silakan login.</center>';
                header("Location: admin.php"); // Arahkan ke halaman login setelah berhasil registrasi
                die();
            } else {
                $_SESSION['errSignUp'] = '<center>Pendaftaran gagal, coba lagi.</center>';
            }
        }
    }
}
?>

<!doctype html>
<html lang="en">

<head>
    <title>Sign Up - Aplikasi Manajemen Surat</title>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />

    <!-- Materialize CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <!-- jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <!-- Materialize JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
</head>

<body class="blue-grey lighten-3 bg">
    <div class="container">
        <div class="row">
            <div class="col s12 m6 offset-m3">
                <div class="card-panel z-depth-2" id="register">
                    <div class="row">
                        <h5 class="center">Sign Up</h5>

                        <!-- Error Message -->
                        <?php if (isset($_SESSION['errSignUp'])) : ?>
                            <div id="alert-message" class="error red lighten-5">
                                <div class="center">
                                    <i class="material-icons">error_outline</i>
                                    <strong>ERROR!</strong>
                                </div>
                                <?= $_SESSION['errSignUp']; ?>
                            </div>
                            <?php unset($_SESSION['errSignUp']); ?>
                        <?php endif; ?>

                        <!-- Success Message -->
                        <?php if (isset($_SESSION['successSignUp'])) : ?>
                            <div id="alert-message" class="success green lighten-5">
                                <div class="center">
                                    <i class="material-icons">check_circle</i>
                                    <strong>SUCCESS!</strong>
                                </div>
                                <?= $_SESSION['successSignUp']; ?>
                            </div>
                            <?php unset($_SESSION['successSignUp']); ?>
                        <?php endif; ?>

                        <!-- Registration Form -->
                        <form class="col s12" method="POST" action="">
                            <div class="input-field col s12">
                                <i class="material-icons prefix">person</i>
                                <input id="username" type="text" name="username" class="validate" required>
                                <label for="username">Username</label>
                            </div>
                            <div class="input-field col s12">
                                <i class="material-icons prefix">lock</i>
                                <input id="password" type="password" name="password" class="validate" required>
                                <label for="password">Password</label>
                            </div>
                            <div class="input-field col s12">
                                <i class="material-icons prefix">account_circle</i>
                                <input id="nama" type="text" name="nama" class="validate" required>
                                <label for="nama">Nama Lengkap</label>
                            </div>
                            <div class="input-field col s12">
                                <i class="material-icons prefix">badge</i>
                                <input id="nip" type="text" name="nip" class="validate" required>
                                <label for="nip">NIP</label>
                            </div>
                            <div class="input-field col s12">
                                <select name="admin" id="admin" required>
                                    <option value="" disabled selected>Pilih Jenis Admin</option>
                                    <option value="1">Super Admin</option>
                                    <option value="2">Administrator</option>
                                    <option value="0">User</option>
                                </select>
                                <label for="admin">Jenis Admin</label>
                            </div>

                            <div class="input-field col s12">
                                <button type="submit" class="btn-large waves-effect waves-light blue-grey col s12" name="register">Daftar</button>
                            </div>
                        </form>
                        <!-- End Registration Form -->

                        <div class="input-field col s12">
                            <p class="center">Sudah punya akun? <a href="./">Login di sini</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Javascript -->
    <script>
        $(document).ready(function() {
            $('select').formSelect(); // Inisialisasi select untuk Materialize CSS
        });
    </script>

</body>

</html>
