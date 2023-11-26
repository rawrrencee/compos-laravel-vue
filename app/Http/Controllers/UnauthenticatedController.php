<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UnauthenticatedController extends Controller
{
    protected $CommonController;
    protected $EmployeeRequestController;
    protected $HardcodedDataController;

    public function __construct(CommonController $CommonController, EmployeeRequestController $EmployeeRequestController, HardcodedDataController $HardcodedDataController)
    {
        $this->CommonController = $CommonController;
        $this->EmployeeRequestController = $EmployeeRequestController;
        $this->HardcodedDataController = $HardcodedDataController;
    }

    public function loginPage()
    {
        return Inertia::render('Unauthenticated/Login', [
            'companyName' => $this->CommonController->getCompanyName()
        ]);
    }

    public function viewRegisterEmployeePage(Request $request)
    {
        $authenticated = false;

        if (isset($request['organisationKey'])) {
            $authenticated = $this->EmployeeRequestController->validateEmployeeKey($request['organisationKey']);

            if (!$authenticated) {
                return Inertia::render('Unauthenticated/RegisterEmployee')
                    ->with('flash', [
                        'show' => true,
                        'type' => 'default',
                        'status' => 'error',
                        'message' => 'An invalid organisation key was provided.'
                    ]);
            }
        }

        return Inertia::render('Unauthenticated/RegisterEmployee', [
            'organisationKey' => $request['organisationKey'],
            'companyName' => $this->CommonController->getCompanyName(),
            'authenticated' => $authenticated,
            'countries' => $this->HardcodedDataController->getCountries(),
            'genders' => $this->HardcodedDataController->getGenders(),
            'races' => $this->HardcodedDataController->getRaces(),
            'residencyStatuses' => $this->HardcodedDataController->getResidencyStatuses(),
        ]);
    }

    public function submitEmployeeRegistration(Request $request)
    {
        $authenticated = $this->EmployeeRequestController->validateEmployeeKey($request['organisationKey']);

        if ($authenticated) {
            return $this->CommonController->redirectBackWithGenericError();
        }

        $request->validate([
            'username' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'commencement_date' => 'required',
            'first_name' => 'required',
            'middle_name' => 'required',
            'last_name' => 'required',
            'preferred_name' => 'required',
            'nric' => 'required',
            'phone_number' => 'required',
            'mobile_number' => 'required',
            'address_1' => 'required',
            'address_2' => 'required',
            'address_3' => 'required',
            'address_4' => 'required',
            'city' => 'required',
            'state' => 'required',
            'postal_code' => 'required',
            'country' => 'required',
            'gender' => 'required',
            'race' => 'required',
            'ethnic_name' => 'required',
            'date_of_birth' => 'required',
            'nationality' => 'required',
            'residency_status' => 'required',
            'pr_conversion_date' => 'required',
            'emergency_name' => 'required',
            'emergency_relationship' => 'required',
            'emergency_address_1' => 'required',
            'emergency_address_2' => 'required',
            'emergency_address_3' => 'required',
            'emergency_address_4' => 'required',
            'emergency_contact_number' => 'required',
            'bank_name' => 'required',
            'bank_account_number' => 'required',
            'remarks' => 'required',
        ]);
    }
}
