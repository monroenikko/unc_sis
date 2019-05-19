<?php

namespace App\Http\Controllers\Control_Panel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StudentController extends Controller
{
    public function index (Request $request) 
    {
        if ($request->ajax())
        {
            $StudentInformation = \App\StudentInformation::with(['user', 'enrolled_class'])->where('status', 1)
            ->orderBY('last_name', 'ASC')
            ->where(function ($query) use ($request) {
                $query->where('first_name', 'like', '%'.$request->search.'%');
                $query->orWhere('middle_name', 'like', '%'.$request->search.'%');
                $query->orWhere('last_name', 'like', '%'.$request->search.'%');
            })
            // ->orWhere('first_name', 'like', '%'.$request->search.'%')
            ->paginate(10);
            // return json_encode(['student_info' => $StudentInformation]);
            return view('control_panel.student_information.partials.data_list', compact('StudentInformation'))->render();
        }
        $StudentInformation = \App\StudentInformation::with(['user', 'enrolled_class'])->where('status', 1)->orderBY('last_name', 'ASC')->paginate(10);
        // return json_encode(['student_info' => $StudentInformation]);
        return view('control_panel.student_information.index', compact('StudentInformation'));
    }

    public function modal_data (Request $request) 
    {
        $StudentInformation = NULL;
        $Profile = \App\StudentInformation::where('id', $request->id)->first(); 
        if ($request->id)
        {
            $StudentInformation = \App\StudentInformation::with(['user'])->where('id', $request->id)->first();   
            // $Profile = \App\StudentInformation::where('id', $request->id)->first();   
            // return view('control_panel.student_information.partials.modal_data', compact('StudentInformation','Profile'))->render(); 
            // return view('control_panel.student_information.partials.modal_data', compact('StudentInformation'))->render()        
        }

        return view('control_panel.student_information.partials.modal_data', compact('StudentInformation','Profile'))->render();  
    	// return view('profile', array('user' => Auth::user()) );
        
    }

 

    public function change_my_photo (Request $request)
    {
        
        $name = time().'.'.$request->user_photo->getClientOriginalExtension();
        $destinationPath = public_path('/img/account/photo/');
        $request->user_photo->move($destinationPath, $name);



    //    / $User = \Auth::user();
        if($request->id)
        {
            $Profile = \App\StudentInformation::where('id', $request->id)->first();

            if ($Profile->photo) 
            {
                $delete_photo = public_path('/img/account/photo/'. $Profile->photo);
                if (\File::exists($delete_photo)) 
                {
                    \File::delete($delete_photo);
                }
            }
    
            $Profile->photo = $name;
    
            if ($Profile->save())
            {
                return response()->json(['res_code' => 0, 'res_msg' => 'User photo successfully updated.']);
            }
            else 
            {
                return response()->json(['res_code' => 1, 'res_msg' => 'Error in saving photo']);
            }
            
            return json_encode($request->all());
        }
        
    }

    public function save_data (Request $request) 
    {
        $rules = [
            'username' => 'required',
            'first_name' => 'required',
            'middle_name' => 'required',
            'last_name' => 'required',
            // 'guardian' => 'required',
            // 'age_june' => 'required',
            // 'age_may' => 'required',
            
            // 'address'   => 'required',
            // 'birthdate' => 'required',
            'gender'    => 'required',
        ];
        
        
        $Validator = \Validator($request->all(), $rules);

        if ($Validator->fails())
        {
            return response()->json(['res_code' => 1, 'res_msg' => 'Please fill all required fields.', 'res_error_msg' => $Validator->getMessageBag()]);
        }

        if ($request->id)
        {
            $StudentInformation = \App\StudentInformation::where('id', $request->id)->first();
            
            $User = \App\User::where('username', $request->username)->where('id', '!=', $StudentInformation->user_id)->first();
            if ($User) 
            {
                return response()->json(['res_code' => 1,'res_msg' => 'Username already used.']);
            }
            $User = \App\User::where('id', $StudentInformation->user_id)->first();
            $User->username = $request->username;
            $User->save();

            $StudentInformation->first_name     = $request->first_name;
            $StudentInformation->middle_name    = $request->middle_name;
            $StudentInformation->last_name      = $request->last_name;
            $StudentInformation->c_address      = $request->address;
            $StudentInformation->age_june = $request->age_june;
            $StudentInformation->age_may = $request->age_may;
            $StudentInformation->birthdate      = $request->birthdate ? date('Y-m-d', strtotime($request->birthdate)) : NULL;
            $StudentInformation->gender         = $request->gender;
            $StudentInformation->guardian       = $request->guardian;
            $StudentInformation->save();
            return response()->json(['res_code' => 0, 'res_msg' => 'Data successfully saved.']);
        }

        $User = \App\User::where('username', $request->username)->first();
        if ($User) 
        {
            return response()->json(['res_code' => 1,'res_msg' => 'Username already used.']);
        }
        $User = new \App\User();
        $User->username = $request->username;
        $User->password = bcrypt($request->first_name . '.' . $request->last_name);
        $User->role     = 5;
        $User->save();

        $StudentInformation                 = new \App\StudentInformation();
        $StudentInformation->first_name     = $request->first_name;
        $StudentInformation->middle_name    = $request->middle_name;
        $StudentInformation->last_name      = $request->last_name;
        $StudentInformation->address        = $request->address;
        $StudentInformation->age_june = $request->age_june;
        $StudentInformation->age_may = $request->age_may;
            // $StudentInformation->birthdate      = date('Y-m-d', strtotime($request->birthdate));
        $StudentInformation->gender         = $request->gender;
        $StudentInformation->guardian         = $request->guardian;
        $StudentInformation->user_id        = $User->id;
        $StudentInformation->save();
        
        return response()->json(['res_code' => 0, 'res_msg' => 'Data successfully saved.']);
    }
    public function deactivate_data (Request $request) 
    {
        $StudentInformation = \App\StudentInformation::where('id', $request->id)->first();

        if ($StudentInformation)
        {
            $StudentInformation->status = 0;
            $StudentInformation->save();

            $User = \App\User::where('id', $StudentInformation->user_id)->first();
            if ($User)
            {
                $User->status = 0;
                $User->save();
            }
            return response()->json(['res_code' => 0, 'res_msg' => 'Data successfully deactivated.']);
        }
        return response()->json(['res_code' => 1, 'res_msg' => 'Invalid request.']);
    }

    public function print_student_grade_modal (Request $request) 
    {
        $Enrollment = \App\Enrollment::where('student_information_id', $request->id)
            ->join('class_details','class_details.id', '=','enrollments.class_details_id')
            ->join('school_years','school_years.id', '=','class_details.school_year_id')
            ->selectRaw('
                enrollments.id AS e_id,
                class_details.id AS c_id,
                school_years.id AS sy_id,
                enrollments.student_information_id AS student_id,
                school_years.school_year AS sy
                ')
            ->get();
        $student_id = $request->id;
        return view('control_panel.student_information.partials.print_individual_grade', compact('Enrollment', 'student_id'));
    }
    
    public function print_student_grades (Request $request)
    {
        if (!$request->id || !$request->cid) 
        {
            return "Invalid request";
        }

        $StudentInformation = \App\StudentInformation::with(['user'])->where('id', $request->id)->first();
        // $StudentInformation = \App\StudentInformation::with('user')->where('id', $request->id)->first();
        // $SchoolYear = \App\SchoolYear::where('current', $request->cid)->first();
        // // return json_encode(['xx'=> $request->all(), 's' => $StudentInformation]);

        if ($StudentInformation) 
        {
            $ClassDetail = \App\ClassDetail::join('section_details', 'section_details.id', '=' ,'class_details.section_id')
            ->join('rooms', 'rooms.id', '=' ,'class_details.room_id')
            ->join('school_years', 'school_years.id', '=' ,'class_details.school_year_id')
            ->join('faculty_informations', 'faculty_informations.id','=','class_details.adviser_id')
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
                rooms.room_description,
                faculty_informations.first_name, faculty_informations.middle_name ,  faculty_informations.last_name,
                faculty_informations.e_signature
            ')
            ->where('section_details.status', 1)
            // ->where('school_years.current', 1)
            ->where('class_details.id', $request->cid)
            ->orderBY('school_years.id', 'ASC')
            ->first();

            $Signatory = \App\Enrollment::join('class_details', 'class_details.id', '=', 'enrollments.class_details_id')
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
            ->where('class_details.school_year_id', $ClassDetail->school_year_id)
            ->select(\DB::raw("
                enrollments.id as enrollment_id,
                enrollments.attendance,
                class_details.grade_level,
                class_subject_details.id as class_subject_details_id,
                class_subject_details.class_days,
                class_subject_details.class_time_from,
                class_subject_details.class_time_to,
                class_subject_details.status as grade_status,
                faculty_informations.last_name,faculty_informations.first_name,faculty_informations.middle_name,
                faculty_informations.e_signature,
                subject_details.id AS subject_id,
                subject_details.subject_code,
                subject_details.subject,
                rooms.room_code,
                section_details.section,
                class_details.school_year_id as school_year_id
            "))
            ->orderBy('class_subject_details.class_subject_order', 'ASC')
            ->first();
            
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
            ->where('class_details.school_year_id', $ClassDetail->school_year_id)
            ->select(\DB::raw("
                enrollments.id as enrollment_id,
                enrollments.attendance,
                class_details.grade_level,
                class_subject_details.id as class_subject_details_id,
                class_subject_details.class_days,
                class_subject_details.class_time_from,
                class_subject_details.class_time_to,
                class_subject_details.status as grade_status,
                CONCAT(faculty_informations.last_name, ', ', faculty_informations.first_name, ' ', faculty_informations.middle_name) as faculty_name,
                faculty_informations.e_signature,
                subject_details.id AS subject_id,
                subject_details.subject_code,
                subject_details.subject,
                rooms.room_code,
                section_details.section,
                class_details.school_year_id as school_year_id
            "))
            ->orderBy('class_subject_details.class_subject_order', 'ASC')
            ->get();
            $GradeSheetData = [];
            $grade_level = 1;
            $sub_total = 0;
            $general_avg = 0;
            $subj_count = 0;
            $grade_status = $Enrollment[0]->grade_status;
            $attendance_data = json_decode(json_encode([
                'days_of_school' => [
                    0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 
                ],
                'days_present' => [
                    0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 
                ],
                'days_absent' => [
                    0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 
                ],
                'times_tardy' => [
                    0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 
                ]
            ]));

            // return json_encode(['c' => count($Enrollment),'Enrollment' => $Enrollment,'StudentInformation' => $StudentInformation, ]);
            if ($StudentInformation && count($Enrollment)>0)
            {
                $StudentEnrolledSubject = \App\StudentEnrolledSubject::where('enrollments_id', $Enrollment[0]->enrollment_id)
                ->get();
                $grade_level = $Enrollment[0]->grade_level;
                if ($Enrollment[0]->attendance) {
                    $attendance_data = json_decode($Enrollment[0]->attendance);
                }
                // return json_encode(['a' => $StudentEnrolledSubject->count(), 'b' => $Enrollment->count(), 'StudentEnrolledSubject'=> $StudentEnrolledSubject, 'Enrollment' => $Enrollment]);
                $GradeSheetData = $Enrollment->map(function ($item, $key) use ($StudentEnrolledSubject, $grade_level, $grade_status) {
                    // $grade = $StudentEnrolledSubject->firstWhere('subject_id', $item->subject_id);
                    $grade = $StudentEnrolledSubject->firstWhere('class_subject_details_id', $item->class_subject_details_id);
                    $sum = 0;
                    $first = $grade->fir_g > 0 ? $grade->fir_g : 0;
                    $second = $grade->sec_g > 0 ? $grade->sec_g : 0;
                    $third = $grade->thi_g > 0 ? $grade->thi_g : 0;
                    $fourth = $grade->fou_g > 0 ? $grade->fou_g : 0;
                    // $third = 0;
                    // $fourth = 0;
                    // if ($grade_level <= 11)
                    // {
                    //     $third = $grade->thi_g > 0 ? $grade->thi_g : 0;
                    //     $fourth = $grade->fou_g > 0 ? $grade->fou_g : 0;
                    // }
                    
                    $sum += $grade->fir_g > 0 ? $grade->fir_g : 0;
                    $sum += $grade->sec_g > 0 ? $grade->sec_g : 0;
                    $sum += $grade->thi_g > 0 ? $grade->thi_g : 0;
                    $sum += $grade->fou_g > 0 ? $grade->fou_g : 0;

                    // if ($grade_level <= 11)
                    // {
                    //     $sum += $grade->thi_g > 0 ? $grade->thi_g : 0;
                    //     $sum += $grade->fou_g > 0 ? $grade->fou_g : 0;
                    // }

                    $divisor = 0;
                    $divisor += $first > 0 ? 1 : 0;
                    $divisor += $second > 0 ? 1 : 0;
                    $divisor += $third > 0 ? 1 : 0;
                    $divisor += $fourth > 0 ? 1 : 0;

                    $final = 0;
                    if ($divisor != 0) 
                    {
                        $final = $sum / $divisor;
                    }
                    
                    $data = [
                        'enrollment_id'     =>  $item->enrollment_id,
                        'grade_level'       =>  $item->grade_level,
                        'class_days'        =>  $item->class_days,
                        'class_time_from'   =>  $item->class_time_from,
                        'class_time_to'     =>  $item->class_time_to,
                        'faculty_name'      =>  $item->faculty_name,
                        'subject_id'        =>  $item->subject_id,
                        'subject_code'      =>  $item->subject_code,
                        'subject'           =>  $item->subject,
                        'room_code'         =>  $item->room_code,
                        'section'           =>  $item->section,
                        'grade_id'          =>  $grade->id,
                        'fir_g'             =>  $grade->fir_g,
                        'sec_g'             =>  $grade->sec_g,
                        'thi_g'             =>  $grade->thi_g,
                        'fou_g'             =>  $grade->fou_g,
                        'final_g'           =>  round($final),
                        'grade_status'      =>  $grade_status,
                        'divisor' => $divisor
                    ];
                    return $data;
                });
                for ($i=0; $i<count($GradeSheetData); $i++)
                {
                    if ($GradeSheetData[$i]['final_g'] > 0) // && $GradeSheetData[$i]['grade_status'] == 2) 
                    {
                        $subj_count++;
                        $sub_total +=  $GradeSheetData[$i]['final_g'];
                    }
                }
                if ($subj_count > 0) 
                {
                    $general_avg = $sub_total / $subj_count;
                }
            }
            $GradeSheetData = json_decode(json_encode($GradeSheetData));
            $table_header = [
                ['key' => 'Jun',],
                ['key' => 'Jul',],
                ['key' => 'Aug',],
                ['key' => 'Sep',],
                ['key' => 'Oct',],
                ['key' => 'Nov',],
                ['key' => 'Dec',],
                ['key' => 'Jan',],
                ['key' => 'Feb',],
                ['key' => 'Mar',],
                ['key' => 'Apr',],
                ['key' => 'total',],
            ];
            
            $student_attendance = [
                'attendance_data'   => $attendance_data,
                'table_header'      => $table_header,
                'days_of_school_total' => array_sum($attendance_data->days_of_school),
                'days_present_total' => array_sum($attendance_data->days_present),
                'days_absent_total' => array_sum($attendance_data->days_absent),
                'times_tardy_total' => array_sum($attendance_data->times_tardy),
            ];
            // return json_encode(['a' => $GradeSheetData, 'subj_count' => $subj_count, 'general_avg' => $general_avg]);
            return view('control_panel_student.grade_sheet.partials.print', compact('GradeSheetData', 'grade_level', 'StudentInformation', 'ClassDetail', 'general_avg', 'student_attendance', 'table_header','Signatory'));
            $pdf = \PDF::loadView('control_panel_student.grade_sheet.partials.print', compact('GradeSheetData', 'grade_level', 'StudentInformation', 'ClassDetail','Signatory'));
            // $pdf->setPaper('Letter', 'landscape');
            return $pdf->stream();
            return view('control_panel_student.grade_sheet.index', compact('GradeSheetData'));
            return json_encode(['GradeSheetData' => $GradeSheetData,]);
        }
        else {
            return "Invalid request";
        }
    }
}
