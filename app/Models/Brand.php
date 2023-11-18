<?php

namespace App\Models;

use DateTime;
use DateTimeZone;
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

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($model) {
            $timezone = new DateTimeZone('Asia/Singapore');
            $date = new DateTime('now', $timezone);
            $formattedDate = $date->format('d-m-Y h:i:s A');

            $split = explode('__deleted', $model->brand_code);
            $model->brand_code = $split[0] . '__deleted@' . $formattedDate;
            $model->save();
        });
    }
}
