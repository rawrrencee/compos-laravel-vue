<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use HasFactory, SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'company_name',
        'identity_type',
        'identity_number',
        'currency',
        'address_1',
        'address_2',
        'country',
        'email',
        'phone_number',
        'mobile_number',
        'website',
        'active',
        'img_path',
        'img_url',
    ];

    public function stores()
    {
        return $this->hasMany(Store::class);
    }
}
