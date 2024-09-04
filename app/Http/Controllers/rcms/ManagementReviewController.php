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
use App\Models\QMSDivision;
use App\Models\RiskManagement;
use App\Models\RoleGroup;
use App\Models\RootCauseAnalysis;
use App\Models\User;
use App\Services\DocumentService;
use Carbon\Carbon;
use PDF;
use Helpers;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ManagementReviewController extends Controller
{

    public function meeting()
    {
       // $old_record = ManagementReview::select('id', 'division_id', 'record')->get();
        $record_number = ((RecordNumber::first()->value('counter')) + 1);
        $record_number = str_pad($record_number, 4, '0', STR_PAD_LEFT);
        $currentDate = Carbon::now();
        $formattedDate = $currentDate->addDays(30);
        $due_date = $formattedDate->format('Y-m-d');
        
        $division = QMSDivision::where('name', Helpers::getDivisionName(session()->get('division')))->first();

        if ($division) {
            $last_record = ManagementReview::where('division_id', $division->id)->latest()->first();

            if ($last_record) {
                $record_number = $last_record->record_number ? str_pad($last_record->record_number->record_number + 1, 4, '0', STR_PAD_LEFT) : '0001';
            } else {
                $record_number = '0001';
            }
        }

        return view("frontend.forms.meeting", compact('due_date', 'record_number'));
    }

    public function managestore(Request $request)
    {
         //$request->dd();
        //  return $request;

        if (!$request->short_description) {
            toastr()->error("Short description is required");
            return redirect()->back();
        }
        $management = new ManagementReview();
        //$management->record_number = ($request->record_number);
        // $management->assign_to = 1;//$request->assign_to;
         $management->priority_level = $request->priority_level;
         $management->assign_to= $request->assign_to;
         $management->Operations= $request->Operations;
         $management->requirement_products_services = $request->requirement_products_services;
         $management->design_development_product_services = $request->design_development_product_services; 
         $management->control_externally_provide_services = $request->control_externally_provide_services;
         $management->production_service_provision= $request->production_service_provision;
         $management->release_product_services = $request->release_product_services;
        $management->control_nonconforming_outputs = $request->control_nonconforming_outputs;
        $management->risk_opportunities = $request->risk_opportunities;
        $management->initiator_group_code= $request->initiator_group_code;
        $management->initiator_Group= $request->initiator_Group;
       // $management->type = $request->type;
       // $management->serial_number = 1;
        //json_encode($request->serial_number);
        //  $management->date =1; //json_encode($request->date);
        //$management->topic = json_encode($request->topic);
       // $management->responsible = json_encode ($request->responsible);

        //$management->comment = json_encode($request->comment);
        //$management->end_time = json_encode($request->end_time);
       // $management->topic = json_encode($request->topic);
        
      // $management = new ManagementReview();
        $management->form_type = "Management Review";
        $management->division_id = $request->division_id;
        $management->record = ((RecordNumber::first()->value('counter')) + 1);
        $management->initiator_id = Auth::user()->id;
        $management->intiation_date = $request->intiation_date;
        $management->division_code = $request->division_code;
        // $management->Initiator_id = $request->Initiator_id;
        $management->short_description = $request->short_description;
        $management->assigned_to = $request->assigned_to;
        $management->due_date = $request->due_date;
        $management->type = $request->type;
       
        $management->start_date = $request->start_date;
        $management->end_date = $request->end_date;
        $management->attendees = $request->attendees;
        $management->agenda = $request->agenda;
        $management->performance_evaluation = $request->performance_evaluation;
        $management->management_review_participants = $request->management_review_participants;
        $management->action_item_details =$request->action_item_details;
        $management->capa_detail_details = $request->capa_detail_details;
        $management->description = $request->description;
        $management->attachment = $request->attachment;
        //  $management->inv_attachment = json_encode($request->inv_attachment);
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
        $management->updated_at = $request->updated_at;
        $management->status = 'Opened';
        $management->stage = 1;
       
        if (!empty($request->inv_attachment)) {
            $files = [];
            if ($request->hasfile('inv_attachment')) {
                foreach ($request->file('inv_attachment') as $file) {
                    $name = $request->name . 'inv_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
            
            $management->inv_attachment= json_encode($files);
        }
        if (!empty($request->file_attchment_if_any)) {
            $files = [];
            if ($request->hasfile('file_attchment_if_any')) {
                foreach ($request->file('file_attchment_if_any') as $file) {
                    $name = $request->name . 'file_attchment_if_any' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
            
            $management->file_attchment_if_any= json_encode($files);
        }
        if (!empty($request->closure_attachments)) {
            $files = [];
            if ($request->hasfile('closure_attachments')) {
                foreach ($request->file('closure_attachments') as $file) {
                    $name = $request->name . 'closure_attachments' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
            
            $management->closure_attachments= json_encode($files);
        }
        
        $management->save();
        $record = RecordNumber::first();
        $record->counter = ((RecordNumber::first()->value('counter')) + 1);
        $record->update();


        //  $request->dd();
        
        // $management = new MeetingSummary();
        $management->risk_opportunities = $request->risk_opportunities;
        $management->external_supplier_performance = $request->external_supplier_performance;
        $management->customer_satisfaction_level = $request->customer_satisfaction_level;
        $management->budget_estimates = $request->budget_estimates; 
        $management->completion_of_previous_tasks = $request->completion_of_previous_tasks;
        $management->production_new = $request->production_new;
        $management->plans_new = $request->plans_new;
        $management->forecast_new = $request->forecast_new;
        $management->due_date_extension= $request->due_date_extension;
        $management->conclusion_new = $request->conclusion_new;
        $management->next_managment_review_date = $request->next_managment_review_date;
        $management->summary_recommendation = $request->summary_recommendation;
        $management->additional_suport_required = $request->additional_suport_required;
        // $management->file_attchment_if_any = json_encode($request->file_attchment_if_any);
       
        $management->save();


       


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

        $data2 = new ManagementReviewDocDetails();
        $data2->review_id = $management->id;
        $data2->type = "performance_evaluation";
        if (!empty($request->monitoring)) {
            $data2->monitoring = serialize($request->monitoring);
        }
        if (!empty($request->measurement)) {
            $data2->measurement = serialize($request->measurement);
        }
        if (!empty($request->analysis)) {
            $data2->analysis = serialize($request->analysis);
        }
        if (!empty($request->evaluation)) {
            $data2->evaluation = serialize($request->evaluation);
        }
        $data2->save();
          
        $data3 = new ManagementReviewDocDetails();
        $data3->review_id = $management->id;
        $data3->type = "management_review_participants";
        if (!empty($request->invited_Person)) {
            $data3->invited_Person = serialize($request->invited_Person);
        }
        if (!empty($request->designee)) {
            $data3->designee = serialize($request->designee);
        }
        if (!empty($request->department)) {
            $data3->department = serialize($request->department);
        }
        if (!empty($request->meeting_Attended)) {
            $data3->meeting_Attended = serialize($request->meeting_Attended);
        }
        if (!empty($request->designee_Name)) {
            $data3->designee_Name = serialize($request->designee_Name);
        }
        if (!empty($request->designee_Department)) {
            $data3->designee_Department = serialize($request->designee_Department);
        }
        if (!empty($request->remarks)) {
            $data3->remarks = serialize($request->remarks);
        }
        $data3->save();

        $data4 = new ManagementReviewDocDetails();
        $data4->review_id = $management->id;
        $data4->type = "action_item_details";
        
        if (!empty($request->short_desc)) {
            $data4->short_desc = serialize($request->short_desc);
        }
        if (!empty($request->date_due)) {
            $data4->date_due = serialize($request->date_due);
        }
        if (!empty($request->site)) {
            $data4->site = serialize($request->site);
        }
        if (!empty($request->responsible_person)) {
            $data4->responsible_person = serialize($request->responsible_person);
        }
        if (!empty($request->current_status)) {
            $data4->current_status = serialize($request->current_status);
        }
        if (!empty($request->date_closed)) {
            $data4->date_closed = serialize($request->date_closed);
        }
        if (!empty($request->remark)) {
            $data4->remark = serialize($request->remark);
        }
        $data4->save();

        $data5 = new ManagementReviewDocDetails();
        $data5->review_id = $management->id;
        $data5->type = "capa_detail_details";
        
        if (!empty($request->Details)) {
            $data5->Details = serialize($request->Details);
        }
        if (!empty($request->capa_type)) {
            $data5->capa_type = serialize($request->capa_type);
        }
        if (!empty($request->site2)) {
            $data5->site2 = serialize($request->site2);
        }
        if (!empty($request->responsible_person2)) {
            $data5->responsible_person2 = serialize($request->responsible_person2);
        }
        if (!empty($request->current_status2)) {
            $data5->current_status2 = serialize($request->current_status2);
        }
        if (!empty($request->date_closed2)) {
            $data5->date_closed2 = serialize($request->date_closed2);
        }
        if (!empty($request->remark2)) {
            $data5->remark2 = serialize($request->remark2);
        }
        $data5->save();

        // $history = new ManagementAuditTrial();
        // $history->ManagementReview_id = $management->id;
        // $history->activity_type = 'Short Description';
        // $history->previous = "Null";
        // $history->current = $management->short_description;
        // $history->comment = "NA";
        // $history->user_id = Auth::user()->id;
        // $history->user_name = Auth::user()->name;
        // $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        // $history->origin_state = $management->status;
        // $history->save();

        // $history = new ManagementAuditTrial();
        // $history->ManagementReview_id = $management->id;
        // $history->activity_type = 'Assigned To';
        // $history->previous = "Null";
        // $history->current = $management->assigned_to;
        // $history->comment = "NA";
        // $history->user_id = Auth::user()->id;
        // $history->user_name = Auth::user()->name;
        // $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        // $history->origin_state = $management->status;
        // $history->save();


        // $history = new ManagementAuditTrial();
        // $history->ManagementReview_id = $management->id;
        // $history->activity_type = 'Date Due';
        // $history->previous = "Null";
        // $history->current = $management->due_date;
        // $history->comment = "NA";
        // $history->user_id = Auth::user()->id;
        // $history->user_name = Auth::user()->name;
        // $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        // $history->origin_state = $management->status;
        // $history->save();


        // $history = new ManagementAuditTrial();
        // $history->ManagementReview_id = $management->id;
        // $history->activity_type = 'Type';
        // $history->previous = "Null";
        // $history->current = $management->type;
        // $history->comment = "NA";
        // $history->user_id = Auth::user()->id;
        // $history->user_name = Auth::user()->name;
        // $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        // $history->origin_state = $management->status;
        // $history->save();


        // $history = new ManagementAuditTrial();
        // $history->ManagementReview_id = $management->id;
        // $history->activity_type = 'Scheduled Start Date';
        // $history->previous = "Null";
        // $history->current = $management->start_date;
        // $history->comment = "NA";
        // $history->user_id = Auth::user()->id;
        // $history->user_name = Auth::user()->name;
        // $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        // $history->origin_state = $management->status;
        // $history->save();


        // $history = new ManagementAuditTrial();
        // $history->ManagementReview_id = $management->id;
        // $history->activity_type = 'Scheduled end date';
        // $history->previous = "Null";
        // $history->current = $management->end_date;
        // $history->comment = "NA";
        // $history->user_id = Auth::user()->id;
        // $history->user_name = Auth::user()->name;
        // $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        // $history->origin_state = $management->status;
        // $history->save();


        // $history = new ManagementAuditTrial();
        // $history->ManagementReview_id = $management->id;
        // $history->activity_type = 'Attendess';
        // $history->previous = "Null";
        // $history->current = $management->attendees;
        // $history->comment = "NA";
        // $history->user_id = Auth::user()->id;
        // $history->user_name = Auth::user()->name;
        // $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        // $history->origin_state = $management->status;
        // $history->save();


        // $history = new ManagementAuditTrial();
        // $history->ManagementReview_id = $management->id;
        // $history->activity_type = 'Agenda';
        // $history->previous = "Null";
        // $history->current = $management->agenda;
        // $history->comment = "NA";
        // $history->user_id = Auth::user()->id;
        // $history->user_name = Auth::user()->name;
        // $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        // $history->origin_state = $management->status;
        // $history->save();

        
        // $history = new ManagementAuditTrial();
        // $history->ManagementReview_id = $management->id;
        // $history->activity_type = 'Performance Evaluation';
        // $history->previous = "Null";
        // $history->current = $management->performance_evaluation;
        // $history->comment = "NA";
        // $history->user_id = Auth::user()->id;
        // $history->user_name = Auth::user()->name;
        // $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        // $history->origin_state = $management->status;
        // $history->save();

        // $history = new ManagementAuditTrial();
        // $history->ManagementReview_id = $management->id;
        // $history->activity_type = 'Management Review Participants';
        // $history->previous = "Null";
        // $history->current = $management->management_review_participants;
        // $history->comment = "NA";
        // $history->user_id = Auth::user()->id;
        // $history->user_name = Auth::user()->name;
        // $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        // $history->origin_state = $management->status;
        // $history->save();

        // $history = new ManagementAuditTrial();
        // $history->ManagementReview_id = $management->id;
        // $history->activity_type = 'Action Item Details';
        // $history->previous = "Null";
        // $history->current = $management->action_item_details;
        // $history->comment = "NA";
        // $history->user_id = Auth::user()->id;
        // $history->user_name = Auth::user()->name;
        // $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        // $history->origin_state = $management->status;
        // $history->save();

        // $history = new ManagementAuditTrial();
        // $history->ManagementReview_id = $management->id;
        // $history->activity_type = 'CAPA Details';
        // $history->previous = "Null";
        // $history->current = $management->capa_detail_details;
        // $history->comment = "NA";
        // $history->user_id = Auth::user()->id;
        // $history->user_name = Auth::user()->name;
        // $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        // $history->origin_state = $management->status;
        // $history->save();


        // $history = new ManagementAuditTrial();
        // $history->ManagementReview_id = $management->id;
        // $history->activity_type = 'Description';
        // $history->previous = "Null";
        // $history->current = $management->description;
        // $history->comment = "NA";
        // $history->user_id = Auth::user()->id;
        // $history->user_name = Auth::user()->name;
        // $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        // $history->origin_state = $management->status;
        // $history->save();


        // $history = new ManagementAuditTrial();
        // $history->ManagementReview_id = $management->id;
        // $history->activity_type = 'Attached Files';
        // $history->previous = "Null";
        // $history->current = $management->attachment;
        // $history->comment = "NA";
        // $history->user_id = Auth::user()->id;
        // $history->user_name = Auth::user()->name;
        // $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        // $history->origin_state = $management->status;
        // $history->save();


        // $history = new ManagementAuditTrial();
        // $history->ManagementReview_id = $management->id;
        // $history->activity_type = 'Inv Attachment';
        // $history->previous = "Null";
        // $history->current = $management->inv_attachment;
        // $history->comment = "NA";
        // $history->user_id = Auth::user()->id;
        // $history->user_name = Auth::user()->name;
        // $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        // $history->origin_state = $management->status;
        // $history->save();
         
        // $history = new ManagementAuditTrial();
        // $history->ManagementReview_id = $management->id;
        // $history->activity_type = 'File Attachment';
        // $history->previous = "Null";
        // $history->current = $management->file_attchment_if_any;
        // $history->comment = "NA";
        // $history->user_id = Auth::user()->id;
        // $history->user_name = Auth::user()->name;
        // $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        // $history->origin_state = $management->status;
        // $history->save();
         
        // $history = new ManagementAuditTrial();
        // $history->ManagementReview_id = $management->id;
        // $history->activity_type = 'File Attachment';
        // $history->previous = "Null";
        // $history->current = $management->closure_attachments;
        // $history->comment = "NA";
        // $history->user_id = Auth::user()->id;
        // $history->user_name = Auth::user()->name;
        // $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        // $history->origin_state = $management->status;
        // $history->save();

        // $history = new ManagementAuditTrial();
        // $history->ManagementReview_id = $management->id;
        // $history->activity_type = 'Actual Start Date';
        // $history->previous = "Null";
        // $history->current = $management->actual_start_date;
        // $history->comment = "NA";
        // $history->user_id = Auth::user()->id;
        // $history->user_name = Auth::user()->name;
        // $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        // $history->origin_state = $management->status;
        // $history->save();

        // $history = new ManagementAuditTrial();
        // $history->ManagementReview_id = $management->id;
        // $history->activity_type = 'Actual End Date';
        // $history->previous = "Null";
        // $history->current = $management->actual_end_date;
        // $history->comment = "NA";
        // $history->user_id = Auth::user()->id;
        // $history->user_name = Auth::user()->name;
        // $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        // $history->origin_state = $management->status;
        // $history->save();


        // $history = new ManagementAuditTrial();
        // $history->ManagementReview_id = $management->id;
        // $history->activity_type = 'Meeting minutes';
        // $history->previous = "Null";
        // $history->current = $management->meeting_minute;
        // $history->comment = "NA";
        // $history->user_id = Auth::user()->id;
        // $history->user_name = Auth::user()->name;
        // $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        // $history->origin_state = $management->status;
        // $history->save();


        // $history = new ManagementAuditTrial();
        // $history->ManagementReview_id = $management->id;
        // $history->activity_type = 'Decisions';
        // $history->previous = "Null";
        // $history->current = $management->decision;
        // $history->comment = "NA";
        // $history->user_id = Auth::user()->id;
        // $history->user_name = Auth::user()->name;
        // $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        // $history->origin_state = $management->status;
        // $history->save();


        // $history = new ManagementAuditTrial();
        // $history->ManagementReview_id = $management->id;
        // $history->activity_type = 'Zone';
        // $history->previous = "Null";
        // $history->current = $management->zone;
        // $history->comment = "NA";
        // $history->user_id = Auth::user()->id;
        // $history->user_name = Auth::user()->name;
        // $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        // $history->origin_state = $management->status;
        // $history->save();


        // $history = new ManagementAuditTrial();
        // $history->ManagementReview_id = $management->id;
        // $history->activity_type = 'Country';
        // $history->previous = "Null";
        // $history->current = $management->country;
        // $history->comment = "NA";
        // $history->user_id = Auth::user()->id;
        // $history->user_name = Auth::user()->name;
        // $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        // $history->origin_state = $management->status;
        // $history->save();


        // $history = new ManagementAuditTrial();
        // $history->ManagementReview_id = $management->id;
        // $history->activity_type = 'City';
        // $history->previous = "Null";
        // $history->current = $management->city;
        // $history->comment = "NA";
        // $history->user_id = Auth::user()->id;
        // $history->user_name = Auth::user()->name;
        // $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        // $history->origin_state = $management->status;
        // $history->save();


        // $history = new ManagementAuditTrial();
        // $history->ManagementReview_id = $management->id;
        // $history->activity_type = 'Site Name';
        // $history->previous = "Null";
        // $history->current = $management->site_name;
        // $history->comment = "NA";
        // $history->user_id = Auth::user()->id;
        // $history->user_name = Auth::user()->name;
        // $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        // $history->origin_state = $management->status;
        // $history->save();


        // $history = new ManagementAuditTrial();
        // $history->ManagementReview_id = $management->id;
        // $history->activity_type = 'Building';
        // $history->previous = "Null";
        // $history->current = $management->building;
        // $history->comment = "NA";
        // $history->user_id = Auth::user()->id;
        // $history->user_name = Auth::user()->name;
        // $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        // $history->origin_state = $management->status;
        // $history->save();


        // $history = new ManagementAuditTrial();
        // $history->ManagementReview_id = $management->id;
        // $history->activity_type = 'Floor';
        // $history->previous = "Null";
        // $history->current = $management->floor;
        // $history->comment = "NA";
        // $history->user_id = Auth::user()->id;
        // $history->user_name = Auth::user()->name;
        // $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        // $history->origin_state = $management->status;
        // $history->save();


        // $history = new ManagementAuditTrial();
        // $history->ManagementReview_id = $management->id;
        // $history->activity_type = 'Room';
        // $history->previous = "Null";
        // $history->current = $management->room;
        // $history->comment = "NA";
        // $history->user_id = Auth::user()->id;
        // $history->user_name = Auth::user()->name;
        // $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        // $history->origin_state = $management->status;
        // $history->save();
        if (!empty($management->record)) {
            $history = new ManagementAuditTrial();
            $history->ManagementReview_id = $management->id;
            $history->activity_type = 'Record Number';
            $history->previous = "Null";
            $history->current = Helpers::getDivisionName(session()->get('division')) . "/EA/" . Helpers::year($management->created_at) . "/" . str_pad($management->record, 4, '0', STR_PAD_LEFT);
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
              $history->origin_state = $management->status;
                $history->save();
            }
            if (!empty($management->division_code)) {
                $history = new ManagementAuditTrial();
                $history->ManagementReview_id = $management->id;
                $history->activity_type = 'Site/Location Code';
                $history->previous = "Null";
                $history->current = $management->division_code;
                $history->comment = "NA";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                  $history->origin_state = $management->status;
                    $history->save();
                }
                // if (!empty($management->division_code)) {
                //     $history = new ManagementAuditTrial();
                //     $history->ManagementReview_id = $management->id;
                //     $history->activity_type = 'Site/Location Code';
                //     $history->previous = "Null";
                //     $history->current = $management->division_code;
                //     $history->comment = "NA";
                //     $history->user_id = Auth::user()->id;
                //     $history->user_name = Auth::user()->name;
                //     $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                //       $history->origin_state = $management->status;
                //         $history->save();
                //     }
                    if (!empty($management->initiator_id)) {
                        $history = new ManagementAuditTrial();
                        $history->ManagementReview_id = $management->id;
                        $history->activity_type = 'Initiator';
                        $history->previous = "Null";
                        $history->current = Helpers::getInitiatorName($management->initiator_id);
                        $history->comment = "NA";
                        $history->user_id = Auth::user()->id;
                        $history->user_name = Auth::user()->name;
                        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                          $history->origin_state = $management->status;
                            $history->save();
                        }
                //     if (!empty($management->division_code)) {
                //         $history = new ManagementAuditTrial();
                //         $history->ManagementReview_id = $management->id;
                //         $history->activity_type = 'Site/Location Code';
                //         $history->previous = "Null";
                //         $history->current = $management->division_code;
                //         $history->comment = "NA";
                //         $history->user_id = Auth::user()->id;
                //         $history->user_name = Auth::user()->name;
                //         $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                //           $history->origin_state = $management->status;
                //             $history->save();
                //  }
        if (!empty($management->short_description)) {
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
            }
            if (!empty($management->intiation_date)) {
                $history = new ManagementAuditTrial();
                $history->ManagementReview_id = $management->id;
                $history->activity_type = 'Date of Initiation';
                $history->previous = "Null";
                $history->current =  Helpers::getdateFormat($management->intiation_date);
                $history->comment = "NA";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                  $history->origin_state = $management->status;
                    $history->save();
                }
            if (!empty($management->assign_to)) {
            $history = new ManagementAuditTrial();
            $history->ManagementReview_id = $management->id;
            $history->activity_type = 'Assigned To';
            $history->previous = "Null";
            $history->current = Helpers::getInitiatorName ($management->assign_to);
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
              $history->origin_state = $management->status;
                $history->save();
            }
    
    
            if (!empty($management->due_date)) {
            $history = new ManagementAuditTrial();
            $history->ManagementReview_id = $management->id;
            $history->activity_type = 'Due Date';
            $history->previous = "Null";
            $history->current = Helpers::getdateFormat($management->due_date);
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $management->status;
            $history->save();
            }
            if (!empty($management->initiator_Group)) {
                $history = new ManagementAuditTrial();
                $history->ManagementReview_id = $management->id;
                $history->activity_type = 'Initiator Group';
                $history->previous = "Null";
                $history->current = Helpers::getInitiatorGroupFullName($management->initiator_Group);
                $history->comment = "NA";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                  $history->origin_state = $management->status;
                    $history->save();
                }
                if (!empty($management->initiator_group_code)) {
                    $history = new ManagementAuditTrial();
                    $history->ManagementReview_id = $management->id;
                    $history->activity_type = 'Initiator Group Code';
                    $history->previous = "Null";
                    $history->current = $management->initiator_group_code;
                    $history->comment = "NA";
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                      $history->origin_state = $management->status;
                        $history->save();
                    }
                    if (!empty($management->priority_level)) {
                        $history = new ManagementAuditTrial();
                        $history->ManagementReview_id = $management->id;
                        $history->activity_type = 'Priority Level';
                        $history->previous = "Null";
                        $history->current = $management->priority_level;
                        $history->comment = "NA";
                        $history->user_id = Auth::user()->id;
                        $history->user_name = Auth::user()->name;
                        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                          $history->origin_state = $management->status;
                            $history->save();
                        }
    
            if (!empty($management->type)) {
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
            }
    
    
            if (!empty($management->start_date)) {
            $history = new ManagementAuditTrial();
            $history->ManagementReview_id = $management->id;
            $history->activity_type = 'Scheduled Start Date';
            $history->previous = "Null";
            $history->current =Helpers::getdateFormat ($management->start_date);
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
              $history->origin_state = $management->status;
                $history->save();
            }
    
    
            if (!empty($management->end_date)) {
            $history = new ManagementAuditTrial();
            $history->ManagementReview_id = $management->id;
            $history->activity_type = 'Scheduled end date';
            $history->previous = "Null";
            $history->current = Helpers::getdateFormat($management->end_date);
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
              $history->origin_state = $management->status;
                $history->save();
            }
    
    
            if (!empty($management->attendees)) {
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
            }
    
    
            if (!empty($management->Agenda)) {
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
            }
    
            
            if (!empty($management->performance_evaluation)) {
            $history = new ManagementAuditTrial();
            $history->ManagementReview_id = $management->id;
            $history->activity_type = 'Performance Evaluation';
            $history->previous = "Null";
            $history->current = $management->performance_evaluation;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
              $history->origin_state = $management->status;
                $history->save();
            }
    
            if (!empty($management->management_review_participants)) {
            $history = new ManagementAuditTrial();
            $history->ManagementReview_id = $management->id;
            $history->activity_type = 'Management Review Participants';
            $history->previous = "Null";
            $history->current = $management->management_review_participants;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
              $history->origin_state = $management->status;
                $history->save();
            }
    
            if (!empty($management->action_item_details)) {
            $history = new ManagementAuditTrial();
            $history->ManagementReview_id = $management->id;
            $history->activity_type = 'Action Item Details';
            $history->previous = "Null";
            $history->current = $management->action_item_details;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
              $history->origin_state = $management->status;
                $history->save();
            }
    
            if (!empty($management->capa_detail_details)) {
            $history = new ManagementAuditTrial();
            $history->ManagementReview_id = $management->id;
            $history->activity_type = 'CAPA Details';
            $history->previous = "Null";
            $history->current = $management->capa_detail_details;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
              $history->origin_state = $management->status;
                $history->save();
            }
    
    
            if (!empty($management->description)) {
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
            }
    
    
            if (!empty($management->attachment)) {
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
            }
    
    
            if (!empty($management->inv_attachment)) {
            $history = new ManagementAuditTrial();
            $history->ManagementReview_id = $management->id;
            $history->activity_type = 'File Attachment';
            $history->previous = "Null";
            $history->current = $management->inv_attachment;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
              $history->origin_state = $management->status;
                $history->save();
            }
             
            if (!empty($management->file_attchment_if_any)) {
            $history = new ManagementAuditTrial();
            $history->ManagementReview_id = $management->id;
            $history->activity_type = 'File Attachment, if any';
            $history->previous = "Null";
            $history->current = $management->file_attchment_if_any;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
              $history->origin_state = $management->status;
                $history->save();
            }
             
            if (!empty($management->closure_attachments)) {
            $history = new ManagementAuditTrial();
            $history->ManagementReview_id = $management->id;
            $history->activity_type = 'File Attachment';
            $history->previous = "Null";
            $history->current = $management->closure_attachments;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
              $history->origin_state = $management->status;
                $history->save();
            }
    
            if (!empty($management->actual_start_date)) {
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
            }
    
            if (!empty($management->actual_end_date)) {
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
            }
    
    
            if (!empty($management->meeting_minute)) {
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
            }
    
    
            if (!empty($management->decision)) {
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
            }
    
    
            if (!empty($management->zone)) {
            $history = new ManagementAuditTrial();
            $history->ManagementReview_id = $management->id;
            $history->activity_type = 'Zone';
            $history->previous = "Null";
            $history->current = $management->zone;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->change_to= "Opened";
            $history->change_from= "Initiation";
            $history->action_name="Create";
            $history->save();
            $history->save();
            }
    
            if (!empty($management->country)) {
            $history = new ManagementAuditTrial();
            $history->ManagementReview_id = $management->id;
            $history->activity_type = 'Country';
            $history->previous = "Null";
            $history->current = $management->country;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->change_to= "Opened";
            $history->change_from= "Initiation";
            $history->action_name="Create";
            $history->save();
            $history->save();
            }
    
            if (!empty($management->city)) {
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
            }
    
    
            if (!empty($management->site_name)) {
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
            }
    
    
            if (!empty($management->building)) {
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
            }
    
    
            if (!empty($management->floor)) {
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
            }
    
    
            if (!empty($management->room)) {
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
            }
             if (!empty($management->Operations)) {
                $history = new ManagementAuditTrial();
                $history->ManagementReview_id = $management->id;
                $history->activity_type = 'Operations';
                $history->previous = "Null";
                $history->current = $management->Operations;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
               $history->origin_state = $management->status;
                $history->save();
            }
             if(!empty($management->control_externally_provide_services)) {
                $history = new ManagementAuditTrial();
                $history->ManagementReview_id = $management->id;
                $history->activity_type = 'Requirements for Products and Services';
                $history->previous = "Null";
                $history->current = $management->control_externally_provide_services;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
               $history->origin_state = $management->status;
                $history->save();
            }
              if (!empty($management->production_service_provision)) {
                $history = new ManagementAuditTrial();
                $history->ManagementReview_id = $management->id;
                $history->activity_type = 'Design and Development';
                $history->previous = "Null";
                $history->current = $management->production_service_provision;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
               $history->origin_state = $management->status;
                $history->save();
            }
              if(!empty($management->release_product_services)) {
                $history = new ManagementAuditTrial();
                $history->ManagementReview_id = $management->id;
                $history->activity_type = 'Control of Externally';
                $history->previous = "Null";
                $history->current = $management->release_product_services;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
               $history->origin_state = $management->status;
                $history->save();
            }
    
            if (!empty($management->production_service_provision)) {
                $history = new ManagementAuditTrial();
                $history->ManagementReview_id = $management->id;
                $history->activity_type = 'Production and Service';
                $history->previous = "Null";
                $history->current = $management->production_service_provision;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
               $history->origin_state = $management->status;
                $history->save();
            }
             if (!empty($management->release_product_services)) {
                $history = new ManagementAuditTrial();
                $history->ManagementReview_id = $management->id;
                $history->activity_type = 'Release of Products';
                $history->previous = "Null";
                $history->current = $management->release_product_services;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
               $history->origin_state = $management->status;
                $history->save();
            }
                if (!empty($management->control_nonconforming_outputs)) {
                $history = new ManagementAuditTrial();
                $history->ManagementReview_id = $management->id;
                $history->activity_type = 'Control of Non-conforming Outputs';
                $history->previous = "Null";
                $history->current = $management->control_nonconforming_outputs;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
               $history->origin_state = $management->status;
                $history->save();
            }
    
            if (!empty($management->risk_opportunities)) {
                $history = new ManagementAuditTrial();
                $history->ManagementReview_id = $management->id;
                $history->activity_type = 'Risk Opportunities';
                $history->previous = "Null";
                $history->current = $management->risk_opportunities;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
               $history->origin_state = $management->status;
                $history->save();
            }
    
            if (!empty($management->external_supplier_performance)) {
                $history = new ManagementAuditTrial();
                $history->ManagementReview_id = $management->id;
                $history->activity_type = 'External Supplier Performance';
                $history->previous = "Null";
                $history->current = $management->external_supplier_performance;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
               $history->origin_state = $management->status;
                $history->save();
            }
    
            if (!empty($management->customer_satisfaction_level)) {
                $history = new ManagementAuditTrial();
                $history->ManagementReview_id = $management->id;
                $history->activity_type = 'Customer Satisfactio Level';
                $history->previous = "Null";
                $history->current = $management->customer_satisfaction_level;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
               $history->origin_state = $management->status;
                $history->save();
            }
    

            if (!empty($management->budget_estimates)) {
                $history = new ManagementAuditTrial();
                $history->ManagementReview_id = $management->id;
                $history->activity_type = 'Budget Estimates';
                $history->previous = "Null";
                $history->current = $management->budget_estimates;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
               $history->origin_state = $management->status;
                $history->save();
            }
    
            if (!empty($management->completion_of_previous_tasks)) {
                $history = new ManagementAuditTrial();
                $history->ManagementReview_id = $management->id;
                $history->activity_type = 'Completion of Previous Tasks';
                $history->previous = "Null";
                $history->current = $management->completion_of_previous_tasks;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
               $history->origin_state = $management->status;
                $history->save();
            }
              if(!empty($management->production_new)) {
                $history = new ManagementAuditTrial();
                $history->ManagementReview_id = $management->id;
                $history->activity_type = 'Production';
                $history->previous = "Null";
                $history->current = $management->production_new;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
               $history->origin_state = $management->status;
                $history->save();
            }
             if (!empty($management->plans_new)) {
                $history = new ManagementAuditTrial();
                $history->ManagementReview_id = $management->id;
                $history->activity_type = 'Plans';
                $history->previous = "Null";
                $history->current = $management->plans_new;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
               $history->origin_state = $management->status;
                $history->save();
            }
    
            if (!empty($management->forecast_new)) {
                $history = new ManagementAuditTrial();
                $history->ManagementReview_id = $management->id;
                $history->activity_type = 'Forecast';
                $history->previous = "Null";
                $history->current = $management->forecast_new;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
               $history->origin_state = $management->status;
                $history->save();
            }
    
            if (!empty($management->additional_suport_required)) {
                $history = new ManagementAuditTrial();
                $history->ManagementReview_id = $management->id;
                $history->activity_type = 'Any Additional Support Required';
                $history->previous = "Null";
                $history->current = $management->additional_suport_required;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
               $history->origin_state = $management->status;
                $history->save();
            }
    

    
            if (!empty($management->next_managment_review_date)) {
                $history = new ManagementAuditTrial();
                $history->ManagementReview_id = $management->id;
                $history->activity_type = 'Next Management Review Date';
                $history->previous = "Null";
                $history->current = $management->next_managment_review_date;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
               $history->origin_state = $management->status;
                $history->save();
            }
    
            if (!empty($management->summary_recommendation)) {
                $history = new ManagementAuditTrial();
                $history->ManagementReview_id = $management->id;
                $history->activity_type = 'Summary & Recommendation';
                $history->previous = "Null";
                $history->current = $management->summary_recommendation;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
               $history->origin_state = $management->status;
                $history->save();
            }
              if(!empty($management->conclusion_new)) {
                $history = new ManagementAuditTrial();
                $history->ManagementReview_id = $management->id;
                $history->activity_type = 'Conclusion';
                $history->previous = "Null";
                $history->current = $management->conclusion_new;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
               $history->origin_state = $management->status;
                $history->save();
            }
             if (!empty($management->closure_attachments)) {
                $history = new ManagementAuditTrial();
                $history->ManagementReview_id = $management->id;
                $history->activity_type = 'Closure Attachments';
                $history->previous = "Null";
                $history->current = $management->closure_attachments;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
               $history->origin_state = $management->status;
                $history->save();
            }
    
            if (!empty($management->due_date_extension)) {
                $history = new ManagementAuditTrial();
                $history->ManagementReview_id = $management->id;
                $history->activity_type = 'Due_Date_Extension_Justification';
                $history->previous = "Null";
                $history->current = $management->due_date_extension;
                $history->comment = "Not Applicable";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
               $history->origin_state = $management->status;
                $history->save();
            }
    
        

         DocumentService::update_qms_numbers();

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
        // $management->Initiator_id= $request->Initiator_id;
        $management->short_description = $request->short_description;
        $management->assigned_to = $request->assigned_to;
        $management->due_date = $request->due_date;
        $management->type = $request->type;
        $management->start_date = $request->start_date;
        $management->end_date = $request->end_date;
        $management->attendees = $request->attendees;
        $management->agenda = $request->agenda;
        $management->performance_evaluation = $request->performance_evaluation;
       $management->management_review_participants = $management->management_review_participants;
       $management->action_item_details =$request->action_item_details;
       $management->capa_detail_details = $request->capa_detail_details;
        $management->description = $request->description;
        $management->attachment = $request->attachment;
        // $management->inv_attachment = json_encode($request->inv_attachment);
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
        $management->risk_opportunities = $request->risk_opportunities;
        $management->priority_level = $request->priority_level;
        // $management->file_attchment_if_any = json_encode($request->file_attchment_if_any);
        $management->assign_to = $request->assign_to;
        $management->initiator_group_code= $request->initiator_group_code;

        $management->Operations= $request->Operations;
        $management->initiator_Group= $request->initiator_Group;
        $management->requirement_products_services = $request->requirement_products_services;
        $management->design_development_product_services = $request->design_development_product_services; 
        $management->control_externally_provide_services = $request->control_externally_provide_services;
        $management->production_service_provision= $request->production_service_provision;
        $management->release_product_services = $request->release_product_services;
        $management->control_nonconforming_outputs = $request->control_nonconforming_outputs;
         $management->external_supplier_performance = $request->external_supplier_performance;
         $management->customer_satisfaction_level = $request->customer_satisfaction_level;
         $management->budget_estimates = $request->budget_estimates; 
         $management->completion_of_previous_tasks = $request->completion_of_previous_tasks;
         $management->production_new = $request->production_new;
         $management->plans_new = $request->plans_new;
         $management->forecast_new = $request->forecast_new;
         $management->additional_suport_required = $request->additional_suport_required;
         $management->next_managment_review_date = $request->next_managment_review_date;
         $management->forecast_new = $request->forecast_new;
         $management->conclusion_new = $request->conclusion_new;
         $management->summary_recommendation = $request->summary_recommendation;
         $management->due_date_extension= $request->due_date_extension;

        //  if (!empty($request->inv_attachment)) {
        //     $files = [];
        //     if ($request->hasfile('inv_attachment')) {
        //         foreach ($request->file('inv_attachment') as $file) {
        //             $name = $request->name . 'inv_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
        //             $file->move('upload/', $name);
        //             $files[] = $name;
        //         }
        //     }
        //     $management->inv_attachment = json_encode($files);
        // }
        $files = is_array($request->existing_inv_attachment_files) ? $request->existing_inv_attachment_files : null;

        if (!empty($request->inv_attachment)) {
            if ($management->inv_attachment) {
                $existingFiles = json_decode($management->inv_attachment, true); // Convert to associative array
                if (is_array($existingFiles)) {
                    $files = array_values($existingFiles); // Re-index the array to ensure it's a proper array
                }
            }

            if ($request->hasfile('inv_attachment')) {
                foreach ($request->file('inv_attachment') as $file) {
                    $name = $request->name . 'inv_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
        }

        $management->inv_attachment = !empty($files) ? json_encode(array_values($files)) : null; // Re-index again before encoding

        // if (!empty($request->file_attchment_if_any)) {
        //     $files = [];
        //     if ($request->hasfile('file_attchment_if_any')) {
        //         foreach ($request->file('file_attchment_if_any') as $file) {
        //             $name = $request->name . 'file_attchment_if_any' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
        //             $file->move('upload/', $name);
        //             $files[] = $name;
        //         }
        //     }
        //     $management->file_attchment_if_any = json_encode($files);
        // }
        // if (!empty($request->closure_attachments)) {
        //     $files = [];
        //     if ($request->hasfile('closure_attachments')) {
        //         foreach ($request->file('closure_attachments') as $file) {
        //             $name = $request->name . 'closure_attachments' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
        //             $file->move('upload/', $name);
        //             $files[] = $name;
        //         }
        //     }
        //     $management->closure_attachments = json_encode($files);
        // } 
        $files = is_array($request->existing_file_attchment_if_any_files) ? $request->existing_file_attchment_if_any_files : null;

        if (!empty($request->file_attchment_if_any)) {
            if ($management->file_attchment_if_any) {
                $existingFiles = json_decode($management->file_attchment_if_any, true); // Convert to associative array
                if (is_array($existingFiles)) {
                    $files = array_values($existingFiles); // Re-index the array to ensure it's a proper array
                }
            }

            if ($request->hasfile('file_attchment_if_any')) {
                foreach ($request->file('file_attchment_if_any') as $file) {
                    $name = $request->name . 'file_attchment_if_any' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
        }

        $management->file_attchment_if_any = !empty($files) ? json_encode(array_values($files)) : null; // Re-index again befor

        $files = is_array($request->existing_closure_attachments_files) ? $request->existing_closure_attachments_files : null;

        if (!empty($request->closure_attachments)) {
            if ($management->closure_attachments) {
                $existingFiles = json_decode($management->closure_attachments, true); // Convert to associative array
                if (is_array($existingFiles)) {
                    $files = array_values($existingFiles); // Re-index the array to ensure it's a proper array
                }
            }

            if ($request->hasfile('closure_attachments')) {
                foreach ($request->file('closure_attachments') as $file) {
                    $name = $request->name . 'closure_attachments' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
        }

        $management->closure_attachments = !empty($files) ? json_encode(array_values($files)) : null; // Re-index again befor

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
        if ($lastDocument->description != $management->description || !empty($request->description_comment)) {

            $history = new ManagementAuditTrial();
            $history->ManagementReview_id = $id;
            $history->activity_type = ' Description';
            $history->previous = $lastDocument->description;
            $history->current = $management->description;
            $history->comment = $request->short_desc_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }

        if ($lastDocument->assign_to != $management->assign_to || !empty($request->assign_to_comment)) {

            $history = new ManagementAuditTrial();
            $history->ManagementReview_id = $id;
            $history->activity_type = 'Assigned To';
            $history->previous = Helpers::getInitiatorName ($lastDocument->assign_to);
            $history->current = Helpers::getInitiatorName ($management->assign_to);
            $history->comment = $request->assign_to_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        // if ($lastDocument->due_date != $management->due_date || !empty($request->due_date_comment)) {

        //     $history = new ManagementAuditTrial();
        //     $history->ManagementReview_id = $id;
        //     $history->activity_type = 'Due Date';
        //     $history->previous = $lastDocument->due_date;
        //     $history->current = $management->due_date;
        //     $history->comment = $request->due_date_comment;
        //     $history->user_id = Auth::user()->id;
        //     $history->user_name = Auth::user()->name;
        //     $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        //     $history->origin_state = $lastDocument->status;
        //     $history->save();
        // }
        if ($lastDocument->initiator_Group != $management->initiator_Group || !empty($request->initiator_Group_comment)) {

            $history = new ManagementAuditTrial();
            $history->ManagementReview_id = $id;
            $history->activity_type = 'Initiator Group';
            $history->previous =Helpers::getInitiatorGroupFullName ($lastDocument->initiator_Group);
            $history->current = Helpers::getInitiatorGroupFullName($management->initiator_Group);
            $history->comment = $request->short_desc_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->initiator_group_code != $management->initiator_group_code || !empty($request->initiator_group_code_comment)) {

            $history = new ManagementAuditTrial();
            $history->ManagementReview_id = $id;
            $history->activity_type = 'Initiator Group Code';
            $history->previous = $lastDocument->initiator_group_code;
            $history->current = $management->initiator_group_code;
            $history->comment = $request->short_desc_comment;
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
        if ($lastDocument->priority_level != $management->priority_level || !empty($request->priority_level_comment)) {

            $history = new ManagementAuditTrial();
            $history->ManagementReview_id = $id;
            $history->activity_type = 'Priority Level';
            $history->previous = $lastDocument->priority_level;
            $history->current = $management->priority_level;
            $history->comment = $request->priority_level_comment;
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
            $history->previous = Helpers::getdateFormat($lastDocument->start_date);
            $history->current = Helpers::getdateFormat($management->start_date);
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
            $history->previous = Helpers::getdateFormat($lastDocument->end_date);
            $history->current = Helpers::getdateFormat($management->end_date);
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
        // if ($lastDocument->agenda != $management->agenda || !empty($request->agenda_comment)) {

        //     $history = new ManagementAuditTrial();
        //     $history->ManagementReview_id = $id;
        //     $history->activity_type = 'Agenda';
        //     $history->previous = $lastDocument->agenda;
        //     $history->current = $management->agenda;
        //     $history->comment = $request->agenda_comment;
        //     $history->user_id = Auth::user()->id;
        //     $history->user_name = Auth::user()->name;
        //     $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        //     $history->origin_state = $lastDocument->status;
        //     $history->save();
        // }
        if ($lastDocument->Operations != $management->Operations || !empty($request->Operations_comment)) {

            $history = new ManagementAuditTrial();
            $history->ManagementReview_id = $id;
            $history->activity_type = 'Operations';
            $history->previous = $lastDocument->Operations;
            $history->current = $management->Operations;
            $history->comment = $request->Operations_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->requirement_products_services != $management->requirement_products_services || !empty($request->requirement_products_services_comment)) {

            $history = new ManagementAuditTrial();
            $history->ManagementReview_id = $id;
            $history->activity_type = 'Requirements for Products and Services ';
            $history->previous = $lastDocument->requirement_products_services;
            $history->current = $management->requirement_products_services;
            $history->comment = $request->requirement_products_services_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }

        if ($lastDocument->design_development_product_services != $management->design_development_product_services || !empty($request->design_development_product_services_comment)) {

            $history = new ManagementAuditTrial();
            $history->ManagementReview_id = $id;
            $history->activity_type = ' Design and Development of Products and Services';
            $history->previous = $lastDocument->design_development_product_services;
            $history->current = $management->design_development_product_services;
            $history->comment = $request->design_development_product_services_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->control_externally_provide_services != $management->control_externally_provide_services || !empty($request->control_externally_provide_services_comment)) {

            $history = new ManagementAuditTrial();
            $history->ManagementReview_id = $id;
            $history->activity_type = 'Control of Externally Provided Processes, Products and Services';
            $history->previous = $lastDocument->control_externally_provide_services;
            $history->current = $management->control_externally_provide_services;
            $history->comment = $request->control_externally_provide_services_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->production_service_provision != $management->production_service_provision || !empty($request->production_service_provision_comment)) {

            $history = new ManagementAuditTrial();
            $history->ManagementReview_id = $id;
            $history->activity_type = 'Production and Service Provision';
            $history->previous = $lastDocument->production_service_provision;
            $history->current = $management->production_service_provision;
            $history->comment = $request->production_service_provision_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->release_product_services != $management->release_product_services || !empty($request->release_product_services_comment)) {

            $history = new ManagementAuditTrial();
            $history->ManagementReview_id = $id;
            $history->activity_type = 'Release of Products and Services';
            $history->previous = $lastDocument->release_product_services;
            $history->current = $management->release_product_services;
            $history->comment = $request->release_product_services_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->inv_attachment != $management->inv_attachment || !empty($request->inv_attachment_comment)) {

            $history = new ManagementAuditTrial();
            $history->ManagementReview_id = $id;
            $history->activity_type = 'File  Attachment';
            $history->previous = $lastDocument->inv_attachment;
            $history->current = $management->inv_attachment;
            $history->comment = $request->inv_attachment_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        
        if ($lastDocument->file_attchment_if_any != $management->file_attchment_if_any || !empty($request->file_attchment_if_any_comment)) {

            $history = new ManagementAuditTrial();
            $history->ManagementReview_id = $id;
            $history->activity_type = 'File Attachment';
            $history->previous = $lastDocument->file_attchment_if_any;
            $history->current = $management->file_attchment_if_any;
            $history->comment = $request->file_attchment_if_any_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->closure_attachments != $management->closure_attachments || !empty($request->closure_attachments_comment)) {

            $history = new ManagementAuditTrial();
            $history->ManagementReview_id = $id;
            $history->activity_type = 'Closure Attachment';
            $history->previous = $lastDocument->closure_attachments;
            $history->current = $management->closure_attachments;
            $history->comment = $request->closure_attachments_comment;
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
        if ($lastDocument->control_nonconforming_outputs != $management->control_nonconforming_outputs || !empty($request->control_nonconforming_outputs_comment)) {

            $history = new ManagementAuditTrial();
            $history->ManagementReview_id = $id;
            $history->activity_type = 'Control of Non-conforming Outputs ';
            $history->previous = $lastDocument->control_nonconforming_outputs;
            $history->current = $management->control_nonconforming_outputs;
            $history->comment = $request->control_nonconforming_outputs_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->production_service_provision != $management->production_service_provision || !empty($request->production_service_provision_comment)) {

            $history = new ManagementAuditTrial();
            $history->ManagementReview_id = $id;
            $history->activity_type = 'Production and Service Provision';
            $history->previous = $lastDocument->production_service_provision;
            $history->current = $management->production_service_provision;
            $history->comment = $request->production_service_provision_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        // if ($lastDocument->control_nonconforming_outputs != $management->control_nonconforming_outputs || !empty($request->control_nonconforming_outputs_comment)) {

        //     $history = new ManagementAuditTrial();
        //     $history->ManagementReview_id = $id;
        //     $history->activity_type = 'Control of Non-conforming Outputs';
        //     $history->previous = $lastDocument->control_nonconforming_outputs;
        //     $history->current = $management->control_nonconforming_outputs;
        //     $history->comment = $request->control_nonconforming_outputs_comment;
        //     $history->user_id = Auth::user()->id;
        //     $history->user_name = Auth::user()->name;
        //     $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        //     $history->origin_state = $lastDocument->status;
        //     $history->save();
        // }
        if ($lastDocument->risk_opportunities != $management->risk_opportunities || !empty($request->risk_opportunities_comment)) {

            $history = new ManagementAuditTrial();
            $history->ManagementReview_id = $id;
            $history->activity_type = 'Risk Opportunities';
            $history->previous = $lastDocument->risk_opportunities;
            $history->current = $management->risk_opportunities;
            $history->comment = $request->risk_opportunities_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->external_supplier_performance != $management->external_supplier_performance || !empty($request->external_supplier_performance_comment)) {

            $history = new ManagementAuditTrial();
            $history->ManagementReview_id = $id;
            $history->activity_type = 'External Supplier Performance';
            $history->previous = $lastDocument->external_supplier_performance;
            $history->current = $management->external_supplier_performance;
            $history->comment = $request->external_supplier_performance_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->customer_satisfaction_level != $management->customer_satisfaction_level || !empty($request->customer_satisfaction_level_comment)) {

            $history = new ManagementAuditTrial();
            $history->ManagementReview_id = $id;
            $history->activity_type = 'Customer Satisfaction Level';
            $history->previous = $lastDocument->customer_satisfaction_level;
            $history->current = $management->customer_satisfaction_level;
            $history->comment = $request->customer_satisfaction_level_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->budget_estimates != $management->budget_estimates || !empty($request->budget_estimates_comment)) {

            $history = new ManagementAuditTrial();
            $history->ManagementReview_id = $id;
            $history->activity_type = 'Budget Estimates';
            $history->previous = $lastDocument->budget_estimates;
            $history->current = $management->budget_estimates;
            $history->comment = $request->budget_estimates_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->completion_of_previous_tasks != $management->completion_of_previous_tasks || !empty($request->completion_of_previous_tasks_comment)) {

            $history = new ManagementAuditTrial();
            $history->ManagementReview_id = $id;
            $history->activity_type = 'Completion of Previous Tasks';
            $history->previous = $lastDocument->completion_of_previous_tasks;
            $history->current = $management->completion_of_previous_tasks;
            $history->comment = $request->completion_of_previous_tasks_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->production_new != $management->production_new || !empty($request->production_new_comment)) {

            $history = new ManagementAuditTrial();
            $history->ManagementReview_id = $id;
            $history->activity_type = 'Production';
            $history->previous = $lastDocument->production_new;
            $history->current = $management->production_new;
            $history->comment = $request->production_new_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->plans_new != $management->plans_new || !empty($request->plans_new_comment)) {

            $history = new ManagementAuditTrial();
            $history->ManagementReview_id = $id;
            $history->activity_type = 'Plans';
            $history->previous = $lastDocument->plans_new;
            $history->current = $management->plans_new;
            $history->comment = $request->plans_new_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->production_new != $management->production_new || !empty($request->production_new_comment)) {

            $history = new ManagementAuditTrial();
            $history->ManagementReview_id = $id;
            $history->activity_type = 'Forecast';
            $history->previous = $lastDocument->production_new;
            $history->current = $management->production_new;
            $history->comment = $request->production_new_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->additional_suport_required != $management->additional_suport_required || !empty($request->additional_suport_required_comment)) {

            $history = new ManagementAuditTrial();
            $history->ManagementReview_id = $id;
            $history->activity_type = 'Any Additional Support Required';
            $history->previous = $lastDocument->additional_suport_required;
            $history->current = $management->additional_suport_required;
            $history->comment = $request->additional_suport_required_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->due_date_extension != $management->due_date_extension || !empty($request->due_date_extension_comment)) {

            $history = new ManagementAuditTrial();
            $history->ManagementReview_id = $id;
            $history->activity_type = 'Due Date Extension Justification';
            $history->previous = $lastDocument->due_date_extension;
            $history->current = $management->due_date_extension;
            $history->comment = $request->due_date_extension_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->conclusion_new != $management->conclusion_new || !empty($request->conclusion_new_comment)) {

            $history = new ManagementAuditTrial();
            $history->ManagementReview_id = $id;
            $history->activity_type = 'Conclusion';
            $history->previous = $lastDocument->conclusion_new;
            $history->current = $management->conclusion_new;
            $history->comment = $request->conclusion_new_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->summary_recommendation != $management->summary_recommendation || !empty($request->summary_recommendation_comment)) {

            $history = new ManagementAuditTrial();
            $history->ManagementReview_id = $id;
            $history->activity_type = 'Summary & Recommendation';
            $history->previous = $lastDocument->summary_recommendation;
            $history->current = $management->summary_recommendation;
            $history->comment = $request->summary_recommendation_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->next_managment_review_date != $management->next_managment_review_date || !empty($request->next_managment_review_date_comment)) {

            $history = new ManagementAuditTrial();
            $history->ManagementReview_id = $id;
            $history->activity_type =' Next Management Review Date';
            $history->previous = Helpers::getdateFormat($lastDocument->next_managment_review_date);
            $history->current = Helpers::getdateFormat($management->next_managment_review_date);
            $history->comment = $request->next_managment_review_date_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        // --------------agenda--------------
        $data1 =  ManagementReviewDocDetails::where('review_id',$id)->where('type',"agenda")->first();
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
        $data1->update();

        $data2 =  ManagementReviewDocDetails::where('review_id',$id)->where('type',"performance_evaluation")->first();
        $data2->review_id = $management->id;
        $data2->type = "performance_evaluation";
        if (!empty($request->monitoring)) {
            $data2->monitoring = serialize($request->monitoring);
        }
        if (!empty($request->measurement)) {
            $data2->measurement = serialize($request->measurement);
        }
        if (!empty($request->analysis)) {
            $data2->analysis = serialize($request->analysis);
        }
        if (!empty($request->evaluation)) {
            $data2->evaluation = serialize($request->evaluation);
        }
        $data2->update();

        $data3 = ManagementReviewDocDetails::where('review_id',$id)->where('type',"management_review_participants")->first();
        $data3->review_id = $management->id;
        $data3->type = "management_review_participants";
        if (!empty($request->invited_Person)) {
            $data3->invited_Person = serialize($request->invited_Person);
        }
        if (!empty($request->designee)) {
            $data3->designee = serialize($request->designee);
        }
        if (!empty($request->department)) {
            $data3->department = serialize($request->department);
        }
        if (!empty($request->meeting_Attended)) {
            $data3->meeting_Attended = serialize($request->meeting_Attended);
        }
        if (!empty($request->designee_Name)) {
            $data3->designee_Name = serialize($request->designee_Name);
        }
        if (!empty($request->designee_Department)) {
            $data3->designee_Department = serialize($request->designee_Department);
        }
        if (!empty($request->remarks)) {
            $data3->remarks = serialize($request->remarks);
        }
        $data3->update();

        $data4 = ManagementReviewDocDetails::where('review_id',$id)->where('type',"action_item_details")->first();
        $data4->review_id = $management->id;
        $data4->type = "action_item_details";
        if (!empty($request->short_desc)) {
            $data4->short_desc = serialize($request->short_desc);
        }
        //dd($request->date_due);
        if (!empty($request->date_due)) {
            $data4->date_due = serialize($request->date_due);
        }
        if (!empty($request->site)) {
            $data4->site = serialize($request->site);
        }
        if (!empty($request->responsible_person)) {
            $data4->responsible_person = serialize($request->responsible_person);
        }
        if (!empty($request->current_status)) {
            $data4->current_status = serialize($request->current_status);
        }
        
        if (!empty($request->date_closed)) {
            $data4->date_closed = serialize($request->date_closed);
        }
        if (!empty($request->remark)) {
            $data4->remark = serialize($request->remark);
        }
        $data4->update();
        
        $data5 = ManagementReviewDocDetails::where('review_id',$id)->where('type',"capa_detail_details")->first();
        $data5->review_id = $management->id;
        $data5->type = "capa_detail_details";
      
        if (!empty($request->Details)) {
            $data5->Details = serialize($request->Details);
        }
        // dd($request->capa_type);
        if (!empty($request->capa_type)) {
            $data5->capa_type = serialize($request->capa_type);
        }
        if (!empty($request->site2)) {
            $data5->site2 = serialize($request->site2);
        }
        if (!empty($request->responsible_person2)) {
            $data5->responsible_person2 = serialize($request->responsible_person2);
        }
        if (!empty($request->current_status2)) {
            $data5->current_status2 = serialize($request->current_status2);
        }
        if (!empty($request->date_closed2)) {
            $data5->date_closed2 = serialize($request->date_closed2);
        }
        if (!empty($request->remark2)) {
            $data5->remark2 = serialize($request->remark2);
        }
        $data5->update();
    
        DocumentService::update_qms_numbers();
        
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
        $data->assign_to_name = User::where('id', $data->assign_to)->value('name');
        $data->initiator_name = User::where('id', $data->initiator_id)->value('name');
        $agenda = ManagementReviewDocDetails::where('review_id',$data->id)->where('type',"agenda")->first();
        $management_review_participants = ManagementReviewDocDetails::where('review_id',$data->id)->where('type',"management_review_participants")->first();
        $performance_evaluation = ManagementReviewDocDetails::where('review_id',$data->id)->where('type',"performance_evaluation")->first();
        $action_item_details=  ManagementReviewDocDetails::where('review_id',$data->id)->where('type',"action_item_details")->first();
        //dd(unserialize($action_item_details->date_due));
        $capa_detail_details=  ManagementReviewDocDetails::where('review_id',$data->id)->where('type',"capa_detail_details")->first();
        
        return view('frontend.management-review.management_review', compact( 'data','agenda','management_review_participants','performance_evaluation','action_item_details','capa_detail_details' ));
    }


    public function manage_send_stage(Request $request, $id)
    {


        if ($request->username == Auth::user()->email && Hash::check($request->password, Auth::user()->password)) {
            $changeControl = ManagementReview::find($id);
            $lastDocument =  ManagementReview::find($id);
            $data =  ManagementReview::find($id);

            if ($changeControl->stage == 1) {
                $changeControl->stage = "2";
                $changeControl->status = 'In Progress';
                $changeControl->Submited_by = Auth::user()->name;
                $changeControl->Submited_on = Carbon::now()->format('d-M-Y');
                $history = new ManagementAuditTrial();
                $history->ManagementReview_id = $id;
                $history->activity_type = 'Activity Log';
                // $history->previous = $lastDocument->Submited_by;
                $history->current = $changeControl->Submited_by;    
                $history->comment = $request->comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                $history->stage='Submited';
                $history->save();
                
                // $list = Helpers::getResponsibleUserList();
                // foreach ($list as $u) {
                //     if($u->q_m_s_divisions_id == $changeControl->division_id){
                //      $email = Helpers::getInitiatorEmail($u->user_id);
                //      if ($email !== null) {
                //          Mail::send(
                //             'mail.view-mail',
                //             ['data' => $changeControl],
                //             function ($message) use ($email) {
                //                 $message->to($email)
                //                     ->subject("Document is Send By ".Auth::user()->name);
                //             }
                //         );
                //       }
                //     } 
                // }
                $changeControl->update();
                toastr()->success('Document Sent');
                return back();
            }
            if ($changeControl->stage == 2) {
                $changeControl->stage = "3";
                $changeControl->status = 'Closed - Done';
                $changeControl->completed_by = Auth::user()->name;
                $changeControl->completed_on = Carbon::now()->format('d-M-Y');
                $history = new ManagementAuditTrial();
                $history->ManagementReview_id = $id;
                $history->activity_type = 'Activity Log';
                // $history->previous = $lastDocument->completed_by;
                $history->current = $changeControl->completed_by;    
                $history->comment = $request->comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                $history->stage='All Actions Completed ';
                $history->save();
                $changeControl->update();
                // $list = Helpers::getInitiatorUserList();
                // foreach ($list as $u) {
                //     if($u->q_m_s_divisions_id == $changeControl->division_id){
                //      $email = Helpers::getInitiatorEmail($u->user_id);
                //      if ($email !== null) {
                //          Mail::send(
                //             'mail.view-mail',
                //             ['data' => $changeControl],
                //             function ($message) use ($email) {
                //                 $message->to($email)
                //                     ->subject("Document is Send By ".Auth::user()->name);
                //             }
                //         );
                //       }
                //     } 
                // }
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
        $canvas->page_text($width / 4, $height / 2, $managementReview->status, null, 25, [0, 0, 0], 2, 6, -20);
        return $pdf->stream('Management-Review' . $id . '.pdf');


    }

    public function child_management_Review(Request $request, $id)
    {
        $parent_id = $id;
        $parent_initiator_id = ManagementReview::where('id', $id)->value('initiator_id');
        $parent_type = "Management-Review";
        $record_number = ((RecordNumber::first()->value('counter')) + 1);
        $record_number = str_pad($record_number, 4, '0', STR_PAD_LEFT);
        $parent_record = $record_number;
        $currentDate = Carbon::now();
        $parent_intiation_date = $currentDate;
        $formattedDate = $currentDate->addDays(30);
        $due_date = $formattedDate->format('d-M-Y');
        $old_record = ManagementReview::select('id', 'division_id', 'record')->get();
        $management = ManagementReview::find($id);
        session()->put('division', $management->division_id);
        return view('frontend.forms.action-item', compact('parent_intiation_date','parent_initiator_id','parent_record', 'record_number', 'due_date', 'parent_id', 'parent_type','old_record'));
    }

    public static function managementReviewReport($id)
    {
        $managementReview = ManagementReview::find($id);
        
        if (!empty($managementReview)) {
            $managementReview->originator = User::where('id', $managementReview->initiator_id)->value('name');
            $data = ManagementAuditTrial::where('ManagementReview_id', $id)->get();
            $pdf = App::make('dompdf.wrapper');
            $time = Carbon::now();
            $pdf = PDF::loadview('frontend.management-review.auditReport', compact('data', 'managementReview'))
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
            $canvas->page_text($width / 4, $height / 2, $managementReview->status, null, 25, [0, 0, 0], 2, 6, -20);
            return $pdf->stream('Management-Review' . $id . '.pdf');
        }
    }
    
}
