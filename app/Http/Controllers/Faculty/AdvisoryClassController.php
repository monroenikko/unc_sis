<?php

namespace App\Http\Controllers\Faculty;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdvisoryClassController extends Controller
{
    public function index (Request $request) 
    {        
        $FacultyInformation = \App\FacultyInformation::where('user_id', \Auth::user()->id)->first();

        $ClassDetail = \App\ClassDetail::join('section_details', 'section_details.id', '=' ,'class_details.section_id')
            ->join('rooms', 'rooms.id', '=' ,'class_details.room_id')
            ->leftJoin('faculty_informations', 'faculty_informations.id', '=' ,'class_details.adviser_id')
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
                rooms.room_description,
                CONCAT(faculty_informations.last_name, " ", faculty_informations.first_name, ", " ,  faculty_informations.middle_name) AS adviser_name
            ')
            ->orderBy('school_years.id', 'DESC')
            ->where('section_details.status', 1)
            ->where('class_details.current', 1)
            ->where('class_details.status', 1)
            // ->where('school_years.current', '!=', 0)
            ->where('class_details.adviser_id', $FacultyInformation->id)
            ->where(function ($query) use($request) {
                if ($request->sy_search) 
                {
                    $query->where('school_years.id', $request->sy_search);
                }
                if ($request->search) 
                {
                    $query->orWhere('section_details.section', 'like', '%' . $request->search . '%');
                    $query->orWhere('rooms.room_code', 'like', '%' . $request->search . '%');
                }
            });
        if ($request->ajax())
        {            
            $ClassDetail = $ClassDetail->paginate(10);
            // return json_encode($ClassDetail);
            return view('control_panel_faculty.class_advisory.partials.data_list', compact('ClassDetail'))->render();
        }

        $SchoolYear = \App\SchoolYear::where('status', 1)->where('current', 1)->orderBy('current', 'DESC')->get();

        $ClassDetail = $ClassDetail->paginate(10);
        
        // return json_encode($ClassDetail);
        return view('control_panel_faculty.class_advisory.index', compact('ClassDetail', 'SchoolYear'));
    }
    
    public function view_class_list (Request $request) 
    {
        $FacultyInformation = \App\FacultyInformation::where('user_id', \Auth::user()->id)->first();
        try {
            $class_id = \Crypt::decrypt($request->c);
            
            $EnrollmentMale = \App\Enrollment::join('student_informations', 'student_informations.id', '=', 'enrollments.student_information_id')
            ->join('class_details', 'class_details.id', '=', 'enrollments.class_details_id')
            ->join('users', 'users.id', '=', 'student_informations.user_id')
            ->whereRaw('class_details.adviser_id = '. $FacultyInformation->id)
            ->whereRaw('enrollments.class_details_id = '. $class_id)
            ->whereRaw('student_informations.gender = 1')       
            ->select(\DB::raw("
                enrollments.id as e_id,
                student_informations.id,
                users.username,
                CONCAT(student_informations.last_name, ' ', student_informations.first_name, ' ', student_informations.middle_name) as student_name
            "))
            ->orderBY('student_name', 'ASC')
            ->paginate(100);

            $EnrollmentFemale = \App\Enrollment::join('student_informations', 'student_informations.id', '=', 'enrollments.student_information_id')
            ->join('class_details', 'class_details.id', '=', 'enrollments.class_details_id')
            ->join('users', 'users.id', '=', 'student_informations.user_id')
            ->whereRaw('class_details.adviser_id = '. $FacultyInformation->id)
            ->whereRaw('enrollments.class_details_id = '. $class_id)
            ->whereRaw('student_informations.gender = 2')       
            ->select(\DB::raw("
                enrollments.id as e_id,
                student_informations.id,
                users.username,
                CONCAT(student_informations.last_name, ' ', student_informations.first_name, ' ', student_informations.middle_name) as student_name
            "))
            ->orderBY('student_name', 'ASC')
            ->paginate(100);

            $ClassDetails = \App\ClassDetail::join('section_details', 'section_details.id', '=', 'class_details.section_id')
            ->join('school_years', 'school_years.id', '=', 'class_details.school_year_id')
            ->where('class_details.id', $class_id)
            ->first();
            
            return view('control_panel_faculty.class_advisory.index_view_class_list', compact('EnrollmentMale','EnrollmentFemale', 'ClassDetails', 'class_id'))->render();
        } catch (Illuminate\Contracts\Encryption\DecryptException $e) {
            return "Invalid parameter";
        }
    }
    
    public function manage_demographic_profile(Request $request)
    {
        $class_id = \Crypt::decrypt($request->c);
        return view('control_panel_faculty.class_advisory.partials.modal_demographic_profile', compact('class_id'));
    }

    public function manage_attendance (Request $request) 
    {
        $FacultyInformation = \App\FacultyInformation::where('user_id', \Auth::user()->id)->first();
        try {
            $class_id = \Crypt::decrypt($request->c);
            $enrollment_id = \Crypt::decrypt($request->enr);
            $Enrollment = \App\Enrollment::join('student_informations', 'student_informations.id', '=', 'enrollments.student_information_id')
            ->join('class_details', 'class_details.id', '=', 'enrollments.class_details_id')
            ->join('users', 'users.id', '=', 'student_informations.user_id')
            ->whereRaw('enrollments.class_details_id = '. $class_id)
            ->whereRaw('class_details.adviser_id = '. $FacultyInformation->id)
            ->whereRaw('enrollments.id = '. $enrollment_id)
            ->select(\DB::raw("
                enrollments.id as e_id,
                student_informations.id,
                CONCAT(student_informations.last_name, ', ', student_informations.first_name, ' ', student_informations.middle_name) as student_name,
                attendance
            "))
            ->first();

            $ClassDetails = \App\ClassDetail::join('section_details', 'section_details.id', '=', 'class_details.section_id')
            ->join('school_years', 'school_years.id', '=', 'class_details.school_year_id')
            ->where('class_details.id', $class_id)->first();
            
            $attendance_data = ['jan' => '30'];
            $attendance_data_str = json_encode($attendance_data);
            $attendance_data_parsed = json_decode($attendance_data_str);
            // return compact('Enrollment', 'ClassDetails', 'attendance_data_str', 'attendance_data_parsed');
            $student_attendance = [];
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

            if ($Enrollment->attendance) {
                $attendance_data = json_decode($Enrollment->attendance);
            }

            $student_attendance = [
                'student_name'      => $Enrollment->student_name,
                'attendance_data'   => $attendance_data,
                'table_header'      => $table_header,
                'days_of_school_total' => array_sum($attendance_data->days_of_school),
                'days_present_total' => array_sum($attendance_data->days_present),
                'days_absent_total' => array_sum($attendance_data->days_absent),
                'times_tardy_total' => array_sum($attendance_data->times_tardy),
            ];
            $e_id = $Enrollment->e_id;
            return view('control_panel_faculty.class_advisory.partials.modal_manage_attendance', compact('student_attendance', 'class_id', 'e_id'))->render();
        } catch (Illuminate\Contracts\Encryption\DecryptException $e) {
            return "Invalid parameter";
        }
    }

    public function save_attendance (Request $request) 
    {
        $FacultyInformation = \App\FacultyInformation::where('user_id', \Auth::user()->id)->first();
        try {
            
            $class_id = \Crypt::decrypt($request->c);
            $enrollment_id = \Crypt::decrypt($request->enr);

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
            return json_encode([$request->all(), 'attendance_data' => json_encode($attendance_data), 'Enrollment' => $Enrollment]);
        }catch (Illuminate\Contracts\Encryption\DecryptException $e) {
            return "Invalid parameter";
        }
    }
    
    public function modal_data (Request $request) 
    {
        $ClassDetail = NULL;
        $FacultyInformation = \App\FacultyInformation::where('status', 1)->get();
        if ($request->id)
        {
            $ClassDetail = \App\ClassDetail::where('id', $request->id)->first();
        }
        // $FacultyInformation = \App\FacultyInformation::where('status', 1)->get();
        // $SubjectDetail = \App\SubjectDetail::where('status', 1)->get();

        $SectionDetail = \App\SectionDetail::where('status', 1)->orderBy('grade_level')->get();
        $SectionDetail_grade_levels = \DB::table('section_details')->select(\DB::raw('DISTINCT(grade_level) as grade_level'))->whereRaw('status = 1')->orderByRaw('grade_level ASC')->get();
        if ($ClassDetail) 
        {
            $SectionDetail = \App\SectionDetail::where('status', 1)->where('grade_level', $ClassDetail->grade_level)->orderBy('grade_level')->get();
        }
        $GradeLevel = \App\GradeLevel::where('status', 1)->get();
        $Room = \App\Room::where('status', 1)->get();
        $SchoolYear = \App\SchoolYear::where('status', 1)->where('current', 1)->get();
        return view('control_panel_faculty.class_advisory.partials.modal_data', compact('ClassDetail', 'SectionDetail', 'Room', 'SchoolYear', 'SectionDetail_grade_levels', 'GradeLevel', 'FacultyInformation'))->render();
    }

    public function modal_manage_subjects (Request $request) 
    {
        $ClassDetail = NULL;
        if ($request->id)
        {
            $ClassDetail = \App\ClassDetail::where('id', $request->id)->first();
        }
        // return json_encode($ClassDetail);
        $FacultyInformation = \App\FacultyInformation::where('status', 1)->get();
        $SubjectDetail = \App\SubjectDetail::where('status', 1)->get();
        $SectionDetail = \App\SectionDetail::where('status', 1)->get();
        $Room = \App\Room::where('status', 1)->get();
        $SchoolYear = \App\SchoolYear::where('status', 1)->get();
        return view('control_panel_faculty.class_advisory.partials.modal_manage_subjects', compact('ClassDetail', 'FacultyInformation', 'SubjectDetail', 'SectionDetail', 'Room', 'SchoolYear'))->render();
    }

    public function save_data (Request $request) 
    {
        $rules = [
            'section'       => 'required',
            'room'          => 'required',
            'school_year'   => 'required',
            'grade_level'   => 'required',
            'adviser'       => 'required',
        ];

        $Validator = \Validator($request->all(), $rules);

        if ($Validator->fails())
        {
            return response()->json(['res_code' => 1, 'res_msg' => 'Please fill all required fields.', 'res_error_msg' => $Validator->getMessageBag()]);
        }

        $sectionDetail = \App\SectionDetail::where('id', $request->section)->first();

        if ($request->id)
        {
            $ClassDetail = \App\ClassDetail::where('id', $request->id)->first();
            $ClassDetail->section_id	 = $request->section;
            $ClassDetail->room_id	 = $request->room;
            $ClassDetail->school_year_id = $request->school_year;
            $ClassDetail->grade_level = $request->grade_level;
            $ClassDetail->adviser_id = $request->adviser;
            $ClassDetail->save();
            return response()->json(['res_code' => 0, 'res_msg' => 'Data successfully saved.']);
        }

        $ClassDetail = new \App\ClassDetail();
        $ClassDetail->section_id	 = $request->section;
        $ClassDetail->room_id	 = $request->room;
        $ClassDetail->school_year_id = $request->school_year;
        $ClassDetail->grade_level = $request->grade_level;
        $ClassDetail->adviser_id = $request->adviser;
        $ClassDetail->save();
        return response()->json(['res_code' => 0, 'res_msg' => 'Data successfully saved.']);
    }

    public function deactivate_data (Request $request) 
    {
        $ClassDetail = \App\ClassDetail::where('id', $request->id)->first();

        if ($ClassDetail)
        {
            $ClassDetail->current = 0;
            $ClassDetail->save();
            return response()->json(['res_code' => 0, 'res_msg' => 'Data successfully deactivated.']);
        }
        return response()->json(['res_code' => 1, 'res_msg' => 'Invalid request.']);
    }
    public function delete_data (Request $request)
    {
        $ClassDetail = \App\ClassDetail::where('id', $request->id)->first();

        if ($ClassDetail)
        {
            $ClassDetail->status = 0;
            $ClassDetail->save();
            return response()->json(['res_code' => 0, 'res_msg' => 'Data successfully deactivated.']);
        }
        return response()->json(['res_code' => 1, 'res_msg' => 'Invalid request.']);
    }

    public function fetch_section_by_grade_level (Request $request)
    {
        $SectionDetail = \App\SectionDetail::where('grade_level', $request->grade_level)->where('status', 1)->get();
        if ($request->type == 'json') 
        {
            return response()->json(compact('SectionDetail'));
        }

        $section_details_elem = '<option value="">Select section</option>';
        foreach($SectionDetail as $data) 
        {
            $section_details_elem .= '<option value="'. $data->id .'">' . $data->section . '</option>';
        }
        return $section_details_elem;
    }

    public function print_student_class_grades (Request $request)
    {
        if (!$request->id || !$request->cid) 
        {
            return "Invalid request";
        }

        $StudentInformation = \App\StudentInformation::with(['user'])->where('id', $request->id)->first();

        $SchoolYear = \App\SchoolYear::where('current', 1)->first();
        $DateRemarks = \App\DateRemark::where('school_year_id', $SchoolYear->id)->first();
        $level = $request->level;
        // $StudentInformation = \App\StudentInformation::with('user')->where('id', $request->id)->first();
        // $SchoolYear = \App\SchoolYear::where('current', $request->cid)->first();
        // // return json_encode(['xx'=> $request->all(), 's' => $StudentInformation]);

        if ($StudentInformation) 
        {
            if($level < 11)
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
            }
            else 
            {
                $ClassDetail = \App\ClassDetail::join('section_details', 'section_details.id', '=' ,'class_details.section_id')
                ->join('rooms', 'rooms.id', '=' ,'class_details.room_id')
                ->join('school_years', 'school_years.id', '=' ,'class_details.school_year_id')
                ->join('faculty_informations', 'faculty_informations.id','=','class_details.adviser_id')
                ->join('strands', 'strands.id','=','class_details.strand_id')
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
                    faculty_informations.e_signature,
                    strands.strand
                ')
                ->where('section_details.status', 1)
                // ->where('school_years.current', 1)
                ->where('class_details.id', $request->cid)
                ->orderBY('school_years.id', 'ASC')
                ->first();
            }
            
            
            
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
            return view('control_panel_student.grade_sheet.partials.print', compact('GradeSheetData', 'grade_level', 'StudentInformation',
                'ClassDetail', 'general_avg', 'student_attendance', 'table_header','Signatory','DateRemarks','Enrollment[0]'));
            $pdf = \PDF::loadView('control_panel_student.grade_sheet.partials.print', compact('GradeSheetData', 'grade_level', 'StudentInformation', 'ClassDetail'
                 , 'general_avg', 'student_attendance', 'table_header','Signatory','DateRemarks','Enrollment[0]'));
            return $pdf->stream();
            return view('control_panel_student.grade_sheet.index', compact('GradeSheetData'));
            return json_encode(['GradeSheetData' => $GradeSheetData,]);
        }
        else {
            return "Invalid request";
        }
    }
}
