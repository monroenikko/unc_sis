<?php

namespace App\Http\Controllers\Finance\Maintenance;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MiscelleneousFeeController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax())
        {
            $MiscFee = \App\MiscFee::where('status', 1)->where('misc_amt', 'like', '%'.$request->search.'%')->paginate(10);
            return view('control_panel_finance.maintenance.miscelleneous_fee.partials.data_list', compact('MiscFee'))->render();
        }
        
        $MiscFee = \App\MiscFee::where('status', 1)->paginate(10);
        return view('control_panel_finance.maintenance.miscelleneous_fee.index', compact('MiscFee'));
    }

    public function modal_data (Request $request) 
    {
        $MiscFee = NULL;
        if ($request->id)
        {
            $MiscFee = \App\MiscFee::where('id', $request->id)->first();
        }
        return view('control_panel_finance.maintenance.miscelleneous_fee.partials.modal_data', compact('MiscFee'))->render();
    }

    public function save_data (Request $request) 
    {
        $rules = [
            'misc_fee' => 'required'           
        ];

        $Validator = \Validator($request->all(), $rules);

        if ($Validator->fails())
        {
            return response()->json(['res_code' => 1, 'res_msg' => 'Please fill all required fields.', 'res_error_msg' => $Validator->getMessageBag()]);
        }   
        // update
        if ($request->id)
        {
            $MiscFee = \App\MiscFee::where('id', $request->id)->first();
            $MiscFee->misc_amt = $request->misc_fee;
            $MiscFee->current = $request->current_sy;
            $MiscFee->save();
            return response()->json(['res_code' => 0, 'res_msg' => 'Data successfully saved.']);
        }
        // save
        $MiscFee = new \App\MiscFee();
        $MiscFee->misc_amt = $request->misc_fee;
        $MiscFee->current = $request->current_sy;
        $MiscFee->save();
        return response()->json(['res_code' => 0, 'res_msg' => 'Data successfully saved.']);
    }

    public function toggle_current_sy (Request $request)
    {
        $MiscFee = \App\MiscFee::where('id', $request->id)->first();
        if ($MiscFee) 
        {
            if ($MiscFee->current == 0) 
            {
                $MiscFee->current = 1; 
                $MiscFee->save(); 
                return response()->json(['res_code' => 0, 'res_msg' => 'Data successfully added to current active Misc Fee.']);
            }
            else 
            {
                $MiscFee->current = 0; 
                $MiscFee->save(); 
                return response()->json(['res_code' => 0, 'res_msg' => 'Data successfully removed from current active Misc Fee.']);
            }
        }
    }

    public function deactivate_data (Request $request) 
    {
        $MiscFee = \App\MiscFee::where('id', $request->id)->first();

        if ($MiscFee)
        {
            $MiscFee->status = 0;
            $MiscFee->save();
            return response()->json(['res_code' => 0, 'res_msg' => 'Data successfully deactivated.']);
        }
        return response()->json(['res_code' => 1, 'res_msg' => 'Invalid request.']);
    }
}
