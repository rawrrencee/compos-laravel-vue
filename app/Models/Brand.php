<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Brand extends Model
{
    use HasFactory, SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'brand_name',
        'brand_code',
        'address_1',
        'address_2',
        'email',
        'phone_number',
        'mobile_number',
        'website',
        'active',
        'img_path',
        'img_url',
    ];
}