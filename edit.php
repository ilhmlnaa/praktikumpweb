<?php
include 'koneksi.php';

$npm = $_GET['npm']; // Menggunakan npm sebagai parameter
$query = "SELECT * FROM mahasiswa WHERE npm = $1";
$result = pg_query_params($koneksi, $query, array($npm));
$data = pg_fetch_assoc($result);

if (isset($_POST['submit'])) {
    // Collect form data
    $nama = $_POST['nama'];
    $kelas = $_POST['kelas'];
    $no_hp = $_POST['no_hp'];
    $alamat = $_POST['alamat'];
    $status = $_POST['status'];

    // Handle file upload
    $picture = $_FILES['picture']['name'];
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($picture);

    // If a new picture is uploaded, update the file
    if ($picture) {
        if (move_uploaded_file($_FILES['picture']['tmp_name'], $target_file)) {
            $update_query = "UPDATE mahasiswa SET nama = $1, kelas = $2, no_hp = $3, alamat = $4, status = $5, picture = $6 WHERE npm = $7";
            $params = array($nama, $kelas, $no_hp, $alamat, $status, $picture, $npm);
        } else {
            echo "Gagal mengupload gambar.";
            exit;
        }
    } else {
        // If no new picture is uploaded, update without changing the picture
        $update_query = "UPDATE mahasiswa SET nama = $1, kelas = $2, no_hp = $3, alamat = $4, status = $5 WHERE npm = $6";
        $params = array($nama, $kelas, $no_hp, $alamat, $status, $npm);
    }

    if (pg_query_params($koneksi, $update_query, $params)) {
        header('Location: index.php');
    } else {
        echo "Gagal mengedit data: " . pg_last_error($koneksi);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Mahasiswa</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

    <div class="container mx-auto p-6 max-w-4xl">
        <h1 class="text-3xl font-semibold text-center mb-6">Edit Mahasiswa</h1>
        
        <form method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <!-- Kolom Kiri -->
            <div class="mb-4">
                <label for="npm" class="block text-sm font-medium text-gray-700">NPM</label>
                <input type="text" id="npm" name="npm" class="mt-1 p-2 w-full border border-gray-300 rounded-md" value="<?= htmlspecialchars($data['npm']); ?>" readonly>
            </div>

            <div class="mb-4">
                <label for="nama" class="block text-sm font-medium text-gray-700">Nama</label>
                <input type="text" id="nama" name="nama" class="mt-1 p-2 w-full border border-gray-300 rounded-md" value="<?= htmlspecialchars($data['nama']); ?>" required>
            </div>

            <div class="mb-4">
                <label for="kelas" class="block text-sm font-medium text-gray-700">Kelas</label>
                <input type="text" id="kelas" name="kelas" class="mt-1 p-2 w-full border border-gray-300 rounded-md" value="<?= htmlspecialchars($data['kelas']); ?>" required>
            </div>

            <div class="mb-4">
                <label for="no_hp" class="block text-sm font-medium text-gray-700">No HP</label>
                <input type="text" id="no_hp" name="no_hp" class="mt-1 p-2 w-full border border-gray-300 rounded-md" value="<?= htmlspecialchars($data['no_hp']); ?>" required>
            </div>

            <!-- Kolom Kanan -->
            <div class="mb-4">
                <label for="alamat" class="block text-sm font-medium text-gray-700">Alamat</label>
                <textarea id="alamat" name="alamat" class="mt-1 p-2 w-full border border-gray-300 rounded-md" required><?= htmlspecialchars($data['alamat']); ?></textarea>
            </div>

            <div class="mb-4">
                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                <select name="status" id="status" class="mt-1 p-2 w-full border border-gray-300 rounded-md" required>
                    <option value="aktif" <?= $data['status'] == 'aktif' ? 'selected' : ''; ?>>Aktif</option>
                    <option value="tidak aktif" <?= $data['status'] == 'tidak aktif' ? 'selected' : ''; ?>>Tidak Aktif</option>
                </select>
            </div>

            <div class="mb-4">
                <label for="picture" class="block text-sm font-medium text-gray-700">Gambar</label>
                <input type="file" id="picture" name="picture" class="mt-1 p-2 w-full border border-gray-300 rounded-md" accept="image/*">
                <div class="mt-2">
                    <?php if ($data['picture']) : ?>
                        <img src="uploads/<?= htmlspecialchars($data['picture']); ?>" alt="Current Picture" class="w-24 h-24 object-cover">
                    <?php else : ?>
                        <p>No image uploaded</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end space-x-2 col-span-2">
                <button type="submit" name="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">Simpan</button>
                <a href="index.php" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600">Kembali</a>
            </div>
        </form>
    </div>

</body>
</html>
