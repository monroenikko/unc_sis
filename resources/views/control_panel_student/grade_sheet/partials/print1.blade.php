<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Student Class Card</title>
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
    <img class="logo sja-logo" width="100" src="{{ asset('img/sja-logo.png') }}" />
    <img class="logo deped-bataan-logo" width="100" src="{{ asset('img/deped-bataan-logo.png') }}" />
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
    </table>
    
    
    
    
    {{-- //<br/> --}}
    {{--  <h4>Subject : <span class="text-red"><i>{{ $ClassSubjectDetail->subject }}</i></span> Time : <span class="text-red"><i>{{ strftime('%r',strtotime($ClassSubjectDetail->class_time_from)) . ' - ' . strftime('%r',strtotime($ClassSubjectDetail->class_time_to)) }}</i></span> Days : <span class="text-red"><i>{{ $ClassSubjectDetail->class_days }}</i></span></h4>  --}}
    {{--  <h4>Grade & Section : <span class="text-red"><i>{{ $ClassSubjectDetail->grade_level . ' ' .$ClassSubjectDetail->section }}</i></span></h4>  --}}
    
                <table class="table no-margin" style="margin-top: .5em; margin-bottom: 1em">
                    <thead>
                        @if ($grade_level >= 11) 

                            <tr>
                                <th>Subject</th>
                                
                                <th colspan="4">
                                    <?php 
                                        $Semester = \App\Semester::where('current', 1)->first()->id; 
                                        
                                        if($Semester == 1)
                                        {
                                            echo 'First Semester';
                                        }
                                        else 
                                        {
                                            echo 'Second Semester';
                                        }
                                    ?>
                                </th>
                                {{--  <th colspan="4">Second Semester</th>  --}}
                            </tr>
                            <tr>
                                <th></th>
                                <th>First Quarter</th>
                                <th>Second Quarter</th>
                                <th>Final Grade</th>
                                <th>Remarks</th>

                                
                            </tr>
                        @elseif($grade_level <= 10)
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
                        @endif
                    </thead>
                    <tbody>
                        @if ($GradeSheetData)
                            <?php
                                $showGenAvg = 0;
                            ?>
                            @foreach ($GradeSheetData as $key => $data)
                                <tr>
                                    <td>{{ $data->subject }}</td>

                                    @if ($data->grade_status === -1)
                                        <td colspan="{{$ClassDetail ? $ClassDetail->section_grade_level <= 10 ? '6' : '4' : '6'}}" class="text-center text-red">Grade not yet finalized</td>
                                    @else 
                                        
                                        @if ($grade_level > 10)
                                        
                                        <?php 
                                        $Semester = \App\Semester::where('current', 1)->first()->id; 
                                        ?>

                                            @if($Semester == 1)
                                                <?php
                                                $fQrtFinal = 0;
                                                $fQrtTotal = 0;
                                                $fQrtCtr = 0;
                                                if ($data->fir_g && $data->fir_g > 0) 
                                                {
                                                    $fQrtTotal += round($data->fir_g);
                                                    $fQrtCtr++;
                                                }

                                                if ($data->sec_g && $data->sec_g > 0) 
                                                {
                                                    $fQrtTotal += round($data->sec_g);
                                                    $fQrtCtr++;
                                                }

                                                if ($fQrtCtr > 1) 
                                                {
                                                    $fQrtFinal = round($fQrtTotal) / ($fQrtCtr);
                                                }
                                                ?>
                                                <td class="text-center"><center>{{ $data->fir_g ? $data->fir_g > 0  ? round($data->fir_g) : '' : '' }}</center></td>
                                                <td class="text-center"><center>{{ $data->sec_g ? $data->sec_g > 0  ? round($data->sec_g) : '' : '' }}</center></td>
                                                <td class="text-center"><center>{{ $fQrtFinal ? round($fQrtFinal)   : '' }}</center></td>
                                                <td class="text-center"><center>{{ $fQrtFinal ? $fQrtFinal > 74  ? 'Passed' : 'Failed' : '' }}</center></td>
                                            @else

                                            @endif
                                     
                                        @else
                                            <td><center>{{ $data->fir_g ? $data->fir_g > 0  ? round($data->fir_g) : '' : '' }}</center></td>
                                            <td><center>{{ $data->sec_g ? $data->sec_g > 0  ? round($data->sec_g) : '' : '' }}</center></td>
                                            <td><center>{{ $data->thi_g ? $data->thi_g > 0  ? round($data->thi_g) : '' : '' }}</center></td>
                                            <td><center>{{ $data->fou_g ? $data->fou_g > 0  ? round($data->fou_g) : '' : '' }}</center></td>
                                            <td><center>{{ $data->fou_g ? $data->fou_g > 0  ? round($data->final_g) : '' : '' }}</center></td>
                                            {{--  <td class="text-center">{{ $data->fir_g ? $data->fir_g > 0  ? round($data->fir_g) : '' : '' }}</td>
                                            <td class="text-center">{{ $data->sec_g ? $data->sec_g > 0  ? round($data->sec_g) : '' : '' }}</td>
                                            <td class="text-center">{{ $data->thi_g ? $data->thi_g > 0  ? round($data->thi_g) : '' : '' }}</td>
                                            <td class="text-center">{{ $data->fou_g ? $data->fou_g > 0  ? round($data->fou_g) : '' : '' }}</td>
                                            <td class="text-center">{{ $data->fou_g ? $data->fou_g > 0  ? round($data->final_g) : '' : '' }}</td>  --}}
                                            @if ($data->fou_g > 0)
                                                <td><center>{{ round($data->final_g) }}</center></td>
                                                <td style="color:{{ $data->final_g >= 75 ? 'green' : 'red' }};"><center><strong>{{ $data->final_g >= 75 ? 'Passed' : 'Failed' }}</strong></center></td>
                                            @else
                                                <td></td>
                                            @endif  
                                        @endif
                                    @endif
                                    {{--  <td>{{ $data->class_time_from . ' -  ' . $data->class_time_to }}</td>
                                    <td>{{ $data->class_days }}</td>
                                    <td>{{ 'Room' . $data->room_code }}</td>  --}}
                                    {{--  <td>{{ $data->grade_level . ' - ' . $data->section }}</td>  --}}
                                    {{--  <td>{{ $data->faculty_name }}</td>  --}}
                                </tr>
                            @endforeach
                                <tr class="text-center">
                                    <td colspan="{{$grade_level <= 10 ? '5' : '3'}}"><b>General Average</b></td>
                                    {{--  <td colspan="{{$ClassDetail ? $ClassDetail->section_grade_level <= 10 ? '8' : '2' : '4'}}"><b>General Average</b></td>  --}}
                                    <td>
                                        <b>
                                            <?php 
                                                for ($i=0; $i<count($GradeSheetData); $i++)
                                                {
                                                    if ($GradeSheetData[$i]['final_g'] > 0 && $GradeSheetData[$i]['grade_status'] == 2) 
                                                    {
                                                        $subj_count++;
                                                        $sub_total +=  $GradeSheetData[$i]['final_g'];
                                                    }
                                                }
                                            ?>
                                            @if($data->fir_g == 0 && $data->sec_g == 0 && $data->thi_g == 0 && $data->fou_g == 0)
                                                {{$general_avg && $general_avg >= 0 ? round($general_avg) : '' }}
                                            @else
                                                
                                            @endif
                                        </b>
                                    </td>
                                    @if($data->fir_g == 0 && $data->sec_g == 0 && $data->thi_g == 0 && $data->fou_g == 0)
                                        @if($general_avg && $general_avg > 75) 
                                            <td style="color:'green';"><strong>Passed</strong></td>
                                        @elseif($general_avg && $general_avg < 75) 
                                            <td style="color:'red';"><strong>Failed</strong></td>
                                        @else 
                                            <td></td>
                                        @endif
                                    @else
                                        <td></td>
                                    @endif
                                    
                                </tr>
                        @else
                            
                    @endif
                </tbody>
            </table>
            

            @if($ClassDetail->section_grade_level >= 11)
            <center>
                <table border="0" style="width: 80%">

                    <tr>
                        <td style="border: 0">Description</td>
                        <td style="border: 0">Grading Scale</td>
                        <td style="border: 0">Remarks</td>                
                    </tr>

                    <tr>
                        <td style="border: 0">Outstanding</td>
                        <td style="border: 0">90-100</td>
                        <td style="border: 0">Passed</td>                
                    </tr>

                    <tr>
                        <td style="border: 0">Very Satisfactory</td>
                        <td style="border: 0">85-89</td>
                        <td style="border: 0">Passed</td>                
                    </tr>

                    <tr>
                        <td style="border: 0">Satisfactory</td>
                        <td style="border: 0">80-84</td>
                        <td style="border: 0">Passed</td>                
                    </tr>

                    <tr>
                        <td style="border: 0">Fairly Satisfactory</td>
                        <td style="border: 0">75-79</td>
                        <td style="border: 0">Passed</td>                
                    </tr>

                    <tr>
                        <td style="border: 0">Did Not Meet expectations</td>
                        <td style="border: 0">Below 75</td>
                        <td style="border: 0">Failed</td>                
                    </tr>
                    <tr>
                        <td style="border: 0"></td>
                        <td style="border: 0"></td>
                        <td style="border: 0"></td>   
                    </tr>

                    <tr>
                        <td colspan="3" style="border: 0">Eligible to transfer admission to:__________________________</td>                
                    </tr>

                    <tr>
                        <td colspan="3" style="border: 0">Lacking units in:__________________________</td>                
                    </tr>
                    
                    <tr>
                        <td colspan="3" style="border: 0">Date:__________________________</td>                
                    </tr>
                    <tr> <td colspan="3" style="border: 0">&nbsp;</td>   </tr>
                    {{-- <tr> <td colspan="3" style="border: 0">&nbsp;</td>   </tr> --}}
                
                    <tr>
                            <table border="0" style="width: 100%; margin-bottom: 0em">
                                    <tr>
                                            <td style="border: 0; width: 50%;">
                                            <center>
                                                <img class="profile-user-img img-responsive img-circle" id="img--user_photo" src="{{ $ClassDetail->e_signature ? \File::exists(public_path('/img/signature/'.$ClassDetail->e_signature)) ? asset('/img/signature/'.$ClassDetail->e_signature) : asset('/img/account/photo/blank-user.gif') : asset('/img/account/photo/blank-user.gif') }}" style="width:100px">
                                            </center>
                                        </td>
                                        <td style="border: 0; width: 50%;">
                                            <center>
                                                <img class="profile-user-img img-responsive img-circle" id="img--user_photo" src="{{ asset('/img/signature/principal_signature.png') }}" style="width:170px">
                                               
                                            </center>
                                        </td>
                                    </tr>
                            </table>
                            <table border="0" style="width: 100%; margin-top: -6em">
                                
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
                                        <span style="margin-left: 23em; text-transform: uppercase">
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
                <p class="report-progress-left m0"  style="margin-top: 0em"><b>ATTENDANCE RECORD</b></p>
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
                        <td colspan="3" style="border: 0">Eligible to transfer admission to:__________________________</td>                
                    </tr>

                    <tr style="margin-top: .5em">
                        <td colspan="3" style="border: 0">Lacking units in:__________________________</td>                
                    </tr>
                    
                    <tr style="margin-top: .5em">
                        <td colspan="3" style="border: 0">Date:__________________________</td>                
                    </tr>
                    <tr style="margin-top: .5em">
                         <td colspan="3" style="border: 0">&nbsp;</td>   </tr>
                    {{-- <tr> <td colspan="3" style="border: 0">&nbsp;</td>   </tr> --}}
                
                    <tr style="margin-top: 0em">
                            <table border="0" style="width: 100%; margin-top: -1em">
                                    <tr>
                                            <td style="border: 0; width: 50%;">
                                            <center>
                                                <img class="profile-user-img img-responsive img-circle" id="img--user_photo" src="{{ $ClassDetail->e_signature ? \File::exists(public_path('/img/signature/'.$ClassDetail->e_signature)) ? asset('/img/signature/'.$ClassDetail->e_signature) : asset('/img/account/photo/blank-user.gif') : asset('/img/account/photo/blank-user.gif') }}" style="width:100px">
                                            </center>
                                        </td>
                                        <td style="border: 0; width: 50%;">
                                            <center>
                                                <img class="profile-user-img img-responsive img-circle" id="img--user_photo" src="{{ asset('/img/signature/principal_signature.png') }}" style="width:170px">
                                            </center>
                                        </td>
                                    </tr>
                            </table>
                            <table border="0" style="width: 100%; margin-top: -50px; margin-bottom: 0em">
                                
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