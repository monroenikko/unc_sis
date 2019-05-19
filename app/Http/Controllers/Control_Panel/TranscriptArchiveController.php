<?php

namespace App\Http\Controllers\Control_Panel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Crypt;


class TranscriptArchiveController extends Controller
{
    public function index (Request $request) 
    {
        if ($request->ajax())
        {
            $TrascriptArhieve = \App\TrascriptArhieve::where(function ($query) use($request) {
                if ($request->search_sy) 
                {
                    $query->where('school_year_graduated', 'like',     '%'.$request->search_sy.'%');
                }
                
                if ($request->search) 
                {
                    $query->where('first_name',     'like',     '%'.$request->search.'%');
                    $query->orWhere('middle_name',  'like',     '%'.$request->search.'%');
                    $query->orWhere('last_name',    'like',     '%'.$request->search.'%');
                }
            })
            ->where('status', 1)
            ->paginate(50);
            return view('control_panel.transcript_archieve.partials.data_list', compact('TrascriptArhieve'));
        }
        
        $TrascriptArhieve = \App\TrascriptArhieve::
        where('status', 1)
        ->paginate(50);
        return view('control_panel.transcript_archieve.index', compact('TrascriptArhieve'));
    }
    public function modal_data (Request $request)
    {
        $FacultyInformation = [];
        $TrascriptArhieve = \App\TrascriptArhieve::where('id', $request->id)->first();
        // return view('control_panel.transcript_archieve.partials.modal_data', compact('FacultyInformation'))->render();
        return view('control_panel.transcript_archieve.partials.modal_data_tor_uploader', compact('FacultyInformation', 'TrascriptArhieve'))->render();
    }
    public function save_transcript (Request $request)
    {
        $rules = [
            'first_name' => 'required',
            'middle_name' => 'required',
            'last_name' => 'required',
            'school_year_graduated' => 'required',
        ];
        $messages = [
            'first_name.required' => 'First name is required.',
            'middle_name.required' => 'Middle name is required',
            'last_name.required' => 'Last name is required',
            'school_year_graduated.required' => 'School year graduated is required',
        ];

        $Validator = \Validator::make($request->all(), $rules, $messages);
        
        if ($Validator->fails()) 
        {
            return response()->json(['res_code' => 1, 'res_msg' => 'Something wrong in saving data', 'res_error_msg' => $Validator->getMessageBag()]);
        }

        if ($request->id) {
            if ($request->tor)
            {
                $rules = [
                    'tor' => 'max:2048'
                ];
                $messages = [
                    'tor.required' => 'TOR is required.',
                    'tor.mimetypes' => 'Excel file(xlsx) type is the only accepted type.',
                    'tor.max' => 'File size should be not more than 2MB.'
                ];
    
                $Validator = \Validator::make($request->all(), $rules, $messages);
                
                if ($Validator->fails()) 
                {
                    return response()->json(['res_code' => 1, 'res_msg' => 'Something wrong in saving data', 'res_error_msg' => $Validator->getMessageBag()]);
                }
            }
            
            $TrascriptArhieve = \App\TrascriptArhieve::where('id', $request->id)->first();
            $TrascriptArhieve->first_name           = $request->first_name;
            $TrascriptArhieve->middle_name          = $request->middle_name;
            $TrascriptArhieve->last_name            = $request->last_name;
            $TrascriptArhieve->school_year_graduated= $request->school_year_graduated;

            if ($request->tor) 
            {
                $path = public_path('data/files/'. $TrascriptArhieve->file_name);
                if (\File::exists($path))
                {
                    \File::delete($path);
                }

                // $file_name = base64_encode(date('U') . '-'. $request->last_name .'-'. $request->first_name .'-'. $request->middle_name . '-' . $request->school_year_graduated.'.').'.'.$request->tor->getClientOriginalExtension();
                $file_name = $request->tor->getClientOriginalName();
                $request->tor->move(public_path('data/files'), $file_name);
                $TrascriptArhieve->file_name = encrypt($file_name);
            }

            $TrascriptArhieve->save();
            return response()->json(['res_code' => 0, 'res_msg' => 'TOR successfully saved and uploaded.']);
        }

        
        $rules = [
            'tor' => 'required|max:2048'
        ];
        $messages = [
            'tor.required' => 'TOR is required.',
            'tor.mimetypes' => 'Excel file(xlsx) type is the only accepted type.',
            'tor.max' => 'File size should be not more than 2MB.'
        ];

        $Validator = \Validator::make($request->all(), $rules, $messages);
        
        if ($Validator->fails()) 
        {
            return response()->json(['res_code' => 1, 'res_msg' => 'Something wrong in saving data', 'res_error_msg' => $Validator->getMessageBag()]);
        }
        $file_name = base64_encode(date('U') . '-'. $request->last_name .'-'. $request->first_name .'-'. $request->middle_name . '-' . $request->school_year_graduated.'.').'.'.$request->tor->getClientOriginalExtension();
        $request->tor->move(public_path('data/files'), $file_name);
        
        $TrascriptArhieve = new \App\TrascriptArhieve();
        $TrascriptArhieve->first_name           = $request->first_name;
        $TrascriptArhieve->middle_name          = $request->middle_name;
        $TrascriptArhieve->last_name            = $request->last_name;
        $TrascriptArhieve->school_year_graduated= $request->school_year_graduated;
        $TrascriptArhieve->file_name            = encrypt($file_name);
        $TrascriptArhieve->save();
        return response()->json(['res_code' => 0, 'res_msg' => 'TOR successfully saved and uploaded.']);
    }   
    public function download_tor (Request $request)
    {
        if (!$request->id || !$request->file_name) 
        {
            return response()->json(['res_code' => 1, 'res_msg' => 'Invalid request1']);
        }

        $TrascriptArhieve = \App\TrascriptArhieve::where('id', $request->id)->first();

        if (!$TrascriptArhieve) 
        {
            return response()->json(['res_code' => 1, 'res_msg' => 'Invalid reques2t']);
        }
        $file_name = decrypt($TrascriptArhieve->file_name);
        return response()->json(['res_code' => 0, 'res_msg' => 'Download will proceed immidiately', 'file_path' => asset('data/files/'.$file_name)]);
    }
    public function delete_data (Request $request)
    {
        if (!$request->id) 
        {
            return response()->json(['res_code' => 1, 'res_msg' => 'Invalid request1']);
        }

        $TrascriptArhieve = \App\TrascriptArhieve::where('id', $request->id)->first();

        if (!$TrascriptArhieve) 
        {
            return response()->json(['res_code' => 1, 'res_msg' => 'Invalid reques2t']);
        }

        $TrascriptArhieve->status = 0;
        $TrascriptArhieve->save();
        return response()->json(['res_code' => 0, 'res_msg' => 'Data deleted']);
    }
}
