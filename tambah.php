<?php
session_start(); // Memulai session PHP agar bisa menggunakan variabel session

include "koneksi.php"; // Menyertakan file koneksi.php yang berisi kode untuk menghubungkan ke database

// Mengecek apakah form telah disubmit
if (isset($_POST['submit'])) {
    // Mengambil data dari form dan melakukan sanitasi input
    $nama = mysqli_real_escape_string($db, $_POST['nama_barang']);
    $harga = mysqli_real_escape_string($db, $_POST['harga_barang']);
    $stok = mysqli_real_escape_string($db, $_POST['stok_barang']);

    // Menyusun query SQL untuk memasukkan data barang ke tabel 'barang'
    $sql = "INSERT INTO barang (nama_barang, harga_barang, stok_barang) VALUES (?, ?, ?)";
    
    // Menyiapkan prepared statement
    if ($stmt = mysqli_prepare($db, $sql)) {
        // Mengikat parameter ke prepared statement
        mysqli_stmt_bind_param($stmt, "sss", $nama, $harga, $stok);

        // Menjalankan prepared statement
        if (mysqli_stmt_execute($stmt)) {
            // Jika berhasil, set pesan sukses dalam session dan arahkan ke halaman lihat.php
            $_SESSION['pesan'] = "Berhasil menambah barang";
            header("Location: lihat.php"); // Arahkan pengguna ke halaman lihat.php
            exit(); // Hentikan eksekusi script setelah redirect
        } else {
            // Jika gagal, tampilkan pesan error dan hentikan eksekusi script
            die("Gagal menambah barang: " . mysqli_error($db));
        }
        // Menutup prepared statement
        mysqli_stmt_close($stmt);
    } else {
        die("Gagal menyiapkan query: " . mysqli_error($db));
    }
}

// Menutup koneksi database
mysqli_close($db);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Tambah Barang</title>
</head>
<body>
    <h2>Form Tambah Barang</h2>
    <!-- Form untuk menambah barang -->
    <form action="tambah.php" method="post">
        <label for="nama_barang">Nama Barang:</label><br>
        <!-- Input untuk nama barang -->
        <input type="text" id="nama_barang" name="nama_barang" required><br><br>
        <label for="harga_barang">Harga Barang:</label><br>
        <!-- Input untuk harga barang -->
        <input type="number" id="harga_barang" name="harga_barang" required><br><br>
        <label for="stok_barang">Stok Barang:</label><br>
        <!-- Input untuk stok barang -->
        <input type="number" id="stok_barang" name="stok_barang" required><br><br>
        <!-- Tombol submit untuk mengirim data ke server -->
        <input type="submit" value="Tambah Barang" name="submit">
        <!-- Link untuk kembali ke halaman lihat.php -->
        <a href="lihat.php">Kembali</a>
    </form>
</body>
</html>
