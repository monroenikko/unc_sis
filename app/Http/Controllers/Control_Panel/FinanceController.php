<?php

namespace App\Http\Controllers\Control_Panel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FinanceController extends Controller
{
    public function index (Request $request) 
    {
        if ($request->ajax())
        {
            $FinanceInformation = \App\FinanceInformation::with(['user'])->where('status', 1)
            ->where(function ($query) use ($request) {
                $query->where('first_name', 'like', '%'.$request->search.'%');
                $query->orWhere('middle_name', 'like', '%'.$request->search.'%');
                $query->orWhere('last_name', 'like', '%'.$request->search.'%');
            })
            // ->orWhere('first_name', 'like', '%'.$request->search.'%')
            ->paginate(10);
            return view('control_panel.finance_information.partials.data_list', compact('FinanceInformation'))->render();
        }
        $FinanceInformation = \App\FinanceInformation::with(['user'])->where('status', 1)->paginate(10);
        return view('control_panel.finance_information.index', compact('FinanceInformation'));
    }

    public function modal_data (Request $request) 
    {
        $FinanceInformation = NULL;
        if ($request->id)
        {
            $FinanceInformation = \App\FinanceInformation::with(['user'])->where('id', $request->id)->first();
        }
        return view('control_panel.finance_information.partials.modal_data', compact('FinanceInformation'))->render();
    }

    public function save_data (Request $request) 
    {
        $rules = [
            'username' => 'required',
            'first_name' => 'required',
            'middle_name' => 'required',
            'last_name' => 'required',
        ];        
        
        $Validator = \Validator($request->all(), $rules);

        if ($Validator->fails())
        {
            return response()->json(['res_code' => 1, 'res_msg' => 'Please fill all required fields.', 'res_error_msg' => $Validator->getMessageBag()]);
        }
        
        if ($request->id)
        {
            $FinanceInformation = \App\FinanceInformation::where('id', $request->id)->first();
            $User = \App\User::where('username', $request->username)->where('id', '!=', $FinanceInformation->user_id)->first();
            if ($User) 
            {
                return response()->json(['res_code' => 1,'res_msg' => 'Username already used.']);
            }
            $FinanceInformation->first_name = $request->first_name;
            $FinanceInformation->middle_name = $request->middle_name;
            $FinanceInformation->last_name = $request->last_name;
            $FinanceInformation->save();
            return response()->json(['res_code' => 0, 'res_msg' => 'Data successfully saved.']);
        }

        $User = new \App\User();
        $User->username = $request->username;
        $User->password = bcrypt($request->first_name . '.' . $request->last_name);
        $User->role     = 6;
        $User->save();

        $FinanceInformation = new \App\FinanceInformation();
        $FinanceInformation->first_name = $request->first_name;
        $FinanceInformation->middle_name = $request->middle_name;
        $FinanceInformation->last_name = $request->last_name;
        $FinanceInformation->user_id = $User->id;
        $FinanceInformation->save();
        
        return response()->json(['res_code' => 0, 'res_msg' => 'Data successfully saved.']);
    }

    public function deactivate_data (Request $request) 
    {
        $FinanceInformation = \App\FinanceInformation::where('id', $request->id)->first();

        if ($FinanceInformation)
        {
            $FinanceInformation->status = 0;
            $FinanceInformation->save();

            $User = \App\User::where('id', $FinanceInformation->user_id)->first();
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
