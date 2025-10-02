<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    protected $fillable = [
        'company_name',
        'contact_person',
        'email',
        'mobile',
        'address',
        'details',
        'status',
        'source',
        'assigned_to',
        'follow_up_date'
    ];

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
