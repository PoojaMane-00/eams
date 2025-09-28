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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('employee_id')->nullable();
            $table->string('department');
            $table->string('designation');
            $table->date('joining_date');
            $table->string('name');
            $table->date('dob');
            $table->string('email')->unique();
            $table->string('mobile');
            $table->enum('gender', ['male', 'female', 'other']);
            $table->enum('marital_status', ['single', 'married'])->nullable();
            $table->text('address');
            $table->string('bank_account')->nullable();
            $table->string('uan')->nullable();
            $table->string('esic')->nullable();
            $table->string('qualification');
            $table->string('institution');
            $table->year('passing_year');
            $table->string('resume')->nullable();
            $table->string('pan_card')->nullable();
            $table->string('aadhaar_card')->nullable();
            $table->string('address_proof')->nullable();
            $table->json('education_certificates')->nullable(); // Can store an array of files
            $table->json('experience_letters')->nullable();  // Can store an array of files
            $table->string('employment_contract')->nullable();
            $table->string('nda')->nullable();
            $table->string('form16')->nullable();
            $table->string('passbook')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
