<?php

namespace App\Http\Controllers\Finance;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FinanceDashboardController extends Controller
{
    public function index () 
    {
        $StudentInformation_all = \DB::table('student_informations')
            ->select(\DB::raw('COUNT(id) as student_count'))
            ->where('status', 1)
            ->first();

        $StudentInformation_all_male = \DB::table('student_informations')
            ->select(\DB::raw('COUNT(id) as student_count'))
            ->where('gender', '=', 1)
            ->first();

        $StudentInformation_all_female = \DB::table('student_informations')
            ->select(\DB::raw('COUNT(id) as student_count'))
            ->where('gender', '=', 2)
            ->where('status', 1)
            ->first();

        return view('control_panel_finance.dashboard.index',
            compact(
                'StudentInformation_all',
                'StudentInformation_all_male',
                'StudentInformation_all_female'
                )
        );
    }
}
