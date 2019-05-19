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
                                <th style="width: 11%">First Quarter</th>
                                <th style="width: 11%">Second Quarter</th>
                                {{-- <th>Third Grading</th>
                                <th>Fourth Grading</th> --}}
                            {{--  @endif  --}}
                            @foreach ($GradeSheetData1 as $key => $data)
                                
                                    @if (!$data->fir_g == 0 && !$data->sec_g  == 0 && !$data->thi_g  == 0 && !$data->fou_g == 0)
                                        <th>Weighted Average</th>
                                        @break
                                    @else
                                        <th>Final Average</th>
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
                        @if ($GradeSheetData)
                            @foreach ($GradeSheetData as $key => $data)
                                <tr>
                                    <td>{{ $data->subject }}</td>
                                    @if ($data->grade_status === -1)
                                        <td colspan="6" class="text-center text-red">Grade not yet finalized</td>
                                    @else 
                                    
                                        {{--  @if ($grade_level >= 11) 
                                            <td>{{ round($data->fir_g) }}</td>
                                            <td>{{ round($data->sec_g) }}</td>
                                            @if (!$data->fir_g || !$data->sec_g)
                                                <td></td>
                                            @else
                                                <td>{{ round($data->final_g) }}</td>
                                            @endif
                                            <td style="color:{{ round($data->final_g) >= 75 ? 'green' : 'red' }};"><strong>{{ round($data->final_g) >= 75 ? 'Passed' : 'Failed' }}</strong></td>
                                        @else  --}}
                                            <td><center>{{ $data->fir_g ? round($data->fir_g) : '' }}</center></td>
                                            <td><center>{{ $data->sec_g ? round($data->sec_g) : '' }}</center></td>
                                            {{-- <td>{{ $data->thi_g ? round($data->thi_g) : '' }}</td>
                                            <td>{{ $data->fou_g ? round($data->fou_g) : '' }}</td> --}}
                                            <td><center>{{ $data->fou_g ? round($data->final_g) : '' }}</center></td>
                                            @if (!$data->fir_g == 0 && !$data->sec_g  == 0 && !$data->thi_g  == 0 && !$data->fou_g == 0)
                                                    <td></td>
                                                @else
                                                    <td style="color:{{ round($data->final_g) >= 75 ? 'green' : 'red' }};"><center><strong>{{ round($data->final_g) >= 75 ? 'Passed' : 'Failed' }}</strong></center></td>
                                                @endif
                                        {{--  @endif  --}}
                                    @endif
                                    {{--  <td>{{ $data->class_time_from . ' -  ' . $data->class_time_to }}</td>
                                    <td>{{ $data->class_days }}</td>
                                    <td>{{ 'Room' . $data->room_code }}</td>  --}}
                                    {{-- <td>{{ $data->grade_level . ' - ' . $data->section }}</td> --}}
                                    <td>{{ $data->faculty_name }}</td>
                                </tr>
                            @endforeach
                        @else
                            
                        @endif
                    </tbody>
                </table>
            </div>
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
                                {{--  @if ($grade_level >= 11) 
                                    <th>First Semister</th>
                                    <th>Second Semister</th>
                                @elseif($grade_level <= 10)  --}}
                                    {{-- <th>First Quarter</th>
                                    <th>Second Quarter</th> --}}
                                    <th style="width: 11%">First Quarter</th>
                                    <th style="width: 11%">Second Quarter</th>
                                {{--  @endif  --}}
                                @foreach ($GradeSheetData1 as $key => $data)
                                
                                    @if (!$data->fir_g == 0 && !$data->sec_g  == 0 && !$data->thi_g  == 0 && !$data->fou_g == 0)
                                        <th>Weighted Average</th>
                                        @break
                                    @else
                                        <th>Final Average</th>
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
                            @if ($GradeSheetData1)
                                @foreach ($GradeSheetData1 as $key => $data)
                                    <tr>
                                        <td>{{ $data->subject }}</td>
                                        @if ($data->grade_status === -1)
                                            <td colspan="6" class="text-center text-red">Grade not yet finalized</td>
                                        @else 
                                        
                                            {{--  @if ($grade_level >= 11) 
                                                <td>{{ round($data->fir_g) }}</td>
                                                <td>{{ round($data->sec_g) }}</td>
                                                @if (!$data->fir_g || !$data->sec_g)
                                                    <td></td>
                                                @else
                                                    <td>{{ round($data->final_g) }}</td>
                                                @endif
                                                <td style="color:{{ round($data->final_g) >= 75 ? 'green' : 'red' }};"><strong>{{ round($data->final_g) >= 75 ? 'Passed' : 'Failed' }}</strong></td>
                                            @else  --}}
                                                {{-- <td>{{ $data->fir_g ? round($data->fir_g) : '' }}</td>
                                                <td>{{ $data->sec_g ? round($data->sec_g) : '' }}</td> --}}
                                                <td><center>{{ $data->thi_g ? round($data->thi_g) : '' }}</center></td>
                                                <td><center>{{ $data->fou_g ? round($data->fou_g) : '' }}</center></td>
                                                <td><center>{{ $data->fou_g ? round($data->final_g) : '' }}</center></td>
                                                @if (!$data->fir_g == 0 && !$data->sec_g  == 0 && !$data->thi_g  == 0 && !$data->fou_g == 0)
                                                    <td></td>
                                                @else
                                                    <td style="color:{{ round($data->final_g) >= 75 ? 'green' : 'red' }};"><center><strong>{{ round($data->final_g) >= 75 ? 'Passed' : 'Failed' }}</strong></center></td>
                                                @endif
                                            {{--  @endif  --}}
                                        @endif
                                        {{--  <td>{{ $data->class_time_from . ' -  ' . $data->class_time_to }}</td>
                                        <td>{{ $data->class_days }}</td>
                                        <td>{{ 'Room' . $data->room_code }}</td>  --}}
                                        {{-- <td>{{ $data->grade_level . ' - ' . $data->section }}</td> --}}
                                        <td>{{ $data->faculty_name }}</td>
                                    </tr>
                                @endforeach
                            @else
                                
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        
    </div>

    @else
        <div class="box">
            <div class="overlay hidden" id="js-loader-overlay"><i class="fa fa-refresh fa-spin"></i></div>
            <div class="box-body">
                   <h4><span class="logo-mini"><img src="{{ asset('/img/sja-logo.png') }}" style="height: 60px;"></span> <b> Grade-level/Section : <i style="color:red">{{  $Enrollment2->grade_level .' - '. $Enrollment2->section }}</i></b></h4>
                   <hr/>
                <!--<button class="btn btn-flat pull-right btn-primary" id="js-btn_print"><i class="fa fa-file-pdf"></i> Print</button>-->
                <div class="js-data-container">
                    <table class="table no-margin table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Subject</th>
                                {{--  @if ($grade_level >= 11) 
                                    <th>First Semister</th>
                                    <th>Second Semister</th>
                                @elseif($grade_level <= 10)  --}}
                                <th>First Grading</th>
                                <th>Second Grading</th>
                                <th>Third Grading</th>
                                <th>Fourth Grading</th>
                                {{--  @endif  --}}
                                @foreach ($GradeSheetData as $key => $data)
                                
                                    @if (!$data->fir_g == 0 && !$data->sec_g  == 0 && !$data->thi_g  == 0 && !$data->fou_g == 0)
                                        <th>Weighted Average</th>
                                        @break
                                    @else
                                        <th>Final Average</th>
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
                            @if ($GradeSheetData)
                                @foreach ($GradeSheetData as $key => $data)
                                    <tr>
                                        <td>{{ $data->subject }}</td>
                                        @if ($data->grade_status === -1)
                                            <td colspan="6" class="text-center text-red">Grade not yet finalized</td>
                                        @else 
                                        
                                            {{--  @if ($grade_level >= 11) 
                                                <td>{{ round($data->fir_g) }}</td>
                                                <td>{{ round($data->sec_g) }}</td>
                                                @if (!$data->fir_g || !$data->sec_g)
                                                    <td></td>
                                                @else
                                                    <td>{{ round($data->final_g) }}</td>
                                                @endif
                                                <td style="color:{{ round($data->final_g) >= 75 ? 'green' : 'red' }};"><strong>{{ round($data->final_g) >= 75 ? 'Passed' : 'Failed' }}</strong></td>
                                            @else  --}}
                                                <td><center>{{ $data->fir_g ? round($data->fir_g) : '' }}</center></td>
                                                <td><center>{{ $data->sec_g ? round($data->sec_g) : '' }}</center></td>
                                                <td><center>{{ $data->thi_g ? round($data->thi_g) : '' }}</center></td>
                                                <td><center>{{ $data->fou_g ? round($data->fou_g) : '' }}</center></td>
                                                <td><center>{{ $data->fou_g ? round($data->final_g) : '' }}</center></td>
                                                @if (!$data->fir_g == 0 && !$data->sec_g  == 0 && !$data->thi_g  == 0 && !$data->fou_g == 0)
                                                    <td></td>
                                                @else
                                                    <td style="color:{{ round($data->final_g) >= 75 ? 'green' : 'red' }};"><center><strong>{{ round($data->final_g) >= 75 ? 'Passed' : 'Failed' }}</strong></center></td>
                                                @endif
                                            {{--  @endif  --}}
                                        @endif
                                        {{--  <td>{{ $data->class_time_from . ' -  ' . $data->class_time_to }}</td>
                                        <td>{{ $data->class_days }}</td>
                                        <td>{{ 'Room' . $data->room_code }}</td>  --}}
                                        {{-- <td>{{ $data->grade_level . ' - ' . $data->section }}</td> --}}
                                        <td>{{ $data->faculty_name }}</td>
                                    </tr>
                                @endforeach
                            @else
                                
                            @endif
                        </tbody>
                    </table>
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