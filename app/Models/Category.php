<?php

namespace App\Models;

use DateTime;
use DateTimeZone;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'category_name',
        'category_code',
        'description',
    ];

    public function subcategories()
    {
        return $this->hasMany(Subcategory::class, 'category_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($model) {
            $timezone = new DateTimeZone('Asia/Singapore');
            $date = new DateTime('now', $timezone);
            $formattedDate = $date->format('d-m-Y h:i:s A');

            $split = explode('__deleted', $model->category_code);
            $model->category_code = $split[0] . '__deleted@' . $formattedDate;
            $model->subcategories->each(function ($subcategory) use ($formattedDate) {
                $subcategorySplit = explode('__deleted', $subcategory->subcategory_code);
                $subcategory->update(['subcategory_code' => $subcategorySplit[0] . '__deleted@' . $formattedDate]);
            });
            $model->save();
        });
    }
}
