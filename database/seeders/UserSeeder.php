<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

use App\Models\Course;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create a Department Coordinator (managing IT & CS courses)
        $coordinatorUser = User::create([
            'email'    => 'coordinator@bisu.edu.ph', 
            'password' => Hash::make('admin123'),      
            'role'     => 'Coordinator',                     
        ]);

        $itCourse = Course::where('course_name', 'BS in Information Technology')->first();
        $csCourse = Course::where('course_name', 'BS in Computer Science')->first();

        if ($itCourse && $csCourse) {
            $coordinatorUser->managedCourses()->attach([$itCourse->id, $csCourse->id]);
        }

        // 2. Create the master Super Admin (Dean / OJT Director) who manages all departments
        User::create([
            'email'    => 'dean@bisu.edu.ph', 
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
