<?php

namespace App\Http\Controllers\Control_Panel_Student;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GradeSheetController extends Controller
{
    public function index (Request $request)
    {
        $StudentInformation = \App\StudentInformation::where('user_id', \Auth::user()->id)->first();
        $SchoolYear = \App\SchoolYear::where('current', 1)->first();
        
        if ($StudentInformation) 
        {
            // firstsem
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
                ->where('class_subject_details.sem', 1)
                ->select(\DB::raw("
                    enrollments.id as enrollment_id,
                    class_details.grade_level,
                    class_details.school_year_id,
                    class_subject_details.class_days,
                    class_subject_details.class_time_from,
                    class_subject_details.class_time_to,
                    class_subject_details.status as grade_status,
                    CONCAT(faculty_informations.last_name, ', ', faculty_informations.first_name, ' ', faculty_informations.middle_name) as faculty_name,
                    subject_details.id AS subject_id,
                    subject_details.subject_code,
                    subject_details.subject,
                    rooms.room_code,
                    section_details.section
                "))
                ->orderBy('class_subject_details.class_time_from', 'ASC')
                ->get();

                $Enrollment2 = \App\Enrollment::join('class_details', 'class_details.id', '=', 'enrollments.class_details_id')
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
                ->where('class_subject_details.sem', 1)
                ->select(\DB::raw("
                    enrollments.id as enrollment_id,
                    class_details.grade_level,
                    class_details.school_year_id,
                    class_subject_details.class_days,
                    class_subject_details.class_time_from,
                    class_subject_details.class_time_to,
                    class_subject_details.status as grade_status,
                    CONCAT(faculty_informations.last_name, ', ', faculty_informations.first_name, ' ', faculty_informations.middle_name) as faculty_name,
                    subject_details.id AS subject_id,
                    subject_details.subject_code,
                    subject_details.subject,
                    rooms.room_code,
                    section_details.section
                "))
                ->orderBy('class_subject_details.class_time_from', 'ASC')
                ->first();

                $getSchoolYear = \App\SchoolYear::where('id', $Enrollment2->school_year_id)->first();

                $GradeSheetData = [];
                $grade_level = 1;
                // return json_encode(['Enrollment' => $Enrollment,'StudentInformation' => $StudentInformation, 'SchoolYear' => $SchoolYear]);
                if ($StudentInformation && count($Enrollment)>0)
                {
                    $StudentEnrolledSubject = \App\StudentEnrolledSubject::where('enrollments_id', $Enrollment[0]->enrollment_id)
                    ->get();
                    $grade_level = $Enrollment[0]->grade_level;
                    $grade_status = $Enrollment[0]->grade_status;
                    // return json_encode(['StudentEnrolledSubject'=> $StudentEnrolledSubject]);
                    $GradeSheetData = $Enrollment->map(function ($item, $key) use ($StudentEnrolledSubject, $grade_level, $grade_status) {
                        $grade = $StudentEnrolledSubject->firstWhere('subject_id', $item->subject_id);
                        $sum = 0;
                        $first = $grade->fir_g > 0 ? $grade->fir_g : 0;
                        $second = $grade->sec_g > 0 ? $grade->sec_g : 0;
                        $third = $grade->thi_g > 0 ? $grade->thi_g : 0;
                        $fourth = $grade->fou_g > 0 ? $grade->fou_g : 0;
                        // $third = 0;
                        // $fourth = 0;
                        // if ($grade_level >= 11)
                        // {
                        //     $third = $grade->thi_g > 0 ? $grade->thi_g : 0;
                        //     $fourth = $grade->fou_g > 0 ? $grade->fou_g : 0;
                        // }
                        
                        $sum += $grade->fir_g > 0 ? $grade->fir_g : 0;
                        $sum += $grade->sec_g > 0 ? $grade->sec_g : 0;
                        $sum += $grade->thi_g > 0 ? $grade->thi_g : 0;
                        $sum += $grade->fou_g > 0 ? $grade->fou_g : 0;
                        // if ($grade_level >= 11)
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
                            'final_g'           =>  $final,
                            'grade_status'      =>  $grade_status,
                            'divisor' => $divisor
                        ];
                        return $data;
                    });
                }
            // return json_encode($GradeSheetData);
        //   2ndsem

                $SecondEnrollment = \App\Enrollment::join('class_details', 'class_details.id', '=', 'enrollments.class_details_id')
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
                ->where('class_subject_details.sem', 2)
                ->select(\DB::raw("
                    enrollments.id as enrollment_id,
                    class_details.grade_level,
                    class_subject_details.class_days,
                    class_subject_details.class_time_from,
                    class_subject_details.class_time_to,
                    class_subject_details.status as grade_status,
                    CONCAT(faculty_informations.last_name, ', ', faculty_informations.first_name, ' ', faculty_informations.middle_name) as faculty_name,
                    subject_details.id AS subject_id,
                    subject_details.subject_code,
                    subject_details.subject,
                    rooms.room_code,
                    section_details.section
                "))
                ->orderBy('class_subject_details.class_time_from', 'ASC')
                ->get();
                $GradeSheetData1 = [];
                $grade_level = 1;
                // return json_encode(['Enrollment' => $Enrollment,'StudentInformation' => $StudentInformation, 'SchoolYear' => $SchoolYear]);
                if ($StudentInformation && count($SecondEnrollment)>0)
                {
                    $StudentEnrolledSubject = \App\StudentEnrolledSubject::where('enrollments_id', $SecondEnrollment[0]->enrollment_id)
                    ->get();
                    $grade_level = $SecondEnrollment[0]->grade_level;
                    $grade_status = $SecondEnrollment[0]->grade_status;
                    // return json_encode(['StudentEnrolledSubject'=> $StudentEnrolledSubject]);
                    $GradeSheetData1 = $SecondEnrollment->map(function ($item, $key) use ($StudentEnrolledSubject, $grade_level, $grade_status) {
                        $grade = $StudentEnrolledSubject->firstWhere('subject_id', $item->subject_id);
                        $sum = 0;
                        $first = $grade->fir_g > 0 ? $grade->fir_g : 0;
                        $second = $grade->sec_g > 0 ? $grade->sec_g : 0;
                        $third = $grade->thi_g > 0 ? $grade->thi_g : 0;
                        $fourth = $grade->fou_g > 0 ? $grade->fou_g : 0;
                        // $third = 0;
                        // $fourth = 0;
                        // if ($grade_level >= 11)
                        // {
                        //     $third = $grade->thi_g > 0 ? $grade->thi_g : 0;
                        //     $fourth = $grade->fou_g > 0 ? $grade->fou_g : 0;
                        // }
                        
                        $sum += $grade->fir_g > 0 ? $grade->fir_g : 0;
                        $sum += $grade->sec_g > 0 ? $grade->sec_g : 0;
                        $sum += $grade->thi_g > 0 ? $grade->thi_g : 0;
                        $sum += $grade->fou_g > 0 ? $grade->fou_g : 0;
                        // if ($grade_level >= 11)
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
                            'final_g'           =>  $final,
                            'grade_status'      =>  $grade_status,
                            'divisor' => $divisor
                        ];
                        return $data;
                    });
                }

            

            $GradeSheetData = json_decode(json_encode($GradeSheetData));
            $GradeSheetData1 = json_decode(json_encode($GradeSheetData1));
            return view('control_panel_student.grade_sheet.index', compact('GradeSheetData', 'grade_level' ,'StudentInformation','GradeSheetData1','getSchoolYear','Enrollment2','Enrollment'));
            return json_encode(['GradeSheetData' => $GradeSheetData,]);
            return json_encode(['GradeSheetData1' => $GradeSheetData1,]);
        }
    }
    public function print_grades (Request $request)
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
                enrollments.class_details_id as cid,
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
                section_details.section
            "))
            ->orderBy('class_subject_details.class_subject_order', 'ASC')
            ->get();
            $ClassDetail = [];
            if ($Enrollment)
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
                // ->where('school_years.current', 1)
                ->where('class_details.id', $Enrollment[0]->cid)
                ->first();

                
            }


            $GradeSheetData = [];
            $grade_level = 1;
            $sub_total = 0;
            $general_avg = 0;
            $subj_count = 0;
            $grade_status = $Enrollment[0]->grade_status;
            // return json_encode(['Enrollment' => $Enrollment,'StudentInformation' => $StudentInformation, 'SchoolYear' => $SchoolYear]);
            if ($StudentInformation && count($Enrollment)>0)
            {
                $StudentEnrolledSubject = \App\StudentEnrolledSubject::where('enrollments_id', $Enrollment[0]->enrollment_id)
                ->get();
                $grade_level = $Enrollment[0]->grade_level;
                // return json_encode(['StudentEnrolledSubject'=> $StudentEnrolledSubject]);
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
                        'final_g'           =>  $final,
                        'grade_status'      =>  $grade_status,
                        'divisor' => $divisor
                    ];
                    return $data;
                });
                for ($i=0; $i<count($GradeSheetData); $i++)
                {
                    if ($GradeSheetData[$i]['final_g'] > 0 && $GradeSheetData[$i]['grade_status'] == 2) 
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
            $pdf = \PDF::loadView('control_panel_student.grade_sheet.partials.print', compact('GradeSheetData', 'grade_level', 'StudentInformation', 'ClassDetail', 'general_avg'));
            // $pdf->setPaper('Letter', 'landscape');
            return $pdf->stream();
            return view('control_panel_student.grade_sheet.index', compact('GradeSheetData'));
            return json_encode(['GradeSheetData' => $GradeSheetData,]);
        }
        else {
            echo "Invalid request";
        }
    }
}
