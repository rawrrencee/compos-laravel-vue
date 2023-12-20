<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeInformation extends Model
{
    use HasFactory, SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'commencement_date',
        'first_name',
        'middle_name',
        'last_name',
        'preferred_name',
        'identity_type',
        'identity_number',
        'phone_number',
        'mobile_number',
        'address_1',
        'address_2',
        'address_3',
        'address_4',
        'city',
        'state',
        'postal_code',
        'country',
        'gender',
        'race',
        'ethnic_name',
        'date_of_birth',
        'nationality',
        'residency_status',
        'pr_conversion_date',
        'emergency_name',
        'emergency_relationship',
        'emergency_address_1',
        'emergency_address_2',
        'emergency_address_3',
        'emergency_address_4',
        'emergency_contact_number',
        'bank_name',
        'bank_account_number',
        'remarks',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}