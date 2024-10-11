<?php
// cek session
if (empty($_SESSION['admin'])) {
    $_SESSION['err'] = '<center>Anda harus login terlebih dahulu!</center>';
    header("Location: ./");
    die();
} else {

    if ($_SESSION['admin'] != 1 and $_SESSION['admin'] != 3 and $_SESSION['admin'] != 2) {
        echo '<script language="javascript">
                window.alert("ERROR! Anda tidak memiliki hak akses untuk membuka halaman ini");
                window.location.href="./logout.php";
              </script>';
    } else {

        if (isset($_REQUEST['act'])) {
            $act = $_REQUEST['act'];
            switch ($act) {
                case 'add':
                    include "tambah_surat_masuk.php";
                    break;
                case 'edit':
                    include "edit_surat_masuk.php";
                    break;
                    break;
                case 'print':
                    include "cetak_disposisi.php";
                    break;
                case 'del':
                    include "hapus_surat_masuk.php";
                    break;
                case 'download':
                    $id_surat = $_REQUEST['id_surat'];
                    $query = mysqli_query($config, "SELECT * FROM tbl_surat_masuk WHERE id_surat_masuk = '$id_surat'");
                    $row = mysqli_fetch_array($query);
                    $file = $row['file'];
                    $path = '/upload/surat_masuk/' . $file; // ganti dengan path yang sesuai

                    if (file_exists($path)) {
                        header('Content-Type: application/octet-stream');
                        header('Content-Disposition: attachment; filename="' . $file . '"');
                        header('Content-Length: ' . filesize($path));
                        readfile($path);
                        exit;
                    } else {
                        echo 'File tidak ditemukan';
                    }
                    break;
                    case 'terkirim':
                        $id_surat = $_REQUEST['id_surat'];
                        $query = mysqli_query($config, "SELECT * FROM tbl_surat_masuk WHERE id_surat_masuk = '$id_surat'");
                        $row = mysqli_fetch_array($query);
                    
                        // Insert data into tbl_surat_keluar
                        $query_keluar = mysqli_query($config, "INSERT INTO tbl_surat_keluar (id_surat_keluar, no_surat, tanggal_surat, pengirim, tujuan, file) VALUES ('', '$row[no_surat]', '$row[tanggal_surat]', '$row[pengirim]', '$row[tujuan]', '$row[file]')");
                    
                        // Check if the data is successfully saved to tbl_surat_keluar
                        if ($query_keluar) {
                            echo '<div class="alert alert-success alert-dismissible fade show text-center" role="alert" style="border-radius: 10px; padding: 20px; font-size: 18px;">
                                    <i class="material-icons md-48" style="color: #4caf50;">check_circle</i><br/>
                                    <strong>Sukses!</strong> Surat telah berhasil terkirim.<br/>
                                    Anda akan diarahkan kembali ke halaman sebelumnya dalam 3 detik atau klik tombol <strong>OK</strong> di bawah untuk kembali segera.<br/>
                                    <button onclick="window.history.back();" class="btn btn-success mt-3">OK</button>
                                  </div>';
                    
                            // JavaScript for auto-back after 3 seconds
                            echo '<script>
                                    setTimeout(function() {
                                        window.history.back();
                                    }, 3000); // Auto-back after 3 seconds
                                  </script>';
                        } else {
                            echo '<div class="alert alert-danger alert-dismissible fade show text-center" role="alert" style="border-radius: 10px; padding: 20px; font-size: 18px;">
                                    <i class="material-icons md-48" style="color: #f44336;">error_outline</i><br/>
                                    <strong>Gagal!</strong> Terjadi kesalahan, surat gagal terkirim.<br/>
                                    Anda akan diarahkan kembali ke halaman sebelumnya dalam 3 detik atau klik tombol <strong>OK</strong> di bawah untuk kembali segera.<br/>
                                    <button onclick="window.history.back();" class="btn btn-danger mt-3">OK</button>
                                  </div>';
                    
                            // JavaScript for auto-back after 3 seconds
                            echo '<script>
                                    setTimeout(function() {
                                        window.history.back();
                                    }, 3000); // Auto-back after 3 seconds
                                  </script>';
                        }
                        break;
                    
                    
            }
        } else {

            $limit = 10; // Set limit to 10 data per page
            $pg = @$_GET['pg'];
            if (empty($pg)) {
                $curr = 0;
                $pg = 1;
            } else {
                $curr = ($pg - 1) * $limit;
            } ?>

            <!-- Row Start -->
            <div class="row">
                <!-- Secondary Nav START -->
                <div class="col s12">
                    <div class="z-depth-1">
                        <nav class="secondary-nav">
                            <div class="nav-wrapper blue-grey darken-1">
                                <div class="col m7">
                                    <ul class="left">
                                        <li class="waves-effect waves-light hide-on-small-only"><a href="?page=tsm" class="judul"><i class="material-icons"> mail</i> Surat Masuk</a></li>
                                        <li class="waves-effect waves-light">
                                            <a href="?page=tsm&act=add"><i class="material-icons md-24">add_circle</i> Tambah Data</a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="col m5 hide-on-med-and-down">
                                    <form method="post" action="?page=tsm">
                                        <div class="input-field round-in-box">
                                            <input id="search" type="search" name="cari" placeholder="Ketik dan tekan enter mencari data..." required>
                                            <label for="search"><i class="material-icons md-dark">search</i></label>
                                            <input type="submit" name="submit" class="hidden">
                                        </div>
                                    </form>
 </div>
                            </div>
                        </nav>
                    </div>
                </div>
                <!-- Secondary Nav END -->
            </div>
            <!-- Row END -->

            <?php
            if (isset($_SESSION['succAdd'])) {
                $succAdd = $_SESSION['succAdd'];
                echo '<div id="alert-message" class="row">
                        <div class="col m12">
                            <div class="card green lighten-5">
                                <div class="card-content notif">
                                    <span class="card-title green-text"><i class="material-icons md-36">done</i> ' . $succAdd . '</span>
                                </div>
                            </div>
                        </div>
                    </div>';
                unset($_SESSION['succAdd']);
            }
            if (isset($_SESSION['succEdit'])) {
                $succEdit = $_SESSION['succEdit'];
                echo '<div id="alert-message" class="row">
                        <div class="col m12">
                            <div class="card green lighten-5">
                                <div class="card-content notif">
                                    <span class="card-title green-text"><i class="material-icons md-36">done</i> ' . $succEdit . '</span>
                                </div>
                            </div>
                        </div>
                    </div>';
                unset($_SESSION['succEdit']);
            }
            if (isset($_SESSION['succDel'])) {
                $succDel = $_SESSION['succDel'];
                echo '<div id="alert-message" class="row">
                        <div class="col m12">
                            <div class="card green lighten-5">
                                <div class="card-content notif">
                                    <span class="card-title green-text"><i class="material-icons md-36">done</i> ' . $succDel . '</span>
                                </div>
                            </div>
                        </div>
                    </div>';
                unset($_SESSION['succDel']);
            }
            ?>

            <!-- Row form Start -->
            <div class="row jarak-form">

                <?php
                if (isset($_REQUEST['submit'])) {
                    $cari = mysqli_real_escape_string($config, $_REQUEST['cari']);
                    echo '
                <div class="col s12" style="margin-top: -18px;">
                    <div class="card blue lighten-5">
                        <div class="card-content">
                        <p class="description">Hasil pencarian untuk kata kunci <strong>"' . stripslashes($cari) . '"</strong><span class="right"><a href="?page=tsm"><i class="material-icons md-36" style="color: #333;">clear</i></a></span></p>
                        </div>
                    </div>
                </div>

                <div class="col m12" id="colres">
                    <table class="bordered" id="tbl">
                        <thead class="blue lighten-4" id="head">
                            <tr>
                                <th width="10%">No. Surat<br/></th>
                                <th width="30%">Tanggal Masuk<br/></th>
                                <th width="24%">Asal Surat</th>
                                <th width="18%">Tujuan Surat<br/></th>
                                <th width="18%">File<br/></th>
                                <th width="18%">Tindakan <span class="right"><i class="material-icons" style="color: #333;">settings</i></span></th>
                            </tr>
                        </thead>
                        <tbody>';

                    // script untuk mencari data
                    $query = mysqli_query($config, "SELECT * FROM tbl_surat_masuk 
                    WHERE no_surat LIKE '%$cari%' 
                    OR pengirim LIKE '%$cari%' 
                    OR tujuan LIKE '%$cari%'
                    ORDER BY pengirim DESC 
                    LIMIT 10");

                    if (mysqli_num_rows($query) > 0) {
                        while ($row = mysqli_fetch_array($query)) {
                            echo '
                        <tr >
                            <td>' . $row['no_surat'] . '</td>
                            <td>' . $row['tanggal_surat'] . '</td>
                            <td>' . $row['pengirim'] . '</td>
                            <td>' . $row['tujuan'] . '</td>
                            <td>';
                            if (!empty($row['file'])) {
                                echo '<a href="?page=gsm&act=fsm&id_surat=' . $row['id_surat_masuk'] . '">' . $row['file'] . '</a>';
                                echo '<a href="?page=gsm&act=download&id_surat=' . $row['id_surat_masuk'] . '" class="btn small blue waves-effect waves-light">
                                <i class="material-icons">file_download</i> DOWNLOAD
                            </a>';
                            } else {
                                echo '<em>Tidak ada file yang di upload</em>';
                            }
                            echo '</td>
                            <td>
                                <a class="btn small blue waves-effect waves-light" href="?page=tsm&act=edit&id_surat=' . $row['id_surat_masuk'] . '">
                                    <i class="material-icons">edit</i> EDIT</a>
                                <a class="btn small yellow darken-3 waves-effect waves-light" href="?page=tsm&act=terkirim&id_surat=' . $row['id_surat_masuk'] . '">
                                    <i class="material-icons">send</i> TERKIRIM
                                </a>
                                <a class="btn small deep-orange waves-effect waves-light" href="?page=tsm&act=del&id_surat=' . $row['id_surat_masuk'] . '">
                                    <i class="material-icons">delete</i> DEL</a>
                            </td>
                        </tr>';
                        }
                    } else {
                        echo '<tr><td colspan="6"><center><p class="add">Tidak ada data yang ditemukan</p></center></td></tr>';
                    }
                    echo '</tbody></table><br/><br/>
                    </div>
                </div>
                <!-- Row form END -->';
                } else {
                    echo '
                <div class="col m12" id="colres">
                    <table class="bordered" id="tbl">
                        <thead class="blue lighten-4" id="head">
                            <tr>
                                <th width="10%">No. Surat<br/></th>
                                <th width="30%">Tanggal Masuk<br/></th>
                                <th width="24%">Asal Surat</th>
                                <th width                                width="18%">Tujuan Surat<br/></th>
                                <th width="18%">File<br/></th>
                                <th width="18%">Tindakan <span class="right"><i class="material-icons" style="color: #333;">settings</i></span></th>
                            </tr>
                        </thead>
                        <tbody>';

                    // Menampilkan data surat masuk dari database
                    $query = mysqli_query($config, "SELECT * FROM tbl_surat_masuk ORDER BY id_surat_masuk DESC LIMIT $curr, $limit");

                    // Cek apakah query berhasil dan ada hasilnya
                    if ($query && mysqli_num_rows($query) > 0) {
                        while ($row = mysqli_fetch_array($query)) {
                            echo '
        <tr>
            <td>' . $row['no_surat'] . '</td>
            <td>' . $row['tanggal_surat'] . '</td>
            <td>' . $row['pengirim'] . '</td>
            <td>' . $row['tujuan'] . '</td>
            <td>';

                            // Cek apakah file ada sebelum menampilkannya
                            if (!empty($row['file'])) {
                                $file_path = './upload/surat_masuk/' . $row['file'];
                                echo '<a href="' . $file_path . '" target="_blank">' . $row['file'] . '</a>';
                                echo '<a href="' . $file_path . '" class="btn small blue waves-effect waves-light" download>
                    <i class="material-icons">file_download</i> DOWNLOAD
                  </a>';
                } else {
                    echo '<em>Tidak ada file yang diupload</em>';
                }
                
                echo '</td>                
            <td>
                <a class="btn small blue waves-effect waves-light" href="?page=tsm&act=edit&id_surat=' . $row['id_surat_masuk'] . '">
                    <i class="material-icons">edit</i> EDIT</a>
                <a class="btn small yellow darken-3 waves-effect waves-light" href="?page=tsm&act=terkirim&id_surat=' . $row['id_surat_masuk'] . '">
                    <i class="material-icons">send</i> TERKIRIM
                </a>
                <a class="btn small deep-orange waves-effect waves-light" href="?page=tsm&act=del&id_surat=' . $row['id_surat_masuk'] . '">
                    <i class="material-icons">delete</i> DEL</a>
            </td>
        </tr>';
                        }
                    } else {
                        echo '<tr><td colspan="6"><center><p class="add">Tidak ada data yang tersedia</p></center></td></tr>';
                    }
                    echo '</tbody></table><br/><br/>
                    </div>';

                    // pagination
                    $query = mysqli_query($config, "SELECT * FROM tbl_surat_masuk");
                    $cdata = mysqli_num_rows($query);
                    $cpg = ceil($cdata / $limit);

                    echo '<!-- Pagination START -->
                      <ul class="pagination">';

                    if ($cdata > $limit) {
                        if ($pg > 1) {
                            $prev = $pg - 1;
                            echo '<li><a href ="?page=tsm&pg=1"><i class="material-icons md-48">first_page</i></a></li>
                              <li><a href="?page=tsm&pg=' . $prev . '"><i class="material-icons md-48">chevron_left</i></a></li>';
                        } else {
                            echo '<li class="disabled"><a href="#"><i class="material-icons md-48">first_page</i></a></li>
                              <li class="disabled"><a href="#"><i class="material-icons md-48">chevron_left</i></a></li>';
                        }

                        for ($i = 1; $i <= $cpg; $i++) {
                            if ($i != $pg) {
                                echo '<li class="waves-effect waves-dark"><a href="?page=tsm&pg=' . $i . '"> ' . $i . ' </a></li>';
                            } else {
                                echo '<li class="active waves-effect waves-dark"><a href="?page=tsm&pg=' . $i . '"> ' . $i . ' </a></li>';
                            }
                        }

                        if ($pg < $cpg) {
                            $next = $pg + 1;
                            echo '<li><a href="?page=tsm&pg=' . $next . '"><i class="material-icons md-48">chevron_right</i></a></li>
                              <li><a href="?page=tsm&pg=' . $cpg . '"><i class="material-icons md-48">last_page</i></a></li>';
                        } else {
                            echo '<li class="disabled"><a href="#"><i class="material-icons md-48">chevron_right</i></a></li>
                              <li class="disabled"><a href="#"><i class="material-icons md-48">last_page</i></a></li>';
                        }
                        echo '
                      </ul>
                      <!-- Pagination END -->';
                    } else {
                        echo '';
                    }
                }
                ?>
            </div>
            <!-- Row form END -->
<?php
        }
    }
}
?>

