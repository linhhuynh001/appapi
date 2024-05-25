<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Album;
use App\Models\Artist;
use App\Models\Audio;
use App\Models\Category;
use App\Models\Favorite;
use App\Models\Playlist;
use App\Models\Role;
use App\Models\User;
use App\Models\CategoryAudio;
use Validator;
use Auth;
use Hash;

class HomeController extends Controller
{
    public function login(){
        return view('admin.login');
    }

    public function loginPost(Request $request){
        $credentials = [
            'email' => $request['email'],
            'password' => $request['password'],
            'role' => 1
        ];
        if(Auth::attempt($credentials)){
            $user = Auth::user();
            return redirect('/dashboard')->with('success','Login successfully !');
        }
        return redirect()->back()->with('error','Username and password incorrect!');
    }

    public function index(){
        $users = User::count();
        return view('layout.index',compact('users'));
    }

    public function getUsers(){
        $user = User::all();
        return view('admin.layout.user',compact('user')); 
    }

    public function getAlbum(){
        $album = Album::all();
        return view('admin.layout.album',compact('album')); 
    }

    public function getAlbumAdd(Request $request){
        $error = 0;
        $name = $request->name;
        $playCount = $request->playCount;
        $status = 'error';
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
                $status = 'success';
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

        return redirect()->back()->with($status,$message);
    }

    public function getArtist(){
        $artist = Artist::all();
        return view('admin.layout.artist',compact('artist')); 
    }

    public function getArtistAdd(Request $request){
        $status = 'error';
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
                $status = 'success';
                $message = 'Add artist successfully';
            }else{
                $message = 'There was an error, please try again later!';
            }   
        }
        return redirect()->back()->with($status,$message);
    }

    public function getAudio(){
        $audio = Audio::all();
        $album = Album::all();
        $artist = Artist::all();
        return view('admin.layout.audio',compact('audio','album','artist')); 
    }

    public function getAudioAdd(Request $request){
        $audio = new Audio;
        $audio->title = $request->title;
        $audio->audioUrl = $request->audioUrl;
        $audio->duration = $request->duration;
        $audio->playCount = $request->playCount;
        $audio->album_id = $request->album_id;
        $audio->artist_id = $request->artist_id;
        if($request->hasFile('imageUrl')){
            $file = $request->file('imageUrl');
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
            $audio->imageUrl = $file_url;
        }
        $audio->save();
        return redirect()->back()->with('success','Add new Audio successfully!');
    }

    public function getPlaylist(){
        $user = User::all();
        $playlist = Playlist::all();
        $category = Category::all();
        return view('admin.layout.playlist',compact('user','playlist','category')); 
    }

    public function getCategory(){
        $category = Category::all();
        $audio = Audio::all();
        return view('admin.layout.category',compact('category','audio')); 
    }

    public function getCategoryAdd(Request $request){
        $category = new Category;
        $category->name = $request->name;
        $category->save();
        return redirect()->back()->with('success','Add new Category successfully!');
    }

    public function getCategoryByAudio(Request $request){
        $category = new CategoryAudio;
        $category->category_id = $request->category_id;
        $category->audio_id = $request->audio_id;
        $check_first = CategoryAudio::where('category_id',$request->category_id)
        ->where('audio_id', $request->audio_id)->first();
        if(!$check_first){
            $category->save();
        }
        return redirect()->back()->with('success','Add new Category successfully!');
    }

    public function getPlaylistAdd(Request $request){
        $playlist = new Playlist;
        $playlist->name = $request->name;
        $playlist->user_id = $request->user_id;
        $playlist->type = $request->type;
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
        $playlist->save();
        return redirect()->back()->with('success','Add new Playlist successfully!');
    }

    public function logout(){
        Auth::logout();
        return redirect()->back()->with('success','Logout successfully!');
    }
}
