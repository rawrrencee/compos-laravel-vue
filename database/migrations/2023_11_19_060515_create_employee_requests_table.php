<?php

use App\Enums\EmployeeRequestStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('employee_requests', function (Blueprint $table) {
            $table->id();

            $table->string('status')->default(EmployeeRequestStatus::PENDING);

            $table->string('username')->unique();
            $table->string('email');
            $table->string('password');

            $table->date('commencement_date')->nullable();
            $table->string('first_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('preferred_name')->nullable();
            $table->string('nric')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('mobile_number')->nullable();
            $table->string('address_1')->nullable();
            $table->string('address_2')->nullable();
            $table->string('address_3')->nullable();
            $table->string('address_4')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('country')->nullable();
            $table->string('gender')->nullable();
            $table->string('race')->nullable();
            $table->string('ethnic_name')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('nationality')->nullable();
            $table->string('residency_status')->nullable();
            $table->date('pr_conversion_date')->nullable();
            $table->string('emergency_name')->nullable();
            $table->string('emergency_relationship')->nullable();
            $table->string('emergency_address_1')->nullable();
            $table->string('emergency_address_2')->nullable();
            $table->string('emergency_address_3')->nullable();
            $table->string('emergency_address_4')->nullable();
            $table->string('emergency_contact_number')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->text('remarks')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_requests');
    }
};
