<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\ItemController;
use Illuminate\Support\Facades\Redirect;

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

// Route::get('/', function () {
    
//     return Inertia::render('Welcome', [
//         'canLogin' => Route::has('login'),
//         'canRegister' => Route::has('register'),
//         'laravelVersion' => Application::VERSION,
//         'phpVersion' => PHP_VERSION,
//     ]);
// });

Route::get('/', function () {
    // Check if the user is authenticated
    if (!auth()->check()) {
        // Redirect to the login page
        return redirect()->route('login');
    }

    // Render the Inertia view for authenticated users
    return Inertia::render('Item/Index');
})->name('home');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::middleware([
    'auth',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    // Admin
    Route::get('/admin/items', [ItemController::class, 'admin_item'])->name('admin.index');
    Route::get('/admin/filter', [ItemController::class, 'filter'])->name('admin.filter');
    Route::post('/admin/filter', [ItemController::class, 'filter'])->name('admin.filter');
    Route::get('/admin/create', [ItemController::class, 'create'])->name('admin.create');
    Route::post('/admin/create', [ItemController::class, 'create'])->name('admin.create');
    Route::post('/admin/store', [ItemController::class, 'store'])->name('admin.store');
    Route::get('/admin/edit/{id}', [ItemController::class, 'edit'])->name('admin.edit');
    Route::post('/admin/edit/{id}', [ItemController::class, 'edit'])->name('admin.edit');
    Route::put('/admin/update/{id}', [ItemController::class, 'update'])->name('admin.update');
    Route::get('/admin/delete/{id}', [ItemController::class, 'delete'])->name('admin.delete');
    Route::delete('/admin/delete/{id}', [ItemController::class, 'delete'])->name('admin.delete');


    // User
    //Route::get('/items', [ItemController::class, 'index'])->name('items.index');
    // Route::get('/items/filter', [ItemController::class, 'filter'])->name('items.filter');
    // Route::post('/items/filter', [ItemController::class, 'filter'])->name('items.filter');

    Route::get('/items', function (){
        return Inertia::render('Item/Index');
    })->name('items.index');

    Route::get('/items/filter', function (){
        return Inertia::render('Item/Index');
    })->name('items.filter');


    Route::get('/items/view/{id}', [ItemController::class, 'view'])->name('items.view');

    Route::get('/items/create', [ItemController::class, 'create'])->name('items.create');
    Route::post('/items/create', [ItemController::class, 'create'])->name('items.create');
    Route::post('/items/store', [ItemController::class, 'store'])->name('items.store');
    
    Route::get('/items/edit/{id}', [ItemController::class, 'edit'])->name('items.edit');
    Route::post('/items/edit/{id}', [ItemController::class, 'edit'])->name('items.edit');
    Route::put('/items/update/{id}', [ItemController::class, 'update'])->name('items.update');
    
    Route::get('/items/delete/{id}', [ItemController::class, 'delete'])->name('items.delete');
    Route::delete('/items/delete/{id}', [ItemController::class, 'delete'])->name('items.delete');

    Route::get('/items/explore', [ItemController::class, 'explore'])->name('items.explore');
    Route::get('/items/explore/filter', [ItemController::class, 'filter'])->name('items.filter');
    Route::post('/items/explore/filter', [ItemController::class, 'filter'])->name('items.filter');


});



