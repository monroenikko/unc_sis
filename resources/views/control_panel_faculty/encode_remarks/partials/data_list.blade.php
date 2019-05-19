@if($ClassSubjectDetail)                        
    <h4><b>Year and Section: <i style="color: red">Grade:{{ $ClassSubjectDetail->grade_level }} - {{ $ClassSubjectDetail->section }}</i></b></h4>     
@else
    <h4><b>Year and Section: <i style="color: red">Not Available</i></b></h4>     
@endif

@if($ClassSubjectDetail->grade_level < 11 )
    
    <table class="table no-margin table-striped table-bordered">
        <thead>
            <tr>
                <th style="width: 30px">#</th>
                <th style="width: ">Student Name<br/></th>   
                <th style="text-align:center">Eligible to transfer and admission to</th>
                <th style="text-align:center">Lacking units in<br/></th>
                <th style="text-align:center; width: 170px">Date<br/></th>            
                <th style="text-align:center" >Action<br/></th>                                 
                
            </tr>
           
        </thead>
        <tbody>      
                                       
            <tr>
                <td colspan="16">
                    <b>Male</b> 
                </td>
            </tr>
    
            
                {{-- <form action="{{ route('faculty.save-class-demographic') }}" method="POST" novalidate="novalidate"> --}}
                
               
                    @foreach ($EnrollmentMale as $key => $data) 
                    <form id="js_lacking_units_jr">
                        {{ csrf_field() }}
                    <tr>
                        <td>{{ $key + 1 }}.</td>
                        <td>{{ $data->student_name }}</td>
                        {{-- <td><a style="cursor: pointer;" rel="{{ $data->student_information_id }}" class="printGrade" data-id="{{ encrypt($data->id) }}">Print Grade</a></td> --}}
                        <td>
                            {{-- <input type="hidden" id="stud_id" name="stud_id" value="" /> --}}
                                {{-- {{  $data->c_address }} --}}
                                
                                
                                    @if(round($StudentEnrolledSubject1->fir_g) != 0 && round($StudentEnrolledSubject1->sec_g) != 0 && round($StudentEnrolledSubject1->thi_g) != 0 && round($StudentEnrolledSubject1->fou_g) != 0)
                                        @if(round($totalsum) > 74) 
                                            @if($ClassSubjectDetail->grade_level < 11)
                                                {{-- <strong><u>&nbsp;&nbsp;College&nbsp;&nbsp;&nbsp;&nbsp;</u></strong> --}}
                                                <input type="text" class="form-control" name="grade" value="Grade {{ $ClassSubjectDetail->grade_level + 1 }}" placeholder="Grade">
                                            
                                            @endif
                                        @elseif(round($totalsum) < 75)                                        
                                            <input type="text" class="form-control" name="grade" value="Grade {{ $ClassSubjectDetail->grade_level - 1 }}" placeholder="Grade">
                                        @endif
                                    @else
                                        <input type="text" class="form-control" name="grade" value="" placeholder="Grade">
                                    @endif
                                
                                
                                {{-- <div class="help-block text-red text-center" id="js-grade">
                                </div>    --}}
                        </td>
                        <td>
                            <input type="text" class="form-control" name="jlacking_units" value="{{ $data->j_lacking_unit }}" placeholder="none">    
                            {{-- <input type="text" class="form-control" name="jlacking_units" id="jlacking_units" placeholder="Lacking units"/> --}}
                                                                                
                        </td>
                        <td>
                                <div class="input-group date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        
                                            <input type="text" name="date" class="tbdatepicker form-control pull-right" id="tbdatepicker" placeholder="11/11/2000" value="{{ $DateRemarks->j_date }}">
                                       
                                    </div>
                                {{-- <div class="help-block text-red text-center" id="js-date">
                                </div> --}}
                                
                        </td>
                        
                        <td>
                            <center>
    
                                    {{-- <div class="input-group-btn pull-left text-left">
                                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="true">Action
                                                <span class="fa fa-caret-down"></span></button>
                                            <ul class="dropdown-menu">
                                                
                                                                                       
                                                <li><a style="cursor: pointer;">Save</a></li>
                                                <li><a style="cursor: pointer;" rel="{{ $data->student_information_id }} " id="js-btn_print" class="printGrade" data-id="{{ encrypt($data->student_information_id) }} ">Print Grade</a></li>                                        
                                            </ul>
                                        </div> --}}
                                
                                <input name="print_sy" id="print_sy" value="{{ $ClassSubjectDetail->id }}" type="hidden" /> 
                                <input name="stud_id" id="stud_id" value="{{ $data->student_information_id }}" type="hidden" />
                                <input name="s_year" id="s_year" value="{{ $SchoolYear->id }}" type="hidden" />
                                <input name="level" id="level" value="{{ $ClassSubjectDetail->grade_level }}" type="hidden" />
                                

                                <button type="submit" class="btn btn-sm btn-primary save">save</button>
                                {{-- <button class="btn btn-sm btn-danger" id="js-btn_print" data-id=""><i class="fa fa-file-pdf"></i> Print</button> --}}
                                <button class="btn btn-sm btn-danger printGradebtn" rel="{{ $data->student_information_id }}" id="js-btn_print" data-id="{{ encrypt($data->student_information_id) }}">Print</button>
                            </center>
                        </td>
                        
                    </tr>
                </form>
                    @endforeach
                
                
    </table>
    
@elseif($ClassSubjectDetail->grade_level > 10)

    <table class="table no-margin table-striped table-bordered">
        <thead>
            <tr>
                <th style="width: 30px">#</th>
                <th style="width: ">Student Name</th>   
                <th style="text-align:center">Eligible to transfer and admission to</th>
                <th style="text-align:center">Lacking units in</th>
                <th style="text-align:center">Date</th>            
                <th style="text-align:center" >Action</th>                                 
                
            </tr>
           
        </thead>
        <tbody>      
                                       
            <tr>
                <td colspan="16">
                    <b>Male</b> 
                </td>
            </tr>
    
            
                {{-- <form action="{{ route('faculty.save-class-demographic') }}" method="POST" novalidate="novalidate"> --}}
                
                
                    @foreach ($EnrollmentMale as $key => $data) 
                    <form id="js_lacking_units_sr">
                    {{ csrf_field() }}
                    <tr>
                        <td>{{ $key + 1 }}.</td>
                        <td>{{ $data->student_name }}</td>
                        
                        {{-- <td><a style="cursor: pointer;" rel="{{ $data->student_information_id }}" class="printGrade" data-id="{{ encrypt($data->id) }}">Print Grade</a></td> --}}
                        <td>
                            {{-- <input type="hidden" id="stud_id" name="stud_id" value="" /> --}}
                                {{-- {{  $data->c_address }} --}}
                                
                                @if($ClassSubjectDetail->grade_level == 11)  
                                  
                                    @if(round($StudentEnrolledSubject1->fir_g) != 0 && round($StudentEnrolledSubject1->sec_g) != 0)
                                        @if(round($totalsum) > 74) 
                                            @if($ClassDetail->section_grade_level == 12)
                                                {{-- <strong><u>&nbsp;&nbsp;College&nbsp;&nbsp;&nbsp;&nbsp;</u></strong> --}}
                                                <input type="text" class="form-control" name="grade" value="College" placeholder="Grade">
                                            @else
                                                <input type="text" class="form-control" name="grade" value="Grade {{ $ClassDetail->section_grade_level}} Second Semester" placeholder="Grade">
                                                {{-- <strong><u>&nbsp;&nbsp;Grade {{ $ClassDetail->section_grade_level + 1 }}&nbsp;&nbsp;&nbsp;&nbsp;</u></strong> --}}
                                            @endif
                                        @elseif(round($totalsum) < 75)                                        
                                            <input type="text" class="form-control" name="grade" value="Grade {{ $ClassDetail->section_grade_level - 1 }}" placeholder="Grade">
                                        @endif
                                    @else
                                        <input type="text" class="form-control" name="grade" placeholder="Grade">
                                    @endif
    
                                @elseif($ClassSubjectDetail->grade_level == 12)
    
                                    @if(round($StudentEnrolledSubject1->thi_g) != 0 && round($StudentEnrolledSubject1->fou_g) != 0)
                                        @if(round($totalsum) > 74) 
                                            @if($ClassDetail->section_grade_level == 12)
                                                {{-- <strong><u>&nbsp;&nbsp;College&nbsp;&nbsp;&nbsp;&nbsp;</u></strong> --}}
                                                <input type="text" class="form-control" name="grade" value="College" placeholder="Grade">
                                            @else
                                                <input type="text" class="form-control" name="grade" value="Grade {{ $ClassDetail->section_grade_level }}" placeholder="Grade">
                                                {{-- <strong><u>&nbsp;&nbsp;Grade {{ $ClassDetail->section_grade_level + 1 }}&nbsp;&nbsp;&nbsp;&nbsp;</u></strong> --}}
                                            @endif
                                        @elseif(round($totalsum) < 75)                                        
                                            <input type="text" class="form-control" name="grade" value="Grade {{ $ClassDetail->section_grade_level - 1 }}" placeholder="Grade">
                                        @endif
                                    @else
                                        <input type="text" class="form-control" name="grade" value="" placeholder="Grade">
                                    @endif
    
                                @endif
                                
                                <div class="help-block text-red text-center" id="js-grade">
                                </div>   
                        </td>
                        <td>
                            @if($ClassSubjectDetail->grade_level == 11)
                                <input type="text" class="form-control" name="s1_lacking_units" placeholder="Lacking units" value="{{ $data->s1_lacking_unit }}"
                                 
                            @elseif($ClassSubjectDetail->grade_level == 12)        
                                <input type="text" class="form-control" name="s2_lacking_units" placeholder="Lacking units" value="{{ $data->s2_lacking_unit }}">
                                
                            @endif
                        </td>
                        <td>
                                <div class="input-group date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                      @if($ClassSubjectDetail->grade_level == 11)
                                            <input type="text" name="date" class="tbdatepicker form-control pull-right" id="tbdatepicker" placeholder="11/11/2000" value="{{ $DateRemarks->s_date1 }}">
                                       @elseif($ClassSubjectDetail->grade_level == 12)
                                            <input type="text" name="date" class="tbdatepicker form-control pull-right" id="tbdatepicker" placeholder="11/11/2000" value="{{ $DateRemarks->s_date2 }}">
                                        @endif
                                    </div>
                                <div class="help-block text-red text-center" id="js-date">
                                </div>
                                
                        </td>
                        
                        <td>
                            <center>
    
                                    {{-- <div class="input-group-btn pull-left text-left">
                                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="true">Action
                                                <span class="fa fa-caret-down"></span></button>
                                            <ul class="dropdown-menu">
                                                
                                                                                       
                                                <li><a style="cursor: pointer;">Save</a></li>
                                                <li><a style="cursor: pointer;" rel="{{ $data->student_information_id }} " id="js-btn_print" class="printGrade" data-id="{{ encrypt($data->student_information_id) }} ">Print Grade</a></li>                                        
                                            </ul>
                                        </div> --}}
                                
                                <input name="print_sy" id="print_sy" value="{{ $ClassSubjectDetail->id }}" type="hidden" />  
                                <input name="stud_id" id="stud_id" value="{{ $data->student_information_id }}" type="hidden" />
                                <input name="s_year" id="s_year" value="{{ $SchoolYear->id }}" type="hidden" /> 
                                <input name="sem" id="sem" value="{{ $ClassSubjectDetail->sem }}" type="hidden" /> 
                                <input name="level" id="level" value="{{ $ClassSubjectDetail->grade_level }}" type="hidden" />
                                
                                <button type="submit" class="btn btn-sm btn-primary save">save</button>
                                {{-- <button class="btn btn-sm btn-danger" id="js-btn_print" data-id=""><i class="fa fa-file-pdf"></i> Print</button> --}}
                                <button class="btn btn-sm btn-danger printGradebtn" rel="{{ $data->student_information_id }}" id="js-btn_print" data-id="{{ encrypt($data->student_information_id) }}">Print</button>
                            </center>
                        </td>
                        
                    </tr>
                    </form>
                    @endforeach
                
                
    </table>

@endif


