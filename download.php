<?php
// Cek apakah file tersedia
if(isset($_GET['file'])){
    $file = $_GET['file'];

    // Tentukan path file
    $filePath = './upload/surat_masuk/' . basename($file);

    // Pastikan file ada
    if(file_exists($filePath)){
        // Hapus buffer output
        ob_clean();
        flush();

        // Set headers agar browser memulai download
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($filePath) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($filePath));

        // Kirim file ke browser
        readfile($filePath);
        exit;
    } else {
        echo "File tidak ditemukan.";
    }
} else {
    echo "Tidak ada file yang dipilih.";
}
?>
