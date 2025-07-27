<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Role; // Import Role model
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CreateAdminUser extends Command
{
    protected $signature = 'app:create-system-admin-user {username} {email} {password}';
    protected $description = 'Create a new system administrator user.';

    public function handle(): int
    {
        $name = $this->argument('username');
        $email = $this->argument('email');
        $password = $this->argument('password');

        $validator = Validator::make([
            'username' => $name,
            'email' => $email,
            'password' => $password,
        ], [
            'username' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }
            return 1;
        }

        try {
            // Find the 'admin' role by name
            $adminRole = Role::where('role_name', 'system-admin')->first();

            if (!$adminRole) {
                $this->error('The "system-admin" role does not exist. Please run `php artisan db:seed` first to create roles.');
                return 1;
            }

            $user = User::create([
                'username' => $name,
                'email' => $email,
                'password' => Hash::make($password),
                'role_id' => $adminRole->id, // Assign the admin role ID
            ]);

            $this->info("System Admin user '{$name}' created successfully with email '{$email}'.");
            return 0;

        } catch (\Exception $e) {
            $this->error("An error occurred: " . $e->getMessage());
            return 1;
        }
    }
}