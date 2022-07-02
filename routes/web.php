<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use \App\Models\Link;
use \App\Http\Controllers\HomeController;

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
    $links = Link::all();

    return view('welcome', ['links' => $links]);
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::get('/submit', function() {
    return view('submit');
});

Route::post('/submit', function (Request $request) {
    $data = $request->validate([
        'title' => 'required | max:255',
        'url' => 'required | url | max:255',
        'description' => 'required | max:255',
    ]);

    $link = tap(new Link($data))->save();

    return redirect('/');
});
