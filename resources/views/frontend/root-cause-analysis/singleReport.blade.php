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
                   Root Cause Analysis Single Report
                </td>
                <td class="w-30">
                    <div class="logo">
                        <img src="http://environmentallab.doculife.co.in/user/images/logo.png" alt="" class="w-100">
                    </div>
                </td>
            </tr>
        </table>
        <table>
            <tr>
                <td class="w-30">
                    <strong>Root Cause Analysis No.</strong>
                </td>
                <td class="w-40">
                   {{ Helpers::divisionNameForQMS($data->division_id) }}/RCA/{{ Helpers::year($data->created_at) }}/{{ str_pad($data->record, 4, '0', STR_PAD_LEFT) }}
                </td>
                <td class="w-30">
                    <strong>Record No.</strong> {{ str_pad($data->record, 4, '0', STR_PAD_LEFT) }}
                </td>
            </tr>
        </table>
    </header>
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
                    <strong></strong>
                </td>
            </tr>
        </table>
    </footer>
    <footer>
        <table>
            <tr>
                <td class="w-30">
                    <strong>Printed On :</strong> {{ date('d-M-Y') }}
                </td>
                <td class="w-40">
                    <strong>Printed By :</strong> {{ Auth::user()->name }}
                </td>
                {{--  <td class="w-30">
                    <strong>Page :</strong> 1 of 1
                </td>  --}}
            </tr>
        </table>
    </footer>

    <div class="inner-block">
        <div class="content-table">
            <div class="block">
                <div class="block-head">
                    Investigation
                </div>
                <table>
                    <tr>
                        <th class="w-20">Record Number</th>
                        <td class="w-80">@if($data->record){{ Helpers::divisionNameForQMS($data->division_id) }}/RCA/{{ Helpers::year($data->created_at) }}/{{ str_pad($data->record, 4, '0', STR_PAD_LEFT) }} @else Not Applicable @endif</td>
                        <th class="w-20">Date Initiation</th>
                        <td class="w-80">{{ Helpers::getdateFormat($data->created_at) }}</td>
                    </tr>
                    <tr>
                        <th class="w-20">Site/Location Code</th>
                        <td class="w-80">@if($data->division_code){{ $data->division_code }} @else Not Applicable @endif</td>

                    </tr>
                    <tr>
                        {{ $data->created_at }} added by {{ $data->originator }}
                        <th class="w-20">Initiator</th>
                        <td class="w-80">{{ Helpers::getInitiatorName($data->initiator_id) }}</td>
                        <th class="w-20">Severity Level</th>
                        <td class="w-80">@if($data->severity_level){{ $data->severity_level }} @else Not Applicable @endif</td>
                    </tr>
                      <tr>

                        <th class="w-20">Initiator Group</th>
                        <td class="w-80">@if($data->initiator_Group){{ Helpers::getInitiatorGroupFullName($data->initiator_Group) }} @else Not Applicable @endif</td>
                        <th class="w-20">Initiator Group Code</th>
                        <td class="w-80">@if($data->initiator_group_code){{ $data->initiator_group_code }} @else Not Applicable @endif</td>
                    </tr>
                    <tr>
                        <th class="w-20">Short Description</th>
                        <td class="w-80" colspan="3">
                            @if($data->short_description){{ $data->short_description }}@else Not Applicable @endif
                        </td>
                    </tr>
                    <tr>
                        <th class="w-20">Assigned To</th>
                        <td class="w-80">@if($data->assign_to){{ Helpers::getInitiatorName($data->assign_to) }} @else Not Applicable @endif</td>

                        <th class="w-20">Due Date</th>
                        <td class="w-80"> @if($data->due_date){{ Helpers::getdateFormat($data->due_date) }} @else Not Applicable @endif</td>
                   </tr>
                   <tr>
                   <th class="w-20">Others</th>
                   <td class="w-80" colspan="3">@if($data->initiated_if_other){{ $data->initiated_if_other }}@else Not Applicable @endif</td>
                   </tr>

                   <tr>
                    <th class="w-20">Type</th>
                    <td class="w-80" colspan="3">@if($data->Type){{ $data->Type }}@else Not Applicable @endif</td>
                    </tr>

                    <tr>
                        <th class="w-20">Priority Level</th>
                        <td class="w-80">@if($data->priority_level){{ $data->priority_level }}@else Not Applicable @endif</td>
                    </tr>
                    <tr>
                        {{-- <th class="w-20">Additional Investigators</th>
                        <td class="w-80">@if($data->investigators){{ $data->investigators }}@else Not Applicable @endif</td> --}}
                        <th class="w-20">Department(s)</th>
                        <td class="w-80">@if($data->department){{ $data->department }}@else Not Applicable @endif</td>
                    </tr>
                    <tr>
                    <th class="w-20">Initiated Through
                        </th>
                        <td class="w-80">@if($data->initiated_through){{ $data->initiated_through }}@else Not Applicable @endif</td>
                    </tr>
                        </table>

                        <div class="block">
                            <div class="block-head">
                            Investigation Details
                            </div>
                            <table>

                                <tr>
                                    <th class="w-20">Description</th>
                                    <td class="w-80" colspan="3">@if($data->description){{ $data->description }}@else Not Applicable @endif</td>
                                </tr>
                                <tr>
                                    <th class="w-20">Comments</th>
                                    <td class="w-80" colspan="3">@if($data->comments){{ $data->comments }}@else Not Applicable @endif</td>
                                </tr>
                                <tr>
                                    <th class="w-20">Related URL</th>
                                    <td class="w-80">@if($data->related_url){{ $data->related_url }}@else Not Applicable @endif</td>
                                </tr>

                           </table>
                <div class="border-table">
                    <div class="block-head">
                        Initial Attachment
                    </div>
                    <table>

                        <tr class="table_bg">
                            <th class="w-20">S.N.</th>
                            <th class="w-60">Batch No</th>
                        </tr>
                            @if($data->root_cause_initial_attachment)
                            @foreach(json_decode($data->root_cause_initial_attachment) as $key => $file)
                                <tr>
                                    <td class="w-20">{{ $key + 1 }}</td>
                                    <td class="w-20"><a href="{{ asset('upload/' . $file) }}" target="_blank"><b>{{ $file }}</b></a> </td>
                                </tr>
                            @endforeach
                            @else
                        <tr>
                            <td class="w-20">1</td>
                            <td class="w-20">Not Applicable</td>
                        </tr>
                        @endif

                    </table>
                </div>

            </div>
            <div class="block">
                <div class="block-head">
                    Investigation & Root Cause
                </div>
                    <table>
                        <tr>
                            <th class="w-20">Root Cause Methodology</th>
                            <td class="w-80">@if($data->root_cause_methodology){{ $data->root_cause_methodology }}@else Not Applicable @endif</td>
                        </tr>

                        <tr>
                            <th class="w-20">Root Cause Description</th>
                            <td class="w-80" colspan="3">@if($data->root_cause_description){{ $data->root_cause_description }}@else Not Applicable @endif</td>
                        </tr>
                        <tr>
                            <th class="w-20">Investigation Summary</th>
                            <td class="w-80" colspan="3">@if($data->investigation_summary){{ $data->investigation_summary }}@else Not Applicable @endif</td>
                        </tr>
                        <!-- <tr>
                            <th class="w-20">Attachments</th>
                            <td class="w-80">@if($data->attachments)<a href="{{ asset('upload/document/',$data->attachments) }}">{{ $data->attachments }}@else Not Applicable @endif</td>
                        </tr> -->
                        <tr>
                            <th class="w-20">Comments</th>
                            <td class="w-80" colspan="3">@if($data->comments){{ $data->comments }}@else Not Applicable @endif</td>
                        </tr>

                    </table>

                    <div class="border-table tbl-bottum ">
                        <div class="block-head">
                          Root Cause
                        </div>
                        <table>

                            <tr class="table_bg">
                                <th class="w-10">Row #</th>
                                <th class="w-30">Root Cause Category</th>
                                <th class="w-30">Root Cause Sub-Category</th>
                                <th class="w-30">Probability</th>
                                <th class="w-30">Remarks</th>
                            </tr>
                                {{-- @if($data->root_cause_initial_attachment)
                                @foreach(json_decode($data->root_cause_initial_attachment) as $key => $file)
                                    <tr>
                                        <td class="w-20">{{ $key + 1 }}</td>
                                        <td class="w-20"><a href="{{ asset('upload/' . $file) }}" target="_blank"><b>{{ $file }}</b></a> </td>
                                    </tr>
                                @endforeach
                                @else --}}
                                @if (!empty($data->Root_Cause_Category))
                               @foreach (unserialize($data->Root_Cause_Category) as $key => $Root_Cause_Category)
                                <tr>
                                 <td class="w-10">{{ $key + 1 }}</td>
                                 <td class="w-30">{{ unserialize($data->Root_Cause_Category)[$key] ? unserialize($data->Root_Cause_Category)[$key] : '' }}</td>
                                 <td class="w-30">{{ unserialize($data->Root_Cause_Sub_Category)[$key] ? unserialize($data->Root_Cause_Sub_Category)[$key] : '' }}</td>
                                 <td class="w-30">{{ unserialize($data->Probability)[$key] ? unserialize($data->Probability)[$key] : '' }}</td>
                                 <td class="w-30">{{ unserialize($data->Remarks)[$key] ?? null }}</td>
                                </tr>
                                 @endforeach
                                @else

                            @endif

                        </table>
                    </div><br>

                    <div class="border-table  tbl-bottum">
                        <div class="block-head">
                            Failure Mode and Effect Analysis
                        </div>
                        <table>

                            <tr class="table_bg">
                                <th class="w-10">Row #</th>
                                <th class="w-30">Risk Factor</th>
                                <th class="w-30">Risk element</th>
                                <th class="w-30">Probable cause of risk element</th>
                                <th class="w-30">Existing Risk Controls</th>
                            </tr>
                                {{-- @if($data->root_cause_initial_attachment)
                                @foreach(json_decode($data->root_cause_initial_attachment) as $key => $file)
                                    <tr>
                                        <td class="w-20">{{ $key + 1 }}</td>
                                        <td class="w-20"><a href="{{ asset('upload/' . $file) }}" target="_blank"><b>{{ $file }}</b></a> </td>
                                    </tr>
                                @endforeach
                                @else --}}
                                @if (!empty($data->risk_factor))
                                 @foreach (unserialize($data->risk_factor) as $key => $riskFactor)
                                <tr>
                                 <td class="w-10">{{ $key + 1 }}</td>
                                 <td class="w-30">{{ $riskFactor }}</td>
                                 <td class="w-30">{{ unserialize($data->risk_element)[$key] ?? null }}</td>
                                 <td class="w-30">{{ unserialize($data->problem_cause)[$key] ?? null }}</td>
                                 <td class="w-30">{{ unserialize($data->existing_risk_control)[$key] ?? null }}</td>
                                </tr>
                                 @endforeach
                                @else

                              @endif

                        </table>

                    </div><br>

                    <div  class="border-table  tbl-bottum">
                        <table>
                            <tr class="table_bg">
                                <th class="w-10">Row #</th>
                                <th class="w-30">Initial Severity- H(3)/M(2)/L(1)</th>
                                <th class="w-30">Initial Probability- H(3)/M(2)/L(1)</th>
                                <th class="w-30">Initial Detectability- H(1)/M(2)/L(3)</th>
                                <th class="w-30">Initial RPN</th>
                            </tr>
                            @if (!empty($data->risk_factor))
                            @foreach (unserialize($data->risk_factor) as $key => $riskFactor)
                           <tr>
                            <td class="w-10">{{ $key + 1 }}</td>
                            <td class="w-30">{{ unserialize($data->initial_severity)[$key] }}</td>
                            <td class="w-30">{{ (unserialize($data->initial_detectability)[$key]) }}</td>
                            <td class="w-30">{{ (unserialize($data->initial_probability)[$key] )  }}</td>
                            <td class="w-30">{{ unserialize($data->initial_rpn)[$key] }}</td>
                           </tr>
                            @endforeach
                           @else

                         @endif
                        </table>
                    </div><br>

                    <div  class="border-table  tbl-bottum">
                        <table>
                            <tr class="table_bg">
                                <th class="w-10">Row #</th>
                                <th class="w-30">Risk Acceptance (Y/N)</th>
                                <th class="w-30">Proposed Additional Risk control measure (Mandatory for Risk elements having RPN>4)</th>
                                <th class="w-30">Residual Severity- H(3)/M(2)/L(1)</th>
                                <th class="w-30">Residual Probability- H(3)/M(2)/L(1)</th>
                            </tr>
                            @if (!empty($data->risk_factor))
                            @foreach (unserialize($data->risk_factor) as $key => $riskFactor)
                           <tr>
                                    <td class="w-10">{{ $key + 1 }}</td>
                                    <td class="w-30">{{ (unserialize($data->risk_acceptance)[$key]) }}</td>
                                    <td class="w-30">{{ unserialize($data->risk_control_measure)[$key] }}</td>
                                    <td class="w-30">{{ (unserialize($data->residual_severity)[$key])  }}</td>
                                    <td class="w-30">{{(unserialize($data->residual_probability)[$key] ) }}</td>
                           </tr>
                            @endforeach
                           @else

                         @endif
                        </table>
                    </div><br>
                    <div class="border-table  tbl-bottum">
                        <table>
                            <tr class="table_bg">
                                <th class="w-10">Row #</th>
                                <th class="w-30">Residual Detectability- H(1)/M(2)/L(3)</th>
                                <th class="w-30">Residual RPN</th>
                                <th class="w-30">Risk Acceptance (Y/N)</th>
                                <th class="w-30">Mitigation proposal (Mention either CAPA reference number, IQ, OQ or PQ)</th>
                            </tr>
                            @if (!empty($data->risk_factor))
                            @foreach (unserialize($data->risk_factor) as $key => $riskFactor)
                           <tr>
                                    <td class="w-10">{{ $key + 1 }}</td>
                                    <td class="w-30">{{  (unserialize($data->residual_detectability)[$key]) }}</td>
                                    <td class="w-30">{{  unserialize($data->residual_rpn)[$key] }}</td>
                                    <td class="w-30">{{ (unserialize($data->risk_acceptance2)[$key])   }}</td>
                                    <td class="w-30">{{unserialize($data->mitigation_proposal)[$key]}}</td>
                           </tr>
                            @endforeach
                           @else

                         @endif
                        </table>
                    </div><br>

                    <div class="block-head">
                        Fishbone or Ishikawa Diagram
                    </div>
                    <table>
                    - <tr>
                        <th class="w-20">Measurement</th>
                        {{-- <td class="w-80">@if($riskgrdfishbone->measurement){{ $riskgrdfishbone->measurement }}@else Not Applicable @endif</td> --}}
                             <td class="w-80" colspan="3">
                            @php
                                $measurement = unserialize($data->measurement);
                            @endphp

                            @if(is_array($measurement))
                                @foreach($measurement as $value)
                                    {{ htmlspecialchars($value) }}
                                @endforeach
                            @elseif(is_string($measurement))
                                {{ htmlspecialchars($measurement) }}
                            @else
                                Not Applicable
                            @endif
                              </td>
                        </tr>
                        <tr>
                        <th class="w-20">Materials</th>
                        {{-- <td class="w-80">@if($data->materials){{ $data->materials }}@else Not Applicable @endif</td> --}}
                             <td class="w-80" colspan="3">
                            @php
                                $materials = unserialize($data->materials);
                            @endphp

                            @if(is_array($materials))
                                @foreach($materials as $value)
                                    {{ htmlspecialchars($value) }}
                                @endforeach
                            @elseif(is_string($materials))
                                {{ htmlspecialchars($materials) }}
                            @else
                                Not Applicable
                            @endif
                               </td>

                    </tr>
                       <tr>
                        <th class="w-20">Methods</th>
                        {{-- <td class="w-80">@if($data->methods){{ $data->methods }}@else Not Applicable @endif</td> --}}
                           <td class="w-80" colspan="3">
                            @php
                                $methods = unserialize($data->methods);
                            @endphp

                            @if(is_array($methods))
                                @foreach($methods as $value)
                                    {{ htmlspecialchars($value) }}
                                @endforeach
                            @elseif(is_string($methods))
                                {{ htmlspecialchars($methods) }}
                            @else
                                Not Applicable
                            @endif
                           </td>
                        </tr>
                        <tr>
                        <th class="w-20">Environment</th>
                        {{-- <td class="w-80">@if($data->environment){{ $data->environment }}@else Not Applicable @endif</td> --}}
                            <td class="w-80" colspan="3">
                            @php
                                $environment = unserialize($data->environment);
                            @endphp

                            @if(is_array($environment))
                                @foreach($environment as $value)
                                    {{ htmlspecialchars($value) }}
                                @endforeach
                            @elseif(is_string($environment))
                                {{ htmlspecialchars($environment) }}
                            @else
                                Not Applicable
                            @endif
                            </td>
                    </tr>
                    <tr>
                        <th class="w-20">Manpower</th>
                        {{-- <td class="w-80">@if($data->manpower){{ $data->manpower }}@else Not Applicable @endif</td> --}}
                            <td class="w-80" colspan="3">
                            @php
                                $manpower = unserialize($data->manpower);
                            @endphp

                            @if(is_array($manpower))
                                @foreach($manpower as $value)
                                    {{ htmlspecialchars($value) }}
                                @endforeach
                            @elseif(is_string($manpower))
                                {{ htmlspecialchars($manpower) }}
                            @else
                                Not Applicable
                            @endif
                           </td>
                        </tr>
                        <tr>
                        <th class="w-20">Machine</th>
                        {{-- <td class="w-80">@if($data->machine){{ $data->machine }}@else Not Applicable @endif</td> --}}
                          <td class="w-80" colspan="3">
                            @php
                                $machine = unserialize($data->machine);
                            @endphp

                            @if(is_array($machine))
                                @foreach($machine as $value)
                                    {{ htmlspecialchars($value) }}
                                @endforeach
                            @elseif(is_string($machine))
                                {{ htmlspecialchars($machine) }}
                            @else
                                Not Applicable
                            @endif
                          </td>
                    </tr>

             </table>
             <div class="inner-block">
                <label class="Summer"style="font-weight: bold; font-size: 13px; display: inline-block; width: 75px;">
                    Problem Statement1</label>
                <span style="font-size: 0.8rem; margin-left: 60px;">
                    @if ($data->problem_statement)
                        {{ $data->problem_statement }}
                    @else
                        Not Applicable
                    @endif
                </span>
            </div>

             <div class="block-head mt-1">
                Why-Why Chart
            </div>

            <div class="inner-block">
                <label class="Summer"style="font-weight: bold; font-size: 13px; display: inline-block; width: 75px;">
                    Problem Statement</label>
                <span style="font-size: 0.8rem; margin-left: 60px;">
                    @if ($data->why_problem_statement)
                        {{ $data->why_problem_statement }}
                    @else
                        Not Applicable
                    @endif
                </span>
            </div>


            <table>


                <tr>

                            <th class="w-20">Why 1 </th>
                            {{-- <td class="w-80">@if($data->why_1){{ $data->why_1 }}@else Not Applicable @endif</td> --}}
                            <td class="w-80" >
                                @php
                                    $why_1 = unserialize($data->why_1);
                                @endphp

                                @if(is_array($why_1))
                                    @foreach($why_1 as $value)
                                        {{ htmlspecialchars($value) }}
                                    @endforeach
                                @elseif(is_string($why_1))
                                    {{ htmlspecialchars($why_1) }}
                                @else
                                    Not Applicable
                                @endif
                                </td>
                 </tr>
                <tr>
                    <th class="w-20" >Why 2</th>
                    {{-- <td class="w-80">@if($data->why_2){{ $data->why_2 }}@else Not Applicable @endif</td> --}}
                    <td class ="w-80">
                        @php
                            $why_2 = unserialize($data->why_2);
                        @endphp

                        @if(is_array($why_2))
                            @foreach($why_2 as $value)
                                {{ htmlspecialchars($value) }}
                            @endforeach
                        @elseif(is_string($why_2))
                            {{ htmlspecialchars($why_2) }}
                        @else
                            Not Applicable
                        @endif
                    </td>
                </tr>
               <tr>

                <th class="w-20">Why 3</th>
                {{-- <td class="w-80">@if($data->why_3){{ $data->why_3 }}@else Not Applicable @endif</td> --}}
                <td class="w-80">
                    @php
                        $why_3 = unserialize($data->why_3);
                    @endphp

                    @if(is_array($why_3))
                        @foreach($why_3 as $value)
                            {{ htmlspecialchars($value) }}
                        @endforeach
                    @elseif(is_string($why_3))
                        {{ htmlspecialchars($why_3) }}
                    @else
                        Not Applicable
                    @endif
                      </td>
               </tr>
               <tr>
                <th class="w-20">Why 4</th>
                {{-- <td class="w-80">@if($data->why_4){{ $data->why_4 }}@else Not Applicable @endif</td> --}}
                <td class="w-80">
                    @php
                        $why_4 = unserialize($data->why_4);
                    @endphp

                    @if(is_array($why_4))
                        @foreach($why_4 as $value)
                            {{ htmlspecialchars($value) }}
                        @endforeach
                    @elseif(is_string($why_4))
                        {{ htmlspecialchars($why_4) }}
                    @else
                        Not Applicable
                    @endif
                      </td>
               </tr>
              <tr>

                <th class="w-20">Why5</th>
                {{-- <td class="w-80">@if($data->why_4){{ $data->why_4 }}@else Not Applicable @endif</td> --}}
                <td class="w-80" colspan="3">
                    @php
                        $why_5 = unserialize($data->why_5);
                    @endphp

                    @if(is_array($why_5))
                        @foreach($why_5 as $value)
                            {{ htmlspecialchars($value) }}
                        @endforeach
                    @elseif(is_string($why_5))
                        {{ htmlspecialchars($why_5) }}
                    @else
                        Not Applicable
                    @endif
                      </td>
              </tr>
     </table>

     <div class="inner-block">
        <label class="Summer"
            style="font-weight: bold; font-size: 13px; display: inline-block; width: 75px;">
            Root Cause :</label>
        <span style="font-size: 0.8rem; margin-left: 60px;">
            @if ($data->why_root_cause)
                {{ $data->why_root_cause }}
            @else
                Not Applicable
            @endif
        </span>
    </div>
     <div class="block-head">
        Is/Is Not Analysis
    </div>



    <table>
    <tr>
        <th class="w-20">What Will Be</th>
        <td class="w-80">@if($data->what_will_be){{ $data->what_will_be }}@else Not Applicable @endif</td>
    </tr>
    <tr>
        <th class="w-20">What Will Not Be </th>
        <td class="w-80">@if($data->what_will_not_be){{ $data->what_will_not_be }}@else Not Applicable @endif</td>
    </tr>
    <tr>
        <th class="w-20">What Will Rationale </th>
        <td class="w-80">@if($data->what_rationable){{ $data->what_rationable }}@else Not Applicable @endif</td>
    </tr>
    <tr>
        <th class="w-20">Where Will Be</th>
        <td class="w-80">@if($data->where_will_be){{ $data->where_will_be }}@else Not Applicable @endif</td>
    </tr>
    <tr>
            <th class="w-20">Where Will Not Be </th>
            <td class="w-80">@if($data->where_will_not_be){{ $data->where_will_not_be }}@else Not Applicable @endif</td>
    </tr>
     <tr>
            <th class="w-20">Where Will Rationale </th>
            <td class="w-80">@if($data->where_rationable){{ $data->where_rationable }}@else Not Applicable @endif</td>

       <tr>
        <th class="w-20">When Will Be</th>
        <td class="w-80">@if($data->when_will_be){{ $data->when_will_be }}@else Not Applicable @endif</td>
       </tr>
       <tr>
        <th class="w-20">When Will Not Be </th>
        <td class="w-80">@if($data->when_will_not_be){{ $data->when_will_not_be }}@else Not Applicable @endif</td>
       </tr>
    <tr>
       <th class="w-20">When Will Rationale </th>
        <td class="w-80">@if($data->when_rationable){{ $data->when_rationable }}@else Not Applicable @endif</td>
    </tr>
    <tr>
        <th class="w-20">Coverage Will Be</th>
        <td class="w-80">@if($data->coverage_will_be){{ $data->coverage_will_be }}@else Not Applicable @endif</td>
    </tr>
    <tr>
        <th class="w-20">Coverage Will Not Be </th>
        <td class="w-80">@if($data->coverage_will_not_be){{ $data->coverage_will_not_be }}@else Not Applicable @endif</td>
    </tr>
    <tr>
        <th class="w-20">Coverage Will Rationale </th>
        <td class="w-80">@if($data->coverage_rationable){{ $data->coverage_rationable }}@else Not Applicable @endif</td>
    </tr>
    <tr>
        <th class="w-20">Who Will Be</th>
        <td class="w-80">@if($data->who_will_be){{ $data->who_will_be }}@else Not Applicable @endif</td>
    </tr>

    <tr>

        <th class="w-20">Who Will Not Be </th>
        <td class="w-80">@if($data->who_will_not_be){{ $data->who_will_not_be }}@else Not Applicable @endif</td>
    </tr>
    <tr>

        <th class="w-20">Who Will Rationale </th>
        <td class="w-80">@if($data->who_rationable){{ $data->who_rationable }}@else Not Applicable @endif</td>

    </tr>
</table>
                </div>
            </div>
            <div class="block">
                <div class="block-head">
                    QA Review
                </div>

                    <table>

                        <tr>
                            <th class="w-20">Final Comments</th>
                              <td class="w-80">@if($data->cft_comments_new){{ $data->cft_comments_new }}@else Not Applicable @endif</td>
                        </tr>

                    </table>
                    <div class="border-table">
                        <div class="block-head">
                            Final Attachment

                        </div>
                        <table>

                            <tr class="table_bg">
                                <th class="w-20">S.N.</th>
                                <th class="w-60">Batch No</th>
                            </tr>
                                @if($data->cft_attchament_new)
                                @foreach(json_decode($data->cft_attchament_new) as $key => $file)
                                    <tr>
                                        <td class="w-20">{{ $key + 1 }}</td>
                                        <td class="w-20"><a href="{{ asset('upload/' . $file) }}" target="_blank"><b>{{ $file }}</b></a> </td>
                                    </tr>
                                @endforeach
                                @else
                            <tr>
                                <td class="w-20">1</td>
                                <td class="w-20">Not Applicable</td>
                            </tr>
                            @endif

                        </table>
                    </div>
                </div>


                <div class="block">
                    <div class="block-head">
                        Activity log
                    </div>
                    <table>

                    <tr>
                        <th class="w-20">Acknowledge By</th>
                        <td class="w-30">{{ $data->acknowledge_by }}</td>
                        <th class="w-20">Acknowledge By</th>
                        <td class="w-30">{{ Helpers::getdateFormat($data->acknowledge_on) }}</td>
                    </tr>
                    <tr>
                        <th class="w-20">Submit By</th>
                        <td class="w-30">{{ $data->submitted_by }}</td>
                        <th class="w-20">Submit On</th>
                        <td class="w-30">{{ Helpers::getdateFormat($data->submitted_on) }}</td>
                    </tr>
                     <tr>
                        <th class="w-20">QA Review Completed By</th>
                        <td class="w-30">{{ $data->qA_review_complete_by }}</td>
                        <th class="w-20">QA Review Completed On</th>
                        <td class="w-30">{{ Helpers::getdateFormat($data->qA_review_complete_on) }}</td>
                    </tr>
                    <tr>
                        <th class="w-20">More Information Required By</th>
                        <td class="w-30">{{ $data->more_info_by }}</td>
                        <th class="w-20">More Information Required On</th>
                        <td class="w-30">{{ Helpers::getdateFormat($data->more_info_on) }}</td>
                    </tr>
                    {{-- <tr>
                        <th class="w-20">Audit preparation completed by</th>
                        <td class="w-30">{{ $data->audit_preparation_completed_by }}</td>
                        <th class="w-20">Audit preparation completed On</th>
                        <td class="w-30">{{ Helpers::getdateFormat($data->audit_preparation_completed_on) }}</td>
                    </tr> --}}
                    <tr>
                        <th class="w-20">Cancelled By</th>
                        <td class="w-30">{{ $data->cancelled_by }}</td>
                        <th class="w-20">Cancelled On</th>
                        <td class="w-30">{{ Helpers::getdateFormat($data->cancelled_on) }}</td>
                    </tr>

                </table>
            </div>
        </div>
    </div>



</body>

</html>
