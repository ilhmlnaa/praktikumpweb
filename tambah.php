<?php
include 'koneksi.php';

if (isset($_POST['submit'])) {
    $npm = $_POST['npm'];
    $nama = $_POST['nama'];
    $kelas = $_POST['kelas'];
    $no_hp = $_POST['no_hp'];
    $alamat = $_POST['alamat'];
    $status = $_POST['status'];
    $picture = $_FILES['picture']['name'];
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($picture);

    // Upload file gambar jika ada
    if (move_uploaded_file($_FILES['picture']['tmp_name'], $target_file)) {
        $query = "INSERT INTO mahasiswa (npm, nama, kelas, no_hp, alamat, picture, status) 
                  VALUES ('$npm', '$nama', '$kelas', '$no_hp', '$alamat', '$picture', '$status')";
        
        if (pg_query($koneksi, $query)) {
            header('Location: index.php');
        } else {
            echo "Gagal menambah data: " . pg_last_error($koneksi);
        }
    } else {
        echo "Gagal mengupload gambar.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Mahasiswa</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

    <div class="container mx-auto p-6 max-w-4xl">
        <h1 class="text-3xl font-semibold text-center mb-6">Tambah Mahasiswa</h1>
        
        <form method="POST" enctype="multipart/form-data" class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <!-- Kolom Kiri -->
            <div class="mb-4">
                <label for="npm" class="block text-sm font-medium text-gray-700">NPM</label>
                <input type="number" class="mt-1 p-2 w-full border border-gray-300 rounded-md" id="npm" name="npm" required>
            </div>
            <div class="mb-4">
                <label for="nama" class="block text-sm font-medium text-gray-700">Nama</label>
                <input type="text" class="mt-1 p-2 w-full border border-gray-300 rounded-md" id="nama" name="nama" required>
            </div>
            <div class="mb-4">
                <label for="kelas" class="block text-sm font-medium text-gray-700">Kelas</label>
                <input type="text" class="mt-1 p-2 w-full border border-gray-300 rounded-md" id="kelas" name="kelas" required>
            </div>
            <div class="mb-4">
                <label for="no_hp" class="block text-sm font-medium text-gray-700">No. HP</label>
                <input type="number" class="mt-1 p-2 w-full border border-gray-300 rounded-md" id="no_hp" name="no_hp" required>
            </div>

            <!-- Kolom Kanan -->
            <div class="mb-4">
                <label for="alamat" class="block text-sm font-medium text-gray-700">Alamat</label>
                <textarea class="mt-1 p-2 w-full border border-gray-300 rounded-md" id="alamat" name="alamat" required></textarea>
            </div>
            <div class="mb-4">
                <label for="picture" class="block text-sm font-medium text-gray-700">Gambar</label>
                <input type="file" class="mt-1 p-2 w-full border border-gray-300 rounded-md" id="picture" name="picture" accept="image/*">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Status</label>
                <div class="flex items-center space-x-4">
                    <label for="status_active" class="flex items-center space-x-2">
                        <input type="radio" id="status_active" name="status" value="aktif" class="border-gray-300 text-blue-500" required>
                        <span>Aktif</span>
                    </label>
                    <label for="status_inactive" class="flex items-center space-x-2">
                        <input type="radio" id="status_inactive" name="status" value="tidak aktif" class="border-gray-300 text-blue-500" required>
                        <span>Tidak Aktif</span>
                    </label>
                </div>
            </div>

            <div class="flex justify-end space-x-2 col-span-2">
                <button type="submit" name="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">Simpan</button>
                <a href="index.php" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600">Kembali</a>
            </div>
        </form>
    </div>

</body>
</html>
