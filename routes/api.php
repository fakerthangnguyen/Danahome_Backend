<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\RoomController;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::post('categories',[CategoriesController::class, 'create']);
Route::get('categories',[CategoriesController::class, 'index']);


//Rooms
Route::group([ 'middleware' =>'auth:api' ], function(){
    Route::post('rooms',[RoomController::class, 'create']);
    Route::post('room2',[RoomController::class, 'create2']);
    Route::delete('rooms/{id}/delete',[RoomController::class,'destroy']);
    Route::put('rooms/{id}/update',[RoomController::class, 'update']);

});
Route::get('rooms/{id}',[RoomController::class, 'detail']);
// Route::post('rooms',[RoomController::class, 'create']);
Route::get('rooms',[RoomController::class, 'index']);
Route::get('hotroom',[RoomController::class, 'hot']);
Route::get('/list',[RoomController::class, 'list']);


//Auth
Route::post('register',[AccountController::class, 'signup']);
Route::post('login',[AccountController::class, 'login']);
Route::group([
    'middleware' => 'auth:api'
  ], function() {
      Route::delete('logout', [AccountController::class,'logout']);
      Route::get('me', [AccountController::class,'user']);
  });


