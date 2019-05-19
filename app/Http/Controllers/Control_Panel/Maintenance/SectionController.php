<?php

namespace App\Http\Controllers\Control_Panel\Maintenance;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SectionController extends Controller
{
    public function index (Request $request) 
    {
        if ($request->ajax())
        {
            $SectionDetail = \App\SectionDetail::where('status', 1)
            ->where(function ($query) use ($request) {
                if ($request->grade_level) 
                {
                    $query->where('grade_level', 'like', '%'.$request->search.'%');
                }
                $query->where('section', 'like', '%'.$request->search.'%');
            })
            ->paginate(10);
            return view('control_panel.section_details.partials.data_list', compact('SectionDetail'))->render();
        }
        $SectionDetail = \App\SectionDetail::where('status', 1)->paginate(10);
        return view('control_panel.section_details.index', compact('SectionDetail'));
    }
    public function modal_data (Request $request) 
    {
        $SectionDetail = NULL;
        if ($request->id)
        {
            $SectionDetail = \App\SectionDetail::where('id', $request->id)->first();
        }
        $GradeLevel = \App\GradeLevel::where('status', 1)->get();
        return view('control_panel.section_details.partials.modal_data', compact('SectionDetail', 'GradeLevel'))->render();
    }

    public function save_data (Request $request) 
    {
        $rules = [
            'section' => 'required',
            'grade_level' => 'required',
        ];
        
        
        $Validator = \Validator($request->all(), $rules);

        if ($Validator->fails())
        {
            return response()->json(['res_code' => 1, 'res_msg' => 'Please fill all required fields.', 'res_error_msg' => $Validator->getMessageBag()]);
        }

        if ($request->id)
        {
            $SectionDetail = \App\SectionDetail::where('id', $request->id)->first();
            $SectionDetail->section = $request->section;
            $SectionDetail->grade_level = $request->grade_level;
            $SectionDetail->save();
            return response()->json(['res_code' => 0, 'res_msg' => 'Data successfully saved.']);
        }

        $SectionDetail = new \App\SectionDetail();
        $SectionDetail->grade_level = $request->grade_level;
        $SectionDetail->section = $request->section;
        $SectionDetail->save();
        
        return response()->json(['res_code' => 0, 'res_msg' => 'Data successfully saved.']);
    }
    public function deactivate_data (Request $request) 
    {
        $SectionDetail = \App\SectionDetail::where('id', $request->id)->first();

        if ($SectionDetail)
        {
            $SectionDetail->status = 0;
            $SectionDetail->current = 0;
            $SectionDetail->save();

            $User = \App\User::where('id', $SectionDetail->user_id)->first();
            if ($User)
            {
                $User->status = 0;
                $User->save();
            }
            return response()->json(['res_code' => 0, 'res_msg' => 'Data successfully deactivated.']);
        }
        return response()->json(['res_code' => 1, 'res_msg' => 'Invalid request.']);
    }
}
