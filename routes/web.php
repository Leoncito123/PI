<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\MapController;
use App\Http\Controllers\OutputController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SensController;
use App\Http\Controllers\TypeController;
use App\Http\Controllers\UbicationController;
use App\Http\Controllers\UserController;
use App\Models\Device;
use App\Models\Type;
use Illuminate\Support\Facades\Route;



Route::get('/', function () {
  return view('welcome');
})->name('welcome');



Route::group(['middleware' => ['role:admin|supervisor']], function () {
  //Ruta de dashboard
  Route::get('/dasboard', [DashboardController::class, 'index'])->name('dashboard');

  //Rutas para el apartado de administración de items

  Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
  Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
  Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
  Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
  Route::get('/admin/ubications', [UbicationController::class, 'index'])->name('admin.ubications');
  Route::get('/admin/sens', [SensController::class, 'index'])->name('admin.sens');
  Route::get('/admin/sensType', [TypeController::class, 'index'])->name('admin.sensType');
  Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users');
  Route::get('/admin/outputs', [OutputController::class, 'index'])->name('admin.outputs');
  Route::get('/admin/devices', [DeviceController::class, 'index'])->name('admin.devices');
  Route::get('/admin/infoUbication', [UbicationController::class, 'infoUbication'])->name('admin.infoUbication');

  //Rutas para la vista de creación de items
  Route::get('/admin/create/sens', [SensController::class, 'createView'])->name('admin.create.sens');
  Route::get('/admin/create/type', [TypeController::class, 'createView'])->name('admin.create.type');
  Route::get('/admin/create/user', [UserController::class, 'createView'])->name('admin.create.user');
  Route::get('/admin/create/output', [OutputController::class, 'createView'])->name('admin.create.output');
  Route::get('/admin/create/device', [DeviceController::class, 'createView'])->name('admin.create.device');
  Route::get('/admin/create/ubication', [UbicationController::class, 'createView'])->name('admin.create.ubication');


  //Rutas para la creación de items
  Route::post('/admin/creates/type', [TypeController::class, 'create'])->name('admin.creates.types');
  Route::post('/admin/creates/sens', [SensController::class, 'create'])->name('admin.creates.sens');
  Route::post('/admin/creates/user', [UserController::class, 'create'])->name('admin.creates.users');
  Route::post('/admin/creates/output', [OutputController::class, 'create'])->name('admin.creates.outputs');
  Route::post('/admin/creates/device', [DeviceController::class, 'create'])->name('admin.creates.devices');
  Route::post('/admin/creates/ubication', [UbicationController::class, 'create'])->name('admin.creates.ubications');

  //Rutas para la vista de edición de items
  Route::get('/admin/edit/type/{id}', [TypeController::class, 'editView'])->name('admin.edit.type');
  Route::get('/admin/edit/sens/{id}', [SensController::class, 'editView'])->name('admin.edit.sens');
  Route::get('/admin/edit/user/{id}', [UserController::class, 'editView'])->name('admin.edit.user');
  Route::get('/admin/edit/output/{id}', [OutputController::class, 'editView'])->name('admin.edit.output');
  Route::get('/admin/edit/device/{id}', [DeviceController::class, 'editView'])->name('admin.edit.device');
  Route::get('/admin/edit/ubication/{id}', [UbicationController::class, 'editView'])->name('admin.edit.ubication');


  //Rutas para la edición de items
  Route::post('/admin/edits/type/{id}', [TypeController::class, 'edit'])->name('admin.edits.type');
  Route::post('/admin/edits/sens/{id}', [SensController::class, 'edit'])->name('admin.edits.sens');
  Route::post('/admin/edits/user/{id}', [UserController::class, 'edit'])->name('admin.edits.user');
  Route::post('/admin/edits/output/{id}', [OutputController::class, 'edit'])->name('admin.edits.output');
  Route::post('/admin/edits/device/{id}', [DeviceController::class, 'edit'])->name('admin.edits.device');
  Route::post('/admin/edits/ubication/{id}', [UbicationController::class, 'edit'])->name('admin.edits.ubication');

  //Rutas para la eliminación de items
  Route::delete('/admin/delete/type/{id}', [TypeController::class, 'destroy'])->name('admin.delete.type');
  Route::delete('/admin/delete/sens/{id}', [SensController::class, 'destroy'])->name('admin.delete.sens');
  Route::delete('/admin/delete/summary/{id}', [SensController::class, 'destroySummary'])->name('admin.delete.summary');
  Route::delete('/admin/delete/user/{id}', [UserController::class, 'destroy'])->name('admin.delete.user');
  Route::delete('/admin/delete/output/{id}', [OutputController::class, 'destroy'])->name('admin.delete.output');
  Route::delete('/admin/delete/device/{id}', [DeviceController::class, 'destroy'])->name('admin.delete.device');
  Route::delete('/admin/delete/ubication/{id}', [UbicationController::class, 'destroy'])->name('admin.delete.ubication');
  //Fin de las rutas de administración
});

//Rutas para todos los roles 
Route::group(['middleware' => ['role:admin|supervisor|worker']], function () {
  Route::get('/map', [MapController::class, 'index'])->name('map');
});

require __DIR__ . '/auth.php';
