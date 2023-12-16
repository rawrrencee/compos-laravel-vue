<?php

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
        Schema::table('employee_requests', function (Blueprint $table) {
            $table->renameColumn('nric', 'identity_number');

            // Add a new column 'identity_type'
            $table->string('identity_type')->nullable()->after('preferred_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employee_requests', function (Blueprint $table) {
            $table->renameColumn('identity_number', 'nric');
            $table->dropColumn('identity_type');
        });
    }
};
