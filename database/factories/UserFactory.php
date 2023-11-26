<?php

namespace Database\Factories;

use App\Models\Dre;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $dres = Dre::all()->pluck('id');
        return [
            'username' => $this->faker->userName(),
            'email' => $this->faker->safeEmail(),
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'password' => Hash::make('123456'),
            'role_id' => rand(3, 5),
            'dre_id' => $dres[rand(0, (count($dres) - 1))]
        ];
    }
}