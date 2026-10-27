USE perpustakaan_2401020005;

INSERT INTO kategori_buku (nama_kategori) VALUES
    ('Teknologi'),
    ('Kemaritiman');

INSERT INTO buku
    (kode_buku, judul, isbn, tahun_terbit, kategori_buku_id)
VALUES
    ('BK00000001', 'Pemrograman Web Dasar', '9786020000011', 2022, 1),
    ('BK00000002', 'Basis Data Praktis', '9786020000028', 2023, 1),
    ('BK00000003', 'Sistem Informasi Maritim', '9786020000035', 2024, 2);