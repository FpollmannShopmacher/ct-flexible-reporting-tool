<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ViewController;

Route::view('/', 'welcome');
Route::view('/view','defaultView');

Route::get('/view/fetch-order', [ViewController::class, 'showOrderCount']);
Route::get('/view/download',[ViewController::class,'download']);
