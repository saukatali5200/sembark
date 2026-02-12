<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/', [App\Http\Controllers\frontend\HomeController::class, 'index'])->name('front.home');

Route::group(['prefix' => 'adminpnlx'], function () {
  Route::any('/',[App\Http\Controllers\adminpnlx\AdminLoginController::class, 'login'])->name('Auth.login')->middleware("throttle:50,1");
  
  Route::group(['middleware' => 'AuthAdmin'], function () {
    Route::get('/logout', [App\Http\Controllers\adminpnlx\DashboardController::class, 'logout'])->name('Admin.logout');
    Route::get('/dashboard', [App\Http\Controllers\adminpnlx\DashboardController::class, 'index'])->name('Admin.dashboard');

  // User
  Route::get('/users', [App\Http\Controllers\adminpnlx\UserController::class, 'index'])->name('User.index');
  Route::get('/users/list', [App\Http\Controllers\adminpnlx\UserController::class, 'getUsers'])->name('User.list');
  Route::get('/users/view/{id}', [App\Http\Controllers\adminpnlx\UserController::class, 'show'])->name('User.show');
  Route::get('/users/create', [App\Http\Controllers\adminpnlx\UserController::class, 'create'])->name('User.create');
  Route::post('/users/store', [App\Http\Controllers\adminpnlx\UserController::class, 'store'])->name('User.store');
  Route::get('/users/edit/{id}', [App\Http\Controllers\adminpnlx\UserController::class, 'edit'])->name('User.edit');
  Route::post('/users/update/{id}', [App\Http\Controllers\adminpnlx\UserController::class, 'update'])->name('User.update');
  Route::get('/users/delete/{id}', [App\Http\Controllers\adminpnlx\UserController::class, 'destroy'])->name('User.destroy');

  // Staff 
  Route::get('/staff', [App\Http\Controllers\adminpnlx\StaffController::class, 'index'])->name('Staff.index');
  Route::get('/staff-list', [App\Http\Controllers\adminpnlx\StaffController::class, 'list'])->name('Staff.list');
  Route::get('/staff/view/{id}', [App\Http\Controllers\adminpnlx\StaffController::class, 'show'])->name('Staff.show');
  Route::get('/staff/create', [App\Http\Controllers\adminpnlx\StaffController::class, 'create'])->name('Staff.create');
  Route::post('/staff/store', [App\Http\Controllers\adminpnlx\StaffController::class, 'store'])->name('Staff.store');
  Route::get('/staff/edit/{id}', [App\Http\Controllers\adminpnlx\StaffController::class, 'edit'])->name('Staff.edit');
  Route::post('/staff/update/{id}', [App\Http\Controllers\adminpnlx\StaffController::class, 'update'])->name('Staff.update');
  Route::get('/staff/delete/{id}', [App\Http\Controllers\adminpnlx\StaffController::class, 'destroy'])->name('Staff.destroy');
  
  /**  Role routes **/
  Route::get('/roles', [App\Http\Controllers\adminpnlx\RoleController::class, 'index'])->name('Role.index');
  Route::get('/roles-list', [App\Http\Controllers\adminpnlx\RoleController::class, 'list'])->name('Role.list');
  Route::get('/roles/view/{id}', [App\Http\Controllers\adminpnlx\RoleController::class, 'show'])->name('Role.show');
  Route::get('/roles/create', [App\Http\Controllers\adminpnlx\RoleController::class, 'create'])->name('Role.create');
  Route::post('/roles/store', [App\Http\Controllers\adminpnlx\RoleController::class, 'store'])->name('Role.store');
  Route::get('/roles/edit/{id}', [App\Http\Controllers\adminpnlx\RoleController::class, 'edit'])->name('Role.edit');
  Route::post('/roles/update/{id}', [App\Http\Controllers\adminpnlx\RoleController::class, 'update'])->name('Role.update');
  Route::get('/roles/destroy/{id}', [App\Http\Controllers\adminpnlx\RoleController::class, 'destroy'])->name('Role.destroy');
  Route::get('/roles/permissions/{roleID}', [App\Http\Controllers\adminpnlx\RoleController::class, 'permissions'])->name('Role.permissions');
  Route::post('/roles/permissions/{roleID}', [App\Http\Controllers\adminpnlx\RoleController::class, 'savePermissions'])->name('Role.savePermissions');

  /**  Acl routes **/
  Route::get('/acls', [App\Http\Controllers\adminpnlx\AclController::class, 'index'])->name('Acl.index');
  Route::get('/acls-list', [App\Http\Controllers\adminpnlx\AclController::class, 'list'])->name('Acl.list');
  Route::get('/acls/view/{id}', [App\Http\Controllers\adminpnlx\AclController::class, 'show'])->name('Acl.show');
  Route::get('/acls/create', [App\Http\Controllers\adminpnlx\AclController::class, 'create'])->name('Acl.create');
  Route::post('/acls/store', [App\Http\Controllers\adminpnlx\AclController::class, 'store'])->name('Acl.store');
  Route::get('/acls/edit/{id}', [App\Http\Controllers\adminpnlx\AclController::class, 'edit'])->name('Acl.edit');
  Route::post('/acls/update/{id}', [App\Http\Controllers\adminpnlx\AclController::class, 'update'])->name('Acl.update');
  Route::get('/acls/destroy/{id}', [App\Http\Controllers\adminpnlx\AclController::class, 'destroy'])->name('Acl.destroy');

  });

});