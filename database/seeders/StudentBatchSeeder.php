<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Company;
use App\Models\Course;
use App\Models\AcademicTerm;
use App\Models\StudentProfile;
use App\Models\OjtLog;
use App\Models\Requirement;
use App\Models\RequirementSubmission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class StudentBatchSeeder extends Seeder
{
    public function run(): void
    {
        // Target active term (or Term 2: 2027-2028)
        $term = AcademicTerm::current() ?? AcademicTerm::where('academic_year', '2027-2028')->first();
        if (!$term) {
            $term = AcademicTerm::firstOrCreate(
                ['academic_year' => '2027-2028', 'semester' => '1st Semester'],
                ['is_active' => true]
            );
        }

        $novaSoft = Company::where('name', 'Nova Soft Solutions')->first();
        $spaceTech = Company::where('name', 'SpaceTech Labs')->first();
        $quantumDev = Company::where('name', 'Quantum Dev')->first();

        $supervisorAlice = User::where('email', 'johndoe@company.com')->first();
        $supervisorMarcus = User::where('email', 'marcus@novasoft.com')->first();
        $supervisorBob = User::where('name', 'Bob Miller')->first();
        $supervisorCharlie = User::where('name', 'Charlie Vance')->first();

        $itCourse = Course::where('course_name', 'BS in Information Technology')->first();
        $csCourse = Course::where('course_name', 'BS in Computer Science')->first();

        $itHours = $itCourse ? $itCourse->required_hours : 486;
        $csHours = $csCourse ? $csCourse->required_hours : 320;

        $studentsData = [
            [
                'email' => 'ethan.martinez@student.bisu.edu.ph',
                'id_number' => '2024-1001',
                'first_name' => 'Ethan',
                'middle_name' => 'Allen',
                'last_name' => 'Martinez',
                'course' => 'BS in Information Technology',
                'required_hours' => $itHours,
                'company_id' => $novaSoft?->id,
                'supervisor_id' => $supervisorAlice?->id,
                'department' => 'Software Engineering',
                'placement_status' => 'Approved',
                'ojt_status' => 'Active',
                'days_rendered' => 15, // 15 days * 8h = 120 hrs
            ],
            [
                'email' => 'chloe.mendoza@student.bisu.edu.ph',
                'id_number' => '2024-2002',
                'first_name' => 'Chloe',
                'middle_name' => 'Nicole',
                'last_name' => 'Mendoza',
                'course' => 'BS in Computer Science',
                'required_hours' => $csHours,
                'company_id' => $quantumDev?->id,
                'supervisor_id' => $supervisorCharlie?->id,
                'department' => 'Cybersecurity Operations',
                'placement_status' => 'Approved',
                'ojt_status' => 'Active',
                'days_rendered' => 20, // 20 days * 8h = 160 hrs
            ],
            [
                'email' => 'joshua.reyes@student.bisu.edu.ph',
                'id_number' => '2024-1003',
                'first_name' => 'Joshua',
                'middle_name' => 'David',
                'last_name' => 'Reyes',
                'course' => 'BS in Information Technology',
                'required_hours' => $itHours,
                'company_id' => $spaceTech?->id,
                'supervisor_id' => $supervisorBob?->id,
                'department' => 'Aerospace Systems',
                'placement_status' => 'Approved',
                'ojt_status' => 'Completed',
                'days_rendered' => 61, // 486 hrs total (completed!)
            ],
            [
                'email' => 'hannah.tan@student.bisu.edu.ph',
                'id_number' => '2024-2004',
                'first_name' => 'Hannah',
                'middle_name' => 'Marie',
                'last_name' => 'Tan',
                'course' => 'BS in Computer Science',
                'required_hours' => $csHours,
                'company_id' => $quantumDev?->id,
                'supervisor_id' => $supervisorCharlie?->id,
                'department' => 'Cybersecurity Operations',
                'placement_status' => 'Approved',
                'ojt_status' => 'Completed',
                'days_rendered' => 40, // 40 * 8 = 320 hrs (completed!)
            ],
            [
                'email' => 'mark.santos@student.bisu.edu.ph',
                'id_number' => '2024-1005',
                'first_name' => 'Mark',
                'middle_name' => 'Joseph',
                'last_name' => 'Santos',
                'course' => 'BS in Information Technology',
                'required_hours' => $itHours,
                'company_id' => $novaSoft?->id,
                'supervisor_id' => $supervisorMarcus?->id,
                'department' => 'Quality Assurance',
                'placement_status' => 'Approved',
                'ojt_status' => 'Active',
                'days_rendered' => 5, // 5 * 8 = 40 hrs
            ],
            [
                'email' => 'alyssa.garcia@student.bisu.edu.ph',
                'id_number' => '2024-2006',
                'first_name' => 'Alyssa',
                'middle_name' => 'Mae',
                'last_name' => 'Garcia',
                'course' => 'BS in Computer Science',
                'required_hours' => $csHours,
                'company_id' => $quantumDev?->id,
                'supervisor_id' => $supervisorCharlie?->id,
                'department' => 'Systems Security',
                'placement_status' => 'Approved',
                'ojt_status' => 'Active',
                'days_rendered' => 10, // 10 * 8 = 80 hrs
            ],
            [
                'email' => 'gabriel.flores@student.bisu.edu.ph',
                'id_number' => '2024-1007',
                'first_name' => 'Gabriel',
                'middle_name' => 'Luis',
                'last_name' => 'Flores',
                'course' => 'BS in Information Technology',
                'required_hours' => $itHours,
                'company_id' => $spaceTech?->id,
                'supervisor_id' => $supervisorBob?->id,
                'department' => 'Network Infrastructure',
                'placement_status' => 'Approved',
                'ojt_status' => 'Active',
                'days_rendered' => 0, // 0 hrs
            ],
            [
                'email' => 'patricia.ramos@student.bisu.edu.ph',
                'id_number' => '2024-1008',
                'first_name' => 'Patricia',
                'middle_name' => 'Jane',
                'last_name' => 'Ramos',
                'course' => 'BS in Information Technology',
                'required_hours' => $itHours,
                'company_id' => $novaSoft?->id,
                'supervisor_id' => null,
                'department' => 'Frontend Engineering',
                'placement_status' => 'Pending',
                'ojt_status' => 'Active',
                'days_rendered' => 0,
            ],
            [
                'email' => 'kenneth.bautista@student.bisu.edu.ph',
                'id_number' => '2024-2009',
                'first_name' => 'Kenneth',
                'middle_name' => 'Carl',
                'last_name' => 'Bautista',
                'course' => 'BS in Computer Science',
                'required_hours' => $csHours,
                'company_id' => null,
                'supervisor_id' => null,
                'department' => null,
                'placement_status' => 'Unassigned',
                'ojt_status' => 'Active',
                'days_rendered' => 0,
            ],
            [
                'email' => 'bea.villanueva@student.bisu.edu.ph',
                'id_number' => '2024-1010',
                'first_name' => 'Bea',
                'middle_name' => 'Angela',
                'last_name' => 'Villanueva',
                'course' => 'BS in Information Technology',
                'required_hours' => $itHours,
                'company_id' => null,
                'supervisor_id' => null,
                'department' => null,
                'placement_status' => 'Unassigned',
                'ojt_status' => 'Active',
                'days_rendered' => 0,
            ],
        ];

        foreach ($studentsData as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['first_name'] . ' ' . $data['last_name'],
                    'password' => Hash::make('student123'),
                    'role' => 'Student',
                ]
            );

            $profile = StudentProfile::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'academic_term_id' => $term->id,
                    'student_id_number' => $data['id_number'],
                    'first_name' => $data['first_name'],
                    'middle_name' => $data['middle_name'],
                    'last_name' => $data['last_name'],
                    'course' => $data['course'],
                    'required_hours' => $data['required_hours'],
                    'company_id' => $data['company_id'],
                    'supervisor_id' => $data['supervisor_id'],
                    'department' => $data['department'],
                    'placement_status' => $data['placement_status'],
                    'ojt_status' => $data['ojt_status'],
                ]
            );

            // Create sample OJT logs if days_rendered > 0
            if ($data['days_rendered'] > 0) {
                $baseDate = Carbon::create(2027, 9, 1);
                $remainingTarget = $data['days_rendered'] * 8;
                if ($data['ojt_status'] === 'Completed') {
                    $remainingTarget = $data['required_hours'];
                }

                $accumulated = 0;
                for ($d = 0; $d < $data['days_rendered'] && $accumulated < $remainingTarget; $d++) {
                    $logDate = (clone $baseDate)->subDays($d);
                    if ($logDate->isWeekend()) {
                        continue;
                    }

                    $hoursThisDay = min(8, $remainingTarget - $accumulated);
                    if ($hoursThisDay <= 0) break;

                    OjtLog::updateOrCreate(
                        [
                            'user_id' => $user->id,
                            'log_date' => $logDate->format('Y-m-d'),
                        ],
                        [
                            'morning_in' => '08:00:00',
                            'morning_out' => '12:00:00',
                            'afternoon_in' => '13:00:00',
                            'afternoon_out' => $hoursThisDay == 8 ? '17:00:00' : '15:00:00',
                            'tasks_performed' => 'Hands-on training, documentation, and development activities.',
                            'hours_rendered' => $hoursThisDay,
                            'status' => 'Approved',
                            'has_overtime' => false,
                        ]
                    );

                    $accumulated += $hoursThisDay;
                }
            }
        }
    }
}
