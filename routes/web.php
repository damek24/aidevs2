<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

//Route::get('/', function () {
//    return view('welcome');
//});
Route::get('{controller}/{method}', function ($controller, $method) {
    $controller = 'App\\Http\\Controllers\\' . ucfirst($controller) . 'Controller';

    if (!class_exists($controller)) {
        abort(404, "Controller not found");
    }

    $controllerInstance = app()->make($controller);

    if (!method_exists($controllerInstance, $method)) {
        abort(404, "Method not found");
    }

    return $controllerInstance->$method();
});
