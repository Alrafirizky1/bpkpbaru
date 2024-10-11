<?php
// Cek session
if (empty($_SESSION['admin'])) {
    $_SESSION['err'] = '<center>Anda harus login terlebih dahulu!</center>';
    header("Location: ./");
    die();
}

if (isset($_REQUEST['submit'])) {
    // Validasi form kosong
    if (empty($_REQUEST['no_surat']) || empty($_REQUEST['pengirim']) || empty($_REQUEST['tujuan']) || empty($_REQUEST['tanggal_surat'])) {
        $_SESSION['errEmpty'] = 'ERROR! Semua form wajib diisi';
        echo '<script language="javascript">window.history.back();</script>';
        die();
    }

    // Ambil data dari form
    $id_surat_masuk = $_REQUEST['id_surat_masuk'];
    $no_surat = $_REQUEST['no_surat'];
    $pengirim = $_REQUEST['pengirim'];
    $tujuan = $_REQUEST['tujuan'];
    $tanggal_surat = $_REQUEST['tanggal_surat'];

    // Validasi input data
    if (!preg_match("/^[a-zA-Z0-9.\/ -]*$/", $no_surat)) {
        $_SESSION['no_surat'] = 'Form No Surat hanya boleh mengandung karakter huruf, angka, spasi, titik(.), minus(-) dan garis miring(/)';
        echo '<script language="javascript">window.history.back();</script>';
        die();
    }

    if (!preg_match("/^[a-zA-Z0-9.,_()%&@\/\r\n -]*$/", $tujuan)) {
        $_SESSION['tujuan'] = 'Form Tujuan hanya boleh mengandung karakter huruf, angka, spasi, titik(.), koma(,), minus(-), garis miring(/), kurung(), underscore(_), dan(&) persen(%) dan at(@)';
        echo '<script language="javascript">window.history.back();</script>';
        die();
    }

    $ekstensi = array('jpg', 'png', 'jpeg', 'doc', 'docx', 'pdf', 'xlsx');
    $file = $_FILES['file']['name'];
    $x = explode('.', $file);
    $eks = strtolower(end($x));
    $ukuran = $_FILES['file']['size'];
    $target_dir = "upload/surat_masuk/";

    // Membuat direktori jika belum ada
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0755, true);
    }

    // Jika form file tidak kosong
    if ($file != "") {
        $rand = rand(1, 10000);
        $file = $rand . "-" . $file;

        // Validasi file
        if (in_array($eks, $ekstensi) == true) {
            if ($ukuran < 2300000) { // Batasi ukuran file max 2.3MB
                // Ambil nama file lama
                $query = mysqli_query($config, "SELECT file FROM tbl_surat_masuk WHERE id_surat_masuk='$id_surat_masuk'");
                list($file_lama) = mysqli_fetch_array($query);

                // Hapus file lama jika ada
                if (!empty($file_lama)) {
                    unlink($target_dir . $file_lama);
                }

                // Pindahkan file ke direktori target
                if (move_uploaded_file($_FILES['file']['tmp_name'], $target_dir . $file)) {
                    // Update data di database beserta file
                    $query = mysqli_query($config, "UPDATE tbl_surat_masuk SET no_surat='$no_surat', pengirim='$pengirim', tujuan='$tujuan', tanggal_surat='$tanggal_surat', file='$file' WHERE id_surat_masuk='$id_surat_masuk'");

                    if ($query) {
                        $_SESSION['succEdit'] = 'SUKSES! Data berhasil diupdate';
                    } else {
                        $_SESSION['errQ'] = 'ERROR! Ada masalah dengan query: ' . mysqli_error($config);
                    }
                } else {
                    $_SESSION['errUpload'] = 'ERROR! Gagal memindahkan file.';
                }
            } else {
                $_SESSION['errSize'] = 'Ukuran file yang diupload terlalu besar!';
            }
        } else {
            $_SESSION['errFormat'] = 'Format file yang diperbolehkan hanya *.JPG, *.PNG, *.DOC, *.DOCX atau *.PDF!';
        }
    } else {
        // Jika form file kosong, update tanpa file
        $query = mysqli_query($config, "UPDATE tbl_surat_masuk SET no_surat='$no_surat', pengirim='$pengirim', tujuan='$tujuan', tanggal_surat='$tanggal_surat' WHERE id_surat_masuk='$id_surat_masuk'");

        if ($query) {
            $_SESSION['succEdit'] = 'SUKSES! Data berhasil diupdate';
        } else {
            $_SESSION['errQ'] = 'ERROR! Ada masalah dengan query: ' . mysqli_error($config);
        }
    }

    header("Location: ./admin.php?page=tsm");
    die();
} else {
    $id_surat_masuk = mysqli_real_escape_string($config, $_REQUEST['id_surat']);
    $query = mysqli_query($config, "SELECT id_surat_masuk, no_surat, tanggal_surat, pengirim, tujuan FROM tbl_surat_masuk WHERE id_surat_masuk='$id_surat_masuk'");
    list($id_surat_masuk, $no_surat, $tanggal_surat, $pengirim, $tujuan) = mysqli_fetch_array($query);
?>

    <!-- Row Start -->
    <div class="row">
        <!-- Secondary Nav START -->
        <div class="col s12">
            <nav class="secondary-nav">
                <div class="nav-wrapper blue-grey darken-1">
                    <ul class="left">
                        <li class="waves-effect waves-light"><a href="#" class="judul"><i class="material-icons">edit</i> Edit Data Surat Masuk</a></li>
                    </ul>
                </div>
            </nav>
        </div>
        <!-- Secondary Nav END -->
    </div>
    <!-- Row END -->

    <?php
    // Tampilkan pesan error jika ada
    if (isset($_SESSION['errQ'])) {
        echo '<div class="row"><div class="col m12"><div class="card red lighten-5"><div class="card-content notif"><span class="card-title red-text"><i class="material-icons md-36">clear</i>' . $_SESSION['errQ'] . '</span></div></div></div></div>';
        unset($_SESSION['errQ']);
    }
    if (isset($_SESSION['errEmpty'])) {
        echo '<div class="row"><div class="col m12"><div class="card red lighten-5"><div class="card-content notif"><span class="card-title red-text"><i class="material-icons md-36">clear</i>' . $_SESSION['errEmpty'] . '</span></div></div></div></div>';
        unset($_SESSION['errEmpty']);
    }
    if (isset($_SESSION['succEdit'])) {
        echo '<div class="row"><div class="col m12"><div class="card green lighten-5"><div class="card-content notif"><span class="card-title green-text"><i class="material-icons md-36">check</i>' . $_SESSION['succEdit'] . '</span></div></div></div></div>';
        unset($_SESSION['succEdit']);
    }
    ?>

    <!-- Form Start -->
    <div class="row jarak-form">
        <form class="col s12" method="POST" action="?page=tsm&act=edit" enctype="multipart/form-data">
            <input type="hidden" name="id_surat_masuk" value="<?php echo $id_surat_masuk; ?>">
            <div class="input-field col s6">
                <i class="material-icons prefix md-prefix">looks_two</i>
                <input id="no_surat" type="text" class="validate" name="no_surat" value="<?php echo $no_surat; ?>" required>
                <label for="no_surat">Nomor Surat</label>
            </div>
            <div class="input-field col s6">
                <i class="material-icons prefix md-prefix">place</i>
                <input id="tujuan" type="text" class="validate" name="tujuan" value="<?php echo $tujuan; ?>" required>
                <label for="tujuan">Tujuan Surat</label>
            </div>
            <div class="input-field col s6">
                <i class="material-icons prefix md-prefix">date_range</i>
                <input id="tanggal_surat" type="text" name="tanggal_surat" class="datepicker" value="<?php echo $tanggal_surat; ?>" required>
                <label for="tanggal_surat">Tanggal Surat</label>
            </div>
            <div class="input-field col s6">
                <i class="material-icons prefix md-prefix">people</i>
                <input id="pengirim" type="text" class="validate" name="pengirim" value="<?php echo $pengirim; ?>" required>
                <label for="pengirim">Pengirim</label>
            </div>

            <div class="input-field col s12">
                <div class="file-field input-field">
                    <div class="btn light-green darken-1">
                        <span>File</span>
                        <input type="file" name="file">
                    </div>
                    <div class="file-path-wrapper">
                        <input class="file-path validate" type="text" placeholder="Upload file/scan gambar surat masuk">
                    </div>
                </div>
            </div>
            <div class="input-field col s12">
                <button type="submit" class="btn-large deep-orange waves-effect waves-light" name="submit">SUBMIT <i class="material-icons right">send</i></button>
            </div>
        </form>
    </div>
    <!-- Form END -->

<?php
}
?>