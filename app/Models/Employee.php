<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
        'employee_id',
        'department',
        'designation',
        'joining_date',
        'name',
        'dob',
        'email',
        'mobile',
        'gender',
        'marital_status',
        'address',
        'bank_account',
        'uan',
        'esic',
        'qualification',
        'institution',
        'passing_year',
        'password',
    ];

    // If you want to use JSON fields
    protected $casts = [
        'education_certificates' => 'array',
        'experience_letters' => 'array',
    ];

    public function documents()
    {
        return $this->hasMany(EmployeeDocument::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
}
