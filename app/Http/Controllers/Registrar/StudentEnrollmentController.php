<?php

namespace App\Http\Controllers\Registrar;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StudentEnrollmentController extends Controller
{
    public function index (Request $request, $id) 
    {
        // echo json_encode($request->user()->get_user_role('admin'), $request->user()->role);
        // return;
        
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
                school_years.id AS sy_id,
                school_years.school_year,
                rooms.room_code,
                rooms.room_description
            ')
            ->where('section_details.status', 1)
            // ->where('school_years.current', 1)
            ->where('class_details.id', $id)
            ->first();
        if ($request->ajax())
        {
            if (!$request->search_fn &&
                !$request->search_mn &&
                !$request->search_ln &&
                !$request->search_student_id) 
            {
                $StudentInformation = [];
                $Semester = \App\Semester::where('current', 1)->first(); 
                return view('control_panel_registrar.student_enrollment.partials.data_list', compact('StudentInformation','Semester'))->render();
            }

            $StudentInformation = \App\StudentInformation::with(['user'])
            ->join('users', 'users.id', '=', 'student_informations.user_id')
            // ->leftJoin('enrollments', 'enrollments.student_information_id', '=', 'student_informations.id')
            // ->leftJoin('class_details', 'class_details.id', '=', 'enrollments.class_details_id')
            // ->leftJoin('school_years', 'school_years.id', '=', 'class_details.school_year_id')
            ->selectRaw("
                student_informations.id,
                users.username,
                CONCAT(student_informations.last_name, ' ', student_informations.first_name, ', ', student_informations.middle_name) AS fullname
            ")
            ->where(function ($query) use ($request) {
                if ($request->search_fn)
                {
                    $query->where('first_name', 'like', '%'.$request->search_fn.'%');
                }

                if ($request->search_mn)
                {
                    $query->where('middle_name', 'like', '%'.$request->search_mn.'%');
                }

                if ($request->search_ln)
                {
                    $query->where('last_name', 'like', '%'.$request->search_ln.'%');
                }

                if ($request->search_student_id)
                {
                    $query->where('users.username', 'like', '%'.$request->search_student_id.'%');
                }
            })
            ->where(function ($query) use ($id) {
                // $query->whereRaw('enrollments.class_details_id IS NULL OR enrollments.class_details_id != '.$id);
                // $query->whereRaw('enrollments.class_details_id IS NULL AND enrollments.class_details_id != '.$id);
            })
            // ->where(function ($query) use ($id, $ClassDetail) {
            //     $query->whereRaw('
            //             enrollments.id IS NULL 
            //         OR 
            //             enrollments.class_details_id != '.$id
            //     );
            // })
            // ->where(function ($query) use ($id, $ClassDetail) {
            //     $query->whereRaw('
            //             (SELECT 
            //                 class_details_id 
            //             FROM
            //                 enrollments
            //             WHERE 
            //                 (
            //                     enrollments.student_information_id = student_informations.id
            //                 )
            //             ) = '. $id
            //     );
            // })
            ->orderByRaw('fullname')
            ->paginate(10);
            $Semester = \App\Semester::where('current', 1)->first(); 
            // return json_encode(['s' => $StudentInformation, 'req' => $request->all(), 'class_details_id' => $id]);
            return view('control_panel_registrar.student_enrollment.partials.data_list', compact('StudentInformation','Semester'))->render();
        }
        // $StudentInformation = \App\StudentInformation::with(['user'])->where('status', 1)->paginate(10);
        $StudentInformation = [];

        $Enrollment = \App\Enrollment::join('student_informations', 'student_informations.id', '=', 'enrollments.student_information_id')
            ->join('users', 'users.id', '=', 'student_informations.user_id')
            ->where(function ($query) use ($request) {
                if ($request->search_fn)
                {
                    $query->where('student_informations.first_name', 'like', '%'.$request->search_fn.'%');
                }

                if ($request->search_mn)
                {
                    $query->where('student_informations.middle_name', 'like', '%'.$request->search_mn.'%');
                }

                if ($request->search_ln)
                {
                    $query->where('student_informations.last_name', 'like', '%'.$request->search_ln.'%');
                }

                if ($request->search_student_id)
                {
                    $query->where('users.username', 'like', '%'.$request->search_student_id.'%');
                }
            })
            ->selectRaw("
                student_informations.id AS student_information_id,
                users.username,
                CONCAT(student_informations.last_name, ' ', student_informations.first_name, ', ', student_informations.middle_name) AS fullname,
                enrollments.id AS enrollment_id
            ")
            ->where('class_details_id', $id)
            ->orderByRaw('fullname')
            ->paginate(70);

        $Enrollment_ids = '';
        foreach($Enrollment as $data)
        {
            $Enrollment_ids .= $data->enrollment_id . '@';
        }
        $Semester = \App\Semester::where('current', 1)->first();
        
        return view('control_panel_registrar.student_enrollment.index', compact('StudentInformation', 'ClassDetail', 'id', 'Enrollment', 'Enrollment_ids','Semester'));
    }

    public function fetch_enrolled_student (Request $request, $id)
    {
        $Enrollment = \App\Enrollment::join('student_informations', 'student_informations.id', '=', 'enrollments.student_information_id')
            ->join('users', 'users.id', '=', 'student_informations.user_id')
            ->where(function ($query) use ($request) {
                if ($request->search_fn)
                {
                    $query->where('student_informations.first_name', 'like', '%'.$request->search_fn.'%');
                }

                if ($request->search_mn)
                {
                    $query->where('student_informations.middle_name', 'like', '%'.$request->search_mn.'%');
                }

                if ($request->search_ln)
                {
                    $query->where('student_informations.last_name', 'like', '%'.$request->search_ln.'%');
                }

                if ($request->search_student_id)
                {
                    $query->where('users.username', 'like', '%'.$request->search_student_id.'%');
                }
            })
            // ->whereRaw('student_informations.id NOT IN ((SELECT  * from enrollments where enrollments.class_details_id = 3))')
            ->selectRaw("
                student_informations.id AS student_information_id,
                users.username,
                CONCAT(student_informations.last_name, ' ', student_informations.first_name, ', ', student_informations.middle_name) AS fullname,
                enrollments.id AS enrollment_id
            ")
            ->where('class_details_id', $id)
            ->orderByRaw('fullname')
            // ->orWhere('first_name', 'like', '%'.$request->search.'%')
            ->paginate(70); //

            // return json_encode($StudentInformation);
            $Semester = \App\Semester::where('current', 1)->first();

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
                school_years.id AS sy_id,
                school_years.school_year,
                rooms.room_code,
                rooms.room_description
            ')
            ->where('section_details.status', 1)
            // ->where('school_years.current', 1)
            ->where('class_details.id', $id)
            ->first();
            
        return view('control_panel_registrar.student_enrollment.partials.data_list_enrolled', compact('Enrollment','Semester','ClassDetail'))->render();
    }
    public function modal_data (Request $request) 
    {
        $StudentInformation = NULL;
        if ($request->id)
        {
            $StudentInformation = \App\StudentInformation::with(['user'])->where('id', $request->id)->first();
        }
        return view('control_panel_registrar.student_enrollment.partials.modal_data', compact('StudentInformation'))->render();
    }

    public function save_data (Request $request) 
    {
        $rules = [
            'username' => 'required',
            'first_name' => 'required',
            'middle_name' => 'required',
            'last_name' => 'required',
            'department' => 'required',
            'email' => 'required|unique:users,username',
        ];
        
        $Validator = \Validator($request->all(), $rules);

        if ($Validator->fails())
        {
            return response()->json(['res_code' => 1, 'res_msg' => 'Please fill all required fields.', 'res_error_msg' => $Validator->getMessageBag()]);
        }

        if ($request->id)
        {
            $StudentInformation = \App\StudentInformation::where('id', $request->id)->first();
            $StudentInformation->first_name = $request->first_name;
            $StudentInformation->middle_name = $request->middle_name;
            $StudentInformation->last_name = $request->last_name;
            $StudentInformation->department_id = $request->department;
            $StudentInformation->save();
            return response()->json(['res_code' => 0, 'res_msg' => 'Data successfully saved.']);
        }

        $User = new \App\User();
        $User->username = $request->username;
        $User->password = bcrypt($request->first_name . '.' . $request->last_name);
        $User->role     = 4;
        $User->save();

        $StudentInformation = new \App\StudentInformation();
        $StudentInformation->first_name = $request->first_name;
        $StudentInformation->middle_name = $request->middle_name;
        $StudentInformation->last_name = $request->last_name;
        $StudentInformation->department_id = $request->department;
        $StudentInformation->user_id = $User->id;
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

    public function enroll_student (Request $request, $id) 
    {
        $StudentInformation = \App\StudentInformation::where('id', $request->student_id)->first();
        $ClassDetail = \App\ClassDetail::with('class_subjects')->where('id', $id)->first();

        // $ClassDetail = \App\ClassDetail::with('class_subjects')->get();
        // return response()->json(['res_code' => 1, 'res_msg' => 'There is a problem in enrolling student.', 'ClassDetail' => $ClassDetail]);
        $ClassDetail1 = \App\ClassDetail::join('section_details', 'section_details.id', '=' ,'class_details.section_id')
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
            school_years.id AS sy_id,
            school_years.school_year,
            rooms.room_code,
            rooms.room_description
        ')
        ->where('section_details.status', 1)
        // ->where('school_years.current', 1)
        ->where('class_details.id', $ClassDetail->id)
        ->first();

        $Enrollment = new \App\Enrollment();
        $Enrollment->student_information_id = $StudentInformation->id;
        $Enrollment->class_details_id = $ClassDetail->id;
        
        if ($Enrollment->save())
        {
            if ($ClassDetail->class_subjects) 
            {
                foreach ($ClassDetail->class_subjects as $data) 
                {
                   

                    if($ClassDetail1->grade_level == 11 || $ClassDetail1->grade_level == 12)
                    {
                        $StudentEnrolledSubject = new \App\StudentEnrolledSubject();
                        $StudentEnrolledSubject->subject_id = $data->subject_id;
                        $StudentEnrolledSubject->enrollments_id = $Enrollment->id;
                        $StudentEnrolledSubject->class_subject_details_id = $data->id;
                        // $StudentEnrolledSubject->student_information_id = $StudentInformation->id;
                        $StudentEnrolledSubject->save();
                        
                        $SaveSecondSem2 = new \App\Grade_sheet_firstsem();
                        $SaveSecondSem2->enrollment_id = $Enrollment->id;
                        $SaveSecondSem2->section_details_id = $ClassDetail1->section_id;
                        $SaveSecondSem2->save();
    
                        $SaveSecondSem2 = new \App\Grade_sheet_firstsemsecond();
                        $SaveSecondSem2->enrollment_id = $Enrollment->id;
                        $SaveSecondSem2->section_details_id = $ClassDetail1->section_id;
                        $SaveSecondSem2->save();  
                    }
                    else
                    {
                        $StudentEnrolledSubject = new \App\StudentEnrolledSubject();
                        $StudentEnrolledSubject->subject_id = $data->subject_id;
                        $StudentEnrolledSubject->enrollments_id = $Enrollment->id;
                        $StudentEnrolledSubject->class_subject_details_id = $data->id;
                        // $StudentEnrolledSubject->student_information_id = $StudentInformation->id;
                        $StudentEnrolledSubject->save();

                        $SaveToGradeSheet1 = new \App\Grade_sheet_first();
                        $SaveToGradeSheet1->enrollment_id = $Enrollment->id;
                        $SaveToGradeSheet1->section_details_id = $ClassDetail1->section_id;
                        $SaveToGradeSheet1->save();

                        $SaveToGradeSheet2 = new \App\Grade_sheet_second();
                        $SaveToGradeSheet2->enrollment_id = $Enrollment->id;
                        $SaveToGradeSheet2->section_details_id = $ClassDetail1->section_id;
                        $SaveToGradeSheet2->save();

                        $SaveToGradeSheet3 = new \App\Grade_sheet_third();
                        $SaveToGradeSheet3->enrollment_id = $Enrollment->id;
                        $SaveToGradeSheet3->section_details_id = $ClassDetail1->section_id;
                        $SaveToGradeSheet3->save();

                        $SaveToGradeSheet4 = new \App\Grade_sheet_fourth();
                        $SaveToGradeSheet4->enrollment_id = $Enrollment->id;
                        $SaveToGradeSheet4->section_details_id = $ClassDetail1->section_id;
                        $SaveToGradeSheet4->save();
                    }                                   

                    
                }
            }
            return response()->json(['res_code' => 0, 
                'res_msg' => 'Student successfully enrolled.', 
                'StudentInformation' => $StudentInformation, 
                'ClassDetail' => $ClassDetail,
                'Enrollment' => $Enrollment,
            ]);
        }

        return response()->json(['res_code' => 1, 'res_msg' => 'There is a problem in enrolling student.', 'Enrollment' => $Enrollment]);
    }
    public function re_enroll_student (Request $request, $id)
    {
        $ClassDetail = \App\ClassDetail::with('class_subjects')->where('id', $id)->first();

        $ClassDetail1 = \App\ClassDetail::join('section_details', 'section_details.id', '=' ,'class_details.section_id')
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
                school_years.id AS sy_id,
                school_years.school_year,
                rooms.room_code,
                rooms.room_description
            ')
            ->where('section_details.status', 1)
            // ->where('school_years.current', 1)
            ->where('class_details.id', $request->class_detail_id)
            ->first();


            $SaveSecondSem = new \App\Grade11_Second_Sem();
            $SaveSecondSem->enrollment_id = $request->enrollment_id;
            $SaveSecondSem->section_details_id = $ClassDetail1->section_id;
            $SaveSecondSem->save();

            $SaveSecondSem2 = new \App\Grade_sheet_secondsemsecond();
            $SaveSecondSem2->enrollment_id = $request->enrollment_id;
            $SaveSecondSem2->section_details_id = $ClassDetail1->section_id;
            $SaveSecondSem2->save();

            
        $StudentEnrolledSubject_list = [];
        if ($ClassDetail->class_subjects)
        {
            $StudentEnrolledSubject = \App\StudentEnrolledSubject::where('enrollments_id', $request->enrollment_id)
                // ->where('subject_id', $class_subject->subject_id)
                ->get();
                
            // return json_encode(['ClassDetail' => $ClassDetail, 'StudentEnrolledSubject' => $StudentEnrolledSubject, 'class_subjects' => $ClassDetail->class_subjects]);
            foreach ($ClassDetail->class_subjects as $key => $class_subject)
            {
                
                $StudentEnrolledSubject = \App\StudentEnrolledSubject::where('enrollments_id', $request->enrollment_id)
                ->where('class_subject_details_id', $class_subject->id)
                ->first();
                
                if ($StudentEnrolledSubject) {
                    $StudentEnrolledSubject_list[] = $StudentEnrolledSubject;
                } 
                else 
                {
                    $newStudentEnrolledSubject = new \App\StudentEnrolledSubject();
                    $newStudentEnrolledSubject->class_subject_details_id = $class_subject->id;
                    $newStudentEnrolledSubject->subject_id = $class_subject->subject_id;
                    $newStudentEnrolledSubject->enrollments_id = $request->enrollment_id;
                    $newStudentEnrolledSubject->sem = 2;
                    $newStudentEnrolledSubject->save();
                    $StudentEnrolledSubject_list[] = $newStudentEnrolledSubject;
                    
                }
                // if ($StudentEnrolledSubject[$key]) 
                // {
                //     $StudentEnrolledSubject_tmp = $StudentEnrolledSubject[$key];
                //     $StudentEnrolledSubject_tmp->class_subject_details_id = $class_subject->id;
                //     $StudentEnrolledSubject_tmp->subject_id = $class_subject->subject_id;
                //     $StudentEnrolledSubject_tmp->save();
                //     $StudentEnrolledSubject_list[] = $StudentEnrolledSubject_tmp;
                // }

                // $StudentEnrolledSubject = \App\StudentEnrolledSubject::where('enrollments_id', $request->enrollment_id)
                //     ->where('subject_id', $class_subject->subject_id)
                //     ->first();
                // $StudentEnrolledSubject->class_subject_details_id = $class_subject->id;
                // $StudentEnrolledSubject->save();
                // $StudentEnrolledSubject_list[] = $StudentEnrolledSubject;
            }
            
            // return json_encode(['ClassDetail' => $ClassDetail, 'StudentEnrolledSubject_list' => $StudentEnrolledSubject_list, 'StudentEnrolledSubject' => $StudentEnrolledSubject, 'class_subjects' => $ClassDetail->class_subjects]);
            return response()->json(['res_code' => 0, 'res_msg' => 'Successfully re-enrolled.']);
            
        }
        return response()->json(['res_code' => 1, 'res_msg' => 'Unable to perform action.']);

    }
    public function re_enroll_student_all (Request $request, $id)
    {
        $ClassDetail = \App\ClassDetail::with('class_subjects')->where('id', $id)->first();

        $ClassDetail1 = \App\ClassDetail::join('section_details', 'section_details.id', '=' ,'class_details.section_id')
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
                school_years.id AS sy_id,
                school_years.school_year,
                rooms.room_code,
                rooms.room_description
            ')
            ->where('section_details.status', 1)
            // ->where('school_years.current', 1)
            ->where('class_details.id', $request->class_detail_id)
            ->first();


            


        $enrollment_ids = explode('@', $request->enrollment_ids);
        array_pop($enrollment_ids);

        $StudentEnrolledSubject_list = [];
        if ($ClassDetail->class_subjects)
        {
            foreach($enrollment_ids as $enrollment_id)
            {
                $SaveSecondSem = new \App\Grade11_Second_Sem();
                $SaveSecondSem->enrollment_id = $enrollment_id;
                $SaveSecondSem->section_details_id = $ClassDetail1->section_id;
                $SaveSecondSem->save();

                $SaveSecondSem2 = new \App\Grade_sheet_secondsemsecond();
                $SaveSecondSem2->enrollment_id = $enrollment_id;
                $SaveSecondSem2->section_details_id = $ClassDetail1->section_id;
                $SaveSecondSem2->save();
                // $StudentEnrolledSubject = \App\StudentEnrolledSubject::where('enrollments_id', $enrollment_id)->get();
                foreach ($ClassDetail->class_subjects as $key => $class_subject)
                {
                    $StudentEnrolledSubject = \App\StudentEnrolledSubject::where('enrollments_id', $enrollment_id)
                    ->where('class_subject_details_id', $class_subject->id)
                    ->first();
                    
                    
                    if ($StudentEnrolledSubject) {
                        $StudentEnrolledSubject_list[] = $StudentEnrolledSubject;
                    } 
                    else 
                    {

                        
                        $newStudentEnrolledSubject = new \App\StudentEnrolledSubject();
                        $newStudentEnrolledSubject->class_subject_details_id = $class_subject->id;
                        $newStudentEnrolledSubject->subject_id = $class_subject->subject_id;
                        $newStudentEnrolledSubject->enrollments_id = $enrollment_id;
                        $newStudentEnrolledSubject->sem = 2;
                        $newStudentEnrolledSubject->save();
                        $StudentEnrolledSubject_list[] = $newStudentEnrolledSubject;
                        
                    }
                    // if ($StudentEnrolledSubject[$key]) 
                    // {
                    //     $StudentEnrolledSubject_tmp = $StudentEnrolledSubject[$key];
                    //     $StudentEnrolledSubject_tmp->class_subject_details_id = $class_subject->id;
                    //     $StudentEnrolledSubject_tmp->subject_id = $class_subject->subject_id;
                    //     $StudentEnrolledSubject_tmp->save();
                    //     $StudentEnrolledSubject_list[] = $StudentEnrolledSubject_tmp;
                    // }
                    

                }
                
            }
            return response()->json(['res_code' => 0, 'res_msg' => 'Successfully re-enrolled.']);
        }
        return response()->json(['res_code' => 1, 'res_msg' => 'Unable to perform action.']);

        return json_encode(['ClassDetail' => $ClassDetail, 'StudentEnrolledSubject_list' => $StudentEnrolledSubject_list, 'StudentEnrolledSubject' => $StudentEnrolledSubject, 'class_subjects' => $ClassDetail->class_subjects]);
    }
    public function cancel_enroll_student (Request $request, $id) 
    {
        $Enrollment = \App\Enrollment::where('id', $request->enrollment_id)->first();
        if ($Enrollment)
        {
            \DB::table('student_enrolled_subjects')->where('enrollments_id', $Enrollment->id)->delete();
            $Enrollment->delete();
            return response()->json(['res_code' => 0, 'res_msg' => 'Student successfully enrolled.']);
        }
        return response()->json(['res_code' => 1, 'res_msg' => 'There is a problem in enrolling student.']);   
    }
    public function print_enrolled_students (Request $request, $id) 
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
            ->where('section_details.status', 1)
            ->where('school_years.current', 1)
            ->where('class_details.id', $request->id)
            ->first();
        $Enrollment = \App\Enrollment::join('student_informations', 'student_informations.id', '=', 'enrollments.student_information_id')
            ->join('users', 'users.id', '=', 'student_informations.user_id')
            // ->whereRaw('student_informations.id NOT IN ((SELECT  * from enrollments where enrollments.class_details_id = 3))')
            ->selectRaw("
                student_informations.id AS student_information_id,
                users.username,
                UPPER(CONCAT(student_informations.last_name, ' ', student_informations.first_name, ', ', student_informations.middle_name)) AS fullname,
                enrollments.id AS enrollment_id
            ")
            ->where('class_details_id', $request->id)
            ->orderByRaw('fullname')
            // ->orWhere('first_name', 'like', '%'.$request->search.'%')
            ->get(); //

        $pdf = \PDF::loadView('control_panel_registrar.student_enrollment.partials.print', compact('Enrollment', 'ClassDetail'));
        return $pdf->stream();
        return $pdf->download('invoice.pdf');   
    }
}
