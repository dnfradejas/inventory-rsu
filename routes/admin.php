<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Role\RoleController;
use App\Http\Controllers\Admin\User\UserController;
use App\Http\Controllers\Admin\Brand\BrandController;
use App\Http\Controllers\Admin\Login\LoginController;
use App\Http\Controllers\Admin\Order\OrderController;
use App\Http\Controllers\Admin\Product\ProductController;
use App\Http\Controllers\Admin\Category\CategoryController;
use App\Http\Controllers\Admin\Delivery\DeliveryController;
use App\Http\Controllers\Admin\Supplier\SupplierController;
use App\Http\Controllers\Admin\Dashboard\DashboardController;
use App\Http\Controllers\Admin\UnitOfMeasure\UnitOfMeasureController;

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
Route::group(['prefix' => 'admin', 'namespace' => 'Admin'], function($route){

    // Implement fake login later
    $route->group(['prefix' => 'login', 'namespace' => 'Admin'], function($route){
        $route->get('/', [LoginController::class, 'fakeLogin'])->name('admin.get.fake.login');

    });

    $route->group(['prefix' => 'secure/login', 'namespace' => 'Admin'], function($route){
        $route->get('/', [LoginController::class, 'getLogin'])->name('admin.get.secure.login');
        $route->post('/', [LoginController::class, 'postLogin'])->name('admin.post.secure.login');
        $route->get('/out', [LoginController::class, 'getLogout'])->name('admin.get.logout');
        
    });

    $route->group(['middleware' => ['admin.loggedin']], function($route){

        $route->group(['namespace' => 'Dashboard'], function($route){
            $route->get('/', [DashboardController::class, 'listing'])->name('admin.dashboard.listing');
        });
        
        $route->group(['prefix' => 'transaction-histories'], function($route){
            $route->get('/', function(){
                $histories = \App\Models\TransactionHistory::get();
                return view('admin.pages.transaction-histories.listing', [
                    'histories' => $histories
                ]);
            })->name('admin.transaction.histories.listing');
        });
        $route->group(['prefix' => 'product', 'namespace' => 'Product'], function($route){
            $route->get('/', [ProductController::class, 'listing'])->name('admin.product.listing');
            $route->get('/new', [ProductController::class, 'displayForm'])->name('admin.product.display.form');
            $route->get('/{slug}/edit', [ProductController::class, 'displayForm'])->name('admin.product.display.edit.form');
            $route->post('/', [ProductController::class, 'postCreate'])->name('admin.product.post.create');
            $route->delete('/', [ProductController::class, 'postDelete'])->name('admin.product.post.delete');

            $route->post('/check-price', [ProductController::class, 'postCheckPrice'])->name('admin.product.post.check.price');

            $route->get('/{id}/view', [ProductController::class, 'getDetail'])->name('admin.product.view.detail');
            
        });
    
        $route->group(['prefix' => 'brand', 'namespace' => 'Brand'], function($route){
            $route->get('/', [BrandController::class, 'listing'])->name('admin.brand.listing');
            $route->get('/new', [BrandController::class, 'displayForm'])->name('admin.brand.display.form');
            $route->get('/{id}/edit', [BrandController::class, 'displayForm'])->name('admin.brand.display.edit.form');
            $route->post('/', [BrandController::class, 'postCreate'])->name('admin.brand.post.create');
            $route->delete('/', [BrandController::class, 'delete'])->name('admin.brand.delete');
        });
    
        $route->group(['prefix' => 'category', 'namespace' => 'Category'], function($route){
            $route->get('/', [CategoryController::class, 'listing'])->name('admin.category.listing');
            $route->get('/new', [CategoryController::class, 'displayForm'])->name('admin.category.display.form');
            $route->get('/{id}/edit', [CategoryController::class, 'displayForm'])->name('admin.category.display.edit.form');
            $route->post('/', [CategoryController::class, 'postCreate'])->name('admin.category.post.create');
            $route->delete('/', [CategoryController::class, 'postDelete'])->name('admin.category.delete');
        });


        $route->group(['prefix' => 'role', 'namespace' => 'Role'], function($route){
            $route->post('/', [RoleController::class, 'postCreate'])->name('admin.role.post.create');
            $route->post('/permission', [RoleController::class, 'postAttachRoleAndPermission'])->name('admin.post.attach.role.permission');
        });

        $route->group(['prefix' => 'user'], function($route){
            $route->get('/', [UserController::class, 'listing'])->name('admin.user.listing');
            $route->get('/new', [UserController::class, 'displayForm'])->name('admin.user.display.form');
            $route->get('/{id}/edit', [UserController::class, 'displayForm'])->name('admin.user.display.edit.form');
            $route->post('/', [UserController::class, 'postCreate'])->name('admin.user.post.create');
            $route->delete('/{id}', [UserController::class, 'delete'])->name('admin.user.delete');
        });

        $route->group(['prefix' => 'order', 'namespace' => 'Order'], function($route){
            $route->get('/list', [OrderController::class, 'list'])->name('admin.order.listing');
            
            $route->get('/{order_ref}/view', [OrderController::class, 'viewOrderDetail'])->name('admin.order.view.detail');
            $route->get('/{id}/statuses', [OrderController::class, 'getStatuses'])->name('admin.order.get.statuses');
            $route->post('/status/update', [OrderController::class, 'postUpdateStatus'])->name('admin.order.update.status');

            $route->get('/new-order', [OrderController::class, 'newOrder'])->name('admin.order.new');
            $route->post('/cancel', [OrderController::class, 'postCancel'])->name('admin.order.post.cancel');

            $route->group(['prefix' => 'export'], function($route){
                $route->post('/', [OrderController::class, 'postExportOrders'])->name('admin.order.post.export');
            });

            $route->get('/receipt/{orderRef}', [OrderController::class, 'displayReceiptAsPdf'])->name('admin.order.display.receipt.to.pdf');



            /** Process Order routes */ 
            $route->group(['prefix' => 'process'], function($route){
                $route->post('/', [OrderController::class, 'postAddOrder'])->name('admin.order.post.add');
                $route->post('/delete-item', [OrderController::class, 'postDeleteOrderItem'])->name('admin.order.post.delete.item');
                $route->post('/order-now', [OrderController::class, 'postOrderNow'])->name('admin.post.order.now'); // untested
            });
        });

        $route->group(['prefix' => 'delivery', 'namespace' => 'Delivery'], function($route){
            $route->get('/', [DeliveryController::class, 'getListing'])->name('admin.delivery.list');
            $route->post('/', [DeliveryController::class, 'postAddDelivery'])->name('admin.delivery.post.create');
            $route->get('/form-modal/{id?}', [DeliveryController::class, 'getHtmlModalForm'])->name('admin.delivery.get.modal.html.form');

            $route->group(['prefix' => 'expired'], function($route){
                $route->delete('/', [DeliveryController::class, 'deleteExpired'])->name('admin.delivery.expired.delete');
            });
        });

        $route->group(['prefix' => 'unit-of-measure', 'namespace' => 'UnitOfMeasure'], function($route){
            $route->get('/', [UnitOfMeasureController::class, 'listing'])->name('admin.uom.listing');
            $route->get('/new', [UnitOfMeasureController::class, 'displayForm'])->name('admin.uom.display.form');
            $route->get('/{slug}/edit', [UnitOfMeasureController::class, 'displayForm'])->name('admin.uom.display.edit.form');
            $route->post('/', [UnitOfMeasureController::class, 'postCreate'])->name('admin.uom.post.create');
            $route->delete('/', [UnitOfMeasureController::class, 'delete'])->name('admin.uom.delete');
        });


        // Untest
        $route->group(['prefix' => 'suppliers'], function($route){
            $route->get('/', [SupplierController::class, 'listing'])->name('admin.supplier.listing');

            $route->get('/new', [SupplierController::class, 'displayForm'])->name('admin.supplier.display.form');
            $route->get('/{id}/edit', [SupplierController::class, 'displayForm'])->name('admin.supplier.display.edit.form');
            $route->post('/', [SupplierController::class, 'postCreate'])->name('admin.supplier.post.create');
            $route->delete('/', [SupplierController::class, 'delete'])->name('admin.supplier.delete');
            
        });
    });

});