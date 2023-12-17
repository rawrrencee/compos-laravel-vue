<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\EmployeeRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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
                return redirect()->route('unauth/register/employee')
                    ->with('show', true)
                    ->with('type', 'default')
                    ->with('status', 'error')
                    ->with('message', 'An invalid organisation key was provided.');
            }
        }

        return Inertia::render('Unauthenticated/RegisterEmployee', [
            'organisationKey' => $request['organisationKey'],
            'companyName' => $this->CommonController->getCompanyName(),
            'authenticated' => $authenticated,
            'countries' => $this->HardcodedDataController->getCountries(),
            'genders' => $this->HardcodedDataController->getGenders(),
            'identityTypes' => $this->HardcodedDataController->getIdentityTypes(),
            'races' => $this->HardcodedDataController->getRaces(),
            'residencyStatuses' => $this->HardcodedDataController->getResidencyStatuses(),
        ]);
    }

    public function submitEmployeeRegistration(Request $request)
    {
        $authenticated = $this->EmployeeRequestController->validateEmployeeKey($request['organisationKey']);

        if (!$authenticated) {
            return $this->CommonController->redirectBackWithGenericError();
        }

        $requestArray = $request->all();
        $this->getSubmitEmployeeRegistrationValidator($requestArray)->validate();

        try {
            DB::beginTransaction();

            $requestArray['password'] = Hash::make($request['password']);
            $requestArray['commencement_date'] = $this->CommonController->formatUtcDateToSingaporeDate($request['commencement_date']);
            $requestArray['date_of_birth'] = $this->CommonController->formatUtcDateToSingaporeDate($request['date_of_birth']);
            $requestArray['pr_conversion_date'] = $this->CommonController->formatUtcDateToSingaporeDate($request['pr_conversion_date']);

            EmployeeRequest::create($requestArray);
            DB::commit();

            return Inertia::render('Unauthenticated/RegistrationSuccessful', [
                'username' => 'test',
                'email' => 'test',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return Inertia::render('Admin/Commerce/Categories/AddOrEditCategory', [
                'errorMessage' => 'Failed to create record: ' . $this->CommonController->formatException($e),
            ]);
        }
    }

    private function getSubmitEmployeeRegistrationValidator(array $requestArray)
    {
        return Validator::make($requestArray, [
            'username' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'commencement_date' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'preferred_name' => 'required',
            'identity_type' => 'required',
            'identity_number' => 'required',
            'mobile_number' => 'required',
            'address_1' => 'required',
            'address_2' => 'required',
            'city' => 'required',
            'state' => 'required',
            'postal_code' => 'required',
            'country' => 'required',
            'gender' => 'required',
            'race' => 'required',
            'date_of_birth' => 'required',
            'nationality' => 'required',
            'residency_status' => 'required',
            'emergency_name' => 'required',
            'emergency_relationship' => 'required',
            'emergency_address_1' => 'required',
            'emergency_address_2' => 'required',
            'emergency_contact_number' => 'required',
            'bank_name' => 'required',
            'bank_account_number' => 'required',
        ]);
    }
}
