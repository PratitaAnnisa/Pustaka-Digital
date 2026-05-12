<?php
session_start();
include 'config/koneksi.php';
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit();
}

//menangkan nilai tambah buku baru dari form tambah buku
if (isset($_POST['submit'])) {
    $kode_buku = $_POST['kode_buku'];
    $judul_buku = $_POST['judul_buku'];
    $pengarang = $_POST['pengarang'];
    $kategori = $_POST['kategori'];
    $stok = $_POST['stok'];
    $status = ($stok > 5) ? 'Tersedia' : (($stok <= 5) ? 'Menipis' : 'Habis');

    //query untuk menambahkan buku baru ke database
    $query = "INSERT INTO koleksi (kode_buku, judul_buku, pengarang, kategori, stok, status) VALUES ('$kode_buku', '$judul_buku', '$pengarang', '$kategori', '$stok', '$status')";
    if (mysqli_query($koneksi, $query)) {
        header("Location: koleksi_buku.php");
        exit();
    } else {
        echo "Error: gagal menambahkan buku //" . mysqli_error($koneksi);
    }
}
?>