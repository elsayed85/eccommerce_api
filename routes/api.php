<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group([], function () {

    // verision 1
    Route::group(['prefix' => 'v1'], function () {

        // login & register
        Route::group(['prefix' => 'oauth'], function () {
            Route::post('/login', 'Api\oauth\loginController@login');
            Route::post('/register', 'Api\oauth\RegisterController@register');

            // // login using facebook
            // Route::get('/facebook', 'Api\oauth\facebook\FacebookController@redirectToFacebook');
            // Route::get('/facebook/callback', 'Api\oauth\facebook\FacebookController@handleFacebookCallback');

            Route::get('email/verify/{id}/{hash}', 'Api\VerificationApiController@verify')->name('verificationapi.verify');
            Route::post('email/resend', 'Api\VerificationApiController@resend')->name('verificationapi.resend');
        });

        Route::post('/logout', 'Api\oauth\logoutController@logout')->middleware('auth:airlock');

        // password reset
        Route::group(['prefix' => 'password'], function () {
            Route::post('create', 'Api\PasswordResetController@create');
            Route::get('find/{token}', 'Api\PasswordResetController@find')->name('password_token');
            Route::post('reset', 'Api\PasswordResetController@reset');
        });

        // products
        Route::get('/product', 'Api\productsController@index');
        Route::get('/product/{product}', 'Api\productsController@show')->where('product', '[0-9]+')->middleware('localization');
        Route::get('/product/filter', 'Api\productsController@filter');
        Route::get("/search",  'Api\productsController@search');

        // category
        Route::apiResource('category', 'Api\categoryController')
            ->except(['update', 'destroy', 'store']);

        // auth
        Route::group(['middleware' => ['auth:airlock', 'verified']], function () {

            // user data
            Route::get('/user', 'Api\UserController@user');

            // update user profile
            Route::post('/profile', 'Api\UserController@updateProfile');
            Route::post('/deactivate', 'Api\UserController@softDelete');


            Route::apiResource('orders', 'Api\OrderController')
                ->except(['update', 'destroy', 'store']);

            // cart
            Route::apiResource('carts', 'Api\CartController')
                ->except(['update', 'index', 'destroy']);
            Route::post('/carts/{cart}', 'Api\CartController@addProduct');
            Route::post('/carts/{cart}/clear', 'Api\CartController@clear');
            Route::delete('/carts/{cart}', 'Api\CartController@removeProduct');
            Route::post('/carts/{cart}/checkout', 'Api\CartController@checkout');

            // wishlist
            Route::apiResource('wishlist', 'Api\WishlistController')
                ->except(['update', 'index', 'destroy']);
            Route::post('/wishlist/{wishlist}', 'Api\WishlistController@addProduct');
            Route::post('/wishlist/{wishlist}/clear', 'Api\WishlistController@clear');
            Route::delete('/wishlist/{wishlist}', 'Api\WishlistController@removeProduct');
            Route::post('/wishlist/{wishlist}/move', 'Api\WishlistController@moveToCart');

            // reviews and rates
            Route::get('/review',  'Api\ReviewsController@index');
            Route::post('/review',  'Api\ReviewsController@store');
            Route::delete('/review/{review}',  'Api\ReviewsController@remove');
        });
    });
});


Route::fallback(function(){
    return response()->json([
        'message' => 'Page Not Found'], 404);
});
