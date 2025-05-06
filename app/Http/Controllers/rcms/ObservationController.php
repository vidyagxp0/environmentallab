<?php

namespace App\Http\Controllers\rcms;

use App\Http\Controllers\Controller;
use App\Jobs\SendMail;
use App\Models\AuditTrialObservation;
use App\Models\Observation;
use App\Models\RecordNumber;
use App\Models\User;
use App\Models\OpenStage;
use App\Models\Capa;
use Carbon\Carbon;
use Helpers;
use App\Models\RoleGroup;
use App\Models\ObservationGrid;
use App\Models\InternalAuditGrid;
use App\Models\QMSDivision;
use App\Models\RootCauseAnalysis;
use App\Services\DocumentService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use PDF;
use Illuminate\Support\Facades\Mail;


class ObservationController extends Controller
{

    public function observation()
    {
        $record_number = ((RecordNumber::first()->value('counter')) + 1);
        $record_number = str_pad($record_number, 4, '0', STR_PAD_LEFT);
        $currentDate = Carbon::now();
        $formattedDate = $currentDate->addDays(30);
        $due_date = $formattedDate->format('Y-m-d');

        $division = QMSDivision::where('name', Helpers::getDivisionName(session()->get('division')))->first();

        // if ($division) {
        //     $last_record = Observation::where('division_id', $division->id)->latest()->first();

        //     if ($last_record) {
        //         $record_number = $last_record->record_number ? str_pad($last_record->record_number->record_number + 1, 4, '0', STR_PAD_LEFT) : '0001';
        //     } else {
        //         $record_number = '0001';
        //     }
        // }

        return view('frontend.forms.observation', compact('due_date', 'record_number'));
    }


    public function observationstore(Request $request)
    {



        if (!$request->short_description) {
            toastr()->error("Short description is required");
            //return redirect()->back();
        }
        $data = new Observation();

        $data->record = ((RecordNumber::first()->value('counter')) + 1);
        $data->initiator_id = Auth::user()->id;
        $data->parent_id = $request->parent_id;
        $data->parent_type = $request->parent_type;
        $data->division_code = $request->division_code;
        $data->division_id = $request->division_id;

        $data->intiation_date = $request->intiation_date;
        $data->due_date = $request->due_date;
        $data->short_description = $request->short_description;
        $data->assign_to = $request->assign_to;
        $data->grading = $request->grading;
        $data->category_observation = $request->category_observation;
        $data->reference_guideline = $request->reference_guideline;
        $data->description = $request->description;
        $data->date_response_due2 = $request->date_Response_due2;

        // if ($request->hasfile('attach_files1')) {
        //     $image = $request->file('attach_files1');
        //     $ext = $image->getClientOriginalExtension();
        //     $image_name = date('y-m-d') . '-' . rand() . '.' . $ext;
        //     $image->move('upload/document/', $image_name);
        //     $data->attach_files1 = $image_name;
        // }

        if (!empty($request->attach_files1)) {
            $files = [];
            if ($request->hasfile('attach_files1')) {
                foreach ($request->file('attach_files1') as $file) {
                    $name = $request->name . 'attach_files1' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }

            $data->attach_files1 = json_encode($files);
        }
        $data->recomendation_capa_date_due = $request->recomendation_capa_date_due;
        $data->non_compliance = $request->non_compliance;
        $data->recommend_action = $request->recommend_action;
        // $data->date_Response_due2 = $request->date_Response_due2;
        $data->capa_date_due = $request->capa_date_due;
        $data->assign_to2 = $request->assign_to2;
        $data->cro_vendor = $request->cro_vendor;
        $data->comments = $request->comments;
        $data->impact = $request->impact;
        $data->impact_analysis = $request->impact_analysis;
        $data->severity_rate = $request->severity_rate;
        $data->occurrence = $request->occurrence;
        $data->detection = $request->detection;
        $data->analysisRPN = $request->analysisRPN;
        $data->actual_start_date = $request->actual_start_date;
        $data->actual_end_date = $request->actual_end_date;
        $data->action_taken = $request->action_taken;
        // $data->date_response_due1= $request->date_response_due1;

        $data->response_date = $request->response_date;
        // $data->attach_files2 = $request->attach_files2;
        $data->related_url = $request->related_url;
        $data->response_summary = $request->response_summary;

        // if ($request->hasfile('related_observations')) {
        //     $image = $request->file('related_observations');
        //     $ext = $image->getClientOriginalExtension();
        //     $image_name = date('y-m-d') . '-' . rand() . '.' . $ext;
        //     $image->move('upload/document/', $image_name);
        //     $data->related_observations = $image_name;
        // }
        if (!empty($request->related_observations)) {
            $files = [];
            if ($request->hasfile('related_observations')) {
                foreach ($request->file('related_observations') as $file) {
                    $name = $request->name . 'related_observations' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }

            $data->related_observations = json_encode($files);
        }


        if (!empty($request->attach_files2)) {
            $files = [];
            if ($request->hasfile('attach_files2')) {
                foreach ($request->file('attach_files2') as $file) {
                    $name = $request->name . 'attach_files2' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }

            $data->attach_files2 = json_encode($files);
        }
        $data->status = 'Opened';
        $data->stage = 1;
        $data->save();

        $data1 = new ObservationGrid();
        $data1->observation_id = $data->id;
        if (!empty($request->action)) {
            $data1->action = serialize($request->action);
        }
        if (!empty($request->responsible)) {
            $data1->responsible = serialize($request->responsible);
        }
        if (!empty($request->item_status)) {
            $data1->item_status = serialize($request->item_status);
        }
        if (!empty($request->deadline)) {
            $data1->deadline = serialize($request->deadline);
        }
        $data1->save();

        $record = RecordNumber::first();
        $record->counter = ((RecordNumber::first()->value('counter')) + 1);
        $record->update();

        $history = new AuditTrialObservation();
        $history->Observation_id = $data->id;
        $history->activity_type = 'Record Number';
        $history->previous = "Null";
        $history->current = Helpers::getDivisionName($data->division_id) . '/OBS/' . Helpers::year($data->created_at) . '/' . str_pad($data->record, 4, '0', STR_PAD_LEFT);
        $history->comment = "NA";
        $history->comment = "NA";
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $data->status;
        $history->save();

        if (!empty($request->parent_id)) {

        $history = new AuditTrialObservation();
        $history->Observation_id = $data->id;
        $history->activity_type = 'Parent Id';
        $history->previous = "Null";
        $history->current = $data->parent_id;
        $history->comment = "NA";
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $data->status;
        $history->save();
        }

        if (!empty($request->parent_type)) {

        $history = new AuditTrialObservation();
        $history->Observation_id = $data->id;
        $history->activity_type = 'Parent Type';
        $history->previous = "Null";
        $history->current = $data->parent_type;
        $history->comment = "NA";
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $data->status;
        $history->save();
        }

        if (!empty($request->division_id)) {

        $history = new AuditTrialObservation();
        $history->Observation_id = $data->id;
        $history->activity_type = 'Site/Location Code';
        $history->previous = "Null";
        $history->current = Helpers::getDivisionName($request->division_id);
        $history->comment = "NA";
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $data->status;
        $history->save();

        }

        $history = new AuditTrialObservation();
        $history->Observation_id = $data->id;
        $history->activity_type = 'Initiator';
        $history->previous ="Null";
        $history->current = Helpers::getInitiatorName($data->initiator_id);
        $history->comment = "NA";
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $data->status;
        $history->save();


        // if (!empty($request->intiation_date)) {

        $history = new AuditTrialObservation();
        $history->Observation_id = $data->id;
        $history->activity_type = 'Date of Initiation';
        $history->previous ="Null";
        $history->current = Helpers::getdateFormat($data->intiation_date);
        $history->comment = "NA";
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $data->status;
        $history->save();
        // }

        if (!empty($request->due_date)) {
        $history = new AuditTrialObservation();
        $history->Observation_id = $data->id;
        $history->activity_type = 'Due Date';
        $history->previous ="Null";
        $history->current = Helpers::getdateFormat($data->due_date);
        $history->comment = "NA";
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $data->status;
        $history->save();
        }

        $history = new AuditTrialObservation();
        $history->Observation_id = $data->id;
        $history->activity_type = 'Short Description';
        $history->previous = "Null";
        $history->current = $data->short_description;
        $history->comment = "NA";
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $data->status;
        $history->save();

        if (!empty($request->assign_to)) {
        $history = new AuditTrialObservation();
        $history->Observation_id = $data->id;
        $history->activity_type = 'Assign To1';
        $history->previous = "Null";
        $history->current = Helpers::getInitiatorName($data->assign_to);
        $history->comment = "NA";
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $data->status;
        $history->save();
        }
        if (!empty($request->grading)) {

        $history = new AuditTrialObservation();
        $history->Observation_id = $data->id;
        $history->activity_type = 'Grading';
        $history->previous = "Null";
        $history->current = $data->grading;
        $history->comment = "NA";
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $data->status;
        $history->save();
        }

        if (!empty($request->category_observation)) {
        $history = new AuditTrialObservation();
        $history->Observation_id = $data->id;
        $history->activity_type = 'Category Observation';
        $history->previous = "Null";
        $history->current = $data->category_observation;
        $history->comment = "NA";
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $data->status;
        $history->save();
        }

        if (!empty($request->reference_guideline)) {
        $history = new AuditTrialObservation();
        $history->Observation_id = $data->id;
        $history->activity_type = 'Reference Guideline';
        $history->previous = "Null";
        $history->current = $data->reference_guideline;
        $history->comment = "NA";
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $data->status;
        $history->save();
        }

        if (!empty($request->description)) {

        $history = new AuditTrialObservation();
        $history->Observation_id = $data->id;
        $history->activity_type = 'Parent Type';
        $history->previous = "Null";
        $history->current = $data->description;
        $history->comment = "NA";
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $data->status;
        $history->save();
        }
        if (!empty($request->attach_files1)) {

        $history = new AuditTrialObservation();
        $history->Observation_id = $data->id;
        $history->activity_type = 'Attached Files1';
        $history->previous = "Null";
        $history->current = $data->attach_files1;
        $history->comment = "NA";
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $data->status;
        $history->save();
        }

        if (!empty($request->recomendation_capa_date_due)) {
        $history = new AuditTrialObservation();
        $history->Observation_id = $data->id;
        $history->activity_type = 'Recomendation Due Date for CAPA';
        $history->previous = "Null";
        $history->current = Helpers::getdateFormat($data->recomendation_capa_date_due);
        $history->comment = "NA";
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $data->status;
        $history->save();
        }
        if (!empty($request->non_compliance)) {

        $history = new AuditTrialObservation();
        $history->Observation_id = $data->id;
        $history->activity_type = 'Non Compliance';
        $history->previous = "Null";
        $history->current = $data->non_compliance;
        $history->comment = "NA";
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $data->status;
        $history->save();
        }
        if (!empty($request->recommend_action)) {
        $history = new AuditTrialObservation();
        $history->Observation_id = $data->id;
        $history->activity_type = 'Recommended Action';
        $history->previous = "Null";
        $history->current = $data->recommend_action;
        $history->comment = "NA";
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $data->status;
        $history->save();
        }

        if (!empty($request->date_Response_due2)) {
        $history = new AuditTrialObservation();
        $history->Observation_id = $data->id;
        $history->activity_type = 'Date Response Due';
        $history->previous = "Null";
        $history->current = Helpers::getdateFormat($request->date_Response_due2);
        $history->comment = "NA";
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $data->status;
        $history->save();
        }

        if (!empty($request->capa_date_due)) {
        $history = new AuditTrialObservation();
        $history->Observation_id = $data->id;
        $history->activity_type = 'CAPA Due Date';
        $history->previous = "Null";
        $history->current = Helpers::getdateFormat($data->capa_date_due);
        $history->comment = "NA";
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $data->status;
        $history->save();
        }

        if (!empty($request->assign_to2)) {
        $history = new AuditTrialObservation();
        $history->Observation_id = $data->id;
        $history->activity_type = 'Assign To';
        $history->previous = "Null";
        $history->current = Helpers::getInitiatorName($data->assign_to2);
        $history->comment = "NA";
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $data->status;
        $history->save();
        }
        if (!empty($request->cro_vendor)) {
        $history = new AuditTrialObservation();
        $history->Observation_id = $data->id;
        $history->activity_type = 'Cro Vendor ';
        $history->previous = "Null";
        $history->current = $data->cro_vendor;
        $history->comment = "NA";
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $data->status;
        $history->save();
        }
        if (!empty($request->comments)) {
        $history = new AuditTrialObservation();
        $history->Observation_id = $data->id;
        $history->activity_type = 'Comments ';
        $history->previous = "Null";
        $history->current = $data->comments;
        $history->comment = "NA";
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $data->status;
        $history->save();
        }
        if (!empty($request->impact)) {

        $history = new AuditTrialObservation();
        $history->Observation_id = $data->id;
        $history->activity_type = 'Impact ';
        $history->previous = "Null";
        $history->current = $data->impact;
        $history->comment = "NA";
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $data->status;
        $history->save();
        }
        if (!empty($request->impact_analysis)) {
        $history = new AuditTrialObservation();
        $history->Observation_id = $data->id;
        $history->activity_type = 'Impact Analysis ';
        $history->previous = "Null";
        $history->current = $data->impact_analysis;
        $history->comment = "NA";
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $data->status;
        $history->save();
        }
        // if (!empty($request->severity_rate)) {
        // $history = new AuditTrialObservation();
        // $history->Observation_id = $data->id;
        // $history->activity_type = 'Severity Rate ';
        // $history->previous = "Null";
        // $history->current = $data->severity_rate;
        // $history->comment = "NA";
        // $history->user_id = Auth::user()->id;
        // $history->user_name = Auth::user()->name;
        // $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        // $history->origin_state = $data->status;
        // $history->save();
        // }
        if (!empty($data->severity_rate)) {
            $history = new AuditTrialObservation();
            $history->Observation_id = $data->id;
            $history->activity_type = 'Severity Rate';
            $history->previous = "Null";
            // $history->current = $data->severity_rate;
            if($request->severity_rate == 1){
                $history->current = "Negligible";
            } elseif($request->severity_rate == 2){
                $history->current = "Moderate";
            } elseif($request->severity_rate == 3){
                $history->current = "Major";
            }else{
                $history->current = "Fatal";
            }
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;

            $history->save();
        }
        if (!empty($request->occurrence)) {
        $history = new AuditTrialObservation();
        $history->Observation_id = $data->id;
        $history->activity_type = 'Occurrence ';
        $history->previous = "Null";

        if($request->occurrence == 1){
            $history->current = "Very Likely";
        } elseif($request->occurrence == 2){
            $history->current = "Likely";
        } elseif($request->occurrence == 3){
            $history->current = "Unlikely";
        } elseif($request->occurrence == 4){
            $history->current = "Rare";
        } elseif($request->occurrence == 5){
            $history->current = "Extremely Unlikely";
        }
        //$history->current = $data->occurrence;
        $history->comment = "NA";
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $data->status;
        $history->save();
        }
        if (!empty($request->detection)) {
        $history = new AuditTrialObservation();
        $history->Observation_id = $data->id;
        $history->activity_type = 'Detection ';
        $history->previous = "Null";

        if($request->detection == 1){
            $history->current = "Very Likely";
        } elseif($request->detection == 2){
            $history->current = "Likely";
        }elseif($request->detection == 3){
            $history->current = "Unlikely";
        } elseif($request->detection == 4){
            $history->current = "Rare";
        } elseif($request->detection == 5){
            $history->current = "Impossible";
        }

        //$history->current = $data->detection;
        $history->comment = "NA";
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $data->status;
        $history->save();
        }
        if (!empty($request->analysisRPN)) {
        $history = new AuditTrialObservation();
        $history->Observation_id = $data->id;
        $history->activity_type = 'RPN ';
        $history->previous = "Null";
        $history->current = $data->analysisRPN;
        $history->comment = "NA";
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $data->status;
        $history->save();
        }
        if (!empty($request->actual_start_date)) {
        $history = new AuditTrialObservation();
        $history->Observation_id = $data->id;
        $history->activity_type = 'Actual Start Date ';
        $history->previous = "Null";
        $history->current = Helpers::getdateFormat($data->actual_start_date);
        $history->comment = "NA";
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $data->status;
        $history->save();
        }
        if (!empty($request->actual_end_date)) {
        $history = new AuditTrialObservation();
        $history->Observation_id = $data->id;
        $history->activity_type = 'Actual End Date ';
        $history->previous = "Null";
        $history->current = Helpers::getdateFormat($data->actual_end_date);
        $history->comment = "NA";
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $data->status;
        $history->save();
        }

        if (!empty($request->action_taken)) {
        $history = new AuditTrialObservation();
        $history->Observation_id = $data->id;
        $history->activity_type = 'Action Taken ';
        $history->previous = "Null";
        $history->current = $data->action_taken;
        $history->comment = "NA";
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $data->status;
        $history->save();
        }

        //if(!empty($request->date_response_due1)){
        //    $history = new AuditTrialObservation();
        //    $history->Observation_id = $data->id;
        //    $history->activity_type = 'Response Summary';
        //    $history->previous = "Null";
        //    $history->current = $data->date_response_due1;
        //    $history->comment = "NA";
        //    $history->user_id = Auth::user()->id;
        //    $history->user_name = Auth::user()->name;
        //    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        //    $history->origin_state = $data->status;
        //    $history->save();
        //}

        if (!empty($request->response_date)) {

        $history = new AuditTrialObservation();
        $history->Observation_id = $data->id;
        $history->activity_type = 'Response Date ';
        $history->previous = "Null";
        $history->current = $data->response_date;
        $history->comment = "NA";
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $data->status;
        $history->save();
        }
        if (!empty($request->attach_files2)) {
        $history = new AuditTrialObservation();
        $history->Observation_id = $data->id;
        $history->activity_type = 'Attached Files2';
        $history->previous = "Null";
        $history->current = $data->attach_files2;
        $history->comment = "NA";
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $data->status;
        $history->save();
        }
        if (!empty($request->related_url)) {
        $history = new AuditTrialObservation();
        $history->Observation_id = $data->id;
        $history->activity_type = 'Related Url ';
        $history->previous = "Null";
        $history->current = $data->related_url;
        $history->comment = "NA";
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $data->status;
        $history->save();
        }
        if (!empty($request->response_summary)) {
        $history = new AuditTrialObservation();
        $history->Observation_id = $data->id;
        $history->activity_type = 'Response Summary ';
        $history->previous = "Null";
        $history->current = $data->response_summary;
        $history->comment = "NA";
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $data->status;
        $history->save();
        }

        if (!empty($request->related_observations)) {
        $history = new AuditTrialObservation();
        $history->Observation_id = $data->id;
        $history->activity_type = 'Related Obsevations';
        $history->previous = "Null";
        $history->current = $data->attach_files2;
        $history->comment = "NA";
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $data->status;
        $history->save();
        }

        DocumentService::update_qms_numbers();


        toastr()->success("Record is created Successfully");
        return redirect(url('rcms/qms-dashboard'));

    }


    public function observationupdate(Request $request, $id)
    {


        $data = Observation::find($id);
        $lastDocument = Observation::find($id);
        $data = Observation::find($id);
        $data->initiator_id = Auth::user()->id;
        $data->parent_id = $request->parent_id;
        $data->parent_type = $request->parent_type;
        // $data->division_code = $request->division_code;
        // $data->intiation_date = $request->intiation_date;
        $data->due_date = $request->due_date;
        $data->short_description = $request->short_description;
        $data->assign_to = $request->assign_to;
        $data->grading = $request->grading;
        $data->category_observation = $request->category_observation;
        $data->reference_guideline = $request->reference_guideline;
        $data->description = $request->description;
        // if ($request->hasfile('attach_files1')) {
        //     $image = $request->file('attach_files1');
        //     $ext = $image->getClientOriginalExtension();
        //     $image_name = date('y-m-d') . '-' . rand() . '.' . $ext;
        //     $image->move('upload/document/', $image_name);
        //     $data->attach_files1 = $image_name;
        // }

        if (!empty($request->attach_files1)) {
            $files = [];
            if ($request->hasfile('attach_files1')) {
                foreach ($request->file('attach_files1') as $file) {
                    $name = $request->name . 'attach_files1' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }

            $data->attach_files1 = json_encode($files);
        }
        $data->recomendation_capa_date_due = $request->recomendation_capa_date_due;
        $data->non_compliance = $request->non_compliance;
        $data->recommend_action = $request->recommend_action;
        $data->date_Response_due2 = $request->date_Response_due2;
        $data->capa_date_due = $request->capa_date_due11;
        $data->assign_to2 = $request->assign_to2;
        $data->cro_vendor = $request->cro_vendor;
        $data->comments = $request->comments;
        $data->impact = $request->impact;
        $data->impact_analysis = $request->impact_analysis;
        $data->severity_rate = $request->severity_rate;
        $data->occurrence = $request->occurrence;
        $data->detection = $request->detection;
        $data->analysisRPN = $request->analysisRPN;
        $data->actual_start_date = $request->actual_start_date;
        $data->actual_end_date = $request->actual_end_date;
        $data->action_taken = $request->action_taken;

        // $data->date_Response_due22 = $request->date_Response_due22;
        // $data->date_response_due1 = $request->date_response_due1;
        $data->response_date = $request->response_date;
        // $data->attach_files2 = $request->attach_files2;
        $data->related_url = $request->related_url;
        $data->response_summary = $request->response_summary;

        // if ($request->hasfile('related_observations')) {
        //     $image = $request->file('related_observations');
        //     $ext = $image->getClientOriginalExtension();
        //     $image_name = date('y-m-d') . '-' . rand() . '.' . $ext;
        //     $image->move('upload/document/', $image_name);
        //     $data->related_observations = $image_name;
        // }


        $files = is_array($request->existing_related_observations_files) ? $request->existing_related_observations_files : null;

        if (!empty($request->related_observations)) {
            $files = [];
            if ($request->hasfile('related_observations')) {
                foreach ($request->file('related_observations') as $file) {
                    $name = $request->name . 'related_observations' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }

            $data->related_observations = json_encode($files);
        }
        // if ($request->hasfile('attach_files2')) {
        //     $image = $request->file('attach_files2');
        //     $ext = $image->getClientOriginalExtension();
        //     $image_name = date('y-m-d') . '-' . rand() . '.' . $ext;
        //     $image->move('upload/document/', $image_name);
        //     $data->attach_files2 = $image_name;
        // }

        $files = is_array($request->existing_attach_files2_files) ? $request->existing_attach_files2_files : null;

        if (!empty($request->attach_files2)) {
            $files = [];
            if ($request->hasfile('attach_files2')) {
                foreach ($request->file('attach_files2') as $file) {
                    $name = $request->name . 'attach_files2' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }

            $data->attach_files2 = json_encode($files);
        }

        $data->status = 'Opened';
        $data->stage = 1;
        $data->update();
        $data1 = ObservationGrid::find($id);
        $data1->observation_id = $data->id;
        if (!empty($request->action)) {
            $data1->action = serialize($request->action);
        }
        if (!empty($request->responsible)) {
            $data1->responsible = serialize($request->responsible);
        }
        if (!empty($request->item_status)) {
            $data1->item_status = serialize($request->item_status);
        }
        if (!empty($request->deadline)) {
            $data1->deadline = serialize($request->deadline);
        }
        $data1->update();

        if ($lastDocument->parent_id != $data->parent_id || !empty($request->parent_id_comment)) {

            $history = new AuditTrialObservation();
            $history->Observation_id = $id;
            $history->activity_type = 'Parent Id';
            $history->previous = $lastDocument->parent_id;
            $history->current = $data->parent_id;
            $history->comment = $request->parent_id_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->parent_type != $data->parent_type || !empty($request->parent_type_comment)) {

            $history = new AuditTrialObservation();
            $history->Observation_id = $id;
            $history->activity_type = 'Parent Type';
            $history->previous = $lastDocument->parent_type;
            $history->current = $data->parent_type;
            $history->comment = $request->parent_type_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }

        //if (!empty($lastDocument->short_description)) {
        //    $history = new AuditTrialObservation();
        //    $history->Observation_id = $lastDocument->id;
        //    $history->activity_type = 'Short Description';
        //    $history->previous = "Null";
        //    $history->current = $lastDocument->short_description;
        //    $history->comment = "NA";
        //    $history->user_id = Auth::user()->id;
        //    $history->user_name = Auth::user()->name;
        //    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        //    $history->origin_state = $lastDocument->status;
        //    $history->save();
        //}

        //if ($lastDocument->due_date != $data->due_date || !empty($request->due_date_comment)) {

        //    $history = new AuditTrialObservation();
        //    $history->Observation_id = $id;
        //    $history->activity_type = 'Due Date';
        //    $history->previous = Helpers::getdateFormat($lastDocument->due_date);
        //    $history->current = Helpers::getdateFormat($data->due_date);
        //    $history->comment = $request->due_date_comment;
        //    $history->user_id = Auth::user()->id;
        //    $history->user_name = Auth::user()->name;
        //    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        //    $history->origin_state = $lastDocument->status;
        //    $history->save();
        //}

        if ($lastDocument->short_description != $data->short_description || !empty($request->short_description_comment)) {
            $history = new AuditTrialObservation();
            $history->Observation_id = $id;
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
        if ($lastDocument->assign_to != $data->assign_to || !empty($request->assign_to_comment)) {

            $history = new AuditTrialObservation();
            $history->Observation_id = $id;
            $history->activity_type = 'Assign To1';
            $history->previous = Helpers::getInitiatorName($lastDocument->assign_to);
            $history->current = Helpers::getInitiatorName($data->assign_to);
            $history->comment = $request->assign_to_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }

        if ($lastDocument->date_Response_due2 != $data->date_Response_due2 || !empty($request->date_Response_due2_comment)) {

            $history = new AuditTrialObservation();
            $history->Observation_id = $id;
            $history->activity_type = 'Date Response Due';
            $history->previous = Helpers::getdateFormat($lastDocument->date_Response_due2);
            $history->current = Helpers::getdateFormat($data->date_Response_due2);
            $history->comment = $request->date_Response_due2_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->grading != $data->grading || !empty($request->grading_comment)) {

            $history = new AuditTrialObservation();
            $history->Observation_id = $id;
            $history->activity_type = 'Grading';
            $history->previous = $lastDocument->grading;
            $history->current = $data->grading;
            $history->comment = $request->grading_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->category_observation != $data->category_observation || !empty($request->category_observation_comment)) {

            $history = new AuditTrialObservation();
            $history->Observation_id = $id;
            $history->activity_type = 'Category Observation';
            $history->previous = $lastDocument->category_observation;
            $history->current = $data->category_observation;
            $history->comment = $request->category_observation_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->reference_guideline != $data->reference_guideline || !empty($request->reference_guideline_comment)) {

            $history = new AuditTrialObservation();
            $history->Observation_id = $id;
            $history->activity_type = 'Reference Guideline';
            $history->previous = $lastDocument->reference_guideline;
            $history->current = $data->reference_guideline;
            $history->comment = $request->reference_guideline_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->description != $data->description || !empty($request->description_comment)) {

            $history = new AuditTrialObservation();
            $history->Observation_id = $id;
            $history->activity_type = 'Parent Type';
            $history->previous = $lastDocument->description;
            $history->current = $data->description;
            $history->comment = $request->description_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->attach_files1 != $data->attach_files1 || !empty($request->attach_files1_comment)) {

            $history = new AuditTrialObservation();
            $history->Observation_id = $id;
            $history->activity_type = 'Attached Files1';
            $history->previous = $lastDocument->attach_files1;
            $history->current = $data->attach_files1;
            $history->comment = $request->attach_files1_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->recomendation_capa_date_due != $data->recomendation_capa_date_due || !empty($request->recomendation_capa_date_due_comment)) {

            $history = new AuditTrialObservation();
            $history->Observation_id = $id;
            $history->activity_type = 'Recomendation Due Date for CAPA';
            $history->previous = $lastDocument->recomendation_capa_date_due;
            $history->current = $data->recomendation_capa_date_due;
            $history->comment = $request->recomendation_capa_date_due_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->non_compliance != $data->non_compliance || !empty($request->non_compliance_comment)) {

            $history = new AuditTrialObservation();
            $history->Observation_id = $id;
            $history->activity_type = 'Non Compliance';
            $history->previous = $lastDocument->non_compliance;
            $history->current = $data->non_compliance;
            $history->comment = $request->non_compliance_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->recommend_action != $data->recommend_action || !empty($request->recommend_action_comment)) {

            $history = new AuditTrialObservation();
            $history->Observation_id = $id;
            $history->activity_type = 'Recommended Action';
            $history->previous = $lastDocument->recommend_action;
            $history->current = $data->recommend_action;
            $history->comment = $request->recommend_action_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        //if ($lastDocument->date_Response_due2 != $data->date_Response_due2 || !empty($request->date_Response_due2_comment)) {

        //    $history = new AuditTrialObservation();
        //    $history->Observation_id = $id;
        //    $history->activity_type = 'Date Response Due2';
        //    $history->previous = $lastDocument->date_Response_due2;
        //    $history->current = $data->date_Response_due2;
        //    $history->comment = $request->date_Response_due2_comment;
        //    $history->user_id = Auth::user()->id;
        //    $history->user_name = Auth::user()->name;
        //    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        //    $history->origin_state = $lastDocument->status;
        //    $history->save();
        //}
        if ($lastDocument->capa_date_due != $data->capa_date_due || !empty($request->capa_date_due_comment)) {

            $history = new AuditTrialObservation();
            $history->Observation_id = $id;
            $history->activity_type = 'CAPA Due Date';
            $history->previous = $lastDocument->capa_date_due;
            $history->current = $data->capa_date_due;
            $history->comment = $request->capa_date_due_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->assign_to2 != $data->assign_to2 || !empty($request->assign_to2_comment)) {

            $history = new AuditTrialObservation();
            $history->Observation_id = $id;
            $history->activity_type = 'Assign To2';
            $history->previous = Helpers::getInitiatorName($lastDocument->assign_to2);
            $history->current = Helpers::getInitiatorName($data->assign_to2);
            $history->comment = $request->assign_to2_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->cro_vendor != $data->cro_vendor || !empty($request->cro_vendor_comment)) {

            $history = new AuditTrialObservation();
            $history->Observation_id = $id;
            $history->activity_type = 'Cro Vendor ';
            $history->previous = $lastDocument->cro_vendor;
            $history->current = $data->cro_vendor;
            $history->comment = $request->cro_vendor_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->comments != $data->comments || !empty($request->comments_comment)) {

            $history = new AuditTrialObservation();
            $history->Observation_id = $id;
            $history->activity_type = 'Comments ';
            $history->previous = $lastDocument->comments;
            $history->current = $data->comments;
            $history->comment = $request->comments_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->impact != $data->impact || !empty($request->impact_comment)) {

            $history = new AuditTrialObservation();
            $history->Observation_id = $id;
            $history->activity_type = 'Impact ';
            $history->previous = $lastDocument->impact;
            $history->current = $data->impact;
            $history->comment = $request->impact_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->impact_analysis != $data->impact_analysis || !empty($request->impact_analysis_comment)) {

            $history = new AuditTrialObservation();
            $history->Observation_id = $id;
            $history->activity_type = 'Impact Analysis ';
            $history->previous = $lastDocument->impact_analysis;
            $history->current = $data->impact_analysis;
            $history->comment = $request->impact_analysis_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->severity_rate != $data->severity_rate || !empty($request->severity_rate_comment)) {

            $history = new AuditTrialObservation();
            $history->Observation_id = $id;
            $history->activity_type = 'Severity Rate';

            if($lastDocument->severity_rate == 1){
                $history->previous = "Negligible";
            } elseif($lastDocument->severity_rate == 2){
                $history->previous = "Moderate";
            } elseif($lastDocument->severity_rate == 3){
                $history->previous = "Major";
            } elseif($lastDocument->severity_rate == 4){
                $history->previous = "Fatal";
            } else{
                $history->previous = "Null";
            }

            if($request->severity_rate == 1){
                $history->current = "Negligible";
            } elseif($request->severity_rate == 2){
                $history->current = "Moderate";
            } elseif($request->severity_rate == 3){
                $history->current = "Major";
            }else{
                $history->current = "Fatal";
            }
            $history->comment = $request->severity_rate_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->occurrence != $data->occurrence || !empty($request->occurrence_comment)) {

            $history = new AuditTrialObservation();
            $history->Observation_id = $id;

            $history->activity_type = 'Occurrence ';
            if($lastDocument->occurrence == 1){
                $history->previous = "Very Likely";
            } elseif($lastDocument->occurrence == 2){
                $history->previous = "Likely";
            } elseif($lastDocument->occurrence == 3){
                $history->previous = "Unlikely";
            } elseif($lastDocument->occurrence == 4){
                $history->previous = "Rare";
            } elseif($lastDocument->occurrence == 5){
                $history->previous = "Extremely Unlikely";
            }

            if($request->occurrence == 1){
                $history->current = "Very Likely";
            } elseif($request->occurrence == 2){
                $history->current = "Likely";
            } elseif($request->occurrence == 3){
                $history->current = "Unlikely";
            } elseif($request->occurrence == 4){
                $history->current = "Rare";
            } elseif($request->occurrence == 5){
                $history->current = "Extremely Unlikely";
            }
            //$history->previous = $lastDocument->occurrence;
            //$history->current = $data->occurrence;
            $history->comment = $request->occurrence_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->detection != $data->detection || !empty($request->detection_comment)) {

            $history = new AuditTrialObservation();
            $history->Observation_id = $id;
            $history->activity_type = 'Detection ';
            if($lastDocument->detection == 1){
                $history->previous = "Very Likely";
            } elseif($lastDocument->detection == 2){
                $history->previous = "Likely";
            }elseif($lastDocument->detection == 3){
                $history->previous = "Unlikely";
            } elseif($lastDocument->detection == 4){
                $history->previous = "Rare";
            } elseif($lastDocument->detection == 5){
                $history->previous = "Impossible";
            }

            if($request->detection == 1){
                $history->current = "Very Likely";
            } elseif($request->detection == 2){
                $history->current = "Likely";
            }elseif($request->detection == 3){
                $history->current = "Unlikely";
            } elseif($request->detection == 4){
                $history->current = "Rare";
            } elseif($request->detection == 5){
                $history->current = "Impossible";
            }
            //$history->previous = $lastDocument->detection;
            //$history->current = $data->detection;
            $history->comment = $request->detection_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->analysisRPN != $data->analysisRPN || !empty($request->analysisRPN_comment)) {

            $history = new AuditTrialObservation();
            $history->Observation_id = $id;
            $history->activity_type = 'RPN ';
            $history->previous = $lastDocument->analysisRPN;
            $history->current = $data->analysisRPN;
            $history->comment = $request->analysisRPN_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->actual_start_date != $data->actual_start_date || !empty($request->actual_start_date_comment)) {

            $history = new AuditTrialObservation();
            $history->Observation_id = $id;
            $history->activity_type = 'Actual Start Date ';
            $history->previous = Helpers::getdateFormat($lastDocument->actual_start_date);
            $history->current = Helpers::getdateFormat($data->actual_start_date);
            $history->comment = $request->actual_start_date_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->actual_end_date != $data->actual_end_date || !empty($request->actual_end_date_comment)) {

            $history = new AuditTrialObservation();
            $history->Observation_id = $id;
            $history->activity_type = 'Actual End Date ';
            $history->previous = Helpers::getdateFormat($lastDocument->actual_end_date);
            $history->current =Helpers::getdateFormat($data->actual_end_date);
            $history->comment = $request->actual_end_date_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->action_taken != $data->action_taken || !empty($request->action_taken_comment)) {

            $history = new AuditTrialObservation();
            $history->Observation_id = $id;
            $history->activity_type = 'Action Taken ';
            $history->previous = $lastDocument->action_taken;
            $history->current = $data->action_taken;
            $history->comment = $request->action_taken_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        //if ($lastDocument->date_response_due1 != $data->date_response_due1 || !empty($request->date_response_due1_comment)) {

        //    $history = new AuditTrialObservation();
        //    $history->Observation_id = $id;
        //    $history->activity_type = 'Response Summary';
        //    $history->previous = $lastDocument->date_response_due1;
        //    $history->current = $data->date_response_due1;
        //    $history->comment = $request->date_response_due1_comment;
        //    $history->user_id = Auth::user()->id;
        //    $history->user_name = Auth::user()->name;
        //    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        //    $history->origin_state = $lastDocument->status;
        //    $history->save();
        //}
        if ($lastDocument->response_date != $data->response_date || !empty($request->response_date_comment)) {

            $history = new AuditTrialObservation();
            $history->Observation_id = $id;
            $history->activity_type = 'Response Date ';
            $history->previous = $lastDocument->response_date;
            $history->current = $data->response_date;
            $history->comment = $request->response_date_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->attach_files2 != $data->attach_files2 || !empty($request->attach_files2_comment)) {

            $history = new AuditTrialObservation();
            $history->Observation_id = $id;
            $history->activity_type = 'Attached Files2 ';
            $history->previous = $lastDocument->attach_files2;
            $history->current = $data->attach_files2;
            $history->comment = $request->attach_files2_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->related_url != $data->related_url || !empty($request->related_url_comment)) {

            $history = new AuditTrialObservation();
            $history->Observation_id = $id;
            $history->activity_type = 'Related Url ';
            $history->previous = $lastDocument->related_url;
            $history->current = $data->related_url;
            $history->comment = $request->related_url_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->response_summary != $data->response_summary || !empty($request->response_summary_comment)) {

            $history = new AuditTrialObservation();
            $history->Observation_id = $id;
            $history->activity_type = 'Response Summary ';
            $history->previous = $lastDocument->response_summary;
            $history->current = $data->response_summary;
            $history->comment = $request->response_summary_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        //if ($lastDocument->response_summary != $data->response_summary || !empty($request->response_summary_comment)) {

        //    $history = new AuditTrialObservation();
        //    $history->Observation_id = $id;
        //    $history->activity_type = 'Response Summary ';
        //    $history->previous = $lastDocument->response_summary;
        //    $history->current = $data->response_summary;
        //    $history->comment = $request->response_summary_comment;
        //    $history->user_id = Auth::user()->id;
        //    $history->user_name = Auth::user()->name;
        //    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        //    $history->origin_state = $lastDocument->status;
        //    $history->save();
        //}
        if ($lastDocument->related_observations != $data->related_observations || !empty($request->attach_files2_comment)) {

            $history = new AuditTrialObservation();
            $history->Observation_id = $id;
            $history->activity_type = 'Related Obsevations';
            $history->previous = $lastDocument->related_observations;
            $history->current = $data->related_observations;
            $history->comment = $request->attach_files2_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }

        DocumentService::update_qms_numbers();

        toastr()->success("Record is update successfully");
        return back();
    }

    public function observationshow($id)
    {
        $data = Observation::find($id);
        $data->record = str_pad($data->record, 4, '0', STR_PAD_LEFT);
        $data->assign_to_name = User::where('id', $data->assign_id)->value('name');
        $data->initiator_name = User::where('id', $data->initiator_id)->value('name');
        $grid_data = InternalAuditGrid::where('audit_id', $id)->where('type', "external_audit")->first();
        $griddata = ObservationGrid::where('observation_id',$data->id)->first();

        return view('frontend.observation.view', compact('data','griddata','grid_data'));
    }


    public function observation_send_stage(Request $request, $id)
    {

        if ($request->username == Auth::user()->email && Hash::check($request->password, Auth::user()->password)) {
            $changestage = Observation::find($id);
            $lastDocument = Observation::find($id);
            if ($changestage->stage == 1) {
                $changestage->stage = "2";
                $changestage->status = "Pending CAPA Plan";
                $changestage->report_issued_by = Auth::user()->name;
                $changestage->report_issued_on = Carbon::now()->format('d-M-Y');
                                $history = new AuditTrialObservation();
                                $history->Observation_id = $id;
                                $history->activity_type = 'Activity Log';
                                $history->previous = $lastDocument->status;
                                $history->current = "Pending CAPA Plan";
                                $history->comment = $request->comment;
                                $history->user_id = Auth::user()->id;
                                $history->user_name = Auth::user()->name;
                                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                                $history->origin_state = $lastDocument->status;
                                $history->stage = " Report Issued";
                                $history->save();

                                $list = Helpers::getLeadAuditeeUserList($changestage->division_id);


                                $userIds = collect($list)->pluck('user_id')->toArray();
                                $users = User::whereIn('id', $userIds)->select('id', 'name', 'email')->get();
                                $userId1 = $users->pluck('id')->implode(',');
                                $userId = $users->pluck('name')->implode(',');

                                if($userId){
                                    $test = new AuditTrialObservation();
                                    $test->Observation_id = $id;
                                    $test->activity_type = "Notification";
                                    $test->action = 'Notification';
                                    $test->comment = "";
                                    $test->user_id = Auth::user()->id;
                                    $test->user_name = Auth::user()->name;
                                    $test->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                                    $test->origin_state = "Not Applicable";
                                    $test->previous = $lastDocument->status;
                                    $test->current = "Pending CAPA Plan";
                                    $test->stage = "";
                                    $test->action_name = "";
                                    $test->mailUserId = $userId1;
                                    $test->role_name = "Lead Auditor";
                                    //dd($test->mailUserId);
                                    $test->save();
                                }



                                // dd($list);
                                // foreach ($list as $u) {
                                //     $email = Helpers:: getAllUserEmail($u->user_id);
                                //     if (!empty($email)) {
                                //         try {
                                //             info('Sending mail to', [$email]);
                                //             Mail::send(
                                //                 'mail.view-mail',
                                //                 ['data' => $changestage,'site'=>'Observation','history' => 'Report Issued', 'process' => 'Observation', 'comment' => $history->comment,'user'=> Auth::user()->name],
                                //                 function ($message) use ($email, $changestage) {
                                //                  $message->to($email)
                                //                  ->subject("QMS Notification: Observation, Record #" . str_pad($changestage->record, 4, '0', STR_PAD_LEFT) . " - Activity: Report Issued Performed"); }
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
                                            $data = ['data' => $changestage,'site'=>'Observation','history' => 'Report Issued', 'process' => 'Observation', 'comment' => $history->comment,'user'=> Auth::user()->name];
                                
                                            SendMail::dispatch($data, $email, $changestage, 'Observation');
                                        }
                                    } catch (\Exception $e) {
                                        \Log::error('Mail sending failed for user_id: ' . $u->user_id . ' - Error: ' . $e->getMessage());       
                                        continue;
                                    }
                                }

                $changestage->update();
                toastr()->success('Document Sent');
                return back();
            }
            if ($changestage->stage == 2) {
                $changestage->stage = "3";
                $changestage->status = "Pending Approval";

                $changestage->Completed_By = Auth::user()->name;
                $changestage->completed_on = Carbon::now()->format('d-M-Y');
                                $history = new AuditTrialObservation();
                                $history->Observation_id = $id;
                                $history->activity_type = 'Activity Log';
                                $history->previous = $lastDocument->status;
                                $history->current = "Pending Approval";
                                $history->comment = $request->comment;
                                $history->user_id = Auth::user()->id;
                                $history->user_name = Auth::user()->name;
                                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                                $history->origin_state = $lastDocument->status;
                                $history->stage = "Completed";
                                $history->save();
            //     $list = Helpers::getQAUserList();
            //     foreach ($list as $u) {
            //         if($u->q_m_s_divisions_id == $changestage->division_id){
            //             $email = Helpers::getInitiatorEmail($u->user_id);
            //              if ($email !== null) {

            //               Mail::send(
            //                   'mail.view-mail',
            //                    ['data' => $changestage],
            //                 function ($message) use ($email) {
            //                     $message->to($email)
            //                         ->subject("Document sent ".Auth::user()->name);
            //                 }
            //               );
            //             }
            //      }
            //   }

            $list = Helpers::getQAUserList($changestage->division_id);

            $userIds = collect($list)->pluck('user_id')->toArray();
            $users = User::whereIn('id', $userIds)->select('id', 'name', 'email')->get();
            $userId1 = $users->pluck('id')->implode(',');
            $userId = $users->pluck('name')->implode(',');

            if($userId){
                $test = new AuditTrialObservation();
                $test->Observation_id = $id;
                $test->activity_type = "Notification";
                $test->action = 'Notification';
                $test->comment = "";
                $test->user_id = Auth::user()->id;
                $test->user_name = Auth::user()->name;
                $test->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $test->origin_state = "Not Applicable";
                $test->previous = $lastDocument->status;
                $test->current = "Pending Approval";
                $test->stage = "";
                $test->action_name = "";
                $test->mailUserId = $userId1;
                $test->role_name = "Lead Auditee";
                //dd($test->mailUserId);
                $test->save();
            }


            // dd($list);
            // foreach ($list as $u) {
            //     $email = Helpers:: getAllUserEmail($u->user_id);
            //     if (!empty($email)) {
            //         try {
            //             info('Sending mail to', [$email]);
            //             Mail::send(
            //                 'mail.view-mail',
            //                 ['data' => $changestage,'site'=>'Observation','history' => 'Complete', 'process' => 'Observation', 'comment' => $history->comment,'user'=> Auth::user()->name],
            //                 function ($message) use ($email, $changestage) {
            //                  $message->to($email)
            //                  ->subject("QMS Notification: Observation, Record #" . str_pad($changestage->record, 4, '0', STR_PAD_LEFT) . " - Activity: Complete Performed"); }
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
                        $data = ['data' => $changestage,'site'=>'Observation','history' => 'Complete', 'process' => 'Observation', 'comment' => $history->comment,'user'=> Auth::user()->name];
            
                        SendMail::dispatch($data, $email, $changestage, 'Observation');
                    }
                } catch (\Exception $e) {
                    \Log::error('Mail sending failed for user_id: ' . $u->user_id . ' - Error: ' . $e->getMessage());       
                    continue;
                }
            }


                $changestage->update();
                toastr()->success('Document Sent');
                return back();
            }
            if ($changestage->stage == 3) {
                $changestage->stage = "4";
                $changestage->status = "CAPA Execution in Progress";
                $changestage->QA_Approved_By = Auth::user()->name;
                $changestage->QA_Approved_on = Carbon::now()->format('d-M-Y');
                            $history = new AuditTrialObservation();
                            $history->Observation_id = $id;
                            $history->activity_type = 'Activity Log';
                            $history->previous = $lastDocument->status;
                            $history->current = "CAPA Execution in Progress";
                            $history->comment = $request->comment;
                            $history->user_id = Auth::user()->id;
                            $history->user_name = Auth::user()->name;
                            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                            $history->origin_state = $lastDocument->status;
                            $history->stage = "QA Approved";
                            $history->save();
                        //     $list = Helpers::getLeadAuditeeUserList();
                        //     foreach ($list as $u) {
                        //         if($u->q_m_s_divisions_id == $changestage->division_id){
                        //             $email = Helpers::getInitiatorEmail($u->user_id);
                        //              if ($email !== null) {

                        //               Mail::send(
                        //                   'mail.view-mail',
                        //                    ['data' => $changestage],
                        //                 function ($message) use ($email) {
                        //                     $message->to($email)
                        //                         ->subject("Document sent ".Auth::user()->name);
                        //                 }
                        //               );
                        //             }
                        //      }
                        //   }

                        $list = Helpers::getLeadAuditeeUserList($changestage->division_id);

                        $userIds = collect($list)->pluck('user_id')->toArray();
                        $users = User::whereIn('id', $userIds)->select('id', 'name', 'email')->get();
                        $userId1 = $users->pluck('id')->implode(',');
                        $userId = $users->pluck('name')->implode(',');

                        if($userId){
                            $test = new AuditTrialObservation();
                            $test->Observation_id = $id;
                            $test->activity_type = "Notification";
                            $test->action = 'Notification';
                            $test->comment = "";
                            $test->user_id = Auth::user()->id;
                            $test->user_name = Auth::user()->name;
                            $test->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                            $test->origin_state = "Not Applicable";
                            $test->previous = $lastDocument->status;
                            $test->current = "CAPA Execution in Progress";
                            $test->stage = "";
                            $test->action_name = "";
                            $test->mailUserId = $userId1;
                            $test->role_name = "QA";
                            //dd($test->mailUserId);
                            $test->save();
                        }



                        // dd($list);
                        // foreach ($list as $u) {
                        //     $email = Helpers:: getAllUserEmail($u->user_id);
                        //     if (!empty($email)) {
                        //         try {
                        //             info('Sending mail to', [$email]);
                        //             Mail::send(
                        //                 'mail.view-mail',
                        //                 ['data' => $changestage,'site'=>'Observation','history' => ' QA Approval', 'process' => 'Observation', 'comment' => $history->comment,'user'=> Auth::user()->name],
                        //                 function ($message) use ($email, $changestage) {
                        //                  $message->to($email)
                        //                  ->subject("QMS Notification: Observation, Record #" . str_pad($changestage->record, 4, '0', STR_PAD_LEFT) . " - Activity: QA Approval Performed"); }
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
                                    $data = ['data' => $changestage,'site'=>'Observation','history' => ' QA Approval', 'process' => 'Observation', 'comment' => $history->comment,'user'=> Auth::user()->name];
                        
                                    SendMail::dispatch($data, $email, $changestage, 'Observation');
                                }
                            } catch (\Exception $e) {
                                \Log::error('Mail sending failed for user_id: ' . $u->user_id . ' - Error: ' . $e->getMessage());       
                                continue;
                            }
                        }
 
                        $list = Helpers::getLeadAuditorUserList($changestage->division_id);

                        $userIds = collect($list)->pluck('user_id')->toArray();
                        $users = User::whereIn('id', $userIds)->select('id', 'name', 'email')->get();
                        $userId1 = $users->pluck('id')->implode(',');
                        $userId = $users->pluck('name')->implode(',');

                        if($userId){
                            $test = new AuditTrialObservation();
                            $test->Observation_id = $id;
                            $test->activity_type = "Notification";
                            $test->action = 'Notification';
                            $test->comment = "";
                            $test->user_id = Auth::user()->id;
                            $test->user_name = Auth::user()->name;
                            $test->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                            $test->origin_state = "Not Applicable";
                            $test->previous = $lastDocument->status;
                            $test->current = "CAPA Execution in Progress";
                            $test->stage = "";
                            $test->action_name = "";
                            $test->mailUserId = $userId1;
                            $test->role_name = "QA";
                            //dd($test->mailUserId);
                            $test->save();
                        }

                        // dd($list);
                        // foreach ($list as $u) {
                        //     $email = Helpers:: getAllUserEmail($u->user_id);
                        //     if (!empty($email)) {
                        //         try {
                        //             info('Sending mail to', [$email]);
                        //             Mail::send(
                        //                 'mail.view-mail',
                        //                 ['data' => $changestage,'site'=>'Observation','history' => ' QA Approval', 'process' => 'Observation', 'comment' => $history->comment,'user'=> Auth::user()->name],
                        //                 function ($message) use ($email, $changestage) {
                        //                  $message->to($email)
                        //                  ->subject("QMS Notification: Observation, Record #" . str_pad($changestage->record, 4, '0', STR_PAD_LEFT) . " - Activity:  QA Approval Performed"); }
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
                                    $data = ['data' => $changestage,'site'=>'Observation','history' => ' QA Approval', 'process' => 'Observation', 'comment' => $history->comment,'user'=> Auth::user()->name];
                        
                                    SendMail::dispatch($data, $email, $changestage, 'Observation');
                                }
                            } catch (\Exception $e) {
                                \Log::error('Mail sending failed for user_id: ' . $u->user_id . ' - Error: ' . $e->getMessage());       
                                continue;
                            }
                        }


                $changestage->update();
                toastr()->success('Document Sent');
                return back();
            }
            if ($changestage->stage == 4) {
                $changestage->stage = "5";
                $changestage->status = "Pending Final Approval";
                $changestage->all_capa_closed_by = Auth::user()->name;
                $changestage->all_capa_closed_on = Carbon::now()->format('d-M-Y');
                            $history = new AuditTrialObservation();
                            $history->Observation_id = $id;
                            $history->activity_type = 'Activity Log';
                            $history->previous = $lastDocument->status;
                            $history->current = "Pending Final Approval";
                            $history->comment = $request->comment;
                            $history->user_id = Auth::user()->id;
                            $history->user_name = Auth::user()->name;
                            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                            $history->origin_state = $lastDocument->status;
                            $history->stage = "All CAPA Closed";
                            $history->save();
            //     $list = Helpers::getLeadAuditeeUserList();
            //     foreach ($list as $u) {
            //         if($u->q_m_s_divisions_id == $changestage->division_id){
            //             $email = Helpers::getInitiatorEmail($u->user_id);
            //              if ($email !== null) {

            //               Mail::send(
            //                   'mail.view-mail',
            //                    ['data' => $changestage],
            //                 function ($message) use ($email) {
            //                     $message->to($email)
            //                         ->subject("Document sent ".Auth::user()->name);
            //                 }
            //               );
            //             }
            //      }
            //   }

                    $list = Helpers::getLeadAuditorUserList($changestage->division_id);

                    $userIds = collect($list)->pluck('user_id')->toArray();
                    $users = User::whereIn('id', $userIds)->select('id', 'name', 'email')->get();
                    $userId1 = $users->pluck('id')->implode(',');
                    $userId = $users->pluck('name')->implode(',');

                    if($userId){
                        $test = new AuditTrialObservation();
                        $test->Observation_id = $id;
                        $test->activity_type = "Notification";
                        $test->action = 'Notification';
                        $test->comment = "";
                        $test->user_id = Auth::user()->id;
                        $test->user_name = Auth::user()->name;
                        $test->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                        $test->origin_state = "Not Applicable";
                        $test->previous = $lastDocument->status;
                        $test->current = "Pending Final Approval";
                        $test->stage = "";
                        $test->action_name = "";
                        $test->mailUserId = $userId1;
                        $test->role_name = "QA";
                        //dd($test->mailUserId);
                        $test->save();
                    }


                    // dd($list);
                    // foreach ($list as $u) {
                    //     $email = Helpers:: getAllUserEmail($u->user_id);
                    //     if (!empty($email)) {
                    //         try {
                    //             info('Sending mail to', [$email]);
                    //             Mail::send(
                    //                 'mail.view-mail',
                    //                 ['data' => $changestage,'site'=>'Observation','history' => 'All CAPA Closed', 'process' => 'Observation', 'comment' => $history->comment,'user'=> Auth::user()->name],
                    //                 function ($message) use ($email, $changestage) {
                    //                 $message->to($email)
                    //                 ->subject("QMS Notification: Observation, Record #" . str_pad($changestage->record, 4, '0', STR_PAD_LEFT) . " - Activity: All CAPA Closed Performed"); }
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
                                $data = ['data' => $changestage,'site'=>'Observation','history' => 'All CAPA Closed', 'process' => 'Observation', 'comment' => $history->comment,'user'=> Auth::user()->name];
                    
                                SendMail::dispatch($data, $email, $changestage, 'Observation');
                            }
                        } catch (\Exception $e) {
                            \Log::error('Mail sending failed for user_id: ' . $u->user_id . ' - Error: ' . $e->getMessage());       
                            continue;
                        }
                    }

                    $list = Helpers::getLeadAuditeeUserList($changestage->division_id);

                    $userIds = collect($list)->pluck('user_id')->toArray();
                    $users = User::whereIn('id', $userIds)->select('id', 'name', 'email')->get();
                    $userId1 = $users->pluck('id')->implode(',');
                    $userId = $users->pluck('name')->implode(',');

                    if($userId){
                        $test = new AuditTrialObservation();
                        $test->Observation_id = $id;
                        $test->activity_type = "Notification";
                        $test->action = 'Notification';
                        $test->comment = "";
                        $test->user_id = Auth::user()->id;
                        $test->user_name = Auth::user()->name;
                        $test->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                        $test->origin_state = "Not Applicable";
                        $test->previous = $lastDocument->status;
                        $test->current = "Pending Final Approval";
                        $test->stage = "";
                        $test->action_name = "";
                        $test->mailUserId = $userId1;
                        $test->role_name = "QA";
                        //dd($test->mailUserId);
                        $test->save();
                    }


                    // dd($list);
                    // foreach ($list as $u) {
                    //     $email = Helpers:: getAllUserEmail($u->user_id);
                    //     if (!empty($email)) {
                    //         try {
                    //             info('Sending mail to', [$email]);
                    //             Mail::send(
                    //                 'mail.view-mail',
                    //                 ['data' => $changestage,'site'=>'Observation','history' => 'All CAPA Closed', 'process' => 'Observation', 'comment' => $history->comment,'user'=> Auth::user()->name],
                    //                 function ($message) use ($email, $changestage) {
                    //                  $message->to($email)
                    //                  ->subject("QMS Notification: Observation, Record #" . str_pad($changestage->record, 4, '0', STR_PAD_LEFT) . " - Activity: All CAPA Closed Performed"); }
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
                                $data = ['data' => $changestage,'site'=>'Observation','history' => 'All CAPA Closed', 'process' => 'Observation', 'comment' => $history->comment,'user'=> Auth::user()->name];
                    
                                SendMail::dispatch($data, $email, $changestage, 'Observation');
                            }
                        } catch (\Exception $e) {
                            \Log::error('Mail sending failed for user_id: ' . $u->user_id . ' - Error: ' . $e->getMessage());       
                            continue;
                        }
                    }

                $changestage->update();
                toastr()->success('Document Sent');
                return back();
            }

            if ($changestage->stage == 5) {
                $changestage->stage = "6";
                $changestage->status = "Closed - Done";
                $changestage->Final_Approval_By = Auth::user()->name;
                $changestage->Final_Approval_on = Carbon::now()->format('d-M-Y');
                $history = new AuditTrialObservation();
                $history->Observation_id = $id;
                $history->activity_type = 'Activity Log';
                $history->previous = $lastDocument->status;
                $history->current = "Closed - Done";
                $history->comment = $request->comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                $history->stage = "Final Approval";
                $history->save();
            //     $list = Helpers::getLeadAuditeeUserList();
            //     foreach ($list as $u) {
            //         if($u->q_m_s_divisions_id == $changestage->division_id){
            //             $email = Helpers::getInitiatorEmail($u->user_id);
            //              if ($email !== null) {

            //               Mail::send(
            //                   'mail.view-mail',
            //                    ['data' => $changestage],
            //                 function ($message) use ($email) {
            //                     $message->to($email)
            //                         ->subject("Document sent ".Auth::user()->name);
            //                 }
            //               );
            //             }
            //      }
            //   }

                        $list = Helpers::getLeadAuditorUserList($changestage->division_id);

                        $userIds = collect($list)->pluck('user_id')->toArray();
                        $users = User::whereIn('id', $userIds)->select('id', 'name', 'email')->get();
                        $userId1 = $users->pluck('id')->implode(',');
                        $userId = $users->pluck('name')->implode(',');

                        if($userId){
                            $test = new AuditTrialObservation();
                            $test->Observation_id = $id;
                            $test->activity_type = "Notification";
                            $test->action = 'Notification';
                            $test->comment = "";
                            $test->user_id = Auth::user()->id;
                            $test->user_name = Auth::user()->name;
                            $test->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                            $test->origin_state = "Not Applicable";
                            $test->previous = $lastDocument->status;
                            $test->current = "Closed - Done";
                            $test->stage = "";
                            $test->action_name = "";
                            $test->mailUserId = $userId1;
                            $test->role_name = "QA";
                            //dd($test->mailUserId);
                            $test->save();
                        }


                        // dd($list);
                        // foreach ($list as $u) {
                        //     $email = Helpers:: getAllUserEmail($u->user_id);
                        //     if (!empty($email)) {
                        //         try {
                        //             info('Sending mail to', [$email]);
                        //             Mail::send(
                        //                 'mail.view-mail',
                        //                 ['data' => $changestage,'site'=>'Observation','history' => 'Final Approval', 'process' => 'Observation', 'comment' => $history->comment,'user'=> Auth::user()->name],
                        //                 function ($message) use ($email, $changestage) {
                        //                 $message->to($email)
                        //                 ->subject("QMS Notification: Observation, Record #" . str_pad($changestage->record, 4, '0', STR_PAD_LEFT) . " - Activity: Final Approval Performed"); }
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
                                    $data = ['data' => $changestage,'site'=>'Observation','history' => 'Final Approval', 'process' => 'Observation', 'comment' => $history->comment,'user'=> Auth::user()->name];
                        
                                    SendMail::dispatch($data, $email, $changestage, 'Observation');
                                }
                            } catch (\Exception $e) {
                                \Log::error('Mail sending failed for user_id: ' . $u->user_id . ' - Error: ' . $e->getMessage());       
                                continue;
                            }
                        }

                        $list = Helpers::getLeadAuditeeUserList($changestage->division_id);

                        $userIds = collect($list)->pluck('user_id')->toArray();
                        $users = User::whereIn('id', $userIds)->select('id', 'name', 'email')->get();
                        $userId1 = $users->pluck('id')->implode(',');
                        $userId = $users->pluck('name')->implode(',');

                        if($userId){
                            $test = new AuditTrialObservation();
                            $test->Observation_id = $id;
                            $test->activity_type = "Notification";
                            $test->action = 'Notification';
                            $test->comment = "";
                            $test->user_id = Auth::user()->id;
                            $test->user_name = Auth::user()->name;
                            $test->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                            $test->origin_state = "Not Applicable";
                            $test->previous = $lastDocument->status;
                            $test->current = "Closed - Done";
                            $test->stage = "";
                            $test->action_name = "";
                            $test->mailUserId = $userId1;
                            $test->role_name = "QA";
                            //dd($test->mailUserId);
                            $test->save();
                        }

                        // dd($list);
                        // foreach ($list as $u) {
                        //     $email = Helpers:: getAllUserEmail($u->user_id);
                        //     if (!empty($email)) {
                        //         try {
                        //             info('Sending mail to', [$email]);
                        //             Mail::send(
                        //                 'mail.view-mail',
                        //                 ['data' => $changestage,'site'=>'Observation','history' => 'Final Approval', 'process' => 'Observation', 'comment' => $history->comment,'user'=> Auth::user()->name],
                        //                 function ($message) use ($email, $changestage) {
                        //                 $message->to($email)
                        //                 ->subject("QMS Notification: Observation, Record #" . str_pad($changestage->record, 4, '0', STR_PAD_LEFT) . " - Activity: Final Approval Performed"); }
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
                                    $data = ['data' => $changestage,'site'=>'Observation','history' => 'Final Approval', 'process' => 'Observation', 'comment' => $history->comment,'user'=> Auth::user()->name];
                        
                                    SendMail::dispatch($data, $email, $changestage, 'Observation');
                                }
                            } catch (\Exception $e) {
                                \Log::error('Mail sending failed for user_id: ' . $u->user_id . ' - Error: ' . $e->getMessage());       
                                continue;
                            }
                        }


                $changestage->update();
                toastr()->success('Document Sent');
                return back();
            }
        } else {
            toastr()->error('E-signature Not match');
            return back();
        }
    }

    // public function ObservationCancel(Request $request, $id)
    // {
    //     if ($request->username == Auth::user()->email && Hash::check($request->password, Auth::user()->password)) {
    //         $changeControl = Observation::find($id);

    //         if ($changeControl->stage == 1) {
    //             $changeControl->stage = "0";
    //             $changeControl->status = "Closed - Cancelled";
    //             $changeControl->update();
    //             toastr()->success('Document Sent');
    //             return back();
    //         }
    //     } else {
    //         toastr()->error('E-signature Not match');
    //         return back();
    //     }
    // }

    public function RejectStateChange(Request $request, $id)
    {
        if ($request->username == Auth::user()->email && Hash::check($request->password, Auth::user()->password)) {
            $changeControl = Observation::find($id);
            $lastDocument = Observation::find($id);


            if ($changeControl->stage == 3) {
                $changeControl->stage = "2";
                $changeControl->status = "Pending CAPA Plan";
                $changeControl->reject_capa_plan_by = Auth::user()->name;
                $changeControl->reject_capa_plan_on = Carbon::now()->format('d-M-Y');
                $history = new AuditTrialObservation();
                $history->Observation_id = $id;
                $history->activity_type = 'Activity Log';
                $history->previous = $lastDocument->status;
                $history->current = "Pending CAPA Plan";
                $history->comment = $request->comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                $history->stage = "Reject CAPA Plan";
                $history->save();
                $changeControl->update();


                $list = Helpers::getLeadAuditeeUserList($changeControl->division_id);

                $userIds = collect($list)->pluck('user_id')->toArray();
                $users = User::whereIn('id', $userIds)->select('id', 'name', 'email')->get();
                $userId1 = $users->pluck('id')->implode(',');
                $userId = $users->pluck('name')->implode(',');

                if($userId){
                    $test = new AuditTrialObservation();
                    $test->Observation_id = $id;
                    $test->activity_type = "Notification";
                    $test->action = 'Notification';
                    $test->comment = "";
                    $test->user_id = Auth::user()->id;
                    $test->user_name = Auth::user()->name;
                    $test->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $test->origin_state = "Not Applicable";
                    $test->previous = $lastDocument->status;
                    $test->current = "Pending CAPA Plan";
                    $test->stage = "";
                    $test->action_name = "";
                    $test->mailUserId = $userId1;
                    $test->role_name = "QA";
                    //dd($test->mailUserId);
                    $test->save();
                }



                // dd($list);
                // foreach ($list as $u) {
                //     $email = Helpers:: getAllUserEmail($u->user_id);
                //     if (!empty($email)) {
                //         try {
                //             info('Sending mail to', [$email]);
                //             Mail::send(
                //                 'mail.view-mail',
                //                 ['data' => $changeControl,'site'=>'Observation','history' => 'Reject CAPA Plan', 'process' => 'Observation', 'comment' => $history->comment,'user'=> Auth::user()->name],
                //                 function ($message) use ($email, $changeControl) {
                //                 $message->to($email)
                //                 ->subject("QMS Notification: Observation, Record #" . str_pad($changeControl->record, 4, '0', STR_PAD_LEFT) . " - Activity: Reject CAPA Plan Performed"); }
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
                            $data = ['data' => $changeControl,'site'=>'Observation','history' => 'Reject CAPA Plan', 'process' => 'Observation', 'comment' => $history->comment,'user'=> Auth::user()->name];
                
                            SendMail::dispatch($data, $email, $changestage, 'Observation');
                        }
                    } catch (\Exception $e) {
                        \Log::error('Mail sending failed for user_id: ' . $u->user_id . ' - Error: ' . $e->getMessage());       
                        continue;
                    }
                }

                toastr()->success('Document Sent');
                return back();
            }
            if ($changeControl->stage == 1) {
                $changeControl->stage = "0";
                $changeControl->status = "Closed - Cancelled";
                $changeControl->final_approvel_by = Auth::user()->name;
                $changeControl->final_approvel_on = Carbon::now()->format('d-M-Y');
                $history = new AuditTrialObservation();
                $history->Observation_id = $id;
                $history->activity_type = 'Activity Log';
                $history->previous = $lastDocument->status;
                $history->current = "Closed - Cancelled";
                $history->comment = $request->comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                $history->stage = "Final Approval";
                $history->save();

                $list = Helpers::getLeadAuditeeUserList($changeControl->division_id);

                $userIds = collect($list)->pluck('user_id')->toArray();
                $users = User::whereIn('id', $userIds)->select('id', 'name', 'email')->get();
                $userId1 = $users->pluck('id')->implode(',');
                $userId = $users->pluck('name')->implode(',');

                if($userId){
                    $test = new AuditTrialObservation();
                    $test->Observation_id = $id;
                    $test->activity_type = "Notification";
                    $test->action = 'Notification';
                    $test->comment = "";
                    $test->user_id = Auth::user()->id;
                    $test->user_name = Auth::user()->name;
                    $test->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $test->origin_state = "Not Applicable";
                    $test->previous = $lastDocument->status;
                    $test->current = "Closed - Cancelled";
                    $test->stage = "";
                    $test->action_name = "";
                    $test->mailUserId = $userId1;
                    $test->role_name = "QA";
                    //dd($test->mailUserId);
                    $test->save();
                }

                // dd($list);
                // foreach ($list as $u) {
                //     $email = Helpers:: getAllUserEmail($u->user_id);
                //     if (!empty($email)) {
                //         try {
                //             info('Sending mail to', [$email]);
                //             Mail::send(
                //                 'mail.view-mail',
                //                 ['data' => $changeControl,'site'=>'Observation','history' => 'Cancel', 'process' => 'Observation', 'comment' => $history->comment,'user'=> Auth::user()->name],
                //                 function ($message) use ($email, $changeControl) {
                //                 $message->to($email)
                //                 ->subject("QMS Notification: Observation, Record #" . str_pad($changeControl->record, 4, '0', STR_PAD_LEFT) . " - Activity: Cancel Performed"); }
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
                            $data = ['data' => $changeControl,'site'=>'Observation','history' => 'Cancel', 'process' => 'Observation', 'comment' => $history->comment,'user'=> Auth::user()->name];
                
                            SendMail::dispatch($data, $email, $changeControl, 'Observation');
                        }
                    } catch (\Exception $e) {
                        \Log::error('Mail sending failed for user_id: ' . $u->user_id . ' - Error: ' . $e->getMessage());       
                        continue;
                    }
                }

                $list = Helpers::getQAUserList($changeControl->division_id);

                $userIds = collect($list)->pluck('user_id')->toArray();
                $users = User::whereIn('id', $userIds)->select('id', 'name', 'email')->get();
                $userId1 = $users->pluck('id')->implode(',');
                $userId = $users->pluck('name')->implode(',');

                if($userId){
                    $test = new AuditTrialObservation();
                    $test->Observation_id = $id;
                    $test->activity_type = "Notification";
                    $test->action = 'Notification';
                    $test->comment = "";
                    $test->user_id = Auth::user()->id;
                    $test->user_name = Auth::user()->name;
                    $test->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $test->origin_state = "Not Applicable";
                    $test->previous = $lastDocument->status;
                    $test->current = "Closed - Cancelled";
                    $test->stage = "";
                    $test->action_name = "";
                    $test->mailUserId = $userId1;
                    $test->role_name = "QA";
                    //dd($test->mailUserId);
                    $test->save();
                }


                // dd($list);
                // foreach ($list as $u) {
                //     $email = Helpers:: getAllUserEmail($u->user_id);
                //     if (!empty($email)) {
                //         try {
                //             info('Sending mail to', [$email]);
                //             Mail::send(
                //                 'mail.view-mail',
                //                 ['data' => $changeControl,'site'=>'Observation','history' => 'Cancel', 'process' => 'Observation', 'comment' => $history->comment,'user'=> Auth::user()->name],
                //                 function ($message) use ($email, $changeControl) {
                //                 $message->to($email)
                //                 ->subject("QMS Notification: Observation, Record #" . str_pad($changeControl->record, 4, '0', STR_PAD_LEFT) . " - Activity: Cancel Performed"); }
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
                            $data = ['data' => $changeControl,'site'=>'Observation','history' => 'Cancel', 'process' => 'Observation', 'comment' => $history->comment,'user'=> Auth::user()->name];
                
                            SendMail::dispatch($data, $email, $changeControl, 'Observation');
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
                $changeControl->stage = "1";
                $changeControl->status = "Opened";
                $changeControl->more_info_required_by = Auth::user()->name;
                $changeControl->more_info_required_on = Carbon::now()->format('d-M-Y');
                $history = new AuditTrialObservation();
                $history->Observation_id = $id;
                $history->activity_type = 'Activity Log';
                $history->previous = $lastDocument->status;
                $history->current = "Opened";
                $history->comment = $request->comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                $history->stage = "More Info Required";
                $history->save();


                $list = Helpers::getLeadAuditorUserList($changeControl->division_id);

                $userIds = collect($list)->pluck('user_id')->toArray();
                $users = User::whereIn('id', $userIds)->select('id', 'name', 'email')->get();
                $userId1 = $users->pluck('id')->implode(',');
                $userId = $users->pluck('name')->implode(',');

                if($userId){
                    $test = new AuditTrialObservation();
                    $test->Observation_id = $id;
                    $test->activity_type = "Notification";
                    $test->action = 'Notification';
                    $test->comment = "";
                    $test->user_id = Auth::user()->id;
                    $test->user_name = Auth::user()->name;
                    $test->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $test->origin_state = "Not Applicable";
                    $test->previous = $lastDocument->status;
                    $test->current = "Opened";
                    $test->stage = "";
                    $test->action_name = "";
                    $test->mailUserId = $userId1;
                    $test->role_name = "QA";
                    //dd($test->mailUserId);
                    $test->save();
                }


                // dd($list);
                // foreach ($list as $u) {
                //     $email = Helpers:: getAllUserEmail($u->user_id);
                //     if (!empty($email)) {
                //         try {
                //             info('Sending mail to', [$email]);
                //             Mail::send(
                //                 'mail.view-mail',
                //                 ['data' => $changeControl,'site'=>'Observation','history' => 'More Info Required', 'process' => 'Observation', 'comment' => $history->comment,'user'=> Auth::user()->name],
                //                 function ($message) use ($email, $changeControl) {
                //                 $message->to($email)
                //                 ->subject("QMS Notification: Observation, Record #" . str_pad($changeControl->record, 4, '0', STR_PAD_LEFT) . " - Activity: More Info Required Performed"); }
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
                            $data = ['data' => $changeControl,'site'=>'Observation','history' => 'More Info Required', 'process' => 'Observation', 'comment' => $history->comment,'user'=> Auth::user()->name];
                
                            SendMail::dispatch($data, $email, $changeControl, 'Observation');
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

            if ($changeControl->stage == 5) {
                $changeControl->stage = "2";
                $changeControl->status = "Pending CAPA Plan";
                $changeControl->final_reject_capa_plan_by = Auth::user()->name;
                $changeControl->final_reject_capa_plan_on = Carbon::now()->format('d-M-Y');
                $history = new AuditTrialObservation();
                $history->Observation_id = $id;
                $history->activity_type = 'Activity Log';
                $history->previous = $lastDocument->status;
                $history->current = "Pending CAPA Plan";
                $history->comment = $request->comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                $history->stage = "Reject CAPA Plan";
                $history->save();

                $list = Helpers::getLeadAuditeeUserList($changeControl->division_id);

                $userIds = collect($list)->pluck('user_id')->toArray();
                $users = User::whereIn('id', $userIds)->select('id', 'name', 'email')->get();
                $userId1 = $users->pluck('id')->implode(',');
                $userId = $users->pluck('name')->implode(',');

                if($userId){
                    $test = new AuditTrialObservation();
                    $test->Observation_id = $id;
                    $test->activity_type = "Notification";
                    $test->action = 'Notification';
                    $test->comment = "";
                    $test->user_id = Auth::user()->id;
                    $test->user_name = Auth::user()->name;
                    $test->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $test->origin_state = "Not Applicable";
                    $test->previous = $lastDocument->status;
                    $test->current = "Pending CAPA Plan";
                    $test->stage = "";
                    $test->action_name = "";
                    $test->mailUserId = $userId1;
                    $test->role_name = "QA";
                    //dd($test->mailUserId);
                    $test->save();
                }

                // dd($list);
                // foreach ($list as $u) {
                //     $email = Helpers:: getAllUserEmail($u->user_id);
                //     if (!empty($email)) {
                //         try {
                //             info('Sending mail to', [$email]);
                //             Mail::send(
                //                 'mail.view-mail',
                //                 ['data' => $changeControl,'site'=>'Observation','history' => 'Final Reject CAPA Plan', 'process' => 'Observation', 'comment' => $history->comment,'user'=> Auth::user()->name],
                //                 function ($message) use ($email, $changeControl) {
                //                 $message->to($email)
                //                 ->subject("QMS Notification: Observation, Record #" . str_pad($changeControl->record, 4, '0', STR_PAD_LEFT) . " - Activity: Final Reject CAPA Plan Performed"); }
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
                            $data = ['data' => $changeControl,'site'=>'Observation','history' => 'Final Reject CAPA Plan', 'process' => 'Observation', 'comment' => $history->comment,'user'=> Auth::user()->name];
                
                            SendMail::dispatch($data, $email, $changeControl, 'Observation');
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

    public function boostStage(Request $request, $id)
    {
        if ($request->username == Auth::user()->email && Hash::check($request->password, Auth::user()->password)) {
            $changeControl = Observation::find($id);
            $lastDocument = Observation::find($id);


            if ($changeControl->stage == 3) {
                $changeControl->stage = "6";
                $changeControl->status = "Closed - Done";
                $changeControl->qa_approvel_without_capa_by = Auth::user()->name;
                $changeControl->qa_approvel_without_capa_on = Carbon::now()->format('d-M-Y');
                $history = new AuditTrialObservation();
                $history->Observation_id = $id;
                $history->activity_type = 'Activity Log';
                $history->previous = $lastDocument->status;
                $history->current = "Closed - Done";
                $history->comment = $request->comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                $history->stage = "QA Approval Without CAPA";
                $history->save();


                    $list = Helpers::getLeadAuditorUserList($changeControl->division_id);

                    $userIds = collect($list)->pluck('user_id')->toArray();
                    $users = User::whereIn('id', $userIds)->select('id', 'name', 'email')->get();
                    $userId1 = $users->pluck('id')->implode(',');
                    $userId = $users->pluck('name')->implode(',');

                    if($userId){
                        $test = new AuditTrialObservation();
                        $test->Observation_id = $id;
                        $test->activity_type = "Notification";
                        $test->action = 'Notification';
                        $test->comment = "";
                        $test->user_id = Auth::user()->id;
                        $test->user_name = Auth::user()->name;
                        $test->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                        $test->origin_state = "Not Applicable";
                        $test->previous = $lastDocument->status;
                        $test->current = "Closed - Done";
                        $test->stage = "";
                        $test->action_name = "";
                        $test->mailUserId = $userId1;
                        $test->role_name = "QA";
                        //dd($test->mailUserId);
                        $test->save();
                    }


                    // dd($list);
                    // foreach ($list as $u) {
                    //     $email = Helpers:: getAllUserEmail($u->user_id);
                    //     if (!empty($email)) {
                    //         try {
                    //             info('Sending mail to', [$email]);
                    //             Mail::send(
                    //                 'mail.view-mail',
                    //                 ['data' => $changeControl,'site'=>'Observation','history' => 'QA Approval Without CAPA', 'process' => 'Observation', 'comment' => $history->comment,'user'=> Auth::user()->name],
                    //                 function ($message) use ($email, $changeControl) {
                    //                 $message->to($email)
                    //                 ->subject("QMS Notification: Observation, Record #" . str_pad($changeControl->record, 4, '0', STR_PAD_LEFT) . " - Activity: QA Approval Without CAPA Performed"); }
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
                                $data = ['data' => $changeControl,'site'=>'Observation','history' => 'QA Approval Without CAPA', 'process' => 'Observation', 'comment' => $history->comment,'user'=> Auth::user()->name];
                    
                                SendMail::dispatch($data, $email, $changeControl, 'Observation');
                            }
                        } catch (\Exception $e) {
                            \Log::error('Mail sending failed for user_id: ' . $u->user_id . ' - Error: ' . $e->getMessage());       
                            continue;
                        }
                    }

                    $list = Helpers::getLeadAuditeeUserList($changeControl->division_id);

                    $userIds = collect($list)->pluck('user_id')->toArray();
                    $users = User::whereIn('id', $userIds)->select('id', 'name', 'email')->get();
                    $userId1 = $users->pluck('id')->implode(',');
                    $userId = $users->pluck('name')->implode(',');

                    if($userId){
                        $test = new AuditTrialObservation();
                        $test->Observation_id = $id;
                        $test->activity_type = "Notification";
                        $test->action = 'Notification';
                        $test->comment = "";
                        $test->user_id = Auth::user()->id;
                        $test->user_name = Auth::user()->name;
                        $test->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                        $test->origin_state = "Not Applicable";
                        $test->previous = $lastDocument->status;
                        $test->current = "Closed - Done";
                        $test->stage = "";
                        $test->action_name = "";
                        $test->mailUserId = $userId1;
                        $test->role_name = "QA";
                        //dd($test->mailUserId);
                        $test->save();
                    }


                    // dd($list);
                    // foreach ($list as $u) {
                    //     $email = Helpers:: getAllUserEmail($u->user_id);
                    //     if (!empty($email)) {
                    //         try {
                    //             info('Sending mail to', [$email]);
                    //             Mail::send(
                    //                 'mail.view-mail',
                    //                 ['data' => $changeControl,'site'=>'Observation','history' => 'QA Approval Without CAPA', 'process' => 'Observation', 'comment' => $history->comment,'user'=> Auth::user()->name],
                    //                 function ($message) use ($email, $changeControl) {
                    //                 $message->to($email)
                    //                 ->subject("QMS Notification: Observation, Record #" . str_pad($changeControl->record, 4, '0', STR_PAD_LEFT) . " - Activity: QA Approval Without CAPA Performed"); }
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
                                $data = ['data' => $changeControl,'site'=>'Observation','history' => 'QA Approval Without CAPA', 'process' => 'Observation', 'comment' => $history->comment,'user'=> Auth::user()->name];
                    
                                SendMail::dispatch($data, $email, $changeControl, 'Observation');
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

    public function observation_child(Request $request, $id)
    {
        $cft = [];
        $parent_id = $id;
        $parent_type = "Capa";
        $old_record = Capa::select('id', 'division_id', 'record', 'created_at')->get();
        $record_number = ((RecordNumber::first()->value('counter')) + 1);
        $record_number = str_pad($record_number, 4, '0', STR_PAD_LEFT);
        $currentDate = Carbon::now();
        $formattedDate = $currentDate->addDays(30);
        $due_date = $formattedDate->format('d-M-Y');
        $changeControl = OpenStage::find(1);
        $rca_old_record = RootCauseAnalysis::select('id', 'division_id', 'record', 'short_description', 'created_at')->get();
        $parent_division = Observation::where('id',$id)->value('division_id');

        if(!empty($changeControl->cft)) $cft = explode(',', $changeControl->cft);
        return view('frontend.forms.capa', compact('record_number', 'due_date','rca_old_record','parent_division', 'parent_id', 'parent_type', 'old_record', 'cft'));
    }


    public function ObservationAuditTrialShow($id)
    {
        $audit = AuditTrialObservation::where('Observation_id', $id)->orderByDESC('id')->get()->unique('activity_type');
        $today = Carbon::now()->format('d-m-y');
        $document = Observation::where('id', $id)->first();
        $document->initiator = User::where('id', $document->initiator_id)->value('name');

        return view('frontend.observation.audit-trial', compact('audit', 'document', 'today'));
    }
    public static function auditReport($id)
    {
        $doc = Observation::find($id);
        if (!empty($doc)) {
            $doc->originator = User::where('id', $doc->initiator_id)->value('name');
            $data = AuditTrialObservation::where('Observation_id', $id)->get();
            $pdf = App::make('dompdf.wrapper');
            $time = Carbon::now();
            $pdf = PDF::loadview('frontend.observation.Obs_audittrail_PDF', compact('data', 'doc'))
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
            return $pdf->stream('Observation-Audit' . $id . '.pdf');
        }
    }
    public function ObservationAuditTrialDetails($id)
    {
        $detail = AuditTrialObservation::find($id);

        $detail_data = AuditTrialObservation::where('activity_type', $detail->activity_type)->where('Observation_id', $detail->Observation_id)->latest()->get();

        $doc = Observation::where('id', $detail->Observation_id)->first();

        $doc->origiator_name = User::find($doc->initiator_id);
        return view('frontend.observation.audit-trial-inner', compact('detail', 'doc', 'detail_data'));
    }

    public function single_pdf($id)
    {
        $data = Observation::find($id);
        if (!empty($data)) {
            $data->originator = User::where('id', $data->initiator_id)->value('name');

            $idsArray = explode(',', $data->Microbiology_Person);
            $users = User::whereIn('id', $idsArray)->get(['name']);
            $userNames = $users->pluck('name')->implode(', ');
            $griddata = ObservationGrid::where('observation_id',$data->id)->first();

            // $docdetail = Docdetail::where('cc_id', $data->id)->first();

            // pdf related work
            $pdf = App::make('dompdf.wrapper');
            $time = Carbon::now();
            $pdf = PDF::loadview('frontend.observation.obs_single_report', compact(
                'data',
                'griddata',


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
}
