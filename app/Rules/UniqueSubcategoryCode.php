<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use App\Models\Subcategory;

class UniqueSubcategoryCode implements ValidationRule
{
    private $categoryId;

    public function __construct($categoryId)
    {
        $this->categoryId = $categoryId;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (Subcategory::where('category_id', $this->categoryId)
            ->where('subcategory_code', $value['subcategory_code'])
            ->where('id', '<>', $value['id'])
            ->exists()
        ) {
            $fail('The combination of category ID and subcategory code already exists.');
        }
    }
}
