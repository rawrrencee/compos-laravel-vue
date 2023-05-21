<?php

namespace Database\Factories;

use App\Models\Brand;
use Illuminate\Database\Eloquent\Factories\Factory;

class BrandFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Brand::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'brand_name' => $this->faker->firstName,
            'brand_code' => $this->faker->lexify('????'),
            'address_1' => $this->faker->streetAddress,
            'address_2' => $this->faker->secondaryAddress,
            'email' => $this->faker->email,
            'phone_number' => $this->faker->phoneNumber,
            'mobile_number' => $this->faker->phoneNumber,
            'website' => 'https://' . $this->faker->domainName,
            'img_path' => null,
            'img_url' => $this->faker->imageUrl(),
            'active' => $this->faker->boolean,
        ];
    }
}
