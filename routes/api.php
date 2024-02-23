<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\CartController;
use App\Http\Controllers\Api\v1\StoreController;
use App\Http\Controllers\Api\v1\ProductController;
use App\Http\Controllers\Api\v1\PlaceOrderController;
use App\Http\Controllers\Api\v1\GuestTokenController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::group(['namespace' => 'Api', 'middleware' => ['cors']], function($route){
    $route->group(['namespace' => 'v1', 'prefix' => 'v1'], function($route){
        $route->group(['prefix' => 'products'], function($route){
            $route->get('/', [ProductController::class, 'getList'])->name('api.v1.products');
            $route->get('/{store_id}/{slug}', [ProductController::class, 'getProductDetail'])->name('api.v1.products.view.detail');
        });

        $route->group(['middleware' => ['jwt.guard']], function($route){
            $route->group(['prefix' => 'cart'], function($route){
                $route->post('/', [CartController::class, 'postAdd'])->name('api.v1.cart.post.add');
                $route->post('/delete', [CartController::class, 'deleteProductFromCart'])->name('api.v1.cart.post.delete');
                $route->get('/', [CartController::class, 'getItems'])->name('api.v1.cart.get');
            });


            $route->group(['prefix' => 'place-order'], function($route){
                $route->post('/', [PlaceOrderController::class, 'postPlaceOrder'])->name('api.v1.place.order');
            });
    
        });
        $route->get('/stores', [StoreController::class, 'getStoreList'])->name('api.v1.stores');
        $route->group(['prefix' => 'guest-token'], function($route){
            $route->get('/', [GuestTokenController::class, 'getGuestToken'])->name('api.v1.guest.token');
        });
    });
});