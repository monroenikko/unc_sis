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
            padding: 5px;
        }
        table {
            width: 100%;
            border-spacing: 0;
            border-collapse: collapse;
            font-size : 11px;
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
            top: 10px;
            left: 10px;
        }
        .report-progress {
            text-align: center;
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
    <img class="logo" width="100" src="{{ asset('img/sja-logo.png') }}" />
    <br/>
    <p class="p0 m0 student-info">School Year : <b>{{ $ClassDetail ? $ClassDetail->school_year : '' }}</b</p>
    <p class="p0 m0 student-info">Grade & Section : <b>{{ $ClassDetail ? $ClassDetail->section_grade_level : '' }} - {{ $ClassDetail ? $ClassDetail->section : '' }}</b</p>
    <p class="p0 m0 student-info">Student Name : <b>{{ ucfirst($StudentInformation->last_name). ', ' .ucfirst($StudentInformation->first_name). ' ' . ucfirst($StudentInformation->middle_name) }}</b</p>
    
    <br/>
    {{--  <h4>Subject : <span class="text-red"><i>{{ $ClassSubjectDetail->subject }}</i></span> Time : <span class="text-red"><i>{{ strftime('%r',strtotime($ClassSubjectDetail->class_time_from)) . ' - ' . strftime('%r',strtotime($ClassSubjectDetail->class_time_to)) }}</i></span> Days : <span class="text-red"><i>{{ $ClassSubjectDetail->class_days }}</i></span></h4>  --}}
    {{--  <h4>Grade & Section : <span class="text-red"><i>{{ $ClassSubjectDetail->grade_level . ' ' .$ClassSubjectDetail->section }}</i></span></h4>  --}}
    @if ($grade_level >= 11) 


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
                            <th>Faculty</th>
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
                                    <td>{{ $data->subject_code . ' ' . $data->subject }}</td>
                                    @if ($data->grade_status === -1)
                                        <td colspan="{{$ClassDetail ? $ClassDetail->section_grade_level <= 10 ? '6' : '4' : '6'}}" class="text-center text-red">Grade not yet finalized</td>
                                    @else 
                                            <td><center>{{ $data->fir_g ? $data->fir_g > 0  ? round($data->fir_g) : '' : '' }}</center></td>
                                            <td><center>{{ $data->sec_g ? $data->sec_g > 0  ? round($data->sec_g) : '' : '' }}</center></td>
                                            <td><center>{{ $data->thi_g ? $data->thi_g > 0  ? round($data->thi_g) : '' : '' }}</center></td>
                                            <td><center>{{ $data->fou_g ? $data->fou_g > 0  ? round($data->fou_g) : '' : '' }}</center></td>
                                            <td><center>{{ $data->fou_g ? $data->fou_g > 0  ? round($data->final_g) : '' : '' }}</center></td>
                                            @if ($data->fou_g > 0)
                                                <?php
                                                    $showGenAvg = 1;
                                                ?>
                                                <td><center>{{ round($data->final_g) }}</center></td>
                                                <td style="color:{{ $data->final_g >= 75 ? 'green' : 'red' }};"><strong>{{ $data->final_g >= 75 ? 'Passed' : 'Failed' }}</strong></td>
                                            @else
                                            <center>
                                                <?php
                                                    $showGenAvg = 0;
                                                ?>
                                            </center>
                                                <td></td>
                                            @endif
                                    @endif
                                    {{--  <td>{{ $data->class_time_from . ' -  ' . $data->class_time_to }}</td>
                                    <td>{{ $data->class_days }}</td>
                                    <td>{{ 'Room' . $data->room_code }}</td>  --}}
                                    {{--  <td>{{ $data->grade_level . ' - ' . $data->section }}</td>  --}}
                                    <td>{{ $data->faculty_name }}</td>
                                    </center>
                                </tr>
                            @endforeach
                                <tr class="text-center">
                                    <td></td>
                                    <td colspan="{{$ClassDetail ? $ClassDetail->section_grade_level <= 10 ? '4' : '2' : '4'}}"><b>General Average</b></td>
                                    <td><b>{{$showGenAvg ? $general_avg == 0 ? '' : $general_avg : '' }}</b></td>
                                    <td>
                                        <b>
                                            <center>
                                                @if($showGenAvg && $general_avg > 75) 
                                                    <td style="color:'green';"><strong>Passed</strong></td>
                                                @elseif($showGenAvg && $general_avg < 75) 
                                                    <td style="color:'red';"><strong>Failed</strong></td>
                                                @endif
                                            </center>
                                        </b>
                                    </td>
                                    <td></td>
                                </tr>
                        @else
                            
                    @endif
                </tbody>
            </table>
    @endif
</body>
</html>