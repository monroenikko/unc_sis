<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Fourth Quarter</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" media="screen" href="main.css" />
    <script src="main.js"></script>
    <style>
        {{--  .page-break {
            page-break-after: always;
        }
        th, td {
            border: 1px solid #ccc;
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
        }  --}}
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
    {{--  <p class="heading1">Republic of the Philippines
    <p class="heading1">Department of Education</p>
    <p class="heading1">Region III</p>
    <p class="heading1">Division of Bataan</p>
    <br/>  --}}
    <h2 class="heading2 heading2-title">Saint John Academy</h2>
    <p class="heading2 heading2-subtitle">Dinalupihan, Bataan</p>
    
    <p class="report-progress m0">JUNIOR HIGH SCHOOL</p>
    <p class="report-progress m0">S.Y. {{ $ClassSubjectDetail ? $ClassSubjectDetail->school_year : '' }}</p>
    {{--  <p class="report-progress m0">( {{ $ClassSubjectDetail ?  $ClassSubjectDetail->grade_level >= 11 ? 'SENIOR HIGH SCHOOL' : 'JUNIOR HIGH SCHOOL' : ''}} )</p>  --}}
    <img style="margin-top: -.4em; margin-left: 9em" class="logo" width="100" src="{{ asset('img/sja-logo.png') }}" />
    <br/>
    <br/>
    {{--  <p class="p0 m0 student-info">Grade sheet</p>  --}}
    {{--  <p class="p0 m0 student-info">School Year : <b>{{ $ClassSubjectDetail ? $ClassSubjectDetail->school_year : '' }}</b</p>  --}}
    <p class="p0 m0 student-info">Grade & Section : <b>{{ $ClassSubjectDetail ? $ClassSubjectDetail->grade_level : '' }} - {{ $ClassSubjectDetail ? $ClassSubjectDetail->section : '' }}</b</p>
    <p class="p0 m0 student-info">Semester : <b><i>{{ $sem }}</i></b</p>
    <br/>
   
    
    <table class="table no-margin table-striped table-bordered">
                    <thead>
                        <tr>
                                <th style="width: 30px">#</th>
                                <th style="width: 200px">Student Name</th>                                       

                                @if($quarter == 'First - Second')
                                    <th style=" ; text-align: center">First Quarter</th>
                                    <th style=" ; text-align: center">Second Quarter</th>
                                @else
                                    <th style="  text-align: center">First Semester</th>
                                    <th style="  text-align: center">Second Semester</th>
                                @endif
    
                                <th style="  text-align: center">GENERAL AVERAGE</th>
                                <th style="  text-align: center">REMARKS</th>
                        </tr>
                    </thead>
                    <tbody>
                            @if($sem == 'First')
                            
                            @if($NumberOfSubject->class_subject_order == 7)
                                <tr>
                                    <td colspan="7">
                                        <b>Male</b>
                                    </td>
                                </tr>
                                @foreach($GradeSheetMale as $key => $sub)
                                <tr>
                                    <td>{{ $key + 1 }}.</td>
                                    <td>{{ $sub->student_name }}</td>
                                    <td style="text-align: center">
                                        <?php
                                        $formattedNum = round($average = ($sub->subject_1 + $sub->subject_2 + $sub->subject_3 + $sub->subject_4 + $sub->subject_5 + $sub->subject_6 + $sub->subject_7)/7);
                                        echo $formattedNum;
                                        ?>                                                
                                    </td>
                                    <td style="text-align: center">
                                        <?php
                                            $sec_g = \App\Grade_sheet_firstsemsecond::where('enrollment_id', $sub->enrollment_id)->first();
                                            $result = round($average = ($sec_g->subject_1 + $sec_g->subject_2 + $sec_g->subject_3 + $sec_g->subject_4 + $sec_g->subject_5 + $sec_g->subject_6 + $sec_g->subject_7)/7);
                                            echo $result;
                                        ?>    
                                    </td>
                            
                                    <?php                                                    
                                    $subject1 = round(\App\Grade_sheet_firstsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_1);
                            
                                    $fg1 = round(($sub->subject_1 + $subject1) / 2);
                                                                                
                                    $subject2 = round(\App\Grade_sheet_firstsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_2);
                            
                                    $fg2 = round(($sub->subject_2 + $subject2) / 2);
                                                                                
                                    $subject3 = round(\App\Grade_sheet_firstsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_3);
                                    
                                    $fg3 = round(($sub->subject_3 + $subject3) / 2);
                                                                                    
                                    $subject4 = round(\App\Grade_sheet_firstsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_4);
                                    
                                    $fg4 = round(($sub->subject_4 + $subject4) / 2);
                                                                                    
                                    $subject5 = round(\App\Grade_sheet_firstsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_5);
                                
                                    $fg5 = round(($sub->subject_5 + $subject5) / 2);
                                                                                    
                                    $subject6 = round(\App\Grade_sheet_firstsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_6);
                                    
                                    $fg6 = round(($sub->subject_6 + $subject6) / 2);
                                                                                    
                                    $subject7 = round(\App\Grade_sheet_firstsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_7);
                                    
                                    $fg7 = round(($sub->subject_7 + $subject7) / 2);
                                                                                    
                                    
                                ?>
                    
                                <td>
                                        <center>                                                
                                                <?php
                                                    $average_1sem = number_format($average = ($fg1 + $fg2 + $fg3 + $fg4 + $fg5 + $fg6 + $fg7 )/7, 2);
                                                    echo round($average_1sem);
                                                ?>
                                        </center>
                                </td>
                                
                                @if(round($average_1sem) >= 75 && round($average_1sem) <= 89)
                                    <td>
                                        <center>Passed</center>
                                    </td>
                                @elseif(round($average_1sem) >= 90 && round($average_1sem) <= 94)
                                    <td>
                                        <center>with honors</center>
                                    </td>
                                @elseif(round($average_1sem)>= 95 && round($average_1sem) <= 97)
                                    <td>
                                        <center>with high honors</center>
                                    </td>
                                @elseif(round($average_1sem) >= 98 && round($average_1sem) <= 100)
                                    <td>
                                        <center>with highest honors</center>
                                    </td>
                                @elseif(round($average_1sem) < 75)
                                    <td>
                                        <center>Failed</center>
                                    </td>
                                @endif
                                </tr>
                                @endforeach
                            
                                    <tr>
                                        <td colspan="7">
                                            <b>Female</b>
                                        </td>
                                    </tr>
                                    @foreach($GradeSheetFeMale as $key => $sub)
                                    <tr>
                                        <td>{{ $key + 1 }}.</td>
                                        <td>{{ $sub->student_name }}</td>
                                        <td style="text-align: center">
                                            <?php
                                            $formattedNum = round($average = ($sub->subject_1 + $sub->subject_2 + $sub->subject_3 + $sub->subject_4 + $sub->subject_5 + $sub->subject_6 + $sub->subject_7)/7);
                                            echo $formattedNum;
                                            ?>                                                
                                        </td>
                                        <td style="text-align: center">
                                            <?php
                                                $sec_g = \App\Grade_sheet_firstsemsecond::where('enrollment_id', $sub->enrollment_id)->first();
                                                $result = round($average = ($sec_g->subject_1 + $sec_g->subject_2 + $sec_g->subject_3 + $sec_g->subject_4 + $sec_g->subject_5 + $sec_g->subject_6 + $sec_g->subject_7)/7);
                                                echo $result;
                                            ?>    
                                            </td>
                                            <?php                                                    
                                            $subject1 = round(\App\Grade_sheet_firstsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_1);
                                    
                                            $fg1 = round(($sub->subject_1 + $subject1) / 2);
                                                                                        
                                            $subject2 = round(\App\Grade_sheet_firstsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_2);
                                    
                                            $fg2 = round(($sub->subject_2 + $subject2) / 2);
                                                                                        
                                            $subject3 = round(\App\Grade_sheet_firstsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_3);
                                            
                                            $fg3 = round(($sub->subject_3 + $subject3) / 2);
                                                                                            
                                            $subject4 = round(\App\Grade_sheet_firstsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_4);
                                            
                                            $fg4 = round(($sub->subject_4 + $subject4) / 2);
                                                                                            
                                            $subject5 = round(\App\Grade_sheet_firstsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_5);
                                        
                                            $fg5 = round(($sub->subject_5 + $subject5) / 2);
                                                                                            
                                            $subject6 = round(\App\Grade_sheet_firstsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_6);
                                            
                                            $fg6 = round(($sub->subject_6 + $subject6) / 2);
                                                                                            
                                            $subject7 = round(\App\Grade_sheet_firstsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_7);
                                            
                                            $fg7 = round(($sub->subject_7 + $subject7) / 2);
                                                                                            
                                            
                                        ?>
                            
                                        <td>
                                                <center>                                                
                                                        <?php
                                                            $average_1sem = number_format($average = ($fg1 + $fg2 + $fg3 + $fg4 + $fg5 + $fg6 + $fg7 )/7, 2);
                                                            echo round($average_1sem);
                                                        ?>
                                                </center>
                                        </td>
                                        
                                        @if(round($average_1sem) >= 75 && round($average_1sem) <= 89)
                                            <td>
                                                <center>Passed</center>
                                            </td>
                                        @elseif(round($average_1sem) >= 90 && round($average_1sem) <= 94)
                                            <td>
                                                <center>with honors</center>
                                            </td>
                                        @elseif(round($average_1sem)>= 95 && round($average_1sem) <= 97)
                                            <td>
                                                <center>with high honors</center>
                                            </td>
                                        @elseif(round($average_1sem) >= 98 && round($average_1sem) <= 100)
                                            <td>
                                                <center>with highest honors</center>
                                            </td>
                                        @elseif(round($average_1sem) < 75)
                                            <td>
                                                <center>Failed</center>
                                            </td>
                                        @endif
                                    </tr>
                                    @endforeach
                            @elseif($NumberOfSubject->class_subject_order == 8)
                                <tr>
                                    <td colspan="7">
                                        <b>Male</b>
                                    </td>
                                </tr>
                                @foreach($GradeSheetMale as $key => $sub)
                                <tr>
                                    <td>{{ $key + 1 }}.</td>
                                    <td>{{ $sub->student_name }}</td>
                                    <td style="text-align: center">
                                        <?php
                                        $formattedNum = round($average = ($sub->subject_1 + $sub->subject_2 + $sub->subject_3 + $sub->subject_4 + $sub->subject_5 + $sub->subject_6 + $sub->subject_7 + $sub->subject_8)/8);
                                        echo $formattedNum;
                                        ?>                                                
                                    </td>
                                    <td style="text-align: center">
                                        <?php
                                            $sec_g = \App\Grade_sheet_firstsemsecond::where('enrollment_id', $sub->enrollment_id)->first();
                                            $result = round($average = ($sec_g->subject_1 + $sec_g->subject_2 + $sec_g->subject_3 + $sec_g->subject_4 + $sec_g->subject_5 + $sec_g->subject_6 + $sec_g->subject_7+$sec_g->subject_8)/8);
                                            echo $result;
                                        ?>    
                                    </td>
                                    
                                    <?php                                                    
                                        $subject1 = round(\App\Grade_sheet_firstsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_1);
                                
                                        $fg1 = round(($sub->subject_1 + $subject1) / 2);
                                                                                    
                                        $subject2 = round(\App\Grade_sheet_firstsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_2);
                                
                                        $fg2 = round(($sub->subject_2 + $subject2) / 2);
                                                                                    
                                        $subject3 = round(\App\Grade_sheet_firstsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_3);
                                        
                                        $fg3 = round(($sub->subject_3 + $subject3) / 2);
                                                                                        
                                        $subject4 = round(\App\Grade_sheet_firstsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_4);
                                        
                                        $fg4 = round(($sub->subject_4 + $subject4) / 2);
                                                                                        
                                        $subject5 = round(\App\Grade_sheet_firstsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_5);
                                    
                                        $fg5 = round(($sub->subject_5 + $subject5) / 2);
                                                                                        
                                        $subject6 = round(\App\Grade_sheet_firstsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_6);
                                        
                                        $fg6 = round(($sub->subject_6 + $subject6) / 2);
                                                                                        
                                        $subject7 = round(\App\Grade_sheet_firstsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_7);
                                        
                                        $fg7 = round(($sub->subject_7 + $subject7) / 2);
                                                                                        
                                        $subject8 = round(\App\Grade_sheet_firstsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_8);
                                        
                                        $fg8 = round(($sub->subject_8 + $subject8) / 2);
                                                                                        
                                        
                                    ?>
                        
                                    <td>
                                            <center>                                                
                                                    <?php
                                                        $average_1sem = number_format($average = ($fg1 + $fg2 + $fg3 + $fg4 + $fg5 + $fg6 + $fg7 + $fg8)/8, 2);
                                                        echo round($average_1sem);
                                                    ?>
                                            </center>
                                    </td>
                                    
                                    @if(round($average_1sem) >= 75 && round($average_1sem) <= 89)
                                        <td>
                                            <center>Passed</center>
                                        </td>
                                    @elseif(round($average_1sem) >= 90 && round($average_1sem) <= 94)
                                        <td>
                                            <center>with honors</center>
                                        </td>
                                    @elseif(round($average_1sem)>= 95 && round($average_1sem) <= 97)
                                        <td>
                                            <center>with high honors</center>
                                        </td>
                                    @elseif(round($average_1sem) >= 98 && round($average_1sem) <= 100)
                                        <td>
                                            <center>with highest honors</center>
                                        </td>
                                    @elseif(round($average_1sem) < 75)
                                        <td>
                                            <center>Failed</center>
                                        </td>
                                    @endif
                                </tr>
                                @endforeach
                            
                                    <tr>
                                        <td colspan="7">
                                            <b>Female</b>
                                        </td>
                                    </tr>
                                    @foreach($GradeSheetFeMale as $key => $sub)
                                    <tr>
                                        <td>{{ $key + 1 }}.</td>
                                        <td>{{ $sub->student_name }}</td>

                                        <td style="text-align: center">
                                            <?php
                                            $formattedNum = round($average = ($sub->subject_1 + $sub->subject_2 + $sub->subject_3 + $sub->subject_4 + $sub->subject_5 + $sub->subject_6 + $sub->subject_7 + $sub->subject_8)/8);
                                            echo $formattedNum;
                                            ?>                                                
                                        </td>
                                        <td style="text-align: center">
                                            <?php
                                                $sec_g = \App\Grade_sheet_firstsemsecond::where('enrollment_id', $sub->enrollment_id)->first();
                                                $result = round($average = ($sec_g->subject_1 + $sec_g->subject_2 + $sec_g->subject_3 + $sec_g->subject_4 + $sec_g->subject_5 + $sec_g->subject_6 + $sec_g->subject_7+$sec_g->subject_8)/8);
                                            echo $result;
                                            ?>    
                                            </td>

                                    
                                            <?php                                                    
                                            $subject1 = round(\App\Grade_sheet_firstsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_1);
                                    
                                            $fg1 = round(($sub->subject_1 + $subject1) / 2);
                                                                                        
                                            $subject2 = round(\App\Grade_sheet_firstsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_2);
                                    
                                            $fg2 = round(($sub->subject_2 + $subject2) / 2);
                                                                                        
                                            $subject3 = round(\App\Grade_sheet_firstsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_3);
                                            
                                            $fg3 = round(($sub->subject_3 + $subject3) / 2);
                                                                                            
                                            $subject4 = round(\App\Grade_sheet_firstsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_4);
                                            
                                            $fg4 = round(($sub->subject_4 + $subject4) / 2);
                                                                                            
                                            $subject5 = round(\App\Grade_sheet_firstsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_5);
                                        
                                            $fg5 = round(($sub->subject_5 + $subject5) / 2);
                                                                                            
                                            $subject6 = round(\App\Grade_sheet_firstsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_6);
                                            
                                            $fg6 = round(($sub->subject_6 + $subject6) / 2);
                                                                                            
                                            $subject7 = round(\App\Grade_sheet_firstsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_7);
                                            
                                            $fg7 = round(($sub->subject_7 + $subject7) / 2);
                                                                                            
                                            $subject8 = round(\App\Grade_sheet_firstsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_8);
                                            
                                            $fg8 = round(($sub->subject_8 + $subject8) / 2);
                                                                                            
                                            
                                        ?>
                            
                                        <td>
                                                <center>                                                
                                                        <?php
                                                            $average_1sem = number_format($average = ($fg1 + $fg2 + $fg3 + $fg4 + $fg5 + $fg6 + $fg7 + $fg8 )/8, 2);
                                                            echo round($average_1sem);
                                                        ?>
                                                </center>
                                        </td>
                                        
                                        @if(round($average_1sem) >= 75 && round($average_1sem) <= 89)
                                            <td>
                                                <center>Passed</center>
                                            </td>
                                        @elseif(round($average_1sem) >= 90 && round($average_1sem) <= 94)
                                            <td>
                                                <center>with honors</center>
                                            </td>
                                        @elseif(round($average_1sem)>= 95 && round($average_1sem) <= 97)
                                            <td>
                                                <center>with high honors</center>
                                            </td>
                                        @elseif(round($average_1sem) >= 98 && round($average_1sem) <= 100)
                                            <td>
                                                <center>with highest honors</center>
                                            </td>
                                        @elseif(round($average_1sem) < 75)
                                            <td>
                                                <center>Failed</center>
                                            </td>
                                        @endif
                                    </tr>
                                    @endforeach
                            @else
                                <tr>
                                    <td colspan="7">
                                        <b>Male</b>
                                    </td>
                                </tr>
                                @foreach($GradeSheetMale as $key => $sub)
                                <tr>
                                    <td>{{ $key + 1 }}.</td>
                                    <td>{{ $sub->student_name }}</td>
                                    <td style="text-align: center">
                                        <?php
                                        $formattedNum = round($average = ($sub->subject_1 + $sub->subject_2 + $sub->subject_3 + $sub->subject_4 + $sub->subject_5 + $sub->subject_6 + $sub->subject_7+ $sub->subject_8+ $sub->subject_9)/9);
                                        echo $formattedNum;
                                        ?>                                                
                                    </td>
                                    <td style="text-align: center">
                                        <?php
                                            $sec_g = \App\Grade_sheet_firstsemsecond::where('enrollment_id', $sub->enrollment_id)->first();
                                            $result = round($average = ($sec_g->subject_1 + $sec_g->subject_2 + $sec_g->subject_3 + $sec_g->subject_4 + $sec_g->subject_5 + $sec_g->subject_6 + $sec_g->subject_7 + $sec_g->subject_8 + $sec_g->subject_9)/9);
                                            echo $result;
                                        ?>    
                                    </td>

                                        <?php                                                    
                                        $subject1 = round(\App\Grade_sheet_firstsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_1);
                                
                                        $fg1 = round(($sub->subject_1 + $subject1) / 2);
                                                                                    
                                        $subject2 = round(\App\Grade_sheet_firstsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_2);
                                
                                        $fg2 = round(($sub->subject_2 + $subject2) / 2);
                                                                                    
                                        $subject3 = round(\App\Grade_sheet_firstsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_3);
                                        
                                        $fg3 = round(($sub->subject_3 + $subject3) / 2);
                                                                                        
                                        $subject4 = round(\App\Grade_sheet_firstsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_4);
                                        
                                        $fg4 = round(($sub->subject_4 + $subject4) / 2);
                                                                                        
                                        $subject5 = round(\App\Grade_sheet_firstsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_5);
                                    
                                        $fg5 = round(($sub->subject_5 + $subject5) / 2);
                                                                                        
                                        $subject6 = round(\App\Grade_sheet_firstsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_6);
                                        
                                        $fg6 = round(($sub->subject_6 + $subject6) / 2);
                                                                                        
                                        $subject7 = round(\App\Grade_sheet_firstsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_7);
                                        
                                        $fg7 = round(($sub->subject_7 + $subject7) / 2);
                                                                                        
                                        $subject8 = round(\App\Grade_sheet_firstsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_8);
                                        
                                        $fg8 = round(($sub->subject_8 + $subject8) / 2);
                                                                                        
                                        $subject9 = round(\App\Grade_sheet_firstsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_9);
                                    
                                        $fg9 = round(($sub->subject_9 + $subject9) / 2);
                                    ?>
                            
                                    <td>
                                            <center>                                                
                                                    <?php
                                                        $average_1sem = number_format($average = ($fg1 + $fg2 + $fg3 + $fg4 + $fg5 + $fg6 + $fg7 + $fg8 + $fg9)/9, 2);
                                                        echo round($average_1sem);
                                                    ?>
                                            </center>
                                    </td>
                                    
                                    @if(round($average_1sem) >= 75 && round($average_1sem) <= 89)
                                        <td>
                                            <center>Passed</center>
                                        </td>
                                    @elseif(round($average_1sem) >= 90 && round($average_1sem) <= 94)
                                        <td>
                                            <center>with honors</center>
                                        </td>
                                    @elseif(round($average_1sem)>= 95 && round($average_1sem) <= 97)
                                        <td>
                                            <center>with high honors</center>
                                        </td>
                                    @elseif(round($average_1sem) >= 98 && round($average_1sem) <= 100)
                                        <td>
                                            <center>with highest honors</center>
                                        </td>
                                    @elseif(round($average_1sem) < 75)
                                        <td>
                                            <center>Failed</center>
                                        </td>
                                    @endif
                                </tr>
                                @endforeach
                            
                                    <tr>
                                        <td colspan="7">
                                            <b>Female</b>
                                        </td>
                                    </tr>

                                    @foreach($GradeSheetFeMale as $key => $sub)
                                    <tr>
                                        <td>{{ $key + 1 }}.</td>
                                        <td>{{ $sub->student_name }}</td>
                                        <td style="text-align: center">
                                            <?php
                                            $formattedNum = round($average = ($sub->subject_1 + $sub->subject_2 + $sub->subject_3 + $sub->subject_4 + $sub->subject_5 + $sub->subject_6 + $sub->subject_7 + $sub->subject_8+ $sub->subject_9)/9);
                                            echo $formattedNum;
                                            ?>                                                
                                        </td>
                                        <td style="text-align: center">
                                            <?php
                                                $sec_g = \App\Grade_sheet_firstsemsecond::where('enrollment_id', $sub->enrollment_id)->first();
                                                $result = round($average = ($sec_g->subject_1 + $sec_g->subject_2 + $sec_g->subject_3 + $sec_g->subject_4 + $sec_g->subject_5 + $sec_g->subject_6 + $sec_g->subject_7 + $sec_g->subject_8 + $sec_g->subject_9)/9);
                                                echo $result;
                                            ?>    
                                            </td>
                                        
                                        <?php                                                    
                                            $subject1 = round(\App\Grade_sheet_firstsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_1);
                                    
                                            $fg1 = round(($sub->subject_1 + $subject1) / 2);
                                                                                        
                                            $subject2 = round(\App\Grade_sheet_firstsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_2);
                                    
                                            $fg2 = round(($sub->subject_2 + $subject2) / 2);
                                                                                        
                                            $subject3 = round(\App\Grade_sheet_firstsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_3);
                                            
                                            $fg3 = round(($sub->subject_3 + $subject3) / 2);
                                                                                            
                                            $subject4 = round(\App\Grade_sheet_firstsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_4);
                                            
                                            $fg4 = round(($sub->subject_4 + $subject4) / 2);
                                                                                            
                                            $subject5 = round(\App\Grade_sheet_firstsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_5);
                                        
                                            $fg5 = round(($sub->subject_5 + $subject5) / 2);
                                                                                            
                                            $subject6 = round(\App\Grade_sheet_firstsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_6);
                                            
                                            $fg6 = round(($sub->subject_6 + $subject6) / 2);
                                                                                            
                                            $subject7 = round(\App\Grade_sheet_firstsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_7);
                                            
                                            $fg7 = round(($sub->subject_7 + $subject7) / 2);
                                                                                            
                                            $subject8 = round(\App\Grade_sheet_firstsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_8);
                                            
                                            $fg8 = round(($sub->subject_8 + $subject8) / 2);
                                                                                            
                                            $subject9 = round(\App\Grade_sheet_firstsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_9);
                                        
                                            $fg9 = round(($sub->subject_9 + $subject9) / 2);
                                        ?>
                            
                                        <td>
                                                <center>                                                
                                                        <?php
                                                            $average_1sem = number_format($average = ($fg1 + $fg2 + $fg3 + $fg4 + $fg5 + $fg6 + $fg7 + $fg8 + $fg9)/9, 2);
                                                            echo round($average_1sem);
                                                        ?>
                                                </center>
                                        </td>
                                        
                                        @if(round($average_1sem) >= 75 && round($average_1sem) <= 89)
                                            <td>
                                                <center>Passed</center>
                                            </td>
                                        @elseif(round($average_1sem) >= 90 && round($average_1sem) <= 94)
                                            <td>
                                                <center>with honors</center>
                                            </td>
                                        @elseif(round($average_1sem)>= 95 && round($average_1sem) <= 97)
                                            <td>
                                                <center>with high honors</center>
                                            </td>
                                        @elseif(round($average_1sem) >= 98 && round($average_1sem) <= 100)
                                            <td>
                                                <center>with highest honors</center>
                                            </td>
                                        @elseif(round($average_1sem) < 75)
                                            <td>
                                                <center>Failed</center>
                                            </td>
                                        @endif
                                    
                                    </tr>
                                    @endforeach
                            @endif
                            
                        @elseif($sem == 'Second')
                                    
                                @if($NumberOfSubject->class_subject_order == 7)
                                    <tr>
                                        <td colspan="7">
                                            <b>Male</b>
                                        </td>
                                    </tr>
                                    @foreach($GradeSheetMale as $key => $sub)
                                    <tr>
                                        <td>{{ $key + 1 }}.</td>
                                        <td>{{ $sub->student_name }}</td>
                                        <td style="text-align: center">
                                            <?php
                                            $formattedNum = round($average = ($sub->subject_1 + $sub->subject_2 + $sub->subject_3 + $sub->subject_4 + $sub->subject_5 + $sub->subject_6 + $sub->subject_7)/7);
                                            echo $formattedNum;
                                            ?>                                                
                                        </td>
                                        <td style="text-align: center">
                                            <?php
                                                $sec_g = \App\Grade_sheet_secondsemsecond::where('enrollment_id', $sub->enrollment_id)->first();
                                                $result = round($average = ($sec_g->subject_1 + $sec_g->subject_2 + $sec_g->subject_3 + $sec_g->subject_4 + $sec_g->subject_5 + $sec_g->subject_6 + $sec_g->subject_7)/7);
                                                echo $result;
                                            ?>    
                                        </td>
                                        <?php                                                    
                                                $subject1 = round(\App\Grade11_Second_Sem::where('enrollment_id', $sub->enrollment_id)->first()->subject_1);
                                                $subject_1 = round(\App\Grade_sheet_secondsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_1);
                                        
                                                $fg_1 = round(($subject_1 + $subject1) / 2);
                                                                                            
                                                $subject2 = round(\App\Grade11_Second_Sem::where('enrollment_id', $sub->enrollment_id)->first()->subject_2);
                                                $subject_2 = round(\App\Grade_sheet_secondsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_2);
                                        
                                                $fg_2 = round(($subject_2 + $subject2) / 2);
                                                                                            
                                                $subject3 = round(\App\Grade11_Second_Sem::where('enrollment_id', $sub->enrollment_id)->first()->subject_3);
                                                $subject_3 = round(\App\Grade_sheet_secondsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_3);
    
                                                $fg_3 = round(($subject_3 + $subject3) / 2);
                                                                                                
                                                $subject4 = round(\App\Grade11_Second_Sem::where('enrollment_id', $sub->enrollment_id)->first()->subject_4);
                                                $subject_4 = round(\App\Grade_sheet_secondsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_4);
                                                
                                                $fg_4 = round(($subject_4 + $subject4) / 2);
                                                                                                
                                                $subject5 = round(\App\Grade11_Second_Sem::where('enrollment_id', $sub->enrollment_id)->first()->subject_5);
                                                $subject_5 = round(\App\Grade_sheet_secondsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_5);
                                            
                                                $fg_5 = round(($subject_5 + $subject5) / 2);
                                                                                                
                                                $subject6 = round(\App\Grade11_Second_Sem::where('enrollment_id', $sub->enrollment_id)->first()->subject_6);
                                                $subject_6 = round(\App\Grade_sheet_secondsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_6);
                                                
                                                $fg_6 = round(($subject_6 + $subject6) / 2);
                                                                                                
                                                $subject7 = round(\App\Grade11_Second_Sem::where('enrollment_id', $sub->enrollment_id)->first()->subject_7);
                                                $subject_7 = round(\App\Grade_sheet_secondsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_7);
                                                
                                                $fg_7 = round(($subject_7 + $subject7) / 2);
                                                                                                
                                                
                                        ?>
                               
                                        <td>
                                                <center>                                                
                                                        <?php
                                                            $average_2sem = round($average = ($fg_1 + $fg_2 + $fg_3 + $fg_4 + $fg_5 + $fg_6 + $fg_7 )/7, 2);
                                                            echo round($average_2sem);
                                                        ?>
                                                </center>
                                        </td>
                                        @if(round($average_2sem) >= 75 && round($average_2sem) <= 89)
                                            <td>
                                                <center>Passed</center>
                                            </td>
                                        @elseif(round($average_2sem) >= 90 && round($average_2sem) <= 94)
                                            <td>
                                                <center>with honors</center>
                                            </td>
                                        @elseif(round($average_2sem)>= 95 && round($average_2sem) <= 97)
                                            <td>
                                                <center>with high honors</center>
                                            </td>
                                        @elseif(round($average_2sem) >= 98 && round($average_2sem) <= 100)
                                            <td>
                                                <center>with highest honors</center>
                                            </td>
                                        @elseif(round($average_2sem) < 75)
                                            <td>
                                                <center>Failed</center>
                                            </td>
                                        @endif
                                    </tr>
                                    @endforeach
                                
                                        <tr>
                                            <td colspan="7">
                                                <b>Female</b>
                                            </td>
                                        </tr>
                                        @foreach($GradeSheetFeMale as $key => $sub)
                                        <tr>
                                            <td>{{ $key + 1 }}.</td>
                                            <td>{{ $sub->student_name }}</td>
                                            <td style="text-align: center">
                                                <?php
                                                $formattedNum = round($average = ($sub->subject_1 + $sub->subject_2 + $sub->subject_3 + $sub->subject_4 + $sub->subject_5 + $sub->subject_6 + $sub->subject_7)/7);
                                                echo $formattedNum;
                                                ?>                                                
                                            </td>
                                            <td style="text-align: center">
                                                <?php
                                                    $sec_g = \App\Grade_sheet_secondsemsecond::where('enrollment_id', $sub->enrollment_id)->first();
                                                    $result = round($average = ($sec_g->subject_1 + $sec_g->subject_2 + $sec_g->subject_3 + $sec_g->subject_4 + $sec_g->subject_5 + $sec_g->subject_6 + $sec_g->subject_7)/7);
                                                    echo $result;
                                                ?>    
                                            </td>
                                                <?php                                                    
                                                $subject1 = round(\App\Grade11_Second_Sem::where('enrollment_id', $sub->enrollment_id)->first()->subject_1);
                                                $subject_1 = round(\App\Grade_sheet_secondsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_1);
                                        
                                                $fg_1 = round(($subject_1 + $subject1) / 2);
                                                                                            
                                                $subject2 = round(\App\Grade11_Second_Sem::where('enrollment_id', $sub->enrollment_id)->first()->subject_2);
                                                $subject_2 = round(\App\Grade_sheet_secondsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_2);
                                        
                                                $fg_2 = round(($subject_2 + $subject2) / 2);
                                                                                            
                                                $subject3 = round(\App\Grade11_Second_Sem::where('enrollment_id', $sub->enrollment_id)->first()->subject_3);
                                                $subject_3 = round(\App\Grade_sheet_secondsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_3);
    
                                                $fg_3 = round(($subject_3 + $subject3) / 2);
                                                                                                
                                                $subject4 = round(\App\Grade11_Second_Sem::where('enrollment_id', $sub->enrollment_id)->first()->subject_4);
                                                $subject_4 = round(\App\Grade_sheet_secondsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_4);
                                                
                                                $fg_4 = round(($subject_4 + $subject4) / 2);
                                                                                                
                                                $subject5 = round(\App\Grade11_Second_Sem::where('enrollment_id', $sub->enrollment_id)->first()->subject_5);
                                                $subject_5 = round(\App\Grade_sheet_secondsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_5);
                                            
                                                $fg_5 = round(($subject_5 + $subject5) / 2);
                                                                                                
                                                $subject6 = round(\App\Grade11_Second_Sem::where('enrollment_id', $sub->enrollment_id)->first()->subject_6);
                                                $subject_6 = round(\App\Grade_sheet_secondsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_6);
                                                
                                                $fg_6 = round(($subject_6 + $subject6) / 2);
                                                                                                
                                                $subject7 = round(\App\Grade11_Second_Sem::where('enrollment_id', $sub->enrollment_id)->first()->subject_7);
                                                $subject_7 = round(\App\Grade_sheet_secondsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_7);
                                                
                                                $fg_7 = round(($subject_7 + $subject7) / 2);
                                                                                                
                                                
                                        ?>
                               
                                        <td>
                                                <center>                                                
                                                        <?php
                                                            $average_2sem = round($average = ($fg_1 + $fg_2 + $fg_3 + $fg_4 + $fg_5 + $fg_6 + $fg_7 )/7, 2);
                                                            echo round($average_2sem);
                                                        ?>
                                                </center>
                                        </td>
                                        @if(round($average_2sem) >= 75 && round($average_2sem) <= 89)
                                            <td>
                                                <center>Passed</center>
                                            </td>
                                        @elseif(round($average_2sem) >= 90 && round($average_2sem) <= 94)
                                            <td>
                                                <center>with honors</center>
                                            </td>
                                        @elseif(round($average_2sem)>= 95 && round($average_2sem) <= 97)
                                            <td>
                                                <center>with high honors</center>
                                            </td>
                                        @elseif(round($average_2sem) >= 98 && round($average_2sem) <= 100)
                                            <td>
                                                <center>with highest honors</center>
                                            </td>
                                        @elseif(round($average_2sem) < 75)
                                            <td>
                                                <center>Failed</center>
                                            </td>
                                        @endif
                                        </tr>
                                        @endforeach
                                @elseif($NumberOfSubject->class_subject_order == 8)
                                    <tr>
                                        <td colspan="7">
                                            <b>Male</b>
                                        </td>
                                    </tr>
                                    @foreach($GradeSheetMale as $key => $sub)
                                    <tr>
                                        <td>{{ $key + 1 }}.</td>
                                        <td>{{ $sub->student_name }}</td>
                                        <td style="text-align: center">
                                            <?php
                                            $formattedNum = round($average = ($sub->subject_1 + $sub->subject_2 + $sub->subject_3 + $sub->subject_4 + $sub->subject_5 + $sub->subject_6 + $sub->subject_7 + $sub->subject_8)/8);
                                            echo $formattedNum;
                                            ?>                                                
                                        </td>
                                        <td style="text-align: center">
                                            <?php
                                                $sec_g = \App\Grade_sheet_secondsemsecond::where('enrollment_id', $sub->enrollment_id)->first();
                                                $result = round($average = ($sec_g->subject_1 + $sec_g->subject_2 + $sec_g->subject_3 + $sec_g->subject_4 + $sec_g->subject_5 + $sec_g->subject_6 + $sec_g->subject_7 + $sec_g->subject_8)/8);
                                                echo $result;
                                            ?>    
                                        </td>
                                        <?php                                                    
                                                $subject1 = round(\App\Grade11_Second_Sem::where('enrollment_id', $sub->enrollment_id)->first()->subject_1);
                                                $subject_1 = round(\App\Grade_sheet_secondsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_1);
                                        
                                                $fg_1 = round(($subject_1 + $subject1) / 2);
                                                                                            
                                                $subject2 = round(\App\Grade11_Second_Sem::where('enrollment_id', $sub->enrollment_id)->first()->subject_2);
                                                $subject_2 = round(\App\Grade_sheet_secondsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_2);
                                        
                                                $fg_2 = round(($subject_2 + $subject2) / 2);
                                                                                            
                                                $subject3 = round(\App\Grade11_Second_Sem::where('enrollment_id', $sub->enrollment_id)->first()->subject_3);
                                                $subject_3 = round(\App\Grade_sheet_secondsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_3);
    
                                                $fg_3 = round(($subject_3 + $subject3) / 2);
                                                                                                
                                                $subject4 = round(\App\Grade11_Second_Sem::where('enrollment_id', $sub->enrollment_id)->first()->subject_4);
                                                $subject_4 = round(\App\Grade_sheet_secondsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_4);
                                                
                                                $fg_4 = round(($subject_4 + $subject4) / 2);
                                                                                                
                                                $subject5 = round(\App\Grade11_Second_Sem::where('enrollment_id', $sub->enrollment_id)->first()->subject_5);
                                                $subject_5 = round(\App\Grade_sheet_secondsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_5);
                                            
                                                $fg_5 = round(($subject_5 + $subject5) / 2);
                                                                                                
                                                $subject6 = round(\App\Grade11_Second_Sem::where('enrollment_id', $sub->enrollment_id)->first()->subject_6);
                                                $subject_6 = round(\App\Grade_sheet_secondsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_6);
                                                
                                                $fg_6 = round(($subject_6 + $subject6) / 2);
                                                                                                
                                                $subject7 = round(\App\Grade11_Second_Sem::where('enrollment_id', $sub->enrollment_id)->first()->subject_7);
                                                $subject_7 = round(\App\Grade_sheet_secondsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_7);
                                                
                                                $fg_7 = round(($subject_7 + $subject7) / 2);
                                                                                                
                                                $subject8 = round(\App\Grade11_Second_Sem::where('enrollment_id', $sub->enrollment_id)->first()->subject_8);
                                                $subject_8 = round(\App\Grade_sheet_secondsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_8);
                                                
                                                $fg_8 = round(($subject_8 + $subject8) / 2);
                                                                                                
                                                
                                        ?>
                               
                                        <td>
                                                <center>                                                
                                                        <?php
                                                            $average_2sem = round($average = ($fg_1 + $fg_2 + $fg_3 + $fg_4 + $fg_5 + $fg_6 + $fg_7 + $fg_8 )/8, 2);
                                                            echo round($average_2sem);
                                                        ?>
                                                </center>
                                        </td>
                                        @if(round($average_2sem) >= 75 && round($average_2sem) <= 89)
                                            <td>
                                                <center>Passed</center>
                                            </td>
                                        @elseif(round($average_2sem) >= 90 && round($average_2sem) <= 94)
                                            <td>
                                                <center>with honors</center>
                                            </td>
                                        @elseif(round($average_2sem)>= 95 && round($average_2sem) <= 97)
                                            <td>
                                                <center>with high honors</center>
                                            </td>
                                        @elseif(round($average_2sem) >= 98 && round($average_2sem) <= 100)
                                            <td>
                                                <center>with highest honors</center>
                                            </td>
                                        @elseif(round($average_2sem) < 75)
                                            <td>
                                                <center>Failed</center>
                                            </td>
                                        @endif
                                    </tr>
                                    @endforeach
                                
                                        <tr>
                                            <td colspan="7">
                                                <b>Female</b>
                                            </td>
                                        </tr>
                                        @foreach($GradeSheetFeMale as $key => $sub)
                                        <tr>
                                            <td>{{ $key + 1 }}.</td>
                                            <td>{{ $sub->student_name }}</td>
                                            <td style="text-align: center">
                                                <?php
                                                $formattedNum = round($average = ($sub->subject_1 + $sub->subject_2 + $sub->subject_3 + $sub->subject_4 + $sub->subject_5 + $sub->subject_6 + $sub->subject_7 + $sub->subject_8)/8);
                                                echo $formattedNum;
                                                ?>                                                
                                            </td>
                                            <td style="text-align: center">
                                                <?php
                                                    $sec_g = \App\Grade_sheet_secondsemsecond::where('enrollment_id', $sub->enrollment_id)->first();
                                                    $result = round($average = ($sec_g->subject_1 + $sec_g->subject_2 + $sec_g->subject_3 + $sec_g->subject_4 + $sec_g->subject_5 + $sec_g->subject_6 + $sec_g->subject_7 + $sec_g->subject_8)/8);
                                                echo $result;
                                                ?>    
                                            </td>
                                            <?php                                                    
                                                $subject1 = round(\App\Grade11_Second_Sem::where('enrollment_id', $sub->enrollment_id)->first()->subject_1);
                                                $subject_1 = round(\App\Grade_sheet_secondsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_1);
                                        
                                                $fg_1 = round(($subject_1 + $subject1) / 2);
                                                                                            
                                                $subject2 = round(\App\Grade11_Second_Sem::where('enrollment_id', $sub->enrollment_id)->first()->subject_2);
                                                $subject_2 = round(\App\Grade_sheet_secondsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_2);
                                        
                                                $fg_2 = round(($subject_2 + $subject2) / 2);
                                                                                            
                                                $subject3 = round(\App\Grade11_Second_Sem::where('enrollment_id', $sub->enrollment_id)->first()->subject_3);
                                                $subject_3 = round(\App\Grade_sheet_secondsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_3);
    
                                                $fg_3 = round(($subject_3 + $subject3) / 2);
                                                                                                
                                                $subject4 = round(\App\Grade11_Second_Sem::where('enrollment_id', $sub->enrollment_id)->first()->subject_4);
                                                $subject_4 = round(\App\Grade_sheet_secondsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_4);
                                                
                                                $fg_4 = round(($subject_4 + $subject4) / 2);
                                                                                                
                                                $subject5 = round(\App\Grade11_Second_Sem::where('enrollment_id', $sub->enrollment_id)->first()->subject_5);
                                                $subject_5 = round(\App\Grade_sheet_secondsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_5);
                                            
                                                $fg_5 = round(($subject_5 + $subject5) / 2);
                                                                                                
                                                $subject6 = round(\App\Grade11_Second_Sem::where('enrollment_id', $sub->enrollment_id)->first()->subject_6);
                                                $subject_6 = round(\App\Grade_sheet_secondsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_6);
                                                
                                                $fg_6 = round(($subject_6 + $subject6) / 2);
                                                                                                
                                                $subject7 = round(\App\Grade11_Second_Sem::where('enrollment_id', $sub->enrollment_id)->first()->subject_7);
                                                $subject_7 = round(\App\Grade_sheet_secondsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_7);
                                                
                                                $fg_7 = round(($subject_7 + $subject7) / 2);
                                                                                                
                                                $subject8 = round(\App\Grade11_Second_Sem::where('enrollment_id', $sub->enrollment_id)->first()->subject_8);
                                                $subject_8 = round(\App\Grade_sheet_secondsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_8);
                                                
                                                $fg_8 = round(($subject_8 + $subject8) / 2);
                                                                                                
                                                
                                        ?>
                               
                                        <td>
                                                <center>                                                
                                                        <?php
                                                            $average_2sem = round($average = ($fg_1 + $fg_2 + $fg_3 + $fg_4 + $fg_5 + $fg_6 + $fg_7 + $fg_8)/8, 2);
                                                            echo round($average_2sem);
                                                        ?>
                                                </center>
                                        </td>
                                        @if(round($average_2sem) >= 75 && round($average_2sem) <= 89)
                                            <td>
                                                <center>Passed</center>
                                            </td>
                                        @elseif(round($average_2sem) >= 90 && round($average_2sem) <= 94)
                                            <td>
                                                <center>with honors</center>
                                            </td>
                                        @elseif(round($average_2sem)>= 95 && round($average_2sem) <= 97)
                                            <td>
                                                <center>with high honors</center>
                                            </td>
                                        @elseif(round($average_2sem) >= 98 && round($average_2sem) <= 100)
                                            <td>
                                                <center>with highest honors</center>
                                            </td>
                                        @elseif(round($average_2sem) < 75)
                                            <td>
                                                <center>Failed</center>
                                            </td>
                                        @endif
                                        </tr>
                                        @endforeach
                                @else
                                    <tr>
                                        <td colspan="7">
                                            <b>Male</b>
                                        </td>
                                    </tr>
                                    @foreach($GradeSheetMale as $key => $sub)
                                    <tr>
                                        <td>{{ $key + 1 }}.</td>
                                        <td>{{ $sub->student_name }}</td>
                                        <td style="text-align: center">
                                            <?php
                                            $formattedNum = round($average = ($sub->subject_1 + $sub->subject_2 + $sub->subject_3 + $sub->subject_4 + $sub->subject_5 + $sub->subject_6 + $sub->subject_7+ $sub->subject_8+ $sub->subject_9)/9);
                                            echo $formattedNum;
                                            ?>                                                
                                        </td>
                                        <td style="text-align: center">
                                            <?php
                                                $sec_g = \App\Grade_sheet_secondsemsecond::where('enrollment_id', $sub->enrollment_id)->first();
                                                $result = round($average = ($sec_g->subject_1 + $sec_g->subject_2 + $sec_g->subject_3 + $sec_g->subject_4 + $sec_g->subject_5 + $sec_g->subject_6 + $sec_g->subject_7 + $sec_g->subject_8 + $sec_g->subject_9)/9);
                                                echo $result;
                                            ?>    
                                        </td>
                                        <?php                                                    
                                                $subject1 = round(\App\Grade11_Second_Sem::where('enrollment_id', $sub->enrollment_id)->first()->subject_1);
                                                $subject_1 = round(\App\Grade_sheet_secondsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_1);
                                        
                                                $fg_1 = round(($subject_1 + $subject1) / 2);
                                                                                            
                                                $subject2 = round(\App\Grade11_Second_Sem::where('enrollment_id', $sub->enrollment_id)->first()->subject_2);
                                                $subject_2 = round(\App\Grade_sheet_secondsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_2);
                                        
                                                $fg_2 = round(($subject_2 + $subject2) / 2);
                                                                                            
                                                $subject3 = round(\App\Grade11_Second_Sem::where('enrollment_id', $sub->enrollment_id)->first()->subject_3);
                                                $subject_3 = round(\App\Grade_sheet_secondsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_3);
    
                                                $fg_3 = round(($subject_3 + $subject3) / 2);
                                                                                                
                                                $subject4 = round(\App\Grade11_Second_Sem::where('enrollment_id', $sub->enrollment_id)->first()->subject_4);
                                                $subject_4 = round(\App\Grade_sheet_secondsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_4);
                                                
                                                $fg_4 = round(($subject_4 + $subject4) / 2);
                                                                                                
                                                $subject5 = round(\App\Grade11_Second_Sem::where('enrollment_id', $sub->enrollment_id)->first()->subject_5);
                                                $subject_5 = round(\App\Grade_sheet_secondsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_5);
                                            
                                                $fg_5 = round(($subject_5 + $subject5) / 2);
                                                                                                
                                                $subject6 = round(\App\Grade11_Second_Sem::where('enrollment_id', $sub->enrollment_id)->first()->subject_6);
                                                $subject_6 = round(\App\Grade_sheet_secondsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_6);
                                                
                                                $fg_6 = round(($subject_6 + $subject6) / 2);
                                                                                                
                                                $subject7 = round(\App\Grade11_Second_Sem::where('enrollment_id', $sub->enrollment_id)->first()->subject_7);
                                                $subject_7 = round(\App\Grade_sheet_secondsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_7);
                                                
                                                $fg_7 = round(($subject_7 + $subject7) / 2);
                                                                                                
                                                $subject8 = round(\App\Grade11_Second_Sem::where('enrollment_id', $sub->enrollment_id)->first()->subject_8);
                                                $subject_8 = round(\App\Grade_sheet_secondsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_8);
                                                
                                                $fg_8 = round(($subject_8 + $subject8) / 2);
                                                                                                
                                                $subject9 = round(\App\Grade11_Second_Sem::where('enrollment_id', $sub->enrollment_id)->first()->subject_9);
                                                $subject_9 = round(\App\Grade_sheet_secondsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_9);
                                                
                                                $fg_9 = round(($subject_9 + $subject9) / 2);
                                        ?>
                               
                                        <td>
                                                <center>                                                
                                                        <?php
                                                            $average_2sem = round($average = ($fg_1 + $fg_2 + $fg_3 + $fg_4 + $fg_5 + $fg_6 + $fg_7 + $fg_8 + $fg_9)/9, 2);
                                                            echo round($average_2sem);
                                                        ?>
                                                </center>
                                        </td>
                                        @if(round($average_2sem) >= 75 && round($average_2sem) <= 89)
                                            <td>
                                                <center>Passed</center>
                                            </td>
                                        @elseif(round($average_2sem) >= 90 && round($average_2sem) <= 94)
                                            <td>
                                                <center>with honors</center>
                                            </td>
                                        @elseif(round($average_2sem)>= 95 && round($average_2sem) <= 97)
                                            <td>
                                                <center>with high honors</center>
                                            </td>
                                        @elseif(round($average_2sem) >= 98 && round($average_2sem) <= 100)
                                            <td>
                                                <center>with highest honors</center>
                                            </td>
                                        @elseif(round($average_2sem) < 75)
                                            <td>
                                                <center>Failed</center>
                                            </td>
                                        @endif
                                    </tr>
                                    @endforeach
                                
                                        <tr>
                                            <td colspan="7">
                                                <b>Female</b>
                                            </td>
                                        </tr>
                                        @foreach($GradeSheetFeMale as $key => $sub)
                                        <tr>
                                            <td>{{ $key + 1 }}.</td>
                                            <td>{{ $sub->student_name }}</td>
                                            <td style="text-align: center">
                                                <?php
                                                $formattedNum = round($average = ($sub->subject_1 + $sub->subject_2 + $sub->subject_3 + $sub->subject_4 + $sub->subject_5 + $sub->subject_6 + $sub->subject_7 + $sub->subject_8 + $sub->subject_9)/9);
                                                echo $formattedNum;
                                                ?>                                                
                                            </td>
                                            <td style="text-align: center">
                                                <?php
                                                    $sec_g = \App\Grade_sheet_secondsemsecond::where('enrollment_id', $sub->enrollment_id)->first();
                                                    $result = round($average = ($sec_g->subject_1 + $sec_g->subject_2 + $sec_g->subject_3 + $sec_g->subject_4 + $sec_g->subject_5 + $sec_g->subject_6 + $sec_g->subject_7 + $sec_g->subject_8 + $sec_g->subject_9)/9);
                                                    echo $result;
                                                ?>    
                                                </td>
                                                <?php                                                    
                                                    $subject1 = round(\App\Grade11_Second_Sem::where('enrollment_id', $sub->enrollment_id)->first()->subject_1);
                                                    $subject_1 = round(\App\Grade_sheet_secondsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_1);
                                            
                                                    $fg_1 = round(($subject_1 + $subject1) / 2);
                                                                                                
                                                    $subject2 = round(\App\Grade11_Second_Sem::where('enrollment_id', $sub->enrollment_id)->first()->subject_2);
                                                    $subject_2 = round(\App\Grade_sheet_secondsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_2);
                                            
                                                    $fg_2 = round(($subject_2 + $subject2) / 2);
                                                                                                
                                                    $subject3 = round(\App\Grade11_Second_Sem::where('enrollment_id', $sub->enrollment_id)->first()->subject_3);
                                                    $subject_3 = round(\App\Grade_sheet_secondsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_3);
        
                                                    $fg_3 = round(($subject_3 + $subject3) / 2);
                                                                                                    
                                                    $subject4 = round(\App\Grade11_Second_Sem::where('enrollment_id', $sub->enrollment_id)->first()->subject_4);
                                                    $subject_4 = round(\App\Grade_sheet_secondsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_4);
                                                    
                                                    $fg_4 = round(($subject_4 + $subject4) / 2);
                                                                                                    
                                                    $subject5 = round(\App\Grade11_Second_Sem::where('enrollment_id', $sub->enrollment_id)->first()->subject_5);
                                                    $subject_5 = round(\App\Grade_sheet_secondsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_5);
                                                
                                                    $fg_5 = round(($subject_5 + $subject5) / 2);
                                                                                                    
                                                    $subject6 = round(\App\Grade11_Second_Sem::where('enrollment_id', $sub->enrollment_id)->first()->subject_6);
                                                    $subject_6 = round(\App\Grade_sheet_secondsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_6);
                                                    
                                                    $fg_6 = round(($subject_6 + $subject6) / 2);
                                                                                                    
                                                    $subject7 = round(\App\Grade11_Second_Sem::where('enrollment_id', $sub->enrollment_id)->first()->subject_7);
                                                    $subject_7 = round(\App\Grade_sheet_secondsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_7);
                                                    
                                                    $fg_7 = round(($subject_7 + $subject7) / 2);
                                                                                                    
                                                    $subject8 = round(\App\Grade11_Second_Sem::where('enrollment_id', $sub->enrollment_id)->first()->subject_8);
                                                    $subject_8 = round(\App\Grade_sheet_secondsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_8);
                                                    
                                                    $fg_8 = round(($subject_8 + $subject8) / 2);
                                                                                                    
                                                    $subject9 = round(\App\Grade11_Second_Sem::where('enrollment_id', $sub->enrollment_id)->first()->subject_9);
                                                    $subject_9 = round(\App\Grade_sheet_secondsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_9);
                                                    
                                                    $fg_9 = round(($subject_9 + $subject9) / 2);
                                               ?>
                                       
                                           <td>
                                                   <center>                                                
                                                           <?php
                                                               $average_2sem = round($average = ($fg_1 + $fg_2 + $fg_3 + $fg_4 + $fg_5 + $fg_6 + $fg_7 + $fg_8 + $fg_9)/9, 2);
                                                               echo round($average_2sem);
                                                           ?>
                                                   </center>
                                           </td>
                                            @if(round($average_2sem) >= 75 && round($average_2sem) <= 89)
                                                <td>
                                                    <center>Passed</center>
                                                </td>
                                            @elseif(round($average_2sem) >= 90 && round($average_2sem) <= 94)
                                                <td>
                                                    <center>with honors</center>
                                                </td>
                                            @elseif(round($average_2sem)>= 95 && round($average_2sem) <= 97)
                                                <td>
                                                    <center>with high honors</center>
                                                </td>
                                            @elseif(round($average_2sem) >= 98 && round($average_2sem) <= 100)
                                                <td>
                                                    <center>with highest honors</center>
                                                </td>
                                            @elseif(round($average_2sem) < 75)
                                                <td>
                                                    <center>Failed</center>
                                                </td>
                                            @endif
                                        </tr>
                                        @endforeach
                                @endif
                        @else
                        <tr>
                                <td colspan="7">
                                    <b>Male</b>
                                </td>
                            </tr>
                            @foreach($GradeSheetMale as $key => $sub)
                            <tr>
                                <td>{{ $key + 1 }}.</td>
                                <td>{{ $sub->student_name }}</td>
                                
                                
                                
                                
                                    <?php                                                    
                                        $subject1 = round(\App\Grade_sheet_firstsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_1);
                                    
                                        $fg1 = round(($sub->subject_1 + $subject1) / 2);
                                                                                        
                                        $subject2 = round(\App\Grade_sheet_firstsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_2);
                                    
                                        $fg2 = round(($sub->subject_2 + $subject2) / 2);
                                                                                        
                                        $subject3 = round(\App\Grade_sheet_firstsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_3);
                                        
                                        $fg3 = round(($sub->subject_3 + $subject3) / 2);
                                                                                            
                                        $subject4 = round(\App\Grade_sheet_firstsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_4);
                                        
                                        $fg4 = round(($sub->subject_4 + $subject4) / 2);
                                                                                            
                                        $subject5 = round(\App\Grade_sheet_firstsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_5);
                                        
                                        $fg5 = round(($sub->subject_5 + $subject5) / 2);
                                                                                            
                                        $subject6 = round(\App\Grade_sheet_firstsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_6);
                                        
                                        $fg6 = round(($sub->subject_6 + $subject6) / 2);
                                                                                        
                                        $subject7 = round(\App\Grade_sheet_firstsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_7);
                                        
                                        $fg7 = round(($sub->subject_7 + $subject7) / 2);
                                                                                            
                                        $subject8 = round(\App\Grade_sheet_firstsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_8);
                                        
                                        $fg8 = round(($sub->subject_8 + $subject8) / 2);
                                                                                        
                                        $subject9 = round(\App\Grade_sheet_firstsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_9);
                                    
                                        $fg9 = round(($sub->subject_9 + $subject9) / 2);
                                        ?>
                                
                                    <td>
                                            <center>                                                
                                                    <?php
                                                        $average_1sem = number_format($average = ($fg1 + $fg2 + $fg3 + $fg4 + $fg5 + $fg6 + $fg7 + $fg8 + $fg9)/9, 2);
                                                        echo round($average_1sem);
                                                    ?>
                                            </center>
                                    </td>
                                
                                    @if($NumberOfSubject->class_subject_order == 7)
                                
                                        <?php                                                    
                                            $subject1 = round(\App\Grade11_Second_Sem::where('enrollment_id', $sub->enrollment_id)->first()->subject_1);
                                            $subject_1 = round(\App\Grade_sheet_secondsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_1);
                                    
                                            $fg_1 = round(($subject1 + $subject_1) / 2);
                                                                                        
                                            $subject2 = round(\App\Grade11_Second_Sem::where('enrollment_id', $sub->enrollment_id)->first()->subject_2);
                                            $subject_2 = round(\App\Grade_sheet_secondsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_2);
                                    
                                            $fg_2 = round(($subject_2 + $subject2) / 2);
                                                                                        
                                            $subject3 = round(\App\Grade11_Second_Sem::where('enrollment_id', $sub->enrollment_id)->first()->subject_3);
                                            $subject_3 = round(\App\Grade_sheet_secondsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_3);

                                            $fg_3 = round(($subject_3 + $subject3) / 2);
                                                                                            
                                            $subject4 = round(\App\Grade11_Second_Sem::where('enrollment_id', $sub->enrollment_id)->first()->subject_4);
                                            $subject_4 = round(\App\Grade_sheet_secondsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_4);
                                            
                                            $fg_4 = round(($subject_4 + $subject4) / 2);
                                                                                            
                                            $subject5 = round(\App\Grade11_Second_Sem::where('enrollment_id', $sub->enrollment_id)->first()->subject_5);
                                            $subject_5 = round(\App\Grade_sheet_secondsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_5);
                                        
                                            $fg_5 = round(($subject_5 + $subject5) / 2);
                                                                                            
                                            $subject6 = round(\App\Grade11_Second_Sem::where('enrollment_id', $sub->enrollment_id)->first()->subject_6);
                                            $subject_6 = round(\App\Grade_sheet_secondsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_6);
                                            
                                            $fg_6 = round(($subject_6 + $subject6) / 2);
                                                                                            
                                            $subject7 = round(\App\Grade11_Second_Sem::where('enrollment_id', $sub->enrollment_id)->first()->subject_7);
                                            $subject_7 = round(\App\Grade_sheet_secondsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_7);
                                            
                                            $fg_7 = round(($subject_7 + $subject7) / 2);                                                                                               
                                            
                                        ?>
                                    
                                        <td>
                                                <center>                                                
                                                        <?php
                                                            $average_2sem = number_format($average = ($fg_1 + $fg_2 + $fg_3 + $fg_4 + $fg_5 + $fg_6 + $fg_7 )/7, 2);
                                                            echo round($average_2sem);
                                                        ?>
                                                </center>
                                        </td>

                                    @elseif($NumberOfSubject->class_subject_order == 8)

                                        <?php                                                    
                                            $subject1 = round(\App\Grade11_Second_Sem::where('enrollment_id', $sub->enrollment_id)->first()->subject_1);
                                            $subject_1 = round(\App\Grade_sheet_secondsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_1);
                                    
                                            $fg_1 = round(($subject1 + $subject_1) / 2);
                                                                                        
                                            $subject2 = round(\App\Grade11_Second_Sem::where('enrollment_id', $sub->enrollment_id)->first()->subject_2);
                                            $subject_2 = round(\App\Grade_sheet_secondsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_2);
                                    
                                            $fg_2 = round(($subject_2 + $subject2) / 2);
                                                                                        
                                            $subject3 = round(\App\Grade11_Second_Sem::where('enrollment_id', $sub->enrollment_id)->first()->subject_3);
                                            $subject_3 = round(\App\Grade_sheet_secondsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_3);

                                            $fg_3 = round(($subject_3 + $subject3) / 2);
                                                                                            
                                            $subject4 = round(\App\Grade11_Second_Sem::where('enrollment_id', $sub->enrollment_id)->first()->subject_4);
                                            $subject_4 = round(\App\Grade_sheet_secondsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_4);
                                            
                                            $fg_4 = round(($subject_4 + $subject4) / 2);
                                                                                            
                                            $subject5 = round(\App\Grade11_Second_Sem::where('enrollment_id', $sub->enrollment_id)->first()->subject_5);
                                            $subject_5 = round(\App\Grade_sheet_secondsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_5);
                                        
                                            $fg_5 = round(($subject_5 + $subject5) / 2);
                                                                                            
                                            $subject6 = round(\App\Grade11_Second_Sem::where('enrollment_id', $sub->enrollment_id)->first()->subject_6);
                                            $subject_6 = round(\App\Grade_sheet_secondsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_6);
                                            
                                            $fg_6 = round(($subject_6 + $subject6) / 2);
                                                                                            
                                            $subject7 = round(\App\Grade11_Second_Sem::where('enrollment_id', $sub->enrollment_id)->first()->subject_7);
                                            $subject_7 = round(\App\Grade_sheet_secondsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_7);
                                            
                                            $fg_7 = round(($subject_7 + $subject7) / 2);
                                                                                            
                                            $subject8 = round(\App\Grade11_Second_Sem::where('enrollment_id', $sub->enrollment_id)->first()->subject_8);
                                            $subject_8 = round(\App\Grade_sheet_secondsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_8);
                                            
                                            $fg_8 = round(($subject_8 + $subject8) / 2);
                                        ?>
                        
                                        <td>
                                                <center>                                                
                                                        <?php
                                                            $average_2sem = number_format($average = ($fg_1 + $fg_2 + $fg_3 + $fg_4 + $fg_5 + $fg_6 + $fg_7 + $fg_8 )/8, 2);
                                                            echo round($average_2sem);
                                                        ?>
                                                </center>
                                        </td>


                                    @elseif($NumberOfSubject->class_subject_order == 9)
                                        <?php                                                    
                                            $subject1 = round(\App\Grade11_Second_Sem::where('enrollment_id', $sub->enrollment_id)->first()->subject_1);
                                            $subject_1 = round(\App\Grade_sheet_secondsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_1);
                                        
                                            $fg_1 = round(($subject_1 + $subject1) / 2);
                                                                                            
                                            $subject2 = round(\App\Grade11_Second_Sem::where('enrollment_id', $sub->enrollment_id)->first()->subject_2);
                                            $subject_2 = round(\App\Grade_sheet_secondsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_2);
                                        
                                            $fg_2 = round(($subject_2 + $subject2) / 2);
                                                                                            
                                            $subject3 = round(\App\Grade11_Second_Sem::where('enrollment_id', $sub->enrollment_id)->first()->subject_3);
                                            $subject_3 = round(\App\Grade_sheet_secondsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_3);

                                            $fg_3 = round(($subject_3 + $subject3) / 2);
                                                                                                
                                            $subject4 = round(\App\Grade11_Second_Sem::where('enrollment_id', $sub->enrollment_id)->first()->subject_4);
                                            $subject_4 = round(\App\Grade_sheet_secondsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_4);
                                            
                                            $fg_4 = round(($subject_4 + $subject4) / 2);
                                                                                                
                                            $subject5 = round(\App\Grade11_Second_Sem::where('enrollment_id', $sub->enrollment_id)->first()->subject_5);
                                            $subject_5 = round(\App\Grade_sheet_secondsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_5);
                                            
                                            $fg_5 = round(($subject_5 + $subject5) / 2);
                                                                                                
                                            $subject6 = round(\App\Grade11_Second_Sem::where('enrollment_id', $sub->enrollment_id)->first()->subject_6);
                                            $subject_6 = round(\App\Grade_sheet_secondsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_6);
                                            
                                            $fg_6 = round(($subject_6 + $subject6) / 2);
                                                                                            
                                            $subject7 = round(\App\Grade11_Second_Sem::where('enrollment_id', $sub->enrollment_id)->first()->subject_7);
                                            $subject_7 = round(\App\Grade_sheet_secondsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_7);
                                            
                                            $fg_7 = round(($subject_7 + $subject7) / 2);
                                                                                                
                                            $subject8 = round(\App\Grade11_Second_Sem::where('enrollment_id', $sub->enrollment_id)->first()->subject_8);
                                            $subject_8 = round(\App\Grade_sheet_secondsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_8);
                                            
                                            $fg_8 = round(($subject_8 + $subject8) / 2);
                                                                                            
                                            $subject9 = round(\App\Grade11_Second_Sem::where('enrollment_id', $sub->enrollment_id)->first()->subject_9);
                                            $subject_9 = round(\App\Grade_sheet_secondsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_9);
                                            
                                            $fg_9 = round(($subject_9 + $subject9) / 2);
                                        ?>
                            
                                        <td style="text-align: center">
                                            <?php
                                            $average_2sem = $average = ($fg_1 + $fg_2 + $fg_3 + $fg_4 + $fg_5 + $fg_6 + $fg_7 + $fg_8 + $fg_9)/9;
                                            echo round($average_2sem);
                                            ?>
                                        </td>

                                    @endif
                                
                                    <td style="text-align: center">
                                        <?php 
                                            // echo round($result_final = ($formattedNum + $result + $thi_result + $fou_result) / 4);
                                            echo round($result_final = (round($average_1sem) + round($average_2sem) ) /2) ;
                                        ?>
                                    </td>
                                @if(round($result_final) >= 75 && round($result_final) <= 89)
                                    <td>
                                        <center>Passed</center>
                                    </td>
                                @elseif(round($result_final) >= 90 && round($result_final) <= 94)
                                    <td>
                                        <center>with honors</center>
                                    </td>
                                @elseif(round($result_final)>= 95 && round($result_final) <= 97)
                                    <td>
                                        <center>with high honors</center>
                                    </td>
                                @elseif(round($result_final) >= 98 && round($result_final) <= 100)
                                    <td>
                                        <center>with highest honors</center>
                                    </td>
                                @elseif(round($result_final) < 75)
                                    <td>
                                        <center>Failed</center>
                                    </td>
                                @endif
                            </tr>
                            @endforeach
                        
                            <tr>
                                <td colspan="7">
                                    <b>Female</b>
                                </td>
                            </tr>
                            @foreach($GradeSheetFeMale as $key => $sub)
                                <tr>
                                    <td>{{ $key + 1 }}.</td>
                                    <td>{{ $sub->student_name }}</td>
                                    
                                <?php                                                    
                                        $subject1 = round(\App\Grade_sheet_firstsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_1);
                                    
                                        $fg1 = round(($sub->subject_1 + $subject1) / 2);
                                                                                        
                                        $subject2 = round(\App\Grade_sheet_firstsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_2);
                                    
                                        $fg2 = round(($sub->subject_2 + $subject2) / 2);
                                                                                        
                                        $subject3 = round(\App\Grade_sheet_firstsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_3);
                                        
                                        $fg3 = round(($sub->subject_3 + $subject3) / 2);
                                                                                            
                                        $subject4 = round(\App\Grade_sheet_firstsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_4);
                                        
                                        $fg4 = round(($sub->subject_4 + $subject4) / 2);
                                                                                            
                                        $subject5 = round(\App\Grade_sheet_firstsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_5);
                                        
                                        $fg5 = round(($sub->subject_5 + $subject5) / 2);
                                                                                            
                                        $subject6 = round(\App\Grade_sheet_firstsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_6);
                                        
                                        $fg6 = round(($sub->subject_6 + $subject6) / 2);
                                                                                        
                                        $subject7 = round(\App\Grade_sheet_firstsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_7);
                                        
                                        $fg7 = round(($sub->subject_7 + $subject7) / 2);
                                                                                            
                                        $subject8 = round(\App\Grade_sheet_firstsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_8);
                                        
                                        $fg8 = round(($sub->subject_8 + $subject8) / 2);
                                                                                        
                                        $subject9 = round(\App\Grade_sheet_firstsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_9);
                                    
                                        $fg9 = round(($sub->subject_9 + $subject9) / 2);
                                        ?>
                                
                                        <td>
                                                <center>                                                
                                                        <?php
                                                            $average_1sem = number_format($average = ($fg1 + $fg2 + $fg3 + $fg4 + $fg5 + $fg6 + $fg7 + $fg8 + $fg9)/9, 2);
                                                            echo round($average_1sem);
                                                        ?>
                                                </center>
                                        </td>
                                
                                        @if($NumberOfSubject->class_subject_order == 7)
                                
                                        <?php                                                    
                                            $subject1 = round(\App\Grade11_Second_Sem::where('enrollment_id', $sub->enrollment_id)->first()->subject_1);
                                            $subject_1 = round(\App\Grade_sheet_secondsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_1);
                                    
                                            $fg_1 = round(($subject1 + $subject_1) / 2);
                                                                                        
                                            $subject2 = round(\App\Grade11_Second_Sem::where('enrollment_id', $sub->enrollment_id)->first()->subject_2);
                                            $subject_2 = round(\App\Grade_sheet_secondsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_2);
                                    
                                            $fg_2 = round(($subject_2 + $subject2) / 2);
                                                                                        
                                            $subject3 = round(\App\Grade11_Second_Sem::where('enrollment_id', $sub->enrollment_id)->first()->subject_3);
                                            $subject_3 = round(\App\Grade_sheet_secondsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_3);

                                            $fg_3 = round(($subject_3 + $subject3) / 2);
                                                                                            
                                            $subject4 = round(\App\Grade11_Second_Sem::where('enrollment_id', $sub->enrollment_id)->first()->subject_4);
                                            $subject_4 = round(\App\Grade_sheet_secondsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_4);
                                            
                                            $fg_4 = round(($subject_4 + $subject4) / 2);
                                                                                            
                                            $subject5 = round(\App\Grade11_Second_Sem::where('enrollment_id', $sub->enrollment_id)->first()->subject_5);
                                            $subject_5 = round(\App\Grade_sheet_secondsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_5);
                                        
                                            $fg_5 = round(($subject_5 + $subject5) / 2);
                                                                                            
                                            $subject6 = round(\App\Grade11_Second_Sem::where('enrollment_id', $sub->enrollment_id)->first()->subject_6);
                                            $subject_6 = round(\App\Grade_sheet_secondsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_6);
                                            
                                            $fg_6 = round(($subject_6 + $subject6) / 2);
                                                                                            
                                            $subject7 = round(\App\Grade11_Second_Sem::where('enrollment_id', $sub->enrollment_id)->first()->subject_7);
                                            $subject_7 = round(\App\Grade_sheet_secondsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_7);
                                            
                                            $fg_7 = round(($subject_7 + $subject7) / 2);                                                                                               
                                            
                                        ?>
                                    
                                        <td>
                                                <center>                                                
                                                        <?php
                                                            $average_2sem = number_format($average = ($fg_1 + $fg_2 + $fg_3 + $fg_4 + $fg_5 + $fg_6 + $fg_7 )/7, 2);
                                                            echo round($average_2sem);
                                                        ?>
                                                </center>
                                        </td>

                                    @elseif($NumberOfSubject->class_subject_order == 8)

                                        <?php                                                    
                                            $subject1 = round(\App\Grade11_Second_Sem::where('enrollment_id', $sub->enrollment_id)->first()->subject_1);
                                            $subject_1 = round(\App\Grade_sheet_secondsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_1);
                                    
                                            $fg_1 = round(($subject1 + $subject_1) / 2);
                                                                                        
                                            $subject2 = round(\App\Grade11_Second_Sem::where('enrollment_id', $sub->enrollment_id)->first()->subject_2);
                                            $subject_2 = round(\App\Grade_sheet_secondsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_2);
                                    
                                            $fg_2 = round(($subject_2 + $subject2) / 2);
                                                                                        
                                            $subject3 = round(\App\Grade11_Second_Sem::where('enrollment_id', $sub->enrollment_id)->first()->subject_3);
                                            $subject_3 = round(\App\Grade_sheet_secondsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_3);

                                            $fg_3 = round(($subject_3 + $subject3) / 2);
                                                                                            
                                            $subject4 = round(\App\Grade11_Second_Sem::where('enrollment_id', $sub->enrollment_id)->first()->subject_4);
                                            $subject_4 = round(\App\Grade_sheet_secondsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_4);
                                            
                                            $fg_4 = round(($subject_4 + $subject4) / 2);
                                                                                            
                                            $subject5 = round(\App\Grade11_Second_Sem::where('enrollment_id', $sub->enrollment_id)->first()->subject_5);
                                            $subject_5 = round(\App\Grade_sheet_secondsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_5);
                                        
                                            $fg_5 = round(($subject_5 + $subject5) / 2);
                                                                                            
                                            $subject6 = round(\App\Grade11_Second_Sem::where('enrollment_id', $sub->enrollment_id)->first()->subject_6);
                                            $subject_6 = round(\App\Grade_sheet_secondsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_6);
                                            
                                            $fg_6 = round(($subject_6 + $subject6) / 2);
                                                                                            
                                            $subject7 = round(\App\Grade11_Second_Sem::where('enrollment_id', $sub->enrollment_id)->first()->subject_7);
                                            $subject_7 = round(\App\Grade_sheet_secondsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_7);
                                            
                                            $fg_7 = round(($subject_7 + $subject7) / 2);
                                                                                            
                                            $subject8 = round(\App\Grade11_Second_Sem::where('enrollment_id', $sub->enrollment_id)->first()->subject_8);
                                            $subject_8 = round(\App\Grade_sheet_secondsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_8);
                                            
                                            $fg_8 = round(($subject_8 + $subject8) / 2);
                                        ?>
                        
                                        <td>
                                                <center>                                                
                                                        <?php
                                                            $average_2sem = number_format($average = ($fg_1 + $fg_2 + $fg_3 + $fg_4 + $fg_5 + $fg_6 + $fg_7 + $fg_8 )/8, 2);
                                                            echo round($average_2sem);
                                                        ?>
                                                </center>
                                        </td>


                                    @elseif($NumberOfSubject->class_subject_order == 9)
                                        <?php                                                    
                                            $subject1 = round(\App\Grade11_Second_Sem::where('enrollment_id', $sub->enrollment_id)->first()->subject_1);
                                            $subject_1 = round(\App\Grade_sheet_secondsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_1);
                                        
                                            $fg_1 = round(($subject_1 + $subject1) / 2);
                                                                                            
                                            $subject2 = round(\App\Grade11_Second_Sem::where('enrollment_id', $sub->enrollment_id)->first()->subject_2);
                                            $subject_2 = round(\App\Grade_sheet_secondsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_2);
                                        
                                            $fg_2 = round(($subject_2 + $subject2) / 2);
                                                                                            
                                            $subject3 = round(\App\Grade11_Second_Sem::where('enrollment_id', $sub->enrollment_id)->first()->subject_3);
                                            $subject_3 = round(\App\Grade_sheet_secondsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_3);

                                            $fg_3 = round(($subject_3 + $subject3) / 2);
                                                                                                
                                            $subject4 = round(\App\Grade11_Second_Sem::where('enrollment_id', $sub->enrollment_id)->first()->subject_4);
                                            $subject_4 = round(\App\Grade_sheet_secondsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_4);
                                            
                                            $fg_4 = round(($subject_4 + $subject4) / 2);
                                                                                                
                                            $subject5 = round(\App\Grade11_Second_Sem::where('enrollment_id', $sub->enrollment_id)->first()->subject_5);
                                            $subject_5 = round(\App\Grade_sheet_secondsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_5);
                                            
                                            $fg_5 = round(($subject_5 + $subject5) / 2);
                                                                                                
                                            $subject6 = round(\App\Grade11_Second_Sem::where('enrollment_id', $sub->enrollment_id)->first()->subject_6);
                                            $subject_6 = round(\App\Grade_sheet_secondsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_6);
                                            
                                            $fg_6 = round(($subject_6 + $subject6) / 2);
                                                                                            
                                            $subject7 = round(\App\Grade11_Second_Sem::where('enrollment_id', $sub->enrollment_id)->first()->subject_7);
                                            $subject_7 = round(\App\Grade_sheet_secondsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_7);
                                            
                                            $fg_7 = round(($subject_7 + $subject7) / 2);
                                                                                                
                                            $subject8 = round(\App\Grade11_Second_Sem::where('enrollment_id', $sub->enrollment_id)->first()->subject_8);
                                            $subject_8 = round(\App\Grade_sheet_secondsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_8);
                                            
                                            $fg_8 = round(($subject_8 + $subject8) / 2);
                                                                                            
                                            $subject9 = round(\App\Grade11_Second_Sem::where('enrollment_id', $sub->enrollment_id)->first()->subject_9);
                                            $subject_9 = round(\App\Grade_sheet_secondsemsecond::where('enrollment_id', $sub->enrollment_id)->first()->subject_9);
                                            
                                            $fg_9 = round(($subject_9 + $subject9) / 2);
                                        ?>
                            
                                        <td style="text-align: center">
                                            <?php
                                            $average_2sem = $average = ($fg_1 + $fg_2 + $fg_3 + $fg_4 + $fg_5 + $fg_6 + $fg_7 + $fg_8 + $fg_9)/9;
                                            echo round($average_2sem);
                                            ?>
                                        </td>

                                    @endif

                                    
                                        <td style="text-align: center">
                                            <?php 
                                                // echo round($result_final = ($formattedNum + $result + $thi_result + $fou_result) / 4);
                                                echo round($result_final = (round($average_1sem) + round($average_2sem) ) /2) ;
                                            ?>
                                        </td>
                                    @if(round($result_final) >= 75 && round($result_final) <= 89)
                                        <td>
                                            <center>Passed</center>
                                        </td>
                                    @elseif(round($result_final) >= 90 && round($result_final) <= 94)
                                        <td>
                                            <center>with honors</center>
                                        </td>
                                    @elseif(round($result_final)>= 95 && round($result_final) <= 97)
                                        <td>
                                            <center>with high honors</center>
                                        </td>
                                    @elseif(round($result_final) >= 98 && round($result_final) <= 100)
                                        <td>
                                            <center>with highest honors</center>
                                        </td>
                                    @elseif(round($result_final) < 75)
                                        <td>
                                            <center>Failed</center>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                            
                        @endif 
                    </tbody>
    </table>

    <p style="text-align: right"><b>{{$ClassSubjectDetail->first_name }} {{$ClassSubjectDetail->middle_name}} {{$ClassSubjectDetail->last_name}}</b> - <i>Class Adviser</i></p>

</body>
</html>