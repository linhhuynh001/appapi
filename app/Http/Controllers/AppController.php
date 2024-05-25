<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Album;
use App\Models\Artist;
use App\Models\Audio;
use App\Models\Category;
use App\Models\Favorite;
use App\Models\Playlist;
use App\Models\Role;
use App\Models\User;
use App\Models\CategoryAudio;
use Hash;
use File;
use DB;
use JWTAuth;
use JWTAuthException;

class AppController extends Controller
{
    private $user;
    public function __construct(User $user){
        $this->user = $user;
    }

    function loadMessage($status = 400, $message = '', $data = null){
        echo json_encode(['status' => $status, 'message' => $message, 'data' => $data]);
    }

    function validatePassword($password) {
        if (strlen($password) < 6) {
            return false;
        }
        $hasUppercase = false;
        $hasNumber = false;
        $hasSpecial = false;
        for ($i = 0; $i < strlen($password); $i++) {
            $ch = $password[$i];
            if ($ch >= 'A' && $ch <= 'Z') {
                $hasUppercase = true;
            } else if ($ch >= '0' && $ch <= '9') {
                $hasNumber = true;
            } else if ($ch === '@' || $ch === '$' || $ch === '!' || $ch === '%' || $ch === '*' || $ch === '?' || $ch === '&') {
                $hasSpecial = true;
            }
        }
        return json_encode(['hasUppercase' => $hasUppercase, 'hasNumber' => $hasNumber, 'hasSpecial' => $hasSpecial]);
    }

    public function SignupApi(Request $request){
        $userCheck = User::where('email', $request->email)->first();
        $error = 0;
        if($request->email == ''){
            $message = 'Email is required!';
            $error++;
        }
        if($request->password == ''){
            $message = 'Password is required!';
            $error++;
        }
        if($userCheck){
            $message = 'This email already exists';
            $error++;
        }else{
            $check_pass = $this->validatePassword($request->password);
            if($check_pass == false){
                $message = 'Password must have more than 6 characters';
                $error++;
            }else{
                $check_pass = json_decode($check_pass,true);
                if($check_pass['hasUppercase'] == false){
                    $message = 'Password must contain capital letters';
                    $error++;
                }else if($check_pass['hasNumber'] == false){
                    $message = 'Password must contain numbers';
                    $error++;
                }else if($check_pass['hasSpecial'] == false){
                    $message = 'Password must contain special characters';
                    $error++;
                }
            }
        }

        if(!$error){
            $user = new User();
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->role = 0;
            $result = $user->save();
            if($result){
                $status = 200;
                $message = 'Sign In Successfully!';
                $data = [
                    'email' => $request->email,
                    'role' => 0,
                ];
            }
        }

        return $this->loadMessage(isset($status) ? $status : 400, isset($message) ? $message : '', isset($data) ? $data : null);
    }

    public function loginApi(Request $request){
        $email = $request->email;
        $password = $request->password;
        $error = 0;
        if($request->email == ''){
            $message = 'Email is required!';
            $error++;
        }
        if($request->password == ''){
            $message = 'Password is required!';
            $error++;
        }else{
            $check_pass = $this->validatePassword($request->password);
            if($check_pass == false){
                $message = 'Password must have more than 6 characters';
                $error++;
            }else{
                $check_pass = json_decode($check_pass,true);
                if($check_pass['hasUppercase'] == false){
                    $message = 'Password must contain capital letters';
                    $error++;
                }else if($check_pass['hasNumber'] == false){
                    $message = 'Password must contain numbers';
                    $error++;
                }else if($check_pass['hasSpecial'] == false){
                    $message = 'Password must contain special characters';
                    $error++;
                }
            }
        }
        if(!$error){
            $credentials = [
                'email' => $email,
                'password' => $password,
            ];
            if(Auth::attempt($credentials)){
                $user = Auth::user();
                Auth::login($user);
                $token = JWTAuth::fromUser($user);
                $status = true;
                $data = [
                    'user' => [
                        'email' => $user->email,
                        'role' => $user->role,
                    ],
                    'token' => $token
                ];
            }else{
                $message = 'Email or Password is wrong!';
            }
        }
        return $this->loadMessage(isset($status) ? $status : 400, isset($message) ? $message : '', isset($data) ? $data : null);
    }

    public function getHotAlbumsByPlayCount(){
        $album = Album::orderBy('playCount','desc')->get();
        if(isset($album) && count($album) > 0){
            $message = 'Hot albums Found!';
            $data = $album;
            $status = 200;
        }else{
            $message = 'Hot albums Not Found';
        }
        return $this->loadMessage(isset($status) ? $status : 400, isset($message) ? $message : '', isset($data) ? $data : null);
    }

    public function HotAlbumsByPlayCountAdd(Request $request){
        $error = 0;
        $name = $request->name;
        $playCount = $request->playCount;
        if($name == ''){
            $message = 'Please enter the album name!';
            $error++;
        }
        if(!$error){
            $album = new Album;
            $album->name = $name;
            $album->playCount = $playCount;
            if($request->hasFile('image')){
                $file = $request->file('image');
                $check_name = $file->getClientOriginalName();
                $check_type = $file->getClientOriginalExtension();
                $name_logo = str_replace(' ','-',$check_name);
                $fileName = substr(md5(time()),0,5).'-'.$name_logo;
                $file_url = '/images/'.date('Y').'/'.date('m').'/'.substr(md5(time()),0,5).'-'.$name_logo;
                $path = public_path().'/images/'.date('Y').'/'.date('m');
                if (!file_exists($path)) {
                    File::makeDirectory($path, $mode = 0777, true, true);
                }
                $file->move($path,$fileName);
                $album->image = $file_url;
            }
            $result = $album->save();
            if($result){
                $status = 200;
                $message = 'Add album successfully';
                $data = [
                    'name' => $name,
                    'playCount' => $playCount,
                    'image' => isset($file_url) ? $file_url : '',
                ];
            }else{
                $message = 'There was an error, please try again later!';
            }   
        }

        return $this->loadMessage(isset($status) ? $status : 400, isset($message) ? $message : '', isset($data) ? $data : null);
    }

    public function HotAlbumsByPlayCountUpdate(Request $request, $id){
        $error = 0;
        $name = $request->name;
        $playCount = $request->playCount;
        if($name == ''){
            $message = 'Please enter the album name!';
            $error++;
        }

        if(!$error){
            $album = Album::find($id);
            $album->name = $name;
            $album->playCount = $playCount;
            if($request->hasFile('image')){
                $file = $request->file('image');
                $check_name = $file->getClientOriginalName();
                $check_type = $file->getClientOriginalExtension();
                $name_logo = str_replace(' ','-',$check_name);
                $fileName = substr(md5(time()),0,5).'-'.$name_logo;
                $file_url = '/images/'.date('Y').'/'.date('m').'/'.substr(md5(time()),0,5).'-'.$name_logo;
                $path = public_path().'/images/'.date('Y').'/'.date('m');
                if (!file_exists($path)) {
                    File::makeDirectory($path, $mode = 0777, true, true);
                }
                $file->move($path,$fileName);
                $album->image = $file_url;
            }
            $result = $album->update();
            if($result){
                $status = 200;
                $message = 'Updated album successfully';
                $data = [
                    'name' => $name,
                    'playCount' => $playCount,
                    'image' => isset($file_url) ? $file_url : '',
                ];
            }else{
                $message = 'There was an error, please try again later!';
            }   
        }

        return $this->loadMessage(isset($status) ? $status : 400, isset($message) ? $message : '', isset($data) ? $data : null);
    }

    public function deleteHotAlbumsByPlayCount($id){
        $album = Album::find($id);
        if(isset($album)){
            if(File::exists('public/'.$album->image)){
                File::delete('public/'.$album->image);
            }
            $album->delete();
            $message = 'Delete album successfully!';
            $status = 200;
        }else{
            $message = 'Album does not exist!';
        }
        return $this->loadMessage(isset($status) ? $status : 400, isset($message) ? $message : '', isset($data) ? $data : null);
    }

    public function getArtistsBySubscribe(){
        $album = Artist::orderBy('subscribeNumber','desc')->get();
        if(isset($album) && count($album) > 0){
            $message = 'Hot artist Found!';
            $data = $album;
            $status = 200;
        }else{
            $message = 'Hot artist Not Found';
        }
        return $this->loadMessage(isset($status) ? $status : 400, isset($message) ? $message : '', isset($data) ? $data : null);
    }

    public function ArtistsBySubscribeAdd(Request $request){
        $error = 0;
        $name = $request->name;
        $subscribeNumber = $request->subscribeNumber;
        if($name == ''){
            $message = 'Please enter the artist name!';
            $error++;
        }
        if(!$error){
            $artist = new Artist;
            $artist->name = $name;
            $artist->subscribeNumber = $subscribeNumber;
            $result = $artist->save();
            if($result){
                $status = 200;
                $message = 'Add artist successfully';
                $data = [
                    'name' => $name,
                    'subscribeNumber' => $subscribeNumber,
                ];
            }else{
                $message = 'There was an error, please try again later!';
            }   
        }

        return $this->loadMessage(isset($status) ? $status : 400, isset($message) ? $message : '', isset($data) ? $data : null);
    }

    public function ArtistsBySubscribeUpdate(Request $request, $id){
        $error = 0;
        $name = $request->name;
        $playCount = $request->playCount;
        if($name == ''){
            $message = 'Please enter the artist name!';
            $error++;
        }

        if(!$error){
            $album = Album::find($id);
            $album->name = $name;
            $album->playCount = $playCount;
            if($request->hasFile('image')){
                $file = $request->file('image');
                $check_name = $file->getClientOriginalName();
                $check_type = $file->getClientOriginalExtension();
                $name_logo = str_replace(' ','-',$check_name);
                $fileName = substr(md5(time()),0,5).'-'.$name_logo;
                $file_url = '/images/'.date('Y').'/'.date('m').'/'.substr(md5(time()),0,5).'-'.$name_logo;
                $path = public_path().'/images/'.date('Y').'/'.date('m');
                if (!file_exists($path)) {
                    File::makeDirectory($path, $mode = 0777, true, true);
                }
                $file->move($path,$fileName);
                $album->image = $file_url;
            }
            $result = $album->update();
            if($result){
                $status = 200;
                $message = 'Updated album successfully';
                $data = [
                    'name' => $name,
                    'playCount' => $playCount,
                    'image' => isset($file_url) ? $file_url : '',
                ];
            }else{
                $message = 'There was an error, please try again later!';
            }   
        }

        return $this->loadMessage(isset($status) ? $status : 400, isset($message) ? $message : '', isset($data) ? $data : null);
    }

    public function deleteArtistsBySubscribe($id){
        $artist = Artist::find($id);
        if(isset($artist)){
            $artist->delete();
            $message = 'Delete artist successfully!';
            $status = 200;
        }else{
            $message = 'Artist does not exist!';
        }
        return $this->loadMessage(isset($status) ? $status : 400, isset($message) ? $message : '', isset($data) ? $data : null);
    }

    public function getPlaylistsByUserId(Request $request){
        $userId = $request->userId;
        $type = $request->type;
        $playlist = Playlist::where('user_id',$userId)
        ->where('type',$type)->get();
        if(isset($playlist) && count($playlist) > 0){
            $message = 'Playlist Found!';
            $data = $playlist;
            $status = 200;
        }else{
            $message = 'Playlist Not Found';
        }
        return $this->loadMessage(isset($status) ? $status : 400, isset($message) ? $message : '', isset($data) ? $data : null);
    }

    public function PlaylistsByUserIdAdd(Request $request){
        $error = 0;
        $name = $request->name;
        $user_id = $request->user_id;
        $type = $request->type;
        if($name == ''){
            $message = 'Please enter the playlist name!';
            $error++;
        }
        if(!$error){
            $playlist = new Playlist;
            $playlist->name = $name;
            $playlist->user_id = $user_id;
            $playlist->type = $type;
            if($request->hasFile('image')){
                $file = $request->file('image');
                $check_name = $file->getClientOriginalName();
                $check_type = $file->getClientOriginalExtension();
                $name_logo = str_replace(' ','-',$check_name);
                $fileName = substr(md5(time()),0,5).'-'.$name_logo;
                $file_url = '/images/'.date('Y').'/'.date('m').'/'.substr(md5(time()),0,5).'-'.$name_logo;
                $path = public_path().'/images/'.date('Y').'/'.date('m');
                if (!file_exists($path)) {
                    File::makeDirectory($path, $mode = 0777, true, true);
                }
                $file->move($path,$fileName);
                $playlist->image = $file_url;
            }
            $result = $playlist->save();
            if($result){
                $status = 200;
                $message = 'Add playlist successfully';
                $data = [
                    'name' => $name,
                    'user_id' => $user_id,
                    'type' => $type,
                    'image' => isset($file_url) ? $file_url : '',
                ];
            }else{
                $message = 'There was an error, please try again later!';
            }   
        }

        return $this->loadMessage(isset($status) ? $status : 400, isset($message) ? $message : '', isset($data) ? $data : null);
    }

    public function PlaylistsByUserIdUpdate(Request $request, $id){
        $error = 0;
        $name = $request->name;
        $user_id = $request->user_id;
        $type = $request->type;
        if($name == ''){
            $message = 'Please enter the playlist name!';
            $error++;
        }
        if(!$error){
            $playlist = Playlist::find($id);
            $playlist->name = $name;
            $playlist->user_id = $user_id;
            $playlist->type = $type;
            if($request->hasFile('image')){
                $file = $request->file('image');
                $check_name = $file->getClientOriginalName();
                $check_type = $file->getClientOriginalExtension();
                $name_logo = str_replace(' ','-',$check_name);
                $fileName = substr(md5(time()),0,5).'-'.$name_logo;
                $file_url = '/images/'.date('Y').'/'.date('m').'/'.substr(md5(time()),0,5).'-'.$name_logo;
                $path = public_path().'/images/'.date('Y').'/'.date('m');
                if (!file_exists($path)) {
                    File::makeDirectory($path, $mode = 0777, true, true);
                }
                $file->move($path,$fileName);
                $playlist->image = $file_url;
            }
            $result = $playlist->update();
            if($result){
                $status = 200;
                $message = 'Updated playlist successfully';
                $data = [
                    'name' => $name,
                    'user_id' => $user_id,
                    'type' => $type,
                    'image' => isset($file_url) ? $file_url : $playlist->image,
                ];
            }else{
                $message = 'There was an error, please try again later!';
            }   
        }

        return $this->loadMessage(isset($status) ? $status : 400, isset($message) ? $message : '', isset($data) ? $data : null);
    }

    public function deletePlaylistsByUserId($id){
        $playlist = Playlist::find($id);
        if(isset($playlist)){
            $playlist->delete();
            $message = 'Delete playlist successfully!';
            $status = 200;
        }else{
            $message = 'Playlist does not exist!';
        }
        return $this->loadMessage(isset($status) ? $status : 400, isset($message) ? $message : '', isset($data) ? $data : null);
    }

    public function getCategory(){
        $category = Category::orderBy('id','desc')->get();
        if(isset($category) && count($category) > 0){
            $message = 'Category Found!';
            $data = $category;
            $status = 200;
        }else{
            $message = 'Category Not Found';
        }
        return $this->loadMessage(isset($status) ? $status : 400, isset($message) ? $message : '', isset($data) ? $data : null);
    }

    public function CategoryAdd(Request $request){
        $error = 0;
        $name = $request->name;
        if($name == ''){
            $message = 'Please enter the category name!';
            $error++;
        }
        if(!$error){
            $category = new Category;
            $category->name = $name;
            $result = $category->save();
            if($result){
                $status = 200;
                $message = 'Add category successfully';
                $data = [
                    'name' => $name,
                ];
            }else{
                $message = 'There was an error, please try again later!';
            }   
        }

        return $this->loadMessage(isset($status) ? $status : 400, isset($message) ? $message : '', isset($data) ? $data : null);
    }

    public function CategoryUpdate(Request $request, $id){
        $error = 0;
        $name = $request->name;
        if($name == ''){
            $message = 'Please enter the category name!';
            $error++;
        }
        if(!$error){
            $category = Category::find($id);
            $category->name = $name;
            $result = $category->update();
            if($result){
                $status = 200;
                $message = 'Updated category successfully';
                $data = [
                    'name' => $name,
                ];
            }else{
                $message = 'There was an error, please try again later!';
            }   
        }

        return $this->loadMessage(isset($status) ? $status : 400, isset($message) ? $message : '', isset($data) ? $data : null);
    }

    public function deleteCategory($id){
        $category = Category::find($id);
        if(isset($category)){
            $category->delete();
            $message = 'Delete category successfully!';
            $status = 200;
        }else{
            $message = 'Category does not exist!';
        }
        return $this->loadMessage(isset($status) ? $status : 400, isset($message) ? $message : '', isset($data) ? $data : null);
    }

    public function CategoryAudioAdd(Request $request){
        $category_id = $request->category_id;
        $audio_id = $request->audio_id;
        $error = 0;
        $checkfirst = CategoryAudio::where('category_id',$category_id)
        ->where('audio_id',$audio_id)->first();
        if($category_id == ''){
            $message = 'Please enter the category id!';
            $error++;
        }
        if($audio_id == ''){
            $message = 'Please enter the audio id!';
            $error++;
        }
        if(!$error){
            if(!$checkfirst){
                $message = "Add category audio successfully";
                $status = 200;
                $data = [
                    'category_id' => $category_id,
                    'audio_id' => $audio_id,
                ];
                $category_audio = new CategoryAudio;
                $category_audio->category_id = $category_id;
                $category_audio->audio_id = $audio_id;
                $category_audio->save();
            }else{
                $message = "Add category audio successfully";
                $status = 200;
                $data = [
                    'category_id' => $category_id,
                    'audio_id' => $audio_id,
                ];
            }
        }
        return $this->loadMessage(isset($status) ? $status : 400, isset($message) ? $message : '', isset($data) ? $data : null);
    }

    public function getArtistsByCategory(Request $request){
        $id = $request->categoryId;
        $keyword = $request->search;
        $artist = DB::table('audio')
        ->leftJoin('artist','audio.artist_id','artist.id')
        ->leftJoin('category_audio','audio.id','category_audio.audio_id')
        ->leftJoin('category','category_audio.category_id','category.id')
        ->when($id != '', function ($search) use ($id) {
            return $search->where("category.id", $id);
        })
        ->when($keyword != '', function ($search) use ($keyword) {
            return $search->where("audio.title", 'like', "%$keyword%")
            ->orWhere("audio.album_id", 'like', "%$keyword%")
            ->orWhere("audio.artist_id", 'like', "%$keyword%")
            ->orWhere("category.name", 'like', "%$keyword%")
            ->orWhere("artist.name", 'like', "%$keyword%");
        })
        ->get();
        if(isset($artist) && count($artist) > 0){
            $message = 'Artists By Category Found!';
            $data = $artist;
            $status = 200;
        }else{
            $message = 'Artists By Category Not Found';
        }
        return $this->loadMessage(isset($status) ? $status : 400, isset($message) ? $message : '', isset($data) ? $data : null);
    }

    public function getAlbumsByCategory(Request $request){
        $id = $request->categoryId;
        $keyword = $request->search;
        $artist = DB::table('audio')
        ->leftJoin('album','audio.album_id','album.id')
        ->leftJoin('category_audio','audio.id','category_audio.audio_id')
        ->leftJoin('category','category_audio.category_id','category.id')
        ->when($id != '', function ($search) use ($id) {
            return $search->where("category.id", $id);
        })
        ->when($keyword != '', function ($search) use ($keyword) {
            return $search->where("audio.title", 'like', "%$keyword%")
            ->orWhere("audio.album_id", 'like', "%$keyword%")
            ->orWhere("audio.artist_id", 'like', "%$keyword%")
            ->orWhere("category.name", 'like', "%$keyword%")
            ->orWhere("album.name", 'like', "%$keyword%");
        })
        ->get();
        if(isset($artist) && count($artist) > 0){
            $message = 'Albums By Category Found!';
            $data = $artist;
            $status = 200;
        }else{
            $message = 'Albums By Category Not Found';
        }
        return $this->loadMessage(isset($status) ? $status : 400, isset($message) ? $message : '', isset($data) ? $data : null);
    }

    public function getAudiosBySearch(Request $request){
        $keyword = $request->search;
        $albumId = $request->albumId;
        $audio = DB::table('audio')
        ->when($albumId != '', function ($search) use ($albumId) {
            return $search->where("audio.album_id", $albumId);
        })
        ->when($keyword != '', function ($search) use ($keyword) {
            return $search->where("audio.title", 'like', "%$keyword%")
            ->orWhere("audio.audioUrl", 'like', "%$keyword%")
            ->orWhere("audio.duration", 'like', "%$keyword%")
            ->orWhere("audio.playCount", 'like', "%$keyword%")
            ->orWhere("audio.album_id", 'like', "%$keyword%")
            ->orWhere("audio.artist_id", 'like', "%$keyword%");
        })->get();
        if(isset($audio) && count($audio) > 0){
            $message = 'Audio By Search Found!';
            $data = $audio;
            $status = 200;
        }else{
            $message = 'Audio By Search Not Found';
        }
        return $this->loadMessage(isset($status) ? $status : 400, isset($message) ? $message : '', isset($data) ? $data : null);
    }

    public function getAlbumById($id){
        $album = Album::find($id);
        if($album){
            $message = 'Album Found!';
            $data = $album;
            $status = 200;
        }else{
            $message = 'Album Not Found';
        }
        return $this->loadMessage(isset($status) ? $status : 400, isset($message) ? $message : '', isset($data) ? $data : null);
    }

    public function listenAudio($id){
        $audio = Audio::find($id);
        if($audio){
            $message = 'Listen Audio';
            $data = $audio;
            $status = 200;
        }else{
            $message = 'Listen Failed';
        }
        return $this->loadMessage(isset($status) ? $status : 400, isset($message) ? $message : '', isset($data) ? $data : null);
    }
}
