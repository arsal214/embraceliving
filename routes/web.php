<?php

use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('auth/login');
});


require __DIR__ . '/auth.php';
/*
|--------------------------------------------------------------------------
| Dashboard related Route
|--------------------------------------------------------------------------
*/


Route::middleware('auth')->namespace('\App\Http\Controllers\Admin')->prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin/dashboard/index');
    });
    /*
    |--------------------------------------------------------------------------
    | users related Route
    |--------------------------------------------------------------------------
    */
    Route::resource('users', 'UserController')->names('users');

    /*
    |--------------------------------------------------------------------------
    | permissions related Route
    |--------------------------------------------------------------------------
    */
    Route::resource('permissions', 'PermissionController')->names('permissions');

    /*
    |--------------------------------------------------------------------------
    | Roles related Route
    |--------------------------------------------------------------------------
    */
    Route::resource('roles', 'RoleController')->names('roles');
    /*
    |--------------------------------------------------------------------------
    | Homes related Route
    |--------------------------------------------------------------------------
    */
    Route::resource('homes', 'HomeController')->names('homes');
    /*
    |--------------------------------------------------------------------------
    | groups related Route
    |--------------------------------------------------------------------------
    */
    Route::resource('groups', 'GroupController')->names('groups');
    /*
    |--------------------------------------------------------------------------
    | regions related Route
    |--------------------------------------------------------------------------
    */
    Route::resource('regions', 'RegionController')->names('regions');
    /*
    |--------------------------------------------------------------------------
    | Themes related Route
    |--------------------------------------------------------------------------
    */
    Route::resource('themes', 'ThemeController')->names('themes');
    /*
    |--------------------------------------------------------------------------
    | Front End related Route
    |--------------------------------------------------------------------------
    */

//    require __DIR__ . '/front.php';
//
});
