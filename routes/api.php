<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AppController;

Route::post('signup',[AppController::class,'SignupApi']);
Route::post('signin',[AppController::class,'loginApi']);

Route::get('albums/hot-albums',[AppController::class,'getHotAlbumsByPlayCount']);
Route::post('albums/hot-albums/add',[AppController::class,'HotAlbumsByPlayCountAdd']);
Route::put('albums/hot-albums/update/{id}',[AppController::class,'HotAlbumsByPlayCountUpdate']);
Route::delete('albums/hot-albums/delete/{id}',[AppController::class,'deleteHotAlbumsByPlayCount']);


Route::get('artists/hot-artists',[AppController::class,'getArtistsBySubscribe']);
Route::post('artists/hot-artists/add',[AppController::class,'ArtistsBySubscribeAdd']);
Route::put('artists/hot-artists/update/{id}',[AppController::class,'ArtistsBySubscribeUpdate']);
Route::delete('artists/hot-artists/delete/{id}',[AppController::class,'deleteArtistsBySubscribe']);

Route::get('playlists',[AppController::class,'getPlaylistsByUserId']);
Route::post('playlists/add',[AppController::class,'PlaylistsByUserIdAdd']);
Route::put('playlists/update/{id}',[AppController::class,'PlaylistsByUserIdUpdate']);
Route::delete('playlists/delete/{id}',[AppController::class,'deletePlaylistsByUserId']);

Route::get('category',[AppController::class,'getCategory']);
Route::post('category/add',[AppController::class,'CategoryAdd']);
Route::put('category/update/{id}',[AppController::class,'CategoryUpdate']);
Route::delete('category/delete/{id}',[AppController::class,'deleteCategory']);

Route::post('category-audio',[AppController::class,'CategoryAudioAdd']);

Route::get('artists',[AppController::class,'getArtistsByCategory']);
Route::get('albums',[AppController::class,'getAlbumsByCategory']);
Route::get('audios',[AppController::class,'getAudiosBySearch']);
Route::get('albums/{id}',[AppController::class,'getAlbumById']);
Route::get('audios/{id}',[AppController::class,'listenAudio']);