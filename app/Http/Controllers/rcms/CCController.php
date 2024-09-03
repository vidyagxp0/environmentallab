<?php

namespace App\Http\Controllers\rcms;

use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Models\ActionItem;
use App\Models\AdditionalInformation;
use App\Models\CC;
use App\Models\RecordNumber;
use App\Models\CCStageHistory;
use App\Models\ChangeClosure;
use App\Models\Docdetail;
use App\Models\Evaluation;
use App\Models\Extension;
use App\Models\GroupComments;
use App\Models\QaApprovalComments;
use App\Models\Qareview;
use App\Models\QMSDivision;
use App\Models\RiskAssessment;
use App\Models\RcmDocHistory;
use App\Models\RiskLevelKeywords;
use App\Models\RoleGroup;
use App\Models\User;
use App\Services\DocumentService;
// use Barryvdh\DomPDF\PDF;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Dompdf\Options;
use Helpers;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use PDF;

class CCController extends Controller
{
    public function changecontrol()
    {

        $riskData = RiskLevelKeywords::all();
        $record_number = ((RecordNumber::first()->value('counter')) + 1);
        $record_number = str_pad($record_number, 4, '0', STR_PAD_LEFT);

        $division = QMSDivision::where('name', Helpers::getDivisionName(session()->get('division')))->first();

        if ($division) {
            $last_cc = CC::where('division_id', $division->id)->latest()->first();
            // dd($last_cc);

            if ($last_cc) {
                $record_number = $last_cc->record_number ? str_pad($last_cc->record + 1, 4, '0', STR_PAD_LEFT) : '0001';
            } else {
                $record_number = '0001';
            }
        }
        
        $currentDate = Carbon::now();
        $formattedDate = $currentDate->addDays(30);
        $due_date = $formattedDate->format('d-M-Y');
        return view('frontend.change-control.new-change-control', compact("riskData", "record_number", "due_date"));
    }

    public function index()
    {

        $document = CC::where('initiator_id', Auth::user()->id)->get();
        foreach ($document as $data) {
            $data->originator = User::where('id', $data->initiator_id)->value('name');
        }

        return view('frontend.change-control.CC', compact('document'));
    }

    public function create()
    {

        $riskData = RiskLevelKeywords::all();
        $record_number = ((RecordNumber::first()->value('counter')) + 1);
        $record_number = str_pad($record_number, 4, '0', STR_PAD_LEFT);

        $division = QMSDivision::where('name', Helpers::getDivisionName(session()->get('division')))->first();

        if ($division) {
            $last_cc = CC::where('division_id', $division->id)->latest()->first();

            if ($last_cc) {
                $record_number = $last_cc->record_number ? str_pad($last_cc->record_number->record_number + 1, 4, '0', STR_PAD_LEFT) : '0001';
            } else {
                $record_number = '0001';
            }
        }

        $currentDate = Carbon::now();
        $formattedDate = $currentDate->addDays(30);
        $due_date = $formattedDate->format('d-M-Y');
        $hod = User::get();
        $cft = User::get();
        $pre = CC::all();

        return view('frontend.change-control.new-change-control', compact("riskData", "record_number", "due_date","hod","cft","pre"));
    }

    public function store(Request $request)
    {
        // $this->validate($request, [
        //     'assign_to' => 'required',
        //     'initiatorGroup' => 'required',
        //     'short_description' => 'required|unique:open_stages,short_description',
        //     'due_date' => 'required',
        // ]);
        
        $openState = new CC();
        $openState->form_type = "CC";
        $openState->division_id = $request->division_id;
        $openState->initiator_id = Auth::user()->id;
        $openState->record = DB::table('record_numbers')->value('counter') + 1;
        $openState->parent_id = $request->parent_id;
        $openState->parent_type = $request->parent_type;
        $openState->intiation_date = $request->intiation_date;
        $openState->Initiator_Group = $request->Initiator_Group;
        $openState->initiator_group_code = $request->initiator_group_code;
        $openState->short_description = $request->short_description;
        $openState->assign_to = $request->assign_to;
        $openState->due_date = $request->due_date;
        $openState->doc_change = $request->doc_change;
        $openState->If_Others = $request->If_Others;
        $openState->Division_Code = $request->Division_Code;
        $openState->severity_level1 = $request->severity_level1;
        $openState->initiated_through = $request->initiated_through;
        $openState->initiated_through_req = $request->initiated_through_req;
        $openState->repeat = $request->repeat;
        $openState->repeat_nature = $request->repeat_nature;
        $openState->current_practice = $request->current_practice;
        $openState->proposed_change = $request->proposed_change;
        $openState->reason_change = $request->reason_change;
        $openState->other_comment = $request->other_comment; 
        $openState->supervisor_comment = $request->supervisor_comment;

        $openState->type_chnage = $request->type_chnage;
        $openState->qa_comments = $request->qa_comments;
        //$openState->related_records = json_encode($request->related_records);
        $openState->related_records = implode(',', $request->related_records);
        $openState->qa_head = json_encode($request->qa_head);

        $openState->qa_eval_comments = json_encode($request->qa_eval_comments);
        //$openState->qa_eval_attach = json_encode($request->qa_eval_attach);
        $openState->training_required = $request->training_required;
        $openState->train_comments = $request->train_comments;

       $openState->Microbiology = $request->Microbiology;
       if ($request->Microbiology_Person) {
           $openState->Microbiology_Person = implode(',', $request->Microbiology_Person);
           $cftReviewerIds = explode(',', $openState->Microbiology_Person);
           $cftReviewers = User::whereIn('id', $cftReviewerIds)->pluck('name')->toArray();
           $cftReviewerNames = implode(', ', $cftReviewers);

       } else {
           toastr()->warning('CFT reviewers can not be empty');
           return back();
       }
        $openState->goup_review = $request->goup_review;
        $openState->Production = $request->Production;
        $openState->Production_Person = $request->Production_Person;
        $openState->Quality_Approver = $request->Quality_Approver;
        $openState->Quality_Approver_Person = $request->Quality_Approver_Person;
        $openState->bd_domestic = $request->bd_domestic;
        $openState->Bd_Person = $request->Bd_Person;
        $openState->additional_attachments = json_encode($request->additional_attachments);

        $openState->cft_comments = $request->cft_comments; 
        $openState->cft_attchament = json_encode($request->cft_attchament);
        $openState->qa_commentss = $request->qa_commentss;
        $openState->designee_comments = $request->designee_comments;
        $openState->Warehouse_comments = $request->Warehouse_comments;
        $openState->Engineering_comments = $request->Engineering_comments;
        $openState->Instrumentation_comments = $request->Instrumentation_comments;
        $openState->Validation_comments = $request->Validation_comments;
        $openState->Others_comments = $request->Others_comments;
        $openState->Group_comments = $request->Group_comments;
        $openState->group_attachments = json_encode($request->group_attachments);

        $openState->risk_identification = $request->risk_identification;
        $openState->severity = $request->severity;
        $openState->Occurance = $request->Occurance;
        $openState->Detection = $request->Detection;
        $openState->RPN = $request->RPN;
        $openState->risk_evaluation = $request->risk_evaluation;
        $openState->migration_action = $request->migration_action;

        $openState->qa_appro_comments = $request->qa_appro_comments;
        $openState->feedback = $request->feedback;
        $openState->tran_attach = json_encode($request->tran_attach);

        $openState->qa_closure_comments = $request->qa_closure_comments;
        $openState->attach_list = json_encode($request->attach_list);
        $openState->effective_check = $request->effective_check;
        $openState->effective_check_date = $request->effective_check_date;
        $openState->Effectiveness_checker = $request->Effectiveness_checker;
        $openState->effective_check_plan = $request->effective_check_plan;
        $openState->due_date_extension = $request->due_date_extension;


        if (!empty($request->in_attachment)) {
            $files = [];
            if ($request->hasfile('in_attachment')) {
                foreach ($request->file('in_attachment') as $file) {
                    $name = "CC" . '-in_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
            $openState->in_attachment = json_encode($files);
        }
 
        $openState->status = 'Opened';
        $openState->stage = 1;
        $openState->save();
 
        // Retrieve the current counter value
        $counter = DB::table('record_numbers')->value('counter');
        // Generate the record number with leading zeros
        $recordNumber = str_pad($counter, 5, '0', STR_PAD_LEFT);
        // Increment the counter value
        $newCounter = $counter + 1;
        DB::table('record_numbers')->update(['counter' => $newCounter]);

        $docdetail = new Docdetail();

        $docdetail->cc_id = $openState->id;
        if (!empty($request->serial_number)) {
            $docdetail->sno = serialize($request->serial_number);
        }
        if (!empty($request->current_doc_number)) {
            $docdetail->current_doc_no = serialize($request->current_doc_number);
        }
        if (!empty($request->current_version)) {
            $docdetail->current_version_no = serialize($request->current_version);
        }
        if (!empty($request->new_doc_number)) {
            $docdetail->new_doc_no = serialize($request->new_doc_number);
        }
        if (!empty($request->new_version)) {
            $docdetail->new_version_no = serialize($request->new_version);
        }
        $docdetail->current_practice = $request->current_practice;
        $docdetail->proposed_change = $request->proposed_change;
        $docdetail->reason_change = $request->reason_change;
        $docdetail->other_comment = $request->other_comment;
        $docdetail->supervisor_comment = $request->supervisor_comment;
        $docdetail->save();

        $review = new Qareview();
        $review->cc_id = $openState->id;
        $review->type_chnage = $request->type_chnage;
        $review->qa_comments = $request->qa_comments;
        if ($request->related_records) {
            $review->related_records = implode(',', $request->related_records);
        }

        if (!empty($request->qa_head)) {
            $files = [];
            if ($request->hasfile('qa_head')) {
                foreach ($request->file('qa_head') as $file) {
                
                    $name = "CC" . '-qa_head' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
            $review->qa_head = json_encode($files);
        }
        $review->save();

        $evaluation = new Evaluation();
        $evaluation->cc_id = $openState->id;
        $evaluation->qa_eval_comments = $request->qa_eval_comments;
        $evaluation->train_comments = $request->train_comments;

        if ($request->training_required) {
            $evaluation->training_required = $request->training_required;
        }
        if (!empty($request->qa_eval_attach)) {
            $files = [];
            if ($request->hasfile('qa_eval_attach')) {
                foreach ($request->file('qa_eval_attach') as $file) {
                    $name = "CC" . '-qa_eval_attach' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
            $evaluation->qa_eval_attach = json_encode($files);
        }

        $evaluation->save();

        $info = new AdditionalInformation();
        $info->cc_id = $openState->id;
        $info->goup_review = $request->goup_review;
        $info->Production = $request->Production;
        $info->Production_Person = $request->Production_Person;
        $info->Quality_Approver = $request->Quality_Approver;
        $info->Quality_Approver_Person = $request->Quality_Approver_Person;
        $info->Microbiology = $request->Microbiology;
         if ($request->Microbiology_Person) {
             $info->Microbiology_Person = implode(',', $request->Microbiology_Person);

            $cftReviewerIds = explode(',', $info->Microbiology_Person);
           $cftReviewers = User::whereIn('id', $cftReviewerIds)->pluck('name')->toArray();
           $cftReviewerNames = implode(', ', $cftReviewers);
         } else {
             toastr()->warning('CFT reviewers can not be empty');
             return back();
         }
        
        $info->bd_domestic = $request->bd_domestic;
        $info->Bd_Person = $request->Bd_Person;
        if (!empty($request->additional_attachments)) {
            $files = [];
            if ($request->hasfile('additional_attachments')) {
                foreach ($request->file('additional_attachments') as $file) {
                    $name = "CC" . '-additional_attachments' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
            $info->additional_attachments = json_encode($files);
        }

        $info->save();

        $comments = new GroupComments();
        $comments->cc_id = $openState->id;
        // $comments->qa_comments = $request->qa_comments;
        $comments->qa_commentss = $request->qa_commentss;
        $comments->designee_comments = $request->designee_comments;
        $comments->Warehouse_comments = $request->Warehouse_comments;
        $comments->Engineering_comments = $request->Engineering_comments;
        $comments->Instrumentation_comments = $request->Instrumentation_comments;
        $comments->Validation_comments = $request->Validation_comments;
        $comments->Others_comments = $request->Others_comments;
        $comments->Group_comments = $request->Group_comments;
        $comments->cft_comments = $request->cft_comments;

        if (!empty($request->group_attachments)) {
            $files = [];
            if ($request->hasfile('group_attachments')) {
                foreach ($request->file('group_attachments') as $file) {
                    $name = "CC" . '-group_attachments' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
            $comments->group_attachments = json_encode($files);
        }
        if (!empty($request->cft_attchament)) {
            $files = [];
            if ($request->hasfile('cft_attchament')) {
                foreach ($request->file('cft_attchament') as $file) {
                    $name = "CC" . '-cft_attchament' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
            $comments->cft_attchament = json_encode($files);
        }

        $comments->save();

        $assessment = new RiskAssessment();
        $assessment->cc_id = $openState->id;
        $assessment->risk_identification = $request->risk_identification;
        $assessment->severity = $request->severity;
        $assessment->Occurance = $request->Occurance;
        $assessment->Detection = $request->Detection;
        $assessment->RPN = $request->RPN;
        $assessment->risk_evaluation = $request->risk_evaluation;
        $assessment->migration_action = $request->migration_action;
        $assessment->save();

        $approcomments = new QaApprovalComments();
        $approcomments->cc_id = $openState->id;
        $approcomments->qa_appro_comments = $request->qa_appro_comments;
        $approcomments->feedback = $request->feedback;
        if (!empty($request->tran_attach)) {
            $files = [];
            if ($request->hasfile('tran_attach')) {
                foreach ($request->file('tran_attach') as $file) {
                    $name = "CC" . '-tran_attach' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
            $approcomments->tran_attach = json_encode($files);
        }

        $approcomments->save();

        $closure = new ChangeClosure();

        $closure->cc_id = $openState->id;

        if (!empty($request->serial_number)) {
            $closure->sno = serialize($request->serial_number);
        }
        if (!empty($request->affected_documents)) {
            $closure->affected_document = serialize($request->affected_documents);
        }
        if (!empty($request->document_name)) {
            $closure->doc_name = serialize($request->document_name);
        }
        if (!empty($request->document_no)) {
            $closure->doc_no = serialize($request->document_no);
        }
        if (!empty($request->version_no)) {
            $closure->version_no = serialize($request->version_no);
        }
        if (!empty($request->implementation_date)) {
            $closure->implementation_date = serialize($request->implementation_date);
        }
        if (!empty($request->new_document_no)) {
            $closure->new_doc_no = serialize($request->new_document_no);
        }
        if (!empty($request->new_version_no)) {
            $closure->new_version_no = serialize($request->new_version_no);
        }

        $closure->qa_closure_comments = $request->qa_closure_comments;
        $closure->Effectiveness_checker = $request->Effectiveness_checker;
        $closure->effective_check = $request->effective_check;
        $closure->effective_check_date = $request->effective_check_date;
        if (!empty($request->attach_list)) {
            $files = [];
            if ($request->hasfile('attach_list')) {
                foreach ($request->file('attach_list') as $file) {
                    $name = "CC" . '-attach_list' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
            $closure->attach_list = json_encode($files);
        }

        $closure->save();


        $history = new RcmDocHistory;
        $history->cc_id = $openState->id;
        $history->activity_type = 'Division';
        $history->previous = "Null";
        $history->current = Helpers::getDivisionName($request->division_id);
        $history->comment = "NA";
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $openState->status;
        $history->save();

        $history = new RcmDocHistory;
        $history->cc_id = $openState->id;
        $history->activity_type = 'Initiator';
        $history->previous = "Null";
        $history->current = Auth::user()->name;
        $history->comment = "NA";
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $openState->status;
        $history->save();

        $history = new RcmDocHistory;
        $history->cc_id = $openState->id;
        $history->activity_type = 'Date of Initiation';
        $history->previous = "Null";
        $history->current = Helpers::getdateFormat($request->intiation_date);
        $history->comment = "NA";
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $openState->status;
        $history->save();

        $history = new RcmDocHistory;
        $history->cc_id = $openState->id;
        $history->activity_type = 'Short Description';
        $history->previous = "Null";
        $history->current = $openState->short_description;
        $history->comment = "NA";
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $openState->status;
        $history->save();

        if(!empty($request->Initiator_Group)){
            $history = new RcmDocHistory;
            $history->cc_id = $openState->id;
            $history->activity_type = 'Inititator Group';
            $history->previous = "Null";
            $history->current = Helpers::getInitiatorGroupFullName($request->Initiator_Group);
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->save();
        }

        if(!empty($request->initiator_group_code)){
            $history = new RcmDocHistory;
            $history->cc_id = $openState->id;
            $history->activity_type = 'Inititator Group Code';
            $history->previous = "Null";
            $history->current = $request->initiator_group_code;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->save();
        }
        
        if(!empty($openState->initiator_id)){
            $history = new RcmDocHistory;
            $history->cc_id = $openState->id;
            $history->activity_type = 'Assigned To';
            $history->previous = "Null";
            $history->current = Helpers::getInitiatorName($request->assign_to);
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->save();
        }

        if(!empty($request->due_date)){
            $history = new RcmDocHistory;
            $history->cc_id = $openState->id;
            $history->activity_type = 'Due Date';
            $history->previous = "Null";
            $history->current = Helpers::getdateFormat($request->due_date);
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->save();
        }

        if(!empty($request->Microbiology)){
            $history = new RcmDocHistory;
            $history->cc_id = $openState->id;
            $history->activity_type = 'CFT Reviewer';
            $history->previous = "Null";
            $history->current = $request->Microbiology;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->save();
        }

        if(!empty($request->Microbiology_Person)){
            $history = new RcmDocHistory;
            $history->cc_id = $openState->id;
            $history->activity_type = 'CFT Reviewer Person';
            $history->previous = "Null";
            $history->current = $cftReviewerNames;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->save();
        }

        if(!empty($request->severity_level1)){
            $history = new RcmDocHistory;
            $history->cc_id = $openState->id;
            $history->activity_type = 'Severity Level';
            $history->previous = "Null";
            $history->current = $request->severity_level1;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->save();
        }

        if(!empty($request->initiated_through)){
            $history = new RcmDocHistory;
            $history->cc_id = $openState->id;
            $history->activity_type = 'Initiated Through';
            $history->previous = "Null";
            $history->current = $request->initiated_through;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->save();
        }

        if(!empty($request->initiated_through_req)){
            $history = new RcmDocHistory;
            $history->cc_id = $openState->id;
            $history->activity_type = 'Others';
            $history->previous = "Null";
            $history->current = $request->initiated_through_req;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->save();
        }

        if(!empty($request->repeat)){
            $history = new RcmDocHistory;
            $history->cc_id = $openState->id;
            $history->activity_type = 'Repeat';
            $history->previous = "Null";
            $history->current = $request->repeat;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->save();
        }

        if(!empty($request->repeat_nature)){
            $history = new RcmDocHistory;
            $history->cc_id = $openState->id;
            $history->activity_type = 'Repeat Nature';
            $history->previous = "Null";
            $history->current = $request->repeat_nature;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->save();
        }

        if(!empty($request->doc_change)){
            $history = new RcmDocHistory;
            $history->cc_id = $openState->id;
            $history->activity_type = 'Nature Of Change';
            $history->previous = "Null";
            $history->current = $request->doc_change;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->save();
        }

        if(!empty($request->If_Others)){
            $history = new RcmDocHistory;
            $history->cc_id = $openState->id;
            $history->activity_type = 'If Others';
            $history->previous = "Null";
            $history->current = $request->If_Others;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->save();
        }

        if(!empty($request->Division_Code)){
            $history = new RcmDocHistory;
            $history->cc_id = $openState->id;
            $history->activity_type = 'Division';
            $history->previous = "Null";
            $history->current = $request->Division_Code;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->save();
        }

        if(!empty($request->in_attachment)){
            $history = new RcmDocHistory;
            $history->cc_id = $openState->id;
            $history->activity_type = 'Initial Attachment';
            $history->previous = "Null";
            $history->current = $openState->in_attachment;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->save();
        }

        /******* Change Details ********/

        if(!empty($request->current_practice)){
            $history = new RcmDocHistory;
            $history->cc_id = $docdetail->id;
            $history->activity_type = 'Current Practice';
            $history->previous = "Null";
            $history->current = $request->current_practice;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->save();
        }

        if(!empty($request->proposed_change)){            
            $history = new RcmDocHistory;
            $history->cc_id = $docdetail->id;
            $history->activity_type = 'Proposed Change';
            $history->previous = "Null";
            $history->current = $request->proposed_change;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->save();
        }

        if(!empty($request->reason_change)){            
            $history = new RcmDocHistory;
            $history->cc_id = $docdetail->id;
            $history->activity_type = 'Reason for Change';
            $history->previous = "Null";
            $history->current = $docdetail->reason_change;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->save();
        }

        if(!empty($request->other_comment)){            
            $history = new RcmDocHistory;
            $history->cc_id = $docdetail->id;
            $history->activity_type = 'Any Other Comments';
            $history->previous = "Null";
            $history->current = $request->other_comment;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->save();
        }

        if(!empty($request->supervisor_comment)){            
            $history = new RcmDocHistory;
            $history->cc_id = $docdetail->id;
            $history->activity_type = 'Supervisor Comments';
            $history->previous = "Null";
            $history->current = $request->supervisor_comment;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->save();
        }
        
        
        /********* QA Review ********/

        if(!empty($request->type_chnage)){            
            $history = new RcmDocHistory;
            $history->cc_id = $review->id;
            $history->activity_type = 'Type of Change';
            $history->previous = "Null";
            $history->current = $request->type_chnage;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->save();
        }

        if(!empty($request->qa_comments)){            
            $history = new RcmDocHistory;
            $history->cc_id = $review->id;
            $history->activity_type = 'QA Review Comments';
            $history->previous = "Null";
            $history->current = $request->qa_comments;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->save();
        }

        // if(!empty($request->related_records)){            
        //     $history = new RcmDocHistory;
        //     $history->cc_id = $review->id;
        //     $history->activity_type = 'Related Records';
        //     $history->previous = "Null";
        //     $history->current = $request->related_records;
        //     $history->comment = "NA";
        //     $history->user_id = Auth::user()->id;
        //     $history->user_name = Auth::user()->name;
        //     $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        //     $history->origin_state = $openState->status;
        //     $history->save();
        // }     
        
        if(!empty($request->qa_head)){
            $history = new RcmDocHistory;
            $history->cc_id = $review->id;
            $history->activity_type = 'QA Attachment';
            $history->previous = "Null";
            $history->current = $review->qa_head;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->save();
        }

        /********** Evaluation **********/
        if(!empty($request->qa_eval_comments)){
            $history = new RcmDocHistory;
            $history->cc_id = $evaluation->id;
            $history->activity_type = 'QA Evaluation Comments';
            $history->previous = "Null";
            $history->current = $evaluation->qa_eval_comments;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->save();
        }        

        if (!empty($request->qa_eval_attach)){
            $history = new RcmDocHistory;
            $history->cc_id = $evaluation->id;
            $history->activity_type = 'QA Evaluation Attachments';
            $history->previous = "Null";
            $history->current = $evaluation->qa_eval_attach;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->save();
        }

        if(!empty($request->training_required)){
            $history = new RcmDocHistory;
            $history->cc_id = $evaluation->id;
            $history->activity_type = 'Training Required';
            $history->previous = "Null";
            $history->current = $evaluation->training_required;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->save();
        }

        if(!empty($request->train_comments)){
            $history = new RcmDocHistory;
            $history->cc_id = $evaluation->id;
            $history->activity_type = 'Training Comments';
            $history->previous = "Null";
            $history->current = $evaluation->train_comments;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->save();
        }
        
        /********* Comments *********/
        if(!empty($request->cft_comments)){
            $history = new RcmDocHistory;
            $history->cc_id = $info->id;
            $history->activity_type = 'CFT Comments';
            $history->previous = "Null";
            $history->current = $request->cft_comments;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->save();
        }

        if(!empty($request->cft_attchament)){
            $history = new RcmDocHistory;
            $history->cc_id = $info->id;
            $history->activity_type = 'CFT Attachments';
            $history->previous = "Null";
            $history->current = $info->cft_attchament;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->save(); 
        }

        if(!empty($request->qa_comments)){
            $history = new RcmDocHistory;
            $history->cc_id = $comments->id;
            $history->activity_type = 'QA Comments';
            $history->previous = "Null";
            $history->current = $request->qa_comments;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->save();
        }

        if(!empty($request->designee_comments)){
            $history = new RcmDocHistory;
            $history->cc_id = $comments->id;
            $history->activity_type = 'QA Head Designee Comments';
            $history->previous = "Null";
            $history->current = $comments->designee_comments;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->save();
        }

        if(!empty($request->Warehouse_comments)){
            $history = new RcmDocHistory;
            $history->cc_id = $comments->id;
            $history->activity_type = 'Warehouse Comments';
            $history->previous = "Null";
            $history->current = $comments->Warehouse_comments;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->save();
        }

        if(!empty($request->Engineering_comments)){
            $history = new RcmDocHistory;
            $history->cc_id = $comments->id;
            $history->activity_type = 'Engineering Comments';
            $history->previous = "Null";
            $history->current = $comments->Engineering_comments;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->save();
        }

        if(!empty($request->Instrumentation_comments)){
            $history = new RcmDocHistory;
            $history->cc_id = $comments->id;
            $history->activity_type = 'Instrumentation Comments';
            $history->previous = "Null";
            $history->current = $comments->Instrumentation_comments;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->save();
        }

        if(!empty($request->Validation_comments)){
            $history = new RcmDocHistory;
            $history->cc_id = $comments->id;
            $history->activity_type = 'Validation Comments';
            $history->previous = "Null";
            $history->current = $comments->Validation_comments;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->save();
        }

        if(!empty($request->Others_comments)){
            $history = new RcmDocHistory;
            $history->cc_id = $comments->id;
            $history->activity_type = 'Others Comments';
            $history->previous = "Null";
            $history->current = $comments->Others_comments;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->save();
        }

        if(!empty($request->Group_comments)){
            $history = new RcmDocHistory;
            $history->cc_id = $comments->id;
            $history->activity_type = 'Comments';
            $history->previous = "Null";
            $history->current = $comments->Group_comments;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->save();
        }

        if(!empty($request->group_attachments)){
            $history = new RcmDocHistory;
            $history->cc_id = $comments->id;
            $history->activity_type = 'Group Attachments';
            $history->previous = "Null";
            $history->current = $comments->group_attachments;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->save();
        }

        /******** Risk Assessment ***********/
        if(!empty($request->risk_identification)){
            $history = new RcmDocHistory;
            $history->cc_id = $assessment->id;
            $history->activity_type = 'Risk Identification';
            $history->previous = "Null";
            $history->current = $assessment->risk_identification;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->save();
        }

        if(!empty($request->severity)){
            $history = new RcmDocHistory;
            $history->cc_id = $assessment->id;
            $history->activity_type = 'Severity';
            $history->previous = "Null";

            if($request->severity == 1){
                $history->current = "Negligible";
            } elseif($request->severity == 2){
                $history->current = "Minor";
            } elseif($request->severity == 3){
                $history->current = "Moderate";
            } elseif($request->severity == 4){
                $history->current = "Major";
            } elseif($request->severity == 5){
                $history->current = "Fatel";
            }

            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->save();
        }

        if(!empty($request->Occurance)){
            $history = new RcmDocHistory;
            $history->cc_id = $assessment->id;
            $history->activity_type = 'Occurance';
            $history->previous = "Null";
           
            if($request->Occurance == 1){
                $history->current = "Very Likely";
            } elseif($request->Occurance == 2){
                $history->current = "Likely";
            } elseif($request->Occurance == 3){
                $history->current = "Unlikely";
            } elseif($request->Occurance == 4){
                $history->current = "Rare";
            } elseif($request->Occurance == 5){
                $history->current = "Extremely Unlikely";
            }

            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->save();
        }

        if(!empty($request->Detection)){
            $history = new RcmDocHistory;
            $history->cc_id = $assessment->id;
            $history->activity_type = 'Detection';
            $history->previous = "Null";
            
            if($request->Detection == 2){
                $history->current = "Likely";
            } elseif($request->Detection == 3){
                $history->current = "Unlikely";
            } elseif($request->Detection == 4){
                $history->current = "Rare";
            } elseif($request->Detection == 5){
                $history->current = "Impossible";
            }

            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->save();
        }

        if(!empty($request->RPN)){
            $history = new RcmDocHistory;
            $history->cc_id = $assessment->id;
            $history->activity_type = 'RPN';
            $history->previous = "Null";
            $history->current = $assessment->RPN;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->save();
        }

        if(!empty($request->risk_evaluation)){
            $history = new RcmDocHistory;
            $history->cc_id = $assessment->id;
            $history->activity_type = 'Risk Evaluation';
            $history->previous = "Null";
            $history->current = $assessment->risk_evaluation;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->save();
        }

        if(!empty($request->migration_action)){
            $history = new RcmDocHistory;
            $history->cc_id = $assessment->id;
            $history->activity_type = 'Migration Action';
            $history->previous = "Null";
            $history->current = $assessment->migration_action;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->save();
        }

        /******** QA Approval Comments *******/
        if(!empty($request->qa_appro_comments)){
            $history = new RcmDocHistory;
            $history->cc_id = $approcomments->id;
            $history->activity_type = 'QA Approval Comments';
            $history->previous = "Null";
            $history->current = $approcomments->qa_appro_comments;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->save();
        }

        if(!empty($request->feedback)){
            $history = new RcmDocHistory;
            $history->cc_id = $approcomments->id;
            $history->activity_type = 'Training Feedback';
            $history->previous = "Null";
            $history->current = $approcomments->feedback;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->save();
        }

        if(!empty($request->tran_attach)){
            $history = new RcmDocHistory;
            $history->cc_id = $approcomments->id;
            $history->activity_type = 'Training Attachments';
            $history->previous = "Null";
            $history->current = $approcomments->tran_attach;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->save();
        }

       
        /******** Change Closure ********/
        if(!empty($request->qa_closure_comments)){
            $history = new RcmDocHistory;
            $history->cc_id = $closure->id;
            $history->activity_type = 'QA Closure Comment';
            $history->previous = "Null";
            $history->current = $closure->qa_closure_comments;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->save();
        }

        if(!empty($request->attach_list)){
            $history = new RcmDocHistory;
            $history->cc_id = $closure->id;
            $history->activity_type = 'Change Closure Attachment';
            $history->previous = "Null";
            $history->current = $closure->attach_list;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->save();
        }

        if(!empty($request->due_date_extension)){
            $history = new RcmDocHistory;
            $history->cc_id = $closure->id;
            $history->activity_type = 'Due Date Extension Justification';
            $history->previous = "Null";
            $history->current = $closure->due_date_extension;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->save();
        }

        toastr()->success('Record is created Successfully ');
        return redirect('rcms/qms-dashboard');
    }

    public function show($id)
    {

        $data = CC::find($id);
        $cc_lid = $data->id;
        $data->assign_to_name = User::where('id', $data->assign_to)->value('name');
        $docdetail = Docdetail::where('cc_id', $id)->first();
        $review = Qareview::where('cc_id', $id)->first();
        $evaluation = Evaluation::where('cc_id', $id)->first();
        $info = AdditionalInformation::where('cc_id', $id)->first();
        $comments = GroupComments::where('cc_id', $id)->first();
        $assessment = RiskAssessment::where('cc_id', $id)->first();
        $approcomments = QaApprovalComments::where('cc_id', $id)->first();
        $closure = ChangeClosure::where('cc_id', $id)->first();
        $hod = User::get();
        $cft = User::get();
        $cft_aff = [];
        if(!is_null($data->Microbiology_Person)){
            $cft_aff = explode(',', $data->Microbiology_Person);
        }
        $pre = CC::all();
        $due_date_extension = $data->due_date_extension;

        DocumentService::update_qms_numbers();
    
        return view('frontend.change-control.CCview', compact(
            'data',
            'docdetail',
            'review',
            'evaluation',
            'info',
            'comments',
            'assessment',
            'approcomments',
            'closure',
            "hod",
            "cft",
            "cft_aff",
            "due_date_extension",
            "cc_lid",
            "pre"
        ));
    }

    public function update(Request $request, $id)
    {
   
        $lastDocument = CC::find($id);
        $openState = CC::find($id);

        $getId = $lastDocument->Microbiology_Person;
        $lastcftReviewerIds = explode(',', $getId);
        $lastcftReviewers = User::whereIn('id', $lastcftReviewerIds)->pluck('name')->toArray();
        $lastcftReviewerNames = implode(', ', $lastcftReviewers);


        $openState->initiator_id = Auth::user()->id;
        $openState->Initiator_Group = $request->Initiator_Group;
        $openState->initiator_group_code = $request->initiator_group_code;
        $openState->short_description = $request->short_description;
        $openState->assign_to = $request->assign_to;
        // $openState->due_date = $request->due_date;
        $openState->doc_change = $request->doc_change;
        $openState->If_Others = $request->If_Others;
        $openState->Division_Code = $request->Division_Code;
        $openState->severity_level1 = $request->severity_level1;
        $openState->initiated_through = $request->initiated_through;
        $openState->initiated_through_req = $request->initiated_through_req;
        $openState->repeat = $request->repeat;
        $openState->repeat_nature = $request->repeat_nature;
        $openState->current_practice = $request->current_practice;
        $openState->proposed_change = $request->proposed_change;
        $openState->reason_change = $request->reason_change;
        $openState->other_comment = $request->other_comment; 
        $openState->supervisor_comment = $request->supervisor_comment;
        // $openState->type_chnage = $request->type_chnage;
        $openState->qa_comments = $request->qa_comments;
       // $openState->related_records = $request->related_records;
        $openState->related_records = implode(',', $request->related_records);
        $openState->qa_head = $request->qa_head;

        $openState->qa_eval_comments = $request->qa_eval_comments;
        $openState->qa_eval_attach = $request->qa_eval_attach;
        $openState->training_required = $request->training_required;
        $openState->train_comments = $request->train_comments;

        $openState->Microbiology = $request->Microbiology;
        
         if ($request->Microbiology_Person) {
             $openState->Microbiology_Person = implode(',', $request->Microbiology_Person);

             $cftReviewerIds = explode(',', $openState->Microbiology_Person);
           $cftReviewers = User::whereIn('id', $cftReviewerIds)->pluck('name')->toArray();
           $cftReviewerNames = implode(', ', $cftReviewers);
         } else {
             toastr()->warning('CFT reviewers can not be empty');
             return back();
         }
        $openState->goup_review = $request->goup_review;
        $openState->Production = $request->Production;
        $openState->Production_Person = $request->Production_Person;
        $openState->Quality_Approver = $request->Quality_Approver;
        $openState->Quality_Approver_Person = $request->Quality_Approver_Person;
        $openState->bd_domestic = $request->bd_domestic;
        $openState->Bd_Person = $request->Bd_Person;
        $openState->additional_attachments = json_encode($request->additional_attachments);

        // $openState->cft_comments = $request->cft_comments; 
        // $openState->cft_attchament = json_encode($request->cft_attchament);
        // $openState->qa_commentss = $request->qa_commentss;
        $openState->designee_comments = $request->designee_comments;
        $openState->Warehouse_comments = $request->Warehouse_comments;
        $openState->Engineering_comments = $request->Engineering_comments;
        $openState->Instrumentation_comments = $request->Instrumentation_comments;
        $openState->Validation_comments = $request->Validation_comments;
        $openState->Others_comments = $request->Others_comments;
        $openState->Group_comments = $request->Group_comments;
        $openState->group_attachments = json_encode($request->group_attachments);

        $openState->risk_identification = $request->risk_identification;
        $openState->severity = $request->severity;
        $openState->Occurance = $request->Occurance;
        $openState->Detection = $request->Detection;
        $openState->RPN = $request->RPN;
        $openState->risk_evaluation = $request->risk_evaluation;
        $openState->migration_action = $request->migration_action;

        $openState->qa_appro_comments = $request->qa_appro_comments;
        $openState->feedback = $request->feedback;
        $openState->tran_attach = json_encode($request->tran_attach);

        $openState->qa_closure_comments = $request->qa_closure_comments;
        $openState->attach_list = json_encode($request->attach_list);
        $openState->effective_check = $request->effective_check;
        $openState->effective_check_date = $request->effective_check_date;
        $openState->Effectiveness_checker = $request->Effectiveness_checker;
        $openState->effective_check_plan = $request->effective_check_plan;

        $openState->due_date_extension = $request->due_date_extension;

        $files = is_array($request->existing_attach_files_initial) ? $request->existing_attach_files_initial : null;
        if (!empty($request->in_attachment)) {
            if ($openState->in_attachment) {
                $existingFiles = json_decode($openState->in_attachment, true); // Convert to associative array
                if (is_array($existingFiles)) {
                    $files = array_values($existingFiles);
                }
            }

            if ($request->hasfile('in_attachment')) {
                foreach ($request->file('in_attachment') as $file) {
                    $name = "CC" . '-in_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
        }
        $openState->in_attachment = !empty($files) ? json_encode(array_values($files)) : null;

        $openState->update();

        $lastdocdetail = Docdetail::where('cc_id', $id)->first();
        $docdetail = Docdetail::where('cc_id', $id)->first();
        if (!empty($request->serial_number)) {
            $docdetail->sno = serialize($request->serial_number);
        }
        if (!empty($request->current_doc_number)) {
            $docdetail->current_doc_no = serialize($request->current_doc_number);
        }
        if (!empty($request->current_version)) {
            $docdetail->current_version_no = serialize($request->current_version);
        }
        if (!empty($request->new_doc_number)) {
            $docdetail->new_doc_no = serialize($request->new_doc_number);
        }
        if (!empty($request->new_version)) {
            $docdetail->new_version_no = serialize($request->new_version);
        }
        $docdetail->current_practice = $request->current_practice;
        $docdetail->proposed_change = $request->proposed_change;
        $docdetail->reason_change = $request->reason_change;
        $docdetail->other_comment = $request->other_comment;
        $docdetail->supervisor_comment = $request->supervisor_comment;
        $docdetail->update();

        $lastreview = Qareview::where('cc_id', $id)->first();
        $review = Qareview::where('cc_id', $id)->first();
        $review->cc_id = $openState->id;
        $review->type_chnage = $request->type_chnage;
        $review->qa_comments = $request->qa_review_comments;
        if ($request->related_records) {
            $review->related_records = implode(',', $request->related_records);
        }

        $files = is_array($request->existing_attach_files_qa_head) ? $request->existing_attach_files_qa_head : null;
        if (!empty($request->qa_head)) {
            if ($review->qa_head) {
                $existingFiles = json_decode($review->qa_head, true); // Convert to associative array
                if (is_array($existingFiles)) {
                    $files = array_values($existingFiles);
                }
            }

            if ($request->hasfile('qa_head')) {
                foreach ($request->file('qa_head') as $file) {
                    $name = "CC" . '-qa_head' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
        }
        $review->qa_head = !empty($files) ? json_encode(array_values($files)) : null;
        $review->update();

        $lastevaluation = Evaluation::where('cc_id', $id)->first();
        $evaluation = Evaluation::where('cc_id', $id)->first();
        $evaluation->cc_id = $openState->id;
        $evaluation->qa_eval_comments = $request->qa_eval_comments;
        $evaluation->train_comments = $request->train_comments;

        if ($request->training_required) {
            $evaluation->training_required = $request->training_required;
        }

        $files = is_array($request->existing_attach_files_eval) ? $request->existing_attach_files_eval : null;
        if (!empty($request->qa_eval_attach)) {
            if ($evaluation->qa_eval_attach) {
                $existingFiles = json_decode($evaluation->qa_eval_attach, true); // Convert to associative array
                if (is_array($existingFiles)) {
                    $files = array_values($existingFiles);
                }
            }

            if ($request->hasfile('qa_eval_attach')) {
                foreach ($request->file('qa_eval_attach') as $file) {
                    $name = "CC" . '-qa_eval_attach' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
        }
        $evaluation->qa_eval_attach = !empty($files) ? json_encode(array_values($files)) : null;
        $evaluation->update();

        $lastinfo = AdditionalInformation::where('cc_id', $id)->first();
        $info = AdditionalInformation::where('cc_id', $id)->first();
        $info->cc_id = $openState->id;
        $info->goup_review = $request->goup_review;
        $info->Production = $request->Production;
        $info->Production_Person = $request->Production_Person;
        $info->Quality_Approver = $request->Quality_Approver;
        $info->Quality_Approver_Person = $request->Quality_Approver_Person;
        $info->Microbiology = $request->Microbiology;
        
         if ($request->Microbiology_Person) {
             $info->Microbiology_Person = implode(',', $request->Microbiology_Person);
             $cftReviewerIds = explode(',', $info->Microbiology_Person);
           $cftReviewers = User::whereIn('id', $cftReviewerIds)->pluck('name')->toArray();
           $cftReviewerNames = implode(', ', $cftReviewers);
         } else {
             toastr()->warning('CFT reviewers can not be empty');
             return back();
         }
        $info->bd_domestic = $request->bd_domestic;
        $info->Bd_Person = $request->Bd_Person;

        if (!empty($request->additional_attachments)) {
            $files = [];
            if ($request->hasfile('additional_attachments')) {
                foreach ($request->file('additional_attachments') as $file) {
                    $name = "CC" . '-additional_attachments' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
            $info->additional_attachments = json_encode($files);
        }
        $info->update();

        $lastcomments = GroupComments::where('cc_id', $id)->first();
        $comments = GroupComments::where('cc_id', $id)->first();
        $comments->cc_id = $openState->id;
        $comments->qa_comments = $request->qa_comments;
        $comments->qa_commentss = $request->qa_commentss;
        $comments->designee_comments = $request->designee_comments;
        $comments->Warehouse_comments = $request->Warehouse_comments;
        $comments->Engineering_comments = $request->Engineering_comments;
        $comments->Instrumentation_comments = $request->Instrumentation_comments;
        $comments->Validation_comments = $request->Validation_comments;
        $comments->Others_comments = $request->Others_comments;
        $comments->Group_comments = $request->Group_comments;
        $comments->cft_comments = $request->cft_comments;


        $files = is_array($request->existing_attach_files_group_attachments) ? $request->existing_attach_files_group_attachments : null;
        if (!empty($request->group_attachments)) {
            if ($comments->group_attachments) {
                $existingFiles = json_decode($comments->group_attachments, true); // Convert to associative array
                if (is_array($existingFiles)) {
                    $files = array_values($existingFiles);
                }
            }

            if ($request->hasfile('group_attachments')) {
                foreach ($request->file('group_attachments') as $file) {
                    $name = "CC" . '-group_attachments' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
        }
        $comments->group_attachments = !empty($files) ? json_encode(array_values($files)) : null;

        $files = is_array($request->existing_attach_files_cftAttach) ? $request->existing_attach_files_cftAttach : null;
        if (!empty($request->cft_attchament)) {
            if ($comments->cft_attchament) {
                $existingFiles = json_decode($comments->cft_attchament, true); // Convert to associative array
                if (is_array($existingFiles)) {
                    $files = array_values($existingFiles);
                }
            }

            if ($request->hasfile('cft_attchament')) {
                foreach ($request->file('cft_attchament') as $file) {
                    $name = "CC" . '-cft_attchament' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
        }
        $comments->cft_attchament = !empty($files) ? json_encode(array_values($files)) : null;
        $comments->update();

        $lastassessment = RiskAssessment::where('cc_id', $id)->first();
        $assessment = RiskAssessment::where('cc_id', $id)->first();
        $assessment->cc_id = $openState->id;
        $assessment->risk_identification = $request->risk_identification;
        $assessment->severity = $request->severity;
        $assessment->Occurance = $request->Occurance;
        $assessment->Detection = $request->Detection;
        $assessment->RPN = $request->RPN;
        $assessment->risk_evaluation = $request->risk_evaluation;
        $assessment->migration_action = $request->migration_action;
        $assessment->update();

        $lastapprocomments = QaApprovalComments::where('cc_id', $id)->first();
        $approcomments = QaApprovalComments::where('cc_id', $id)->first();
        $approcomments->cc_id = $openState->id;
        $approcomments->qa_appro_comments = $request->qa_appro_comments;
        $approcomments->feedback = $request->feedback;


        $files = is_array($request->existing_attach_files_training) ? $request->existing_attach_files_training : null;
        if (!empty($request->tran_attach)) {
            if ($approcomments->tran_attach) {
                $existingFiles = json_decode($approcomments->tran_attach, true); // Convert to associative array
                if (is_array($existingFiles)) {
                    $files = array_values($existingFiles);
                }
            }

            if ($request->hasfile('tran_attach')) {
                foreach ($request->file('tran_attach') as $file) {
                    $name = "CC" . '-tran_attach' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
        }
        $approcomments->tran_attach = !empty($files) ? json_encode(array_values($files)) : null;
        $approcomments->update();

        $lastclosure = ChangeClosure::where('cc_id', $id)->first();
        $closure = ChangeClosure::where('cc_id', $id)->first();

        $closure->cc_id = $openState->id;

        if (!empty($request->serial_number)) {
            $closure->sno = serialize($request->serial_number);
        }
        if (!empty($request->affected_documents)) {
            $closure->affected_document = serialize($request->affected_documents);
        }
        if (!empty($request->document_name)) {
            $closure->doc_name = serialize($request->document_name);
        }
        if (!empty($request->document_no)) {
            $closure->doc_no = serialize($request->document_no);
        }
        if (!empty($request->version_no)) {
            $closure->version_no = serialize($request->version_no);
        }
        if (!empty($request->implementation_date)) {
            $closure->implementation_date = serialize($request->implementation_date);
        }
        if (!empty($request->new_document_no)) {
            $closure->new_doc_no = serialize($request->new_document_no);
        }
        if (!empty($request->new_version_no)) {
            $closure->new_version_no = serialize($request->new_version_no);
        }

        $closure->qa_closure_comments = $request->qa_closure_comments;
        $closure->Effectiveness_checker = $request->Effectiveness_checker;
        $closure->effective_check = $request->effective_check;
        $closure->effective_check_date = $request->effective_check_date;

        $files = is_array($request->existing_attach_files_attach_list) ? $request->existing_attach_files_attach_list : null;
        if (!empty($request->attach_list)) {
            if ($closure->attach_list) {
                $existingFiles = json_decode($closure->attach_list, true); // Convert to associative array
                if (is_array($existingFiles)) {
                    $files = array_values($existingFiles);
                }
            }

            if ($request->hasfile('attach_list')) {
                foreach ($request->file('attach_list') as $file) {
                    $name = "CC" . '-attach_list' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
        }
        $closure->attach_list = !empty($files) ? json_encode(array_values($files)) : null;
        $closure->update();

        if ($lastDocument->short_description != $request->short_description) {
            $history = new RcmDocHistory;
            $history->cc_id = $id;
            $history->activity_type = 'Short Description';
            $history->previous = $lastDocument->short_description;
            $history->current = $request->short_description;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }

        if ($lastDocument->Initiator_Group != $request->Initiator_Group) {
            $history = new RcmDocHistory;
            $history->cc_id = $id;
            $history->activity_type = 'Inititator Group';
            $history->previous = $lastDocument->Initiator_Group;
            $history->current = Helpers::getInitiatorGroupFullName($request->Initiator_Group);
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }

        if ($lastDocument->initiator_group_code != $request->initiator_group_code) {
            $history = new RcmDocHistory;
            $history->cc_id = $id;
            $history->activity_type = 'Inititator Group';
            $history->previous = $lastDocument->initiator_group_code;
            $history->current = $request->initiator_group_code;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        
        if ($lastDocument->assign_to != $request->assign_to) {
            $history = new RcmDocHistory;
            $history->cc_id = $id;
            $history->activity_type = 'Assigned To';
            $history->previous = Helpers::getInitiatorName($lastDocument->assign_to);
            $history->current = Helpers::getInitiatorName($request->assign_to);
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }

        // if ($lastDocument->due_date != $request->due_date) {
        //     $history = new RcmDocHistory;
        //     $history->cc_id = $id;
        //     $history->activity_type = 'Due Date';
        //     $history->previous = Helpers::getdateFormat($lastDocument->due_date);
        //     $history->current = Helpers::getdateFormat($request->due_date);
        //     $history->comment = "NA";
        //     $history->user_id = Auth::user()->id;
        //     $history->user_name = Auth::user()->name;
        //     $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        //     $history->origin_state = $lastDocument->status;
        //     $history->save();
        // }

        if ($lastDocument->severity_level1 != $request->severity_level1) {
            $history = new RcmDocHistory;
            $history->cc_id = $id;
            $history->activity_type = 'Severity Level';
            $history->previous = $lastDocument->severity_level1;
            $history->current = $request->severity_level1;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }

        if ($lastDocument->initiated_through != $request->initiated_through) {
            $history = new RcmDocHistory;
            $history->cc_id = $id;
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
            $history = new RcmDocHistory;
            $history->cc_id = $id;
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

        if ($lastDocument->repeat != $request->repeat) {
            $history = new RcmDocHistory;
            $history->cc_id = $id;
            $history->activity_type = 'Repeat';
            $history->previous = $lastDocument->repeat;
            $history->current = $request->repeat;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }

        if ($lastDocument->repeat_nature != $request->repeat_nature) {
            $history = new RcmDocHistory;
            $history->cc_id = $id;
            $history->activity_type = 'Repeat Nature';
            $history->previous = $lastDocument->repeat_nature;
            $history->current = $request->repeat_nature;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }

        if ($lastDocument->doc_change != $request->doc_change) {
            $history = new RcmDocHistory;
            $history->cc_id = $id;
            $history->activity_type = 'Nature Of Change';
            $history->previous = $lastDocument->doc_change;
            $history->current = $request->doc_change;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }

        if ($lastDocument->If_Others != $request->If_Others) {
            $history = new RcmDocHistory;
            $history->cc_id = $id;
            $history->activity_type = 'If Others';
            $history->previous = $lastDocument->If_Others;
            $history->current = $request->If_Others;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }

        if ($lastDocument->Division_Code != $request->Division_Code) {
            $history = new RcmDocHistory;
            $history->cc_id = $id;
            $history->activity_type = 'Division';
            $history->previous = $lastDocument->Division_Code;
            $history->current = $request->Division_Code;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }

        //<!---------------Change Details History---------------->

        if ($lastdocdetail->current_practice != $docdetail->current_practice || !empty($request->current_practice_comment)) {
            $history = new RcmDocHistory;
            $history->cc_id = $id;
            $history->activity_type = 'Current Practice';
            $history->previous = $lastdocdetail->current_practice;
            $history->current = $docdetail->current_practice;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }

        if ($lastdocdetail->proposed_change != $docdetail->proposed_change || !empty($request->proposed_change_comment)) {
            $history = new RcmDocHistory;
            $history->cc_id = $id;
            $history->activity_type = 'Proposed Change';
            $history->previous = $lastdocdetail->proposed_change;
            $history->current = $docdetail->proposed_change;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastdocdetail->reason_change != $docdetail->reason_change || !empty($request->proposed_change_comment)) {
            $history = new RcmDocHistory;
            $history->cc_id = $id;
            $history->activity_type = 'Reason for Change';
            $history->previous = $lastdocdetail->proposed_change;
            $history->current = $docdetail->proposed_change;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastdocdetail->other_comment != $docdetail->other_comment || !empty($request->other_comment_comment)) {
            $history = new RcmDocHistory;
            $history->cc_id = $id;
            $history->activity_type = 'Any Other Comments';
            $history->previous = $lastdocdetail->other_comment;
            $history->current = $docdetail->other_comment;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }

        if ($lastdocdetail->supervisor_comment != $docdetail->other_comment || !empty($request->supervisor_comment_comment)) {
            $history = new RcmDocHistory;
            $history->cc_id = $id;
            $history->activity_type = 'Supervisor Comments';
            $history->previous = $lastdocdetail->supervisor_comment;
            $history->current = $docdetail->supervisor_comment;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastreview->type_chnage != $review->type_chnage) {
            $history = new RcmDocHistory;
            $history->cc_id = $id;
            $history->activity_type = 'Type of Change';
            $history->previous = $lastreview->type_chnage;
            $history->current = $review->type_chnage;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }

        if ($lastreview->qa_head != $review->qa_head) {
            $history = new RcmDocHistory;
            $history->cc_id = $id;
            $history->activity_type = 'QA Attachments';
            $history->previous = $lastreview->qa_head;
            $history->current = $review->qa_head;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastreview->qa_comments != $review->qa_comments || !empty($request->qa_comments_comment)) {
            $history = new RcmDocHistory;
            $history->cc_id = $id;
            $history->activity_type = 'QA Review Comments';
            $history->previous = $lastreview->qa_comments;
            $history->current = $review->qa_comments;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastreview->related_records != $review->related_records || !empty($request->related_records_comment)) {
            $history = new RcmDocHistory;
            $history->cc_id = $id;
            $history->activity_type = 'Related Records';
            $history->previous = $lastreview->related_records;
            $history->current = $review->related_records;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }


        if ($lastevaluation->qa_eval_comments != $evaluation->qa_eval_comments || !empty($request->qa_eval_comments_comment)) {
            $history = new RcmDocHistory;
            $history->cc_id = $id;
            $history->activity_type = 'QA Evaluation Comments';
            $history->previous = $lastevaluation->qa_eval_comments;
            $history->current = $evaluation->qa_eval_comments;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastevaluation->qa_eval_attach != $evaluation->qa_eval_attach || !empty($request->qa_eval_attach_comment)) {
            $history = new RcmDocHistory;
            $history->cc_id = $id;
            $history->activity_type = 'QA Evaluation Attachments';
            $history->previous = $lastevaluation->qa_eval_attach;
            $history->current = $evaluation->qa_eval_attach;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastevaluation->train_comments != $evaluation->train_comments || !empty($request->train_comments_comment)) {
            $history = new RcmDocHistory;
            $history->cc_id = $id;
            $history->activity_type = 'Training Comments';
            $history->previous = $lastevaluation->train_comments;
            $history->current = $evaluation->train_comments;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastevaluation->training_required != $evaluation->training_required || !empty($request->training_required_comment)) {
            $history = new RcmDocHistory;
            $history->cc_id = $id;
            $history->activity_type = 'Training Required';
            $history->previous = $lastevaluation->training_required;
            $history->current = $evaluation->training_required;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        // if ($lastinfo->goup_review != $info->goup_review || !empty($request->goup_review_comment)) {
        //     $history = new RcmDocHistory;
        //     $history->cc_id = $id;
        //     $history->activity_type = 'Is Group Review Required?';
        //     $history->previous = $lastinfo->goup_review;
        //     $history->current = $info->goup_review;
        //     $history->comment = $request->goup_review_comment;
        //     $history->user_id = Auth::user()->id;
        //     $history->user_name = Auth::user()->name;
        //     $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        //     $history->origin_state = $lastDocument->status;
        //     $history->save();
        // }
        // if ($lastinfo->Production != $info->Production || !empty($request->Production_comment)) {
        //     $history = new RcmDocHistory;
        //     $history->cc_id = $id;
        //     $history->activity_type = 'Production';
        //     $history->previous = $lastinfo->Production;
        //     $history->current = $info->Production;
        //     $history->comment = $request->Production_comment;
        //     $history->user_id = Auth::user()->id;
        //     $history->user_name = Auth::user()->name;
        //     $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        //     $history->origin_state = $lastDocument->status;
        //     $history->save();
        // }
        // if ($lastinfo->Production_Person != $info->Production_Person || !empty($request->Production_Person_comment)) {
        //     $history = new RcmDocHistory;
        //     $history->cc_id = $id;
        //     $history->activity_type = 'Production Person';
        //     $history->previous = $lastinfo->Production_Person;
        //     $history->current = $info->Production_Person;
        //     $history->comment = $request->Production_Person_comment;
        //     $history->user_id = Auth::user()->id;
        //     $history->user_name = Auth::user()->name;
        //     $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        //     $history->origin_state = $lastDocument->status;
        //     $history->save();
        // }
        // if ($lastinfo->Quality_Approver != $info->Quality_Approver || !empty($request->Quality_Approver_comment)) {
        //     $history = new RcmDocHistory;
        //     $history->cc_id = $id;
        //     $history->activity_type = 'Quality Approver';
        //     $history->previous = $lastinfo->Quality_Approver;
        //     $history->current = $info->Quality_Approver;
        //     $history->comment = $request->Quality_Approver_comment;
        //     $history->user_id = Auth::user()->id;
        //     $history->user_name = Auth::user()->name;
        //     $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        //     $history->origin_state = $lastDocument->status;
        //     $history->save();
        // }

        // if ($lastinfo->Quality_Approver_Person != $info->Quality_Approver_Person || !empty($request->Quality_Approver_Person_comment)) {
        //     $history = new RcmDocHistory;
        //     $history->cc_id = $id;
        //     $history->activity_type = 'Quality Approver Person';
        //     $history->previous = $lastinfo->Quality_Approver_Person;
        //     $history->current = $info->Quality_Approver_Person;
        //     $history->comment = $request->Quality_Approver_Person_comment;
        //     $history->user_id = Auth::user()->id;
        //     $history->user_name = Auth::user()->name;
        //     $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        //     $history->origin_state = $lastDocument->status;
        //     $history->save();
        // }
        if ($lastinfo->Microbiology != $info->Microbiology || !empty($request->Microbiology_comment)) {
            $history = new RcmDocHistory;
            $history->cc_id = $id;
            $history->activity_type = 'CFT Reviewer';
            $history->previous = $lastinfo->Microbiology;
            $history->current = $info->Microbiology;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastinfo->Microbiology_Person != $request->Microbiology_Person) {
            $history = new RcmDocHistory;
            $history->cc_id = $id;
            $history->activity_type = 'CFT Reviewer Person';
            $history->previous = $lastcftReviewerNames;
            $history->current = $cftReviewerNames;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        // if ($lastinfo->bd_domestic != $info->bd_domestic || !empty($request->bd_domestic_comment)) {
        //     $history = new RcmDocHistory;
        //     $history->cc_id = $id;
        //     $history->activity_type = 'Others';
        //     $history->previous = $lastinfo->bd_domestic;
        //     $history->current = $info->bd_domestic;
        //     $history->comment = $request->bd_domestic_comment;
        //     $history->user_id = Auth::user()->id;
        //     $history->user_name = Auth::user()->name;
        //     $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        //     $history->origin_state = $lastDocument->status;
        //     $history->save();
        // }
        // if ($lastinfo->Bd_Person != $info->Bd_Person || !empty($request->Bd_Person_comment)) {
        //     $history = new RcmDocHistory;
        //     $history->cc_id = $id;
        //     $history->activity_type = 'Others Person';
        //     $history->previous = $lastinfo->Bd_Person;
        //     $history->current = $info->bd_domesticBd_Person;
        //     $history->comment = $request->Bd_Person_comment;
        //     $history->user_id = Auth::user()->id;
        //     $history->user_name = Auth::user()->name;
        //     $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        //     $history->origin_state = $lastDocument->status;
        //     $history->save();
        // }

        // if ($lastinfo->additional_attachments != $info->additional_attachments || !empty($request->additional_attachments_comment)) {
        //     $history = new RcmDocHistory;
        //     $history->cc_id = $id;
        //     $history->activity_type = 'Additional Attachments';
        //     $history->previous = $lastinfo->additional_attachments;
        //     $history->current = $info->additional_attachments;
        //     $history->comment = $request->additional_attachments_comment;
        //     $history->user_id = Auth::user()->id;
        //     $history->user_name = Auth::user()->name;
        //     $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        //     $history->origin_state = $lastDocument->status;
        //     $history->save();
        // }

        // ----------------------Group Comments History------------------------
        if ($lastcomments->qa_commentss != $comments->qa_commentss) {
            $history = new RcmDocHistory;
            $history->cc_id = $id;
            $history->activity_type = 'QA Comments';
            $history->previous = $lastcomments->qa_commentss;
            $history->current = $comments->qa_commentss;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }

        if ($lastcomments->cft_attchament != $comments->cft_attchament) {
            $history = new RcmDocHistory;
            $history->cc_id = $id;
            $history->activity_type = 'Attachment';
            $history->previous = $lastcomments->cft_attchament;
            $history->current = $comments->cft_attchament;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }

        if ($lastcomments->designee_comments != $comments->designee_comments || !empty($request->designee_comments_comment)) {
            $history = new RcmDocHistory;
            $history->cc_id = $id;
            $history->activity_type = 'QA Head Designee Comments';
            $history->previous = $lastcomments->designee_comments;
            $history->current = $comments->designee_comments;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastcomments->Warehouse_comments != $comments->Warehouse_comments || !empty($request->Warehouse_comments_comment)) {
            $history = new RcmDocHistory;
            $history->cc_id = $id;
            $history->activity_type = 'Warehouse Comments';
            $history->previous = $lastcomments->Warehouse_comments;
            $history->current = $comments->Warehouse_comments;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastcomments->Engineering_comments != $comments->Engineering_comments || !empty($request->Engineering_comments_comment)) {
            $history = new RcmDocHistory;
            $history->cc_id = $id;
            $history->activity_type = 'Engineering Comments';
            $history->previous = $lastcomments->Engineering_comments;
            $history->current = $comments->Engineering_comments;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastcomments->Instrumentation_comments != $comments->Instrumentation_comments || !empty($request->Instrumentation_comments_comment)) {
            $history = new RcmDocHistory;
            $history->cc_id = $id;
            $history->activity_type = 'Instrumentation Comments';
            $history->previous = $lastcomments->Instrumentation_comments;
            $history->current = $comments->Instrumentation_comments;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastcomments->Validation_comments != $comments->Validation_comments || !empty($request->Validation_comments_comment)) {
            $history = new RcmDocHistory;
            $history->cc_id = $id;
            $history->activity_type = 'Validation Comments';
            $history->previous = $lastcomments->Validation_comments;
            $history->current = $comments->Validation_comments;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastcomments->Others_comments != $comments->Others_comments || !empty($request->Others_comments_comment)) {
            $history = new RcmDocHistory;
            $history->cc_id = $id;
            $history->activity_type = 'Others Comments';
            $history->previous = $lastcomments->Others_comments;
            $history->current = $comments->Others_comments;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastcomments->Group_comments != $comments->Group_comments || !empty($request->Group_comments_comment)) {
            $history = new RcmDocHistory;
            $history->cc_id = $id;
            $history->activity_type = 'Comments';
            $history->previous = $lastcomments->Group_comments;
            $history->current = $comments->Group_comments;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastcomments->group_attachments != $comments->group_attachments || !empty($request->group_attachments_comment)) {
            $history = new RcmDocHistory;
            $history->cc_id = $id;
            $history->activity_type = 'Attachments';
            $history->previous = $lastcomments->group_attachments;
            $history->current = $comments->group_attachments;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        // ----------------------Risk Assesments------------------------

        if ($lastassessment->risk_identification != $assessment->risk_identification || !empty($request->risk_identification_comment)) {
            $history = new RcmDocHistory;
            $history->cc_id = $id;
            $history->activity_type = 'Risk Identification';
            $history->previous = $lastassessment->risk_identification;
            $history->current = $assessment->risk_identification;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }

        if ($lastassessment->severity != $assessment->severity || !empty($request->severity_comment)) {
            $history = new RcmDocHistory;
            $history->cc_id = $id;
            $history->activity_type = 'Severity';
            
            if($lastassessment->severity == 1){
                $history->previous = "Negligible";
            } elseif($lastassessment->severity == 2){
                $history->previous = "Minor";
            } elseif($lastassessment->severity == 3){
                $history->previous = "Moderate";
            } elseif($lastassessment->severity == 4){
                $history->previous = "Major";
            } elseif($lastassessment->severity == 5){
                $history->previous = "Fatel";
            }

            if($request->severity == 1){
                $history->current = "Negligible";
            } elseif($request->severity == 2){
                $history->current = "Minor";
            } elseif($request->severity == 3){
                $history->current = "Moderate";
            } elseif($request->severity == 4){
                $history->current = "Major";
            } elseif($request->severity == 5){
                $history->current = "Fatel";
            }

            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastassessment->Occurance != $assessment->Occurance || !empty($request->Occurance_comment)) {
            $history = new RcmDocHistory;
            $history->cc_id = $id;
            $history->activity_type = 'Occurance';
            
            if($lastassessment->Occurance == 1){
                $history->previous = "Very Likely";
            } elseif($lastassessment->Occurance == 2){
                $history->previous = "Likely";
            } elseif($lastassessment->Occurance == 3){
                $history->previous = "Unlikely";
            } elseif($lastassessment->Occurance == 4){
                $history->previous = "Rare";
            } elseif($lastassessment->Occurance == 5){
                $history->previous = "Extremely Unlikely";
            }


            if($request->Occurance == 1){
                $history->current = "Very Likely";
            } elseif($request->Occurance == 2){
                $history->current = "Likely";
            } elseif($request->Occurance == 3){
                $history->current = "Unlikely";
            } elseif($request->Occurance == 4){
                $history->current = "Rare";
            } elseif($request->Occurance == 5){
                $history->current = "Extremely Unlikely";
            }

            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastassessment->Detection != $assessment->Detection || !empty($request->Detection_comment)) {
            $history = new RcmDocHistory;
            $history->cc_id = $id;
            $history->activity_type = 'Detection';

            if($lastassessment->Detection == 2){
                $history->previous = "Likely";
            } elseif($lastassessment->Detection == 3){
                $history->previous = "Unlikely";
            } elseif($lastassessment->Detection == 4){
                $history->previous = "Rare";
            } elseif($lastassessment->Detection == 5){
                $history->previous = "Impossible";
            }


            if($request->Detection == 2){
                $history->current = "Likely";
            } elseif($request->Detection == 3){
                $history->current = "Unlikely";
            } elseif($request->Detection == 4){
                $history->current = "Rare";
            } elseif($request->Detection == 5){
                $history->current = "Impossible";
            }

            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastassessment->RPN != $assessment->RPN || !empty($request->RPN_comment)) {
            $history = new RcmDocHistory;
            $history->cc_id = $id;
            $history->activity_type = 'RPN';
            $history->previous = $lastassessment->RPN;
            $history->current = $assessment->RPN;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastassessment->risk_evaluation != $assessment->risk_evaluation || !empty($request->risk_evaluation_comment)) {
            $history = new RcmDocHistory;
            $history->cc_id = $id;
            $history->activity_type = 'Risk Evaluation';
            $history->previous = $lastassessment->risk_evaluation;
            $history->current = $assessment->risk_evaluation;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastassessment->migration_action != $assessment->migration_action || !empty($request->migration_action_comment)) {
            $history = new RcmDocHistory;
            $history->cc_id = $id;
            $history->activity_type = 'Migration Action';
            $history->previous = $lastassessment->migration_action;
            $history->current = $assessment->migration_action;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        //-----------------------QA Approval Comments-----------------

        if ($lastapprocomments->qa_appro_comments != $approcomments->qa_appro_comments || !empty($request->qa_appro_comments_comment)) {
            $history = new RcmDocHistory;
            $history->cc_id = $id;
            $history->activity_type = 'QA Approval Comments';
            $history->previous = $lastapprocomments->qa_appro_comments;
            $history->current = $approcomments->qa_appro_comments;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastapprocomments->feedback != $approcomments->feedback || !empty($request->feedback_comment)) {
            $history = new RcmDocHistory;
            $history->cc_id = $id;
            $history->activity_type = 'Training Feedback';
            $history->previous = $lastapprocomments->feedback;
            $history->current = $approcomments->feedback;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastapprocomments->tran_attach != $approcomments->tran_attach || !empty($request->tran_attach_comment)) {
            $history = new RcmDocHistory;
            $history->cc_id = $id;
            $history->activity_type = 'Training Attachments';
            $history->previous = $lastapprocomments->tran_attach;
            $history->current = $approcomments->tran_attach;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        // --------------------Change Closure------------------
        if ($lastclosure->qa_closure_comments != $closure->qa_closure_comments || !empty($request->qa_closure_comments_comment)) {
            $history = new RcmDocHistory;
            $history->cc_id = $id;
            $history->activity_type = 'QA Closure Comments';
            $history->previous = $lastclosure->qa_closure_comments;
            $history->current = $closure->qa_closure_comments;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }

        if ($lastDocument->due_date_extension != $request->due_date_extension) {
            $history = new RcmDocHistory;
            $history->cc_id = $id;
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


    public function destroy($id)
    {
    }

    public function stageChange(Request $request, $id)
    {
        if ($request->username == Auth::user()->email && Hash::check($request->password, Auth::user()->password)) {
            $changeControl = CC::find($id);
            $lastDocument = CC::find($id);
            $evaluation = Evaluation::where('cc_id', $id)->first();
            if ($changeControl->stage == 1) {
                    $changeControl->stage = "2";
                    $changeControl->status = "HOD Review";
                    
                    $history = new RcmDocHistory;
                    $history->cc_id = $id;
                    $history->activity_type = 'Activity Log';
                    $history->previous = "";
                    $history->current = Auth::user()->name;
                    $history->comment = $request->comment;
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $lastDocument->status;
                    $history->stage = 'Submit';
                    $history->save();
            //  $list = Helpers::getHodUserList();
            //     foreach ($list as $u) {
            //         if($u->q_m_s_divisions_id == $changeControl->division_id){
            //             $email = Helpers::getInitiatorEmail($u->user_id);
            //              if ($email !== null) {
            //               Mail::send(
            //                   'mail.view-mail',
            //                    ['data' => $changeControl],
            //                 function ($message) use ($email) {
            //                     $message->to($email)
            //                         ->subject("Document is Send By".Auth::user()->name);
            //                 }
            //               );
            //             }
            //      } 
            //   }
                    $changeControl->update();
                    $history = new CCStageHistory();
                    $history->type = "Change-Control";
                    $history->doc_id = $id;
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->stage_id = $changeControl->stage;
                    $history->status = $changeControl->status;
                    $history->save();
                    
                    $history = new CCStageHistory();
                    $history->type = "Activity-log";
                    $history->doc_id = $id;
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->stage_id = $changeControl->stage;
                    $history->status = $changeControl->status;
                    $history->save();
                    // Helpers::hodMail($changeControl);
                    toastr()->success('Document Sent');
                    return back();

            }
            if ($changeControl->stage == 2) {
                    $changeControl->stage = "3";
                    $changeControl->status = "Pending CFT/SME/QA Review";
                                $history = new RcmDocHistory;
                                $history->cc_id = $id;
                                $history->activity_type = 'Activity Log';
                                $history->previous = "";
                                $history->current = Auth::user()->name;
                                $history->comment = $request->comment;
                                $history->user_id = Auth::user()->id;
                                $history->user_name = Auth::user()->name;
                                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                                $history->origin_state = $lastDocument->status;
                                $history->stage = 'HOD Review Complete';
                                $history->save();
                            //     $list = Helpers::getInitiatorUserList();
                            //     foreach ($list as $u) {
                            //         if($u->q_m_s_divisions_id == $changeControl->division_id){
                            //             $email = Helpers::getInitiatorEmail($u->user_id);
                            //              if ($email !== null) {
                                      
                            //               Mail::send(
                            //                   'mail.view-mail',
                            //                    ['data' => $changeControl],
                            //                 function ($message) use ($email) {
                            //                     $message->to($email)
                            //                         ->subject("Document is Send By".Auth::user()->name);
                            //                 }
                            //               );
                            //             }
                            //      } 
                            //   }            
                    $changeControl->update();
                    $history = new CCStageHistory();
                    $history->type = "Change-Control";
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

                    $changeControl->stage = "4";
                    $changeControl->status = "CFT/SME/QA Review";
                        $history = new RcmDocHistory;
                        $history->cc_id = $id;
                        $history->activity_type = 'Activity Log';
                        $history->previous = "";
                        $history->current = Auth::user()->name;
                        $history->comment = $request->comment;
                        $history->user_id = Auth::user()->id;
                        $history->user_name = Auth::user()->name;
                        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                        $history->origin_state = $lastDocument->status;
                        $history->stage = 'Send to CFT/SME/QA Review';
                        $history->save();
                        // $list = Helpers::getCFTUserList();
                        //         foreach ($list as $u) {
                        //             if($u->q_m_s_divisions_id == $changeControl->division_id){
                        //                 $email = Helpers::getInitiatorEmail($u->user_id);
                        //                  if ($email !== null) {
                                      
                        //                   Mail::send(
                        //                       'mail.view-mail',
                        //                        ['data' => $changeControl],
                        //                     function ($message) use ($email) {
                        //                         $message->to($email)
                        //                             ->subject("Document is Send By".Auth::user()->name);
                        //                     }
                        //                   );
                        //                 }
                        //          } 
                        //       }     
                    $changeControl->update();
                    $history = new CCStageHistory();
                    $history->type = "Change-Control";
                    $history->doc_id = $id;
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->stage_id = $changeControl->stage;
                    $history->status = $changeControl->status;
                    $history->save();
                    toastr()->success('Document Sent');
                    return back();

            }
            if ($changeControl->stage == 4) {
                if ($evaluation->training_required == "yes") {
                    $changeControl->stage = "6";
                    $changeControl->status = "Pending Change Implementation";
                //     $list = Helpers::getHodUserList();
                //     foreach ($list as $u) {
                //         if($u->q_m_s_divisions_id == $changeControl->division_id){
                //             $email = Helpers::getInitiatorEmail($u->user_id);
                //              if ($email !== null) {
                          
                //               Mail::send(
                //                   'mail.view-mail',
                //                    ['data' => $changeControl],
                //                 function ($message) use ($email) {
                //                     $message->to($email)
                //                         ->subject("Document is Send By".Auth::user()->name);
                //                 }
                //               );
                //             }
                //      } 
                //   }
                    $changeControl->update();
                    $history = new CCStageHistory();
                    $history->type = "Change-Control";
                    $history->doc_id = $id;
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->stage_id = $changeControl->stage;
                    $history->status = $changeControl->status;
                    $history->save();
                    toastr()->success('Document Sent');
                    return back();
                } else {

                    $changeControl->stage = "7";
                    $changeControl->status = "Pending Change Implementation";
                    $changeControl->update();
                            $history = new RcmDocHistory;
                            $history->cc_id = $id;
                            $history->activity_type = 'Activity Log';
                            $history->previous = "";
                            $history->current = Auth::user()->name;
                            $history->comment = $request->comment;
                            $history->user_id = Auth::user()->id;
                            $history->user_name = Auth::user()->name;
                            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                            $history->origin_state = $lastDocument->status;
                            $history->stage = 'Review Complete';
                            $history->save();
                    $history = new CCStageHistory();
                    $history->type = "Change-Control";
                    $history->doc_id = $id;
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->stage_id = $changeControl->stage;
                    $history->status = $changeControl->status;
                    $history->save();
                    toastr()->success('Document Sent');
                    return back();
                }
            }
            // if ($changeControl->stage == 5) {
            //     $rules = [
            //         'qa_eval_comments' => 'required|max:255',
            //         'training_required' => 'required',
            //         'train_comments' =>'required',
            //         ];
            //         $customMessages = [
            //             'qa_eval_comments.required' => 'The QA Evaluation comments field is required.',
            //             'training_required.required' => 'The training required field is required.',
            //             'train_comments.required' =>'The training comments field is required.',
            //         ];
            //         $validator = Validator::make($evaluation->toArray(), $rules, $customMessages);
            //         if ($validator->fails()) {
            //             $errorMessages = implode('<br>', $validator->errors()->all());
            //             session()->put('errorMessages', $errorMessages);
            // return back();
            //         }
            //     else{
            //         $changeControl->stage = "6";
            //         $changeControl->status = "CFT Review Completed";
            //         $changeControl->update();
            //         $history = new CCStageHistory();
            //         $history->type = "Change-Control";
            //         $history->doc_id = $id;
            //         $history->user_id = Auth::user()->id;
            //         $history->user_name = Auth::user()->name;
            //         $history->stage_id = $changeControl->stage;
            //         $history->status = $changeControl->status;
            //         $history->save();
            //         toastr()->success('Document Sent');
            //         return back();
            //     }

            // }


            if ($changeControl->stage == 5) {

                    $changeControl->stage = '7';
                    $changeControl->status = 'Pending Change Implemented';
                    $changeControl->update();
                    $history = new CCStageHistory();
                    $history->type = "Change-Control";
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

                    $changeControl->stage = "8";
                    $changeControl->status = "QA-Final Review";
                    $changeControl->update();
                    $history = new CCStageHistory();
                    $history->type = "Change-Control";
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

                    $changeControl->stage = "9";
                    $changeControl->status = "Closed-Done";
                            $history = new RcmDocHistory;
                            $history->cc_id = $id;
                            $history->activity_type = 'Activity Log';
                            $history->previous = "";
                            $history->current = Auth::user()->name;
                            $history->comment = $request->comment;
                            $history->user_id = Auth::user()->id;
                            $history->user_name = Auth::user()->name;
                            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                            $history->origin_state = $lastDocument->status;
                            $history->stage = 'Implemented';
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
            //                         ->subject("Document is Send By".Auth::user()->name);
            //                 }
            //               );
            //             }
            //      } 
            //   }
                    $changeControl->update();
                    $history = new CCStageHistory();
                    $history->type = "Change-Control";
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

    public function stagereject(Request $request, $id)
    {
        if ($request->username == Auth::user()->email && Hash::check($request->password, Auth::user()->password)) {
            $changeControl = CC::find($id);
            $openState = CC::find($id);

            if ($changeControl->stage == 1) {
                $changeControl->stage = "0";
                $changeControl->status = "Closed-Cancelled";
            //     $list = Helpers::getHodUserList();
            //     foreach ($list as $u) {
            //         if($u->q_m_s_divisions_id == $changeControl->division_id){
            //             $email = Helpers::getInitiatorEmail($u->user_id);
            //              if ($email !== null) {
                      
            //               Mail::send(
            //                   'mail.view-mail',
            //                    ['data' => $changeControl],
            //                 function ($message) use ($email) {
            //                     $message->to($email)
            //                         ->subject("Document is Send By".Auth::user()->name);
            //                 }
            //               );
            //             }
            //      } 
            //   }
                $changeControl->update();
                $history = new CCStageHistory();
                $history->type = "Change-Control";
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
            //     $list = Helpers::getInitiatorUserList();
            //     foreach ($list as $u) {
            //         if($u->q_m_s_divisions_id == $changeControl->division_id){
            //             $email = Helpers::getInitiatorEmail($u->user_id);
            //              if ($email !== null) {
                      
            //               Mail::send(
            //                   'mail.view-mail',
            //                    ['data' => $changeControl],
            //                 function ($message) use ($email) {
            //                     $message->to($email)
            //                         ->subject("Document is Send By".Auth::user()->name);
            //                 }
            //               );
            //             }
            //      } 
            //   }
                $changeControl->update();
                $history = new CCStageHistory();
                $history->type = "Change-Control";
                $history->doc_id = $id;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->stage_id = $changeControl->stage;
                $history->status = "More-info Required";
                $history->save();
                toastr()->success('Document Sent');
                return back();
            }
            if ($changeControl->stage == 3) {
                $changeControl->stage = "2";
                $changeControl->status = "HOD Review";
            //     $list = Helpers::getHodUserList();
            //     foreach ($list as $u) {
            //         if($u->q_m_s_divisions_id == $changeControl->division_id){
            //             $email = Helpers::getInitiatorEmail($u->user_id);
            //              if ($email !== null) {
                      
            //               Mail::send(
            //                   'mail.view-mail',
            //                    ['data' => $changeControl],
            //                 function ($message) use ($email) {
            //                     $message->to($email)
            //                         ->subject("Document is Send By".Auth::user()->name);
            //                 }
            //               );
            //             }
            //      } 
            //   }
                $changeControl->update();
                $history = new CCStageHistory();
                $history->type = "Change-Control";
                $history->doc_id = $id;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->stage_id = $changeControl->stage;
                $history->status = "More-info Required";
                $history->save();
                toastr()->success('Document Sent');
                return back();
            }
            if ($changeControl->stage == 4) {
                $changeControl->stage = "3";
                $changeControl->status = "Under Supervisor review";
            //     $list = Helpers::getHodUserList();
            //     foreach ($list as $u) {
            //         if($u->q_m_s_divisions_id == $changeControl->division_id){
            //             $email = Helpers::getInitiatorEmail($u->user_id);
            //              if ($email !== null) {
                      
            //               Mail::send(
            //                   'mail.view-mail',
            //                    ['data' => $changeControl],
            //                 function ($message) use ($email) {
            //                     $message->to($email)
            //                         ->subject("Document is Send By".Auth::user()->name);
            //                 }
            //               );
            //             }
            //      } 
            //   }
                $changeControl->update();
                $history = new CCStageHistory();
                $history->type = "Change-Control";
                $history->doc_id = $id;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->stage_id = $changeControl->stage;
                $history->status = "More-info Required";
                $history->save();
                toastr()->success('Document Sent');
                return back();
            }
            if ($changeControl->stage == 5) {
                $changeControl->stage = "4";
                $changeControl->status = "QA Review";
                $changeControl->update();
                $history = new CCStageHistory();
                $history->type = "Change-Control";
                $history->doc_id = $id;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->stage_id = $changeControl->stage;
                $history->status = "More-info Required";
                $history->save();
                toastr()->success('Document Sent');
                return back();
            }
        } else {
            toastr()->error('E-signature Not match');
            return back();
        }
    }


    public function stageCFTnotReq(Request $request, $id)
    {
        if ($request->username == Auth::user()->email && Hash::check($request->password, Auth::user()->password)) {
            $changeControl = CC::find($id);
            $lastDocument = CC::find($id);
            $openState = CC::find($id);

            $changeControl->stage = "7";
            $changeControl->status = "Pending Change Implementation";
                    $history = new RcmDocHistory;
                    $history->cc_id = $id;
                    $history->activity_type = 'Activity Log';
                    $history->previous = "";
                    $history->current = Auth::user()->name;
                    $history->comment = $request->comment;
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $lastDocument->status;
                    $history->stage = 'CFT/SME/QA Review Not required';
                    $history->save();
            $changeControl->update();
            $history = new CCStageHistory();
            $history->type = "Change-Control";
            $history->doc_id = $id;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->stage_id = $changeControl->stage;
            $history->status = $changeControl->status;
            $history->save();
            toastr()->success('Document Sent');
            return back();
        } else {
            toastr()->error('E-signature Not match');
            return back();
        }
    }

    public function stagecancel(Request $request, $id)
    {
        if ($request->username == Auth::user()->email && Hash::check($request->password, Auth::user()->password)) {
            $changeControl = CC::find($id);
            $openState = CC::find($id);

 
            $changeControl->stage = "0";
            $changeControl->status = "Closed-Cancelled";
            $changeControl->update();
            $history = new CCStageHistory();
            $history->type = "Change-Control";
            $history->doc_id = $id;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->stage_id = $changeControl->stage;
            $history->status = $changeControl->status;
            $history->save();
            toastr()->success('Document Sent');
            return back();
        } else {
            toastr()->error('E-signature Not match');
            return back();
        }
    }
    public function child(Request $request,$id){
        // return "hiii";
        $cc = CC::find($id);
        $parent_name = "CC";
        $record_number = ((RecordNumber::first()->value('counter')) + 1);
        $record_number = str_pad($record_number, 4, '0', STR_PAD_LEFT);
        $currentDate = Carbon::now();
        $formattedDate = $currentDate->addDays(30);
        $due_date = $formattedDate->format('d-M-Y');

        $parent_data = CC::where('id', $id)->select('record','division_id','initiator_id','short_description')->first();
        $parent_data1 = CC::select('record','division_id','initiator_id','id')->get();
        $parent_record = CC::where('id', $id)->value('record');
        $parent_record = str_pad($parent_record, 4, '0', STR_PAD_LEFT);
        $parent_division_id = CC::where('id', $id)->value('division_id');
        $parent_initiator_id = CC::where('id', $id)->value('initiator_id');
        $parent_intiation_date = CC::where('id', $id)->value('intiation_date');
        $parent_short_description = CC::where('id', $id)->value('short_description');
        $old_record = CC::select('id', 'division_id', 'record')->get();

        if($request->revision == "Action-Item"){
            $cc->originator = User::where('id',$cc->initiator_id)->value('name');
            return view('frontend.forms.action-item',compact('parent_record','parent_name','record_number','cc','parent_data','parent_data1','parent_short_description','parent_initiator_id','parent_intiation_date','parent_division_id','due_date','old_record'));
        }
        if($request->revision == "Extension"){
            $cc->originator = User::where('id',$cc->initiator_id)->value('name');
            return view('frontend.forms.extension',compact('parent_name','record_number','parent_short_description','parent_initiator_id','parent_intiation_date','parent_division_id', 'parent_record','cc'));
        }
        if($request->revision == "New Document"){
            $cc->originator = User::where('id',$cc->initiator_id)->value('name');
            return redirect()->route('documents.create');
            
        }
        else{
            toastr()->warning('Not Working');
            return back();
        }
    }

    public function auditTrial($id)
    {
        $audit = RcmDocHistory::where('cc_id', $id)->orderByDESC('id')->get()->unique('activity_type');
        $today = Carbon::now()->format('d-m-y');
        $document = CC::where('id', $id)->first();
        $document->originator = User::where('id', $document->initiator_id)->value('name');

        return view('frontend.rcms.CC.audit-trial', compact('audit', 'document', 'today'));
    }

    public function auditDetails($id)
    {
        $detail = RcmDocHistory::find($id);
        $detail_data = RcmDocHistory::where('activity_type', $detail->activity_type)->where('cc_id', $detail->cc_id)->latest()->get();
        $doc = CC::where('id', $detail->cc_id)->first();
        $doc->origiator_name = User::find($doc->initiator_id);
        return view('frontend.rcms.CC.audit-trial-inner', compact('detail', 'doc', 'detail_data'));
    }



    public function summery_pdf($id)
    {
        $data = CC::find($id);
        if (!empty($data)) {
            $data->originator = User::where('id', $data->initiator_id)->value('name');
        } else {
            $datas = ActionItem::find($id);

            if (empty($datas)) {
                $datas = Extension::find($id);
                $data = CC::find($datas->cc_id);
                $data->originator = User::where('id', $data->initiator_id)->value('name');
                $data->created_at = $datas->created_at;
            } else {
                $data = CC::find($datas->cc_id);
                $data->originator = User::where('id', $data->initiator_id)->value('name');
                $data->created_at = $datas->created_at;
            }
        }

        // pdf related work
        $pdf = App::make('dompdf.wrapper');
        $time = Carbon::now();
        $pdf = PDF::loadview('frontend.change-control.summary_pdf', compact('data', 'time'))
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

        $canvas->page_text(
            $width / 3,
            $height / 2,
            $data->status,
            null,
            60,
            [0, 0, 0],
            2,
            6,
            -20
        );

        if ($data->documents) {

            $pdfArray = explode(',', $data->documents);
            foreach ($pdfArray as $pdfFile) {
                $existingPdfPath = public_path('upload/PDF/' . $pdfFile);
                $permissions = 0644; // Example permission value, change it according to your needs
                if (file_exists($existingPdfPath)) {
                    // Create a new Dompdf instance
                    $options = new Options();
                    $options->set('chroot', public_path());
                    $options->set('isPhpEnabled', true);
                    $options->set('isRemoteEnabled', true);
                    $options->set('isHtml5ParserEnabled', true);
                    $options->set('allowedFileExtensions', ['pdf']); // Allow PDF file extension

                    $dompdf = new Dompdf($options);

                    chmod($existingPdfPath, $permissions);

                    // Load the existing PDF file
                    $dompdf->loadHtmlFile($existingPdfPath);

                    // Render the PDF
                    $dompdf->render();

                    // Output the PDF to the browser
                    $dompdf->stream();
                }
            }
        }

        return $pdf->stream('SOP' . $id . '.pdf');
    }

    public function audit_pdf($id)
    {
        $doc = CC::find($id);
        if (!empty($doc)) {
            $doc->originator = User::where('id', $doc->initiator_id)->value('name');
        } else {
            $datas = ActionItem::find($id);

            if (empty($datas)) {
                $datas = Extension::find($id);
                $doc = CC::find($datas->cc_id);
                $doc->originator = User::where('id', $doc->initiator_id)->value('name');
                $doc->created_at = $datas->created_at;
            } else {
                $doc = CC::find($datas->cc_id);
                $doc->originator = User::where('id', $doc->initiator_id)->value('name');
                $doc->created_at = $datas->created_at;
            }
        }
        $data = RcmDocHistory::where('cc_id', $doc->id)->orderByDesc('id')->get();
        // pdf related work
        $pdf = App::make('dompdf.wrapper');
        $time = Carbon::now();
        $pdf = PDF::loadview('frontend.change-control.audit_trial_pdf', compact('data', 'doc'))
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

        $canvas->page_text(
            $width / 3,
            $height / 2,
            $doc->status,
            null,
            60,
            [0, 0, 0],
            2,
            6,
            -20
        );



        return $pdf->stream('SOP' . $id . '.pdf');
    }

    public function ccView($id)
    {

        $data = CC::find($id);
        if (empty($data)) {
            $data = ActionItem::find($id);
            if (empty($data)) {
                $data = Extension::find($id);
            }
        }
        $html = '';
        $html = '<div class="block">
        <div class="record_no">
            Record No. ' . str_pad($data->record, 4, '0', STR_PAD_LEFT) .
            '</div>
        <div class="short_desc">' .
            $data->short_description . '
        </div>
        <div class="division">
            QMS - EMEA / Change Control
        </div>
        <div class="status">' .
            $data->status . '
        </div>
            </div>
            <div class="block">
                <div class="block-head">
                    Actions
                </div>
                <div class="block-list">
                    <a href="/rcms/audit/' . $data->id . '" class="list-item">View History</a>
                    <a href="send-notification" class="list-item">Send Notification</a>
                    <div class="list-drop">
                        <div class="list-item" onclick="showAction()">
                            <div>Run Report</div>
                            <div><i class="fa-solid fa-angle-down"></i></div>
                        </div>
                        <div class="drop-list">
                            <a target="_blank" href="summary/' . $data->id . '" class="inner-item">Change Control Summary</a>
                            <a target="_blank" href="/rcms/audit/' . $data->id . '" class="inner-item">Audit Trail</a>
                            <a target="_blank" href="/rcms/change_control_single_pdf/' . $data->id . '" class="inner-item">Change Control Single Report</a>
                            <a target="_blank" href="/rcms/change_control_family_pdf" class="inner-item">Change Control Parent with Immediate Child</a>
                        </div>
                    </div>
                </div>
            </div>';
        $response['html'] = $html;

        return response()->json($response);
    }
    public function single_pdf($id)
    {
        $data = CC::find($id);
        if (!empty($data)) {
            $data->originator = User::where('id', $data->initiator_id)->value('name');

            $idsArray = explode(',', $data->Microbiology_Person);
            $users = User::whereIn('id', $idsArray)->get(['name']);
            $userNames = $users->pluck('name')->implode(', ');

            $docdetail = Docdetail::where('cc_id', $data->id)->first();
            $review = Qareview::where('cc_id', $data->id)->first();
            $evaluation = Evaluation::where('cc_id', $data->id)->first();
            $info = AdditionalInformation::where('cc_id', $data->id)->first();
            $comments = GroupComments::where('cc_id', $data->id)->first();
            $assessment = RiskAssessment::where('cc_id', $data->id)->first();
            $approcomments = QaApprovalComments::where('cc_id', $data->id)->first();
            $closure = ChangeClosure::where('cc_id', $data->id)->first();


            // pdf related work
            $pdf = App::make('dompdf.wrapper');
            $time = Carbon::now();
            $pdf = PDF::loadview('frontend.change-control.change_control_single_pdf', compact(
                'data',
                'docdetail',
                'review',
                'evaluation',
                'info',
                'comments',
                'assessment',
                'approcomments',
                'closure',
                'userNames'
            ))
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

            $canvas->page_text(
                $width / 4,
                $height / 2,
                $data->status,
                null,
                25,
                [0, 0, 0],
                2,
                6,
                -20
            );



            return $pdf->stream('SOP' . $id . '.pdf');
        }
    }


    public function parent_child()
    {



        // pdf related work
        $pdf = App::make('dompdf.wrapper');
        $time = Carbon::now();
        $pdf = PDF::loadview('frontend.change-control.change_control_family_pdf')
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

        $canvas->page_text(
            $width / 4,
            $height / 2,
            "Opened",
            null,
            25,
            [0, 0, 0],
            2,
            6,
            -20
        );



        return $pdf->stream('SOP.pdf');
    }

    public function eCheck($id)
    {
        $data = CC::find($id);
        return view('frontend.effectivenessCheck.create', compact('data'));
    }
}
