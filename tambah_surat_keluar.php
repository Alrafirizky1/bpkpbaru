<?php
// Cek session
if (empty($_SESSION['admin'])) {
    $_SESSION['err'] = '<center>Anda harus login terlebih dahulu!</center>';
    header("Location: ./");
    die();
} else {
    if (isset($_REQUEST['submit'])) {

        // Validasi form kosong
        if (
            empty($_REQUEST['no_surat']) || empty($_REQUEST['tgl_surat']) ||
            empty($_REQUEST['pengirim']) || empty($_REQUEST['tujuan'])
        ) {
            $_SESSION['errEmpty'] = 'ERROR! Semua form wajib diisi';
            echo '<script language="javascript">window.history.back();</script>';
        } else {

            $no_surat = $_REQUEST['no_surat'];
            $tgl_surat = $_REQUEST['tgl_surat'];
            $pengirim = $_REQUEST['pengirim'];
            $tujuan = $_REQUEST['tujuan'];
            $id_user = $_SESSION['id_user'];

            // Validasi input
            if (!preg_match("/^[a-zA-Z0-9.\/ -]*$/", $no_surat)) {
                $_SESSION['no_surat'] = 'Form No Surat hanya boleh mengandung huruf, angka, spasi, titik(.), minus(-), dan garis miring(/)';
                echo '<script>window.history.back();</script>';
                exit;
            }

            if (!preg_match("/^[a-zA-Z0-9.,()\/ -]*$/", $pengirim)) {
                $_SESSION['pengirim'] = 'Form Pengirim hanya boleh mengandung huruf, angka, spasi, titik(.), koma(,), minus(-), kurung(), dan garis miring(/)';
                echo '<script>window.history.back();</script>';
                exit;
            }

            if (!preg_match("/^[a-zA-Z0-9.,\/ -]*$/", $tujuan)) {
                $_SESSION['tujuan'] = 'Form Tujuan hanya boleh mengandung huruf, angka, spasi, titik(.), koma(,), minus(-), dan garis miring(/)';
                echo '<script>window.history.back();</script>';
                exit;
            }

            if (!preg_match("/^[0-9.-]*$/", $tgl_surat)) {
                $_SESSION['tgl_surat'] = 'Form Tanggal Surat hanya boleh mengandung angka dan minus(-)';
                echo '<script>window.history.back();</script>';
                exit;
            }

            $ekstensi = array('jpg', 'png', 'jpeg', 'doc', 'docx', 'pdf', 'xlsx', 'html');
            $file = $_FILES['file']['name'];
            $x = explode('.', $file);
            $eks = strtolower(end($x));
            $ukuran = $_FILES['file']['size'];
            $target_dir = "upload/surat_keluar/";

            // Cek jika ada file yang diupload
            if (!empty($file)) {
                // Cek ekstensi file
                if (in_array($eks, $ekstensi) === true) {
                    // Cek ukuran file
                    if ($ukuran < 2097152) { // 2MB
                        // Pindahkan file ke folder tujuan
                        if (move_uploaded_file($_FILES['file']['tmp_name'], $target_dir . $file)) {
                            // Insert data dengan file
                            $query = "INSERT INTO tbl_surat_keluar ( no_surat, tanggal_surat, pengirim, tujuan, file)
                                      VALUES ('$no_surat', '$tgl_surat', '$pengirim', '$tujuan', '$file')";
                            if (mysqli_query($config, $query)) {
                                $_SESSION['succAdd'] = 'SUKSES! Data berhasil ditambahkan';
                                header("Location: ./admin.php?page=tsk");
                                exit;
                            } else {
                                $_SESSION['errQ'] = 'ERROR! Ada masalah dengan query';
                                echo '<script>window.history.back();</script>';
                            }
                        } else {
                            $_SESSION['uploadErr'] = 'ERROR! Gagal mengunggah file';
                            echo '<script>window.history.back();</script>';
                        }
                    } else {
                        $_SESSION['fileSizeErr'] = 'ERROR! Ukuran file terlalu besar';
                        echo '<script>window.history.back();</script>';
                    }
                } else {
                    $_SESSION['fileTypeErr'] = 'ERROR! Ekstensi file tidak diizinkan';
                    echo '<script>window.history.back();</script>';
                }
            } else {
                // Insert data tanpa file
                $query = "INSERT INTO tbl_surat_keluar (no_surat, tanggal_surat, pengirim, tujuan)
                          VALUES ('$no_surat', '$tgl_surat', '$pengirim', '$tujuan')";

                if (mysqli_query($config, $query)) {
                    $_SESSION['succAdd'] = 'SUKSES! Data berhasil ditambahkan';
                    header("Location: ./admin.php?page=tsk");
                    exit;
                } else {
                    $_SESSION['errQ'] = 'ERROR! Ada masalah dengan query';
                    echo '<script>window.history.back();</script>';
                }
            }
        }
    }
}
?>

<!-- Tampilan Form HTML -->
<div class="row">
    <div class="col s12">
        <nav class="secondary-nav">
            <div class="nav-wrapper blue-grey darken-1">
                <ul class="left">
                    <li class="waves-effect waves-light"><a href="?page=tsk&act=add" class="judul"><i class="material-icons">mail</i> Tambah Data Surat Keluar</a></li>
                </ul>
            </div>
        </nav>
    </div>
</div>

<!-- Notifikasi Error -->
<?php
if (isset($_SESSION['errQ'])) {
    echo "<div class='card red lighten-5'><span class='red-text'>{$_SESSION['errQ']}</span></div>";
    unset($_SESSION['errQ']);
}
if (isset($_SESSION['errEmpty'])) {
    echo "<div class='card red lighten-5'><span class='red-text'>{$_SESSION['errEmpty']}</span></div>";
    unset($_SESSION['errEmpty']);
}
if (isset($_SESSION['fileSizeErr'])) {
    echo "<div class='card red lighten-5'><span class='red-text'>{$_SESSION['fileSizeErr']}</span></div>";
    unset($_SESSION['fileSizeErr']);
}
if (isset($_SESSION['fileTypeErr'])) {
    echo "<div class='card red lighten-5'><span class='red-text'>{$_SESSION['fileTypeErr']}</span></div>";
    unset($_SESSION['fileTypeErr']);
}
if (isset($_SESSION['uploadErr'])) {
    echo "<div class='card red lighten-5'><span class='red-text'>{$_SESSION['uploadErr']}</span></div>";
    unset($_SESSION['uploadErr']);
}
?>

<!-- Form Input Surat -->
<div class="row jarak-form">
    <form class="col s12" method="POST" action="?page=tsk&act=add" enctype="multipart/form-data">
        <!-- Input Fields -->
        <div class="row">
            <div class="input-field col s12">
                <input id="no_surat" type="text" name="no_surat" required>
                <label for="no_surat">No Surat</label>
            </div>
        </div>

        <div class="row">
            <div class="input-field col s12">
                <input id="tgl_surat" type="text" name="tgl_surat" required>
                <label for="tgl_surat">Tanggal Surat</label>
            </div>
        </div>

        <div class="row">
            <div class="input-field col s12">
                <input id="pengirim" type="text" name="pengirim" required>
                <label for="pengirim">Pengirim</label>
            </div>
        </div>

        <div class="row">
            <div class="input-field col s12">
                <input id="tujuan" type="text" name="tujuan" required>
                <label for="tujuan">Tujuan</label>
            </div>
        </div>

        <div class="row">
            <div class="file-field input-field col s12">
                <div class="btn light-green darken-1">
                    <span>File</span>
                    <input type="file" id="file" name="file">
                </div>
                <div class="file-path-wrapper">
                    <input class="file-path validate" type="text" placeholder="Upload file/scan gambar surat keluar">
                </div>
            </div>
        </div>

        <!-- Perbaiki tata letak tombol menggunakan Flexbox -->
        <div class="row" style="display: flex; justify-content: space-between;">
            <div class="col s6">
                <button type="submit" name="submit" class="btn-large blue waves-effect waves-light">
                    SIMPAN <i class="material-icons">done</i>
                </button>
            </div>
            <div class="col s6">
                <a href="?page=tsk" class="btn-large deep-orange waves-effect waves-light">
                    BATAL <i class="material-icons">clear</i>
                </a>
            </div>
        </div>
    </form>
</div>