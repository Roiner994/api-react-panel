<?php
use Illuminate\Http\Request;

Route::group(['middleware' => ['jwt-auth']], function () {
    Route::resource('users', 'UserController')->only(['index', 'update', 'destroy']);
    Route::resource('categories', 'CategoryController')->only(['index', 'store', 'update', 'destroy']);
    Route::post('products/{id}','ProductController@update');
    Route::resource('products', 'ProductController')->only(['index', 'store', 'destroy']);
});

Route::get('path/images/products', function() {
	$response = ['success'=>true, 'data' => ['type' => 'PUBLIC_PATH', 'path' => 'images/products/'] ];
    return response()->json($response, 201);
});


Route::post('users/login', 'UserController@login');
Route::post('users', 'UserController@register');

