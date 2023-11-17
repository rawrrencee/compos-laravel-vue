<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subcategory extends Model
{
    use HasFactory;

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
