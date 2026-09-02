<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Requirement;
use App\Models\Course;

class RequirementsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $allCourses = Course::all();

        // 1. Parent's Consent Form
        $consent = Requirement::updateOrCreate(
            ['title' => "Parent's Consent"],
            [
                'description'   => 'Signed consent waiver granting permission for internship training at the host agency.',
                'template_path' => 'templates/requirements/PARENTS-CONSENT-OJT.pdf',
            ]
        );
        // Attach to all academic courses
        if ($allCourses->isNotEmpty()) {
            $consent->courses()->sync($allCourses->pluck('id'));
        }

        // 2. Student Trainee Information Sheet
        $infoSheet = Requirement::updateOrCreate(
            ['title' => 'Student Trainee Information Sheet'],
            [
                'description'   => 'Comprehensive trainee personal information, contact records, and educational profile.',
                'template_path' => 'templates/requirements/student-information-sheet .pdf',
            ]
        );
        if ($allCourses->isNotEmpty()) {
            $infoSheet->courses()->sync($allCourses->pluck('id'));
        }

        // 3. Internship Contract / Agreement (MOA)
        $contract = Requirement::updateOrCreate(
            ['title' => 'Internship Contract / Agreement (MOA)'],
            [
                'description'   => 'Tripartite agreement between BISU Balilihan, the Host Training Establishment (HTE), and the Intern.',
                'template_path' => 'templates/requirements/Internship-Agreement-BSCS.pdf',
            ]
        );
        // Attach to all academic courses
        if ($allCourses->isNotEmpty()) {
            $contract->courses()->sync($allCourses->pluck('id'));
        }
    }
}
