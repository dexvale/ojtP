<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Course::create([
            'course_name' => 'BS in Information Technology',
            'required_hours' => 486,
        ]);

        Course::create([
            'course_name' => 'BS in Computer Science',
            'required_hours' => 320,
        ]);

        Course::create([
            'course_name' => 'BS in Electrical Technology',
            'required_hours' => 720,
        ]);

        Course::create([
            'course_name' => 'BS in Electronics Technology',
            'required_hours' => 720,
        ]);

        Course::create([
            'course_name' => 'BSIT in Food Preparation and Service Management',
            'required_hours' => 500,
        ]);

        Course::create([
            'course_name' => 'BS in Criminology',
            'required_hours' => 540,
        ]);

      


      

        
    }
}
