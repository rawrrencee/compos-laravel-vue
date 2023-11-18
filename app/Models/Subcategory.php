<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subcategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'category_id',
        'subcategory_name',
        'subcategory_code',
        'description',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($model) {
            $model->subcategory_code = $model->subcategory_code . '__deleted@' . date('d-m-Y h:i:s A');
            $model->save();
        });
    }
}