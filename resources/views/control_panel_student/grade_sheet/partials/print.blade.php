<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Student Gradesheet</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="main.css" />
    <script src="main.js"></script>
    <style>
            * {
                font-family: Arial, Times, serif;
            }
            .page-break {
                page-break-after: always;
            }
            th, td {
                border: 1px solid #000;
                padding: 3px;
            }
            table {
                width: 100%;
                border-spacing: 0;
                border-collapse: collapse;
                font-size : 11px;
            }
    
            .table-student-info {
                width: 600px;
            }
            
            .table-student-info th, .table-student-info td {
                border: none;
                padding: 0 2px 2px 2px;
            }
            .text-red {
                color : #dd4b39 !important;
            }
            small {
                font-size : 10px;
            }
            .text-center {
                text-align: center;
            }
            .heading1 {
                text-align: center;
                padding: 0;
                margin:0;
                font-size: 11px;
            }
            .heading2 {
                text-align: center;
                padding: 0;
                margin:0;
            }
            .heading2-title {
                font-family: "Old English Text MT", Times, serif;
            }
            .heading2-subtitle {
                font-size: 12px;
            }
            .p0 {
                padding: 0;
            }
            .m0 {
                margin: 0;
            }
    
            .student-info {
                font-size: 12px;
            }
    
            .logo {
                position: absolute;
            }
            .sja-logo {
                top: 10px;
                right: 10px;
            }
            .deped-bataan-logo {
                top: 10px;
                left: 10px;
            }
            .report-progress {
                text-align: center;
                font-size: 12px;
                font-weight: 700;
            }
            .report-progress-left {
                text-align: left;
                font-size: 12px;
                font-weight: 700;
            }
        </style>
</head>
<body>
    <?php 
        $Semester = \App\Semester::where('current', 1)->first()->id; 
    ?>
        <p class="heading1">Republic of the Philippines
                <p class="heading1">Department of Education</p>
                <p class="heading1">Region III</p>
                <p class="heading1">Division of Bataan</p>
                <br/>
                <h2 class="heading2 heading2-title">Saint John Academy</h2>
                <p class="heading2 heading2-subtitle">Dinalupihan, Bataan</p>
                <br/>
                <p class="report-progress m0">REPORT ON LEARNING PROGRESS AND ACHIEVEMENT</p>
                <p class="report-progress m0">( {{ $ClassDetail ?  $ClassDetail->section_grade_level >= 11 ? 'SENIOR HIGH SCHOOL' : 'JUNIOR HIGH SCHOOL' : ''}} )</p>
                <img style="margin-right: 8em"  class="logo sja-logo" width="100" src="{{ asset('img/sja-logo.png') }}" />
                <img style="margin-left: 8em" class="logo deped-bataan-logo" width="100" src="{{ asset('img/deped-bataan-logo.png') }}" />
                <br/>
                <table class="table-student-info">
                    <tr>
                        <td>
                            <p class="p0 m0 student-info"><b>Name</b> : {{ ucfirst($StudentInformation->last_name). ', ' .ucfirst($StudentInformation->first_name). ' ' . ucfirst($StudentInformation->middle_name) }}</p>
                        </td>
                        <td>
                            <p class="p0 m0 student-info"><b>LRN</b> : {{ $StudentInformation->user->username }}</p>
                        </td>
                    </tr>
                    
                    <tr>
                        <td>
                            <p class="p0 m0 student-info"><b>Birthdate</b> : {{ $StudentInformation->birthdate ? date_format(date_create($StudentInformation->birthdate), 'M d, Y') : '' }}</p>
                        </td>
                        <td>
                            <p class="p0 m0 student-info"><b>Age</b> : 
                             {{ $StudentInformation->age_may }} years old</p>
                             {{-- {{ $StudentInformation->birthdate ? date_diff(date_create($StudentInformation->birthdate), date_create(date("Y-m-d H:i:s")))->format('%y years old') : '' }} --}}
                        </td>
                    </tr>
                    
                    <tr>
                        <td>
                            <p class="p0 m0 student-info"><b>Grade & Section </b>: {{ $ClassDetail ? $ClassDetail->section_grade_level : '' }} - {{ $ClassDetail ? $ClassDetail->section : '' }}</p>
                        </td>
                        <td>
                            <p class="p0 m0 student-info"><b>Sex</b> : {{ $StudentInformation->gender == 1 ? "Male" : "Female" }}</p>
                        </td>
                    </tr>
                    
                    <tr>
                        <td>
                            <p class="p0 m0 student-info"><b>School</b> Year : {{ $ClassDetail ? $ClassDetail->school_year : '' }}</p>
                        </td>
                        <td>
                            <p class="p0 m0 student-info"><b>Curriculum</b> : K to 12 BASIC EDUCATION CURRICULUM</p>
                        </td>
                    </tr>
                    <tr>
                        
                        
                            @if ($grade_level >= 11)
                                @if($Semester == 1)
                                <td><p class="p0 m0 student-info"><b>Strand:</b> {{ $ClassDetail ? $ClassDetail->strand : '' }}</p></td>
                                    <td>
                                            <p class="p0 m0 student-info"><b>Semester</b> : <i style="color: red">First</i></p>
                                    </td>
                                @else
                                <td><p class="p0 m0 student-info"><b>Strand:</b> {{ $ClassDetail ? $ClassDetail->strand : '' }}</p></td>
                                    <td>
                                            <p class="p0 m0 student-info"><b>Semester</b> : <i style="color: red">Second</i></p>
                                    </td>
                                @endif
                            @endif
                        
                        
                    </tr>
                </table> 
    <br/>
    {{--  <h4>Subject : <span class="text-red"><i>{{ $ClassSubjectDetail->subject }}</i></span> Time : <span class="text-red"><i>{{ strftime('%r',strtotime($ClassSubjectDetail->class_time_from)) . ' - ' . strftime('%r',strtotime($ClassSubjectDetail->class_time_to)) }}</i></span> Days : <span class="text-red"><i>{{ $ClassSubjectDetail->class_days }}</i></span></h4>  --}}
    {{--  <h4>Grade & Section : <span class="text-red"><i>{{ $ClassSubjectDetail->grade_level . ' ' .$ClassSubjectDetail->section }}</i></span></h4>  --}}
    @if ($grade_level >= 11) 
        
        <?php 
            $Semester = \App\Semester::where('current', 1)->first()->id; 
        ?>
        @if($Semester == 1)
                <table class="table no-margin">
                        <thead>
                            <tr>
                                <th>Subject</th>
                                <th style="width: 100px">First Quarter</th>
                                <th style="width: 100px">Second Quarter</th>
                                <th>Final Grade</th>
                                <th>Remarks</th>
                                
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                
                                $SchoolYear = \App\SchoolYear::where('current', 1)->first();
                                
                                $Enrollment = \App\Enrollment::join('class_details', 'class_details.id', '=', 'enrollments.class_details_id')
                                // ->join('student_enrolled_subjects', 'student_enrolled_subjects.enrollments_id', '=', 'enrollments.id')
                                ->join('class_subject_details', 'class_subject_details.class_details_id', '=', 'class_details.id')
                                ->join('rooms', 'rooms.id', '=', 'class_details.room_id')
                                ->join('faculty_informations', 'faculty_informations.id', '=', 'class_subject_details.faculty_id')
                                ->join('section_details', 'section_details.id', '=', 'class_details.section_id')
                                ->join('subject_details', 'subject_details.id', '=', 'class_subject_details.subject_id')                                
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
                                    class_subject_details.class_subject_order,
                                    class_subject_details.class_details_id,
                                    CONCAT(faculty_informations.last_name, ', ', faculty_informations.first_name, ' ', faculty_informations.middle_name) as faculty_name,
                                    subject_details.id AS subject_id,
                                    subject_details.subject_code,
                                    subject_details.subject,
                                    rooms.room_code,
                                    section_details.section
                                    
                                "))
                                ->where('student_information_id', $StudentInformation->id)
                                ->where('class_subject_details.status', '!=', 0)
                                ->where('enrollments.status', 1)
                                ->where('class_details.status', 1)
                                ->where('class_subject_details.sem', 1)
                                ->where('class_details.school_year_id', $SchoolYear->id)
                                ->orderBy('class_subject_details.class_subject_order', 'ASC')
                                ->get();
                                
                                $StudentEnrolledSubject = \App\StudentEnrolledSubject::where('enrollments_id', $Enrollment[0]->enrollment_id)
                                ->where('sem', 1)
                                ->get();
                            ?>
                            @foreach($Enrollment as $key => $data)
                            <tr>
                                <td>
                                    <?php                                     

                                        $subject = \App\ClassSubjectDetail::where('id', $data->class_subject_details_id)                                        
                                            ->orderBY('class_subject_order', 'ASC')->get();

                                        echo \App\SubjectDetail::where('id', $subject[0]->subject_id)->first()->subject; 
                                        //echo $ClassSubjectDetail->subject;   
                                    ?>
                                </td>
                                
                                <td style="text-align: center">
                                    <?php 
                                         $StudentEnrolledSubject1 = \App\StudentEnrolledSubject::where('enrollments_id', $data->enrollment_id)
                                        ->where('subject_id', $data->subject_id)
                                        ->where('sem', 1)
                                        ->first(); 

                                        echo $StudentEnrolledSubject1->fir_g ? $StudentEnrolledSubject1->fir_g > 0 ? round($StudentEnrolledSubject1->fir_g) : '' : '';
                                        
                                    ?>
                                    {{-- {{ $data->fir_g ? $data->fir_g > 0  ? round($data->fir_g) : '' : '' }} --}}
                                 </td>
                                <td style="text-align: center">
                                    <?php 
                                         $StudentEnrolledSubject1 = \App\StudentEnrolledSubject::where('enrollments_id', $data->enrollment_id)
                                        ->where('subject_id', $data->subject_id)
                                        ->where('sem', 1)
                                        ->first(); 
                                        
                                        echo $StudentEnrolledSubject1->sec_g ? $StudentEnrolledSubject1->sec_g > 0 ? round($StudentEnrolledSubject1->sec_g) : '' : '';
                                    ?>
                                    {{-- {{ $data->sec_g ? $data->sec_g > 0  ? round($data->sec_g) : '' : '' }} --}}
                                </td>
                                <td style="text-align: center">
                                    <?php 
                                        $StudentEnrolledSubject1 = \App\StudentEnrolledSubject::where('enrollments_id', $data->enrollment_id)
                                        ->where('subject_id', $data->subject_id)
                                        ->where('sem', 1)
                                        ->first(); 

                                        if(round($StudentEnrolledSubject1->fir_g) != 0 && round($StudentEnrolledSubject1->sec_g) != 0)
                                        {
                                            echo round($final_ave = (round($StudentEnrolledSubject1->fir_g) + round($StudentEnrolledSubject1->sec_g)) / 2);
                                            // echo $final_ave = (round($data->fir_g) + round($data->sec_g)) / 2;
                                        }
                                        else 
                                        {
                                            echo "";
                                        }                                        
                                    ?>
                                </td>
                                @if(round($StudentEnrolledSubject1->fir_g) != 0 && round($StudentEnrolledSubject1->sec_g) != 0) 
                                    @if($final_ave && $final_ave > 74) 
                                        <td style="color:'green'; text-align: center"><strong>Passed</strong></td>
                                    @elseif($final_ave && $final_ave < 75) 
                                        <td style="color:'red'; text-align: center"><strong>Failed</strong></td>
                                    @endif
                                @else
                                    <td></td>
                                @endif                                
                                
                            </tr>
                            @endforeach
                            
                            <tr class="text-center">
                                    <td colspan="{{$grade_level <= 10 ? '5' : '3'}}"><b>General Average</b></td>
                                    {{--  <td colspan="{{$ClassDetail ? $ClassDetail->section_grade_level <= 10 ? '8' : '2' : '4'}}"><b>General Average</b></td>  --}}
                                    <td>
                                        <b>
                                            <?php 
                                                $StudentEnrolledSubject1 = \App\StudentEnrolledSubject::where('enrollments_id', $data->enrollment_id)
                                                ->where('subject_id', $data->subject_id)
                                                ->where('sem', 1)
                                                ->first();     
                                            ?>

                                            @if(round($StudentEnrolledSubject1->fir_g) != 0 && round($StudentEnrolledSubject1->sec_g) != 0)
                                                
                                                <?php
                                                 $totalsum = 0;
                                                 $count_subjects1 = \App\StudentEnrolledSubject::where('enrollments_id', $Enrollment[0]->enrollment_id)
                                                    ->where('sem', 1)->where('status', '!=', 0)->count();
                                                // echo $count_subjects1;

                                                $StudentEnrolledSubject = \App\StudentEnrolledSubject::where('enrollments_id', $Enrollment[0]->enrollment_id)
                                                    ->where('sem', 1)
                                                    ->get();
                                                ?>
                                                @foreach($StudentEnrolledSubject as $key => $data)
                                                <?php
                                                    
                                                    round($final_ave = (round($data->fir_g) + round($data->sec_g)) / 2);
                                                                                                
                                                    $totalsum += round($final_ave) / 9 ;   
                                                                                                         
                                                ?>
                                                @endforeach
                                                <?php
                                                 echo round($totalsum);
                                                ?>   
                                            @else
                                                
                                            @endif
                                        </b>
                                    </td>
                                    @if(round($StudentEnrolledSubject1->fir_g) != 0 && round($StudentEnrolledSubject1->sec_g) != 0)
                                        @if(round($totalsum) > 74) 
                                            <td style="color:'green';"><strong>Passed</strong></td>
                                            
                                        @elseif(round($totalsum) < 75) 
                                            
                                            <td style="color:'red';"><strong>Failed</strong></td>
                                        @else 
                                            <td></td>
                                        @endif
                                    @else
                                        <td></td>
                                    @endif
                                    
                            </tr>
                        </tbody>
                </table>
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
                    
                    
                    $attendance_data = json_decode($Enrollment[0]->attendance_first);

                    
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
                <p class="report-progress-left m0"  style="margin-top: .5em"><b>ATTENDANCE RECORD</b></p>
                <table style="width:100%; margin-bottom: 1em">
                    <tr>
                        <th>
                            
                        </th>
                            @foreach ($student_attendance['table_header'] as $data)
                                    <th>{{ $data['key'] }}</th>
                            @endforeach
                    </tr>
                    <tr>
                        <th>
                            Days of School
                        </th>
                        @foreach ($student_attendance['attendance_data']->days_of_school as $key => $data)
                            <th style="width:7%">{{ $data }}
                            </th>
                        @endforeach
                        <th class="days_of_school_total">
                            {{ $student_attendance['days_of_school_total'] }}
                        </th>
                    </tr>
                    <tr>
                        <th>
                            Days Present
                        </th>
                        @foreach ($student_attendance['attendance_data']->days_present as $key => $data)
                            <th style="width:7%">{{ $data }} 
                            </th>
                        @endforeach
                        <th class="days_present_total">
                            {{ $student_attendance['days_present_total'] }}
                        </th>
                    </tr>
                    <tr>
                        <th>
                            Days Absent
                        </th>
                        @foreach ($student_attendance['attendance_data']->days_absent as $key => $data)
                            <th style="width:7%">{{ $data }}  
                            </th>
                        @endforeach
                        <th class="days_absent_total">
                            {{ $student_attendance['days_absent_total'] }}
                        </th>
                    </tr>
                    <tr>
                        <th>
                            Times Tardy
                        </th>
                        @foreach ($student_attendance['attendance_data']->times_tardy as $key => $data)
                            <th style="width:7%">{{ $data }}  
                            </th>
                        @endforeach
                        <th class="times_tardy_total">
                            {{ $student_attendance['times_tardy_total'] }}
                        </th>
                    </tr>
                </table>
            
            <center>
                <table border="0" style="width: 80%">

                    <tr style="margin-top: .5em">
                        <td style="border: 0">Description</td>
                        <td style="border: 0">Grading Scale</td>
                        <td style="border: 0">Remarks</td>                
                    </tr>

                    <tr style="margin-top: .5em">
                        <td style="border: 0">Outstanding</td>
                        <td style="border: 0">90-100</td>
                        <td style="border: 0">Passed</td>                
                    </tr>

                    <tr style="margin-top: .5em">
                        <td style="border: 0">Very Satisfactory</td>
                        <td style="border: 0">85-89</td>
                        <td style="border: 0">Passed</td>                
                    </tr>

                    <tr style="margin-top: .5em">
                        <td style="border: 0">Satisfactory</td>
                        <td style="border: 0">80-84</td>
                        <td style="border: 0">Passed</td>                
                    </tr>

                    <tr style="margin-top: .5em">
                        <td style="border: 0">Fairly Satisfactory</td>
                        <td style="border: 0">75-79</td>
                        <td style="border: 0">Passed</td>                
                    </tr>

                    <tr style="margin-top: .5em">
                        <td style="border: 0">Did Not Meet expectations</td>
                        <td style="border: 0">Below 75</td>
                        <td style="border: 0">Failed</td>                
                    </tr>
                    <tr style="margin-top: .5em">
                        <td style="border: 0"></td>
                        <td style="border: 0"></td>
                        <td style="border: 0"></td>   
                    </tr>

                    <tr style="margin-top: .5em">
                        <td colspan="3" style="border: 0">Eligible to transfer and admission to:
                        
                            @if(round($StudentEnrolledSubject1->fir_g) != 0 && round($StudentEnrolledSubject1->sec_g) != 0)
                                @if(round($totalsum) > 74) 
                                    
                                        <strong><u>&nbsp;&nbsp;Grade {{ $ClassDetail->section_grade_level}} Second Semester&nbsp;&nbsp;&nbsp;&nbsp;</u></strong>
                                                                       
                                @elseif(round($totalsum) < 75) 
                                    
                                   <strong>Failed</strong>
                                @else 
                                    <td></td>
                                @endif
                            @else
                                <td></td>
                            @endif        
                        
                        </td>                
                    </tr>

                    <tr style="margin-top: .5em">
                        <td colspan="3" style="border: 0">Lacking units in:__________________________</td>                
                    </tr>
                    
                    <tr style="margin-top: .5em">
                        <td colspan="3" style="border: 0">Date:___<u>{{ $DateRemarks->s_date1 }}</u>____</td>                
                    </tr>
                    <tr style="margin-top: .5em">
                        <td colspan="3" style="border: 0">&nbsp;</td>   </tr>
                    {{-- <tr> <td colspan="3" style="border: 0">&nbsp;</td>   </tr> --}}
                
                    <tr style="margin-top: 0em">
                            <table border="0" style="width: 100%; margin-top: -1em">
                                    <tr>
                                            <td style="border: 0; width: 50%;">
                                            <center>
                                                <img class="profile-user-img img-responsive img-circle" id="img--user_photo" src="{{ $ClassDetail->e_signature ? \File::exists(public_path('/img/signature/'.$ClassDetail->e_signature)) ? asset('/img/signature/'.$ClassDetail->e_signature) : asset('/img/account/photo/blank-user.png') : asset('/img/account/photo/blank-user.png') }}" style="width:100px">
                                            </center>
                                        </td>
                                        <td style="border: 0; width: 50%;">
                                            <center>
                                                <img class="profile-user-img img-responsive img-circle" id="img--user_photo" src="{{ asset('/img/signature/principal_signature.png') }}" style="width:170px">
                                            </center>
                                        </td>
                                    </tr>
                            </table>
                            <table border="0" style="width: 100%; margin-top: -70px; margin-bottom: 0em">                                
                                <tr>
                                    <td style="border: 0; width: 50%; height: 100px">
                                        <span style="margin-left: 2em; text-transform: uppercase">
                                            <center>
                                            {{ $ClassDetail->first_name }} {{ $ClassDetail->middle_name }} {{ $ClassDetail->last_name }}</center>
                                            </br>
                                            <center style="margin-top: -1em">Adviser</center>
                                        </span>
                                    </td>
                                    <td style="border: 0; width: 50%; height: 100px">
                                            <span style="margin-left: 23em;">
                                                <center>Gemma R. Yao, Ph.D.</center>
                                                </br>
                                                <center style="margin-top: -1em">PRINCIPAL</center>
                                            </span>
                                        </td>
                                </tr>
                            </table>
                        
                    </tr>
                    

                </table>

                <div class="page-break"></div>
            </center>
        @else
        
            <table class="table no-margin">
                    <thead>
                        <tr>
                            <th>Subject</th>
                            <th style="width: 100px">First Quarter</th>
                            <th style="width: 100px">Second Quarter</th>
                            <th>Final Grade</th>
                            <th>Remarks</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $SchoolYear = \App\SchoolYear::where('current', 1)->first();
                            
                            $Enrollment = \App\Enrollment::join('class_details', 'class_details.id', '=', 'enrollments.class_details_id')
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
                            
                            $StudentEnrolledSubject = \App\StudentEnrolledSubject::where('enrollments_id', $Enrollment[0]->enrollment_id)
                            ->where('sem', 2)->where('status', 1)
                            ->get();
                        ?>

                        @foreach($Enrollment as $key => $data)
                            <tr>
                                <td>
                                    <?php                                     

                                        $subject = \App\ClassSubjectDetail::where('id', $data->class_subject_details_id)                                        
                                            ->orderBY('class_subject_order', 'ASC')->get();

                                        echo \App\SubjectDetail::where('id', $subject[0]->subject_id)->first()->subject; 
                                        //echo $ClassSubjectDetail->subject;   
                                    ?>
                                </td>
                                
                                <td style="text-align: center">
                                    <?php 
                                         $StudentEnrolledSubject1 = \App\StudentEnrolledSubject::where('enrollments_id', $data->enrollment_id)
                                        ->where('subject_id', $data->subject_id)
                                        ->where('sem', 2)
                                        ->first(); 

                                        echo $StudentEnrolledSubject1->thi_g ? $StudentEnrolledSubject1->thi_g > 0 ? round($StudentEnrolledSubject1->thi_g) : '' : '';
                                        
                                    ?>
                                    {{-- {{ $data->fir_g ? $data->fir_g > 0  ? round($data->fir_g) : '' : '' }} --}}
                                 </td>
                                <td style="text-align: center">
                                    <?php 
                                         $StudentEnrolledSubject1 = \App\StudentEnrolledSubject::where('enrollments_id', $data->enrollment_id)
                                        ->where('subject_id', $data->subject_id)
                                        ->where('sem', 2)
                                        ->first(); 
                                        
                                        echo $StudentEnrolledSubject1->fou_g ? $StudentEnrolledSubject1->fou_g > 0 ? round($StudentEnrolledSubject1->fou_g) : '' : '';
                                    ?>
                                    {{-- {{ $data->sec_g ? $data->sec_g > 0  ? round($data->sec_g) : '' : '' }} --}}
                                </td>
                                <td style="text-align: center">
                                    <?php 
                                        $StudentEnrolledSubject1 = \App\StudentEnrolledSubject::where('enrollments_id', $data->enrollment_id)
                                        ->where('subject_id', $data->subject_id)
                                        ->where('sem', 2)
                                        ->first(); 

                                        if(round($StudentEnrolledSubject1->thi_g) != 0 && round($StudentEnrolledSubject1->fou_g) != 0)
                                        {
                                            echo round($final_ave = (round($StudentEnrolledSubject1->thi_g) + round($StudentEnrolledSubject1->fou_g)) / 2);
                                            // echo $final_ave = (round($data->fir_g) + round($data->sec_g)) / 2;
                                        }
                                        else 
                                        {
                                            echo "";
                                        }                                        
                                    ?>
                                </td>
                                
                                @if(round($StudentEnrolledSubject1->thi_g) != 0 && round($StudentEnrolledSubject1->fou_g) != 0) 
                                    @if($final_ave && $final_ave > 74) 
                                        <td style="color:'green'; text-align: center"><strong>Passed</strong></td>
                                    @elseif($final_ave && $final_ave < 75) 
                                        <td style="color:'red'; text-align: center"><strong>Failed</strong></td>
                                    @endif
                                @else
                                    <td></td>
                                @endif                                
                                
                            </tr>
                        @endforeach
                        
                        <tr class="text-center">
                                <td colspan="{{$grade_level <= 10 ? '5' : '3'}}"><b>General Average</b></td>
                                {{--  <td colspan="{{$ClassDetail ? $ClassDetail->section_grade_level <= 10 ? '8' : '2' : '4'}}"><b>General Average</b></td>  --}}
                                <td>
                                        <b>
                                            <?php 
                                                $StudentEnrolledSubject1 = \App\StudentEnrolledSubject::where('enrollments_id', $Enrollment[0]->enrollment_id)
                                                ->where('subject_id', $data->subject_id)
                                                ->where('sem', 2)
                                                ->first();     
                                            ?>
                                            @if(round($StudentEnrolledSubject1->thi_g) != 0 && round($StudentEnrolledSubject1->fou_g) != 0)
                                                {{-- {{$$final_ave && $general_avg >= 0 ? round($general_avg) : '' }} --}}
                                                <?php
                                                 $totalsum = 0;
                                                 $count_subjects1 = \App\StudentEnrolledSubject::where('enrollments_id', $Enrollment[0]->enrollment_id)
                                                    ->where('sem', 2)->where('status', '!=', 0)->count();
                                                ?>
                                                @foreach($StudentEnrolledSubject as $key => $data)
                                                <?php
                                                    
                                                    round($final_ave = (round($data->thi_g) + round($data->fou_g)) / 2);                                                                                                
                                                    $totalsum+= round($final_ave) / $count_subjects1 ;   
                                                    // echo $sum;
                                                                                                         
                                                ?>
                                                @endforeach
                                                <?php
                                                 echo round($totalsum);
                                                ?>                                                
                                                
                                            @else
                                                
                                            @endif
                                        </b>
                                </td>
                                <?php 
                                    $StudentEnrolledSubject1 = \App\StudentEnrolledSubject::where('enrollments_id', $Enrollment[0]->enrollment_id)
                                        ->where('subject_id', $data->subject_id)
                                        ->where('sem', 2)
                                        ->first(); 
                                ?>
                                    @if(round($StudentEnrolledSubject1->thi_g) != 0 && round($StudentEnrolledSubject1->fou_g) != 0)
                                        @if(round($totalsum) > 74) 
                                            <td style="color:'green';"><strong>Passed</strong></td>
                                            
                                        @elseif(round($totalsum) < 75) 
                                            
                                            <td style="color:'red';"><strong>Failed</strong></td>
                                        @else 
                                            <td></td>
                                        @endif
                                    @else
                                        <td></td>
                                    @endif
                                
                            </tr>
                    </tbody>
            </table>

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
                    
                    
                    $attendance_data = json_decode($Enrollment[0]->attendance_second);

                    
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
            <p class="report-progress-left m0"  style="margin-top: .5em"><b>ATTENDANCE RECORD</b></p>
            <table style="width:100%; margin-bottom: 1em">
                <tr>
                    <th>
                        
                    </th>
                        @foreach ($student_attendance['table_header'] as $data)
                                <th>{{ $data['key'] }}</th>
                        @endforeach
                </tr>
                <tr>
                    <th>
                        Days of School
                    </th>
                    @foreach ($student_attendance['attendance_data']->days_of_school as $key => $data)
                        <th style="width:7%">{{ $data }}
                        </th>
                    @endforeach
                    <th class="days_of_school_total">
                        {{ $student_attendance['days_of_school_total'] }}
                    </th>
                </tr>
                <tr>
                    <th>
                        Days Present
                    </th>
                    @foreach ($student_attendance['attendance_data']->days_present as $key => $data)
                        <th style="width:7%">{{ $data }} 
                        </th>
                    @endforeach
                    <th class="days_present_total">
                        {{ $student_attendance['days_present_total'] }}
                    </th>
                </tr>
                <tr>
                    <th>
                        Days Absent
                    </th>
                    @foreach ($student_attendance['attendance_data']->days_absent as $key => $data)
                        <th style="width:7%">{{ $data }}  
                        </th>
                    @endforeach
                    <th class="days_absent_total">
                        {{ $student_attendance['days_absent_total'] }}
                    </th>
                </tr>
                <tr>
                    <th>
                        Times Tardy
                    </th>
                    @foreach ($student_attendance['attendance_data']->times_tardy as $key => $data)
                        <th style="width:7%">{{ $data }}  
                        </th>
                    @endforeach
                    <th class="times_tardy_total">
                        {{ $student_attendance['times_tardy_total'] }}
                    </th>
                </tr>
            </table>
        
        <center>
            <table border="0" style="width: 80%">

                <tr style="margin-top: .5em">
                    <td style="border: 0">Description</td>
                    <td style="border: 0">Grading Scale</td>
                    <td style="border: 0">Remarks</td>                
                </tr>

                <tr style="margin-top: .5em">
                    <td style="border: 0">Outstanding</td>
                    <td style="border: 0">90-100</td>
                    <td style="border: 0">Passed</td>                
                </tr>

                <tr style="margin-top: .5em">
                    <td style="border: 0">Very Satisfactory</td>
                    <td style="border: 0">85-89</td>
                    <td style="border: 0">Passed</td>                
                </tr>

                <tr style="margin-top: .5em">
                    <td style="border: 0">Satisfactory</td>
                    <td style="border: 0">80-84</td>
                    <td style="border: 0">Passed</td>                
                </tr>

                <tr style="margin-top: .5em">
                    <td style="border: 0">Fairly Satisfactory</td>
                    <td style="border: 0">75-79</td>
                    <td style="border: 0">Passed</td>                
                </tr>

                <tr style="margin-top: .5em">
                    <td style="border: 0">Did Not Meet expectations</td>
                    <td style="border: 0">Below 75</td>
                    <td style="border: 0">Failed</td>                
                </tr>
                <tr style="margin-top: .5em">
                    <td style="border: 0"></td>
                    <td style="border: 0"></td>
                    <td style="border: 0"></td>   
                </tr>

                <tr style="margin-top: .5em">
                    <td colspan="3" style="border: 0">Eligible to transfer and admission to:
                            @if(round($StudentEnrolledSubject1->thi_g) != 0 && round($StudentEnrolledSubject1->fou_g) != 0)
                            @if(round($totalsum) > 74) 
                                
                                    <strong><u>&nbsp;&nbsp;College&nbsp;&nbsp;&nbsp;&nbsp;</u></strong>
                                                                 
                            @elseif(round($totalsum) < 75) 
                                
                               <strong>Failed</strong>
                            @else 
                                <td></td>
                            @endif
                        @else
                            <td></td>
                        @endif       
                    </td>                
                </tr>

                <tr style="margin-top: .5em">
                    <td colspan="3" style="border: 0">Lacking units in:__________________________</td>                
                </tr>
                
                <tr style="margin-top: .5em">
                    <td colspan="3" style="border: 0">Date:___<u>{{ $DateRemarks->s_date2 }}</u>____</td>                
                </tr>
                <tr style="margin-top: .5em">
                     <td colspan="3" style="border: 0">&nbsp;</td>   </tr>
                {{-- <tr> <td colspan="3" style="border: 0">&nbsp;</td>   </tr> --}}
            
                <tr style="margin-top: 0em">
                        <table border="0" style="width: 100%; margin-top: -1em">
                                <tr>
                                        <td style="border: 0; width: 50%;">
                                        <center>
                                            <img class="profile-user-img img-responsive img-circle" id="img--user_photo" src="{{ $ClassDetail->e_signature ? \File::exists(public_path('/img/signature/'.$ClassDetail->e_signature)) ? asset('/img/signature/'.$ClassDetail->e_signature) : asset('/img/account/photo/blank-user.png') : asset('/img/account/photo/blank-user.png') }}" style="width:100px">
                                        </center>
                                    </td>
                                    <td style="border: 0; width: 50%;">
                                        <center>
                                            <img class="profile-user-img img-responsive img-circle" id="img--user_photo" src="{{ asset('/img/signature/principal_signature.png') }}" style="width:170px">
                                        </center>
                                    </td>
                                </tr>
                        </table>
                        <table border="0" style="width: 100%; margin-top: -70px; margin-bottom: 0em">
                            
                            <tr>
                                <td style="border: 0; width: 50%; height: 100px">
                                    <span style="margin-left: 2em; text-transform: uppercase">
                                        <center>
                                        {{ $ClassDetail->first_name }} {{ $ClassDetail->middle_name }} {{ $ClassDetail->last_name }}</center>
                                        </br>
                                        <center style="margin-top: -1em">Adviser</center>
                                    </span>
                                </td>
                                <td style="border: 0; width: 50%; height: 100px">
                                        <span style="margin-left: 23em;">
                                            <center>Gemma R. Yao, Ph.D.</center>
                                            </br>
                                            <center style="margin-top: -1em">PRINCIPAL</center>
                                        </span>
                                    </td>
                            </tr>
                        </table>
                    
                </tr>
                

            </table>

            <div class="page-break"></div>
        </center>
        @endif

    @else
                <table class="table no-margin">
                    <thead>
                        <tr>
                                <th>Subject</th>
                                <th>First Grading</th>
                                <th>Second Grading</th>
                                <th>Third Grading</th>
                                <th>Fourth Grading</th>
                                <th>Final Grading</th>
                                <th>Remarks</th>
                                {{--  <th>Faculty</th>  --}}
                        </tr>
                    </thead>
                    <tbody>
                        @if ($GradeSheetData)
                            <?php
                                $showGenAvg = 0;
                            ?>
                            @foreach ($GradeSheetData as $key => $data)
                                <tr>
                                    <center>
                                    <td>{{ $data->subject }}</td>
                                    @if ($data->grade_status === -1)
                                        <td colspan="{{$ClassDetail ? $ClassDetail->section_grade_level <= 10 ? '6' : '4' : '6'}}" class="text-center text-red">Grade not yet finalized</td>
                                    @else 
                                            <td><center>{{ $data->fir_g ? $data->fir_g > 0  ? round($data->fir_g) : '' : '' }}</center></td>
                                            <td><center>{{ $data->sec_g ? $data->sec_g > 0  ? round($data->sec_g) : '' : '' }}</center></td>
                                            <td><center>{{ $data->thi_g ? $data->thi_g > 0  ? round($data->thi_g) : '' : '' }}</center></td>
                                            <td><center>{{ $data->fou_g ? $data->fou_g > 0  ? round($data->fou_g) : '' : '' }}</center></td>
                                            {{-- <td><center>{{ $data->fou_g ? $data->fou_g > 0  ? round($data->final_g) : '' : '' }}</center></td> --}}
                                            {{--  <td class="text-center">{{ $data->fir_g ? $data->fir_g > 0  ? round($data->fir_g) : '' : '' }}</td>
                                            <td class="text-center">{{ $data->sec_g ? $data->sec_g > 0  ? round($data->sec_g) : '' : '' }}</td>
                                            <td class="text-center">{{ $data->thi_g ? $data->thi_g > 0  ? round($data->thi_g) : '' : '' }}</td>
                                            <td class="text-center">{{ $data->fou_g ? $data->fou_g > 0  ? round($data->fou_g) : '' : '' }}</td>
                                            <td class="text-center">{{ $data->fou_g ? $data->fou_g > 0  ? round($data->final_g) : '' : '' }}</td>  --}}
                                            @if ($data->fou_g > 0)
                                                <td><center>{{ round($data->final_g) }}</center></td>
                                                <td><center><strong>{{ $data->final_g >= 75 ? 'Passed' : 'Failed' }}</strong></center></td>
                                            @else
                                                <td></td>
                                            @endif  
                                    @endif
                                    {{--  <td>{{ $data->class_time_from . ' -  ' . $data->class_time_to }}</td>
                                    <td>{{ $data->class_days }}</td>54
                                    /
                                    <td>{{ 'Room' . $data->room_code }}</td>  --}}
                                    {{--  <td>{{ $data->grade_level . ' - ' . $data->section }}</td>  --}}
                                    {{-- <td>{{ $data->faculty_name }}</td> --}}
                                    </center>
                                </tr>
                            @endforeach
                                <tr class="text-center">
                                    <td colspan="{{ $grade_level <= 10 ? '5' : '3'}}"><b>General Average</b></td>
                                    {{--  <td colspan="{{$ClassDetail ? $ClassDetail->section_grade_level <= 10 ? '8' : '2' : '4'}}"><b>General Average</b></td>  --}}
                                    <td>
                                        <b>
                                            @if($data->fir_g == 0 && $data->sec_g == 0 && $data->thi_g == 0 && $data->fou_g == 0)
                                                
                                            @else
                                                {{ $general_avg && $general_avg >= 0 ? round($general_avg) : '' }}
                                            @endif
                                        </b>
                                    </td>
                                    @if($data->fir_g == 0 && $data->sec_g == 0 && $data->thi_g == 0 && $data->fou_g == 0)
                                        @if($general_avg && $general_avg > 74) 
                                            <td style="color:'green';"><strong>Passed</strong></td>
                                        @elseif($general_avg && $general_avg < 75) 
                                            <td style="color:'red';"><strong>Failed</strong></td>
                                        @else 
                                            <td></td>
                                        @endif
                                    @else
                                        @if($general_avg && $general_avg > 74) 
                                            <td style="color:'green';"><strong>Passed</strong></td>
                                        @elseif($general_avg && $general_avg < 75) 
                                            <td style="color:'red';"><strong>Failed</strong></td>
                                        @else 
                                            <td></td>
                                        @endif
                                    @endif
                                    
                                </tr>
                        @else
                            
                    @endif
                </tbody>
            </table>

            <p class="report-progress-left m0"  style="margin-top: .5em"><b>ATTENDANCE RECORD</b></p>
                <table style="width:100%; margin-bottom: 1em">
                    <tr>
                        <th>
                            
                        </th>
                            @foreach ($student_attendance['table_header'] as $data)
                                    <th>{{ $data['key'] }}</th>
                            @endforeach
                    </tr>
                    <tr>
                        <th>
                            Days of School
                        </th>
                        @foreach ($student_attendance['attendance_data']->days_of_school as $key => $data)
                            <th style="width:7%">{{ $data }}
                            </th>
                        @endforeach
                        <th class="days_of_school_total">
                            {{ $student_attendance['days_of_school_total'] }}
                        </th>
                    </tr>
                    <tr>
                        <th>
                            Days Present
                        </th>
                        @foreach ($student_attendance['attendance_data']->days_present as $key => $data)
                            <th style="width:7%">{{ $data }} 
                            </th>
                        @endforeach
                        <th class="days_present_total">
                            {{ $student_attendance['days_present_total'] }}
                        </th>
                    </tr>
                    <tr>
                        <th>
                            Days Absent
                        </th>
                        @foreach ($student_attendance['attendance_data']->days_absent as $key => $data)
                            <th style="width:7%">{{ $data }}  
                            </th>
                        @endforeach
                        <th class="days_absent_total">
                            {{ $student_attendance['days_absent_total'] }}
                        </th>
                    </tr>
                    <tr>
                        <th>
                            Times Tardy
                        </th>
                        @foreach ($student_attendance['attendance_data']->times_tardy as $key => $data)
                            <th style="width:7%">{{ $data }}  
                            </th>
                        @endforeach
                        <th class="times_tardy_total">
                            {{ $student_attendance['times_tardy_total'] }}
                        </th>
                    </tr>
                </table>
            
            <center>
                <table border="0" style="width: 80%">

                    <tr style="margin-top: .5em">
                        <td style="border: 0">Description</td>
                        <td style="border: 0">Grading Scale</td>
                        <td style="border: 0">Remarks</td>                
                    </tr>

                    <tr style="margin-top: .5em">
                        <td style="border: 0">Outstanding</td>
                        <td style="border: 0">90-100</td>
                        <td style="border: 0">Passed</td>                
                    </tr>

                    <tr style="margin-top: .5em">
                        <td style="border: 0">Very Satisfactory</td>
                        <td style="border: 0">85-89</td>
                        <td style="border: 0">Passed</td>                
                    </tr>

                    <tr style="margin-top: .5em">
                        <td style="border: 0">Satisfactory</td>
                        <td style="border: 0">80-84</td>
                        <td style="border: 0">Passed</td>                
                    </tr>

                    <tr style="margin-top: .5em">
                        <td style="border: 0">Fairly Satisfactory</td>
                        <td style="border: 0">75-79</td>
                        <td style="border: 0">Passed</td>                
                    </tr>

                    <tr style="margin-top: .5em">
                        <td style="border: 0">Did Not Meet expectations</td>
                        <td style="border: 0">Below 75</td>
                        <td style="border: 0">Failed</td>                
                    </tr>
                    <tr style="margin-top: .5em">
                        <td style="border: 0"></td>
                        <td style="border: 0"></td>
                        <td style="border: 0"></td>   
                    </tr>

                    <tr style="margin-top: .5em">
                        <td colspan="3" style="border: 0">Eligible to transfer and admission to:
                               
                                        @if($general_avg && $general_avg > 74) 
                                            <strong><u>&nbsp;&nbsp;Grade {{ $ClassDetail->section_grade_level + 1 }}&nbsp;&nbsp;&nbsp;&nbsp;</u></strong>
                                        @elseif($general_avg && $general_avg < 75) 
                                            <strong><u>&nbsp;&nbsp;Grade {{ $ClassDetail->section_grade_level }}&nbsp;&nbsp;&nbsp;&nbsp;</u></strong>
                                        @else 
                                            <td></td>
                                        @endif
                                    
                                
                        </td>                
                    </tr>

                    <tr style="margin-top: .5em">
                        <td colspan="3" style="border: 0">Lacking units in:__________________________</td>                
                    </tr>
                    
                    <tr style="margin-top: .5em">
                        <td colspan="3" style="border: 0">Date:___<u>{{ $DateRemarks->j_date }}</u>____</td>                
                    </tr>
                    <tr style="margin-top: .5em">
                         <td colspan="3" style="border: 0">&nbsp;</td>   </tr>
                    {{-- <tr> <td colspan="3" style="border: 0">&nbsp;</td>   </tr> --}}
                
                    <tr style="margin-top: 0em">
                            <table border="0" style="width: 100%; margin-top: -1em">
                                    <tr>
                                            <td style="border: 0; width: 50%;">
                                            <center>
                                                <img class="profile-user-img img-responsive img-circle" id="img--user_photo" src="{{ $ClassDetail->e_signature ? \File::exists(public_path('/img/signature/'.$ClassDetail->e_signature)) ? asset('/img/signature/'.$ClassDetail->e_signature) : asset('/img/account/photo/blank-user.png') : asset('/img/account/photo/blank-user.png') }}" style="width:100px">
                                            </center>
                                        </td>
                                        <td style="border: 0; width: 50%;">
                                            <center>
                                                <img class="profile-user-img img-responsive img-circle" id="img--user_photo" src="{{ asset('/img/signature/principal_signature.png') }}" style="width:170px">
                                            </center>
                                        </td>
                                    </tr>
                            </table>
                            <table border="0" style="width: 100%; margin-top: -70px; margin-bottom: 0em">
                                
                                <tr>
                                    <td style="border: 0; width: 50%; height: 100px">
                                        <span style="margin-left: 2em; text-transform: uppercase">
                                            <center>
                                                {{ $ClassDetail->first_name }} {{ $ClassDetail->middle_name }} {{ $ClassDetail->last_name }}</center>
                                            </br>
                                            <center style="margin-top: -1em">Adviser</center>
                                        </span>
                                    </td>
                                    <td style="border: 0; width: 50%; height: 100px">
                                            <span style="margin-left: 23em;">
                                                <center>Gemma R. Yao, Ph.D.</center>
                                                </br>
                                                <center style="margin-top: -1em">PRINCIPAL</center>
                                            </span>
                                        </td>
                                </tr>
                            </table>
                        
                    </tr>
                    

                </table>

                <div class="page-break"></div>
            </center>
    @endif
</body>
</html>