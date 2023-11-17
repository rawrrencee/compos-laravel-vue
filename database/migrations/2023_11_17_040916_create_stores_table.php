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
        Schema::create('stores', function (Blueprint $table) {
            $table->id();
            $table->string('store_name');
            $table->string('store_code')->unique();
            $table->unsignedBigInteger('company_id');
            $table->string('address_1')->nullable();
            $table->string('address_2')->nullable();
            $table->string('email')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('mobile_number')->nullable();
            $table->string('website')->nullable();
            $table->tinyInteger('active')->default(0);
            $table->tinyInteger('include_tax')->default(0);
            $table->tinyInteger('tax_percentage')->default(0);
            $table->string('img_path')->nullable();
            $table->string('img_url')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('company_id')->references('id')->on('companies');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stores');
    }
};
