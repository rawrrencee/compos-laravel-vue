<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

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
            $model->category_code = $model->category_code . '__deleted@' . date('d-m-Y h:i:s A');
            $model->subcategories->each(function ($subcategory) {
                $subcategory->update(['subcategory_code' => $subcategory->subcategory_code . '__deleted@' . date('d-m-Y h:i:s A')]);
            });
            $model->save();
        });
    }
}
