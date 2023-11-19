<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\GlobalSettings;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UnauthenticatedController extends Controller
{
    public function loginPage()
    {
        return Inertia::render('Unauthenticated/Login', [
            'companyName' => GlobalSettings::where('global_key', 'COMPANY_NAME')->first()?->global_value
        ]);
    }

    public function registerEmployeePage(Request $request)
    {
        return Inertia::render('Unauthenticated/RegisterEmployee');
    }
}
