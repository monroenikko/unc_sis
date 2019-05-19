<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AcademicController extends Controller
{
    public function junior_high()
    {
    	return view('pages.academic.junior_high');
    }
    public function senior_high()
    {
    	return view('pages.academic.senior_high');
    }
}