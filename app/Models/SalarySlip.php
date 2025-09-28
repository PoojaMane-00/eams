<?php

namespace App\Models;

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalarySlip extends Model
{
    protected $fillable = [
        'employee_id',
        'salary_month',
        'basic_salary',
        'hra',
        'allowances',
        'deductions',
        'net_salary',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
