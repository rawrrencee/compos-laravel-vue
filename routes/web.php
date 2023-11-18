<?php

use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommonController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use \App\Http\Controllers\CompanyController;
use App\Http\Controllers\EmployeeController;
use \App\Http\Controllers\StoreController;
use \App\Http\Controllers\SupplierController;

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

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/404', function () {
    return Inertia::render('Error/404');
})->name('404');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/photo', [CommonController::class, 'showPhoto'])->name('photo');

    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::prefix('sales')->group(function () {
        Route::get('/', function () {
            return Inertia::render('Error/404');
        })->name('sales');

        Route::get('/pos', function () {
            return Inertia::render('Error/404');
        })->name('sales/pos');

        Route::get('/transactions', function () {
            return Inertia::render('Error/404');
        })->name('sales/transactions');
    });

    Route::prefix('products')->group(function () {
        Route::get('/', function () {
            return Inertia::render('Error/404');
        })->name('products');

        Route::get('/stockcheck', function () {
            return Inertia::render('Error/404');
        })->name('products/stockcheck');

        Route::get('/stocktake', function () {
            return Inertia::render('Error/404');
        })->name('products/stocktake');

        Route::get('/delivery-orders', function () {
            return Inertia::render('Error/404');
        })->name('products/delivery-orders');
    });

    Route::get('/salaries', function () {
        return Inertia::render('Error/404');
    })->name('salaries');

    Route::get('/timecards', function () {
        return Inertia::render('Error/404');
    })->name('timecards');

    Route::prefix('admin')->group(function () {
        Route::prefix('analytics')->group(function () {
            Route::get('/', function () {
                return Inertia::render('Error/404');
            })->name('admin/analytics');

            Route::get('/employees', function () {
                return Inertia::render('Error/404');
            })->name('admin/analytics/employees');

            Route::get('/items', function () {
                return Inertia::render('Error/404');
            })->name('admin/analytics/items');

            Route::get('/sales', function () {
                return Inertia::render('Error/404');
            })->name('admin/analytics/sales');
        });

        Route::get('/inventory-logs', function () {
            return Inertia::render('Error/404');
        })->name('admin/inventory-logs');

        Route::prefix('salary-reports')->group(function () {
            Route::get('/', function () {
                return Inertia::render('Error/404');
            })->name('admin/salary-reports');

            Route::get('/monthly', function () {
                return Inertia::render('Error/404');
            })->name('admin/salary-reports/monthly');

            Route::get('/yearly', function () {
                return Inertia::render('Error/404');
            })->name('admin/salary-reports/yearly');
        });

        Route::prefix('infrastructure')->group(function () {
            Route::get('/', function () {
                return Inertia::render('Error/404');
            })->name('admin/infrastructure');

            Route::prefix('companies')->group(function () {
                Route::get('/', [CompanyController::class, 'index'])->name('admin/infrastructure/companies');

                Route::get('/add', function () {
                    return Inertia::render('Admin/Infrastructure/Companies/AddOrEditCompany');
                })->name('admin/infrastructure/companies/add');
                Route::post('/add', [CompanyController::class, 'store'])->name('admin/infrastructure/companies/add.store');

                Route::get('/edit', [CompanyController::class, 'edit'])->name('admin/infrastructure/companies/edit');
                Route::post('/edit', [CompanyController::class, 'update'])->name('admin/infrastructure/companies/edit.update');
                Route::post('/edit-bulk', [CompanyController::class, 'bulkUpdate'])->name('admin/infrastructure/companies/edit.bulk');

                Route::get('/view', [CompanyController::class, 'view'])->name('admin/infrastructure/companies/view');
                Route::post('/delete-photo', [CompanyController::class, 'deletePhoto'])->name('admin/infrastructure/companies/photo.delete');

                Route::post('/import', [CompanyController::class, 'importCsv'])->name('admin/infrastructure/companies/import');
                Route::post('/export', [CompanyController::class, 'exportCsv'])->name('admin/infrastructure/companies/export');

                Route::post('/delete', [CompanyController::class, 'destroy'])->name('admin/infrastructure/companies/delete');
            });

            Route::prefix('stores')->group(function () {
                Route::get('/', [StoreController::class, 'index'])->name('admin/infrastructure/stores');

                Route::get('/add', [StoreController::class, 'add'])->name('admin/infrastructure/stores/add');
                Route::post('/add', [StoreController::class, 'store'])->name('admin/infrastructure/stores/add.store');

                Route::get('/edit', [StoreController::class, 'edit'])->name('admin/infrastructure/stores/edit');
                Route::post('/edit', [StoreController::class, 'update'])->name('admin/infrastructure/stores/edit.update');
                Route::post('/edit-bulk', [StoreController::class, 'bulkUpdate'])->name('admin/infrastructure/stores/edit.bulk');

                Route::get('/view', [StoreController::class, 'view'])->name('admin/infrastructure/stores/view');
                Route::post('/delete-photo', [StoreController::class, 'deletePhoto'])->name('admin/infrastructure/stores/photo.delete');

                Route::post('/import', [StoreController::class, 'importCsv'])->name('admin/infrastructure/stores/import');
                Route::post('/export', [StoreController::class, 'exportCsv'])->name('admin/infrastructure/stores/export');

                Route::post('/delete', [StoreController::class, 'destroy'])->name('admin/infrastructure/stores/delete');
            });

            Route::prefix('suppliers')->group(function () {
                Route::get('/', [SupplierController::class, 'index'])->name('admin/infrastructure/suppliers');

                Route::get('/add', function () {
                    return Inertia::render('Admin/Infrastructure/Suppliers/AddOrEditSupplier');
                })->name('admin/infrastructure/suppliers/add');
                Route::post('/add', [SupplierController::class, 'store'])->name('admin/infrastructure/suppliers/add.store');

                Route::get('/edit', [SupplierController::class, 'edit'])->name('admin/infrastructure/suppliers/edit');
                Route::post('/edit', [SupplierController::class, 'update'])->name('admin/infrastructure/suppliers/edit.update');
                Route::post('/edit-bulk', [SupplierController::class, 'bulkUpdate'])->name('admin/infrastructure/suppliers/edit.bulk');

                Route::get('/view', [SupplierController::class, 'view'])->name('admin/infrastructure/suppliers/view');
                Route::post('/delete-photo', [SupplierController::class, 'deletePhoto'])->name('admin/infrastructure/suppliers/photo.delete');

                Route::post('/import', [SupplierController::class, 'importCsv'])->name('admin/infrastructure/suppliers/import');
                Route::post('/export', [SupplierController::class, 'exportCsv'])->name('admin/infrastructure/suppliers/export');

                Route::post('/delete', [SupplierController::class, 'destroy'])->name('admin/infrastructure/suppliers/delete');
            });
        });

        Route::prefix('commerce')->group(function () {
            Route::get('/', function () {
                return Inertia::render('Error/404');
            })->name('admin/commerce');

            Route::prefix('brands')->group(function () {
                Route::get('/', [BrandController::class, 'index'])->name('admin/commerce/brands');

                Route::get('/add', function () {
                    return Inertia::render('Admin/Commerce/Brands/AddOrEditBrand');
                })->name('admin/commerce/brands/add');
                Route::post('/add', [BrandController::class, 'store'])->name('admin/commerce/brands/add.store');

                Route::get('/edit', [BrandController::class, 'edit'])->name('admin/commerce/brands/edit');
                Route::post('/edit', [BrandController::class, 'update'])->name('admin/commerce/brands/edit.update');
                Route::post('/edit-bulk', [BrandController::class, 'bulkUpdate'])->name('admin/commerce/brands/edit.bulk');

                Route::get('/view', [BrandController::class, 'view'])->name('admin/commerce/brands/view');
                Route::post('/delete-photo', [BrandController::class, 'deletePhoto'])->name('admin/commerce/brands/photo.delete');

                Route::post('/import', [BrandController::class, 'importCsv'])->name('admin/commerce/brands/import');
                Route::post('/export', [BrandController::class, 'exportCsv'])->name('admin/commerce/brands/export');

                Route::post('/delete', [BrandController::class, 'destroy'])->name('admin/commerce/brands/delete');
            });

            Route::prefix('categories')->group(function () {
                Route::get('/', [CategoryController::class, 'index'])->name('admin/commerce/categories');

                Route::get('/add', function () {
                    return Inertia::render('Admin/Commerce/Categories/AddOrEditCategory');
                })->name('admin/commerce/categories/add');
                Route::post('/add', [CategoryController::class, 'store'])->name('admin/commerce/categories/add.store');

                Route::get('/edit', [CategoryController::class, 'edit'])->name('admin/commerce/categories/edit');
                Route::post('/edit', [CategoryController::class, 'update'])->name('admin/commerce/categories/edit.update');
                Route::post('/edit-bulk', [CategoryController::class, 'bulkUpdate'])->name('admin/commerce/categories/edit.bulk');

                Route::get('/view', [CategoryController::class, 'view'])->name('admin/commerce/categories/view');
                Route::post('/delete-photo', [CategoryController::class, 'deletePhoto'])->name('admin/commerce/categories/photo.delete');

                Route::post('/import', [CategoryController::class, 'importCsv'])->name('admin/commerce/categories/import');
                Route::post('/export', [CategoryController::class, 'exportCsv'])->name('admin/commerce/categories/export');

                Route::post('/delete', [CategoryController::class, 'destroy'])->name('admin/commerce/categories/delete');
            });

            Route::get('/items', function () {
                return Inertia::render('Error/404');
            })->name('admin/commerce/items');

            Route::get('/item-kits', function () {
                return Inertia::render('Error/404');
            })->name('admin/commerce/item-kits');

            Route::get('/stocktake', function () {
                return Inertia::render('Error/404');
            })->name('admin/commerce/stocktake');

            Route::get('/transactions', function () {
                return Inertia::render('Error/404');
            })->name('admin/commerce/transactions');
        });

        Route::prefix('users')->group(function () {
            Route::get('/', function () {
                return Inertia::render('Admin/Users/Users');
            })->name('admin/users');

            Route::get('/permissions', function () {
                return Inertia::render('Error/404');
            })->name('admin/users/permissions');

            Route::prefix('employees')->group(function () {
                Route::get('/', function () {
                    return Inertia::render('Admin/Users/Employees/Overview');
                })->name('admin/users/employees');
                Route::get('/add', function () {
                    return Inertia::render('Admin/Users/Employees/AddNewEmployee');
                })->name('admin/users/employees/add');

                Route::get('/salaries', function () {
                    return Inertia::render('Error/404');
                })->name('admin/users/employees/salaries');

                Route::get('/timecards', function () {
                    return Inertia::render('Error/404');
                })->name('admin/users/employees/timecards');

                Route::get('/designations', function () {
                    return Inertia::render('Error/404');
                })->name('admin/users/employees/designations');

                Route::get('/permissions', function () {
                    return Inertia::render('Error/404');
                })->name('admin/users/employees/permissions');

                Route::get('/requests', [EmployeeController::class, 'employeeRequestsPage'])->name('admin/users/employees/requests');
                Route::post('/updateEmployeeRequestKey', [EmployeeController::class, 'updateEmployeeRequestKey'])->name('admin/users/employees/requests.key-update');
            });

            Route::get('/customers', function () {
                return Inertia::render('Error/404');
            })->name('admin/users/customers');
        });
    });
});

// Route::fallback(function () {
//     return Inertia::render('Error/404');
// });
