<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CategoryProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;



// 1. BASIC ROUTES
// Route::get('/basic', fn () => 'GET request');
// Route::post('/basic', fn () => 'POST request');
// Route::put('/basic', fn () => 'PUT request');
// Route::patch('/basic', fn () => 'PATCH request');
// Route::delete('/basic', fn () => 'DELETE request');



Route::resource('categories', CategoryController::class);

// 2. ROUTE PARAMETERS
Route::get('/users/{id}', function (int $id) {
    return "User ID: {$id}";
});

Route::get('/users/{id}/posts/{postId?}', function (
    int $id,
    ?int $postId = null
) {
    if ($postId) {
        return "Post #{$postId} milik User #{$id}";
    }
    return "Semua post milik User #{$id}";
});


// 3. ADMIN ROUTES (prefix + named)
Route::prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [AdminController::class, 'index'])
            ->name('dashboard');
        Route::get('/users', fn () => 'Admin Users')
            ->name('users');
    });


// 4. RESOURCE ROUTES
Route::resource('products', ProductController::class);


Route::resource('categories', CategoryController::class)
    ->only(['index', 'store', 'destroy']);


Route::resource('orders', OrderController::class)
    ->except(['create', 'edit']);


// 5. NESTED RESOURCE
Route::resource(
    'categories.products',
    CategoryProductController::class
)->only(['index', 'create', 'store']);


// 6. REDIRECT & FALLBACK
Route::redirect('/old-url', '/new-url', 301);


Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});
