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
                return redirect()->route('unauth.employees.register.view')
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

    public function viewRegistrationSuccessfulPage()
    {
        return Inertia::render('Unauthenticated/RegistrationSuccessful', [
            'companyName' => $this->CommonController->getCompanyName()
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

            $maskedUsername = $this->CommonController->maskUsername($request['username']);
            $maskedEmail = $this->CommonController->maskEmail($request['email']);

            $outputMessage = 'Your registration with username ' . $maskedUsername . ' was successful. You will be notified via your email, ' . $maskedEmail . ' once your account has been approved.';

            return redirect()
                ->route('unauth.employees.register.success')
                ->with('show', true)
                ->with('type', 'default')
                ->with('status', 'success')
                ->with('message', $outputMessage);
        } catch (\Exception $e) {
            DB::rollBack();

            return $this->CommonController->handleException($e, 'default', 'create');
        }
    }

    private function getSubmitEmployeeRegistrationValidator(array $requestArray)
    {
        return Validator::make($requestArray, $this->EmployeeRequestController->getEmployeeRequestValidatorRules(true));
    }
}
