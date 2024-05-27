<?php

use App\Models\ProductColor;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\AuthContoller;
use App\Http\Controllers\ProductSizeController;
use App\Http\Controllers\ProductColorController;
use App\Http\Controllers\Backend\BrandController;
use App\Http\Controllers\Backend\OrderController;
use App\Http\Controllers\Backend\BannerController;
use App\Http\Controllers\Backend\RegionController;
use App\Http\Controllers\Backend\PaymentController;
use App\Http\Controllers\Backend\ProductController;
use App\Http\Controllers\Backend\CategoryController;
use App\Http\Controllers\Backend\CustomerController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Other\ApplicationController;
use App\Http\Controllers\Backend\DeliveryFeeController;
use App\Http\Controllers\Backend\SubCategoryController;
use App\Http\Controllers\OrderSuccessMessageController;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return view('welcome');
})->name('home');

Route::get('/privacy-policy', function () {
    return view('privacy-policy');
})->name('privacyPolicy');

//Auth
Route::get('/bg-admin-login', [AuthContoller::class, 'login'])->name('login');
Route::post('/bg-admin-login', [AuthContoller::class, 'postLogin'])->name('postLogin');

Route::get('/logout', [AuthContoller::class, 'logout'])->name('logout')->middleware('auth');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    //noti
    Route::get('/noti/{id}', [DashboardController::class, 'deleteNoti'])->name('noti.delete');

    //profile
    Route::get('/edit-profile', [AuthContoller::class, 'editProfile'])->name('profile.edit');
    Route::post('/edit-profile', [AuthContoller::class, 'updateProfile'])->name('profile.update');

    //auth
    Route::get('/edit-password', [AuthContoller::class, 'editPassword'])->name('editPassword');
    Route::post('/edit-password', [AuthContoller::class, 'updatePassword'])->name('updatePassword');

    //product colors
    Route::get('/products/colors', [ProductColorController::class, 'index'])->name('product.color');
    Route::get('/products/colors/datatable/ssd', [ProductColorController::class, 'serverSide']);

    Route::get('/products/colors/create', [ProductColorController::class, 'create'])->name('product.color.create');
    Route::post('/products/colors/create', [ProductColorController::class, 'store'])->name('product.color.store');
    Route::get('/products/colors/edit/{product_color}', [ProductColorController::class, 'edit'])->name('product.color.edit');
    Route::put('/products/colors/edit/{product_color}', [ProductColorController::class, 'update'])->name('product.color.update');
    Route::delete('/products/colors/{product_color}', [ProductColorController::class, 'destroy'])->name('product.color.destroy');

    //product sizes
    Route::get('/products/sizes', [ProductSizeController::class, 'index'])->name('product.size');
    Route::get('/products/sizes/datatable/ssd', [ProductSizeController::class, 'serverSide']);

    Route::get('/products/sizes/create', [ProductSizeController::class, 'create'])->name('product.size.create');
    Route::post('/products/sizes/create', [ProductSizeController::class, 'store'])->name('product.size.store');
    Route::get('/products/sizes/edit/{product_size}', [ProductSizeController::class, 'edit'])->name('product.size.edit');
    Route::put('/products/sizes/edit/{product_size}', [ProductSizeController::class, 'update'])->name('product.size.update');
    Route::delete('/products/sizes/{product_size}', [ProductSizeController::class, 'destroy'])->name('product.size.destroy');

    //Products
    Route::get('/products', [ProductController::class, 'listing'])->name('product');
    Route::get('/products/datatable/ssd', [ProductController::class, 'serverSide']);

    Route::get('/products/create', [ProductController::class, 'create'])->name('product.create');
    Route::post('/products', [ProductController::class, 'store'])->name('product.store');
    Route::get('/products/{product}', [ProductController::class, 'detail'])->name('product.detail');
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('product.edit');
    Route::put('/products/{product}/update', [ProductController::class, 'update'])->name('product.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('product.destroy');

    Route::get('product-images/{product}', [ProductController::class, 'images']); // get images from edit

    // Categories
    Route::get('/categories', [CategoryController::class, 'index'])->name('category');
    Route::get('/categories/datatable/ssd', [CategoryController::class, 'serverSide']);

    Route::get('/categories/create', [CategoryController::class, 'create'])->name('category.create');
    Route::post('/categories', [CategoryController::class, 'store'])->name('category.store');
    Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('category.edit');
    Route::put('/categories/{category}/update', [CategoryController::class, 'update'])->name('category.update');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('category.destroy');

    // SubCategories
    Route::get('/subcategories', [SubCategoryController::class, 'index'])->name('subcategory');
    Route::get('/subcategories/datatable/ssd', [SubCategoryController::class, 'serverSide']);
    Route::get('/subcategories/create', [SubCategoryController::class, 'create'])->name('subcategory.create');
    Route::post('/subcategories', [SubCategoryController::class, 'store'])->name('subcategory.store');
    Route::get('/subcategories/{subCategory}/edit', [SubCategoryController::class, 'edit'])->name('subcategory.edit');
    Route::put('/subcategories/{subCategory}/update', [SubCategoryController::class, 'update'])->name('subcategory.update');
    Route::delete('/subcategories/{subCategory}', [SubCategoryController::class, 'destroy'])->name('subcategory.destroy');

    // Brands
    Route::get('/brands', [BrandController::class, 'index'])->name('brand');
    Route::get('/brands/datatable/ssd', [BrandController::class, 'serverSide']);

    Route::get('/brands/create', [BrandController::class, 'create'])->name('brand.create');
    Route::post('/brands', [BrandController::class, 'store'])->name('brand.store');
    Route::get('/brands/{brand}/edit', [BrandController::class, 'edit'])->name('brand.edit');
    Route::put('/brands/{brand}/update', [BrandController::class, 'update'])->name('brand.update');
    Route::delete('/brands/{brand}', [BrandController::class, 'destroy'])->name('brand.destroy');

    //banners
    Route::get('/banners', [BannerController::class, 'index'])->name('banner');
    Route::get('/banners/datatable/ssd', [BannerController::class, 'serverSide']);

    Route::get('/banners/create', [BannerController::class, 'create'])->name('banner.create');
    Route::post('/banners/create', [BannerController::class, 'store'])->name('banner.store');
    Route::get('/banners/edit/{banner}', [BannerController::class, 'edit'])->name('banner.edit');
    Route::post('/banners/edit/{banner}', [BannerController::class, 'update'])->name('banner.update');
    Route::delete('/banners/{banner}', [BannerController::class, 'destroy'])->name('banner.destroy');

    //payments
    Route::get('/payments', [PaymentController::class, 'index'])->name('payment');
    Route::get('/payments/datatable/ssd', [PaymentController::class, 'serverSide']);

    Route::get('/payments/create', [PaymentController::class, 'create'])->name('payment.create');
    Route::post('/payments/create', [PaymentController::class, 'store'])->name('payment.store');
    Route::get('/payments/edit/{payment}', [PaymentController::class, 'edit'])->name('payment.edit');
    Route::post('/payments/edit/{payment}', [PaymentController::class, 'update'])->name('payment.update');
    Route::delete('/payments/{payment}', [PaymentController::class, 'destroy'])->name('payment.destroy');

    //customers
    Route::get('/customers', [CustomerController::class, 'index'])->name('customer');
    Route::get('/customers/{customer}', [CustomerController::class, 'detail'])->name('customer.detail');

    Route::get('/customers/edit/{customer}', [CustomerController::class, 'edit'])->name('customer.edit');
    Route::put('/customers/edit/{customer}', [CustomerController::class, 'update'])->name('customer.update');
    Route::put('/customers/update-password/{customer}', [CustomerController::class, 'updatePassword'])->name('customer.updatePassword');
    Route::post('/customers/ban/{customer}', [CustomerController::class, 'banCustomer'])->name('customer.ban');

    Route::get('/customers/datatable/ssd', [CustomerController::class, 'serverSide']);

    //regions (cash on delivery)
    Route::get('/regions', [RegionController::class, 'index'])->name('region');
    Route::get('/regions/datatable/ssd', [RegionController::class, 'serverSide']);

    Route::get('/regions/create', [RegionController::class, 'create'])->name('region.create');
    Route::post('/regions/create', [RegionController::class, 'store'])->name('region.store');
    Route::get('/regions/edit/{region}', [RegionController::class, 'edit'])->name('region.edit');
    Route::post('/regions/edit/{region}', [RegionController::class, 'update'])->name('region.update');
    Route::delete('/regions/{region}', [RegionController::class, 'destroy'])->name('region.destroy');

    //delivery fee
    Route::get('/delivery-fees', [DeliveryFeeController::class, 'index'])->name('deliveryfee');
    Route::get('/delivery-fees/datatable/ssd', [DeliveryFeeController::class, 'serverSide']);
    Route::get('/delivery-fees/create', [DeliveryFeeController::class, 'create'])->name('deliveryfee.create');
    Route::post('/delivery-fees/create', [DeliveryFeeController::class, 'store'])->name('deliveryfee.store');
    Route::get('/delivery-fees/edit/{delivery_fee}', [DeliveryFeeController::class, 'edit'])->name('deliveryfee.edit');
    Route::post('/delivery-fees/edit/{delivery_fee}', [DeliveryFeeController::class, 'update'])->name('deliveryfee.update');
    Route::delete('/delivery-fees/{delivery_fee}', [DeliveryFeeController::class, 'destroy'])->name('deliveryfee.destroy');

    //orders
    Route::get('/orders', [OrderController::class, 'index'])->name('order');
    Route::get('/orders/status/{status}', [OrderController::class, 'orderByStatus'])->name('orderByStatus');

    Route::post('/orders/{order}', [OrderController::class, 'updateStatus'])->name('order.updateStatus');
    Route::get('/orders/cancel/{order}', [OrderController::class, 'cancelOrder'])->name('order.cancel');
    Route::post('/orders/cancel/{order}', [OrderController::class, 'saveCancelOrder'])->name('order.saveCancel');

    Route::get('/orders/refund/all', [OrderController::class, 'refundOrderList'])->name('order.refund.list');
    Route::get('/orders/refund/{order}', [OrderController::class, 'refundOrder'])->name('order.refund');
    Route::post('/orders/refund/{order}', [OrderController::class, 'saveRefundOrder'])->name('order.saveRefund');

    Route::get('/orders/deliver/{order}', [OrderController::class, 'deliverOrder'])->name('order.deliver');
    Route::post('/orders/deliver/{order}', [OrderController::class, 'saveDeliverOrder'])->name('order.saveDeliver');

    Route::get('/orders/{order}/{notiId?}', [OrderController::class, 'detail'])->name('order.detail');

    Route::get('/all-orders/datatable/ssd', [OrderController::class, 'getAllOrder']);
    Route::get('/refund-orders/datatable/ssd', [OrderController::class, 'getRefundList']);
    Route::get('/orders/{status}/datatable/ssd', [OrderController::class, 'getOrderByStatus']);

    //order success message
    Route::get('/order-success-messages', [OrderSuccessMessageController::class, 'index'])->name('orderSuccessMessage');
    Route::get('/order-success-messages/datatable/ssd', [OrderSuccessMessageController::class, 'serverSide']);

    Route::get('/order-success-messages/create', [OrderSuccessMessageController::class, 'create'])->name('orderSuccessMessage.create');
    Route::post('/order-success-messages/create', [OrderSuccessMessageController::class, 'store'])->name('orderSuccessMessage.store');
    Route::get('/order-success-messages/edit/{order_success_message}', [OrderSuccessMessageController::class, 'edit'])->name('orderSuccessMessage.edit');
    Route::post('/order-success-messages/edit/{order_success_message}', [OrderSuccessMessageController::class, 'update'])->name('orderSuccessMessage.update');
    Route::delete('/order-success-messages/{order_success_message}', [OrderSuccessMessageController::class, 'destroy'])->name('orderSuccessMessage.destroy');
});

Route::get('image/{filename}', [ApplicationController::class, 'image'])->where('filename', '.*');
