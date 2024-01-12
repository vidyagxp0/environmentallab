<?php

namespace App\Http\Controllers\rcms;

use App\Http\Controllers\Controller;
use App\Models\ActionItem;
use App\Models\Auditee;
use App\Models\AuditProgram;
use App\Models\Capa;
use App\Models\CC;
use App\Models\EffectivenessCheck;
use App\Models\InternalAudit;
use App\Models\LabIncident;
use App\Models\ManagementReview;
use App\Models\RecordNumber;
use App\Models\ManagementAuditTrial;
use App\Models\ManagementReviewDocDetails;
use App\Models\RiskManagement;
use App\Models\RoleGroup;
use App\Models\RootCauseAnalysis;
use App\Models\User;
use Carbon\Carbon;
use PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ManagementReviewController extends Controller
{

    public function meeting()
    {
        $record_number = ((RecordNumber::first()->value('counter')) + 1);
        $record_number = str_pad($record_number, 4, '0', STR_PAD_LEFT);
        $currentDate = Carbon::now();
        $formattedDate = $currentDate->addDays(30);
        $due_date = $formattedDate->format('Y-m-d');

        return view("frontend.forms.meeting", compact('due_date', 'record_number'));
    }

    public function managestore(Request $request)
    {
        // return $request;

        if (!$request->short_description) {
            toastr()->error("Short description is required");
            return redirect()->back();
        }
        $management = new ManagementReview();
        $management->form_type = "management-review";
        $management->division_id = $request->division_id;
        $management->record = ((RecordNumber::first()->value('counter')) + 1);
        $management->initiator_id = Auth::user()->id;
        $management->intiation_date = $request->intiation_date;
        $management->division_code = $request->division_code;
        $management->short_description = $request->short_description;
        $management->assigned_to = $request->assigned_to;
        $management->due_date = $request->due_date;
        $management->type = $request->type;
        $management->start_date = $request->start_date;
        $management->end_date = $request->end_date;
        $management->attendees = $request->attendees;
        $management->agenda = $request->agenda;
        $management->description = $request->description;
        $management->attachment = $request->attachment;
        $management->inv_attachment = $request->inv_attachment;
        $management->actual_start_date = $request->actual_start_date;
        $management->actual_end_date = $request->actual_end_date;
        $management->meeting_minute = $request->meeting_minute;
        $management->decision = $request->decision;
        $management->zone = $request->zone;
        $management->country = $request->country;
        $management->city = $request->city;
        $management->site_name = $request->site_name;
        $management->building = $request->building;
        $management->floor = $request->floor;
        $management->room = $request->room;
        $management->status = 'Opened';
        $management->stage = 1;
        $management->save();
        $record = RecordNumber::first();
        $record->counter = ((RecordNumber::first()->value('counter')) + 1);
        $record->update();
            // --------------agenda--------------
        $data1 = new ManagementReviewDocDetails();
        $data1->review_id = $management->id;
        $data1->type = "agenda";
        if (!empty($request->date)) {
            $data1->date = serialize($request->date);
        }
        if (!empty($request->topic)) {
            $data1->topic = serialize($request->topic);
        }
        if (!empty($request->responsible)) {
            $data1->responsible = serialize($request->responsible);
        }
        if (!empty($request->start_time)) {
            $data1->start_time = serialize($request->start_time);
        }
        if (!empty($request->end_time)) {
            $data1->end_time = serialize($request->end_time);
        }
        if (!empty($request->comment)) {
            $data1->comment = serialize($request->comment);
        }
        $data1->save();



        $history = new ManagementAuditTrial();
        $history->ManagementReview_id = $management->id;
        $history->activity_type = 'Short Description';
        $history->previous = "Null";
        $history->current = $management->short_description;
        $history->comment = "NA";
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $management->status;
        $history->save();

        $history = new ManagementAuditTrial();
        $history->ManagementReview_id = $management->id;
        $history->activity_type = 'Assigned To';
        $history->previous = "Null";
        $history->current = $management->assigned_to;
        $history->comment = "NA";
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $management->status;
        $history->save();


        $history = new ManagementAuditTrial();
        $history->ManagementReview_id = $management->id;
        $history->activity_type = 'Date Due';
        $history->previous = "Null";
        $history->current = $management->due_date;
        $history->comment = "NA";
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $management->status;
        $history->save();


        $history = new ManagementAuditTrial();
        $history->ManagementReview_id = $management->id;
        $history->activity_type = 'Type';
        $history->previous = "Null";
        $history->current = $management->type;
        $history->comment = "NA";
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $management->status;
        $history->save();


        $history = new ManagementAuditTrial();
        $history->ManagementReview_id = $management->id;
        $history->activity_type = 'Scheduled Start Date';
        $history->previous = "Null";
        $history->current = $management->start_date;
        $history->comment = "NA";
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $management->status;
        $history->save();


        $history = new ManagementAuditTrial();
        $history->ManagementReview_id = $management->id;
        $history->activity_type = 'Scheduled end date';
        $history->previous = "Null";
        $history->current = $management->end_date;
        $history->comment = "NA";
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $management->status;
        $history->save();


        $history = new ManagementAuditTrial();
        $history->ManagementReview_id = $management->id;
        $history->activity_type = 'Attendess';
        $history->previous = "Null";
        $history->current = $management->attendees;
        $history->comment = "NA";
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $management->status;
        $history->save();


        $history = new ManagementAuditTrial();
        $history->ManagementReview_id = $management->id;
        $history->activity_type = 'Agenda';
        $history->previous = "Null";
        $history->current = $management->agenda;
        $history->comment = "NA";
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $management->status;
        $history->save();


        $history = new ManagementAuditTrial();
        $history->ManagementReview_id = $management->id;
        $history->activity_type = 'Description';
        $history->previous = "Null";
        $history->current = $management->description;
        $history->comment = "NA";
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $management->status;
        $history->save();


        $history = new ManagementAuditTrial();
        $history->ManagementReview_id = $management->id;
        $history->activity_type = 'Attached Files';
        $history->previous = "Null";
        $history->current = $management->attachment;
        $history->comment = "NA";
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $management->status;
        $history->save();


        $history = new ManagementAuditTrial();
        $history->ManagementReview_id = $management->id;
        $history->activity_type = 'Inv Attachment';
        $history->previous = "Null";
        $history->current = $management->inv_attachment;
        $history->comment = "NA";
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $management->status;
        $history->save();


        $history = new ManagementAuditTrial();
        $history->ManagementReview_id = $management->id;
        $history->activity_type = 'Actual Start Date';
        $history->previous = "Null";
        $history->current = $management->actual_start_date;
        $history->comment = "NA";
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $management->status;
        $history->save();

        $history = new ManagementAuditTrial();
        $history->ManagementReview_id = $management->id;
        $history->activity_type = 'Actual End Date';
        $history->previous = "Null";
        $history->current = $management->actual_end_date;
        $history->comment = "NA";
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $management->status;
        $history->save();


        $history = new ManagementAuditTrial();
        $history->ManagementReview_id = $management->id;
        $history->activity_type = 'Meeting minutes';
        $history->previous = "Null";
        $history->current = $management->meeting_minute;
        $history->comment = "NA";
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $management->status;
        $history->save();


        $history = new ManagementAuditTrial();
        $history->ManagementReview_id = $management->id;
        $history->activity_type = 'Decisions';
        $history->previous = "Null";
        $history->current = $management->decision;
        $history->comment = "NA";
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $management->status;
        $history->save();


        $history = new ManagementAuditTrial();
        $history->ManagementReview_id = $management->id;
        $history->activity_type = 'Zone';
        $history->previous = "Null";
        $history->current = $management->zone;
        $history->comment = "NA";
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $management->status;
        $history->save();


        $history = new ManagementAuditTrial();
        $history->ManagementReview_id = $management->id;
        $history->activity_type = 'Country';
        $history->previous = "Null";
        $history->current = $management->country;
        $history->comment = "NA";
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $management->status;
        $history->save();


        $history = new ManagementAuditTrial();
        $history->ManagementReview_id = $management->id;
        $history->activity_type = 'City';
        $history->previous = "Null";
        $history->current = $management->city;
        $history->comment = "NA";
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $management->status;
        $history->save();


        $history = new ManagementAuditTrial();
        $history->ManagementReview_id = $management->id;
        $history->activity_type = 'Site Name';
        $history->previous = "Null";
        $history->current = $management->site_name;
        $history->comment = "NA";
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $management->status;
        $history->save();


        $history = new ManagementAuditTrial();
        $history->ManagementReview_id = $management->id;
        $history->activity_type = 'Building';
        $history->previous = "Null";
        $history->current = $management->building;
        $history->comment = "NA";
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $management->status;
        $history->save();


        $history = new ManagementAuditTrial();
        $history->ManagementReview_id = $management->id;
        $history->activity_type = 'Floor';
        $history->previous = "Null";
        $history->current = $management->floor;
        $history->comment = "NA";
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $management->status;
        $history->save();


        $history = new ManagementAuditTrial();
        $history->ManagementReview_id = $management->id;
        $history->activity_type = 'Room';
        $history->previous = "Null";
        $history->current = $management->room;
        $history->comment = "NA";
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $management->status;
        $history->save();




        toastr()->success("Record is created Successfully");
        return redirect(url('rcms/qms-dashboard'));
    }
    public function manageUpdate(Request $request, $id)
    {

        if (!$request->short_description) {
            toastr()->error("Short description is required");
            return redirect()->back();
        }
        $lastDocument = ManagementReview::find($id);
        $management = ManagementReview::find($id);
        $management->initiator_id = Auth::user()->id;
        $management->division_code = $request->division_code;
        $management->short_description = $request->short_description;
        $management->assigned_to = $request->assigned_to;
        $management->due_date = $request->due_date;
        $management->type = $request->type;
        $management->start_date = $request->start_date;
        $management->end_date = $request->end_date;
        $management->attendees = $request->attendees;
        $management->agenda = $request->agenda;
        $management->description = $request->description;
        $management->attachment = $request->attachment;
        $management->inv_attachment = $request->inv_attachment;
        $management->actual_start_date = $request->actual_start_date;
        $management->actual_end_date = $request->actual_end_date;
        $management->meeting_minute = $request->meeting_minute;
        $management->decision = $request->decision;
        $management->zone = $request->zone;
        $management->country = $request->country;
        $management->city = $request->city;
        $management->site_name = $request->site_name;
        $management->building = $request->building;
        $management->floor = $request->floor;
        $management->room = $request->room;

        $management->update();


        if ($lastDocument->short_description != $management->short_description || !empty($request->short_desc_comment)) {

            $history = new ManagementAuditTrial();
            $history->ManagementReview_id = $id;
            $history->activity_type = 'Short Description';
            $history->previous = $lastDocument->short_description;
            $history->current = $management->short_description;
            $history->comment = $request->short_desc_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }

        if ($lastDocument->assigned_to != $management->assigned_to || !empty($request->assigned_to_comment)) {

            $history = new ManagementAuditTrial();
            $history->ManagementReview_id = $id;
            $history->activity_type = 'Assigned To';
            $history->previous = $lastDocument->assigned_to;
            $history->current = $management->assigned_to;
            $history->comment = $request->assigned_to_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->due_date != $management->due_date || !empty($request->due_date_comment)) {

            $history = new ManagementAuditTrial();
            $history->ManagementReview_id = $id;
            $history->activity_type = 'Date Due';
            $history->previous = $lastDocument->due_date;
            $history->current = $management->due_date;
            $history->comment = $request->due_date_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->type != $management->type || !empty($request->type_comment)) {

            $history = new ManagementAuditTrial();
            $history->ManagementReview_id = $id;
            $history->activity_type = 'Type';
            $history->previous = $lastDocument->type;
            $history->current = $management->type;
            $history->comment = $request->type_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->start_date != $management->start_date || !empty($request->start_date_comment)) {

            $history = new ManagementAuditTrial();
            $history->ManagementReview_id = $id;
            $history->activity_type = 'Scheduled Start Date';
            $history->previous = $lastDocument->start_date;
            $history->current = $management->start_date;
            $history->comment = $request->start_date_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->end_date != $management->end_date || !empty($request->end_date_comment)) {

            $history = new ManagementAuditTrial();
            $history->ManagementReview_id = $id;
            $history->activity_type = 'Scheduled end date';
            $history->previous = $lastDocument->end_date;
            $history->current = $management->end_date;
            $history->comment = $request->end_date_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->attendees != $management->attendees || !empty($request->attendees_comment)) {

            $history = new ManagementAuditTrial();
            $history->ManagementReview_id = $id;
            $history->activity_type = 'Attendess';
            $history->previous = $lastDocument->attendees;
            $history->current = $management->attendees;
            $history->comment = $request->attendees_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->agenda != $management->agenda || !empty($request->agenda_comment)) {

            $history = new ManagementAuditTrial();
            $history->ManagementReview_id = $id;
            $history->activity_type = 'Agenda';
            $history->previous = $lastDocument->agenda;
            $history->current = $management->agenda;
            $history->comment = $request->agenda_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->description != $management->description || !empty($request->description_comment)) {

            $history = new ManagementAuditTrial();
            $history->ManagementReview_id = $id;
            $history->activity_type = 'Description';
            $history->previous = $lastDocument->description;
            $history->current = $management->description;
            $history->comment = $request->description_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->attachment != $management->attachment || !empty($request->attachment_comment)) {

            $history = new ManagementAuditTrial();
            $history->ManagementReview_id = $id;
            $history->activity_type = 'Attached Files';
            $history->previous = $lastDocument->attachment;
            $history->current = $management->attachment;
            $history->comment = $request->attachment_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->inv_attachment != $management->inv_attachment || !empty($request->inv_attachment_comment)) {

            $history = new ManagementAuditTrial();
            $history->ManagementReview_id = $id;
            $history->activity_type = 'Inv Attachment';
            $history->previous = $lastDocument->inv_attachment;
            $history->current = $management->inv_attachment;
            $history->comment = $request->inv_attachment_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->actual_start_date != $management->actual_start_date || !empty($request->actual_start_date_comment)) {

            $history = new ManagementAuditTrial();
            $history->ManagementReview_id = $id;
            $history->activity_type = 'Actual Start Date';
            $history->previous = $lastDocument->actual_start_date;
            $history->current = $management->actual_start_date;
            $history->comment = $request->actual_start_date_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->actual_end_date != $management->actual_end_date || !empty($request->actual_end_date_comment)) {

            $history = new ManagementAuditTrial();
            $history->ManagementReview_id = $id;
            $history->activity_type = 'Actual End Date';
            $history->previous = $lastDocument->actual_end_date;
            $history->current = $management->actual_end_date;
            $history->comment = $request->actual_end_date_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->meeting_minute != $management->meeting_minute || !empty($request->meeting_minute_comment)) {

            $history = new ManagementAuditTrial();
            $history->ManagementReview_id = $id;
            $history->activity_type = 'Meeting minutes';
            $history->previous = $lastDocument->meeting_minute;
            $history->current = $management->meeting_minute;
            $history->comment = $request->meeting_minute_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->decision != $management->decision || !empty($request->decision_comment)) {

            $history = new ManagementAuditTrial();
            $history->ManagementReview_id = $id;
            $history->activity_type = 'Decisions';
            $history->previous = $lastDocument->decision;
            $history->current = $management->decision;
            $history->comment = $request->decision_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->zone != $management->zone || !empty($request->zone_comment)) {

            $history = new ManagementAuditTrial();
            $history->ManagementReview_id = $id;
            $history->activity_type = 'Zone';
            $history->previous = $lastDocument->zone;
            $history->current = $management->zone;
            $history->comment = $request->zone_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->country != $management->country || !empty($request->country_comment)) {

            $history = new ManagementAuditTrial();
            $history->ManagementReview_id = $id;
            $history->activity_type = 'Country';
            $history->previous = $lastDocument->country;
            $history->current = $management->country;
            $history->comment = $request->country_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->city != $management->city || !empty($request->city_comment)) {

            $history = new ManagementAuditTrial();
            $history->ManagementReview_id = $id;
            $history->activity_type = 'City';
            $history->previous = $lastDocument->city;
            $history->current = $management->city;
            $history->comment = $request->city_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->site_name != $management->site_name || !empty($request->site_name_comment)) {

            $history = new ManagementAuditTrial();
            $history->ManagementReview_id = $id;
            $history->activity_type = 'Site Name';
            $history->previous = $lastDocument->site_name;
            $history->current = $management->site_name;
            $history->comment = $request->site_name_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->building != $management->building || !empty($request->building_comment)) {

            $history = new ManagementAuditTrial();
            $history->ManagementReview_id = $id;
            $history->activity_type = 'Building';
            $history->previous = $lastDocument->building;
            $history->current = $management->building;
            $history->comment = $request->building_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->floor != $management->floor || !empty($request->floor_comment)) {

            $history = new ManagementAuditTrial();
            $history->ManagementReview_id = $id;
            $history->activity_type = 'Floor';
            $history->previous = $lastDocument->floor;
            $history->current = $management->floor;
            $history->comment = $request->floor_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->room != $management->room || !empty($request->room_comment)) {

            $history = new ManagementAuditTrial();
            $history->ManagementReview_id = $id;
            $history->activity_type = 'Room';
            $history->previous = $lastDocument->room;
            $history->current = $management->room;
            $history->comment = $request->room_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }

        toastr()->success("Record is updated Successfully");
        return back();
    }

    public function ManagementReviewAuditTrial($id)
    {
        $audit = ManagementAuditTrial::where('ManagementReview_id', $id)->orderByDESC('id')->get()->unique('activity_type');
        $today = Carbon::now()->format('d-m-y');
        $document = ManagementReview::where('id', $id)->first();
        $document->originator = User::where('id', $document->initiator_id)->value('name');

        return view('frontend.management-review.audit-trial', compact('audit', 'document', 'today'));
    }


    public function ManagementReviewAuditDetails($id)
    {
        $detail = ManagementAuditTrial::find($id);
        $detail_data = ManagementAuditTrial::where('activity_type', $detail->activity_type)->where('ManagementReview_id', $detail->ManagementReview_id)->latest()->get();
        $doc = ManagementReview::where('id', $detail->ManagementReview_id)->first();
        $doc->origiator_name = User::find($doc->initiator_id);
        return view('frontend.management-review.audit-trial-inner', compact('detail', 'doc', 'detail_data'));
    }

    public function manageshow($id)
    {

        $data = ManagementReview::find($id);
        $data->record = str_pad($data->record, 4, '0', STR_PAD_LEFT);
        $data->assign_to_name = User::where('id', $data->assign_id)->value('name');
        $data->initiator_name = User::where('id', $data->initiator_id)->value('name');
        $agenda = ManagementReviewDocDetails::where('review_id',$data->id)->where('type',"agenda")->first();
        return view('frontend.management-review.management_review', compact(
            'data','agenda'
        ));
    }


    public function manage_send_stage(Request $request, $id)
    {


        if ($request->username == Auth::user()->email && Hash::check($request->password, Auth::user()->password)) {
            $changeControl = ManagementReview::find($id);

            if ($changeControl->stage == 1) {
                $changeControl->stage = "2";
                $changeControl->status = "In Progress";
                $changeControl->update();
                toastr()->success('Document Sent');
                return back();
            }
            if ($changeControl->stage == 2) {
                $changeControl->stage = "3";
                $changeControl->status = "Closed - Done";
                $changeControl->completed_by = Auth::user()->name;
                $changeControl->completed_on = Carbon::now()->format('d-M-Y');
                $changeControl->update();
                toastr()->success('Document Sent');
                return back();
            }
        } else {
            toastr()->error('E-signature Not match');
            return back();
        }
    }

 

    public function managementReport($id)
    {
        $managementReview = ManagementReview::find($id);
        $managementReview->internalAudit = InternalAudit::all();
        $managementReview->externalAudit = Auditee::all();
        $managementReview->capa = Capa::all();
        $managementReview->auditProgram = AuditProgram::all();
        $managementReview->LabIncident = LabIncident::all();
        $managementReview->riskAnalysis = RiskManagement::all();
        $managementReview->rootCause = RootCauseAnalysis::all();
        $managementReview->changeControl = CC::all();
        $managementReview->actionItem = ActionItem::all();
        $managementReview->effectiveNess = EffectivenessCheck::all();
        $pdf = PDF::loadview('frontend.management-review.report', compact('managementReview'))
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
        $canvas->page_text($width / 3, $height / 2, $managementReview->status, null, 60, [0, 0, 0], 2, 6, -20);
        return $pdf->stream('Management-Review' . $id . '.pdf');


    }

    public function child_management_Review(Request $request, $id)
    {
        $parent_id = $id;
        $parent_type = "Action-Item";
        $record_number = ((RecordNumber::first()->value('counter')) + 1);
        $record_number = str_pad($record_number, 4, '0', STR_PAD_LEFT);
        $currentDate = Carbon::now();
        $formattedDate = $currentDate->addDays(30);
        $due_date = $formattedDate->format('d-M-Y');
        return view('frontend.forms.action-item', compact('record_number', 'due_date', 'parent_id', 'parent_type'));
    }
}
