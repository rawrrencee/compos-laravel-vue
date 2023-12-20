<?php

namespace App\Http\Controllers;

use App\Enums\UserType;
use App\Http\Controllers\Controller;
use App\Models\EmployeeInformation;
use App\Models\EmployeeRequest;
use App\Models\User;

class UserController extends Controller
{
    protected $CommonController;
    protected $HardcodedDataController;

    public function __construct(CommonController $CommonController, HardcodedDataController $HardcodedDataController)
    {
        $this->CommonController = $CommonController;
        $this->HardcodedDataController = $HardcodedDataController;
    }

    public function createUserFromEmployeeRequest(EmployeeRequest $employeeRequest)
    {
        $user = User::create([
            'name' => $employeeRequest->first_name,
            'email' => $employeeRequest->email,
            'password' => $employeeRequest->password,
            'user_type' => UserType::EMPLOYEE,
        ]);

        return $user;
    }

    public function createEmployeeInformation(EmployeeRequest $employeeRequest, User $user)
    {
        $data = $employeeRequest->toArray();
        $data['user_id'] = $user->id;
        $employeeInformation = EmployeeInformation::create($data);

        return $employeeInformation;
    }
}
