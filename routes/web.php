<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// 视频处理完成回调地址
Route::post('videos/return/back', 'VideoController@acceptVodReturn')->name('videos.return.back');
// 解密服务
Route::get('vod/decrypt', 'VideoController@vodDecrypt')->name('vod.decrypt');
// 视频上传
Route::post('video/upload', 'VideoController@upload')->name('video.upload');
// 更新videoId
Route::post('videos/update/videoId', 'VideoController@updateVideoId')->name('videos.update.videoId');

// 视频更新
Route::post('video/refresh/upload', 'VideoController@refreshUpload')->name('video.refreshUpload');

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia\Inertia::render('Dashboard');
    })->name('dashboard');

    Route::get('/lessons', function () {
        return Inertia\Inertia::render('Lesson/Lessons');
    })->name('lessons');


});

//Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
//    return Inertia\Inertia::render('Dashboard');
//})->name('dashboard');
