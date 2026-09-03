<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Course;
use App\Models\Company;
use App\Models\StudentProfile;
use App\Models\OjtLog;
use App\Models\Requirement;
use App\Models\RequirementSubmission;
use App\Models\StudentEvaluation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 0. Create Initial Active Academic Term
        $activeTerm = \App\Models\AcademicTerm::firstOrCreate(
            ['academic_year' => '2026-2027', 'semester' => '1st Semester'],
            ['is_active' => true]
        );

        // 1. Create Mock Companies
        $company1 = Company::create([
            'name' => 'Nova Soft Solutions',
            'industry' => 'Software Development',
            'location' => 'Tagbilaran City, Bohol',
            'contact_person' => 'Alice Margate',
            'contact_number' => '09123456789',
        ]);

        $company2 = Company::create([
            'name' => 'SpaceTech Labs',
            'industry' => 'Aerospace & Systems',
            'location' => 'Panglao, Bohol',
            'contact_person' => 'Bob Miller',
            'contact_number' => '09176543210',
        ]);

        $company3 = Company::create([
            'name' => 'Quantum Dev',
            'industry' => 'Cybersecurity',
            'location' => 'Talibon, Bohol',
            'contact_person' => 'Charlie Vance',
            'contact_number' => '09283334445',
        ]);

        // Fetch courses seeded by CourseSeeder
        $itCourse = Course::where('course_name', 'BS in Information Technology')->first();
        $csCourse = Course::where('course_name', 'BS in Computer Science')->first();
       

        // Connect Companies and Courses
        if ($itCourse && $csCourse) {
            $company1->courses()->sync([$itCourse->id, $csCourse->id]);
            $company2->courses()->sync([$itCourse->id, $csCourse->id]);
            $company3->courses()->sync([$csCourse->id]);
        }

        // 2. Create a Department Coordinator (managing IT & CS courses)
        $coordinatorUser = User::create([
            'name'     => 'Dr. Elena Vance',
            'email'    => 'coordinator@bisu.edu.ph', 
            'password' => Hash::make('admin123'),      
            'role'     => 'Coordinator',                     
        ]);

        if ($itCourse && $csCourse) {
            $coordinatorUser->managedCourses()->attach([$itCourse->id, $csCourse->id]);
        }

        // 3. Create the master Super Admin (Dean / OJT Director) who manages all departments
        User::create([
            'name'     => 'Dexter Vale',
            'email'    => 'superadmin@gmail.com', 
            'password' => Hash::make('admin123'),      
            'role'     => 'Admin',                     
        ]);

        // 4. Create Test Supervisor Accounts for each company/department
        $supervisor1 = User::create([
            'name'           => 'Alice Margate',
            'email'          => 'johndoe@company.com', 
            'password'       => Hash::make('super123'),
            'role'           => 'Advisor', 
            'company_id'     => $company1->id,
            'department'     => 'Software Engineering',
            'contact_number' => '09123456789',
        ]);

        $supervisor1b = User::create([
            'name'           => 'Marcus Rivera',
            'email'          => 'marcus@novasoft.com', 
            'password'       => Hash::make('super123'),
            'role'           => 'Advisor', 
            'company_id'     => $company1->id,
            'department'     => 'Quality Assurance',
            'contact_number' => '09198887766',
        ]);

        $supervisor2 = User::create([
            'name'           => 'Bob Miller',
            'email'          => 'bob@spacetech.com', 
            'password'       => Hash::make('super123'),
            'role'           => 'Advisor', 
            'company_id'     => $company2->id,
            'department'     => 'Aerospace Systems',
            'contact_number' => '09176543210',
        ]);

        $supervisor3 = User::create([
            'name'           => 'Charlie Vance',
            'email'          => 'charlie@quantumdev.com', 
            'password'       => Hash::make('super123'),
            'role'           => 'Advisor', 
            'company_id'     => $company3->id,
            'department'     => 'Cybersecurity Operations',
            'contact_number' => '09283334445',
        ]);

        $supervisor = $supervisor1; // backwards compatibility alias

        // 5. Create Student Accounts & Profiles
        
        // Student 1 (IT - Managed, Dexter Vale)
        $studentUser1 = User::create([
            'email'    => 'dexter@student.bisu.edu.ph',
            'password' => Hash::make('student123'),
            'role'     => 'Student',
        ]);
        $profile1 = $studentUser1->studentProfile()->create([
            'academic_term_id'  => $activeTerm->id,
            'student_id_number' => '2023-1024',
            'first_name'        => 'Dexter',
            'middle_name'       => null,
            'last_name'         => 'Vale',
            'course'            => 'BS in Information Technology',
            'required_hours'    => 600,
            'company_id'        => $company1->id,
            'supervisor_id'     => $supervisor1->id,
            'department'        => 'Software Engineering',
            'placement_status'  => 'Approved',
            'ojt_status'        => 'Active',
        ]);

        // Student 2 (IT - Managed, Liam Smith)
        $studentUser2 = User::create([
            'email'    => 'liam@student.bisu.edu.ph',
            'password' => Hash::make('student123'),
            'role'     => 'Student',
        ]);
        $profile2 = $studentUser2->studentProfile()->create([
            'academic_term_id'  => $activeTerm->id,
            'student_id_number' => '2023-1025',
            'first_name'        => 'Liam',
            'middle_name'       => 'James',
            'last_name'         => 'Smith',
            'course'            => 'BS in Information Technology',
            'required_hours'    => 600,
            'company_id'        => $company2->id,
            'supervisor_id'     => $supervisor2->id,
            'department'        => 'Aerospace Systems',
            'placement_status'  => 'Approved',
            'ojt_status'        => 'Active',
        ]);

        // Student 3 (CS - Managed, Sophia Johnson)
        $studentUser3 = User::create([
            'email'    => 'sophia@student.bisu.edu.ph',
            'password' => Hash::make('student123'),
            'role'     => 'Student',
        ]);
        $profile3 = $studentUser3->studentProfile()->create([
            'academic_term_id'  => $activeTerm->id,
            'student_id_number' => '2023-2001',
            'first_name'        => 'Sophia',
            'middle_name'       => 'Rose',
            'last_name'         => 'Johnson',
            'course'            => 'BS in Computer Science',
            'required_hours'    => 485,
            'company_id'        => $company3->id,
            'supervisor_id'     => $supervisor3->id,
            'department'        => 'Cybersecurity Operations',
            'placement_status'  => 'Approved',
            'ojt_status'        => 'Active',
        ]);

        // Student 4 (Unassigned Student for testing placement submission)
        $studentUser4 = User::create([
            'email'    => 'emma@student.bisu.edu.ph',
            'password' => Hash::make('student123'),
            'role'     => 'Student',
        ]);
        $profile4 = $studentUser4->studentProfile()->create([
            'academic_term_id'  => $activeTerm->id,
            'student_id_number' => '2023-3004',
            'first_name'        => 'Emma',
            'middle_name'       => 'Watson',
            'last_name'         => 'Brown',
            'course'            => 'BS in Information Technology',
            'required_hours'    => 600,
            'company_id'        => null,
            'supervisor_id'     => null,
            'department'        => null,
            'placement_status'  => 'Unassigned',
            'ojt_status'        => 'Active',
        ]);

        // Student 5 (CS - Managed, Robert Chen) - COMPLETED
        $studentUser5 = User::create([
            'email'    => 'robert@student.bisu.edu.ph',
            'password' => Hash::make('student123'),
            'role'     => 'Student',
        ]);
        $profile5 = $studentUser5->studentProfile()->create([
            'academic_term_id'  => $activeTerm->id,
            'student_id_number' => '2023-2008',
            'first_name'        => 'Robert',
            'middle_name'       => 'S',
            'last_name'         => 'Chen',
            'course'            => 'BS in Computer Science',
            'required_hours'    => 485,
            'company_id'        => $company3->id,
            'supervisor_id'     => $supervisor3->id,
            'department'        => 'Cybersecurity Operations',
            'placement_status'  => 'Approved',
            'ojt_status'        => 'Completed',
        ]);

        // Student 6 (IT - Managed, Emily Rivera) - COMPLETED
        $studentUser6 = User::create([
            'email'    => 'emily@student.bisu.edu.ph',
            'password' => Hash::make('student123'),
            'role'     => 'Student',
        ]);
        $profile6 = $studentUser6->studentProfile()->create([
            'academic_term_id'  => $activeTerm->id,
            'student_id_number' => '2023-1090',
            'first_name'        => 'Emily',
            'middle_name'       => 'Jane',
            'last_name'         => 'Rivera',
            'course'            => 'BS in Information Technology',
            'required_hours'    => 600,
            'company_id'        => $company1->id,
            'supervisor_id'     => $supervisor1->id,
            'department'        => 'Software Engineering',
            'placement_status'  => 'Approved',
            'ojt_status'        => 'Completed',
        ]);

    }
}
