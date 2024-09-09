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
                    External Audit Single Report
                </td>
                <td class="w-30">
                    <div class="logo" style="position: relative; height: 60px; width: 150px;">
                        <img src="https://environmentallab.doculife.co.in/user/images/logo1.png" alt=""
                            style="width: 60px; position: absolute; left: 40%;">
                    </div>
                </td>
            </tr>
        </table>
        <table>
            <tr>
                <td class="w-30">
                    <strong>External Audit No.</strong>
                </td>
                <td class="w-40">
                    {{ Helpers::divisionNameForQMS($data->division_id) }}/EA/{{ Helpers::year($data->created_at) }}/{{ str_pad($data->record, 4, '0', STR_PAD_LEFT) }}
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
                    <tr>
                        <th class="w-20">Record Number</th>
                        <td class="w-30">
                            {{ Helpers::divisionNameForQMS($data->division_id) }}/EA/{{ Helpers::year($data->created_at) }}/{{ str_pad($data->record, 4, '0', STR_PAD_LEFT) }}
                        </td>
                        {{-- {{ $data->created_at }} added by {{ $data->originator }} --}}
                        <th class="w-20">Initiator</th>
                        <td class="w-30">{{ Helpers::getInitiatorName($data->initiator_id) }}</td>
                    </tr>
                    <tr>
                        <th class="w-20">Date of Initiation</th>
                        <td class="w-30">{{ Helpers::getdateFormat($data->intiation_date) }}</td>

                        <th class="w-20">Name of Auditor</th>
                        <td class="w-30">
                            @if ($data->external_auditor_name)
                                {{ $data->external_auditor_name }}
                            @else
                                Not Applicable
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th class="w-20">Site/Location Code</th>
                        <td class="w-30">
                            @if ($data->division_code)
                                {{ $data->division_code }}
                            @else
                                Not Applicable
                            @endif
                        </td>
                        <th class="w-20">Area of Auditing</th>
                        <td class="w-30">
                            @if ($data->area_of_auditing)
                                {{ $data->area_of_auditing }}
                            @else
                                Not Applicable
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th class="w-20">Assigned To</th>
                        <td class="w-30">
                            @if ($data->multiple_assignee_to)
                                @foreach (explode(',', $data->multiple_assignee_to) as $Key => $value)
                                    {{ Helpers::getInitiatorName($value) }}
                                @endforeach
                            @else
                                Not Applicable
                            @endif
                        </td>
                        <th class="w-20">Due Date</th>
                        <td class="w-30">
                            @if ($data->due_date)
                                {{ Helpers::getdateFormat($data->due_date) }}
                            @else
                                Not Applicable
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th class="w-20">Initiator Group</th>
                        <td class="w-30">
                            @if ($data->Initiator_Group)
                                {{ \Helpers::getInitiatorGroupFullName($data->Initiator_Group) }}
                            @else
                                Not Applicable
                            @endif
                        </td>
                        <th class="w-20">Initiator Group Code</th>
                        <td class="w-30">
                            @if ($data->initiator_group_code)
                                {{ $data->initiator_group_code }}
                            @else
                                Not Applicable
                            @endif
                        </td>
                    </tr>
                    <tr>

                        <th class="w-20">External Agencies </th>
                        <td class="w-30">
                            @if ($data->external_agencies)
                                {{ $data->external_agencies }}
                            @else
                                Not Applicable
                            @endif
                        </td>

                        <th class="w-20"> Severity Level</th>
                        <td class="w-30">
                            @if ($data->severity_level)
                                {{ $data->severity_level }}
                            @else
                                Not Applicable
                            @endif
                        </td>
                    </tr>
                    <tr>

                        <th class="w-20">Others</th>
                        <td class="w-30" colspan="3">
                            @if ($data->others)
                                {{ $data->others }}
                            @else
                                Not Applicable
                            @endif
                        </td>

                    </tr>
                    <tr>
                        <th class="w-20">Short Description</th>
                        <td class="w-30" colspan="3">
                            @if ($data->short_description)
                                {{ $data->short_description }}
                            @else
                                Not Applicable
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th class="w-20">Description</th>
                        <td class="w-30" colspan="3">
                            @if ($data->initial_comments)
                                {{ $data->initial_comments }}
                            @else
                                Not Applicable
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th class="w-20">Reason For Audit</th>
                        <td class="w-30" colspan="3">
                            @if ($data->reason_for_audit)
                                {{ $data->reason_for_audit }}
                            @else
                                Not Applicable
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th class="w-20">Type of Audit</th>
                        <td class="w-30">
                            @if ($data->audit_type)
                                {{ $data->audit_type }}
                            @else
                                Not Applicable
                            @endif
                        </td>
                    </tr>
                    <tr>

                        <th class="w-20">If Others</th>
                        <td class="w-30" colspan="3">
                            @if ($data->if_other)
                                {{ $data->if_other }}
                            @else
                                Not Applicable
                            @endif
                        </td>

                    </tr>

                    <tr>
                        <th class="w-20">Initiated Through</th>
                        <td class="w-30">
                            @if ($data->initiated_through)
                                {{ $data->initiated_through }}
                            @else
                                Not Applicable
                            @endif
                        </td>
                    </tr>
                    <tr>

                        <th class="w-20">Others</th>
                        <td class="w-30" colspan="3">
                            @if ($data->initiated_if_other)
                                {{ $data->initiated_if_other }}
                            @else
                                Not Applicable
                            @endif
                        </td>

                    </tr>

                    {{-- <tr>
                        <th class="w-20">Audit Type</th>
                        <td class="w-30">
                            @if ($data->audit_type)
                                {{ $data->audit_type }}
                            @else
                                Not Applicable
                            @endif
                        </td>
                    </tr> --}}


                </table>
                <div class="border-table">
                    <div class="block-head">
                        Initial Attachment
                    </div>
                    <table>

                        <tr class="table_bg">
                            <th class="w-20">S.N.</th>
                            <th class="w-60">File</th>
                        </tr>
                        @if ($data->inv_attachment)
                            @foreach (json_decode($data->inv_attachment) as $key => $file)
                                <tr>
                                    <td class="w-20">{{ $key + 1 }}</td>
                                    <td class="w-20"><a href="{{ asset('upload/' . $file) }}"
                                            target="_blank"><b>{{ $file }}</b></a> </td>
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
                <div class="head">
                    <div class="block-head">
                        Audit Planning
                    </div>
                    <table>
                        <tr>
                            <th class="w-30">Audit Schedule Start Date</th>
                            <td class="w-20">
                                @if ($data->start_date)
                                    {{ Helpers::getdateFormat($data->start_date) }}
                                @else
                                    Not Applicable
                                @endif
                            </td>
                            <th class="w-30">Audit Schedule End Date</th>
                            <td class="w-20">
                                @if ($data->end_date)
                                    {{ Helpers::getdateFormat($data->end_date) }}
                                @else
                                    Not Applicable
                                @endif
                            </td>

                        </tr>
                        <tr>
                            <th class="w-20">Comments (If Any)</th>
                            <td class="w-30" colspan="3">
                                @if ($data->if_comments)
                                    {{ $data->if_comments }}
                                @else
                                    Not Applicable
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="w-20">Product/Material Name</th>
                            <td class="w-30" colspan="3">
                                @if ($data->material_name)
                                    {{ $data->material_name }}
                                @else
                                    Not Applicable
                                @endif
                            </td>
                        </tr>

                    </table>
                </div>
            </div>

            {{-- <div class="block-head">
                <table>
                    <div class="block">
                        <div class="block-head">
                            Audit Agenda
                        </div>
                        <div class="border-table">
                            <table>
                                <tr class="table_bg">
                                    <th>Row #</th>
                                    <th>Area of Audit</th>
                                    <th>Scheduled Start Date</th>
                                    <th>Scheduled Start Time</th>
                                    <th>Scheduled End Date</th>
                                    <th>Scheduled End Time</th>
                                    <th>Auditor</th>
                                    <th>Auditee</th>
                                    <th>Remarks</th>
                                </tr>
                                @if ($grid_data->start_date)
                                    @foreach (unserialize($grid_data->start_date) as $key => $temps)
                                        <tr style="color: black; font-weight: normal;">
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ unserialize($grid_data->area_of_audit)[$key] ? unserialize($grid_data->area_of_audit)[$key] : '' }}
                                            </td>
                                            <td>{{ Helpers::getdateFormat(unserialize($grid_data->start_date)[$key]) }}
                                            </td>
                                            <td>{{ unserialize($grid_data->start_time)[$key] ? unserialize($grid_data->start_time)[$key] : '' }}
                                            </td>
                                            <td>{{ Helpers::getdateFormat(unserialize($grid_data->end_date)[$key]) }}
                                            </td>
                                            <td>{{ unserialize($grid_data->end_time)[$key] ? unserialize($grid_data->end_time)[$key] : '' }}
                                            </td>
                                            {{-- <td>{{ $grid_data->auditor }}</td> --}}
            {{-- <td>{{ isset(unserialize($grid_data->auditor)[$key]) ? Helpers::getInitiatorName(unserialize($grid_data->auditor)[$key]) : '' }}
                                            </td>
                                            <td>{{ isset(unserialize($grid_data->auditee)[$key]) ? Helpers::getInitiatorName(unserialize($grid_data->auditee)[$key]) : '' }}
                                            </td> --}}

            {{-- <td>{{ unserialize($grid_data->auditee)[$key] ? (unserialize($grid_data->auditee)[$key] == $value->id ? 'selected' : ' ') : '' }}</td> --}}
            {{-- <td>{{ unserialize($grid_data->auditee)[$key] ? unserialize($grid_data->auditee)[$key] : '' }}</td> --}}
            {{-- <td>{{ unserialize($grid_data->remark)[$key] ? unserialize($grid_data->remark)[$key] : '' }}
                                            </td>

                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td>Not Applicable</td>
                                        <td>Not Applicable</td>
                                        <td>Not Applicable</td>
                                        <td>Not Applicable</td>
                                        <td>Not Applicable</td>
                                    </tr>
                                @endif

                            </table>
                        </div>
                    </div>
                </table> --}}
            {{-- </div> --}}

            <div class="block-head">
                <div class="block">
                    <div class="block-head">
                        Audit Agenda - Part 1
                    </div>
                    <div class="border-table">
                        <table>
                            <tr class="table_bg">
                                <th>Row #</th>
                                <th>Area of Audit</th>
                                <th>Scheduled Start Date</th>
                                <th>Scheduled Start Time</th>
                            </tr>
                            @if ($grid_data->start_date)
                                @foreach (unserialize($grid_data->start_date) as $key => $temps)
                                    <tr style="color: black; font-weight: normal;">
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ unserialize($grid_data->area_of_audit)[$key] ? unserialize($grid_data->area_of_audit)[$key] : '' }}
                                        </td>
                                        <td>{{ Helpers::getdateFormat(unserialize($grid_data->start_date)[$key]) }}
                                        </td>
                                        <td>{{ unserialize($grid_data->start_time)[$key] ? unserialize($grid_data->start_time)[$key] : '' }}
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="4">Not Applicable</td>
                                </tr>
                            @endif
                        </table>
                    </div>
                </div>

                <div class="block">
                    <div class="block-head">
                        Audit Agenda - Part 2
                    </div>
                    <div class="border-table">
                        <table>
                            <tr class="table_bg">
                                <th>Row #</th>
                                <th>Scheduled End Date</th>
                                <th>Scheduled End Time</th>
                                <th>Auditor</th>
                                <th>Auditee</th>
                                <th>Remarks</th>
                            </tr>
                            @if ($grid_data->start_date)
                                @foreach (unserialize($grid_data->start_date) as $key => $temps)
                                    <tr style="color: black; font-weight: normal;">
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ Helpers::getdateFormat(unserialize($grid_data->end_date)[$key]) }}</td>
                                        <td>{{ unserialize($grid_data->end_time)[$key] ? unserialize($grid_data->end_time)[$key] : '' }}
                                        </td>
                                        <td>{{ isset(unserialize($grid_data->auditor)[$key]) ? Helpers::getInitiatorName(unserialize($grid_data->auditor)[$key]) : '' }}
                                        </td>
                                        <td>{{ isset(unserialize($grid_data->auditee)[$key]) ? Helpers::getInitiatorName(unserialize($grid_data->auditee)[$key]) : '' }}
                                        </td>
                                        <td>{{ unserialize($grid_data->remark)[$key] ? unserialize($grid_data->remark)[$key] : '' }}
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="6">Not Applicable</td>
                                </tr>
                            @endif
                        </table>
                    </div>
                </div>
            </div>




            <div class="block">
                <div class="head">
                    <div class="block-head">
                        Audit Preparation
                    </div>
                    <table>
                        <tr>
                            <th class="w-20">Lead Auditor</th>
                            <td class="w-30" colspan="3">
                                @if ($data->lead_auditor)
                                    {{ $data->lead_auditor }}
                                @else
                                    Not Applicable
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="w-20">External Auditor Details</th>
                            <td class="w-30" colspan="3">
                                @if ($data->Auditor_Details)
                                    {{ $data->Auditor_Details }}
                                @else
                                    Not Applicable
                                @endif
                            </td>

                        </tr>
                        <tr>
                            <th class="w-20">External Auditing Agency</th>
                            <td class="w-30" colspan="3">
                                @if ($data->External_Auditing_Agency)
                                    {{ $data->External_Auditing_Agency }}
                                @else
                                    Not Applicable
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="w-20">Relevant Guidelines / Industry Standards</th>
                            <td class="w-30" colspan="3">
                                @if ($data->Relevant_Guidelines)
                                    {{ $data->Relevant_Guidelines }}
                                @else
                                    Not Applicable
                                @endif
                            </td>

                        </tr>
                        <tr>
                            <th class="w-20">QA Comments</th>
                            <td class="w-30" colspan="3">
                                @if ($data->QA_Comments)
                                    {{ $data->QA_Comments }}
                                @else
                                    Not Applicable
                                @endif
                            </td>
                        </tr>
                        {{-- <tr>

                            <th class="w-20">Guideline Attachment</th>
                            <td class="w-30">
                                @if ($data->file_attachment_guideline)
                                    {{ $data->file_attachment_guideline }}
                                @else
                                    Not Applicable
                                @endif
                            </td>
                        </tr> --}}
                        <tr>
                            <th class="w-20">Audit Category</th>
                            <td class="w-30">
                                @if ($data->Audit_Category)
                                    {{ $data->Audit_Category }}
                                @else
                                    Not Applicable
                                @endif
                            </td>

                        </tr>
                        <tr>
                            <th class="w-20">Supplier/Vendor/Manufacturer Site</th>
                            <td class="w-30" colspan="3">
                                @if ($data->Supplier_Site)
                                    {{ $data->Supplier_Site }}
                                @else
                                    Not Applicable
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="w-20">Supplier/Vendor/Manufacturer Details</th>
                            <td class="w-30" colspan="3">
                                @if ($data->Supplier_Details)
                                    {{ $data->Supplier_Details }}
                                @else
                                    Not Applicable
                                @endif
                            </td>

                        </tr>
                        <tr>
                            <th class="w-20">Audit Team</th>
                            <td class="w-30" colspan="3">
                                @if ($data->Audit_team)
                                    {{ $data->Audit_team }}
                                @else
                                    Not Applicable
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="w-20">Auditee</th>
                            <td class="w-30" colspan="3">
                                @if ($data->Auditee)
                                    @php
                                        $auditees = explode(',', $data->Auditee);
                                    @endphp
                                    @foreach ($auditees as $key => $value)
                                        {{ Helpers::getInitiatorName($value) }}{{ $key < count($auditees) - 1 ? ', ' : '' }}&nbsp;
                                    @endforeach
                                @else
                                    Not Applicable
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="w-20">Comments</th>
                            <td class="w-30" colspan="3">
                                @if ($data->Comments)
                                    {{ $data->Comments }}
                                @else
                                    Not Applicable
                                @endif
                            </td>
                        </tr>
                        {{-- <tr>
                            <th class="w-20">Audit Comments</th>
                            <td class="w-30" colspan="3">
                                @if ($data->Audit_Comments1)
                                    {{ $data->Audit_Comments1 }}
                                @else
                                    Not Applicable
                                @endif
                            </td>
                        </tr> --}}
                    </table>
                </div>

            </div>
            <div class="border-table">
                <div class="block-head">
                    File Attachment
                </div>
                <table>

                    <tr class="table_bg">
                        <th class="w-20">S.N.</th>
                        <th class="w-60">File</th>
                    </tr>
                    @if ($data->file_attachment)
                        @foreach (json_decode($data->file_attachment) as $key => $file)
                            <tr>
                                <td class="w-20">{{ $key + 1 }}</td>
                                <td class="w-20"><a href="{{ asset('upload/' . $file) }}"
                                        target="_blank"><b>{{ $file }}</b></a> </td>
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
            <div class="border-table">
                <div class="block-head">
                    Guideline Attachment
                </div>
                <table>

                    <tr class="table_bg">
                        <th class="w-20">S.N.</th>
                        <th class="w-60">File</th>
                    </tr>
                    @if ($data->file_attachment_guideline)
                        @foreach (json_decode($data->file_attachment_guideline) as $key => $file)
                            <tr>
                                <td class="w-20">{{ $key + 1 }}</td>
                                <td class="w-20"><a href="{{ asset('upload/' . $file) }}"
                                        target="_blank"><b>{{ $file }}</b></a> </td>
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
            <div class="block">
                <div class="head">
                    <div class="block-head">
                        Audit Execution
                    </div>
                    <table>

                        <tr>

                            <th class="w-20">Audit Start Date</th>
                            <td class="w-30">
                                <div>
                                    @if ($data->audit_start_date)
                                        {{ Helpers::getdateFormat($data->audit_start_date) }}
                                    @else
                                        Not Applicable
                                    @endif
                                </div>
                            </td>
                            <th class="w-20">Audit End Date</th>
                            <td class="w-30">
                                <div>
                                    @if ($data->audit_end_date)
                                        {{ Helpers::getdateFormat($data->audit_end_date) }}
                                    @else
                                        Not Applicable
                                    @endif
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th class="w-20">Audit Comments
                            </th>
                            <td class="w-80" colspan="3">
                                <div>
                                    @if ($data->Audit_Comments1)
                                        {{ $data->Audit_Comments1 }}
                                    @else
                                        Not Applicable
                                    @endif
                                </div>
                            </td>
                        </tr>

                    </table>
                    <div class="block-head">
                        <table>
                            <div class="block">
                                <div class="block-head">
                                    Observation Details
                                </div>
                                <div class="border-table">
                                    <table>
                                        <tr class="table_bg">
                                            <th>Row#</th>
                                            <th>Observation Details</th>
                                            <th>Pre Comments</th>
                                            <th>CAPA Details if any</th>
                                            <th>Post Comments</th>
                                        </tr>
                                        @if ($grid_data1->observation_id)
                                            @foreach (unserialize($grid_data1->observation_id) as $key => $tempData)
                                                <tr style="color: black; font-weight: normal;">
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>{{ $tempData ? $tempData : '' }}</td>
                                                    <td>{{ unserialize($grid_data1->observation_description)[$key] ? unserialize($grid_data1->observation_description)[$key] : '' }}
                                                    </td>
                                                    <td>{{ unserialize($grid_data1->area)[$key] ? unserialize($grid_data1->area)[$key] : '' }}
                                                    </td>
                                                    <td>{{ unserialize($grid_data1->auditee_response)[$key] ? unserialize($grid_data1->auditee_response)[$key] : '' }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td>Not Applicable</td>
                                                <td>Not Applicable</td>
                                                <td>Not Applicable</td>
                                                <td>Not Applicable</td>
                                                <td>Not Applicable</td>
                                            </tr>
                                        @endif

                                    </table>
                                </div>
                            </div>
                        </table>
                    </div>


                    <div class="border-table">
                        <div class="block-head">
                            Audit Attachments
                        </div>
                        <table>

                            <tr class="table_bg">
                                <th class="w-20">S.N.</th>
                                <th class="w-60">File</th>
                            </tr>
                            @if ($data->Audit_file)
                                @foreach (json_decode($data->Audit_file) as $key => $file)
                                    <tr>
                                        <td class="w-20">{{ $key + 1 }}</td>
                                        <td class="w-20"><a href="{{ asset('upload/' . $file) }}"
                                                target="_blank"><b>{{ $file }}</b></a> </td>
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
                </table>
            </div>

            <div class="block">
                <div class="block-head">
                    Audit Response & Closure
                </div>
                <table>

                    <tr>
                        <th class="w-20">Reference Record</th>
                        <td class="w-30">
                            @if ($data->Reference_Recores1)
                                {{ $data->Reference_Recores1 }}
                            @else
                                Not Applicable
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th class="w-20">Due Date Extension Justification</th>
                        <td class="w-30" colspan="3">
                            @if ($data->due_date_extension)
                                {{ $data->due_date_extension }}
                            @else
                                Not Applicable
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th class="w-20">Remarks</th>
                        <td class="w-80" colspan="3">
                            @if ($data->Remarks)
                                {{ $data->Remarks }}
                            @else
                                Not Applicable
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th class="w-20">Audit Comments
                        </th>
                        <td class="w-80">
                            <div>
                                @if ($data->Audit_Comments2)
                                    {{ $data->Audit_Comments2 }}
                                @else
                                    Not Applicable
                                @endif
                            </div>
                        </td>
                    </tr>


                </table>
            </div>
            <div class="border-table">
                <div class="block-head">
                    Audit Attachments
                </div>
                <table>

                    <tr class="table_bg">
                        <th class="w-20">S.N.</th>
                        <th class="w-60">File </th>
                    </tr>
                    @if ($data->myfile)
                        @foreach (json_decode($data->myfile) as $key => $file)
                            <tr>
                                <td class="w-20">{{ $key + 1 }}</td>
                                <td class="w-20"><a href="{{ asset('upload/' . $file) }}"
                                        target="_blank"><b>{{ $file }}</b></a> </td>
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
            <div class="border-table">
                <div class="block-head">
                    Report Attachment
                </div>
                <table>

                    <tr class="table_bg">
                        <th class="w-20">S.N.</th>
                        <th class="w-60">File</th>
                    </tr>
                    @if ($data->report_file)
                        @foreach (json_decode($data->report_file) as $key => $file)
                            <tr>
                                <td class="w-20">{{ $key + 1 }}</td>
                                <td class="w-20"><a href="{{ asset('upload/' . $file) }}"
                                        target="_blank"><b>{{ $file }}</b></a> </td>
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


            <div class="block">
                <div class="block-head">
                    Activity Log
                </div>
                <table>
                    <tr>
                        <th class="w-20">Schedule Audit By</th>
                        <td class="w-30">{{ $data->audit_schedule_by }}</td>
                        <th class="w-20">Schedule Audit On</th>
                        <td class="w-30">{{ Helpers::getdateFormat($data->audit_schedule_on) }}</td>
                    </tr>
                    <tr>
                        <th class="w-20">Cancelled By</th>
                        <td class="w-30">{{ $data->cancelled_by }}</td>
                        <th class="w-20">Cancelled On</th>
                        <td class="w-30">{{ Helpers::getdateFormat($data->cancelled_on) }}</td>
                    </tr>
                    <tr>
                        <th class="w-20">Complete Audit Preparation by</th>
                        <td class="w-30">{{ $data->audit_preparation_completed_by }}</td>
                        <th class="w-20">Complete Audit Preparation On</th>
                        <td class="w-30">{{ Helpers::getdateFormat($data->audit_preparation_completed_on) }}</td>
                    </tr>
                    <tr>
                        <th class="w-20">Issue Report By</th>
                        <td class="w-30">{{ $data->audit_mgr_more_info_reqd_by }}</td>
                        <th class="w-20">Issue Report On</th>
                        <td class="w-30">{{ Helpers::getdateFormat($data->audit_mgr_more_info_reqd_on) }}</td>
                    </tr>
                    <tr>
                        <th class="w-20">CAPA Plan Proposed By</th>
                        <td class="w-30">{{ $data->audit_observation_submitted_by }}</td>
                        <th class="w-20">CAPA Plan Proposed On</th>
                        <td class="w-30">{{ Helpers::getdateFormat($data->audit_observation_submitted_on) }}</td>
                    </tr>

                    <tr>
                        <th class="w-20">No CAPAs Required By</th>
                        <td class="w-30">{{ $data->audit_response_completed_by }}</td>
                        <th class="w-20">No CAPAs Required On</th>
                        <td class="w-30">{{ Helpers::getdateFormat($data->audit_response_completed_on) }}</td>
                    </tr>
                    <tr>
                        <th class="w-20">All CAPA Closed By
                        </th>
                        <td class="w-30">{{ $data->audit_lead_more_info_reqd_by }}</td>
                        <th class="w-20">All CAPA Closed On</th>
                        <td class="w-30">{{ Helpers::getdateFormat($data->audit_lead_more_info_reqd_on) }}</td>
                    </tr>
                    {{-- <tr>
                        <th class="w-20">Response Feedback Verified By</th>
                        <td class="w-30">{{ $data->response_feedback_verified_by }}</td>
                        <th class="w-20">Response Feedback Verified On</th>
                        <td class="w-30">{{ Helpers::getdateFormat($data->response_feedback_verified_on) }}</td>
                    </tr> --}}
                    <tr>
                        <th class="w-20">Rejected By</th>
                        <td class="w-30">{{ $data->rejected_by }}</td>
                        <th class="w-20">Rejected On</th>
                        <td class="w-30">{{ Helpers::getdateFormat($data->rejected_on) }}</td>
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
                {{-- <td class="w-30">
                    <strong>Page :</strong> 1 of 1
                </td> --}}
            </tr>
        </table>
    </footer>

</body>

</html>
