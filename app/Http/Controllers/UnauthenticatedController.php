<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UnauthenticatedController extends Controller
{
    protected $CommonController;
    protected $EmployeeRequestController;

    public function __construct(CommonController $CommonController, EmployeeRequestController $EmployeeRequestController)
    {
        $this->CommonController = $CommonController;
        $this->EmployeeRequestController = $EmployeeRequestController;
    }

    public function loginPage()
    {
        return Inertia::render('Unauthenticated/Login', [
            'companyName' => $this->CommonController->getCompanyName()
        ]);
    }

    public function registerEmployeePage(Request $request)
    {
        $authenticated = $this->EmployeeRequestController->getEmployeeRequestKey()->global_value == $request['organisationKey'];

        return Inertia::render('Unauthenticated/RegisterEmployee', [
            'companyName' => $this->CommonController->getCompanyName(),
            'authenticated' => $authenticated
        ]);
    }
}
