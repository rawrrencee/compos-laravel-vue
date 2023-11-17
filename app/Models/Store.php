<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Store extends Model
{
    use HasFactory, SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'store_name',
        'store_code',
        'company_id',
        'address_1',
        'address_2',
        'email',
        'phone_number',
        'mobile_number',
        'website',
        'active',
        'include_tax',
        'tax_percentage',
        'img_path',
        'img_url',
    ];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($model) {
            $model->store_code = $model->store_code . '__deleted@' . date('d-m-Y h:i:s A');
            $model->save();
        });
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
