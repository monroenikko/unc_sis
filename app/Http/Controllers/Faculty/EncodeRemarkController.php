<?php

namespace App\Http\Controllers\Faculty;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;



class EncodeRemarkController extends Controller
{
    public function index ()
    {
        $FacultyInformation = \App\FacultyInformation::where('user_id', \Auth::user()->id)->first();

        $SchoolYear = \App\SchoolYear::where('current', 1)->first();
        $DateRemarks = \App\DateRemark::where('school_year_id', $SchoolYear->id)->first();

        $ClassSubjectDetail = \App\ClassSubjectDetail::join('class_details', 'class_details.id', '=', 'class_subject_details.class_details_id')
            // ->join('subject_details', 'subject_details.id', '=', 'class_subject_details.subject_id')
            ->join('rooms','rooms.id', '=', 'class_details.room_id')
            ->join('section_details', 'section_details.id', '=', 'class_details.section_id')
            ->join('school_years', 'school_years.id' ,'=', 'class_details.school_year_id')
            ->join('faculty_informations', 'faculty_informations.id','=','class_details.adviser_id')
            // ->whereRaw('class_subject_details.id = '. $request->search_class_subject)
            // ->whereRaw('class_details.id = '. $search_class_subject[1])
            ->where('class_details.adviser_id', $FacultyInformation->id)
            ->where('school_years.current', '!=', 0)              
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
                class_details.school_year_id,
                faculty_informations.first_name, faculty_informations.middle_name ,  faculty_informations.last_name,
                school_years.school_year
            '))
            ->orderBY('class_details.school_year_id','DESC')
            ->first();


        
            $EnrollmentMale = \App\Enrollment::join('student_informations', 'student_informations.id', '=', 'enrollments.student_information_id')
                ->join('class_details', 'class_details.id', '=', 'enrollments.class_details_id')
                ->join('school_years', 'school_years.id' ,'=', 'class_details.school_year_id')
                ->join('users', 'users.id', '=', 'student_informations.user_id')
                ->whereRaw('class_details.adviser_id = '. $FacultyInformation->id)
                // ->whereRaw('enrollments.class_details_id = '. $class_id)
                ->whereRaw('student_informations.gender = 1')    
                ->where('school_years.current', '!=', 0)     
                ->select(\DB::raw("
                    enrollments.id as e_id,
                    enrollments.attendance,
                    enrollments.j_lacking_unit,
                    enrollments.s1_lacking_unit,
                    enrollments.s2_lacking_unit,
                    student_informations.id as student_information_id,
                    users.username,
                    CONCAT(student_informations.last_name, ' ', student_informations.first_name, ' ', student_informations.middle_name) as student_name
                "))
                ->orderBY('student_name', 'ASC')
                ->get();

            $EnrollmentFemale = \App\Enrollment::join('student_informations', 'student_informations.id', '=', 'enrollments.student_information_id')
            ->join('class_details', 'class_details.id', '=', 'enrollments.class_details_id')
            ->join('school_years', 'school_years.id' ,'=', 'class_details.school_year_id')
            ->join('users', 'users.id', '=', 'student_informations.user_id')
            ->whereRaw('class_details.adviser_id = '. $FacultyInformation->id)
            ->where('school_years.current', '!=', 0)  
            // ->whereRaw('enrollments.class_details_id = '. $class_id)
            ->whereRaw('student_informations.gender = 2')       
            ->select(\DB::raw("
                enrollments.id as e_id,
                enrollments.attendance,
                student_informations.id  as student_information_id,
                users.username,
                CONCAT(student_informations.last_name, ' ', student_informations.first_name, ' ', student_informations.middle_name) as student_name
            "))
            ->orderBY('student_name', 'ASC')
            ->get();

                
                //======================male 1st sem 
                if($ClassSubjectDetail->grade_level > 10)
                {
                    $Enrollment = \App\Enrollment::join('class_details', 'class_details.id', '=', 'enrollments.class_details_id')
                    // ->join('student_enrolled_subjects', 'student_enrolled_subjects.enrollments_id', '=', 'enrollments.id')
                    ->join('class_subject_details', 'class_subject_details.class_details_id', '=', 'class_details.id')
                    ->join('rooms', 'rooms.id', '=', 'class_details.room_id')
                    ->join('faculty_informations', 'faculty_informations.id', '=', 'class_subject_details.faculty_id')
                    ->join('section_details', 'section_details.id', '=', 'class_details.section_id')
                    ->join('subject_details', 'subject_details.id', '=', 'class_subject_details.subject_id')
                    ->where('student_information_id', $EnrollmentMale[0]->student_information_id)
                    // ->where('class_subject_details.status', 1)
                    ->where('class_subject_details.status', '!=', 0)
                    ->where('enrollments.status', 1)
                    ->where('class_details.status', 1)
                    ->where('class_subject_details.sem', $ClassSubjectDetail->sem)
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
                        subject_details.id AS subject_id,
                        subject_details.subject_code,
                        subject_details.subject,
                        rooms.room_code,
                        section_details.section
                        
                    "))
                    ->orderBy('class_subject_details.class_subject_order', 'ASC')
                    ->get();

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
                            class_details.adviser_id,
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

                    $StudentEnrolledSubject1 = \App\StudentEnrolledSubject::where('enrollments_id', $EnrollmentMale[0]->e_id)
                    ->where('subject_id', $Enrollment[0]->subject_id)
                    ->where('sem', $ClassSubjectDetail->sem)
                    ->first();

                    $StudentEnrolledSubject = \App\StudentEnrolledSubject::where('enrollments_id', $Enrollment[0]->enrollment_id)
                     ->get();

                     

                     
                    if($ClassSubjectDetail->sem == 1)
                    {
                        if(round($StudentEnrolledSubject1->fir_g) != 0 && round($StudentEnrolledSubject1->sec_g) != 0)
                        {
                            $totalsum = 0;
                            $count_subjects1 = \App\StudentEnrolledSubject::where('enrollments_id', $Enrollment[0]->enrollment_id)
                                ->where('sem', 1)->where('status', '!=', 0)->count();
                            // echo $count_subjects1;

                            $StudentEnrolledSubject = \App\StudentEnrolledSubject::where('enrollments_id', $Enrollment[0]->enrollment_id)
                                ->where('sem', $ClassSubjectDetail->sem)
                                ->get();
                            
                            foreach($StudentEnrolledSubject as $key => $data)
                            {
                                round($final_ave = (round($data->fir_g) + round($data->sec_g)) / 2);                                                                                
                                $totalsum += round($final_ave) / 9 ;
                            }
                                                                
                        }
                    }
                    else
                    {
                        if(round($StudentEnrolledSubject1->thi_g) != 0 && round($StudentEnrolledSubject1->fou_g) != 0)
                        {
                            $totalsum = 0;
                            $count_subjects1 = \App\StudentEnrolledSubject::where('enrollments_id', $Enrollment[0]->enrollment_id)
                                ->where('sem', 2)->where('status', '!=', 0)->count();
                            // echo $count_subjects1;

                            $StudentEnrolledSubject = \App\StudentEnrolledSubject::where('enrollments_id', $Enrollment[0]->enrollment_id)
                                ->where('sem', 2)
                                ->get();
                            
                            foreach($StudentEnrolledSubject as $key => $data)
                            {
                                round($final_ave = (round($data->thi_g) + round($data->fou_g)) / 2);                                                                                
                                $totalsum += round($final_ave) / $count_subjects1 ;
                            }
                                                                
                        }
                    }



                    $Enrollment_female = \App\Enrollment::join('class_details', 'class_details.id', '=', 'enrollments.class_details_id')
                    // ->join('student_enrolled_subjects', 'student_enrolled_subjects.enrollments_id', '=', 'enrollments.id')
                    ->join('class_subject_details', 'class_subject_details.class_details_id', '=', 'class_details.id')
                    ->join('rooms', 'rooms.id', '=', 'class_details.room_id')
                    ->join('faculty_informations', 'faculty_informations.id', '=', 'class_subject_details.faculty_id')
                    ->join('section_details', 'section_details.id', '=', 'class_details.section_id')
                    ->join('subject_details', 'subject_details.id', '=', 'class_subject_details.subject_id')
                    ->where('student_information_id', $EnrollmentFemale[0]->student_information_id)
                    // ->where('class_subject_details.status', 1)
                    ->where('class_subject_details.status', '!=', 0)
                    ->where('enrollments.status', 1)
                    ->where('class_details.status', 1)
                    ->where('class_subject_details.sem', $ClassSubjectDetail->sem)
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
                        subject_details.id AS subject_id,
                        subject_details.subject_code,
                        subject_details.subject,
                        rooms.room_code,
                        section_details.section
                        
                    "))
                    ->orderBy('class_subject_details.class_subject_order', 'ASC')
                    ->get();

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
                            class_details.adviser_id,
                            section_details.section,
                            section_details.grade_level as section_grade_level,
                            school_years.school_year,
                            rooms.room_code,
                            rooms.room_description
                        ')
                        ->where('section_details.status', 1)
                        // ->where('school_years.current', 1)
                    ->where('class_details.id', $Enrollment_female[0]->cid)
                    ->first();

                    $StudentEnrolledSubject_female = \App\StudentEnrolledSubject::where('enrollments_id', $EnrollmentFemale[0]->e_id)
                    ->where('subject_id', $Enrollment_female[0]->subject_id)
                    ->where('sem', $ClassSubjectDetail->sem)
                    ->first();

                    $StudentEnrolledSubject_fem = \App\StudentEnrolledSubject::where('enrollments_id', $Enrollment_female[0]->enrollment_id)
                     ->get();

                     

                     
                    if($ClassSubjectDetail->sem == 1)
                    {
                        if(round($StudentEnrolledSubject_female->fir_g) != 0 && round($StudentEnrolledSubject_female->sec_g) != 0)
                        {
                            $totalsum = 0;
                            $count_subjects1 = \App\StudentEnrolledSubject::where('enrollments_id', $Enrollment_female[0]->enrollment_id)
                                ->where('sem', 1)->where('status', '!=', 0)->count();
                            // echo $count_subjects1;

                            $StudentEnrolledSubject_fem = \App\StudentEnrolledSubject::where('enrollments_id', $Enrollment_female[0]->enrollment_id)
                                ->where('sem', $ClassSubjectDetail->sem)
                                ->get();
                            
                            foreach($StudentEnrolledSubject_fem as $key => $data)
                            {
                                round($final_ave = (round($data->fir_g) + round($data->sec_g)) / 2);                                                                                
                                $totalsum += round($final_ave) / 9 ;
                            }
                                                                
                        }
                    }
                    else
                    {
                        if(round($StudentEnrolledSubject_female->thi_g) != 0 && round($StudentEnrolledSubject_female->fou_g) != 0)
                        {
                            $totalsum = 0;
                            $count_subjects1 = \App\StudentEnrolledSubject::where('enrollments_id', $Enrollment_female[0]->enrollment_id)
                                ->where('sem', 2)->where('status', '!=', 0)->count();
                            // echo $count_subjects1;

                            $StudentEnrolledSubject_fem = \App\StudentEnrolledSubject::where('enrollments_id', $Enrollment_female[0]->enrollment_id)
                                ->where('sem', 2)
                                ->get();
                            
                            foreach($StudentEnrolledSubject_fem as $key => $data)
                            {
                                round($final_ave = (round($data->thi_g) + round($data->fou_g)) / 2);                                                                                
                                $totalsum += round($final_ave) / $count_subjects1 ;
                            }
                                                                
                        }
                    }


                        
                }
                else if($ClassSubjectDetail->grade_level < 11)
                {
                    $EnrollmentJuniorMale = \App\Enrollment::join('class_details', 'class_details.id', '=', 'enrollments.class_details_id')
                    // ->join('student_enrolled_subjects', 'student_enrolled_subjects.enrollments_id', '=', 'enrollments.id')
                        ->join('class_subject_details', 'class_subject_details.class_details_id', '=', 'class_details.id')
                        ->join('rooms', 'rooms.id', '=', 'class_details.room_id')
                        ->join('faculty_informations', 'faculty_informations.id', '=', 'class_subject_details.faculty_id')
                        ->join('section_details', 'section_details.id', '=', 'class_details.section_id')
                        ->join('subject_details', 'subject_details.id', '=', 'class_subject_details.subject_id')
                        ->where('student_information_id', $EnrollmentMale[0]->student_information_id)
                        // ->where('class_subject_details.status', 1)
                        ->where('class_subject_details.status', '!=', 0)
                        ->where('enrollments.status', 1)
                        ->where('class_details.status', 1)
                        // ->where('class_subject_details.sem', 2)
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
                            subject_details.id AS subject_id,
                            subject_details.subject_code,
                            subject_details.subject,
                            rooms.room_code,
                            section_details.section
                            
                        "))
                        ->orderBy('class_subject_details.class_subject_order', 'ASC')
                        ->get();

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
                                class_details.adviser_id,
                                section_details.section,
                                section_details.grade_level as section_grade_level,
                                school_years.school_year,
                                rooms.room_code,
                                rooms.room_description
                            ')
                            ->where('section_details.status', 1)
                            // ->where('school_years.current', 1)
                        ->where('class_details.id', $EnrollmentMale[0]->cid)
                        ->first();

                        $StudentEnrolledSubject1 = \App\StudentEnrolledSubject::where('enrollments_id', $EnrollmentMale[0]->e_id)
                        ->where('subject_id', $EnrollmentJuniorMale[0]->subject_id)
                        // ->where('sem', 1)
                        ->first();

                        $StudentEnrolledSubject = \App\StudentEnrolledSubject::where('enrollments_id', $EnrollmentJuniorMale[0]->enrollment_id)
                        ->get();

                        
                    

                    if(round($StudentEnrolledSubject1->fir_g) != 0 && round($StudentEnrolledSubject1->sec_g) != 0 && round($StudentEnrolledSubject1->thi_g) != 0 && round($StudentEnrolledSubject1->fou_g) != 0)
                    {
                        
                        $totalsum = 0;
                        $count_subjects1 = \App\StudentEnrolledSubject::where('enrollments_id', $EnrollmentJuniorMale[0]->enrollment_id)
                            ->where('status', '!=', 0)->count();
                        // echo $count_subjects1;

                        $StudentEnrolledSubject = \App\StudentEnrolledSubject::where('enrollments_id', $EnrollmentJuniorMale[0]->enrollment_id)
                            ->get();
                        
                        foreach($StudentEnrolledSubject as $key => $data)
                        {
                            round($final_ave = (round($data->fir_g) + round($data->sec_g) + round($data->thi_g) + round($data->fou_g)) / 4);                                                                                
                            $totalsum += round($final_ave) / 9 ;
                        }
                                                            
                    }     
                    
                }            

            return view('control_panel_faculty.encode_remarks.index',
                compact('EnrollmentMale','EnrollmentFemale','ClassSubjectDetail','SchoolYear','DateRemarks',
                'FacultyInformation','StudentEnrolledSubject1','StudentEnrolledSubject','totalsum','ClassDetail','Student_info',
                'Enrollment_female','StudentEnrolledSubject_female','StudentEnrolledSubject_fem'))->render();
        
        
        // return view('control_panel_faculty.encode_remarks.index');
    }

    public function save(Request $request)
    {
        $stud_id = $request->stud_id;
        $class_detail_id = $request->print_sy;
        $s_year = $request->s_year;

        $data = $request->all();

        try {
            // \App\StudentInformation::where('id', $request->stud_id)
            // ->update([
            //     // 'birthdate'=>$data->birthdate ? date('Y-m-d', strtotime($request->birthdate)) : NULL,
            //     'age_june'=>$data['age_june'], 
            //     'age_may'=>$data['age_may'],
            //      'c_address'=>$data['address'], 
            //     'guardian'=>$data['guardian']
            //     ]);            
            // $Student_info = new \App\LackingUnit();
            $Student_info = \App\Enrollment::where('student_information_id', $stud_id)
            ->where('class_details_id', $class_detail_id)->first();
            // $Student_info->student_id = $stud_id;
            // $Student_info->class_detail_id = $class_detail_id;
                

                if($request->level < 11)
                {             
                    $Student_info->j_lacking_unit = $data['jlacking_units'];
                    $Student_info->s1_lacking_unit = "none";
                    $Student_info->s2_lacking_unit = "none";
                    $Student_info->save(); 
                }
                else if($request->level > 10)
                {
                    if($request->sem == 1)
                    {
                        $Student_info->j_lacking_unit = "none";
                        $Student_info->s1_lacking_unit = $data['s1_lacking_units'];
                        $Student_info->s2_lacking_unit = "none";
                        $Student_info->save(); 
                    }
                    else
                    {
                        $Student_info->j_lacking_unit = "none";
                        $Student_info->s1_lacking_unit = "none";
                        $Student_info->s2_lacking_unit = $data['s2_lacking_units'];
                        $Student_info->save(); 
                    }                                        
                }  

                     
            // $Student_info->birthdate = $request->birthdate ? date('Y-m-d', strtotime($request->birthdate)) : NULL;;
            // $Student_info->age_june = $data['age_june'];
            // $Student_info->age_may = $data['age_may'];
            // $Student_info->c_address = $data['address'];
            // $Student_info->guardian = $data['guardian'];
            // $Student_info->save();

            return response()->json(['res_code' => 0, 'res_msg' => 'Remarks successfully saved.',]);
        }catch (Illuminate\Contracts\Encryption\DecryptException $e) {
            return "Invalid parameter";
        }
       
    }

    
}
