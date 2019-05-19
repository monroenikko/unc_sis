<?php

namespace App\Http\Controllers\Faculty;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Grade_sheet_first;
use App\Grade_sheet_firstsem;
use PDF;

class GradeSheetController extends Controller
{
    
    public function index (Request $request) 
    {
         $FacultyInformation = \App\FacultyInformation::where('user_id', \Auth::user()->id)->first();
        // return json_encode(['FacultyInformation' => $FacultyInformation, 'Auth' => \Auth::user()]);
        $SchoolYear = \App\SchoolYear::where('status', 1)->where('current', 1)->orderBy('current', 'ASC')->orderBy('school_year', 'ASC')->get();
        
        $ClassSubjectDetail = \App\ClassSubjectDetail::join('class_details', 'class_details.id', '=', 'class_subject_details.class_details_id')
        ->join('subject_details', 'subject_details.id', '=', 'class_subject_details.subject_id')
        ->join('section_details', 'section_details.id', '=', 'class_details.section_id')
        // ->whereRaw('class_subject_details.id = '. $request->search_class_subject)
        // ->whereRaw('class_details.id = '. $search_class_subject[1])
        ->where('faculty_id', $FacultyInformation->id)
        // ->where('class_details.school_year_id', $SchoolYear->id)
        ->where('class_subject_details.status', '!=', 0)
        ->where('class_details.status', '!=', 0)
        ->select(\DB::raw('
            class_subject_details.id,
            class_subject_details.class_schedule,
            class_subject_details.class_time_from,
            class_subject_details.class_time_to,
            class_subject_details.class_days,
            subject_details.subject_code,
            subject_details.subject,
            section_details.section,
            class_details.grade_level,
            class_subject_details.status as grading_status,
            class_subject_details.sem
        '))
        ->first();
        
        return view('control_panel_faculty.student_grade_sheet.index', compact('SchoolYear','ClassSubjectDetail'));
    }

    public function list_students_by_class (Request $request)
    {
        $FacultyInformation = \App\FacultyInformation::where('user_id', \Auth::user()->id)->first();
        
        $EnrollmentMale = \App\Enrollment::join('class_subject_details', 'class_subject_details.class_details_id', '=', 'enrollments.class_details_id')
                    ->join('class_details', 'class_details.id', '=', 'class_subject_details.class_details_id')
                    ->join('student_informations', 'student_informations.id', '=', 'enrollments.student_information_id')
                    // ->join('student_enrolled_subjects', 'student_enrolled_subjects.subject_id', '=', 'class_subject_details.subject_id')
                    // ->join('student_enrolled_subjects', 'student_enrolled_subjects.subject_id', '=', 'class_subject_details.subject_id')
                    // ->join('student_enrolled_subjects', 'student_enrolled_subjects.enrollments_id', '=', 'enrollments.id')
                    ->join('student_enrolled_subjects', function ($join) {
                        $join->on('student_enrolled_subjects.enrollments_id', '=', 'enrollments.id');
                        // ->on('student_enrolled_subjects.subject_id', '=', 'class_subject_details.subject_id');
                    })
                    ->whereRaw('class_subject_details.faculty_id = '. $FacultyInformation->id)
                    ->whereRaw('class_subject_details.id = '. $request->search_class_subject)
                    ->whereRaw('student_enrolled_subjects.class_subject_details_id = '. $request->search_class_subject)
                    // ->whereRaw('class_details.id = '. $search_class_subject[1])
                    ->whereRaw('class_details.current = 1')
                    ->whereRaw('class_details.status != 0')
                    ->whereRaw('student_informations.gender = 1')
                    ->select(\DB::raw("
                        student_informations.id,
                        class_details.id as class_details_id,
                        CONCAT(student_informations.last_name, ' ', student_informations.first_name, ' ', student_informations.middle_name) as student_name,
                        student_informations.gender,
                        student_enrolled_subjects.id as student_enrolled_subject_id,
                        enrollments.id as enrollment_id,
                        student_enrolled_subjects.fir_g,
                        student_enrolled_subjects.fir_g_status,
                        student_enrolled_subjects.sec_g,
                        student_enrolled_subjects.sec_g_status,
                        student_enrolled_subjects.thi_g,
                        student_enrolled_subjects.thi_g_status,
                        student_enrolled_subjects.fou_g,
                        student_enrolled_subjects.fou_g_status,
                        student_enrolled_subjects.fin_g,
                        student_enrolled_subjects.fin_g_status,
                        class_subject_details.status as grading_status,
                        class_subject_details.sem
                    "))
                    ->orderBy('student_name',  'ASC')
                    ->paginate(100);
                    
                    
        $EnrollmentFemale = \App\Enrollment::join('class_subject_details', 'class_subject_details.class_details_id', '=', 'enrollments.class_details_id')
            ->join('class_details', 'class_details.id', '=', 'class_subject_details.class_details_id')
            ->join('student_informations', 'student_informations.id', '=', 'enrollments.student_information_id')
            // ->join('student_enrolled_subjects', 'student_enrolled_subjects.subject_id', '=', 'class_subject_details.subject_id')
            // ->join('student_enrolled_subjects', 'student_enrolled_subjects.subject_id', '=', 'class_subject_details.subject_id')
            // ->join('student_enrolled_subjects', 'student_enrolled_subjects.enrollments_id', '=', 'enrollments.id')
            ->join('student_enrolled_subjects', function ($join) {
                $join->on('student_enrolled_subjects.enrollments_id', '=', 'enrollments.id');
                // ->on('student_enrolled_subjects.subject_id', '=', 'class_subject_details.subject_id');
            })
            ->whereRaw('class_subject_details.faculty_id = '. $FacultyInformation->id)
            ->whereRaw('class_subject_details.id = '. $request->search_class_subject)
            ->whereRaw('student_enrolled_subjects.class_subject_details_id = '. $request->search_class_subject)
            // ->whereRaw('class_details.id = '. $search_class_subject[1])
            ->whereRaw('class_details.current = 1')
            ->whereRaw('class_details.status != 0')
            ->whereRaw('student_informations.gender = 2')
            ->select(\DB::raw("
                student_informations.id,
                class_details.id as class_details_id,
                CONCAT(student_informations.last_name, ' ', student_informations.first_name, ' ', student_informations.middle_name) as student_name,
                student_informations.gender,
                student_enrolled_subjects.id as student_enrolled_subject_id,
                enrollments.id as enrollment_id,
                student_enrolled_subjects.fir_g,
                student_enrolled_subjects.fir_g_status,
                student_enrolled_subjects.sec_g,
                student_enrolled_subjects.sec_g_status,
                student_enrolled_subjects.thi_g,
                student_enrolled_subjects.thi_g_status,
                student_enrolled_subjects.fou_g,
                student_enrolled_subjects.fou_g_status,
                student_enrolled_subjects.fin_g,
                student_enrolled_subjects.fin_g_status,
                class_subject_details.status as grading_status,
                class_subject_details.sem
            "))
            ->orderBy('student_name', 'ASC')
            ->paginate(100);
        // return json_encode($Enrollment);
        // $ClassSubjectDetail_status = \App\ClassSubjectDetail::where('id', $request->search_class_subject)->first();
        $ClassSubjectDetail = \App\ClassSubjectDetail::join('class_details', 'class_details.id', '=', 'class_subject_details.class_details_id')
            ->join('subject_details', 'subject_details.id', '=', 'class_subject_details.subject_id')
            ->join('section_details', 'section_details.id', '=', 'class_details.section_id')
            ->whereRaw('class_subject_details.id = '. $request->search_class_subject)
            // ->whereRaw('class_details.id = '. $search_class_subject[1])
            ->where('faculty_id', $FacultyInformation->id)
            ->where('class_details.school_year_id', $request->search_sy)
            ->where('class_subject_details.status', '!=', 0)
            ->where('class_details.status', '!=', 0)
            ->select(\DB::raw('
                class_subject_details.id,
                class_subject_details.class_schedule,
                class_subject_details.class_time_from,
                class_subject_details.class_time_to,
                class_subject_details.class_days,
                subject_details.subject_code,
                subject_details.subject,
                section_details.section,
                class_details.grade_level,
                class_subject_details.status as grading_status,
                class_subject_details.sem
            '))
            ->first();
        // return json_encode($ClassSubjectDetail);
        return view('control_panel_faculty.student_grade_sheet.partials.data_list', compact('EnrollmentFemale', 'EnrollmentMale', 'ClassSubjectDetail'))->render();
    }

    public function list_students_by_class_print (Request $request) 
    {
        
        $FacultyInformation = \App\FacultyInformation::where('user_id', \Auth::user()->id)->first();
        // return json_encode(['FacultyInformation' => $FacultyInformation, 'req' => $request->all()]);
        $EnrollmentMale = \App\Enrollment::join('class_subject_details', 'class_subject_details.class_details_id', '=', 'enrollments.class_details_id')
                    ->join('class_details', 'class_details.id', '=', 'class_subject_details.class_details_id')
                    ->join('student_informations', 'student_informations.id', '=', 'enrollments.student_information_id')
                    // ->join('student_enrolled_subjects', 'student_enrolled_subjects.subject_id', '=', 'class_subject_details.subject_id')
                    // ->join('student_enrolled_subjects', 'student_enrolled_subjects.subject_id', '=', 'class_subject_details.subject_id')
                    // ->join('student_enrolled_subjects', 'student_enrolled_subjects.enrollments_id', '=', 'enrollments.id')
                    ->join('student_enrolled_subjects', function ($join) {
                        $join->on('student_enrolled_subjects.enrollments_id', '=', 'enrollments.id');
                        // ->on('student_enrolled_subjects.subject_id', '=', 'class_subject_details.subject_id');
                    })
                    ->whereRaw('class_subject_details.faculty_id = '. $FacultyInformation->id)
                    ->whereRaw('class_subject_details.id = '. $request->search_class_subject)
                    ->whereRaw('student_enrolled_subjects.class_subject_details_id = '. $request->search_class_subject)
                    // ->whereRaw('class_details.id = '. $search_class_subject[1])
                    ->whereRaw('class_details.current = 1')
                    ->whereRaw('class_details.status != 0')
                    ->whereRaw('student_informations.gender = 1')
                    ->select(\DB::raw("
                        student_informations.id,
                        class_details.id as class_details_id,
                        CONCAT(student_informations.last_name, ' ', student_informations.first_name, ' ', student_informations.middle_name) as student_name,
                        student_enrolled_subjects.id as student_enrolled_subject_id,
                        enrollments.id as enrollment_id,
                        student_enrolled_subjects.fir_g,
                        student_enrolled_subjects.fir_g_status,
                        student_enrolled_subjects.sec_g,
                        student_enrolled_subjects.sec_g_status,
                        student_enrolled_subjects.thi_g,
                        student_enrolled_subjects.thi_g_status,
                        student_enrolled_subjects.fou_g,
                        student_enrolled_subjects.fou_g_status,
                        student_enrolled_subjects.fin_g,
                        student_enrolled_subjects.fin_g_status,
                        class_subject_details.status as grading_status 
                    "))
                    ->orderBy('student_informations.gender', 'ASC')
                    ->orderBy('student_name', 'ASC')
                    ->get();

        $EnrollmentFemale = \App\Enrollment::join('class_subject_details', 'class_subject_details.class_details_id', '=', 'enrollments.class_details_id')
                    ->join('class_details', 'class_details.id', '=', 'class_subject_details.class_details_id')
                    ->join('student_informations', 'student_informations.id', '=', 'enrollments.student_information_id')
                    // ->join('student_enrolled_subjects', 'student_enrolled_subjects.subject_id', '=', 'class_subject_details.subject_id')
                    // ->join('student_enrolled_subjects', 'student_enrolled_subjects.subject_id', '=', 'class_subject_details.subject_id')
                    // ->join('student_enrolled_subjects', 'student_enrolled_subjects.enrollments_id', '=', 'enrollments.id')
                    ->join('student_enrolled_subjects', function ($join) {
                        $join->on('student_enrolled_subjects.enrollments_id', '=', 'enrollments.id');
                        // ->on('student_enrolled_subjects.subject_id', '=', 'class_subject_details.subject_id');
                    })
                    ->whereRaw('class_subject_details.faculty_id = '. $FacultyInformation->id)
                    ->whereRaw('class_subject_details.id = '. $request->search_class_subject)
                    ->whereRaw('student_enrolled_subjects.class_subject_details_id = '. $request->search_class_subject)
                    // ->whereRaw('class_details.id = '. $search_class_subject[1])
                    ->whereRaw('class_details.current = 1')
                    ->whereRaw('class_details.status != 0')
                    ->whereRaw('student_informations.gender = 2')
                    ->select(\DB::raw("
                        student_informations.id,
                        class_details.id as class_details_id,
                        CONCAT(student_informations.last_name, ' ', student_informations.first_name, ' ', student_informations.middle_name) as student_name,
                        student_enrolled_subjects.id as student_enrolled_subject_id,
                        enrollments.id as enrollment_id,
                        student_enrolled_subjects.fir_g,
                        student_enrolled_subjects.fir_g_status,
                        student_enrolled_subjects.sec_g,
                        student_enrolled_subjects.sec_g_status,
                        student_enrolled_subjects.thi_g,
                        student_enrolled_subjects.thi_g_status,
                        student_enrolled_subjects.fou_g,
                        student_enrolled_subjects.fou_g_status,
                        student_enrolled_subjects.fin_g,
                        student_enrolled_subjects.fin_g_status,
                        class_subject_details.status as grading_status 
                    "))
                    ->orderBy('student_informations.gender', 'ASC')
                    ->orderBy('student_name', 'ASC')
                    ->get();
                    
        $ClassSubjectDetail = \App\ClassSubjectDetail::join('class_details', 'class_details.id', '=', 'class_subject_details.class_details_id')
            ->join('subject_details', 'subject_details.id', '=', 'class_subject_details.subject_id')
            ->join('section_details', 'section_details.id', '=', 'class_details.section_id')
            ->join('school_years', 'school_years.id', '=' ,'class_details.school_year_id')
            ->whereRaw('class_subject_details.id = '. $request->search_class_subject)
            // ->whereRaw('class_details.id = '. $search_class_subject[1])
            ->where('faculty_id', $FacultyInformation->id)
            ->where('class_details.school_year_id', $request->search_sy)
            ->where('class_subject_details.status', '!=', 0)
            ->where('class_details.status', '!=', 0)
            ->select(\DB::raw('
                class_subject_details.id,
                class_subject_details.class_schedule,
                class_subject_details.class_time_from,
                class_subject_details.class_time_to,
                class_subject_details.class_days,
                subject_details.subject_code,
                subject_details.subject,
                section_details.section,
                class_details.grade_level,
                class_subject_details.status as grading_status,
                school_years.school_year
            '))
            ->first();
        if (count($EnrollmentFemale) == 0 || count($EnrollmentMale) == 0) {
            return "invalid request";
        }
        return view('control_panel_faculty.student_grade_sheet.partials.print', compact('EnrollmentFemale', 'EnrollmentMale', 'ClassSubjectDetail', 'FacultyInformation'));
        $pdf = \PDF::loadView('control_panel_faculty.student_grade_sheet.partials.print', compact('EnrollmentFemale', 'EnrollmentMale', 'ClassSubjectDetail', 'FacultyInformation'));
        $pdf->setPaper('Letter', 'portrait');
        return $pdf->stream();
    }
    



    public function list_class_subject_details (Request $request) 
    {
        $FacultyInformation = \App\FacultyInformation::where('user_id', \Auth::user()->id)->first();  
        $ClassSubjectDetail = \App\ClassSubjectDetail::join('class_details', 'class_details.id', '=', 'class_subject_details.class_details_id')
            ->join('subject_details', 'subject_details.id', '=', 'class_subject_details.subject_id')
            ->join('section_details', 'section_details.id', '=', 'class_details.section_id')
            ->where('faculty_id', $FacultyInformation->id)
            ->where('class_details.school_year_id', $request->search_sy)
            ->where('class_subject_details.status', '!=', 0)
            ->where('class_details.status', '!=', 0)
            ->select(\DB::raw('  
                class_subject_details.id,
                class_subject_details.class_details_id,
                class_subject_details.class_schedule,
                class_subject_details.class_time_from,
                class_subject_details.class_time_to,
                class_subject_details.class_days,
                class_subject_details.sem,
                subject_details.subject_code,
                subject_details.subject,
                section_details.section,
                class_details.grade_level
            '))
            ->get();        
                
            $class_details_elements = '<option value="">Select Class Subject</option>';
            if ($ClassSubjectDetail) 
            {
                foreach ($ClassSubjectDetail as $data) 
                {
                    $class_details_elements .= '<option value="'. $data->id .'">'.  ' '. $data->subject_code . ' ' . $data->subject . ' - Grade ' .  $data->grade_level . ' Section ' . $data->section . '</option>';
                }
    
                return $class_details_elements;
            }
            return $class_details_elements;
                      
    }

    public function semester()
    {
        $class_details_elements = '<option value="">Select Semester</option>';
        $class_details_elements .= '<option value="1st">First Semester</option>';
        $class_details_elements .= '<option value="2nd">Second Semester</option>'; 
        return $class_details_elements;        
    }

    public function list_class_subject_details1 (Request $request) 
    {
        if($request->search_semester == '1st')
        {
            $FacultyInformation = \App\FacultyInformation::where('user_id', \Auth::user()->id)->first();  
            $ClassSubjectDetail = \App\ClassSubjectDetail::join('class_details', 'class_details.id', '=', 'class_subject_details.class_details_id')
                ->join('subject_details', 'subject_details.id', '=', 'class_subject_details.subject_id')
                ->join('section_details', 'section_details.id', '=', 'class_details.section_id')
                ->where('faculty_id', $FacultyInformation->id)
                ->where('class_details.school_year_id', $request->search_sy1)
                ->where('class_subject_details.status', '!=', 0)
                ->where('class_details.status', '!=', 0)
                ->where('class_subject_details.sem', 1)
                ->select(\DB::raw('  
                    class_subject_details.id,
                    class_subject_details.class_details_id,
                    class_subject_details.class_schedule,
                    class_subject_details.class_time_from,
                    class_subject_details.class_time_to,
                    class_subject_details.class_days,
                    class_subject_details.sem,
                    subject_details.subject_code,
                    subject_details.subject,
                    section_details.section,
                    class_details.grade_level
                '))
                ->get();
            
            
            
                $class_details_elements = '<option value="">Select Class Subject</option>';
                if ($ClassSubjectDetail) 
                {
                    foreach ($ClassSubjectDetail as $data) 
                    {
                        $class_details_elements .= '<option value="'. $data->id .'">'. 'Semester-'. $data->sem . ' '. $data->subject_code . ' ' . $data->subject . ' - Grade ' .  $data->grade_level . ' Section ' . $data->section . '</option>';
                    }
        
                    return $class_details_elements;
                }
                return $class_details_elements;
          
           
        }
        else 
        {
            $FacultyInformation = \App\FacultyInformation::where('user_id', \Auth::user()->id)->first();  
            $ClassSubjectDetail = \App\ClassSubjectDetail::join('class_details', 'class_details.id', '=', 'class_subject_details.class_details_id')
                ->join('subject_details', 'subject_details.id', '=', 'class_subject_details.subject_id')
                ->join('section_details', 'section_details.id', '=', 'class_details.section_id')
                ->where('faculty_id', $FacultyInformation->id)
                ->where('class_details.school_year_id', $request->search_sy1)
                ->where('class_subject_details.status', '!=', 0)
                ->where('class_details.status', '!=', 0)
                ->where('class_subject_details.sem', 2)
                ->select(\DB::raw('  
                    class_subject_details.id,
                    class_subject_details.class_details_id,
                    class_subject_details.class_schedule,
                    class_subject_details.class_time_from,
                    class_subject_details.class_time_to,
                    class_subject_details.class_days,
                    class_subject_details.sem,
                    subject_details.subject_code,
                    subject_details.subject,
                    section_details.section,
                    class_details.grade_level
                '))
                ->get();
            
            
            
                $class_details_elements = '<option value="">Select Class Subject</option>';
                if ($ClassSubjectDetail) 
                {
                    foreach ($ClassSubjectDetail as $data) 
                    {
                        $class_details_elements .= '<option value="'. $data->id .'">'. 'Semester-'. $data->sem . ' '. $data->subject_code . ' ' . $data->subject . ' - Grade ' .  $data->grade_level . ' Section ' . $data->section . '</option>';
                    }
        
                    return $class_details_elements;
                }
                return $class_details_elements;
          
        }
        
        
    }

    public function list_students_by_class1 (Request $request) 
    {
        $FacultyInformation = \App\FacultyInformation::where('user_id', \Auth::user()->id)->first();
        
        $EnrollmentMale = \App\Enrollment::join('class_subject_details', 'class_subject_details.class_details_id', '=', 'enrollments.class_details_id')
                    ->join('class_details', 'class_details.id', '=', 'class_subject_details.class_details_id')
                    ->join('student_informations', 'student_informations.id', '=', 'enrollments.student_information_id')
                    // ->join('student_enrolled_subjects', 'student_enrolled_subjects.subject_id', '=', 'class_subject_details.subject_id')
                    // ->join('student_enrolled_subjects', 'student_enrolled_subjects.subject_id', '=', 'class_subject_details.subject_id')
                    // ->join('student_enrolled_subjects', 'student_enrolled_subjects.enrollments_id', '=', 'enrollments.id')
                    ->join('student_enrolled_subjects', function ($join) {
                        $join->on('student_enrolled_subjects.enrollments_id', '=', 'enrollments.id');
                        // ->on('student_enrolled_subjects.subject_id', '=', 'class_subject_details.subject_id');
                    })
                    ->whereRaw('class_subject_details.faculty_id = '. $FacultyInformation->id)
                    ->whereRaw('class_subject_details.id = '. $request->search_class_subject_sem)
                    ->whereRaw('student_enrolled_subjects.class_subject_details_id = '. $request->search_class_subject_sem)
                    // ->whereRaw('class_details.id = '. $search_class_subject[1])
                    ->whereRaw('class_details.current = 1')
                    ->whereRaw('class_details.status != 0')
                    ->whereRaw('student_informations.gender = 1')
                    ->select(\DB::raw("
                        student_informations.id,
                        class_details.id as class_details_id,
                        CONCAT(student_informations.last_name, ' ', student_informations.first_name, ' ', student_informations.middle_name) as student_name,
                        student_informations.gender,
                        student_enrolled_subjects.id as student_enrolled_subject_id,
                        enrollments.id as enrollment_id,
                        student_enrolled_subjects.fir_g,
                        student_enrolled_subjects.fir_g_status,
                        student_enrolled_subjects.sec_g,
                        student_enrolled_subjects.sec_g_status,
                        student_enrolled_subjects.thi_g,
                        student_enrolled_subjects.thi_g_status,
                        student_enrolled_subjects.fou_g,
                        student_enrolled_subjects.fou_g_status,
                        student_enrolled_subjects.fin_g,
                        student_enrolled_subjects.fin_g_status,
                        class_subject_details.status as grading_status,
                        class_subject_details.sem
                    "))
                    ->orderBy('student_name',  'ASC')
                    ->paginate(100);
                    
                    
        $EnrollmentFemale = \App\Enrollment::join('class_subject_details', 'class_subject_details.class_details_id', '=', 'enrollments.class_details_id')
            ->join('class_details', 'class_details.id', '=', 'class_subject_details.class_details_id')
            ->join('student_informations', 'student_informations.id', '=', 'enrollments.student_information_id')
            // ->join('student_enrolled_subjects', 'student_enrolled_subjects.subject_id', '=', 'class_subject_details.subject_id')
            // ->join('student_enrolled_subjects', 'student_enrolled_subjects.subject_id', '=', 'class_subject_details.subject_id')
            // ->join('student_enrolled_subjects', 'student_enrolled_subjects.enrollments_id', '=', 'enrollments.id')
            ->join('student_enrolled_subjects', function ($join) {
                $join->on('student_enrolled_subjects.enrollments_id', '=', 'enrollments.id');
                // ->on('student_enrolled_subjects.subject_id', '=', 'class_subject_details.subject_id');
            })
            ->whereRaw('class_subject_details.faculty_id = '. $FacultyInformation->id)
            ->whereRaw('class_subject_details.id = '. $request->search_class_subject_sem)
            ->whereRaw('student_enrolled_subjects.class_subject_details_id = '. $request->search_class_subject_sem)
            // ->whereRaw('class_details.id = '. $search_class_subject[1])
            ->whereRaw('class_details.current = 1')
            ->whereRaw('class_details.status != 0')
            ->whereRaw('student_informations.gender = 2')
            ->select(\DB::raw("
                student_informations.id,
                class_details.id as class_details_id,
                CONCAT(student_informations.last_name, ' ', student_informations.first_name, ' ', student_informations.middle_name) as student_name,
                student_informations.gender,
                student_enrolled_subjects.id as student_enrolled_subject_id,
                enrollments.id as enrollment_id,
                student_enrolled_subjects.fir_g,
                student_enrolled_subjects.fir_g_status,
                student_enrolled_subjects.sec_g,
                student_enrolled_subjects.sec_g_status,
                student_enrolled_subjects.thi_g,
                student_enrolled_subjects.thi_g_status,
                student_enrolled_subjects.fou_g,
                student_enrolled_subjects.fou_g_status,
                student_enrolled_subjects.fin_g,
                student_enrolled_subjects.fin_g_status,
                class_subject_details.status as grading_status,
                class_subject_details.sem
            "))
            ->orderBy('student_name', 'ASC')
            ->paginate(100);
        // return json_encode($Enrollment);
        // $ClassSubjectDetail_status = \App\ClassSubjectDetail::where('id', $request->search_class_subject)->first();
        $ClassSubjectDetail = \App\ClassSubjectDetail::join('class_details', 'class_details.id', '=', 'class_subject_details.class_details_id')
            ->join('subject_details', 'subject_details.id', '=', 'class_subject_details.subject_id')
            ->join('section_details', 'section_details.id', '=', 'class_details.section_id')
            ->whereRaw('class_subject_details.id = '. $request->search_class_subject_sem)
            // ->whereRaw('class_details.id = '. $search_class_subject[1])
            ->where('faculty_id', $FacultyInformation->id)
            ->where('class_details.school_year_id', $request->search_sy1)
            ->where('class_subject_details.status', '!=', 0)
            ->where('class_details.status', '!=', 0)
            ->select(\DB::raw('
                class_subject_details.id,
                class_subject_details.class_schedule,
                class_subject_details.class_time_from,
                class_subject_details.class_time_to,
                class_subject_details.class_days,
                subject_details.subject_code,
                subject_details.subject,
                section_details.section,
                class_details.grade_level,
                class_subject_details.status as grading_status,
                class_subject_details.sem
            '))
            ->first();
        // return json_encode($ClassSubjectDetail);
        $semester;
        if($request->search_semester == '1st')
        {
            $semester = 1;
        }
        else 
        {
            $semester = 2;
        }
        return view('control_panel_faculty.student_grade_sheet.partials.data_list', compact('EnrollmentFemale', 'EnrollmentMale', 'ClassSubjectDetail','semester'))->render();
    }

    public function list_students_by_class_print_senior (Request $request) 
    {
        
        $FacultyInformation = \App\FacultyInformation::where('user_id', \Auth::user()->id)->first();
        // return json_encode(['FacultyInformation' => $FacultyInformation, 'req' => $request->all()]);
        $EnrollmentMale = \App\Enrollment::join('class_subject_details', 'class_subject_details.class_details_id', '=', 'enrollments.class_details_id')
                    ->join('class_details', 'class_details.id', '=', 'class_subject_details.class_details_id')
                    ->join('student_informations', 'student_informations.id', '=', 'enrollments.student_information_id')
                    // ->join('student_enrolled_subjects', 'student_enrolled_subjects.subject_id', '=', 'class_subject_details.subject_id')
                    // ->join('student_enrolled_subjects', 'student_enrolled_subjects.subject_id', '=', 'class_subject_details.subject_id')
                    // ->join('student_enrolled_subjects', 'student_enrolled_subjects.enrollments_id', '=', 'enrollments.id')
                    ->join('student_enrolled_subjects', function ($join) {
                        $join->on('student_enrolled_subjects.enrollments_id', '=', 'enrollments.id');
                        // ->on('student_enrolled_subjects.subject_id', '=', 'class_subject_details.subject_id');
                    })
                    ->whereRaw('class_subject_details.faculty_id = '. $FacultyInformation->id)
                    ->whereRaw('class_subject_details.id = '. $request->search_class_subject_sem)
                    ->whereRaw('student_enrolled_subjects.class_subject_details_id = '. $request->search_class_subject_sem)
                    // ->whereRaw('class_details.id = '. $search_class_subject[1])
                    ->whereRaw('class_details.current = 1')
                    ->whereRaw('class_details.status != 0')
                    ->whereRaw('student_informations.gender = 1')
                    ->select(\DB::raw("
                        student_informations.id,
                        class_details.id as class_details_id,
                        CONCAT(student_informations.last_name, ' ', student_informations.first_name, ' ', student_informations.middle_name) as student_name,
                        student_enrolled_subjects.id as student_enrolled_subject_id,
                        enrollments.id as enrollment_id,
                        student_enrolled_subjects.fir_g,
                        student_enrolled_subjects.fir_g_status,
                        student_enrolled_subjects.sec_g,
                        student_enrolled_subjects.sec_g_status,
                        student_enrolled_subjects.thi_g,
                        student_enrolled_subjects.thi_g_status,
                        student_enrolled_subjects.fou_g,
                        student_enrolled_subjects.fou_g_status,
                        student_enrolled_subjects.fin_g,
                        student_enrolled_subjects.fin_g_status,
                        class_subject_details.status as grading_status 
                    "))
                    ->orderBy('student_informations.gender', 'ASC')
                    ->orderBy('student_name', 'ASC')
                    ->get();

        $EnrollmentFemale = \App\Enrollment::join('class_subject_details', 'class_subject_details.class_details_id', '=', 'enrollments.class_details_id')
                    ->join('class_details', 'class_details.id', '=', 'class_subject_details.class_details_id')
                    ->join('student_informations', 'student_informations.id', '=', 'enrollments.student_information_id')
                    // ->join('student_enrolled_subjects', 'student_enrolled_subjects.subject_id', '=', 'class_subject_details.subject_id')
                    // ->join('student_enrolled_subjects', 'student_enrolled_subjects.subject_id', '=', 'class_subject_details.subject_id')
                    // ->join('student_enrolled_subjects', 'student_enrolled_subjects.enrollments_id', '=', 'enrollments.id')
                    ->join('student_enrolled_subjects', function ($join) {
                        $join->on('student_enrolled_subjects.enrollments_id', '=', 'enrollments.id');
                        // ->on('student_enrolled_subjects.subject_id', '=', 'class_subject_details.subject_id');
                    })
                    ->whereRaw('class_subject_details.faculty_id = '. $FacultyInformation->id)
                    ->whereRaw('class_subject_details.id = '. $request->search_class_subject_sem)
                    ->whereRaw('student_enrolled_subjects.class_subject_details_id = '. $request->search_class_subject_sem)
                    // ->whereRaw('class_details.id = '. $search_class_subject[1])
                    ->whereRaw('class_details.current = 1')
                    ->whereRaw('class_details.status != 0')
                    ->whereRaw('student_informations.gender = 2')
                    ->select(\DB::raw("
                        student_informations.id,
                        class_details.id as class_details_id,
                        CONCAT(student_informations.last_name, ' ', student_informations.first_name, ' ', student_informations.middle_name) as student_name,
                        student_enrolled_subjects.id as student_enrolled_subject_id,
                        enrollments.id as enrollment_id,
                        student_enrolled_subjects.fir_g,
                        student_enrolled_subjects.fir_g_status,
                        student_enrolled_subjects.sec_g,
                        student_enrolled_subjects.sec_g_status,
                        student_enrolled_subjects.thi_g,
                        student_enrolled_subjects.thi_g_status,
                        student_enrolled_subjects.fou_g,
                        student_enrolled_subjects.fou_g_status,
                        student_enrolled_subjects.fin_g,
                        student_enrolled_subjects.fin_g_status,
                        class_subject_details.status as grading_status 
                    "))
                    ->orderBy('student_informations.gender', 'ASC')
                    ->orderBy('student_name', 'ASC')
                    ->get();
                    
        $ClassSubjectDetail = \App\ClassSubjectDetail::join('class_details', 'class_details.id', '=', 'class_subject_details.class_details_id')
            ->join('subject_details', 'subject_details.id', '=', 'class_subject_details.subject_id')
            ->join('section_details', 'section_details.id', '=', 'class_details.section_id')
            ->join('school_years', 'school_years.id', '=' ,'class_details.school_year_id')
            ->whereRaw('class_subject_details.id = '. $request->search_class_subject_sem)
            // ->whereRaw('class_details.id = '. $search_class_subject[1])
            ->where('faculty_id', $FacultyInformation->id)
            ->where('class_details.school_year_id', $request->search_sy1)
            ->where('class_subject_details.status', '!=', 0)
            ->where('class_details.status', '!=', 0)
            ->select(\DB::raw('
                class_subject_details.id,
                class_subject_details.class_schedule,
                class_subject_details.class_time_from,
                class_subject_details.class_time_to,
                class_subject_details.class_days,
                class_subject_details.sem,
                subject_details.subject_code,
                subject_details.subject,
                section_details.section,
                class_details.grade_level,
                class_subject_details.status as grading_status,
                school_years.school_year
            '))
            ->first();

        if (count($EnrollmentFemale) == 0 || count($EnrollmentMale) == 0) {
            return "invalid request";
        }

        $semester = $request->search_semester;
        

        return view('control_panel_faculty.student_grade_sheet.partials.print_senior', compact('EnrollmentFemale', 'EnrollmentMale', 'ClassSubjectDetail', 'FacultyInformation','semester'));
        $pdf = \PDF::loadView('control_panel_faculty.student_grade_sheet.partials.print_senior', compact('EnrollmentFemale', 'EnrollmentMale', 'ClassSubjectDetail', 'FacultyInformation','semester'));
        $pdf->setPaper('Letter', 'portrait');
        return $pdf->stream();
    }

    public function temporary_save_grade(Request $request)
    {
        if (!$request->student_enrolled_subject_id || !$request->enrollment_id || !$request->grading || !$request->classSubjectDetailID ) 
        {
            return response()->json(['res_code' => 1, 'res_msg' => 'Invalid request.',]);
        } 

        $validator = \Validator::make($request->all(), [
            'grade' => 'numeric|between:0,100.00'
        ], [
            'grade.between' => 'grade is invalid. 0 - 100.00'
        ]);
        if ($validator->fails())
        {
            return response()->json(['res_code' => 1, 'res_msg' => 'grade is invalid. 0 - 100.00.', 'res_error_msg' => $validator->getMessageBag()]);
        }

        $student_enrolled_subject_id = base64_decode($request->student_enrolled_subject_id);
        $enrollment_id = base64_decode($request->enrollment_id);
        $grading = base64_decode($request->grading);
        $grade = $request->grade;

        $StudentEnrolledSubject = \App\StudentEnrolledSubject::where('id', $student_enrolled_subject_id)->where('enrollments_id', $enrollment_id)
            ->where('class_subject_details_id', $request->classSubjectDetailID)->first();

        $selectedsubjectid = \App\ClassSubjectDetail::where('id', $StudentEnrolledSubject->class_subject_details_id)->first();

        $SelectedSubject = \App\SubjectDetail::where('id', $selectedsubjectid->subject_id)
                ->first();

                $ClassSubjectDetail = \App\ClassSubjectDetail::join('class_details', 'class_details.id', '=', 'class_subject_details.class_details_id')
                ->join('rooms','rooms.id', '=', 'class_details.room_id')
                ->join('section_details', 'section_details.id', '=', 'class_details.section_id')       
                ->where('class_subject_details.id',  $StudentEnrolledSubject->class_subject_details_id)
                ->select(\DB::raw('                
                    rooms.room_code,
                    rooms.room_description,
                    section_details.section,
                    class_details.id,
                    class_details.section_id,
                    class_details.grade_level,
                    class_subject_details.status as grading_status,
                    class_subject_details.sem, class_subject_details.class_subject_order
                '))
                ->first();

        if (!$StudentEnrolledSubject)
        {
            return response()->json(['res_code' => 1, 'res_msg' => 'Invalid request.',]);
        }

        if (!$StudentEnrolledSubject)
        {
            return response()->json(['res_code' => 1, 'res_msg' => 'Invalid request.',]);
        }

        if($ClassSubjectDetail->grade_level == 11 || $ClassSubjectDetail->grade_level == 12)
        {
            if ($grading == 'first') 
            {
                $StudentEnrolledSubject->fir_g = $request->grade;
                // $StudentEnrolledSubject->fir_g_status = 1;            

                $subject;

                if($ClassSubjectDetail->class_subject_order == 1)//Filipino
                {
                    $subject = 'subject_1';
                }
                else if($ClassSubjectDetail->class_subject_order == 2)//mapeh
                {
                    $subject = 'subject_2';
                }
                else if($ClassSubjectDetail->class_subject_order == 3)//science
                {
                    $subject = 'subject_3';
                }
                else if($ClassSubjectDetail->class_subject_order == 4)//religion
                {
                    $subject = 'subject_4';
                }
                else if($ClassSubjectDetail->class_subject_order == 5)//english
                {
                    $subject = 'subject_5';                
                }
                else if($ClassSubjectDetail->class_subject_order == 6)//ap
                {
                    $subject = 'subject_6';
                }
                else if($ClassSubjectDetail->class_subject_order == 7)//math
                {          
                    $subject = 'subject_7';
                }
                else if($ClassSubjectDetail->class_subject_order == 8)//esp
                {
                    $subject = 'subject_8';      
                }
                else if($ClassSubjectDetail->class_subject_order == 9)//ict
                {
                    $subject = 'subject_9';
                }
                
                
                $GradesEncode = \App\Grade_sheet_firstsem::where(['enrollment_id'=>$enrollment_id])
                ->update([$subject=>$grade]); 

                $StudentEnrolledSubject->save();        
                return response()->json(['res_code' => 0, 'res_msg' => 'Grade successfully saved.',]);
            }
            else if ($grading == 'second') 
            {
                $StudentEnrolledSubject->sec_g = $request->grade;
                // $StudentEnrolledSubject->sec_g_status = 1;            

                $subject;

                if($ClassSubjectDetail->class_subject_order == 1)//Filipino
                {
                    $subject = 'subject_1';
                }
                else if($ClassSubjectDetail->class_subject_order == 2)//mapeh
                {
                    $subject = 'subject_2';
                }
                else if($ClassSubjectDetail->class_subject_order == 3)//science
                {
                    $subject = 'subject_3';
                }
                else if($ClassSubjectDetail->class_subject_order == 4)//religion
                {
                    $subject = 'subject_4';
                }
                else if($ClassSubjectDetail->class_subject_order == 5)//english
                {
                    $subject = 'subject_5';                
                }
                else if($ClassSubjectDetail->class_subject_order == 6)//ap
                {
                    $subject = 'subject_6';
                }
                else if($ClassSubjectDetail->class_subject_order == 7)//math
                {          
                    $subject = 'subject_7';
                }
                else if($ClassSubjectDetail->class_subject_order == 8)//esp
                {
                    $subject = 'subject_8';      
                }
                else if($ClassSubjectDetail->class_subject_order == 9)//ict
                {
                    $subject = 'subject_9';
                }
                
                
                $GradesEncode = \App\Grade_sheet_firstsemsecond::where(['enrollment_id'=>$enrollment_id])
                ->update([$subject=>$grade]); 

                $StudentEnrolledSubject->save();        
                return response()->json(['res_code' => 0, 'res_msg' => 'Grade successfully saved.',]);
            }
            else if ($grading == 'third') 
            {
                $StudentEnrolledSubject->thi_g = $request->grade;
                // $StudentEnrolledSubject->thi_g_status = 1;            

                $subject;

                if($ClassSubjectDetail->class_subject_order == 1)//Filipino
                {
                    $subject = 'subject_1';
                }
                else if($ClassSubjectDetail->class_subject_order == 2)//mapeh
                {
                    $subject = 'subject_2';
                }
                else if($ClassSubjectDetail->class_subject_order == 3)//science
                {
                    $subject = 'subject_3';
                }
                else if($ClassSubjectDetail->class_subject_order == 4)//religion
                {
                    $subject = 'subject_4';
                }
                else if($ClassSubjectDetail->class_subject_order == 5)//english
                {
                    $subject = 'subject_5';                
                }
                else if($ClassSubjectDetail->class_subject_order == 6)//ap
                {
                    $subject = 'subject_6';
                }
                else if($ClassSubjectDetail->class_subject_order == 7)//math
                {          
                    $subject = 'subject_7';
                }
                else if($ClassSubjectDetail->class_subject_order == 8)//esp
                {
                    $subject = 'subject_8';      
                }
                else if($ClassSubjectDetail->class_subject_order == 9)//ict
                {
                    $subject = 'subject_9';
                }
                
                
                $GradesEncode = \App\Grade11_Second_Sem::where(['enrollment_id'=>$enrollment_id])
                ->update([$subject=>$grade]); 

                $StudentEnrolledSubject->save();        
                return response()->json(['res_code' => 0, 'res_msg' => 'Grade successfully saved.',]);
            }
            else if ($grading == 'fourth') 
            {
                $StudentEnrolledSubject->fou_g = $grade;
                // $StudentEnrolledSubject->fou_g_status = 1;            

                $subject;

                if($ClassSubjectDetail->class_subject_order == 1)//Filipino
                {
                    $subject = 'subject_1';
                }
                else if($ClassSubjectDetail->class_subject_order == 2)//mapeh
                {
                    $subject = 'subject_2';
                }
                else if($ClassSubjectDetail->class_subject_order == 3)//science
                {
                    $subject = 'subject_3';
                }
                else if($ClassSubjectDetail->class_subject_order == 4)//religion
                {
                    $subject = 'subject_4';
                }
                else if($ClassSubjectDetail->class_subject_order == 5)//english
                {
                    $subject = 'subject_5';                
                }
                else if($ClassSubjectDetail->class_subject_order == 6)//ap
                {
                    $subject = 'subject_6';
                }
                else if($ClassSubjectDetail->class_subject_order == 7)//math
                {          
                    $subject = 'subject_7';
                }
                else if($ClassSubjectDetail->class_subject_order == 8)//esp
                {
                    $subject = 'subject_8';      
                }
                else if($ClassSubjectDetail->class_subject_order == 9)//ict
                {
                    $subject = 'subject_9';
                }
                
                
                $GradesEncode = \App\Grade_sheet_secondsemsecond::where(['enrollment_id'=>$enrollment_id])
                ->update([$subject=>$grade]); 

                $StudentEnrolledSubject->save();        
                return response()->json(['res_code' => 0, 'res_msg' => 'Grade successfully saved.',]);
            }
            
        }
        else 
        { 
        

            if ($grading == 'first') 
            {
                $StudentEnrolledSubject->fir_g = $grade;
            

                $subject;

                if($SelectedSubject->subject == 'Filipino')//Filipino
                {
                    $subject = 'filipino';
                }
                else if($SelectedSubject->subject == 'MAPEH')//mapeh
                {
                    $subject = 'mapeh';
                }
                else if($SelectedSubject->subject == 'Science')//science
                {
                    $subject = 'science';
                }
                else if($SelectedSubject->subject == 'Religion')//religion
                {
                    $subject = 'religion';
                }
                else if($SelectedSubject->subject == 'English')//english
                {
                    $subject = 'english';                    
                        
                }
                else if($SelectedSubject->subject == 'Araling Panlipunan')//ap
                {
                    $subject = 'ap';
                }
                else if($SelectedSubject->subject == 'Mathematics')//math
                {          
                    $subject = 'math';
                }
                else if($SelectedSubject->subject == 'ESP')//esp
                {
                    $subject = 'esp';      
                }
                else if($SelectedSubject->subject == 'ICT/Bread and Pastry')//ict
                {
                    $subject = 'ict';
                }
                else if($SelectedSubject->subject == 'ICT/Cookery')//ict
                {
                    $subject = 'ict';
                }
                else if($SelectedSubject->subject == 'ICT/Entrepreneurship')//ict
                {
                    $subject = 'ict';
                }
                else if($SelectedSubject->subject == 'ICT/Accounting')//ict
                {
                    $subject = 'ict';
                }            

                $GradesEncode = \App\Grade_sheet_first::where(['enrollment_id'=>$enrollment_id])
                    ->update([$subject => $grade]);
            
            
            } 
            else if ($grading == 'second') 
            {
                $StudentEnrolledSubject->sec_g = $grade;
                // $StudentEnrolledSubject->sec_g_status = 1;
                $subject;

                if($SelectedSubject->subject == 'Filipino')//Filipino
                {
                    $subject = 'filipino';
                }
                else if($SelectedSubject->subject == 'MAPEH')//mapeh
                {
                    $subject = 'mapeh';
                }
                else if($SelectedSubject->subject == 'Science')//science
                {
                    $subject = 'science';
                }
                else if($SelectedSubject->subject == 'Religion')//religion
                {
                    $subject = 'religion';
                }
                else if($SelectedSubject->subject == 'English')//english
                {
                    $subject = 'english';                
                }
                else if($SelectedSubject->subject == 'Araling Panlipunan')//ap
                {
                    $subject = 'ap';
                }
                else if($SelectedSubject->subject == 'Mathematics')//math
                {          
                    $subject = 'math';
                }
                else if($SelectedSubject->subject == 'ESP')//esp
                {
                    $subject = 'esp';      
                }
                else if($SelectedSubject->subject == 'ICT/Bread and Pastry')//ict
                {
                    $subject = 'ict';
                }
                else if($SelectedSubject->subject == 'ICT/Cookery')//ict
                {
                    $subject = 'ict';
                }
                else if($SelectedSubject->subject == 'ICT/Entrepreneurship')//ict
                {
                    $subject = 'ict';
                }
                else if($SelectedSubject->subject == 'ICT/Accounting')//ict
                {
                    $subject = 'ict';
                }            

                $GradesEncode = \App\Grade_sheet_second::where(['enrollment_id'=>$enrollment_id])
                ->update([$subject => $grade]);
            }
            else if ($grading == 'third') 
            {
                $StudentEnrolledSubject->thi_g = $grade;
                // $StudentEnrolledSubject->thi_g_status = 1;
                $subject;

                if($SelectedSubject->subject == 'Filipino')//Filipino
                {
                    $subject = 'filipino';
                }
                else if($SelectedSubject->subject == 'MAPEH')//mapeh
                {
                    $subject = 'mapeh';
                }
                else if($SelectedSubject->subject == 'Science')//science
                {
                    $subject = 'science';
                }
                else if($SelectedSubject->subject == 'Religion')//religion
                {
                    $subject = 'religion';
                }
                else if($SelectedSubject->subject == 'English')//english
                {
                    $subject = 'english';                
                }
                else if($SelectedSubject->subject == 'Araling Panlipunan')//ap
                {
                    $subject = 'ap';
                }
                else if($SelectedSubject->subject == 'Mathematics')//math
                {          
                    $subject = 'math';
                }
                else if($SelectedSubject->subject == 'ESP')//esp
                {
                    $subject = 'esp';      
                }
                else if($SelectedSubject->subject == 'ICT/Bread and Pastry')//ict
                {
                    $subject = 'ict';
                }
                else if($SelectedSubject->subject == 'ICT/Cookery')//ict
                {
                    $subject = 'ict';
                }
                else if($SelectedSubject->subject == 'ICT/Entrepreneurship')//ict
                {
                    $subject = 'ict';
                }
                else if($SelectedSubject->subject == 'ICT/Accounting')//ict
                {
                    $subject = 'ict';
                }            

                $GradesEncode = \App\Grade_sheet_third::where(['enrollment_id'=>$enrollment_id])
                ->update([$subject => $grade]);
            }
            else if ($grading == 'fourth') 
            {
                $StudentEnrolledSubject->fou_g = $grade;
                // $StudentEnrolledSubject->fou_g_status = 1;

                $subject;

                if($SelectedSubject->subject == 'Filipino')//Filipino
                {
                    $subject = 'filipino';
                }
                else if($SelectedSubject->subject == 'MAPEH')//mapeh
                {
                    $subject = 'mapeh';
                }
                else if($SelectedSubject->subject == 'Science')//science
                {
                    $subject = 'science';
                }
                else if($SelectedSubject->subject == 'Religion')//religion
                {
                    $subject = 'religion';
                }
                else if($SelectedSubject->subject == 'English')//english
                {
                    $subject = 'english';                
                }
                else if($SelectedSubject->subject == 'Araling Panlipunan')//ap
                {
                    $subject = 'ap';
                }
                else if($SelectedSubject->subject == 'Mathematics')//math
                {          
                    $subject = 'math';
                }
                else if($SelectedSubject->subject == 'ESP')//esp
                {
                    $subject = 'esp';      
                }
                else if($SelectedSubject->subject == 'ICT/Bread and Pastry')//ict
                {
                    $subject = 'ict';
                }
                else if($SelectedSubject->subject == 'ICT/Cookery')//ict
                {
                    $subject = 'ict';
                }
                else if($SelectedSubject->subject == 'ICT/Entrepreneurship')//ict
                {
                    $subject = 'ict';
                }
                else if($SelectedSubject->subject == 'ICT/Accounting')//ict
                {
                    $subject = 'ict';
                }            

                $GradesEncode = \App\Grade_sheet_fourth::where(['enrollment_id'=>$enrollment_id])
                ->update([$subject => $grade]);
            }
            $StudentEnrolledSubject->save();
            return response()->json(['res_code' => 0, 'res_msg' => 'Grade successfully saved temporarily.', 'aa' => $student_enrolled_subject_id]);
        }
    }
    public function save_grade (Request $request)
    {
        if (!$request->student_enrolled_subject_id || !$request->enrollment_id || !$request->grading || !$request->grade || !$request->classSubjectDetailID) 
        {
            return response()->json(['res_code' => 1, 'res_msg' => 'Invalid request.',]);
        }

        $validator = \Validator::make($request->all(), [
            'grade' => 'numeric|between:65,100.00'
        ], [
            'grade.between' => 'grade is invalid. 65 - 100.00'
        ]);
        
        if ($validator->fails())
        {
            return response()->json(['res_code' => 1, 'res_msg' => 'grade is invalid. 65 - 100.00.', 'res_error_msg' => $validator->getMessageBag()]);
        }


        $student_enrolled_subject_id = base64_decode($request->student_enrolled_subject_id);
        $enrollment_id = base64_decode($request->enrollment_id);
        $grading = base64_decode($request->grading);
        $grade = $request->grade;
        
        $StudentEnrolledSubject = \App\StudentEnrolledSubject::where('id', $student_enrolled_subject_id)->where('enrollments_id', $enrollment_id)
        ->where('class_subject_details_id', $request->classSubjectDetailID)->first();

        $selectedsubjectid = \App\ClassSubjectDetail::where('id', $StudentEnrolledSubject->class_subject_details_id)->first();

        $SelectedSubject = \App\SubjectDetail::where('id', $selectedsubjectid->subject_id)
                ->first();

        // $Grade_leve = \App\ClassSubjectDetail::join('class_details_id', 'class_details')
        $ClassSubjectDetail = \App\ClassSubjectDetail::join('class_details', 'class_details.id', '=', 'class_subject_details.class_details_id')
        ->join('rooms','rooms.id', '=', 'class_details.room_id')
        ->join('section_details', 'section_details.id', '=', 'class_details.section_id')       
        ->where('class_subject_details.id',  $StudentEnrolledSubject->class_subject_details_id)
        ->select(\DB::raw('                
            rooms.room_code,
            rooms.room_description,
            section_details.section,
            class_details.id,
            class_details.section_id,
            class_details.grade_level,
            class_subject_details.status as grading_status,
            class_subject_details.sem, class_subject_details.class_subject_order
        '))
        ->first();


               

        if (!$StudentEnrolledSubject)
        {
            return response()->json(['res_code' => 1, 'res_msg' => 'Invalid request.',]);
        }

        if($ClassSubjectDetail->grade_level == 11 || $ClassSubjectDetail->grade_level == 12)
        {
            if ($grading == 'first') 
            {
                $StudentEnrolledSubject->fir_g = $request->grade;
                $StudentEnrolledSubject->fir_g_status = 1;            

                $subject;

                if($ClassSubjectDetail->class_subject_order == 1)//Filipino
                {
                    $subject = 'subject_1';
                }
                else if($ClassSubjectDetail->class_subject_order == 2)//mapeh
                {
                    $subject = 'subject_2';
                }
                else if($ClassSubjectDetail->class_subject_order == 3)//science
                {
                    $subject = 'subject_3';
                }
                else if($ClassSubjectDetail->class_subject_order == 4)//religion
                {
                    $subject = 'subject_4';
                }
                else if($ClassSubjectDetail->class_subject_order == 5)//english
                {
                    $subject = 'subject_5';                
                }
                else if($ClassSubjectDetail->class_subject_order == 6)//ap
                {
                    $subject = 'subject_6';
                }
                else if($ClassSubjectDetail->class_subject_order == 7)//math
                {          
                    $subject = 'subject_7';
                }
                else if($ClassSubjectDetail->class_subject_order == 8)//esp
                {
                    $subject = 'subject_8';      
                }
                else if($ClassSubjectDetail->class_subject_order == 9)//ict
                {
                    $subject = 'subject_9';
                }
                
                
                $GradesEncode = \App\Grade_sheet_firstsem::where(['enrollment_id'=>$enrollment_id])
                ->update([$subject=>$grade]); 

                $StudentEnrolledSubject->save();        
                return response()->json(['res_code' => 0, 'res_msg' => 'Grade successfully saved.',]);
            }
            else if ($grading == 'second') 
            {
                $StudentEnrolledSubject->sec_g = $request->grade;
                $StudentEnrolledSubject->sec_g_status = 1;            

                $subject;

                if($ClassSubjectDetail->class_subject_order == 1)//Filipino
                {
                    $subject = 'subject_1';
                }
                else if($ClassSubjectDetail->class_subject_order == 2)//mapeh
                {
                    $subject = 'subject_2';
                }
                else if($ClassSubjectDetail->class_subject_order == 3)//science
                {
                    $subject = 'subject_3';
                }
                else if($ClassSubjectDetail->class_subject_order == 4)//religion
                {
                    $subject = 'subject_4';
                }
                else if($ClassSubjectDetail->class_subject_order == 5)//english
                {
                    $subject = 'subject_5';                
                }
                else if($ClassSubjectDetail->class_subject_order == 6)//ap
                {
                    $subject = 'subject_6';
                }
                else if($ClassSubjectDetail->class_subject_order == 7)//math
                {          
                    $subject = 'subject_7';
                }
                else if($ClassSubjectDetail->class_subject_order == 8)//esp
                {
                    $subject = 'subject_8';      
                }
                else if($ClassSubjectDetail->class_subject_order == 9)//ict
                {
                    $subject = 'subject_9';
                }
                
                
                $GradesEncode = \App\Grade_sheet_firstsemsecond::where(['enrollment_id'=>$enrollment_id])
                ->update([$subject=>$grade]); 

                $StudentEnrolledSubject->save();        
                return response()->json(['res_code' => 0, 'res_msg' => 'Grade successfully saved.',]);
            }
            else if ($grading == 'third') 
            {
                $StudentEnrolledSubject->thi_g = $request->grade;
                $StudentEnrolledSubject->thi_g_status = 1;            

                $subject;

                if($ClassSubjectDetail->class_subject_order == 1)//Filipino
                {
                    $subject = 'subject_1';
                }
                else if($ClassSubjectDetail->class_subject_order == 2)//mapeh
                {
                    $subject = 'subject_2';
                }
                else if($ClassSubjectDetail->class_subject_order == 3)//science
                {
                    $subject = 'subject_3';
                }
                else if($ClassSubjectDetail->class_subject_order == 4)//religion
                {
                    $subject = 'subject_4';
                }
                else if($ClassSubjectDetail->class_subject_order == 5)//english
                {
                    $subject = 'subject_5';                
                }
                else if($ClassSubjectDetail->class_subject_order == 6)//ap
                {
                    $subject = 'subject_6';
                }
                else if($ClassSubjectDetail->class_subject_order == 7)//math
                {          
                    $subject = 'subject_7';
                }
                else if($ClassSubjectDetail->class_subject_order == 8)//esp
                {
                    $subject = 'subject_8';      
                }
                else if($ClassSubjectDetail->class_subject_order == 9)//ict
                {
                    $subject = 'subject_9';
                }
                
                
                $GradesEncode = \App\Grade11_Second_Sem::where(['enrollment_id'=>$enrollment_id])
                ->update([$subject=>$grade]); 

                $StudentEnrolledSubject->save();        
                return response()->json(['res_code' => 0, 'res_msg' => 'Grade successfully saved.',]);
            }
            else if ($grading == 'fourth') 
            {
                $StudentEnrolledSubject->fou_g = $request->grade;
                $StudentEnrolledSubject->fou_g_status = 1;            

                $subject;

                if($ClassSubjectDetail->class_subject_order == 1)//Filipino
                {
                    $subject = 'subject_1';
                }
                else if($ClassSubjectDetail->class_subject_order == 2)//mapeh
                {
                    $subject = 'subject_2';
                }
                else if($ClassSubjectDetail->class_subject_order == 3)//science
                {
                    $subject = 'subject_3';
                }
                else if($ClassSubjectDetail->class_subject_order == 4)//religion
                {
                    $subject = 'subject_4';
                }
                else if($ClassSubjectDetail->class_subject_order == 5)//english
                {
                    $subject = 'subject_5';                
                }
                else if($ClassSubjectDetail->class_subject_order == 6)//ap
                {
                    $subject = 'subject_6';
                }
                else if($ClassSubjectDetail->class_subject_order == 7)//math
                {          
                    $subject = 'subject_7';
                }
                else if($ClassSubjectDetail->class_subject_order == 8)//esp
                {
                    $subject = 'subject_8';      
                }
                else if($ClassSubjectDetail->class_subject_order == 9)//ict
                {
                    $subject = 'subject_9';
                }
                
                
                $GradesEncode = \App\Grade_sheet_secondsemsecond::where(['enrollment_id'=>$enrollment_id])
                ->update([$subject=>$grade]); 

                $StudentEnrolledSubject->save();        
                return response()->json(['res_code' => 0, 'res_msg' => 'Grade successfully saved.',]);
            }
            
        }
        else 
        {            
            

            if ($request->grading == 'first') 
            {
                $StudentEnrolledSubject->fir_g = $request->grade;
                $StudentEnrolledSubject->fir_g_status = 1;            

                $subject;

                if($SelectedSubject->subject == 'Filipino')//Filipino
                {
                    $subject = 'filipino';
                }
                else if($SelectedSubject->subject == 'MAPEH')//mapeh
                {
                    $subject = 'mapeh';
                }
                else if($SelectedSubject->subject == 'Science')//science
                {
                    $subject = 'science';
                }
                else if($SelectedSubject->subject == 'Religion')//religion
                {
                    $subject = 'religion';
                }
                else if($SelectedSubject->subject == 'English')//english
                {
                    $subject = 'english';
                
                }
                else if($SelectedSubject->subject == 'Araling Panlipunan')//ap
                {
                    $subject = 'ap';
                }
                else if($SelectedSubject->subject == 'Mathematics')//math
                {          
                    $subject = 'math';
                }
                else if($SelectedSubject->subject == 'ESP')//esp
                {
                    $subject = 'esp';      
                }
                else if($SelectedSubject->subject == 'ICT/Bread and Pastry')//ict
                {
                    $subject = 'ict';
                }
                else if($SelectedSubject->subject == 'ICT/Cookery')//ict
                {
                    $subject = 'ict';
                }
                else if($SelectedSubject->subject == 'ICT/Entrepreneurship')//ict
                {
                    $subject = 'ict';
                }
                else if($SelectedSubject->subject == 'ICT/Accounting')//ict
                {
                    $subject = 'ict';
                }   
                
                $GradesEncode = \App\Grade_sheet_first::where(['enrollment_id'=>$enrollment_id])
                ->update([$subject=>$grade]); 
            
            } 
            else if ($request->grading == 'second') 
            {
                $StudentEnrolledSubject->sec_g = $request->grade;
                $StudentEnrolledSubject->sec_g_status = 1;

                $subject;

                if($SelectedSubject->subject == 'Filipino')//Filipino
                {
                    $subject = 'filipino';
                }
                else if($SelectedSubject->subject == 'MAPEH')//mapeh
                {
                    $subject = 'mapeh';
                }
                else if($SelectedSubject->subject == 'Science')//science
                {
                    $subject = 'science';
                }
                else if($SelectedSubject->subject == 'Religion')//religion
                {
                    $subject = 'religion';
                }
                else if($SelectedSubject->subject == 'English')//english
                {
                    $subject = 'english';
                }
                else if($SelectedSubject->subject == 'Araling Panlipunan')//ap
                {
                    $subject = 'ap';
                }
                else if($SelectedSubject->subject == 'Mathematics')//math
                {          
                    $subject = 'math';
                }
                else if($SelectedSubject->subject == 'ESP')//esp
                {
                    $subject = 'esp';      
                }
                else if($SelectedSubject->subject == 'ICT/Bread and Pastry')//ict
                {
                    $subject = 'ict';
                }
                else if($SelectedSubject->subject == 'ICT/Cookery')//ict
                {
                    $subject = 'ict';
                }
                else if($SelectedSubject->subject == 'ICT/Entrepreneurship')//ict
                {
                    $subject = 'ict';
                }
                else if($SelectedSubject->subject == 'ICT/Accounting')//ict
                {
                    $subject = 'ict';
                }   
                    
                $GradesEncode = \App\Grade_sheet_second::where(['enrollment_id'=>$enrollment_id])
                ->update([$subject=>$grade]);
            }
            else if ($request->grading == 'third') 
            {
                $StudentEnrolledSubject->thi_g = $request->grade;
                $StudentEnrolledSubject->thi_g_status = 1;

                $subject;

                if($SelectedSubject->subject == 'Filipino')//Filipino
                {
                    $subject = 'filipino';
                }
                else if($SelectedSubject->subject == 'MAPEH')//mapeh
                {
                    $subject = 'mapeh';
                }
                else if($SelectedSubject->subject == 'Science')//science
                {
                    $subject = 'science';
                }
                else if($SelectedSubject->subject == 'Religion')//religion
                {
                    $subject = 'religion';
                }
                else if($SelectedSubject->subject == 'English')//english
                {
                    $subject = 'english';
                }
                else if($SelectedSubject->subject == 'Araling Panlipunan')//ap
                {
                    $subject = 'ap';
                }
                else if($SelectedSubject->subject == 'Mathematics')//math
                {          
                    $subject = 'math';
                }
                else if($SelectedSubject->subject == 'ESP')//esp
                {
                    $subject = 'esp';      
                }
                else if($SelectedSubject->subject == 'ICT/Bread and Pastry')//ict
                {
                    $subject = 'ict';
                }
                else if($SelectedSubject->subject == 'ICT/Cookery')//ict
                {
                    $subject = 'ict';
                }
                else if($SelectedSubject->subject == 'ICT/Entrepreneurship')//ict
                {
                    $subject = 'ict';
                }
                else if($SelectedSubject->subject == 'ICT/Accounting')//ict
                {
                    $subject = 'ict';
                }   
                    
                $GradesEncode = \App\Grade_sheet_third::where(['enrollment_id'=>$enrollment_id])
                ->update([$subject=>$grade]);
            }
            else if ($request->grading == 'fourth') 
            {
                $StudentEnrolledSubject->fou_g = $request->grade;
                $StudentEnrolledSubject->fou_g_status = 1;

                $subject;

                if($SelectedSubject->subject == 'Filipino')//Filipino
                {
                    $subject = 'filipino';
                }
                else if($SelectedSubject->subject == 'MAPEH')//mapeh
                {
                    $subject = 'mapeh';
                }
                else if($SelectedSubject->subject == 'Science')//science
                {
                    $subject = 'science';
                }
                else if($SelectedSubject->subject == 'Religion')//religion
                {
                    $subject = 'religion';
                }
                else if($SelectedSubject->subject == 'English')//english
                {
                    $subject = 'english';
                }
                else if($SelectedSubject->subject == 'Araling Panlipunan')//ap
                {
                    $subject = 'ap';
                }
                else if($SelectedSubject->subject == 'Mathematics')//math
                {          
                    $subject = 'math';
                }
                else if($SelectedSubject->subject == 'ESP')//esp
                {
                    $subject = 'esp';      
                }
                else if($SelectedSubject->subject == 'ICT/Bread and Pastry')//ict
                {
                    $subject = 'ict';
                }
                else if($SelectedSubject->subject == 'ICT/Cookery')//ict
                {
                    $subject = 'ict';
                }
                else if($SelectedSubject->subject == 'ICT/Entrepreneurship')//ict
                {
                    $subject = 'ict';
                }
                else if($SelectedSubject->subject == 'ICT/Accounting')//ict
                {
                    $subject = 'ict';
                }   
                    
                $GradesEncode = \App\Grade_sheet_fourth::where(['enrollment_id'=>$enrollment_id])
                ->update([$subject=>$grade]);
            }
            $StudentEnrolledSubject->save();        
            return response()->json(['res_code' => 0, 'res_msg' => 'Grade successfully saved.',]);
        }
    }
    public function finalize_grade (Request $request) 
    {
        $FacultyInformation = \App\FacultyInformation::where('user_id', \Auth::user()->id)->first();
        $ClassSubjectDetail = \App\ClassSubjectDetail::where('class_subject_details.id', $request->id)
            ->where('faculty_id', $FacultyInformation->id)
            ->first();
        if ($ClassSubjectDetail) 
        {
            $ClassSubjectDetail->status = 2;
            $ClassSubjectDetail->save();
            return response()->json(['res_code' => 0, 'res_msg' => 'Grade successfully finalized.',]);
        }
        else 
        {
            return response()->json(['res_code' => 1, 'res_msg' => 'Invalid request',]); 
        }
    }

    public function view_student_data()
    {
        $SchoolYear = \App\SectionDetail::get();
        return view('control_panel_faculty.student_data.index', compact('SchoolYear'));
    }

    public function list_class_section (Request $request) 
    {
        // $FacultyInformation = \App\FacultyInformation::where('user_id', \Auth::user()->id)->first(); 
        $sectionID = $request->search_sy; 
        // $data = $request->all();

        $getIdClassDetails = \App\ClassDetail::where(['section_id'=> $sectionID])->first();

        $EnrollmentID = \App\Enrollment::where(['class_details_id'=>$getIdClassDetails->id])->get();

        foreach($EnrollmentID as $dataID)
        {
            $Grade_sheet_first = new Grade_sheet_firstsem();
            $Grade_sheet_first->enrollment_id =  $dataID->id;
            $Grade_sheet_first->section_details_id = $sectionID;
            $Grade_sheet_first->subject_1 = 0.00;
            $Grade_sheet_first->subject_2 = 0.00;
            $Grade_sheet_first->subject_3 = 0.00;
            $Grade_sheet_first->subject_4 = 0.00;
            $Grade_sheet_first->subject_5 = 0.00;
            $Grade_sheet_first->subject_6 = 0.00;
            $Grade_sheet_first->subject_7 = 0.00;
            $Grade_sheet_first->subject_8 = 0.00;
            $Grade_sheet_first->subject_9 = 0.00;   
            $Grade_sheet_first->current = 1;
            $Grade_sheet_first->status = 1;
            $Grade_sheet_first->save();

        }

        return redirect()->back()->with('flash_message_success','Room Reserved Successfuly!');
       
    }
}
