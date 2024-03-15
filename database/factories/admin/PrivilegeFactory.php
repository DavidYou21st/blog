<?php

namespace Database\Factories\Admin;

use App\Models\Admin\Privilege;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PrivilegeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Privilege::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'privilege_name' => $this->faker->name(),
            'module_name' => $this->faker->name(),
            'controller_name' => $this->faker->name(),
            'action_name' => $this->faker->name(),
            'route_url' => $this->faker->name(),
            'route_name' => $this->faker->name(),
            'parameter' => $this->faker->name(),
            'privilege_icon' => $this->faker->name(),
            'target' => $this->faker->name(),
            'orders' => 0,
            'is_menu' => 0,
            'parent_id' => 0,
            'created_at' => now(),
            'updated_at' => now()
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
