<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/buku', function (Request $request) {
    $pdo = DB::connection()->getPdo();

    $sql = 'SELECT * FROM buku';

    $ambil = $pdo->prepare($sql);
    $ambil->execute();

    $buku = $ambil->fetchAll(PDO::FETCH_ASSOC);
});