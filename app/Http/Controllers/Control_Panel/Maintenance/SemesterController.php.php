<?php

namespace App\Http\Controllers\Control_Panel\Maintenance;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SemesterController extends Controller
{
    //
    public function index (Request $request) 
    {
        

        if ($request->ajax())
        {
            $Semester = \App\Semester::get();
            // return redirect()->back();
        //    retirm view('control_panel.semester.partials.data_list', compact('Semester'))->render();
            return view('control_panel.semester.index', compact('Semester'))->render();

            // return response()->json(compact('html'));
            // return redirect('control_panel\semester\partials\data_list')->compact('Semester')->render();
            // return redirect('Control_Panel\Maintenance\SemesterController@index')
        }
        // $Semester = \App\Semester::where('status', 1)->paginate(10);
        $Semester = \App\Semester::get();
        return view('control_panel.semester.index', compact('Semester'));
    }

    public function toggle_current_sy (Request $request)
    {
        $Semester = \App\Semester::where('id', $request->id)->first();
        if ($Semester) 
        {
            if($request->id == 1)
            {
                if ($Semester->current == 0) 
                {
                    \App\Semester::where(['id'=> 2])->update(['current'=>0]);
    
                    $Semester->current = 1; 
                    $Semester->save(); 
                    return response()->json(['res_code' => 0, 'res_msg' => 'First Semester is now currently activated.']);
                }
                else 
                {
                    \App\Semester::where(['id'=> 2])->update(['current'=>1]);
    
                    $Semester->current = 0; 
                    $Semester->save(); 
                    return response()->json(['res_code' => 0, 'res_msg' => 'First Semester is now currently de-activated.']);
                }
            }
            else 
            {
                if ($Semester->current == 0) 
                {
                    \App\Semester::where(['id'=> 1])->update(['current'=>0]);
    
                    $Semester->current = 1; 
                    $Semester->save(); 
                    return response()->json(['res_code' => 0, 'res_msg' => 'Second Semester is now currently activated.']);
                }
                else 
                {
                    \App\Semester::where(['id'=> 1])->update(['current'=>1]);
    
                    $Semester->current = 0; 
                    $Semester->save(); 
                    return response()->json(['res_code' => 0, 'res_msg' => 'Second Semester is now currently de-activated.']);
                }
            }
            
        }
    }

    public function set_sem (Request $request)
    {
        $Semester = \App\Semester::where('id', $request->id)->first();
        
        if ($Semester) 
        {
            if ($Semester->current == 0) 
            {
                $Semester->current = 1; 
                $Semester->save(); 
                return response()->json(['res_code' => 0, 'res_msg' => 'This Semester is now currently activated.']);
            }
            else 
            {
                $Semester->current = 0; 
                $Semester->save(); 
                return response()->json(['res_code' => 0, 'res_msg' => 'This Semester is now currently de-activated.']);
            }
        }
    }
}
