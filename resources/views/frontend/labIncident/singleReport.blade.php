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
                    Lab Incident Single Report
                </td>
                <td class="w-30">
                    <div class="logo">
                        <img src="https://dms.mydemosoftware.com/user/images/logo1.png" alt=""
                            style="width: 60px;">
                    </div>
                </td>
            </tr>
        </table>
        <table>
            <tr>
                <td class="w-30">
                    <strong>Lab Incident No.</strong>
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
                    <tr> {{ $data->created_at }} added by {{ $data->originator }}
                        <th class="w-20">Initiator</th>
                        <td class="w-30">{{ $data->originator }}</td>

                        <th class="w-20">Date Initiation</th>
                        <td class="w-30">{{ Helpers::getdateFormat($data->created_at) }}</td>
                    </tr>
                    <tr>
                        <th class="w-20">Site/Location Code</th>
                        <td class="w-30">
                            @if ($data->division_id)
                                {{ Helpers::getDivisionName($data->division_id) }}
                            @else
                                Not Applicable
                            @endif
                        </td>

                        <th class="w-20">Assigned To</th>
                        <td class="w-30">
                            @if ($data->assign_to)
                                {{ Helpers::getInitiatorName($data->assign_to) }}
                            @else
                                Not Applicable
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th class="w-20">Initiator Group</th>
                        <td class="w-30">
                            @if ($data->Initiator_Group)
                                {{ Helpers::getInitiatorGroupFullName($data->Initiator_Group) }}
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
                        <th class="w-20">Due Date</th>
                        <td class="w-80" colspan="3">
                            @if ($data->due_date)
                                {{ $data->due_date }}
                            @else
                                Not Applicable
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th class="w-20">Short Description</th>
                        <td class="w-80" colspan="3">
                            @if ($data->short_desc)
                                {{ $data->short_desc }}
                            @else
                                Not Applicable
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th class="w-20">Severity Level</th>
                        <td class="w-30">
                            @if ($data->severity_level2)
                                {{ $data->severity_level2 }}
                            @else
                                Not Applicable
                            @endif
                        </td>

                        <th class="w-20">Incident Category</th>
                        <td class="w-30">
                            @if ($data->Incident_Category)
                                {{ $data->Incident_Category }}
                            @else
                                Not Applicable
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th class="w-20">Others</th>
                        <td class="w-80" colspan="3">
                            @if ($data->Incident_Category_others)
                                {{ $data->Incident_Category_others }}
                            @else
                                Not Applicable
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th class="w-20">Invocation Type</th>
                        <td class="w-30">
                            @if ($data->Invocation_Type)
                                {{ $data->Invocation_Type }}
                            @else
                                Not Applicable
                            @endif
                        </td>
                    </tr>


                    <!-- <tr>
                        <th class="w-20">Other Ref.Doc.No</th>
                        <td class="w-30">
@if ($data->Other_Ref)
{{ $data->Other_Ref }}
@else
Not Applicable
@endif
</td>
                        <th class="w-20">Incident Category</th>
                        <td class="w-30">
@if ($data->Incident_Category)
{{ $data->Incident_Category }}
@else
Not Applicable
@endif
</td>
                        <th class="w-20">Others</th>
                        <td class="w-30">
@if ($data->Incident_Category_others)
{{ $data->Incident_Category_others }}
@else
Not Applicable
@endif
</td>
                    </tr> -->
                </table>

                <div class="border-table">
                    <div class="block-head">
                        Initial Attachment
                    </div>
                    <table>
                        <tr class="table_bg">
                            <th class="w-20">S.N.</th>
                            <th class="w-80">Attachment</th>
                        </tr>
                        @if ($data->Initial_Attachment)
                            @foreach (json_decode($data->Initial_Attachment) as $key => $file)
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
                        Incident Details
                    </div>
                    <table>
                        <tr>
                            <th class="w-20">Incident Details</th>
                            <td class="w-80">
                                @if ($data->Incident_Details)
                                    {{ $data->Incident_Details }}
                                @else
                                    Not Applicable
                                @endif
                            </td>
                        </tr>

                        <tr>
                            <th class="w-20">Document Details</th>
                            <td class="w-80">
                                @if ($data->Document_Details)
                                    {{ $data->Document_Details }}
                                @else
                                    Not Applicable
                                @endif
                            </td>
                        </tr>

                        <tr>
                            <th class="w-20">Instrument Details</th>
                            <td class="w-80">
                                @if ($data->Instrument_Details)
                                    {{ $data->Instrument_Details }}
                                @else
                                    Not Applicable
                                @endif
                            </td>
                        </tr>

                        <tr>
                            <th class="w-20">Involved Personnel</th>
                            <td class="w-80">
                                @if ($data->Involved_Personnel)
                                    {{ $data->Involved_Personnel }}
                                @else
                                    Not Applicable
                                @endif
                            </td>
                        </tr>

                        <tr>
                            <th class="w-20">Product Details,If Any</th>
                            <td class="w-80">
                                @if ($data->Product_Details)
                                    {{ $data->Product_Details }}
                                @else
                                    Not Applicable
                                @endif
                            </td>
                        </tr>

                        <tr>
                            <th class="w-20">Supervisor Review Comments</th>
                            <td class="w-80">
                                @if ($data->Supervisor_Review_Comments)
                                    {{ $data->Supervisor_Review_Comments }}
                                @else
                                    Not Applicable
                                @endif
                            </td>
                        </tr>
                    </table>

                    <div class="border-table">
                        <div class="block-head">
                            Incident Attachment
                        </div>
                        <table>
                            <tr class="table_bg">
                                <th class="w-20">S.N.</th>
                                <th class="w-80">Attachment</th>
                            </tr>
                            @if ($data->Attachments)
                                @foreach (json_decode($data->Attachments) as $key => $file)
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
            </div>

            <div class="block">
                <div class="block-head">
                    Investigation Details
                </div>
                <table>
                    <tr>
                        <th class="w-20">Investigation Details</th>
                        <td class="w-80" colspan="3">
                            @if ($data->Investigation_Details)
                                {{ $data->Investigation_Details }}
                            @else
                                Not Applicable
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th class="w-20">Action Taken</th>
                        <td class="w-80" colspan="3">
                            @if ($data->Action_Taken)
                                {{ $data->Action_Taken }}
                            @else
                                Not Applicable
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th class="w-20">Root Cause</th>
                        <td class="w-80" colspan="3">
                            @if ($data->Root_Cause)
                                {{ $data->Root_Cause }}
                            @else
                                Not Applicable
                            @endif
                        </td>
                    </tr>
                </table>

                <div class="border-table">
                    <div class="block-head">
                        Investigation Attachment
                    </div>
                    <table>
                        <tr class="table_bg">
                            <th class="w-20">S.N.</th>
                            <th class="w-80">Attachment</th>
                        </tr>
                        @if ($data->Inv_Attachment)
                            @foreach (json_decode($data->Inv_Attachment) as $key => $file)
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
        </div>

        <div class="block">
            <div class="block-head">
                CAPA
            </div>
            <table>
                <tr>
                    <th class="w-20">Corrective Action</th>
                    <td class="w-80" colspan="3">
                        @if ($data->Currective_Action)
                            {{ $data->Currective_Action }}
                        @else
                            Not Applicable
                        @endif
                    </td>
                </tr>

                <tr>
                    <th class="w-20">Preventive Action</th>
                    <td class="w-80" colspan="3">
                        @if ($data->Preventive_Action)
                            {{ $data->Preventive_Action }}
                        @else
                            Not Applicable
                        @endif
                    </td>
                </tr>

                <tr>
                    <th class="w-20">Corrective & Preventive Action</th>
                    <td class="w-80" colspan="3">
                        @if ($data->Corrective_Preventive_Action)
                            {{ $data->Corrective_Preventive_Action }}
                        @else
                            Not Applicable
                         @endif
                    </td>
                </tr>
            </table>

            <div class="border-table">
                <div class="block-head">
                    CAPA Attachment
                </div>
                <table>
                    <tr class="table_bg">
                        <th class="w-20">S.N.</th>
                        <th class="w-80">Attachment</th>
                    </tr>
                    @if ($data->CAPA_Attachment)
                        @foreach (json_decode($data->CAPA_Attachment) as $key => $file)
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
            <div class="block-head">
                QA Review
            </div>
            <table>
                <tr>
                    <th class="w-20">QA Review Comment</th>
                    <td class="w-80" colspan="3">
                        @if ($data->QA_Review_Comments)
                            {{ $data->QA_Review_Comments }}
                        @else
                            Not Applicable
                        @endif
                    </td>
                </tr>
            </table>

            <div class="border-table">
                <div class="block-head">
                    QA Review Attachment
                </div>
                <table>
                    <tr class="table_bg">
                        <th class="w-20">S.N.</th>
                        <th class="w-80">Attachment</th>
                    </tr>
                    @if ($data->QA_Head_Attachment)
                        @foreach (json_decode($data->QA_Head_Attachment) as $key => $file)
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
            <div class="block-head">
                QA Head/Designee
            </div>
            <table>
                <tr>
                    <th class="w-20">QA Head/Designee Comments</th>
                    <td class="w-80" colspan="3">
                        @if ($data->QA_Head)
                            {{ $data->QA_Head }}
                        @else
                            Not Applicable
                        @endif
                    </td>
                </tr>

                <tr>
                    <th class="w-20">Incident Type</th>
                    <td class="w-30">
                        @if ($data->Incident_Type)
                            {{ $data->Incident_Type }}
                        @else
                            Not Applicable
                        @endif
                    </td>
                </tr>

                <tr>
                    <th class="w-20">Conclusion</th>
                    <td class="w-80" colspan="3">
                        @if ($data->Conclusion)
                            {{ $data->Conclusion }}
                        @else
                            Not Applicable
                        @endif
                    </td>
                </tr>

                <tr>
                    <th class="w-20">Due Date Extension Justification</th>
                    <td class="w-80" colspan="3">
                        @if ($data->due_date_extension)
                            {{ $data->due_date_extension }}
                        @else
                            Not Applicable
                        @endif
                    </td>
                </tr>
            </table>
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
                    <td class="w-30">{{ $data->submitted_on }}</td>
                </tr>
                <tr>
                    <th class="w-20">Incident Review Completed By</th>
                    <td class="w-30">{{ $data->incident_review_completed_by }}</td>
                    <th class="w-20">Incident Review Completed On</th>
                    <td class="w-30">{{ $data->incident_review_completed_on }}</td>
                </tr>
                <tr>
                    <th class="w-20">Investigation Completed By</th>
                    <td class="w-30">{{ $data->investigation_completed_by }}</td>
                    <th class="w-20">Investigation Completed On</th>
                    <td class="w-30">{{ $data->investigation_completed_on }}</td>
                </tr>
                <tr>
                    <th class="w-20">QA Review Completed By</th>
                    <td class="w-30">{{ $data->qA_review_completed_by }}</td>
                    <th class="w-20">QA Review Completed On</th>
                    <td class="w-30">{{ $data->qA_review_completed_on }}</td>
                </tr>
                <tr>
                    <th class="w-20">QA Head Approval Completed By
                    </th>
                    <td class="w-30">{{ $data->qA_head_approval_completed_by }}</td>
                    <th class="w-20">QA Head Approval Completed On</th>
                    <td class="w-30">{{ $data->qA_head_approval_completed_on }}</td>
                </tr>
                <tr>
                    <th class="w-20">All Activities Completed By</th>
                    <td class="w-30">{{ $data->all_activities_completed_by }}</td>
                    <th class="w-20">All Activities Completed On</th>
                    <td class="w-30">{{ $data->all_activities_completed_on }}</td>
                </tr>
                <tr>
                        <th class="w-20">Review Completed By</th>
                        <td class="w-30">{{ $data->incident_review_completed_by }}</td>
                        <th class="w-20">Review Completed On</th>
                        <td class="w-30">{{ $data->incident_review_completed_on }}</td>
                    </tr>
                <tr>
                    <th class="w-20">Cancelled By</th>
                    <td class="w-30">{{ $data->cancelled_by }}</td>
                    <th class="w-20">
                        Cancelled On</th>
                    <td class="w-30">{{ $data->cancelled_on }}</td>
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
