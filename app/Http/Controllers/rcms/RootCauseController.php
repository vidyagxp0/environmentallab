<?php

namespace App\Http\Controllers\rcms;

use App\Http\Controllers\Controller;
use App\Models\RecordNumber;
use App\Models\RootAuditTrial;
use App\Models\RoleGroup;
use App\Models\RiskAssesmentGrid;
use App\Models\RootCauseAnalysis;
use App\Models\RootCauseAnalysisHistory;
use App\Models\User;
use App\Models\RootcauseAnalysisDocDetails;
use Carbon\Carbon;
use PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

 class RootCauseController extends Controller
{
    public function rootcause()
    {
        $record_number = ((RecordNumber::first()->value('counter')) + 1);
        $record_number = str_pad($record_number, 4, '0', STR_PAD_LEFT);
        $currentDate = Carbon::now();
        $formattedDate = $currentDate->addDays(30);
        $due_date = $formattedDate->format('Y-m-d');
        return view("frontend.forms.root-cause-analysis", compact('due_date', 'record_number'));
        return view("frontend.forms.root-cause-analysis");
    }


    public function root_store(Request $request)
    { 

        //$request->dd();
        //return $request;

        if (!$request->short_description) {
           toastr()->error("Short description is required");
             return redirect()->back();
        }

        $root = new RootCauseAnalysis();
        $root->form_type = "root-cause-analysis";
        $root->originator_id = json_encode($request->originator_id);
        $root->date_opened = ($request->date_opened);
        $root->priority_level = ($request->priority_level);
        $root->severity_level = ($request->severity_level);
        $root->short_description =($request->short_description);
        $root->assigned_to = ($request->assigned_to);
        $root->root_cause_description = ($request->root_cause_description);
        $root->due_date = ($request->due_date);
        $root->cft_comments_new = $request->cft_comments_new;
         $root->qa_comments_new = $request->qa_comments_new;
         $root->designee_comments_new = $request->designee_comments_new;
        $root->Warehouse_comments_new = $request->Warehouse_comments_new;
         $root->Engineering_comments_new = $request->Engineering_comments_new;
        $root->Instrumentation_comments_new = $request->Instrumentation_comments_new;
        $root->Validation_comments_new = $request->Validation_comments_new;
        $root->Others_comments_new = $request->Others_comments_new;
        $root->Group_comments_new = $request->Group_comments_new;
        $root->Type= ($request->Type);
        $root->investigators = ($request->investigators);
        $root->initiated_through = ($request->initiated_through);
        $root->initiated_if_other = ($request->initiated_if_other);
        $root->department = ($request->department);
        $root->description = ($request->description);
        $root->comments = ($request->comments);
        $root->related_url = ($request->related_url);
        // $root->root_cause_methodology = json_encode($request->root_cause_methodology);
        $root->root_cause_methodology = implode(',', $request->root_cause_methodology);

        $root->measurement = json_encode($request->measurement);
        $root->materials = json_encode($request->materials);
        $root->methods = json_encode($request->methods);
        $root->environment = json_encode($request->environment);
        $root->manpower = json_encode($request->manpower);
        $root->machine = json_encode($request->machine);
        $root->problem_statement = ($request->problem_statement);
        $root->why_problem_statement = ($request->why_problem_statement);
        $root->why_1 = json_encode($request->why_1);
        $root->why_2 = json_encode($request->why_2);
        $root->why_3 = json_encode($request->why_3);
        $root->why_4 = json_encode($request->why_4);
        $root->why_5 = json_encode($request->why_5);
        //$root->root_cause = ($request->root_cause);
        $root->what_will_be = ($request->what_will_be);
        $root->what_will_not_be = ($request->what_will_not_be);
        $root->what_rationable = ($request->what_rationable);
        $root->where_will_be = ($request->where_will_be);
        $root->where_will_not_be = ($request->where_will_not_be);
        $root->where_rationable = ($request->where_rationable);
        $root->when_will_be = ($request->when_will_be);
        $root->when_will_not_be = ($request->when_will_not_be);
        $root->when_rationable = ($request->when_rationable);
        $root->coverage_will_be = ($request->coverage_will_be);
        $root->coverage_will_not_be = ($request->coverage_will_not_be);
        $root->coverage_rationable = ($request->coverage_rationable);
        $root->who_will_be = ($request->who_will_be);
        $root->who_will_not_be = ($request->who_will_not_be);
        $root->who_rationable = ($request->who_rationable);
        $root->investigation_summary = ($request->investigation_summary);
        $root->zone = ($request->zone);
        $root->country = ($request->country);
        $root->state = ($request->state);
        $root->city = ($request->city);
        $root->submitted_by = ($request->submitted_by);


        $root->record = ((RecordNumber::first()->value('counter')) + 1);
        $root->initiator_id = Auth::user()->id;
        $root->division_code = $request->division_code;
        $root->intiation_date = $request->intiation_date;
        $root->initiator_Group = $request->initiator_Group;
        $root->short_description = $request->short_description;
        // $root->severity_level = $request->severity_level;

        $root->due_date = $request->due_date;
        $root->assign_id = $request->assign_id;
        $root->Sample_Types = $request->Sample_Types;
        $root->test_lab = $request->test_lab;
        $root->ten_trend = $request->ten_trend;
        // $root->investigators =  $request->investigators;

        if (!empty($request->root_cause_initial_attachment)) {
            $files = [];
            if ($request->hasfile('root_cause_initial_attachment')) {
                foreach ($request->file('root_cause_initial_attachment') as $file) {
                    $name = $request->name . 'root_cause_initial_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
            $root->root_cause_initial_attachment = json_encode($files);
        }
        if (!empty($request->cft_attchament_new)) {
            $files = [];
            if ($request->hasfile('cft_attchament_new')) {
                foreach ($request->file('cft_attchament_new') as $file) {
                    $name = $request->name . 'cft_attchament_new' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
            $root->cft_attchament_new = json_encode($files);
        }
        if (!empty($request->group_attachments_new)) {
            $files = [];
            if ($request->hasfile('group_attachments_new')) {
                foreach ($request->file('group_attachments_new') as $file) {
                    $name = $request->name . 'group_attachments_new' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
            $root->group_attachments_new = json_encode($files);
        }
        
        $root->comments = $request->comments;
        $root->lab_inv_concl = $request->lab_inv_concl;

        if (!empty($request->lab_inv_attach)) {
            $files = [];
            if ($request->hasfile('lab_inv_attach')) {
                foreach ($request->file('lab_inv_attach') as $file) {
                    $name = $request->name . 'lab_inv_attach' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
            $root->lab_inv_attach = json_encode($files);
        }
        $root->qc_head_comments = $request->qc_head_comments;



        if (!empty($request->inv_attach)) {
            $files = [];
            if ($request->hasfile('inv_attach')) {
                foreach ($request->file('inv_attach') as $file) {
                    $name = $request->name . 'inv_attach' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }

            $root->inv_attach = json_encode($files);
        }
        $root->status = 'Opened';
        $root->stage = 1;
        $root->save();


         // -----------grid=------
         $data1 = new RiskAssesmentGrid();
         $data1->risk_id = $root->id;
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
         $data2->risk_id = $root->id;
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
         $data3->risk_id = $root->id;
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
         if (!empty($request->why_root_cause)) {
             $data3->why_root_cause = $request->why_root_cause;
         }
         $data3->save();
 
         // --------------------------------------------
         $data4 = new RiskAssesmentGrid();
         $data4->risk_id = $root->id;
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

        // ----------------------------chemical analysis 1---------------------------------
        $data1 = new RootcauseAnalysisDocDetails();
        $data1->root_id = $root->id;
        $data1->type = "chemical_analysis_1";
        if (!empty($request->questions)) {
            $data1->Question = serialize($request->questions);
        }
        if (!empty($request->response)) {
            $data1->Response = serialize($request->response);
        }
        $data1->save();
        // ----------------------------chemical analysis 2----
        $data2 = new RootcauseAnalysisDocDetails();
        $data2->root_id = $root->id;
        $data2->type = "chemical_analysis_2";
        if (!empty($request->questions2)) {
            $data2->Question = serialize($request->questions2);
        }
        if (!empty($request->response2)) {
            $data2->Response = serialize($request->response2);
        }
        $data2->save();
        // ----------------------------chemical analysis 3----
        $data3 = new RootcauseAnalysisDocDetails();
        $data3->root_id = $root->id;
        $data3->type = "chemical_analysis_3";
        if (!empty($request->questions3)) {
            $data3->Question = serialize($request->questions3);
        }
        if (!empty($request->response3)) {
            $data3->Response = serialize($request->response3);
        }
        $data3->save();
        // ----------------------------chemical analysis 4----
        $data4 = new RootcauseAnalysisDocDetails();
        $data4->root_id = $root->id;
        $data4->type = "chemical_analysis_4";
        if (!empty($request->questions4)) {
            $data4->Question = serialize($request->questions4);
        }
        if (!empty($request->response4)) {
            $data4->Response = serialize($request->response4);
        }
        $data4->save();
        // ----------------------------water analysis 1----
        $data5 = new RootcauseAnalysisDocDetails();
        $data5->root_id = $root->id;
        $data5->type = "water_analysis_1";
        if (!empty($request->questions5)) {
            $data5->Question = serialize($request->questions5);
        }
        if (!empty($request->response5)) {
            $data5->Response = serialize($request->response5);
        }
        $data5->save();
        // ----------------------------water analysis 2----
        $data6 = new RootcauseAnalysisDocDetails();
        $data6->root_id = $root->id;
        $data6->type = "water_analysis_2";
        if (!empty($request->questions6)) {
            $data6->Question = serialize($request->questions6);
        }
        if (!empty($request->response6)) {
            $data6->Response = serialize($request->response6);
        }
        $data6->save();
        // ----------------------------water analysis 3----
        $data7 = new RootcauseAnalysisDocDetails();
        $data7->root_id = $root->id;
        $data7->type = "water_analysis_3";
        if (!empty($request->questions7)) {
            $data7->Question = serialize($request->questions7);
        }
        if (!empty($request->response7)) {
            $data7->Response = serialize($request->response7);
        }
        $data7->save();
        // ----------------------------water analysis 4----
        $data8 = new RootcauseAnalysisDocDetails();
        $data8->root_id = $root->id;
        $data8->type = "water_analysis_4";
        if (!empty($request->questions8)) {
            $data8->Question = serialize($request->questions8);
        }
        if (!empty($request->response8)) {
            $data8->Response = serialize($request->response8);
        }
        $data8->save();
        // ----------------------------Environmental Monitoring I----
        $data9 = new RootcauseAnalysisDocDetails();
        $data9->root_id = $root->id;
        $data9->type = "environment_monitoring_1";
        if (!empty($request->questions9)) {
            $data9->Question = serialize($request->questions9);
        }
        if (!empty($request->response9)) {
            $data9->Response = serialize($request->response9);
        }
        $data9->save();
        // --------------------------------
        $data10 = new RootcauseAnalysisDocDetails();
        $data10->root_id = $root->id;
        $data10->type = "environment_monitoring_2";
        if (!empty($request->questions10)) {
            $data10->Question = serialize($request->questions10);
        }
        if (!empty($request->response10)) {
            $data10->Response = serialize($request->response10);
        }
        $data10->save();
        // ----------------------------Environmental Monitoring 3----
        $data11 = new RootcauseAnalysisDocDetails();
        $data11->root_id = $root->id;
        $data11->type = "environment_monitoring_3";
        if (!empty($request->questions11)) {
            $data11->Question = serialize($request->questions11);
        }
        if (!empty($request->response11)) {
            $data11->Response = serialize($request->response11);
        }
        $data11->save();
        // ----------------------------Environmental Monitoring 4----
        $data12 = new RootcauseAnalysisDocDetails();
        $data12->root_id = $root->id;
        $data12->type = "environment_monitoring_4";
        if (!empty($request->questions12)) {
            $data12->Question = serialize($request->questions12);
        }
        if (!empty($request->response12)) {
            $data12->Response = serialize($request->response12);
        }
        $data12->save();
        // ----------------------------Environmental Monitoring 5----
        $data13 = new RootcauseAnalysisDocDetails();
        $data13->root_id = $root->id;
        $data13->type = "environment_monitoring_5";
        if (!empty($request->questions13)) {
            $data13->Question = serialize($request->questions13);
        }
        if (!empty($request->response13)) {
            $data13->Response = serialize($request->response13);
        }
        $data13->save();
        // ----------------------------Environmental Monitoring 6----
        $data14 = new RootcauseAnalysisDocDetails();
        $data14->root_id = $root->id;
        $data14->type = "environment_monitoring_6";
        if (!empty($request->questions14)) {
            $data14->Question = serialize($request->questions14);
        }
        if (!empty($request->response14)) {
            $data14->Response = serialize($request->response14);
        }
        $data14->save();


        // -------------------------------------------------------
        $record = RecordNumber::first();
        $record->counter = ((RecordNumber::first()->value('counter')) + 1);
        $record->update();
        
        //-----------------------grid
        // $data1 = new RiskAssesmentGrid();
        // $data1->risk_id = $data->id;
        // $data1->type = "effect_analysis";
        // if (!empty($request->risk_factor)) {
        //     $data1->risk_factor = serialize($request->risk_factor);
        // }
        // if (!empty($request->risk_element)) {
        //     $data1->risk_element = serialize($request->risk_element);
        // }
        // if (!empty($request->problem_cause)) {
        //     $data1->problem_cause = serialize($request->problem_cause);
        // }
        // if (!empty($request->existing_risk_control)) {
        //     $data1->existing_risk_control = serialize($request->existing_risk_control);
        // }
        // if (!empty($request->initial_severity)) {
        //     $data1->initial_severity = serialize($request->initial_severity);
        // }
        // if (!empty($request->initial_detectability)) {
        //     $data1->initial_detectability = serialize($request->initial_detectability);
        // }
        // if (!empty($request->initial_probability)) {
        //     $data1->initial_probability = serialize($request->initial_probability);
        // }
        // if (!empty($request->initial_rpn)) {
        //     $data1->initial_rpn = serialize($request->initial_rpn);
        // }
        // if (!empty($request->risk_acceptance)) {
        //     $data1->risk_acceptance = serialize($request->risk_acceptance);
        // }
        // if (!empty($request->risk_control_measure)) {
        //     $data1->risk_control_measure = serialize($request->risk_control_measure);
        // }
        // if (!empty($request->residual_severity)) {
        //     $data1->residual_severity = serialize($request->residual_severity);
        // }
        // if (!empty($request->residual_probability)) {
        //     $data1->residual_probability = serialize($request->residual_probability);
        // }
        // if (!empty($request->residual_detectability)) {
        //     $data1->residual_detectability = serialize($request->residual_detectability);
        // }
        // if (!empty($request->residual_rpn)) {
        //     $data1->residual_rpn = serialize($request->residual_rpn);
        // }
        // if (!empty($request->risk_acceptance2)) {
        //     $data1->risk_acceptance2 = serialize($request->risk_acceptance2);
        // }
        // if (!empty($request->mitigation_proposal)) {
        //     $data1->mitigation_proposal = serialize($request->mitigation_proposal);
        // }

        // $data1->save();

        // // ---------------------------------------
        // $data2 = new RiskAssesmentGrid();
        // $data2->risk_id = $data->id;
        // $data2->type = "fishbone";

        // if (!empty($request->measurement)) {
        //     $data2->measurement = serialize($request->measurement);
        // }
        // if (!empty($request->materials)) {
        //     $data2->materials = serialize($request->materials);
        // }
        // if (!empty($request->methods)) {
        //     $data2->methods = serialize($request->methods);
        // }
        // if (!empty($request->environment)) {
        //     $data2->environment = serialize($request->environment);
        // }
        // if (!empty($request->manpower)) {
        //     $data2->manpower = serialize($request->manpower);
        // }
        // if (!empty($request->machine)) {
        //     $data2->machine = serialize($request->machine);
        // }
        // if (!empty($request->problem_statement)) {
        //     $data2->problem_statement = $request->problem_statement;
        // }
        // $data2->save();
        // // =-------------------------------

        // $data3 = new RiskAssesmentGrid();
        // $data3->risk_id = $data->id;
        // $data3->type = "why_chart";
        // if (!empty($request->why_problem_statement)) {
        //     $data3->why_problem_statement = $request->why_problem_statement;
        // }
        // if (!empty($request->why_1)) {
        //     $data3->why_1 = serialize($request->why_1);
        // }
        // if (!empty($request->why_2)) {
        //     $data3->why_2 = serialize($request->why_2);
        // }
        // if (!empty($request->why_3)) {
        //     $data3->why_3 = serialize($request->why_3);
        // }
        // if (!empty($request->why_4)) {
        //     $data3->why_4 = serialize($request->why_4);
        // }
        // if (!empty($request->why_5)) {
        //     $data3->why_5 = serialize($request->why_5);
        // }
        // if (!empty($request->why_root_cause)) {
        //     $data3->why_root_cause = $request->why_root_cause;
        // }
        // $data3->save();

        // // --------------------------------------------
        // $data4 = new RiskAssesmentGrid();
        // $data4->risk_id = $data->id;
        // $data4->type = "what_who_where";
        // if (!empty($request->what_will_be)) {
        //     $data4->what_will_be = $request->what_will_be;
        // }
        // if (!empty($request->what_will_not_be)) {
        //     $data4->what_will_not_be = $request->what_will_not_be;
        // }
        // if (!empty($request->what_rationable)) {
        //     $data4->what_rationable = $request->what_rationable;
        // }
        // if (!empty($request->where_will_be)) {
        //     $data4->where_will_be = $request->where_will_be;
        // }
        // if (!empty($request->where_will_not_be)) {
        //     $data4->where_will_not_be = $request->where_will_not_be;
        // }
        // if (!empty($request->where_rationable)) {
        //     $data4->where_rationable = $request->where_rationable;
        // }
        // if (!empty($request->coverage_will_be)) {
        //     $data4->coverage_will_be = $request->coverage_will_be;
        // }
        // if (!empty($request->coverage_will_not_be)) {
        //     $data4->coverage_will_not_be = $request->coverage_will_not_be;
        // }
        // if (!empty($request->coverage_rationable)) {
        //     $data4->coverage_rationable = $request->coverage_rationable;
        // }
        // if (!empty($request->who_will_be)) {
        //     $data4->who_will_be = $request->who_will_be;
        // }
        // if (!empty($request->who_will_not_be)) {
        //     $data4->who_will_not_be = $request->who_will_not_be;
        // }
        // if (!empty($request->who_rationable)) {
        //     $data4->who_rationable = $request->who_rationable;
        // } if (!empty($request->when_will_be)) {
        //     $data4->when_will_be = $request->when_will_be;
        // }
        //  if (!empty($request->when_will_not_be)) {
        //     $data4->when_will_not_be = $request->when_will_not_be;
        // }
        //  if (!empty($request->when_rationable)) {
        //     $data4->when_rationable = $request->when_rationable;
        // }
        // $data4->save();



        $history = new RootAuditTrial();
        $history->root_id = $root->id;
        $history->activity_type = 'Division Code';
        $history->previous = "Null";
        $history->current = $root->division_code;
        $history->comment = "NA";
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $root->status;
        $history->save();

        $history = new RootAuditTrial();
        $history->root_id = $root->id;
        $history->activity_type = 'Initiator Group';
        $history->previous = "Null";
        $history->current = $root->initiator_Group;
        $history->comment = "NA";
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $root->status;
        $history->save();

        $history = new RootAuditTrial();
        $history->root_id = $root->id;
        $history->activity_type = 'Short Description';
        $history->previous = "Null";
        $history->current = $root->short_description;
        $history->comment = "Null";
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $root->status;
        $history->save();

        $history = new RootAuditTrial();
        $history->root_id = $root->id;
        $history->activity_type = 'Assign Id';
        $history->previous = "Null";
        $history->current = $root->assign_id;
        $history->comment = "NA";
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $root->status;
        $history->save();

        $history = new RootAuditTrial();
        $history->root_id = $root->id;
        $history->activity_type = 'Sample Types';
        $history->previous = "Null";
        $history->current = $root->Sample_Types;
        $history->comment = "NA";
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $root->status;
        $history->save();

        $history = new RootAuditTrial();
        $history->root_id = $root->id;
        $history->activity_type = 'Test Lab';
        $history->previous = "Null";
        $history->current = $root->test_lab;
        $history->comment = "NA";
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $root->status;
        $history->save();

        $history = new RootAuditTrial();
        $history->root_id = $root->id;
        $history->activity_type = 'Ten Trend';
        $history->previous = "Null";
        $history->current = $root->ten_trend;
        $history->comment = "NA";
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $root->status;
        $history->save();

        $history = new RootAuditTrial();
        $history->root_id = $root->id;
        $history->activity_type = 'Investigators';
        $history->previous = "Null";
        $history->current = $root->investigators;
        $history->comment = "NA";
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $root->status;
        $history->save();

        $history = new RootAuditTrial();
        $history->root_id = $root->id;
        $history->activity_type = 'Attachments';
        $history->previous = "Null";
        $history->current = empty($root->attachments) ? null : $root->attachments;
        $history->comment = "NA";
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $root->status;
        $history->save();

        $history = new RootAuditTrial();
        $history->root_id = $root->id;
        $history->activity_type = 'Comments';
        $history->previous = "Null";
        $history->current = $root->comments;
        $history->comment = "NA";
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $root->status;
        $history->save();

        $history = new RootAuditTrial();
        $history->root_id = $root->id;
        $history->activity_type = 'Lab Inv Concl';
        $history->previous = "Null";
        $history->current = $root->lab_inv_concl;
        $history->comment = "NA";
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $root->status;
        $history->save();

        $history = new RootAuditTrial();
        $history->root_id = $root->id;
        $history->activity_type = 'lab Inv Attach';
        $history->previous = "Null";
        $history->current = $root->lab_inv_attach;
        $history->comment = "NA";
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $root->status;
        $history->save();

        $history = new RootAuditTrial();
        $history->root_id = $root->id;
        $history->activity_type = 'Qc Head Comments';
        $history->previous = "Null";
        $history->current = $root->qc_head_comments;
        $history->comment = "NA";
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $root->status;
        $history->save();

        $history = new RootAuditTrial();
        $history->root_id = $root->id;
        $history->activity_type = 'Inv Attach';
        $history->previous = "Null";
        $history->current = $root->inv_attach;
        $history->comment = "NA";
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $root->status;
        $history->save();


        toastr()->success("Record is created Successfully");
        return redirect(url('rcms/qms-dashboard'));
    }
    public function root_update(Request $request, $id)
    {
        // return $request;

        if (!$request->short_description) {
            toastr()->error("Short description is required");
            return redirect()->back();
        }

        $lastDocument =  RootCauseAnalysis::find($id);
        $root =  RootCauseAnalysis::find($id);
        // $root->record = ((RecordNumber::first()->value('counter')) + 1);
        // $root->initiator_id = Auth::user()->id;
        // $root->division_code = $request->division_code;
        // $root->intiation_date = $request->intiation_date;
        $root->initiator_Group = $request->initiator_Group;
        $root->initiated_through = $request->initiated_through;
        $root->initiated_if_other = ($request->initiated_if_other);
        $root->short_description = $request->short_description;
        $root->severity_level= $request->severity_level;
        $root->Type= ($request->Type);
        $root->priority_level = ($request->priority_level);
        $root->department = ($request->department);
        $root->description = ($request->description);
        $root->investigation_summary = ($request->investigation_summary);
        $root->root_cause_description = ($request->root_cause_description);
        $root->cft_comments_new = ($request->cft_comments_new);
        $root->investigators = ($request->investigators);
        $root->related_url = ($request->related_url);
        // $root->root_cause_methodology = json_encode($request->root_cause_methodology);
        // $root->root_cause_methodology = ($request->root_cause_methodology);
        $root->root_cause_methodology = implode(',', $request->root_cause_methodology);

        $root->country = ($request->country);
        $root->methods = json_encode($request->methods);
         $root->due_date = $request->due_date;
        $root->assign_id = $request->assign_id;
        $root->Sample_Types = $request->Sample_Types;
        $root->test_lab = $request->test_lab;
        $root->ten_trend = $request->ten_trend;
        // $root->investigators =  implode(',', $request->investigators);

        if (!empty($request->attachments)) {
            $files = [];
            if ($request->hasfile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $name = $request->name . 'attachments' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }


            $root->attachments = json_encode($files);
        }
        $root->comments = $request->comments;
        $root->lab_inv_concl = $request->lab_inv_concl;

        if (!empty($request->lab_inv_attach)) {
            $files = [];
            if ($request->hasfile('lab_inv_attach')) {
                foreach ($request->file('lab_inv_attach') as $file) {
                    $name = $request->name . 'lab_inv_attach' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }


            $root->lab_inv_attach = json_encode($files);
        }
        $root->qc_head_comments = $request->qc_head_comments;

        if (!empty($request->inv_attach)) {
            $files = [];
            if ($request->hasfile('inv_attach')) {
                foreach ($request->file('inv_attach') as $file) {
                    $name = $request->name . 'inv_attach' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }


            $root->inv_attach = json_encode($files);
        }
        $root->status = 'Opened';
        $root->stage = 1;
        $root->update();


        if ($lastDocument->division_code != $root->division_code || !empty($request->division_code_comment)) {

            $history = new RootAuditTrial();
            $history->root_id = $id;
            $history->activity_type = 'Division Code';
            $history->previous = $lastDocument->division_code;
            $history->current = $root->division_code;
            $history->comment = $request->division_code_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->initiator_Group != $root->initiator_Group || !empty($request->initiator_Group_comment)) {

            $history = new RootAuditTrial();
            $history->root_id = $id;
            $history->activity_type = 'Initiator Group';
            $history->previous = $lastDocument->initiator_Group;
            $history->current = $root->initiator_Group;
            $history->comment = $request->initiator_Group_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->short_description != $root->short_description || !empty($request->short_description_comment)) {

            $history = new RootAuditTrial();
            $history->root_id = $id;
            $history->activity_type = 'Short Description';
            $history->previous = $lastDocument->short_description;
            $history->current = $root->short_description;
            $history->comment = $request->short_description_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->assign_id != $root->assign_id || !empty($request->assign_id_comment)) {

            $history = new RootAuditTrial();
            $history->root_id = $id;
            $history->activity_type = 'Assign Id';
            $history->previous = $lastDocument->assign_id;
            $history->current = $root->assign_id;
            $history->comment = $request->assign_id_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->Sample_Types != $root->Sample_Types || !empty($request->Sample_Types_comment)) {

            $history = new RootAuditTrial();
            $history->root_id = $id;
            $history->activity_type = 'Sample Types';
            $history->previous = $lastDocument->Sample_Types;
            $history->current = $root->Sample_Types;
            $history->comment = $request->Sample_Types_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->test_lab != $root->test_lab || !empty($request->test_lab_comment)) {

            $history = new RootAuditTrial();
            $history->root_id = $id;
            $history->activity_type = 'Test Lab';
            $history->previous = $lastDocument->test_lab;
            $history->current = $root->test_lab;
            $history->comment = $request->test_lab_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->ten_trend != $root->ten_trend || !empty($request->ten_trend_comment)) {

            $history = new RootAuditTrial();
            $history->root_id = $id;
            $history->activity_type = 'Ten Trend';
            $history->previous = $lastDocument->ten_trend;
            $history->current = $root->ten_trend;
            $history->comment = $request->ten_trend_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();   
        }
        if ($lastDocument->investigators != $root->investigators || !empty($request->investigators_comment)) {

            $history = new RootAuditTrial();
            $history->root_id = $id;
            $history->activity_type = 'Investigators';
            $history->previous = $lastDocument->investigators;
            $history->current = $root->investigators;
            $history->comment = $request->investigators_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->attachments != $root->attachments || !empty($request->attachments_comment)) {

            $history = new RootAuditTrial();
            $history->root_id = $id;
            $history->activity_type = 'Attachments';
            $history->previous = $lastDocument->attachments;
            $history->current = $root->attachments;
            $history->comment = $request->attachments_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->comments != $root->comments || !empty($request->comments_comment)) {

            $history = new RootAuditTrial();
            $history->root_id = $id;
            $history->activity_type = 'Comments';
            $history->previous = $lastDocument->comments;
            $history->current = $root->comments;
            $history->comment = $request->comments_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->lab_inv_concl != $root->lab_inv_concl || !empty($request->lab_inv_concl_comment)) {

            $history = new RootAuditTrial();
            $history->root_id = $id;
            $history->activity_type = 'Lab Inv Concl';
            $history->previous = $lastDocument->lab_inv_concl;
            $history->current = $root->lab_inv_concl;
            $history->comment = $request->lab_inv_concl_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->lab_inv_attach != $root->lab_inv_attach || !empty($request->lab_inv_attach_comment)) {

            $history = new RootAuditTrial();
            $history->root_id = $id;
            $history->activity_type = 'lab Inv Attach';
            $history->previous = $lastDocument->lab_inv_attach;
            $history->current = $root->lab_inv_attach;
            $history->comment = $request->lab_inv_attach_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->qc_head_comments != $root->qc_head_comments || !empty($request->qc_head_comments_comment)) {

            $history = new RootAuditTrial();
            $history->root_id = $id;
            $history->activity_type = 'Qc Head Comments';
            $history->previous = $lastDocument->qc_head_comments;
            $history->current = $root->qc_head_comments;
            $history->comment = $request->qc_head_comments_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }
        if ($lastDocument->inv_attach != $root->inv_attach || !empty($request->inv_attachcomment)) {

            $history = new RootAuditTrial();
            $history->root_id = $id;
            $history->activity_type = 'Inv Attach';
            $history->previous = $lastDocument->inv_attach;
            $history->current = $root->inv_attach;
            $history->comment = $request->inv_attach_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->save();
        }


        toastr()->success("Record is update Successfully");
        return back();
    }


    public function root_show($id)
    {

        $data = RootCauseAnalysis::find($id);
        if(empty($data)) {
            toastr()->error('Invalid ID.');
            return back();
        }
        $data->record = str_pad($data->record, 4, '0', STR_PAD_LEFT);
        $data->assign_to_name = User::where('id', $data->assign_id)->value('name');
        $data->initiator_name = User::where('id', $data->initiator_id)->value('name');
        $dataAnalysis1 = RootcauseAnalysisDocDetails::where('root_id',$data->id)->where('type',"chemical_analysis_1")->first();
        $dataAnalysis2 = RootcauseAnalysisDocDetails::where('root_id',$data->id)->where('type',"chemical_analysis_2")->first();
        $dataAnalysis3 = RootcauseAnalysisDocDetails::where('root_id',$data->id)->where('type',"chemical_analysis_3")->first();
        $dataAnalysis4 = RootcauseAnalysisDocDetails::where('root_id',$data->id)->where('type',"chemical_analysis_4")->first();
        $dataAnalysis5 = RootcauseAnalysisDocDetails::where('root_id',$data->id)->where('type',"water_analysis_1")->first();
        $dataAnalysis6 = RootcauseAnalysisDocDetails::where('root_id',$data->id)->where('type',"water_analysis_2")->first();
        $dataAnalysis7 = RootcauseAnalysisDocDetails::where('root_id',$data->id)->where('type',"water_analysis_3")->first();
        $dataAnalysis8 = RootcauseAnalysisDocDetails::where('root_id',$data->id)->where('type',"water_analysis_4")->first();
        $dataAnalysis9 = RootcauseAnalysisDocDetails::where('root_id',$data->id)->where('type',"environment_monitoring_1")->first();
        $dataAnalysis10 = RootcauseAnalysisDocDetails::where('root_id',$data->id)->where('type',"environment_monitoring_2")->first();
        $dataAnalysis11 = RootcauseAnalysisDocDetails::where('root_id',$data->id)->where('type',"environment_monitoring_3")->first();
        $dataAnalysis12 = RootcauseAnalysisDocDetails::where('root_id',$data->id)->where('type',"environment_monitoring_4")->first();
        $dataAnalysis13 = RootcauseAnalysisDocDetails::where('root_id',$data->id)->where('type',"environment_monitoring_5")->first();
        $dataAnalysis14 = RootcauseAnalysisDocDetails::where('root_id',$data->id)->where('type',"environment_monitoring_6")->first();
        $riskEffectAnalysis = RiskAssesmentGrid::where('risk_id',$data->id)->where('type',"effect_analysis")->first();
        $fishbone = RiskAssesmentGrid::where('risk_id',$data->id)->where('type',"fishbone")->first();
        $whyChart = RiskAssesmentGrid::where('risk_id',$data->id)->where('type',"why_Chart")->first();
        $what_who_where = RiskAssesmentGrid::where('risk_id',$data->id)->where('type',"what_who_where")->first();



        return view('frontend.root-cause-analysis.root_cause_analysisView', compact(
            'data',
            'dataAnalysis1',
            'dataAnalysis2',
            'dataAnalysis3',
            'dataAnalysis4',
            'dataAnalysis5',
            'dataAnalysis6',
            'dataAnalysis7',
            'dataAnalysis8',
            'dataAnalysis9',
            'dataAnalysis10',
            'dataAnalysis11',
            'dataAnalysis12',
            'dataAnalysis13',
            'dataAnalysis14',
            'riskEffectAnalysis',
            'fishbone',
            'whyChart',
            'what_who_where'

        ));
    }

    public function root_send_stage(Request $request, $id)
    {


        if ($request->username == Auth::user()->email && Hash::check($request->password, Auth::user()->password)) {
            $root = RootCauseAnalysis::find($id);

            if ($root->stage == 1) {
                $root->stage = "2";
                $root->status = "Investigation in Progress";
                $root->update();
                toastr()->success('Document Sent');
                return back();
            }
            if ($root->stage == 2) {
                $root->stage = "3";
                $root->status = "Pending Group Review Discussion";
                $root->submitted_by = Auth::user()->name;
                $root->submitted_on = Carbon::now()->format('d-M-Y');
                $root->update();
                toastr()->success('Document Sent');
                return back();
            }
            if ($root->stage == 3) {
                $root->stage = "4";
                $root->status = "Pending Group Review";
                $root->report_result_by = Auth::user()->name;
                $root->report_result_on = Carbon::now()->format('d-M-Y');
                $root->update();
                toastr()->success('Document Sent');
                return back();
            }
            if ($root->stage == 4) {
                $root->stage = "5";
                $root->status = "Pending QA Review";
                $root->update();
                toastr()->success('Document Sent');
                return back();
            }

            if ($root->stage == 5) {
                $root->stage = "6";
                $root->status = "Closed - Done";
                $root->evaluation_complete_by = Auth::user()->name;
                $root->evaluation_complete_on = Carbon::now()->format('d-M-Y');
                $root->update();
                toastr()->success('Document Sent');
                return back();
            }
        } else {
            toastr()->error('E-signature Not match');
            return back();
        }
    }

    public function root_Cancel(Request $request, $id)
    {
        if ($request->username == Auth::user()->email && Hash::check($request->password, Auth::user()->password)) {
            $root = RootCauseAnalysis::find($id);

            $root->stage = "0";
            $root->status = "Closed-Cancelled";
            $root->update();
            $history = new RootCauseAnalysisHistory();
            $history->type = "Root Cause Analysis";
            $history->doc_id = $id;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->stage_id = $root->stage;
            $history->status = $root->status;
            $history->save();
            toastr()->success('Document Sent');
            return back();
        } else {
            toastr()->error('E-signature Not match');
            return back();
        }
    }

    public function root_reject(Request $request, $id)
    {
        if ($request->username == Auth::user()->email && Hash::check($request->password, Auth::user()->password)) {
            $capa = RootCauseAnalysis::find($id);

            if ($capa->stage == 3) {
                $capa->stage = "5";
                $capa->status = "Pending QA Review";
                $capa->update();

                toastr()->success('Document Sent');
                return back();
            }
            if ($capa->stage == 5) {
                $capa->stage = "2";
                $capa->status = "Investigation in Progress";
                $capa->update();

                toastr()->success('Document Sent');
                return back();
            }
            if ($capa->stage == 4) {
                $capa->stage = "2";
                $capa->status = "Investigation in Progress";
                $capa->update();

                toastr()->success('Document Sent');
                return back();
            }
        } else {
            toastr()->error('E-signature Not match');
            return back();
        }
    }


    public function rootAuditTrial($id)
    {
        $audit = RootAuditTrial::where('root_id', $id)->orderByDESC('id')->get()->unique('activity_type');
        $today = Carbon::now()->format('d-m-y');
        $document = RootCauseAnalysis::where('id', $id)->first();
        $document->initiator = User::where('id', $document->initiator_id)->value('name');

        return view("frontend.root-cause-analysis.root-audit-trail", compact('audit', 'document', 'today'));
    }

    public function auditDetailsroot($id)
    {

        $detail = RootAuditTrial::find($id);

        $detail_data = RootAuditTrial::where('activity_type', $detail->activity_type)->where('root_id', $detail->root_id)->latest()->get();

        $doc = RootCauseAnalysis::where('id', $detail->root_id)->first();

        $doc->origiator_name = User::find($doc->initiator_id);
        return view("frontend.root-cause-analysis.root-audit-trial-inner", compact('detail', 'doc', 'detail_data'));
    }

    public static function singleReport($id)
    {
        $data = RootCauseAnalysis::find($id);
        if (!empty($data)) {
            $data->originator_id = User::where('id', $data->initiator_id)->value('name');
            $pdf = App::make('dompdf.wrapper');
            $time = Carbon::now();
            $pdf = PDF::loadview('frontend.root-cause-analysis.singleReport', compact('data'))
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
            $canvas->page_text($width / 3, $height / 2, $data->status, null, 60, [0, 0, 0], 2, 6, -20);
            return $pdf->stream('Root-cause' . $id . '.pdf');
        }
    }

    public static function auditReport($id)
    {
        $doc = RootCauseAnalysis::find($id);
        if (!empty($doc)) {
            $doc->originator_id = User::where('id', $doc->initiator_id)->value('name');
            $data = RootAuditTrial::where('root_id', $id)->get();
            $pdf = App::make('dompdf.wrapper');
            $time = Carbon::now();
            $pdf = PDF::loadview('frontend.root-cause-analysis.auditReport', compact('data', 'doc'))
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
            $canvas->page_text($width / 3, $height / 2, $doc->status, null, 60, [0, 0, 0], 2, 6, -20);
            return $pdf->stream('Root-Audit' . $id . '.pdf');
        }
    }
}
