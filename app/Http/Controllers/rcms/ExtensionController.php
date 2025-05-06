<?php

namespace App\Http\Controllers\rcms;

use App\Http\Controllers\Controller;
use App\Models\CC;
use App\Models\CCStageHistory;
use App\Models\Extension;
use App\Models\QaApproval;
use App\Models\RecordNumber;
use App\Models\ExtensionAuditTrail;
use App\Models\QMSDivision;
use App\Models\User;
use App\Models\RoleGroup;
use App\Services\DocumentService;
use PDF;
use Helpers;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class ExtensionController extends Controller
{


    public function extension_child(Request $request)
    {
        
        $parent_due_date = "";
        $parent_id = '';
        $parent_name = $request->parent_name;
        if ($request->due_date) {
            $parent_due_date = $request->due_date;
        }
        $old_record = Extension::select('id', 'division_id', 'record')->get();
        $record_number = ((RecordNumber::first()->value('counter')) + 1);
        $record_number = str_pad($record_number, 4, '0', STR_PAD_LEFT);
        
        $division = QMSDivision::where('name', Helpers::getDivisionName(session()->get('division')))->first();

        if ($division) {
            $last_extension = Extension::where('division_id', $division->id)->latest()->first();

            if ($last_extension) {
                $record_number = $last_extension->record_number ? str_pad($last_extension->record_number->record_number + 1, 4, '0', STR_PAD_LEFT) : '0001';
            } else {
                $record_number = '0001';
            }
        }

        if($request->child_type=='documents'){
            return redirect('documents/create');
        }
        return view('frontend.forms.extension', compact('parent_id', 'parent_name','old_record', 'record_number', 'parent_due_date'));
    }
    public function index()
    {
        $document = Extension::all();

        foreach ($document as $data) {
            $cc = CC::find($data->cc_id);
            $data->originator = User::where('id', $cc->initiator_id)->value('name');
        }
        return view('frontend.extension.extension', compact('document'));
    }



    public function store(Request $request)
    {

        $openState = new Extension();
        $openState->cc_id = $request->cc_id;
        $openState->parent_id = $request->parent_id;
        $openState->parent_type = $request->parent_type;
        $openState->initiator_id = Auth::user()->id;

        $openState->intiation_date = $request->intiation_date;
        $openState->revised_date = $request->revised_date;
        
        $openState->due_date = $request->due_date;
        $openState->division_id = $request->division_id;
        if (!empty($request->extention_attachment)) {
            $files = [];
            if ($request->hasfile('extention_attachment')) {
                foreach ($request->file('extention_attachment') as $file) {
                    $name = $request->name . '-extention_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
            $openState->extention_attachment = json_encode($files);
        }
        if (!empty($request->closure_attachments)) {
            $files = [];
            if ($request->hasfile('closure_attachments')) {
                foreach ($request->file('closure_attachments') as $file) {
                    $name = $request->name . '-closure_attachments' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
            $openState->closure_attachments = json_encode($files);
        }
      
        $openState->approver1 = $request->approver1;
        $openState->approver_comments = $request->approver_comments;
        $openState->record = DB::table('record_numbers')->value('counter') + 1;
        $openState->short_description = $request->short_description;
        $openState->initiated_through = $request->initiated_through;
        $openState->initiated_if_other = $request->initiated_if_other;

        // $openState->short_description = $request->short_description;
        $openState->justification = $request->justification;
        // $openState->refrence_record=  implode(',', $request->refrence_record);
        
        $openState->type = $request->type;
        $openState->status = "Opened";
        $openState->stage = 1;
        $openState->save();
        // -----------------------------------------
        $counter = DB::table('record_numbers')->value('counter');
        $recordNumber = str_pad($counter, 5, '0', STR_PAD_LEFT);
        $newCounter = $counter + 1;
        DB::table('record_numbers')->update(['counter' => $newCounter]);
        if ($request->cc_id) {
            $appro  = new QaApproval();
            $appro->cc_id =  $openState->id;
            $appro->appro_comments = $request->appro_comments;
            $appro->save();
        }

        if (!empty($request->short_description)) {

            $history = new ExtensionAuditTrail();
            $history->extension_id = $openState->id;
            $history->activity_type = 'Short Description';
            $history->previous = "Null";
            $history->current = $openState->short_description;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->save();
            }
        // if (!empty($request->initiator_id)) {

        $history = new ExtensionAuditTrail();
        $history->extension_id = $openState->id;
        $history->activity_type  = 'Initiator';
        $history->previous = "Null";
        $history->current = Helpers::getInitiatorName($openState->initiator_id);
        $history->comment = "NA";
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $openState->status;
        $history->save();
        // }

        $history = new ExtensionAuditTrail();
        $history->extension_id = $openState->id;
        $history->activity_type = 'Record Number';
        $history->previous = "Null";
        $history->current = Helpers::getDivisionName($openState->division_id) . '/Extension/' . Helpers::year($openState->created_at) . '/' . str_pad($openState->record, 4, '0', STR_PAD_LEFT); 
        $history->comment = "NA";
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $openState->status;
        $history->save();

        if (!empty($request->intiation_date)) {

        $history = new ExtensionAuditTrail();
        $history->extension_id = $openState->id;
        $history->activity_type = 'Date Of Initiation';
        $history->previous = "Null";
        $history->current = $openState->intiation_date;
        $history->comment = "NA";
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $openState->status;
        $history->save();
        }
        if (!empty($request->due_date)) {

        $history = new ExtensionAuditTrail();
        $history->extension_id = $openState->id;
        $history->activity_type = 'Current Parent Due Date';
        $history->previous = "Null";
        $history->current = Helpers::getdateFormat($openState->due_date);
        $history->comment = "NA";
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $openState->status;
        $history->save();
        }
            if (!empty($request->revised_date)) {

            $history = new ExtensionAuditTrail();
            $history->extension_id = $openState->id;
            $history->activity_type = 'Revised Due Date';
            $history->previous = "Null";
            $history->current = Helpers::getdateFormat($openState->revised_date);
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->save();
            }
        if (!empty($request->justification)) {

            $history = new ExtensionAuditTrail();
            $history->extension_id = $openState->id;
            $history->activity_type = 'Justification of Extension';
            $history->previous = "Null";
            $history->current = $openState->justification;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->save();
        }
        if (!empty($request->division_id)) {

            $history = new ExtensionAuditTrail();
            $history->extension_id = $openState->id;
            $history->activity_type = 'Division Code';
            $history->previous = "Null";
            $history->current = Helpers::getDivisionName($openState->division_id);
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->save();
         }
        if (!empty($request->approver1)) {
            $history = new ExtensionAuditTrail();
            $history->extension_id = $openState->id;
            $history->activity_type = 'Approver';
            $history->previous = "Null";
            $history->current = Helpers::getInitiatorName($openState->approver1);
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->save();
        }

        if (!empty($request->initiated_through)) {

        $history = new ExtensionAuditTrail();
        $history->extension_id = $openState->id;
        $history->activity_type = 'Initiated Through';
        $history->previous = "Null";
        $history->current = $openState->initiated_through;
        $history->comment = "NA";
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $openState->status;
        $history->save();
        }
        if (!empty($request->approver_comments)) {

            $history = new ExtensionAuditTrail();
            $history->extension_id = $openState->id;
            $history->activity_type = 'Approver Comments';
            $history->previous = "Null";
            $history->current = $openState->approver_comments;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->save();
            }
        if (!empty($request->initiated_if_other)) {

        $history = new ExtensionAuditTrail();
        $history->extension_id = $openState->id;
        $history->activity_type = 'Reference Record';
        $history->previous = "Null";
        $history->current = $openState->initiated_if_other;
        $history->comment = "NA";
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $openState->status;
        $history->save();
        }
        
        if (!empty($request->extention_attachment)) {

        $history = new ExtensionAuditTrail();
        $history->extension_id = $openState->id;
        $history->activity_type = 'Extention Attachments';
        $history->previous = "Null";
        $history->current = $openState->extention_attachment;
        $history->comment = "NA";
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $openState->status;
        $history->save();
        }
        if (!empty($request->closure_attachments)) {

        $history = new ExtensionAuditTrail();
        $history->extension_id = $openState->id;
        $history->activity_type = 'Closure Attachments';
        $history->previous = "Null";
        $history->current = $openState->closure_attachments;
        $history->comment = "NA";
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $openState->status;
        $history->save();
        }
        DocumentService::update_qms_numbers();

        toastr()->success('Document created');
        return redirect('rcms/qms-dashboard');
    }

    public function show($id)
    {
        $old_record = Extension::select('id', 'division_id', 'record')->get();
        $data = Extension::find($id);
        $cc = CC::find($data->cc_id);
        $appro  = QaApproval::where('cc_id', $id)->first();

        return view('frontend.extension.View', compact('data', 'old_record','cc', 'appro'));
    }

    public function edit($id)
    {
    }

    public function update(Request $request, $id)
    {
        $lastDocument = Extension::find($id);
        $openState = Extension::find($id);
        $openState->short_description = $request->short_description;
        $openState->justification = $request->justification;
        // $openState->refrence_record=  implode(',', $request->refrence_record);
        $openState->initiated_through = $request->initiated_through;
        $openState->type = $request->type;
                //  dd($request->approver1);
        $openState->approver1 = $request->approver1;
        
        $openState->revised_date = $request->revised_date;
        $openState->initiated_if_other = $request->initiated_if_other;
        
        $openState->due_date = $request->due_date;
        if (!empty($request->extention_attachment)) {
            $files = [];
            if ($request->hasfile('extention_attachment')) {
                foreach ($request->file('extention_attachment') as $file) {
                    $name = $request->name . '-extention_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
            $openState->extention_attachment = json_encode($files);
        }
        if (!empty($request->closure_attachments)) {
            $files = [];
            if ($request->hasfile('closure_attachments')) {
                foreach ($request->file('closure_attachments') as $file) {
                    $name = $request->name . '-closure_attachments' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
            $openState->closure_attachments = json_encode($files);
        }
       
        
        $openState->approver_comments = $request->approver_comments;

        $openState->save();



        

        if ($lastDocument->approver1 != $openState->approver1 || !empty($request->approver1_comment)) {

            $history = new ExtensionAuditTrail();
            $history->extension_id = $id;
            $history->activity_type = 'Approver';
            $history->previous = Helpers::getInitiatorName($lastDocument->approver1);
            $history->current = Helpers::getInitiatorName($openState->approver1);
            $history->comment = $request->approver1_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }

        if ($lastDocument->due_date != $openState->due_date || !empty($request->due_date_comment)) {

            $history = new ExtensionAuditTrail();
            $history->extension_id = $id;
            $history->activity_type = 'Current Parent Due Date';
            $history->previous = Helpers::getdateFormat($lastDocument->due_date);
            $history->current = Helpers::getdateFormat($openState->due_date);
            $history->comment = $request->approver1_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->revised_date != $openState->revised_date || !empty($request->revised_date_comment)) {

            $history = new ExtensionAuditTrail();
            $history->extension_id = $id;
            $history->activity_type = 'Revised Due Date';
            $history->previous = Helpers::getdateFormat($lastDocument->revised_date);
            $history->current = Helpers::getdateFormat($openState->revised_date);
            $history->comment = $request->approver1_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
       
        if ($lastDocument->short_description != $openState->short_description || !empty($request->short_description_comment)) {

            $history = new ExtensionAuditTrail();
            $history->extension_id = $id;
            $history->activity_type = 'Short description';
            $history->previous = $lastDocument->short_description;
            $history->current = $openState->short_description;
            $history->comment = $request->short_description_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->justification != $openState->justification || !empty($request->justification_comment)) {

            $history = new ExtensionAuditTrail();
            $history->extension_id = $id;
            $history->activity_type = 'Justification of Extension';
            $history->previous = $lastDocument->justification;
            $history->current = $openState->justification;
            $history->comment = $request->justification_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }

        if ($lastDocument->initiated_through != $openState->initiated_through || !empty($request->initiated_through_comment)) {

            $history = new ExtensionAuditTrail();
            $history->extension_id = $id;
            $history->activity_type = 'Initiated Through';
            $history->previous = $lastDocument->initiated_through;
            $history->current = $openState->initiated_through;
            $history->comment = $request->initiated_through_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->approver_comments != $openState->approver_comments || !empty($request->approver_comments_comment)) {

            $history = new ExtensionAuditTrail();
            $history->extension_id = $id;
            $history->activity_type = 'Approver Comments';
            $history->previous = $lastDocument->approver_comments;
            $history->current = $openState->approver_comments;
            $history->comment = $request->approver_comments_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }


        if ($lastDocument->initiated_if_other != $openState->initiated_if_other || !empty($request->initiated_if_other_comment)) {

            $history = new ExtensionAuditTrail();
            $history->extension_id = $id;
            $history->activity_type = 'Reference Record';
            $history->previous = $lastDocument->initiated_if_other;
            $history->current = $openState->initiated_if_other;
            $history->comment = $request->initiated_if_other_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }

        if ($lastDocument->extention_attachment != $openState->extention_attachment || !empty($request->extention_attachment_comment)) {

            $history = new ExtensionAuditTrail();
            $history->extension_id = $id;
            $history->activity_type = 'Extention Attachments';
            $history->previous = $lastDocument->extention_attachment;
            $history->current = $openState->extention_attachment;
            $history->comment = $request->extention_attachment_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }

        if ($lastDocument->closure_attachments != $openState->closure_attachments || !empty($request->closure_attachments_comment)) {

            $history = new ExtensionAuditTrail();
            $history->extension_id = $id;
            $history->activity_type = 'Closure Attachments';
            $history->previous = $lastDocument->closure_attachments;
            $history->current = $openState->closure_attachments;
            $history->comment = $request->closure_attachments_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }

        // $appro  = QaApproval::where('cc_id', $id)->first();
        // $appro->cc_id =  $openState->id;
        // $appro->appro_comments = $request->appro_comments;
        // $appro->update();

        DocumentService::update_qms_numbers();

        toastr()->success('Record is Updated Successfully');
        return back();
    }

    public function destroy($id)
    {
    }

    public function stageChange(Request $request, $id)
    {
        $openState = Extension::find($id);
        if ($request->username == Auth::user()->email && Hash::check($request->password, Auth::user()->password)) {
            $changeControl = Extension::find($id);
            $lastDocument = Extension::find($id);
            $task = QaApproval::where('cc_id', $id)->first();
            if ($changeControl->stage == 1) {
                $rules = [
                    'appro_comments' => 'required|max:255',

                ];
                $customMessages = [
                    'appro_comments.required' => 'The Qa Approver Comment field is required.',

                ];
                if ($task) {
                    $validator = Validator::make($task->toArray(), $rules, $customMessages);
                    if ($validator->fails()) {
                        $errorMessages = implode('<br>', $validator->errors()->all());
                        session()->put('errorMessages', $errorMessages);
                        return back();
                    } else {
                        $changeControl->stage = "2";
                        $changeControl->status = "Pending Approval";
                        $changeControl->update();
                        $history = new CCStageHistory();
                        $history->type = "Extension";
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
                    $changeControl->stage = "2";
                    $changeControl->status = "Pending Approval";
                    $changeControl->submitted_on =Carbon::now()->format('d-M-Y');
                    $changeControl->submitted_by =Auth::user()->name;
                    // $changeControl->submitted_on =  Carbon::now()->format('d-M-Y');
                    // $changeControl->submitted_by = Auth::user()->name;
                            $history = new ExtensionAuditTrail();
                            $history->extension_id = $id;
                            $history->activity_type = 'Activity Log';
                            // $history->previous = "";
                            // $history->current =  $changeControl->submitted_by;
                            $history->previous = $lastDocument->status;
                            $history->current = "Pending Approval";
                            $history->comment = $request->comment;
                            $history->user_id = Auth::user()->id;
                            $history->user_name = Auth::user()->name;
                            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                            $history->origin_state = $lastDocument->status;
                            $history->stage = "Submitted";
                            $history->save();
                   
                    $changeControl->update();
                    $changeControl->update();
                    $history = new CCStageHistory();
                    $history->type = "Extension";
                    $history->doc_id = $id;
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->stage_id = $changeControl->stage;
                    $history->status = $changeControl->status;
                    $history->save();
                    // $list = Helpers::getApproverUserList();
                    // foreach ($list as $u) {
                    //     if($u->q_m_s_divisions_id == $openState->division_id){
                    //      $email = Helpers::getInitiatorEmail($u->user_id);
                    //      if ($email !== null) {
                    //          Mail::send(
                    //             'mail.view-mail',
                    //             ['data' => $openState],
                    //             function ($message) use ($email) {
                    //                 $message->to($email)
                    //                     ->subject("Document is Send By ".Auth::user()->name);
                    //             }
                    //         );
                    //       }
                    //     } 
                    // }

                $list = Helpers::getApproverUserList($changeControl->division_id);
                $userIds = collect($list)->pluck('user_id')->toArray();
                $users = User::whereIn('id', $userIds)->select('id', 'name', 'email')->get();
                $userIdNew = $users->pluck('id')->implode(',');
                $userId = $users->pluck('name')->implode(',');
                if(!empty($userId)){
                    try {
                        $notification = new ExtensionAuditTrail();
                        $notification->extension_id = $id;
                        $notification->activity_type = "Notification";
                        $notification->action = 'Notification';
                        $notification->comment = "";
                        $notification->user_id = Auth::user()->id;
                        $notification->user_name = Auth::user()->name;
                        $notification->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                        $notification->origin_state = "Not Applicable";
                        $notification->previous = $lastDocument->status;
                        $notification->current = "Pending Approval";
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

                        // dd($list);
                        foreach ($list as $u) {
                            $email = Helpers:: getAllUserEmail($u->user_id);
                            if (!empty($email)) {
                                try {
                                    info('Sending mail to', [$email]);
                                    Mail::send(
                                        'mail.view-mail',
                                        ['data' => $changeControl,'site'=>'Extension','history' => 'Submitted', 'process' => 'Extension', 'comment' => $request->comment,'user'=> Auth::user()->name],
                                        function ($message) use ($email, $changeControl) {
                                         $message->to($email)
                                         ->subject("QMS Notification: Extension , Record #" . str_pad($changeControl->record, 4, '0', STR_PAD_LEFT) . " - Activity: Submitted Performed"); }
                                        );
        
                                } catch (\Exception $e) {
                                    \Log::error('Mail failed to send: ' . $e->getMessage());
                                }
                            }
                            // }
                        }

                    toastr()->success('Document Sent');

                    return back();
                }
            }

            if ($changeControl->stage == 2) {
                $changeControl->stage = "3";
                $changeControl->status = "Closed-Done";
                $changeControl->ext_approved_by = Auth::user()->name;
                $changeControl->ext_approved_on = Carbon::now()->format('d-M-Y');
                        $history = new ExtensionAuditTrail();
                        $history->extension_id = $id;
                        $history->activity_type = 'Activity Log';
                        $history->previous = $lastDocument->status;
                        $history->current = "Closed-Done";
                        $history->comment = $request->comment;
                        $history->user_id = Auth::user()->id;
                        $history->user_name = Auth::user()->name;
                        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                        $history->origin_state = $lastDocument->status;
                        $history->stage = "Ext Approved";
                        $history->save();
                        $changeControl->update();
                
               
                $list = Helpers::getApproverUserList($changeControl->division_id);
                $userIds = collect($list)->pluck('user_id')->toArray();
                $users = User::whereIn('id', $userIds)->select('id', 'name', 'email')->get();
                $userIdNew = $users->pluck('id')->implode(',');
                $userId = $users->pluck('name')->implode(',');
                if(!empty($userId)){
                    try {
                        $notification = new ExtensionAuditTrail();
                        $notification->extension_id = $id;
                        $notification->activity_type = "Notification";
                        $notification->action = 'Notification';
                        $notification->comment = "";
                        $notification->user_id = Auth::user()->id;
                        $notification->user_name = Auth::user()->name;
                        $notification->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                        $notification->origin_state = "Not Applicable";
                        $notification->previous = $lastDocument->status;
                        $notification->current = "Ext Approved";
                        $notification->stage = "";
                        $notification->action_name = "";
                        $notification->mailUserId = $userIdNew;
                        $notification->role_name = "Approver";
                        $notification->save();
                        // dd($history);
                    } catch (\Throwable $e) {
                        \Log::error('Mail failed to send: ' . $e->getMessage());
                    }
                }

                        // dd($list);
                        foreach ($list as $u) {
                            $email = Helpers:: getAllUserEmail($u->user_id);
                            if (!empty($email)) {
                                try {
                                    info('Sending mail to', [$email]);
                                    Mail::send(
                                        'mail.view-mail',
                                        ['data' => $changeControl,'site'=>'Extension','history' => 'Ext Approved', 'process' => 'Extension', 'comment' => $request->comment,'user'=> Auth::user()->name],
                                        function ($message) use ($email, $changeControl) {
                                         $message->to($email)
                                         ->subject("QMS Notification: Extension , Record #" . str_pad($changeControl->record, 4, '0', STR_PAD_LEFT) . " - Activity: Ext Approved Performed"); }
                                        );
        
                                } catch (\Exception $e) {
                                    \Log::error('Mail failed to send: ' . $e->getMessage());
                                }
                            }
                            // }
                        }

                toastr()->success('Document Sent');
                return back();
            }
        } else {
            toastr()->error('E-signature Not match');
            return back();
        }
    }


    public function stagecancel(Request $request, $id)
    {
        if ($request->username == Auth::user()->email && Hash::check($request->password, Auth::user()->password)) {
            $changeControl = Extension::find($id);
            $lastDocument = Extension::find($id);
            $openState = Extension::find($id);

            if ($changeControl->stage == 1) {
                $changeControl->stage = "0";
                $changeControl->status = "Closed-Cancelled";
                $changeControl->cancelled_by = Auth::user()->name;
                $changeControl->cancelled_on = Carbon::now()->format('d-M-Y');;
                            $history = new ExtensionAuditTrail();
                            $history->extension_id = $id;
                            $history->activity_type = 'Activity Log';
                            $history->previous = $lastDocument->status;
                            $history->current = "Closed-Cancelled";
                            $history->comment = $request->comment;
                            $history->user_id = Auth::user()->id;
                            $history->user_name = Auth::user()->name;
                            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                            $history->origin_state = $lastDocument->status;
                            $history->stage = 'Cancelled';
                            $history->save();
                $changeControl->update();
                $history = new CCStageHistory();
                $history->type = "Extension";
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
                $changeControl->more_information_required_by = Auth::user()->name;
                $changeControl->more_information_required_on = Carbon::now()->format('d-M-Y');
                                $history = new ExtensionAuditTrail();
                                $history->extension_id = $id;
                                $history->activity_type = 'Activity Log';
                                $history->previous = $lastDocument->status;
                                $history->current = "Opened";
                                $history->comment = $request->comment;
                                $history->user_id = Auth::user()->id;
                                $history->user_name = Auth::user()->name;
                                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                                $history->origin_state = $lastDocument->status;
                                $history->stage = 'More Info Required';
                                $history->save();
                $changeControl->update();
                $history = new CCStageHistory();
                $history->type = "Extension";
                $history->doc_id = $id;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->stage_id = $changeControl->stage;
                $history->status = "More-information Required";
                $history->save();
                // $list = Helpers::getInitiatorUserList();
                // foreach ($list as $u) {
                //     if($u->q_m_s_divisions_id == $openState->division_id){
                //      $email = Helpers::getInitiatorEmail($u->user_id);
                //      if ($email !== null) {
                //          Mail::send(
                //             'mail.view-mail',
                //             ['data' => $openState],
                //             function ($message) use ($email) {
                //                 $message->to($email)
                //                     ->subject("Document is Send By ".Auth::user()->name);
                //             }
                //         );
                //       }
                //     } 
                // }

                $list = Helpers::getInitiatorUserList($changeControl->division_id);

                // $list = Helpers::getHODUserList($capa->division_id);
                $userIds = collect($list)->pluck('user_id')->toArray();
                $users = User::whereIn('id', $userIds)->select('id', 'name', 'email')->get();
                $userIdNew = $users->pluck('id')->implode(',');
                $userId = $users->pluck('name')->implode(',');
                if(!empty($userId)){
                    try {
                        $notification = new ExtensionAuditTrail();
                        $notification->extension_id = $id;
                        $notification->activity_type = "Notification";
                        $notification->action = 'Notification';
                        $notification->comment = "";
                        $notification->user_id = Auth::user()->id;
                        $notification->user_name = Auth::user()->name;
                        $notification->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                        $notification->origin_state = "Not Applicable";
                        $notification->previous = $lastDocument->status;
                        $notification->current = "QA More Info Required";
                        $notification->stage = "";
                        $notification->action_name = "";
                        $notification->mailUserId = $userIdNew;
                        $notification->role_name = "Approver";
                        $notification->save();
                        // dd($history);
                    } catch (\Throwable $e) {
                        \Log::error('Mail failed to send: ' . $e->getMessage());
                    }
                }

                        // dd($list);
                        foreach ($list as $u) {
                            $email = Helpers:: getAllUserEmail($u->user_id);
                            if (!empty($email)) {
                                try {
                                    info('Sending mail to', [$email]);
                                    Mail::send(
                                        'mail.view-mail',
                                        ['data' => $changeControl,'site'=>'Extension','history' => 'More-information Required', 'process' => 'Extension', 'comment' => $request->comment,'user'=> Auth::user()->name],
                                        function ($message) use ($email, $changeControl) {
                                         $message->to($email)
                                         ->subject("QMS Notification: Extension , Record #" . str_pad($changeControl->record, 4, '0', STR_PAD_LEFT) . " - Activity: More-information Required Performed"); }
                                        );
        
                                } catch (\Exception $e) {
                                    \Log::error('Mail failed to send: ' . $e->getMessage());
                                }
                            }
                            // }
                        }

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
            $changeControl = Extension::find($id);
            $lastDocument = Extension::find($id);
            $openState = Extension::find($id);
            if ($changeControl->stage == 2) {

                $changeControl->stage = "4";
                $changeControl->status = "closed-reject";
                $changeControl->rejected_by = Auth::user()->name;
                $changeControl->rejected_on = Carbon::now()->format('d-M-Y');
                        $history = new ExtensionAuditTrail();
                        $history->extension_id = $id;
                        $history->activity_type = 'Activity Log';
                        $history->previous = $lastDocument->status;
                        $history->current = "closed-reject";
                        $history->comment = $request->comment;
                        $history->user_id = Auth::user()->id;
                        $history->user_name = Auth::user()->name;
                        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                        $history->origin_state = $lastDocument->status;
                        $history->stage = 'Rejected';
                        $history->save();
                $changeControl->update();
                
                $history = new CCStageHistory();
                $history->type = "Extension";
                $history->doc_id = $id;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->stage_id = $changeControl->stage;
                $history->status = $changeControl->status;
                $history->save();
                // $list = Helpers::getInitiatorUserList();
                // foreach ($list as $u) {
                //     if($u->q_m_s_divisions_id == $openState->division_id){
                //      $email = Helpers::getInitiatorEmail($u->user_id);
                //      if ($email !== null) {
                //          Mail::send(
                //             'mail.view-mail',
                //             ['data' => $openState],
                //             function ($message) use ($email) {
                //                 $message->to($email)
                //                     ->subject("Document is Reject By ".Auth::user()->name);
                //             }
                //         );
                //       }
                //     } 
                // }

                $list = Helpers::getInitiatorUserList($changeControl->division_id);

                $userIds = collect($list)->pluck('user_id')->toArray();
                $users = User::whereIn('id', $userIds)->select('id', 'name', 'email')->get();
                $userIdNew = $users->pluck('id')->implode(',');
                $userId = $users->pluck('name')->implode(',');
                if(!empty($userId)){
                    try {
                        $notification = new ExtensionAuditTrail();
                        $notification->extension_id = $id;
                        $notification->activity_type = "Notification";
                        $notification->action = 'Notification';
                        $notification->comment = "";
                        $notification->user_id = Auth::user()->id;
                        $notification->user_name = Auth::user()->name;
                        $notification->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                        $notification->origin_state = "Not Applicable";
                        $notification->previous = $lastDocument->status;
                        $notification->current = "Reject";
                        $notification->stage = "";
                        $notification->action_name = "";
                        $notification->mailUserId = $userIdNew;
                        $notification->role_name = "Approver";
                        $notification->save();
                        // dd($history);
                    } catch (\Throwable $e) {
                        \Log::error('Mail failed to send: ' . $e->getMessage());
                    }
                }

                        // dd($list);
                        foreach ($list as $u) {
                            $email = Helpers:: getAllUserEmail($u->user_id);
                            if (!empty($email)) {
                                try {
                                    info('Sending mail to', [$email]);
                                    Mail::send(
                                        'mail.view-mail',
                                        ['data' => $changeControl,'site'=>'Extension','history' => 'Rejected', 'process' => 'Extension', 'comment' => $request->comment,'user'=> Auth::user()->name],
                                        function ($message) use ($email, $changeControl) {
                                         $message->to($email)
                                         ->subject("QMS Notification: Extension , Record #" . str_pad($changeControl->record, 4, '0', STR_PAD_LEFT) . " - Activity: Rejected Performed"); }
                                        );
        
                                } catch (\Exception $e) {
                                    \Log::error('Mail failed to send: ' . $e->getMessage());
                                }
                            }
                            // }
                        }

                toastr()->success('Document Sent');
                return back();
            } else {
                return back();
            }
            // $changeControl = CC::find($id);
            // $changeControl->stage = "4";
            // $changeControl->status = "Closed-Rejected";
            // $changeControl->update();
            // $history = new CCStageHistory();
            // $history->type = "Extension";
            // $history->doc_id = $id;
            // $history->user_id = Auth::user()->id;
            // $history->user_name = Auth::user()->name;
            // $history->stage_id = $changeControl->stage;
            // $history->status = $changeControl->status;
            // $history->save();

        } else {
            toastr()->error('E-signature Not match');
            return back();
        }
    }

    public function extensionAuditTrial($id)
    {
        $audit = ExtensionAuditTrail::where('extension_id', $id)->orderByDESC('id')->get()->unique('activity_type');
        $today = Carbon::now()->format('d-m-y');
        $document = Extension::where('id', $id)->first();
        $document->initiator = User::where('id', $document->initiator_id)->value('name');

        return view('frontend.extension.audit-trial', compact('audit', 'document', 'today'));
    }

    public function extensionAuditTrialDetails($id)
    {
        $detail = ExtensionAuditTrail::find($id);
        $detail_data = ExtensionAuditTrail::where('activity_type', $detail->activity_type)->where('extension_id', $detail->extension_id)->latest()->get();
        $doc = Extension::where('id', $detail->extension_id)->first();
        $doc->origiator_name = User::find($doc->initiator_id);
        return view('frontend.extension.audit-trial-inner', compact('detail', 'doc', 'detail_data'));
    }
    
    public static function singleReport($id)
    {
        $data = Extension::find($id);
        if (!empty($data)) {
            $data->originator = User::where('id', $data->initiator_id)->value('name');
            $pdf = App::make('dompdf.wrapper');
            $time = Carbon::now();
            $pdf = PDF::loadview('frontend.extension.singleReport', compact('data'))
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
            return $pdf->stream('Extension' . $id . '.pdf');
        }
    }

    public static function auditReport($id)
    {
        $doc = Extension::find($id);
        if (!empty($doc)) {
            $doc->originator = User::where('id', $doc->initiator_id)->value('name');
            $data = ExtensionAuditTrail::where('extension_id', $id)->get();
            $pdf = App::make('dompdf.wrapper');
            $time = Carbon::now();
            $pdf = PDF::loadview('frontend.extension.auditReport', compact('data', 'doc'))
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
            return $pdf->stream('Extension-Audit' . $id . '.pdf');
        }
    }
}
