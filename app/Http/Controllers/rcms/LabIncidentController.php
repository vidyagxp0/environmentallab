<?php

namespace App\Http\Controllers\rcms;

use App\Http\Controllers\Controller;
use App\Jobs\SendMail;
use Illuminate\Http\Request;
use App\Models\Capa;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\RootCauseAnalysis;
use App\Models\RecordNumber;
use App\Models\LabIncidentAuditTrial;
use App\Models\CCStageHistory;
use App\Models\RoleGroup;
use App\Models\User;
use PDF;
use Helpers;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\OpenStage;
use App\Models\LabIncident;
use App\Models\QMSDivision;
use App\Services\DocumentService;
use Illuminate\Support\Facades\App;

class LabIncidentController extends Controller
{

    public function labincident()
    {
        $record_number = ((RecordNumber::first()->value('counter')) + 1);
        $record_number = str_pad($record_number, 4, '0', STR_PAD_LEFT);
        $currentDate = Carbon::now();
        $formattedDate = $currentDate->addDays(30);
        $due_date = $formattedDate->format('Y-m-d');

        $division = QMSDivision::where('name', Helpers::getDivisionName(session()->get('division')))->first();

        if ($division) {
            $last_record = LabIncident::where('division_id', $division->id)->latest()->first();

            if ($last_record) {
                $record_number = $last_record->record_number ? str_pad($last_record->record_number->record_number + 1, 4, '0', STR_PAD_LEFT) : '0001';
            } else {
                $record_number = '0001';
            }
        }

        return view('frontend.forms.lab-incident', compact('due_date', 'record_number'));
    }
    public function create(request $request)
    {
        // return $request;
        if (!$request->short_desc) {
            toastr()->info("Short Description is required");
            return redirect()->back()->withInput();
        }
        $data = new LabIncident();
        $data->Form_Type = "lab-incident";
        $data->record = ((RecordNumber::first()->value('counter')) + 1);
        $data->initiator_id = Auth::user()->id;
        $data->division_id = $request->division_id;
        $data->short_desc = $request->short_desc;
        $data->severity_level2= $request->severity_level2;
        $data->initiated_through = $request->initiated_through;
        $data->initiated_through_req = $request->initiated_through_req;
        $data->intiation_date = $request->intiation_date;
        $data->Initiator_Group= $request->Initiator_Group;
        $data->initiator_group_code= $request->initiator_group_code;
        $data->Other_Ref= $request->Other_Ref;
        $data->due_date = $request->due_date;
        $data->assign_to = $request->assign_to;
        $data->Incident_Category= $request->Incident_Category;
        $data->Invocation_Type = $request->Invocation_Type;
        $data->Incident_Details = $request->Incident_Details;
        $data->Document_Details = $request->Document_Details;
        $data->Instrument_Details = $request->Instrument_Details;
        $data->Involved_Personnel = $request->Involved_Personnel;
        $data->Product_Details = $request->Product_Details;
        $data->Supervisor_Review_Comments = $request->Supervisor_Review_Comments;
        $data->Cancelation_Remarks = $request->Cancelation_Remarks;
        $data->Investigation_Details = $request->Investigation_Details;
        $data->Action_Taken = $request->Action_Taken;
        $data->Root_Cause = $request->Root_Cause;
        $data->Currective_Action = $request->Currective_Action;
        $data->Preventive_Action = $request->Preventive_Action;
        $data->Corrective_Preventive_Action = $request->Corrective_Preventive_Action;
        $data->QA_Review_Comments = $request->QA_Review_Comments;
        $data->QA_Head = $request->QA_Head;
        $data->Effectiveness_Check = $request->Effectiveness_Check;
        $data->effectivess_check_creation_date = $request->effectivess_check_creation_date;
        $data->Incident_Type = $request->Incident_Type;
        $data->Conclusion = $request->Conclusion;
        $data->effect_check_date= $request->effect_check_date;
        $data->occurance_date = $request->occurance_date;
        $data->Incident_Category_others = $request->Incident_Category_others;
        $data->due_date_extension= $request->due_date_extension;
        $data->status = 'Opened';
        $data->stage = 1;

        if (!empty($request->Initial_Attachment)) {
            $files = [];
            if ($request->hasfile('Initial_Attachment')) {
                foreach ($request->file('Initial_Attachment') as $file) {
                    $name = $request->name . 'Initial_Attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
            $data->Initial_Attachment = json_encode($files);
        }
        if (!empty($request->Attachments)) {
            $files = [];
            if ($request->hasfile('Attachments')) {
                foreach ($request->file('Attachments') as $file) {
                    $name = $request->name . 'Attachments' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
            $data->Attachments = json_encode($files);
        }
        if (!empty($request->Inv_Attachment)) {
            $files = [];
            if ($request->hasfile('Inv_Attachment')) {
                foreach ($request->file('Inv_Attachment') as $file) {
                    $name = $request->name . 'Inv_Attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
            $data->Inv_Attachment = json_encode($files);
        }
        if (!empty($request->CAPA_Attachment)) {
            $files = [];
            if ($request->hasfile('CAPA_Attachment')) {
                foreach ($request->file('CAPA_Attachment') as $file) {
                    $name = $request->name . 'CAPA_Attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
            $data->CAPA_Attachment = json_encode($files);
        }
        if (!empty($request->QA_Head_Attachment)) {
            $files = [];
            if ($request->hasfile('QA_Head_Attachment')) {
                foreach ($request->file('QA_Head_Attachment') as $file) {
                    $name = $request->name . 'QA_Head_Attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
            $data->QA_Head_Attachment = json_encode($files);
        }
         $data->save();

        $record = RecordNumber::first();
        $record->counter = ((RecordNumber::first()->value('counter')) + 1);
        $record->update();

        $history = new LabIncidentAuditTrial();
        $history->LabIncident_id = $data->id;
        $history->activity_type = 'Site/Location Code';
        $history->previous = "Null";
        $history->current = Helpers::getDivisionName($request->division_id);
        $history->comment = "NA";
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $data->status;
        $history->save();

        $history = new LabIncidentAuditTrial();
        $history->LabIncident_id = $data->id;
        $history->activity_type = 'Record Number';
        $history->previous = "Null";
        $history->current = Helpers::getDivisionName($data->division_id) . '/LI/' . Helpers::year($data->created_at) . '/' . str_pad($data->record, 4, '0', STR_PAD_LEFT);
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $data->status;
        $history->save();

        $history = new LabIncidentAuditTrial();
        $history->LabIncident_id = $data->id;
        $history->activity_type = 'Initiator';
        $history->previous = "Null";
        $history->current = Auth::user()->name;
        $history->comment = "NA";
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $data->status;
        $history->save();

        $history = new LabIncidentAuditTrial();
        $history->LabIncident_id = $data->id;
        $history->activity_type = 'Date of Initiation';
        $history->previous = "Null";
        $history->current = Helpers::getdateFormat($request->intiation_date);
        $history->comment = "NA";
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $data->status;
        $history->save();

        $history = new LabIncidentAuditTrial();
        $history->LabIncident_id = $data->id;
        $history->activity_type = 'Short Description';
        $history->previous = "Null";
        $history->current = $data->short_desc;
        $history->comment = "NA";
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $data->status;
        $history->save();

        if (!empty($request->assign_to)) {
            $history = new LabIncidentAuditTrial();
            $history->LabIncident_id = $data->id;
            $history->activity_type = 'Assigned to';
            $history->previous = "Null";
            $history->current = Helpers::getInitiatorName($request->assign_to);
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->save();
        }

        if (!empty($request->due_date)) {
            $history = new LabIncidentAuditTrial();
            $history->LabIncident_id = $data->id;
            $history->activity_type = 'Due Date';
            $history->previous = "Null";
            $history->current = Helpers::getdateFormat($request->due_date);
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->save();
        }

        if (!empty($request->Initiator_Group)) {
            $history = new LabIncidentAuditTrial();
            $history->LabIncident_id = $data->id;
            $history->activity_type = 'Initiator Group';
            $history->previous = "Null";
            $history->current = Helpers::getInitiatorGroupFullName($request->Initiator_Group);
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->save();
        }

        if (!empty($request->initiator_group_code)) {
            $history = new LabIncidentAuditTrial();
            $history->LabIncident_id = $data->id;
            $history->activity_type = 'Initiator Group Code';
            $history->previous = "Null";
            $history->current = $request->initiator_group_code;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->save();
        }

        if (!empty($request->severity_level2)) {
            $history = new LabIncidentAuditTrial();
            $history->LabIncident_id = $data->id;
            $history->activity_type = 'Severity Level';
            $history->previous = "Null";
            $history->current = $request->severity_level2;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->save();
        }

        if (!empty($request->initiated_through)) {
            $history = new LabIncidentAuditTrial();
            $history->LabIncident_id = $data->id;
            $history->activity_type = 'Initiated Through';
            $history->previous = "Null";
            $history->current = $request->initiated_through;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->save();
        }

        if (!empty($request->initiated_through_req)) {
            $history = new LabIncidentAuditTrial();
            $history->LabIncident_id = $data->id;
            $history->activity_type = 'others';
            $history->previous = "Null";
            $history->current = $request->initiated_through_req;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->save();
        }

        if (!empty($request->Incident_Category)) {
            $history = new LabIncidentAuditTrial();
            $history->LabIncident_id = $data->id;
            $history->activity_type = 'Incident Category';
            $history->previous = "Null";
            $history->current = $request->Incident_Category;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->save();
        }

        if (!empty($request->Incident_Category_others)) {
            $history = new LabIncidentAuditTrial();
            $history->LabIncident_id = $data->id;
            $history->activity_type = 'Others';
            $history->previous = "Null";
            $history->current = $request->Incident_Category_others;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->save();
        }

        if (!empty($request->Invocation_Type)) {
            $history = new LabIncidentAuditTrial();
            $history->LabIncident_id = $data->id;
            $history->activity_type = 'Invocation Type';
            $history->previous = "Null";
            $history->current = $request->Invocation_Type;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->save();
        }

        if (!empty($request->Initial_Attachment)) {
            $history = new LabIncidentAuditTrial();
            $history->LabIncident_id = $data->id;
            $history->activity_type = 'Initial Attachment';
            $history->previous = "Null";
            $history->current = $data->Initial_Attachment;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->save();
        }

        /******** Incident Detail *********/
        if (!empty($request->Incident_Details)) {
            $history = new LabIncidentAuditTrial();
            $history->LabIncident_id = $data->id;
            $history->activity_type = 'Incident Details';
            $history->previous = "Null";
            $history->current = $request->Incident_Details;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->save();
        }

        if (!empty($request->Document_Details)) {
            $history = new LabIncidentAuditTrial();
            $history->LabIncident_id = $data->id;
            $history->activity_type = 'Document Details';
            $history->previous = "Null";
            $history->current = $request->Document_Details;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->save();
        }

        if (!empty($request->Instrument_Details)) {
            $history = new LabIncidentAuditTrial();
            $history->LabIncident_id = $data->id;
            $history->activity_type = 'Instrument Details';
            $history->previous = "Null";
            $history->current = $request->Instrument_Details;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->save();
        }

        if (!empty($request->Involved_Personnel)) {
            $history = new LabIncidentAuditTrial();
            $history->LabIncident_id = $data->id;
            $history->activity_type = 'Involved Personnel';
            $history->previous = "Null";
            $history->current = $request->Involved_Personnel;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->save();
        }

        if (!empty($request->Product_Details)) {
            $history = new LabIncidentAuditTrial();
            $history->LabIncident_id = $data->id;
            $history->activity_type = 'Product Details,If Any';
            $history->previous = "Null";
            $history->current = $request->Product_Details;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->save();
        }

        if (!empty($request->Supervisor_Review_Comments)) {
            $history = new LabIncidentAuditTrial();
            $history->LabIncident_id = $data->id;
            $history->activity_type = 'Supervisor Review Comments';
            $history->previous = "Null";
            $history->current = $request->Supervisor_Review_Comments;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->save();
        }

        if (!empty($request->Attachments)) {
            $history = new LabIncidentAuditTrial();
            $history->LabIncident_id = $data->id;
            $history->activity_type = 'Incident Attachments';
            $history->previous = "Null";
            $history->current = $data->Attachments;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->save();
        }

        /********** Investigation Detail ***********/
        if (!empty($request->Investigation_Details)) {
            $history = new LabIncidentAuditTrial();
            $history->LabIncident_id = $data->id;
            $history->activity_type = 'Investigation Details';
            $history->previous = "Null";
            $history->current = $request->Investigation_Details;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->save();
        }

        if (!empty($request->Action_Taken)) {
            $history = new LabIncidentAuditTrial();
            $history->LabIncident_id = $data->id;
            $history->activity_type = 'Action Taken';
            $history->previous = "Null";
            $history->current = $request->Action_Taken;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->save();
        }

        if (!empty($request->Root_Cause)) {
            $history = new LabIncidentAuditTrial();
            $history->LabIncident_id = $data->id;
            $history->activity_type = 'Root Cause';
            $history->previous = "Null";
            $history->current = $request->Root_Cause;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->save();
        }

        if (!empty($request->Inv_Attachment)) {
            $history = new LabIncidentAuditTrial();
            $history->LabIncident_id = $data->id;
            $history->activity_type = 'Investigation Attachment';
            $history->previous = "Null";
            $history->current = $data->Inv_Attachment;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->save();
        }

        /********* CAPA Detail **********/
        if (!empty($request->Currective_Action)) {
            $history = new LabIncidentAuditTrial();
            $history->LabIncident_id = $data->id;
            $history->activity_type = 'Corrective Action';
            $history->previous = "Null";
            $history->current = $request->Currective_Action;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->save();
        }

        if (!empty($request->Preventive_Action)) {
            $history = new LabIncidentAuditTrial();
            $history->LabIncident_id = $data->id;
            $history->activity_type = 'Preventive Action';
            $history->previous = "Null";
            $history->current = $request->Preventive_Action;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->save();
        }

        if (!empty($request->Corrective_Preventive_Action)) {
            $history = new LabIncidentAuditTrial();
            $history->LabIncident_id = $data->id;
            $history->activity_type = 'Corrective & Preventive Action';
            $history->previous = "Null";
            $history->current = $request->Corrective_Preventive_Action;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->save();
        }

        if (!empty($request->CAPA_Attachment)) {
            $history = new LabIncidentAuditTrial();
            $history->LabIncident_id = $data->id;
            $history->activity_type = 'CAPA Attachment';
            $history->previous = "Null";
            $history->current = $data->CAPA_Attachment;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->save();
        }

        /********* QA Review *********/
        if (!empty($request->QA_Review_Comments)) {
            $history = new LabIncidentAuditTrial();
            $history->LabIncident_id = $data->id;
            $history->activity_type = 'QA Review Comments';
            $history->previous = "Null";
            $history->current = $request->QA_Review_Comments;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->save();
        }

        if (!empty($request->QA_Head_Attachment)) {
            $history = new LabIncidentAuditTrial();
            $history->LabIncident_id = $data->id;
            $history->activity_type = 'QA Review Attachment';
            $history->previous = "Null";
            $history->current = $data->QA_Head_Attachment;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->save();
        }

        /******** QA Head Designee *********/
        if (!empty($request->QA_Head)) {
            $history = new LabIncidentAuditTrial();
            $history->LabIncident_id = $data->id;
            $history->activity_type = 'QA Head/Designee Comments';
            $history->previous = "Null";
            $history->current = $request->QA_Head;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->save();
        }

        if (!empty($request->Incident_Type)) {
            $history = new LabIncidentAuditTrial();
            $history->LabIncident_id = $data->id;
            $history->activity_type = 'Incident Type';
            $history->previous = "Null";
            $history->current = $request->Incident_Type;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->save();
        }

        if (!empty($request->Conclusion)) {
            $history = new LabIncidentAuditTrial();
            $history->LabIncident_id = $data->id;
            $history->activity_type = 'Conclusion';
            $history->previous = "Null";
            $history->current = $request->Conclusion;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->save();
        }

        if (!empty($request->due_date_extension)) {
            $history = new LabIncidentAuditTrial();
            $history->LabIncident_id = $data->id;
            $history->activity_type = 'Due Date Extension Justification';
            $history->previous = "Null";
            $history->current = $request->due_date_extension;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->save();
        }

        DocumentService::update_qms_numbers();

        toastr()->success('Record is created Successfully');

        return redirect('rcms/qms-dashboard');

    }
    public function updateLabIncident(request $request, $id)
    {

        if (!$request->short_desc) {
            toastr()->info("Short Description is required");
            return redirect()->back()->withInput();
        }

        $lastDocument = LabIncident::find($id);
        $data = LabIncident::find($id);
        $data->short_desc = $request->short_desc;
        $data->Initiator_Group= $request->Initiator_Group;
        $data->initiator_group_code= $request->initiator_group_code;
        $data->Other_Ref= $request->Other_Ref;
        $data->due_date = $request->due_date;
        $data->assign_to = $request->assign_to;
        $data->Incident_Category= $request->Incident_Category;
        $data->Invocation_Type = $request->Invocation_Type;
        $data->Incident_Details = $request->Incident_Details;
        $data->Document_Details = $request->Document_Details;
        $data->Instrument_Details = $request->Instrument_Details;
        $data->Involved_Personnel = $request->Involved_Personnel;
        $data->Product_Details = $request->Product_Details;
        $data->Supervisor_Review_Comments = $request->Supervisor_Review_Comments;
        $data->Cancelation_Remarks = $request->Cancelation_Remarks;
        $data->Investigation_Details = $request->Investigation_Details;
        $data->Action_Taken = $request->Action_Taken;
        $data->Root_Cause = $request->Root_Cause;
        $data->Currective_Action = $request->Currective_Action;
        $data->Preventive_Action = $request->Preventive_Action;
        $data->Corrective_Preventive_Action = $request->Corrective_Preventive_Action;
        $data->QA_Review_Comments = $request->QA_Review_Comments;
        $data->QA_Head = $request->QA_Head;
        $data->Effectiveness_Check = $request->Effectiveness_Check;
        $data->effectivess_check_creation_date = $request->effectivess_check_creation_date;
        $data->Incident_Type = $request->Incident_Type;
        $data->Conclusion = $request->Conclusion;
        $data->effect_check_date= $request->effect_check_date;
        $data->occurance_date = $request->occurance_date;
        $data->Incident_Category_others = $request->Incident_Category_others;
        $data->due_date_extension= $request->due_date_extension;
        $data->severity_level2= $request->severity_level2;
        $data->initiated_through = $request->initiated_through;
        $data->initiated_through_req = $request->initiated_through_req;




        $files = is_array($request->existing_attach_files_initial) ? $request->existing_attach_files_initial : null;
        if (!empty($request->Initial_Attachment)) {
            if ($data->Initial_Attachment) {
                $existingFiles = json_decode($data->Initial_Attachment, true); // Convert to associative array
                if (is_array($existingFiles)) {
                    $files = array_values($existingFiles);
                }
            }

            if ($request->hasfile('Initial_Attachment')) {
                foreach ($request->file('Initial_Attachment') as $file) {
                    $name = "LI" . '-Initial_Attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
        }
        $data->Initial_Attachment = !empty($files) ? json_encode(array_values($files)) : null;


        $files = is_array($request->existing_attach_files_incident) ? $request->existing_attach_files_incident : null;
        if (!empty($request->Attachments)) {
            if ($data->Attachments) {
                $existingFiles = json_decode($data->Attachments, true); // Convert to associative array
                if (is_array($existingFiles)) {
                    $files = array_values($existingFiles);
                }
            }

            if ($request->hasfile('Attachments')) {
                foreach ($request->file('Attachments') as $file) {
                    $name = "LI" . '-Attachments' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
        }
        $data->Attachments = !empty($files) ? json_encode(array_values($files)) : null;

        $files = is_array($request->existing_attach_files_investigation) ? $request->existing_attach_files_investigation : null;
        if (!empty($request->Inv_Attachment)) {
            if ($data->Inv_Attachment) {
                $existingFiles = json_decode($data->Inv_Attachment, true); // Convert to associative array
                if (is_array($existingFiles)) {
                    $files = array_values($existingFiles);
                }
            }

            if ($request->hasfile('Inv_Attachment')) {
                foreach ($request->file('Inv_Attachment') as $file) {
                    $name = "LI" . '-Inv_Attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
        }
        $data->Inv_Attachment = !empty($files) ? json_encode(array_values($files)) : null;


        $files = is_array($request->existing_attach_files_capa) ? $request->existing_attach_files_capa : null;
        if (!empty($request->CAPA_Attachment)) {
            if ($data->CAPA_Attachment) {
                $existingFiles = json_decode($data->CAPA_Attachment, true); // Convert to associative array
                if (is_array($existingFiles)) {
                    $files = array_values($existingFiles);
                }
            }

            if ($request->hasfile('CAPA_Attachment')) {
                foreach ($request->file('CAPA_Attachment') as $file) {
                    $name = "LI" . '-CAPA_Attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
        }
        $data->CAPA_Attachment = !empty($files) ? json_encode(array_values($files)) : null;

        $files = is_array($request->existing_attach_files_QA) ? $request->existing_attach_files_QA : null;
        if (!empty($request->QA_Head_Attachment)) {
            if ($data->QA_Head_Attachment) {
                $existingFiles = json_decode($data->QA_Head_Attachment, true); // Convert to associative array
                if (is_array($existingFiles)) {
                    $files = array_values($existingFiles);
                }
            }

            if ($request->hasfile('QA_Head_Attachment')) {
                foreach ($request->file('QA_Head_Attachment') as $file) {
                    $name = "LI" . '-QA_Head_Attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
        }
        $data->QA_Head_Attachment = !empty($files) ? json_encode(array_values($files)) : null;
        $data->update();

        if ($lastDocument->short_desc != $request->short_desc) {
            $history = new LabIncidentAuditTrial();
            $history->LabIncident_id = $id;
            $history->activity_type = 'Short Description';
            $history->previous = $lastDocument->short_desc;
            $history->current = $request->short_desc;
            $history->comment = $request->short_desc_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }

        if ($lastDocument->Initiator_Group != $request->Initiator_Group) {
            $history = new LabIncidentAuditTrial();
            $history->LabIncident_id = $id;
            $history->activity_type = 'Initiator Group';
            $history->previous = Helpers::getInitiatorGroupFullName($lastDocument->Initiator_Group);
            $history->current = Helpers::getInitiatorGroupFullName($request->Initiator_Group);
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }

        if ($lastDocument->initiator_group_code != $request->initiator_group_code) {
            $history = new LabIncidentAuditTrial();
            $history->LabIncident_id = $id;
            $history->activity_type = 'Initiator Group Code';
            $history->previous = $lastDocument->initiator_group_code;
            $history->current = $request->initiator_group_code;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }

        // if ($lastDocument->due_date != $request->due_date) {
        //     $history = new LabIncidentAuditTrial();
        //     $history->LabIncident_id = $id;
        //     $history->activity_type = 'Due Date';
        //     $history->previous = $lastDocument->due_date;
        //     $history->current = Helpers::getdateFormat($request->due_date);
        //     $history->comment = "NA";
        //     $history->user_id = Auth::user()->id;
        //     $history->user_name = Auth::user()->name;
        //     $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        //     $history->origin_state = $lastDocument->status;
        //     $history->save();
        // }

        if ($lastDocument->assign_to != $request->assign_to) {
            $history = new LabIncidentAuditTrial();
            $history->LabIncident_id = $id;
            $history->activity_type = 'Assigned to';
            $history->previous = Helpers::getInitiatorName($lastDocument->assign_to);
            $history->current = Helpers::getInitiatorName($request->assign_to);
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }

        if ($lastDocument->severity_level2 != $request->severity_level2) {
            $history = new LabIncidentAuditTrial();
            $history->LabIncident_id = $id;
            $history->activity_type = 'Severity Level';
            $history->previous = $lastDocument->severity_level2;
            $history->current = $request->severity_level2;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }

        if ($lastDocument->initiated_through != $request->initiated_through) {
            $history = new LabIncidentAuditTrial();
            $history->LabIncident_id = $id;
            $history->activity_type = 'Initiated Through';
            $history->previous = $lastDocument->initiated_through;
            $history->current = $request->initiated_through;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }

        if ($lastDocument->initiated_through_req != $request->initiated_through_req) {
            $history = new LabIncidentAuditTrial();
            $history->LabIncident_id = $id;
            $history->activity_type = 'Others';
            $history->previous = $lastDocument->initiated_through_req;
            $history->current = $request->initiated_through_req;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }

        if ($lastDocument->Incident_Category != $request->Incident_Category) {
            $history = new LabIncidentAuditTrial();
            $history->LabIncident_id = $id;
            $history->activity_type = 'Incident Category';
            $history->previous = $lastDocument->Incident_Category;
            $history->current = $request->Incident_Category;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }

        if ($lastDocument->Incident_Category_others != $request->Incident_Category_others) {
            $history = new LabIncidentAuditTrial();
            $history->LabIncident_id = $id;
            $history->activity_type = 'Others';
            $history->previous = $lastDocument->Incident_Category_others;
            $history->current = $request->Incident_Category_others;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }

        if ($lastDocument->Invocation_Type != $request->Invocation_Type) {
            $history = new LabIncidentAuditTrial();
            $history->LabIncident_id = $id;
            $history->activity_type = 'Invocation Type';
            $history->previous = $lastDocument->Invocation_Type;
            $history->current = $request->Invocation_Type;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }

        if ($lastDocument->Initial_Attachment != $data->Initial_Attachment) {
            $history = new LabIncidentAuditTrial();
            $history->LabIncident_id = $id;
            $history->activity_type = 'Initial Attachment';
            $history->previous = $lastDocument->Initial_Attachment;
            $history->current = $data->Initial_Attachment;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }

        /*********** Incident Detail ***********/
        if ($lastDocument->Incident_Details != $request->Incident_Details) {
            $history = new LabIncidentAuditTrial();
            $history->LabIncident_id = $id;
            $history->activity_type = 'Incident Details';
            $history->previous = $lastDocument->Incident_Details;
            $history->current = $request->Incident_Details;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }

        if ($lastDocument->Document_Details != $request->Document_Details) {
            $history = new LabIncidentAuditTrial();
            $history->LabIncident_id = $id;
            $history->activity_type = 'Document Details';
            $history->previous = $lastDocument->Document_Details;
            $history->current = $data->Document_Details;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }

        if ($lastDocument->Instrument_Details != $request->Instrument_Details) {
            $history = new LabIncidentAuditTrial();
            $history->LabIncident_id = $id;
            $history->activity_type = 'Instrument Details';
            $history->previous = $lastDocument->Instrument_Details;
            $history->current = $request->Instrument_Details;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }

        if ($lastDocument->Involved_Personnel != $request->Involved_Personnel) {
            $history = new LabIncidentAuditTrial();
            $history->LabIncident_id = $id;
            $history->activity_type = 'Involved Personnel';
            $history->previous = $lastDocument->Involved_Personnel;
            $history->current = $request->Involved_Personnel;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }

        if ($lastDocument->Product_Details != $request->Product_Details) {
            $history = new LabIncidentAuditTrial();
            $history->LabIncident_id = $id;
            $history->activity_type = 'Product Details,If Any';
            $history->previous = $lastDocument->Product_Details;
            $history->current = $request->Product_Details;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }

        if ($lastDocument->Supervisor_Review_Comments != $request->Supervisor_Review_Comments) {
            $history = new LabIncidentAuditTrial();
            $history->LabIncident_id = $id;
            $history->activity_type = 'Supervisor Review Comments';
            $history->previous = $lastDocument->Supervisor_Review_Comments;
            $history->current = $request->Supervisor_Review_Comments;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }

        if ($lastDocument->Attachments != $data->Attachments) {
            $history = new LabIncidentAuditTrial();
            $history->LabIncident_id = $id;
            $history->activity_type = 'Incident Attachments';
            $history->previous = $lastDocument->Attachments;
            $history->current = $data->Attachments;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }

        if ($lastDocument->Cancelation_Remarks != $request->Cancelation_Remarks) {
            $history = new LabIncidentAuditTrial();
            $history->LabIncident_id = $id;
            $history->activity_type = 'Cancelation Remarks';
            $history->previous = $lastDocument->Cancelation_Remarks;
            $history->current = $request->Cancelation_Remarks;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }

        /*********** Investigation Detail ***********/
        if ($lastDocument->Investigation_Details != $request->Investigation_Details) {
            $history = new LabIncidentAuditTrial();
            $history->LabIncident_id = $id;
            $history->activity_type = 'Investigation Details';
            $history->previous = $lastDocument->Investigation_Details;
            $history->current = $request->Investigation_Details;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }

        if ($lastDocument->Action_Taken != $request->Action_Taken) {
            $history = new LabIncidentAuditTrial();
            $history->LabIncident_id = $id;
            $history->activity_type = 'Action Taken';
            $history->previous = $lastDocument->Action_Taken;
            $history->current = $request->Action_Taken;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }

        if ($lastDocument->Root_Cause != $request->Root_Cause) {
            $history = new LabIncidentAuditTrial();
            $history->LabIncident_id = $id;
            $history->activity_type = 'Root Cause';
            $history->previous = $lastDocument->Root_Cause;
            $history->current = $request->Root_Cause;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }

        if ($lastDocument->Inv_Attachment != $data->Inv_Attachment) {
            $history = new LabIncidentAuditTrial();
            $history->LabIncident_id = $id;
            $history->activity_type = 'Investigation Attachment';
            $history->previous = $lastDocument->Inv_Attachment;
            $history->current = $data->Inv_Attachment;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }

        /********* CAPA Details *********/
        if ($lastDocument->Currective_Action != $request->Currective_Action) {
            $history = new LabIncidentAuditTrial();
            $history->LabIncident_id = $id;
            $history->activity_type = 'Corrective Action';
            $history->previous = $lastDocument->Currective_Action;
            $history->current = $request->Currective_Action;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }

        if ($lastDocument->Preventive_Action != $request->Preventive_Action) {
            $history = new LabIncidentAuditTrial();
            $history->LabIncident_id = $id;
            $history->activity_type = 'Preventive Action';
            $history->previous = $lastDocument->Preventive_Action;
            $history->current = $request->Preventive_Action;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }

        if ($lastDocument->Corrective_Preventive_Action != $request->Corrective_Preventive_Action) {
            $history = new LabIncidentAuditTrial();
            $history->LabIncident_id = $id;
            $history->activity_type = 'Corrective & Preventive Action';
            $history->previous = $lastDocument->Corrective_Preventive_Action;
            $history->current = $request->Corrective_Preventive_Action;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }

        if ($lastDocument->CAPA_Attachment != $data->CAPA_Attachment) {
            $history = new LabIncidentAuditTrial();
            $history->LabIncident_id = $id;
            $history->activity_type = 'Capa Attachment';
            $history->previous = $lastDocument->CAPA_Attachment;
            $history->current = $data->CAPA_Attachment;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }

        /******* QA Review *******/
        if ($lastDocument->QA_Review_Comments != $request->QA_Review_Comments) {
            $history = new LabIncidentAuditTrial();
            $history->LabIncident_id = $id;
            $history->activity_type = 'QA Review Comments';
            $history->previous = $lastDocument->QA_Review_Comments;
            $history->current = $request->QA_Review_Comments;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }

        if ($lastDocument->QA_Head_Attachment != $data->QA_Head_Attachment) {
            $history = new LabIncidentAuditTrial();
            $history->LabIncident_id = $id;
            $history->activity_type = 'QA Review Attachment';
            $history->previous = $lastDocument->QA_Head_Attachment;
            $history->current = $data->QA_Head_Attachment;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }

         /******* QA Head/Designee *******/
        if ($lastDocument->QA_Head != $request->QA_Head) {
            $history = new LabIncidentAuditTrial();
            $history->LabIncident_id = $id;
            $history->activity_type = 'QA Head/Designee Comments';
            $history->previous = $lastDocument->QA_Head;
            $history->current = $request->QA_Head;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }

        if ($lastDocument->Incident_Type != $request->Incident_Type) {
            $history = new LabIncidentAuditTrial();
            $history->LabIncident_id = $id;
            $history->activity_type = 'Incident Type';
            $history->previous = $lastDocument->Incident_Type;
            $history->current = $request->Incident_Type;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }

        if ($lastDocument->Conclusion != $request->Conclusion) {
            $history = new LabIncidentAuditTrial();
            $history->LabIncident_id = $id;
            $history->activity_type = 'Conclusion';
            $history->previous = $lastDocument->Conclusion;
            $history->current = $request->Conclusion;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }

        if ($lastDocument->due_date_extension != $request->due_date_extension) {
            $history = new LabIncidentAuditTrial();
            $history->LabIncident_id = $id;
            $history->activity_type = 'Due Date Extension Justification';
            $history->previous = $lastDocument->due_date_extension;
            $history->current = $request->due_date_extension;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }

        DocumentService::update_qms_numbers();
        toastr()->success('Record is updated Successfully');
        return back();
    }

    public function LabIncidentShow($id)
    {

        $data = LabIncident::find($id);
        $data->record = str_pad($data->record, 4, '0', STR_PAD_LEFT);
        $data->assign_to_name = User::where('id', $data->assign_id)->value('name');
        $data->initiator_name = User::where('id', $data->initiator_id)->value('name');
        return view('frontend.labIncident.view', compact('data'));
    }
    public function lab_incident_capa_child(Request $request, $id)
    {
        $cft = [];
        $parent_id = $id;
        $parent_type = "Capa";
        $old_record = Capa::select('id', 'division_id', 'record', 'created_at')->get();
        $rca_old_record = Capa::select('id', 'division_id', 'record', 'created_at')->get();
        $record_number = ((RecordNumber::first()->value('counter')) + 1);
        $record_number = str_pad($record_number, 4, '0', STR_PAD_LEFT);
        $currentDate = Carbon::now();
        $formattedDate = $currentDate->addDays(30);
        $due_date = $formattedDate->format('d-M-Y');
        $changeControl = OpenStage::find(1);
        $parent_division = LabIncident::where('id',$id)->value('division_id');
         if(!empty($changeControl->cft)) $cft = explode(',', $changeControl->cft);
        return view('frontend.forms.capa', compact('record_number', 'due_date', 'parent_id','parent_division', 'parent_type','old_record','cft', 'rca_old_record'));
    }

    public function lab_incident_root_child(Request $request, $id)
    {
        $parent_id = $id;
        $parent_type = "Capa";
        $record_number = ((RecordNumber::first()->value('counter')) + 1);
        $record_number = str_pad($record_number, 4, '0', STR_PAD_LEFT);
        $currentDate = Carbon::now();
        $formattedDate = $currentDate->addDays(30);
        $due_date = $formattedDate->format('d-M-Y');
        $parent_division = LabIncident::where('id',$id)->value('division_id');
        return view('frontend.forms.root-cause-analysis', compact('record_number', 'due_date', 'parent_id', 'parent_type','parent_division'));
    }
    public function LabIncidentStateChange(Request $request, $id)
    {
        if ($request->username == Auth::user()->email && Hash::check($request->password, Auth::user()->password)) {
            $changeControl = LabIncident::find($id);
            $lastDocument =  LabIncident::find($id);
            $data =  LabIncident::find($id);

            if ($changeControl->stage == 1) {
                $changeControl->stage = "2";
                $changeControl->submitted_by = Auth::user()->name;
                $changeControl->submitted_on = Carbon::now()->format('d-M-Y');
                $changeControl->status = "Pending Incident Review";

                $history = new LabIncidentAuditTrial();
                $history->LabIncident_id = $id;
                $history->activity_type = 'Activity Log';
                $history->previous = $lastDocument->status;
                $history->current = "Pending Incident Review";
                $history->comment = $request->comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                $history->stage = 'Submitted';
                $history->save();
                // $list = Helpers::getHodUserList();
                //     foreach ($list as $u) {

                //         if($u->q_m_s_divisions_id == $changeControl->division_id){
                //             $email = Helpers::getInitiatorEmail($u->user_id);
                //              if ($email !== null) {

                //               Mail::send(
                //                   'mail.view-mail',
                //                    ['data' => $changeControl],
                //                 function ($message) use ($email) {
                //                     $message->to($email)
                //                         ->subject("Document is Submitted By ".Auth::user()->name);
                //                 }
                //               );
                //             }
                //      }
                //   }

                $list = Helpers::getHODUserList($changeControl->division_id);
                        $userIds = collect($list)->pluck('user_id')->toArray();
                        $users = User::whereIn('id', $userIds)->select('id', 'name', 'email')->get();
                        $userIdNew = $users->pluck('id')->implode(',');
                        $userId = $users->pluck('name')->implode(',');
                        if($userId){
                                $notification = new LabIncidentAuditTrial();
                                $notification->LabIncident_id = $id;
                                $notification->activity_type = "Notification";
                                $notification->action = 'Notification';
                                $notification->comment = "";
                                $notification->user_id = Auth::user()->id;
                                $notification->user_name = Auth::user()->name;
                                $notification->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                                $notification->origin_state = "Not Applicable";
                                $notification->previous = $lastDocument->status;
                                $notification->current = "Pending Incident Review";
                                $notification->stage = "";
                                $notification->action_name = "";
                                $notification->mailUserId = $userIdNew;
                                $notification->role_name = "Initiator";
                                $notification->save();
                        }
                        // foreach ($list as $u) {
                        //     $email = Helpers::getAllUserEmail($u->user_id);
                        //     if (!empty($email)) {
                        //         try {
                        //             info('Sending mail to', [$email]);
                        //             Mail::send(
                        //                 'mail.view-mail',
                        //                 ['data' => $changeControl,'site'=>'Lab Incident','history' => 'Submitted', 'process' => 'Lab Incident', 'comment' => $history->comment,'user'=> Auth::user()->name],
                        //                 function ($message) use ($email, $changeControl) {
                        //                  $message->to($email)
                        //                  ->subject("QMS Notification: Lab Incident , Record #" . str_pad($changeControl->record, 4, '0', STR_PAD_LEFT) . " - Activity: Submitted Performed"); }
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
                                    $data = ['data' => $changeControl,'site'=>'Lab Incident','history' => 'Submitted', 'process' => 'Lab Incident', 'comment' => $history->comment,'user'=> Auth::user()->name];

                                    SendMail::dispatch($data, $email, $changeControl, 'Lab Incident');
                                }
                            } catch (\Exception $e) {
                                \Log::error('Mail sending failed for user_id: ' . $u->user_id . ' - Error: ' . $e->getMessage());
                                continue;
                            }
                        }

                $list = Helpers::getQCHeadDesigneeUserList($changeControl->division_id);
                        $userIds = collect($list)->pluck('user_id')->toArray();
                        $users = User::whereIn('id', $userIds)->select('id', 'name', 'email')->get();
                        $userIdNew = $users->pluck('id')->implode(',');
                        $userId = $users->pluck('name')->implode(',');
                        if($userId){
                            try {
                                $notification = new LabIncidentAuditTrial();
                                $notification->LabIncident_id = $id;
                                $notification->activity_type = "Notification";
                                $notification->action = 'Notification';
                                $notification->comment = "";
                                $notification->user_id = Auth::user()->id;
                                $notification->user_name = Auth::user()->name;
                                $notification->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                                $notification->origin_state = "Not Applicable";
                                $notification->previous = $lastDocument->status;
                                $notification->current = "Pending Incident Review";
                                $notification->stage = "";
                                $notification->action_name = "";
                                $notification->mailUserId = $userIdNew;
                                $notification->role_name = "Initiator";
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
                        //                 ['data' => $changeControl,'site'=>'Lab Incident','history' => 'Submitted', 'process' => 'Lab Incident', 'comment' => $history->comment,'user'=> Auth::user()->name],
                        //                 function ($message) use ($email, $changeControl) {
                        //                  $message->to($email)
                        //                  ->subject("QMS Notification: Lab Incident , Record #" . str_pad($changeControl->record, 4, '0', STR_PAD_LEFT) . " - Activity: Submitted Performed"); }
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
                                    $data = ['data' => $changeControl,'site'=>'Lab Incident','history' => 'Submitted', 'process' => 'Lab Incident', 'comment' => $history->comment,'user'=> Auth::user()->name];

                                    SendMail::dispatch($data, $email, $changeControl, 'Lab Incident');
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
                $changeControl->status = "Pending Investigation";
                $changeControl->incident_review_completed_by = Auth::user()->name;
                $changeControl->incident_review_completed_on = Carbon::now()->format('d-M-Y');

                $history = new LabIncidentAuditTrial();
                $history->LabIncident_id = $id;
                $history->activity_type = 'Activity Log';
                $history->previous = $lastDocument->status;
                $history->current = "Pending Investigation";
                $history->comment = $request->comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                $history->stage = 'Incident Review completed';
                $history->save();
                // $list = Helpers::getQCHeadUserList();
                //     foreach ($list as $u) {
                //         if($u->q_m_s_divisions_id == $changeControl ->division_id){
                //             $email = Helpers::getInitiatorEmail($u->user_id);
                //              if ($email !== null) {

                //               Mail::send(
                //                   'mail.view-mail',
                //                    ['data' => $changeControl ],
                //                 function ($message) use ($email) {
                //                     $message->to($email)
                //                         ->subject("Document is Send By ".Auth::user()->name);
                //                 }
                //               );
                //             }
                //      }
                //   }

                $list = Helpers::getQCHeadDesigneeUserList($changeControl->division_id);
                        $userIds = collect($list)->pluck('user_id')->toArray();
                        $users = User::whereIn('id', $userIds)->select('id', 'name', 'email')->get();
                        $userIdNew = $users->pluck('id')->implode(',');
                        $userId = $users->pluck('name')->implode(',');
                        if($userId){
                            try {
                                $notification = new LabIncidentAuditTrial();
                                $notification->LabIncident_id = $id;
                                $notification->activity_type = "Notification";
                                $notification->action = 'Notification';
                                $notification->comment = "";
                                $notification->user_id = Auth::user()->id;
                                $notification->user_name = Auth::user()->name;
                                $notification->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                                $notification->origin_state = "Not Applicable";
                                $notification->previous = $lastDocument->status;
                                $notification->current = 'Pending Investigation';
                                $notification->stage = "";
                                $notification->action_name = "";
                                $notification->mailUserId = $userIdNew;
                                $notification->role_name = "HOD/Designee";
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
                        //                 ['data' => $changeControl,'site'=>'Lab Incident','history' => 'Incident Review Completed', 'process' => 'Lab Incident', 'comment' => $history->comment,'user'=> Auth::user()->name],
                        //                 function ($message) use ($email, $changeControl) {
                        //                  $message->to($email)
                        //                  ->subject("QMS Notification: Lab Incident , Record #" . str_pad($changeControl->record, 4, '0', STR_PAD_LEFT) . " - Activity: Incident Review Completed Performed"); }
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
                                    $data = ['data' => $changeControl,'site'=>'Lab Incident','history' => 'Incident Review Completed', 'process' => 'Lab Incident', 'comment' => $history->comment,'user'=> Auth::user()->name];

                                    SendMail::dispatch($data, $email, $changeControl, 'Lab Incident');
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
                $changeControl->status = "Pending Activity Completion";
                $changeControl->investigation_completed_by = Auth::user()->name;
                $changeControl->investigation_completed_on = Carbon::now()->format('d-M-Y');

                $history = new LabIncidentAuditTrial();
                $history->LabIncident_id = $id;
                $history->activity_type = 'Activity Log';
                $history->previous = $lastDocument->status;
                $history->current = "Pending Activity Completion";
                $history->comment = $request->comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                $history->stage = 'Investigation Completed';
                $history->save();
                // $list = Helpers::getHodUserList();
                //     foreach ($list as $u) {
                //         if($u->q_m_s_divisions_id == $changeControl->division_id){
                //             $email = Helpers::getInitiatorEmail($u->user_id);
                //              if ($email !== null) {

                //               Mail::send(
                //                   'mail.view-mail',
                //                    ['data' => $changeControl],
                //                 function ($message) use ($email) {
                //                     $message->to($email)
                //                         ->subject("Investigation is Completed By ".Auth::user()->name);
                //                 }
                //               );
                //             }
                //      }
                //   }

                $list = Helpers::getHODUserList($changeControl->division_id);
                        $userIds = collect($list)->pluck('user_id')->toArray();
                        $users = User::whereIn('id', $userIds)->select('id', 'name', 'email')->get();
                        $userIdNew = $users->pluck('id')->implode(',');
                        $userId = $users->pluck('name')->implode(',');
                        if($userId){
                            try {
                                $notification = new LabIncidentAuditTrial();
                                $notification->LabIncident_id = $id;
                                $notification->activity_type = "Notification";
                                $notification->action = 'Notification';
                                $notification->comment = "";
                                $notification->user_id = Auth::user()->id;
                                $notification->user_name = Auth::user()->name;
                                $notification->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                                $notification->origin_state = "Not Applicable";
                                $notification->previous = $lastDocument->status;
                                $notification->current = 'Pending Activity Completion';
                                $notification->stage = "";
                                $notification->action_name = "";
                                $notification->mailUserId = $userIdNew ;
                                $notification->role_name = "QC Head/Designee";
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
                        //                 ['data' => $changeControl,'site'=>'Lab Incident','history' => 'Investigation Completed', 'process' => 'Lab Incident', 'comment' => $history->comment,'user'=> Auth::user()->name],
                        //                 function ($message) use ($email, $changeControl) {
                        //                  $message->to($email)
                        //                  ->subject("QMS Notification: Lab Incident , Record #" . str_pad($changeControl->record, 4, '0', STR_PAD_LEFT) . " - Activity: Investigation Completed Performed"); }
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
                                    $data = ['data' => $changeControl,'site'=>'Lab Incident','history' => 'Investigation Completed', 'process' => 'Lab Incident', 'comment' => $history->comment,'user'=> Auth::user()->name];

                                    SendMail::dispatch($data, $email, $changeControl, 'Lab Incident');
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
                $changeControl->status = "Pending CAPA";
                $changeControl->all_activities_completed_by = Auth::user()->name;
                $changeControl->all_activities_completed_on = Carbon::now()->format('d-M-Y');

                $history = new LabIncidentAuditTrial();
                $history->LabIncident_id = $id;
                $history->activity_type = 'Activity Log';
                $history->previous = $lastDocument->status;
                $history->current = "Pending CAPA";
                $history->comment = $request->comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                $history->stage = 'All Activities Completed';
                $history->save();
                $changeControl->update();
                toastr()->success('Document Sent');
                return back();
            }
            if ($changeControl->stage == 5) {
                $changeControl->stage = "6";
                $changeControl->status = "Pending QA Review";
                $changeControl->review_completed_by = Auth::user()->name;
                $changeControl->review_completed_on = Carbon::now()->format('d-M-Y');

                $history = new LabIncidentAuditTrial();
                $history->LabIncident_id = $id;
                $history->activity_type = 'Activity Log';
                $history->previous = $lastDocument->status;
                $history->current = "Pending QA Review";
                $history->comment = $request->comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                $history->stage='Review Completed';
                $history->save();
            //  $list = Helpers::getQAUserList();
            //     foreach ($list as $u) {
            //         if($u->q_m_s_divisions_id ==$changeControl->division_id){
            //             $email = Helpers::getInitiatorEmail($u->user_id);
            //              if ($email !== null) {

            //               Mail::send(
            //                   'mail.view-mail',
            //                    ['data' => $changeControl],
            //                 function ($message) use ($email) {
            //                     $message->to($email)
            //                         ->subject("Document is Submitted By ".Auth::user()->name);
            //                 }
            //               );
            //             }
            //      }
            //   }

            $list = Helpers::getQAUserList($changeControl->division_id);
            $userIds = collect($list)->pluck('user_id')->toArray();
            $users = User::whereIn('id', $userIds)->select('id', 'name', 'email')->get();
            $userIdNew = $users->pluck('id')->implode(',');
            $userId = $users->pluck('name')->implode(',');
            if($userId){
                try {
                    $notification = new LabIncidentAuditTrial();
                    $notification->LabIncident_id = $id;
                    $notification->activity_type = "Notification";
                    $notification->action = 'Notification';
                    $notification->comment = "";
                    $notification->user_id = Auth::user()->id;
                    $notification->user_name = Auth::user()->name;
                    $notification->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $notification->origin_state = "Not Applicable";
                    $notification->previous = $lastDocument->status;
                    $notification->current = 'Pending QA Review';
                    $notification->stage = "";
                    $notification->action_name = "";
                    $notification->mailUserId = $userIdNew;
                    $notification->role_name = "QC Head/Designee";
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
                        //                 ['data' => $changeControl,'site'=>'Lab Incident','history' => 'Review Completed', 'process' => 'Lab Incident', 'comment' => $history->comment,'user'=> Auth::user()->name],
                        //                 function ($message) use ($email, $changeControl) {
                        //                  $message->to($email)
                        //                  ->subject("QMS Notification: Lab Incident , Record #" . str_pad($changeControl->record, 4, '0', STR_PAD_LEFT) . " - Activity: Review Completed Performed"); }
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
                                    $data = ['data' => $changeControl,'site'=>'Lab Incident','history' => 'Review Completed', 'process' => 'Lab Incident', 'comment' => $history->comment,'user'=> Auth::user()->name];

                                    SendMail::dispatch($data, $email, $changeControl, 'Lab Incident');
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
            if ($changeControl->stage == 6) {
                $changeControl->stage = "7";
                $changeControl->status = "Pending QA Head Approval";
                $changeControl->qA_review_completed_by = Auth::user()->name;
                $changeControl->qA_review_completed_on = Carbon::now()->format('d-M-Y');

                $history = new LabIncidentAuditTrial();
                $history->LabIncident_id = $id;
                $history->activity_type = 'Activity Log';
                $history->previous = $lastDocument->status;
                $history->current = "Pending QA Head Approval";
                $history->comment = $request->comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                $history->stage='QA Review Completed';
                $history->save();

                $list = Helpers::getHODUserList($changeControl->division_id);
                $userIds = collect($list)->pluck('user_id')->toArray();
            $users = User::whereIn('id', $userIds)->select('id', 'name', 'email')->get();
            $userIdNew = $users->pluck('id')->implode(',');
            $userId = $users->pluck('name')->implode(',');
            if($userId){
                try {
                    $notification = new LabIncidentAuditTrial();
                    $notification->LabIncident_id = $id;
                    $notification->activity_type = "Notification";
                    $notification->action = 'Notification';
                    $notification->comment = "";
                    $notification->user_id = Auth::user()->id;
                    $notification->user_name = Auth::user()->name;
                    $notification->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $notification->origin_state = "Not Applicable";
                    $notification->previous = $lastDocument->status;
                    $notification->current = 'Pending QA Head Approval';
                    $notification->stage = "";
                    $notification->action_name = "";
                    $notification->mailUserId = $userIdNew;
                    $notification->role_name = "QA";
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
                //                 ['data' => $changeControl,'site'=>'Lab Incident','history' => 'QA Review Completed', 'process' => 'Lab Incident', 'comment' => $history->comment,'user'=> Auth::user()->name],
                //                 function ($message) use ($email, $changeControl) {
                //                  $message->to($email)
                //                  ->subject("QMS Notification: Lab Incident , Record #" . str_pad($changeControl->record, 4, '0', STR_PAD_LEFT) . " - Activity: QA Review Completed Performed"); }
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
                            $data = ['data' => $changeControl,'site'=>'Lab Incident','history' => 'QA Review Completed', 'process' => 'Lab Incident', 'comment' => $history->comment,'user'=> Auth::user()->name];

                            SendMail::dispatch($data, $email, $changeControl, 'Lab Incident');
                        }
                    } catch (\Exception $e) {
                        \Log::error('Mail sending failed for user_id: ' . $u->user_id . ' - Error: ' . $e->getMessage());
                        continue;
                    }
                }

                 $list = Helpers::getQCHeadDesigneeUserList($changeControl->division_id);
                 $userIds = collect($list)->pluck('user_id')->toArray();
            $users = User::whereIn('id', $userIds)->select('id', 'name', 'email')->get();
            $userIdNew = $users->pluck('id')->implode(',');
            $userId = $users->pluck('name')->implode(',');
            if($userId){
                try {
                    $notification = new LabIncidentAuditTrial();
                    $notification->LabIncident_id = $id;
                    $notification->activity_type = "Notification";
                    $notification->action = 'Notification';
                    $notification->comment = "";
                    $notification->user_id = Auth::user()->id;
                    $notification->user_name = Auth::user()->name;
                    $notification->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $notification->origin_state = "Not Applicable";
                    $notification->previous = $lastDocument->status;
                    $notification->current = 'Pending QA Head Approval';
                    $notification->stage = "";
                    $notification->action_name = "";
                    $notification->mailUserId = $userIdNew;
                    $notification->role_name = "QA";
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
                        //                 ['data' => $changeControl,'site'=>'Lab Incident','history' => 'QA Review Completed', 'process' => 'Lab Incident', 'comment' => $history->comment,'user'=> Auth::user()->name],
                        //                 function ($message) use ($email, $changeControl) {
                        //                  $message->to($email)
                        //                  ->subject("QMS Notification: Lab Incident , Record #" . str_pad($changeControl->record, 4, '0', STR_PAD_LEFT) . " - Activity: QA Review Completed Performed"); }
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
                                    $data =  ['data' => $changeControl,'site'=>'Lab Incident','history' => 'QA Review Completed', 'process' => 'Lab Incident', 'comment' => $history->comment,'user'=> Auth::user()->name];

                                    SendMail::dispatch($data, $email, $changeControl, 'Lab Incident');
                                }
                            } catch (\Exception $e) {
                                \Log::error('Mail sending failed for user_id: ' . $u->user_id . ' - Error: ' . $e->getMessage());
                                continue;
                            }
                        }

            $list = Helpers::getInitiatorUserList($changeControl->division_id);
            $userIds = collect($list)->pluck('user_id')->toArray();
            $users = User::whereIn('id', $userIds)->select('id', 'name', 'email')->get();
            $userIdNew = $users->pluck('id')->implode(',');
            $userId = $users->pluck('name')->implode(',');
            if($userId){
                try {
                    $notification = new LabIncidentAuditTrial();
                    $notification->LabIncident_id = $id;
                    $notification->activity_type = "Notification";
                    $notification->action = 'Notification';
                    $notification->comment = "";
                    $notification->user_id = Auth::user()->id;
                    $notification->user_name = Auth::user()->name;
                    $notification->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $notification->origin_state = "Not Applicable";
                    $notification->previous = $lastDocument->status;
                    $notification->current = 'Pending QA Head Approval';
                    $notification->stage = "";
                    $notification->action_name = "";
                    $notification->mailUserId = $userIdNew;
                    $notification->role_name = "QA";
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
                        //                 ['data' => $changeControl,'site'=>'Lab Incident','history' => 'QA Review Completed', 'process' => 'Lab Incident', 'comment' => $history->comment,'user'=> Auth::user()->name],
                        //                 function ($message) use ($email, $changeControl) {
                        //                  $message->to($email)
                        //                  ->subject("QMS Notification: Lab Incident , Record #" . str_pad($changeControl->record, 4, '0', STR_PAD_LEFT) . " - Activity: QA Review Completed Performed"); }
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
                                    $data =  ['data' => $changeControl,'site'=>'Lab Incident','history' => 'QA Review Completed', 'process' => 'Lab Incident', 'comment' => $history->comment,'user'=> Auth::user()->name];

                                    SendMail::dispatch($data, $email, $changeControl, 'Lab Incident');
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

            if ($changeControl->stage == 7) {
                $changeControl->stage = "8";
                $changeControl->status = "Closed - Done";
                $changeControl->qA_head_approval_completed_by = Auth::user()->name;
                $changeControl->qA_head_approval_completed_on = Carbon::now()->format('d-M-Y');

                $history = new LabIncidentAuditTrial();
                $history->LabIncident_id = $id;
                $history->activity_type = 'Activity Log';
                $history->previous = $lastDocument->status;
                $history->current = "Closed - Done";
                $history->comment = $request->comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                $history->stage='QA Head Approval Completed';
                $history->save();
                // $list = Helpers::getHodUserList();
                //     foreach ($list as $u) {
                //         if($u->q_m_s_divisions_id ==$changeControl->division_id){
                //             $email = Helpers::getInitiatorEmail($u->user_id);
                //              if ($email !== null) {

                //               Mail::send(
                //                   'mail.view-mail',
                //                    ['data' => $changeControl],
                //                 function ($message) use ($email) {
                //                     $message->to($email)
                //                         ->subject("Document is send By ".Auth::user()->name);
                //                 }
                //               );
                //             }
                //      }
                //   }
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
            $changeControl = LabIncident::find($id);
            $lastDocument = LabIncident::find($id);
            $data =  LabIncident::find($id);


            if ($changeControl->stage == 2) {
                $changeControl->stage = "1";
                $changeControl->status = "Opened";
                $changeControl->request_more_info_by = Auth::user()->name;
                $changeControl->request_more_info_on = Carbon::now()->format('d-M-Y');
                        $history = new LabIncidentAuditTrial();
                        $history->LabIncident_id = $id;
                        $history->activity_type = 'Activity Log';
                        $history->previous = $lastDocument->status;
                        $history->current = "Opened";
                        $history->comment = $request->comment;
                        $history->user_id = Auth::user()->id;
                        $history->user_name = Auth::user()->name;
                        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                        $history->origin_state = $lastDocument->status;
                        $history->stage = "Request More Info";
                        $history->save();

                        $list = Helpers::getInitiatorUserList($changeControl->division_id);
                        $userIds = collect($list)->pluck('user_id')->toArray();
            $users = User::whereIn('id', $userIds)->select('id', 'name', 'email')->get();
            $userIdNew = $users->pluck('id')->implode(',');
            $userId = $users->pluck('name')->implode(',');
            if($userId){
                try {
                    $notification = new LabIncidentAuditTrial();
                    $notification->LabIncident_id = $id;
                    $notification->activity_type = "Notification";
                    $notification->action = 'Notification';
                    $notification->comment = "";
                    $notification->user_id = Auth::user()->id;
                    $notification->user_name = Auth::user()->name;
                    $notification->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $notification->origin_state = "Not Applicable";
                    $notification->previous = $lastDocument->status;
                    $notification->current = 'Opened';
                    $notification->stage = "";
                    $notification->action_name = "";
                    $notification->mailUserId = $userIdNew;
                    $notification->role_name = "HOD/Designee";
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
                        //                 ['data' => $changeControl,'site'=>'Lab Incident','history' => 'Request More Info', 'process' => 'Lab Incident', 'comment' => $history->comment,'user'=> Auth::user()->name],
                        //                 function ($message) use ($email, $changeControl) {
                        //                  $message->to($email)
                        //                  ->subject("QMS Notification: Lab Incident , Record #" . str_pad($changeControl->record, 4, '0', STR_PAD_LEFT) . " - Activity: Request More Info Performed"); }
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
                                    $data = ['data' => $changeControl,'site'=>'Lab Incident','history' => 'Request More Info', 'process' => 'Lab Incident', 'comment' => $history->comment,'user'=> Auth::user()->name];

                                    SendMail::dispatch($data, $email, $changeControl, 'Lab Incident');
                                }
                            } catch (\Exception $e) {
                                \Log::error('Mail sending failed for user_id: ' . $u->user_id . ' - Error: ' . $e->getMessage());
                                continue;
                            }
                        }

                $changeControl->update();
                $history = new CCStageHistory();
                $history->type = "Lab Incident";
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
                $changeControl->stage = "2";
                $changeControl->status = "Pending Incident Review";
                // $changeControl->update();
                $changeControl->request_more_information_by = Auth::user()->name;
                $changeControl->request_more_information_on= Carbon::now()->format('d-M-Y');
                        $history = new LabIncidentAuditTrial();
                        $history->LabIncident_id = $id;
                        $history->activity_type = 'Activity Log';
                        $history->previous = $lastDocument->status;
                        $history->current = "Pending Incident Review";
                        $history->comment = $request->comment;
                        $history->user_id = Auth::user()->id;
                        $history->user_name = Auth::user()->name;
                        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                        $history->origin_state = $lastDocument->status;
                        $history->stage = "Request More Info";
                        $history->save();
                $changeControl->update();
                $history = new CCStageHistory();
                $history->type = "Lab Incident";
                $history->doc_id = $id;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->stage_id = $changeControl->stage;
                $history->status = $changeControl->status;
                $history->save();

                toastr()->success('Document Sent');
                return back();
            }
            if ($changeControl->stage == 5) {
                $changeControl->stage = "3";
                $changeControl->status = "Pending Investigation";
                // $changeControl->update();
                $changeControl->further_investigation_required_by = Auth::user()->name;
                $changeControl->further_investigation_required_on = Carbon::now()->format('d-M-Y');
                        $history = new LabIncidentAuditTrial();
                        $history->LabIncident_id = $id;
                        $history->activity_type = 'Activity Log';
                        $history->previous = $lastDocument->status;
                        $history->current = "Pending Investigation";
                        $history->comment = $request->comment;
                        $history->user_id = Auth::user()->id;
                        $history->user_name = Auth::user()->name;
                        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                        $history->origin_state = $lastDocument->status;
                        $history->stage = "Further Investigation Required";
                        $history->save();

                        $list = Helpers::getInitiatorUserList($changeControl->division_id);
                         $userIds = collect($list)->pluck('user_id')->toArray();
                        $users = User::whereIn('id', $userIds)->select('id', 'name', 'email')->get();
                        $userIdNew = $users->pluck('id')->implode(',');
                        $userId = $users->pluck('name')->implode(',');
                        if($userId){
                            try {
                                $notification = new LabIncidentAuditTrial();
                                $notification->LabIncident_id = $id;
                                $notification->activity_type = "Notification";
                                $notification->action = 'Notification';
                                $notification->comment = "";
                                $notification->user_id = Auth::user()->id;
                                $notification->user_name = Auth::user()->name;
                                $notification->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                                $notification->origin_state = "Not Applicable";
                                $notification->previous = $lastDocument->status;
                                $notification->current = "Pending Investigation";
                                $notification->stage = "";
                                $notification->action_name = "";
                                $notification->mailUserId = $userIdNew;
                                $notification->role_name = "QC Head/Designee";
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
                        //                 ['data' => $changeControl,'site'=>'Lab Incident','history' => 'Further Investigation Required', 'process' => 'Lab Incident', 'comment' => $history->comment,'user'=> Auth::user()->name],
                        //                 function ($message) use ($email, $changeControl) {
                        //                  $message->to($email)
                        //                  ->subject("QMS Notification: Lab Incident , Record #" . str_pad($changeControl->record, 4, '0', STR_PAD_LEFT) . " - Activity: Further Investigation Required Performed"); }
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
                                    $data = ['data' => $changeControl,'site'=>'Lab Incident','history' => 'Further Investigation Required', 'process' => 'Lab Incident', 'comment' => $history->comment,'user'=> Auth::user()->name];

                                    SendMail::dispatch($data, $email, $changeControl, 'Lab Incident');
                                }
                            } catch (\Exception $e) {
                                \Log::error('Mail sending failed for user_id: ' . $u->user_id . ' - Error: ' . $e->getMessage());
                                continue;
                            }
                        }

                        $list = Helpers::getHODUserList($changeControl->division_id);
                        $userIds = collect($list)->pluck('user_id')->toArray();
                        $users = User::whereIn('id', $userIds)->select('id', 'name', 'email')->get();
                        $userIdNew = $users->pluck('id')->implode(',');
                        $userId = $users->pluck('name')->implode(',');
                        if($userId){
                            try {
                                $notification = new LabIncidentAuditTrial();
                                $notification->LabIncident_id = $id;
                                $notification->activity_type = "Notification";
                                $notification->action = 'Notification';
                                $notification->comment = "";
                                $notification->user_id = Auth::user()->id;
                                $notification->user_name = Auth::user()->name;
                                $notification->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                                $notification->origin_state = "Not Applicable";
                                $notification->previous = $lastDocument->status;
                                $notification->current = "Pending Investigation";
                                $notification->stage = "";
                                $notification->action_name = "";
                                $notification->mailUserId = $userIdNew;
                                $notification->role_name = "QC Head/Designee";
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
                        //                 ['data' => $changeControl,'site'=>'Lab Incident','history' => 'Further Investigation Required', 'process' => 'Lab Incident', 'comment' => $history->comment,'user'=> Auth::user()->name],
                        //                 function ($message) use ($email, $changeControl) {
                        //                  $message->to($email)
                        //                  ->subject("QMS Notification: Lab Incident , Record #" . str_pad($changeControl->record, 4, '0', STR_PAD_LEFT) . " - Activity: Further Investigation Required Performed"); }
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
                                    $data =  ['data' => $changeControl,'site'=>'Lab Incident','history' => 'Further Investigation Required', 'process' => 'Lab Incident', 'comment' => $history->comment,'user'=> Auth::user()->name];

                                    SendMail::dispatch($data, $email, $changeControl, 'Lab Incident');
                                }
                            } catch (\Exception $e) {
                                \Log::error('Mail sending failed for user_id: ' . $u->user_id . ' - Error: ' . $e->getMessage());
                                continue;
                            }
                        }

                        $list = Helpers::getQCHeadDesigneeUserList($changeControl->division_id);
                        $userIds = collect($list)->pluck('user_id')->toArray();
                        $users = User::whereIn('id', $userIds)->select('id', 'name', 'email')->get();
                        $userIdNew = $users->pluck('id')->implode(',');
                        $userId = $users->pluck('name')->implode(',');
                        if($userId){
                            try {
                                $notification = new LabIncidentAuditTrial();
                                $notification->LabIncident_id = $id;
                                $notification->activity_type = "Notification";
                                $notification->action = 'Notification';
                                $notification->comment = "";
                                $notification->user_id = Auth::user()->id;
                                $notification->user_name = Auth::user()->name;
                                $notification->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                                $notification->origin_state = "Not Applicable";
                                $notification->previous = $lastDocument->status;
                                $notification->current = "Pending Investigation";
                                $notification->stage = "";
                                $notification->action_name = "";
                                $notification->mailUserId = $userIdNew;
                                $notification->role_name = "QC Head/Designee";
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
                        //                 ['data' => $changeControl,'site'=>'Lab Incident','history' => 'Further Investigation Required', 'process' => 'Lab Incident', 'comment' => $history->comment,'user'=> Auth::user()->name],
                        //                 function ($message) use ($email, $changeControl) {
                        //                  $message->to($email)
                        //                  ->subject("QMS Notification: Lab Incident , Record #" . str_pad($changeControl->record, 4, '0', STR_PAD_LEFT) . " - Activity: Further Investigation Required Performed"); }
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
                                    $data = ['data' => $changeControl,'site'=>'Lab Incident','history' => 'Further Investigation Required', 'process' => 'Lab Incident', 'comment' => $history->comment,'user'=> Auth::user()->name];

                                    SendMail::dispatch($data, $email, $changeControl, 'Lab Incident');
                                }
                            } catch (\Exception $e) {
                                \Log::error('Mail sending failed for user_id: ' . $u->user_id . ' - Error: ' . $e->getMessage());
                                continue;
                            }
                        }

                $changeControl->update();
                $history = new CCStageHistory();
                $history->type = "Lab Incident";
                $history->doc_id = $id;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->stage_id = $changeControl->stage;
                $history->status = $changeControl->status;
                $history->save();

                toastr()->success('Document Sent');
                return back();
            }
            if ($changeControl->stage == 6) {
                $changeControl->stage = "5";
                $changeControl->status = "Pending CAPA";
                // $changeControl->update();
                $changeControl->return_to_pending_capa_by = Auth::user()->name;
                $changeControl->return_to_pending_capa_on = Carbon::now()->format('d-M-Y');
                        $history = new LabIncidentAuditTrial();
                        $history->LabIncident_id = $id;
                        $history->activity_type = 'Activity Log';
                        $history->previous = $lastDocument->status;
                        $history->current = "Pending CAPA";
                        $history->comment = $request->comment;
                        $history->user_id = Auth::user()->id;
                        $history->user_name = Auth::user()->name;
                        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                        $history->origin_state = $lastDocument->status;
                        $history->stage = "Return To Pending CAPA";
                        $history->save();
                $changeControl->update();
                $history = new CCStageHistory();
                $history->type = "Lab Incident";
                $history->doc_id = $id;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->stage_id = $changeControl->stage;
                $history->status = $changeControl->status;
                $history->save();

                toastr()->success('Document Sent');
                return back();
            }
            if ($changeControl->stage == 7) {
                $changeControl->stage = "6";
                $changeControl->status = "Pending QA Review";
                // $changeControl->update();
                $changeControl->return_to_qa_review_by = Auth::user()->name;
                $changeControl->return_to_qa_review_on = Carbon::now()->format('d-M-Y');
                        $history = new LabIncidentAuditTrial();
                        $history->LabIncident_id = $id;
                        $history->activity_type = 'Activity Log';
                        $history->previous = $lastDocument->status;
                        $history->current = "Pending QA Review";
                        $history->comment = $request->comment;
                        $history->user_id = Auth::user()->id;
                        $history->user_name = Auth::user()->name;
                        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                        $history->origin_state = $lastDocument->status;
                        $history->stage = "Return to QA Review";
                        $history->save();
                $changeControl->update();
                $history = new CCStageHistory();
                $history->type = "Lab Incident";
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


    public function LabIncidentCancel(Request $request, $id)
    {
        if ($request->username == Auth::user()->email && Hash::check($request->password, Auth::user()->password)) {
            $changeControl = LabIncident::find($id);

            if ($changeControl->stage == 2) {
                $changeControl->stage = "0";
                $changeControl->status = "Closed - Cancelled";
                $changeControl->cancelled_by = Auth::user()->name;
                $changeControl->cancelled_on = Carbon::now()->format('d-M-Y');
                        $history = new LabIncidentAuditTrial();
                        $history->LabIncident_id = $id;
                        $history->activity_type = 'Activity Log';
                        $history->previous = "Pending Incident Review";
                        $history->current = "Closed-Cancelled";
                        $history->comment = $request->comment;
                        $history->user_id = Auth::user()->id;
                        $history->user_name = Auth::user()->name;
                        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                        $history->origin_state = "Pending Incident Review";
                        $history->stage = "Cancelled";
                        $history->save();

                        $list = Helpers::getInitiatorUserList($changeControl->division_id);
                        $userIds = collect($list)->pluck('user_id')->toArray();
                        $users = User::whereIn('id', $userIds)->select('id', 'name', 'email')->get();
                        $userIdNew = $users->pluck('id')->implode(',');
                        $userId = $users->pluck('name')->implode(',');
                        if($userId){
                            try {
                                $notification = new LabIncidentAuditTrial();
                                $notification->LabIncident_id = $id;
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
                                $notification->role_name = "HOD/Designee";
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
                        //                 ['data' => $changeControl,'site'=>'Lab Incident','history' => 'Cancelled', 'process' => 'Lab Incident', 'comment' => $history->comment,'user'=> Auth::user()->name],
                        //                 function ($message) use ($email, $changeControl) {
                        //                  $message->to($email)
                        //                  ->subject("QMS Notification: Lab Incident , Record #" . str_pad($changeControl->record, 4, '0', STR_PAD_LEFT) . " - Activity: Cancelled Performed"); }
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
                                    $data = ['data' => $changeControl,'site'=>'Lab Incident','history' => 'Cancelled', 'process' => 'Lab Incident', 'comment' => $history->comment,'user'=> Auth::user()->name];

                                    SendMail::dispatch($data, $email, $changeControl, 'Lab Incident');
                                }
                            } catch (\Exception $e) {
                                \Log::error('Mail sending failed for user_id: ' . $u->user_id . ' - Error: ' . $e->getMessage());
                                continue;
                            }
                        }

                    $list = Helpers::getQAUserList($changeControl->division_id);
                        $userIds = collect($list)->pluck('user_id')->toArray();
                        $users = User::whereIn('id', $userIds)->select('id', 'name', 'email')->get();
                        $userIdNew = $users->pluck('id')->implode(',');
                        $userId = $users->pluck('name')->implode(',');
                        if($userId){
                            try {
                                $notification = new LabIncidentAuditTrial();
                                $notification->LabIncident_id = $id;
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
                                $notification->role_name = "HOD/Designee";
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
                        //                 ['data' => $changeControl,'site'=>'Lab Incident','history' => 'Cancelled', 'process' => 'Lab Incident', 'comment' => $history->comment,'user'=> Auth::user()->name],
                        //                 function ($message) use ($email, $changeControl) {
                        //                  $message->to($email)
                        //                  ->subject("QMS Notification: Lab Incident , Record #" . str_pad($changeControl->record, 4, '0', STR_PAD_LEFT) . " - Activity: Cancelled Performed"); }
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
                                    $data = ['data' => $changeControl,'site'=>'Lab Incident','history' => 'Cancelled', 'process' => 'Lab Incident', 'comment' => $history->comment,'user'=> Auth::user()->name];

                                    SendMail::dispatch($data, $email, $changeControl, 'Lab Incident');
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
        } else {
            toastr()->error('E-signature Not match');
            return back();
        }
    }


    public function LabIncidentAuditTrial($id)
    {
        $audit = LabIncidentAuditTrial::where('LabIncident_id', $id)->orderByDESC('id')->get()->unique('activity_type');
        $today = Carbon::now()->format('d-m-y');
        $document = LabIncident::where('id', $id)->first();
        $document->initiator = User::where('id', $document->initiator_id)->value('name');

        return view('frontend.labIncident.audit-trial', compact('audit', 'document', 'today'));
    }

    public function auditDetailsLabIncident($id)
    {

        $detail = LabIncidentAuditTrial::find($id);

        $detail_data = LabIncidentAuditTrial::where('activity_type', $detail->activity_type)->where('LabIncident_id', $detail->LabIncident_id)->latest()->get();

        $doc = LabIncident::where('id', $detail->LabIncident_id)->first();

        $doc->origiator_name = User::find($doc->initiator_id);
        return view('frontend.labIncident.audit-trial-inner', compact('detail', 'doc', 'detail_data'));
    }


    public function root_cause_analysis(Request $request, $id)
    {
        return view("frontend.labIncident.root_cause_analysis");
    }


    public static function singleReport($id)
    {
        $data = LabIncident::find($id);
        if (!empty($data)) {
            $data->originator = User::where('id', $data->initiator_id)->value('name');
            $pdf = App::make('dompdf.wrapper');
            $time = Carbon::now();
            $pdf = PDF::loadview('frontend.labIncident.singleReport', compact('data'))
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
            return $pdf->stream('Lab-Incident' . $id . '.pdf');
        }
    }

    public static function auditReport($id)
    {
        $doc = LabIncident::find($id);
        if (!empty($doc)) {
            $doc->originator = User::where('id', $doc->initiator_id)->value('name');
            $data = LabIncidentAuditTrial::where('LabIncident_id', $id)->get();
            $pdf = App::make('dompdf.wrapper');
            $time = Carbon::now();
            $pdf = PDF::loadview('frontend.labIncident.auditReport', compact('data', 'doc'))
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
            return $pdf->stream('LabIncident-AuditTrial' . $id . '.pdf');
        }
    }
}
