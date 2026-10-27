<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ubah Buku</title>
</head>
<body>
  <h1>Ubah Buku</h1>

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

  <form action="{{ route('buku.update', $buku['id']) }}" method="POST">
     @csrf
     @method('PUT')
     <p>Kode Buku<br>
       <input type="text" name="kode_buku" value="{{ old('kode_buku', $buku['kode_buku']) }}">
     </p>
     <p>Judul<br>
       <input type="text" name="judul" value="{{ old('judul', $buku['judul']) }}">
     </p>
     <p>ISBN<br>
       <input type="text" name="isbn" value="{{ old('isbn', $buku['isbn']) }}">
     </p>
     <p>Tahun Terbit<br>
       <input type="number" name="tahun_terbit" value="{{ old('tahun_terbit', $buku['tahun_terbit']) }}">
     </p>
     <p>Kategori<br>
       <select name="kategori_buku_id">
           @foreach ($daftarKategori as $kategori)
             <option value="{{ $kategori['id'] }}"
                @selected(old('kategori_buku_id', $buku['kategori_buku_id']) == $kategori['id'])>
                {{ $kategori['nama_kategori'] }}
             </option>
           @endforeach
       </select>
     </p>
     <button type="submit">Simpan Perubahan</button>
     <a href="{{ route('buku.index') }}">Kembali</a>
  </form>
</body>
</html>