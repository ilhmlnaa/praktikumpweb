<?php
include 'koneksi.php';

// Proses pencarian berdasarkan NPM
$search = isset($_GET['search']) ? $_GET['search'] : '';
$query = "SELECT * FROM mahasiswa";
if ($search) {
    $query .= " WHERE CAST(npm AS TEXT) ILIKE '%$search%'"; // Gunakan CAST untuk konversi ke teks
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    
    <style>
        /* CSS untuk menyembunyikan kolom picture dan actions saat mencetak */
        @media print {
            .no-print {
                display: none;
            }

            /* Table styles for printing */
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

            /* Optional: Make font bigger in print view */
            body {
                font-size: 14px;
            }

            /* Optional: Ensure no extra margins or spaces in the print view */
            @page {
                margin: 10mm;
            }
        }
    </style>
</head>
<body class="bg-gray-100 flex flex-col justify-between min-h-screen">

    <!-- Header -->
    <?php include 'header.php'; ?>

    <!-- Main Content -->
    <div class="container mx-auto p-6 flex-grow">
        <h1 class="text-3xl font-bold text-center mb-6 text-slate-500">Manajemen Data Mahasiswa</h1>

        <!-- Tombol Add Data, Print, dan Kembali -->
        <div class="mb-4 flex justify-start space-x-4">
            <a href="tambah.php" class="bg-blue-400 p-3 rounded-lg  text-white font-bold hover:bg-blue-500">Tambah Mahasiswa</a>
            
            <!-- Tombol Print -->
            <button onclick="printTable()" class="bg-green-500 text-white p-3  rounded-md hover:bg-green-600">Print Data</button>

            <!-- Tombol Kembali setelah pencarian -->
            <?php if ($search): ?>
                <a href="index.php" class="bg-gray-500 text-white p-3 rounded-md hover:bg-gray-600">Kembali</a>
            <?php endif; ?>
        </div>

        <!-- Form pencarian -->
        <!-- <div class="mb-4 flex justify-center">
            <form action="index.php" method="GET" class="flex items-center space-x-2">
                <input type="text" name="search" placeholder="Cari berdasarkan NPM" class="px-4 py-2 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500" value="<?= htmlspecialchars($search); ?>" />
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">Cari</button>
            </form>
        </div> -->

        <!-- Tabel Data Mahasiswa -->
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
                            <td class="py-2 px-4 text-center no-print">
                                <a href="edit.php?npm=<?= $row['npm']; ?>" class="bg-blue-400 text-white p-2 rounded-md hover:bg-blue-600">Edit</a>
                                <a href="hapus.php?npm=<?= $row['npm']; ?>" class="bg-red-400 text-white p-2 rounded-md hover:bg-red-600" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9" class="py-2 px-4 text-center text-red-500">Mahasiswa dengan NPM <?= htmlspecialchars($search); ?> tidak ditemukan.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

    </div>

    <!-- Footer -->
    <?php include 'footer.php'; ?>

    <script>
        function printTable() {
            // Clone the table to avoid affecting the original table
            var table = document.querySelector("table").cloneNode(true);
            // Remove the columns that should not be printed
            var cellsToHide = table.querySelectorAll(".no-print");
            cellsToHide.forEach(function(cell) {
                cell.style.display = 'none';
            });

            // Open the print dialog
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
