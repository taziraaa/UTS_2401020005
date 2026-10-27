<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

function statusTahunBuku(int $tahun): string
{
    if ($tahun >= 2023) {
        return 'Baru';
    }

    return 'Lama';
}

function bersihkanTeks(string $nilai): string
{
    return strip_tags(trim($nilai));
}

function bersihkanInputBuku(Request $request): void
{
    $request->merge([
        'kode_buku' => bersihkanTeks((string) $request->input('kode_buku')),
        'judul' => bersihkanTeks((string) $request->input('judul')),
        'isbn' => bersihkanTeks((string) $request->input('isbn')),
    ]);
}

function aturanBuku(): array
{
    return [
        'kode_buku' => ['required', 'regex:/^[A-Za-z0-9]{10}$/'],
        'judul' => ['required', 'string', 'min:3', 'max:100'],
        'isbn' => ['required', 'regex:/^[0-9]{13}$/'],
        'tahun_terbit' => ['required', 'integer', 'between:2000,2026'],
        'kategori_buku_id' => ['required', 'integer'],
    ];
}

function pesanBuku(): array
{
    return [
        'kode_buku.required' => 'Kode buku wajib diisi.',
        'kode_buku.regex' => 'Kode buku harus tepat 10 karakter huruf atau angka.',
        'judul.required' => 'Judul wajib diisi.',
        'judul.min' => 'Judul minimal 3 karakter.',
        'judul.max' => 'Judul maksimal 100 karakter.',
        'isbn.required' => 'ISBN wajib diisi.',
        'isbn.regex' => 'ISBN harus tepat 13 digit angka.',
        'tahun_terbit.required' => 'Tahun terbit wajib diisi.',
        'tahun_terbit.integer' => 'Tahun terbit harus berupa angka.',
        'tahun_terbit.between' => 'Tahun terbit harus antara 2000 dan 2026.',
        'kategori_buku_id.required' => 'Kategori wajib dipilih.',
        'kategori_buku_id.integer' => 'Kategori tidak valid.',
    ];
}

function kategoriTersedia(\PDO $pdo, int $id): bool
{
    $cek = $pdo->prepare('SELECT COUNT(*) FROM kategori_buku WHERE id = :id');
    $cek->execute(['id' => $id]);

    return (int) $cek->fetchColumn() > 0;
}

Route::get('/buku', function (Request $request) {
    $kataKunci = bersihkanTeks((string) $request->query('q', ''));
    $kategoriId = trim((string) $request->query('kategori_buku_id', ''));

    try {
        $pdo = DB::connection()->getPdo();

        $sql = 'SELECT b.id, b.kode_buku, b.judul, b.isbn, b.tahun_terbit,
                       b.kategori_buku_id, k.nama_kategori
                FROM buku AS b
                JOIN kategori_buku AS k
                  ON k.id = b.kategori_buku_id
                WHERE 1 = 1';
        $parameter = [];

        if ($kataKunci !== '') {
            $sql .= ' AND (b.kode_buku LIKE :kata_kode
                      OR b.judul LIKE :kata_judul
                      OR b.isbn LIKE :kata_isbn)';
            $nilaiPencarian = '%' . $kataKunci . '%';
            $parameter['kata_kode'] = $nilaiPencarian;
            $parameter['kata_judul'] = $nilaiPencarian;
            $parameter['kata_isbn'] = $nilaiPencarian;
        }

        if ($kategoriId !== '') {
            $sql .= ' AND b.kategori_buku_id = :kategori_buku_id';
            $parameter['kategori_buku_id'] = (int) $kategoriId;
        }

        $sql .= ' ORDER BY b.kode_buku';
        $ambilBuku = $pdo->prepare($sql);
        $ambilBuku->execute($parameter);
        $daftarBuku = $ambilBuku->fetchAll(\PDO::FETCH_ASSOC);

        foreach ($daftarBuku as $indeks => $buku) {
            $daftarBuku[$indeks]['status'] = statusTahunBuku(
                (int) $buku['tahun_terbit']
            );
        }

        $ambilKategori = $pdo->prepare(
            'SELECT id, nama_kategori FROM kategori_buku ORDER BY nama_kategori'
        );
        $ambilKategori->execute();
        $daftarKategori = $ambilKategori->fetchAll(\PDO::FETCH_ASSOC);

        return view('buku', compact(
            'daftarBuku',
            'daftarKategori',
            'kataKunci',
            'kategoriId'
        ));
    } catch (\Throwable $error) {
        report($error);

        return response(
            'Data tidak dapat dibaca. Periksa koneksi dan query.',
            500
        );
    }
})->name('buku.index');