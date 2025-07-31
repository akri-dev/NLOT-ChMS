<?php

namespace Database\Factories;

use App\Models\Role; // Make sure to import your Role model
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

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
     * @return array
     */
    public function definition()
    {
        // Changed 'name' to 'role_name' to match your seeder and likely your database schema
        return [
            'role_name' => $this->faker->unique()->word(), // Generates a unique word for the role name
            // Add other role attributes here if your roles table has them
        ];
    }

    /**
     * Indicate that the role is a 'system-admin' role.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function systemAdmin()
    {
        return $this->state(function (array $attributes) {
            return [
                'role_name' => 'system-admin',
            ];
        });
    }

    /**
     * Indicate that the role is a 'ministry-leader' role.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function ministryLeader()
    {
        return $this->state(function (array $attributes) {
            return [
                'role_name' => 'ministry-leader',
            ];
        });
    }

    /**
     * Indicate that the role is a 'ministry-staff' role.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function ministryStaff()
    {
        return $this->state(function (array $attributes) {
            return [
                'role_name' => 'ministry-staff',
            ];
        });
    }

    /**
     * Indicate that the role is a 'church-member' role.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function churchMember()
    {
        return $this->state(function (array $attributes) {
            return [
                'role_name' => 'church-member',
            ];
        });
    }
}
