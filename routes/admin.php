<?php

//       redirect to dashboard
//       /admin => /admin/dashboard
Route::redirect('/', 'admin/dashboard');


Route::group(['prefix' => 'dashboard'], function () {
    Route::get('/', 'Admin\Dashboard\DashboardContoller@index')->name('home');
    // product controll
    Route::Resource('/product', 'Admin\Dashboard\Products\ProductsContoller');
    // barnds controll
    Route::Resource('/brand', 'Admin\Dashboard\Brands\BrandController');
});

// admin profile
Route::get('/profile', 'Admin\AdminController@profile');
// update profile data
Route::put('/profile', 'Admin\AdminController@Updateprofile');
