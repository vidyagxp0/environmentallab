<?php

namespace App\Http\Controllers\rcms;

use App\Http\Controllers\Controller;
use App\Models\ActionItem;
use App\Models\Capa;
use App\Models\CC;
use App\Models\EffectivenessCheck;
use App\Models\Extension;
use App\Models\InternalAudit;
use App\Models\ManagementReview;
use App\Models\RiskManagement;
use App\Models\LabIncident;
use App\Models\Auditee;
use App\Models\AuditProgram;
use App\Models\RootCauseAnalysis;
use App\Models\Observation;
use App\Models\User;
use Carbon\Carbon;
use Helpers;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class DesktopController extends Controller
{
    public function rcms_desktop()
    {
        $table = [];
        $change_control = CC::orderByDesc('id')->get();
        $action_item = ActionItem::orderByDesc('id')->get();
        $extention = Extension::orderByDesc('id')->get();
        $effectiveness_check = EffectivenessCheck::orderByDesc('id')->get();
        $internal_audit = InternalAudit::orderByDesc('id')->get();
        $capa = Capa::orderByDesc('id')->get();
        $risk_management = RiskManagement::orderByDesc('id')->get();
        $management_review = ManagementReview::orderByDesc('id')->get();
        $labincident = LabIncident::orderByDesc('id')->get();
        $external_audit = Auditee::orderByDesc('id')->get();
        $audit_pragram = AuditProgram::orderByDesc('id')->get();
        $root_cause_analysis = RootCauseAnalysis::orderByDesc('id')->get();
        $observation = Observation::orderByDesc('id')->get();

        foreach ($change_control as $data) {

            $data->record_number = Helpers::recordFormat($data->record);
            $data->process = "Change-Control";
            $data->assign_to = "Amit guru";
            $data->open_date = Helpers::getdateFormat($data->initiation_date);
            $data->due_date = Helpers::getdateFormat($data->due_date);
            $data->division_name = Helpers::divisionNameForQMS($data->division_id);
            $data->create = Carbon::parse($data->created_at)->format('d-M-Y h:i A');
        }

        foreach ($action_item as $data) {
            $data->record_number = Helpers::recordFormat($data->record);
            $data->process = "Action-item";
            $data->assign_to = "Amit guru";
            $data->open_date = Helpers::getdateFormat($data->initiation_date);
            $data->due_date = Helpers::getdateFormat($data->due_date);
            $data->division_name = Helpers::divisionNameForQMS($data->division_id);
            $data->create = Carbon::parse($data->created_at)->format('d-M-Y h:i A');
        }
        foreach ($extention as $data) {
            $data->record_number = Helpers::recordFormat($data->record);
            $data->process = "Extention";
            $data->assign_to = "Amit guru";
            $data->open_date = Helpers::getdateFormat($data->initiation_date);
            $data->due_date = Helpers::getdateFormat($data->due_date);
            $data->division_name = Helpers::divisionNameForQMS($data->division_id);
            $data->create = Carbon::parse($data->created_at)->format('d-M-Y h:i A');
        }
        foreach ($effectiveness_check as $data) {
            $data->record_number = Helpers::recordFormat($data->record);
            $data->process = "Effectiveness-check";
            $data->assign_to = "Amit guru";
            $data->open_date = Helpers::getdateFormat($data->initiation_date);
            $data->due_date = Helpers::getdateFormat($data->due_date);
            $data->division_name = Helpers::divisionNameForQMS($data->division_id);
            $data->create = Carbon::parse($data->created_at)->format('d-M-Y h:i A');
        }
        foreach ($internal_audit as $data) {
            $data->record_number = Helpers::recordFormat($data->record);
            $data->process = "Internal-Audit";
            $data->assign_to = "Amit guru";
            $data->open_date = Helpers::getdateFormat($data->initiation_date);
            $data->due_date = Helpers::getdateFormat($data->due_date);
            $data->division_name = Helpers::divisionNameForQMS($data->division_id);
            $data->create = Carbon::parse($data->created_at)->format('d-M-Y h:i A');
        }
        foreach ($capa as $data) {
            $data->record_number = Helpers::recordFormat($data->record);
            $data->process = "Capa";
            $data->assign_to = "Amit guru";
            $data->open_date = Helpers::getdateFormat($data->initiation_date);
            $data->due_date = Helpers::getdateFormat($data->due_date);
            $data->division_name = Helpers::divisionNameForQMS($data->division_id);
            $data->create = Carbon::parse($data->created_at)->format('d-M-Y h:i A');
        }
        foreach ($risk_management as $data) {
            $data->record_number = Helpers::recordFormat($data->record);
            $data->process = "Risk-Assesment";
            $data->assign_to = "Amit guru";
            $data->open_date = Helpers::getdateFormat($data->initiation_date);
            $data->due_date = Helpers::getdateFormat($data->due_date);
            $data->division_name = Helpers::divisionNameForQMS($data->division_id);
            $data->create = Carbon::parse($data->created_at)->format('d-M-Y h:i A');
        }
        foreach ($management_review as $data) {
            $data->record_number = Helpers::recordFormat($data->record);
            $data->process = "Management-Review";
            $data->assign_to = "Amit guru";
            $data->open_date = Helpers::getdateFormat($data->initiation_date);
            $data->due_date = Helpers::getdateFormat($data->due_date);
            $data->division_name = Helpers::divisionNameForQMS($data->division_id);
            $data->create = Carbon::parse($data->created_at)->format('d-M-Y h:i A');
        }
        foreach ($labincident as $data) {
            $data->record_number = Helpers::recordFormat($data->record);
            $data->process = "Lab-Incident";
            $data->assign_to = "Amit guru";
            $data->open_date = Helpers::getdateFormat($data->initiation_date);
            $data->due_date = Helpers::getdateFormat($data->due_date);
            $data->division_name = Helpers::divisionNameForQMS($data->division_id);
            $data->create = Carbon::parse($data->created_at)->format('d-M-Y h:i A');
        }
        foreach ($external_audit as $data) {
            $data->record_number = Helpers::recordFormat($data->record);
            $data->process = "External-Audit";
            $data->assign_to = "Amit guru";
            $data->open_date = Helpers::getdateFormat($data->initiation_date);
            $data->due_date = Helpers::getdateFormat($data->due_date);
            $data->division_name = Helpers::divisionNameForQMS($data->division_id);
            $data->create = Carbon::parse($data->created_at)->format('d-M-Y h:i A');
        }
        foreach ($audit_pragram as $data) {
            $data->record_number = Helpers::recordFormat($data->record);
            $data->process = "Audit-Program";
            $data->assign_to = "Amit guru";
            $data->open_date = Helpers::getdateFormat($data->initiation_date);
            $data->due_date = Helpers::getdateFormat($data->due_date);
            $data->division_name = Helpers::divisionNameForQMS($data->division_id);
            $data->create = Carbon::parse($data->created_at)->format('d-M-Y h:i A');
        }
        foreach ($root_cause_analysis as $data) {
            $data->record_number = Helpers::recordFormat($data->record);
            $data->process = "Root-Cause-Analysis";
            $data->assign_to = "Amit guru";
            $data->open_date = Helpers::getdateFormat($data->initiation_date);
            $data->due_date = Helpers::getdateFormat($data->due_date);
            $data->division_name = Helpers::divisionNameForQMS($data->division_id);
            $data->create = Carbon::parse($data->created_at)->format('d-M-Y h:i A');
        }
        foreach ($observation as $data) {
            $data->record_number = Helpers::recordFormat($data->record);
            $data->process = "Observation";
            $data->assign_to = "Amit guru";
            $data->open_date = Helpers::getdateFormat($data->initiation_date);
            $data->due_date = Helpers::getdateFormat($data->due_date);
            $data->division_name = Helpers::divisionNameForQMS($data->division_id);
            $data->create = Carbon::parse($data->created_at)->format('d-M-Y h:i A');
        }

        //   return $table;

        return view('frontend.rcms.desktop', compact(
            'observation',
            'root_cause_analysis',
            'audit_pragram',
            'external_audit',
            'management_review',
            'labincident',
            'risk_management',
            'capa',
            'internal_audit',
            'effectiveness_check',
            'extention',
            'action_item',
            'observation',
            'change_control',
        ));
    }


    public function dashboard_search(Request $request){
        // return $request;

        if($request->form =="internal_audit"){
            $data = InternalAudit::where('status',$request->stage)->get();
            return $data;
            return view('frontend.rcms.desktop',compact('data'));

        }
    }
}
