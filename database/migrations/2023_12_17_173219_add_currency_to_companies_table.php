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
        Schema::table('companies', function (Blueprint $table) {
            $table->string('identity_type')->nullable()->after('company_name');
            $table->string('identity_number')->nullable()->after('identity_type');
            $table->string('currency')->nullable()->after('identity_number');
            $table->string('country')->nullable()->after('address_2');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn('identity_type');
            $table->dropColumn('identity_number');
            $table->dropColumn('currency');
            $table->dropColumn('country');
        });
    }
};
