<?php
//cek session
if(empty($_SESSION['admin'])){
    $_SESSION['err'] = '<center>Anda harus login terlebih dahulu!</center>';
    header("Location: ./");
    die();
} else {

    if($_SESSION['admin'] != 1 AND $_SESSION['admin'] != 3 AND $_SESSION['admin'] != 2){
        echo '<script language="javascript">
                window.alert("ERROR! Anda tidak memiliki hak akses untuk membuka halaman ini");
                window.location.href="./logout.php";
              </script>';
    } else {

        if(isset($_REQUEST['id_surat'])){
            $id_surat = $_REQUEST['id_surat'];

            //script untuk menampilkan data yang akan dihapus
            $query = mysqli_query($config, "SELECT * FROM tbl_surat_keluar WHERE id_surat_keluar = '$id_surat'");
            $data = mysqli_fetch_array($query);

            if(mysqli_num_rows($query) > 0){
                ?>
                <div class="row">
                    <div class="col m12">
                        <div class="card">
                            <div class="card-content">
                                <h4>Yakin ingin menghapus data surat keluar?</h4>
                                <p>No. Surat: <?php echo $data['no_surat']; ?></p>
                                <p>Tanggal Surat: <?php echo indoDate($data['tanggal_surat']); ?></p>
                                <p>Pengirim: <?php echo $data['pengirim']; ?></p>
                                <p>Tujuan: <?php echo $data['tujuan']; ?></p>
                                <a href="?page=tsk&act=del&id_surat=<?php echo $id_surat; ?>&confirm=yes" class="btn waves-effect waves-light red">Hapus</a>
                                <a href="?page=tsk" class="btn waves-effect waves-light blue">Batal</a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
            } 

            if(isset($_REQUEST['confirm'])){
                if($_REQUEST['confirm'] == 'yes'){
                    //script untuk menghapus data
                    $query = mysqli_query($config, "DELETE FROM tbl_surat_keluar WHERE id_surat_keluar = '$id_surat'");

                    if($query){
                        $_SESSION['succDel'] = 'Data surat keluar berhasil dihapus!';
                        header("Location: ?page=tsk");
                    } else {
                        echo '<script language="javascript">
                                window.alert("Gagal menghapus data!");
                                window.location.href="?page=tsk";
                              </script>';
                    }
                }
            }
        } else {
            echo '<script language="javascript">
                    window.alert("Gagal menghapus data!");
                    window.location.href="?page=tsk";
                  </script>';
        }
    }
}
?>