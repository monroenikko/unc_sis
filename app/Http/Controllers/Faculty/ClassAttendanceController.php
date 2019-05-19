<?php

namespace App\Http\Controllers\Faculty;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ClassAttendanceController extends Controller
{
    public function index (Request $request)
    {
        $FacultyInformation = \App\FacultyInformation::where('user_id', \Auth::user()->id)->first();

        $school_year_id = \App\SchoolYear::where('current', 1)->where('status', 1)->first()->id;
        $Semester = \App\ClassSubjectDetail::join('class_details', 'class_details.id', '=', 'class_subject_details.class_details_id')
        ->join('rooms','rooms.id', '=', 'class_details.room_id')
        ->join('section_details', 'section_details.id', '=', 'class_details.section_id')
        ->where('class_details.adviser_id', $FacultyInformation->id)
        ->where('class_details.school_year_id', $school_year_id)
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
            

        $count = $class_id = \App\ClassDetail::where('adviser_id', $FacultyInformation->id)->count();

        if ($request->ajax())
            {
               
                $attendance_male = \App\Enrollment::join('student_informations', 'student_informations.id', '=', 'enrollments.student_information_id')
                ->where('class_details_id', $class_id)
                ->whereRaw('student_informations.gender = 1')
                ->select(\DB::raw("
                        enrollments.id as e_id,
                        enrollments.attendance,
                        student_informations.id,
                        CONCAT(student_informations.last_name, ' ', student_informations.first_name, ' ', student_informations.middle_name) as student_name
                    "))
                ->orderBY('student_name', 'ASC')
                ->get();
    
                $attendance_female = \App\Enrollment::join('student_informations', 'student_informations.id', '=', 'enrollments.student_information_id')
                ->where('class_details_id', $class_id)
                ->whereRaw('student_informations.gender = 2')
                ->select(\DB::raw("
                        enrollments.id as e_id,
                        enrollments.attendance,
                        student_informations.id,
                        CONCAT(student_informations.last_name, ' ', student_informations.first_name, ' ', student_informations.middle_name) as student_name
                    "))
                ->orderBY('student_name', 'ASC')
                ->get();
    
                $Senior_firstsem_m = \App\Enrollment::join('student_informations', 'student_informations.id', '=', 'enrollments.student_information_id')
                ->where('class_details_id', $class_id)
                ->whereRaw('student_informations.gender = 1')
                ->select(\DB::raw("
                        enrollments.id as e_id,
                        enrollments.attendance_first,
                        enrollments.attendance_second,
                        student_informations.id,
                        CONCAT(student_informations.last_name, ' ', student_informations.first_name, ' ', student_informations.middle_name) as student_name
                    "))
                ->orderBY('student_name', 'ASC')
                ->get();
    
                $Senior_firstsem_f = \App\Enrollment::join('student_informations', 'student_informations.id', '=', 'enrollments.student_information_id')
                ->where('class_details_id', $class_id)
                ->whereRaw('student_informations.gender = 2')
                ->select(\DB::raw("
                        enrollments.id as e_id,
                        enrollments.attendance_first,
                        enrollments.attendance_second,
                        student_informations.id,
                        CONCAT(student_informations.last_name, ' ', student_informations.first_name, ' ', student_informations.middle_name) as student_name
                    "))
                ->orderBY('student_name', 'ASC')
                ->get();
                // return json_encode(['student_info' => $StudentInformation]);
                return view('control_panel.student_attendance.partials.data_list', compact('attendance_male','attendance_female','Semester','Senior_firstsem_m','Senior_firstsem_f','Senior_secondsem_f','Senior_secondsem_m'))->render();
            }
    

        if($count < 0)
        {
            $class_id = \App\ClassDetail::where('adviser_id', $FacultyInformation->id)->first()->id;
        }
        
        if($count > 0)
        {
            $class_id = \App\ClassDetail::where('adviser_id', $FacultyInformation->id)->first()->id;            
           
            $Semester = \App\ClassSubjectDetail::join('class_details', 'class_details.id', '=', 'class_subject_details.class_details_id')
                ->join('rooms','rooms.id', '=', 'class_details.room_id')
                ->join('section_details', 'section_details.id', '=', 'class_details.section_id')
                ->where('class_details.adviser_id', $FacultyInformation->id)
                ->where('class_details.school_year_id', $school_year_id)
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
    
            
            // $FacultyInformation = \App\FacultyInformation::where('user_id', \Auth::user()->id)->first();
    
            // $class_id = \App\ClassDetail::where('adviser_id', $FacultyInformation->id)->first()->id;
            
    
            
                $attendance_male = \App\Enrollment::join('student_informations', 'student_informations.id', '=', 'enrollments.student_information_id')
                ->where('class_details_id', $class_id)
                ->whereRaw('student_informations.gender = 1')
                ->select(\DB::raw("
                        enrollments.id as e_id,
                        enrollments.attendance,
                        student_informations.id,
                        CONCAT(student_informations.last_name, ' ', student_informations.first_name, ' ', student_informations.middle_name) as student_name
                    "))
                ->orderBY('student_name', 'ASC')
                ->get();
    
                $attendance_female = \App\Enrollment::join('student_informations', 'student_informations.id', '=', 'enrollments.student_information_id')
                ->where('class_details_id', $class_id)
                ->whereRaw('student_informations.gender = 2')
                ->select(\DB::raw("
                        enrollments.id as e_id,
                        enrollments.attendance,
                        student_informations.id,
                        CONCAT(student_informations.last_name, ' ', student_informations.first_name, ' ', student_informations.middle_name) as student_name
                    "))
                ->orderBY('student_name', 'ASC')
                ->get();
    
                $Senior_firstsem_m = \App\Enrollment::join('student_informations', 'student_informations.id', '=', 'enrollments.student_information_id')
                ->where('class_details_id', $class_id)
                ->whereRaw('student_informations.gender = 1')
                ->select(\DB::raw("
                        enrollments.id as e_id,
                        enrollments.attendance_first,
                        enrollments.attendance_second,
                        student_informations.id,
                        CONCAT(student_informations.last_name, ' ', student_informations.first_name, ' ', student_informations.middle_name) as student_name
                    "))
                ->orderBY('student_name', 'ASC')
                ->get();
    
                $Senior_firstsem_f = \App\Enrollment::join('student_informations', 'student_informations.id', '=', 'enrollments.student_information_id')
                ->where('class_details_id', $class_id)
                ->whereRaw('student_informations.gender = 2')
                ->select(\DB::raw("
                        enrollments.id as e_id,
                        enrollments.attendance_first,
                        enrollments.attendance_second,
                        student_informations.id,
                        CONCAT(student_informations.last_name, ' ', student_informations.first_name, ' ', student_informations.middle_name) as student_name
                    "))
                ->orderBY('student_name', 'ASC')
                ->get();
        }
        return view('control_panel_faculty.student_attendance.index',
            compact('attendance_male','attendance_female','Semester','Senior_firstsem_m','Senior_firstsem_f'))->render();
        
    }

    public function print_attendance (Request $request)
    {
        $FacultyInformation = \App\FacultyInformation::where('user_id', \Auth::user()->id)->first();

        $school_year_id = \App\SchoolYear::where('current', 1)->where('status', 1)->first()->id;

        $Semester = \App\ClassSubjectDetail::join('class_details', 'class_details.id', '=', 'class_subject_details.class_details_id')
        ->join('rooms','rooms.id', '=', 'class_details.room_id')
        ->join('section_details', 'section_details.id', '=', 'class_details.section_id')
        ->where('class_details.adviser_id', $FacultyInformation->id)
        ->where('class_details.school_year_id', $school_year_id)
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
            

        $count = $class_id = \App\ClassDetail::where('adviser_id', $FacultyInformation->id)->count();

        if ($request->ajax())
            {
               
                $attendance_male = \App\Enrollment::join('student_informations', 'student_informations.id', '=', 'enrollments.student_information_id')
                ->where('class_details_id', $class_id)
                ->whereRaw('student_informations.gender = 1')
                ->select(\DB::raw("
                        enrollments.id as e_id,
                        enrollments.attendance,
                        student_informations.id,
                        CONCAT(student_informations.last_name, ' ', student_informations.first_name, ' ', student_informations.middle_name) as student_name
                    "))
                ->orderBY('student_name', 'ASC')
                ->get();
    
                $attendance_female = \App\Enrollment::join('student_informations', 'student_informations.id', '=', 'enrollments.student_information_id')
                ->where('class_details_id', $class_id)
                ->whereRaw('student_informations.gender = 2')
                ->select(\DB::raw("
                        enrollments.id as e_id,
                        enrollments.attendance,
                        student_informations.id,
                        CONCAT(student_informations.last_name, ' ', student_informations.first_name, ' ', student_informations.middle_name) as student_name
                    "))
                ->orderBY('student_name', 'ASC')
                ->get();
    
                $Senior_firstsem_m = \App\Enrollment::join('student_informations', 'student_informations.id', '=', 'enrollments.student_information_id')
                ->where('class_details_id', $class_id)
                ->whereRaw('student_informations.gender = 1')
                ->select(\DB::raw("
                        enrollments.id as e_id,
                        enrollments.attendance_first,
                        enrollments.attendance_second,
                        student_informations.id,
                        CONCAT(student_informations.last_name, ' ', student_informations.first_name, ' ', student_informations.middle_name) as student_name
                    "))
                ->orderBY('student_name', 'ASC')
                ->get();
    
                $Senior_firstsem_f = \App\Enrollment::join('student_informations', 'student_informations.id', '=', 'enrollments.student_information_id')
                ->where('class_details_id', $class_id)
                ->whereRaw('student_informations.gender = 2')
                ->select(\DB::raw("
                        enrollments.id as e_id,
                        enrollments.attendance_first,
                        enrollments.attendance_second,
                        student_informations.id,
                        CONCAT(student_informations.last_name, ' ', student_informations.first_name, ' ', student_informations.middle_name) as student_name
                    "))
                ->orderBY('student_name', 'ASC')
                ->get();
                // return json_encode(['student_info' => $StudentInformation]);
                return view('control_panel.student_attendance.partials.data_list', compact('attendance_male','attendance_female','Semester','Senior_firstsem_m','Senior_firstsem_f','Senior_secondsem_f','Senior_secondsem_m','FacultyInformation'))->render();
            }
    

        if($count < 0)
        {
            $class_id = \App\ClassDetail::where('adviser_id', $FacultyInformation->id)->first()->id;
        }
        
        if($count > 0)
        {
            $class_id = \App\ClassDetail::where('adviser_id', $FacultyInformation->id)->first()->id;            
           
            $Semester = \App\ClassSubjectDetail::join('class_details', 'class_details.id', '=', 'class_subject_details.class_details_id')
                ->join('rooms','rooms.id', '=', 'class_details.room_id')
                ->join('section_details', 'section_details.id', '=', 'class_details.section_id')
                ->where('class_details.adviser_id', $FacultyInformation->id)
                ->where('class_details.school_year_id', $school_year_id)
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
    
            
            // $FacultyInformation = \App\FacultyInformation::where('user_id', \Auth::user()->id)->first();
    
            // $class_id = \App\ClassDetail::where('adviser_id', $FacultyInformation->id)->first()->id;
            
    
            
                $attendance_male = \App\Enrollment::join('student_informations', 'student_informations.id', '=', 'enrollments.student_information_id')
                ->where('class_details_id', $class_id)
                ->whereRaw('student_informations.gender = 1')
                ->select(\DB::raw("
                        enrollments.id as e_id,
                        enrollments.attendance,
                        student_informations.id,
                        CONCAT(student_informations.last_name, ' ', student_informations.first_name, ' ', student_informations.middle_name) as student_name
                    "))
                ->orderBY('student_name', 'ASC')
                ->get();
    
                $attendance_female = \App\Enrollment::join('student_informations', 'student_informations.id', '=', 'enrollments.student_information_id')
                ->where('class_details_id', $class_id)
                ->whereRaw('student_informations.gender = 2')
                ->select(\DB::raw("
                        enrollments.id as e_id,
                        enrollments.attendance,
                        student_informations.id,
                        CONCAT(student_informations.last_name, ' ', student_informations.first_name, ' ', student_informations.middle_name) as student_name
                    "))
                ->orderBY('student_name', 'ASC')
                ->get();
    
                $Senior_firstsem_m = \App\Enrollment::join('student_informations', 'student_informations.id', '=', 'enrollments.student_information_id')
                ->where('class_details_id', $class_id)
                ->whereRaw('student_informations.gender = 1')
                ->select(\DB::raw("
                        enrollments.id as e_id,
                        enrollments.attendance_first,
                        enrollments.attendance_second,
                        student_informations.id,
                        CONCAT(student_informations.last_name, ' ', student_informations.first_name, ' ', student_informations.middle_name) as student_name
                    "))
                ->orderBY('student_name', 'ASC')
                ->get();
    
                $Senior_firstsem_f = \App\Enrollment::join('student_informations', 'student_informations.id', '=', 'enrollments.student_information_id')
                ->where('class_details_id', $class_id)
                ->whereRaw('student_informations.gender = 2')
                ->select(\DB::raw("
                        enrollments.id as e_id,
                        enrollments.attendance_first,
                        enrollments.attendance_second,
                        student_informations.id,
                        CONCAT(student_informations.last_name, ' ', student_informations.first_name, ' ', student_informations.middle_name) as student_name
                    "))
                ->orderBY('student_name', 'ASC')
                ->get();
        
        
            
        }

        $pdf = \PDF::loadView('control_panel_faculty.student_attendance.partials.print', compact('attendance_male','attendance_female','Semester','Senior_firstsem_m','Senior_firstsem_f','FacultyInformation'));
        $pdf->setPaper('Legal', 'portrait');
        return $pdf->stream();
        
       
    }

    public function save_attendance (Request $request) 
    {
        $FacultyInformation = \App\FacultyInformation::where('user_id', \Auth::user()->id)->first();
        
        try {
            
            // $class_id = \Crypt::decrypt($request->c);
            $enrollment_id = $request->enroll_id;

            $days_of_school = [
                // 18, 22, 20, 20, 18, 19, 16, 22, 19,21,5,
                0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 
            ];
            foreach ($request->days_of_school as $i => $d)
            {
                $days_of_school[$i] = $d;
            }
            
            $days_present = [
                0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 
            ];
            foreach ($request->days_present as $i => $d)
            {
                $days_present[$i] = $d;
            }

            $days_absent = [
                0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 
            ];
            foreach ($request->days_absent as $i => $d)
            {
                $days_absent[$i] = $d;
            }

            $times_tardy = [
                0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 
            ];
            foreach ($request->times_tardy as $i => $d)
            {
                $times_tardy[$i] = $d;
            }

            $attendance_data = [
                'days_of_school' => $days_of_school,
                'days_present' => $days_present,
                'days_absent' => $days_absent,
                'times_tardy' => $times_tardy
            ];

          

            $Enrollment = \App\Enrollment::whereRaw('enrollments.id = '. $enrollment_id)->first();
            $Enrollment->attendance = json_encode($attendance_data);
            $Enrollment->save();
            // return redirect()->back()->with('flash_message_success','Attendance successfully saved!');
            return response()->json(['res_code' => 0, 'res_msg' => 'Attendance successfully saved.',]);
            // return redirect()->back();
        //    return json_encode([$request->all(), 'attendance_data' => json_encode($attendance_data), 'Enrollment' => $Enrollment]);
        }catch (Illuminate\Contracts\Encryption\DecryptException $e) {
            return "Invalid parameter";
        }
    }

    public function save_attendance_senior_first (Request $request) 
    {
        $FacultyInformation = \App\FacultyInformation::where('user_id', \Auth::user()->id)->first();
        
        try {
            
            // $class_id = \Crypt::decrypt($request->c);
            $enrollment_id = $request->enroll_id;

            $days_of_school = [
                // 18, 22, 20, 20, 18, 19, 16, 22, 19,21,5,
                0, 0, 0, 0, 0,
            ];
            foreach ($request->days_of_school as $i => $d)
            {
                $days_of_school[$i] = $d;
            }
            
            $days_present = [
                0, 0, 0, 0, 0,
            ];
            foreach ($request->days_present as $i => $d)
            {
                $days_present[$i] = $d;
            }

            $days_absent = [
                0, 0, 0, 0, 0,
            ];
            foreach ($request->days_absent as $i => $d)
            {
                $days_absent[$i] = $d;
            }

            $times_tardy = [
                0, 0, 0, 0, 0,
            ];
            foreach ($request->times_tardy as $i => $d)
            {
                $times_tardy[$i] = $d;
            }

            $attendance_data = [
                'days_of_school' => $days_of_school,
                'days_present' => $days_present,
                'days_absent' => $days_absent,
                'times_tardy' => $times_tardy
            ];

          

            $Enrollment = \App\Enrollment::whereRaw('enrollments.id = '. $enrollment_id)->first();
            $Enrollment->attendance_first = json_encode($attendance_data);
            $Enrollment->save();
            // return redirect()->back()->with('flash_message_success','Attendance successfully saved!');
            return response()->json(['res_code' => 0, 'res_msg' => 'Attendance for 1st Semester is successfully saved.',]);
            // return redirect()->back();
        //    return json_encode([$request->all(), 'attendance_data' => json_encode($attendance_data), 'Enrollment' => $Enrollment]);
        }catch (Illuminate\Contracts\Encryption\DecryptException $e) {
            return "Invalid parameter";
        }
    }

    public function save_attendance_senior_second (Request $request) 
    {
        $FacultyInformation = \App\FacultyInformation::where('user_id', \Auth::user()->id)->first();
        
        try {
            
            // $class_id = \Crypt::decrypt($request->c);
            $enrollment_id = $request->enroll_id;

            $days_of_school = [
                // 18, 22, 20, 20, 18, 19, 16, 22, 19,21,5,
                0, 0, 0, 0, 0, 0,
            ];
            foreach ($request->days_of_school as $i => $d)
            {
                $days_of_school[$i] = $d;
            }
            
            $days_present = [
                0, 0, 0, 0, 0, 0,
            ];
            foreach ($request->days_present as $i => $d)
            {
                $days_present[$i] = $d;
            }

            $days_absent = [
                0, 0, 0, 0, 0, 0, 
            ];
            foreach ($request->days_absent as $i => $d)
            {
                $days_absent[$i] = $d;
            }

            $times_tardy = [
                0, 0, 0, 0, 0, 0,
            ];
            foreach ($request->times_tardy as $i => $d)
            {
                $times_tardy[$i] = $d;
            }

            $attendance_data = [
                'days_of_school' => $days_of_school,
                'days_present' => $days_present,
                'days_absent' => $days_absent,
                'times_tardy' => $times_tardy
            ];

          

            $Enrollment = \App\Enrollment::whereRaw('enrollments.id = '. $enrollment_id)->first();
            $Enrollment->attendance_second = json_encode($attendance_data);
            $Enrollment->save();
            // return redirect()->back()->with('flash_message_success','Attendance successfully saved!');
            return response()->json(['res_code' => 0, 'res_msg' => 'Attendance for 2nd Semester is successfully saved.',]);
            // return redirect()->back();
        //    return json_encode([$request->all(), 'attendance_data' => json_encode($attendance_data), 'Enrollment' => $Enrollment]);
        }catch (Illuminate\Contracts\Encryption\DecryptException $e) {
            return "Invalid parameter";
        }
    }
}
