<?php

namespace App\Http\Controllers\Control_Panel_Student;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ClassScheduleController extends Controller
{
    public function index (Request $request) 
    {
        $StudentInformation = \App\StudentInformation::where('user_id', \Auth::user()->id)->first();
        $SchoolYear = \App\SchoolYear::where('current', 1)->first();
        if ($StudentInformation) 
        {
            $Enrollment = \App\Enrollment::join('class_details', 'class_details.id', '=', 'enrollments.class_details_id')
            // ->join('student_enrolled_subjects', 'student_enrolled_subjects.enrollments_id', '=', 'enrollments.id')
            ->join('class_subject_details', 'class_subject_details.class_details_id', '=', 'class_details.id')
            ->join('rooms', 'rooms.id', '=', 'class_details.room_id')
            ->join('faculty_informations', 'faculty_informations.id', '=', 'class_subject_details.faculty_id')
            ->join('section_details', 'section_details.id', '=', 'class_details.section_id')
            ->join('subject_details', 'subject_details.id', '=', 'class_subject_details.subject_id')
            ->where('student_information_id', $StudentInformation->id)
            // ->where('class_subject_details.status', 1)
            ->where('class_subject_details.status', '!=', 0)
            ->where('enrollments.status', 1)
            ->where('class_details.status', 1)
            ->where('class_details.school_year_id', $SchoolYear->id)
            ->select(\DB::raw("
                enrollments.id as enrollment_id,
                class_details.grade_level,
                class_subject_details.class_schedule,
                class_subject_details.class_days,
                class_subject_details.class_time_from,
                class_subject_details.class_time_to,
                CONCAT(faculty_informations.last_name, ', ', faculty_informations.first_name, ' ', faculty_informations.middle_name) as faculty_name,
                subject_details.subject_code,
                subject_details.subject,
                rooms.room_code,
                section_details.section
            "))
            ->orderBy('class_subject_details.class_time_from', 'ASC')
            ->get();
            return view('control_panel_student.class_schedule.index', compact('Enrollment', 'StudentInformation', 'SchoolYear'));
            // return json_encode([$Enrollment, $StudentInformation]);
        }
    }
}
