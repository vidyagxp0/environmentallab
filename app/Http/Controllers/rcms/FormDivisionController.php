<?php

namespace App\Http\Controllers\rcms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FormDivisionController extends Controller
{
    public function formDivision(Request $request)
    {
        $request->session()->forget('division');
        $request->session()->put('division', $request->division_id);
        if ($request->process_name == "Internal Audit") {
            return redirect('audit');
        } elseif ($request->process_name == "External Audit") {
            return redirect('auditee');
        } elseif ($request->process_name == "CAPA") {
            return redirect('capa');
        } elseif ($request->process_name == "Audit Program") {
            return redirect('audit-program');
        } elseif ($request->process_name == "Lab Incident") {
            return redirect('lab-incident');
        } elseif ($request->process_name == "Risk Assessment") {
            return redirect('risk-management');
        } elseif ($request->process_name == "Root Cause Analysis") {
            return redirect('root-cause-analysis');
        } elseif ($request->process_name == "Change Control") {
            return redirect()->route('CC.create');
        } elseif ($request->process_name == "Management Review") {
            return redirect('meeting');
        }
    }
}
