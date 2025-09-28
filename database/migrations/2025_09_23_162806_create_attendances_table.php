<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Schema::create('attendances', function (Blueprint $table) {
        //     $table->id();
        //     $table->unsignedBigInteger('employee_id');
        //     $table->date('date');
        //     $table->enum('status', ['present', 'half_day', 'leave', 'absent'])->default('absent');
        //     $table->time('punch_in')->nullable();
        //     $table->time('punch_out')->nullable();
        //     $table->text('remarks')->nullable(); // optional admin notes
        //     $table->timestamps();

        //     $table->unique(['employee_id', 'date']);
        //     $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
        // });

        Schema::create('salary_slips', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->date('salary_month');
            $table->decimal('basic_salary', 10, 2);
            $table->decimal('hra', 10, 2)->default(0);
            $table->decimal('allowances', 10, 2)->default(0);
            $table->decimal('deductions', 10, 2)->default(0);
            $table->decimal('net_salary', 10, 2);
            $table->timestamps();

            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
