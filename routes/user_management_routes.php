// =====================================
// USER MANAGEMENT ROUTES (SUPERADMIN ONLY)
// =====================================
Route::middleware(['auth', 'role:superadmin'])->prefix('admin/users')->name('admin.users.')->group(function () {
    Route::get('/', [App\Http\Controllers\Admin\UserManagementController::class, 'index'])->name('index');
    Route::get('/create', [App\Http\Controllers\Admin\UserManagementController::class, 'create'])->name('create');
    Route::post('/', [App\Http\Controllers\Admin\UserManagementController::class, 'store'])->name('store');
    Route::get('/{user}/edit', [App\Http\Controllers\Admin\UserManagementController::class, 'edit'])->name('edit');
    Route::put('/{user}', [App\Http\Controllers\Admin\UserManagementController::class, 'update'])->name('update');
    Route::delete('/{user}', [App\Http\Controllers\Admin\UserManagementController::class, 'destroy'])->name('destroy');
    Route::post('/{user}/restore', [App\Http\Controllers\Admin\UserManagementController::class, 'restore'])->name('restore');
});
