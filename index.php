<?php
include 'koneksi.php';

// Proses pencarian berdasarkan NPM melalui parameter
$search = isset($_GET['search']) ? $_GET['search'] : '';
// Pengambilan semua data mahasiswa
$query = "SELECT * FROM mahasiswa";
// Jika ada parameter pencarian, tambahkan WHERE ke query
if ($search) {
    $query .= " WHERE CAST(npm AS TEXT) ILIKE '%$search%'"; // Menggunakan CAST untuk konversi ke teks
}

$result = pg_query($koneksi, $query); 
// Cek apakah ada hasil pencarian
$num_rows = pg_num_rows($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Data Mahasiswa</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print {
                display: none;
            }
            table {
                width: 100%;
                border-collapse: collapse;
                margin: 20px 0;
            }
            th, td {
                padding: 8px 12px;
                border: 1px solid #000;
                text-align: center;
            }
            th {
                background-color: #f3f4f6;
            }
            body {
                font-size: 14px;
            }

            @page {
                margin: 10mm;
            }
        }
    </style>
</head>
<body class="bg-gray-100 flex flex-col justify-between min-h-screen">

    <!-- Bagian Header -->
    <?php include 'header.php'; ?>

    <!-- Bagian konten utama -->
    <div class="container mx-auto p-6 flex-grow">
        <h1 class="text-3xl font-bold text-center mb-6 text-slate-500">Manajemen Data Mahasiswa</h1>

        <!-- Tombol Add Data, Print, dan Kembali -->
        <div class="mb-4 flex flex-wrap justify-start space-x-4 gap-4">
            <a href="tambah.php" class="bg-blue-400 p-3 rounded-lg text-white font-bold hover:bg-blue-500 w-full sm:w-auto text-center">Tambah Mahasiswa</a>
            <button onclick="printTable()" class="bg-green-500 text-white p-3 rounded-md hover:bg-green-600 w-full sm:w-auto text-center">Print Data</button>
            <?php if ($search): ?>
                <a href="index.php" class="bg-gray-500 text-white p-3 rounded-md hover:bg-gray-600 w-full sm:w-auto text-center">Kembali</a>
            <?php endif; ?>
        </div>

        <!-- Tabel Data Mahasiswa -->
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-300 rounded-lg">
                <thead class="bg-gray-800 text-white">
                    <tr>
                        <th class="py-2 px-4 border-b text-center">No</th>
                        <th class="py-2 px-4 border-b text-center no-print">Picture</th>
                        <th class="py-2 px-4 border-b text-center">Nama</th>
                        <th class="py-2 px-4 border-b text-center">NPM</th>
                        <th class="py-2 px-4 border-b text-center">Kelas</th>
                        <th class="py-2 px-4 border-b text-center">No HP</th>
                        <th class="py-2 px-4 border-b text-center">Alamat</th>
                        <th class="py-2 px-4 border-b text-center">Status</th>
                        <th class="py-2 px-4 border-b text-center no-print">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($num_rows > 0): ?>
                        <?php $no = 1; ?>
                        <?php while ($row = pg_fetch_assoc($result)) : ?>
                            <tr class="border-b">
                                <td class="py-2 px-4 text-center"><?= $no++; ?></td>
                                <td class="py-2 px-4 text-center no-print">
                                    <img src="uploads/<?= htmlspecialchars($row['picture']); ?>" alt="Foto" class="w-12 h-12 rounded-full mx-auto">
                                </td>
                                <td class="py-2 px-4 text-center"><?= htmlspecialchars($row['nama']); ?></td>
                                <td class="py-2 px-4 text-center"><?= htmlspecialchars($row['npm']); ?></td>
                                <td class="py-2 px-4 text-center"><?= htmlspecialchars($row['kelas']); ?></td>
                                <td class="py-2 px-4 text-center"><?= htmlspecialchars($row['no_hp']); ?></td>
                                <td class="py-2 px-4 text-center"><?= htmlspecialchars($row['alamat']); ?></td>
                                <td class="py-2 px-4 text-center">
                                    <?php if (strtolower($row['status']) === 'aktif'): ?>
                                        <span class="text-green-500">Mahasiswa Aktif</span>
                                    <?php else: ?>
                                        <span class="text-red-500">Mahasiswa Tidak Aktif</span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-2 px-4 text-center no-print flex gap-2">
                                    <a href="edit.php?npm=<?= $row['npm']; ?>" class="bg-blue-400 text-white p-2 rounded-md hover:bg-blue-600">Edit</a>
                                    <a href="hapus.php?npm=<?= $row['npm']; ?>" class="bg-red-400 text-white p-2 rounded-md hover:bg-red-600" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="9" class="py-2 px-4 text-center text-red-500">
                                <?php if ($search): ?>
                                    Mahasiswa dengan NPM <?= htmlspecialchars($search); ?> tidak ditemukan.
                                <?php else: ?>
                                    Data belum ditambahkan.
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Bagian Footer -->
    <?php include 'footer.php'; ?>

    <script>
        function printTable() {
            var table = document.querySelector("table").cloneNode(true);
            var cellsToHide = table.querySelectorAll(".no-print");
            cellsToHide.forEach(function(cell) {
                cell.style.display = 'none';
            });
            var printWindow = window.open('', '', 'height=600,width=800');
            printWindow.document.write('<html><head><title>Print Table</title><style>');
            printWindow.document.write('table {width: 100%; border-collapse: collapse; margin: 20px 0;}');
            printWindow.document.write('th, td {padding: 8px 12px; border: 1px solid #000; text-align: center;}');
            printWindow.document.write('th {background-color: #f3f4f6;}');
            printWindow.document.write('</style></head><body>');
            printWindow.document.write(table.outerHTML);
            printWindow.document.write('</body></html>');
            printWindow.document.close();
            printWindow.print();
        }
    </script>

</body>
</html>

