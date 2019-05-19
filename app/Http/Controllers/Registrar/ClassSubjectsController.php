<?php

namespace App\Http\Controllers\Registrar;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ClassSubjectsController extends Controller
{
    public function index (Request $request, $class_id) 
    {
        $Semester = \App\Semester::where('current', 1)->first();
        
        $ClassDetail = NULL;

        if($Semester->semester == '1st')
        {
            $ClassSubjectDetail = \App\ClassSubjectDetail::join('subject_details', 'subject_details.id', '=' ,'class_subject_details.subject_id')
            ->join('class_details', 'class_details.id', '=' ,'class_subject_details.class_details_id')
            ->join('faculty_informations', 'faculty_informations.id', '=' ,'class_subject_details.faculty_id')
            // ->join('rooms', 'rooms.id', '=' ,'class_subject_details.room_id')

            // class_details.room_id,
                // rooms.room_code,
                // rooms.room_description
            ->selectRaw("
                class_subject_details.id,
                class_details.school_year_id,
                class_details.grade_level,
                CONCAT(faculty_informations.last_name, ' ', faculty_informations.first_name) as faculty_name,
                subject_details.subject_code,
                subject_details.subject,
                class_subject_details.class_schedule,
                class_subject_details.class_time_from,
                class_subject_details.class_time_to,
                class_subject_details.class_days,
                class_subject_details.class_subject_order
            ")
            ->where('class_subject_details.class_details_id', $class_id)
            ->where('class_subject_details.status', 1)
            ->where('class_subject_details.sem', 1)
            // ->orderBy('class_subject_details.class_time_from', 'ASC');
            ->orderBy('class_subject_details.class_subject_order', 'ASC');
        }
        else 
        {
            $ClassSubjectDetail = \App\ClassSubjectDetail::join('subject_details', 'subject_details.id', '=' ,'class_subject_details.subject_id')
            ->join('class_details', 'class_details.id', '=' ,'class_subject_details.class_details_id')
            ->join('faculty_informations', 'faculty_informations.id', '=' ,'class_subject_details.faculty_id')
            // ->join('rooms', 'rooms.id', '=' ,'class_subject_details.room_id')

            // class_details.room_id,
                // rooms.room_code,
                // rooms.room_description
            ->selectRaw("
                class_subject_details.id,
                class_details.school_year_id,
                class_details.grade_level,
                CONCAT(faculty_informations.last_name, ' ', faculty_informations.first_name) as faculty_name,
                subject_details.subject_code,
                subject_details.subject,
                class_subject_details.class_schedule,
                class_subject_details.class_time_from,
                class_subject_details.class_time_to,
                class_subject_details.class_days,
                class_subject_details.class_subject_order
            ")
            ->where('class_subject_details.class_details_id', $class_id)
            ->where('class_subject_details.status', 1)
            ->where('class_subject_details.sem', 2)
            // ->orderBy('class_subject_details.class_time_from', 'ASC');
            ->orderBy('class_subject_details.class_subject_order', 'ASC');
        }

        $ClassSubjectDetail1 = \App\ClassSubjectDetail::join('subject_details', 'subject_details.id', '=' ,'class_subject_details.subject_id')
            ->join('class_details', 'class_details.id', '=' ,'class_subject_details.class_details_id')
            ->join('faculty_informations', 'faculty_informations.id', '=' ,'class_subject_details.faculty_id')
            // ->join('rooms', 'rooms.id', '=' ,'class_subject_details.room_id')

            // class_details.room_id,
                // rooms.room_code,
                // rooms.room_description
            ->selectRaw("
                class_subject_details.id,
                class_details.school_year_id,
                class_details.grade_level,
                CONCAT(faculty_informations.last_name, ' ', faculty_informations.first_name) as faculty_name,
                subject_details.subject_code,
                subject_details.subject,
                class_subject_details.class_schedule,
                class_subject_details.class_time_from,
                class_subject_details.class_time_to,
                class_subject_details.class_days,
                class_subject_details.class_subject_order
            ")
            ->where('class_subject_details.class_details_id', $class_id)
            ->where('class_subject_details.status', 1)
            ->where('class_subject_details.sem', 1)
            // ->orderBy('class_subject_details.class_time_from', 'ASC');
            ->orderBy('class_subject_details.class_subject_order', 'ASC');

        
        
        
        
        if ($request->ajax())
        {            
            $ClassSubjectDetail = $ClassSubjectDetail->paginate(10);
            $ClassSubjectDetail1 = $ClassSubjectDetail1->paginate(10);

            $ClassDetail = \App\ClassDetail::join('section_details', 'section_details.id', '=' ,'class_details.section_id')
            ->join('rooms', 'rooms.id', '=' ,'class_details.room_id')
            ->join('school_years', 'school_years.id', '=' ,'class_details.school_year_id')
            ->selectRaw('
                class_details.id,
                class_details.section_id,
                class_details.room_id,
                class_details.school_year_id,
                class_details.grade_level,
                class_details.current,
                section_details.section,
                section_details.grade_level as section_grade_level,
                school_years.school_year,
                rooms.room_code,
                rooms.room_description
            ')
            ->where('class_details.id', $class_id)
            ->where('section_details.status', 1)
            ->first();

            return view('control_panel_registrar.class_subjects.partials.data_list', compact('ClassSubjectDetail','Semester','ClassSubjectDetail1','ClassDetail'))->render();
        }
        else 
        {
            $ClassDetail = \App\ClassDetail::join('section_details', 'section_details.id', '=' ,'class_details.section_id')
            ->join('rooms', 'rooms.id', '=' ,'class_details.room_id')
            ->join('school_years', 'school_years.id', '=' ,'class_details.school_year_id')
            ->selectRaw('
                class_details.id,
                class_details.section_id,
                class_details.room_id,
                class_details.school_year_id,
                class_details.grade_level,
                class_details.current,
                section_details.section,
                section_details.grade_level as section_grade_level,
                school_years.school_year,
                rooms.room_code,
                rooms.room_description
            ')
            ->where('class_details.id', $class_id)
            ->where('section_details.status', 1)
            ->first();
        }

        $ClassSubjectDetail = $ClassSubjectDetail->paginate(10);
        $ClassSubjectDetail1 = $ClassSubjectDetail1->paginate(10);
        return view('control_panel_registrar.class_subjects.index', compact('ClassSubjectDetail', 'class_id', 'ClassDetail','Semester','ClassSubjectDetail1'));
    }
    public function modal_data (Request $request) 
    {
        $ClassSubjectDetail = NULL;
        if ($request->class_subject_details_id)
        {
            $ClassSubjectDetail = \App\ClassSubjectDetail::where('id', $request->class_subject_details_id)->first();
        }
        $class_details_id = $request->class_details_id;
        $FacultyInformation = \App\FacultyInformation::where('status', 1)->get();
        $SubjectDetail = \App\SubjectDetail::where('status', 1)->get();
        
        $ClassDetail = NULL;

        $ClassDetail = \App\ClassDetail::join('section_details', 'section_details.id', '=' ,'class_details.section_id')
            ->join('rooms', 'rooms.id', '=' ,'class_details.room_id')
            ->join('school_years', 'school_years.id', '=' ,'class_details.school_year_id')
            ->selectRaw('
                class_details.id,
                class_details.section_id,
                class_details.room_id,
                class_details.school_year_id,
                class_details.grade_level,
                class_details.current,
                section_details.section,
                section_details.grade_level as section_grade_level,
                school_years.school_year,
                rooms.room_code,
                rooms.room_description
            ')
            ->where('class_details.id', $class_details_id)
            ->where('section_details.status', 1)
            ->first();
        // $section_id = $ClassDetail->section;
        
        // return json_encode($ClassSubjectDetail);
        return view('control_panel_registrar.class_subjects.partials.modal_data', compact('ClassSubjectDetail', 'FacultyInformation', 'SubjectDetail', 'class_details_id','ClassDetail'))->render();
    }

    public function modal_manage_subjects (Request $request) 
    {
        $ClassSubjectDetail = NULL;
        if ($request->class_subject_details_id)
        {
            $ClassSubjectDetail = \App\ClassSubjectDetail::where('id', $request->class_subject_details_id)->first();
        }
        
        $FacultyInformation = \App\FacultyInformation::where('status', 1)->get();
        $ClassSubjectDetail = \App\ClassSubjectDetail::where('status', 1)->get();
        $SectionDetail = \App\SectionDetail::where('status', 1)->get();
        $Room = \App\Room::where('status', 1)->get();
        $SchoolYear = \App\SchoolYear::where('status', 1)->get();

        

        return view('control_panel_registrar.class_subjects.partials.modal_manage_subjects', compact('ClassSubjectDetail', 'FacultyInformation', 'ClassSubjectDetail', 'SectionDetail', 'Room', 'SchoolYear'))->render();
    }

    public function save_data (Request $request) 
    {
        $scheds = '';
        if ($request->sched_mon) 
        {
            $scheds .= '1@'.date('H:i', strtotime($request->subject_time_from_mon)).'-'.date('H:i', strtotime($request->subject_time_to_mon)).';';
        }
        if ($request->sched_tue) 
        {
            $scheds .= '2@'.date('H:i', strtotime($request->subject_time_from_tue)).'-'.date('H:i', strtotime($request->subject_time_to_tue)).';';
        }
        if ($request->sched_wed) 
        {
            $scheds .= '3@'.date('H:i', strtotime($request->subject_time_from_wed)).'-'.date('H:i', strtotime($request->subject_time_to_wed)).';';
        }
        if ($request->sched_thur) 
        {
            $scheds .= '4@'.date('H:i', strtotime($request->subject_time_from_thur)).'-'.date('H:i', strtotime($request->subject_time_to_thur)).';';
        }
        if ($request->sched_fri) 
        {
            $scheds .= '5@'.date('H:i', strtotime($request->subject_time_from_fri)).'-'.date('H:i', strtotime($request->subject_time_to_fri)).';';
        }
        // return json_encode(['a' => $request->all(), 'scheds' => $scheds]);
        $rules = [
            'faculty'           => 'required',
            'subject'           => 'required',
            // 'subject_time_from' => 'required',
            // 'subject_time_to'   => 'required'
        ];
        
        
        $Validator = \Validator($request->all(), $rules);

        if ($Validator->fails())
        {
            return response()->json(['res_code' => 1, 'res_msg' => 'Please fill all required fields.', 'res_error_msg' => $Validator->getMessageBag()]);
        }

        // return json_encode($request->id);
        // $sectionDetail = \App\sectionDetail::where('id', $request->section)->first();
        
        if ($request->id)
        {
            $ClassSubjectDetail = \App\ClassSubjectDetail::where('id', $request->id)->first();
            $ClassSubjectDetail->class_time_from		    = date('H:i', strtotime($request->subject_time_from));
            $ClassSubjectDetail->class_time_to		    = date('H:i', strtotime($request->subject_time_to));
            $ClassSubjectDetail->subject_id	        = $request->subject;
            $ClassSubjectDetail->faculty_id		    = $request->faculty;
            $ClassSubjectDetail->class_details_id   = $request->class_details_id;
            $ClassSubjectDetail->class_subject_order   = $request->order;
            $ClassSubjectDetail->class_schedule   = $scheds;

            // $class_days = '';
            // $class_days .= $request->sched_mon ? 'm/' : '';
            // $class_days .= $request->sched_tue ? 'tu/' : '';
            // $class_days .= $request->sched_wed ? 'w/' : '';
            // $class_days .= $request->sched_thu ? 'th/' : '';
            // $class_days .= $request->sched_fri ? 'f' : '';
            
            // $class_days .= !$request->sched_mon && !$request->sched_tue && !$request->sched_wed && !$request->sched_thu && !$request->sched_fri ? 'm/tu/w/th/f' : '';

            // $ClassSubjectDetail->class_days = $class_days;
            if($request->order == 1)
            {
                
                \App\Subject_Title::where(['section_id'=> $request->section_id])->update(['subject_1'=>$request->subject]);                
                
                    
            }
            else if($request->order == 2)
            {
            
                \App\Subject_Title::where(['section_id'=> $request->section_id])->update(['subject_2'=>$request->subject]);                
                
            }
            else if($request->order == 3)
            {
                
                \App\Subject_Title::where(['section_id'=> $request->section_id])->update(['subject_3'=>$request->subject]);                
                
            }
            else if($request->order == 4)
            {
            
                \App\Subject_Title::where(['section_id'=> $request->section_id])->update(['subject_4'=>$request->subject]);                
                
            }
            else if($request->order == 5)
            {
                
                \App\Subject_Title::where(['section_id'=> $request->section_id])->update(['subject_5'=>$request->subject]);                
                
            }
            else if($request->order == 6)
            {
                
                \App\Subject_Title::where(['section_id'=> $request->section_id])->update(['subject_6'=>$request->subject]);                
                
            }
            else if($request->order == 7)
            {
                
                \App\Subject_Title::where(['section_id'=> $request->section_id])->update(['subject_7'=>$request->subject]);                
                
            }
            else if($request->order == 8)
            {
                
                \App\Subject_Title::where(['section_id'=> $request->section_id])->update(['subject_8'=>$request->subject]);                
                
            }
            else if($request->order == 9)
            {
                
                \App\Subject_Title::where(['section_id'=> $request->section_id])->update(['subject_9'=>$request->subject]);                
                
            }
            

            $ClassSubjectDetail->save();
            return response()->json(['res_code' => 0, 'res_msg' => 'Data successfully updated.', 'ClassSubjectDetail' => $ClassSubjectDetail]);
        }
        

        $ClassSubjectDetail = new \App\ClassSubjectDetail();
        $ClassSubjectDetail->class_time_from		    = date('H:i', strtotime($request->subject_time_from));
        $ClassSubjectDetail->class_time_to		    = date('H:i', strtotime($request->subject_time_to));
        $ClassSubjectDetail->subject_id	        = $request->subject;
        $ClassSubjectDetail->faculty_id		    = $request->faculty;
        $ClassSubjectDetail->class_details_id   = $request->class_details_id;
        $ClassSubjectDetail->class_subject_order   = $request->order;
        $ClassSubjectDetail->class_schedule   = $scheds;
        
        $Semester = \App\Semester::where('current', 1)->first();

        $sem;
        if($Semester->semester == '1st')
        {
            $ClassSubjectDetail->sem = 1;
            $sem = 1;
        }
        else 
        {
            $ClassSubjectDetail->sem = 2;
            $sem = 2;
        }

        // saving for title of gradesheet
        
        
        $count = \App\Subject_Title::where('section_id', $request->section_id)->count();

        if($request->order == 1)
        {
            if($count == 0)
            {
                $Subject_Title = new \App\Subject_Title();  
                $Subject_Title->section_id = $request->section_id;
                $Subject_Title->subject_1  = $request->subject;
                $Subject_Title->subject_2  = 0;
                $Subject_Title->subject_3  = 0;
                $Subject_Title->subject_4  = 0;
                $Subject_Title->subject_5  = 0;
                $Subject_Title->subject_6  = 0;
                $Subject_Title->subject_7  = 0;
                $Subject_Title->subject_8  = 0;
                $Subject_Title->subject_9  = 0;
                $Subject_Title->sem = $sem;
                $Subject_Title->save();                
            }
            else
            {
                \App\Subject_Title::where(['section_id'=> $request->section_id])->update(['subject_1'=>$request->subject]);                
            }
                   
        }
        else if($request->order == 2)
        {
           if($count == 0)
            {
                $Subject_Title = new \App\Subject_Title();  
                $Subject_Title->section_id = $request->section_id;
                $Subject_Title->subject_1  = 0;
                $Subject_Title->subject_2  = $request->subject;
                $Subject_Title->subject_3  = 0;
                $Subject_Title->subject_4  = 0;
                $Subject_Title->subject_5  = 0;
                $Subject_Title->subject_6  = 0;
                $Subject_Title->subject_7  = 0;
                $Subject_Title->subject_8  = 0;
                $Subject_Title->subject_9  = 0;
                $Subject_Title->sem = $sem;
                $Subject_Title->save();
                
            }
            else
            {
                \App\Subject_Title::where(['section_id'=> $request->section_id])->update(['subject_2'=>$request->subject]);                
            }
        }
        else if($request->order == 3)
        {
            if($count == 0)
            {
                $Subject_Title = new \App\Subject_Title();  
                $Subject_Title->section_id = $request->section_id;
                $Subject_Title->subject_1  = 0;
                $Subject_Title->subject_2  = 0;
                $Subject_Title->subject_3  = $request->subject;
                $Subject_Title->subject_4  = 0;
                $Subject_Title->subject_5  = 0;
                $Subject_Title->subject_6  = 0;
                $Subject_Title->subject_7  = 0;
                $Subject_Title->subject_8  = 0;
                $Subject_Title->subject_9  = 0;
                $Subject_Title->sem = $sem;
                $Subject_Title->save();
                
            }
            else
            {
                \App\Subject_Title::where(['section_id'=> $request->section_id])->update(['subject_3'=>$request->subject]);                
            }
        }
        else if($request->order == 4)
        {
            if($count == 0)
            {
                $Subject_Title = new \App\Subject_Title();  
                $Subject_Title->section_id = $request->section_id;
                $Subject_Title->subject_1  = 0;
                $Subject_Title->subject_2  = 0;
                $Subject_Title->subject_3  = 0;
                $Subject_Title->subject_4  = $request->subject;
                $Subject_Title->subject_5  = 0;
                $Subject_Title->subject_6  = 0;
                $Subject_Title->subject_7  = 0;
                $Subject_Title->subject_8  = 0;
                $Subject_Title->subject_9  = 0;
                $Subject_Title->sem = $sem;
                $Subject_Title->save();
                
            }
            else
            {
                \App\Subject_Title::where(['section_id'=> $request->section_id])->update(['subject_4'=>$request->subject]);                
            }
        }
        else if($request->order == 5)
        {
            if($count == 0)
            {
                $Subject_Title = new \App\Subject_Title();  
                $Subject_Title->section_id = $request->section_id;
                $Subject_Title->subject_1  = 0;
                $Subject_Title->subject_2  = 0;
                $Subject_Title->subject_3  = 0;
                $Subject_Title->subject_4  = 0;
                $Subject_Title->subject_5  = $request->subject;
                $Subject_Title->subject_6  = 0;
                $Subject_Title->subject_7  = 0;
                $Subject_Title->subject_8  = 0;
                $Subject_Title->subject_9  = 0;
                $Subject_Title->sem = $sem;
                $Subject_Title->save();
                
            }
            else
            {
                \App\Subject_Title::where(['section_id'=> $request->section_id])->update(['subject_5'=>$request->subject]);                
            }
        }
        else if($request->order == 6)
        {
            if($count == 0)
            {
                $Subject_Title = new \App\Subject_Title();  
                $Subject_Title->section_id = $request->section_id;
                $Subject_Title->subject_1  = 0;
                $Subject_Title->subject_2  = 0;
                $Subject_Title->subject_3  = 0;
                $Subject_Title->subject_4  = 0;
                $Subject_Title->subject_5  = 0;
                $Subject_Title->subject_6  = $request->subject;
                $Subject_Title->subject_7  = 0;
                $Subject_Title->subject_8  = 0;
                $Subject_Title->subject_9  = 0;
                $Subject_Title->sem = $sem;
                $Subject_Title->save();
                
            }
            else
            {
                \App\Subject_Title::where(['section_id'=> $request->section_id])->update(['subject_6'=>$request->subject]);                
            }
        }
        else if($request->order == 7)
        {
            if($count == 0)
            {
                $Subject_Title = new \App\Subject_Title();  
                $Subject_Title->section_id = $request->section_id;
                $Subject_Title->subject_1  = 0;
                $Subject_Title->subject_2  = 0;
                $Subject_Title->subject_3  = 0;
                $Subject_Title->subject_4  = 0;
                $Subject_Title->subject_5  = 0;
                $Subject_Title->subject_6  = 0;
                $Subject_Title->subject_7  = $request->subject;
                $Subject_Title->subject_8  = 0;
                $Subject_Title->subject_9  = 0;
                $Subject_Title->sem = $sem;
                $Subject_Title->save();
                
            }
            else
            {
                \App\Subject_Title::where(['section_id'=> $request->section_id])->update(['subject_7'=>$request->subject]);                
            }
        }
        else if($request->order == 8)
        {
            if($count == 0)
            {
                $Subject_Title = new \App\Subject_Title();  
                $Subject_Title->section_id = $request->section_id;
                $Subject_Title->subject_1  = 0;
                $Subject_Title->subject_2  = 0;
                $Subject_Title->subject_3  = 0;
                $Subject_Title->subject_4  = 0;
                $Subject_Title->subject_5  = 0;
                $Subject_Title->subject_6  = 0;
                $Subject_Title->subject_7  = 0;
                $Subject_Title->subject_8  = $request->subject;
                $Subject_Title->subject_9  = 0;
                $Subject_Title->sem = $sem;
                $Subject_Title->save();
                
            }
            else
            {
                \App\Subject_Title::where(['section_id'=> $request->section_id])->update(['subject_8'=>$request->subject]);                
            }
        }
        else if($request->order == 9)
        {
            if($count == 0)
            {
                $Subject_Title = new \App\Subject_Title();  
                $Subject_Title->section_id = $request->section_id;
                $Subject_Title->subject_1  = 0;
                $Subject_Title->subject_2  = 0;
                $Subject_Title->subject_3  = 0;
                $Subject_Title->subject_4  = 0;
                $Subject_Title->subject_5  = 0;
                $Subject_Title->subject_6  = 0;
                $Subject_Title->subject_7  = 0;
                $Subject_Title->subject_8  = 0;
                $Subject_Title->subject_9  = $request->subject;
                $Subject_Title->sem = $sem;
                $Subject_Title->save();
                
            }
            else
            {
                \App\Subject_Title::where(['section_id'=> $request->section_id])->update(['subject_9'=>$request->subject]);                
            }
        }
        
            
        // $class_days = '';
        // $class_days .= $request->sched_mon ? 'm/' : '';
        // $class_days .= $request->sched_tue ? 'tu/' : '';
        // $class_days .= $request->sched_wed ? 'w/' : '';
        // $class_days .= $request->sched_thu ? 'th/' : '';
        // $class_days .= $request->sched_fri ? 'f' : '';
        
        // $class_days .= !$request->sched_mon && !$request->sched_tue && !$request->sched_wed && !$request->sched_thu && !$request->sched_fri ? 'm/tu/w/th/f' : '';

        // $ClassSubjectDetail->class_days = $class_days;
        $ClassSubjectDetail->save();
        
        return response()->json(['res_code' => 0, 'res_msg' => 'Data successfully saved.', 'ClassSubjectDetail' => $ClassSubjectDetail]);
    }

    public function deactivate_data (Request $request) 
    {
        $ClassSubjectDetail = \App\ClassSubjectDetail::where('id', $request->id)->first();

        if ($ClassSubjectDetail)
        {
            $ClassSubjectDetail->status = 0;
            $ClassSubjectDetail->save();
            return response()->json(['res_code' => 0, 'res_msg' => 'Data successfully deactivated.']);
        }
        return response()->json(['res_code' => 1, 'res_msg' => 'Invalid request.']);
    }
}
