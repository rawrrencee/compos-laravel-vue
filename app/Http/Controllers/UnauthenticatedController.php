<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\GlobalSettings;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UnauthenticatedController extends Controller
{
    protected $CommonController;

    public function __construct(CommonController $CommonController)
    {
        $this->CommonController = $CommonController;
    }

    public function loginPage()
    {
        return Inertia::render('Unauthenticated/Login', [
            'companyName' => $this->CommonController->getCompanyName()
        ]);
    }

    public function registerEmployeePage()
    {
        return Inertia::render('Unauthenticated/RegisterEmployee', [
            'companyName' => $this->CommonController->getCompanyName()
        ]);
    }
}
