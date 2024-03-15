<?php

namespace Database\Factories\Admin;

use App\Models\Admin\Manager;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ManagerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Manager::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'full_name' => $this->faker->name(),
            'account' => $this->faker->name(),
            'password' => $this->faker->password(), // password
            'entry_password' => Str::random(10),
            'email' => $this->faker->email(),
            'salt' => Str::random(6),
            'last_ip' => $this->faker->ipv4(),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unverified()
    {
//        return $this->state(function (array $attributes) {
//            return [
//                'email_verified_at' => null,
//            ];
//        });
    }
}
