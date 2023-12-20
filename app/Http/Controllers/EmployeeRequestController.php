<?php

namespace App\Http\Controllers;

use App\Enums\EmployeeRequestStatus;
use App\Http\Controllers\Controller;
use App\Models\EmployeeRequest;
use App\Models\GlobalSettings;
use App\Rules\EnumValue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class EmployeeRequestController extends Controller
{
    protected $CommonController;
    protected $HardcodedDataController;
    protected $EMPLOYEE_REQUEST_KEY;

    public function __construct(CommonController $CommonController, HardcodedDataController $HardcodedDataController)
    {
        $this->CommonController = $CommonController;
        $this->HardcodedDataController = $HardcodedDataController;
        $this->EMPLOYEE_REQUEST_KEY = 'EMPLOYEE_REQUEST';
    }

    public function getEmployeeRequestById(string $id)
    {
        return EmployeeRequest::whereId($id)->firstOrFail();
    }

    public function getEmployeeRequestKey()
    {
        return GlobalSettings::where('global_key', $this->EMPLOYEE_REQUEST_KEY)->first();
    }

    public function getEmployeeRequestStatuses()
    {
        return array_column(EmployeeRequestStatus::cases(), 'value');
    }

    public function validateEmployeeKey($key)
    {
        return $this->getEmployeeRequestKey()->global_value == $key;
    }

    public function index(Request $request)
    {
        $request['sortBy'] = $request['sortBy'] ?? 'employee_name';
        $request['orderBy'] = $request['orderBy'] ?? 'asc';
        $request['perPage'] = $request['perPage'] ?? '10';

        $validator = Validator::make($request->all(), [
            'sortBy' => [
                'required',
                Rule::in(['employee_name', 'preferred_name', 'email', 'status', 'created_at'])
            ],
            'orderBy' => [
                'required',
                Rule::in(['asc', 'desc'])
            ],
            'perPage' => 'numeric|max:2000',
            'tableFilterOptions.status' => [
                'nullable',
                new EnumValue(EmployeeRequestStatus::cases())
            ],
            'tableFilterOptions.showDeleted' => [
                'nullable',
                Rule::in(['onlyNonDeleted', 'onlyDeleted', 'both'])
            ],
        ]);

        if ($validator->fails()) {
            return redirect()->route('404');
        }

        // Filters
        $searchTerm = isset($request['tableFilterOptions']) ? $request['tableFilterOptions']['searchTerm']  ?? null : null;

        $employeeRequests = EmployeeRequest::query();
        if (!empty($searchTerm)) {
            $employeeRequests->where(function ($q) use ($searchTerm) {
                $q->where('first_name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('middle_name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('last_name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('preferred_name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('email', 'like', '%' . $searchTerm . '%');
            });
        }

        if (isset($request['tableFilterOptions']) && isset($request['tableFilterOptions']['status'])) {
            $employeeRequests->where('status', $request['tableFilterOptions']['status']);
        }

        if (isset($request['tableFilterOptions']) && isset($request['tableFilterOptions']['showDeleted'])) {
            switch ($request['tableFilterOptions']['showDeleted']) {
                case 'onlyDeleted':
                    $employeeRequests->onlyTrashed();
                    break;
                case 'both':
                    $employeeRequests->withTrashed();
                    break;
                case 'onlyNonDeleted':
                default:
                    break;
            }
        }

        $employeeRequests->select('id', DB::raw("CONCAT(first_name, ' ', COALESCE(middle_name, ''), ' ', COALESCE(last_name, '')) AS employee_name"), 'preferred_name', 'email', 'status', 'created_at');

        if ($request['sortBy'] !== 'status') {
            $employeeRequests->orderByRaw("
            CASE status 
                WHEN 'PENDING' THEN 1 
                WHEN 'APPROVED' THEN 2 
                WHEN 'REJECTED' THEN 3 
                ELSE 4 
            END");
        }

        return Inertia::render('Admin/Users/Employees/Requests', [
            'sortBy' => $request['sortBy'],
            'orderBy' => $request['orderBy'],
            'paginatedResults' =>
            $employeeRequests
                ->orderBy($request['sortBy'], $request['orderBy'])
                ->paginate($request['perPage']),
            'tableFilterOptions' => $request['tableFilterOptions'],
            'employeeRequestKey' => $this->getEmployeeRequestKey(),
            'employeeRequestStatuses' => $this->getEmployeeRequestStatuses(),
            'viewEmployeeRequest' => null,
            'countries' => $this->HardcodedDataController->getCountries(),
            'genders' => $this->HardcodedDataController->getGenders(),
            'identityTypes' => $this->HardcodedDataController->getIdentityTypes(),
            'races' => $this->HardcodedDataController->getRaces(),
            'residencyStatuses' => $this->HardcodedDataController->getResidencyStatuses(),
        ]);
    }

    public function view(Request $request)
    {
        $id = intval($request['id']);
        if ($id === 0) {
            return redirect()->route('404');
        }

        $employeeRequest = EmployeeRequest::withTrashed()
            ->where('id', '=', $id)
            ->first();

        if (!isset($employeeRequest)) {
            return $this->CommonController->handleException(new \Exception("Employee Request not found.", 1), 'default', 'retrieve');
        }

        $renderWith = [
            'employeeRequestStatuses' => $this->getEmployeeRequestStatuses(),
            'viewEmployeeRequest' => $employeeRequest,
            'countries' => $this->HardcodedDataController->getCountries(),
            'genders' => $this->HardcodedDataController->getGenders(),
            'identityTypes' => $this->HardcodedDataController->getIdentityTypes(),
            'races' => $this->HardcodedDataController->getRaces(),
            'residencyStatuses' => $this->HardcodedDataController->getResidencyStatuses(),
        ];

        if ($employeeRequest->status === EmployeeRequestStatus::APPROVED->value) {
            $renderWith['flash'] = ['show' => true, 'type' => 'default', 'status' => 'success', 'message' => 'Approved Employee Requests can no longer be modified.'];
        }

        return Inertia::render('Admin/Users/Employees/ViewRequest', $renderWith);
    }

    public function updateEmployeeRequestKey(Request $request)
    {
        $request->validate([
            'global_value' => 'required|max:100'
        ]);


        try {
            DB::beginTransaction();

            $existing = $this->getEmployeeRequestKey();
            if (isset($existing)) {
                $existing->update($request->only(['global_value']));
            } else {
                GlobalSettings::create(['global_key' => $this->EMPLOYEE_REQUEST_KEY, 'global_value' => $request['global_value']]);
            }

            DB::commit();

            return redirect()->back()
                ->with('show', true)
                ->with('type', 'default')
                ->with('status', 'success')
                ->with('message', 'Employee Request Key updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            return $this->CommonController->handleException($e);
        }
    }

    public function updateEmployeeRequest(Request $request)
    {
        Validator::make($request->all(), $this->getEmployeeRequestValidatorRules(false))->validate();

        try {
            DB::beginTransaction();
            $existing = $this->getEmployeeRequestById($request['id']);

            if (empty($request['password'])) {
                $request['password'] = $existing->password;
            } else {
                $request['password'] = Hash::make($request['password']);
            }

            if (isset($existing)) {
                $existing->update($request->all());
                DB::commit();
            } else {
                throw new \Exception("Employee Request not found.", 1);
            }

            return to_route('users.employees.requests.viewLandingPage')
                ->with('show', true)
                ->with('type', 'default')
                ->with('status', 'success')
                ->with('message', 'Employee Request updated successfully.')
                ->with('route', 'users.employees.requests.viewById')
                ->with('id', $existing->id);
        } catch (\Exception $e) {
            DB::rollBack();

            return $this->CommonController->handleException($e);
        }
    }

    public function getEmployeeRequestValidatorRules(bool $isUnauthenticated)
    {
        $rules = [
            'username' => 'required',
            'email' => 'required|email',
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
        ];

        if ($isUnauthenticated) {
            $rules['password'] = 'required';
        } else {
            $rules['id'] = 'required|exists:employee_requests,id';
            $rules['status'] = 'required';
        }

        return $rules;
    }
}
