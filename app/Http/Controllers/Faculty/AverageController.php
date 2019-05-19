<?php

namespace App\Http\Controllers\Faculty;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AverageController extends Controller
{
    public function firstSecondAverage(Request $request)
    {
        $type = "average";
        $FacultyInformation = \App\FacultyInformation::where('user_id', \Auth::user()->id)->first();
     
        $ClassSubjectDetail = \App\ClassSubjectDetail::join('class_details', 'class_details.id', '=', 'class_subject_details.class_details_id')
            // ->join('subject_details', 'subject_details.id', '=', 'class_subject_details.subject_id')
            ->join('rooms','rooms.id', '=', 'class_details.room_id')
            ->join('section_details', 'section_details.id', '=', 'class_details.section_id')
            // ->whereRaw('class_subject_details.id = '. $request->search_class_subject)
            // ->whereRaw('class_details.id = '. $search_class_subject[1])
            ->where('class_details.adviser_id', $FacultyInformation->id)
            ->where('class_details.school_year_id', $request->search_sy)
            // ->where('class_subject_details.status', '!=', 0)
            ->where('class_details.status', '!=', 0)
            ->select(\DB::raw('                
                rooms.room_code,
                rooms.room_description,
                section_details.section,
                class_details.id,
                class_details.section_id,
                class_details.grade_level,
                class_subject_details.status as grading_status,
                class_subject_details.sem
            '))
            ->first();

        return view('control_panel_faculty.my_advisory_class.partials.data_list', 
        compact('type','ClassSubjectDetail'))->render();
    }
}
