<?php

namespace App\Http\Controllers\Registrar;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GradeSheetController extends Controller
{
    
    public function index (Request $request) 
    {
        // $FacultyInformation = \App\FacultyInformation::where('user_id', \Auth::user()->id)->first();
        // return json_encode(['FacultyInformation' => $FacultyInformation, 'Auth' => \Auth::user()]);
        $SchoolYear = \App\SchoolYear::where('status', 1)->orderBy('current', 'ASC')->orderBy('school_year', 'ASC')->get();
        return view('control_panel_registrar.student_grade_sheet.index', compact('SchoolYear'));
    }
    public function list_students_by_class (Request $request) 
    {
        // $FacultyInformation = \App\FacultyInformation::where('user_id', \Auth::user()->id)->first();
        
        $Enrollment = \App\Enrollment::join('class_subject_details', 'class_subject_details.class_details_id', '=', 'enrollments.class_details_id')
                    ->join('class_details', 'class_details.id', '=', 'class_subject_details.class_details_id')
                    ->join('student_informations', 'student_informations.id', '=', 'enrollments.student_information_id')
                    // ->join('student_enrolled_subjects', 'student_enrolled_subjects.subject_id', '=', 'class_subject_details.subject_id')
                    // ->join('student_enrolled_subjects', 'student_enrolled_subjects.subject_id', '=', 'class_subject_details.subject_id')
                    // ->join('student_enrolled_subjects', 'student_enrolled_subjects.enrollments_id', '=', 'enrollments.id')
                    ->join('student_enrolled_subjects', function ($join) {
                        $join->on('student_enrolled_subjects.enrollments_id', '=', 'enrollments.id')
                        ->on('student_enrolled_subjects.subject_id', '=', 'class_subject_details.subject_id');
                    })
                    // ->whereRaw('class_subject_details.faculty_id = '. $FacultyInformation->id)
                    ->whereRaw('class_subject_details.id = '. $request->search_class_subject)
                    ->whereRaw('class_details.current = 1')
                    ->whereRaw('class_details.status = 1')
                    ->select(\DB::raw("
                        student_informations.id,
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
                        student_enrolled_subjects.fin_g_status
                    "))
                    ->paginate(50);
        $ClassSubjectDetail = \App\ClassSubjectDetail::join('class_details', 'class_details.id', '=', 'class_subject_details.class_details_id')
            ->join('subject_details', 'subject_details.id', '=', 'class_subject_details.subject_id')
            ->join('section_details', 'section_details.id', '=', 'class_details.section_id')
            ->where('class_subject_details.id', $request->search_class_subject)
            // ->where('faculty_id', $FacultyInformation->id)
            ->where('class_details.school_year_id', $request->search_sy)
            ->where('class_subject_details.status', 1)
            ->where('class_details.status', 1)
            ->select(\DB::raw('
                class_subject_details.id,
                class_subject_details.class_time_from,
                class_subject_details.class_time_to,
                class_subject_details.class_days,
                subject_details.subject_code,
                subject_details.subject,
                section_details.section,
                class_details.grade_level
            '))
            ->first();
        return view('control_panel_registrar.student_grade_sheet.partials.data_list', compact('Enrollment', 'ClassSubjectDetail'))->render();
    }
    public function list_class_subject_details (Request $request) 
    {
        // $FacultyInformation = \App\FacultyInformation::where('user_id', \Auth::user()->id)->first();
        $ClassSubjectDetail = \App\ClassSubjectDetail::join('class_details', 'class_details.id', '=', 'class_subject_details.class_details_id')
            ->join('subject_details', 'subject_details.id', '=', 'class_subject_details.subject_id')
            ->join('section_details', 'section_details.id', '=', 'class_details.section_id')
            // ->where('faculty_id', $FacultyInformation->id)
            ->where('class_details.school_year_id', $request->search_sy)
            ->where('class_subject_details.status', 1)
            ->where('class_details.status', 1)
            ->select(\DB::raw('  
                class_subject_details.id,
                class_subject_details.class_time_from,
                class_subject_details.class_time_to,
                class_subject_details.class_days,
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
                $class_details_elements .= '<option value="'. $data->id .'">'. $data->subject_code . ' ' . $data->subject . ' - Grade ' .  $data->grade_level . ' Section ' . $data->section . ' -- Schedule ' . $data->class_time_from . '-' . $data->class_time_to . '-' . $data->class_days   .'</option>';
            }

            return $class_details_elements;
        }
        return $class_details_elements;
    }
}
