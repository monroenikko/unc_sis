<?php

namespace App\Http\Controllers\Control_Panel\Maintenance;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StrandController extends Controller
{
    public function index(Request $request)
    {
        
        if ($request->ajax())
        {
            $Strand = \App\Strand::
            where('status', 1)
            ->where('strand', 'like', '%'.$request->search.'%')            
            ->paginate(10);
            return view('control_panel.strand.partials.data_list', compact('Strand'))->render();
            // $SchoolYear_id = \App\SchoolYear::where('status', 1)->where('school_year', 'like', '%'.$request->search.'%')->paginate(10);

            // $SchoolYear = \App\DateRemark::where('status', 1)->where('school_year_id', $SchoolYear_id[0]->id)->paginate(10);
            
        }  
        $Strand = \App\Strand::
            where('status', 1)
            ->paginate(10);
        return view('control_panel.strand.index', compact('Strand'));
    }

    public function modal_data (Request $request) 
    {
        $Strand = NULL;
        
        if ($request->id)
        {
            $Strand = \App\Strand::where('id', $request->id)->first();         
        }

        // $getSchoolYear = \App\SchoolYear::get();        

        return view('control_panel.strand.partials.modal_data', compact('Strand'))->render();
    }

    public function save_data (Request $request) 
    {
        $rules = [
            'strand_name' => 'required',
            'abb_name' => 'required'
            
        ];

        $Validator = \Validator($request->all(), $rules);

        if ($Validator->fails())
        {
            return response()->json(['res_code' => 1, 'res_msg' => 'Please fill all required fields.', 'res_error_msg' => $Validator->getMessageBag()]);
        }

        if ($request->id)
        {
            $Strand = \App\Strand::where('id', $request->id)->first();
            $Strand->strand = $request->strand_name;
            $Strand->abbreviation = $request->abb_name;            
            $Strand->save();
            return response()->json(['res_code' => 0, 'res_msg' => 'Data successfully saved.']);
        }

        $Strand = new \App\Strand();
        
        $Strand->strand = $request->strand_name;
        $Strand->abbreviation = $request->abb_name;            
        $Strand->save();
        return response()->json(['res_code' => 0, 'res_msg' => 'Data successfully saved.']);
    }
}
