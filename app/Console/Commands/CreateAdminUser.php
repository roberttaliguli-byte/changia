<?php
// app/Console/Commands/CreateAdminUser.php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateAdminUser extends Command
{
   protected $signature = 'admin:create
                        {--email= : Admin email address}
                        {--name= : Admin name}
                        {--phone= : Admin phone number}
                        {--password= : Admin password}';

    protected $description = 'Create a new admin user securely';

    public function handle()
    {
        // Get admin details
        $name = $this->option('name') ?? $this->ask('Enter admin name');
        $email = $this->option('email') ?? $this->ask('Enter admin email');
        $phone = $this->option('phone') ?? $this->ask('Enter admin phone number');
        
        // Validate
        if (!$name || !$email || !$phone) {
            $this->error('Name, email, and phone are required!');
            return 1;
        }
        
        // Check if user already exists
        if (User::where('email', $email)->exists()) {
            $this->error("User with email {$email} already exists!");
            return 1;
        }
        
        if (User::where('phone', $phone)->exists()) {
            $this->error("User with phone {$phone} already exists!");
            return 1;
        }
        
$password = $this->option('password');

if (!$password) {
    $this->error('Password is required.');
    return 1;
}

if (strlen($password) < 8) {
    $this->error('Password must be at least 8 characters!');
    return 1;
}
        
        // Create admin user
        try {
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
                'password' => Hash::make($password),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]);
            
            $this->info('✓ Admin user created successfully!');
            $this->table(
                ['Name', 'Email', 'Phone', 'Role'],
                [[$user->name, $user->email, $user->phone, $user->role]]
            );
            $this->warn('Please keep these credentials secure!');
            
            return 0;
        } catch (\Exception $e) {
            $this->error('Failed to create admin user: ' . $e->getMessage());
            return 1;
        }
    }
}

