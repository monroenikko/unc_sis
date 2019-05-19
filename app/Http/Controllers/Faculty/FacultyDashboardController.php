<?php

namespace App\Http\Controllers\Faculty;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FacultyDashboardController extends Controller
{
    public function index () 
    {
        $FacultyInformation = \App\FacultyInformation::where('user_id', \Auth::user()->id)->first();
        $SchoolYear         = \App\SchoolYear::where('current', 1)->first();

        $StudentInformation_tagged_student = \DB::table('student_informations')
            ->select(\DB::raw('COUNT(student_informations.id) AS total_students'))
            ->whereRaw('student_informations.id 
                    IN 
                    (SELECT DISTINCT(student_information_id) 
                        FROM enrollments 
                        JOIN class_subject_details on class_subject_details.class_details_id = enrollments.class_details_id
                        JOIN class_details on class_details.id = class_subject_details.class_details_id
                        WHERE class_subject_details.faculty_id = '. $FacultyInformation->id .'
                        AND class_details.current = 1
                        AND class_details.status = 1)'
                )
            ->where('student_informations.status', 1)
            ->first();
        // return json_encode(['StudentInformation_tagged_student' => $StudentInformation_tagged_student, 'FacultyInformation' => $FacultyInformation]);
        $StudentInformation_tagged_student_male = \DB::table('student_informations')
            ->select(\DB::raw('COUNT(student_informations.id) AS total_students'))
            ->whereRaw('student_informations.id 
                    IN 
                    (SELECT DISTINCT(student_information_id) 
                        FROM enrollments 
                        JOIN class_subject_details on class_subject_details.class_details_id = enrollments.class_details_id
                        JOIN class_details on class_details.id = class_subject_details.class_details_id
                        WHERE class_subject_details.faculty_id = '. $FacultyInformation->id .'
                        AND class_details.current = 1
                        AND class_details.status = 1)'
                )
            ->where('student_informations.status', 1)
            ->where('student_informations.gender', 1)
            ->first();

        $StudentInformation_tagged_student_female = \DB::table('student_informations')
            ->select(\DB::raw('COUNT(student_informations.id) AS total_students'))
            ->whereRaw('student_informations.id 
                    IN 
                    (SELECT DISTINCT(student_information_id) 
                        FROM enrollments 
                        JOIN class_subject_details on class_subject_details.class_details_id = enrollments.class_details_id
                        JOIN class_details on class_details.id = class_subject_details.class_details_id
                        WHERE class_subject_details.faculty_id = '. $FacultyInformation->id .'
                        AND class_details.current = 1
                        AND class_details.status = 1)'
                )
            ->where('student_informations.status', 1)
            ->where('student_informations.gender', 2)
            ->first();

        $ClassSubjectDetail_count = \App\ClassSubjectDetail::join('class_details', 'class_details.id', '=', 'class_subject_details.class_details_id')
            ->where('faculty_id', $FacultyInformation->id)
            ->where('class_subject_details.status', 1)
            ->where('class_details.status', 1)
            ->where('class_details.current', 1)
            ->selectRaw('
                COUNT(class_subject_details.id) AS subject_count
            ')
            ->first();

        // return json_encode($StudentInformation_all);
        return view('control_panel_faculty.dashboard.index',
            compact(
                'StudentInformation_tagged_student',
                'StudentInformation_tagged_student_male',
                'StudentInformation_tagged_student_female',
                'ClassSubjectDetail_count'
                )
        );
    }
}
