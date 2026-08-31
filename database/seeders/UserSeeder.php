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
        // 1. Create Mock Companies
        $company1 = Company::create([
            'name' => 'Nova Soft Solutions',
            'industry' => 'Software Development',
            'location' => 'Tagbilaran City, Bohol',
            'contact_person' => 'Alice Margate',
            'contact_number' => '09123456789',
            'allocation_slots' => 5,
        ]);

        $company2 = Company::create([
            'name' => 'SpaceTech Labs',
            'industry' => 'Aerospace & Systems',
            'location' => 'Panglao, Bohol',
            'contact_person' => 'Bob Miller',
            'contact_number' => '09176543210',
            'allocation_slots' => 3,
        ]);

        $company3 = Company::create([
            'name' => 'Quantum Dev',
            'industry' => 'Cybersecurity',
            'location' => 'Talibon, Bohol',
            'contact_person' => 'Charlie Vance',
            'contact_number' => '09283334445',
            'allocation_slots' => 4,
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
            'email'    => 'coordinator@bisu.edu.ph', 
            'password' => Hash::make('admin123'),      
            'role'     => 'Coordinator',                     
        ]);

        if ($itCourse && $csCourse) {
            $coordinatorUser->managedCourses()->attach([$itCourse->id, $csCourse->id]);
        }

        // 3. Create the master Super Admin (Dean / OJT Director) who manages all departments
        User::create([
            'email'    => 'dean@bisu.edu.ph', 
            'password' => Hash::make('admin123'),      
            'role'     => 'Admin',                     
        ]);

        // 4. Create Test Supervisor Accounts for each company/department
        $supervisor1 = User::create([
            'email'      => 'johndoe@company.com', 
            'password'   => Hash::make('super123'),
            'role'       => 'Advisor', 
            'company_id' => $company1->id,
            'department' => 'Software Engineering',
        ]);

        $supervisor2 = User::create([
            'email'      => 'bob@spacetech.com', 
            'password'   => Hash::make('super123'),
            'role'       => 'Advisor', 
            'company_id' => $company2->id,
            'department' => 'Aerospace Systems',
        ]);

        $supervisor3 = User::create([
            'email'      => 'charlie@quantumdev.com', 
            'password'   => Hash::make('super123'),
            'role'       => 'Advisor', 
            'company_id' => $company3->id,
            'department' => 'Cybersecurity Operations',
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
        ]);

        // Student 2 (IT - Managed, Liam Smith)
        $studentUser2 = User::create([
            'email'    => 'liam@student.bisu.edu.ph',
            'password' => Hash::make('student123'),
            'role'     => 'Student',
        ]);
        $profile2 = $studentUser2->studentProfile()->create([
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
        ]);

        // Student 3 (CS - Managed, Sophia Johnson)
        $studentUser3 = User::create([
            'email'    => 'sophia@student.bisu.edu.ph',
            'password' => Hash::make('student123'),
            'role'     => 'Student',
        ]);
        $profile3 = $studentUser3->studentProfile()->create([
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
        ]);

        // Student 4 (Unassigned Student for testing placement submission)
        $studentUser4 = User::create([
            'email'    => 'emma@student.bisu.edu.ph',
            'password' => Hash::make('student123'),
            'role'     => 'Student',
        ]);
        $profile4 = $studentUser4->studentProfile()->create([
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
        ]);

        // Student 5 (CS - Managed, Robert Chen) - COMPLETED
        $studentUser5 = User::create([
            'email'    => 'robert@student.bisu.edu.ph',
            'password' => Hash::make('student123'),
            'role'     => 'Student',
        ]);
        $profile5 = $studentUser5->studentProfile()->create([
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
        ]);

        // Student 6 (IT - Managed, Emily Rivera) - COMPLETED
        $studentUser6 = User::create([
            'email'    => 'emily@student.bisu.edu.ph',
            'password' => Hash::make('student123'),
            'role'     => 'Student',
        ]);
        $profile6 = $studentUser6->studentProfile()->create([
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
        ]);

        // 6. Create Requirements
        $req1 = Requirement::create([
            'title' => 'Parent Consent Form',
            'description' => 'Signed liability waiver from parent or guardian.',
        ]);
        $req2 = Requirement::create([
            'title' => 'Medical Certificate',
            'description' => 'Medical clearance stating fit-to-work status.',
        ]);
        $req3 = Requirement::create([
            'title' => 'Weekly Logbook W1',
            'description' => 'Rendered hours logbook for Week 1.',
        ]);
        $req4 = Requirement::create([
            'title' => 'Weekly Logbook W2',
            'description' => 'Rendered hours logbook for Week 2.',
        ]);

        // Associate requirements with courses
        if ($itCourse && $csCourse) {
            $req1->courses()->sync([$itCourse->id, $csCourse->id]);
            $req2->courses()->sync([$itCourse->id, $csCourse->id]);
            $req3->courses()->sync([$itCourse->id, $csCourse->id]);
            $req4->courses()->sync([$itCourse->id]);
        }

        // 7. Create Requirement Submissions
        // Dexter: Consent (Approved), Logbook W1 (Pending)
        RequirementSubmission::create([
            'requirement_id' => $req1->id,
            'user_id' => $studentUser1->id,
            'file_path' => 'requirements/dexter_consent.pdf',
            'status' => 'Approved',
        ]);
        RequirementSubmission::create([
            'requirement_id' => $req3->id,
            'user_id' => $studentUser1->id,
            'file_path' => 'requirements/dexter_logbook_w1.pdf',
            'status' => 'Pending',
            'created_at' => now()->subHours(2), // 2 hours ago
        ]);

        // Liam: Medical (Pending)
        RequirementSubmission::create([
            'requirement_id' => $req2->id,
            'user_id' => $studentUser2->id,
            'file_path' => 'requirements/liam_medical.pdf',
            'status' => 'Pending',
            'created_at' => now()->subMinutes(45), // 45 mins ago
        ]);

        // Sophia: Weekly Logbook W1 (Rejected)
        RequirementSubmission::create([
            'requirement_id' => $req3->id,
            'user_id' => $studentUser3->id,
            'file_path' => 'requirements/sophia_logbook_w1.pdf',
            'status' => 'Rejected',
            'remarks' => 'Missing supervisor signature on Page 3.',
        ]);

        // Robert Chen: Consent, Medical, Logbook W1 (All Approved)
        RequirementSubmission::create([
            'requirement_id' => $req1->id,
            'user_id' => $studentUser5->id,
            'file_path' => 'requirements/robert_consent.pdf',
            'status' => 'Approved',
        ]);
        RequirementSubmission::create([
            'requirement_id' => $req2->id,
            'user_id' => $studentUser5->id,
            'file_path' => 'requirements/robert_medical.pdf',
            'status' => 'Approved',
        ]);
        RequirementSubmission::create([
            'requirement_id' => $req3->id,
            'user_id' => $studentUser5->id,
            'file_path' => 'requirements/robert_logbook_w1.pdf',
            'status' => 'Approved',
        ]);

        // Emily Rivera: Consent, Medical, Logbook W1, Logbook W2 (All Approved)
        RequirementSubmission::create([
            'requirement_id' => $req1->id,
            'user_id' => $studentUser6->id,
            'file_path' => 'requirements/emily_consent.pdf',
            'status' => 'Approved',
        ]);
        RequirementSubmission::create([
            'requirement_id' => $req2->id,
            'user_id' => $studentUser6->id,
            'file_path' => 'requirements/emily_medical.pdf',
            'status' => 'Approved',
        ]);
        RequirementSubmission::create([
            'requirement_id' => $req3->id,
            'user_id' => $studentUser6->id,
            'file_path' => 'requirements/emily_logbook_w1.pdf',
            'status' => 'Approved',
        ]);
        RequirementSubmission::create([
            'requirement_id' => $req4->id,
            'user_id' => $studentUser6->id,
            'file_path' => 'requirements/emily_logbook_w2.pdf',
            'status' => 'Approved',
        ]);

        // 8. Create OJT Logs (Rendered Hours)
        // Dexter Vale: 4 Approved logs (32 hours), 1 Pending (8 hours)
        for ($i = 1; $i <= 4; $i++) {
            OjtLog::create([
                'user_id' => $studentUser1->id,
                'log_date' => "2026-08-" . sprintf("%02d", $i),
                'hours_rendered' => 8.0,
                'status' => 'Approved',
                'tasks_performed' => 'Assisted in systems development and integration testing.',
            ]);
        }
        OjtLog::create([
            'user_id' => $studentUser1->id,
            'log_date' => '2026-08-05',
            'hours_rendered' => 8.0,
            'status' => 'Pending',
            'tasks_performed' => 'Working on coordinator dashboard features.',
        ]);

        // Liam Smith: 5 Approved logs (40 hours)
        for ($i = 1; $i <= 5; $i++) {
            OjtLog::create([
                'user_id' => $studentUser2->id,
                'log_date' => "2026-08-" . sprintf("%02d", $i),
                'hours_rendered' => 8.0,
                'status' => 'Approved',
                'tasks_performed' => 'Aerospace hardware alignment check and calibration.',
            ]);
        }

        // Sophia Johnson: 3 Approved logs (24 hours)
        for ($i = 1; $i <= 3; $i++) {
            OjtLog::create([
                'user_id' => $studentUser3->id,
                'log_date' => "2026-08-" . sprintf("%02d", $i),
                'hours_rendered' => 8.0,
                'status' => 'Approved',
                'tasks_performed' => 'Reviewed security protocols and audit reports.',
            ]);
        }

        // Emma Watson (CpE): 6 Approved logs (48 hours)
        for ($i = 1; $i <= 6; $i++) {
            OjtLog::create([
                'user_id' => $studentUser4->id,
                'log_date' => "2026-08-" . sprintf("%02d", $i),
                'hours_rendered' => 8.0,
                'status' => 'Approved',
                'tasks_performed' => 'Microcontroller assembly and debugging.',
            ]);
        }

        // Seed logs for Robert Chen
        // June 2026 (20 logs, 160 hrs)
        for ($day = 1; $day <= 20; $day++) {
            OjtLog::create([
                'user_id' => $studentUser5->id,
                'log_date' => "2026-06-" . sprintf("%02d", $day),
                'hours_rendered' => 8.0,
                'status' => 'Approved',
                'tasks_performed' => 'Reviewed security protocols and audit reports.',
            ]);
        }
        // July 2026 (20 logs, 160 hrs)
        for ($day = 1; $day <= 20; $day++) {
            OjtLog::create([
                'user_id' => $studentUser5->id,
                'log_date' => "2026-07-" . sprintf("%02d", $day),
                'hours_rendered' => 8.0,
                'status' => 'Approved',
                'tasks_performed' => 'Performed network penetration testing.',
            ]);
        }
        // August 2026 (21 logs, 168 hrs)
        for ($day = 1; $day <= 21; $day++) {
            OjtLog::create([
                'user_id' => $studentUser5->id,
                'log_date' => "2026-08-" . sprintf("%02d", $day),
                'hours_rendered' => 8.0,
                'status' => 'Approved',
                'tasks_performed' => 'Configured firewall rules and completed reports.',
            ]);
        }

        // Seed logs for Emily Rivera
        // June 2026 (25 logs, 200 hrs)
        for ($day = 1; $day <= 25; $day++) {
            OjtLog::create([
                'user_id' => $studentUser6->id,
                'log_date' => "2026-06-" . sprintf("%02d", $day),
                'hours_rendered' => 8.0,
                'status' => 'Approved',
                'tasks_performed' => 'Assisted in front-end development and layouts.',
            ]);
        }
        // July 2026 (25 logs, 200 hrs)
        for ($day = 1; $day <= 25; $day++) {
            OjtLog::create([
                'user_id' => $studentUser6->id,
                'log_date' => "2026-07-" . sprintf("%02d", $day),
                'hours_rendered' => 8.0,
                'status' => 'Approved',
                'tasks_performed' => 'Designed database schemas and ran migrations.',
            ]);
        }
        // August 2026 (26 logs, 208 hrs)
        for ($day = 1; $day <= 26; $day++) {
            OjtLog::create([
                'user_id' => $studentUser6->id,
                'log_date' => "2026-08-" . sprintf("%02d", $day),
                'hours_rendered' => 8.0,
                'status' => 'Approved',
                'tasks_performed' => 'Conducted API testing and prepared documentation.',
            ]);
        }

        // 9. Seed Evaluations (Supervisor evaluates Robert Chen & Emily Rivera)
        // Robert Chen — evaluated by supervisor at Nova Soft Solutions (company1)
        // But Robert is in company3 (Quantum Dev), so we need company3's supervisor
        // For demo: use $supervisor (company1) to evaluate Dexter Vale (also company1)
        StudentEvaluation::create([
            'student_id'        => $profile1->id, // Dexter Vale (Nova Soft)
            'supervisor_id'     => $supervisor->id,
            'technical_score'   => 4.5,
            'soft_skills_score' => 4.0,
            'attitude_score'    => 5.0,
            'comments'          => 'Dexter is a quick learner and shows excellent initiative on projects.',
            'evaluated_at'      => now()->subDays(5),
        ]);

        StudentEvaluation::create([
            'student_id'        => $profile2->id, // Liam Smith (SpaceTech, company2)
            'supervisor_id'     => $supervisor->id,
            'technical_score'   => 3.5,
            'soft_skills_score' => 4.5,
            'attitude_score'    => 4.0,
            'comments'          => 'Liam communicates well and is punctual, but needs more hands-on practice.',
            'evaluated_at'      => now()->subDays(3),
        ]);
    }
}
