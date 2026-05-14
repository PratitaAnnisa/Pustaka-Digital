<?php
session_start();
include 'config/koneksi.php';
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Hapus data buku dari database
    $query = "DELETE FROM koleksi WHERE id = '$id'";
    $result = mysqli_query($koneksi, $query);

    if ($result) {
        // Jika penghapusan berhasil, arahkan kembali ke halaman daftar buku
        echo "Buku berhasil dihapus.";
        header("Location: koleksi_buku.php");
        exit();
    }
} else {
    echo "Error: " . mysqli_error($koneksi);
}