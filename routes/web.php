<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;


Route::group(['prefix' => 'dashboard','middleware' => ['auth']], function() {
    Route::get('/',[HomeController::class,'index'])->name('dashboard.url');
    Route::get('users',[HomeController::class,'getUsers'])->name('get.users');
    Route::get('albums',[HomeController::class,'getAlbum'])->name('get.album');
    Route::post('albums/add',[HomeController::class,'getAlbumAdd'])->name('get.album.add');
    Route::get('artist',[HomeController::class,'getArtist'])->name('get.artist');
    Route::post('artist/add',[HomeController::class,'getArtistAdd'])->name('get.artist.add');
    Route::get('audio',[HomeController::class,'getAudio'])->name('get.audio');
    Route::post('audio/add',[HomeController::class,'getAudioAdd'])->name('get.audio.add');
    Route::get('playlist',[HomeController::class,'getPlaylist'])->name('get.playlist');
    Route::post('playlist/add',[HomeController::class,'getPlaylistAdd'])->name('get.playlist.add');
    Route::post('categorybyaudio',[HomeController::class,'getCategoryByAudio'])->name('get.categorybyaudio');
    Route::get('category',[HomeController::class,'getCategory'])->name('get.category');
    Route::post('category/add',[HomeController::class,'getCategoryAdd'])->name('get.category.add');
});
Route::get('dashboard/login',[HomeController::class,'login'])->name('login');
Route::post('dashboard/login',[HomeController::class,'loginPost'])->name('login.submit');
Route::get('logout',[HomeController::class,'logout'])->name('logout');