CREATE DATABASE pwebpraktikum;

\c pwebpraktikum

CREATE TABLE mahasiswa (
    npm SERIAL PRIMARY KEY,              
    nama VARCHAR(100) NOT NULL,         
    kelas VARCHAR(50) NOT NULL,          
    no_hp VARCHAR(15) NOT NULL,        
    alamat TEXT NOT NULL,               
    picture TEXT NOT NULL,                        
    status VARCHAR(15) NOT NULL  
);
