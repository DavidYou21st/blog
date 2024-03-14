<?php

namespace Database\Factories\Admin;

use App\Models\Admin\Role;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class RoleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Role::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'role_name' => fake()->name(),
            'status' => 1,
            'is_cate' => 0,
            'orders' => 0,
            'remark' => '',
            'parent_id' => 1,
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return static
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
