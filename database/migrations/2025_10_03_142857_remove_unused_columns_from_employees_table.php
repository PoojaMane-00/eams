<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveUnusedColumnsFromEmployeesTable extends Migration
{
    public function up()
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn([
                'resume',
                'pan_card',
                'aadhaar_card',
                'address_proof',
                'employment_contract',
                'nda',
                'form16',
                'passbook'
            ]);
        });
    }

    public function down()
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->string('resume')->nullable();
            $table->string('pan_card')->nullable();
            $table->string('aadhaar_card')->nullable();
            $table->string('address_proof')->nullable();
            $table->string('employment_contract')->nullable();
            $table->string('nda')->nullable();
            $table->string('form16')->nullable();
            $table->string('passbook')->nullable();
        });
    }
}
