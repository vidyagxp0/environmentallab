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
                    Change Control Single Report
                </td>
                <td class="w-30">
                    <div class="logo">
                        <img src="http://environmentallab.doculife.co.in/user/images/logo.png" alt=""
                            style="width: 200px;">
                    </div>
                </td>
            </tr>
        </table>
        <table>
            <tr>
                <td class="w-30">
                    <strong>Change Control No.</strong>
                </td>
                <td class="w-40">
                    {{ Helpers::getDivisionName($data->division_id) }}/CC/{{ date('Y') }}/{{ $data->record ? str_pad($data->record, 4, '0', STR_PAD_LEFT) : '' }}
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
                            @if ($data->record)
                                {{ Helpers::divisionNameForQMS($data->division_id) }}/CC/{{ Helpers::year($data->created_at) }}/{{ $data->record ? str_pad($data->record, 4, '0', STR_PAD_LEFT) : '' }}
                            @else
                                Not Applicable
                            @endif
                        </td>
                        <th class="w-20">Division Code</th>
                        <td class="w-30">
                            @if ($data->division_id)
                                {{ Helpers::getDivisionName($data->division_id) }}
                            @else
                                Not Applicable
                            @endif
                        </td>
                    </tr>
                    <tr>
                        On {{ Helpers::getDateFormat($data->created_at) }} added by {{ $data->originator }}
                        <th class="w-20">Initiator</th>
                        <td class="w-30">{{ $data->originator }}</td>


                        <th class="w-20">Date of Initiation</th>
                        <td class="w-30">{{ Helpers::getdateFormat($data->intiation_date) }}</td>
                    </tr>
                    <tr>
                        <th class="w-20">Assigned To</th>
                        <td class="w-30">
                            @if ($data->assign_to)
                                {{ Helpers::getInitiatorName($data->assign_to) }}
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
                        <th class="w-20">CFT Reviewer</th>
                        <td class="w-30">
                            @if ($data->Microbiology)
                                {{ $data->Microbiology }}
                            @else
                                Not Applicable
                            @endif

                        </td>

                        <th class="w-20">CFT Reviewer Person</th>
                        <td class="w-30">
                            @if ($data->Microbiology_Person)
                                {{ $userNames }}
                            @else
                                Not Applicable
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th class="w-20">Severity Level</th>
                        <td class="w-30">
                            @if ($data->severity_level1)
                                {{ $data->severity_level1 }}
                            @else
                                Not Applicable
                            @endif
                        </td>

                        <th class="w-20">Initiated Through</th>
                        <td class="w-30" colspan="3">
                            @if ($data->initiated_through)
                                {{ $data->initiated_through }}
                            @else
                                Not Applicable
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th class="w-20">Short Description</th>
                        <td class="w-80" colspan="3">
                            @if ($data->short_description)
                                {{ $data->short_description }}
                            @else
                                Not Applicable
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th class="w-20">Others</th>
                        <td class="w-80" colspan="3">
                            @if ($data->initiated_through_req)
                                {{ $data->initiated_through_req }}
                            @else
                                Not Applicable
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th class="w-20">Repeat</th>
                        <td class="w-30">
                            @if ($data->repeat)
                                {{ $data->repeat }}
                            @else
                                Not Applicable
                            @endif
                        </td>
                        <th class="w-20">Division</th>
                        <td class="w-30">
                            @if ($data->Division_Code)
                                {{ $data->Division_Code }}
                            @else
                                Not Applicable
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th class="w-20">Repeat Nature</th>
                        <td class="w-80" colspan="3">
                            @if ($data->repeat_nature)
                                {{ $data->repeat_nature }}
                            @else
                                Not Applicable
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th class="w-20">Nature of Change</th>
                        <td class="w-30">
                            @if ($data->doc_change)
                                {{ $data->doc_change }}
                            @else
                                Not Applicable
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th class="w-20">If Others</th>
                        <td class="w-80" colspan="3">
                            @if ($data->If_Others)
                                {{ $data->If_Others }}
                            @else
                                Not Applicable
                            @endif
                        </td>
                    </tr>
                </table>
                <div class="border-table">
                    <div class="block-head">
                        Initial Attachment
                    </div>
                    <table>

                        <tr class="table_bg">
                            <th class="w-20">S.N.</th>
                            <th class="w-60">Attachment</th>
                        </tr>
                        @if ($data->in_attachment)
                            @foreach (json_decode($data->in_attachment) as $key => $file)
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
                    Change Details
                </div>
                <div class="border-table">
                    <div class="block-head">
                        Document Details
                    </div>
                    <table>
                        <tr class="table_bg">
                            <th class="w-15">S.N.</th>
                            <th class="w-25">Current Document No.</th>
                            <th class="w-25">Current Version No.</th>
                            <th class="w-25">New Document No.</th>
                            <th class="w-25">New Version No.</th>
                        </tr>
                        @foreach (unserialize($docdetail->current_doc_no) as $key => $docdetails)
                            <tr>
                                <td class="w-15">{{ $key + 1 }}</td>
                                <td class="w-25">
                                    {{ unserialize($docdetail->current_doc_no)[$key] ?? 'Not Applicable' }}</td>
                                <td class="w-25">
                                    {{ unserialize($docdetail->current_version_no)[$key] ?? 'Not Applicable' }}</td>
                                <td class="w-25">{{ unserialize($docdetail->new_doc_no)[$key] ?? 'Not Applicable' }}
                                </td>
                                <td class="w-25">
                                    {{ unserialize($docdetail->new_version_no)[$key] ?? 'Not Applicable' }}</td>
                            </tr>
                        @endforeach
                    </table>
                </div>
                <table>
                    <tr>
                        <th class="w-20">Current Practice</th>
                        <td colspan="3">
                            <div>
                                @if ($docdetail->current_practice)
                                    {{ $docdetail->current_practice }}
                                @else
                                    Not Applicable
                                @endif
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th class="w-20">Proposed Change</th>
                        <td colspan="3">
                            <div>
                                @if ($docdetail->proposed_change)
                                    {{ $docdetail->proposed_change }}
                                @else
                                    Not Applicable
                                @endif
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th class="w-20">Reason For Change</th>
                        <td colspan="3">
                            <div>
                                @if ($docdetail->reason_change)
                                    {{ $docdetail->reason_change }}
                                @else
                                    Not Applicable
                                @endif
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th class="w-20">Supervisor Comments</th>
                        <td colspan="3">
                            <div>
                                @if ($docdetail->supervisor_comment)
                                    {{ $docdetail->supervisor_comment }}
                                @else
                                    Not Applicable
                                @endif
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th class="w-20">Any Other Comments</th>
                        <td colspan="3">
                            <div>
                                @if ($docdetail->other_comment)
                                    {{ $docdetail->other_comment }}
                                @else
                                    Not Applicable
                                @endif
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="block">
                <div class="head">
                    <div class="block-head">
                        QA Review
                    </div>
                    <table>
                        <tr>
                            <th class="w-20">Type of Change</th>
                            <td class="w-80" colspan="3">
                                @if ($review->type_chnage)
                                    {{ $review->type_chnage }}
                                @else
                                    Not Applicable
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="w-20">QA Review Comments</th>
                            <td class="w-80" colspan="3">
                                @if ($review->qa_comments)
                                    {{ $review->qa_comments }}
                                @else
                                    Not Applicable
                                @endif

                            </td>
                        </tr>
                        <tr>
                            <th class="w-20">Related Records</th>
                            <td class="w-80" colspan="3">
                                @if ($review->related_records)
                                    {{ str_replace(',', ', ', $review->related_records) }}
                                @else
                                    Not Applicable
                                @endif
                            </td>
                        </tr>
                    </table>

                    <div class="border-table">
                        <div class="block-head">
                            QA Attachments
                        </div>
                        <table>
                            <tr class="table_bg">
                                <th class="w-20">S.N.</th>
                                <th class="w-60">Attachment</th>
                            </tr>
                            @if ($review->qa_head)
                                @foreach (json_decode($review->qa_head) as $key => $file)
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
                <div class="head">
                    <div class="block-head">
                        Evaluation Details
                    </div>
                    <table>
                        <tr>
                            <th class="w-20">QA Evaluation Comments</th>
                            <td class="w-30" colspan="3">
                                @if ($evaluation->qa_eval_comments)
                                    {{ $evaluation->qa_eval_comments }}
                                @else
                                    Not Applicable
                                @endif
                            </td>
                        </tr>
                    </table>
                    <div class="border-table">
                        <div class="block-head">
                            QA Evaluation Attachments
                        </div>
                        <table>

                            <tr class="table_bg">
                                <th class="w-20">S.N.</th>
                                <th class="w-60">Attachment</th>
                            </tr>
                            @if ($evaluation->qa_eval_attach)
                                @foreach (json_decode($evaluation->qa_eval_attach) as $key => $file)
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


                    <div class="block-head">
                        Training Information
                    </div>
                    <table>
                        <tr>
                            <th class="w-20">Training Required</th>
                            <td class="w-30">
                                @if ($evaluation->training_required)
                                    {{ $evaluation->training_required }}
                                @else
                                    Not Applicable
                                @endif
                            </td>
                        </tr>

                        <tr>
                            <th class="w-20">Training Comments</th>
                            <td class="w-30" colspan="3">
                                <div>
                                    @if ($evaluation->train_comments)
                                        {{ $evaluation->train_comments }}
                                    @else
                                        Not Applicable
                                    @endif
                                </div>
                            </td>
                        </tr>
                    </table>


                </div>
            </div>

            <div class="block">
                <div class="head">
                    <div class="block-head">
                        Comments
                    </div>
                    <div class="block-head">
                        Feedback
                    </div>
                    <table>
                        <tr>
                            <th class="w-20">Comments</th>
                            <td class="w-80" colspan="3">
                                @if ($comments->cft_comments)
                                    {{ $comments->cft_comments }}
                                @else
                                    Not Applicable
                                @endif
                            </td>
                        </tr>
                    </table>
                    <div class="block-head">
                        Concerned Feedback
                    </div>
                    <table>
                        <tr>
                            <th class="w-20">QA Comments</th>
                            <td class="w-80" colspan="3">
                                <div><strong>On {{ Helpers::getDateFormat($comments->created_at) }} added by
                                        {{ $data->originator }}</strong>
                                    <div>
                                        {{ $comments->qa_commentss }}
                                    </div>
                            </td>
                        </tr>
                        <tr>
                            <th class="w-20">QA Head Designee Comments</th>
                            <td class="w-80" colspan="3">
                                <div><strong>On {{ Helpers::getDateFormat($comments->created_at) }} added by
                                        {{ $data->originator }}</strong>
                                    <div>
                                        {{ $comments->designee_comments }}
                                    </div>
                            </td>
                        </tr>
                        <tr>
                            <th class="w-20">Warehouse Comments</th>
                            <td class="w-80" colspan="3">
                                <div><strong>On {{ Helpers::getDateFormat($comments->created_at) }} added by
                                        {{ $data->originator }}</strong>
                                </div>
                                <div>
                                    {{ $comments->Warehouse_comments }}
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th class="w-20">Engineering Comments</th>
                            <td class="w-80" colspan="3">
                                <div><strong>On {{ Helpers::getDateFormat($comments->created_at) }} added by
                                        {{ $data->originator }}</strong>
                                </div>
                                <div>
                                    {{ $comments->Engineering_comments }}
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th class="w-20">Instrumentation Comments</th>
                            <td class="w-80" colspan="3">
                                <div><strong>On {{ Helpers::getDateFormat($comments->created_at) }} added by
                                        {{ $data->originator }}</strong>
                                </div>
                                <div>
                                    {{ $comments->Instrumentation_comments }}
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th class="w-20">Validation Comments</th>
                            <td class="w-80" colspan="3">
                                <div><strong>On {{ Helpers::getDateFormat($comments->created_at) }} added by
                                        {{ $data->originator }}</strong>
                                </div>
                                <div>
                                    {{ $comments->Validation_comments }}
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th class="w-20">Others Comments</th>
                            <td class="w-80" colspan="3">
                                <div><strong>On {{ Helpers::getDateFormat($comments->created_at) }} added by
                                        {{ $data->originator }}</strong>
                                </div>
                                <div>
                                    {{ $comments->Others_comments }}
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th class="w-20">Comments</th>
                            <td class="w-80" colspan="3">
                                <div><strong>On {{ Helpers::getDateFormat($comments->created_at) }} added by
                                        {{ $data->originator }}</strong>
                                </div>
                                <div>
                                    {{ $comments->Group_comments }}
                                </div>
                            </td>
                        </tr>
                    </table>

                    <div class="border-table">
                        <div class="block-head">
                            Attachments
                        </div>
                        <table>

                            <tr class="table_bg">
                                <th class="w-20">S.N.</th>
                                <th class="w-60">Attachment</th>
                            </tr>
                            @if ($comments->cft_attchament)
                                @foreach (json_decode($comments->cft_attchament) as $key => $file)
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
                            Group Attachments
                        </div>
                        <table>

                            <tr class="table_bg">
                                <th class="w-20">S.N.</th>
                                <th class="w-60">Attachment</th>
                            </tr>
                            @if ($comments->group_attachments)
                                @foreach (json_decode($comments->group_attachments) as $key => $file)
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
                    Risk Assessment
                </div>
                <table>
                    <tr>
                        <th class="w-20">Risk Identification</th>
                        <td class="w-80" colspan="3">
                            <div><strong>On {{ Helpers::getDateFormat($assessment->created_at) }} added by
                                    {{ $data->originator }}</strong></div>
                            <div>
                                {{ $assessment->risk_identification }}
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th class="w-20">Severity</th>
                        <td class="w-30">
                            @if ($assessment->severity == 1)
                                Negligible
                            @elseif($assessment->severity == 2)
                                Minor
                            @elseif($assessment->severity == 3)
                                Moderate
                            @elseif($assessment->severity == 4)
                                Major
                            @elseif($assessment->severity == 5)
                                Fatel
                            @else
                                Not Applicable
                            @endif
                        </td>

                        <th class="w-20">Occurrence</th>
                        <td class="w-30">
                            @if ($assessment->Occurance == 1)
                                Very Likely
                            @elseif($assessment->Occurance == 2)
                                Likely
                            @elseif($assessment->Occurance == 3)
                                Unlikely
                            @elseif($assessment->Occurance == 4)
                                Rare
                            @elseif($assessment->Occurance == 5)
                                Extremely Unlikely
                            @else
                                Not Applicable
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th class="w-20">Detection</th>
                        <td class="w-30">
                            @if ($assessment->Detection == 1)
                                Very Likely
                            @elseif($assessment->Detection == 2)
                                Likely
                            @elseif($assessment->Detection == 3)
                                Unlikely
                            @elseif($assessment->Detection == 4)
                                Rare
                            @elseif($assessment->Detection == 5)
                                Impossible
                            @else
                                Not Applicable
                            @endif
                        </td>
                        <th class="w-20">RPN</th>
                        <td class="w-30">
                            @if ($assessment->RPN)
                                {{ $assessment->RPN }}
                            @else
                                Not Applicable
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th class="w-20">Risk Evaluation</th>
                        <td class="w-80" colspan="3">
                            <div><strong>On {{ Helpers::getDateFormat($assessment->created_at) }} added by
                                    {{ $data->originator }}</strong></div>
                            <div>
                                {{ $assessment->risk_evaluation }}
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th class="w-20">Migration Action</th>
                        <td class="w-80" colspan="3">
                            <div><strong>On {{ Helpers::getDateFormat($assessment->created_at) }} added by
                                    {{ $data->originator }}</strong></div>
                            <div>
                                {{ $assessment->migration_action }}
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="block">
                <div class="block-head">
                    QA Approval Comments
                </div>
                <table>
                    <tr>
                        <th class="w-20">QA Approval Comments</th>
                        <td class="w-80" colspan="3">
                            <div><strong>On {{ Helpers::getDateFormat($approcomments->created_at) }} added by
                                    {{ $data->originator }}</strong>
                            </div>
                            <div>
                                {{ $approcomments->qa_appro_comments }}
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th class="w-20">Training Feedback</th>
                        <td class="w-80" colspan="3">
                            <div><strong>On {{ Helpers::getDateFormat($approcomments->created_at) }} added by
                                    {{ $data->originator }}</strong>
                            </div>
                            <div>
                                {{ $approcomments->feedback }}
                            </div>
                        </td>
                    </tr>

                </table>
                <div class="border-table">
                    <div class="block-head">
                        Training Attachments
                    </div>
                    <table>

                        <tr class="table_bg">
                            <th class="w-20">S.N.</th>
                            <th class="w-60">Attachment</th>
                        </tr>
                        @if ($approcomments->tran_attach)
                            @foreach (json_decode($approcomments->tran_attach) as $key => $file)
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
                    Change Closure
                </div>

                <div class="border-table">
                    <div class="block-head">
                        Affected Documents
                    </div>

                    <table>
                        <tr class="table_bg">
                            <th class="w-15">S.N.</th>
                            <th class="w-25">Affected Documents</th>
                            <th class="w-25">Document Name</th>
                            <th class="w-25">Document No.</th>
                            <th class="w-25">Version No.</th>
                            <th class="w-25">Implementation Date</th>
                            <th class="w-25">New Document No.</th>
                            <th class="w-25">New Version No.</th>
                        </tr>
                        @foreach (unserialize($closure->affected_document) as $key => $docdetails)
                            <tr>
                                <td class="w-15">{{ $key + 1 }}</td>
                                <td class="w-25">
                                    {{ unserialize($closure->affected_document)[$key] ?? 'Not Applicable' }}</td>
                                <td class="w-25">{{ unserialize($closure->doc_name)[$key] ?? 'Not Applicable' }}
                                </td>
                                <td class="w-25">{{ unserialize($closure->doc_no)[$key] ?? 'Not Applicable' }}</td>
                                <td class="w-25">{{ unserialize($closure->version_no)[$key] ?? 'Not Applicable' }}
                                </td>
                                <td class="w-25">
                                    {{ isset(unserialize($closure->implementation_date)[$key]) ? date('d-M-Y', strtotime(unserialize($closure->implementation_date)[$key])) : 'Not Applicable' }}
                                </td>
                                <td class="w-25">{{ unserialize($closure->new_doc_no)[$key] ?? 'Not Applicable' }}
                                </td>
                                <td class="w-25">
                                    {{ unserialize($closure->new_version_no)[$key] ?? 'Not Applicable' }}</td>
                            </tr>
                        @endforeach
                    </table>

                </div>


                <table>
                    <!-- <tr>
                        <th class="w-20">Affected Documents+</th>
                        <td class="w-80">
                            <div><strong>On {{ Helpers::getDateFormat($approcomments->created_at) }} added by {{ $data->originator }}</strong>
                            </div>
                            <div>
                                {{ $approcomments->risk_identification }}
                            </div>
                        </td>
                    </tr> -->
                    <tr>
                        <th class="w-20">QA Closure Comments</th>
                        <td class="w-80" colspan="3">
                            @if ($closure->qa_closure_comments)
                                {{ $closure->qa_closure_comments }}
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
                <div class="border-table">
                    <div class="block-head">
                        List Of Attachments
                    </div>
                    <table>

                        <tr class="table_bg">
                            <th class="w-20">S.N.</th>
                            <th class="w-60">Attachment</th>
                        </tr>
                        @if ($closure->attach_list)
                            @foreach (json_decode($closure->attach_list) as $key => $file)
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
                    Activity Log
                </div>
                <table>
                    <tr>
                        <th class="w-20">Submitted By</th>
                        <td class="w-30">
                            @php
                                $submit = DB::table('c_c_stage_histories')
                                    ->where('type', 'Change-Control')
                                    ->where('doc_id', $data->id)
                                    ->where('stage_id', 2)
                                    ->get();
                            @endphp
                            @foreach ($submit as $temp)
                                <div class="static">{{ $temp->user_name }}</div>
                            @endforeach
                        </td>

                        <th class="w-20">Submitted On</th>
                        <td class="w-30">
                            @php
                                $submit = DB::table('c_c_stage_histories')
                                    ->where('type', 'Change-Control')
                                    ->where('doc_id', $data->id)
                                    ->where('stage_id', 2)
                                    ->get();
                            @endphp
                            @foreach ($submit as $temp)
                                <div class="static">{{ Helpers::getdateFormat1($temp->created_at) }}</div>
                            @endforeach
                        </td>
                    </tr>

                    <tr>
                        <th class="w-20">Cancelled By</th>
                        <td class="w-30">
                            @php
                                $submit = DB::table('c_c_stage_histories')
                                    ->where('type', 'Change-Control')
                                    ->where('doc_id', $data->id)
                                    ->where('stage_id', 0)
                                    ->get();
                            @endphp
                            @foreach ($submit as $temp)
                                <div class="static">{{ $temp->user_name }}</div>
                            @endforeach
                        </td>
                        <th class="w-20">Cancelled On</th>
                        <td class="w-30">
                            @php
                                $submit = DB::table('c_c_stage_histories')
                                    ->where('type', 'Change-Control')
                                    ->where('doc_id', $data->id)
                                    ->where('stage_id', 0)
                                    ->get();
                            @endphp
                            @foreach ($submit as $temp)
                                <div class="static">{{ Helpers::getdateFormat1($temp->created_at) }}</div>
                            @endforeach
                        </td>
                    </tr>
                    {{-- <tr>
                        <th class="w-20">More Information Required By</th>
                        <td class="w-30">
                            @php
                            $submit = DB::table('c_c_stage_histories')
                                ->where('type', 'Change-Control')
                                ->where('doc_id', $data->id)
                                ->where('status', 'More-info Required')
                                ->get();
                        @endphp
                        @foreach ($submit as $temp)
                            <div class="static">{{ $temp->user_name }}</div>
                        @endforeach
                        </td>
                        <th class="w-20">More Information Required On</th>
                        <td class="w-30">
                            @php
                            $submit = DB::table('c_c_stage_histories')
                                ->where('type', 'Change-Control')
                                ->where('doc_id', $data->id)
                                ->where('status', 'More-info Required')
                                ->get();
                        @endphp
                        @foreach ($submit as $temp)
                            <div class="static">{{ $temp->created_at }}</div>
                        @endforeach
                        </td>
                    </tr> --}}
                    <tr>
                        <th class="w-20">HOD Review Complete By</th>
                        <td class="w-30"> @php
                            $submit = DB::table('c_c_stage_histories')
                                ->where('type', 'Change-Control')
                                ->where('doc_id', $data->id)
                                ->where('stage_id', 3)
                                ->get();
                        @endphp
                            @foreach ($submit as $temp)
                                <div class="static">{{ $temp->user_name }}</div>
                            @endforeach
                        </td>
                        <th class="w-20">HOD Review Complete On</th>
                        <td class="w-30">
                            @php
                                $submit = DB::table('c_c_stage_histories')
                                    ->where('type', 'Change-Control')
                                    ->where('doc_id', $data->id)
                                    ->where('stage_id', 3)
                                    ->get();
                            @endphp
                            @foreach ($submit as $temp)
                                <div class="static">{{ Helpers::getdateFormat1($temp->created_at) }}</div>
                            @endforeach
                        </td>
                    </tr>
                    {{-- <tr>
                        <th class="w-20">More Information Req. By</th>
                        <td class="w-30">Piyush Sahu</td>
                        <th class="w-20">More Information Req. On</th>
                        <td class="w-30">12-12-2203</td>
                    </tr> --}}
                    <tr>
                        <th class="w-20">Send to CFT/SME/QA Review By</th>
                        <td class="w-30">
                            @php
                                $submit = DB::table('c_c_stage_histories')
                                    ->where('type', 'Change-Control')
                                    ->where('doc_id', $data->id)
                                    ->where('stage_id', 4)
                                    ->get();
                            @endphp
                            @foreach ($submit as $temp)
                                <div class="static">{{ $temp->user_name }}</div>
                            @endforeach
                        </td>
                        <th class="w-20">Send to CFT/SME/QA Review On</th>
                        <td class="w-30">
                            @php
                                $submit = DB::table('c_c_stage_histories')
                                    ->where('type', 'Change-Control')
                                    ->where('doc_id', $data->id)
                                    ->where('stage_id', 4)
                                    ->get();
                            @endphp
                            @foreach ($submit as $temp)
                                <div class="static">{{ Helpers::getdateFormat1($temp->created_at) }}</div>
                            @endforeach
                        </td>
                    </tr>
                    <!-- <tr>
                        <th class="w-20">More Info Req. By</th>
                        <td class="w-30">Piyush Sahu</td>
                        <th class="w-20">More Info Req. On</th>
                        <td class="w-30">12-12-2203</td>
                    </tr> -->
                    <tr>
                        <th class="w-20">CFT/SME/QA Review Not required By</th>
                        <td class="w-30">
                            @php
                                $submit = DB::table('c_c_stage_histories')
                                    ->where('type', 'Change-Control')
                                    ->where('doc_id', $data->id)
                                    ->where('stage_id', 6)
                                    ->get();
                            @endphp
                            @foreach ($submit as $temp)
                                <div class="static">{{ $temp->user_name }}</div>
                            @endforeach
                        </td>
                        <th class="w-20">CFT/SME/QA Review Not required On</th>
                        <td class="w-30">
                            @php
                                $submit = DB::table('c_c_stage_histories')
                                    ->where('type', 'Change-Control')
                                    ->where('doc_id', $data->id)
                                    ->where('stage_id', 6)
                                    ->get();
                            @endphp
                            @foreach ($submit as $temp)
                                <div class="static">{{ Helpers::getdateFormat1($temp->created_at) }}</div>
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th class="w-20">Review Completed By</th>
                        <td class="w-30">
                            @php
                                $submit = DB::table('c_c_stage_histories')
                                    ->where('type', 'Change-Control')
                                    ->where('doc_id', $data->id)
                                    ->where('stage_id', 7)
                                    ->get();
                            @endphp
                            @foreach ($submit as $temp)
                                <div class="static">{{ $temp->user_name }}</div>
                            @endforeach
                        </td>
                        <th class="w-20">Review Completed On</th>
                        <td class="w-30">
                            @php
                                $submit = DB::table('c_c_stage_histories')
                                    ->where('type', 'Change-Control')
                                    ->where('doc_id', $data->id)
                                    ->where('stage_id', 7)
                                    ->get();
                            @endphp
                            @foreach ($submit as $temp)
                                <div class="static">{{ Helpers::getdateFormat1($temp->created_at) }}</div>
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th class="w-20">Implemented By</th>
                        <td class="w-30">
                            @php
                                $submit = DB::table('c_c_stage_histories')
                                    ->where('type', 'Change-Control')
                                    ->where('doc_id', $data->id)
                                    ->where('stage_id', 9)
                                    ->get();
                            @endphp
                            @foreach ($submit as $temp)
                                <div class="static">{{ $temp->user_name }}</div>
                            @endforeach
                        </td>
                        <th class="w-20">Implemented On</th>
                        <td class="w-30">
                            @php
                                $submit = DB::table('c_c_stage_histories')
                                    ->where('type', 'Change-Control')
                                    ->where('doc_id', $data->id)
                                    ->where('stage_id', 9)
                                    ->get();
                            @endphp
                            @foreach ($submit as $temp)
                                <div class="static">{{ Helpers::getdateFormat1($temp->created_at) }}</div>
                            @endforeach
                        </td>
                    </tr>
                    {{-- <tr>
                        <th class="w-20">Change Implemented By</th>
                        <td class="w-30">Piyush Sahu</td>
                        <th class="w-20">Change Implemented On</th>
                        <td class="w-30">12-12-2203</td>
                    </tr> --}}
                    <!-- <tr>
                        <th class="w-20">QA More Information Required By</th>
                        <td class="w-30">Piyush Sahu</td>
                        <th class="w-20">QA More Information Required On</th>
                        <td class="w-30">12-12-2203</td>
                    </tr> -->
                    {{-- <tr>
                        <th class="w-20">QA Final Review Completed By</th>
                        <td class="w-30">Piyush Sahu</td>
                        <th class="w-20">QA Final Review Completed On</th>
                        <td class="w-30">12-12-2203</td>
                    </tr> --}}
                </table>
            </div>
        </div>
    </div>



</body>

</html>
