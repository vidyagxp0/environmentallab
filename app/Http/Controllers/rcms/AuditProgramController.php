<?php

namespace App\Http\Controllers\rcms;

use App\Http\Controllers\Controller;
use App\Jobs\SendMail;
use Illuminate\Http\Request;
use App\Models\AuditProgram;
use App\Models\RecordNumber;
use App\Models\RoleGroup;
use App\Models\InternalAudit;
use App\Models\User;
use App\Models\AuditProgramGrid;
use Carbon\Carbon;
use PDF;
use Helpers;
use Illuminate\Support\Facades\Mail;
use App\Models\AuditProgramAuditTrial;
use App\Models\QMSDivision;
use App\Services\DocumentService;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class AuditProgramController extends Controller
{

    public function auditprogram()
    {
        $old_record = AuditProgram::select('id', 'division_id', 'record', 'created_at')->get();
        $record_number = ((RecordNumber::first()->value('counter')) + 1);
        $record_number = str_pad($record_number, 4, '0', STR_PAD_LEFT);
        $currentDate = Carbon::now();
        $formattedDate = $currentDate->addDays(30);
        $due_date = $formattedDate->format('Y-m-d');

        $division = QMSDivision::where('name', Helpers::getDivisionName(session()->get('division')))->first();

        if ($division) {
            $last_record = AuditProgram::where('division_id', $division->id)->latest()->first();

            if ($last_record) {
                $record_number = $last_record->record_number ? str_pad($last_record->record_number->record_number + 1, 4, '0', STR_PAD_LEFT) : '0001';
            } else {
                $record_number = '0001';
            }
        }

        return view('frontend.forms.audit-program', compact('due_date', 'record_number', 'old_record'));
    }
    public function create(request $request)
    {
        // return $request;
        // if (!$request->short_description) {
        //     toastr()->info("Short Description is required");
        //     return redirect()->back()->withInput();
        // }
        // if (!$request->country) {
        //     toastr()->info("Country is required");
        //     return redirect()->back()->withInput();
        // }
        // if (!$request->state) {
        //     toastr()->info("State is required");
        //     return redirect()->back()->withInput();
        // }
        // if (!$request->City) {
        //     toastr()->info("City is required");
        //     return redirect()->back()->withInput();
        // }
        $data = new AuditProgram();
        // $data->form_type = "audit-program";
        $data->record = ((RecordNumber::first()->value('counter')) + 1);
        $data->initiator_id = Auth::user()->id;
        $data->division_id = $request->division_id;
        $data->division_code = $request->division_code;
        $data->parent_id = $request->parent_id;
        $data->parent_type = $request->parent_type;
        $data->intiation_date = $request->intiation_date;
        $data->short_description = $request->short_description;

        $data->initiated_through = $request->initiated_through;
        $data->initiated_through_req = $request->initiated_through_req;
        $data->type_other = $request->type_other;
        $data->repeat = $request->repeat;
        $data->repeat_nature = $request->repeat_nature;
        $data->due_date_extension = $request->due_date_extension;


        $data->Initiator_Group = $request->Initiator_Group;
        $data->initiator_group_code = $request->initiator_group_code;
        // $data->assign_to = $request->assign_to;
        $data->assign_to =  $request->assign_to;
        $data->due_date = $request->due_date;
        $data->type = $request->type;
        $data->year = $request->year;
        $data->Quarter = $request->Quarter;
        $data->description = $request->description;
        $data->comments = $request->comments;
        $data->related_url = $request->related_url;
        $data->url_description = $request->url_description;
        //$data->suggested_audits = $request->suggested_audits;
        $data->zone = $request->zone;
        $data->country = $request->country;
        $data->City = $request->City;
        $data->state = $request->state;
        $data->severity1_level = $request->severity1_level;

        $data->status = 'Opened';
        $data->stage = 1;

        if (!empty($request->attachments)) {
            $files = [];
            if ($request->hasfile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $name = $request->name . '-attachments' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
            $data->attachments = json_encode($files);
        }
        $data->save();

        $record = RecordNumber::first();
        $record->counter = ((RecordNumber::first()->value('counter')) + 1);
        $record->update();

        // ----------------grid-------
        $data1 = new AuditProgramGrid();
        $data1->audit_program_id = $data->id;

        if (!empty($request->serial_number)) {
            $data1->serial_number = serialize($request->serial_number);
        }
        if (!empty($request->Auditees)) {
            $data1->auditor = serialize($request->Auditees);
        }
        if (!empty($request->start_date)) {
            $data1->start_date = serialize($request->start_date);
        }
        if (!empty($request->end_date)) {
            $data1->end_date = serialize($request->end_date);
        }
        if (!empty($request->lead_investigator)) {
            $data1->lead_investigator = serialize($request->lead_investigator);
        }
        if (!empty($request->comment)) {
            $data1->comment = serialize($request->comment);
        }
        $data1->save();


        if (!empty($data->short_description)) {
            $history = new AuditProgramAuditTrial();
            $history->AuditProgram_id = $data->id;
            $history->activity_type = 'Short Description';
            $history->previous = "Null";
            $history->current = $data->short_description;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->save();
        }

        if (!empty($data->record)) {
            $history = new AuditProgramAuditTrial();
            $history->AuditProgram_id = $data->id;
            $history->activity_type = 'Record Number';
            $history->previous = "Null";
            $history->current = Helpers::getDivisionName($data->division_id) . '/AP/' . Helpers::year($data->created_at) . '/' . str_pad($data->record, 4, '0', STR_PAD_LEFT);
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->save();
        }

        if (!empty($data->intiation_date)) {
            $history = new AuditProgramAuditTrial();
            $history->AuditProgram_id = $data->id;
            $history->activity_type = 'Date of Initiation';
            $history->previous = "Null";
            $history->current = Helpers::getdateFormat($data->intiation_date);
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->save();
        }

        if (!empty($data->division_code)) {
            $history = new AuditProgramAuditTrial();
            $history->AuditProgram_id = $data->id;
            $history->activity_type = 'Site/Location Code';
            $history->previous = "Null";
            $history->current = $data->division_code;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->save();
        }

        if (!empty($data->initiator_id)) {
            $history = new AuditProgramAuditTrial();
            $history->AuditProgram_id = $data->id;
            $history->activity_type = 'Initiator';
            $history->previous = "Null";
            $history->current = Helpers::getInitiatorName($data->initiator_id);
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->save();
        }

        if (!empty($data->due_date)) {
            $history = new AuditProgramAuditTrial();
            $history->AuditProgram_id = $data->id;
            $history->activity_type = 'Due Date';
            $history->previous = "Null";
            $history->current = Helpers::getdateFormat($data->due_date);
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->save();
        }

        if (!empty($data->initiator_group_code)) {
            $history = new AuditProgramAuditTrial();
            $history->AuditProgram_id = $data->id;
            $history->activity_type = 'Initiator Group Code';
            $history->previous = "Null";
            $history->current = $data->initiator_group_code;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->save();
        }

        // if (!empty($data->assign_to)) {
        //     $history = new AuditProgramAuditTrial();
        //     $history->AuditProgram_id = $data->id;
        //     $history->activity_type = 'Assigned to';
        //     $history->previous = "Null";
        //     $history->current = Helpers::getInitiatorName($data->assign_to);
        //     $history->comment = "NA";
        //     $history->user_id = Auth::user()->id;
        //     $history->user_name = Auth::user()->name;
        //     $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        //     $history->origin_state = $data->status;
        //     $history->save();
        // }

        if (!empty($request->assign_to)) {
            $history = new AuditProgramAuditTrial();
            $history->AuditProgram_id = $data->id;
            $history->activity_type = 'Assigned To';
            $history->previous = "Null";
            $history->current =  Helpers::getInitiatorName($request->assign_to);
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->save();
        }

        // if (!empty($data->due_date)) {
        //     $history = new AuditProgramAuditTrial();
        //     $history->AuditProgram_id = $data->id;
        //     $history->activity_type = 'Date Due';
        //     $history->previous = "Null";
        //     $history->current = $data->due_date;
        //     $history->comment = "NA";
        //     $history->user_id = Auth::user()->id;
        //     $history->user_name = Auth::user()->name;
        //     $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        //     $history->origin_state = $data->status;
        //     $history->save();
        // }

        if (!empty($data->type)) {
            $history = new AuditProgramAuditTrial();
            $history->AuditProgram_id = $data->id;
            $history->activity_type = 'Type';
            $history->previous = "Null";
            $history->current = $data->type;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->save();
        }

        if (!empty($data->severity1_level)) {
            $history = new AuditProgramAuditTrial();
            $history->AuditProgram_id = $data->id;
            $history->activity_type = 'Severity Level';
            $history->previous = "Null";
            $history->current = $data->severity1_level;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->save();
        }


        if (!empty($data->initiated_through)) {
            $history = new AuditProgramAuditTrial();
            $history->AuditProgram_id = $data->id;
            $history->activity_type = 'Initiated Through';
            $history->previous = "Null";
            $history->current = $data->initiated_through;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->save();
        }

        if (!empty($data->initiated_through_req)) {
            $history = new AuditProgramAuditTrial();
            $history->AuditProgram_id = $data->id;
            $history->activity_type = 'Others';
            $history->previous = "Null";
            $history->current = $data->initiated_through_req;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->save();
        }

        if (!empty($data->type_other)) {
            $history = new AuditProgramAuditTrial();
            $history->AuditProgram_id = $data->id;
            $history->activity_type = 'Type(Others)';
            $history->previous = "Null";
            $history->current = $data->type_other;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->save();
        }

        if (!empty($data->due_date_extension)) {
            $history = new AuditProgramAuditTrial();
            $history->AuditProgram_id = $data->id;
            $history->activity_type = 'Due Date Extension Justification';
            $history->previous = "Null";
            $history->current = $data->due_date_extension;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->save();
        }

        if (!empty($data->year)) {
            $history = new AuditProgramAuditTrial();
            $history->AuditProgram_id = $data->id;
            $history->activity_type = 'Year';
            $history->previous = "Null";
            $history->current = $data->year;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->save();
        }

        if (!empty($data->Quarter)) {
            $history = new AuditProgramAuditTrial();
            $history->AuditProgram_id = $data->id;
            $history->activity_type = 'Quarter';
            $history->previous = "Null";
            $history->current = $data->Quarter;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->save();
        }

        if (!empty($data->description)) {
            $history = new AuditProgramAuditTrial();
            $history->AuditProgram_id = $data->id;
            $history->activity_type = 'Description';
            $history->previous = "Null";
            $history->current = $data->description;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->save();
        }

        if (!empty($data->comments)) {
            $history = new AuditProgramAuditTrial();
            $history->AuditProgram_id = $data->id;
            $history->activity_type = 'Comments';
            $history->previous = "Null";
            $history->current = $data->comments;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->save();
        }

        if (!empty($data->related_url)) {
            $history = new AuditProgramAuditTrial();
            $history->AuditProgram_id = $data->id;
            $history->activity_type = 'Related URL';
            $history->previous = "Null";
            $history->current = $data->related_url;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->save();
        }
        if (!empty($data->url_description)) {
            $history = new AuditProgramAuditTrial();
            $history->AuditProgram_id = $data->id;
            $history->activity_type = "URl's Description";
            $history->previous = "Null";
            $history->current = $data->url_description;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->save();
        }

        if (!empty($data->Initiator_Group)) {
            $history = new AuditProgramAuditTrial();
            $history->AuditProgram_id = $data->id;
            $history->activity_type = 'Initiator Group';
            $history->previous = "Null";
            $history->current = Helpers::getInitiatorGroupFullName($data->Initiator_Group);
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->save();
        }
        if (!empty($data->zone)) {
            $history = new AuditProgramAuditTrial();
            $history->AuditProgram_id = $data->id;
            $history->activity_type = 'Zone';
            $history->previous = "Null";
            $history->current = $data->zone;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->save();
        }

        if (!empty($data->country)) {
            $history = new AuditProgramAuditTrial();
            $history->AuditProgram_id = $data->id;
            $history->activity_type = 'Country';
            $history->previous = "Null";
            $history->current = $data->country;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->save();
        }

        if (!empty($data->City)) {
            $history = new AuditProgramAuditTrial();
            $history->AuditProgram_id = $data->id;
            $history->activity_type = 'City';
            $history->previous = "Null";
            $history->current = $data->City;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->save();
        }

        if (!empty($data->state)) {
            $history = new AuditProgramAuditTrial();
            $history->AuditProgram_id = $data->id;
            $history->activity_type = 'State/District';
            $history->previous = "Null";
            $history->current = $data->state;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->save();
        }

        if (!empty($data->attachments)) {
            $history = new AuditProgramAuditTrial();
            $history->AuditProgram_id = $data->id;
            $history->activity_type = 'Attached Files';
            $history->previous = "Null";
            $history->current = $data->attachments;
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
    public function UpdateAuditProgram(request $request, $id)
    {


        if (!$request->short_description) {
            toastr()->info("Short Description is required");
            return redirect()->back()->withInput();
        }
        $lastDocument = AuditProgram::find($id);
        $data = AuditProgram::find($id);
        // $data->record = ((RecordNumber::first()->value('counter')) + 1);
        $data->short_description = $request->short_description;

        $data->initiated_through = $request->initiated_through;
        $data->initiated_through_req = $request->initiated_through_req;
        $data->type_other = $request->type_other;
        $data->repeat = $request->repeat;
        $data->repeat_nature = $request->repeat_nature;
        $data->due_date_extension = $request->due_date_extension;

        $data->assign_to = $request->assign_to;
        // $data->assign_to = is_array($request->assign_to) ? implode(',', $request->assign_to) : $request->assign_to;

        $data->due_date = $request->due_date;
        $data->Initiator_Group = $request->Initiator_Group;
        $data->initiator_group_code = $request->initiator_group_code;
        $data->type = $request->type;
        $data->year = $request->year;
        $data->Quarter = $request->Quarter;
        $data->description = $request->description;
        $data->comments = $request->comments;
        $data->related_url = $request->related_url;
        $data->url_description = $request->url_description;
        //$data->suggested_audits = $request->suggested_audits;
        $data->zone = $request->zone;
        $data->country = $request->country;
        $data->City = $request->City;
        $data->state = $request->state;
        $data->severity1_level = $request->severity1_level;
        if (!empty($request->attachments)) {
            $files = [];
            if ($request->hasfile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $name = $request->name . 'attachments' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
            $data->attachments = json_encode($files);
        }
        $data->update();

        // ------------------------------
        $data1 = AuditProgramGrid::where('audit_program_id', $data->id)->first();
        $data1->delete();
        $data1 = new AuditProgramGrid();
        $data1->audit_program_id = $data->id;

        if (!empty($request->serial_number)) {
            $data1->serial_number = serialize($request->serial_number);
        }
        if (!empty($request->Auditees)) {
            $data1->auditor = serialize($request->Auditees);
        }
        if (!empty($request->start_date)) {
            $data1->start_date = serialize($request->start_date);
        }
        if (!empty($request->end_date)) {
            $data1->end_date = serialize($request->end_date);
        }
        if (!empty($request->lead_investigator)) {
            $data1->lead_investigator = serialize($request->lead_investigator);
        }
        if (!empty($request->comment)) {
            $data1->comment = serialize($request->comment);
        }
        $data1->save();

        // --------------------

        if ($lastDocument->short_description != $data->short_description || !empty($request->short_description_comment)) {
            $history = new AuditProgramAuditTrial();
            $history->AuditProgram_id = $id;
            $history->activity_type = 'Short Description';
            $history->previous = $lastDocument->short_description;
            $history->current = $data->short_description;
            $history->comment = $request->short_description_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }

        if ($lastDocument->initiator_group_code != $data->initiator_group_code || !empty($request->initiator_group_code_comment)) {
            $history = new AuditProgramAuditTrial();
            $history->AuditProgram_id = $id;
            $history->activity_type = 'Initiator Group Code';
            $history->previous = $lastDocument->initiator_group_code;
            $history->current = $data->initiator_group_code;
            $history->comment = $request->initiator_group_code_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }

        if ($lastDocument->severity1_level != $data->severity1_level || !empty($request->severity1_level_comment)) {
            $history = new AuditProgramAuditTrial();
            $history->AuditProgram_id = $id;
            $history->activity_type = 'Severity Level';
            $history->previous = $lastDocument->severity1_level;
            $history->current = $data->severity1_level;
            $history->comment = $request->severity1_level_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }


        if ($lastDocument->initiated_through != $data->initiated_through || !empty($request->initiated_through_comment)) {
            $history = new AuditProgramAuditTrial();
            $history->AuditProgram_id = $id;
            $history->activity_type = 'Initiated Through';
            $history->previous = $lastDocument->initiated_through;
            $history->current = $data->initiated_through;
            $history->comment = $request->initiated_through_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }


        if ($lastDocument->initiated_through_req != $data->initiated_through_req || !empty($request->initiated_through_req_comment)) {
            $history = new AuditProgramAuditTrial();
            $history->AuditProgram_id = $id;
            $history->activity_type = 'Others';
            $history->previous = $lastDocument->initiated_through_req;
            $history->current = $data->initiated_through_req;
            $history->comment = $request->initiated_through_req_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }

        if ($lastDocument->type_other != $data->type_other || !empty($request->type_other_comment)) {
            $history = new AuditProgramAuditTrial();
            $history->AuditProgram_id = $id;
            $history->activity_type = 'Type(Others)';
            $history->previous = $lastDocument->type_other;
            $history->current = $data->type_other;
            $history->comment = $request->type_other_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }


        if ($lastDocument->due_date_extension != $data->due_date_extension || !empty($request->due_date_extension_comment)) {
            $history = new AuditProgramAuditTrial();
            $history->AuditProgram_id = $id;
            $history->activity_type = 'Due Date Extension Justification';
            $history->previous = $lastDocument->due_date_extension;
            $history->current = $data->due_date_extension;
            $history->comment = $request->due_date_extension_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        // if ($lastDocument->assign_to != $data->assign_to || !empty($request->assign_to_comment)) {

        //     $history = new AuditProgramAuditTrial();
        //     $history->AuditProgram_id = $id;
        //     $history->activity_type = 'Assigned to';
        //     $history->previous = Helpers::getInitiatorName($lastDocument->assign_to);
        //     $history->current = Helpers::getInitiatorName($request->assign_to);
        //     $history->comment = $request->assign_to_comment;
        //     $history->user_id = Auth::user()->id;
        //     $history->user_name = Auth::user()->name;
        //     $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        //     $history->origin_state = $lastDocument->status;
        //     $history->save();
        // }

        if ($lastDocument->assign_to != $data->assign_to || !empty($request->assign_to_comment)) {
            $history = new AuditProgramAuditTrial();
            $history->AuditProgram_id = $data->id;
            $history->activity_type = 'Assigned To';
            $history->previous = Helpers::getInitiatorName($lastDocument->assign_to);
            $history->current = Helpers::getInitiatorName($data->assign_to);
            $history->comment = $request->assign_to_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }

        if ($lastDocument->type != $data->type || !empty($request->type_comment)) {

            $history = new AuditProgramAuditTrial();
            $history->AuditProgram_id = $id;
            $history->activity_type = 'Type';
            $history->previous = $lastDocument->type;
            $history->current = $data->type;
            $history->comment = $request->type_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->year != $data->year || !empty($request->year_comment)) {

            $history = new AuditProgramAuditTrial();
            $history->AuditProgram_id = $id;
            $history->activity_type = 'Year';
            $history->previous = $lastDocument->year;
            $history->current = $data->year;
            $history->comment = $request->year_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->Quarter != $data->Quarter || !empty($request->Quarter_comment)) {

            $history = new AuditProgramAuditTrial();
            $history->AuditProgram_id = $id;
            $history->activity_type = 'Quarter';
            $history->previous = $lastDocument->Quarter;
            $history->current = $data->Quarter;
            $history->comment = $request->Quarter_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->description != $data->description || !empty($request->description_comment)) {

            $history = new AuditProgramAuditTrial();
            $history->AuditProgram_id = $id;
            $history->activity_type = 'Description';
            $history->previous = $lastDocument->description;
            $history->current = $data->description;
            $history->comment = $request->description_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->comments != $data->comments || !empty($request->comments_comment)) {

            $history = new AuditProgramAuditTrial();
            $history->AuditProgram_id = $id;
            $history->activity_type = 'Comments';
            $history->previous = $lastDocument->comments;
            $history->current = $data->comments;
            $history->comment = $request->comments_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }

        if ($lastDocument->related_url != $data->related_url || !empty($request->related_url_comment)) {

            $history = new AuditProgramAuditTrial();
            $history->AuditProgram_id = $id;
            $history->activity_type = 'Related URL';
            $history->previous = $lastDocument->related_url;
            $history->current = $data->related_url;
            $history->comment = $request->related_url_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }

        if ($lastDocument->url_description != $data->url_description || !empty($request->url_description_comment)) {

            $history = new AuditProgramAuditTrial();
            $history->AuditProgram_id = $id;
            $history->activity_type = "URl's Description";
            $history->previous = $lastDocument->url_description;
            $history->current = $data->url_description;
            $history->comment = $request->related_url_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }

        if ($lastDocument->Initiator_Group != $data->Initiator_Group || !empty($request->Initiator_Group_comment)) {

            $history = new AuditProgramAuditTrial();
            $history->AuditProgram_id = $id;
            $history->activity_type = 'Initiator Group';
            $history->previous = Helpers::getInitiatorGroupFullName($lastDocument->Initiator_Group);
            $history->current = Helpers::getInitiatorGroupFullName($data->Initiator_Group);
            $history->comment = $request->date_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->zone != $data->zone || !empty($request->zone_comment)) {

            $history = new AuditProgramAuditTrial();
            $history->AuditProgram_id = $id;
            $history->activity_type = 'Zone';
            $history->previous = $lastDocument->zone;
            $history->current = $data->zone;
            $history->comment = $request->zone_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->country != $data->country || !empty($request->country_comment)) {

            $history = new AuditProgramAuditTrial();
            $history->AuditProgram_id = $id;
            $history->activity_type = 'Country';
            $history->previous = $lastDocument->country;
            $history->current = $data->country;
            $history->comment = $request->country_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->City != $data->City || !empty($request->City_comment)) {

            $history = new AuditProgramAuditTrial();
            $history->AuditProgram_id = $id;
            $history->activity_type = 'City';
            $history->previous = $lastDocument->City;
            $history->current = $data->City;
            $history->comment = $request->City_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->state != $data->state || !empty($request->state_comment)) {

            $history = new AuditProgramAuditTrial();
            $history->AuditProgram_id = $id;
            $history->activity_type = 'State/District';
            $history->previous = $lastDocument->state;
            $history->current = $data->state;
            $history->comment = $request->state_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->attachments != $data->attachments || !empty($request->attachments_comment)) {

            $history = new AuditProgramAuditTrial();
            $history->AuditProgram_id = $id;
            $history->activity_type = 'Attached Files';
            $history->previous = $lastDocument->attachments;
            $history->current = $data->attachments;
            $history->comment = $request->attachments_comment;
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


    public function AuditProgramShow($id)
    {

        $data = AuditProgram::find($id);
        $data->record = str_pad($data->record, 4, '0', STR_PAD_LEFT);
        $data->assign_to_name = User::where('id', $data->assign_id)->value('name');
        $data->initiator_name = User::where('id', $data->initiator_id)->value('name');
        $AuditProgramGrid = AuditProgramGrid::where('audit_program_id', $id)->first();
        $startdate = [];
        if($AuditProgramGrid->start_date){
            $startdate = unserialize($AuditProgramGrid->start_date);
        }
        $enddate = [];
        if($AuditProgramGrid->end_date){
            $enddate = unserialize($AuditProgramGrid->end_date);
        }

        $client = new Client();

        // Get Country List
        try {
            $countryList = $client->get('https://geodata.phplift.net/api/index.php?type=getCountries');
            $data->countryArr = json_decode($countryList->getBody(), true);
        } catch (RequestException $e) {
            $data->countryArr = [];
            // You can log the error if needed
        }

        // Get State List (only if countryId exists)
        if (!empty($data->country)) {
            try {
                $stateList = $client->get('https://geodata.phplift.net/api/index.php?type=getStates&countryId=' . $data->country);
                $data->stateArr = json_decode($stateList->getBody(), true);
            } catch (RequestException $e) {
                $data->stateArr = [];
            }
        } else {
            $data->stateArr = [];
        }

        // Get City List (only if stateId exists)
        if (!empty($data->state)) {
            try {
                $cityList = $client->get('https://geodata.phplift.net/api/index.php?type=getCities&stateId=' . $data->state);
                $data->cityArr = json_decode($cityList->getBody(), true);
            } catch (RequestException $e) {
                $data->cityArr = [];
            }
        } else {
            $data->cityArr = [];
        }

        // Fetch old records
        $old_record = AuditProgram::select('id', 'division_id', 'record', 'created_at')->get();

        return view('frontend.audit-program.view', compact('data', 'AuditProgramGrid', 'startdate', 'enddate', 'old_record'));
    }


    public function AuditStateChange(Request $request, $id)
    {
        if ($request->username == Auth::user()->email && Hash::check($request->password, Auth::user()->password)) {
            $changeControl = AuditProgram::find($id);
            $lastDocument = AuditProgram::find($id);

            if ($changeControl->stage == 1) {
                $changeControl->stage = "2";
                $changeControl->status = "Pending Approval";
                $changeControl->submitted_by = Auth::user()->name;
                $changeControl->submitted_on = Carbon::now()->format('d-M-Y');
                    $history = new AuditProgramAuditTrial();
                    $history->AuditProgram_id = $id;
                    $history->activity_type = 'Activity Log';
                    $history->previous = $lastDocument->status;
                    $history->current = "Pending Approval";
                    $history->comment = $request->comment;
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $lastDocument->status;
                    $history->stage = "Submitted";
                    $history->save();

                    $list = Helpers::getAuditManagerUserList($changeControl->division_id);

                    $userIds = collect($list)->pluck('user_id')->toArray();
                    $users = User::whereIn('id', $userIds)->select('id', 'name', 'email')->get();
                    $userIdNew = $users->pluck('id')->implode(',');
                    $userId = $users->pluck('name')->implode(',');
                    if($userId){
                            $auditNoti = new AuditProgramAuditTrial();
                            $auditNoti->AuditProgram_id = $id;
                            $auditNoti->activity_type = "Notification";
                            $auditNoti->action = 'Notification';
                            $auditNoti->comment = "";
                            $auditNoti->user_id = Auth::user()->id;
                            $auditNoti->user_name = Auth::user()->name;
                            $auditNoti->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                            $auditNoti->origin_state = "Not Applicable";
                            $auditNoti->previous = $lastDocument->status;
                            $auditNoti->current = "Submitted";
                            $auditNoti->stage = "";
                            $auditNoti->action_name = "";
                            $auditNoti->mailUserId = $userIdNew;
                            $auditNoti->role_name = "Initiator";
                            $auditNoti->save();
                    }

                // dd($list);
                // foreach ($list as $u) {
                //     $email = Helpers:: getAllUserEmail($u->user_id);
                //     if (!empty($email)) {
                //         try {
                //             info('Sending mail to', [$email]);
                //             Mail::send(
                //                 'mail.view-mail',
                //                 ['data' => $changeControl,'site'=>'Audit Program','history' => 'Submitted', 'process' => 'Audit Program', 'comment' => $history->comment,'user'=> Auth::user()->name],
                //                 function ($message) use ($email, $changeControl) {
                //                  $message->to($email)
                //                  ->subject("QMS Notification: Audit Program , Record #" . str_pad($changeControl->record, 4, '0', STR_PAD_LEFT) . " - Activity: Submitted Performed"); }
                //                 );

                //         } catch (\Exception $e) {
                //             \Log::error('Mail failed to send: ' . $e->getMessage());
                //         }
                //     }
                //     // }
                // }

                foreach ($list as $u) {
                    try {
                        $email = Helpers::getAllUserEmail($u->user_id);
                        if ($email !== null) {
                            $data = ['data' => $changeControl,'site'=>'Audit Program','history' => 'Submitted', 'process' => 'Audit Program', 'comment' => $history->comment,'user'=> Auth::user()->name];

                            SendMail::dispatch($data, $email, $changeControl, 'Audit Program');
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
                $changeControl->approved_by = Auth::user()->name;
                $changeControl->approved_on = Carbon::now()->format('d-M-Y');
                    $history = new AuditProgramAuditTrial();
                    $history->AuditProgram_id = $id;
                    $history->activity_type = 'Activity Log';
                    $history->previous = $lastDocument->status;
                    $history->current = "Pending Audit";
                    $history->comment = $request->comment;
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $lastDocument->status;
                    $history->stage = 'Approved';
                    $history->save();
                $changeControl->update();
                toastr()->success('Document Sent');
                return back();
            }
            if ($changeControl->stage == 3) {
                $changeControl->stage = "4";
                $changeControl->status = "Closed - Done";
                $changeControl->Audit_Completed_By = Auth::user()->name;
                $changeControl->Audit_Completed_On = Carbon::now()->format('d-M-Y');
                        $history = new AuditProgramAuditTrial();
                        $history->AuditProgram_id = $id;
                        $history->activity_type = 'Activity Log';
                        $history->previous = $lastDocument->status;
                        $history->current = "Closed - Done";
                        $history->comment = $request->comment;
                        $history->user_id = Auth::user()->id;
                        $history->user_name = Auth::user()->name;
                        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                        $history->origin_state = $lastDocument->status;
                        $history->stage = 'Audit Completed';
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

    public function AuditRejectStateChange(Request $request, $id)
    {
        if ($request->username == Auth::user()->email && Hash::check($request->password, Auth::user()->password)) {
            $changeControl = AuditProgram::find($id);
            $lastDocument = AuditProgram::find($id);

            if ($changeControl->stage == 2) {
                $changeControl->stage = "1";
                $changeControl->status = "Opened";
                $changeControl->rejected_by = Auth::user()->name;
                $changeControl->rejected_on  = Carbon::now()->format('d-M-Y');
                        $history = new AuditProgramAuditTrial();
                        $history->AuditProgram_id = $id;
                        $history->activity_type = 'Activity Log';
                        $history->previous = $lastDocument->status;
                        $history->current = "Opened";
                        $history->comment = $request->comment;
                        $history->user_id = Auth::user()->id;
                        $history->user_name = Auth::user()->name;
                        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                        $history->origin_state = $lastDocument->status;
                        $history->stage = 'Reject';
                        $history->save();

                        $list = Helpers::getInitiatorUserList($changeControl->division_id);
                        $userIds = collect($list)->pluck('user_id')->toArray();
                        $users = User::whereIn('id', $userIds)->select('id', 'name', 'email')->get();
                        $userIdNew = $users->pluck('id')->implode(',');
                        $userId = $users->pluck('name')->implode(',');
                        if($userId){
                                $auditNoti = new AuditProgramAuditTrial();
                                $auditNoti->AuditProgram_id = $id;
                                $auditNoti->activity_type = "Notification";
                                $auditNoti->action = 'Notification';
                                $auditNoti->comment = "";
                                $auditNoti->user_id = Auth::user()->id;
                                $auditNoti->user_name = Auth::user()->name;
                                $auditNoti->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                                $auditNoti->origin_state = "Not Applicable";
                                $auditNoti->previous = $lastDocument->status;
                                $auditNoti->current = "Reject";
                                $auditNoti->stage = "";
                                $auditNoti->action_name = "";
                                $auditNoti->mailUserId = $userIdNew;
                                $auditNoti->role_name = " Audit Manager";
                                $auditNoti->save();
                        }
                        // dd($list);
                        // foreach ($list as $u) {
                        //     $email = Helpers:: getAllUserEmail($u->user_id);
                        //     if (!empty($email)) {
                        //         try {
                        //             info('Sending mail to', [$email]);
                        //             Mail::send(
                        //                 'mail.view-mail',
                        //                 ['data' => $changeControl,'site'=>'Audit Program','history' => 'Rejected', 'process' => 'Audit Program', 'comment' => $history->comment,'user'=> Auth::user()->name],
                        //                 function ($message) use ($email, $changeControl) {
                        //                  $message->to($email)
                        //                  ->subject("QMS Notification: Audit Program , Record #" . str_pad($changeControl->record, 4, '0', STR_PAD_LEFT) . " - Activity: Rejected Performed"); }
                        //                 );

                        //         } catch (\Exception $e) {
                        //             \Log::error('Mail failed to send: ' . $e->getMessage());
                        //         }
                        //     }
                        //     // }
                        // }

                        foreach ($list as $u) {
                            try {
                                $email = Helpers::getAllUserEmail($u->user_id);
                                if ($email !== null) {
                                    $data = ['data' => $changeControl,'site'=>'Audit Program','history' => 'Rejected', 'process' => 'Audit Program', 'comment' => $history->comment,'user'=> Auth::user()->name];

                                    SendMail::dispatch($data, $email, $changeControl, 'Audit Program');
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

    public function AuditProgramCancel(Request $request, $id)
    {
        if ($request->username == Auth::user()->email && Hash::check($request->password, Auth::user()->password)) {
            $changeControl = AuditProgram::find($id);
            $lastDocument = AuditProgram::find($id);

            if ($changeControl->stage == 1) {
                $changeControl->stage = "0";
                $changeControl->status = "Closed - Cancelled";
                $changeControl->cancelled_by   = Auth::user()->name;
                $changeControl->cancelled_on = Carbon::now()->format('d-M-Y');
                            $history = new AuditProgramAuditTrial();
                            $history->AuditProgram_id = $id;
                            $history->activity_type = 'Activity Log';
                            $$history->previous = $lastDocument->status;
                            $history->current = "Closed - Cancelled";
                            $history->comment = $request->comment;
                            $history->user_id = Auth::user()->id;
                            $history->user_name = Auth::user()->name;
                            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                            $history->origin_state = $lastDocument->status;
                            $history->stage = 'Cancelled';
                            $history->save();
                $changeControl->update();
                toastr()->success('Document Sent');
                return back();
            }
            if ($changeControl->stage == 2) {
                $changeControl->stage = "0";
                $changeControl->status = "Closed - Cancelled";
                $changeControl->cancelled_by  = Auth::user()->name;
                $changeControl->cancelled_on = Carbon::now()->format('d-M-Y');
                        $history = new AuditProgramAuditTrial();
                        $history->AuditProgram_id = $id;
                        $history->activity_type = 'Activity Log';
                        $history->previous = $lastDocument->status;
                        $history->current = "Closed - Cancelled";
                        $history->comment = $request->comment;
                        $history->user_id = Auth::user()->id;
                        $history->user_name = Auth::user()->name;
                        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                        $history->origin_state = $lastDocument->status;
                        $history->stage = 'Cancelled';
                        $history->save();
                $changeControl->update();
                toastr()->success('Document Sent');
                return back();
            }
            if ($changeControl->stage == 3) {
                $changeControl->stage = "0";
                $changeControl->status = "Closed - Cancelled";
                $changeControl->cancelled_by  = Auth::user()->name;
                $changeControl->cancelled_on = Carbon::now()->format('d-M-Y');
                        $history = new AuditProgramAuditTrial();
                        $history->AuditProgram_id = $id;
                        $history->activity_type = 'Activity Log';
                        $history->previous = $lastDocument->status;
                        $history->current = "Closed - Cancelled";
                        $history->comment = $request->comment;
                        $history->user_id = Auth::user()->id;
                        $history->user_name = Auth::user()->name;
                        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                        $history->origin_state = $lastDocument->status;
                        $history->stage = 'Cancelled';
                        $history->save();
                $changeControl->update();
                toastr()->success('Document Sent');
                return back();
            }
        }
    }

    public function AuditProgramTrialShow($id)
    {
        $audit = AuditProgramAuditTrial::where('AuditProgram_id', $id)->orderByDESC('id')->get()->unique('activity_type');
        $today = Carbon::now()->format('d-m-y');
        $document = AuditProgram::where('id', $id)->first();
        $document->initiator = User::where('id', $document->initiator_id)->value('name');

        return view('frontend.audit-program.audit-trial', compact('audit', 'document', 'today'));
    }
    public function auditProgramDetails($id)
    {

        $detail = AuditProgramAuditTrial::find($id);

        $detail_data = AuditProgramAuditTrial::where('activity_type', $detail->activity_type)->where('AuditProgram_id', $detail->AuditProgram_id)->latest()->get();

        $doc = AuditProgram::where('id', $detail->AuditProgram_id)->first();

        $doc->origiator_name = User::find($doc->initiator_id);
        return view('frontend.audit-program.audit-trial-inner', compact('detail', 'doc', 'detail_data'));
    }

    public function child_audit_program(Request $request, $id)
    {
        $parent_id = $id;
        $parent_type = "Audit_Program";
        $record_number = ((RecordNumber::first()->value('counter')) + 1);
        $record_number = str_pad($record_number, 4, '0', STR_PAD_LEFT);
        $currentDate = Carbon::now();
        $formattedDate = $currentDate->addDays(30);
        $due_date = $formattedDate->format('d-M-Y');
        $old_record = InternalAudit::select('id', 'division_id', 'record', 'created_at')->get();
        $capa_old_record = InternalAudit::select('id', 'division_id', 'record', 'created_at')->get();
        $cc_old_record = InternalAudit::select('id', 'division_id', 'record', 'created_at')->get();
        $rca_old_record = InternalAudit::select('id', 'division_id', 'record', 'created_at')->get();
        $action_items_old_record = InternalAudit::select('id', 'division_id', 'record', 'created_at')->get();
      
        $parent_division_id = InternalAudit::where('id', $id)->value('division_id');
        $parent_initiator_id = InternalAudit::where('id', $id)->value('initiator_id');
        
    //   dd($parent_division_id);

        if ($request->child_type == "Internal_Audit") {
            return view('frontend.forms.audit', compact('old_record','record_number', 'due_date', 'parent_id', 'parent_type',
            'capa_old_record','cc_old_record','rca_old_record','action_items_old_record','parent_division_id','parent_initiator_id'));
        }
        if ($request->child_type == "extension") {
            $parent_due_date = "";
            $parent_id = $id;
            $parent_name = $request->parent_name;
            if ($request->due_date) {
                $parent_due_date = $request->due_date;
            }

            $record_number = ((RecordNumber::first()->value('counter')) + 1);
            $record_number = str_pad($record_number, 4, '0', STR_PAD_LEFT);
            return view('frontend.forms.extension', compact('parent_id', 'parent_name', 'record_number', 'parent_due_date'));
        }
        else {
            return view('frontend.forms.auditee', compact('old_record','record_number', 'due_date', 'parent_id', 'parent_type','parent_division_id','parent_initiator_id'));
        }
    }

    public static function singleReport($id)
    {
        $data = AuditProgram::find($id);
        $AuditProgramGrid = AuditProgramGrid::where('audit_program_id', $id)->first();
        $startdate = [];
        if($AuditProgramGrid->start_date){
            $startdate = unserialize($AuditProgramGrid->start_date);
        }
        $enddate = [];
        if($AuditProgramGrid->end_date){
            $enddate = unserialize($AuditProgramGrid->end_date);
        }

        if (!empty($data)) {
            $data->originator = User::where('id', $data->initiator_id)->value('name');
            $pdf = App::make('dompdf.wrapper');
            $time = Carbon::now();
            $pdf = PDF::loadview('frontend.audit-program.singleReport', compact('data','AuditProgramGrid'))
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
            return $pdf->stream('Audit-Program' . $id . '.pdf');
        }
    }

    public static function auditReport($id)
    {
        $doc = AuditProgram::find($id);
        if (!empty($doc)) {
            $doc->originator = User::where('id', $doc->initiator_id)->value('name');
            $data = AuditProgramAuditTrial::where('AuditProgram_id', $id)->get();
            $pdf = App::make('dompdf.wrapper');
            $time = Carbon::now();
            $pdf = PDF::loadview('frontend.audit-program.auditReport', compact('data', 'doc'))
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
            return $pdf->stream('AuditProgram-AuditTrial' . $id . '.pdf');
        }
    }
}
