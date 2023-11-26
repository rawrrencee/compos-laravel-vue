<?php

namespace App\Http\Controllers;

use App\Enums\EmployeeRequestStatus;
use App\Http\Controllers\Controller;
use App\Models\EmployeeRequest;
use App\Models\GlobalSettings;
use App\Rules\EnumValue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class EmployeeRequestController extends Controller
{
    protected $CommonController;
    protected $EMPLOYEE_REQUEST_KEY;

    public function __construct(CommonController $CommonController)
    {
        $this->CommonController = $CommonController;
        $this->EMPLOYEE_REQUEST_KEY = 'EMPLOYEE_REQUEST';
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
            'viewEmployeeRequest' => null
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
            return redirect()->route('admin/users/employees/requests');
        }

        return Inertia::render('Admin/Users/Employees/Requests', [
            'employeeRequestStatuses' => $this->getEmployeeRequestStatuses(),
            'viewEmployeeRequest' => $employeeRequest
        ]);
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
}
