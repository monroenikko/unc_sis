<?php

namespace App\Http\Controllers\Finance\Maintenance;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TuitionFeeController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax())
        {
            $TuitionFee = \App\TuitionFee::where('status', 1)->where('tuition_amt', 'like', '%'.$request->search.'%')->paginate(10);
            return view('control_panel_finance.maintenance.tuition_fee.partials.data_list', compact('TuitionFee'))->render();
        }
        
        $TuitionFee = \App\TuitionFee::where('status', 1)->paginate(10);
        return view('control_panel_finance.maintenance.tuition_fee.index', compact('TuitionFee'));
    }

    public function modal_data (Request $request) 
    {
        $TuitionFee = NULL;
        if ($request->id)
        {
            $TuitionFee = \App\TuitionFee::where('id', $request->id)->first();
        }
        return view('control_panel_finance.maintenance.tuition_fee.partials.modal_data', compact('TuitionFee'))->render();
    }

    public function save_data (Request $request) 
    {
        $rules = [
            'tuition_fee' => 'required'
           
        ];

        $Validator = \Validator($request->all(), $rules);

        if ($Validator->fails())
        {
            return response()->json(['res_code' => 1, 'res_msg' => 'Please fill all required fields.', 'res_error_msg' => $Validator->getMessageBag()]);
        }   
        // update
        if ($request->id)
        {
            $TuitionFee = \App\TuitionFee::where('id', $request->id)->first();
            $TuitionFee->tuition_amt = $request->tuition_fee;
            $TuitionFee->current = $request->current_sy;
            $TuitionFee->save();
            return response()->json(['res_code' => 0, 'res_msg' => 'Data successfully saved.']);
        }
        // save
        $TuitionFee = new \App\TuitionFee();
        $TuitionFee->tuition_amt = $request->tuition_fee;
        $TuitionFee->current = $request->current_sy;
        $TuitionFee->save();
        return response()->json(['res_code' => 0, 'res_msg' => 'Data successfully saved.']);
    }

    public function toggle_current_sy (Request $request)
    {
        $TuitionFee = \App\TuitionFee::where('id', $request->id)->first();
        if ($TuitionFee) 
        {
            if ($TuitionFee->current == 0) 
            {
                $TuitionFee->current = 1; 
                $TuitionFee->save(); 
                return response()->json(['res_code' => 0, 'res_msg' => 'Data successfully added to current active Tuition Fee.']);
            }
            else 
            {
                $TuitionFee->current = 0; 
                $TuitionFee->save(); 
                return response()->json(['res_code' => 0, 'res_msg' => 'Data successfully removed from current active Tuition Fee.']);
            }
        }
    }

    public function deactivate_data (Request $request) 
    {
        $TuitionFee = \App\TuitionFee::where('id', $request->id)->first();

        if ($TuitionFee)
        {
            $TuitionFee->status = 0;
            $TuitionFee->save();
            return response()->json(['res_code' => 0, 'res_msg' => 'Data successfully deactivated.']);
        }
        return response()->json(['res_code' => 1, 'res_msg' => 'Invalid request.']);
    }
}
    
