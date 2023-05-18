<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

class CompanyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Company::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'company_name' => $this->faker->company,
            'address_1' => $this->faker->streetAddress,
            'address_2' => $this->faker->secondaryAddress,
            'email' => $this->faker->email,
            'phone_number' => $this->faker->phoneNumber,
            'mobile_number' => $this->faker->phoneNumber,
            'website' => $this->faker->domainName,
            'img_path' => null,
            'img_url' => $this->faker->imageUrl(),
            'active' => $this->faker->boolean,
        ];
    }
}
