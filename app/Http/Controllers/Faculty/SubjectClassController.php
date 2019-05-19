<?php

namespace App\Http\Controllers\Faculty;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SubjectClassController extends Controller
{
    public function index (Request $request) 
    {
        // $FacultyInformation = \App\FacultyInformation::where('user_id', \Auth::user()->id)->first();
        // return json_encode(['FacultyInformation' => $FacultyInformation, 'Auth' => \Auth::user()]);
        $SchoolYear         = \App\SchoolYear::where('status', 1)->orderBy('current', 'ASC')->orderBy('school_year', 'ASC')->get();
        return view('control_panel_faculty.subject_class_details.index', compact('SchoolYear'));
    }
    public function list_students_by_class (Request $request) 
    {
        $FacultyInformation = \App\FacultyInformation::where('user_id', \Auth::user()->id)->first();

        $EnrollmentMale = \App\Enrollment::join('class_subject_details', 'class_subject_details.class_details_id', '=', 'enrollments.class_details_id')
                    ->join('class_details', 'class_details.id', '=', 'class_subject_details.class_details_id')
                    ->join('student_informations', 'student_informations.id', '=', 'enrollments.student_information_id')
                    ->join('users', 'users.id', '=', 'student_informations.user_id')
                    ->whereRaw('class_subject_details.faculty_id = '. $FacultyInformation->id)
                    ->whereRaw('class_subject_details.id = '. $request->search_class_subject)
                    ->whereRaw('class_details.current = 1')
                    ->whereRaw('class_details.status = 1')
                    ->whereRaw('student_informations.gender = 1')
                    ->select(\DB::raw("
                        student_informations.id,
                        users.username,
                        CONCAT(student_informations.last_name, ', ', student_informations.first_name, ' ', student_informations.middle_name) as student_name
                    "))
                    ->orderBY('student_name','ASC')
                    ->paginate(50);

        $EnrollmentFemale = \App\Enrollment::join('class_subject_details', 'class_subject_details.class_details_id', '=', 'enrollments.class_details_id')
                    ->join('class_details', 'class_details.id', '=', 'class_subject_details.class_details_id')
                    ->join('student_informations', 'student_informations.id', '=', 'enrollments.student_information_id')
                    ->join('users', 'users.id', '=', 'student_informations.user_id')
                    ->whereRaw('class_subject_details.faculty_id = '. $FacultyInformation->id)
                    ->whereRaw('class_subject_details.id = '. $request->search_class_subject)
                    ->whereRaw('class_details.current = 1')
                    ->whereRaw('class_details.status = 1')
                    ->whereRaw('student_informations.gender = 2')
                    ->select(\DB::raw("
                        student_informations.id,
                        users.username,
                        CONCAT(student_informations.last_name, ', ', student_informations.first_name, ' ', student_informations.middle_name) as student_name
                    "))
                    ->orderBY('student_name','ASC')
                    ->paginate(50);

        $ClassSubjectDetail = \App\ClassSubjectDetail::join('class_details', 'class_details.id', '=', 'class_subject_details.class_details_id')
            ->join('subject_details', 'subject_details.id', '=', 'class_subject_details.subject_id')
            ->join('section_details', 'section_details.id', '=', 'class_details.section_id')
            ->where('class_subject_details.id', $request->search_class_subject)
            ->where('faculty_id', $FacultyInformation->id)
            ->where('class_details.school_year_id', $request->search_sy)
            ->where('class_subject_details.status', '!=', 0)
            ->where('class_details.status', 1)
            ->select(\DB::raw('
                class_subject_details.id,
                class_subject_details.class_schedule,
                class_subject_details.class_time_from,
                class_subject_details.class_time_to,
                class_subject_details.class_days,
                subject_details.subject_code,
                subject_details.subject,
                section_details.section,
                class_details.grade_level
            '))
            ->first();
        return view('control_panel_faculty.subject_class_details.partials.data_list', compact('EnrollmentMale','EnrollmentFemale', 'ClassSubjectDetail'))->render();
    }
    public function list_students_by_class_print (Request $request) 
    {
        $FacultyInformation = \App\FacultyInformation::where('user_id', \Auth::user()->id)->first();

        // $Enrollment = \App\Enrollment::join('class_subject_details', 'class_subject_details.class_details_id', '=', 'enrollments.class_details_id')
        //             ->join('class_details', 'class_details.id', '=', 'class_subject_details.class_details_id')
        //             ->join('student_informations', 'student_informations.id', '=', 'enrollments.student_information_id')
        //             ->join('users', 'users.id', '=', 'student_informations.user_id')
        //             ->whereRaw('class_subject_details.faculty_id = '. $FacultyInformation->id)
        //             ->whereRaw('class_subject_details.id = '. $request->search_class_subject)
        //             ->whereRaw('class_details.current = 1')
        //             ->whereRaw('class_details.status = 1')
        //             ->select(\DB::raw("
        //                 student_informations.id,
        //                 users.username,
        //                 UPPER(CONCAT(student_informations.last_name, ', ', student_informations.first_name, ' ', student_informations.middle_name)) as student_name
        //             "))
        //             ->paginate(50);
        
        $EnrollmentMale = \App\Enrollment::join('class_subject_details', 'class_subject_details.class_details_id', '=', 'enrollments.class_details_id')
                    ->join('class_details', 'class_details.id', '=', 'class_subject_details.class_details_id')
                    ->join('student_informations', 'student_informations.id', '=', 'enrollments.student_information_id')
                    ->join('users', 'users.id', '=', 'student_informations.user_id')
                    ->whereRaw('class_subject_details.faculty_id = '. $FacultyInformation->id)
                    ->whereRaw('class_subject_details.id = '. $request->search_class_subject)
                    ->whereRaw('class_details.current = 1')
                    ->whereRaw('class_details.status = 1')
                    ->whereRaw('student_informations.gender = 1')
                    ->select(\DB::raw("
                        student_informations.id,
                        users.username,
                        CONCAT(student_informations.last_name, ', ', student_informations.first_name, ' ', student_informations.middle_name) as student_name
                    "))
                    ->orderBY('student_name','ASC')
                    ->paginate(50);

        $EnrollmentFemale = \App\Enrollment::join('class_subject_details', 'class_subject_details.class_details_id', '=', 'enrollments.class_details_id')
                    ->join('class_details', 'class_details.id', '=', 'class_subject_details.class_details_id')
                    ->join('student_informations', 'student_informations.id', '=', 'enrollments.student_information_id')
                    ->join('users', 'users.id', '=', 'student_informations.user_id')
                    ->whereRaw('class_subject_details.faculty_id = '. $FacultyInformation->id)
                    ->whereRaw('class_subject_details.id = '. $request->search_class_subject)
                    ->whereRaw('class_details.current = 1')
                    ->whereRaw('class_details.status = 1')
                    ->whereRaw('student_informations.gender = 2')
                    ->select(\DB::raw("
                        student_informations.id,
                        users.username,
                        CONCAT(student_informations.last_name, ', ', student_informations.first_name, ' ', student_informations.middle_name) as student_name
                    "))
                    ->orderBY('student_name','ASC')
                    ->paginate(50);

        $ClassSubjectDetail = \App\ClassSubjectDetail::join('class_details', 'class_details.id', '=', 'class_subject_details.class_details_id')
            ->join('subject_details', 'subject_details.id', '=', 'class_subject_details.subject_id')
            ->join('section_details', 'section_details.id', '=', 'class_details.section_id')
            ->where('class_subject_details.id', $request->search_class_subject)
            ->where('faculty_id', $FacultyInformation->id)
            ->where('class_details.school_year_id', $request->search_sy)
            ->where('class_subject_details.status', '!=', 0)
            ->where('class_details.status', 1)
            ->select(\DB::raw('
                class_subject_details.id,
                class_subject_details.class_schedule,
                class_subject_details.class_time_from,
                class_subject_details.class_time_to,
                class_subject_details.class_days,
                subject_details.subject_code,
                subject_details.subject,
                section_details.section,
                class_details.grade_level
            '))
            ->first();

        $pdf = \PDF::loadView('control_panel_faculty.subject_class_details.partials.print', compact('FacultyInformation', 'EnrollmentMale','EnrollmentFemale', 'ClassSubjectDetail'));
        $pdf->setPaper('Legal', 'portrait');
        return $pdf->stream();
    }
    public function list_class_subject_details (Request $request) 
    {
        $FacultyInformation = \App\FacultyInformation::where('user_id', \Auth::user()->id)->first();
        $ClassSubjectDetail = \App\ClassSubjectDetail::join('class_details', 'class_details.id', '=', 'class_subject_details.class_details_id')
            ->join('subject_details', 'subject_details.id', '=', 'class_subject_details.subject_id')
            ->join('section_details', 'section_details.id', '=', 'class_details.section_id')
            ->where('faculty_id', $FacultyInformation->id)
            ->where('class_details.school_year_id', $request->search_sy)
            ->where('class_subject_details.status', '!=', 0)
            ->where('class_details.status', 1)
            ->select(\DB::raw('  
                class_subject_details.id,
                class_subject_details.class_schedule,
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
                $class_details_elements .= '<option value="'. $data->id .'">'. $data->subject_code . ' ' . $data->subject . ' - Grade ' .  $data->grade_level . ' Section ' . $data->section .'</option>';
            }

            return $class_details_elements;
        }
        return $class_details_elements;
    }
    // public function modal_data (Request $request) 
    // {
    //     $ClassDetail = NULL;
    //     if ($request->id)
    //     {
    //         $ClassDetail = \App\ClassDetail::where('id', $request->id)->first();
    //     }
    //     // return json_encode($ClassDetail);
    //     $FacultyInformation = \App\FacultyInformation::where('status', 1)->get();
    //     $SubjectDetail = \App\SubjectDetail::where('status', 1)->get();
    //     $SectionDetail = \App\SectionDetail::where('status', 1)->get();
    //     $Room = \App\Room::where('status', 1)->get();
    //     $SchoolYear = \App\SchoolYear::where('status', 1)->where('current', 1)->get();
    //     return view('control_panel_registrar.class_details.partials.modal_data', compact('ClassDetail', 'FacultyInformation', 'SubjectDetail', 'SectionDetail', 'Room', 'SchoolYear'))->render();
    // }

    // public function modal_manage_subjects (Request $request) 
    // {
    //     $ClassDetail = NULL;
    //     if ($request->id)
    //     {
    //         $ClassDetail = \App\ClassDetail::where('id', $request->id)->first();
    //     }
    //     // return json_encode($ClassDetail);
    //     $FacultyInformation = \App\FacultyInformation::where('status', 1)->get();
    //     $SubjectDetail = \App\SubjectDetail::where('status', 1)->get();
    //     $SectionDetail = \App\SectionDetail::where('status', 1)->get();
    //     $Room = \App\Room::where('status', 1)->get();
    //     $SchoolYear = \App\SchoolYear::where('status', 1)->get();
    //     return view('control_panel_registrar.class_details.partials.modal_manage_subjects', compact('ClassDetail', 'FacultyInformation', 'SubjectDetail', 'SectionDetail', 'Room', 'SchoolYear'))->render();
    // }

    public function class_schedules (Request $request)
    {
        $FacultyInformation = \App\FacultyInformation::where('user_id', \Auth::user()->id)->first();
        $SchoolYear         = \App\SchoolYear::where('status', 1)->orderBy('current', 'ASC')->orderBy('school_year', 'ASC')->get();
        $ClassSubjectDetail = \App\ClassSubjectDetail::where('faculty_id', $FacultyInformation->id)
            ->join('class_details', 'class_details.id', '=', 'class_subject_details.class_details_id')
            ->join('school_years', 'school_years.id', '=', 'class_details.school_year_id')
            ->join('section_details', 'section_details.id', '=', 'class_details.section_id')
            ->join('rooms', 'rooms.id', '=', 'class_details.room_id')
            ->join('subject_details', 'subject_details.id', '=', 'class_subject_details.subject_id')
            ->select(\DB::raw('
                class_subject_details.id,
                class_subject_details.class_schedule,
                class_subject_details.class_days,
                class_subject_details.class_time_from,
                class_subject_details.class_time_to,
                subject_details.subject_code,
                subject_details.subject,
                rooms.room_code,
                rooms.room_description,
                section_details.section,
                section_details.grade_level
            '))
            ->where('class_details.status', 1)
            ->where('class_details.current', 1)
            ->where('school_years.status', 1)
            ->where('school_years.current', 1)
            ->where('class_subject_details.status', '!=', 0)
            ->orderBy('class_time_from', 'ASC')
            ->get();
        // return json_encode($ClassSubjectDetail);
        return view('control_panel_faculty.class_schedule.index', compact('ClassSubjectDetail'))->render();
    }

    public function class_schedules_print (Request $request)
    {
        $FacultyInformation = \App\FacultyInformation::where('user_id', \Auth::user()->id)->first();
        $SchoolYear         = \App\SchoolYear::where('status', 1)->orderBy('current', 'ASC')->orderBy('school_year', 'ASC')->get();
        $ClassSubjectDetail = \App\ClassSubjectDetail::where('faculty_id', $FacultyInformation->id)
            ->join('class_details', 'class_details.id', '=', 'class_subject_details.class_details_id')
            ->join('school_years', 'school_years.id', '=', 'class_details.school_year_id')
            ->join('section_details', 'section_details.id', '=', 'class_details.section_id')
            ->join('rooms', 'rooms.id', '=', 'class_details.room_id')
            ->join('subject_details', 'subject_details.id', '=', 'class_subject_details.subject_id')
            ->select(\DB::raw('
                class_subject_details.id,
                class_subject_details.class_schedule,
                class_subject_details.class_days,
                class_subject_details.class_time_from,
                class_subject_details.class_time_to,
                subject_details.subject_code,
                subject_details.subject,
                rooms.room_code,
                rooms.room_description,
                section_details.section,
                section_details.grade_level
            '))
            ->where('class_details.status', 1)
            ->where('class_details.current', 1)
            ->where('school_years.status', 1)
            ->where('school_years.current', 1)
            ->where('class_subject_details.status', '!=', 0)
            ->orderBy('class_time_from', 'ASC')
            ->get();
        // return json_encode($ClassSubjectDetail);
        $pdf = \PDF::loadView('control_panel_faculty.class_schedule.partials.print',
         compact('FacultyInformation', 'ClassSubjectDetail'));
        $pdf->setPaper('Legal', 'portrait');
        return $pdf->stream();
    }

    
    // public function class_schedules (Request $request)
    // {
    //     $FacultyInformation = \App\FacultyInformation::where('user_id', \Auth::user()->id)->first();
    //     $SchoolYear         = \App\SchoolYear::where('status', 1)->orderBy('current', 'ASC')->orderBy('school_year', 'ASC')->get();
    //     $ClassSubjectDetail = \App\ClassSubjectDetail::where('faculty_id', $FacultyInformation->id)
    //     ->join('subject_details', 'subject_details.id', '=', 'class_subject_details.subject_id')
    //     ->select(\DB::raw('
    //         class_subject_details.id,
    //         class_subject_details.class_days,
    //         class_subject_details.class_time_from,
    //         class_subject_details.class_time_to,
    //         subject_details.subject_code,
    //         subject_details.subject
    //     '))
    //     ->where('class_subject_details.status', 1)
    //     ->orderBy('class_time_from', 'ASC')
    //     ->get();
    //     $class_sched_table = [
    //         'm'     => [], 
    //         'tu'    => [], 
    //         'w'     => [], 
    //         'th'    => [], 
    //         'f'     => [], 
    //     ];
    //     foreach ($ClassSubjectDetail as $data)
    //     {
    //         $days = explode('/', $data->class_days);
    //         foreach ($days as $day)
    //         {
    //             $class_sched_table[$day][] = [
    //                 'subject' => $data->subject_code,
    //                 'from' => $data->class_time_from, 
    //                 'to' => $data->class_time_to,
    //             ];

    //         }
    //     }
    //     $class_sched_table = json_decode(json_encode($class_sched_table), FALSE);
    //     // $class_sched_table = (object) $class_sched_table;
    //     // echo $class_sched_table->m[0];

    //     // foreach ($class_sched_table as $data)
    //     // {
    //     //     echo $data[0]->subject;
    //     //     // echo json_encode($data[0]);

    //     // }
    //     // return;
        
    //     $time_list = [
    //         '07:00:00 AM',
    //         '07:30:00 AM',
    //         '08:00:00 AM',
    //         '08:30:00 AM',
    //         '09:00:00 AM',
    //         '09:30:00 AM',
    //         '10:00:00 AM',
    //         '10:30:00 AM',
    //         '11:00:00 AM',
    //         '11:30:00 AM',
    //         '12:00:00 PM',
    //         '12:30:00 PM'
    //     ];
    //     $time_list = json_decode(json_encode($time_list), FALSE);

    //     $time_table = [

    //     ];
    //     $table_elem = '';
    //     $table_elem .= '
    //         <tr>
    //             <td>TIME</td>
    //             <td>Monday</td>
    //         </tr>
    //     ';
    //     // return json_encode($class_sched_table);

    //     $table_monday = [];
    //     $table_tue = [];
    //     foreach ($time_list as $data)
    //     {
    //         $table_monday[$data] = '-';
    //         $table_tue[$data] = '-';
    //     }


    //     $hasOpened = false;
    //     foreach ($time_list as $data)
    //     {
    //         $mon = $class_sched_table->m[0];
            
    //         if ($data == strftime('%r', strtotime($mon->from))) 
    //         {
    //             $table_monday[$data] = 'open';
    //             $hasOpened = true;
    //         }

    //         if ($data == strftime('%r', strtotime($mon->to)))
    //         {

    //             $hasOpened = false;
    //         }

    //         if ($hasOpened && $table_monday[$data] == '-')
    //         { 
    //             $table_monday[$data] = 'delete';
    //         }

    //         $mon = $class_sched_table->m[1];
            
    //         if ($data == strftime('%r', strtotime($mon->from))) 
    //         {
    //             $diff = strtotime($mon->to) - strtotime($mon->from);
    //             $table_monday[$data] = 'open';
    //             $hasOpened = true;
    //         }

    //         if ($data == strftime('%r', strtotime($mon->to)))
    //         {

    //             $hasOpened = false;
    //         }

    //         if ($hasOpened && $table_monday[$data] == '-')
    //         {
    //             $table_monday[$data] = 'delete';
    //         }
    //     }
    //     // $temp = $table_monday;
    //     // foreach ($time_list as $data)
    //     // {
    //     //     if ($table_monday[$data] != 'delete')
    //     //     {
    //     //         $table_monday[$data] = $temp[$data];
    //     //     }   
    //     // }
    //     // return json_encode($table_monday);
    //     $time_plot = '';
    //     $time_plot = '<table border="1" cellpadding="20">';
    //     $time_plot .= '<tr><th>monday</th>';
    //     $time_plot .= '<th>Tuesday</th></tr>';
    //     foreach ($time_list as $data)
    //     {
    //         $time_plot .= '<tr>';
    //         $time_plot .= '<td rowspan="3">'. $data .'</td>';
    //         if ($table_monday[$data])
    //         {
    //             if ($table_monday[$data] == 'open')
    //             {
    //                 $time_plot .= '<td rowspan="3">'. $table_monday[$data] .'</td>';
    //             }
    //             else if ($table_monday[$data] != 'delete')
    //             {
    //                 $time_plot .= '<td>'. $table_monday[$data] .'</td>';
    //             }
    //         }
    //             // $time_plot .= '<td>'. $table_monday[$data] .'</td>';
    //         // $time_plot .= '<td>'. $table_tue[$data] .'</td>';
    //         $time_plot .= '</tr>';
    //     }
    //     $time_plot .= '</table>';
    //     echo $time_plot;
    //     return;
    //     return json_encode($table_monday);

    //     // $hasOpened = false;
    //     // foreach ($time_list as $data)
    //     // {
    //     //     // strftime('%r',strtotime($ClassSubjectDetail->class_time_from))
    //     //     // $monday_time = [];
    //     //     $table_elem .= '<tr>'; 
    //     //     $table_elem .= '<td>' . $data . '</td>'; 

    //     //     $mon = $class_sched_table->m[0];
    //     //     if ($data == strftime('%r', strtotime($mon->from))) 
    //     //     {
    //     //         $table_elem .= '<td>' . $data .  ' ' . $mon->from . '</td>'; 
    //     //         $hasOpened = true;
    //     //     } 
    //     //     else if ($data == strftime('%r', strtotime($mon->to))) 
    //     //     {
    //     //         $table_elem .= '<td>' . $data .  ' ' . $mon->to . '</td>'; 
    //     //         $hasOpened = false;
    //     //     }
    //     //     else 
    //     //     {
    //     //         // if ($hasOpened == true)
    //     //         // {
    //     //         //     $table_elem .= '<td>-- open</td>'; 
    //     //         // }
    //     //         // else 
    //     //         // {
    //     //             $table_elem .= '<td>no sched</td>'; 
    //     //         // }
    //     //     }

    //     //     $table_elem .= '    </tr>';
    //     // }

    //     // echo '<table border="1">
    //     //     '.
    //     //         $table_elem
    //     //     .'
    //     // </table>';
    //     return;
    //     return json_encode($monday_time);
    //     // return json_encode($class_sched_table);
    //     // return view('control_panel_faculty.class_schedule.index', compact('SchoolYear'));
    // }
}
