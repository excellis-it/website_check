<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\ForgetPasswordController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\SellerController;
use App\Http\Controllers\Admin\UrlManagementController;
use App\Http\Controllers\Admin\RolePermissionController;
use App\Http\Controllers\Frontend\HomeController;
use Illuminate\Support\Facades\Artisan;

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

// Clear cache
Route::get('clear', function () {
    Artisan::call('optimize:clear');
    return "Optimize clear has been successfully";
});

Route::get('check-url-status', function(){
    Artisan::call('urls:check');
    return "URL status check has been successfully";
});

Route::get('/', [AuthController::class, 'redirectAdminLogin']);
Route::get('/admin/login', [AuthController::class, 'login'])->name('admin.login');
Route::post('/login-check', [AuthController::class, 'loginCheck'])->name('admin.login.check');  //login check
Route::post('forget-password', [ForgetPasswordController::class, 'forgetPassword'])->name('admin.forget.password');
Route::post('change-password', [ForgetPasswordController::class, 'changePassword'])->name('admin.change.password');
Route::get('forget-password/show', [ForgetPasswordController::class, 'forgetPasswordShow'])->name('admin.forget.password.show');
Route::get('reset-password/{id}/{token}', [ForgetPasswordController::class, 'resetPassword'])->name('admin.reset.password');
Route::post('change-password', [ForgetPasswordController::class, 'changePassword'])->name('admin.change.password');

Route::group(['middleware' => ['admin'], 'prefix' => 'admin'], function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('profile', [ProfileController::class, 'index'])->name('admin.profile');
    Route::post('profile/update', [ProfileController::class, 'profileUpdate'])->name('admin.profile.update');
    Route::get('logout', [AuthController::class, 'logout'])->name('admin.logout');

    Route::prefix('password')->group(function () {
        Route::get('/', [ProfileController::class, 'password'])->name('admin.password'); // password change
        Route::post('/update', [ProfileController::class, 'passwordUpdate'])->name('admin.password.update'); // password update
    });

    Route::resources([
        'customers' => CustomerController::class,
    ]);
    //  Customer Routes
    Route::prefix('customers')->group(function () {
        Route::get('/customer-delete/{id}', [CustomerController::class, 'delete'])->name('customers.delete');
    });
    Route::get('/changeCustomerStatus', [CustomerController::class, 'changeCustomersStatus'])->name('customers.change-status');
    Route::get('/customer-fetch-data', [CustomerController::class, 'fetchData'])->name('customers.fetch-data');

    // URL Management Routes
    Route::resource('url-management', UrlManagementController::class)->parameters([
        'url-management' => 'encryptedId'
    ]);
    Route::prefix('url-management')->group(function () {
        Route::get('/{encryptedId}/delete', [UrlManagementController::class, 'destroy'])->name('url-management.delete');
        Route::post('/{encryptedId}/check', [UrlManagementController::class, 'checkUrl'])->name('url-management.check');
    });
    Route::get('/url-management-fetch-data', [UrlManagementController::class, 'fetchData'])->name('url-management.fetch-data');

    // Role & Permission Management Routes
    Route::resource('roles', RolePermissionController::class);
    Route::get('/roles/{id}/delete', [RolePermissionController::class, 'destroy'])->name('roles.delete');
    Route::get('/roles-fetch-data', [RolePermissionController::class, 'fetchData'])->name('roles.fetch-data');

    // Permission Routes
    Route::prefix('permissions')->group(function () {
        Route::get('/', [RolePermissionController::class, 'permissionsIndex'])->name('permissions.index');
        Route::get('/create', [RolePermissionController::class, 'permissionsCreate'])->name('permissions.create');
        Route::post('/', [RolePermissionController::class, 'permissionsStore'])->name('permissions.store');
        Route::get('/{id}/edit', [RolePermissionController::class, 'permissionsEdit'])->name('permissions.edit');
        Route::put('/{id}', [RolePermissionController::class, 'permissionsUpdate'])->name('permissions.update');
        Route::get('/{id}/delete', [RolePermissionController::class, 'permissionsDestroy'])->name('permissions.delete');
    });
    Route::get('/permissions-fetch-data', [RolePermissionController::class, 'permissionsFetchData'])->name('permissions.fetch-data');
});
