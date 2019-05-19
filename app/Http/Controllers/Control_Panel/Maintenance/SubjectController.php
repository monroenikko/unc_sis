<?php

namespace App\Http\Controllers\Control_Panel\Maintenance;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SubjectController extends Controller
{
    public function index (Request $request) 
    {
        if ($request->ajax())
        {
            $SubjectDetail = \App\SubjectDetail::where('status', 1)
            ->where(function ($query) use ($request) {
                $query->where('subject_code', 'like', '%'.$request->search.'%');
                $query->orWhere('subject', 'like', '%'.$request->search.'%');
            })
            ->paginate(10);
            return view('control_panel.subject.partials.data_list', compact('SubjectDetail'))->render();
        }
        $SubjectDetail = \App\SubjectDetail::where('status', 1)->paginate(10);
        return view('control_panel.subject.index', compact('SubjectDetail'));
    }
    public function modal_data (Request $request) 
    {
        $SubjectDetail = NULL;
        if ($request->id)
        {
            $SubjectDetail = \App\SubjectDetail::where('id', $request->id)->first();
        }
        return view('control_panel.subject.partials.modal_data', compact('SubjectDetail'))->render();
    }

    public function save_data (Request $request) 
    {
        $rules = [
            'subject_code' => 'required',
            'subject' => 'required',
            'units' => 'required'
        ];

        $Validator = \Validator($request->all(), $rules);

        if ($Validator->fails())
        {
            return response()->json(['res_code' => 1, 'res_msg' => 'Please fill all required fields.', 'res_error_msg' => $Validator->getMessageBag()]);
        }

        if ($request->id)
        {
            $SubjectDetail = \App\SubjectDetail::where('id', $request->id)->first();
            $SubjectDetail->subject_code = $request->subject_code;
            $SubjectDetail->subject = $request->subject;
            $SubjectDetail->subject_abbr = $request->subject_abbr;
            $SubjectDetail->units = $request->units;
            $SubjectDetail->save();
            return response()->json(['res_code' => 0, 'res_msg' => 'Data successfully saved.']);
        }

        $SubjectDetail = new \App\SubjectDetail();
        $SubjectDetail->subject_code = $request->subject_code;
        $SubjectDetail->subject = $request->subject;
        $SubjectDetail->subject_abbr = $request->subject_abbr;
        $SubjectDetail->units = $request->units;
        $SubjectDetail->save();
        return response()->json(['res_code' => 0, 'res_msg' => 'Data successfully saved.']);
    }
    public function deactivate_data (Request $request) 
    {
        $SubjectDetail = \App\SubjectDetail::where('id', $request->id)->first();

        if ($SubjectDetail)
        {
            $SubjectDetail->status = 0;
            $SubjectDetail->save();
            return response()->json(['res_code' => 0, 'res_msg' => 'Data successfully deactivated.']);
        }
        return response()->json(['res_code' => 1, 'res_msg' => 'Invalid request.']);
    }
}
