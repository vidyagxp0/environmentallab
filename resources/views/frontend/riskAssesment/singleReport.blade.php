<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Connexo - Software</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
</head>

<style>
    body {
        font-family: 'Roboto', sans-serif;
        margin: 0;
        padding: 0;
        min-height: 100vh;
    }

    .w-10 {
        width: 10%;
    }

    .w-20 {
        width: 20%;
    }

    .w-25 {
        width: 25%;
    }

    .w-30 {
        width: 30%;
    }

    .w-40 {
        width: 40%;
    }

    .w-50 {
        width: 50%;
    }

    .w-60 {
        width: 60%;
    }

    .w-70 {
        width: 70%;
    }

    .w-80 {
        width: 80%;
    }

    .w-90 {
        width: 90%;
    }

    .w-100 {
        width: 100%;
    }

    .h-100 {
        height: 100%;
    }

    header table,
    header th,
    header td,
    footer table,
    footer th,
    footer td,
    .border-table table,
    .border-table th,
    .border-table td {
        border: 1px solid black;
        border-collapse: collapse;
        font-size: 0.9rem;
        vertical-align: middle;
    }

    table {
        width: 100%;
    }

    th,
    td {
        padding: 10px;
        text-align: left;
    }

    footer .head,
    header .head {
        text-align: center;
        font-weight: bold;
        font-size: 1.2rem;
    }

    @page {
        size: A4;
        margin-top: 160px;
        margin-bottom: 60px;
    }

    header {
        position: fixed;
        top: -140px;
        left: 0;
        width: 100%;
        display: block;
    }

    footer {
        width: 100%;
        position: fixed;
        display: block;
        bottom: -40px;
        left: 0;
        font-size: 0.9rem;
    }

    footer td {
        text-align: center;
    }

    .inner-block {
        padding: 10px;
    }

    .inner-block tr {
        font-size: 0.8rem;
    }

    .inner-block .block {
        margin-bottom: 30px;
    }

    .inner-block .block-head {
        font-weight: bold;
        font-size: 1.1rem;
        padding-bottom: 5px;
        border-bottom: 2px solid #4274da;
        margin-bottom: 10px;
        color: #4274da;
    }

    .inner-block th,
    .inner-block td {
        vertical-align: baseline;
    }

    .table_bg {
        background: #4274da57;
    }
</style>

<body>

    <header>
        <table>
            <tr>
                <td class="w-70 head">
                Risk Assesment Single Report
                </td>
                <td class="w-30">
                    <div class="logo">
                        <img src="https://dms.mydemosoftware.com/user/images/logo1.png" alt="" class="w-100">
                    </div>
                </td>
            </tr>
        </table>
        <table>
            <tr>
                <td class="w-30">
                    <strong>Risk Assesment No.</strong>
                </td>
                <td class="w-40">
                   {{ Helpers::divisionNameForQMS($data->division_id) }}/{{ Helpers::year($data->created_at) }}/{{ str_pad($data->record, 4, '0', STR_PAD_LEFT) }}
                </td>
                <td class="w-30">
                    <strong>Record No.</strong> {{ str_pad($data->record, 4, '0', STR_PAD_LEFT) }}
                </td>
            </tr>
        </table>
    </header>

    <div class="inner-block">
        <div class="content-table">
            <div class="block">
                <div class="block-head">
                    General Information
                </div>
                <table>
                    <tr>  {{ $data->created_at }} added by {{ $data->originator }}
                        <th class="w-20">Initiator</th>
                        <td class="w-30">{{ $data->originator }}</td>
                        <th class="w-20">Date Initiation</th>
                        <td class="w-30">{{ Helpers::getdateFormat($data->created_at) }}</td>
                    </tr>
                    <tr>
                        <th class="w-20">Site/Location Code</th>
                        <td class="w-30">@if($data->division_code){{ $data->division_code }} @else Not Applicable @endif</td>
                        <th class="w-20"> Assigned To</th>
                        <td class="w-30">@if($data->assign_id){{ $data->assign_id }} @else Not Applicable @endif</td>
                    </tr>
                    <tr>
                        <th class="w-20">Severity Level</th>
                        <td class="w-30">@if($data->severity2_level){{ $data->severity2_level}} @else Not Applicable @endif</td>
                        <th class="w-20">State/District</th>
                        <td class="w-30">@if($data->state){{ $data->state }} @else Not Applicable @endif</td>
                    </tr>
                    <tr>
                        <th class="w-20">Initiator Group</th>
                        <td class="w-30">@if($data->Initiator_Group){{ $data->Initiator_Group }} @else Not Applicable @endif</td>
                        <th class="w-20">Initiator Group Code</th>
                        <td class="w-30">@if($data->division_id){{ $data->division_id }} @else Not Applicable @endif</td>
                    </tr>
                    <tr>
                        <th class="w-20">Short Description</th>
                        <td class="w-80" colspan="3">
                            @if($data->short_description){{ $data->short_description }}@else Not Applicable @endif
                        </td>
                    </tr>
                    <tr>
                        <th class="w-20">Due Date</th>
                        <td class="w-80" colspan="3"> @if($data->due_date){{ $data->due_date }} @else Not Applicable @endif</td>
                    </tr>
                    <tr>
                        <th class="w-20">Department(s)</th>

                        <td class="w-30">@if($data->departments){{ $data->departments }}@else Not Applicable @endif</td>
                        <th class="w-20">Team Members</th>
                        <td class="w-30">@if($data->team_members){{ $data->team_members }}@else Not Applicable @endif</td>
                    </tr>
                    <tr>
                        <th class="w-20">Risk/Opportunity Description</th>
                        <td class="w-30">@if($data->description){{ $data->description }} @else Not Applicable @endif</td>
                        <th class="w-20">Risk/Opportunity Comments</th>
                        <td class="w-30">@if($data->comments){{ $data->comments }} @else Not Applicable @endif</td>
                    </tr>
                    
                </table>
            </div>

            <div class="block">
                <div class="head">

                    <table>
                        <tr>
                            <th class="w-20">Source of Risk</th>
                            <td class="w-80">@if($data->source_of_risk){{ $data->source_of_risk }}@else Not Applicable @endif</td>
                        </tr>

                        <tr>
                            <th class="w-20">Type..</th>
                            <td class="w-80">@if($data->type){{ $data->type }}@else Not Applicable @endif</td>
                        </tr>
                        <tr>
                            <th class="w-20">Priority Level</th>
                            <td class="w-80">@if($data->priority_level){{ $data->priority_level }}@else Not Applicable @endif</td>
                        </tr>
                        <tr>
                            <th class="w-20">zone</th>
                            <td class="w-80">@if($data->zone){{ $data->zone }}@else Not Applicable @endif</td>
                        </tr>
                        <tr>
                            <th class="w-20">Country</th>
                            <td class="w-80">@if($data->Country){{ $data->Country }}@else Not Applicable @endif</td>
                        </tr>
                        <tr>
                            <th class="w-20">City</th>
                            <td class="w-80">@if($data->city){{ $data->city }}@else Not Applicable @endif</td>
                        </tr>
                        <tr>
                            <th class="w-20">Description</th>
                            <td class="w-80">@if($data->description){{ $data->description }}@else Not Applicable @endif</td>
                        </tr>
                        <tr>
                            <th class="w-20">Description</th>
                            <td class="w-80">@if($data->description){{ $data->description }}@else Not Applicable @endif</td>
                        </tr>
                        <tr>
                            <th class="w-20">Comments</th>
                            <td class="w-80">@if($data->comments){{ $data->comments }}@else Not Applicable @endif</td>
                        </tr>

                    </table>
                </div>
            </div>
            <div class="block">
                <div class="head">
                    <div class="block-head">
                       Risk Details
                    </div>
                    <table>
                        <tr>
                            <th class="w-20">Department(s)
                            </th>
                            <td class="w-80">@if($data->departments2){{ $data->departments2 }}@else Not Applicable @endif</td>

                        </tr>
                        <tr>
                            <th class="w-20">Site Name</th>
                            <td class="w-80">@if($data->site_name){{ $data->site_name }}@else Not Applicable @endif</td>

                        </tr>
                        <tr>
                            <th class="w-20">Building.</th>
                            <td class="w-80"> {{ $data->building }}</td>
                        </tr>
                        <tr>
                            <th class="w-20">Floor...</th>

                                <td class="w-80"> {{ $data->floor }}</td>

                        </tr>
                        <tr>
                            <th class="w-20">Room</th>
                            <td class="w-80">@if($data->room){{ $data->room }}@else Not Applicable @endif</td>

                        </tr>
                        <tr>
                            <th class="w-20">Duration</th>
                            <td class="w-80">@if($data->duration){{ $data->duration }}@else Not Applicable @endif</td>

                        </tr>
                        <tr>
                            <th class="w-20">Hazard</th>
                            <td class="w-80">@if($data->hazard){{ $data->hazard }}@else Not Applicable @endif</td>

                        </tr>
                        <tr>
                            <th class="w-20">Room</th>
                            <td class="w-80">@if($data->room2){{ $data->room2 }}@else Not Applicable @endif</td>

                        </tr>
                        <tr>
                            <th class="w-20">Regulatory Climate</th>
                            <td class="w-80">@if($data->regulatory_climate){{ $data->regulatory_climate }}@else Not Applicable @endif</td>

                        </tr>
                        <tr>
                            <th class="w-20">Number of Employees</th>
                            <td class="w-80">@if($data->Number_of_employees){{ $data->Number_of_employees }}@else Not Applicable @endif</td>

                        </tr>
                        <tr>
                            <th class="w-20">Risk Management Strategy</th>
                            <td class="w-80">@if($data->risk_management_strategy){{ $data->risk_management_strategy }}@else Not Applicable @endif</td>

                        </tr>
                        <tr>
                            <th class="w-20">Related Record</th>
                            <td class="w-80">@if($data->related_record){{ $data->related_record }}@else Not Applicable @endif</td>

                        </tr>

                    </table>
                </div>
            </div>
            <div class="block">
                <div class="block-head">
                    Work Group Assignment
                </div>
                <table>
                <tr>
                        <th class="w-20">Scheduled Start Date</th>
                        <td class="w-30">@if($data->schedule_start_date1){{ $data->schedule_start_date1 }}@else Not Applicable @endif</td>
                        <th class="w-20">Scheduled End Date</th>
                        <td class="w-30">@if($data->schedule_end_date1){{ $data->schedule_end_date1 }}@else Not Applicable @endif</td>
                    </tr>
                    <tr>
                        <th class="w-50" colspan="2">Estimated Man-Hours</th>
                        <td class="w-50" colspan="2">@if($data->estimated_man_hours){{ $data->estimated_man_hours }}@else Not Applicable @endif</td>
                    </tr>
                    <tr>
                        <th class="w-20">Estimated Cost
                        </th>
                        <td class="w-30">@if($data->estimated_cost){{ $data->estimated_cost }}@else Not Applicable @endif</td>
                        <th class="w-20">Currency (If Any)
                        </th>
                        <td class="w-30">@if($data->currency){{ $data->currency }}@else Not Applicable @endif</td>
                    </tr>
                    <tr>
                        <th class="w-20">Team Members</th>
                        <td class="w-30">@if($data->team_members2){{ $data->team_members2 }}@else Not Applicable @endif</td>
                        <th class="w-20">Training Requirement</th>
                        <td class="w-30">@if($data->training_require){{ $data->training_require }}@else Not Applicable @endif</td>
                    </tr>
                    <tr>
                        <th class="w-20">Justification / Rationale</th>
                        <td class="w-30">@if($data->justification){{ $data->justification }}@else Not Applicable @endif</td>
                        <th class="w-20">References
                        </th>
                        <td class="w-30">@if($data->reference){{ $data->reference }}@else Not Applicable @endif</td>
                    </tr>
                    <tr>
                        <th class="w-20">Action Plan</th>
                        <td class="w-30">@if($data->action_plan){{ $data->action_plan }}@else Not Applicable @endif</td>
                        <th class="w-20">Work Group Attachments
                        </th>
                        <td class="w-30">@if($data->work_group_attachments){{ $data->work_group_attachments}}@else Not Applicable @endif</td>
                    </tr>


                </table>
            </div>
            <div class="block">
                <div class="head">
                    <div class="block-head">
                      Risk Analysis
                    </div>
                    <table>
                        <tr>
                            <th class="w-20">Cost of Risks</th>
                            <td class="w-80">

                                <div>
                                    @if($data->cost_of_risk){{ $data->cost_of_risk }}@else Not Applicable @endif
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th class="w-20">Environmental Impact</th>
                            <td class="w-80">
                                <div>
                                    @if($data->environmental_impact){{ $data->environmental_impact }}@else Not Applicable @endif
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th class="w-20">Public Perception Impact
                            </th>
                            <td class="w-80">
                                <div>
                                    @if($data->public_perception_impact){{ $data->public_perception_impact }}@else Not Applicable @endif
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th class="w-20">Calculated Risk
                            </th>
                            <td class="w-80">
                                <div>
                                    @if($data->calculated_risk){{ $data->calculated_risk }}@else Not Applicable @endif
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th class="w-20">Impacted Objects
                            </th>
                            <td class="w-80">
                                <div>
                                    @if($data->impacted_objects){{ $data->impacted_objects }}@else Not Applicable @endif
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th class="w-20">Severity Rate
                            </th>
                            <td class="w-80">
                                <div>
                                    @if($data->severity_rate){{ $data->severity_rate }}@else Not Applicable @endif
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th class="w-20">Occurrence
                            </th>
                            <td class="w-80">
                                <div>
                                    @if($data->occurrence){{ $data->occurrence }}@else Not Applicable @endif
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th class="w-20">Detection
                            </th>
                            <td class="w-80">
                                <div>
                                    @if($data->detection){{ $data->detection }}@else Not Applicable @endif
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th class="w-20">RPN
                            </th>
                            <td class="w-80">
                                <div>
                                    @if($data->rpn){{ $data->rpn }}@else Not Applicable @endif
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th class="w-20">Residual Risk
                            </th>
                            <td class="w-80">
                                <div>
                                    @if($data->residual_risk){{ $data->residual_risk }}@else Not Applicable @endif
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th class="w-20">Residual Risk Impact
                            </th>
                            <td class="w-80">
                                <div>
                                    @if($data->residual_risk_impact){{ $data->residual_risk_impact }}@else Not Applicable @endif
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th class="w-20">Residual Risk Probability
                            </th>
                            <td class="w-80">
                                <div>
                                    @if($data->residual_risk_probability){{ $data->residual_risk_probability }}@else Not Applicable @endif
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th class="w-20">Comments
                            </th>
                            <td class="w-80">
                                <div>
                                    @if($data->comments2){{ $data->comments2 }}@else Not Applicable @endif
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            {{--  <div class="block">
                <div class="block-head">
                    Activity Log
                </div>
                <table>
                    <tr>
                        <th class="w-20">Audit Schedule By</th>
                        <td class="w-30">{{ $data->audit_schedule_by }}</td>
                        <th class="w-20">Audit Schedule On</th>
                        <td class="w-30">{{ $data->created_at }}</td>
                    </tr>
                    <tr>
                        <th class="w-20">Audit preparation completed by</th>
                        <td class="w-30">{{ $data->audit_preparation_completed_by }}</td>
                        <th class="w-20">Audit preparation completed On</th>
                        <td class="w-30">{{ $data->audit_preparation_completed_on }}</td>
                    </tr>
                    <tr>
                        <th class="w-20">More Information Required By</th>
                        <td class="w-30"{{ $data->audit_mgr_more_info_reqd_by }}</td>
                        <th class="w-20">More Information Required On</th>
                        <td class="w-30">{{ $data->audit_mgr_more_info_reqd_on }}</td>
                    </tr>
                    <tr>
                        <th class="w-20">Audit Observation Submitted By</th>
                        <td class="w-30">{{ $data->audit_observation_submitted_by }}</td>
                        <th class="w-20">Supervisor Reviewed On(QA)</th>
                        <td class="w-30">{{ $data->audit_observation_submitted_on }}</td>
                    </tr>
                    <tr>
                        <th class="w-20">Audit Lead More Info Reqd By
                        </th>
                        <td class="w-30">{{ $data->audit_lead_more_info_reqd_by }}</td>
                        <th class="w-20">More Information Req. On</th>
                        <td class="w-30">{{ $data->audit_lead_more_info_reqd_on }}</td>
                    </tr>
                    <tr>
                        <th class="w-20">Audit Response Completed By</th>
                        <td class="w-30">{{ $data->audit_response_completed_by }}</td>
                        <th class="w-20">QA Review Completed On</th>
                        <td class="w-30">{{ $data->audit_response_completed_on }}</td>
                    </tr>
                    <tr>
                        <th class="w-20">Response Feedback Verified By</th>
                        <td class="w-30">{{ $data->response_feedback_verified_by }}</td>
                        <th class="w-20">
                            Response Feedback Verified On</th>
                        <td class="w-30">{{ $data->response_feedback_verified_on }}</td>
                    </tr>


                </table>

            </div>  --}}
        </div>
        <div class="block">
                <div class="block-head">
                    Activity Log
                </div>
                <table>
                    <tr>
                        <th class="w-20">Submitted By</th>
                        <td class="w-30">{{ $data->submitted_by }}</td>
                        <th class="w-20">Submitted On</th>
                        <td class="w-30">{{ Helpers::getdateFormat($data->submitted_on) }}</td>
                    </tr>
                    <tr>
                        <th class="w-20">Evaluated By</th>
                        <td class="w-30">{{ $data->evaluated_by }}</td>
                        <th class="w-20">Evaluated On</th>
                        <td class="w-30">{{ Helpers::getdateFormat($data->evaluated_on) }}</td>
                    </tr>
                    <tr>
                        <th class="w-20">Plan Approved By</th>
                        <td class="w-30">{{ $data->plan_approved_by }}</td>
                        <th class="w-20">Plan Approved On</th>
                        <td class="w-30">{{ Helpers::getdateFormat($data->plan_approved_on) }}</td>
                    </tr>
                    <tr>
                        <th class="w-20">Risk Analysis Completed By</th>
                        <td class="w-30">{{ $data->risk_Analysis_completd_by }}</td>
                        <th class="w-20">Risk Analysis Completed On</th>
                        <td class="w-30">{{ Helpers::getdateFormat($data->risk_Analysis_completd_on) }}</td>
                    </tr>
                    

                </table>
            </div>
        </div>
    </div>

    

    <footer>
        <table>
            <tr>
                <td class="w-30">
                    <strong>Printed On :</strong> {{ date('d-M-Y') }}
                </td>
                <td class="w-40">
                    <strong>Printed By :</strong> {{ Auth::user()->name }}
                </td>
                <td class="w-30">
                    <strong>Page :</strong> 1 of 1
                </td>
            </tr>
        </table>
    </footer>

</body>

</html>
