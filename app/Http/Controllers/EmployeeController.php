<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\GlobalSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class EmployeeController extends Controller
{
    protected $CommonController;

    public function __construct(CommonController $CommonController)
    {
        $this->CommonController = $CommonController;
    }

    public function getEmployeeRequestCode()
    {
        return GlobalSettings::where('global_key', 'EMPLOYEE_REQUEST')->first();
    }

    public function employeeRequestsPage()
    {
        return Inertia::render('Admin/Users/Employees/Requests', ['employeeRequest' => $this->getEmployeeRequestCode()]);
    }

    public function updateEmployeeRequestKey(Request $request)
    {
        $request->validate([
            'global_value' => 'required|max:100'
        ]);


        try {
            DB::beginTransaction();

            $existing = $this->getEmployeeRequestCode();
            if (isset($existing)) {
                $existing->update($request->only(['global_value']));
            } else {
                GlobalSettings::create(['global_key' => 'EMPLOYEE_REQUEST', 'global_value' => $request['global_value']]);
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
