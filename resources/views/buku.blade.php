<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Data Buku Perpustakaan</title>
</head>
<body>
  <h1>Data Buku Perpustakaan</h1>

  @if (session('berhasil'))
    <p>{{ session('berhasil') }}</p>
  @endif
  @if (session('gagal'))
    <p>{{ session('gagal') }}</p>
  @endif

  @if ($errors->any())
    <ul>
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
    </ul>
  @endif

  <h2>Tambah Buku</h2>
  <form action="{{ route('buku.store') }}" method="POST">
    @csrf
    <p>Kode Buku<br><input type="text" name="kode_buku" value="{{ old('kode_buku') }}"></p>
    <p>Judul<br><input type="text" name="judul" value="{{ old('judul') }}"></p>
    <p>ISBN<br><input type="text" name="isbn" value="{{ old('isbn') }}"></p>
    <p>Tahun Terbit<br><input type="number" name="tahun_terbit" value="{{ old('tahun_terbit') }}"></p>
    <p>Kategori<br>
       <select name="kategori_buku_id">
         <option value="">-- Pilih Kategori --</option>
         @foreach ($daftarKategori as $kategori)
             <option value="{{ $kategori['id'] }}"
               @selected(old('kategori_buku_id') == $kategori['id'])>
               {{ $kategori['nama_kategori'] }}
             </option>
         @endforeach
       </select>
    </p>
    <button type="submit">Tambah Data</button>
  </form>

  <h2>Pencarian dan Filter</h2>
  <form action="{{ route('buku.index') }}" method="GET">
    <p>Kata Kunci (kode, judul, ISBN)<br>
       <input type="text" name="q" value="{{ $kataKunci }}">
    </p>
    <p>Kategori<br>
       <select name="kategori_buku_id">
          <option value="">-- Semua Kategori --</option>
          @foreach ($daftarKategori as $kategori)
             <option value="{{ $kategori['id'] }}"
               @selected((string) $kategoriId === (string) $kategori['id'])>
               {{ $kategori['nama_kategori'] }}
             </option>
          @endforeach
       </select>
    </p>
    <button type="submit">Cari</button>
    <a href="{{ route('buku.index') }}">Reset</a>
  </form>

  <h2>Daftar Buku</h2>
  <table border="1" cellpadding="6" cellspacing="0">
     <tr>
       <th>Kode</th><th>Judul</th><th>ISBN</th><th>Tahun</th>
       <th>Status</th><th>Kategori</th><th>Aksi</th>
     </tr>
     @forelse ($daftarBuku as $buku)
       <tr>
          <td>{{ $buku['kode_buku'] }}</td>
          <td>{{ $buku['judul'] }}</td>
          <td>{{ $buku['isbn'] }}</td>
          <td>{{ $buku['tahun_terbit'] }}</td>
          <td>
            @if ($buku['status'] === 'Baru')
              Baru
            @else
              Lama
            @endif
          </td>
          <td>{{ $buku['nama_kategori'] }}</td>
          <td>
             <a href="{{ route('buku.edit', $buku['id']) }}">Ubah</a>
             <form action="{{ route('buku.destroy', $buku['id']) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit">Hapus</button>
             </form>
          </td>
       </tr>
     @empty
       <tr><td colspan="7">Data tidak ditemukan.</td></tr>
     @endforelse
  </table>
</body>
</html>