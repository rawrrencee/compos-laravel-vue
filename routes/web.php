<?php

use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommonController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use \App\Http\Controllers\CompanyController;
use App\Http\Controllers\EmployeeRequestController;
use \App\Http\Controllers\StoreController;
use \App\Http\Controllers\SupplierController;
use App\Http\Controllers\UnauthenticatedController;

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
//     return Inertia::render('Unauthenticated/Login', [
//         'canLogin' => Route::has('login'),
//         'canRegister' => Route::has('register'),
//         'laravelVersion' => Application::VERSION,
//         'phpVersion' => PHP_VERSION,
//     ]);
// });

Route::get('/', [UnauthenticatedController::class, 'loginPage'])->name('unauth.employees.login');

Route::get('/404', function () {
    return Inertia::render('Error/404');
})->name('404');

Route::prefix('register')->group(function () {
    Route::prefix('employee')->group(function () {
        Route::get('/', [UnauthenticatedController::class, 'viewRegisterEmployeePage'])->name('unauth.employees.register.view');
        Route::get('/success', [UnauthenticatedController::class, 'viewRegistrationSuccessfulPage'])->name('unauth.employees.register.success');

        Route::post('/submit', [UnauthenticatedController::class, 'submitEmployeeRegistration'])->name('unauth.employees.register.submit');
    });
});

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
            })->name('infrastructure');

            Route::prefix('companies')->group(function () {
                Route::get('/', [CompanyController::class, 'index'])->name('infrastructure.companies.viewLandingPage');
                Route::get('/add', function () {
                    return Inertia::render('Admin/Infrastructure/Companies/AddOrEditCompany');
                })->name('infrastructure.companies.viewCreatePage');
                Route::get('/edit', [CompanyController::class, 'edit'])->name('infrastructure.companies.viewEditPageById');
                Route::get('/view', [CompanyController::class, 'view'])->name('infrastructure.companies.viewReadOnlyPageById');

                Route::post('/add', [CompanyController::class, 'store'])->name('infrastructure.companies.create');
                Route::post('/delete-photo', [CompanyController::class, 'deletePhoto'])->name('infrastructure.companies.photo.delete');
                Route::post('/delete', [CompanyController::class, 'destroy'])->name('infrastructure.companies.delete');
                Route::post('/edit-bulk', [CompanyController::class, 'bulkUpdate'])->name('infrastructure.companies.bulkUpdate');
                Route::post('/edit', [CompanyController::class, 'update'])->name('infrastructure.companies.update');
                Route::post('/export', [CompanyController::class, 'exportCsv'])->name('infrastructure.companies.export');
                Route::post('/import', [CompanyController::class, 'importCsv'])->name('infrastructure.companies.import');
            });

            Route::prefix('stores')->group(function () {
                Route::get('/', [StoreController::class, 'index'])->name('infrastructure.stores.viewLandingPage');
                Route::get('/add', [StoreController::class, 'add'])->name('infrastructure.stores.viewCreatePage');
                Route::get('/edit', [StoreController::class, 'edit'])->name('infrastructure.stores.viewEditPageById');
                Route::get('/view', [StoreController::class, 'view'])->name('infrastructure.stores.viewReadOnlyPageById');

                Route::post('/add', [StoreController::class, 'store'])->name('infrastructure.stores.create');
                Route::post('/delete-photo', [StoreController::class, 'deletePhoto'])->name('infrastructure.stores.photo.delete');
                Route::post('/delete', [StoreController::class, 'destroy'])->name('infrastructure.stores.delete');
                Route::post('/edit-bulk', [StoreController::class, 'bulkUpdate'])->name('infrastructure.stores.bulkUpdate');
                Route::post('/edit', [StoreController::class, 'update'])->name('infrastructure.stores.update');
                Route::post('/export', [StoreController::class, 'exportCsv'])->name('infrastructure.stores.export');
                Route::post('/import', [StoreController::class, 'importCsv'])->name('infrastructure.stores.import');
            });

            Route::prefix('suppliers')->group(function () {
                Route::get('/', [SupplierController::class, 'index'])->name('infrastructure.suppliers.viewLandingPage');
                Route::get('/add', function () {
                    return Inertia::render('suppliers.viewCreatePageOrEditSupplier');
                })->name('infrastructure.suppliers.viewCreatePage');
                Route::get('/edit', [SupplierController::class, 'edit'])->name('infrastructure.suppliers.viewEditPageById');
                Route::get('/view', [SupplierController::class, 'view'])->name('infrastructure.suppliers.viewReadOnlyPageById');

                Route::post('/add', [SupplierController::class, 'store'])->name('infrastructure.suppliers.create');
                Route::post('/delete-photo', [SupplierController::class, 'deletePhoto'])->name('infrastructure.suppliers.photo.delete');
                Route::post('/delete', [SupplierController::class, 'destroy'])->name('infrastructure.suppliers.delete');
                Route::post('/edit-bulk', [SupplierController::class, 'bulkUpdate'])->name('infrastructure.suppliers.bulkUpdate');
                Route::post('/edit', [SupplierController::class, 'update'])->name('infrastructure.suppliers.update');
                Route::post('/export', [SupplierController::class, 'exportCsv'])->name('infrastructure.suppliers.export');
                Route::post('/import', [SupplierController::class, 'importCsv'])->name('infrastructure.suppliers.import');
            });
        });

        Route::prefix('commerce')->group(function () {
            Route::get('/', function () {
                return Inertia::render('Error/404');
            })->name('commerce');

            Route::prefix('brands')->group(function () {
                Route::get('/', [BrandController::class, 'index'])->name('commerce.brands.viewLandingPage');
                Route::get('/add', function () {
                    return Inertia::render('Admin/Commerce/Brands/AddOrEditBrand');
                })->name('commerce.brands.viewCreatePage');
                Route::get('/edit', [BrandController::class, 'edit'])->name('commerce.brands.viewEditPageById');
                Route::get('/view', [BrandController::class, 'view'])->name('commerce.brands.viewReadOnlyPageById');

                Route::post('/add', [BrandController::class, 'store'])->name('commerce.brands.create');
                Route::post('/delete-photo', [BrandController::class, 'deletePhoto'])->name('commerce.brands.photo.delete');
                Route::post('/delete', [BrandController::class, 'destroy'])->name('commerce.brands.delete');
                Route::post('/edit-bulk', [BrandController::class, 'bulkUpdate'])->name('commerce.brands.bulkUpdate');
                Route::post('/edit', [BrandController::class, 'update'])->name('commerce.brands.update');
                Route::post('/export', [BrandController::class, 'exportCsv'])->name('commerce.brands.export');
                Route::post('/import', [BrandController::class, 'importCsv'])->name('commerce.brands.import');
            });

            Route::prefix('categories')->group(function () {
                Route::get('/', [CategoryController::class, 'index'])->name('commerce.categories.viewLandingPage');
                Route::get('/add', function () {
                    return Inertia::render('Admin/Commerce/Categories/AddOrEditCategory');
                })->name('commerce.categories.viewCreatePage');
                Route::get('/edit', [CategoryController::class, 'edit'])->name('commerce.categories.viewEditPageById');
                Route::get('/view', [CategoryController::class, 'view'])->name('commerce.categories.viewReadOnlyPageById');

                Route::post('/add', [CategoryController::class, 'store'])->name('commerce.categories.create');
                Route::post('/delete', [CategoryController::class, 'destroy'])->name('commerce.categories.delete');
                Route::post('/edit-bulk', [CategoryController::class, 'bulkUpdate'])->name('commerce.categories.bulkUpdate');
                Route::post('/edit', [CategoryController::class, 'update'])->name('commerce.categories.update');
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

                Route::get('/requests', [EmployeeRequestController::class, 'index'])->name('admin/users/employees/requests');
                Route::get('/requests/view', [EmployeeRequestController::class, 'view'])->name('admin/users/employees/requests/view');

                Route::post('/updateEmployeeRequestKey', [EmployeeRequestController::class, 'updateEmployeeRequestKey'])->name('admin/users/employees/requests.key-update');
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
