@extends('control_panel_student.layouts.master')

@section ('styles') 
@endsection

@section ('content_title')
    Grade Sheet
@endsection

@section ('content')

    @if($grade_level == 11 || $grade_level == 12)

   
        <div class="box">
            <div class="overlay hidden" id="js-loader-overlay"><i class="fa fa-refresh fa-spin"></i></div>
            <div class="box-body">
                
                    <div class="container-fluid">
                            <h4><span class="logo-mini"><img src="{{ asset('/img/sja-logo.png') }}" style="height: 60px;"></span> <b>First Semester {{ $getSchoolYear->school_year}}  Grade-level/Section : <i style="color:red">{{ $Enrollment2->grade_level .' - '. $Enrollment2->section}}</i></b></h4>
                    </div>
                <hr/>
                <!--<button class="btn btn-flat pull-right btn-primary" id="js-btn_print"><i class="fa fa-file-pdf"></i> Print</button>-->
                <div class="js-data-container">
                    <table class="table no-margin table-bordered table-striped">
                        <thead>
                            <tr>
                                <th style="width: 40%">Subject</th>
                                {{--  @if ($grade_level >= 11) 
                                    <th>First Semister</th>
                                    <th>Second Semister</th>
                                @elseif($grade_level <= 10)  --}}
                                    <th style="width: 11%;text-align:center">First Quarter</th>
                                    <th style="width: 11%;text-align:center">Second Quarter</th>
                                    {{-- <th>Third Grading</th>
                                    <th>Fourth Grading</th> --}}
                                {{--  @endif  --}}
                                @foreach ($GradeSheetData1 as $key => $data)
                                    
                                        @if (!$data->fir_g == 0 && !$data->sec_g  == 0 && !$data->thi_g  == 0 && !$data->fou_g == 0)
                                            <th style="text-align:center">Weighted Average</th>
                                            @break
                                        @else
                                            <th style="text-align:center">Final Average</th>
                                            @break
                                        @endif
                                        
                                @endforeach
                                <th>Remarks</th>
                                {{--  <th>Time</th>
                                <th>Days</th>
                                <th>Room</th>  --}}
                                {{-- <th>Grade & Section</th> --}}
                                <th>Faculty</th>
                            </tr>
                        </thead>
                        <tbody>
                                <?php 
                                    $SchoolYear = \App\SchoolYear::where('current', 1)->first();
                                    
                                    $EnrollmentFirstSem = \App\Enrollment::join('class_details', 'class_details.id', '=', 'enrollments.class_details_id')
                                    // ->join('student_enrolled_subjects', 'student_enrolled_subjects.enrollments_id', '=', 'enrollments.id')
                                    ->join('class_subject_details', 'class_subject_details.class_details_id', '=', 'class_details.id')
                                    ->join('rooms', 'rooms.id', '=', 'class_details.room_id')
                                    ->join('faculty_informations', 'faculty_informations.id', '=', 'class_subject_details.faculty_id')
                                    ->join('section_details', 'section_details.id', '=', 'class_details.section_id')
                                    ->join('subject_details', 'subject_details.id', '=', 'class_subject_details.subject_id')
                                    ->where('student_information_id', $StudentInformation->id)
                                    // ->where('faculty_informations.id', $ClassDetail->adviser_id)
                                    // ->where('class_subject_details.status', 1)
                                    ->where('class_subject_details.status', '!=', 0)
                                    ->where('class_subject_details.sem', 1)
                                    ->where('enrollments.status', 1)
                                    ->where('class_details.status', 1)                            
                                    ->where('class_details.school_year_id', $SchoolYear->id)
                                    ->select(\DB::raw("
                                        enrollments.id as enrollment_id,
                                        enrollments.class_details_id as cid,
                                        enrollments.attendance_first,
                                        enrollments.attendance_second,
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

                                    $StudentEnrolledSubject1stSem = \App\StudentEnrolledSubject::where('enrollments_id', $EnrollmentFirstSem[0]->enrollment_id)
                                    ->where('sem', 1)->where('status', 1)
                                    ->get();
                                ?>
                                @if ($StudentEnrolledSubject1stSem)
                                @foreach ($StudentEnrolledSubject1stSem as $key => $data)
                                    <tr>
                                        <td>
                                            <?php  
                                                $subject = \App\ClassSubjectDetail::where('id', $data->class_subject_details_id)
                                                ->first();
                                                echo \App\SubjectDetail::where('id', $subject->subject_id)->first()->subject;                                                     
                                            ?>
                                        </td>
                                        @if ($data->fir_g == 0 && $data->sec_g  == 0 && $data->thi_g  == 0 && $data->fou_g == 0)
                                            <td colspan="6" class="text-center text-red">Grade not yet finalized</td>
                                        @else       
                                                <?php
                                                    $average = $data->fir_g + $data->sec_g  / 2; 
                                                    $final_a =  $average / 2;
                                                ?>                                      
                                                <td><center>{{ $data->fir_g ? round($data->fir_g) : '' }}</center></td>
                                                <td><center>{{ $data->sec_g ? round($data->sec_g) : '' }}</center></td>
                                                {{-- <td><center>{{ $data->thi_g ? round($data->thi_g) : '' }}</center></td>
                                                <td><center>{{ $data->fou_g ? round($data->fou_g) : '' }}</center></td> --}}
                                                
                                                
                                                @if ($data->fir_g == 0 && $data->sec_g  == 0)
                                                <td>
                                                        <center>{{ $final_a ? round($final_a) : '' }}</center>
                                                </td>    
                                                <td style="color:{{ round($final_a) >= 75 ? 'green' : 'red' }};"><center><strong>{{ round($final_a) >= 75 ? 'Passed' : 'Failed' }}</strong></center></td>
                                                    
                                                @else
                                                    <td></td>
                                                    <td></td>
                                                        <!--<td style="color:{{ round($final_a) >= 75 ? 'green' : 'red' }};"><center><strong>{{ round($final_a) >= 75 ? 'Passed' : 'Failed' }}</strong></center></td>-->
                                                

                                                    {{-- <td style="color:{{ round($data->final_g) >= 75 ? 'green' : 'red' }};"><center><strong>{{ round($data->final_g) >= 75 ? 'Passed' : 'Failed' }}</strong></center></td> --}}
                                                @endif                                                
                                        @endif
                                        
                                        <td>
                                            <?php 
                                                // $c_id = \App\Enrollment::where('id', $Enrollment[0]->enrollment_id)->get();
                                                // echo $c_id[0]->class_details_id;

                                                $faculty = \App\ClassSubjectDetail::where('id', $data->class_subject_details_id)->first();
                                            //echo $faculty->faculty_id; 
                                                echo \App\FacultyInformation::where('id', $faculty->faculty_id)->first()->last_name;                                             
                                            ?>
                                            ,
                                            <?php 
                                                echo \App\FacultyInformation::where('id', $faculty->faculty_id)->first()->first_name;                                             
                                            ?>
                                            
                                            <?php 
                                                echo\App\FacultyInformation::where('id', $faculty->faculty_id)->first()->middle_name;                                             
                                            ?>
                                            
                                            
                                            
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                
                            @endif
                            
                        </tbody>
                    </table>

                    

                </div>

                <?php
                    $student_attendance = [];
                    $table_header = 
                    [
                        ['key' => 'Jun',],
                        ['key' => 'Jul',],
                        ['key' => 'Aug',],
                        ['key' => 'Sep',],
                        ['key' => 'Oct',],
                        ['key' => 'total',],
                    ];
                        
                        $attendance_data = json_decode(json_encode([
                            'days_of_school' => [
                                0, 0, 0, 0, 0, 
                            ],
                            'days_present' => [
                                0, 0, 0, 0, 0,
                            ],
                            'days_absent' => [
                                0, 0, 0, 0, 0,
                            ],
                            'times_tardy' => [
                                0, 0, 0, 0, 0,
                            ]
                        ]));
                        
                        
                        $attendance_data = json_decode($EnrollmentFirstSem[0]->attendance_first);

                        
                    //    $attendance_data;

                    //     if ($EnrollmentMale[0]->attendance) {
                    //         $attendance_data = json_decode($EnrollmentMale[0]->attendance);
                    //     }    

                        $student_attendance = [
                            // 'student_name'      => $EnrollmentMale[0]->student_name,
                            'attendance_data'   => $attendance_data,
                            'table_header'      => $table_header,
                            'days_of_school_total' => array_sum($attendance_data->days_of_school),
                            'days_present_total' => array_sum($attendance_data->days_present),
                            'days_absent_total' => array_sum($attendance_data->days_absent),
                            'times_tardy_total' => array_sum($attendance_data->times_tardy),
                        ];

                        
                        ?>
                    <p class="report-progress-left m0"  style="margin-top: 2em"><b>ATTENDANCE RECORD</b></p>
                    <table style="width:100%; margin-bottom: 1em" class="table no-margin table-bordered table-striped">
                        <tr>
                            <th style="text-align:center">
                                <center>
                                @foreach ($student_attendance['table_header'] as $data)
                                <th style="text-align:center">{{ $data['key'] }}</th>
                                @endforeach
                                </center>
                            </th>
                                
                        </tr>
                        <tr>
                            <th>
                                Days of School
                            </th>
                            @foreach ($student_attendance['attendance_data']->days_of_school as $key => $data)
                                <th style="width:7%; text-align:center">
                                    {{ $data }}
                                </th>
                            @endforeach
                            <th class="days_of_school_total" style="text-align:center">
                                {{ $student_attendance['days_of_school_total'] }}
                            </th>
                        </tr>
                        <tr>
                            <th>
                                Days Present
                            </th>
                            @foreach ($student_attendance['attendance_data']->days_present as $key => $data)
                            <th style="width:7%; text-align:center">
                                {{ $data }} 
                            </th>
                            @endforeach
                            <th class="days_present_total" style="text-align:center">
                                {{ $student_attendance['days_present_total'] }}
                            </th>
                        </tr>
                        <tr>
                            <th>
                                Days Absent
                            </th>
                            @foreach ($student_attendance['attendance_data']->days_absent as $key => $data)
                            <th style="width:7%; text-align:center">
                                {{ $data }}  
                            </th>
                            @endforeach
                            <th class="days_absent_total" style="text-align:center">
                                {{ $student_attendance['days_absent_total'] }}
                            </th>
                        </tr>
                        <tr>
                            <th>
                                Times Tardy
                            </th>
                            @foreach ($student_attendance['attendance_data']->times_tardy as $key => $data)
                            <th style="width:7%; text-align:center">
                                {{ $data }}  
                            </th>
                            @endforeach
                            <th class="times_tardy_total" style="text-align:center">
                                {{ $student_attendance['times_tardy_total'] }}
                            </th>
                        </tr>
                    </table>
            </div>
        </div>


        <div class="box">
            <div class="box-body">
                    <h4><span class="logo-mini"><img src="{{ asset('/img/sja-logo.png') }}" style="height: 60px;"></span> <b>Second Semester {{ $getSchoolYear->school_year }}  Grade-level/Section : <i style="color:red">{{ $Enrollment2->grade_level .' - '. $Enrollment2->section}}</i></b></h4>
                    <hr/>
                    <!--<button class="btn btn-flat pull-right btn-primary" id="js-btn_print"><i class="fa fa-file-pdf"></i> Print</button>-->
                    
                    
                    <div class="js-data-container">
                        <table class="table no-margin table-bordered table-striped">
                            <thead>
                                <tr>
                                        <th style="width: 40%">Subject</th>
                                    
                                        <th style="width: 11%; text-align:center">First Quarter</th>
                                        <th style="width: 11%; text-align:center">Second Quarter</th>
                                    
                                    @foreach ($GradeSheetData1 as $key => $data)
                                    
                                        @if (!$data->fir_g == 0 && !$data->sec_g  == 0 && !$data->thi_g  == 0 && !$data->fou_g == 0)
                                            <th style="text-align:center">Weighted Average</th>
                                            @break
                                        @else
                                        <th style="text-align:center">Final Average</th>
                                            @break
                                        @endif
                                        
                                    @endforeach
                                    <th>Remarks</th>
                                    
                                    <th>Faculty</th>
                                </tr>
                            </thead>
                            <tbody>
                                    <?php 
                                    $SchoolYear = \App\SchoolYear::where('current', 1)->first();
                                    
                                    $EnrollmentSecondSem = \App\Enrollment::join('class_details', 'class_details.id', '=', 'enrollments.class_details_id')
                                    // ->join('student_enrolled_subjects', 'student_enrolled_subjects.enrollments_id', '=', 'enrollments.id')
                                    ->join('class_subject_details', 'class_subject_details.class_details_id', '=', 'class_details.id')
                                    ->join('rooms', 'rooms.id', '=', 'class_details.room_id')
                                    ->join('faculty_informations', 'faculty_informations.id', '=', 'class_subject_details.faculty_id')
                                    ->join('section_details', 'section_details.id', '=', 'class_details.section_id')
                                    ->join('subject_details', 'subject_details.id', '=', 'class_subject_details.subject_id')
                                    ->where('student_information_id', $StudentInformation->id)
                                    // ->where('faculty_informations.id', $ClassDetail->adviser_id)
                                    // ->where('class_subject_details.status', 1)
                                    ->where('class_subject_details.status', '!=', 0)
                                    ->where('class_subject_details.sem', 2)
                                    ->where('enrollments.status', 1)
                                    ->where('class_details.status', 1)                            
                                    ->where('class_details.school_year_id', $SchoolYear->id)
                                    ->select(\DB::raw("
                                        enrollments.id as enrollment_id,
                                        enrollments.class_details_id as cid,
                                        enrollments.attendance_first,
                                        enrollments.attendance_second,
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
                                    
                                    $StudentEnrolledSubject2ndSem = \App\StudentEnrolledSubject::where('enrollments_id', $EnrollmentSecondSem[0]->enrollment_id)
                                    ->where('sem', 2)->where('status', 1)
                                    ->get();
                                ?>
                                @if ($StudentEnrolledSubject2ndSem)
                                @foreach ($StudentEnrolledSubject2ndSem as $key => $data)
                                    <tr>
                                        <td>
                                            <?php  
                                                $subject = \App\ClassSubjectDetail::where('id', $data->class_subject_details_id)
                                                ->first();
                                                echo \App\SubjectDetail::where('id', $subject->subject_id)->first()->subject;                                                     
                                            ?>
                                        </td>
                                        @if ($data->fir_g == 0 && $data->sec_g  == 0 && $data->thi_g  == 0 && $data->fou_g == 0)
                                            <td colspan="6" class="text-center text-red">Grade not yet finalized</td>
                                        @else       
                                                <?php
                                                    $average = $data->thi_g + $data->fou_g  / 2; 
                                                    $final_a =  $average / 2;
                                                ?>                                      
                                                <td><center>{{ $data->thi_g ? round($data->thi_g) : '' }}</center></td>
                                                <td><center>{{ $data->fou_g ? round($data->fou_g) : '' }}</center></td>
                                                {{-- <td><center>{{ $data->thi_g ? round($data->thi_g) : '' }}</center></td>
                                                <td><center>{{ $data->fou_g ? round($data->fou_g) : '' }}</center></td> --}}
                                                
                                                
                                                @if ($data->thi_g == 0 && $data->fou_g  == 0)
                                                <td>
                                                        <center>{{ $final_a ? round($final_a) : '' }}</center>
                                                </td>    
                                                <td style="color:{{ round($final_a) >= 75 ? 'green' : 'red' }};">
                                                    <center><strong>{{ round($final_a) >= 75 ? 'Passed' : 'Failed' }}</strong></center>
                                                </td>
                                                    
                                                @else
                                                    <td></td>
                                                    <td></td>
                                                        <!--<td style="color:{{ round($final_a) >= 75 ? 'green' : 'red' }};"><center><strong>{{ round($final_a) >= 75 ? 'Passed' : 'Failed' }}</strong></center></td>-->
                                                

                                                    {{-- <td style="color:{{ round($data->final_g) >= 75 ? 'green' : 'red' }};"><center><strong>{{ round($data->final_g) >= 75 ? 'Passed' : 'Failed' }}</strong></center></td> --}}
                                                @endif                                                
                                        @endif
                                        
                                        <td>
                                            <?php 
                                                // $c_id = \App\Enrollment::where('id', $Enrollment[0]->enrollment_id)->get();
                                                // echo $c_id[0]->class_details_id;

                                                $faculty = \App\ClassSubjectDetail::where('id', $data->class_subject_details_id)->first();
                                            //echo $faculty->faculty_id; 
                                                echo \App\FacultyInformation::where('id', $faculty->faculty_id)->first()->last_name;                                             
                                            ?>
                                            ,
                                            <?php 
                                                echo \App\FacultyInformation::where('id', $faculty->faculty_id)->first()->first_name;                                             
                                            ?>
                                            
                                            <?php 
                                                echo\App\FacultyInformation::where('id', $faculty->faculty_id)->first()->middle_name;                                             
                                            ?>
                                            
                                            
                                            
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                
                            @endif
                            </tbody>

                    
                        </table>
                    </div>

                    <?php
                    $student_attendance = [];
                    $table_header = [
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
                                0, 0, 0, 0, 0, 
                            ],
                            'days_present' => [
                                0, 0, 0, 0, 0,
                            ],
                            'days_absent' => [
                                0, 0, 0, 0, 0,
                            ],
                            'times_tardy' => [
                                0, 0, 0, 0, 0,
                            ]
                        ]));
                        
                        
                        $attendance_data = json_decode($EnrollmentSecondSem[0]->attendance_second);

                        
                    //    $attendance_data;

                    //     if ($EnrollmentMale[0]->attendance) {
                    //         $attendance_data = json_decode($EnrollmentMale[0]->attendance);
                    //     }    

                        $student_attendance = [
                            // 'student_name'      => $EnrollmentMale[0]->student_name,
                            'attendance_data'   => $attendance_data,
                            'table_header'      => $table_header,
                            'days_of_school_total' => array_sum($attendance_data->days_of_school),
                            'days_present_total' => array_sum($attendance_data->days_present),
                            'days_absent_total' => array_sum($attendance_data->days_absent),
                            'times_tardy_total' => array_sum($attendance_data->times_tardy),
                        ];

                    
                ?>
                <p class="report-progress-left m0"  style="margin-top: 2em"><b>ATTENDANCE RECORD</b></p>
                <table style="width:100%; margin-bottom: 1em " class="table no-margin table-bordered table-striped">
                    <tr>
                        <th>
                            @foreach ($student_attendance['table_header'] as $data)
                                <th style="text-align:center">{{ $data['key'] }}</th>
                            @endforeach
                        </th>
                            
                    </tr>
                    <tr>
                        <th>
                            Days of School
                        </th>
                        @foreach ($student_attendance['attendance_data']->days_of_school as $key => $data)
                            <th style="width:7%;text-align:center">
                                {{ $data }}
                            </th>
                        @endforeach
                        <th class="days_of_school_total" style="text-align:center">
                            {{ $student_attendance['days_of_school_total'] }}
                        </th>
                    </tr>
                    <tr>
                        <th>
                            Days Present
                        </th>
                        @foreach ($student_attendance['attendance_data']->days_present as $key => $data)
                            <th style="width:7%;text-align:center">
                                {{ $data }} 
                            </th>
                        @endforeach
                        <th class="days_present_total" style="text-align:center">
                            {{ $student_attendance['days_present_total'] }}
                        </th>
                    </tr>
                    <tr>
                        <th>
                            Days Absent
                        </th>
                        @foreach ($student_attendance['attendance_data']->days_absent as $key => $data)
                            <th style="width:7%;text-align:center">
                                {{ $data }}  
                            </th>
                        @endforeach
                        <th class="days_absent_total" style="text-align:center">
                            {{ $student_attendance['days_absent_total'] }}
                        </th>
                    </tr>
                    <tr>
                        <th>
                            Times Tardy
                        </th>
                        @foreach ($student_attendance['attendance_data']->times_tardy as $key => $data)
                            <th style="width:7%;text-align:center">
                                {{ $data }}  
                            </th>
                        @endforeach
                        <th class="times_tardy_total" style="text-align:center">
                            {{ $student_attendance['times_tardy_total'] }}
                        </th>
                    </tr>
                </table>
                </div>
            
        </div>

    @else
        <div class="box">
            <div class="overlay hidden" id="js-loader-overlay"><i class="fa fa-refresh fa-spin"></i></div>
            <div class="box-body">
                   <h4><span class="logo-mini"><img src="{{ asset('/img/sja-logo.png') }}" style="height: 60px;"></span> <b> Grade-level/Section : <i style="color:red">{{  $Enrollment2->grade_level .' - '. $Enrollment2->section }}</i></b></h4>
                   <hr/>
                   
                        <div class="js-data-container">
                            <table class="table no-margin table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Subject</th>
                                        
                                        <th>First Grading</th>
                                        <th>Second Grading</th>
                                        <th>Third Grading</th>
                                        <th>Fourth Grading</th>
                                        {{--  @endif  --}}
                                        @foreach ($StudentEnrolledSubject as $key => $data)
                                        
                                            @if (!$data->fir_g == 0 && !$data->sec_g  == 0 && !$data->thi_g  == 0 && !$data->fou_g == 0)
                                                <th>Weighted Average</th>
                                                @break
                                            @else
                                                <th>Final Average</th>
                                                @break
                                            @endif
                                            
                                        @endforeach
                                        <th>Remarks</th>
                                        
                                        <th>Faculty</th>
                                    </tr>
                                </thead>
                                <tbody>
                                        <?php 
                                        $SchoolYear = \App\SchoolYear::where('current', 1)->first();
                                        
                                        $EnrollmentHS = \App\Enrollment::join('class_details', 'class_details.id', '=', 'enrollments.class_details_id')
                                        // ->join('student_enrolled_subjects', 'student_enrolled_subjects.enrollments_id', '=', 'enrollments.id')
                                        ->join('class_subject_details', 'class_subject_details.class_details_id', '=', 'class_details.id')
                                        ->join('rooms', 'rooms.id', '=', 'class_details.room_id')
                                        ->join('faculty_informations', 'faculty_informations.id', '=', 'class_subject_details.faculty_id')
                                        ->join('section_details', 'section_details.id', '=', 'class_details.section_id')
                                        ->join('subject_details', 'subject_details.id', '=', 'class_subject_details.subject_id')
                                        ->where('student_information_id', $StudentInformation->id)
                                        // ->where('faculty_informations.id', $ClassDetail->adviser_id)
                                        // ->where('class_subject_details.status', 1)
                                        ->where('class_subject_details.status', '!=', 0)
                                        ->where('class_subject_details.sem', 1)
                                        ->where('enrollments.status', 1)
                                        ->where('class_details.status', 1)                            
                                        ->where('class_details.school_year_id', $SchoolYear->id)
                                        ->select(\DB::raw("
                                            enrollments.id as enrollment_id,
                                            enrollments.class_details_id as cid,
                                            enrollments.attendance_first,
                                            enrollments.attendance_second,
                                            enrollments.attendance,
                                            class_details.grade_level,
                                            class_subject_details.id as class_subject_details_id,
                                            class_subject_details.class_subject_order,
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
        
                                        $StudentEnrolledSubj = \App\StudentEnrolledSubject::where('enrollments_id', $EnrollmentHS[0]->enrollment_id)
                                        ->where('sem', 1)->where('status', 1)
                                        ->get();
                                    ?>
                                    @if ($StudentEnrolledSubj)
                                        @foreach ($StudentEnrolledSubj as $key => $data)
                                            <tr>
                                                <td>
                                                    <?php  
                                                    $subject = \App\ClassSubjectDetail::where('id', $data->class_subject_details_id)
                                                        ->first();
                                                        echo \App\SubjectDetail::where('id', $subject->subject_id)->first()->subject;                                                     
                                                    ?>
                                                </td>
                                                @if ($data->fir_g == 0 && $data->sec_g  == 0 && $data->thi_g  == 0 && $data->fou_g == 0)
                                                    <td colspan="6" class="text-center text-red">Grade not yet finalized</td>
                                                @else       
                                                        <?php
                                                            $average = $data->fir_g + $data->sec_g + $data->thi_g + $data->fou_g / 4; 
                                                            $final_a =  $average / 4;
                                                        ?>                                      
                                                        <td><center>{{ $data->fir_g ? round($data->fir_g) : '' }}</center></td>
                                                        <td><center>{{ $data->sec_g ? round($data->sec_g) : '' }}</center></td>
                                                        <td><center>{{ $data->thi_g ? round($data->thi_g) : '' }}</center></td>
                                                        <td><center>{{ $data->fou_g ? round($data->fou_g) : '' }}</center></td>
                                                        
                                                        
                                                        @if ($data->fir_g == 0 && $data->sec_g  == 0 && $data->thi_g  == 0 && $data->fou_g == 0)
                                                        <td>
                                                                <center>{{ $final_a ? round($final_a) : '' }}</center>
                                                        </td>    
                                                        <td style="color:{{ round($final_a) >= 75 ? 'green' : 'red' }};"><center><strong>{{ round($final_a) >= 75 ? 'Passed' : 'Failed' }}</strong></center></td>
                                                            
                                                        @else
                                                            <td></td>
                                                            <td></td>
                                                                <!--<td style="color:{{ round($final_a) >= 75 ? 'green' : 'red' }};"><center><strong>{{ round($final_a) >= 75 ? 'Passed' : 'Failed' }}</strong></center></td>-->
                                                           

                                                            {{-- <td style="color:{{ round($data->final_g) >= 75 ? 'green' : 'red' }};"><center><strong>{{ round($data->final_g) >= 75 ? 'Passed' : 'Failed' }}</strong></center></td> --}}
                                                        @endif                                                
                                                @endif
                                                
                                                <td>
                                                    <?php 
                                                        // $c_id = \App\Enrollment::where('id', $Enrollment[0]->enrollment_id)->get();
                                                        // echo $c_id[0]->class_details_id;
        
                                                        $faculty = \App\ClassSubjectDetail::where('id', $data->class_subject_details_id)->first();
                                                       //echo $faculty->faculty_id; 
                                                        echo \App\FacultyInformation::where('id', $faculty->faculty_id)->first()->last_name;                                             
                                                    ?>
                                                    ,
                                                    <?php 
                                                        echo \App\FacultyInformation::where('id', $faculty->faculty_id)->first()->first_name;                                             
                                                    ?>
                                                    
                                                    <?php 
                                                        echo\App\FacultyInformation::where('id', $faculty->faculty_id)->first()->middle_name;                                             
                                                    ?>
                                                    
                                                    
                                                    
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        
                                    @endif
                                </tbody>
                            </table>
                            <br/><br/>
                                
                                @if($EnrollmentHS[0]->attendance=="")

                                @else
                                <p class="report-progress-left m0"  style="margin-top: 2em"><b>ATTENDANCE RECORD</b></p>
                                    <table style="margin-top: 2em" class="table no-margin table-bordered table-striped">
                                            <tr>
                                                                                                
                                                <?php
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
                                                    
                                                    if($EnrollmentHS[0])
                                                    {
                                                        $attendance_data = json_decode($EnrollmentHS[0]->attendance);
                                                    }
                                                    
                                                //    $attendance_data;

                                                //     if ($EnrollmentMale[0]->attendance) {
                                                //         $attendance_data = json_decode($EnrollmentMale[0]->attendance);
                                                //     }    

                                                    $student_attendance = [
                                                        // 'student_name'      => $EnrollmentMale[0]->student_name,
                                                        'attendance_data'   => $attendance_data,
                                                        'table_header'      => $table_header,
                                                        'days_of_school_total' => array_sum($attendance_data->days_of_school),
                                                        'days_present_total' => array_sum($attendance_data->days_present),
                                                        'days_absent_total' => array_sum($attendance_data->days_absent),
                                                        'times_tardy_total' => array_sum($attendance_data->times_tardy),
                                                    ];

                                                    
                                                    ?>
                                                    
                                                    
                                                

                                                    <th>
                                                        
                                                    </th>
                                                        @foreach ($student_attendance['table_header'] as $data)
                                                                <th style="text-align:center">{{ $data['key'] }}</th> 
                                                        {{-- / {{ json_encode($data) }}  --}}
                                                        @endforeach
                                                </tr>
                                                <tr>
                                                    <th>
                                                        Days of School
                                                    </th>
                                                    @foreach ($student_attendance['attendance_data']->days_of_school as $key => $data)
                                                        <th style="width:7%; text-align:center">
                                                            {{ $data }}
                                                        </th>                                                                        
                                                    @endforeach
                                                    <th class="days_of_school_total"  style="text-align:center">
                                                        {{ $student_attendance['days_of_school_total'] }}
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        Days Present
                                                    </th>
                                                    @foreach ($student_attendance['attendance_data']->days_present as $key => $data)
                                                        <th style="width:7%;text-align:center">
                                                            {{ $data }} 
                                                        </th>
                                                    @endforeach
                                                    <th class="days_present_total" style="text-align:center">
                                                        {{ $student_attendance['days_present_total'] }}
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        Days Absent
                                                    </th>
                                                    @foreach ($student_attendance['attendance_data']->days_absent as $key => $data)
                                                        <th style="width:7%; text-align:center">
                                                            {{ $data }}   
                                                        </th>
                                                    @endforeach
                                                    <th class="days_absent_total" style="text-align:center">
                                                        {{ $student_attendance['days_absent_total'] }}
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        Times Tardy
                                                    </th>
                                                    @foreach ($student_attendance['attendance_data']->times_tardy as $key => $data)
                                                        <th style="width:7%; text-align:center">
                                                            {{ $data }} 
                                                        </th>
                                                    @endforeach
                                                    <th class="times_tardy_total" style="text-align:center">
                                                        {{ $student_attendance['times_tardy_total'] }}
                                                    </th>
                                                </tr>
                                    </table>
                                @endif
                            
                        </div>
            </div>            
        </div>
    @endif
@endsection

@section ('scripts')
    <script src="{{ asset('cms/plugins/timepicker/bootstrap-timepicker.min.js') }}"></script>
    <script>
        var page = 1;
        function fetch_data () {
            var formData = new FormData($('#js-form_search')[0]);
            formData.append('page', page);
            loader_overlay();
            $.ajax({
                url : "{{ route('faculty.subject_class.list_students_by_class') }}",
                type : 'POST',
                data : formData,
                processData : false,
                contentType : false,
                success     : function (res) {
                    loader_overlay();
                    $('.js-data-container').html(res);
                }
            });
        }
        $(function () {

            $('body').on('submit', '#js-form_search', function (e) {
                e.preventDefault();
                if (!$('#search_class_subject').val()) {
                    alert('Please select a subject');
                    return;
                }
                fetch_data();
            });
            $('body').on('change', '#search_sy', function () {
                $.ajax({
                    url : "{{ route('faculty.subject_class.list_class_subject_details') }}",
                    type : 'POST',
                    {{--  dataType    : 'JSON',  --}}
                    data        : {_token: '{{ csrf_token() }}', search_sy: $('#search_sy').val()},
                    success     : function (res) {

                        $('#search_class_subject').html(res);
                    }
                })
            })
            $('body').on('click', '#js-btn_print', function (e) {
                e.preventDefault()
                const search_class_subject = $('#search_class_subject').val()
                const search_sy = $('#search_sy').val()
                window.open("{{ route('student.grade_sheet.print_grades') }}", '', 'height=800,width=800')
            })
        });
    </script>
@endsection