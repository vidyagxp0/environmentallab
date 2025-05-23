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
                    Audit Program Single Report
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
                    <strong>Audit Program No.</strong>
                </td>
                <td class="w-40">
                   {{ Helpers::divisionNameForQMS($data->division_id) }}/AP/{{ Helpers::year($data->created_at) }}/{{ str_pad($data->record, 4, '0', STR_PAD_LEFT) }}
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
                <!-- <td class="w-30">
                    <strong>Page :</strong> 1 of 1
                </td> -->
            </tr>
        </table>
    </footer>
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
                        <th class="w-20">Date of Initiation</th>
                        <td class="w-30">{{ Helpers::getdateFormat($data->created_at) }}</td>
                    </tr>
                    <tr>
                        <th class="w-20">Site/Location Code</th>
                        <td class="w-30">@if($data->division_code){{ $data->division_code }} @else Not Applicable @endif</td>
                        <th class="w-20">Initiator Group</th>
                        <td class="w-30">@if($data->Initiator_Group){{ Helpers::getInitiatorGroupFullName($data->Initiator_Group)}} @else Not Applicable @endif</td>
                    </tr>
                    <tr>

                        <th class="w-20">Assigned To</th>
                        <td class="w-30">@if($data->assign_to){{ Helpers::getInitiatorName($data->assign_to) }} @else Not Applicable @endif</td>
                        <th class="w-20">Initiator Group Code</th>
                        <td class="w-30">@if($data->initiator_group_code){{ $data->initiator_group_code }} @else Not Applicable @endif</td>

                    </tr>
                    <tr>
                        <th class="w-20">Record Number</th>
                        <td class="w-80">@if($data->record){{ Helpers::divisionNameForQMS($data->division_id) }}/AP/{{ Helpers::year($data->created_at) }}/{{ str_pad($data->record, 4, '0', STR_PAD_LEFT) }} @else Not Applicable @endif</td>
                        <th class="w-20">Due Date</th>
                        <td class="w-80" > @if($data->due_date){{ Helpers::getdateFormat($data->due_date) }} @else Not Applicable @endif</td>
                    </tr>
                    <tr>
                        <th class="w-20">Short Description</th>
                        <td class="w-80" > @if($data->short_description){{ $data->short_description }}@else Not Applicable @endif</td>
                    </tr>
                    <tr>
                        <th class="w-20">Type</th>
                        <td class="w-30">@if($data->type){{ $data->type }}@else Not Applicable @endif</td>
                    </tr>
                    <tr>
                        <th class="w-20">Type(Others)</th>
                        <td class="w-30" colspan="3">@if($data->type_other){{ $data->type_other }}@else Not Applicable @endif</td>
                    </tr>
                    <tr>
                        <th class="w-20">Due Date Extension Justification</th>
                        <td class="w-30" colspan="3">@if($data->due_date_extension){{ $data->due_date_extension}}@else Not Applicable @endif</td>
                    </tr>
                        <tr>
                         <th class="w-20">Quarter</th>
                         <td class="w-30">@if($data->Quarter){{ $data->Quarter }}@else Not Applicable @endif</td>
                         <th class="w-20">Year</th>
                         <td class="w-30">@if($data->year){{ $data->year }}@else Not Applicable @endif</td>
                         </tr>
                        <tr>
                          <th class="w-20">URl's Description</th>
                          <td class="w-30">@if($data->url_description){{ $data->url_description }}@else Not Applicable @endif</td>
                          <th class="w-20">Severity Level</th>
                          <td class="w-30">@if($data->severity1_level){{ $data->severity1_level }}@else Not Applicable @endif</td>
                        </tr>
                        <tr>
                            <th class="w-20">Comments</th>
                            <td class="w-80" colspan="3">@if($data->comments){{ $data->comments }}@else Not Applicable @endif</td>
                        </tr>
                        <tr>
                            <th class="w-20">Others</th>
                            <td class="w-30" colspan="3">@if($data->initiated_through_req){{ $data->initiated_through_req }} @else Not Applicable @endif</td>
                        </tr>
                        <tr>
                            <th class="w-20">Related URL </th>
                            <td class="w-80">@if($data->related_url){{ $data->related_url }}@else Not Applicable @endif</td>
                            <th class="w-20">Initiated Through</th>
                            <td class="w-30">@if($data->initiated_through){{ $data->initiated_through }} @else Not Applicable @endif</td>
                        </tr>

                     </table>
                     <div class="border-table">
                    <div class="block-head">
                    Attached Files
                    </div>
                    <table>

                        <tr class="table_bg">
                            <th class="w-20">S.N.</th>
                            <th class="w-60">Batch No</th>
                        </tr>
                            @if($data->attachments)
                            @foreach(json_decode($data->attachments) as $key => $file)
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
                </div>


        <!-- ------------------------------- audit program grid--------------------------------------- -->
        @php
        $users = DB::table('users')
            ->select('id', 'name')
            ->get();

    @endphp
            <div class="block">
                    <div class="block-head">
                        Audit Program
                    </div>
                <div class="border-table">
                    <table>
                        <tr class="table_bg">
                        <th class="w-20">Row #</th>
                            <th class="w-20">Auditees</th>
                            <th class="w-20">Date Start	</th>
                            <!-- <th class="w-20">Date End</th>
                            <th class="w-20">Lead Investigator</th>
                            <th class="w-20">Comment</th> -->
                        </tr>
                        @if ($AuditProgramGrid)
                        @foreach (unserialize($AuditProgramGrid->auditor) as $key => $temps)
                        <tr>
                            <td class="w-15">
                            {{ $key + 1 }}</td>
                            <td class="w-15">{{ unserialize($AuditProgramGrid->auditor)[$key] ? Helpers::getInitiatorName(unserialize($AuditProgramGrid->auditor)[$key]) : '' }}</td>
                            <td class="w-15">{{ unserialize($AuditProgramGrid->start_date)[$key] ? Helpers::getdateFormat(unserialize($AuditProgramGrid->start_date)[$key]) : '' }}</td>
                            <!-- <td class="w-5"><div class="group-input new-date-data-field mb-0">
                                                                        <div class="input-date "><div
                                                                         class="calenderauditee">
                                                                         <input type="text"   id="end_date{{$key}}" readonly placeholder="DD-MMM-YYYY" value="{{ Helpers::getdateFormat(unserialize($AuditProgramGrid->end_date)[$key]) }}"/>
                                                                <input class="hide-input" type="date"  id="end_date{{$key}}_checkdate" value="{{unserialize($AuditProgramGrid->end_date)[$key]}}"  name="end_date[]"  min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" {{ $data->stage == 0 || $data->stage == 4 ? 'disabled' : '' }} value="{{ Helpers::getdateFormat(unserialize($AuditProgramGrid->end_date)[$key]) }}
                                                                  oninput="handleDateInput(this, `end_date' + serialNumber +'`)" /></div></div></div></td>
                            <td class="w-15"> <select id="select-state" placeholder="Select..."
                                                                    name="lead_investigator[]"  {{ $data->stage == 0 || $data->stage == 4 ? 'disabled' : '' }}>
                                                                    <option value="">-Select-</option>
                                                                    @foreach ($users as $value)
                                                                        <option
                                                                            {{ unserialize($AuditProgramGrid->lead_investigator)[$key] ? (unserialize($AuditProgramGrid->lead_investigator)[$key] == $value->id ? 'selected' : ' ') : '' }}
                                                                            value="{{ $value->id }}">
                                                                            {{ $value->name }}
                                                                        </option>
                                                                    @endforeach
                                                                </select></td>
                            <td class="w-15"><input type="text" name="comment[]"  {{ $data->stage == 0 || $data->stage == 4 ? 'disabled' : '' }}
                            value="{{ unserialize($AuditProgramGrid->comment)[$key] ? unserialize($AuditProgramGrid->comment)[$key] : '' }}"></td>   -->

                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td>Not Applicable</td>
                            <td>Not Applicable</td>
                            <td>Not Applicable</td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>




            <div class="block">
                <div class="block-head">
                  Audit Program
                </div>
                <div class="border-table">
                    <table>
                        <tr class="table_bg">

                            <th class="w-20">Date End</th>
                            <th class="w-20">Lead Investigator</th>
                            <th class="w-20">Comment</th>
                        </tr>
                        @if ($AuditProgramGrid)
                        @foreach (unserialize($AuditProgramGrid->auditor) as $key => $temps)
                        <tr>
                            <!-- <td class="w-15"><input disabled type="text" name="serial_number[]" {{ $data->stage == 0 || $data->stage == 4 ? 'disabled' : '' }}
                            value="{{ $key + 1 }}" ></td> -->


                            <td class="w-5">{{ unserialize($AuditProgramGrid->end_date)[$key] ? Helpers::getdateFormat(unserialize($AuditProgramGrid->end_date)[$key]) : '' }}</td>
                            <td class="w-15">{{ unserialize($AuditProgramGrid->lead_investigator)[$key] ? Helpers::getInitiatorName(unserialize($AuditProgramGrid->lead_investigator)[$key]) : '' }}</td>
                            <td class="w-15">{{ unserialize($AuditProgramGrid->comment)[$key] ? unserialize($AuditProgramGrid->comment)[$key] : '' }}</td>

                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td>Not Applicable</td>
                            <td>Not Applicable</td>
                            <td>Not Applicable</td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>



     <!--  ------------------------------- audit program grid--------------------------------------- -->


        <div class="inner-block">
        <div class="content-table">
            <div class="block">
                <div class="block-head">
                    Activity log
                </div>
                <table>
                    <tr>
                        <th class="w-20">Submitted By</th>
                        <td class="w-30">{{ $data->submitted_by }}</td>
                        <th class="w-20">
                            Submitted On</th>
                        <td class="w-30">{{ $data->submitted_on }}</td>
                    </tr>

                    <tr>
                        <th class="w-20">Audit Completed By</th>
                        <td class="w-30">{{ $data->Audit_Completed_By }}</td>
                        <th class="w-20">
                        Audit Completed On</th>
                        <td class="w-30">{{ $data->Audit_Completed_On }}</td>
                    </tr>
                    <tr>
                        <th class="w-20">Approved By</th>
                        <td class="w-30">{{ $data->approved_by }}</td>
                        <th class="w-20">Approved On</th>
                        <td class="w-30">{{ $data->approved_on }}</td>
                    </tr>
                    <tr>
                        <th class="w-20">Rejected By</th>
                        <td class="w-30">{{ $data->rejected_by }}</td>
                        <th class="w-20">Rejected On</th>
                        <td class="w-30">{{ $data->rejected_on }}</td>
                    </tr>

                    <tr>
                        <th class="w-20">Cancelled By</th>
                        <td class="w-30">{{ $data->cancelled_by }}</td>
                        <th class="w-20">Cancelled On</th>
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
                    <strong></strong>
                </td>
            </tr>
        </table>
    </footer>

</body>

</html>
