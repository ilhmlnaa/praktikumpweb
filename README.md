<h1 align="center">Tugas Praktikum Pemrograman web </h1>
<br/>

## Deskripsi

<pre> Membuat aplikasi manajemen data mahasiswa menggunakan PHP, PostgreSQL dan Tailwindcss. 
 Aplikasi ini digunakan untuk mengelola data mahasiswa, termasuk menambah, mengedit, 
 menghapus, dan mencari mahasiswa berdasarkan NPM. </pre>

## Kelompok

<pre> Nama Kelompok:
    - Dzanuar Catur Satria
    - Ilham Maulana
    - Moreno Putra Suryadi
 </pre>

## Settup Database

Database yang kita gunakan adalah postgreSQL dan menggunakan pgAdmin untuk membuat tabel nya. Berikut adalah perintah sql untuk membuat tabel yang diperlukan pada project ini

<pre> 
CREATE TABLE mahasiswa (
    npm SERIAL PRIMARY KEY,              
    nama VARCHAR(100) NOT NULL,         
    kelas VARCHAR(50) NOT NULL,          
    no_hp VARCHAR(15) NOT NULL,        
    alamat TEXT NOT NULL,               
    picture TEXT NOT NULL,                        
    status VARCHAR(15) NOT NULL  
);
 </pre>
