<?php

namespace App\Http\Controllers\Control_Panel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserProfileController extends Controller
{
    public function view_my_profile (Request $request)
    {
        $User = \Auth::user();
        $Profile = \App\AdminInformation::where('user_id', $User->id)->first();
        return view('control_panel.user_profile.index', compact('User', 'Profile'));
    }
    public function fetch_profile (Request $request)
    {
        $User = \Auth::user();
        $Profile = \App\AdminInformation::where('user_id', $User->id)->first();
        return response()->json(['res_code' => 0, 'res_msg' => '', 'Profile' => $Profile]);
    }
    public function update_profile (Request $request) 
    {
        $rules = [
            'first_name' => 'required',
            'middle_name' => 'required',
            'last_name' => 'required',
        ];
        
        $validator = \Validator::make($request->all(), $rules);

        if ($validator->fails())
        {   
            return response()->json(['res_code' => 1, 'res_msg' => 'Please fill all required fields.', 'res_error_msg' => $validator->getMessageBag()]);
        }
        $User = \Auth::user();
        $Profile = \App\AdminInformation::where('user_id', $User->id)->first();

        $Profile->first_name = $request->first_name;
        $Profile->middle_name = $request->middle_name;
        $Profile->last_name = $request->last_name;
        $Profile->contact_number = $request->contact_number;
        $Profile->email = $request->email;
        $Profile->address = $request->address;
        // $Profile->birthday = date('Y-m-d', strtotime($request->birthday));
                
        if ($Profile->save())
        {
            return response()->json(['res_code' => 0, 'res_msg' => 'User profile successfully updated.']);
        }
        else 
        {
            return response()->json(['res_code' => 1, 'res_msg' => 'Unable to update profile.']);
        }
    }
    public function change_my_photo (Request $request)
    {
        $name = time().'.'.$request->user_photo->getClientOriginalExtension();
        $destinationPath = public_path('/img/account/photo/');
        $request->user_photo->move($destinationPath, $name);



        $User = \Auth::user();
        $Profile = \App\AdminInformation::where('user_id', $User->id)->first();

        if ($Profile->photo) 
        {
            $delete_photo = public_path('/img/account/photo/'. $Profile->photo);
            if (\File::exists($delete_photo)) 
            {
                \File::delete($delete_photo);
            }
        }

        $Profile->photo = $name;

        if ($Profile->save())
        {
            return response()->json(['res_code' => 0, 'res_msg' => 'User photo successfully updated.']);
        }
        else 
        {
            return response()->json(['res_code' => 1, 'res_msg' => 'Error in saving photo']);
        }

        return json_encode($request->all());
    }

    public function change_my_password (Request $request)
    {
        $rules = [
            'old_password' => 'required',
            'password' => 'required|confirmed',
            'password_confirmation' => 'required'
        ];

        $validator = \Validator::make($request->all(), $rules);

        if ($validator->fails())
        {   
            return response()->json(['res_code' => 1, 'res_msg' => 'Please fill all required fields.', 'res_error_msg' => $validator->getMessageBag()]);
        }
        // return json_encode(['aa' => \Auth::user()->password]);
        if (\Hash::check($request->old_password, \Auth::user()->password))
        {
            \Auth::user()->password = bcrypt($request->password);
            \Auth::user()->save();
            
            
            return response()->json(['res_code' => 0, 'res_msg' => 'Password successfully changed.']);
        }
        else 
        {
            return response()->json(['res_code' => 1, 'res_msg' => 'Incorrect old password.']);
        }
    }
}
