<?php

namespace App\Http\Controllers\rcms;

use App\Http\Controllers\Controller;
use App\Jobs\SendMail;
use App\Models\ActionItem;
use App\Models\Auditee;
use App\Models\AuditeeHistory;
use App\Models\Capa;
use App\Models\CC;
use App\Models\RootCauseAnalysis;
use Helpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\RecordNumber;
use App\Models\RoleGroup;
use App\Models\InternalAuditGrid;
use App\Models\AuditTrialExternal;
use App\Models\QMSDivision;
use Carbon\Carbon;
use App\Models\User;
use App\Services\DocumentService;
use PDF;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuditeeController extends Controller
{

    public function external_audit()
    {
        $old_record = Auditee::select('id', 'division_id', 'record', 'created_at')->get();
        $record_number = ((RecordNumber::first()->value('counter')) + 1);
        $record_number = str_pad($record_number, 4, '0', STR_PAD_LEFT);
        $currentDate = Carbon::now();
        $formattedDate = $currentDate->addDays(30);
        $due_date = $formattedDate->format('Y-m-d');

        $division = QMSDivision::where('name', Helpers::getDivisionName(session()->get('division')))->first();

        if ($division) {
            $last_record = Auditee::where('division_id', $division->id)->latest()->first();

            if ($last_record) {
                $record_number = $last_record->record_number ? str_pad($last_record->record_number->record_number + 1, 4, '0', STR_PAD_LEFT) : '0001';
            } else {
                $record_number = '0001';
            }
        }


        return view("frontend.forms.auditee", compact('due_date', 'record_number', 'old_record'));
    }

    public function store(Request $request)
    {
        //$request->dd();
        //  return $request->audit_start_date;
        //  die;


        if (!$request->short_description) {
            toastr()->error("Short description is required");
            return redirect()->back()->withInput();
        }

        $internalAudit = new Auditee();
        $internalAudit->form_type = "External-audit";
        $internalAudit->record = ((RecordNumber::first()->value('counter')) + 1);
        $internalAudit->initiator_id = Auth::user()->id;
        $internalAudit->division_id = $request->division_id;
        //$internalAudit->parent_id = $request->parent_id;
        //$internalAudit->parent_type = $request->parent_type;
        $internalAudit->external_auditor_name =  $request->external_auditor_name;
        $internalAudit->area_of_auditing =  $request->area_of_auditing;
        $internalAudit->division_code = $request->division_code;
        $internalAudit->intiation_date = $request->intiation_date;
        $internalAudit->assign_to = $request->assign_to;
        $internalAudit->multiple_assignee_to =  implode(',', $request->multiple_assignee_to);
        $internalAudit->due_date = $request->due_date;
        $internalAudit->Initiator_Group = $request->Initiator_Group;
        $internalAudit->initiator_group_code = $request->initiator_group_code;
        $internalAudit->short_description = $request->short_description;
        $internalAudit->audit_type = $request->audit_type;
        $internalAudit->if_other = $request->if_other;

        // $internalAudit->external_auditor_name = $request->external_auditor_name;
        // $internalAudit->area_of_auditing = $request->area_of_auditing;

        $internalAudit->initiated_through = $request->initiated_through;
        $internalAudit->initiated_if_other = $request->initiated_if_other;
        $internalAudit->others = $request->others;
        $internalAudit->repeat = $request->repeat;
        $internalAudit->repeat_nature = $request->repeat_nature;
        $internalAudit->due_date_extension = $request->due_date_extension;
        $internalAudit->initial_comments = $request->initial_comments;
        $internalAudit->severity_level = $request->severity_level;


        $internalAudit->start_date = $request->start_date;
        $internalAudit->end_date = $request->end_date;
        $internalAudit->audit_agenda = $request->audit_agenda;
        //$internalAudit->Facility =  implode(',', $request->Facility);
        //$internalAudit->Group = implode(',', $request->Group);
        $internalAudit->material_name = $request->material_name;
        $internalAudit->if_comments = $request->if_comments;
        $internalAudit->lead_auditor = $request->lead_auditor;
        $internalAudit->Audit_team =  $request->Audit_team;
        $internalAudit->reason_for_audit =  $request->reason_for_audit;

        // $internalAudit->external_auditor_name =  $request->external_auditor_name;
        // $internalAudit->area_of_auditing =  $request->area_of_auditing;
        $internalAudit->Auditee =  implode(',', $request->Auditee);
        $auditeeIdsArray = explode(',', $internalAudit->Auditee);
        $auditeeNames = User::whereIn('id', $auditeeIdsArray)->pluck('name')->toArray();
        $auditeeNamesString = implode(', ', $auditeeNames);
        $internalAudit->Auditor_Details = $request->Auditor_Details;
        $internalAudit->External_Auditing_Agency = $request->External_Auditing_Agency;
        $internalAudit->Relevant_Guidelines = $request->Relevant_Guidelines;
        $internalAudit->QA_Comments = $request->QA_Comments;
        $internalAudit->Audit_Category = $request->Audit_Category;
        $internalAudit->Supplier_Details = $request->Supplier_Details;
        $internalAudit->Supplier_Site = $request->Supplier_Site;
        $internalAudit->Comments = $request->Comments;
        $internalAudit->Audit_Comments1 = $request->Audit_Comments1;
        $internalAudit->Remarks = $request->Remarks;
        $internalAudit->Reference_Recores1 =  implode(',', $request->Reference_Recores1);
        // dd($internalAudit->Reference_Recores1);
        $internalAudit->Audit_Comments2 = $request->Audit_Comments2;
        $internalAudit->due_date = $request->due_date;
        $internalAudit->audit_start_date = $request->audit_start_date;
        $internalAudit->audit_end_date = $request->audit_end_date;
        $internalAudit->status = 'Opened';
        $internalAudit->stage = 1;
        $internalAudit->external_agencies = $request->external_agencies;

        if (!empty($request->file_attachment_guideline)) {
            $files = [];
            if ($request->hasfile('file_attachment_guideline')) {
                foreach ($request->file('file_attachment_guideline') as $file) {
                    $name = $request->name . 'file_attachment_guideline' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }

            $internalAudit->file_attachment_guideline = json_encode($files);
        }

        if (!empty($request->inv_attachment)) {
            $files = [];
            if ($request->hasfile('inv_attachment')) {
                foreach ($request->file('inv_attachment') as $file) {
                    $name = $request->name . 'inv_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }

            $internalAudit->inv_attachment = json_encode($files);
        }

        if (!empty($request->file_attachment)) {
            $files = [];
            if ($request->hasfile('file_attachment')) {
                foreach ($request->file('file_attachment') as $file) {
                    $name = $request->name . 'file_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }

            $internalAudit->file_attachment = json_encode($files);
        }


        if (!empty($request->Audit_file)) {
            $files = [];
            if ($request->hasfile('Audit_file')) {
                foreach ($request->file('Audit_file') as $file) {
                    $name = $request->name . 'Audit_file' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
            $internalAudit->Audit_file = json_encode($files);
        }

        if (!empty($request->report_file)) {
            $files = [];
            if ($request->hasfile('report_file')) {
                foreach ($request->file('report_file') as $file) {
                    $name = $request->name . 'report_file' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
            $internalAudit->report_file = json_encode($files);
        }
        if (!empty($request->myfile)) {
            $files = [];
            if ($request->hasfile('myfile')) {
                foreach ($request->file('myfile') as $file) {
                    $name = $request->name . 'myfile' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
            $internalAudit->myfile = json_encode($files);
        }


        //return $internalAudit;
        $internalAudit->save();

        $record = RecordNumber::first();
        $record->counter = ((RecordNumber::first()->value('counter')) + 1);
        $record->update();



        $auditAgenda = InternalAuditGrid::where(['audit_id' => $internalAudit->id, 'identifier' => 'AuditAgenda'])->firstOrCreate();
        $auditAgenda->audit_id = $internalAudit->id;
        $auditAgenda->identifier = 'AuditAgenda';
        $auditAgenda->data = $request->auditAgendaData;
        $auditAgenda->save();

        // $data3 = new InternalAuditGrid();
        // $data3->audit_id = $internalAudit->id;
        // $data3->type = "external_audit";
        // if (!empty($request->audit)) {
        //     $data3->area_of_audit = serialize($request->audit);
        // }
        // if (!empty($request->scheduled_start_date)) {
        //     $data3->start_date = serialize($request->scheduled_start_date);
        // }
        // if (!empty($request->scheduled_start_time)) {
        //     $data3->start_time = serialize($request->scheduled_start_time);
        // }
        // if (!empty($request->scheduled_end_date)) {
        //     $data3->end_date = serialize($request->scheduled_end_date);
        // }
        // if (!empty($request->scheduled_end_time)) {
        //     $data3->end_time = serialize($request->scheduled_end_time);
        // }
        // if (!empty($request->auditor)) {
        //     $data3->auditor = serialize($request->auditor);
        // }
        // if (!empty($request->auditee)) {
        //     $data3->auditee = serialize($request->auditee);
        // }
        // if (!empty($request->remarks)) {
        //     $data3->remark = serialize($request->remarks);
        // }
        // $data3->save();


        $data4 = new InternalAuditGrid();
        $data4->audit_id = $internalAudit->id;
        $data4->type = "Observation_field_Auditee";
        if (!empty($request->observation_id)) {
            $data4->observation_id = serialize($request->observation_id);
        }
        if (!empty($request->date)) {
            $data4->date = serialize($request->date);
        }
        if (!empty($request->auditorG)) {
            $data4->auditor = serialize($request->auditorG);
        }
        if (!empty($request->auditeeG)) {
            $data4->auditee = serialize($request->auditeeG);
        }
        if (!empty($request->observation_description)) {
            $data4->observation_description = serialize($request->observation_description);
        }
        if (!empty($request->severity_level)) {
            $data4->severity_level = serialize($request->severity_level);
        }
        if (!empty($request->area)) {
            $data4->area = serialize($request->area);
        }
        if (!empty($request->observation_category)) {
            $data4->observation_category = serialize($request->observation_category);
        }
         if (!empty($request->capa_required)) {
            $data4->capa_required = serialize($request->capa_required);
        }
         if (!empty($request->auditee_response)) {
            $data4->auditee_response = serialize($request->auditee_response);
        }
        if (!empty($request->auditor_review_on_response)) {
            $data4->auditor_review_on_response = serialize($request->auditor_review_on_response);
        }
        if (!empty($request->qa_comment)) {
            $data4->qa_comment = serialize($request->qa_comment);
        }
        if (!empty($request->capa_details)) {
            $data4->capa_details = serialize($request->capa_details);
        }
        if (!empty($request->capa_due_date)) {
            $data4->capa_due_date = serialize($request->capa_due_date);
        }
        if (!empty($request->capa_owner)) {
            $data4->capa_owner = serialize($request->capa_owner);
        }
        if (!empty($request->action_taken)) {
            $data4->action_taken = serialize($request->action_taken);
        }
        if (!empty($request->capa_completion_date)) {
            $data4->capa_completion_date = serialize($request->capa_completion_date);
        }
        if (!empty($request->status_Observation)) {
            $data4->status = serialize($request->status_Observation);
        }
        if (!empty($request->remark_observation)) {
            $data4->remark = serialize($request->remark_observation);
        }
        //dd($data4);
        $data4->save();
        if (!empty($internalAudit->intiation_date)) {
            $history = new AuditTrialExternal();
            $history->ExternalAudit_id = $internalAudit->id;
            $history->activity_type = 'Date of Initiation';
            $history->previous = "Null";
            $history->current = Helpers::getdateFormat($internalAudit->intiation_date);
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $internalAudit->status;
            $history->save();
        }

        if (!empty($internalAudit->initiator_id)) {
            $history = new AuditTrialExternal();
            $history->ExternalAudit_id = $internalAudit->id;
            $history->activity_type = 'Initiator';
            $history->previous = "Null";
            $history->current = Helpers::getInitiatorName($internalAudit->initiator_id);
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $internalAudit->status;


            $history->save();
        }
        if (!empty($internalAudit->multiple_assignee_to)) {
            $history = new AuditTrialExternal();
            $history->ExternalAudit_id = $internalAudit->id;
            $history->activity_type = 'Assigned to';
            $history->previous = "Null";
            $history->current = Helpers::getInitiatorName($internalAudit->multiple_assignee_to);
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $internalAudit->status;
            $history->save();
        }

        if (!empty($internalAudit->Initiator_Group)) {
            $history = new AuditTrialExternal();
            $history->ExternalAudit_id = $internalAudit->id;
            $history->activity_type = 'Initiator Group';
            $history->previous = "Null";
            $history->current =Helpers::getInitiatorGroupFullName ($internalAudit->Initiator_Group);
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $internalAudit->status;
            $history->save();
        }
        if (!empty($internalAudit->initiator_group_code)) {
            $history = new AuditTrialExternal();
            $history->ExternalAudit_id = $internalAudit->id;
            $history->activity_type = 'Initiator Group Code';
            $history->previous = "Null";
            $history->current = $internalAudit->initiator_group_code;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $internalAudit->status;
            $history->save();
        }

        if (!empty($internalAudit->short_description)) {
            $history = new AuditTrialExternal();
            $history->ExternalAudit_id = $internalAudit->id;
            $history->activity_type = 'Short Description';
            $history->previous = "Null";
            $history->current = $internalAudit->short_description;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $internalAudit->status;

            $history->save();
        }
        if (!empty($internalAudit->Audit_Category)) {
            $history = new AuditTrialExternal();
            $history->ExternalAudit_id = $internalAudit->id;
            $history->activity_type = 'Audit Category';
            $history->previous = "Null";
            $history->current = $internalAudit->Audit_Category;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $internalAudit->status;

            $history->save();
        }
        if (!empty($internalAudit->severity_level)) {
            $history = new AuditTrialExternal();
            $history->ExternalAudit_id = $internalAudit->id;
            $history->activity_type = 'Severity Level';
            $history->previous = "Null";
            $history->current = $internalAudit->severity_level;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $internalAudit->status;

            $history->save();
        }
        if (!empty($internalAudit->external_auditor_name)) {
            $history = new AuditTrialExternal();
            $history->ExternalAudit_id = $internalAudit->id;
            $history->activity_type = 'Name of Auditor';
            $history->previous = "Null";
            $history->current = $internalAudit->external_auditor_name;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $internalAudit->status;

            $history->save();
        }
        if (!empty($internalAudit->area_of_auditing)) {
            $history = new AuditTrialExternal();
            $history->ExternalAudit_id = $internalAudit->id;
            $history->activity_type = 'Area of Auditing';
            $history->previous = "Null";
            $history->current = $internalAudit->area_of_auditing;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $internalAudit->status;

            $history->save();
        }

        if (!empty($internalAudit->audit_type)) {
            $history = new AuditTrialExternal();
            $history->ExternalAudit_id = $internalAudit->id;
            $history->activity_type = 'Type of Audit';
            $history->previous = "Null";
            $history->current = $internalAudit->audit_type;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $internalAudit->status;
            $history->save();
        }

        if (!empty($internalAudit->initiated_if_other)) {
            $history = new AuditTrialExternal();
            $history->ExternalAudit_id = $internalAudit->id;
            $history->activity_type = 'Others';
            $history->previous = "Null";
            $history->current = $internalAudit->initiated_if_other;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $internalAudit->status;

            $history->save();
        }

        if (!empty($internalAudit->if_other)) {
            $history = new AuditTrialExternal();
            $history->ExternalAudit_id = $internalAudit->id;
            $history->activity_type = 'If Other';
            $history->previous = "Null";
            $history->current = $internalAudit->if_other;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $internalAudit->status;
            $history->save();
        }
        if (!empty($internalAudit->external_agencies)) {
            $history = new AuditTrialExternal();
            $history->ExternalAudit_id = $internalAudit->id;
            $history->activity_type = 'External Agencies';
            $history->previous = "Null";
            $history->current = strtoupper($internalAudit->external_agencies);
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $internalAudit->status;
            $history->save();
        }
        if (!empty($internalAudit->initial_comments)) {
            $history = new AuditTrialExternal();
            $history->ExternalAudit_id = $internalAudit->id;
            $history->activity_type = 'Description';
            $history->previous = "Null";
            $history->current = $internalAudit->initial_comments;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $internalAudit->status;
            $history->save();
        }
        if (!empty($internalAudit->reason_for_audit)) {
            $history = new AuditTrialExternal();
            $history->ExternalAudit_id = $internalAudit->id;
            $history->activity_type = 'Reason For Audit';
            $history->previous = "Null";
            $history->current = $internalAudit->reason_for_audit;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $internalAudit->status;
            $history->save();
        }
        if (!empty($internalAudit->others)) {
            $history = new AuditTrialExternal();
            $history->ExternalAudit_id = $internalAudit->id;
            $history->activity_type = 'Other';
            $history->previous = "Null";
            $history->current = $internalAudit->others;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $internalAudit->status;
            $history->save();
        }
        // if (!empty($internalAudit->initial_comments)) {
        //     $history = new AuditTrialExternal();
        //     $history->ExternalAudit_id = $internalAudit->id;
        //     $history->activity_type = 'Initial Comments';
        //     $history->previous = "Null";
        //     $history->current = $internalAudit->initial_comments;
        //     $history->comment = "NA";
        //     $history->user_id = Auth::user()->id;
        //     $history->user_name = Auth::user()->name;
        //     $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        //     $history->origin_state = $internalAudit->status;
        //     $history->save();
        // }

        if (!empty($internalAudit->start_date)) {
            $history = new AuditTrialExternal();
            $history->ExternalAudit_id = $internalAudit->id;
            $history->activity_type = 'Audit Schedule Start Date';
            $history->previous = "Null";
            $history->current = Helpers::getdateFormat( $internalAudit->start_date);
            $history->comment = "Na";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $internalAudit->status;
            $history->save();
        }

        if (!empty($internalAudit->end_date)) {
            $history = new AuditTrialExternal();
            $history->ExternalAudit_id = $internalAudit->id;
            $history->activity_type = 'Audit Schedule End Date';
            $history->previous = "Null";
            $history->current = Helpers::getdateFormat( $internalAudit->end_date);
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $internalAudit->status;
            $history->save();
        }

        if (!empty($internalAudit->audit_agenda)) {
            $history = new AuditTrialExternal();
            $history->ExternalAudit_id = $internalAudit->id;
            $history->activity_type = 'Audit Agenda';
            $history->previous = "Null";
            $history->current = $internalAudit->audit_agenda;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $internalAudit->status;
            $history->save();
        }

        // if (!empty($internalAudit->Facility)) {
        //     $history = new AuditTrialExternal();
        //     $history->ExternalAudit_id = $internalAudit->id;
        //     $history->activity_type = 'Facility Name';
        //     $history->previous = "Null";
        //     $history->current = $internalAudit->Facility;
        //     $history->comment = "NA";
        //     $history->user_id = Auth::user()->id;
        //     $history->user_name = Auth::user()->name;
        //     $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        //     $history->origin_state = $internalAudit->status;
        //     $history->save();
        // }

        // if (!empty($internalAudit->Group)) {
        //     $history = new AuditTrialExternal();
        //     $history->ExternalAudit_id = $internalAudit->id;
        //     $history->activity_type = 'Group Name';
        //     $history->previous = "Null";
        //     $history->current = $internalAudit->Group;
        //     $history->comment = "NA";
        //     $history->user_id = Auth::user()->id;
        //     $history->user_name = Auth::user()->name;
        //     $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        //     $history->origin_state = $internalAudit->status;
        //     $history->save();
        // }

        if (!empty($internalAudit->material_name)) {
            $history = new AuditTrialExternal();
            $history->ExternalAudit_id = $internalAudit->id;
            $history->activity_type = 'Product/Material Name';
            $history->previous = "Null";
            $history->current = $internalAudit->material_name;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $internalAudit->status;
            $history->save();
        }
        if (!empty($internalAudit->if_comments)) {
            $history = new AuditTrialExternal();
            $history->ExternalAudit_id = $internalAudit->id;
            $history->activity_type = 'Comments(If Any)';
            $history->previous = "Null";
            $history->current = $internalAudit->if_comments;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $internalAudit->status;
            $history->save();
        }


        if (!empty($internalAudit->lead_auditor)) {
            $history = new AuditTrialExternal();
            $history->ExternalAudit_id = $internalAudit->id;
            $history->activity_type = 'Lead Auditor';
            $history->previous = "Null";
            $history->current = $internalAudit->lead_auditor;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $internalAudit->status;
            $history->save();
        }

        if (!empty($internalAudit->Audit_team)) {
            $history = new AuditTrialExternal();
            $history->ExternalAudit_id = $internalAudit->id;
            $history->activity_type = 'Audit Team';
            $history->previous = "Null";
            $history->current = $internalAudit->Audit_team;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $internalAudit->status;
            $history->save();
        }

        if (!empty($internalAudit->Auditee)) {
            $history = new AuditTrialExternal();
            $history->ExternalAudit_id = $internalAudit->id;
            $history->activity_type = 'Auditee';
            $history->previous = "Null";
            $history->current =  $auditeeNamesString;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $internalAudit->status;
            $history->save();
        }

        if (!empty($internalAudit->Auditor_Details)) {
            $history = new AuditTrialExternal();
            $history->ExternalAudit_id = $internalAudit->id;
            $history->activity_type = 'External Auditor Details';
            $history->previous = "Null";
            $history->current = $internalAudit->Auditor_Details;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $internalAudit->status;
            $history->save();
        }
        if (!empty($internalAudit->External_Auditing_Agency)) {
            $history = new AuditTrialExternal();
            $history->ExternalAudit_id = $internalAudit->id;
            $history->activity_type = 'External Auditing Agency';
            $history->previous = "Null";
            $history->current = $internalAudit->External_Auditing_Agency;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $internalAudit->status;
            $history->save();
        }
        if (!empty($internalAudit->Relevant_Guidelines)) {
            $history = new AuditTrialExternal();
            $history->ExternalAudit_id = $internalAudit->id;
            $history->activity_type = 'Relevant Guidelines / Industry Standards';
            $history->previous = "Null";
            $history->current = $internalAudit->Relevant_Guidelines;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $internalAudit->status;
            $history->save();
        }
        if (!empty($internalAudit->Supplier_Details)) {
            $history = new AuditTrialExternal();
            $history->ExternalAudit_id = $internalAudit->id;
            $history->activity_type = 'Supplier/Vendor/Manufacturer Details';
            $history->previous = "Null";
            $history->current = $internalAudit->Supplier_Details;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $internalAudit->status;
            $history->save();
        }
        if (!empty($internalAudit->Supplier_Site)) {
            $history = new AuditTrialExternal();
            $history->ExternalAudit_id = $internalAudit->id;
            $history->activity_type = 'Supplier/Vendor/Manufacturer Site';
            $history->previous = "Null";
            $history->current = $internalAudit->Supplier_Site;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $internalAudit->status;
            $history->save();
        }
        if (!empty($internalAudit->QA_Comments)) {
            $history = new AuditTrialExternal();
            $history->ExternalAudit_id = $internalAudit->id;
            $history->activity_type = 'QA Comments';
            $history->previous = "Null";
            $history->current = $internalAudit->QA_Comments;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $internalAudit->status;
            $history->save();
        }
        if (!empty($internalAudit->file_attachment_guideline)) {
            $history = new AuditTrialExternal();
            $history->ExternalAudit_id = $internalAudit->id;
            $history->activity_type = 'Guideline Attachment';
            $history->previous = "Null";
            $history->current = $internalAudit->file_attachment_guideline;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $internalAudit->status;
            $history->save();
        }

        if (!empty($internalAudit->Comments)) {
            $history = new AuditTrialExternal();
            $history->ExternalAudit_id = $internalAudit->id;
            $history->activity_type = 'Comments';
            $history->previous = "Null";
            $history->current = $internalAudit->Comments;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $internalAudit->status;
            $history->save();
        }

        if (!empty($internalAudit->Audit_Comments1)) {
            $history = new AuditTrialExternal();
            $history->ExternalAudit_id = $internalAudit->id;
            $history->activity_type = 'Audit Comments';
            $history->previous = "Null";
            $history->current = $internalAudit->Audit_Comments1;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $internalAudit->status;
            $history->save();
        }
        if (!empty($internalAudit->due_date_extension)) {
            $history = new AuditTrialExternal();
            $history->ExternalAudit_id = $internalAudit->id;
            $history->activity_type = 'Due Date Extension Justification';
            $history->previous = "Null";
            $history->current = $internalAudit->due_date_extension;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $internalAudit->status;
            $history->save();
        }

        if (!empty($internalAudit->Remarks)) {
            $history = new AuditTrialExternal();
            $history->ExternalAudit_id = $internalAudit->id;
            $history->activity_type = 'Remarks';
            $history->previous = "Null";
            $history->current = $internalAudit->Remarks;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $internalAudit->status;
            $history->save();
        }

        if (!empty($internalAudit->Reference_Recores1)) {
            $history = new AuditTrialExternal();
            $history->ExternalAudit_id = $internalAudit->id;
            $history->activity_type = 'Reference Record';
            $history->previous = "Null";
            $history->current = str_replace(',', ', ', $internalAudit->Reference_Recores1);
            // $history->current =  implode(',', $request->Reference_Recores1);
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $internalAudit->status;
            $history->save();
        }
        if (!empty($internalAudit->initiated_through)) {
            $history = new AuditTrialExternal();
            $history->ExternalAudit_id = $internalAudit->id;
            $history->activity_type = 'Initiated Through';
            $history->previous = "Null";
            $history->current = $internalAudit->initiated_through;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $internalAudit->status;

            $history->save();
        }
        if (!empty($internalAudit->Reference_Recores2)) {
            $history = new AuditTrialExternal();
            $history->ExternalAudit_id = $internalAudit->id;
            $history->activity_type = 'Reference Record';
            $history->previous = "Null";
            $history->current = str_replace(',', ', ', $internalAudit->Reference_Recores2);
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $internalAudit->status;
            $history->save();
        }

        if (!empty($internalAudit->Audit_Comments2)) {
            $history = new AuditTrialExternal();
            $history->ExternalAudit_id = $internalAudit->id;
            $history->activity_type = 'Audit Comment';
            $history->previous = "Null";
            $history->current = $internalAudit->Audit_Comments2;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $internalAudit->status;
            $history->save();
        }

        if (!empty($internalAudit->inv_attachment)) {
            $history = new AuditTrialExternal();
            $history->ExternalAudit_id = $internalAudit->id;
            $history->activity_type = 'Initial Attachment';
            $history->previous = "Null";
            $history->current = $internalAudit->inv_attachment;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $internalAudit->status;
            $history->save();
        }

        if (!empty($internalAudit->file_attachment)) {
            $history = new AuditTrialExternal();
            $history->ExternalAudit_id = $internalAudit->id;
            $history->activity_type = 'File Attachment';
            $history->previous = "Null";
            $history->current = $internalAudit->file_attachment;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $internalAudit->status;
            $history->save();
        }

        if (!empty($internalAudit->Audit_file)) {
            $history = new AuditTrialExternal();
            $history->ExternalAudit_id = $internalAudit->id;
            $history->activity_type = 'Audit Attachments';
            $history->previous = "Null";
            $history->current = $internalAudit->Audit_file;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $internalAudit->status;
            $history->save();
        }

        if (!empty($internalAudit->report_file)) {
            $history = new AuditTrialExternal();
            $history->ExternalAudit_id = $internalAudit->id;
            $history->activity_type = 'Report Attachments';
            $history->previous = "Null";
            $history->current = $internalAudit->report_file;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $internalAudit->status;
            $history->save();
        }

        if (!empty($internalAudit->myfile)) {
            $history = new AuditTrialExternal();
            $history->ExternalAudit_id = $internalAudit->id;
            $history->activity_type = 'Audit Attachment';
            $history->previous = "Null";
            $history->current = $internalAudit->myfile;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $internalAudit->status;
            $history->save();
        }

        // if (!empty($internalAudit->myfile)) {
        //     $history = new AuditTrialExternal();
        //     $history->ExternalAudit_id = $internalAudit->id;
        //     $history->activity_type = 'Inv Attachment';
        //     $history->previous = "Null";
        //     $history->current = $internalAudit->myfile;
        //     $history->comment = "NA";
        //     $history->user_id = Auth::user()->id;
        //     $history->user_name = Auth::user()->name;
        //     $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        //     $history->origin_state = $internalAudit->status;
        //     $history->save();
        // }
        if (!empty($internalAudit->record)) {
            $history = new AuditTrialExternal();
            $history->ExternalAudit_id = $internalAudit->id;
            $history->activity_type = 'Record Number';
            $history->previous = "Null";
            $history->current = Helpers::getDivisionName(session()->get('division')) . "/EA/" . Helpers::year($internalAudit->created_at) . "/" . str_pad($internalAudit->record, 4, '0', STR_PAD_LEFT);
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $internalAudit->status;


            $history->save();
        }
        if(!empty($internalAudit->division_code))
        {


            $history = new AuditTrialExternal();
            $history->ExternalAudit_id = $internalAudit->id;
            $history->activity_type = 'Site/Location Code';
            $history->previous = "Null";
            $history->current = $internalAudit->division_code;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $internalAudit->status;


            $history->save();
        }

        if (!empty($internalAudit->due_date)) {
            $history = new AuditTrialExternal();
            $history->ExternalAudit_id = $internalAudit->id;
            $history->activity_type = 'Due Date';
            $history->previous = "Null";
            $history->current = Helpers::getdateFormat($internalAudit->due_date);
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $internalAudit->status;
            $history->save();
        }

        if (!empty($internalAudit->audit_start_date)) {
            $history = new AuditTrialExternal();
            $history->ExternalAudit_id = $internalAudit->id;
            $history->activity_type = 'Audit Start Date';
            $history->previous = "Null";
            $history->current = Helpers::getdateFormat($internalAudit->audit_start_date);
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $internalAudit->status;
            $history->save();
        }

        if (!empty($internalAudit->audit_end_date)) {
            $history = new AuditTrialExternal();
            $history->ExternalAudit_id = $internalAudit->id;
            $history->activity_type = 'Audit End Date';
            $history->previous = "Null";
            $history->current = Helpers::getdateFormat($internalAudit->audit_end_date);
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $internalAudit->status;
            $history->save();
        }

        DocumentService::update_qms_numbers();

        toastr()->success("Record is Create Successfully");
        return redirect(url('rcms/qms-dashboard'));

    }

    public function show($id)
    {

        $old_record = Auditee::select('id', 'division_id', 'record', 'created_at')->get();
        $data = Auditee::find($id);
        $data->record = str_pad($data->record, 4, '0', STR_PAD_LEFT);
        $data->assign_to_name = User::where('id', $data->assign_id)->value('name');
        $data->initiator_name = User::where('id', $data->initiator_id)->value('name');
        $grid_data = InternalAuditGrid::where('audit_id', $id)->where('type', "external_audit")->first();
        $grid_data1 = InternalAuditGrid::where('audit_id', $id)->where('type', "Observation_field_Auditee")->first();

        $auditAgendaData = InternalAuditGrid::where(['audit_id' => $id, 'identifier' => 'AuditAgenda'])->first();
        if ($auditAgendaData) {
            $auditAgenda = json_decode($auditAgendaData->data, true);
        } else {
            $auditAgenda = [];
        }
        return view('frontend.externalAudit.view', compact('data', 'old_record','grid_data','grid_data1', 'auditAgenda'));
    }

    public function update(Request $request, $id)
    {
        $lastDocument = Auditee::find($id);
        $internalAudit = Auditee::find($id);
        //$internalAudit->division_id = $request->division_id;
        //$internalAudit->parent_id = $request->parent_id;
        //$internalAudit->parent_type = $request->parent_type;
        $internalAudit->intiation_date = $request->intiation_date;
        $internalAudit->assign_to = $request->assign_to;
        $internalAudit->multiple_assignee_to =  implode(',', $request->multiple_assignee_to);
        $internalAudit->due_date = $request->due_date;
        $internalAudit->Initiator_Group = $request->Initiator_Group;
        $internalAudit->initiator_group_code = $request->initiator_group_code;
        $internalAudit->short_description = $request->short_description;
        $internalAudit->audit_type = $request->audit_type;
        $internalAudit->if_other = $request->if_other;
        $internalAudit->area_of_auditing =  $request->area_of_auditing;
        $internalAudit->external_auditor_name =  $request->external_auditor_name;
        $internalAudit->reason_for_audit =  $request->reason_for_audit;

        $internalAudit->initiated_through = $request->initiated_through;
        $internalAudit->initiated_if_other = $request->initiated_if_other;
        $internalAudit->others = $request->others;
        $internalAudit->external_agencies = $request->external_agencies;
        $internalAudit->repeat = $request->repeat;
        $internalAudit->repeat_nature = $request->repeat_nature;
        $internalAudit->due_date_extension = $request->due_date_extension;

        $internalAudit->initial_comments = $request->initial_comments;
        $internalAudit->start_date = $request->start_date;

        $internalAudit->end_date = $request->end_date;
        $internalAudit->audit_agenda = $request->audit_agenda;
        //$internalAudit->Facility =  implode(',', $request->Facility);
        //$internalAudit->Group = implode(',', $request->Group);
        $internalAudit->material_name = $request->material_name;
        $internalAudit->if_comments = $request->if_comments;
        $internalAudit->lead_auditor = $request->lead_auditor;
        $internalAudit->Audit_team =  $request->Audit_team;
        $internalAudit->Auditee =  implode(',', $request->Auditee);
        $auditeeIdsArray = explode(',', $internalAudit->Auditee);
        $auditeeNames = User::whereIn('id', $auditeeIdsArray)->pluck('name')->toArray();
        $auditeeNamesString = implode(', ', $auditeeNames);        $internalAudit->Auditor_Details = $request->Auditor_Details;
        $internalAudit->Audit_Category = $request->Audit_Category;
        $internalAudit->External_Auditing_Agency = $request->External_Auditing_Agency;
        $internalAudit->Relevant_Guidelines = $request->Relevant_Guidelines;
        $internalAudit->QA_Comments = $request->QA_Comments;
        $internalAudit->Supplier_Details = $request->Supplier_Details;
        $internalAudit->Supplier_Site = $request->Supplier_Site;
        $internalAudit->Comments = $request->Comments;
        $internalAudit->Audit_Comments1 = $request->Audit_Comments1;
        $internalAudit->Remarks = $request->Remarks;
        $internalAudit->Reference_Recores1 = implode(',', $request->Reference_Recores1);
        if (!empty($request->file_attachment_guideline)) {
            $files = [];
            if ($request->hasfile('file_attachment_guideline')) {

                foreach ($request->file('file_attachment_guideline') as $file) {
                    $name = $request->name . 'file_attachment_guideline' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }


            $internalAudit->file_attachment_guideline = json_encode($files);
        }

        $internalAudit->Audit_Comments2 = $request->Audit_Comments2;
        $internalAudit->due_date = $request->due_date;
        $internalAudit->audit_start_date = $request->audit_start_date;
        $internalAudit->audit_end_date = $request->audit_end_date;
        $internalAudit->severity_level = $request->severity_level;

        if (!empty($request->inv_attachment)) {
            $files = [];
            if ($request->hasfile('inv_attachment')) {
                foreach ($request->file('inv_attachment') as $file) {
                    $name = $request->name . 'inv_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }


            $internalAudit->inv_attachment = json_encode($files);
        }


        if (!empty($request->file_attachment)) {
            $files = [];
            if ($request->hasfile('file_attachment')) {

                foreach ($request->file('file_attachment') as $file) {
                    $name = $request->name . 'file_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }


            $internalAudit->file_attachment = json_encode($files);
        }


        if (!empty($request->Audit_file)) {
            $files = [];
            if ($request->hasfile('Audit_file')) {
                foreach ($request->file('Audit_file') as $file) {
                    $name = $request->name . 'Audit_file' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }


            $internalAudit->Audit_file = json_encode($files);
        }

        if (!empty($request->report_file)) {
            $files = [];
            if ($request->hasfile('report_file')) {
                foreach ($request->file('report_file') as $file) {
                    $name = $request->name . 'report_file' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }


            $internalAudit->report_file = json_encode($files);
        }
        if (!empty($request->myfile)) {
            $files = [];
            if ($request->hasfile('myfile')) {
                foreach ($request->file('myfile') as $file) {
                    $name = $request->name . 'myfile' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }


            $internalAudit->myfile = json_encode($files);
        }

        $internalAudit->update();
        // $data3 = InternalAuditGrid::where('audit_id',$internalAudit->id)->where('type','external_audit')->first();
        // if (!empty($request->audit)) {
        //     $data3->area_of_audit = serialize($request->audit);
        // }
        // if (!empty($request->scheduled_start_date)) {
        //     $data3->start_date = serialize($request->scheduled_start_date);
        // }
        // if (!empty($request->scheduled_start_time)) {
        //     $data3->start_time = serialize($request->scheduled_start_time);
        // }
        // if (!empty($request->scheduled_end_date)) {
        //     $data3->end_date = serialize($request->scheduled_end_date);
        // }
        // if (!empty($request->scheduled_end_time)) {
        //     $data3->end_time = serialize($request->scheduled_end_time);
        // }
        // if (!empty($request->auditor)) {
        //     $data3->auditor = serialize($request->auditor);
        // }
        // if (!empty($request->auditee)) {
        //     $data3->auditee = serialize($request->auditee);
        // }
        // if (!empty($request->remark)) {
        //     $data3->remark = serialize($request->remark);
        // }
        // $data3->update();

        $auditAgenda = InternalAuditGrid::where(['audit_id' => $id, 'identifier' => 'AuditAgenda'])->firstOrCreate();
        $auditAgenda->audit_id = $id;
        $auditAgenda->identifier = 'AuditAgenda';
        $auditAgenda->data = $request->auditAgendaData;
        $auditAgenda->update();

        $data4 = InternalAuditGrid::where('audit_id',$internalAudit->id)->where('type','Observation_field_Auditee')->first();

        if (!empty($request->observation_id)) {
            $data4->observation_id = serialize($request->observation_id);
        }
        if (!empty($request->date)) {
            $data4->date = serialize($request->date);
        }
        if (!empty($request->auditorG)) {
            $data4->auditor = serialize($request->auditorG);
        }
        if (!empty($request->auditeeG)) {
            $data4->auditee = serialize($request->auditeeG);
        }
        if (!empty($request->observation_description)) {
            $data4->observation_description = serialize($request->observation_description);
        }
        if (!empty($request->severity_level)) {
            $data4->severity_level = serialize($request->severity_level);
        }
        if (!empty($request->area)) {
            $data4->area = serialize($request->area);
        }
        if (!empty($request->observation_category)) {
            $data4->observation_category = serialize($request->observation_category);
        }
         if (!empty($request->capa_required)) {
            $data4->capa_required = serialize($request->capa_required);
        }
         if (!empty($request->auditee_response)) {
            $data4->auditee_response = serialize($request->auditee_response);
        }
        if (!empty($request->auditor_review_on_response)) {
            $data4->auditor_review_on_response = serialize($request->auditor_review_on_response);
        }
        if (!empty($request->qa_comment)) {
            $data4->qa_comment = serialize($request->qa_comment);
        }
        if (!empty($request->capa_details)) {
            $data4->capa_details = serialize($request->capa_details);
        }
        if (!empty($request->capa_due_date)) {
            $data4->capa_due_date = serialize($request->capa_due_date);
        }
        if (!empty($request->capa_owner)) {
            $data4->capa_owner = serialize($request->capa_owner);
        }
        if (!empty($request->action_taken)) {
            $data4->action_taken = serialize($request->action_taken);
        }
        if (!empty($request->capa_completion_date)) {
            $data4->capa_completion_date = serialize($request->capa_completion_date);
        }
        if (!empty($request->status_Observation)) {
            $data4->status = serialize($request->status_Observation);
        }
        if (!empty($request->remark_observation)) {
            $data4->remark = serialize($request->remark_observation);
        }
        $data4->update();
        if ($lastDocument->Date != $internalAudit->Date || !empty($request->Date_comment)) {

            $history = new AuditTrialExternal();
            $history->ExternalAudit_id = $id;
            $history->activity_type = 'Date of Initiator';
            $history->previous = $lastDocument->Date;
            $history->current = $internalAudit->Date;
            $history->comment = $request->date_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->multiple_assignee_to != $internalAudit->multiple_assignee_to || !empty($request->multiple_assignee_to_comment)) {

            $history = new AuditTrialExternal();
            $history->ExternalAudit_id = $id;
            $history->activity_type = 'Assigned to';
            $history->previous = Helpers::getInitiatorName($lastDocument->multiple_assignee_to);
            $history->current = Helpers::getInitiatorName($internalAudit->multiple_assignee_to);
            $history->comment = $request->date_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->Initiator_Group != $internalAudit->Initiator_Group || !empty($request->Initiator_Group_comment)) {

            $history = new AuditTrialExternal();
            $history->ExternalAudit_id = $id;
            $history->activity_type = 'Initiator Group';
            $history->previous = Helpers::getInitiatorGroupFullName($lastDocument->Initiator_Group);
            $history->current =Helpers::getInitiatorGroupFullName ($internalAudit->Initiator_Group);
            $history->comment = $request->date_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->initiator_group_code != $internalAudit->initiator_group_code || !empty($request->initiator_group_code_comment)) {

            $history = new AuditTrialExternal();
            $history->ExternalAudit_id = $id;
            $history->activity_type = 'Initiator Group Code';
            $history->previous = $lastDocument->initiator_group_code;
            $history->current = $internalAudit->initiator_group_code;
            $history->comment = $request->date_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }


        if ($lastDocument->severity_level != $internalAudit->severity_level || !empty($request->severity_level_comment)) {

            $history = new AuditTrialExternal();
            $history->ExternalAudit_id = $id;
            $history->activity_type = 'Severity Level';
            $history->previous = $lastDocument->severity_level;
            $history->current = $internalAudit->severity_level;
            $history->comment = $request->date_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->initiated_through != $internalAudit->initiated_through || !empty($request->initiated_through_comment)) {

            $history = new AuditTrialExternal();
            $history->ExternalAudit_id = $id;
            $history->activity_type = 'Initiated Through';
            $history->previous = $lastDocument->initiated_through;
            $history->current = $internalAudit->initiated_through;
            $history->comment = $request->date_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }


        if ($lastDocument->short_description != $internalAudit->short_description || !empty($request->short_description_comment)) {

            $history = new AuditTrialExternal();
            $history->ExternalAudit_id = $id;
            $history->activity_type = 'Short Description';
            $history->previous = $lastDocument->short_description;
            $history->current = $internalAudit->short_description;
            $history->comment = $request->date_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->Audit_Category != $internalAudit->Audit_Category || !empty($request->Audit_Category_comment)) {

            $history = new AuditTrialExternal();
            $history->ExternalAudit_id = $id;
            $history->activity_type = 'Audit Category';
            $history->previous = $lastDocument->Audit_Category;
            $history->current = $internalAudit->Audit_Category;
            $history->comment = $request->date_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->initiated_if_other != $internalAudit->initiated_if_other || !empty($request->initiated_if_other_comment)) {

            $history = new AuditTrialExternal();
            $history->ExternalAudit_id = $id;
            $history->activity_type = 'Others';
            $history->previous = $lastDocument->initiated_if_other;
            $history->current = $internalAudit->initiated_if_other;
            $history->comment = $request->date_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->external_auditor_name != $internalAudit->external_auditor_name || !empty($request->external_auditor_name_comment)) {

            $history = new AuditTrialExternal();
            $history->ExternalAudit_id = $id;
            $history->activity_type = 'Name of Auditor';
            $history->previous = $lastDocument->external_auditor_name;
            $history->current = $internalAudit->external_auditor_name;
            $history->comment = $request->date_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->area_of_auditing != $internalAudit->area_of_auditing || !empty($request->area_of_auditing_comment)) {

            $history = new AuditTrialExternal();
            $history->ExternalAudit_id = $id;
            $history->activity_type = 'Area of Auditing';
            $history->previous = $lastDocument->area_of_auditing;
            $history->current = $internalAudit->area_of_auditing;
            $history->comment = $request->date_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->audit_type != $internalAudit->audit_type || !empty($request->audit_type_comment)) {

            $history = new AuditTrialExternal();
            $history->ExternalAudit_id = $id;
            $history->activity_type = 'Type of Audit';
            $history->previous = $lastDocument->audit_type;
            $history->current = $internalAudit->audit_type;
            $history->comment = $request->date_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->if_other != $internalAudit->if_other || !empty($request->if_other_comment)) {

            $history = new AuditTrialExternal();
            $history->ExternalAudit_id = $id;
            $history->activity_type = 'If Other';
            $history->previous = $lastDocument->if_other;
            $history->current = $internalAudit->if_other;
            $history->comment = $request->date_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->external_agencies != $internalAudit->external_agencies || !empty($request->external_agencies_comment)) {

            $history = new AuditTrialExternal();
            $history->ExternalAudit_id = $id;
            $history->activity_type = 'External Agencies';
            $history->previous = strtoupper($lastDocument->external_agencies);
            $history->current = strtoupper($internalAudit->external_agencies);
            $history->comment = $request->date_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->others != $internalAudit->others || !empty($request->others_comment)) {

            $history = new AuditTrialExternal();
            $history->ExternalAudit_id = $id;
            $history->activity_type = 'Other';
            $history->previous = $lastDocument->others;
            $history->current = $internalAudit->others;
            $history->comment = $request->date_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->initial_comments != $internalAudit->initial_comments || !empty($request->initial_comments_comment)) {

            $history = new AuditTrialExternal();
            $history->ExternalAudit_id = $id;
            $history->activity_type = 'Description';
            $history->previous = $lastDocument->initial_comments;
            $history->current = $internalAudit->initial_comments;
            $history->comment = $request->date_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->reason_for_audit != $internalAudit->reason_for_audit || !empty($request->reason_for_audit_comment)) {

            $history = new AuditTrialExternal();
            $history->ExternalAudit_id = $id;
            $history->activity_type = 'Reason For Audit';
            $history->previous = $lastDocument->reason_for_audit;
            $history->current = $internalAudit->reason_for_audit;
            $history->comment = $request->date_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        // if ($lastDocument->initial_comments != $internalAudit->initial_comments || !empty($request->initial_comments_comment)) {

        //     $history = new AuditTrialExternal();
        //     $history->ExternalAudit_id = $id;
        //     $history->activity_type = 'Initial Comments';
        //     $history->previous = $lastDocument->initial_comments;
        //     $history->current = $internalAudit->initial_comments;
        //     $history->comment = $request->date_comment;
        //     $history->user_id = Auth::user()->id;
        //     $history->user_name = Auth::user()->name;
        //     $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        //     $history->origin_state = $lastDocument->status;
        //     $history->save();
        // }
        if ($lastDocument->start_date != $internalAudit->start_date || !empty($request->start_date_comment)) {

            $history = new AuditTrialExternal();
            $history->ExternalAudit_id = $id;
            $history->activity_type = 'Audit Schedule Start Date';
            $history->previous = Helpers::getdateFormat($lastDocument->start_date);
            $history->current =  Helpers::getdateFormat($internalAudit->start_date);
            $history->comment = $request->date_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->end_date != $internalAudit->end_date || !empty($request->end_date_comment)) {

            $history = new AuditTrialExternal();
            $history->ExternalAudit_id = $id;
            $history->activity_type = 'Audit Schedule End Date';
            $history->previous =  Helpers::getdateFormat($lastDocument->end_date);
            $history->current =  Helpers::getdateFormat($internalAudit->end_date);
            $history->comment = $request->date_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->audit_agenda != $internalAudit->audit_agenda || !empty($request->audit_agenda_comment)) {

            $history = new AuditTrialExternal();
            $history->ExternalAudit_id = $id;
            $history->activity_type = 'Audit Agenda';
            $history->previous = $lastDocument->audit_agenda;
            $history->current = $internalAudit->audit_agenda;
            $history->comment = $request->date_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        // if ($lastDocument->Facility != $internalAudit->Facility || !empty($request->Facility_comment)) {

        //     $history = new AuditTrialExternal();
        //     $history->ExternalAudit_id = $id;
        //     $history->activity_type = 'Facility Name';
        //     $history->previous = $lastDocument->Facility;
        //     $history->current = $internalAudit->Facility;
        //     $history->comment = $request->date_comment;
        //     $history->user_id = Auth::user()->id;
        //     $history->user_name = Auth::user()->name;
        //     $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        //     $history->origin_state = $lastDocument->status;
        //     $history->save();
        // }
        // if ($lastDocument->Group != $internalAudit->Group || !empty($request->Group_comment)) {

        //     $history = new AuditTrialExternal();
        //     $history->ExternalAudit_id = $id;
        //     $history->activity_type = 'Group Name';
        //     $history->previous = $lastDocument->Group;
        //     $history->current = $internalAudit->Group;
        //     $history->comment = $request->date_comment;
        //     $history->user_id = Auth::user()->id;
        //     $history->user_name = Auth::user()->name;
        //     $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        //     $history->origin_state = $lastDocument->status;
        //     $history->save();
        // }
        if ($lastDocument->material_name != $internalAudit->material_name || !empty($request->material_name_comment)) {

            $history = new AuditTrialExternal();
            $history->ExternalAudit_id = $id;
            $history->activity_type = 'Product/Material Name';
            $history->previous = $lastDocument->material_name;
            $history->current = $internalAudit->material_name;
            $history->comment = $request->date_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->if_comments != $internalAudit->if_comments || !empty($request->if_comments_comment)) {

            $history = new AuditTrialExternal();
            $history->ExternalAudit_id = $id;
            $history->activity_type = 'Comments(If Any)';
            $history->previous = $lastDocument->if_comments;
            $history->current = $internalAudit->if_comments;
            $history->comment = $request->date_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->lead_auditor != $internalAudit->lead_auditor || !empty($request->lead_auditor_comment)) {

            $history = new AuditTrialExternal();
            $history->ExternalAudit_id = $id;
            $history->activity_type = 'Lead Auditor';
            $history->previous = $lastDocument->lead_auditor;
            $history->current = $internalAudit->lead_auditor;
            $history->comment = $request->date_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->Audit_team != $internalAudit->Audit_team || !empty($request->Audit_team_comment)) {

            $history = new AuditTrialExternal();
            $history->ExternalAudit_id = $id;
            $history->activity_type = 'Audit Team';
            $history->previous = $lastDocument->Audit_team;
            $history->current = $internalAudit->Audit_team;
            $history->comment = $request->date_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->Auditee != $internalAudit->Auditee || !empty($request->Auditee_comment)) {
            // Convert the Auditee IDs to names
            $auditeeIdsArray = explode(',', $internalAudit->Auditee);
            $auditeeNames = User::whereIn('id', $auditeeIdsArray)->pluck('name')->toArray();
            $auditeeNamesString = implode(', ', $auditeeNames);

            // For the lastDocument, retrieve its auditee names
            $lastDocumentAuditeeIdsArray = explode(',', $lastDocument->Auditee);
            $lastDocumentAuditeeNames = User::whereIn('id', $lastDocumentAuditeeIdsArray)->pluck('name')->toArray();
            $lastDocumentAuditeeNamesString = implode(', ', $lastDocumentAuditeeNames);

            $history = new AuditTrialExternal();
            $history->ExternalAudit_id = $id;
            $history->activity_type = 'Auditee';
            $history->previous = $lastDocumentAuditeeNamesString;
            $history->current = $auditeeNamesString;
            $history->comment = $request->date_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }

        if ($lastDocument->Auditor_Details != $internalAudit->Auditor_Details || !empty($request->Auditor_Details_comment)) {

            $history = new AuditTrialExternal();
            $history->ExternalAudit_id = $id;
            $history->activity_type = 'External Auditor Details';
            $history->previous = $lastDocument->Auditor_Details;
            $history->current = $internalAudit->Auditor_Details;
            $history->comment = $request->date_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->External_Auditing_Agency != $internalAudit->External_Auditing_Agency || !empty($request->External_Auditing_Agency_comment)) {

            $history = new AuditTrialExternal();
            $history->ExternalAudit_id = $id;
            $history->activity_type = 'External Auditing Agency';
            $history->previous = $lastDocument->External_Auditing_Agency;
            $history->current = $internalAudit->External_Auditing_Agency;
            $history->comment = $request->date_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->Relevant_Guidelines != $internalAudit->Relevant_Guidelines || !empty($request->Relevant_Guidelines_comment)) {

            $history = new AuditTrialExternal();
            $history->ExternalAudit_id = $id;
            $history->activity_type = 'Relevant Guidelines / Industry Standards';
            $history->previous = $lastDocument->Relevant_Guidelines;
            $history->current = $internalAudit->Relevant_Guidelines;
            $history->comment = $request->date_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->Supplier_Details != $internalAudit->Supplier_Details || !empty($request->Supplier_Details_comment)) {

            $history = new AuditTrialExternal();
            $history->ExternalAudit_id = $id;
            $history->activity_type = 'Supplier/Vendor/Manufacturer Details';
            $history->previous = $lastDocument->Supplier_Details;
            $history->current = $internalAudit->Supplier_Details;
            $history->comment = $request->date_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->Supplier_Site != $internalAudit->Supplier_Site || !empty($request->Supplier_Site_comment)) {

            $history = new AuditTrialExternal();
            $history->ExternalAudit_id = $id;
            $history->activity_type = 'Supplier/Vendor/Manufacturer Site';
            $history->previous = $lastDocument->Supplier_Site;
            $history->current = $internalAudit->Supplier_Site;
            $history->comment = $request->date_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->QA_Comments != $internalAudit->QA_Comments || !empty($request->QA_Comments_comment)) {

            $history = new AuditTrialExternal();
            $history->ExternalAudit_id = $id;
            $history->activity_type = 'QA Comments';
            $history->previous = $lastDocument->QA_Comments;
            $history->current = $internalAudit->QA_Comments;
            $history->comment = $request->date_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->file_attachment_guideline != $internalAudit->file_attachment_guideline || !empty($request->file_attachment_guideline_comment)) {

            $history = new AuditTrialExternal();
            $history->ExternalAudit_id = $id;
            $history->activity_type = 'Guideline Attachment';
            $history->previous = $lastDocument->file_attachment_guideline;
            $history->current = $internalAudit->file_attachment_guideline;
            $history->comment = $request->date_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->Comments != $internalAudit->Comments || !empty($request->Comments_comment)) {

            $history = new AuditTrialExternal();
            $history->ExternalAudit_id = $id;
            $history->activity_type = 'Comments';
            $history->previous = $lastDocument->Comments;
            $history->current = $internalAudit->Comments;
            $history->comment = $request->date_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->due_date_extension != $internalAudit->due_date_extension || !empty($request->due_date_extension_comment)) {

            $history = new AuditTrialExternal();
            $history->ExternalAudit_id = $id;
            $history->activity_type = 'Due Date Extension Justification';
            $history->previous = $lastDocument->due_date_extension;
            $history->current = $internalAudit->due_date_extension;
            $history->comment = $request->date_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->Audit_Comments1 != $internalAudit->Audit_Comments1 || !empty($request->Audit_Comments1_comment)) {

            $history = new AuditTrialExternal();
            $history->ExternalAudit_id = $id;
            $history->activity_type = 'Audit Comments';
            $history->previous = $lastDocument->Audit_Comments1;
            $history->current = $internalAudit->Audit_Comments1;
            $history->comment = $request->date_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->Remarks != $internalAudit->Remarks || !empty($request->Remarks_comment)) {

            $history = new AuditTrialExternal();
            $history->ExternalAudit_id = $id;
            $history->activity_type = 'Remarks';
            $history->previous = $lastDocument->Remarks;
            $history->current = $internalAudit->Remarks;
            $history->comment = $request->date_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->Reference_Recores1 != $internalAudit->Reference_Recores1 || !empty($request->Reference_Recores1_comment)) {

            $history = new AuditTrialExternal();
            $history->ExternalAudit_id = $id;
            $history->activity_type = 'Reference Record';
            $history->previous = str_replace(',', ', ', $lastDocument->Reference_Recores1);
            $history->current = str_replace(',', ', ', $internalAudit->Reference_Recores1);
            $history->comment = $request->date_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->Reference_Recores2 != $internalAudit->Reference_Recores2 || !empty($request->Reference_Recores2_comment)) {

            $history = new AuditTrialExternal();
            $history->ExternalAudit_id = $id;
            $history->activity_type = 'Reference Record';
            $history->previous = $lastDocument->Reference_Recores2;
            $history->current = $internalAudit->Reference_Recores2;
            $history->comment = $request->date_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->Audit_Comments2 != $internalAudit->Audit_Comments2 || !empty($request->Audit_Comments2_comment)) {

            $history = new AuditTrialExternal();
            $history->ExternalAudit_id = $id;
            $history->activity_type = 'Audit Comment';
            $history->previous = $lastDocument->Audit_Comments2;
            $history->current = $internalAudit->Audit_Comments2;
            $history->comment = $request->date_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->inv_attachment != $internalAudit->inv_attachment || !empty($request->inv_attachment_comment)) {

            $history = new AuditTrialExternal();
            $history->ExternalAudit_id = $id;
            $history->activity_type = 'Initial Attachment';
            $history->previous = $lastDocument->inv_attachment;
            $history->current = $internalAudit->inv_attachment;
            $history->comment = $request->date_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->file_attachment != $internalAudit->file_attachment || !empty($request->file_attachment_comment)) {

            $history = new AuditTrialExternal();
            $history->ExternalAudit_id = $id;
            $history->activity_type = 'File Attachment';
            $history->previous = $lastDocument->file_attachment;
            $history->current = $internalAudit->file_attachment;
            $history->comment = $request->date_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->Audit_file != $internalAudit->Audit_file || !empty($request->Audit_file_comment)) {

            $history = new AuditTrialExternal();
            $history->ExternalAudit_id = $id;
            $history->activity_type = 'Audit Attachments';
            $history->previous = $lastDocument->Audit_file;
            $history->current = $internalAudit->Audit_file;
            $history->comment = $request->date_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->report_file != $internalAudit->report_file || !empty($request->report_file_comment)) {

            $history = new AuditTrialExternal();
            $history->ExternalAudit_id = $id;
            $history->activity_type = 'Report Attachments';
            $history->previous = $lastDocument->report_file;
            $history->current = $internalAudit->report_file;
            $history->comment = $request->date_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->myfile != $internalAudit->myfile || !empty($request->myfile_comment)) {

            $history = new AuditTrialExternal();
            $history->ExternalAudit_id = $id;
            $history->activity_type = 'Audit Attachment';
            $history->previous = $lastDocument->myfile;
            $history->current = $internalAudit->myfile;
            $history->comment = $request->date_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        // if ($lastDocument->myfile != $internalAudit->myfile || !empty($request->myfile_comment)) {

        //     $history = new AuditTrialExternal();
        //     $history->ExternalAudit_id = $id;
        //     $history->activity_type = 'Inv Attachment';
        //     $history->previous = $lastDocument->myfile;
        //     $history->current = $internalAudit->myfile;
        //     $history->comment = $request->date_comment;
        //     $history->user_id = Auth::user()->id;
        //     $history->user_name = Auth::user()->name;
        //     $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        //     $history->origin_state = $lastDocument->status;
        //     $history->save();
        // }
        // if ($lastDocument->due_date != $internalAudit->due_date || !empty($request->due_date_comment)) {

        //     $history = new AuditTrialExternal();
        //     $history->ExternalAudit_id = $id;
        //     $history->activity_type = 'Due Date';
        //     $history->previous =Helpers::getdateFormat( $lastDocument->due_date);
        //     $history->current = Helpers::getdateFormat($internalAudit->due_date);
        //     $history->comment = $request->date_comment;
        //     $history->user_id = Auth::user()->id;
        //     $history->user_name = Auth::user()->name;
        //     $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        //     $history->origin_state = $lastDocument->status;
        //     $history->save();
        // }
        if ($lastDocument->audit_start_date != $internalAudit->audit_start_date || !empty($request->audit_start_date_comment)) {

            $history = new AuditTrialExternal();
            $history->ExternalAudit_id = $id;
            $history->activity_type = 'Audit Start Date';
            $history->previous =Helpers::getdateFormat ($lastDocument->audit_start_date);
            $history->current = Helpers::getdateFormat($internalAudit->audit_start_date);
            $history->comment = $request->date_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->audit_end_date != $internalAudit->audit_end_date || !empty($request->audit_end_date_comment)) {

            $history = new AuditTrialExternal();
            $history->ExternalAudit_id = $id;
            $history->activity_type = 'Audit End Date';
            $history->previous = Helpers::getdateFormat($lastDocument->audit_end_date);
            $history->current = Helpers::getdateFormat($internalAudit->audit_end_date);
            $history->comment = $request->date_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }

        DocumentService::update_qms_numbers();

        toastr()->success("Record is Updated Successfully");
        return back();
    }


    public function ExternalAuditStateChange(Request $request, $id)
    {
        if ($request->username == Auth::user()->email && Hash::check($request->password, Auth::user()->password)) {
            $changeControl = Auditee::find($id);
            $lastDocument = Auditee::find($id);
            $internalAudit = Auditee::find($id);

            if ($changeControl->stage == 1) {
                $changeControl->stage = "2";
                $changeControl->status = "Audit Preparation";
                $changeControl->audit_schedule_by = Auth::user()->name;
                $changeControl->audit_schedule_on = Carbon::now()->format('d-M-Y');
                        $history = new AuditTrialExternal();
                        $history->ExternalAudit_id = $id;
                        $history->activity_type = 'Activity Log';
                        $history->previous = $lastDocument->status;
                        $history->current = "Audit Preparation";
                        $history->comment = $request->comment;
                        $history->user_id = Auth::user()->id;
                        $history->user_name = Auth::user()->name;
                        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                        $history->origin_state = $lastDocument->status;
                        $history->stage = "Schedule Audit";
                        // dd($history->current);
                        $history->save();
                    //     $list = Helpers::getLeadAuditorUserList();


                    //     foreach ($list as $u) {
                    //         if($u->q_m_s_divisions_id == $changeControl->division_id){
                    //             $email = Helpers::getInitiatorEmail($u->user_id);

                    //              if ($email !== null) {


                    //               Mail::send(
                    //                   'mail.view-mail',
                    //                    ['data' => $changeControl],
                    //                 function ($message) use ($email) {
                    //                     $message->to($email)
                    //                         ->subject("Document sent ".Auth::user()->name);
                    //                 }
                    //               );
                    //             }
                    //      }
                    //   }


                $list = Helpers::getLeadAuditorUserList($changeControl->division_id);
                        $userIds = collect($list)->pluck('user_id')->toArray();
                        $users = User::whereIn('id', $userIds)->select('id', 'name', 'email')->get();
                        $userIdNew = $users->pluck('id')->implode(',');
                        $userId = $users->pluck('name')->implode(',');
                        if($userId){
                            try {
                                $notification = new AuditTrialExternal();
                                $notification->ExternalAudit_id = $id;
                                $notification->activity_type = "Notification";
                                $notification->action = 'Notification';
                                $notification->comment = "";
                                $notification->user_id = Auth::user()->id;
                                $notification->user_name = Auth::user()->name;
                                $notification->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                                $notification->origin_state = "Not Applicable";
                                $notification->previous = $lastDocument->status;
                                $notification->current = "Audit Preparation";
                                $notification->stage = "";
                                $notification->action_name = "";
                                $notification->mailUserId = $userIdNew;
                                $notification->role_name = "Audit Manager";
                                $notification->save();
                                // dd($history);
                            } catch (\Throwable $e) {
                                \Log::error('Mail failed to send: ' . $e->getMessage());
                            }
                        }
                // foreach ($list as $u) {
                //     $email = Helpers::getAllUserEmail($u->user_id);
                //     if (!empty($email)) {
                //         try {
                //             info('Sending mail to', [$email]);
                //             Mail::send(
                //                 'mail.view-mail',
                //                 ['data' => $changeControl,'site'=>'External Audit','history' => 'Schedule Audit', 'process' => 'External Audit', 'comment' => $history->comment,'user'=> Auth::user()->name],
                //                 function ($message) use ($email, $changeControl) {
                //                  $message->to($email)
                //                  ->subject("QMS Notification: External Audit , Record #" . str_pad($changeControl->record, 4, '0', STR_PAD_LEFT) . " - Activity: Schedule Audit Performed"); }
                //                 );

                //         } catch (\Exception $e) {
                //             \Log::error('Mail failed to send: ' . $e->getMessage());
                //         }
                //     }
                // }

                foreach ($list as $u) {
                    try {
                        $email = Helpers::getAllUserEmail($u->user_id);
                        if ($email !== null) {
                            $data = ['data' => $changeControl,'site'=>'External Audit','history' => 'Schedule Audit', 'process' => 'External Audit', 'comment' => $history->comment,'user'=> Auth::user()->name];

                            SendMail::dispatch($data, $email, $changeControl, 'External Audit');
                        }
                    } catch (\Exception $e) {
                        \Log::error('Mail sending failed for user_id: ' . $u->user_id . ' - Error: ' . $e->getMessage());
                        continue;
                    }
                }

                $changeControl->update();
                toastr()->success('Document Sent');
                return back();
            }
            if ($changeControl->stage == 2) {
                $changeControl->stage = "3";
                $changeControl->status = "Pending Audit";
                $changeControl->audit_preparation_completed_by = Auth::user()->name;
                $changeControl->audit_preparation_completed_on = Carbon::now()->format('d-M-Y');
                        $history = new AuditTrialExternal();
                        $history->ExternalAudit_id = $id;
                        $history->activity_type = 'Activity Log';
                        $history->previous = $lastDocument->status;
                        $history->current = "Pending Audit";
                        $history->comment = $request->comment;
                        $history->user_id = Auth::user()->id;
                        $history->user_name = Auth::user()->name;
                        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                        $history->origin_state = $lastDocument->status;
                        $history->stage = "Complete Audit Preparation";
                        $history->save();
                    //     $list = Helpers::getAuditManagerUserList();
                    //     foreach ($list as $u) {
                    //         if($u->q_m_s_divisions_id == $changeControl->division_id){
                    //             $email = Helpers::getInitiatorEmail($u->user_id);
                    //              if ($email !== null) {

                    //               Mail::send(
                    //                   'mail.view-mail',
                    //                    ['data' => $changeControl],
                    //                 function ($message) use ($email) {
                    //                     $message->to($email)
                    //                         ->subject("Document sent ".Auth::user()->name);
                    //                 }
                    //               );
                    //             }
                    //      }
                    //   }

                $list = Helpers::getAuditManagerUserList($changeControl->division_id);
                        $userIds = collect($list)->pluck('user_id')->toArray();
                        $users = User::whereIn('id', $userIds)->select('id', 'name', 'email')->get();
                        $userIdNew = $users->pluck('id')->implode(',');
                        $userId = $users->pluck('name')->implode(',');
                        if($userId){
                            try {
                                $notification = new AuditTrialExternal();
                                $notification->ExternalAudit_id = $id;
                                $notification->activity_type = "Notification";
                                $notification->action = 'Notification';
                                $notification->comment = "";
                                $notification->user_id = Auth::user()->id;
                                $notification->user_name = Auth::user()->name;
                                $notification->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                                $notification->origin_state = "Not Applicable";
                                $notification->previous = $lastDocument->status;
                                $notification->current = "Pending Audit";
                                $notification->stage = "";
                                $notification->action_name = "";
                                $notification->mailUserId = $userIdNew;
                                $notification->role_name = "Lead Auditor";
                                $notification->save();
                                // dd($history);
                            } catch (\Throwable $e) {
                                \Log::error('Mail failed to send: ' . $e->getMessage());
                            }
                        }
                // foreach ($list as $u) {
                //     $email = Helpers::getAllUserEmail($u->user_id);
                //     if (!empty($email)) {
                //         try {
                //             info('Sending mail to', [$email]);
                //             Mail::send(
                //                 'mail.view-mail',
                //                 ['data' => $changeControl,'site'=>'External Audit','history' => 'Complete Audit Preparation', 'process' => 'External Audit', 'comment' => $history->comment,'user'=> Auth::user()->name],
                //                 function ($message) use ($email, $changeControl) {
                //                  $message->to($email)
                //                  ->subject("QMS Notification: External Audit , Record #" . str_pad($changeControl->record, 4, '0', STR_PAD_LEFT) . " - Activity: Complete Audit Preparation Performed"); }
                //                 );

                //         } catch (\Exception $e) {
                //             \Log::error('Mail failed to send: ' . $e->getMessage());
                //         }
                //     }
                // }

                foreach ($list as $u) {
                    try {
                        $email = Helpers::getAllUserEmail($u->user_id);
                        if ($email !== null) {
                            $data = ['data' => $changeControl,'site'=>'External Audit','history' => 'Complete Audit Preparation', 'process' => 'External Audit', 'comment' => $history->comment,'user'=> Auth::user()->name];

                            SendMail::dispatch($data, $email, $changeControl, 'External Audit');
                        }
                    } catch (\Exception $e) {
                        \Log::error('Mail sending failed for user_id: ' . $u->user_id . ' - Error: ' . $e->getMessage());
                        continue;
                    }
                }
                $changeControl->update();
                toastr()->success('Document Sent');
                return back();
            }
            if ($changeControl->stage == 3) {
                $changeControl->stage = "4";
                $changeControl->status = "Pending Response";
                $changeControl->audit_mgr_more_info_reqd_by = Auth::user()->name;
                $changeControl->audit_mgr_more_info_reqd_on = Carbon::now()->format('d-M-Y');
                        $history = new AuditTrialExternal();
                        $history->ExternalAudit_id = $id;
                        $history->activity_type = 'Activity Log';
                        $history->previous = $lastDocument->status;
                        $history->current = "Pending Response";
                        $history->comment = $request->comment;
                        $history->user_id = Auth::user()->id;
                        $history->user_name = Auth::user()->name;
                        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                        $history->origin_state = $lastDocument->status;
                        $history->stage = "Issue Report";
                        $history->save();
                    //     $list = Helpers::getLeadAuditeeUserList();
                    //     foreach ($list as $u) {
                    //         if($u->q_m_s_divisions_id == $changeControl->division_id){
                    //             $email = Helpers::getInitiatorEmail($u->user_id);
                    //              if ($email !== null) {

                    //               Mail::send(
                    //                   'mail.view-mail',
                    //                    ['data' => $changeControl],
                    //                 function ($message) use ($email) {
                    //                     $message->to($email)
                    //                         ->subject("Document sent ".Auth::user()->name);
                    //                 }
                    //               );
                    //             }
                    //      }
                    //   }
                    $list = Helpers::getLeadAuditeeUserList($changeControl->division_id);
                        $userIds = collect($list)->pluck('user_id')->toArray();
                        $users = User::whereIn('id', $userIds)->select('id', 'name', 'email')->get();
                        $userIdNew = $users->pluck('id')->implode(',');
                        $userId = $users->pluck('name')->implode(',');
                        if($userId){
                            try {
                                $notification = new AuditTrialExternal();
                                $notification->ExternalAudit_id = $id;
                                $notification->activity_type = "Notification";
                                $notification->action = 'Notification';
                                $notification->comment = "";
                                $notification->user_id = Auth::user()->id;
                                $notification->user_name = Auth::user()->name;
                                $notification->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                                $notification->origin_state = "Not Applicable";
                                $notification->previous = $lastDocument->status;
                                $notification->current = "Pending Response";
                                $notification->stage = "";
                                $notification->action_name = "";
                                $notification->mailUserId = $userIdNew;
                                $notification->role_name = "Lead Auditor";
                                $notification->save();
                                // dd($history);
                            } catch (\Throwable $e) {
                                \Log::error('Mail failed to send: ' . $e->getMessage());
                            }
                        }
                    // foreach ($list as $u) {
                    //     $email = Helpers::getAllUserEmail($u->user_id);
                    //     if (!empty($email)) {
                    //         try {
                    //             info('Sending mail to', [$email]);
                    //             Mail::send(
                    //                 'mail.view-mail',
                    //                 ['data' => $changeControl,'site'=>'External Audit','history' => 'Issue Report', 'process' => 'External Audit', 'comment' => $history->comment,'user'=> Auth::user()->name],
                    //                 function ($message) use ($email, $changeControl) {
                    //                  $message->to($email)
                    //                  ->subject("QMS Notification: External Audit , Record #" . str_pad($changeControl->record, 4, '0', STR_PAD_LEFT) . " - Activity: Issue Report Performed"); }
                    //                 );

                    //         } catch (\Exception $e) {
                    //             \Log::error('Mail failed to send: ' . $e->getMessage());
                    //         }
                    //     }
                    // }

                    foreach ($list as $u) {
                        try {
                            $email = Helpers::getAllUserEmail($u->user_id);
                            if ($email !== null) {
                                $data = ['data' => $changeControl,'site'=>'External Audit','history' => 'Issue Report', 'process' => 'External Audit', 'comment' => $history->comment,'user'=> Auth::user()->name];

                                SendMail::dispatch($data, $email, $changeControl, 'External Audit');
                            }
                        } catch (\Exception $e) {
                            \Log::error('Mail sending failed for user_id: ' . $u->user_id . ' - Error: ' . $e->getMessage());
                            continue;
                        }
                    }
                $changeControl->update();
                toastr()->success('Document Sent');
                return back();
            }
            if ($changeControl->stage == 4) {
                $changeControl->stage = "5";
                $changeControl->status = "CAPA Execution in Progress";
                $changeControl->audit_observation_submitted_by = Auth::user()->name;
                $changeControl->audit_observation_submitted_on = Carbon::now()->format('d-M-Y');
                        $history = new AuditTrialExternal();
                        $history->ExternalAudit_id = $id;
                        $history->activity_type = 'Activity Log';
                        $history->previous = $lastDocument->status;
                        $history->current = "CAPA Execution in Progress";
                        $history->comment = $request->comment;
                        $history->user_id = Auth::user()->id;
                        $history->user_name = Auth::user()->name;
                        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                        $history->origin_state = $lastDocument->status;
                        $history->stage = "CAPA Plan Proposed";
                        $history->save();
                $changeControl->update();
                toastr()->success('Document Sent');
                return back();
            }

            if ($changeControl->stage == 5) {
                $changeControl->stage = "6";
                $changeControl->status = "Closed - Done";
                $changeControl->audit_lead_more_info_reqd_by = Auth::user()->name;
                $changeControl->audit_lead_more_info_reqd_on = Carbon::now()->format('d-M-Y');
                // $changeControl->audit_response_completed_by = Auth::user()->name;
                // $changeControl->audit_response_completed_on = Carbon::now()->format('d-M-Y');
                // $changeControl->response_feedback_verified_by = Auth::user()->name;
                // $changeControl->response_feedback_verified_on = Carbon::now()->format('d-M-Y');
                $history = new AuditTrialExternal();
                        $history->ExternalAudit_id = $id;
                        $history->activity_type = 'Activity Log';
                        $history->previous = $lastDocument->status;
                        $history->current = "Closed - Done";
                        $history->comment = $request->comment;
                        $history->user_id = Auth::user()->id;
                        $history->user_name = Auth::user()->name;
                        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                        $history->origin_state = $lastDocument->status;
                        $history->stage = "All CAPA Closed";
                        $history->save();
                $changeControl->update();
                toastr()->success('Document Sent');
                return back();
            }
        } else {
            toastr()->error('E-signature Not match');
            return back();
        }
    }

    public function RejectStateChange(Request $request, $id)
    {
        if ($request->username == Auth::user()->email && Hash::check($request->password, Auth::user()->password)) {
            $changeControl = Auditee::find($id);
            $lastDocument = Auditee::find($id);
            $internalAudit = Auditee::find($id);

            if ($changeControl->stage == 4) {
                $changeControl->stage = "6";
                $changeControl->status = "Closed - Done";
                $changeControl->audit_response_completed_by = Auth::user()->name;
                $changeControl->audit_response_completed_on = Carbon::now()->format('d-M-Y');
                $history = new AuditTrialExternal();
                        $history->ExternalAudit_id = $id;
                        $history->activity_type = 'Activity Log';
                        $history->previous = $lastDocument->status;
                        $history->current = "Closed - Done";
                        $history->comment = $request->comment;
                        $history->user_id = Auth::user()->id;
                        $history->user_name = Auth::user()->name;
                        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                        $history->origin_state = $lastDocument->status;
                        $history->stage = "No CAPAs Required";
                        $history->save();
                $changeControl->update();
                $history = new AuditeeHistory();
                $history->type = "External Audit";
                $history->doc_id = $id;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->stage_id = $changeControl->stage;
                $history->status = $changeControl->status;
                $history->save();
                toastr()->success('Document Sent');
                return back();
            }
            if ($changeControl->stage == 2) {
                $changeControl->stage = "1";
                $changeControl->status = "Opened";
                $changeControl->rejected_by = Auth::user()->name;
                $changeControl->rejected_on = Carbon::now()->format('d-M-Y');
                        $history = new AuditTrialExternal();
                        $history->ExternalAudit_id = $id;
                        $history->activity_type = 'Activity Log';
                        $history->previous = $lastDocument->status;
                        $history->current = "Opened";
                        $history->comment = $request->comment;
                        $history->user_id = Auth::user()->id;
                        $history->user_name = Auth::user()->name;
                        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                        $history->origin_state = $lastDocument->status;
                        $history->stage = "Rejected";
                        $history->save();
                    //     $list = Helpers::getAuditManagerUserList();
                    //     foreach ($list as $u) {
                    //         if($u->q_m_s_divisions_id == $changeControl->division_id){
                    //             $email = Helpers::getInitiatorEmail($u->user_id);
                    //              if ($email !== null) {

                    //               Mail::send(
                    //                   'mail.view-mail',
                    //                    ['data' => $changeControl],
                    //                 function ($message) use ($email) {
                    //                     $message->to($email)
                    //                         ->subject("Document is Rejected ".Auth::user()->name);
                    //                 }
                    //               );
                    //             }
                    //      }
                    //   }

                    $list = Helpers::getAuditManagerUserList($changeControl->division_id);
                        $userIds = collect($list)->pluck('user_id')->toArray();
                        $users = User::whereIn('id', $userIds)->select('id', 'name', 'email')->get();
                        $userIdNew = $users->pluck('id')->implode(',');
                        $userId = $users->pluck('name')->implode(',');
                        if($userId){
                            try {
                                $notification = new AuditTrialExternal();
                                $notification->ExternalAudit_id = $id;
                                $notification->activity_type = "Notification";
                                $notification->action = 'Notification';
                                $notification->comment = "";
                                $notification->user_id = Auth::user()->id;
                                $notification->user_name = Auth::user()->name;
                                $notification->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                                $notification->origin_state = "Not Applicable";
                                $notification->previous = $lastDocument->status;
                                $notification->current = "Opened";
                                $notification->stage = "";
                                $notification->action_name = "";
                                $notification->mailUserId = $userIdNew;
                                $notification->role_name = "Lead Auditor";
                                $notification->save();
                                // dd($history);
                            } catch (\Throwable $e) {
                                \Log::error('Mail failed to send: ' . $e->getMessage());
                            }
                        }
                    // foreach ($list as $u) {
                    //     $email = Helpers::getAllUserEmail($u->user_id);
                    //     if (!empty($email)) {
                    //         try {
                    //             info('Sending mail to', [$email]);
                    //             Mail::send(
                    //                 'mail.view-mail',
                    //                 ['data' => $changeControl,'site'=>'External Audit','history' => 'Rejected', 'process' => 'External Audit', 'comment' => $history->comment,'user'=> Auth::user()->name],
                    //                 function ($message) use ($email, $changeControl) {
                    //                  $message->to($email)
                    //                  ->subject("QMS Notification: External Audit , Record #" . str_pad($changeControl->record, 4, '0', STR_PAD_LEFT) . " - Activity: Rejected Performed"); }
                    //                 );

                    //         } catch (\Exception $e) {
                    //             \Log::error('Mail failed to send: ' . $e->getMessage());
                    //         }
                    //     }
                    // }

                    foreach ($list as $u) {
                        try {
                            $email = Helpers::getAllUserEmail($u->user_id);
                            if ($email !== null) {
                                $data = ['data' => $changeControl,'site'=>'External Audit','history' => 'Rejected', 'process' => 'External Audit', 'comment' => $history->comment,'user'=> Auth::user()->name];

                                SendMail::dispatch($data, $email, $changeControl, 'External Audit');
                            }
                        } catch (\Exception $e) {
                            \Log::error('Mail sending failed for user_id: ' . $u->user_id . ' - Error: ' . $e->getMessage());
                            continue;
                        }
                    }

                $changeControl->update();
                $history = new AuditeeHistory();
                $history->type = "External Audit";
                $history->doc_id = $id;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->stage_id = $changeControl->stage;
                $history->status = $changeControl->status;
                $history->save();
                toastr()->success('Document Sent');
                return back();
            }
            if ($changeControl->stage == 3) {
                $changeControl->stage = "1";
                $changeControl->status = "Opened";
                $changeControl->rejected_by = Auth::user()->name;
                $changeControl->rejected_on = Carbon::now()->format('d-M-Y');
                        $history = new AuditTrialExternal();
                        $history->ExternalAudit_id = $id;
                        $history->activity_type = 'Activity Log';
                        $history->previous = $lastDocument->status;
                        $history->current = "Opened";
                        $history->comment = $request->comment;
                        $history->user_id = Auth::user()->id;
                        $history->user_name = Auth::user()->name;
                        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                        $history->origin_state = $lastDocument->status;
                        $history->stage = "Rejected";
                        $history->save();

                        $list = Helpers::getAuditManagerUserList($changeControl->division_id);
                        $userIds = collect($list)->pluck('user_id')->toArray();
                        $users = User::whereIn('id', $userIds)->select('id', 'name', 'email')->get();
                        $userIdNew = $users->pluck('id')->implode(',');
                        $userId = $users->pluck('name')->implode(',');
                        if($userId){
                            try {
                                $notification = new AuditTrialExternal();
                                $notification->ExternalAudit_id = $id;
                                $notification->activity_type = "Notification";
                                $notification->action = 'Notification';
                                $notification->comment = "";
                                $notification->user_id = Auth::user()->id;
                                $notification->user_name = Auth::user()->name;
                                $notification->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                                $notification->origin_state = "Not Applicable";
                                $notification->previous = $lastDocument->status;
                                $notification->current = "Opened";
                                $notification->stage = "";
                                $notification->action_name = "";
                                $notification->mailUserId = $userIdNew;
                                $notification->role_name = "Lead Auditor";
                                $notification->save();
                                // dd($history);
                            } catch (\Throwable $e) {
                                \Log::error('Mail failed to send: ' . $e->getMessage());
                            }
                        }

                        // foreach ($list as $u) {
                        //     $email = Helpers::getAllUserEmail($u->user_id);
                        //     if (!empty($email)) {
                        //         try {
                        //             info('Sending mail to', [$email]);
                        //             Mail::send(
                        //                 'mail.view-mail',
                        //                 ['data' => $changeControl,'site'=>'External Audit','history' => 'Rejected', 'process' => 'External Audit', 'comment' => $history->comment,'user'=> Auth::user()->name],
                        //                 function ($message) use ($email, $changeControl) {
                        //                  $message->to($email)
                        //                  ->subject("QMS Notification: External Audit , Record #" . str_pad($changeControl->record, 4, '0', STR_PAD_LEFT) . " - Activity: Rejected Performed"); }
                        //                 );

                        //         } catch (\Exception $e) {
                        //             \Log::error('Mail failed to send: ' . $e->getMessage());
                        //         }
                        //     }
                        // }

                        foreach ($list as $u) {
                            try {
                                $email = Helpers::getAllUserEmail($u->user_id);
                                if ($email !== null) {
                                    $data = ['data' => $changeControl,'site'=>'External Audit','history' => 'Rejected', 'process' => 'External Audit', 'comment' => $history->comment,'user'=> Auth::user()->name];

                                    SendMail::dispatch($data, $email, $changeControl, 'External Audit');
                                }
                            } catch (\Exception $e) {
                                \Log::error('Mail sending failed for user_id: ' . $u->user_id . ' - Error: ' . $e->getMessage());
                                continue;
                            }
                        }

                $changeControl->update();
                $history = new AuditeeHistory();
                $history->type = "External Audit";
                $history->doc_id = $id;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->stage_id = $changeControl->stage;
                $history->status = $changeControl->status;
                $history->save();
                toastr()->success('Document Sent');
                return back();
            }
        } else {
            toastr()->error('E-signature Not match');
            return back();
        }
    }

    public function externalAuditCancel(Request $request, $id)
    {
        if ($request->username == Auth::user()->email && Hash::check($request->password, Auth::user()->password)) {
            $changeControl = Auditee::find($id);
            $lastDocument = Auditee::find($id);
            $internalAudit = Auditee::find($id);

            if ($changeControl->stage == 1) {
                $changeControl->stage = "0";
                $changeControl->status = "Closed-Cancelled";
                $changeControl->cancelled_by = Auth::user()->name;
                $changeControl->cancelled_on = Carbon::now()->format('d-M-Y');
                        $history = new AuditTrialExternal();
                        $history->ExternalAudit_id = $id;
                        $history->activity_type = 'Activity Log';
                        $history->previous = $lastDocument->status;
                        $history->current = "Closed-Cancelled";
                        $history->comment = $request->comment;
                        $history->user_id = Auth::user()->id;
                        $history->user_name = Auth::user()->name;
                        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                        $history->origin_state = $lastDocument->status;
                        $history->stage = "Cancelled";
                        $history->save();

                        $list = Helpers::getLeadAuditorUserList($changeControl->division_id);
                        $userIds = collect($list)->pluck('user_id')->toArray();
                        $users = User::whereIn('id', $userIds)->select('id', 'name', 'email')->get();
                        $userIdNew = $users->pluck('id')->implode(',');
                        $userId = $users->pluck('name')->implode(',');
                        if($userId){
                            try {
                                $notification = new AuditTrialExternal();
                                $notification->ExternalAudit_id = $id;
                                $notification->activity_type = "Notification";
                                $notification->action = 'Notification';
                                $notification->comment = "";
                                $notification->user_id = Auth::user()->id;
                                $notification->user_name = Auth::user()->name;
                                $notification->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                                $notification->origin_state = "Not Applicable";
                                $notification->previous = $lastDocument->status;
                                $notification->current = "Closed-Cancelled";
                                $notification->stage = "";
                                $notification->action_name = "";
                                $notification->mailUserId = $userIdNew;
                                $notification->role_name = "Audit Manager";
                                $notification->save();
                                // dd($history);
                            } catch (\Throwable $e) {
                                \Log::error('Mail failed to send: ' . $e->getMessage());
                            }
                        }

                        // foreach ($list as $u) {
                        //     $email = Helpers::getAllUserEmail($u->user_id);
                        //     if (!empty($email)) {
                        //         try {
                        //             info('Sending mail to', [$email]);
                        //             Mail::send(
                        //                 'mail.view-mail',
                        //                 ['data' => $changeControl,'site'=>'External Audit','history' => 'Cancelled', 'process' => 'External Audit', 'comment' => $history->comment,'user'=> Auth::user()->name],
                        //                 function ($message) use ($email, $changeControl) {
                        //                  $message->to($email)
                        //                  ->subject("QMS Notification: External Audit , Record #" . str_pad($changeControl->record, 4, '0', STR_PAD_LEFT) . " - Activity: Cancelled Performed"); }
                        //                 );

                        //         } catch (\Exception $e) {
                        //             \Log::error('Mail failed to send: ' . $e->getMessage());
                        //         }
                        //     }
                        // }

                        foreach ($list as $u) {
                            try {
                                $email = Helpers::getAllUserEmail($u->user_id);
                                if ($email !== null) {
                                    $data = ['data' => $changeControl,'site'=>'External Audit','history' => 'Cancelled', 'process' => 'External Audit', 'comment' => $history->comment,'user'=> Auth::user()->name];

                                    SendMail::dispatch($data, $email, $changeControl, 'External Audit');
                                }
                            } catch (\Exception $e) {
                                \Log::error('Mail sending failed for user_id: ' . $u->user_id . ' - Error: ' . $e->getMessage());
                                continue;
                            }
                        }

                $changeControl->update();
                $history = new AuditeeHistory();
                $history->type = "External Audit";
                $history->doc_id = $id;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->stage_id = $changeControl->stage;
                $history->status = $changeControl->status;
                $history->save();
                toastr()->success('Document Sent');
                return back();
            }
            if ($changeControl->stage == 2) {
                $changeControl->stage = "0";
                $changeControl->status = "Closed-Cancelled";
                $changeControl->cancelled_by = Auth::user()->name;
                $changeControl->cancelled_on = Carbon::now()->format('d-M-Y');
                $history = new AuditTrialExternal();
                $history->ExternalAudit_id = $id;
                $history->activity_type = 'Activity Log';
                $history->previous = $lastDocument->status;
                $history->current = "Closed-Cancelled";
                $history->comment = $request->comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                $history->stage = "Cancelled";
                $history->save();

                 $list = Helpers::getLeadAuditorUserList($changeControl->division_id);
                        $userIds = collect($list)->pluck('user_id')->toArray();
                        $users = User::whereIn('id', $userIds)->select('id', 'name', 'email')->get();
                        $userIdNew = $users->pluck('id')->implode(',');
                        $userId = $users->pluck('name')->implode(',');
                        if($userId){
                            try {
                                $notification = new AuditTrialExternal();
                                $notification->ExternalAudit_id = $id;
                                $notification->activity_type = "Notification";
                                $notification->action = 'Notification';
                                $notification->comment = "";
                                $notification->user_id = Auth::user()->id;
                                $notification->user_name = Auth::user()->name;
                                $notification->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                                $notification->origin_state = "Not Applicable";
                                $notification->previous = $lastDocument->status;
                                $notification->current = "Closed-Cancelled";
                                $notification->stage = "";
                                $notification->action_name = "";
                                $notification->mailUserId = $userIdNew;
                                $notification->role_name = "Lead Auditor";
                                $notification->save();
                                // dd($history);
                            } catch (\Throwable $e) {
                                \Log::error('Mail failed to send: ' . $e->getMessage());
                            }
                        }
                        // foreach ($list as $u) {
                        //     $email = Helpers::getAllUserEmail($u->user_id);
                        //     if (!empty($email)) {
                        //         try {
                        //             info('Sending mail to', [$email]);
                        //             Mail::send(
                        //                 'mail.view-mail',
                        //                 ['data' => $changeControl,'site'=>'External Audit','history' => 'Cancelled', 'process' => 'External Audit', 'comment' =>$history->comment,'user'=> Auth::user()->name],
                        //                 function ($message) use ($email, $changeControl) {
                        //                  $message->to($email)
                        //                  ->subject("QMS Notification: External Audit , Record #" . str_pad($changeControl->record, 4, '0', STR_PAD_LEFT) . " - Activity: Cancelled Performed"); }
                        //                 );

                        //         } catch (\Exception $e) {
                        //             \Log::error('Mail failed to send: ' . $e->getMessage());
                        //         }
                        //     }
                        // }

                        foreach ($list as $u) {
                            try {
                                $email = Helpers::getAllUserEmail($u->user_id);
                                if ($email !== null) {
                                    $data = ['data' => $changeControl,'site'=>'External Audit','history' => 'Cancelled', 'process' => 'External Audit', 'comment' =>$history->comment,'user'=> Auth::user()->name];

                                    SendMail::dispatch($data, $email, $changeControl, 'External Audit');
                                }
                            } catch (\Exception $e) {
                                \Log::error('Mail sending failed for user_id: ' . $u->user_id . ' - Error: ' . $e->getMessage());
                                continue;
                            }
                        }


                $changeControl->update();
                $history = new AuditeeHistory();
                $history->type = "External Audit";
                $history->doc_id = $id;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->stage_id = $changeControl->stage;
                $history->status = $changeControl->status;
                $history->save();
                toastr()->success('Document Sent');
                return back();
            }
            if ($changeControl->stage == 3) {
                $changeControl->stage = "0";
                $changeControl->status = "Closed-Cancelled";
                $changeControl->cancelled_by = Auth::user()->name;
                $changeControl->cancelled_on = Carbon::now()->format('d-M-Y');
                $history = new AuditTrialExternal();
                $history->ExternalAudit_id = $id;
                $history->activity_type = 'Activity Log';
                $history->previous = $lastDocument->status;
                $history->current = "Closed-Cancelled";
                $history->comment = $request->comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                $history->stage = "Cancelled";

                 $list = Helpers::getLeadAuditorUserList($changeControl->division_id);
                        $userIds = collect($list)->pluck('user_id')->toArray();
                        $users = User::whereIn('id', $userIds)->select('id', 'name', 'email')->get();
                        $userIdNew = $users->pluck('id')->implode(',');
                        $userId = $users->pluck('name')->implode(',');
                        if($userId){
                            try {
                                $notification = new AuditTrialExternal();
                                $notification->ExternalAudit_id = $id;
                                $notification->activity_type = "Notification";
                                $notification->action = 'Notification';
                                $notification->comment = "";
                                $notification->user_id = Auth::user()->id;
                                $notification->user_name = Auth::user()->name;
                                $notification->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                                $notification->origin_state = "Not Applicable";
                                $notification->previous = $lastDocument->status;
                                $notification->current = "Closed-Cancelled";
                                $notification->stage = "";
                                $notification->action_name = "";
                                $notification->mailUserId = $userIdNew;
                                $notification->role_name = "Lead Auditor";
                                $notification->save();
                                // dd($history);
                            } catch (\Throwable $e) {
                                \Log::error('Mail failed to send: ' . $e->getMessage());
                            }
                        }
                        // foreach ($list as $u) {
                        //     $email = Helpers::getAllUserEmail($u->user_id);
                        //     if (!empty($email)) {
                        //         try {
                        //             info('Sending mail to', [$email]);
                        //             Mail::send(
                        //                 'mail.view-mail',
                        //                 ['data' => $changeControl,'site'=>'External Audit','history' => 'Cancelled', 'process' => 'External Audit', 'comment' => $history->comment,'user'=> Auth::user()->name],
                        //                 function ($message) use ($email, $changeControl) {
                        //                  $message->to($email)
                        //                  ->subject("QMS Notification: External Audit , Record #" . str_pad($changeControl->record, 4, '0', STR_PAD_LEFT) . " - Activity: Cancelled Performed"); }
                        //                 );

                        //         } catch (\Exception $e) {
                        //             \Log::error('Mail failed to send: ' . $e->getMessage());
                        //         }
                        //     }
                        // }

                        foreach ($list as $u) {
                            try {
                                $email = Helpers::getAllUserEmail($u->user_id);
                                if ($email !== null) {
                                    $data = ['data' => $changeControl,'site'=>'External Audit','history' => 'Cancelled', 'process' => 'External Audit', 'comment' => $history->comment,'user'=> Auth::user()->name];

                                    SendMail::dispatch($data, $email, $changeControl, 'External Audit');
                                }
                            } catch (\Exception $e) {
                                \Log::error('Mail sending failed for user_id: ' . $u->user_id . ' - Error: ' . $e->getMessage());
                                continue;
                            }
                        }


                $history->save();
                $changeControl->update();
                $history = new AuditeeHistory();
                $history->type = "External Audit";
                $history->doc_id = $id;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->stage_id = $changeControl->stage;
                $history->status = $changeControl->status;
                $history->save();
                toastr()->success('Document Sent');
                return back();
            }
        } else {
            toastr()->error('E-signature Not match');
            return back();
        }
    }

    public function AuditTrialExternalShow($id)
    {
        $audit = AuditTrialExternal::where('ExternalAudit_id', $id)->orderByDESC('id')->get()->unique('activity_type');
        $today = Carbon::now()->format('d-m-y');
        $document = Auditee::where('id', $id)->first();
        $document->initiator = User::where('id', $document->initiator_id)->value('name');
        return view('frontend.externalAudit.audit-trial', compact('audit', 'document', 'today'));
    }


    public function AuditTrialExternalDetails($id)
    {

        $detail = AuditTrialExternal::find($id);

        $detail_data = AuditTrialExternal::where('activity_type', $detail->activity_type)->where('ExternalAudit_id', $detail->ExternalAudit_id)->latest()->get();

        $doc = Auditee::where('id', $detail->ExternalAudit_id)->first();

        $doc->origiator_name = User::find($doc->initiator_id);
        return view('frontend.externalAudit.audit-trial-inner', compact('detail', 'doc', 'detail_data'));
    }

    public static function singleReport($id)
    {
        $data = Auditee::find($id);
        if (!empty($data)) {
            $grid_data = InternalAuditGrid::where('audit_id', $id)->where('type', "external_audit")->first();
            $grid_data1 = InternalAuditGrid::where('audit_id', $id)->where('type', "Observation_field_Auditee")->first();
            $data->originator = User::where('id', $data->initiator_id)->value('name');

            $auditAgendaData = InternalAuditGrid::where(['audit_id' => $id, 'identifier' => 'AuditAgenda'])->first();
            $auditAgenda = json_decode($auditAgendaData->data, true);

            $pdf = App::make('dompdf.wrapper');
            // $auditeeNames = User::whereIn('id', $auditeeIdsArray)->pluck('name')->toArray();
            // $auditeeNamesString = implode(', ', $auditeeNames);
            $time = Carbon::now();
            $pdf = PDF::loadview('frontend.externalAudit.singleReport', compact('data','grid_data','grid_data1','auditAgenda'))
                ->setOptions([
                    'defaultFont' => 'sans-serif',
                    'isHtml5ParserEnabled' => true,
                    'isRemoteEnabled' => true,
                    'isPhpEnabled' => true,
                ]);
            $pdf->setPaper('A4');
            $pdf->render();
            $canvas = $pdf->getDomPDF()->getCanvas();
            $height = $canvas->get_height();
            $width = $canvas->get_width();
            

            $canvas->page_script(function ($pageNumber, $pageCount, $canvas, $fontMetrics) {
                $text = "Page " . $pageNumber . " of " . $pageCount;
                $font = $fontMetrics->getFont("Helvetica", "bold");
                $size = 12;
                $color = [0, 0, 0];
            
                $width = $canvas->get_width();
                $textWidth = $fontMetrics->getTextWidth($text, $font, $size);
            
                // RIGHT ALIGN (20px from right edge)
                $x = $width - $textWidth -80;
                $y = $canvas->get_height() -37;
            
                $canvas->text($x, $y, $text, $font, $size, $color);
            });
            $canvas->page_script('$pdf->set_opacity(0.1,"Multiply");');
            $canvas->page_text($width / 4, $height / 2, $data->status, null, 25, [0, 0, 0], 2, 6, -20);
            return $pdf->stream('External-Audit' . $id . '.pdf');
        }
    }

    public static function auditReport($id)
    {
        $doc = Auditee::find($id);
        if (!empty($doc)) {
            $doc->originator = User::where('id', $doc->initiator_id)->value('name');
            $data = AuditTrialExternal::where('ExternalAudit_id', $id)->get();
            $pdf = App::make('dompdf.wrapper');
            $time = Carbon::now();
            $pdf = PDF::loadview('frontend.externalAudit.auditReport', compact('data', 'doc'))
                ->setOptions([
                    'defaultFont' => 'sans-serif',
                    'isHtml5ParserEnabled' => true,
                    'isRemoteEnabled' => true,
                    'isPhpEnabled' => true,
                ]);
            $pdf->setPaper('A4');
            $pdf->render();
            $canvas = $pdf->getDomPDF()->getCanvas();
            $height = $canvas->get_height();
            $width = $canvas->get_width();
            $canvas->page_script('$pdf->set_opacity(0.1,"Multiply");');
            $canvas->page_text($width / 4, $height / 2, $doc->status, null, 25, [0, 0, 0], 2, 6, -20);
            return $pdf->stream('External-Audit' . $id . '.pdf');
        }
    }


    // public function child_external(Request $request, $id)
    // {
    //     $parent_id = $id;
    //     $parent_type = "Observations";
    //     $record_number = ((RecordNumber::first()->value('counter')) + 1);
    //     $record_number = str_pad($record_number, 4, '0', STR_PAD_LEFT);
    //     $parent_division_id = Auditee::where('id', $id)->value('division_id');

    //     $currentDate = Carbon::now();
    //     $formattedDate = $currentDate->addDays(30);
    //     $due_date = $formattedDate->format('d-M-Y');
    //     return view('frontend.forms.observation', compact('record_number','parent_division_id', 'due_date', 'parent_id', 'parent_type'));
    // }

    public function child_external(Request $request, $id)
    {
        $cc = Auditee::find($id);
        $cft = [];
        $parent_id = $id;
        $parent_division_id = $cc->division_id;
        $parent_type = "Internal Audit";
        $old_record = Capa::select('id', 'division_id', 'record', 'created_at')->get();
        $record_number = ((RecordNumber::first()->value('counter')) + 1);
        $record_number = str_pad($record_number, 4, '0', STR_PAD_LEFT);
        $currentDate = Carbon::now();
        $formattedDate = $currentDate->addDays(30);
        $due_date = $formattedDate->format('d-M-Y');
        $parent_intiation_date = Capa::where('id', $id)->value('intiation_date');
        $parent_record =  ((RecordNumber::first()->value('counter')) + 1);
        $parent_record = str_pad($parent_record, 4, '0', STR_PAD_LEFT);
        $parent_initiator_id = $id;
        // $changeControl = OpenStage::find(1);/
        $hod = User::get();
        $pre = CC::all();
    // $old_record = Capa::select('id', 'division_id', 'record', 'short_description')->get();
        $rca_old_record = RootCauseAnalysis::select('id', 'division_id', 'record', 'short_description', 'created_at')->get();

        // if (!empty($changeControl->cft)) $cft = explode(',', $changeControl->cft);

        if ($request->revision == "Observation-child") {
            $old_record = ActionItem::all();
            $parent_division = Auditee::where('id', $id)->value('division_id');
            $cc->originator = User::where('id', $cc->initiator_id)->value('name');
            return view('frontend.forms.observation', compact('record_number','old_record','parent_division','rca_old_record', 'due_date','parent_division_id','parent_id', 'parent_type','parent_intiation_date','parent_record','parent_initiator_id'));

        }


        if ($request->revision == "capa-child") {
            $cc->originator = User::where('id', $cc->initiator_id)->value('name');
            $parent_division = Auditee::where('id', $id)->value('division_id');
           return view('frontend.forms.capa', compact('record_number', 'due_date','rca_old_record', 'parent_id', 'parent_type', 'old_record', 'cft', 'parent_division',));
        }

    }
}

