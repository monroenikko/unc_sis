<?php

namespace App\Http\Controllers\Faculty;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB\PDF;

class MyAdvisoryClassController extends Controller
{
    public function index (Request $request)
    {
        $FacultyInformation = \App\FacultyInformation::where('user_id', \Auth::user()->id)->first();
        // return json_encode(['FacultyInformation' => $FacultyInformation, 'Auth' => \Auth::user()]);
        $SchoolYear  = \App\SchoolYear::where('status', 1)->where('current', 1)->orderBy('current', 'ASC')->orderBy('school_year', 'ASC')->get();

        $GradeLevel = \App\ClassSubjectDetail::join('class_details', 'class_details.id', '=', 'class_subject_details.class_details_id')
            // ->join('subject_details', 'subject_details.id', '=', 'class_subject_details.subject_id')
            ->join('rooms','rooms.id', '=', 'class_details.room_id')
            ->join('section_details', 'section_details.id', '=', 'class_details.section_id')
            // ->whereRaw('class_subject_details.id = '. $request->search_class_subject)
            // ->whereRaw('class_details.id = '. $search_class_subject[1])
            ->where('class_details.adviser_id', $FacultyInformation->id)
            // ->where('class_details.school_year_id', $request->search_sy)
            // ->where('class_subject_details.status', '!=', 0)
            ->where('class_details.status', '!=', 0)
            ->select(\DB::raw('                
                rooms.room_code,
                rooms.room_description,
                section_details.section,
                class_details.id,
                class_details.section_id,
                class_details.grade_level,
                class_subject_details.status as grading_status
            '))
            ->first();

        return view('control_panel_faculty.my_advisory_class.index', compact('SchoolYear','GradeLevel'));
        // return view('control_panel_faculty.my_advisory_class.index');
    
    }

    public function list_quarter (Request $request)
    {
        $FacultyInformation = \App\FacultyInformation::where('user_id', \Auth::user()->id)->first();  
        $class_details_elements = '<option value="">Select Class Quarter</option>';
       
        $class_details_elements .= '<option value="1st">First Quarter</option>';
        $class_details_elements .= '<option value="2nd">Second Quarter</option>';
        $class_details_elements .= '<option value="3rd">Third Quarter</option>';
        $class_details_elements .= '<option value="4th">Fourth Quarter</option>';
        $class_details_elements .= '<option value="">-------------------------------AVERAGE--------------------------------</option>';
        $class_details_elements .= '<option value="1st-2nd">First - Second Quarter Average</option>';
        $class_details_elements .= '<option value="1st-3rd">First - Second - Third Quarter Average</option>';
        $class_details_elements .= '<option value="1st-4th">First - Second - Third - Fourth Quarter Average</option>';       
        return $class_details_elements;
    }

    public function list_quarter_sem (Request $request)
    {
        $FacultyInformation = \App\FacultyInformation::where('user_id', \Auth::user()->id)->first(); 
        
        if($request->semester_grades == "3rd")
        {
            $class_details_elements = '<option value="">-------------------------------AVERAGE--------------------------------</option>';
            $class_details_elements .= '<option value="1st-2nd">Sem-1 First - Second Quarter Average</option>';
            $class_details_elements .= '<option value="3rd-4th">Sem-2 First - Second Quarter Average</option>'; 
            $class_details_elements .= '<option value="1-2">Sem-1 and 2 Average</option>'; 
        }
        else
        {
            $class_details_elements = '<option value="">Select Class Quarter</option>';
            $class_details_elements .= '<option value="1st">First Quarter</option>';
            $class_details_elements .= '<option value="2nd">Second Quarter</option>';
        }
           
        return $class_details_elements;
    }

    public function list_class_subject_details (Request $request) 
    {
        $FacultyInformation = \App\FacultyInformation::where('user_id', \Auth::user()->id)->first(); 
        
        $class_details_elements = '<option value="">Select Semester</option>';        
        $class_details_elements .= '<option value="1st">First Semester</option>';
        $class_details_elements .= '<option value="2nd">Second Semester</option>';
        $class_details_elements .= '<option value="3rd">Average</option>';
        
        
        return $class_details_elements;
    }


    public function first_sem_1quarter (Request $request) 
    {
        $FacultyInformation = \App\FacultyInformation::where('user_id', \Auth::user()->id)->first();
     
        $ClassSubjectDetail = \App\ClassSubjectDetail::join('class_details', 'class_details.id', '=', 'class_subject_details.class_details_id')
            // ->join('subject_details', 'subject_details.id', '=', 'class_subject_details.subject_id')
            ->join('rooms','rooms.id', '=', 'class_details.room_id')
            ->join('section_details', 'section_details.id', '=', 'class_details.section_id')
            // ->whereRaw('class_subject_details.id = '. $request->search_class_subject)
            // ->whereRaw('class_details.id = '. $search_class_subject[1])
            ->where('class_details.adviser_id', $FacultyInformation->id)
            ->where('class_details.school_year_id', $request->search_sy1)
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

            
        
            $AdvisorySubject = \App\ClassSubjectDetail::join('subject_details', 'subject_details.id', '=' ,'class_subject_details.subject_id')
            ->join('class_details', 'class_details.id', '=' ,'class_subject_details.class_details_id')
            ->join('faculty_informations', 'faculty_informations.id', '=' ,'class_subject_details.faculty_id')           
            ->selectRaw("                
                subject_details.subject, subject_details.id, class_subject_details.class_subject_order, subject_details.subject_code
            ")
            ->where('class_subject_details.class_details_id', $ClassSubjectDetail->id)
            ->where('class_subject_details.status', 1)
            // ->where('faculty_id', $FacultyInformation->id)
            ->where('class_details.school_year_id', $request->search_sy1)
            ->where('class_subject_details.sem', 1)
            //  ->where('class_details.school_year_id', $request->search_sy)
            // ->orderBy('class_subject_details.class_time_from', 'ASC');
            ->orderBY('class_subject_details.class_subject_order','ASC')
            ->get();            
            

            $GradeSheetMale = \App\Grade_sheet_firstsem::join('class_details','class_details.section_id','=','grade_sheet_firstsems.section_details_id')            
            ->join('enrollments','enrollments.id','=','grade_sheet_firstsems.enrollment_id')
            ->join('student_informations','student_informations.id','=','enrollments.student_information_id')
            ->where('class_details.section_id', $ClassSubjectDetail->section_id)
            ->whereRaw('student_informations.gender = 1')
            ->selectRaw("                    
                    CONCAT(student_informations.last_name, ' ', student_informations.first_name, ' ', student_informations.middle_name) as student_name,
                    grade_sheet_firstsems.subject_1, grade_sheet_firstsems.subject_2, grade_sheet_firstsems.subject_3, grade_sheet_firstsems.subject_4
                    , grade_sheet_firstsems.subject_5, grade_sheet_firstsems.subject_6, grade_sheet_firstsems.subject_7
                    , grade_sheet_firstsems.subject_8, grade_sheet_firstsems.subject_9
                    ")
            ->distinct()
            ->orderBY('student_name','ASC')
            ->get();

            $GradeSheetFeMale = \App\Grade_sheet_firstsem::join('class_details','class_details.section_id','=','grade_sheet_firstsems.section_details_id')            
            ->join('enrollments','enrollments.id','=','grade_sheet_firstsems.enrollment_id')
            ->join('student_informations','student_informations.id','=','enrollments.student_information_id')
            ->where('class_details.section_id', $ClassSubjectDetail->section_id)
            ->whereRaw('student_informations.gender = 2')
            ->selectRaw("                    
                    CONCAT(student_informations.last_name, ' ', student_informations.first_name, ' ', student_informations.middle_name) as student_name,
                    grade_sheet_firstsems.subject_1, grade_sheet_firstsems.subject_2, grade_sheet_firstsems.subject_3, grade_sheet_firstsems.subject_4
                    , grade_sheet_firstsems.subject_5, grade_sheet_firstsems.subject_6, grade_sheet_firstsems.subject_7
                    , grade_sheet_firstsems.subject_8, grade_sheet_firstsems.subject_9
                    ")
            ->distinct()
            ->orderBY('student_name','ASC')
            ->get();

            $quarter = 'First';
            $sem = 'First';

            $NumberOfSubject = \App\ClassSubjectDetail::join('subject_details', 'subject_details.id', '=' ,'class_subject_details.subject_id')
            ->join('class_details', 'class_details.id', '=' ,'class_subject_details.class_details_id')
            ->join('faculty_informations', 'faculty_informations.id', '=' ,'class_subject_details.faculty_id')           
            ->selectRaw("                
                            class_subject_details.class_subject_order
                        ")
            ->where('class_subject_details.class_details_id', $ClassSubjectDetail->id)
            ->where('class_subject_details.status', 1)
            // ->where('faculty_id', $FacultyInformation->id)
            ->where('class_details.school_year_id', $request->search_sy1)
            ->where('class_subject_details.sem', 1)
            //  ->where('class_details.school_year_id', $request->search_sy)
            // ->orderBy('class_subject_details.class_time_from', 'ASC');
            ->orderBY('class_subject_details.class_subject_order','DESC')
            ->first();       

            $type = "";
           
        // return json_encode($ClassSubjectDetail);
        return view('control_panel_faculty.my_advisory_class.partials.data_list', compact('type','NumberOfSubject', 'ClassSubjectDetail','AdvisorySubject','GradeSheetMale','GradeSheetFeMale','quarter','AdvisorySubject','sem'))->render();
    }

    public function first_sem_2quarter (Request $request) 
    {
        $FacultyInformation = \App\FacultyInformation::where('user_id', \Auth::user()->id)->first();
     
        $ClassSubjectDetail = \App\ClassSubjectDetail::join('class_details', 'class_details.id', '=', 'class_subject_details.class_details_id')
            // ->join('subject_details', 'subject_details.id', '=', 'class_subject_details.subject_id')
            ->join('rooms','rooms.id', '=', 'class_details.room_id')
            ->join('section_details', 'section_details.id', '=', 'class_details.section_id')
            // ->whereRaw('class_subject_details.id = '. $request->search_class_subject)
            // ->whereRaw('class_details.id = '. $search_class_subject[1])
            ->where('class_details.adviser_id', $FacultyInformation->id)
            ->where('class_details.school_year_id', $request->search_sy1)
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

            
        
            $AdvisorySubject = \App\ClassSubjectDetail::join('subject_details', 'subject_details.id', '=' ,'class_subject_details.subject_id')
            ->join('class_details', 'class_details.id', '=' ,'class_subject_details.class_details_id')
            ->join('faculty_informations', 'faculty_informations.id', '=' ,'class_subject_details.faculty_id')           
            ->selectRaw("                
                subject_details.subject, subject_details.id, class_subject_details.class_subject_order, subject_details.subject_code
            ")
            ->where('class_subject_details.class_details_id', $ClassSubjectDetail->id)
            ->where('class_subject_details.status', 1)
            // ->where('faculty_id', $FacultyInformation->id)
            ->where('class_details.school_year_id', $request->search_sy1)
            ->where('class_subject_details.sem', 1)
            //  ->where('class_details.school_year_id', $request->search_sy)
            // ->orderBy('class_subject_details.class_time_from', 'ASC');
            ->orderBY('class_subject_details.class_subject_order','ASC')
            ->get();            
            

            $GradeSheetMale = \App\Grade_sheet_firstsemsecond::join('class_details','class_details.section_id','=','grade_sheet_firstsemseconds.section_details_id')            
            ->join('enrollments','enrollments.id','=','grade_sheet_firstsemseconds.enrollment_id')
            ->join('student_informations','student_informations.id','=','enrollments.student_information_id')
            ->where('class_details.section_id', $ClassSubjectDetail->section_id)
            ->whereRaw('student_informations.gender = 1')
            ->selectRaw("                    
                    CONCAT(student_informations.last_name, ' ', student_informations.first_name, ' ', student_informations.middle_name) as student_name,
                    grade_sheet_firstsemseconds.subject_1, grade_sheet_firstsemseconds.subject_2, grade_sheet_firstsemseconds.subject_3, grade_sheet_firstsemseconds.subject_4
                    , grade_sheet_firstsemseconds.subject_5, grade_sheet_firstsemseconds.subject_6, grade_sheet_firstsemseconds.subject_7
                    , grade_sheet_firstsemseconds.subject_8, grade_sheet_firstsemseconds.subject_9,grade_sheet_firstsemseconds.enrollment_id
                    ")
            ->distinct()
            ->orderBY('student_name','ASC')
            ->get();

            $GradeSheetFeMale = \App\Grade_sheet_firstsemsecond::join('class_details','class_details.section_id','=','grade_sheet_firstsemseconds.section_details_id')            
            ->join('enrollments','enrollments.id','=','grade_sheet_firstsemseconds.enrollment_id')
            ->join('student_informations','student_informations.id','=','enrollments.student_information_id')
            ->where('class_details.section_id', $ClassSubjectDetail->section_id)
            ->whereRaw('student_informations.gender = 2')
            ->selectRaw("                    
                    CONCAT(student_informations.last_name, ' ', student_informations.first_name, ' ', student_informations.middle_name) as student_name,
                    grade_sheet_firstsemseconds.subject_1, grade_sheet_firstsemseconds.subject_2, grade_sheet_firstsemseconds.subject_3, grade_sheet_firstsemseconds.subject_4
                    , grade_sheet_firstsemseconds.subject_5, grade_sheet_firstsemseconds.subject_6, grade_sheet_firstsemseconds.subject_7
                    , grade_sheet_firstsemseconds.subject_8, grade_sheet_firstsemseconds.subject_9,grade_sheet_firstsemseconds.enrollment_id
                    ")
            ->distinct()
            ->orderBY('student_name','ASC')
            ->get();

            $quarter = 'Second';
            $sem = 'First';
            $type = "";

            $NumberOfSubject = \App\ClassSubjectDetail::join('subject_details', 'subject_details.id', '=' ,'class_subject_details.subject_id')
            ->join('class_details', 'class_details.id', '=' ,'class_subject_details.class_details_id')
            ->join('faculty_informations', 'faculty_informations.id', '=' ,'class_subject_details.faculty_id')           
            ->selectRaw("                
                            class_subject_details.class_subject_order
                        ")
            ->where('class_subject_details.class_details_id', $ClassSubjectDetail->id)
            ->where('class_subject_details.status', 1)
            // ->where('faculty_id', $FacultyInformation->id)
            ->where('class_details.school_year_id', $request->search_sy1)
            ->where('class_subject_details.sem', 1)
            //  ->where('class_details.school_year_id', $request->search_sy)
            // ->orderBy('class_subject_details.class_time_from', 'ASC');
            ->orderBY('class_subject_order','DESC')
            ->first();       

           
        // return json_encode($ClassSubjectDetail);
        return view('control_panel_faculty.my_advisory_class.partials.data_list', compact('type', 'NumberOfSubject','ClassSubjectDetail','AdvisorySubject','GradeSheetMale','GradeSheetFeMale','quarter','AdvisorySubject','sem'))->render();
    }

    public function first_sem_3quarter (Request $request) 
    {
        $FacultyInformation = \App\FacultyInformation::where('user_id', \Auth::user()->id)->first();
     
        $ClassSubjectDetail = \App\ClassSubjectDetail::join('class_details', 'class_details.id', '=', 'class_subject_details.class_details_id')
            // ->join('subject_details', 'subject_details.id', '=', 'class_subject_details.subject_id')
            ->join('rooms','rooms.id', '=', 'class_details.room_id')
            ->join('section_details', 'section_details.id', '=', 'class_details.section_id')
            // ->whereRaw('class_subject_details.id = '. $request->search_class_subject)
            // ->whereRaw('class_details.id = '. $search_class_subject[1])
            ->where('class_details.adviser_id', $FacultyInformation->id)
            ->where('class_details.school_year_id', $request->search_sy1)
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

            
        
            $AdvisorySubject = \App\ClassSubjectDetail::join('subject_details', 'subject_details.id', '=' ,'class_subject_details.subject_id')
            ->join('class_details', 'class_details.id', '=' ,'class_subject_details.class_details_id')
            ->join('faculty_informations', 'faculty_informations.id', '=' ,'class_subject_details.faculty_id')           
            ->selectRaw("                
                subject_details.subject, subject_details.id, class_subject_details.class_subject_order, subject_details.subject_code
            ")
            ->where('class_subject_details.class_details_id', $ClassSubjectDetail->id)
            ->where('class_subject_details.status', 1)
            // ->where('faculty_id', $FacultyInformation->id)
            ->where('class_details.school_year_id', $request->search_sy1)
            ->where('class_subject_details.sem', 2)
            //  ->where('class_details.school_year_id', $request->search_sy)
            // ->orderBy('class_subject_details.class_time_from', 'ASC');
            ->orderBY('class_subject_details.class_subject_order','ASC')
            ->get();        
            
            $NumberOfSubject = \App\ClassSubjectDetail::join('subject_details', 'subject_details.id', '=' ,'class_subject_details.subject_id')
            ->join('class_details', 'class_details.id', '=' ,'class_subject_details.class_details_id')
            ->join('faculty_informations', 'faculty_informations.id', '=' ,'class_subject_details.faculty_id')           
            ->selectRaw("                
                            class_subject_details.class_subject_order
                        ")
            ->where('class_subject_details.class_details_id', $ClassSubjectDetail->id)
            ->where('class_subject_details.status', 1)
            // ->where('faculty_id', $FacultyInformation->id)
            ->where('class_details.school_year_id', $request->search_sy1)
            ->where('class_subject_details.sem', 2)
            //  ->where('class_details.school_year_id', $request->search_sy)
            // ->orderBy('class_subject_details.class_time_from', 'ASC');
            ->orderBY('class_subject_order','DESC')
            ->first();           
            

            $GradeSheetMale = \App\Grade11_Second_Sem::join('class_details','class_details.section_id','=','grade11__second__sems.section_details_id')            
            ->join('enrollments','enrollments.id','=','grade11__second__sems.enrollment_id')
            ->join('student_informations','student_informations.id','=','enrollments.student_information_id')
            ->where('class_details.section_id', $ClassSubjectDetail->section_id)
            ->whereRaw('student_informations.gender = 1')
            ->selectRaw("                    
                    CONCAT(student_informations.last_name, ' ', student_informations.first_name, ' ', student_informations.middle_name) as student_name,
                    grade11__second__sems.subject_1, grade11__second__sems.subject_2, grade11__second__sems.subject_3, grade11__second__sems.subject_4
                    , grade11__second__sems.subject_5, grade11__second__sems.subject_6, grade11__second__sems.subject_7
                    , grade11__second__sems.subject_8, grade11__second__sems.subject_9
                    ")
            ->distinct()
            ->orderBY('student_name','ASC')
            ->get();

            $GradeSheetFeMale = \App\Grade11_Second_Sem::join('class_details','class_details.section_id','=','grade11__second__sems.section_details_id')            
            ->join('enrollments','enrollments.id','=','grade11__second__sems.enrollment_id')
            ->join('student_informations','student_informations.id','=','enrollments.student_information_id')
            ->where('class_details.section_id', $ClassSubjectDetail->section_id)
            ->whereRaw('student_informations.gender = 2')
            ->selectRaw("                    
                    CONCAT(student_informations.last_name, ' ', student_informations.first_name, ' ', student_informations.middle_name) as student_name,
                    grade11__second__sems.subject_1, grade11__second__sems.subject_2, grade11__second__sems.subject_3, grade11__second__sems.subject_4
                    , grade11__second__sems.subject_5, grade11__second__sems.subject_6, grade11__second__sems.subject_7
                    , grade11__second__sems.subject_8, grade11__second__sems.subject_9
                    ")
            ->distinct()
            ->orderBY('student_name','ASC')
            ->get();

            $quarter = 'First';
            $sem = 'Second';
            $type = "";

           
        // return json_encode($ClassSubjectDetail);
        return view('control_panel_faculty.my_advisory_class.partials.data_list', compact( 'type','NumberOfSubject','ClassSubjectDetail','AdvisorySubject','GradeSheetMale','GradeSheetFeMale','quarter','AdvisorySubject','sem'))->render();
    }

    public function first_sem_4quarter (Request $request) 
    {
        $FacultyInformation = \App\FacultyInformation::where('user_id', \Auth::user()->id)->first();
        $type = "";
        $ClassSubjectDetail = \App\ClassSubjectDetail::join('class_details', 'class_details.id', '=', 'class_subject_details.class_details_id')
            // ->join('subject_details', 'subject_details.id', '=', 'class_subject_details.subject_id')
            ->join('rooms','rooms.id', '=', 'class_details.room_id')
            ->join('section_details', 'section_details.id', '=', 'class_details.section_id')
            // ->whereRaw('class_subject_details.id = '. $request->search_class_subject)
            // ->whereRaw('class_details.id = '. $search_class_subject[1])
            ->where('class_details.adviser_id', $FacultyInformation->id)
            ->where('class_details.school_year_id', $request->search_sy1)
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

            
        
            $AdvisorySubject = \App\ClassSubjectDetail::join('subject_details', 'subject_details.id', '=' ,'class_subject_details.subject_id')
            ->join('class_details', 'class_details.id', '=' ,'class_subject_details.class_details_id')
            ->join('faculty_informations', 'faculty_informations.id', '=' ,'class_subject_details.faculty_id')           
            ->selectRaw("                
                subject_details.subject, subject_details.id, class_subject_details.class_subject_order, subject_details.subject_code
            ")
            ->where('class_subject_details.class_details_id', $ClassSubjectDetail->id)
            ->where('class_subject_details.status', 1)
            // ->where('faculty_id', $FacultyInformation->id)
            ->where('class_details.school_year_id', $request->search_sy1)
            ->where('class_subject_details.sem', 2)
            //  ->where('class_details.school_year_id', $request->search_sy)
            // ->orderBy('class_subject_details.class_time_from', 'ASC');
            ->orderBY('class_subject_details.class_subject_order','ASC')
            ->get();            
            

            $GradeSheetMale = \App\Grade_sheet_secondsemsecond::join('class_details','class_details.section_id','=','grade_sheet_secondsemseconds.section_details_id')            
            ->join('enrollments','enrollments.id','=','grade_sheet_secondsemseconds.enrollment_id')
            ->join('student_informations','student_informations.id','=','enrollments.student_information_id')
            ->where('class_details.section_id', $ClassSubjectDetail->section_id)
            ->whereRaw('student_informations.gender = 1')
            ->selectRaw("                    
                    CONCAT(student_informations.last_name, ' ', student_informations.first_name, ' ', student_informations.middle_name) as student_name,
                    grade_sheet_secondsemseconds.subject_1, grade_sheet_secondsemseconds.subject_2, grade_sheet_secondsemseconds.subject_3, grade_sheet_secondsemseconds.subject_4
                    , grade_sheet_secondsemseconds.subject_5, grade_sheet_secondsemseconds.subject_6, grade_sheet_secondsemseconds.subject_7
                    , grade_sheet_secondsemseconds.subject_8, grade_sheet_secondsemseconds.subject_9,grade_sheet_secondsemseconds.enrollment_id
                    ")
            ->distinct()
            ->orderBY('student_name','ASC')
            ->get();

            $GradeSheetFeMale = \App\Grade_sheet_secondsemsecond::join('class_details','class_details.section_id','=','grade_sheet_secondsemseconds.section_details_id')            
            ->join('enrollments','enrollments.id','=','grade_sheet_secondsemseconds.enrollment_id')
            ->join('student_informations','student_informations.id','=','enrollments.student_information_id')
            ->where('class_details.section_id', $ClassSubjectDetail->section_id)
            ->whereRaw('student_informations.gender = 2')
            ->selectRaw("                    
                    CONCAT(student_informations.last_name, ' ', student_informations.first_name, ' ', student_informations.middle_name) as student_name,
                    grade_sheet_secondsemseconds.subject_1, grade_sheet_secondsemseconds.subject_2, grade_sheet_secondsemseconds.subject_3, grade_sheet_secondsemseconds.subject_4
                    , grade_sheet_secondsemseconds.subject_5, grade_sheet_secondsemseconds.subject_6, grade_sheet_secondsemseconds.subject_7
                    , grade_sheet_secondsemseconds.subject_8, grade_sheet_secondsemseconds.subject_9,grade_sheet_secondsemseconds.enrollment_id
                    ")
            ->distinct()
            ->orderBY('student_name','ASC')
            ->get();

            $quarter = 'Second';
            $sem = 'Second';

            $NumberOfSubject = \App\ClassSubjectDetail::join('subject_details', 'subject_details.id', '=' ,'class_subject_details.subject_id')
            ->join('class_details', 'class_details.id', '=' ,'class_subject_details.class_details_id')
            ->join('faculty_informations', 'faculty_informations.id', '=' ,'class_subject_details.faculty_id')           
            ->selectRaw("                
                            class_subject_details.class_subject_order
                        ")
            ->where('class_subject_details.class_details_id', $ClassSubjectDetail->id)
            ->where('class_subject_details.status', 1)
            // ->where('faculty_id', $FacultyInformation->id)
            ->where('class_details.school_year_id', $request->search_sy1)
            ->where('class_subject_details.sem', 2)
            //  ->where('class_details.school_year_id', $request->search_sy)
            // ->orderBy('class_subject_details.class_time_from', 'ASC');
            ->orderBY('class_subject_order','DESC')
            ->first();      

           
        // return json_encode($ClassSubjectDetail);
        return view('control_panel_faculty.my_advisory_class.partials.data_list', compact( 'type','NumberOfSubject','ClassSubjectDetail','AdvisorySubject','GradeSheetMale','GradeSheetFeMale','quarter','AdvisorySubject','sem'))->render();
    }

// JUNIOR highschool
    public function firstquarter (Request $request) 
    {
        $FacultyInformation = \App\FacultyInformation::where('user_id', \Auth::user()->id)->first();
        $type = "";
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
                class_subject_details.status as grading_status
            '))
            ->first();
            
        
            $AdvisorySubject = \App\ClassSubjectDetail::join('subject_details', 'subject_details.id', '=' ,'class_subject_details.subject_id')
            ->join('class_details', 'class_details.id', '=' ,'class_subject_details.class_details_id')
            ->join('faculty_informations', 'faculty_informations.id', '=' ,'class_subject_details.faculty_id')           
            ->selectRaw("                
                subject_details.subject, subject_details.id
            ")
            ->where('class_subject_details.class_details_id', $ClassSubjectDetail->id)
            ->where('class_subject_details.status', 1)
            // ->where('faculty_id', $FacultyInformation->id)
            ->where('class_details.school_year_id', $request->search_sy)
            //  ->where('class_details.school_year_id', $request->search_sy)
            // ->orderBy('class_subject_details.class_time_from', 'ASC');
            ->get();            
            $quarter = 'First';

            $GradeSheetMale = \App\Grade_sheet_first::join('class_details','class_details.section_id','=','grade_sheet_firsts.section_details_id')            
            ->join('enrollments','enrollments.id','=','grade_sheet_firsts.enrollment_id')
            ->join('student_informations','student_informations.id','=','enrollments.student_information_id')
            ->where('class_details.section_id', $ClassSubjectDetail->section_id)
            ->whereRaw('student_informations.gender = 1')
            ->selectRaw("                    
                    CONCAT(student_informations.last_name, ' ', student_informations.first_name, ' ', student_informations.middle_name) as student_name,
                    grade_sheet_firsts.filipino, grade_sheet_firsts.english, grade_sheet_firsts.math, grade_sheet_firsts.science, grade_sheet_firsts.ap, grade_sheet_firsts.ict, grade_sheet_firsts.mapeh
                    , grade_sheet_firsts.esp, grade_sheet_firsts.religion
                    ")
            ->distinct()
            ->orderBY('student_name','ASC')
            ->get();

            $GradeSheetFeMale = \App\Grade_sheet_first::join('class_details','class_details.section_id','=','grade_sheet_firsts.section_details_id')            
            ->join('enrollments','enrollments.id','=','grade_sheet_firsts.enrollment_id')
            ->join('student_informations','student_informations.id','=','enrollments.student_information_id')
            ->where('class_details.section_id', $ClassSubjectDetail->section_id)
            ->whereRaw('student_informations.gender = 2')
            ->selectRaw("                    
                    CONCAT(student_informations.last_name, ' ', student_informations.first_name, ' ', student_informations.middle_name) as student_name,
                    grade_sheet_firsts.filipino, grade_sheet_firsts.english, grade_sheet_firsts.math, grade_sheet_firsts.science, grade_sheet_firsts.ap, grade_sheet_firsts.ict, grade_sheet_firsts.mapeh
                    , grade_sheet_firsts.esp, grade_sheet_firsts.religion
                    ")
            ->distinct()
            ->orderBY('student_name','ASC')
            ->get();

            

           
        // return json_encode($ClassSubjectDetail);
        return view('control_panel_faculty.my_advisory_class.partials.data_list', compact( 'type','ClassSubjectDetail','AdvisorySubject','GradeSheetMale','GradeSheetFeMale','quarter'))->render();
    }

    public function secondquarter (Request $request) 
    {
        $FacultyInformation = \App\FacultyInformation::where('user_id', \Auth::user()->id)->first();
        $type = "";
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
                class_subject_details.status as grading_status
            '))
            ->first();
            
        
            $AdvisorySubject = \App\ClassSubjectDetail::join('subject_details', 'subject_details.id', '=' ,'class_subject_details.subject_id')
            ->join('class_details', 'class_details.id', '=' ,'class_subject_details.class_details_id')
            ->join('faculty_informations', 'faculty_informations.id', '=' ,'class_subject_details.faculty_id')           
            ->selectRaw("                
                subject_details.subject, subject_details.id
            ")
            ->where('class_subject_details.class_details_id', $ClassSubjectDetail->id)
            ->where('class_subject_details.status', 1)
            // ->where('faculty_id', $FacultyInformation->id)
            ->where('class_details.school_year_id', $request->search_sy)
            //  ->where('class_details.school_year_id', $request->search_sy)
            // ->orderBy('class_subject_details.class_time_from', 'ASC');
            ->get();


            
            

            $GradeSheetMale = \App\Grade_sheet_second::join('class_details','class_details.section_id','=','grade_sheet_seconds.section_details_id')            
            ->join('enrollments','enrollments.id','=','grade_sheet_seconds.enrollment_id')
            ->join('student_informations','student_informations.id','=','enrollments.student_information_id')
            ->where('class_details.section_id', $ClassSubjectDetail->section_id)
            ->whereRaw('student_informations.gender = 1')
            ->selectRaw("                    
                    CONCAT(student_informations.last_name, ' ', student_informations.first_name, ' ', student_informations.middle_name) as student_name,
                    grade_sheet_seconds.filipino, grade_sheet_seconds.english, grade_sheet_seconds.math, grade_sheet_seconds.science, grade_sheet_seconds.ap, grade_sheet_seconds.ict, grade_sheet_seconds.mapeh
                    , grade_sheet_seconds.esp, grade_sheet_seconds.religion
                    ")
            ->distinct()
            ->orderBY('student_name','ASC')
            ->get();

            $GradeSheetFeMale = \App\Grade_sheet_second::join('class_details','class_details.section_id','=','grade_sheet_seconds.section_details_id')            
            ->join('enrollments','enrollments.id','=','grade_sheet_seconds.enrollment_id')
            ->join('student_informations','student_informations.id','=','enrollments.student_information_id')
            ->where('class_details.section_id', $ClassSubjectDetail->section_id)
            ->whereRaw('student_informations.gender = 2')
            ->selectRaw("                    
                    CONCAT(student_informations.last_name, ' ', student_informations.first_name, ' ', student_informations.middle_name) as student_name,
                    grade_sheet_seconds.filipino, grade_sheet_seconds.english, grade_sheet_seconds.math, grade_sheet_seconds.science, grade_sheet_seconds.ap, grade_sheet_seconds.ict, grade_sheet_seconds.mapeh
                    , grade_sheet_seconds.esp, grade_sheet_seconds.religion
                    ")
            ->distinct()
            ->orderBY('student_name','ASC')
            ->get();

            $quarter = 'Second';
           
        // return json_encode($ClassSubjectDetail);
        return view('control_panel_faculty.my_advisory_class.partials.data_list', compact( 'type','ClassSubjectDetail','AdvisorySubject','GradeSheetMale','GradeSheetFeMale','quarter'))->render();
    }

    public function thirdquarter (Request $request) 
    {
        $FacultyInformation = \App\FacultyInformation::where('user_id', \Auth::user()->id)->first();
        $type = "";
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
                class_subject_details.status as grading_status
            '))
            ->first();
            
        
            $AdvisorySubject = \App\ClassSubjectDetail::join('subject_details', 'subject_details.id', '=' ,'class_subject_details.subject_id')
            ->join('class_details', 'class_details.id', '=' ,'class_subject_details.class_details_id')
            ->join('faculty_informations', 'faculty_informations.id', '=' ,'class_subject_details.faculty_id')           
            ->selectRaw("                
                subject_details.subject, subject_details.id
            ")
            ->where('class_subject_details.class_details_id', $ClassSubjectDetail->id)
            ->where('class_subject_details.status', 1)
            // ->where('faculty_id', $FacultyInformation->id)
            ->where('class_details.school_year_id', $request->search_sy)
            //  ->where('class_details.school_year_id', $request->search_sy)
            // ->orderBy('class_subject_details.class_time_from', 'ASC');
            ->get();


            
            

            $GradeSheetMale = \App\Grade_sheet_third::join('class_details','class_details.section_id','=','grade_sheet_thirds.section_details_id')            
            ->join('enrollments','enrollments.id','=','grade_sheet_thirds.enrollment_id')
            ->join('student_informations','student_informations.id','=','enrollments.student_information_id')
            ->where('class_details.section_id', $ClassSubjectDetail->section_id)
            ->whereRaw('student_informations.gender = 1')
            ->selectRaw("                    
                    CONCAT(student_informations.last_name, ' ', student_informations.first_name, ' ', student_informations.middle_name) as student_name,
                    grade_sheet_thirds.filipino, grade_sheet_thirds.english, grade_sheet_thirds.math, grade_sheet_thirds.science, grade_sheet_thirds.ap, grade_sheet_thirds.ict, grade_sheet_thirds.mapeh
                    , grade_sheet_thirds.esp, grade_sheet_thirds.religion
                    ")
            ->distinct()
            ->orderBY('student_name','ASC')
            ->get();

            $GradeSheetFeMale = \App\Grade_sheet_third::join('class_details','class_details.section_id','=','grade_sheet_thirds.section_details_id')            
            ->join('enrollments','enrollments.id','=','grade_sheet_thirds.enrollment_id')
            ->join('student_informations','student_informations.id','=','enrollments.student_information_id')
            ->where('class_details.section_id', $ClassSubjectDetail->section_id)
            ->whereRaw('student_informations.gender = 2')
            ->selectRaw("                    
                    CONCAT(student_informations.last_name, ' ', student_informations.first_name, ' ', student_informations.middle_name) as student_name,
                    grade_sheet_thirds.filipino, grade_sheet_thirds.english, grade_sheet_thirds.math, grade_sheet_thirds.science, grade_sheet_thirds.ap, grade_sheet_thirds.ict, grade_sheet_thirds.mapeh
                    , grade_sheet_thirds.esp, grade_sheet_thirds.religion
                    ")
            ->distinct()
            ->orderBY('student_name','ASC')
            ->get();

            $quarter = 'Third';
        // return json_encode($ClassSubjectDetail);
        return view('control_panel_faculty.my_advisory_class.partials.data_list', compact( 'type','ClassSubjectDetail','AdvisorySubject','GradeSheetMale','GradeSheetFeMale','quarter'))->render();
    }

    public function fourthquarter (Request $request) 
    {
        $FacultyInformation = \App\FacultyInformation::where('user_id', \Auth::user()->id)->first();
        $type = "";
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
                class_subject_details.status as grading_status
            '))
            ->first();
            
        
            $AdvisorySubject = \App\ClassSubjectDetail::join('subject_details', 'subject_details.id', '=' ,'class_subject_details.subject_id')
            ->join('class_details', 'class_details.id', '=' ,'class_subject_details.class_details_id')
            ->join('faculty_informations', 'faculty_informations.id', '=' ,'class_subject_details.faculty_id')           
            ->selectRaw("                
                subject_details.subject, subject_details.id
            ")
            ->where('class_subject_details.class_details_id', $ClassSubjectDetail->id)
            ->where('class_subject_details.status', 1)
            // ->where('faculty_id', $FacultyInformation->id)
            ->where('class_details.school_year_id', $request->search_sy)
            //  ->where('class_details.school_year_id', $request->search_sy)
            // ->orderBy('class_subject_details.class_time_from', 'ASC');
            ->get();


            
            

            $GradeSheetMale = \App\Grade_sheet_fourth::join('class_details','class_details.section_id','=','grade_sheet_fourths.section_details_id')            
            ->join('enrollments','enrollments.id','=','grade_sheet_fourths.enrollment_id')
            ->join('student_informations','student_informations.id','=','enrollments.student_information_id')
            ->where('class_details.section_id', $ClassSubjectDetail->section_id)
            ->whereRaw('student_informations.gender = 1')
            ->selectRaw("  grade_sheet_fourths.enrollment_id,                
                    CONCAT(student_informations.last_name, ' ', student_informations.first_name, ' ', student_informations.middle_name) as student_name,
                    grade_sheet_fourths.filipino, grade_sheet_fourths.english, grade_sheet_fourths.math, grade_sheet_fourths.science, grade_sheet_fourths.ap, grade_sheet_fourths.ict, grade_sheet_fourths.mapeh
                    , grade_sheet_fourths.esp, grade_sheet_fourths.religion
                    ")
            ->distinct()
            ->orderBY('student_name','ASC')
            ->get();

            $GradeSheetFeMale = \App\Grade_sheet_fourth::join('class_details','class_details.section_id','=','grade_sheet_fourths.section_details_id')            
            ->join('enrollments','enrollments.id','=','grade_sheet_fourths.enrollment_id')
            ->join('student_informations','student_informations.id','=','enrollments.student_information_id')
            ->where('class_details.section_id', $ClassSubjectDetail->section_id)
            ->whereRaw('student_informations.gender = 2')
            ->selectRaw(" grade_sheet_fourths.enrollment_id,                   
                    CONCAT(student_informations.last_name, ' ', student_informations.first_name, ' ', student_informations.middle_name) as student_name,
                    grade_sheet_fourths.filipino, grade_sheet_fourths.english, grade_sheet_fourths.math, grade_sheet_fourths.science, grade_sheet_fourths.ap, grade_sheet_fourths.ict, grade_sheet_fourths.mapeh
                    , grade_sheet_fourths.esp, grade_sheet_fourths.religion
                    ")
            ->distinct()
            ->orderBY('student_name','ASC')
            ->get();

            $quarter = 'Fourth';           
        // return json_encode($ClassSubjectDetail);
        return view('control_panel_faculty.my_advisory_class.partials.data_list', compact('type','ClassSubjectDetail','AdvisorySubject','GradeSheetMale','GradeSheetFeMale','quarter'))->render();
    }




    public function print_firstquarter (Request $request) 
    {
        $FacultyInformation = \App\FacultyInformation::where('user_id', \Auth::user()->id)->first();
     
        $ClassSubjectDetail = \App\ClassSubjectDetail::join('class_details', 'class_details.id', '=', 'class_subject_details.class_details_id')
            // ->join('subject_details', 'subject_details.id', '=', 'class_subject_details.subject_id')
            ->join('rooms','rooms.id', '=', 'class_details.room_id')
            ->join('section_details', 'section_details.id', '=', 'class_details.section_id')
            ->join('school_years', 'school_years.id' ,'=', 'class_details.school_year_id')
            ->join('faculty_informations', 'faculty_informations.id','=','class_details.adviser_id')
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
                faculty_informations.first_name, faculty_informations.middle_name ,  faculty_informations.last_name,
                school_years.school_year                
            '))
            ->first();
            
        
            $AdvisorySubject = \App\ClassSubjectDetail::join('subject_details', 'subject_details.id', '=' ,'class_subject_details.subject_id')
            ->join('class_details', 'class_details.id', '=' ,'class_subject_details.class_details_id')
            ->join('faculty_informations', 'faculty_informations.id', '=' ,'class_subject_details.faculty_id')           
            ->selectRaw("                
                subject_details.subject, subject_details.id
            ")
            ->where('class_subject_details.class_details_id', $ClassSubjectDetail->id)
            ->where('class_subject_details.status', 1)
            // ->where('faculty_id', $FacultyInformation->id)
            ->where('class_details.school_year_id', $request->search_sy)
            //  ->where('class_details.school_year_id', $request->search_sy)
            // ->orderBy('class_subject_details.class_time_from', 'ASC');
            ->get();            
            

            $GradeSheetMale = \App\Grade_sheet_first::join('class_details','class_details.section_id','=','grade_sheet_firsts.section_details_id')            
            ->join('enrollments','enrollments.id','=','grade_sheet_firsts.enrollment_id')
            ->join('student_informations','student_informations.id','=','enrollments.student_information_id')
            ->where('class_details.section_id', $ClassSubjectDetail->section_id)
            ->whereRaw('student_informations.gender = 1')
            ->selectRaw("                    
                    CONCAT(student_informations.last_name, ' ', student_informations.first_name, ' ', student_informations.middle_name) as student_name,
                    grade_sheet_firsts.filipino, grade_sheet_firsts.english, grade_sheet_firsts.math, grade_sheet_firsts.science, grade_sheet_firsts.ap, grade_sheet_firsts.ict, grade_sheet_firsts.mapeh
                    , grade_sheet_firsts.esp, grade_sheet_firsts.religion
                    ")
            ->distinct()
            ->orderBY('student_name','ASC')
            ->get();

            $GradeSheetFeMale = \App\Grade_sheet_first::join('class_details','class_details.section_id','=','grade_sheet_firsts.section_details_id')            
            ->join('enrollments','enrollments.id','=','grade_sheet_firsts.enrollment_id')
            ->join('student_informations','student_informations.id','=','enrollments.student_information_id')
            ->where('class_details.section_id', $ClassSubjectDetail->section_id)
            ->whereRaw('student_informations.gender = 2')
            ->selectRaw("                    
                    CONCAT(student_informations.last_name, ' ', student_informations.first_name, ' ', student_informations.middle_name) as student_name,
                    grade_sheet_firsts.filipino, grade_sheet_firsts.english, grade_sheet_firsts.math, grade_sheet_firsts.science, grade_sheet_firsts.ap, grade_sheet_firsts.ict, grade_sheet_firsts.mapeh
                    , grade_sheet_firsts.esp, grade_sheet_firsts.religion
                    ")
            ->distinct()
            ->orderBY('student_name','ASC')
            ->get();

            $quarter = 'First';

           
        // return json_encode($ClassSubjectDetail);
        // $GradeSheetData = json_decode(json_encode($ClassSubjectDetail));
        
        return view('control_panel_faculty.my_advisory_class.partials.print_first_quarter', compact( 'ClassSubjectDetail','AdvisorySubject','GradeSheetMale','GradeSheetFeMale','quarter'));
        $pdf = \PDF::loadView('control_panel_faculty.my_advisory_class.partials.print_first_quarter', compact( 'ClassSubjectDetail','AdvisorySubject','GradeSheetMale','GradeSheetFeMale','quarter'));
        $pdf->setPaper('Legal', 'portrait');
        $pdf->setPaper([0, 0, 396.85, 1800.98],'Legal', 'portrait');
        return $pdf->stream();
        // return json_encode(['GradeSheetData' => $ClassSubjectDetail,]);
    }

    public function print_secondquarter (Request $request) 
    {
        $FacultyInformation = \App\FacultyInformation::where('user_id', \Auth::user()->id)->first();
     
        $ClassSubjectDetail = \App\ClassSubjectDetail::join('class_details', 'class_details.id', '=', 'class_subject_details.class_details_id')
            // ->join('subject_details', 'subject_details.id', '=', 'class_subject_details.subject_id')
            ->join('rooms','rooms.id', '=', 'class_details.room_id')
            ->join('section_details', 'section_details.id', '=', 'class_details.section_id')
            ->join('school_years', 'school_years.id' ,'=', 'class_details.school_year_id')
            ->join('faculty_informations', 'faculty_informations.id','=','class_details.adviser_id')
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
                faculty_informations.first_name, faculty_informations.middle_name ,  faculty_informations.last_name,
                school_years.school_year                
            '))
            ->first();
            
        
            $AdvisorySubject = \App\ClassSubjectDetail::join('subject_details', 'subject_details.id', '=' ,'class_subject_details.subject_id')
            ->join('class_details', 'class_details.id', '=' ,'class_subject_details.class_details_id')
            ->join('faculty_informations', 'faculty_informations.id', '=' ,'class_subject_details.faculty_id')           
            ->selectRaw("                
                subject_details.subject, subject_details.id
            ")
            ->where('class_subject_details.class_details_id', $ClassSubjectDetail->id)
            ->where('class_subject_details.status', 1)
            // ->where('faculty_id', $FacultyInformation->id)
            ->where('class_details.school_year_id', $request->search_sy)
            //  ->where('class_details.school_year_id', $request->search_sy)
            // ->orderBy('class_subject_details.class_time_from', 'ASC');
            ->get();            
            

            $GradeSheetMale = \App\Grade_sheet_second::join('class_details','class_details.section_id','=','grade_sheet_seconds.section_details_id')            
            ->join('enrollments','enrollments.id','=','grade_sheet_seconds.enrollment_id')
            ->join('student_informations','student_informations.id','=','enrollments.student_information_id')
            ->where('class_details.section_id', $ClassSubjectDetail->section_id)
            ->whereRaw('student_informations.gender = 1')
            ->selectRaw("                    
                    CONCAT(student_informations.last_name, ' ', student_informations.first_name, ' ', student_informations.middle_name) as student_name,
                    grade_sheet_seconds.filipino, grade_sheet_seconds.english, grade_sheet_seconds.math, grade_sheet_seconds.science, grade_sheet_seconds.ap, grade_sheet_seconds.ict, grade_sheet_seconds.mapeh
                    , grade_sheet_seconds.esp, grade_sheet_seconds.religion
                    ")
            ->distinct()
            ->orderBY('student_name','ASC')
            ->get();

            $GradeSheetFeMale = \App\Grade_sheet_second::join('class_details','class_details.section_id','=','grade_sheet_seconds.section_details_id')            
            ->join('enrollments','enrollments.id','=','grade_sheet_seconds.enrollment_id')
            ->join('student_informations','student_informations.id','=','enrollments.student_information_id')
            ->where('class_details.section_id', $ClassSubjectDetail->section_id)
            ->whereRaw('student_informations.gender = 2')
            ->selectRaw("                    
                    CONCAT(student_informations.last_name, ' ', student_informations.first_name, ' ', student_informations.middle_name) as student_name,
                    grade_sheet_seconds.filipino, grade_sheet_seconds.english, grade_sheet_seconds.math, grade_sheet_seconds.science, grade_sheet_seconds.ap, grade_sheet_seconds.ict, grade_sheet_seconds.mapeh
                    , grade_sheet_seconds.esp, grade_sheet_seconds.religion
                    ")
            ->distinct()
            ->orderBY('student_name','ASC')
            ->get();

            $quarter = 'Second';

           
        // return json_encode($ClassSubjectDetail);
        return view('control_panel_faculty.my_advisory_class.partials.print_first_quarter', 
            compact( 'ClassSubjectDetail','AdvisorySubject','GradeSheetMale','GradeSheetFeMale','quarter'));
        $pdf = \PDF::loadView('control_panel_faculty.my_advisory_class.partials.print_first_quarter',
            compact( 'ClassSubjectDetail','AdvisorySubject','GradeSheetMale','GradeSheetFeMale','quarter'));
        $pdf->setPaper('Legal', 'portrait');
        $pdf->setPaper([0, 0, 396.85, 1800.98],'Legal', 'Portrait');
        // $dompdf->setPaper(array(0,0,612,$height))
        return $pdf->stream();
    }

    public function print_thirdquarter (Request $request) 
    {
        $FacultyInformation = \App\FacultyInformation::where('user_id', \Auth::user()->id)->first();
     
        $ClassSubjectDetail = \App\ClassSubjectDetail::join('class_details', 'class_details.id', '=', 'class_subject_details.class_details_id')
            // ->join('subject_details', 'subject_details.id', '=', 'class_subject_details.subject_id')
            ->join('rooms','rooms.id', '=', 'class_details.room_id')
            ->join('section_details', 'section_details.id', '=', 'class_details.section_id')
            ->join('school_years', 'school_years.id' ,'=', 'class_details.school_year_id')
            ->join('faculty_informations', 'faculty_informations.id','=','class_details.adviser_id')
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
                faculty_informations.first_name, faculty_informations.middle_name ,  faculty_informations.last_name,
                school_years.school_year                
            '))
            ->first();
            
        
            $AdvisorySubject = \App\ClassSubjectDetail::join('subject_details', 'subject_details.id', '=' ,'class_subject_details.subject_id')
            ->join('class_details', 'class_details.id', '=' ,'class_subject_details.class_details_id')
            ->join('faculty_informations', 'faculty_informations.id', '=' ,'class_subject_details.faculty_id')           
            ->selectRaw("                
                subject_details.subject, subject_details.id
            ")
            ->where('class_subject_details.class_details_id', $ClassSubjectDetail->id)
            ->where('class_subject_details.status', 1)
            // ->where('faculty_id', $FacultyInformation->id)
            ->where('class_details.school_year_id', $request->search_sy)
            //  ->where('class_details.school_year_id', $request->search_sy)
            // ->orderBy('class_subject_details.class_time_from', 'ASC');
            ->get();            
            

            $GradeSheetMale = \App\Grade_sheet_third::join('class_details','class_details.section_id','=','grade_sheet_thirds.section_details_id')            
            ->join('enrollments','enrollments.id','=','grade_sheet_thirds.enrollment_id')
            ->join('student_informations','student_informations.id','=','enrollments.student_information_id')
            ->where('class_details.section_id', $ClassSubjectDetail->section_id)
            ->whereRaw('student_informations.gender = 1')
            ->selectRaw("                    
                    CONCAT(student_informations.last_name, ' ', student_informations.first_name, ' ', student_informations.middle_name) as student_name,
                    grade_sheet_thirds.filipino, grade_sheet_thirds.english, grade_sheet_thirds.math, grade_sheet_thirds.science, grade_sheet_thirds.ap, grade_sheet_thirds.ict, grade_sheet_thirds.mapeh
                    , grade_sheet_thirds.esp, grade_sheet_thirds.religion
                    ")
            ->distinct()
            ->orderBY('student_name','ASC')
            ->get();

            $GradeSheetFeMale = \App\Grade_sheet_third::join('class_details','class_details.section_id','=','grade_sheet_thirds.section_details_id')            
            ->join('enrollments','enrollments.id','=','grade_sheet_thirds.enrollment_id')
            ->join('student_informations','student_informations.id','=','enrollments.student_information_id')
            ->where('class_details.section_id', $ClassSubjectDetail->section_id)
            ->whereRaw('student_informations.gender = 2')
            ->selectRaw("                    
                    CONCAT(student_informations.last_name, ' ', student_informations.first_name, ' ', student_informations.middle_name) as student_name,
                    grade_sheet_thirds.filipino, grade_sheet_thirds.english, grade_sheet_thirds.math, grade_sheet_thirds.science, grade_sheet_thirds.ap, grade_sheet_thirds.ict, grade_sheet_thirds.mapeh
                    , grade_sheet_thirds.esp, grade_sheet_thirds.religion
                    ")
            ->distinct()
            ->orderBY('student_name','ASC')
            ->get();

            $quarter = 'Third';

           
        // return json_encode($ClassSubjectDetail);
        // return view('control_panel_faculty.my_advisory_class.partials.data_list', compact( 'ClassSubjectDetail','AdvisorySubject','GradeSheetMale','GradeSheetFeMale','quarter'))->render();
        return view('control_panel_faculty.my_advisory_class.partials.print_first_quarter', 
            compact( 'ClassSubjectDetail','AdvisorySubject','GradeSheetMale','GradeSheetFeMale','quarter'));
        $pdf = \PDF::loadView('control_panel_faculty.my_advisory_class.partials.print_first_quarter', 
        compact( 'ClassSubjectDetail','AdvisorySubject','GradeSheetMale','GradeSheetFeMale','quarter'));
        $pdf->setPaper('Legal', 'portrait');
        return $pdf->stream();
    }
//error
    public function print_fourthquarter (Request $request) 
    {
        
        
        $FacultyInformation = \App\FacultyInformation::where('user_id', \Auth::user()->id)->first();
     
        $ClassSubjectDetail = \App\ClassSubjectDetail::join('class_details', 'class_details.id', '=', 'class_subject_details.class_details_id')
            // ->join('subject_details', 'subject_details.id', '=', 'class_subject_details.subject_id')
            ->join('rooms','rooms.id', '=', 'class_details.room_id')
            ->join('section_details', 'section_details.id', '=', 'class_details.section_id')
            ->join('school_years', 'school_years.id' ,'=', 'class_details.school_year_id')
            ->join('faculty_informations', 'faculty_informations.id','=','class_details.adviser_id')
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
                faculty_informations.first_name, faculty_informations.middle_name ,  faculty_informations.last_name,
                school_years.school_year                
            '))
            ->first();
        
        
            $AdvisorySubject = \App\ClassSubjectDetail::join('subject_details', 'subject_details.id', '=' ,'class_subject_details.subject_id')
            ->join('class_details', 'class_details.id', '=' ,'class_subject_details.class_details_id')
            ->join('faculty_informations', 'faculty_informations.id', '=' ,'class_subject_details.faculty_id')           
            ->selectRaw("                
                subject_details.subject, subject_details.id
            ")
            ->where('class_subject_details.class_details_id', $ClassSubjectDetail->id)
            ->where('class_subject_details.status', 1)
            // ->where('faculty_id', $FacultyInformation->id)
            ->where('class_details.school_year_id', $request->search_sy)
            //  ->where('class_details.school_year_id', $request->search_sy)
            // ->orderBy('class_subject_details.class_time_from', 'ASC');
            ->get();


            
            

            $GradeSheetMale = \App\Grade_sheet_fourth::join('class_details','class_details.section_id','=','grade_sheet_fourths.section_details_id')            
            ->join('enrollments','enrollments.id','=','grade_sheet_fourths.enrollment_id')
            ->join('student_informations','student_informations.id','=','enrollments.student_information_id')
            ->where('class_details.section_id', $ClassSubjectDetail->section_id)
            ->whereRaw('student_informations.gender = 1')
            ->selectRaw("  grade_sheet_fourths.enrollment_id,                
                    CONCAT(student_informations.last_name, ' ', student_informations.first_name, ' ', student_informations.middle_name) as student_name,
                    grade_sheet_fourths.filipino, grade_sheet_fourths.english, grade_sheet_fourths.math, grade_sheet_fourths.science, grade_sheet_fourths.ap, grade_sheet_fourths.ict, grade_sheet_fourths.mapeh
                    , grade_sheet_fourths.esp, grade_sheet_fourths.religion
                    ")
            ->distinct()
            ->orderBY('student_name','ASC')
            ->get();

            $GradeSheetFeMale = \App\Grade_sheet_fourth::join('class_details','class_details.section_id','=','grade_sheet_fourths.section_details_id')            
            ->join('enrollments','enrollments.id','=','grade_sheet_fourths.enrollment_id')
            ->join('student_informations','student_informations.id','=','enrollments.student_information_id')
            ->where('class_details.section_id', $ClassSubjectDetail->section_id)
            ->whereRaw('student_informations.gender = 2')
            ->selectRaw(" grade_sheet_fourths.enrollment_id,                   
                    CONCAT(student_informations.last_name, ' ', student_informations.first_name, ' ', student_informations.middle_name) as student_name,
                    grade_sheet_fourths.filipino, grade_sheet_fourths.english, grade_sheet_fourths.math, grade_sheet_fourths.science, grade_sheet_fourths.ap, grade_sheet_fourths.ict, grade_sheet_fourths.mapeh
                    , grade_sheet_fourths.esp, grade_sheet_fourths.religion
                    ")
            ->distinct()
            ->orderBY('student_name','ASC')
            ->get();
            
            $quarter = 'Fourth';           
        // return json_encode($ClassSubjectDetail);
        return view('control_panel_faculty.my_advisory_class.partials.print_first_quarter', 
            compact( 'ClassSubjectDetail','AdvisorySubject','GradeSheetMale','GradeSheetFeMale','quarter'));
        $pdf = \PDF::loadView('control_panel_faculty.my_advisory_class.partials.print_first_quarter', 
        compact('ClassSubjectDetail','AdvisorySubject','GradeSheetMale','GradeSheetFeMale','quarter'));
        $pdf->setPaper('Legal', 'portrait');
        return $pdf->stream();
    }

    public function print_firstSem_1quarter(Request $request)
    {
        $FacultyInformation = \App\FacultyInformation::where('user_id', \Auth::user()->id)->first();
     
        $ClassSubjectDetail = \App\ClassSubjectDetail::join('class_details', 'class_details.id', '=', 'class_subject_details.class_details_id')
            // ->join('subject_details', 'subject_details.id', '=', 'class_subject_details.subject_id')
            ->join('rooms','rooms.id', '=', 'class_details.room_id')
            ->join('section_details', 'section_details.id', '=', 'class_details.section_id')
            ->join('school_years', 'school_years.id' ,'=', 'class_details.school_year_id')
            ->join('faculty_informations', 'faculty_informations.id','=','class_details.adviser_id')
            // ->whereRaw('class_subject_details.id = '. $request->search_class_subject)
            // ->whereRaw('class_details.id = '. $search_class_subject[1])
            ->where('class_details.adviser_id', $FacultyInformation->id)
            ->where('class_details.school_year_id', $request->search_sy1)
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
                class_subject_details.sem,
                faculty_informations.first_name, faculty_informations.middle_name ,  faculty_informations.last_name,
                school_years.school_year
            '))
            ->first();

            
        
            $AdvisorySubject = \App\ClassSubjectDetail::join('subject_details', 'subject_details.id', '=' ,'class_subject_details.subject_id')
            ->join('class_details', 'class_details.id', '=' ,'class_subject_details.class_details_id')
            ->join('faculty_informations', 'faculty_informations.id', '=' ,'class_subject_details.faculty_id')           
            ->selectRaw("                
                subject_details.subject, subject_details.id, class_subject_details.class_subject_order, subject_details.subject_code
            ")
            ->where('class_subject_details.class_details_id', $ClassSubjectDetail->id)
            ->where('class_subject_details.status', 1)
            // ->where('faculty_id', $FacultyInformation->id)
            ->where('class_details.school_year_id', $request->search_sy1)
            ->where('class_subject_details.sem', 1)
            //  ->where('class_details.school_year_id', $request->search_sy)
            // ->orderBy('class_subject_details.class_time_from', 'ASC');
            ->orderBY('class_subject_order','ASC')
            ->get();            
            

            $GradeSheetMale = \App\Grade_sheet_firstsem::join('class_details','class_details.section_id','=','grade_sheet_firstsems.section_details_id')            
            ->join('enrollments','enrollments.id','=','grade_sheet_firstsems.enrollment_id')
            ->join('student_informations','student_informations.id','=','enrollments.student_information_id')
            ->where('class_details.section_id', $ClassSubjectDetail->section_id)
            ->whereRaw('student_informations.gender = 1')
            ->selectRaw("                    
                    CONCAT(student_informations.last_name, ' ', student_informations.first_name, ' ', student_informations.middle_name) as student_name,
                    grade_sheet_firstsems.subject_1, grade_sheet_firstsems.subject_2, grade_sheet_firstsems.subject_3, grade_sheet_firstsems.subject_4
                    , grade_sheet_firstsems.subject_5, grade_sheet_firstsems.subject_6, grade_sheet_firstsems.subject_7
                    , grade_sheet_firstsems.subject_8, grade_sheet_firstsems.subject_9
                    ")
            ->distinct()
            ->orderBY('student_name','ASC')
            ->get();

            $GradeSheetFeMale = \App\Grade_sheet_firstsem::join('class_details','class_details.section_id','=','grade_sheet_firstsems.section_details_id')            
            ->join('enrollments','enrollments.id','=','grade_sheet_firstsems.enrollment_id')
            ->join('student_informations','student_informations.id','=','enrollments.student_information_id')
            ->where('class_details.section_id', $ClassSubjectDetail->section_id)
            ->whereRaw('student_informations.gender = 2')
            ->selectRaw("                    
                    CONCAT(student_informations.last_name, ' ', student_informations.first_name, ' ', student_informations.middle_name) as student_name,
                    grade_sheet_firstsems.subject_1, grade_sheet_firstsems.subject_2, grade_sheet_firstsems.subject_3, grade_sheet_firstsems.subject_4
                    , grade_sheet_firstsems.subject_5, grade_sheet_firstsems.subject_6, grade_sheet_firstsems.subject_7
                    , grade_sheet_firstsems.subject_8, grade_sheet_firstsems.subject_9
                    ")
            ->distinct()
            ->orderBY('student_name','ASC')
            ->get();

            $quarter = 'First';
            $sem = 'First';

            $NumberOfSubject = \App\ClassSubjectDetail::join('subject_details', 'subject_details.id', '=' ,'class_subject_details.subject_id')
            ->join('class_details', 'class_details.id', '=' ,'class_subject_details.class_details_id')
            ->join('faculty_informations', 'faculty_informations.id', '=' ,'class_subject_details.faculty_id')           
            ->selectRaw("                
                            class_subject_details.class_subject_order
                        ")
            ->where('class_subject_details.class_details_id', $ClassSubjectDetail->id)
            ->where('class_subject_details.status', 1)
            // ->where('faculty_id', $FacultyInformation->id)
            ->where('class_details.school_year_id', $request->search_sy1)
            ->where('class_subject_details.sem', 1)
            //  ->where('class_details.school_year_id', $request->search_sy)
            // ->orderBy('class_subject_details.class_time_from', 'ASC');
            ->orderBY('class_subject_order','DESC')
            ->first();  
            
            // return json_encode($ClassSubjectDetail);
            // return view('control_panel_faculty.my_advisory_class.partials.data_list', compact( 'ClassSubjectDetail','AdvisorySubject','GradeSheetMale','GradeSheetFeMale','quarter','NumberOfSubject','sem'))->render();
            return view('control_panel_faculty.my_advisory_class.partials.print_senior_gradesheet', 
                compact( 'ClassSubjectDetail','AdvisorySubject','GradeSheetMale','GradeSheetFeMale','quarter','NumberOfSubject','sem'));
            $pdf = \PDF::loadView('control_panel_faculty.my_advisory_class.partials.print_senior_gradesheet',
                compact( 'ClassSubjectDetail','AdvisorySubject','GradeSheetMale','GradeSheetFeMale','quarter','NumberOfSubject','sem'));
            $pdf->setPaper('Legal', 'portrait');
            return $pdf->stream();
    }

    public function print_firstSem_2quarter (Request $request) 
    {
        $FacultyInformation = \App\FacultyInformation::where('user_id', \Auth::user()->id)->first();
     
        $ClassSubjectDetail = \App\ClassSubjectDetail::join('class_details', 'class_details.id', '=', 'class_subject_details.class_details_id')
            // ->join('subject_details', 'subject_details.id', '=', 'class_subject_details.subject_id')
            ->join('rooms','rooms.id', '=', 'class_details.room_id')
            ->join('section_details', 'section_details.id', '=', 'class_details.section_id')
            ->join('school_years', 'school_years.id' ,'=', 'class_details.school_year_id')
            ->join('faculty_informations', 'faculty_informations.id','=','class_details.adviser_id')
            // ->whereRaw('class_subject_details.id = '. $request->search_class_subject)
            // ->whereRaw('class_details.id = '. $search_class_subject[1])
            ->where('class_details.adviser_id', $FacultyInformation->id)
            ->where('class_details.school_year_id', $request->search_sy1)
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
                class_subject_details.sem,
                faculty_informations.first_name, faculty_informations.middle_name ,  faculty_informations.last_name,
                school_years.school_year
            '))
            ->first();

            
        
            $AdvisorySubject = \App\ClassSubjectDetail::join('subject_details', 'subject_details.id', '=' ,'class_subject_details.subject_id')
            ->join('class_details', 'class_details.id', '=' ,'class_subject_details.class_details_id')
            ->join('faculty_informations', 'faculty_informations.id', '=' ,'class_subject_details.faculty_id')           
            ->selectRaw("                
                subject_details.subject, subject_details.id, class_subject_details.class_subject_order, subject_details.subject_code
            ")
            ->where('class_subject_details.class_details_id', $ClassSubjectDetail->id)
            ->where('class_subject_details.status', 1)
            // ->where('faculty_id', $FacultyInformation->id)
            ->where('class_details.school_year_id', $request->search_sy1)
            ->where('class_subject_details.sem', 1)
            //  ->where('class_details.school_year_id', $request->search_sy)
            // ->orderBy('class_subject_details.class_time_from', 'ASC');
            ->orderBY('class_subject_order','ASC')
            ->get();            
            

            $GradeSheetMale = \App\Grade_sheet_firstsemsecond::join('class_details','class_details.section_id','=','grade_sheet_firstsemseconds.section_details_id')            
            ->join('enrollments','enrollments.id','=','grade_sheet_firstsemseconds.enrollment_id')
            ->join('student_informations','student_informations.id','=','enrollments.student_information_id')
            ->where('class_details.section_id', $ClassSubjectDetail->section_id)
            ->whereRaw('student_informations.gender = 1')
            ->selectRaw("                    
                    CONCAT(student_informations.last_name, ' ', student_informations.first_name, ' ', student_informations.middle_name) as student_name,
                    grade_sheet_firstsemseconds.subject_1, grade_sheet_firstsemseconds.subject_2, grade_sheet_firstsemseconds.subject_3, grade_sheet_firstsemseconds.subject_4
                    , grade_sheet_firstsemseconds.subject_5, grade_sheet_firstsemseconds.subject_6, grade_sheet_firstsemseconds.subject_7
                    , grade_sheet_firstsemseconds.subject_8, grade_sheet_firstsemseconds.subject_9,grade_sheet_firstsemseconds.enrollment_id
                    ")
            ->distinct()
            ->orderBY('student_name','ASC')
            ->get();

            $GradeSheetFeMale = \App\Grade_sheet_firstsemsecond::join('class_details','class_details.section_id','=','grade_sheet_firstsemseconds.section_details_id')            
            ->join('enrollments','enrollments.id','=','grade_sheet_firstsemseconds.enrollment_id')
            ->join('student_informations','student_informations.id','=','enrollments.student_information_id')
            ->where('class_details.section_id', $ClassSubjectDetail->section_id)
            ->whereRaw('student_informations.gender = 2')
            ->selectRaw("                    
                    CONCAT(student_informations.last_name, ' ', student_informations.first_name, ' ', student_informations.middle_name) as student_name,
                    grade_sheet_firstsemseconds.subject_1, grade_sheet_firstsemseconds.subject_2, grade_sheet_firstsemseconds.subject_3, grade_sheet_firstsemseconds.subject_4
                    , grade_sheet_firstsemseconds.subject_5, grade_sheet_firstsemseconds.subject_6, grade_sheet_firstsemseconds.subject_7
                    , grade_sheet_firstsemseconds.subject_8, grade_sheet_firstsemseconds.subject_9,grade_sheet_firstsemseconds.enrollment_id
                    ")
            ->distinct()
            ->orderBY('student_name','ASC')
            ->get();

            $quarter = 'Second';
            $sem = 'First';

            $NumberOfSubject = \App\ClassSubjectDetail::join('subject_details', 'subject_details.id', '=' ,'class_subject_details.subject_id')
            ->join('class_details', 'class_details.id', '=' ,'class_subject_details.class_details_id')
            ->join('faculty_informations', 'faculty_informations.id', '=' ,'class_subject_details.faculty_id')           
            ->selectRaw("                
                            class_subject_details.class_subject_order
                        ")
            ->where('class_subject_details.class_details_id', $ClassSubjectDetail->id)
            ->where('class_subject_details.status', 1)
            // ->where('faculty_id', $FacultyInformation->id)
            ->where('class_details.school_year_id', $request->search_sy1)
            ->where('class_subject_details.sem', 1)
            //  ->where('class_details.school_year_id', $request->search_sy)
            // ->orderBy('class_subject_details.class_time_from', 'ASC');
            ->orderBY('class_subject_order','DESC')
            ->first();       

           
        return view('control_panel_faculty.my_advisory_class.partials.print_senior_gradesheet', 
            compact( 'ClassSubjectDetail','AdvisorySubject','GradeSheetMale','GradeSheetFeMale','quarter','NumberOfSubject','sem'));
        $pdf = \PDF::loadView('control_panel_faculty.my_advisory_class.partials.print_senior_gradesheet', 
            compact( 'ClassSubjectDetail','AdvisorySubject','GradeSheetMale','GradeSheetFeMale','quarter','NumberOfSubject','sem'));
        $pdf->setPaper('Legal', 'portrait');
        return $pdf->stream();    
    }

    public function print_secondSem_1quarter (Request $request) 
    {
        $FacultyInformation = \App\FacultyInformation::where('user_id', \Auth::user()->id)->first();
     
        $ClassSubjectDetail = \App\ClassSubjectDetail::join('class_details', 'class_details.id', '=', 'class_subject_details.class_details_id')
            // ->join('subject_details', 'subject_details.id', '=', 'class_subject_details.subject_id')
            ->join('rooms','rooms.id', '=', 'class_details.room_id')
            ->join('section_details', 'section_details.id', '=', 'class_details.section_id')
            ->join('school_years', 'school_years.id' ,'=', 'class_details.school_year_id')
            ->join('faculty_informations', 'faculty_informations.id','=','class_details.adviser_id')
            // ->whereRaw('class_subject_details.id = '. $request->search_class_subject)
            // ->whereRaw('class_details.id = '. $search_class_subject[1])
            ->where('class_details.adviser_id', $FacultyInformation->id)
            ->where('class_details.school_year_id', $request->search_sy1)
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
                class_subject_details.sem,
                faculty_informations.first_name, faculty_informations.middle_name ,  faculty_informations.last_name,
                school_years.school_year
            '))
            ->first();

            
        
            $AdvisorySubject = \App\ClassSubjectDetail::join('subject_details', 'subject_details.id', '=' ,'class_subject_details.subject_id')
            ->join('class_details', 'class_details.id', '=' ,'class_subject_details.class_details_id')
            ->join('faculty_informations', 'faculty_informations.id', '=' ,'class_subject_details.faculty_id')           
            ->selectRaw("                
                subject_details.subject, subject_details.id, class_subject_details.class_subject_order, subject_details.subject_code
            ")
            ->where('class_subject_details.class_details_id', $ClassSubjectDetail->id)
            ->where('class_subject_details.status', 1)
            // ->where('faculty_id', $FacultyInformation->id)
            ->where('class_details.school_year_id', $request->search_sy1)
            ->where('class_subject_details.sem', 2)
            //  ->where('class_details.school_year_id', $request->search_sy)
            // ->orderBy('class_subject_details.class_time_from', 'ASC');
            ->orderBY('class_subject_order','ASC')
            ->get();        
            
            $NumberOfSubject = \App\ClassSubjectDetail::join('subject_details', 'subject_details.id', '=' ,'class_subject_details.subject_id')
            ->join('class_details', 'class_details.id', '=' ,'class_subject_details.class_details_id')
            ->join('faculty_informations', 'faculty_informations.id', '=' ,'class_subject_details.faculty_id')           
            ->selectRaw("                
                            class_subject_details.class_subject_order
                        ")
            ->where('class_subject_details.class_details_id', $ClassSubjectDetail->id)
            ->where('class_subject_details.status', 1)
            // ->where('faculty_id', $FacultyInformation->id)
            ->where('class_details.school_year_id', $request->search_sy1)
            ->where('class_subject_details.sem', 2)
            //  ->where('class_details.school_year_id', $request->search_sy)
            // ->orderBy('class_subject_details.class_time_from', 'ASC');
            ->orderBY('class_subject_order','DESC')
            ->first();           
            

            $GradeSheetMale = \App\Grade11_Second_Sem::join('class_details','class_details.section_id','=','grade11__second__sems.section_details_id')            
            ->join('enrollments','enrollments.id','=','grade11__second__sems.enrollment_id')
            ->join('student_informations','student_informations.id','=','enrollments.student_information_id')
            ->where('class_details.section_id', $ClassSubjectDetail->section_id)
            ->whereRaw('student_informations.gender = 1')
            ->selectRaw("                    
                    CONCAT(student_informations.last_name, ' ', student_informations.first_name, ' ', student_informations.middle_name) as student_name,
                    grade11__second__sems.subject_1, grade11__second__sems.subject_2, grade11__second__sems.subject_3, grade11__second__sems.subject_4
                    , grade11__second__sems.subject_5, grade11__second__sems.subject_6, grade11__second__sems.subject_7
                    , grade11__second__sems.subject_8, grade11__second__sems.subject_9
                    ")
            ->distinct()
            ->orderBY('student_name','ASC')
            ->get();

            $GradeSheetFeMale = \App\Grade11_Second_Sem::join('class_details','class_details.section_id','=','grade11__second__sems.section_details_id')            
            ->join('enrollments','enrollments.id','=','grade11__second__sems.enrollment_id')
            ->join('student_informations','student_informations.id','=','enrollments.student_information_id')
            ->where('class_details.section_id', $ClassSubjectDetail->section_id)
            ->whereRaw('student_informations.gender = 2')
            ->selectRaw("                    
                    CONCAT(student_informations.last_name, ' ', student_informations.first_name, ' ', student_informations.middle_name) as student_name,
                    grade11__second__sems.subject_1, grade11__second__sems.subject_2, grade11__second__sems.subject_3, grade11__second__sems.subject_4
                    , grade11__second__sems.subject_5, grade11__second__sems.subject_6, grade11__second__sems.subject_7
                    , grade11__second__sems.subject_8, grade11__second__sems.subject_9
                    ")
            ->distinct()
            ->orderBY('student_name','ASC')
            ->get();

            $quarter = 'First';
            $sem = 'Second';

           
        return view('control_panel_faculty.my_advisory_class.partials.print_senior_gradesheet', 
            compact( 'ClassSubjectDetail','AdvisorySubject','GradeSheetMale','GradeSheetFeMale','quarter','NumberOfSubject','sem'));
        $pdf = \PDF::loadView('control_panel_faculty.my_advisory_class.partials.print_senior_gradesheet', 
            compact( 'ClassSubjectDetail','AdvisorySubject','GradeSheetMale','GradeSheetFeMale','quarter','NumberOfSubject','sem'));
        $pdf->setPaper('Legal', 'portrait');
        return $pdf->stream();    
    }

    public function print_secondSem_2quarter (Request $request) 
    {
        $FacultyInformation = \App\FacultyInformation::where('user_id', \Auth::user()->id)->first();
     
        $ClassSubjectDetail = \App\ClassSubjectDetail::join('class_details', 'class_details.id', '=', 'class_subject_details.class_details_id')
            // ->join('subject_details', 'subject_details.id', '=', 'class_subject_details.subject_id')
            ->join('rooms','rooms.id', '=', 'class_details.room_id')
            ->join('section_details', 'section_details.id', '=', 'class_details.section_id')
            ->join('school_years', 'school_years.id' ,'=', 'class_details.school_year_id')
            ->join('faculty_informations', 'faculty_informations.id','=','class_details.adviser_id')
            // ->whereRaw('class_subject_details.id = '. $request->search_class_subject)
            // ->whereRaw('class_details.id = '. $search_class_subject[1])
            ->where('class_details.adviser_id', $FacultyInformation->id)
            ->where('class_details.school_year_id', $request->search_sy1)
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
                class_subject_details.sem,
                faculty_informations.first_name, faculty_informations.middle_name ,  faculty_informations.last_name,
                school_years.school_year
            '))
            ->first();

           
            
        
            $AdvisorySubject = \App\ClassSubjectDetail::join('subject_details', 'subject_details.id', '=' ,'class_subject_details.subject_id')
            ->join('class_details', 'class_details.id', '=' ,'class_subject_details.class_details_id')
            ->join('faculty_informations', 'faculty_informations.id', '=' ,'class_subject_details.faculty_id')           
            ->selectRaw("                
                subject_details.subject, subject_details.id, class_subject_details.class_subject_order, subject_details.subject_code
            ")
            ->where('class_subject_details.class_details_id', $ClassSubjectDetail->id)
            ->where('class_subject_details.status', 1)
            // ->where('faculty_id', $FacultyInformation->id)
            ->where('class_details.school_year_id', $request->search_sy1)
            ->where('class_subject_details.sem', 2)
            //  ->where('class_details.school_year_id', $request->search_sy)
            // ->orderBy('class_subject_details.class_time_from', 'ASC');
            ->orderBY('class_subject_order','ASC')
            ->get();            
            

            $GradeSheetMale = \App\Grade_sheet_secondsemsecond::join('class_details','class_details.section_id','=','grade_sheet_secondsemseconds.section_details_id')            
            ->join('enrollments','enrollments.id','=','grade_sheet_secondsemseconds.enrollment_id')
            ->join('student_informations','student_informations.id','=','enrollments.student_information_id')
            ->where('class_details.section_id', $ClassSubjectDetail->section_id)
            ->whereRaw('student_informations.gender = 1')
            ->selectRaw("                    
                    CONCAT(student_informations.last_name, ' ', student_informations.first_name, ' ', student_informations.middle_name) as student_name,
                    grade_sheet_secondsemseconds.subject_1, grade_sheet_secondsemseconds.subject_2, grade_sheet_secondsemseconds.subject_3, grade_sheet_secondsemseconds.subject_4
                    , grade_sheet_secondsemseconds.subject_5, grade_sheet_secondsemseconds.subject_6, grade_sheet_secondsemseconds.subject_7
                    , grade_sheet_secondsemseconds.subject_8, grade_sheet_secondsemseconds.subject_9,grade_sheet_secondsemseconds.enrollment_id
                    ")
            ->distinct()
            ->orderBY('student_name','ASC')
            ->get();

            $GradeSheetFeMale = \App\Grade_sheet_secondsemsecond::join('class_details','class_details.section_id','=','grade_sheet_secondsemseconds.section_details_id')            
            ->join('enrollments','enrollments.id','=','grade_sheet_secondsemseconds.enrollment_id')
            ->join('student_informations','student_informations.id','=','enrollments.student_information_id')
            ->where('class_details.section_id', $ClassSubjectDetail->section_id)
            ->whereRaw('student_informations.gender = 2')
            ->selectRaw("                    
                    CONCAT(student_informations.last_name, ' ', student_informations.first_name, ' ', student_informations.middle_name) as student_name,
                    grade_sheet_secondsemseconds.subject_1, grade_sheet_secondsemseconds.subject_2, grade_sheet_secondsemseconds.subject_3, grade_sheet_secondsemseconds.subject_4
                    , grade_sheet_secondsemseconds.subject_5, grade_sheet_secondsemseconds.subject_6, grade_sheet_secondsemseconds.subject_7
                    , grade_sheet_secondsemseconds.subject_8, grade_sheet_secondsemseconds.subject_9,grade_sheet_secondsemseconds.enrollment_id
                    ")
            ->distinct()
            ->orderBY('student_name','ASC')
            ->get();

            $quarter = 'Second';
            $sem = 'Second';

            $NumberOfSubject = \App\ClassSubjectDetail::join('subject_details', 'subject_details.id', '=' ,'class_subject_details.subject_id')
            ->join('class_details', 'class_details.id', '=' ,'class_subject_details.class_details_id')
            ->join('faculty_informations', 'faculty_informations.id', '=' ,'class_subject_details.faculty_id')           
            ->selectRaw("                
                            class_subject_details.class_subject_order
                        ")
            ->where('class_subject_details.class_details_id', $ClassSubjectDetail->id)
            ->where('class_subject_details.status', 1)
            // ->where('faculty_id', $FacultyInformation->id)
            ->where('class_details.school_year_id', $request->search_sy1)
            ->where('class_subject_details.sem', 2)
            //  ->where('class_details.school_year_id', $request->search_sy)
            // ->orderBy('class_subject_details.class_time_from', 'ASC');
            ->orderBY('class_subject_order','DESC')
            ->first();      

           
        // return json_encode($ClassSubjectDetail);
        return view('control_panel_faculty.my_advisory_class.partials.print_senior_gradesheet', 
            compact( 'ClassSubjectDetail','AdvisorySubject','GradeSheetMale','GradeSheetFeMale','quarter','NumberOfSubject','sem'));
        $pdf = \PDF::loadView('control_panel_faculty.my_advisory_class.partials.print_senior_gradesheet', 
            compact( 'ClassSubjectDetail','AdvisorySubject','GradeSheetMale','GradeSheetFeMale','quarter','NumberOfSubject','sem'));
            $pdf->setPaper('Legal', 'portrait');
            return $pdf->stream();
        }


        // average
        public function gradeSheetAverage(Request $request)
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
    
            $AdvisorySubject = \App\ClassSubjectDetail::join('subject_details', 'subject_details.id', '=' ,'class_subject_details.subject_id')
            ->join('class_details', 'class_details.id', '=' ,'class_subject_details.class_details_id')
            ->join('faculty_informations', 'faculty_informations.id', '=' ,'class_subject_details.faculty_id')           
            ->selectRaw("                
                subject_details.subject, subject_details.id
            ")
            ->where('class_subject_details.class_details_id', $ClassSubjectDetail->id)
            ->where('class_subject_details.status', 1)
            // ->where('faculty_id', $FacultyInformation->id)
            ->where('class_details.school_year_id', $request->search_sy)
            //  ->where('class_details.school_year_id', $request->search_sy)
            // ->orderBy('class_subject_details.class_time_from', 'ASC');
            ->get();

            // $class_details_elements .= '<option value="1st-2nd">First - Second Quarter Average</option>';
            // $class_details_elements .= '<option value="1st-3rd">First - Second - Third Quarter Average</option>';
            // $class_details_elements .= '<option value="1st-4th">First - Second - Third - Fourth Quarter Average</option>';
            $quarter;

            if($request->quarter_grades == "1st-2nd")
            {
                $quarter = 'First - Second';
            }
            else if($request->quarter_grades == "1st-3rd")
            {
                $quarter = 'First - Third';
            }
            else if($request->quarter_grades == "1st-4th")
            {
                $quarter = 'First - Fourth';
            }
            

            $GradeSheetMale = \App\Grade_sheet_first::join('class_details','class_details.section_id','=','grade_sheet_firsts.section_details_id')            
            ->join('enrollments','enrollments.id','=','grade_sheet_firsts.enrollment_id')
            ->join('student_informations','student_informations.id','=','enrollments.student_information_id')
            ->where('class_details.section_id', $ClassSubjectDetail->section_id)
            ->whereRaw('student_informations.gender = 1')
            ->selectRaw("grade_sheet_firsts.enrollment_id,                 
                    CONCAT( student_informations.last_name, ' ', student_informations.first_name, ' ', student_informations.middle_name) as student_name,
                    grade_sheet_firsts.filipino, grade_sheet_firsts.english, grade_sheet_firsts.math, grade_sheet_firsts.science, grade_sheet_firsts.ap, grade_sheet_firsts.ict, grade_sheet_firsts.mapeh
                    , grade_sheet_firsts.esp, grade_sheet_firsts.religion
                    ")
            ->distinct()
            ->orderBY('student_name','ASC')
            ->get();

            $GradeSheetFeMale = \App\Grade_sheet_first::join('class_details','class_details.section_id','=','grade_sheet_firsts.section_details_id')            
            ->join('enrollments','enrollments.id','=','grade_sheet_firsts.enrollment_id')
            ->join('student_informations','student_informations.id','=','enrollments.student_information_id')
            ->where('class_details.section_id', $ClassSubjectDetail->section_id)
            ->whereRaw('student_informations.gender = 2')
            ->selectRaw("grade_sheet_firsts.enrollment_id,                   
                    CONCAT( student_informations.last_name, ' ', student_informations.first_name, ' ', student_informations.middle_name) as student_name,
                    grade_sheet_firsts.filipino, grade_sheet_firsts.english, grade_sheet_firsts.math, grade_sheet_firsts.science, grade_sheet_firsts.ap, grade_sheet_firsts.ict, grade_sheet_firsts.mapeh
                    , grade_sheet_firsts.esp, grade_sheet_firsts.religion
                    ")
            ->distinct()
            ->orderBY('student_name','ASC')
            ->get();

            // return view('control_panel_faculty.my_advisory_class.partials.data_list', 
            //     compact('type','ClassSubjectDetail','AdvisorySubject','quarter'))->render();
            
            return view('control_panel_faculty.my_advisory_class.partials.data_list', 
                compact( 'type','ClassSubjectDetail','AdvisorySubject','GradeSheetMale','GradeSheetFeMale','quarter'))->render();
    
        }

        public function firstSecondGradeSheetAverage_print(Request $request)
        {
            $type = "average";

            $FacultyInformation = \App\FacultyInformation::where('user_id', \Auth::user()->id)->first();         
            $ClassSubjectDetail = \App\ClassSubjectDetail::join('class_details', 'class_details.id', '=', 'class_subject_details.class_details_id')
            // ->join('subject_details', 'subject_details.id', '=', 'class_subject_details.subject_id')
            ->join('rooms','rooms.id', '=', 'class_details.room_id')
            ->join('section_details', 'section_details.id', '=', 'class_details.section_id')
            ->join('school_years', 'school_years.id' ,'=', 'class_details.school_year_id')
            ->join('faculty_informations', 'faculty_informations.id','=','class_details.adviser_id')
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
                faculty_informations.first_name, faculty_informations.middle_name ,  faculty_informations.last_name,
                school_years.school_year                
            '))
            ->first();
    
            $AdvisorySubject = \App\ClassSubjectDetail::join('subject_details', 'subject_details.id', '=' ,'class_subject_details.subject_id')
            ->join('class_details', 'class_details.id', '=' ,'class_subject_details.class_details_id')
            ->join('faculty_informations', 'faculty_informations.id', '=' ,'class_subject_details.faculty_id')           
            ->selectRaw("                
                subject_details.subject, subject_details.id
            ")
            ->where('class_subject_details.class_details_id', $ClassSubjectDetail->id)
            ->where('class_subject_details.status', 1)
            // ->where('faculty_id', $FacultyInformation->id)
            ->where('class_details.school_year_id', $request->search_sy)
            //  ->where('class_details.school_year_id', $request->search_sy)
            // ->orderBy('class_subject_details.class_time_from', 'ASC');
            ->get();

            // $class_details_elements .= '<option value="1st-2nd">First - Second Quarter Average</option>';
            // $class_details_elements .= '<option value="1st-3rd">First - Second - Third Quarter Average</option>';
            // $class_details_elements .= '<option value="1st-4th">First - Second - Third - Fourth Quarter Average</option>';
            $quarter = 'First - Second';
            
            

            $GradeSheetMale = \App\Grade_sheet_first::join('class_details','class_details.section_id','=','grade_sheet_firsts.section_details_id')            
            ->join('enrollments','enrollments.id','=','grade_sheet_firsts.enrollment_id')
            ->join('student_informations','student_informations.id','=','enrollments.student_information_id')
            ->where('class_details.section_id', $ClassSubjectDetail->section_id)
            ->whereRaw('student_informations.gender = 1')
            ->selectRaw("grade_sheet_firsts.enrollment_id,                 
                    CONCAT( student_informations.last_name, ' ', student_informations.first_name, ' ', student_informations.middle_name) as student_name,
                    grade_sheet_firsts.filipino, grade_sheet_firsts.english, grade_sheet_firsts.math, grade_sheet_firsts.science, grade_sheet_firsts.ap, grade_sheet_firsts.ict, grade_sheet_firsts.mapeh
                    , grade_sheet_firsts.esp, grade_sheet_firsts.religion
                    ")
            ->distinct()
            ->orderBY('student_name','ASC')
            ->get();

            $GradeSheetFeMale = \App\Grade_sheet_first::join('class_details','class_details.section_id','=','grade_sheet_firsts.section_details_id')            
            ->join('enrollments','enrollments.id','=','grade_sheet_firsts.enrollment_id')
            ->join('student_informations','student_informations.id','=','enrollments.student_information_id')
            ->where('class_details.section_id', $ClassSubjectDetail->section_id)
            ->whereRaw('student_informations.gender = 2')
            ->selectRaw("grade_sheet_firsts.enrollment_id,                   
                    CONCAT( student_informations.last_name, ' ', student_informations.first_name, ' ', student_informations.middle_name) as student_name,
                    grade_sheet_firsts.filipino, grade_sheet_firsts.english, grade_sheet_firsts.math, grade_sheet_firsts.science, grade_sheet_firsts.ap, grade_sheet_firsts.ict, grade_sheet_firsts.mapeh
                    , grade_sheet_firsts.esp, grade_sheet_firsts.religion
                    ")
            ->distinct()
            ->orderBY('student_name','ASC')
            ->get();

            // return view('control_panel_faculty.my_advisory_class.partials.data_list', 
            //     compact('type','ClassSubjectDetail','AdvisorySubject','quarter'))->render();
            
            // return view('control_panel_faculty.my_advisory_class.partials.data_list', 
            //     compact( 'type','ClassSubjectDetail','AdvisorySubject','GradeSheetMale','GradeSheetFeMale','quarter'))->render();
            return view('control_panel_faculty.my_advisory_class.partials.print_average', 
            compact( 'type','ClassSubjectDetail','AdvisorySubject','GradeSheetMale','GradeSheetFeMale','quarter'));
            $pdf = \PDF::loadView('control_panel_faculty.my_advisory_class.partials.print_average', 
            compact( 'type','ClassSubjectDetail','AdvisorySubject','GradeSheetMale','GradeSheetFeMale','quarter'));
            $pdf->setPaper('Legal', 'portrait');
            return $pdf->stream();
        }

        public function firstThirdGradeSheetAverage_print(Request $request)
        {
            $type = "average";

            $FacultyInformation = \App\FacultyInformation::where('user_id', \Auth::user()->id)->first();         
            $ClassSubjectDetail = \App\ClassSubjectDetail::join('class_details', 'class_details.id', '=', 'class_subject_details.class_details_id')
            // ->join('subject_details', 'subject_details.id', '=', 'class_subject_details.subject_id')
            ->join('rooms','rooms.id', '=', 'class_details.room_id')
            ->join('section_details', 'section_details.id', '=', 'class_details.section_id')
            ->join('school_years', 'school_years.id' ,'=', 'class_details.school_year_id')
            ->join('faculty_informations', 'faculty_informations.id','=','class_details.adviser_id')
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
                faculty_informations.first_name, faculty_informations.middle_name ,  faculty_informations.last_name,
                school_years.school_year                
            '))
            ->first();
    
            $AdvisorySubject = \App\ClassSubjectDetail::join('subject_details', 'subject_details.id', '=' ,'class_subject_details.subject_id')
            ->join('class_details', 'class_details.id', '=' ,'class_subject_details.class_details_id')
            ->join('faculty_informations', 'faculty_informations.id', '=' ,'class_subject_details.faculty_id')           
            ->selectRaw("                
                subject_details.subject, subject_details.id
            ")
            ->where('class_subject_details.class_details_id', $ClassSubjectDetail->id)
            ->where('class_subject_details.status', 1)
            // ->where('faculty_id', $FacultyInformation->id)
            ->where('class_details.school_year_id', $request->search_sy)
            //  ->where('class_details.school_year_id', $request->search_sy)
            // ->orderBy('class_subject_details.class_time_from', 'ASC');
            ->get();

            // $class_details_elements .= '<option value="1st-2nd">First - Second Quarter Average</option>';
            // $class_details_elements .= '<option value="1st-3rd">First - Second - Third Quarter Average</option>';
            // $class_details_elements .= '<option value="1st-4th">First - Second - Third - Fourth Quarter Average</option>';
            
                $quarter = 'First - Third';
            
            

            $GradeSheetMale = \App\Grade_sheet_first::join('class_details','class_details.section_id','=','grade_sheet_firsts.section_details_id')            
            ->join('enrollments','enrollments.id','=','grade_sheet_firsts.enrollment_id')
            ->join('student_informations','student_informations.id','=','enrollments.student_information_id')
            ->where('class_details.section_id', $ClassSubjectDetail->section_id)
            ->whereRaw('student_informations.gender = 1')
            ->selectRaw("grade_sheet_firsts.enrollment_id,                 
                    CONCAT( student_informations.last_name, ' ', student_informations.first_name, ' ', student_informations.middle_name) as student_name,
                    grade_sheet_firsts.filipino, grade_sheet_firsts.english, grade_sheet_firsts.math, grade_sheet_firsts.science, grade_sheet_firsts.ap, grade_sheet_firsts.ict, grade_sheet_firsts.mapeh
                    , grade_sheet_firsts.esp, grade_sheet_firsts.religion
                    ")
            ->distinct()
            ->orderBY('student_name','ASC')
            ->get();

            $GradeSheetFeMale = \App\Grade_sheet_first::join('class_details','class_details.section_id','=','grade_sheet_firsts.section_details_id')            
            ->join('enrollments','enrollments.id','=','grade_sheet_firsts.enrollment_id')
            ->join('student_informations','student_informations.id','=','enrollments.student_information_id')
            ->where('class_details.section_id', $ClassSubjectDetail->section_id)
            ->whereRaw('student_informations.gender = 2')
            ->selectRaw("grade_sheet_firsts.enrollment_id,                   
                    CONCAT( student_informations.last_name, ' ', student_informations.first_name, ' ', student_informations.middle_name) as student_name,
                    grade_sheet_firsts.filipino, grade_sheet_firsts.english, grade_sheet_firsts.math, grade_sheet_firsts.science, grade_sheet_firsts.ap, grade_sheet_firsts.ict, grade_sheet_firsts.mapeh
                    , grade_sheet_firsts.esp, grade_sheet_firsts.religion
                    ")
            ->distinct()
            ->orderBY('student_name','ASC')
            ->get();

            // return view('control_panel_faculty.my_advisory_class.partials.data_list', 
            //     compact('type','ClassSubjectDetail','AdvisorySubject','quarter'))->render();
            
            // return view('control_panel_faculty.my_advisory_class.partials.data_list', 
            //     compact( 'type','ClassSubjectDetail','AdvisorySubject','GradeSheetMale','GradeSheetFeMale','quarter'))->render();
            return view('control_panel_faculty.my_advisory_class.partials.print_average', 
            compact( 'type','ClassSubjectDetail','AdvisorySubject','GradeSheetMale','GradeSheetFeMale','quarter'));
            $pdf = \PDF::loadView('control_panel_faculty.my_advisory_class.partials.print_average', 
            compact( 'type','ClassSubjectDetail','AdvisorySubject','GradeSheetMale','GradeSheetFeMale','quarter'));
            $pdf->setPaper('Legal', 'portrait');
            return $pdf->stream();
        }

        public function firstFourthGradeSheetAverage_print(Request $request)
        {
            $type = "average";

            $FacultyInformation = \App\FacultyInformation::where('user_id', \Auth::user()->id)->first();         
            $ClassSubjectDetail = \App\ClassSubjectDetail::join('class_details', 'class_details.id', '=', 'class_subject_details.class_details_id')
            // ->join('subject_details', 'subject_details.id', '=', 'class_subject_details.subject_id')
            ->join('rooms','rooms.id', '=', 'class_details.room_id')
            ->join('section_details', 'section_details.id', '=', 'class_details.section_id')
            ->join('school_years', 'school_years.id' ,'=', 'class_details.school_year_id')
            ->join('faculty_informations', 'faculty_informations.id','=','class_details.adviser_id')
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
                faculty_informations.first_name, faculty_informations.middle_name ,  faculty_informations.last_name,
                school_years.school_year                
            '))
            ->first();
    
            $AdvisorySubject = \App\ClassSubjectDetail::join('subject_details', 'subject_details.id', '=' ,'class_subject_details.subject_id')
            ->join('class_details', 'class_details.id', '=' ,'class_subject_details.class_details_id')
            ->join('faculty_informations', 'faculty_informations.id', '=' ,'class_subject_details.faculty_id')           
            ->selectRaw("                
                subject_details.subject, subject_details.id
            ")
            ->where('class_subject_details.class_details_id', $ClassSubjectDetail->id)
            ->where('class_subject_details.status', 1)
            // ->where('faculty_id', $FacultyInformation->id)
            ->where('class_details.school_year_id', $request->search_sy)
            //  ->where('class_details.school_year_id', $request->search_sy)
            // ->orderBy('class_subject_details.class_time_from', 'ASC');
            ->get();

            // $class_details_elements .= '<option value="1st-2nd">First - Second Quarter Average</option>';
            // $class_details_elements .= '<option value="1st-3rd">First - Second - Third Quarter Average</option>';
            // $class_details_elements .= '<option value="1st-4th">First - Second - Third - Fourth Quarter Average</option>';
            
           
            $quarter = 'First - Fourth';
            
            

            $GradeSheetMale = \App\Grade_sheet_first::join('class_details','class_details.section_id','=','grade_sheet_firsts.section_details_id')            
            ->join('enrollments','enrollments.id','=','grade_sheet_firsts.enrollment_id')
            ->join('student_informations','student_informations.id','=','enrollments.student_information_id')
            ->where('class_details.section_id', $ClassSubjectDetail->section_id)
            ->whereRaw('student_informations.gender = 1')
            ->selectRaw("grade_sheet_firsts.enrollment_id,                 
                    CONCAT( student_informations.last_name, ' ', student_informations.first_name, ' ', student_informations.middle_name) as student_name,
                    grade_sheet_firsts.filipino, grade_sheet_firsts.english, grade_sheet_firsts.math, grade_sheet_firsts.science, grade_sheet_firsts.ap, grade_sheet_firsts.ict, grade_sheet_firsts.mapeh
                    , grade_sheet_firsts.esp, grade_sheet_firsts.religion
                    ")
            ->distinct()
            ->orderBY('student_name','ASC')
            ->get();

            $GradeSheetFeMale = \App\Grade_sheet_first::join('class_details','class_details.section_id','=','grade_sheet_firsts.section_details_id')            
            ->join('enrollments','enrollments.id','=','grade_sheet_firsts.enrollment_id')
            ->join('student_informations','student_informations.id','=','enrollments.student_information_id')
            ->where('class_details.section_id', $ClassSubjectDetail->section_id)
            ->whereRaw('student_informations.gender = 2')
            ->selectRaw("grade_sheet_firsts.enrollment_id,                   
                    CONCAT( student_informations.last_name, ' ', student_informations.first_name, ' ', student_informations.middle_name) as student_name,
                    grade_sheet_firsts.filipino, grade_sheet_firsts.english, grade_sheet_firsts.math, grade_sheet_firsts.science, grade_sheet_firsts.ap, grade_sheet_firsts.ict, grade_sheet_firsts.mapeh
                    , grade_sheet_firsts.esp, grade_sheet_firsts.religion
                    ")
            ->distinct()
            ->orderBY('student_name','ASC')
            ->get();


            
            // return view('control_panel_faculty.my_advisory_class.partials.data_list', 
            //     compact('type','ClassSubjectDetail','AdvisorySubject','quarter'))->render();
            
            // return view('control_panel_faculty.my_advisory_class.partials.data_list', 
            //     compact( 'type','ClassSubjectDetail','AdvisorySubject','GradeSheetMale','GradeSheetFeMale','quarter'))->render();
            return view('control_panel_faculty.my_advisory_class.partials.print_average', 
            compact( 'type','ClassSubjectDetail','AdvisorySubject','GradeSheetMale','GradeSheetFeMale','quarter'));
            $pdf = \PDF::loadView('control_panel_faculty.my_advisory_class.partials.print_average', 
            compact( 'type','ClassSubjectDetail','AdvisorySubject','GradeSheetMale','GradeSheetFeMale','quarter'));
            $pdf->setPaper('Legal', 'portrait');
            return $pdf->stream();
        }

        public function seniorFirstSemGradeSheetAverage(Request $request)
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
            ->where('class_details.school_year_id', $request->search_sy1)
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

            // $class_details_elements .= '<option value="1st-2nd">Sem-1 First - Second Quarter Average</option>';
            // $class_details_elements .= '<option value="3rd-4th">Sem-2 First - Second Quarter Average</option>'; 
            // $class_details_elements .= '<option value="1-2">Sem-1 and 2 Average</option>'; 
            
            $quarter;
            $sem;

            if($request->quarter_ == "1st-2nd")
            {
                $sem = 'First';
                $quarter = 'First - Second';
            }
            else if($request->quarter_ == "3rd-4th")
            {
                $sem = 'Second';
                $quarter = 'First - Second';
            }
            else if($request->quarter_ == "1-2")
            {
                $quarter = 'First - Fourth';
                $sem = 'First and Second';
            }
            

            $FacultyInformation = \App\FacultyInformation::where('user_id', \Auth::user()->id)->first();       
            
        
            $AdvisorySubject = \App\ClassSubjectDetail::join('subject_details', 'subject_details.id', '=' ,'class_subject_details.subject_id')
            ->join('class_details', 'class_details.id', '=' ,'class_subject_details.class_details_id')
            ->join('faculty_informations', 'faculty_informations.id', '=' ,'class_subject_details.faculty_id')           
            ->selectRaw("                
                subject_details.subject, subject_details.id, class_subject_details.class_subject_order, subject_details.subject_code
            ")
            ->where('class_subject_details.class_details_id', $ClassSubjectDetail->id)
            ->where('class_subject_details.status', 1)
            // ->where('faculty_id', $FacultyInformation->id)
            ->where('class_details.school_year_id', $request->search_sy1)
            ->where('class_subject_details.sem', 1)
            //  ->where('class_details.school_year_id', $request->search_sy)
            // ->orderBy('class_subject_details.class_time_from', 'ASC');
            ->orderBY('class_subject_details.class_subject_order','ASC')
            ->get();            
            

            $GradeSheetMale = \App\Grade_sheet_firstsem::join('class_details','class_details.section_id','=','grade_sheet_firstsems.section_details_id')            
            ->join('enrollments','enrollments.id','=','grade_sheet_firstsems.enrollment_id')
            ->join('student_informations','student_informations.id','=','enrollments.student_information_id')
            ->where('class_details.section_id', $ClassSubjectDetail->section_id)
            ->whereRaw('student_informations.gender = 1')
            ->selectRaw("  grade_sheet_firstsems.enrollment_id,                
                    CONCAT(student_informations.last_name, ' ', student_informations.first_name, ' ', student_informations.middle_name) as student_name,
                    grade_sheet_firstsems.subject_1, grade_sheet_firstsems.subject_2, grade_sheet_firstsems.subject_3, grade_sheet_firstsems.subject_4
                    , grade_sheet_firstsems.subject_5, grade_sheet_firstsems.subject_6, grade_sheet_firstsems.subject_7
                    , grade_sheet_firstsems.subject_8, grade_sheet_firstsems.subject_9
                    ")
            ->distinct()
            ->orderBY('student_name','ASC')
            ->get();

            $GradeSheetFeMale = \App\Grade_sheet_firstsem::join('class_details','class_details.section_id','=','grade_sheet_firstsems.section_details_id')            
            ->join('enrollments','enrollments.id','=','grade_sheet_firstsems.enrollment_id')
            ->join('student_informations','student_informations.id','=','enrollments.student_information_id')
            ->where('class_details.section_id', $ClassSubjectDetail->section_id)
            ->whereRaw('student_informations.gender = 2')
            ->selectRaw("   grade_sheet_firstsems.enrollment_id,                    
                    CONCAT(student_informations.last_name, ' ', student_informations.first_name, ' ', student_informations.middle_name) as student_name,
                    grade_sheet_firstsems.subject_1, grade_sheet_firstsems.subject_2, grade_sheet_firstsems.subject_3, grade_sheet_firstsems.subject_4
                    , grade_sheet_firstsems.subject_5, grade_sheet_firstsems.subject_6, grade_sheet_firstsems.subject_7
                    , grade_sheet_firstsems.subject_8, grade_sheet_firstsems.subject_9
                    ")
            ->distinct()
            ->orderBY('student_name','ASC')
            ->get();

            

            $NumberOfSubject = \App\ClassSubjectDetail::join('subject_details', 'subject_details.id', '=' ,'class_subject_details.subject_id')
            ->join('class_details', 'class_details.id', '=' ,'class_subject_details.class_details_id')
            ->join('faculty_informations', 'faculty_informations.id', '=' ,'class_subject_details.faculty_id')           
            ->selectRaw("                
                            class_subject_details.class_subject_order
                        ")
            ->where('class_subject_details.class_details_id', $ClassSubjectDetail->id)
            ->where('class_subject_details.status', 1)
            // ->where('faculty_id', $FacultyInformation->id)
            ->where('class_details.school_year_id', $request->search_sy1)
            ->where('class_subject_details.sem', 2)
            //  ->where('class_details.school_year_id', $request->search_sy)
            // ->orderBy('class_subject_details.class_time_from', 'ASC');
            ->orderBY('class_subject_details.class_subject_order','DESC')
            ->first();       

            $type = "average";
           
        // return json_encode($ClassSubjectDetail);
        // return view('control_panel_faculty.my_advisory_class.partials.data_list', 
        // compact('type','NumberOfSubject', 'ClassSubjectDetail','AdvisorySubject','GradeSheetMale','GradeSheetFeMale','quarter','AdvisorySubject','sem'))->render();
    
            
            return view('control_panel_faculty.my_advisory_class.partials.data_list', 
            compact('type','NumberOfSubject', 'ClassSubjectDetail','AdvisorySubject','GradeSheetMale','GradeSheetFeMale','quarter','AdvisorySubject','sem'))->render();
    
        }

        public function seniorSecondSemGradeSheetAverage(Request $request)
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
            ->where('class_details.school_year_id', $request->search_sy1)
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

            // $class_details_elements .= '<option value="1st-2nd">Sem-1 First - Second Quarter Average</option>';
            // $class_details_elements .= '<option value="3rd-4th">Sem-2 First - Second Quarter Average</option>'; 
            // $class_details_elements .= '<option value="1-2">Sem-1 and 2 Average</option>'; 
            
            $quarter;
            $sem;

            if($request->quarter_ == "1st-2nd")
            {
                $sem = 'First';
                $quarter = 'First - Second';
            }
            else if($request->quarter_ == "3rd-4th")
            {
                $sem = 'Second';
                $quarter = 'First - Second';
            }
            else if($request->quarter_ == "1-2")
            {
                $quarter = 'First - Fourth';
                $sem = 'First and Second';
            }
            

            $FacultyInformation = \App\FacultyInformation::where('user_id', \Auth::user()->id)->first();       
                    
            $AdvisorySubject = \App\ClassSubjectDetail::join('subject_details', 'subject_details.id', '=' ,'class_subject_details.subject_id')
            ->join('class_details', 'class_details.id', '=' ,'class_subject_details.class_details_id')
            ->join('faculty_informations', 'faculty_informations.id', '=' ,'class_subject_details.faculty_id')           
            ->selectRaw("                
                subject_details.subject, subject_details.id, class_subject_details.class_subject_order, subject_details.subject_code
            ")
            ->where('class_subject_details.class_details_id', $ClassSubjectDetail->id)
            ->where('class_subject_details.status', 1)
            // ->where('faculty_id', $FacultyInformation->id)
            ->where('class_details.school_year_id', $request->search_sy1)
            ->where('class_subject_details.sem', 2)
            //  ->where('class_details.school_year_id', $request->search_sy)
            // ->orderBy('class_subject_details.class_time_from', 'ASC');
            ->orderBY('class_subject_details.class_subject_order','ASC')
            ->get();        
            
            $NumberOfSubject = \App\ClassSubjectDetail::join('subject_details', 'subject_details.id', '=' ,'class_subject_details.subject_id')
            ->join('class_details', 'class_details.id', '=' ,'class_subject_details.class_details_id')
            ->join('faculty_informations', 'faculty_informations.id', '=' ,'class_subject_details.faculty_id')           
            ->selectRaw("                
                            class_subject_details.class_subject_order
                        ")
            ->where('class_subject_details.class_details_id', $ClassSubjectDetail->id)
            ->where('class_subject_details.status', 1)
            // ->where('faculty_id', $FacultyInformation->id)
            ->where('class_details.school_year_id', $request->search_sy1)
            ->where('class_subject_details.sem', 2)
            //  ->where('class_details.school_year_id', $request->search_sy)
            // ->orderBy('class_subject_details.class_time_from', 'ASC');
            ->orderBY('class_subject_order','DESC')
            ->first();           
            

            $GradeSheetMale = \App\Grade11_Second_Sem::join('class_details','class_details.section_id','=','grade11__second__sems.section_details_id')            
            ->join('enrollments','enrollments.id','=','grade11__second__sems.enrollment_id')
            ->join('student_informations','student_informations.id','=','enrollments.student_information_id')
            ->where('class_details.section_id', $ClassSubjectDetail->section_id)
            ->whereRaw('student_informations.gender = 1')
            ->selectRaw("  grade11__second__sems.enrollment_id,                
                    CONCAT(student_informations.last_name, ' ', student_informations.first_name, ' ', student_informations.middle_name) as student_name,
                    grade11__second__sems.subject_1, grade11__second__sems.subject_2, grade11__second__sems.subject_3, grade11__second__sems.subject_4
                    , grade11__second__sems.subject_5, grade11__second__sems.subject_6, grade11__second__sems.subject_7
                    , grade11__second__sems.subject_8, grade11__second__sems.subject_9
                    ")
            ->distinct()
            ->orderBY('student_name','ASC')
            ->get();

            $GradeSheetFeMale = \App\Grade11_Second_Sem::join('class_details','class_details.section_id','=','grade11__second__sems.section_details_id')            
            ->join('enrollments','enrollments.id','=','grade11__second__sems.enrollment_id')
            ->join('student_informations','student_informations.id','=','enrollments.student_information_id')
            ->where('class_details.section_id', $ClassSubjectDetail->section_id)
            ->whereRaw('student_informations.gender = 2')
            ->selectRaw("    grade11__second__sems.enrollment_id,                
                    CONCAT(student_informations.last_name, ' ', student_informations.first_name, ' ', student_informations.middle_name) as student_name,
                    grade11__second__sems.subject_1, grade11__second__sems.subject_2, grade11__second__sems.subject_3, grade11__second__sems.subject_4
                    , grade11__second__sems.subject_5, grade11__second__sems.subject_6, grade11__second__sems.subject_7
                    , grade11__second__sems.subject_8, grade11__second__sems.subject_9
                    ")
            ->distinct()
            ->orderBY('student_name','ASC')
            ->get();
            
            return view('control_panel_faculty.my_advisory_class.partials.data_list', 
            compact('type','NumberOfSubject', 'ClassSubjectDetail','AdvisorySubject','GradeSheetMale','GradeSheetFeMale','quarter','AdvisorySubject','sem'))->render();
    
        }

        public function first_sem_GradeSheetAverage_print(Request $request)
        {
            $type = "average";

            $FacultyInformation = \App\FacultyInformation::where('user_id', \Auth::user()->id)->first();
         
            $ClassSubjectDetail = \App\ClassSubjectDetail::join('class_details', 'class_details.id', '=', 'class_subject_details.class_details_id')
            // ->join('subject_details', 'subject_details.id', '=', 'class_subject_details.subject_id')
            ->join('rooms','rooms.id', '=', 'class_details.room_id')
            ->join('section_details', 'section_details.id', '=', 'class_details.section_id')
            ->join('school_years', 'school_years.id' ,'=', 'class_details.school_year_id')
            ->join('faculty_informations', 'faculty_informations.id','=','class_details.adviser_id')
            // ->whereRaw('class_subject_details.id = '. $request->search_class_subject)
            // ->whereRaw('class_details.id = '. $search_class_subject[1])
            ->where('class_details.adviser_id', $FacultyInformation->id)
            ->where('class_details.school_year_id', $request->search_sy1)
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
                faculty_informations.first_name, faculty_informations.middle_name ,  faculty_informations.last_name,
                school_years.school_year                
            '))
            ->first();

           
            $sem = 'First';
            $quarter = 'First - Second';
           
            $FacultyInformation = \App\FacultyInformation::where('user_id', \Auth::user()->id)->first();       
            
            $AdvisorySubject = \App\ClassSubjectDetail::join('subject_details', 'subject_details.id', '=' ,'class_subject_details.subject_id')
                ->join('class_details', 'class_details.id', '=' ,'class_subject_details.class_details_id')
                ->join('faculty_informations', 'faculty_informations.id', '=' ,'class_subject_details.faculty_id')           
                ->selectRaw("                
                    subject_details.subject, subject_details.id, class_subject_details.class_subject_order, subject_details.subject_code
                ")
                ->where('class_subject_details.class_details_id', $ClassSubjectDetail->id)
                ->where('class_subject_details.status', 1)
                // ->where('faculty_id', $FacultyInformation->id)
                ->where('class_details.school_year_id', $request->search_sy1)
                ->where('class_subject_details.sem', 1)
                //  ->where('class_details.school_year_id', $request->search_sy)
                // ->orderBy('class_subject_details.class_time_from', 'ASC');
                ->orderBY('class_subject_details.class_subject_order','ASC')
                ->get();            
            

            $GradeSheetMale = \App\Grade_sheet_firstsem::join('class_details','class_details.section_id','=','grade_sheet_firstsems.section_details_id')            
                ->join('enrollments','enrollments.id','=','grade_sheet_firstsems.enrollment_id')
                ->join('student_informations','student_informations.id','=','enrollments.student_information_id')
                ->where('class_details.section_id', $ClassSubjectDetail->section_id)
                ->whereRaw('student_informations.gender = 1')
                ->selectRaw("  grade_sheet_firstsems.enrollment_id,                
                        CONCAT(student_informations.last_name, ' ', student_informations.first_name, ' ', student_informations.middle_name) as student_name,
                        grade_sheet_firstsems.subject_1, grade_sheet_firstsems.subject_2, grade_sheet_firstsems.subject_3, grade_sheet_firstsems.subject_4
                        , grade_sheet_firstsems.subject_5, grade_sheet_firstsems.subject_6, grade_sheet_firstsems.subject_7
                        , grade_sheet_firstsems.subject_8, grade_sheet_firstsems.subject_9
                        ")
                ->distinct()
                ->orderBY('student_name','ASC')
                ->get();

            $GradeSheetFeMale = \App\Grade_sheet_firstsem::join('class_details','class_details.section_id','=','grade_sheet_firstsems.section_details_id')            
                ->join('enrollments','enrollments.id','=','grade_sheet_firstsems.enrollment_id')
                ->join('student_informations','student_informations.id','=','enrollments.student_information_id')
                ->where('class_details.section_id', $ClassSubjectDetail->section_id)
                ->whereRaw('student_informations.gender = 2')
                ->selectRaw("   grade_sheet_firstsems.enrollment_id,                    
                        CONCAT(student_informations.last_name, ' ', student_informations.first_name, ' ', student_informations.middle_name) as student_name,
                        grade_sheet_firstsems.subject_1, grade_sheet_firstsems.subject_2, grade_sheet_firstsems.subject_3, grade_sheet_firstsems.subject_4
                        , grade_sheet_firstsems.subject_5, grade_sheet_firstsems.subject_6, grade_sheet_firstsems.subject_7
                        , grade_sheet_firstsems.subject_8, grade_sheet_firstsems.subject_9
                        ")
                ->distinct()
                ->orderBY('student_name','ASC')
                ->get();

            

            $NumberOfSubject = \App\ClassSubjectDetail::join('subject_details', 'subject_details.id', '=' ,'class_subject_details.subject_id')
                ->join('class_details', 'class_details.id', '=' ,'class_subject_details.class_details_id')
                ->join('faculty_informations', 'faculty_informations.id', '=' ,'class_subject_details.faculty_id')           
                ->selectRaw("                
                                class_subject_details.class_subject_order
                            ")
                ->where('class_subject_details.class_details_id', $ClassSubjectDetail->id)
                ->where('class_subject_details.status', 1)
                // ->where('faculty_id', $FacultyInformation->id)
                ->where('class_details.school_year_id', $request->search_sy1)
                ->where('class_subject_details.sem', 2)
                //  ->where('class_details.school_year_id', $request->search_sy)
                // ->orderBy('class_subject_details.class_time_from', 'ASC');
                ->orderBY('class_subject_details.class_subject_order','DESC')
                ->first();       

            return view('control_panel_faculty.my_advisory_class.partials.print_senior_average', 
            compact( 'type','NumberOfSubject', 'ClassSubjectDetail','AdvisorySubject','GradeSheetMale','GradeSheetFeMale','quarter','AdvisorySubject','sem'));
            $pdf = \PDF::loadView('control_panel_faculty.my_advisory_class.partials.print_senior_average', 
            compact( 'type','NumberOfSubject', 'ClassSubjectDetail','AdvisorySubject','GradeSheetMale','GradeSheetFeMale','quarter','AdvisorySubject','sem'));
            $pdf->setPaper('Legal', 'portrait');
            return $pdf->stream();
    
        }

        public function second_sem_GradeSheetAverage_print(Request $request)
        {
            $type = "average";

            $FacultyInformation = \App\FacultyInformation::where('user_id', \Auth::user()->id)->first();
         
            $ClassSubjectDetail = \App\ClassSubjectDetail::join('class_details', 'class_details.id', '=', 'class_subject_details.class_details_id')
            // ->join('subject_details', 'subject_details.id', '=', 'class_subject_details.subject_id')
            ->join('rooms','rooms.id', '=', 'class_details.room_id')
            ->join('section_details', 'section_details.id', '=', 'class_details.section_id')
            ->join('school_years', 'school_years.id' ,'=', 'class_details.school_year_id')
            ->join('faculty_informations', 'faculty_informations.id','=','class_details.adviser_id')
            // ->whereRaw('class_subject_details.id = '. $request->search_class_subject)
            // ->whereRaw('class_details.id = '. $search_class_subject[1])
            ->where('class_details.adviser_id', $FacultyInformation->id)
            ->where('class_details.school_year_id', $request->search_sy1)
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
                faculty_informations.first_name, faculty_informations.middle_name ,  faculty_informations.last_name,
                school_years.school_year                
            '))
            ->first();

           
            $sem = 'Second';
            $quarter = 'First - Second';
           
            
                
                $FacultyInformation = \App\FacultyInformation::where('user_id', \Auth::user()->id)->first();       
                    
                $AdvisorySubject = \App\ClassSubjectDetail::join('subject_details', 'subject_details.id', '=' ,'class_subject_details.subject_id')
                ->join('class_details', 'class_details.id', '=' ,'class_subject_details.class_details_id')
                ->join('faculty_informations', 'faculty_informations.id', '=' ,'class_subject_details.faculty_id')           
                ->selectRaw("                
                    subject_details.subject, subject_details.id, class_subject_details.class_subject_order, subject_details.subject_code
                ")
                ->where('class_subject_details.class_details_id', $ClassSubjectDetail->id)
                ->where('class_subject_details.status', 1)
                // ->where('faculty_id', $FacultyInformation->id)
                ->where('class_details.school_year_id', $request->search_sy1)
                ->where('class_subject_details.sem', 2)
                //  ->where('class_details.school_year_id', $request->search_sy)
                // ->orderBy('class_subject_details.class_time_from', 'ASC');
                ->orderBY('class_subject_details.class_subject_order','ASC')
                ->get();        
                
                $NumberOfSubject = \App\ClassSubjectDetail::join('subject_details', 'subject_details.id', '=' ,'class_subject_details.subject_id')
                ->join('class_details', 'class_details.id', '=' ,'class_subject_details.class_details_id')
                ->join('faculty_informations', 'faculty_informations.id', '=' ,'class_subject_details.faculty_id')           
                ->selectRaw("                
                                class_subject_details.class_subject_order
                            ")
                ->where('class_subject_details.class_details_id', $ClassSubjectDetail->id)
                ->where('class_subject_details.status', 1)
                // ->where('faculty_id', $FacultyInformation->id)
                ->where('class_details.school_year_id', $request->search_sy1)
                ->where('class_subject_details.sem', 2)
                //  ->where('class_details.school_year_id', $request->search_sy)
                // ->orderBy('class_subject_details.class_time_from', 'ASC');
                ->orderBY('class_subject_order','DESC')
                ->first();           
                
    
                $GradeSheetMale = \App\Grade11_Second_Sem::join('class_details','class_details.section_id','=','grade11__second__sems.section_details_id')            
                ->join('enrollments','enrollments.id','=','grade11__second__sems.enrollment_id')
                ->join('student_informations','student_informations.id','=','enrollments.student_information_id')
                ->where('class_details.section_id', $ClassSubjectDetail->section_id)
                ->whereRaw('student_informations.gender = 1')
                ->selectRaw("  grade11__second__sems.enrollment_id,                
                        CONCAT(student_informations.last_name, ' ', student_informations.first_name, ' ', student_informations.middle_name) as student_name,
                        grade11__second__sems.subject_1, grade11__second__sems.subject_2, grade11__second__sems.subject_3, grade11__second__sems.subject_4
                        , grade11__second__sems.subject_5, grade11__second__sems.subject_6, grade11__second__sems.subject_7
                        , grade11__second__sems.subject_8, grade11__second__sems.subject_9
                        ")
                ->distinct()
                ->orderBY('student_name','ASC')
                ->get();
    
                $GradeSheetFeMale = \App\Grade11_Second_Sem::join('class_details','class_details.section_id','=','grade11__second__sems.section_details_id')            
                ->join('enrollments','enrollments.id','=','grade11__second__sems.enrollment_id')
                ->join('student_informations','student_informations.id','=','enrollments.student_information_id')
                ->where('class_details.section_id', $ClassSubjectDetail->section_id)
                ->whereRaw('student_informations.gender = 2')
                ->selectRaw("    grade11__second__sems.enrollment_id,                
                        CONCAT(student_informations.last_name, ' ', student_informations.first_name, ' ', student_informations.middle_name) as student_name,
                        grade11__second__sems.subject_1, grade11__second__sems.subject_2, grade11__second__sems.subject_3, grade11__second__sems.subject_4
                        , grade11__second__sems.subject_5, grade11__second__sems.subject_6, grade11__second__sems.subject_7
                        , grade11__second__sems.subject_8, grade11__second__sems.subject_9
                        ")
                ->distinct()
                ->orderBY('student_name','ASC')
                ->get();

            return view('control_panel_faculty.my_advisory_class.partials.print_senior_average', 
                compact( 'type','NumberOfSubject', 'ClassSubjectDetail','AdvisorySubject','GradeSheetMale','GradeSheetFeMale','quarter','AdvisorySubject','sem'));
            $pdf = \PDF::loadView('control_panel_faculty.my_advisory_class.partials.print_senior_average', 
                compact( 'type','NumberOfSubject', 'ClassSubjectDetail','AdvisorySubject','GradeSheetMale','GradeSheetFeMale','quarter','AdvisorySubject','sem'));
            $pdf->setPaper('Legal', 'portrait');
            return $pdf->stream();
    
        }

        public function finalGradeSheetAverage_print(Request $request)
        {
            $type = "average";

            $FacultyInformation = \App\FacultyInformation::where('user_id', \Auth::user()->id)->first();
         
            $ClassSubjectDetail = \App\ClassSubjectDetail::join('class_details', 'class_details.id', '=', 'class_subject_details.class_details_id')
            // ->join('subject_details', 'subject_details.id', '=', 'class_subject_details.subject_id')
            ->join('rooms','rooms.id', '=', 'class_details.room_id')
            ->join('section_details', 'section_details.id', '=', 'class_details.section_id')
            ->join('school_years', 'school_years.id' ,'=', 'class_details.school_year_id')
            ->join('faculty_informations', 'faculty_informations.id','=','class_details.adviser_id')
            // ->whereRaw('class_subject_details.id = '. $request->search_class_subject)
            // ->whereRaw('class_details.id = '. $search_class_subject[1])
            ->where('class_details.adviser_id', $FacultyInformation->id)
            ->where('class_details.school_year_id', $request->search_sy1)
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
                faculty_informations.first_name, faculty_informations.middle_name ,  faculty_informations.last_name,
                school_years.school_year                
            '))
            ->first();

            $no_sub  = \App\ClassSubjectDetail::where('id', $ClassSubjectDetail->id)
            ->where('sem', 2)->where('status', '!=', 0)->count();

           
            $quarter = 'First - Fourth';
            $sem = 'First and Second';
           
            $FacultyInformation = \App\FacultyInformation::where('user_id', \Auth::user()->id)->first();       
            
                      
            

            $GradeSheetMale = \App\Grade_sheet_firstsem::join('class_details','class_details.section_id','=','grade_sheet_firstsems.section_details_id')            
                ->join('enrollments','enrollments.id','=','grade_sheet_firstsems.enrollment_id')
                ->join('student_informations','student_informations.id','=','enrollments.student_information_id')
                ->where('class_details.section_id', $ClassSubjectDetail->section_id)
                ->whereRaw('student_informations.gender = 1')
                ->selectRaw("  grade_sheet_firstsems.enrollment_id,                
                        CONCAT(student_informations.last_name, ' ', student_informations.first_name, ' ', student_informations.middle_name) as student_name,
                        grade_sheet_firstsems.subject_1, grade_sheet_firstsems.subject_2, grade_sheet_firstsems.subject_3, grade_sheet_firstsems.subject_4
                        , grade_sheet_firstsems.subject_5, grade_sheet_firstsems.subject_6, grade_sheet_firstsems.subject_7
                        , grade_sheet_firstsems.subject_8, grade_sheet_firstsems.subject_9
                        ")
                ->distinct()
                ->orderBY('student_name','ASC')
                ->get();

            $GradeSheetFeMale = \App\Grade_sheet_firstsem::join('class_details','class_details.section_id','=','grade_sheet_firstsems.section_details_id')            
                ->join('enrollments','enrollments.id','=','grade_sheet_firstsems.enrollment_id')
                ->join('student_informations','student_informations.id','=','enrollments.student_information_id')
                ->where('class_details.section_id', $ClassSubjectDetail->section_id)
                ->whereRaw('student_informations.gender = 2')
                ->selectRaw("   grade_sheet_firstsems.enrollment_id,                    
                        CONCAT(student_informations.last_name, ' ', student_informations.first_name, ' ', student_informations.middle_name) as student_name,
                        grade_sheet_firstsems.subject_1, grade_sheet_firstsems.subject_2, grade_sheet_firstsems.subject_3, grade_sheet_firstsems.subject_4
                        , grade_sheet_firstsems.subject_5, grade_sheet_firstsems.subject_6, grade_sheet_firstsems.subject_7
                        , grade_sheet_firstsems.subject_8, grade_sheet_firstsems.subject_9
                        ")
                ->distinct()
                ->orderBY('student_name','ASC')
                ->get();
            

            $NumberOfSubject = \App\ClassSubjectDetail::join('subject_details', 'subject_details.id', '=' ,'class_subject_details.subject_id')
                ->join('class_details', 'class_details.id', '=' ,'class_subject_details.class_details_id')
                ->join('faculty_informations', 'faculty_informations.id', '=' ,'class_subject_details.faculty_id')           
                ->selectRaw("                
                                class_subject_details.class_subject_order
                            ")
                ->where('class_subject_details.class_details_id', $ClassSubjectDetail->id)
                ->where('class_subject_details.status','!=', 0)
                // ->where('faculty_id', $FacultyInformation->id)
                ->where('class_details.school_year_id', $request->search_sy1)
                ->where('class_subject_details.sem', 2)
                //  ->where('class_details.school_year_id', $request->search_sy)
                // ->orderBy('class_subject_details.class_time_from', 'ASC');
                ->orderBY('class_subject_details.class_subject_order','DESC')
                ->first();  

                
            // $count_subjects1 = \App\StudentEnrolledSubject::where('enrollments_id', $ClassSubjectDetail->enrollment_id)
            //     ->where('sem', 2)->where('status', '!=', 0)->count();

            return view('control_panel_faculty.my_advisory_class.partials.print_senior_average', 
            compact( 'type','NumberOfSubject', 'ClassSubjectDetail','AdvisorySubject','GradeSheetMale','GradeSheetFeMale','quarter','sem'));
            $pdf = \PDF::loadView('control_panel_faculty.my_advisory_class.partials.print_senior_average', 
            compact( 'type','NumberOfSubject', 'ClassSubjectDetail','AdvisorySubject','GradeSheetMale','GradeSheetFeMale','quarter','sem'));
            $pdf->setPaper('Legal', 'portrait');
            return $pdf->stream();
    
        }
}
