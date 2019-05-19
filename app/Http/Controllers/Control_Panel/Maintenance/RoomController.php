<?php

namespace App\Http\Controllers\Control_Panel\Maintenance;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RoomController extends Controller
{
    public function index (Request $request) 
    {
        if ($request->ajax())
        {
            $Room = \App\Room::where('status', 1)
            ->where(function ($query) use ($request) {
                $query->where('room_code', 'like', '%'.$request->search.'%');
                $query->orWhere('room_description', 'like', '%'.$request->search.'%');
            })
            ->paginate(10);
            return view('control_panel.class_rooms.partials.data_list', compact('Room'))->render();
        }
        $Room = \App\Room::where('status', 1)->paginate(10);
        return view('control_panel.class_rooms.index', compact('Room'));
    }
    public function modal_data (Request $request) 
    {
        $Room = NULL;
        if ($request->id)
        {
            $Room = \App\Room::where('id', $request->id)->first();
        }
        return view('control_panel.class_rooms.partials.modal_data', compact('Room'))->render();
    }

    public function save_data (Request $request) 
    {
        $rules = [
            'room_code' => 'required',
            'room_description' => 'required'
        ];

        $Validator = \Validator($request->all(), $rules);

        if ($Validator->fails())
        {
            return response()->json(['res_code' => 1, 'res_msg' => 'Please fill all required fields.', 'res_error_msg' => $Validator->getMessageBag()]);
        }

        if ($request->id)
        {
            $Room = \App\Room::where('id', $request->id)->first();
            $Room->room_code = $request->room_code;
            $Room->room_description = $request->room_description;
            $Room->save();
            return response()->json(['res_code' => 0, 'res_msg' => 'Data successfully saved.']);
        }

        $Room = new \App\Room();
        $Room->room_code = $request->room_code;
        $Room->room_description = $request->room_description;
        $Room->save();
        return response()->json(['res_code' => 0, 'res_msg' => 'Data successfully saved.']);
    }

    public function deactivate_data (Request $request) 
    {
        $Room = \App\Room::where('id', $request->id)->first();

        if ($Room)
        {
            $Room->status = 0;
            $Room->save();
            return response()->json(['res_code' => 0, 'res_msg' => 'Data successfully deactivated.']);
        }
        return response()->json(['res_code' => 1, 'res_msg' => 'Invalid request.']);
    }
}
