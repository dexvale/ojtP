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
            'required_hours' => 600,
        ]);

        Course::create([
            'course_name' => 'BS in Computer Science',
            'required_hours' => 485,
        ]);

        
    }
}
