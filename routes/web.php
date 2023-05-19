<?php

use App\Http\Controllers\CommonController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use \App\Http\Controllers\CompanyController;

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

                Route::get('/view', function () {
                    return redirect()->route('admin/infrastructure/companies');
                });
                Route::post('/view', [CompanyController::class, 'view'])->name('admin/infrastructure/companies/view');
                Route::post('/delete-photo', [CompanyController::class, 'deletePhoto'])->name('admin/infrastructure/companies/photo.delete');

                Route::get('/export', [CompanyController::class, 'exportCsv'])->name('admin/infrastructure/companies/export');
            });

            Route::get('/stores', function () {
                return Inertia::render('Error/404');
            })->name('admin/infrastructure/stores');

            Route::get('/suppliers', function () {
                return Inertia::render('Error/404');
            })->name('admin/infrastructure/suppliers');
        });

        Route::prefix('commerce')->group(function () {
            Route::get('/', function () {
                return Inertia::render('Error/404');
            })->name('admin/commerce');

            Route::get('/brands', function () {
                return Inertia::render('Error/404');
            })->name('admin/commerce/brands');

            Route::get('/categories', function () {
                return Inertia::render('Error/404');
            })->name('admin/commerce/categories');

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

                Route::get('/requests', function () {
                    return Inertia::render('Admin/Users/Employees/Requests');
                })->name('admin/users/employees/requests');
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
