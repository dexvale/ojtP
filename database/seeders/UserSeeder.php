<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create ONLY the master Coordinator / Admin Account
        User::create([
            // 'name' field is intentionally omitted because the `users` table is strict-auth-only
            'email'    => 'coordinator@bisu.edu.ph', 
            'password' => Hash::make('admin123'),      
            'role'     => 'Admin',                     
        ]);

        // 2. Create a Test Student Account (Authentication Layer)
        $studentUser = User::create([
            // 'name' is intentionally omitted here as well
            'email'    => 'dexter@student.bisu.edu.ph', // Used as the login username
            'password' => Hash::make('student123'),
            'role'     => 'Student',
        ]);

        // Link the student profile details to the authentication account
        $studentUser->studentProfile()->create([
            'student_id_number' => '2023-1024',
            'first_name'        => 'Dexter',
            'middle_name'       => null,
            'last_name'         => 'Vale',
            'course'            => 'BS in Information Technology',
        ]);
        
        // 3. Create a Test Supervisor Account
        User::create([
            // 'name' is intentionally omitted
            'email'    => 'johndoe@company.com', // Used as the login username
            'password' => Hash::make('super123'),
            'role'     => 'Advisor', // Mapped 'Supervisor' UI role to the 'Advisor' database enum
        ]);
    }
}
