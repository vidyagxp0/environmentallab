<?php

namespace App\Http\Controllers;

use App\Jobs\SendMail;
use App\Models\QMSDivision;
use App\Models\RecordNumber;
use App\Models\RiskAuditTrail;
use App\Models\RiskManagement;
use App\Models\RiskAssesmentGrid;
use App\Models\RoleGroup;
use App\Models\User;
use App\Services\DocumentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use PDF;
use Helpers;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\App;

class RiskManagementController extends Controller
{

    public function risk()
    {
        $old_record = RiskManagement::select('id', 'division_id', 'record', 'created_at')->get();
        $record_number = ((RecordNumber::first()->value('counter')) + 1);
        $record_number = str_pad($record_number, 4, '0', STR_PAD_LEFT);
        $currentDate = Carbon::now();
        $formattedDate = $currentDate->addDays(30);
        $due_date = $formattedDate->format('Y-m-d');

        $division = QMSDivision::where('name', Helpers::getDivisionName(session()->get('division')))->first();

        if ($division) {
            $last_record = RiskManagement::where('division_id', $division->id)->latest()->first();

            if ($last_record) {
                $record_number = $last_record->record_number ? str_pad($last_record->record_number->record_number + 1, 4, '0', STR_PAD_LEFT) : '0001';
            } else {
                $record_number = '0001';
            }
        }

        return view("frontend.forms.risk-management", compact('due_date', 'record_number', 'old_record'));
    }

    public function store(Request $request)
    {
        // return dd($request);
        // return $request;



        if (!$request->short_description) {
            toastr()->info("Short Description is required");
            return redirect()->back()->withInput();
        }
        // return $request;
        $data = new RiskManagement();
        $data->form_type = "risk-assesment";
        $data->division_id = $request->division_id;
        $data->division_code = $request->division_code;
        //$data->record_number = $request->record_number;
        $data->record = ((RecordNumber::first()->value('counter')) + 1);
        $data->initiator_id = Auth::user()->id;
        $data->short_description = $request->short_description;
        $data->intiation_date = $request->intiation_date;
        $data->open_date = $request->open_date;
        $data->assign_to = $request->assign_to;
        $data->due_date = $request->due_date;
        $data->Initiator_Group = $request->Initiator_Group;
        $data->initiator_group_code = $request->initiator_group_code;
        $data->departments = implode(',', $request->departments);
        // $data->team_members = implode(',', $request->team_members);
        $data->source_of_risk = $request->source_of_risk;
        $data->source_of_risk2 = $request->source_of_risk2;
        $data->type = $request->type;
        $data->priority_level = $request->priority_level;
        $data->zone = $request->zone;
        $data->country = $request->country;
        $data->state = $request->state;
        $data->city = $request->city;
        $data->description = $request->description;
        $data->severity2_level = $request->severity2_level;
        $data->comments = $request->comments;
        $data->departments2 = implode(',', $request->departments2);
        $data->site_name = $request->site_name;
        $data->building = $request->building;
        $data->floor = $request->floor;
        $data->room = $request->room;
        $data->related_record = json_encode($request->related_record);
        $data->duration = $request->duration;
        $data->hazard = $request->hazard;
        $data->room2 = $request->room2;
        $data->regulatory_climate = $request->regulatory_climate;
        $data->Number_of_employees = $request->Number_of_employees;
        $data->risk_management_strategy = $request->risk_management_strategy;
        $data->estimated_man_hours = $request->estimated_man_hours;
        $data->schedule_start_date1 = $request->schedule_start_date1;
        $data->schedule_end_date1 = $request->schedule_end_date1;
        $data->estimated_cost = $request->estimated_cost;
        $data->currency = $request->currency;

        $data->root_cause_methodology = implode(',', $request->root_cause_methodology);
        // $data->measurement = json_encode($request->measurement);
        // $data->materials = json_encode($request->materials);
        // $data->methods = json_encode($request->methods);
        // $data->environment = json_encode($request->environment);
        //$data->manpower = json_encode($request->manpower);
        //$data->machine = json_encode($request->machine);
        //$data->problem_statement1 = ($request->problem_statement1);
        // $data->why_problem_statement = $request->why_problem_statement;
        // $data->why_1 = json_encode($request->why_1);
        // $data->why_2 = json_encode($request->why_2);
        // $data->why_3 = json_encode($request->why_3);
        // $data->why_4 = json_encode($request->why_4);
        // $data->why_5 = json_encode($request->why_5);
        // $data->root_cause = $request->root_cause;
        // $data->what_will_be = $request->what_will_be;
        // $data->what_will_not_be = $request->what_will_not_be;
        // $data->what_rationable = $request->what_rationable;
        // $data->where_will_be = $request->where_will_be;
        // $data->where_will_not_be = $request->where_will_not_be;
        // $data->where_rationable = $request->where_rationable;
        // $data->when_will_be = $request->when_will_be;
        // $data->when_will_not_be = $request->when_will_not_be;
        // $data->when_rationable = $request->when_rationable;
        // $data->coverage_will_be = $request->coverage_will_be;
        // $data->coverage_will_not_be = $request->coverage_will_not_be;
        // $data->coverage_rationable = $request->coverage_rationable;
        // $data->who_will_be = $request->who_will_be;
        // $data->who_will_not_be = $request->who_will_not_be;
        // $data->who_rationable = $request->who_rationable;
        // $data->training_require = $request->training_require;
        $data->justification = $request->justification;
        $data->cost_of_risk = $request->cost_of_risk;
        $data->environmental_impact = $request->environmental_impact;
        $data->public_perception_impact = $request->public_perception_impact;
        $data->calculated_risk = $request->calculated_risk;
        $data->impacted_objects = $request->impacted_objects;
        $data->severity_rate = $request->severity_rate;
        $data->occurrence = $request->occurrence;
        $data->detection = $request->detection;
        $data->detection2 = $request->detection2;
        $data->rpn = $request->rpn;
        //  return $data;
        $data->residual_risk = $request->residual_risk;
        $data->residual_risk_impact = $request->residual_risk_impact;
        $data->residual_risk_probability = $request->residual_risk_probability;
        $data->analysisN2 = $request->analysisN2;
        $data->analysisRPN2 = $request->analysisRPN2;
        $data->rpn2 = $request->rpn2;
        $data->comments2 = $request->comments2;
        $data->root_cause_description = $request->root_cause_description;
        $data->investigation_summary = $request->investigation_summary;
        $data->mitigation_required = $request->mitigation_required;
        $data->mitigation_plan = $request->mitigation_plan;
        $data->mitigation_due_date = $request->mitigation_due_date;
        $data->mitigation_status = $request->mitigation_status;
        $data->mitigation_status_comments = $request->mitigation_status_comments;
        $data->impact = $request->impact;
        $data->criticality = $request->criticality;
        $data->impact_analysis = $request->impact_analysis;
        $data->risk_analysis = $request->risk_analysis;
        $data->due_date_extension = $request->due_date_extension;
        // $data->initial_rpn = $request->initial_rpn;
        //$data->severity = $request->severity;
        //$data->occurance = $request->occurance;
        $data->refrence_record =  implode(',', $request->refrence_record);



        if (!empty($request->reference)) {
            $files = [];
            if ($request->hasfile('reference')) {
                foreach ($request->file('reference') as $file) {
                    $name = $request->name . 'reference' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }


            $data->reference = json_encode($files);
        }

        if (!empty($request->attachment)) {
            $files = [];
            if ($request->hasfile('attachment')) {
                foreach ($request->file('attachment') as $file) {
                    $name = $request->name . 'attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }


            $data->attachment = json_encode($files);
        }
        $data->status = 'Opened';
        $data->stage = 1;
        // return $data;
        $data->save();

        $record = RecordNumber::first();
        $record->counter = ((RecordNumber::first()->value('counter')) + 1);
        $record->update();



        // -----------grid=------
        $data1 = new RiskAssesmentGrid();
        $data1->risk_id = $data->id;
        $data1->type = "effect_analysis";
        if (!empty($request->risk_factor)) {
            $data1->risk_factor = serialize($request->risk_factor);
        }
        if (!empty($request->risk_element)) {
            $data1->risk_element = serialize($request->risk_element);
        }
        if (!empty($request->problem_cause)) {
            $data1->problem_cause = serialize($request->problem_cause);
        }
        if (!empty($request->existing_risk_control)) {
            $data1->existing_risk_control = serialize($request->existing_risk_control);
        }
        if (!empty($request->initial_severity)) {
            $data1->initial_severity = serialize($request->initial_severity);
        }
        if (!empty($request->initial_detectability)) {
            $data1->initial_detectability = serialize($request->initial_detectability);
        }
        if (!empty($request->initial_probability)) {
            $data1->initial_probability = serialize($request->initial_probability);
        }
        if (!empty($request->initial_rpn)) {
            $data1->initial_rpn = serialize($request->initial_rpn);
        }
        if (!empty($request->risk_acceptance)) {
            $data1->risk_acceptance = serialize($request->risk_acceptance);
        }
        if (!empty($request->risk_control_measure)) {
            $data1->risk_control_measure = serialize($request->risk_control_measure);
        }
        if (!empty($request->residual_severity)) {
            $data1->residual_severity = serialize($request->residual_severity);
        }
        if (!empty($request->residual_probability)) {
            $data1->residual_probability = serialize($request->residual_probability);
        }
        if (!empty($request->residual_detectability)) {
            $data1->residual_detectability = serialize($request->residual_detectability);
        }
        if (!empty($request->residual_rpn)) {
            $data1->residual_rpn = serialize($request->residual_rpn);
        }

        if (!empty($request->risk_acceptance2)) {
            $data1->risk_acceptance2 = serialize($request->risk_acceptance2);
        }
        if (!empty($request->mitigation_proposal)) {
            $data1->mitigation_proposal = serialize($request->mitigation_proposal);
        }

        $data1->save();

        // ---------------------------------------
        $data2 = new RiskAssesmentGrid();
        $data2->risk_id = $data->id;
        $data2->type = "fishbone";

        if (!empty($request->measurement)) {
            $data2->measurement = serialize($request->measurement);
        }
        if (!empty($request->materials)) {
            $data2->materials = serialize($request->materials);
        }
        if (!empty($request->methods)) {
            $data2->methods = serialize($request->methods);
        }
        if (!empty($request->environment)) {
            $data2->environment = serialize($request->environment);
        }
        if (!empty($request->manpower)) {
            $data2->manpower = serialize($request->manpower);
        }

        if (!empty($request->machine)) {
            $data2->machine = serialize($request->machine);
        }

        if (!empty($request->problem_statement)) {
            $data2->problem_statement = $request->problem_statement;
        }
        $data2->save();
        // =-------------------------------

        $data3 = new RiskAssesmentGrid();
        $data3->risk_id = $data->id;
        $data3->type = "why_chart";
        if (!empty($request->why_problem_statement)) {
            $data3->why_problem_statement = $request->why_problem_statement;
        }
        if (!empty($request->why_1)) {
            $data3->why_1 = serialize($request->why_1);
        }
        if (!empty($request->why_2)) {
            $data3->why_2 = serialize($request->why_2);
        }
        if (!empty($request->why_3)) {
            $data3->why_3 = serialize($request->why_3);
        }
        if (!empty($request->why_4)) {
            $data3->why_4 = serialize($request->why_4);
        }

        if (!empty($request->why_5)) {
            $data3->why_5 = serialize($request->why_5);
        }
    //    dd($request->why_root_cause);
        if (!empty($request->why_root_cause)) {
            $data3->why_root_cause = $request->why_root_cause;
        }
        $data3->save();

        // --------------------------------------------
        $data4 = new RiskAssesmentGrid();
        $data4->risk_id = $data->id;
        $data4->type = "what_who_where";
        if (!empty($request->what_will_be)) {
            $data4->what_will_be = $request->what_will_be;
        }
        if (!empty($request->what_will_not_be)) {
            $data4->what_will_not_be = $request->what_will_not_be;
        }
        if (!empty($request->what_rationable)) {
            $data4->what_rationable = $request->what_rationable;
        }
        if (!empty($request->where_will_be)) {
            $data4->where_will_be = $request->where_will_be;
        }
        if (!empty($request->where_will_not_be)) {
            $data4->where_will_not_be = $request->where_will_not_be;
        }
        if (!empty($request->where_rationable)) {
            $data4->where_rationable = $request->where_rationable;
        }
        if (!empty($request->coverage_will_be)) {
            $data4->coverage_will_be = $request->coverage_will_be;
        }
        if (!empty($request->coverage_will_not_be)) {
            $data4->coverage_will_not_be = $request->coverage_will_not_be;
        }
        if (!empty($request->coverage_rationable)) {
            $data4->coverage_rationable = $request->coverage_rationable;
        }
        if (!empty($request->who_will_be)) {
            $data4->who_will_be = $request->who_will_be;
        }
        if (!empty($request->who_will_not_be)) {
            $data4->who_will_not_be = $request->who_will_not_be;
        }
        if (!empty($request->who_rationable)) {
            $data4->who_rationable = $request->who_rationable;
        } if (!empty($request->when_will_be)) {
            $data4->when_will_be = $request->when_will_be;
        }
         if (!empty($request->when_will_not_be)) {
            $data4->when_will_not_be = $request->when_will_not_be;
        }
         if (!empty($request->when_rationable)) {
            $data4->when_rationable = $request->when_rationable;
        }
        $data4->save();


        $data5 = new RiskAssesmentGrid();
        $data5->risk_id = $data->id;
        $data5->type = "Action_Plan";

        if (!empty($request->action)) {
            $data5->action = serialize($request->action);
        }
        if (!empty($request->responsible)) {
            $data5->responsible = serialize($request->responsible);
        }
        if (!empty($request->deadline)) {
            $data5->deadline = serialize($request->deadline);
        }
        if (!empty($request->item_static)) {
            $data5->item_static = serialize($request->item_static);
        }

        $data5->save();

        $data6 = new RiskAssesmentGrid();
        $data6->risk_id = $data->id;
        $data6->type = "Mitigation_Plan_Details";

        if (!empty($request->mitigation_steps)) {
            $data6->mitigation_steps = serialize($request->mitigation_steps);
        }
        if (!empty($request->deadline2)) {
            $data6->deadline2 = serialize($request->deadline2);
        }
        if (!empty($request->responsible_person)) {
            $data6->responsible_person = serialize($request->responsible_person);
        }
        if (!empty($request->status)) {
            $data6->status = serialize($request->status);
        }
        if (!empty($request->remark)) {
            $data6->remark = serialize($request->remark);
        }

        $data6->save();
        // ------------------------------------------------

        $history = new RiskAuditTrail();
        $history->risk_id = $data->id;
        $history->activity_type = 'Record Number';
        $history->previous = "Null";
        $history->current = Helpers::getDivisionName($data->division_id) . '/RCA/' . Helpers::year($data->created_at) . '/' . str_pad($data->record, 4, '0', STR_PAD_LEFT);
        $history->comment = "NA";
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $data->status;
        $history->save();


        $history = new RiskAuditTrail();
        $history->risk_id = $data->id;
        $history->activity_type = 'Site/Location Code';
        $history->previous = "Null";
        $history->current = $data->division_code;
        $history->comment = "NA";
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $data->status;
        $history->save();

        $history = new RiskAuditTrail();
        $history->risk_id = $data->id;
        $history->activity_type = 'Initiator';
        $history->previous = "Null";
        $history->current = Helpers::getInitiatorName($data->initiator_id);
        $history->comment = "NA";
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $data->status;
        $history->save();

        // if (!empty($data->intiation_date)) {
        //     $history = new RiskAuditTrail();
        //     $history->risk_id = $data->id;
        //     $history->activity_type = 'Date of Initiation';
        //     $history->previous = "Null";
        //     $history->current = Helpers::getdateFormat($data->intiation_date);
        //     $history->comment = "NA";
        //     $history->user_id = Auth::user()->id;
        //     $history->user_name = Auth::user()->name;
        //     $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        //     $history->origin_state = $data->status;
        //     $history->save();
        // }

        if (!empty($data->short_description)) {
            $history = new RiskAuditTrail();
            $history->risk_id = $data->id;
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
        // if (!empty($data->initiator_name)) {
        //     $history = new RiskAuditTrail();
        //     $history->risk_id = $data->id;
        //     $history->activity_type = 'Initiator';
        //     $history->previous = "Null";
        //     $history->current = $data->initiator_name;
        //     $history->comment = "NA";
        //     $history->user_id = Auth::user()->id;
        //     $history->user_name = Auth::user()->name;
        //     $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        //     $history->origin_state = $data->status;
        //     $history->save();
        // }
        // if (!empty($data->division_code)) {
        //     $history = new RiskAuditTrail();
        //     $history->risk_id = $data->id;
        //     $history->activity_type = 'Site/Location Code';
        //     $history->previous = "Null";
        //     $history->current = $data->division_code;
        //     $history->comment = "NA";
        //     $history->user_id = Auth::user()->id;
        //     $history->user_name = Auth::user()->name;
        //     $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        //     $history->origin_state = $data->status;
        //     $history->save();
        // }
        if (!empty($data->intiation_date)) {
            $history = new RiskAuditTrail();
            $history->risk_id = $data->id;
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
        if (!empty($data->severity2_level)) {
            $history = new RiskAuditTrail();
            $history->risk_id = $data->id;
            $history->activity_type = 'Severity Level';
            $history->previous = "Null";
            $history->current = $data->severity2_level;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->save();
        }
        if (!empty($data->Initiator_Group)) {
            $history = new RiskAuditTrail();
            $history->risk_id = $data->id;
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
        if (!empty($data->initiator_group_code)) {
            $history = new RiskAuditTrail();
            $history->risk_id = $data->id;
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

        if (!empty($data->open_date)) {
            $history = new RiskAuditTrail();
            $history->risk_id = $data->id;
            $history->activity_type = 'Open Date';
            $history->previous = "Null";
            $history->current = $data->open_date;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->save();
        }
        if (!empty($data->due_date)) {
            $history = new RiskAuditTrail();
            $history->risk_id = $data->id;
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

        if (!empty($data->assign_to)) {
            $history = new RiskAuditTrail();
            $history->risk_id = $data->id;
            $history->activity_type = 'Assigned To';
            $history->previous = "Null";
            $history->current = Helpers::getInitiatorName($data->assign_to);
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->save();
        }

        if (!empty($request->departments)) {
            $history = new RiskAuditTrail();
            $history->risk_id = $data->id;
            $history->activity_type = 'Department1';
            $history->previous = "Null";
            $history->current = $data->departments;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->save();
        }

        // if (!empty($data->team_members)) {
        //     $history = new RiskAuditTrail();
        //     $history->risk_id = $data->id;
        //     $history->activity_type = 'Team Members';
        //     $history->previous = "Null";
        //     $history->current = $data->team_members;
        //     $history->comment = "NA";
        //     $history->user_id = Auth::user()->id;
        //     $history->user_name = Auth::user()->name;
        //     $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        //     $history->origin_state = $data->status;
        //     $history->save();
        // }

        if (!empty($data->source_of_risk)) {
            $history = new RiskAuditTrail();
            $history->risk_id = $data->id;
            $history->activity_type = 'Source Of Risk';
            $history->previous = "Null";
            $history->current = $data->source_of_risk;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->save();
        }

        if (!empty($data->type)) {
            $history = new RiskAuditTrail();
            $history->risk_id = $data->id;
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

        if (!empty($data->priority_level)) {
            $history = new RiskAuditTrail();
            $history->risk_id = $data->id;
            $history->activity_type = 'Priority Level';
            $history->previous = "Null";
            $history->current = $data->priority_level;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->save();
        }

        if (!empty($data->zone)) {
            $history = new RiskAuditTrail();
            $history->risk_id = $data->id;
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
            $history = new RiskAuditTrail();
            $history->risk_id = $data->id;
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

        if (!empty($data->state)) {
            $history = new RiskAuditTrail();
            $history->risk_id = $data->id;
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

        if (!empty($data->city)) {
            $history = new RiskAuditTrail();
            $history->risk_id = $data->id;
            $history->activity_type = 'City';
            $history->previous = "Null";
            $history->current = $data->city;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->save();
        }

        if (!empty($data->description)) {
            $history = new RiskAuditTrail();
            $history->risk_id = $data->id;
            $history->activity_type = 'Risk/Opportunity Description ';
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
            $history = new RiskAuditTrail();
            $history->risk_id = $data->id;
            $history->activity_type = 'Risk/Opportunity Comments';
            $history->previous = "Null";
            $history->current = $data->comments;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->save();
        }

        if (!empty($data->departments2)) {
            $history = new RiskAuditTrail();
            $history->risk_id = $data->id;
            $history->activity_type = 'Department2';
            $history->previous = "Null";
            $history->current = $data->departments2;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->save();
        }
        if (!empty($data->source_of_risk2)) {
            $history = new RiskAuditTrail();
            $history->risk_id = $data->id;
            $history->activity_type = 'Source of Risk';
            $history->previous = "Null";
            $history->current = $data->source_of_risk2;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->save();
        }

        if (!empty($data->site_name)) {
            $history = new RiskAuditTrail();
            $history->risk_id = $data->id;
            $history->activity_type = 'Site Name';
            $history->previous = "Null";
            $history->current = $data->site_name;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->save();
        }

        if (!empty($data->building)) {
            $history = new RiskAuditTrail();
            $history->risk_id = $data->id;
            $history->activity_type = 'Building';
            $history->previous = "Null";
            $history->current = $data->building;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->save();
        }

        if (!empty($data->floor)) {
            $history = new RiskAuditTrail();
            $history->risk_id = $data->id;
            $history->activity_type = 'Floor';
            $history->previous = "Null";
            $history->current = $data->floor;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->save();
        }

        if (!empty($data->room)) {
            $history = new RiskAuditTrail();
            $history->risk_id = $data->id;
            $history->activity_type = 'Room';
            $history->previous = "Null";
            $history->current = $data->room;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->save();
        }

        if (!empty($data->duration)) {
            $history = new RiskAuditTrail();
            $history->risk_id = $data->id;
            $history->activity_type = 'Duration';
            $history->previous = "Null";
            $history->current = $data->duration;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->save();
        }

        if (!empty($data->hazard)) {
            $history = new RiskAuditTrail();
            $history->risk_id = $data->id;
            $history->activity_type = 'Hazard';
            $history->previous = "Null";
            $history->current = $data->hazard;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->save();
        }

        if (!empty($data->room2)) {
            $history = new RiskAuditTrail();
            $history->risk_id = $data->id;
            $history->activity_type = 'Room2';
            $history->previous = "Null";
            $history->current = $data->room2;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->save();
        }

        if (!empty($data->regulatory_climate)) {
            $history = new RiskAuditTrail();
            $history->risk_id = $data->id;
            $history->activity_type = 'Regulatory Climate';
            $history->previous = "Null";
            $history->current = $data->regulatory_climate;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->save();
        }

        if (!empty($data->Number_of_employees)) {
            $history = new RiskAuditTrail();
            $history->risk_id = $data->id;
            $history->activity_type = 'Number Of Employees';
            $history->previous = "Null";
            $history->current = $data->Number_of_employees;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->save();
        }
        if (!empty($data->schedule_start_date1)) {
            $history = new RiskAuditTrail();
            $history->risk_id = $data->id;
            $history->activity_type = 'Scheduled Start Date';
            $history->previous = "Null";
            $history->current = Helpers::getdateFormat($data->schedule_start_date1);
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->save();
        }
        if (!empty($data->schedule_end_date)) {
            $history = new RiskAuditTrail();
            $history->risk_id = $data->id;
            $history->activity_type = 'Scheduled End Date';
            $history->previous = "Null";
            $history->current = Helpers::getdateFormat($data->schedule_end_date);
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->save();
        }
        // if (!empty($data->estimated_man_hours)) {
        //     $history = new RiskAuditTrail();
        //     $history->risk_id = $data->id;
        //     $history->activity_type = 'Estimated Man-Hours';
        //     $history->previous = "Null";
        //     $history->current = $data->estimated_man_hours;
        //     $history->comment = "NA";
        //     $history->user_id = Auth::user()->id;
        //     $history->user_name = Auth::user()->name;
        //     $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        //     $history->origin_state = $data->status;
        //     $history->save();
        // }

        // if (!empty($internalAudit->refrence_record)) {
        //     $history = new RiskAuditTrail();
        //     $history->risk_id = $data->id;
        //     $history->activity_type = 'Reference Recores';
        //     $history->previous = "Null";
        //     $history->current = $data->refrence_record;
        //     $history->comment = "NA";
        //     $history->user_id = Auth::user()->id;
        //     $history->user_name = Auth::user()->name;
        //     $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        //     $history->origin_state = $data->status;
        //     $history->save();
        // }

        if (!empty($data->risk_management_strategy)) {
            $history = new RiskAuditTrail();
            $history->risk_id = $data->id;
            $history->activity_type = 'Risk Management Strategy';
            $history->previous = "Null";
            $history->current = $data->risk_management_strategy;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->save();
        }

        if (!empty($data->estimated_man_hours)) {
            $history = new RiskAuditTrail();
            $history->risk_id = $data->id;
            $history->activity_type = 'Estimated  man  Hours';
            $history->previous = "Null";
            $history->current = $data->estimated_man_hours;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->save();
        }

        if (!empty($data->estimated_cost)) {
            $history = new RiskAuditTrail();
            $history->risk_id = $data->id;
            $history->activity_type = 'Estimated Cost';
            $history->previous = "Null";
            $history->current = $data->estimated_cost;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->save();
        }

        if (!empty($data->currency)) {
            $history = new RiskAuditTrail();
            $history->risk_id = $data->id;
            $history->activity_type = 'Currency';
            $history->previous = "Null";
            $history->current = $data->currency;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->save();
        }
        if (!empty($data->justification)) {
            $history = new RiskAuditTrail();
            $history->risk_id = $data->id;
            $history->activity_type = 'Justification / Rationale';
            $history->previous = "Null";
            $history->current = $data->justification;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->save();
        }
        if (!empty($data->root_cause_methodology)) {
            $history = new RiskAuditTrail();
            $history->risk_id = $data->id;
            $history->activity_type = 'Root Cause Methodology';
            $history->previous = "Null";
            $history->current = $data->root_cause_methodology;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->save();
        }

        if (!empty($data->training_require)) {
            $history = new RiskAuditTrail();
            $history->risk_id = $data->id;
            $history->activity_type = 'Training Require';
            $history->previous = "Null";
            $history->current = $data->training_require;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->save();
        }

        if (!empty($data->justification)) {
            $history = new RiskAuditTrail();
            $history->risk_id = $data->id;
            $history->activity_type = 'Justification';
            $history->previous = "Null";
            $history->current = $data->justification;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->save();
        }

        if (!empty($data->reference)) {
            $history = new RiskAuditTrail();
            $history->risk_id = $data->id;
            $history->activity_type = 'Work Group Attachments';
            $history->previous = "Null";
            $history->current = $data->reference;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->save();
        }

        if (!empty($data->attachment)) {
            $history = new RiskAuditTrail();
            $history->risk_id = $data->id;
            $history->activity_type = 'General Attachments';
            $history->previous = "Null";
            $history->current = $data->attachment;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->save();
        }

        // if (!empty($data->reference)) {
        //     $history = new RiskAuditTrail();
        //     $history->risk_id = $data->id;
        //     $history->activity_type = 'Work Group Attachments';
        //     $history->previous = "Null";
        //     $history->current = $data->reference;
        //     $history->comment = "NA";
        //     $history->user_id = Auth::user()->id;
        //     $history->user_name = Auth::user()->name;
        //     $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        //     $history->origin_state = $data->status;
        //     $history->save();
        // }


        if (!empty($data->root_cause_description)) {
            $history = new RiskAuditTrail();
            $history->risk_id = $data->id;
            $history->activity_type = 'Root Cause Description';
            $history->previous = "Null";
            $history->current = $data->root_cause_description;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->save();
        }
        if (!empty($data->investigation_summary)) {
            $history = new RiskAuditTrail();
            $history->risk_id = $data->id;
            $history->activity_type = 'Investigation Summary';
            $history->previous = "Null";
            $history->current = $data->investigation_summary;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->save();
        }


        if (!empty($data->cost_of_risk)) {
            $history = new RiskAuditTrail();
            $history->risk_id = $data->id;
            $history->activity_type = 'Cost Of Risk';
            $history->previous = "Null";
            $history->current = $data->cost_of_risk;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->save();
        }

        if (!empty($data->environmental_impact)) {
            $history = new RiskAuditTrail();
            $history->risk_id = $data->id;
            $history->activity_type = 'Environmental Impact';
            $history->previous = "Null";
            $history->current = $data->environmental_impact;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->save();
        }
        if (!empty($data->criticality)) {
            $history = new RiskAuditTrail();
            $history->risk_id = $data->id;
            $history->activity_type = 'Criticality';
            $history->previous = "Null";
            $history->current = $data->criticality;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->save();
        }
        if (!empty($data->impact_analysis)) {
            $history = new RiskAuditTrail();
            $history->risk_id = $data->id;
            $history->activity_type = 'Impact Analysis';
            $history->previous = "Null";
            $history->current = $data->impact_analysis;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->save();
        }
        if (!empty($data->risk_analysis)) {
            $history = new RiskAuditTrail();
            $history->risk_id = $data->id;
            $history->activity_type = 'Risk Analysis';
            $history->previous = "Null";
            $history->current = $data->risk_analysis;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->save();
        }
        if (!empty($data->refrence_record)) {
            $history = new RiskAuditTrail();
            $history->risk_id = $data->id;
            $history->activity_type = 'Reference Record';
            $history->previous = "Null";
            $history->current = $data->refrence_record;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->save();
        }
        if (!empty($data->due_date_extension)) {
            $history = new RiskAuditTrail();
            $history->risk_id = $data->id;
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

        if (!empty($data->public_perception_impact)) {
            $history = new RiskAuditTrail();
            $history->risk_id = $data->id;
            $history->activity_type = 'Public Perception Impact';
            $history->previous = "Null";
            $history->current = $data->public_perception_impact;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->save();
        }

        if (!empty($data->calculated_risk)) {
            $history = new RiskAuditTrail();
            $history->risk_id = $data->id;
            $history->activity_type = 'Calculated Risk';
            $history->previous = "Null";
            $history->current = $data->calculated_risk;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->save();
        }

        if (!empty($data->impacted_objects)) {
            $history = new RiskAuditTrail();
            $history->risk_id = $data->id;
            $history->activity_type = 'Impacted Objects';
            $history->previous = "Null";
            $history->current = $data->impacted_objects;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->save();
        }

        // if (!empty($data->severity_rate)) {
        //     $history = new RiskAuditTrail();
        //     $history->risk_id = $data->id;
        //     $history->activity_type = 'Severity Rate';
        //     $history->previous = "Null";
        //     $history->current = $data->severity_rate;
        //     $history->comment = "NA";
        //     $history->user_id = Auth::user()->id;
        //     $history->user_name = Auth::user()->name;
        //     $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        //     $history->origin_state = $data->status;
        //     $history->save();
        // }

        if(!empty($request->severity_rate)){
            $history = new RiskAuditTrail;
            $history->risk_id = $data->id;
            $history->activity_type = 'Severity Rate';
            $history->previous = "Null";

            if($request->severity_rate == 1){
                $history->current = "Negligible";
            } elseif($request->severity_rate == 2){
                $history->current = "Moderate";
            } elseif($request->severity_rate == 3){
                $history->current = "Major";
            } elseif($request->severity_rate == 4){
                $history->current = "Fatal";
            }

            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->save();
        }

        // if (!empty($data->occurrence)) {
        //     $history = new RiskAuditTrail();
        //     $history->risk_id = $data->id;
        //     $history->activity_type = 'Occurrence';
        //     $history->previous = "Null";
        //     $history->current = $data->occurrence;
        //     $history->comment = "NA";
        //     $history->user_id = Auth::user()->id;
        //     $history->user_name = Auth::user()->name;
        //     $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        //     $history->origin_state = $data->status;
        //     $history->save();
        // }/

        if(!empty($request->occurrence)){
            $history = new RiskAuditTrail;
            $history->risk_id = $data->id;
            $history->activity_type = 'Occurrence';
            $history->previous = "Null";

            if($request->occurrence == 1){
                $history->current = "Very Likely";
            } elseif($request->occurrence == 2){
                $history->current = "Likely";
            } elseif($request->occurrence == 3){
                $history->current = "Unlikely";
            } elseif($request->occurrence == 4){
                $history->current = "Rare";
            }elseif($request->occurrence == 5){
                $history->current = "Extremely Unlikely";
            }

            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->save();
        }

        // if (!empty($data->detection)) {
        //     $history = new RiskAuditTrail();
        //     $history->risk_id = $data->id;
        //     $history->activity_type = 'Detection';
        //     $history->previous = "Null";
        //     $history->current = $data->detection;
        //     $history->comment = "NA";
        //     $history->user_id = Auth::user()->id;
        //     $history->user_name = Auth::user()->name;
        //     $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        //     $history->origin_state = $data->status;
        //     $history->save();
        // }

        if(!empty($request->detection)){
            $history = new RiskAuditTrail;
            $history->risk_id = $data->id;
            $history->activity_type = 'Detection';
            $history->previous = "Null";

            if($request->detection == 2){
                $history->current = "Likely";
            } elseif($request->detection == 3){
                $history->current = "Unlikely";
            } elseif($request->detection == 4){
                $history->current = "Rare";
            } elseif($request->detection == 5){
                $history->current = "Impossible";
            }

            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->save();
        }

        if (!empty($data->rpn)) {
            $history = new RiskAuditTrail();
            $history->risk_id = $data->id;
            $history->activity_type = 'Rpn';
            $history->previous = "Null";
            $history->current = $data->rpn;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->save();
        }

        if (!empty($data->residual_risk)) {
            $history = new RiskAuditTrail();
            $history->risk_id = $data->id;
            $history->activity_type = 'Residual Risk';
            $history->previous = "Null";
            $history->current = $data->residual_risk;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->save();
        }

        // if (!empty($data->residual_risk_impact)) {
        //     $history = new RiskAuditTrail();
        //     $history->risk_id = $data->id;
        //     $history->activity_type = 'Residual Risk Impact';
        //     $history->previous = "Null";
        //     $history->current = $data->residual_risk_impact;
        //     $history->comment = "NA";
        //     $history->user_id = Auth::user()->id;
        //     $history->user_name = Auth::user()->name;
        //     $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        //     $history->origin_state = $data->status;
        //     $history->save();
        // }

        if(!empty($request->residual_risk_impact)){
            $history = new RiskAuditTrail;
            $history->risk_id = $data->id;
            $history->activity_type = 'Residual Risk Impact  ';
            $history->previous = "Null";

            if($request->residual_risk_impact == 1){
                $history->current = "High";
            } elseif($request->residual_risk_impact == 2){
                $history->current = "Medium";
            } elseif($request->residual_risk_impact == 3){
                $history->current = "Low";
            }

            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->save();
        }

        // if (!empty($data->residual_risk_probability)) {
        //     $history = new RiskAuditTrail();
        //     $history->risk_id = $data->id;
        //     $history->activity_type = 'Residual Risk Probability';
        //     $history->previous = "Null";
        //     $history->current = $data->residual_risk_probability;
        //     $history->comment = "NA";
        //     $history->user_id = Auth::user()->id;
        //     $history->user_name = Auth::user()->name;
        //     $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        //     $history->origin_state = $data->status;
        //     $history->save();
        // }

        if(!empty($request->residual_risk_probability)){
            $history = new RiskAuditTrail;
            $history->risk_id = $data->id;
            $history->activity_type = 'Residual Risk Probability';
            $history->previous = "Null";

            if($request->residual_risk_probability == 1){
                $history->current = "High";
            } elseif($request->residual_risk_probability == 2){
                $history->current = "Medium";
            } elseif($request->residual_risk_probability == 3){
                $history->current = "Low";
            }

            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->save();
        }



        // if (!empty($data->detection2)) {
        //     $history = new RiskAuditTrail();
        //     $history->risk_id = $data->id;
        //     $history->activity_type = 'Residual Detection';
        //     $history->previous = "Null";
        //     $history->current = $data->detection2;
        //     $history->comment = "NA";
        //     $history->user_id = Auth::user()->id;
        //     $history->user_name = Auth::user()->name;
        //     $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        //     $history->origin_state = $data->status;
        //     $history->save();
        // }

        if(!empty($request->detection2)){
            $history = new RiskAuditTrail;
            $history->risk_id = $data->id;
            $history->activity_type = 'Residual Detection';
            $history->previous = "Null";

            if($request->detection2 == 1){
                $history->current = "Very Likely";
            } elseif($request->detection2 == 2){
                $history->current = "Likely";
            } elseif($request->detection2 == 3){
                $history->current = "Unlikely";
            } elseif($request->detection2 == 4){
                $history->current = "Rare";
            }elseif($request->detection2 == 5){
                $history->current = "Impossible";
            }

            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->save();
        }


        if (!empty($data->rpn2)) {
            $history = new RiskAuditTrail();
            $history->risk_id = $data->id;
            $history->activity_type = 'Residual RPN';
            $history->previous = "Null";
            $history->current = $data->rpn2;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->save();
        }
        if (!empty($data->mitigation_required)) {
            $history = new RiskAuditTrail();
            $history->risk_id = $data->id;
            $history->activity_type = 'Mitigation Required';
            $history->previous = "Null";
            $history->current = $data->mitigation_required;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->save();
        }
        if (!empty($data->mitigation_plan)) {
            $history = new RiskAuditTrail();
            $history->risk_id = $data->id;
            $history->activity_type = 'Mitigation Plan';
            $history->previous = "Null";
            $history->current = $data->mitigation_plan;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->save();
        }
        if (!empty($data->mitigation_status)) {
            $history = new RiskAuditTrail();
            $history->risk_id = $data->id;
            $history->activity_type = 'Status of Mitigation';
            $history->previous = "Null";
            $history->current = $data->mitigation_status;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->save();
        }
        if (!empty($data->mitigation_status_comments)) {
            $history = new RiskAuditTrail();
            $history->risk_id = $data->id;
            $history->activity_type = 'Mitigation Status Comments';
            $history->previous = "Null";
            $history->current = $data->mitigation_status_comments;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->save();
        }
        if (!empty($data->impact)) {
            $history = new RiskAuditTrail();
            $history->risk_id = $data->id;
            $history->activity_type = 'Impact';
            $history->previous = "Null";
            $history->current = $data->mitigation_status_comments;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->save();
        }


        if (!empty($data->comments2)) {
            $history = new RiskAuditTrail();
            $history->risk_id = $data->id;
            $history->activity_type = 'Comments';
            $history->previous = "Null";
            $history->current = $data->comments2;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $data->status;
            $history->save();
        }
        if (!empty($data->mitigation_due_date)) {
            $history = new RiskAuditTrail();
            $history->risk_id = $data->id;
            $history->activity_type = 'Scheduled End Date';
            $history->previous = "Null";
            $history->current = Helpers::getdateFormat($data->mitigation_due_date);
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
    public function riskUpdate(Request $request, $id)
    {

        if (!$request->short_description) {
            toastr()->info("Short Description is required");
            return redirect()->back()->withInput();
        }

        $lastDocument =  RiskManagement::find($id);
        $data =  RiskManagement::find($id);
        $data->division_code = $request->division_code;
        //$data->record_number = $request->record_number;
        $data->short_description = $request->short_description;
        $data->open_date = $request->open_date;
        $data->assign_to = $request->assign_to;
        $data->due_date = $request->due_date;
        $data->Initiator_Group = $request->Initiator_Group;
        $data->initiator_group_code = $request->initiator_group_code;
        $data->departments = implode(',', $request->departments);
        // $data->team_members = implode(',', $request->team_members);
        $data->source_of_risk = $request->source_of_risk;
        $data->source_of_risk2 = $request->source_of_risk2;
        $data->type = $request->type;
        $data->priority_level = $request->priority_level;
        $data->zone = $request->zone;
        $data->country = $request->country;
        $data->state = $request->state;
        $data->city = $request->city;
        $data->description = $request->description;
        $data->severity2_level = $request->severity2_level;
        $data->comments = $request->comments;
        $data->departments2 = implode(',', $request->departments2);
        $data->site_name = $request->site_name;
        $data->building = $request->building;
        $data->floor = $request->floor;
        $data->room = $request->room;
        $data->related_record = json_encode($request->related_record);
        $data->duration = $request->duration;
        $data->hazard = $request->hazard;
        $data->room2 = $request->room2;
        $data->regulatory_climate = $request->regulatory_climate;
        $data->Number_of_employees = $request->Number_of_employees;
        $data->risk_management_strategy = $request->risk_management_strategy;
        $data->estimated_man_hours = $request->estimated_man_hours;
        $data->schedule_start_date1 = $request->schedule_start_date1;
        $data->schedule_end_date1 = $request->schedule_end_date1;
        $data->estimated_cost = $request->estimated_cost;
        $data->currency = $request->currency;

        $data->root_cause_methodology = implode(',', $request->root_cause_methodology);
        //$data->training_require = $request->training_require;
        $data->justification = $request->justification;
        $data->cost_of_risk = $request->cost_of_risk;
        $data->environmental_impact = $request->environmental_impact;
        $data->public_perception_impact = $request->public_perception_impact;
        $data->calculated_risk = $request->calculated_risk;
        $data->impacted_objects = $request->impacted_objects;
        $data->severity_rate = $request->severity_rate;
        $data->occurrence = $request->occurrence;
        $data->detection = $request->detection;
        $data->detection2 = $request->detection2;
        $data->rpn = $request->rpn;
        $data->residual_risk = $request->residual_risk;
        $data->residual_risk_impact = $request->residual_risk_impact;
        $data->residual_risk_probability = $request->residual_risk_probability;
        $data->analysisN2 = $request->analysisN2;
        $data->analysisRPN2 = $request->analysisRPN2;
        $data->rpn2 = $request->rpn2;
        $data->comments2 = $request->comments2;
        $data->root_cause_description = $request->root_cause_description;
        $data->investigation_summary = $request->investigation_summary;
        $data->mitigation_required = $request->mitigation_required;
        $data->mitigation_plan = $request->mitigation_plan;
        $data->mitigation_due_date = $request->mitigation_due_date;
        $data->mitigation_status = $request->mitigation_status;
        $data->mitigation_status_comments = $request->mitigation_status_comments;
        $data->impact = $request->impact;
        $data->criticality = $request->criticality;
        $data->impact_analysis = $request->impact_analysis;
        $data->risk_analysis = $request->risk_analysis;
        $data->due_date_extension = $request->due_date_extension;
        //$data->severity = $request->severity;
        //$data->occurance = $request->occurance;
        $data->refrence_record =  implode(',', $request->refrence_record);


        if (!empty($request->reference)) {
            $files = [];
            if ($request->hasfile('reference')) {
                foreach ($request->file('reference') as $file) {
                    $name = $request->name . 'reference' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }


            $data->reference = json_encode($files);
        }

        if (!empty($request->attachment)) {
            $files = [];
            if ($request->hasfile('attachment')) {
                foreach ($request->file('attachment') as $file) {
                    $name = $request->name . 'attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }


            $data->attachment = json_encode($files);
        }
        // return $data;
        $data->update();
             // -----------grid=------
            //  $data1 = new RiskAssesmentGrid();
            //  $data1->risk_id = $data->id;
            //  $data1->type = "effect_analysis";

             $data1 = RiskAssesmentGrid::where('risk_id',$data->id)->where('type','effect_analysis')->first();

             if (!empty($request->risk_factor)) {
                 $data1->risk_factor = serialize($request->risk_factor);
             }
             if (!empty($request->risk_element)) {
                 $data1->risk_element = serialize($request->risk_element);
             }
             if (!empty($request->problem_cause)) {
                 $data1->problem_cause = serialize($request->problem_cause);
             }
             if (!empty($request->existing_risk_control)) {
                 $data1->existing_risk_control = serialize($request->existing_risk_control);
             }
             if (!empty($request->initial_severity)) {
                 $data1->initial_severity = serialize($request->initial_severity);
             }
             if (!empty($request->initial_detectability)) {
                 $data1->initial_detectability = serialize($request->initial_detectability);
             }
             if (!empty($request->initial_probability)) {
                 $data1->initial_probability = serialize($request->initial_probability);
             }
             if (!empty($request->initial_rpn)) {
                 $data1->initial_rpn = serialize($request->initial_rpn);
             }
             if (!empty($request->risk_acceptance)) {
                 $data1->risk_acceptance = serialize($request->risk_acceptance);
             }
             if (!empty($request->risk_control_measure)) {
                 $data1->risk_control_measure = serialize($request->risk_control_measure);
             }
             if (!empty($request->residual_severity)) {
                 $data1->residual_severity = serialize($request->residual_severity);
             }
             if (!empty($request->residual_probability)) {
                 $data1->residual_probability = serialize($request->residual_probability);
             }
             if (!empty($request->residual_detectability)) {
                 $data1->residual_detectability = serialize($request->residual_detectability);
             }
             if (!empty($request->residual_rpn)) {
                 $data1->residual_rpn = serialize($request->residual_rpn);
             }
             if (!empty($request->risk_acceptance2)) {
                 $data1->risk_acceptance2 = serialize($request->risk_acceptance2);
             }
             if (!empty($request->mitigation_proposal)) {
                 $data1->mitigation_proposal = serialize($request->mitigation_proposal);
             }

             $data1->save();

             // ---------------------------------------
            //  $data2 = new RiskAssesmentGrid();
            //  $data2->risk_id = $data->id;
            //  $data2->type = "fishbone";
                 $data2 = RiskAssesmentGrid::where('risk_id',$data->id)->where('type','fishbone')->first();

             if (!empty($request->measurement)) {
                 $data2->measurement = serialize($request->measurement);
             }
             if (!empty($request->materials)) {
                 $data2->materials = serialize($request->materials);
             }
             if (!empty($request->methods)) {
                 $data2->methods = serialize($request->methods);
             }
             if (!empty($request->environment)) {
                 $data2->environment = serialize($request->environment);
             }
             if (!empty($request->manpower)) {
                 $data2->manpower = serialize($request->manpower);
             }
             if (!empty($request->machine)) {
                 $data2->machine = serialize($request->machine);
             }
             if (!empty($request->problem_statement)) {
                 $data2->problem_statement = $request->problem_statement;
             }
             $data2->save();
             // =-------------------------------
               $data3 = RiskAssesmentGrid::where('risk_id',$data->id)->where('type','why_chart')->first();
            //  $data3 = new RiskAssesmentGrid();
            //  $data3->risk_id = $data->id;
            //  $data3->type = "why_chart";

             if (!empty($request->why_problem_statement)) {
                 $data3->why_problem_statement = $request->why_problem_statement;
             }
             if (!empty($request->why_1)) {
                 $data3->why_1 = serialize($request->why_1);
             }
             if (!empty($request->why_2)) {
                 $data3->why_2 = serialize($request->why_2);
             }
             if (!empty($request->why_3)) {
                 $data3->why_3 = serialize($request->why_3);
             }
             if (!empty($request->why_4)) {
                 $data3->why_4 = serialize($request->why_4);
             }
             if (!empty($request->why_5)) {
                 $data3->why_5 = serialize($request->why_5);
             }
             if (!empty($request->why_root_cause)) {
                 $data3->why_root_cause = $request->why_root_cause;
             }
             $data3->save();

             // --------------------------------------------
            //  $data4 = new RiskAssesmentGrid();
            //  $data4->risk_id = $data->id;
            //  $data4->type = "what_who_where";
              $data4 = RiskAssesmentGrid::where('risk_id',$data->id)->where('type','what_who_where')->first();

             if (!empty($request->what_will_be)) {
                 $data4->what_will_be = $request->what_will_be;
             }
             if (!empty($request->what_will_not_be)) {
                 $data4->what_will_not_be = $request->what_will_not_be;
             }
             if (!empty($request->what_rationable)) {
                 $data4->what_rationable = $request->what_rationable;
             }
             if (!empty($request->where_will_be)) {
                 $data4->where_will_be = $request->where_will_be;
             }
             if (!empty($request->where_will_not_be)) {
                 $data4->where_will_not_be = $request->where_will_not_be;
             }
             if (!empty($request->where_rationable)) {
                 $data4->where_rationable = $request->where_rationable;
             }
             if (!empty($request->coverage_will_be)) {
                 $data4->coverage_will_be = $request->coverage_will_be;
             }
             if (!empty($request->coverage_will_not_be)) {
                 $data4->coverage_will_not_be = $request->coverage_will_not_be;
             }
             if (!empty($request->coverage_rationable)) {
                 $data4->coverage_rationable = $request->coverage_rationable;
             }
             if (!empty($request->who_will_be)) {
                 $data4->who_will_be = $request->who_will_be;
             }
             if (!empty($request->who_will_not_be)) {
                 $data4->who_will_not_be = $request->who_will_not_be;
             }
             if (!empty($request->who_rationable)) {
                 $data4->who_rationable = $request->who_rationable;
             } if (!empty($request->when_will_be)) {
                 $data4->when_will_be = $request->when_will_be;
             }
              if (!empty($request->when_will_not_be)) {
                 $data4->when_will_not_be = $request->when_will_not_be;
             }
              if (!empty($request->when_rationable)) {
                 $data4->when_rationable = $request->when_rationable;
             }
             $data4->save();

      $data5 = RiskAssesmentGrid::where('risk_id',$data->id)->where('type','Action_Plan')->first();
            //  $data5 = new RiskAssesmentGrid();
            //  $data5->risk_id = $data->id;
            //  $data5->type = "Action_Plan";

             if (!empty($request->action)) {
                 $data5->action = serialize($request->action);
             }
             if (!empty($request->responsible)) {
                 $data5->responsible = serialize($request->responsible);
             }
             if (!empty($request->deadline)) {
                 $data5->deadline = serialize($request->deadline);
             }
             if (!empty($request->item_static)) {
                 $data5->item_static = serialize($request->item_static);
             }

             $data5->save();

            //  $data6 = new RiskAssesmentGrid();
            //  $data6->risk_id = $data->id;
            //  $data6->type = "Mitigation_Plan_Details";
              $data6 = RiskAssesmentGrid::where('risk_id',$data->id)->where('type','Mitigation_Plan_Details')->first();
             if (!empty($request->mitigation_steps)) {
                 $data6->mitigation_steps = serialize($request->mitigation_steps);
             }
             if (!empty($request->deadline2)) {
                 $data6->deadline2 = serialize($request->deadline2);
             }
             if (!empty($request->responsible_person)) {
                 $data6->responsible_person = serialize($request->responsible_person);
             }
             if (!empty($request->status)) {
                 $data6->status = serialize($request->status);
             }
             if (!empty($request->remark)) {
                 $data6->remark = serialize($request->remark);
             }

             $data6->save();

             if ($lastDocument->division_id != $data->division_id || !empty($request->record_comment)) {

                $history = new RiskAuditTrail();
                $history->risk_id = $id;
                $history->activity_type = 'Record Number';
                $history->previous = $lastDocument->record;
                $history->current = Helpers::getDivisionName($data->division_id) . '/RCA/' . Helpers::year($data->created_at) . '/' . str_pad($data->record, 4, '0', STR_PAD_LEFT);
                $history->comment = $request->record_comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                $history->save();
            }
            if ($lastDocument->division_code != $data->division_code || !empty($request->division_code_comment)) {

                $history = new RiskAuditTrail();
                $history->risk_id = $id;
                $history->activity_type = 'Site/Location Code';
                $history->previous = $lastDocument->division_code;
                $history->current = $data->division_code;
                $history->comment = $request->division_code_comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                $history->save();
            }
            if ($lastDocument->initiator_id != $data->initiator_id || !empty($request->initiator_name_comment)) {

                $history = new RiskAuditTrial();
                $history->risk_id = $id;
                $history->activity_type = 'Initiator';
                $history->previous = Helpers::getInitiatorName($lastDocument->initiator_id);
                $history->current = Helpers::getInitiatorName($data->initiator_id);
                $history->comment = $request->initiator_name_comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                $history->save();
            }

        if ($lastDocument->intiation_date != $data->intiation_date || !empty($request->intiation_date_comment)) {

            $history = new RiskAuditTrail();
            $history->data = $id;
            $history->activity_type = 'Date of Initiation';
            $history->previous = Helpers::getdateFormat($lastDocument->intiation_date);
            $history->current = Helpers::getdateFormat($data->intiation_date);
            $history->comment = $request->intiation_date_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }



        if ($lastDocument->short_description != $data->short_description || !empty($request->short_description_comment)) {

            $history = new RiskAuditTrail();
            $history->risk_id = $id;
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
        if ($lastDocument->severity2_level != $data->severity2_level || !empty($request->severity2_level_comment)) {

            $history = new RiskAuditTrail();
            $history->risk_id = $id;
            $history->activity_type = 'Severity Level';
            $history->previous = $lastDocument->severity2_level;
            $history->current = $data->severity2_level;
            $history->comment = $request->short_description_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->Initiator_Group != $data->Initiator_Group || !empty($request->Initiator_Group_comment)) {

            $history = new RiskAuditTrail();
            $history->risk_id = $id;
            $history->activity_type = 'Initiator Group';
            $history->previous = Helpers::getInitiatorGroupFullName($lastDocument->Initiator_Group);
            $history->current = Helpers::getInitiatorGroupFullName($data->Initiator_Group);
            $history->comment = $request->Initiator_Group_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->initiator_group_code != $data->initiator_group_code || !empty($request->initiator_group_code_comment)) {

            $history = new RiskAuditTrail();
            $history->risk_id = $id;
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

        if ($lastDocument->open_date != $data->open_date || !empty($request->open_date_comment)) {

            $history = new RiskAuditTrail();
            $history->risk_id = $id;
            $history->activity_type = 'Open Date';
            $history->previous = $lastDocument->open_date;
            $history->current = $data->open_date;
            $history->comment = $request->open_date_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }

        if ($lastDocument->due_date != $data->due_date || !empty($request->due_date_comment)) {

            $history = new RiskAuditTrail();
            $history->risk_id = $id;
            $history->activity_type = 'Due Date';
            $history->previous = Helpers::getdateFormat($lastDocument->due_date);
            $history->current = Helpers::getdateFormat($data->due_date);
            $history->comment = $request->due_date_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }


        if ($lastDocument->assign_to != $data->assign_to || !empty($request->assign_to_comment)) {

            $history = new RiskAuditTrail();
            $history->risk_id = $id;
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
        if ($lastDocument->departments != $data->departments || !empty($request->departments_comment)) {
            $history = new RiskAuditTrail();
            $history->risk_id = $id;
            $history->activity_type = 'Department1';
            $history->previous = $lastDocument->departments;
            $history->current = $data->departments;
            $history->comment = $request->departments_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }

        // if ($lastDocument->team_members != $data->team_members || !empty($request->team_members_comment)) {

        //     $history = new RiskAuditTrail();
        //     $history->risk_id = $id;
        //     $history->activity_type = 'Team Members';
        //     $history->previous = $lastDocument->team_members;
        //     $history->current = $data->team_members;
        //     $history->comment = $request->team_members_comment;
        //     $history->user_id = Auth::user()->id;
        //     $history->user_name = Auth::user()->name;
        //     $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        //     $history->origin_state = $lastDocument->status;
        //     $history->save();
        // }

        if ($lastDocument->source_of_risk != $data->source_of_risk || !empty($request->source_of_risk_comment)) {

            $history = new RiskAuditTrail();
            $history->risk_id = $id;
            $history->activity_type = 'Source Of Risk';
            $history->previous = $lastDocument->source_of_risk;
            $history->current = $data->source_of_risk;
            $history->comment = $request->source_of_risk_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }

        if ($lastDocument->type != $data->type || !empty($request->type_comment)) {

            $history = new RiskAuditTrail();
            $history->risk_id = $id;
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

        if ($lastDocument->priority_level != $data->priority_level || !empty($request->priority_level_comment)) {

            $history = new RiskAuditTrail();
            $history->risk_id = $id;
            $history->activity_type = 'Priority Level';
            $history->previous = $lastDocument->priority_level;
            $history->current = $data->priority_level;
            $history->comment = $request->priority_level_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }

        if ($lastDocument->zone != $data->zone || !empty($request->zone_comment)) {

            $history = new RiskAuditTrail();
            $history->risk_id = $id;
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

            $history = new RiskAuditTrail();
            $history->risk_id = $id;
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

        if ($lastDocument->state != $data->state || !empty($request->state_comment)) {

            $history = new RiskAuditTrail();
            $history->risk_id = $id;
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

        if ($lastDocument->city != $data->city || !empty($request->city_comment)) {

            $history = new RiskAuditTrail();
            $history->risk_id = $id;
            $history->activity_type = 'City';
            $history->previous = $lastDocument->city;
            $history->current = $data->city;
            $history->comment = $request->city_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }

        if ($lastDocument->description != $data->description || !empty($request->description_comment)) {

            $history = new RiskAuditTrail();
            $history->risk_id = $id;
            $history->activity_type = 'Risk/Opportunity Description ';
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

            $history = new RiskAuditTrail();
            $history->risk_id = $id;
            $history->activity_type = 'Risk/Opportunity Comment';
            $history->previous = $lastDocument->comments;
            $history->current = $data->comments;
            $history->comment = $request->comments_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->mitigation_required != $data->mitigation_required || !empty($request->mitigation_required_comment)) {

            $history = new RiskAuditTrail();
            $history->risk_id = $id;
            $history->activity_type = 'Mitigation Required';
            $history->previous = $lastDocument->mitigation_required;
            $history->current = $data->mitigation_required;
            $history->comment = $request->mitigation_required_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->mitigation_plan != $data->mitigation_plan || !empty($request->mitigation_plan_comment)) {

            $history = new RiskAuditTrail();
            $history->risk_id = $id;
            $history->activity_type = 'Mitigation Plan ';
            $history->previous = $lastDocument->mitigation_plan;
            $history->current = $data->mitigation_plan;
            $history->comment = $request->mitigation_plan_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->mitigation_status_comments != $data->mitigation_status_comments || !empty($request->mitigation_status_comments_comment)) {

            $history = new RiskAuditTrail();
            $history->risk_id = $id;
            $history->activity_type = 'Mitigation Status Comments ';
            $history->previous = $lastDocument->mitigation_status_comments;
            $history->current = $data->mitigation_status_comments;
            $history->comment = $request->mitigation_status_comments_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }

        if ($lastDocument->departments2 != $data->departments2 || !empty($request->departments2_comment)) {

            $history = new RiskAuditTrail();
            $history->risk_id = $id;
            $history->activity_type = 'Department2';
            $history->previous = $lastDocument->departments2;
            $history->current = $data->departments2;
            $history->comment = $request->departments2_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }



        if ($lastDocument->source_of_risk2 != $data->source_of_risk2 || !empty($request->source_of_risk2_comment)) {

            $history = new RiskAuditTrail();
            $history->risk_id = $id;
            $history->activity_type = 'Source of Risk';
            $history->previous = $lastDocument->source_of_risk2;
            $history->current = $data->source_of_risk2;
            $history->comment = $request->source_of_risk2_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->site_name != $data->site_name || !empty($request->site_name_comment)) {

            $history = new RiskAuditTrail();
            $history->risk_id = $id;
            $history->activity_type = 'Site Name';
            $history->previous = $lastDocument->site_name;
            $history->current = $data->site_name;
            $history->comment = $request->site_name_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->building != $data->building || !empty($request->building_comment)) {

            $history = new RiskAuditTrail();
            $history->risk_id = $id;
            $history->activity_type = 'Building';
            $history->previous = $lastDocument->building;
            $history->current = $data->building;
            $history->comment = $request->building_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->floor != $data->floor || !empty($request->floor_comment)) {

            $history = new RiskAuditTrail();
            $history->risk_id = $id;
            $history->activity_type = 'Floor';
            $history->previous = $lastDocument->floor;
            $history->current = $data->floor;
            $history->comment = $request->floor_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->room != $data->room || !empty($request->room_comment)) {

            $history = new RiskAuditTrail();
            $history->risk_id = $id;
            $history->activity_type = 'Room';
            $history->previous = $lastDocument->room;
            $history->current = $data->room;
            $history->comment = $request->room_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->duration != $data->duration || !empty($request->duration_comment)) {

            $history = new RiskAuditTrail();
            $history->risk_id = $id;
            $history->activity_type = 'Duration';
            $history->previous = $lastDocument->duration;
            $history->current = $data->duration;
            $history->comment = $request->duration_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->hazard != $data->hazard || !empty($request->hazard_comment)) {

            $history = new RiskAuditTrail();
            $history->risk_id = $id;
            $history->activity_type = 'Hazard';
            $history->previous = $lastDocument->hazard;
            $history->current = $data->hazard;
            $history->comment = $request->hazard_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->room2 != $data->room2 || !empty($request->room2_comment)) {

            $history = new RiskAuditTrail();
            $history->risk_id = $id;
            $history->activity_type = 'Room2';
            $history->previous = $lastDocument->room2;
            $history->current = $data->room2;
            $history->comment = $request->room2_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->regulatory_climate != $data->regulatory_climate || !empty($request->regulatory_climate_comment)) {

            $history = new RiskAuditTrail();
            $history->risk_id = $id;
            $history->activity_type = 'Regulatory Climate';
            $history->previous = $lastDocument->regulatory_climate;
            $history->current = $data->regulatory_climate;
            $history->comment = $request->regulatory_climate_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->Number_of_employees != $data->Number_of_employees || !empty($request->Number_of_employees_comment)) {

            $history = new RiskAuditTrail();
            $history->risk_id = $id;
            $history->activity_type = 'Number Of Employees';
            $history->previous = $lastDocument->Number_of_employees;
            $history->current = $data->Number_of_employees;
            $history->comment = $request->Number_of_employees_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->schedule_start_date1 != $data->schedule_start_date1 || !empty($request->schedule_start_date1_comment)) {

            $history = new RiskAuditTrail();
            $history->risk_id = $id;
            $history->activity_type = 'Scheduled Start Date';
            $history->previous = Helpers::getdateFormat($lastDocument->schedule_start_date1);
            $history->current = Helpers::getdateFormat($data->schedule_start_date1);
            $history->comment = $request->schedule_start_date1_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->schedule_end_date != $data->schedule_end_date || !empty($request->schedule_end_date_comment)) {

            $history = new RiskAuditTrail();
            $history->risk_id = $id;
            $history->activity_type = 'Scheduled End Date';
            $history->previous = Helpers::getdateFormat($lastDocument->schedule_end_date);
            $history->current = Helpers::getdateFormat($data->schedule_end_date);
            $history->comment = $request->schedule_end_date_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->estimated_man_hours != $data->estimated_man_hours || !empty($request->estimated_man_hours_comment)) {

            $history = new RiskAuditTrail();
            $history->risk_id = $id;
            $history->activity_type = 'Estimated Man-Hours  ';
            $history->previous = $lastDocument->estimated_man_hours;
            $history->current = $data->estimated_man_hours;
            $history->comment = $request->estimated_man_hours_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        // if ($lastDocument->refrence_record != $data->refrence_record || !empty($request->refrence_record_comment)) {

        //     $history = new RiskAuditTrail();
        //     $history->risk_id = $id;
        //     $history->activity_type = 'Reference Recores';
        //     $history->previous = $lastDocument->refrence_record;
        //     $history->current = $data->refrence_record;
        //     $history->comment = $request->refrence_record_comment;
        //     $history->user_id = Auth::user()->id;
        //     $history->user_name = Auth::user()->name;
        //     $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        //     $history->origin_state = $lastDocument->status;
        //     $history->save();
        // }

        if ($lastDocument->risk_management_strategy != $data->risk_management_strategy || !empty($request->risk_management_strategy_comment)) {

            $history = new RiskAuditTrail();
            $history->risk_id = $id;
            $history->activity_type = 'Risk Management Strategy';
            $history->previous = $lastDocument->risk_management_strategy;
            $history->current = $data->risk_management_strategy;
            $history->comment = $request->risk_management_strategy_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        // if ($lastDocument->estimated_man_hours != $data->estimated_man_hours || !empty($request->estimated_man_hours_comment)) {

        //     $history = new RiskAuditTrail();
        //     $history->risk_id = $id;
        //     $history->activity_type = 'Estimated  man  Hours';
        //     $history->previous = $lastDocument->estimated_man_hours;
        //     $history->current = $data->estimated_man_hours;
        //     $history->comment = $request->estimated_man_hours_comment;
        //     $history->user_id = Auth::user()->id;
        //     $history->user_name = Auth::user()->name;
        //     $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        //     $history->origin_state = $lastDocument->status;
        //     $history->save();
        // }
        if ($lastDocument->estimated_cost != $data->estimated_cost || !empty($request->estimated_cost_comment)) {

            $history = new RiskAuditTrail();
            $history->risk_id = $id;
            $history->activity_type = 'Estimated Cost';
            $history->previous = $lastDocument->estimated_cost;
            $history->current = $data->estimated_cost;
            $history->comment = $request->estimated_cost_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->currency != $data->currency || !empty($request->currency_comment)) {

            $history = new RiskAuditTrail();
            $history->risk_id = $id;
            $history->activity_type = 'Currency';
            $history->previous = $lastDocument->currency;
            $history->current = $data->currency;
            $history->comment = $request->currency_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->training_require != $data->training_require || !empty($request->training_require_comment)) {

            $history = new RiskAuditTrail();
            $history->risk_id = $id;
            $history->activity_type = 'Training Require';
            $history->previous = $lastDocument->training_require;
            $history->current = $data->training_require;
            $history->comment = $request->training_require_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->justification != $data->justification || !empty($request->justification_comment)) {

            $history = new RiskAuditTrail();
            $history->risk_id = $id;
            $history->activity_type = 'Justification / Rationale';
            $history->previous = $lastDocument->justification;
            $history->current = $data->justification;
            $history->comment = $request->justification_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->root_cause_methodology != $data->root_cause_methodology || !empty($request->root_cause_methodology_comment)) {

            $history = new RiskAuditTrail();
            $history->risk_id = $id;
            $history->activity_type = 'Root Cause Methodology';
            $history->previous = $lastDocument->root_cause_methodology;
            $history->current = $data->root_cause_methodology;
            $history->comment = $request->root_cause_methodology_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->investigation_summary != $data->investigation_summary || !empty($request->investigation_summary_comment)) {

            $history = new RiskAuditTrail();
            $history->risk_id = $id;
            $history->activity_type = 'Investigation Summary';
            $history->previous = $lastDocument->investigation_summary;
            $history->current = $data->investigation_summary;
            $history->comment = $request->investigation_summary_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->reference != $data->reference || !empty($request->reference_comment)) {

            $history = new RiskAuditTrail();
            $history->risk_id = $id;
            $history->activity_type = 'Work Group Attachments';
            $history->previous = $lastDocument->reference;
            $history->current = $data->reference;
            $history->comment = $request->reference_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }

        if ($lastDocument->attachment != $data->attachment || !empty($request->reference_comment)) {

            $history = new RiskAuditTrail();
            $history->risk_id = $id;
            $history->activity_type = 'Work Group Attachments';
            $history->previous = $lastDocument->attachment;
            $history->current = $data->attachment;
            $history->comment = $request->reference_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->due_date_extension != $data->due_date_extension || !empty($request->due_date_extension_comment)) {

            $history = new RiskAuditTrail();
            $history->risk_id = $id;
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
        if ($lastDocument->root_cause_description != $data->root_cause_description || !empty($request->root_cause_description_comment)) {

            $history = new RiskAuditTrail();
            $history->risk_id = $id;
            $history->activity_type = 'Root Cause Description';
            $history->previous = $lastDocument->root_cause_description;
            $history->current = $data->root_cause_description;
            $history->comment = $request->root_cause_description_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->cost_of_risk != $data->cost_of_risk || !empty($request->cost_of_risk_comment)) {

            $history = new RiskAuditTrail();
            $history->risk_id = $id;
            $history->activity_type = 'Cost Of Risk';
            $history->previous = $lastDocument->cost_of_risk;
            $history->current = $data->cost_of_risk;
            $history->comment = $request->cost_of_risk_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->environmental_impact != $data->environmental_impact || !empty($request->environmental_impact_comment)) {

            $history = new RiskAuditTrail();
            $history->risk_id = $id;
            $history->activity_type = 'Environmental Impact';
            $history->previous = $lastDocument->environmental_impact;
            $history->current = $data->environmental_impact;
            $history->comment = $request->environmental_impact_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->impact != $data->impact || !empty($request->impact_comment)) {

            $history = new RiskAuditTrail();
            $history->risk_id = $id;
            $history->activity_type = ' Impact';
            $history->previous = $lastDocument->impact;
            $history->current = $data->impact;
            $history->comment = $request->environmental_impact_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->criticality != $data->criticality || !empty($request->criticality_comment)) {

            $history = new RiskAuditTrail();
            $history->risk_id = $id;
            $history->activity_type = ' Criticality';
            $history->previous = $lastDocument->criticality;
            $history->current = $data->criticality;
            $history->comment = $request->criticalityt_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->impact_analysis != $data->impact_analysis || !empty($request->impact_analysis_comment)) {

            $history = new RiskAuditTrail();
            $history->risk_id = $id;
            $history->activity_type = ' Impact Analysis';
            $history->previous = $lastDocument->impact_analysis;
            $history->current = $data->impact_analysis;
            $history->comment = $request->impact_analysis_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->risk_analysis != $data->risk_analysis || !empty($request->risk_analysis_comment)) {

            $history = new RiskAuditTrail();
            $history->risk_id = $id;
            $history->activity_type = ' Risk Analysis';
            $history->previous = $lastDocument->risk_analysis;
            $history->current = $data->risk_analysis;
            $history->comment = $request->risk_analysis_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->refrence_record != $data->refrence_record || !empty($request->refrence_record_comment)) {

            $history = new RiskAuditTrail();
            $history->risk_id = $id;
            $history->activity_type = ' Reference Record';
            $history->previous = $lastDocument->refrence_record;
            $history->current = $data->refrence_record;
            $history->comment = $request->refrence_record_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->public_perception_impact != $data->public_perception_impact || !empty($request->public_perception_impact_comment)) {

            $history = new RiskAuditTrail();
            $history->risk_id = $id;
            $history->activity_type = 'Public Perception Impact';
            $history->previous = $lastDocument->public_perception_impact;
            $history->current = $data->public_perception_impact;
            $history->comment = $request->public_perception_impact_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->calculated_risk != $data->calculated_risk || !empty($request->calculated_risk_comment)) {

            $history = new RiskAuditTrail();
            $history->risk_id = $id;
            $history->activity_type = 'Calculated Risk';
            $history->previous = $lastDocument->calculated_risk;
            $history->current = $data->calculated_risk;
            $history->comment = $request->calculated_risk_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->impacted_objects != $data->impacted_objects || !empty($request->impacted_objects_comment)) {

            $history = new RiskAuditTrail();
            $history->risk_id = $id;
            $history->activity_type = 'Impacted Objects';
            $history->previous = $lastDocument->impacted_objects;
            $history->current = $data->impacted_objects;
            $history->comment = $request->impacted_objects_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        // if ($lastDocument->severity_rate != $data->severity_rate || !empty($request->severity_rate_comment)) {

        //     $history = new RiskAuditTrail();
        //     $history->risk_id = $id;
        //     $history->activity_type = 'Severity Rate';
        //     $history->previous = $lastDocument->severity_rate;
        //     $history->current = $data->severity_rate;
        //     $history->comment = $request->severity_rate_comment;
        //     $history->user_id = Auth::user()->id;
        //     $history->user_name = Auth::user()->name;
        //     $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        //     $history->origin_state = $lastDocument->status;
        //     $history->save();
        // }

        if ($lastDocument->severity_rate != $data->severity_rate || !empty($request->severity_rate_comment)) {
            $history = new RiskAuditTrail;
            $history->risk_id = $id;
            $history->activity_type = 'Severity Rate';

            if($lastDocument->severity_rate == 1){
                $history->previous = "Negligible ";
            } elseif($lastDocument->severity_rate == 2){
                $history->previous = "Moderate";
            } elseif($lastDocument->severity_rate == 3){
                $history->previous = "Major";
            } elseif($lastDocument->severity_rate == 4){
                $history->previous = "Fatal";
            }


            if($request->detection == 1){
                $history->current = "Negligible ";
            } elseif($request->detection == 2){
                $history->current = "Moderate";
            } elseif($request->detection == 3){
                $history->current = "Major";
            } elseif($request->detection == 4){
                $history->current = "Fatal";
            }


            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }


        // if ($lastDocument->occurrence != $data->occurrence || !empty($request->occurrence_comment)) {

        //     $history = new RiskAuditTrail();
        //     $history->risk_id = $id;
        //     $history->activity_type = 'Occurrence';
        //     $history->previous = $lastDocument->occurrence;
        //     $history->current = $data->occurrence;
        //     $history->comment = $request->occurrence_comment;
        //     $history->user_id = Auth::user()->id;
        //     $history->user_name = Auth::user()->name;
        //     $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        //     $history->origin_state = $lastDocument->status;
        //     $history->save();
        // }

        if ($lastDocument->occurrence != $data->occurrence || !empty($request->occurrence_comment)) {
            $history = new RiskAuditTrail;
            $history->risk_id = $id;
            $history->activity_type = 'Occurrence';

            if($lastDocument->occurrence == 1){
                $history->previous = "Very Likely";
            } elseif($lastDocument->occurrence == 2){
                $history->previous = "Likely";
            } elseif($lastDocument->occurrence == 3){
                $history->previous = "Unlikely";
            } elseif($lastDocument->occurrence == 4){
                $history->previous = "Rare";
            }elseif($lastDocument->occurrence == 5){
                $history->previous = "Extremely Unlikely";
            }


            if($request->detection == 1){
                $history->current = "Very Likely";
            } elseif($request->detection == 2){
                $history->current = "Likely";
            } elseif($request->detection == 3){
                $history->current = "Unlikely";
            } elseif($request->detection == 4){
                $history->current = "Rare";
            }elseif($request->detection == 5){
                $history->current = "Extremely Unlikely";
            }


            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }



        // if ($lastDocument->detection != $data->detection || !empty($request->detection_comment)) {

        //     $history = new RiskAuditTrail();
        //     $history->risk_id = $id;
        //     $history->activity_type = 'Detection';
        //     $history->previous = $lastDocument->detection;
        //     $history->current = $data->detection;
        //     $history->comment = $request->detection_comment;
        //     $history->user_id = Auth::user()->id;
        //     $history->user_name = Auth::user()->name;
        //     $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        //     $history->origin_state = $lastDocument->status;
        //     $history->save();
        // }

        if ($lastDocument->detection != $data->detection || !empty($request->detection_comment)) {
            $history = new RiskAuditTrail;
            $history->risk_id = $id;
            $history->activity_type = 'Detection';

            if($lastDocument->detection == 2){
                $history->previous = "Likely";
            } elseif($lastDocument->detection == 3){
                $history->previous = "Unlikely";
            } elseif($lastDocument->detection == 4){
                $history->previous = "Rare";
            } elseif($lastDocument->detection == 5){
                $history->previous = "Impossible";
            }


            if($request->detection == 2){
                $history->current = "Likely";
            } elseif($request->detection == 3){
                $history->current = "Unlikely";
            } elseif($request->detection == 4){
                $history->current = "Rare";
            } elseif($request->detection == 5){
                $history->current = "Impossible";
            }

            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }

        if ($lastDocument->rpn != $data->rpn || !empty($request->rpn_comment)) {

            $history = new RiskAuditTrail();
            $history->risk_id = $id;
            $history->activity_type = 'Rpn';
            $history->previous = $lastDocument->rpn;
            $history->current = $data->rpn;
            $history->comment = $request->rpn_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->residual_risk != $data->residual_risk || !empty($request->residual_risk_comment)) {

            $history = new RiskAuditTrail();
            $history->risk_id = $id;
            $history->activity_type = 'Residual Risk';
            $history->previous = $lastDocument->residual_risk;
            $history->current = $data->residual_risk;
            $history->comment = $request->residual_risk_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        // if ($lastDocument->residual_risk_impact != $data->residual_risk_impact || !empty($request->residual_risk_impact_comment)) {

        //     $history = new RiskAuditTrail();
        //     $history->risk_id = $id;
        //     $history->activity_type = 'Residual Risk Impact';
        //     $history->previous = $lastDocument->residual_risk_impact;
        //     $history->current = $data->residual_risk_impact;
        //     $history->comment = $request->residual_risk_impact_comment;
        //     $history->user_id = Auth::user()->id;
        //     $history->user_name = Auth::user()->name;
        //     $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        //     $history->origin_state = $lastDocument->status;
        //     $history->save();
        // }

        if ($lastDocument->residual_risk_impact != $data->residual_risk_impact || !empty($request->residual_risk_impact_comment)) {
            $history = new RiskAuditTrail;
            $history->risk_id = $id;
            $history->activity_type = 'Residual Risk Impact ';

            if($lastDocument->residual_risk_impact == 1){
                $history->previous = "High ";
            } elseif($lastDocument->residual_risk_impact == 2){
                $history->previous = "Medium";
            } elseif($lastDocument->residual_risk_impact == 3){
                $history->previous = "Low";
            }


            if($request->residual_risk_probability == 1){
                $history->current = "High ";
            } elseif($request->residual_risk_probability == 2){
                $history->current = "Medium";
            } elseif($request->residual_risk_probability == 3){
                $history->current = "Low";
            }


            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }


        // if ($lastDocument->residual_risk_probability != $data->residual_risk_probability || !empty($request->residual_risk_probability_comment)) {

        //     $history = new RiskAuditTrail();
        //     $history->risk_id = $id;
        //     $history->activity_type = 'Residual Risk Probability';
        //     $history->previous = $lastDocument->residual_risk_probability;
        //     $history->current = $data->residual_risk_probability;
        //     $history->comment = $request->residual_risk_probability_comment;
        //     $history->user_id = Auth::user()->id;
        //     $history->user_name = Auth::user()->name;
        //     $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        //     $history->origin_state = $lastDocument->status;
        //     $history->save();
        // }

        if ($lastDocument->residual_risk_probability != $data->residual_risk_probability || !empty($request->residual_risk_probability_comment)) {
            $history = new RiskAuditTrail;
            $history->risk_id = $id;
            $history->activity_type = 'Residual Risk Probability';

            if($lastDocument->residual_risk_probability == 1){
                $history->previous = "High ";
            } elseif($lastDocument->residual_risk_probability == 2){
                $history->previous = "Medium";
            } elseif($lastDocument->residual_risk_probability == 3){
                $history->previous = "Low";
            }


            if($request->residual_risk_probability == 1){
                $history->current = "High ";
            } elseif($request->residual_risk_probability == 2){
                $history->current = "Medium";
            } elseif($request->residual_risk_probability == 3){
                $history->current = "Low";
            }


            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }


        // if ($lastDocument->detection2 != $data->detection2 || !empty($request->detection2_comment)) {

        //     $history = new RiskAuditTrail();
        //     $history->risk_id = $id;
        //     $history->activity_type = 'Residual Detection ';
        //     $history->previous = $lastDocument->detection2;
        //     $history->current = $data->detection2;
        //     $history->comment = $request->detection2_comment;
        //     $history->user_id = Auth::user()->id;
        //     $history->user_name = Auth::user()->name;
        //     $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        //     $history->origin_state = $lastDocument->status;
        //     $history->save();
        // }

        if ($lastDocument->detection2 != $data->detection2 || !empty($request->detection2_comment)) {
            $history = new RiskAuditTrail;
            $history->risk_id = $id;
            $history->activity_type = 'Residual Detection';

            if($lastDocument->detection2 == 1){
                $history->previous = "Very Likely";
            } elseif($lastDocument->detection2 == 2){
                $history->previous = "Likely";
            } elseif($lastDocument->detection2 == 3){
                $history->previous = "Unlikely";
            } elseif($lastDocument->detection2 == 4){
                $history->previous = "Rare";
            }elseif($lastDocument->detection2 == 5){
                $history->previous = "Impossible";
            }


            if($request->detection2 == 1){
                $history->current = "Very Likely";
            } elseif($request->detection2 == 2){
                $history->current = "Likely";
            } elseif($request->detection2 == 3){
                $history->current = "Unlikely";
            } elseif($request->detection2 == 4){
                $history->current = "Rare";
            }elseif($request->detection2 == 5){
                $history->current = "Impossible";
            }


            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->rpn2 != $data->rpn2 || !empty($request->rpn2_comment)) {

            $history = new RiskAuditTrail();
            $history->risk_id = $id;
            $history->activity_type = 'Residual RPN';
            $history->previous = $lastDocument->rpn2;
            $history->current = $data->rpn2;
            $history->comment = $request->rpn2_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->comments2 != $data->comments2 || !empty($request->comments2_comment)) {

            $history = new RiskAuditTrail();
            $history->risk_id = $id;
            $history->activity_type = 'Comments';
            $history->previous = $lastDocument->comments2;
            $history->current = $data->comments2;
            $history->comment = $request->comments2_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->mitigation_due_date != $data->mitigation_due_date || !empty($request->mitigation_due_date_comment)) {

            $history = new RiskAuditTrail();
            $history->risk_id = $id;
            $history->activity_type = 'Scheduled End Date';
            $history->previous =Helpers::getdateFormat($lastDocument->mitigation_due_date);
            $history->current = Helpers::getdateFormat($data->mitigation_due_date);
            $history->comment = $request->mitigation_due_date_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->mitigation_status != $data->mitigation_status || !empty($request->mitigation_status_comment)) {

            $history = new RiskAuditTrail();
            $history->risk_id = $id;
            $history->activity_type = 'Status of Mitigation';
            $history->previous = $lastDocument->mitigation_status;
            $history->current = $data->mitigation_status;
            $history->comment = $request->mitigation_status_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }

        DocumentService::update_qms_numbers();

        toastr()->success("Record is update Successfully");
        return redirect()->back();
    }

    public function show($id)
    {
        $data = RiskManagement::find($id);
        $old_record = RiskManagement::select('id', 'division_id', 'record', 'created_at')->get();
        $data->record = str_pad($data->record, 4, '0', STR_PAD_LEFT);
        $data->assign_to_name = User::where('id', $data->assign_to)->value('name');
        $data->initiator_name = User::where('id', $data->initiator_id)->value('name');
        $riskEffectAnalysis = RiskAssesmentGrid::where('risk_id',$id)->where('type',"effect_analysis")->first();
        $fishbone = RiskAssesmentGrid::where('risk_id',$id)->where('type',"fishbone")->first();
        $whyChart = RiskAssesmentGrid::where('risk_id',$id)->where('type',"why_chart")->first();
        $what_who_where = RiskAssesmentGrid::where('risk_id',$id)->where('type',"what_who_where")->first();
        $action_plan = RiskAssesmentGrid::where('risk_id',$id)->where('type',"Action_Plan")->first();
        $mitigation_plan_details = RiskAssesmentGrid::where('risk_id',$id)->where('type',"Mitigation_Plan_Details")->first();

        return view('frontend.riskAssesment.view', compact('data','riskEffectAnalysis','fishbone','whyChart','what_who_where', 'old_record', 'action_plan', 'mitigation_plan_details'));
    }


    public function riskAssesmentStateChange(Request $request, $id)
    {
        if ($request->username == Auth::user()->email && Hash::check($request->password, Auth::user()->password)) {
            $changeControl = RiskManagement::find($id);
            $lastDocument =  RiskManagement::find($id);
            $data =  RiskManagement::find($id);


            if ($changeControl->stage == 1) {
                $changeControl->stage = "2";
                $changeControl->status = 'Risk Analysis & Work Group Assignment';
                $changeControl->submitted_by = Auth::user()->name;
                $changeControl->submitted_on = Carbon::now()->format('d-M-Y');

                $history = new RiskAuditTrail();
                $history->risk_id = $id;

                $history->activity_type = 'Activity Log';
                $history->current = "Risk Analysis & Work Group Assignment";
                $history->previous = $lastDocument->status;
                // $history->current = status;
                $history->comment = $request->comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                // $history->status = $lastDocument->status;
                $history->stage='Submitted';
                $history->save();

                    $list = Helpers::getHODUserList($changeControl->division_id);
                    $userIds = collect($list)->pluck('user_id')->toArray();
                    $users = User::whereIn('id', $userIds)->select('id', 'name', 'email')->get();
                    $userId = $users->pluck('name')->implode(',');
                    $userId1 = $users->pluck('id')->implode(',');

                    if($userId){
                        $test = new RiskAuditTrail();
                        $test->risk_id = $id;
                        $test->activity_type = "Notification";
                        $test->action = 'Notification';
                        $test->comment = "";
                        $test->user_id = Auth::user()->id;
                        $test->user_name = Auth::user()->name;
                        $test->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                        $test->origin_state = "Not Applicable";
                        $test->previous = $lastDocument->status;
                        $test->current = "Risk Analysis & Work Group Assignment";
                        $test->stage = "";
                        $test->action_name = "";
                        $test->mailUserId = $userId1;
                        $test->role_name = "Initiator";
                        $test->save();
                    }
                    // if(!empty($userId)){
                    //     try {
                    //         $notification = new RiskAuditTrail();
                    //             $notification->risk_id = $id;
                    //             $notification->activity_type = "Notification";
                    //             $notification->action = 'Notification';
                    //             $notification->comment = "";
                    //             $notification->user_id = Auth::user()->id;
                    //             $notification->user_name = Auth::user()->name;
                    //             $notification->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    //             $notification->origin_state = "Not Applicable";
                    //             $notification->previous = $lastDocument->status;
                    //             $notification->current = "Risk Analysis & Work Group Assignment";
                    //             $notification->stage = "";
                    //             $notification->action_name = "";
                    //             $notification->mailUserId = $userId;
                    //             $notification->role_name = "Initiator";
                    //             $notification->save();
                    //             dd($notification);
                    //         } catch (\Throwable $e) {
                    //             \Log::error('Mail failed to send: ' . $e->getMessage());
                    //         }
                    // }

                        // foreach ($list as $u) {
                        //     $email = Helpers:: getAllUserEmail($u->user_id);
                        //     if (!empty($email)) {
                        //         try {
                        //             info('Sending mail to', [$email]);
                        //             Mail::send(
                        //                 'mail.view-mail',
                        //                 ['data' => $changeControl,'site'=>'Risk Assessment','history' => 'Submitted', 'process' => 'Risk Assessment', 'comment' => $history->comment,'user'=> Auth::user()->name],
                        //                 function ($message) use ($email, $changeControl) {
                        //                 $message->to($email)
                        //                 ->subject("QMS Notification: Risk Assessment , Record #" . str_pad($changeControl->record, 4, '0', STR_PAD_LEFT) . " - Activity: Submitted Performed"); }
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
                                    $data = ['data' => $changeControl,'site'=>'Risk Assessment','history' => 'Submitted', 'process' => 'Risk Assessment', 'comment' => $history->comment,'user'=> Auth::user()->name];
                        
                                    SendMail::dispatch($data, $email, $changeControl, 'Risk Assessment');
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
                $changeControl->status = 'Risk Processing & Action Plan';
                $changeControl->evaluated_by = Auth::user()->name;
                $changeControl->evaluated_on = Carbon::now()->format('d-M-Y');
                $history = new RiskAuditTrail();
                $history->risk_id = $id;
                $history->activity_type = 'Activity Log';
                $history->previous = $lastDocument->status;
                $history->current ='Risk Processing & Action Plan';
                $history->comment = $request->comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                $history->stage='HOD/Designee';
                $history->save();
            //     $list = Helpers::getWorkGroupUserList();
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
                    $list = Helpers::getWorkGroupUserList($changeControl->division_id);

                    $userIds = collect($list)->pluck('user_id')->toArray();
                    $users = User::whereIn('id', $userIds)->select('id', 'name', 'email')->get();
                    $userId = $users->pluck('name')->implode(',');
                    $userId1 = $users->pluck('id')->implode(',');

                    if($userId){
                        $test = new RiskAuditTrail();
                        $test->risk_id = $id;
                        $test->activity_type = "Notification";
                        $test->action = 'Notification';
                        $test->comment = "";
                        $test->user_id = Auth::user()->id;
                        $test->user_name = Auth::user()->name;
                        $test->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                        $test->origin_state = "Not Applicable";
                        $test->previous = $lastDocument->status;
                        $test->current = "Risk Processing & Action Plan";
                        $test->stage = "";
                        $test->action_name = "";
                        $test->mailUserId = $userId1;
                        $test->role_name = "HOD";
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
                        //                 ['data' => $changeControl,'site'=>'Risk Assessment','history' => 'evaluated', 'process' => 'Risk Assessment', 'comment' => $history->comment,'user'=> Auth::user()->name],
                        //                 function ($message) use ($email, $changeControl) {
                        //                 $message->to($email)
                        //                 ->subject("QMS Notification: Risk Assessment , Record #" . str_pad($changeControl->record, 4, '0', STR_PAD_LEFT) . " - Activity: evaluated Performed"); }
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
                                    $data = ['data' => $changeControl,'site'=>'Risk Assessment','history' => 'evaluated', 'process' => 'Risk Assessment', 'comment' => $history->comment,'user'=> Auth::user()->name];
                        
                                    SendMail::dispatch($data, $email, $changeControl, 'Risk Assessment');
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
                $changeControl->status = 'Pending HOD Approval';
                $changeControl->actions_completed_by = Auth::user()->name;
                $changeControl->actions_completed_on = Carbon::now()->format('d-M-Y');
                $history = new RiskAuditTrail();
                $history->risk_id = $id;
                $history->activity_type = 'Activity Log';
                $history->previous = $lastDocument->status;
                $history->current ="Pending HOD Approval";
                $history->comment = $request->comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                $history->stage='Action Plan';
                $history->save();
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
                    $list = Helpers::getHODUserList($changeControl->division_id);
                        // dd($list);
                        $userIds = collect($list)->pluck('user_id')->toArray();
                        $users = User::whereIn('id', $userIds)->select('id', 'name', 'email')->get();
                        $userId = $users->pluck('name')->implode(',');
                        $userId1 = $users->pluck('id')->implode(',');

                        if($userId){
                            $test = new RiskAuditTrail();
                            $test->risk_id = $id;
                            $test->activity_type = "Notification";
                            $test->action = 'Notification';
                            $test->comment = "";
                            $test->user_id = Auth::user()->id;
                            $test->user_name = Auth::user()->name;
                            $test->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                            $test->origin_state = "Not Applicable";
                            $test->previous = $lastDocument->status;
                            $test->current = "Pending HOD Approval";
                            $test->stage = "";
                            $test->action_name = "";
                            $test->mailUserId = $userId1;
                            $test->role_name = "Work Group";
                            $test->save();
                        }
                        // foreach ($list as $u) {
                        //     $email = Helpers:: getAllUserEmail($u->user_id);
                        //     if (!empty($email)) {
                        //         try {
                        //             info('Sending mail to', [$email]);
                        //             Mail::send(
                        //                 'mail.view-mail',
                        //                 ['data' => $changeControl,'site'=>'Risk Assessment','history' => 'Actions Completed', 'process' => 'Risk Assessment', 'comment' => $history->comment,'user'=> Auth::user()->name],
                        //                 function ($message) use ($email, $changeControl) {
                        //                 $message->to($email)
                        //                 ->subject("QMS Notification: Risk Assessment , Record #" . str_pad($changeControl->record, 4, '0', STR_PAD_LEFT) . " - Activity: Actions Completed Performed"); }
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
                                    $data = ['data' => $changeControl,'site'=>'Risk Assessment','history' => 'Actions Completed', 'process' => 'Risk Assessment', 'comment' => $history->comment,'user'=> Auth::user()->name];
                        
                                    SendMail::dispatch($data, $email, $changeControl, 'Risk Assessment');
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
                $changeControl->status = 'Actions Items in Progress';
                $changeControl->plan_approved_by = Auth::user()->name;
                $changeControl->plan_approved_on = Carbon::now()->format('d-M-Y');
                $history = new RiskAuditTrail();
                $history->risk_id = $id;
                $history->activity_type = 'Activity Log';
                $history->previous = $lastDocument->status;
                $history->current ='Actions Items in Progress';
                $history->comment = $request->comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                $history->stage='Action Plan Approved';
                $history->save();
            //     $list = Helpers::getQAHeadUserList();
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
                    $list = Helpers::getQAUserList($changeControl->division_id);
                        // dd($list);

                        $userIds = collect($list)->pluck('user_id')->toArray();
                        $users = User::whereIn('id', $userIds)->select('id', 'name', 'email')->get();
                        $userId = $users->pluck('name')->implode(',');
                        $userId1 = $users->pluck('id')->implode(',');

                        if($userId){
                            $test = new RiskAuditTrail();
                            $test->risk_id = $id;
                            $test->activity_type = "Notification";
                            $test->action = 'Notification';
                            $test->comment = "";
                            $test->user_id = Auth::user()->id;
                            $test->user_name = Auth::user()->name;
                            $test->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                            $test->origin_state = "Not Applicable";
                            $test->previous = $lastDocument->status;
                            $test->current = "Actions Items in Progress";
                            $test->stage = "";
                            $test->action_name = "";
                            $test->mailUserId = $userId1;
                            $test->role_name = "HOD/Designee";
                            $test->save();
                        }

                        // foreach ($list as $u) {
                        //     $email = Helpers:: getAllUserEmail($u->user_id);
                        //     if (!empty($email)) {
                        //         try {
                        //             info('Sending mail to', [$email]);
                        //             Mail::send(
                        //                 'mail.view-mail',
                        //                 ['data' => $changeControl,'site'=>'Risk Assessment','history' => 'Plan Approved', 'process' => 'Risk Assessment', 'comment' => $history->comment,'user'=> Auth::user()->name],
                        //                 function ($message) use ($email, $changeControl) {
                        //                 $message->to($email)
                        //                 ->subject("QMS Notification: Risk Assessment , Record #" . str_pad($changeControl->record, 4, '0', STR_PAD_LEFT) . " - Activity: Submitted Performed"); }
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
                                    $data = ['data' => $changeControl,'site'=>'Risk Assessment','history' => 'Plan Approved', 'process' => 'Risk Assessment', 'comment' => $history->comment,'user'=> Auth::user()->name];
                        
                                    SendMail::dispatch($data, $email, $changeControl, 'Risk Assessment');
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
                $changeControl->stage = "6";
                $changeControl->status = 'Residual Risk Evaluation';
                $changeControl->all_actions_completed_by = Auth::user()->name;
                $changeControl->all_actions_completed_on = Carbon::now()->format('d-M-Y');
                $history = new RiskAuditTrail();
                $history->risk_id = $id;
                $history->activity_type = 'Activity Log';
                $history->previous = $lastDocument->status;
                $history->current ="Residual Risk Evaluation";
                $history->comment = $request->comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                $history->stage='All Action';
                $history->save();
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
                    $list = Helpers::getInitiatorUserList($changeControl->division_id);
                        // dd($list);

                        $userIds = collect($list)->pluck('user_id')->toArray();
                        $users = User::whereIn('id', $userIds)->select('id', 'name', 'email')->get();
                        $userId = $users->pluck('name')->implode(',');
                        $userId1 = $users->pluck('id')->implode(',');

                        if($userId){
                            $test = new RiskAuditTrail();
                            $test->risk_id = $id;
                            $test->activity_type = "Notification";
                            $test->action = 'Notification';
                            $test->comment = "";
                            $test->user_id = Auth::user()->id;
                            $test->user_name = Auth::user()->name;
                            $test->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                            $test->origin_state = "Not Applicable";
                            $test->previous = $lastDocument->status;
                            $test->current = "Residual Risk Evaluation";
                            $test->stage = "";
                            $test->action_name = "";
                            $test->mailUserId = $userId1;
                            $test->role_name = "QA";
                            $test->save();
                        }


                        // foreach ($list as $u) {
                        //     $email = Helpers:: getAllUserEmail($u->user_id);
                        //     if (!empty($email)) {
                        //         try {
                        //             info('Sending mail to', [$email]);
                        //             Mail::send(
                        //                 'mail.view-mail',
                        //                 ['data' => $changeControl,'site'=>'Risk Assessment','history' => 'All Actions Completed', 'process' => 'Risk Assessment', 'comment' => $history->comment,'user'=> Auth::user()->name],
                        //                 function ($message) use ($email, $changeControl) {
                        //                 $message->to($email)
                        //                 ->subject("QMS Notification: Risk Assessment , Record #" . str_pad($changeControl->record, 4, '0', STR_PAD_LEFT) . " - Activity: All Actions Completed Performed"); }
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
                                    $data = ['data' => $changeControl,'site'=>'Risk Assessment','history' => 'All Actions Completed', 'process' => 'Risk Assessment', 'comment' => $history->comment,'user'=> Auth::user()->name];
                        
                                    SendMail::dispatch($data, $email, $changeControl, 'Risk Assessment');
                                }
                            } catch (\Exception $e) {
                                \Log::error('Mail sending failed for user_id: ' . $u->user_id . ' - Error: ' . $e->getMessage());       
                                continue;
                            }
                        }

                        $list = Helpers::getHODUserList($changeControl->division_id);
                        // dd($list);
                        // foreach ($list as $u) {
                        //     $email = Helpers:: getAllUserEmail($u->user_id);
                        //     if (!empty($email)) {
                        //         try {
                        //             info('Sending mail to', [$email]);
                        //             Mail::send(
                        //                 'mail.view-mail',
                        //                 ['data' => $changeControl,'site'=>'Risk Assessment','history' => 'All Actions Completed', 'process' => 'Risk Assessment', 'comment' => $history->comment,'user'=> Auth::user()->name],
                        //                 function ($message) use ($email, $changeControl) {
                        //                 $message->to($email)
                        //                 ->subject("QMS Notification: Risk Assessment , Record #" . str_pad($changeControl->record, 4, '0', STR_PAD_LEFT) . " - Activity: Submitted Performed"); }
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
                                    $data = ['data' => $changeControl,'site'=>'Risk Assessment','history' => 'All Actions Completed', 'process' => 'Risk Assessment', 'comment' => $history->comment,'user'=> Auth::user()->name];
                        
                                    SendMail::dispatch($data, $email, $changeControl, 'Risk Assessment');
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
                $changeControl->status = 'Closed - Done';
                $changeControl->risk_analysis_completed_by = Auth::user()->name;
                $changeControl->risk_analysis_completed_on = Carbon::now()->format('d-M-Y');
                $history = new RiskAuditTrail();
                $history->risk_id = $id;
                $history->activity_type = 'Activity Log';
                $history->current = "Closed - Done";
                $history->previous = $lastDocument->status;
                $history->comment = $request->comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                $history->stage='Residual Risk Evalution';
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
            $changeControl = RiskManagement::find($id);
            $lastDocument =  RiskManagement::find($id);
            $data =  RiskManagement::find($id);



            if ($changeControl->stage == 1) {
                $changeControl->stage = "0";
                $changeControl->status = "Closed - Cancelled";
                $changeControl->cancelled_by = Auth::user()->name;
                $changeControl->cancelled_on = Carbon::now()->format('d-M-Y');
                $history = new RiskAuditTrail();
                $history->risk_id = $id;
                $history->activity_type = 'Activity Log';
                $history->current ="Closed - Cancelled";
                $history->previous = $lastDocument->status;
                $history->comment = $request->comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                $history->stage='Cancelled';
                $history->save();

                $changeControl->update();
                toastr()->success('Document Sent');
                return back();
            }
            if ($changeControl->stage == 2) {
                $changeControl->stage = "1";
                $changeControl->status = "Opened";
                $changeControl->More_info1_by = Auth::user()->name;
                $changeControl->More_info1_on = Carbon::now()->format('d-M-Y');
                $history = new RiskAuditTrail();
                $history->risk_id = $id;
                $history->activity_type = 'Activity Log';
                $history->current = "Opened";
                $history->previous = $lastDocument->status;
                $history->comment = $request->comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                $history->stage='Cancelled';
                $history->save();
                $changeControl->update();

                $list = Helpers::getInitiatorUserList($changeControl->division_id);
                // dd($list);

                $userIds = collect($list)->pluck('user_id')->toArray();
                        $users = User::whereIn('id', $userIds)->select('id', 'name', 'email')->get();
                        $userId = $users->pluck('name')->implode(',');
                        $userId1 = $users->pluck('id')->implode(',');

                        if($userId){
                            $test = new RiskAuditTrail();
                            $test->risk_id = $id;
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
                            $test->role_name = "HOD/Designee";
                            $test->save();
                        }


                // foreach ($list as $u) {
                //     $email = Helpers:: getAllUserEmail($u->user_id);
                //     if (!empty($email)) {
                //         try {
                //             info('Sending mail to', [$email]);
                //             Mail::send(
                //                 'mail.view-mail',
                //                 ['data' => $changeControl,'site'=>'Risk Assessment','history' => 'More Information', 'process' => 'Risk Assessment', 'comment' => $history->comment,'user'=> Auth::user()->name],
                //                 function ($message) use ($email, $changeControl) {
                //                  $message->to($email)
                //                  ->subject("QMS Notification: Risk Assessment , Record #" . str_pad($changeControl->record, 4, '0', STR_PAD_LEFT) . " - Activity: More Information Performed"); }
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
                            $data = ['data' => $changeControl,'site'=>'Risk Assessment','history' => 'More Information', 'process' => 'Risk Assessment', 'comment' => $history->comment,'user'=> Auth::user()->name];
                
                            SendMail::dispatch($data, $email, $changeControl, 'Risk Assessment');
                        }
                    } catch (\Exception $e) {
                        \Log::error('Mail sending failed for user_id: ' . $u->user_id . ' - Error: ' . $e->getMessage());       
                        continue;
                    }
                }


                toastr()->success('Document Sent');
                return back();
            }
             if ($changeControl->stage == 3) {
                $changeControl->stage = "2";
                $changeControl->status = "Risk Analysis & Work Group Assignment";
                $changeControl->More_info2_by = Auth::user()->name;
                $changeControl->More_info2_on = Carbon::now()->format('d-M-Y');
                $history = new RiskAuditTrail();
                $history->risk_id = $id;
                $history->activity_type = 'Activity Log';
                $history->current = "Risk Analysis & Work Group Assignment";
                $history->previous = $lastDocument->status;
                $history->comment = $request->comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                $history->stage='Cancelled';
                $history->save();
                $changeControl->update();

                $list = Helpers::getHODUserList($changeControl->division_id);
                // dd($list);


                $userIds = collect($list)->pluck('user_id')->toArray();
                        $users = User::whereIn('id', $userIds)->select('id', 'name', 'email')->get();
                        $userId = $users->pluck('name')->implode(',');
                        $userId1 = $users->pluck('id')->implode(',');

                        if($userId){
                            $test = new RiskAuditTrail();
                            $test->risk_id = $id;
                            $test->activity_type = "Notification";
                            $test->action = 'Notification';
                            $test->comment = "";
                            $test->user_id = Auth::user()->id;
                            $test->user_name = Auth::user()->name;
                            $test->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                            $test->origin_state = "Not Applicable";
                            $test->previous = $lastDocument->status;
                            $test->current = "Risk Analysis & Work Group Assignment";
                            $test->stage = "";
                            $test->action_name = "";
                            $test->mailUserId = $userId1;
                            $test->role_name = "Work Group";
                            $test->save();
                        }



                // foreach ($list as $u) {
                //     $email = Helpers:: getAllUserEmail($u->user_id);
                //     if (!empty($email)) {
                //         try {
                //             info('Sending mail to', [$email]);
                //             Mail::send(
                //                 'mail.view-mail',
                //                 ['data' => $changeControl,'site'=>'Risk Assessment','history' => 'More Information', 'process' => 'Risk Assessment', 'comment' => $history->comment,'user'=> Auth::user()->name],
                //                 function ($message) use ($email, $changeControl) {
                //                  $message->to($email)
                //                  ->subject("QMS Notification: Risk Assessment , Record #" . str_pad($changeControl->record, 4, '0', STR_PAD_LEFT) . " - Activity: More Information Performed"); }
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
                            $data = ['data' => $changeControl,'site'=>'Risk Assessment','history' => 'More Information', 'process' => 'Risk Assessment', 'comment' => $history->comment,'user'=> Auth::user()->name];
                
                            SendMail::dispatch($data, $email, $changeControl, 'Risk Assessment');
                        }
                    } catch (\Exception $e) {
                        \Log::error('Mail sending failed for user_id: ' . $u->user_id . ' - Error: ' . $e->getMessage());       
                        continue;
                    }
                }
                toastr()->success('Document Sent');
                return back();
            }
            if ($changeControl->stage == 4) {
                $changeControl->stage = "3";
                $changeControl->status = "Risk Processing & Action Plan";
                $changeControl->More_info3_by = Auth::user()->name;
                $changeControl->More_info3_on = Carbon::now()->format('d-M-Y');
                $history = new RiskAuditTrail();
                $history->risk_id = $id;
                $history->activity_type = 'Activity Log';
                $history->current ="Risk Processing & Action Plan";
                $history->previous = $lastDocument->status;
                $history->comment = $request->comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                $history->stage='Cancelled';
                $history->save();
                $changeControl->update();


                $list = Helpers::getWorkGroupUserList($changeControl->division_id);
                // dd($list);

                $userIds = collect($list)->pluck('user_id')->toArray();
                        $users = User::whereIn('id', $userIds)->select('id', 'name', 'email')->get();
                        $userId = $users->pluck('name')->implode(',');
                        $userId1 = $users->pluck('id')->implode(',');

                        if($userId){
                            $test = new RiskAuditTrail();
                            $test->risk_id = $id;
                            $test->activity_type = "Notification";
                            $test->action = 'Notification';
                            $test->comment = "";
                            $test->user_id = Auth::user()->id;
                            $test->user_name = Auth::user()->name;
                            $test->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                            $test->origin_state = "Not Applicable";
                            $test->previous = $lastDocument->status;
                            $test->current = "Risk Processing & Action Plan";
                            $test->stage = "";
                            $test->action_name = "";
                            $test->mailUserId = $userId1;
                            $test->role_name = "HOD/Designee";
                            $test->save();
                        }


                // foreach ($list as $u) {
                //     $email = Helpers:: getAllUserEmail($u->user_id);
                //     if (!empty($email)) {
                //         try {
                //             info('Sending mail to', [$email]);
                //             Mail::send(
                //                 'mail.view-mail',
                //                 ['data' => $changeControl,'site'=>'Risk Assessment','history' => 'More Information', 'process' => 'Risk Assessment', 'comment' => $history->comment,'user'=> Auth::user()->name],
                //                 function ($message) use ($email, $changeControl) {
                //                  $message->to($email)
                //                  ->subject("QMS Notification: Risk Assessment , Record #" . str_pad($changeControl->record, 4, '0', STR_PAD_LEFT) . " - Activity: More Information Performed"); }
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
                            $data = ['data' => $changeControl,'site'=>'Risk Assessment','history' => 'More Information', 'process' => 'Risk Assessment', 'comment' => $history->comment,'user'=> Auth::user()->name];
                
                            SendMail::dispatch($data, $email, $changeControl, 'Risk Assessment');
                        }
                    } catch (\Exception $e) {
                        \Log::error('Mail sending failed for user_id: ' . $u->user_id . ' - Error: ' . $e->getMessage());       
                        continue;
                    }
                }

                toastr()->success('Document Sent');
                return back();
            }

            if ($changeControl->stage == 5) {
                $changeControl->stage = "4";
                $changeControl->status = "Pending HOD Approval";
                $changeControl->More_info3_by = Auth::user()->name;
                $changeControl->More_info3_on = Carbon::now()->format('d-M-Y');
                $history = new RiskAuditTrail();
                $history->risk_id = $id;
                $history->activity_type = 'Activity Log';
                $history->current = "Pending HOD Approval";
                $history->previous = $lastDocument->status;
                $history->comment = $request->comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                $history->stage='Cancelled';
                $history->save();
                $changeControl->update();

                $list = Helpers::getHODUserList($changeControl->division_id);
                // dd($list);

                $userIds = collect($list)->pluck('user_id')->toArray();
                        $users = User::whereIn('id', $userIds)->select('id', 'name', 'email')->get();
                        $userId = $users->pluck('name')->implode(',');
                        $userId1 = $users->pluck('id')->implode(',');

                        if($userId){
                            $test = new RiskAuditTrail();
                            $test->risk_id = $id;
                            $test->activity_type = "Notification";
                            $test->action = 'Notification';
                            $test->comment = "";
                            $test->user_id = Auth::user()->id;
                            $test->user_name = Auth::user()->name;
                            $test->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                            $test->origin_state = "Not Applicable";
                            $test->previous = $lastDocument->status;
                            $test->current = "Pending HOD Approval";
                            $test->stage = "";
                            $test->action_name = "";
                            $test->mailUserId = $userId1;
                            $test->role_name = "QA";
                            $test->save();
                        }


                // foreach ($list as $u) {
                //     $email = Helpers:: getAllUserEmail($u->user_id);
                //     if (!empty($email)) {
                //         try {
                //             info('Sending mail to', [$email]);
                //             Mail::send(
                //                 'mail.view-mail',
                //                 ['data' => $changeControl,'site'=>'Risk Assessment','history' => 'More Information', 'process' => 'Risk Assessment', 'comment' => $history->comment,'user'=> Auth::user()->name],
                //                 function ($message) use ($email, $changeControl) {
                //                  $message->to($email)
                //                  ->subject("QMS
                //                  : Risk Assessment , Record #" . str_pad($changeControl->record, 4, '0', STR_PAD_LEFT) . " - Activity: More Information   Performed"); }
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
                            $data = ['data' => $changeControl,'site'=>'Risk Assessment','history' => 'More Information', 'process' => 'Risk Assessment', 'comment' => $history->comment,'user'=> Auth::user()->name];
                
                            SendMail::dispatch($data, $email, $changeControl, 'Risk Assessment');
                        }
                    } catch (\Exception $e) {
                        \Log::error('Mail sending failed for user_id: ' . $u->user_id . ' - Error: ' . $e->getMessage());       
                        continue;
                    }
                }
                toastr()->success('Document Sent');
                return back();
            }
            if ($changeControl->stage == 6) {
                $changeControl->stage = "5";
                $changeControl->status = "Actions Items in Progress";
                $changeControl->More_info5_by= Auth::user()->name;
                $changeControl->More_info5_on = Carbon::now()->format('d-M-Y');
                $history = new RiskAuditTrail();
                $history->risk_id = $id;
                $history->activity_type = 'Activity Log';
                $history->current ="Actions Items in Progress";
                $history->previous = $lastDocument->status;
                $history->comment = $request->comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                $history->stage='Cancelled';
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


    public function riskAuditTrial($id)
    {
        $audit = RiskAuditTrail::where('risk_id', $id)->orderByDESC('id')->get()->unique('activity_type');
        $today = Carbon::now()->format('d-m-y');
        $document = RiskManagement::where('id', $id)->first();
        $document->initiator = User::where('id', $document->initiator_id)->value('name');

        return view("frontend.riskAssesment.audit-trail", compact('audit', 'document', 'today'));
    }

    public function auditDetailsrisk($id)
    {

        $detail = RiskAuditTrail::find($id);

        $detail_data = RiskAuditTrail::where('activity_type', $detail->activity_type)->where('risk_id', $detail->risk_id)->latest()->get();

        $doc = RiskManagement::where('id', $detail->risk_id)->first();

        $doc->origiator_name = User::find($doc->initiator_id);
        return view("frontend.riskAssesment.audit-trial-inner", compact('detail', 'doc', 'detail_data'));
    }

    public static function singleReport($id)
    {
        $data = RiskManagement::find($id);
        if (!empty($data)) {

            $riskgrdfishbone = RiskAssesmentGrid::where('risk_id', $data->id)->where('type','fishbone')->first();

            $riskgrdwhy_chart = RiskAssesmentGrid::where('risk_id', $data->id)->where('type','why_chart')->first();
            $riskgrdwhat_who_where = RiskAssesmentGrid::where('risk_id', $data->id)->where('type','what_who_where')->first();
            $data->action_plan = RiskAssesmentGrid::where('risk_id', $data->id)->where('type', "action_plan")->first();

             //dd($riskgrd);
            $data->originator = User::where('id', $data->initiator_id)->value('name');
            $pdf = App::make('dompdf.wrapper');
            $time = Carbon::now();
            $pdf = PDF::loadview('frontend.riskAssesment.singleReport', compact('data','riskgrdfishbone','riskgrdwhy_chart','riskgrdwhat_who_where'))
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
            $canvas->page_text($width / 4, $height / 2, $data->status, null, 25, [0, 0, 0], 2, 6, -20);
            return $pdf->stream('Risk-assesment' . $id . '.pdf');
        }
    }

    public static function auditReport($id)
    {
        $doc = RiskManagement::find($id);
        if (!empty($doc)) {
            $doc->originator = User::where('id', $doc->initiator_id)->value('name');
            $data = RiskAuditTrail::where('risk_id', $id)->get();
            $pdf = App::make('dompdf.wrapper');
            $time = Carbon::now();
            $pdf = PDF::loadview('frontend.riskAssesment.auditReport', compact('data', 'doc'))
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
            return $pdf->stream('Risk-Audit-Trial' . $id . '.pdf');
        }
    }

    public function child(Request $request, $id)
    {
        $parent_id = $id;
        $parent_type = "Action-Item";
        $record_number = ((RecordNumber::first()->value('counter')) + 1);
        $record_number = str_pad($record_number, 4, '0', STR_PAD_LEFT);
        $currentDate = Carbon::now();
        $formattedDate = $currentDate->addDays(30);
        $due_date = $formattedDate->format('d-M-Y');
        $parent_record = RiskManagement::where('id', $id)->value('record');
        $parent_record = str_pad($parent_record, 4, '0', STR_PAD_LEFT);
        $parent_division_id = RiskManagement::where('id', $id)->value('division_id');
        $parent_initiator_id = RiskManagement::where('id', $id)->value('initiator_id');
        $parent_intiation_date = RiskManagement::where('id', $id)->value('intiation_date');
        $parent_short_description = RiskManagement::where('id', $id)->value('short_description');
        $old_record = RiskManagement::select('id', 'division_id', 'record', 'created_at')->get();

        return view('frontend.forms.action-item', compact('parent_id', 'parent_type', 'record_number', 'currentDate', 'formattedDate', 'due_date', 'parent_record', 'parent_record', 'parent_division_id', 'parent_initiator_id', 'parent_intiation_date', 'parent_short_description','old_record'));
    }
}
