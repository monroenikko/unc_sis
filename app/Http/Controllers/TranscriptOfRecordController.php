<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TranscriptOfRecordController extends Controller
{
    public function tor()
    {
    	return view('pages.tor');
    }
}
