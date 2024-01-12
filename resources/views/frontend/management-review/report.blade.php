<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Connexo - Software</title>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
</head>

<body style="margin: 0px">
    <style>
        table.bordered td,
        table.bordered th {
            border: 1px solid #000;
            padding: 8px;
        }

        table.bordered th.main-head {
            border: 1px solid #fff;
            padding: 8px;
            text-align: left;
        }

        .report-chart {
            max-width: 650px;
            margin: 35px auto;
            background: #dbdbdb;
            padding: 10px 0px;
        }
    </style>
    <table style="width: 700px;">
        <!-- TABLE HEADER -->
        <thead align="left" style="display: table-header-group">
            <tr>
                <th>
                    <table style="width: 100%;">
                        <tr>
                            <td style="width: 150px; vertical-align: middle">
                                <img src="https://dms.connexodemo.com/user/images/logo1.png" alt="..."
                                    style="width: 150px; padding:8px;">
                            </td>
                            <td>&nbsp;</td>
                            <td style="width: 150px; vertical-align: middle">
                                <img src="https://dms.connexodemo.com/user/images/customer.png" alt="..."
                                    style="width: 150px; padding:8px;">
                            </td>
                            <td>&nbsp;</td>
                            <td style="width: 150px; vertical-align: middle">
                                <img src="https://dms.connexodemo.com/user/images/logo.png" alt="..."
                                    style="width: 150px; padding:8px;">
                            </td>
                        </tr>
                    </table>
                </th>
            </tr>
            <tr>
                <td>
                    <table style="width: 100%;">
                        <tr style="background: #4274da;">
                            <td style="color: white; padding: 8px; font-size: 1.2rem;">Management Review Report</td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
        </thead>

        <!-- TABLE FOOTER -->
        <tfoot align="left" style="display: table-footer-group">
            <tr>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>
                    <table style="width: 100%; border-top: 1px solid black;">
                        <tr>
                            <td style="padding: 8px; font-size: 0.8rem;">Printed by : Amit Guru</td>
                            <td style="padding: 8px; font-size: 0.8rem; text-align: center;">Printed on : 12 Dec, 2023</td>
                            <td style="padding: 8px; font-size: 0.8rem; text-align: right;">Page 1 of 10</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </tfoot>

        <!-- TABLE CONTENT -->
        <tbody>
            <tr>
                <td>
                    <table>
                        <tbody>
                            <tr>
                                <td style="padding: 8px">&nbsp;</td>
                            </tr>
                            <tr>
                                <td style="padding: 8px">&nbsp;</td>
                            </tr>
                            <tr>
                                <td style="padding: 8px">
                                    Period : 6 Months
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 8px">
                                    Report Number : CNX - 0004
                                </td>
                            </tr>
                            <tr>
                                <td style="padding: 8px">&nbsp;</td>
                            </tr>
                            <tr>
                                <td style="padding: 8px">&nbsp;</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>

        <tbody>
            <tr>
                <td>
                    <table class="bordered" style="width: 100%; border-collapse: collapse; font-size: 0.8rem;">

                        <thead style="display: table-header-group">
                            <tr style="font-size: 1rem;">
                                <th class="main-head" colspan="8">[A] Internal Audit</th>
                            </tr>
                            <tr style="background: #4274da; color: white;">
                                <th>Audit Type</th>
                                <th>Audit Date</th>
                                <th colspan="6">Findings</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr style="background: #d7d9db;">
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>Critical</td>
                                <td>Major</td>
                                <td>Minor</td>
                                <td>Recommendation</td>
                                <td>CAPA Details</td>
                                <td>Closure Details</td>
                            </tr>
                            @foreach ($managementReview->internalAudit as $temp)
                            <tr>
                                <td>{{ $temp->audit_type }}</td>
                                <td>{{Helpers::getdateFormat($temp->created_at) }}</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                            @endforeach
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>

                        </tbody>

                    </table>
                </td>
            </tr>
            <tr>
                <td style="padding: 8px">&nbsp;</td>
            </tr>
            <tr>
                <td class="report-chart">
                   <img src="https://dms.connexodemo.com/user/reportChart/r2.PNG" width="100%">
                </td>
            </tr>
            <tr>
                <td style="padding: 8px">&nbsp;</td>
            </tr>
            <tr>
                <td class="report-chart">
                    <img src="https://dms.connexodemo.com/user/reportChart/r1.PNG" width="100%">
                 </td>
            </tr>
            <tr>
                <td style="padding: 8px">&nbsp;</td>
            </tr>
            <tr>
                <td class="report-chart">
                    <img src="https://dms.connexodemo.com/user/reportChart/r3.PNG" width="100%">
                 </td>
            </tr>
            <tr>
                <td style="padding: 8px">&nbsp;</td>
            </tr>
        </tbody>

        <tbody>
            <tr>
                <td>
                    <table class="bordered" style="width: 100%; border-collapse: collapse; font-size: 0.8rem;">

                        <thead style="display: table-header-group">
                            <tr style="font-size: 1rem;">
                                <th class="main-head" colspan="8">[B] External Audit</th>
                            </tr>
                            <tr style="background: #4274da; color: white;">
                                <th>Audit Type</th>
                                <th>Audit Date</th>
                                <th colspan="6">Findings</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr style="background: #d7d9db;">
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>Critical</td>
                                <td>Major</td>
                                <td>Minor</td>
                                <td>Recommendatio</td>
                                <td>CAPA Details</td>
                                <td>Closure Details</td>
                            </tr>
                            @foreach ($managementReview->externalAudit as $temp)
                            <tr>
                                <td>{{$temp->type_of_audit}}</td>
                                <td>{{ Helpers::getdateFormat($temp->created_at) }}</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                            </tr>
                            @endforeach
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>

                        </tbody>

                    </table>
                </td>
            </tr>
            <tr>
                <td style="padding: 8px">&nbsp;</td>
            </tr>
            <tr>
                <td class="report-chart">
                    <img src="https://dms.connexodemo.com/user/reportChart/r4.PNG" width="100%">
                 </td>
            </tr>
            <tr>
                <td style="padding: 8px">&nbsp;</td>
            </tr>
            <tr>
                <td class="report-chart">
                    <img src="https://dms.connexodemo.com/user/reportChart/r5.PNG" width="100%">
                 </td>
            </tr>
            <tr>
                <td style="padding: 8px">&nbsp;</td>
            </tr>
            <tr>
                <td class="report-chart">
                    <img src="https://dms.connexodemo.com/user/reportChart/r6.PNG" width="100%">
                 </td>
            </tr>
            <tr>
                <td style="padding: 8px">&nbsp;</td>
            </tr>
        </tbody>

        <tbody>
            <tr>
                <td>
                    <table class="bordered" style="width: 100%; border-collapse: collapse; font-size: 0.8rem;">

                        <thead style="display: table-header-group">
                            <tr style="font-size: 1rem;">
                                <th class="main-head" colspan="9">[C] Action Item Details</th>
                            </tr>
                            <tr style="background: #4274da; color: white;">
                                <th>Record Number</th>
                                <th>Short Description</th>
                                <th>CAPA Type (Corrective Action / Preventive Action)</th>
                                <th>Date Opened</th>
                                <th>Site / Division</th>
                                <th>Date Due</th>
                                <th>Current Status</th>
                                <th>Person Responsible</th>
                                <th>Date Closed</th>
                            </tr>
                        </thead>

                        <tbody>
                            @if(count($managementReview->actionItem)>0)
                            @foreach ($managementReview->actionItem as $temp)
                            <tr>
                                <td>{{ str_pad($temp->record, 4, '0', STR_PAD_LEFT) }}</td>
                                <td>{{ $temp->description }}</td>
                                <td>{{ $temp->action_taken }}</td>
                                <td>{{ Helpers::getdateFormat($temp->created_at) }}</td>
                                <td>{{ $temp->site }}</td>
                                <td>{{ $temp->due_date }}</td>
                                <td>{{ $temp->status }}</td>
                                <td>{{ $temp->assign_to }}</td>
                                <td>{{ $temp->updated_at }}</td>

                            </tr>
                            @endforeach
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            @else
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            @endif
                        </tbody>

                    </table>
                </td>
            </tr>
            <tr>
                <td style="padding: 8px">&nbsp;</td>
            </tr>
        </tbody>

        <tbody>
            <tr>
                <td>
                    <table class="bordered" style="width: 100%; border-collapse: collapse; font-size: 0.8rem;">

                        <thead style="display: table-header-group;">
                            <tr style="font-size: 1rem;">
                                <th class="main-head" style="border-bottom: 1px solid #000;">
                                    [D] Suitability of Policies and Procedure
                                </th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td>
                                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Odio earum cumque itaque.
                                </td>
                            </tr>
                        </tbody>

                    </table>
                </td>
            </tr>
            <tr>
                <td style="padding: 8px">&nbsp;</td>
            </tr>
        </tbody>

        <tbody>
            <tr>
                <td>
                    <table class="bordered" style="width: 100%; border-collapse: collapse; font-size: 0.8rem;">

                        <thead style="display: table-header-group">
                            <tr style="font-size: 1rem;">
                                <th class="main-head" colspan="5">
                                    [E] Status of Actions from Previous Management Reviews
                                </th>
                            </tr>
                            <tr style="background: #4274da; color: white;">
                                <th>Action Item Details</th>
                                <th>Owner</th>
                                <th>Due Date</th>
                                <th>Status</th>
                                <th>Remarks</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                        </tbody>

                    </table>
                </td>
            </tr>
            <tr>
                <td style="padding: 8px">&nbsp;</td>
            </tr>
        </tbody>

        <tbody>
            <tr>
                <td>
                    <table class="bordered" style="width: 100%; border-collapse: collapse; font-size: 0.8rem;">

                        <thead style="display: table-header-group">
                            <tr style="font-size: 1rem;">
                                <th class="main-head" colspan="7">[F] Outcome of Recent Internal Audits</th>
                            </tr>
                            <tr style="background: #4274da; color: white;">
                                <th>Month</th>
                                <th>Sites Audited</th>
                                <th>Critical</th>
                                <th>Major</th>
                                <th>Minor</th>
                                <th>Recommendation</th>
                                <th>CAPA Details, if any</th>
                            </tr>
                        </thead>

                        <tbody>

                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                        </tbody>

                    </table>
                </td>
            </tr>
            <tr>
                <td style="padding: 8px">&nbsp;</td>
            </tr>
        </tbody>

        <tbody>
            <tr>
                <td>
                    <table class="bordered" style="width: 100%; border-collapse: collapse; font-size: 0.8rem;">

                        <thead style="display: table-header-group">
                            <tr style="font-size: 1rem;">
                                <th class="main-head" colspan="7">[G] Outcome of Recent External Audits</th>
                            </tr>
                            <tr style="background: #4274da; color: white;">
                                <th>Month</th>
                                <th>Sites Audited</th>
                                <th>Critical</th>
                                <th>Major</th>
                                <th>Minor</th>
                                <th>Recommendation</th>
                                <th>CAPA Details, if any</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                        </tbody>

                    </table>
                </td>
            </tr>
            <tr>
                <td style="padding: 8px">&nbsp;</td>
            </tr>
        </tbody>

        <tbody>
            <tr>
                <td>
                    <table class="bordered" style="width: 100%; border-collapse: collapse; font-size: 0.8rem;">

                        <thead style="display: table-header-group">
                            <tr style="font-size: 1rem;">
                                <th class="main-head" colspan="9">[H] CAPA Details</th>
                            </tr>
                            <tr style="background: #4274da; color: white;">
                                <th>Record Number</th>
                                <th>Short Description</th>
                                <th>CAPA Type (Corrective Action / Preventive Action)</th>
                                <th>Date Opened</th>
                                <th>Site / Division</th>
                                <th>Date Due</th>
                                <th>Current Status</th>
                                <th>Person Responsible</th>
                                <th>Date Closed</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($managementReview->capa as $temp)
                            <tr>
                                <td>{{ Helpers::record($temp->record) }}</td>
                                <td>{{ $temp->short_description }}</td>
                                <td>{{ $temp->corrective_action }} {{ $temp->preventive_action }}</td>
                                <td>{{ Helpers::getdateFormat($temp->created_at) }}</td>
                                <td>{{ Helpers::getDivisionName($temp->division_id) }}</td>
                                <td>{{ $temp->due_date }}</td>
                                <td>{{ $temp->status }}</td>
                                <td>{{ Helpers::getInitiatorName($temp->assign_id) }}</td>
                                @if($temp->stage == 6)
                                <td> Document not close yet </td>
                                @else
                                <td>{{ Helpers::getdateFormat($temp->updated_at) }}</td>
                                @endif
                            </tr>
                            @endforeach

                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>

                        </tbody>

                    </table>
                </td>
            </tr>
            <tr>
                <td style="padding: 8px">&nbsp;</td>
            </tr>
        </tbody>

        <tbody>
            <tr>
                <td>
                    <table class="bordered" style="width: 100%; border-collapse: collapse; font-size: 0.8rem;">

                        <thead style="display: table-header-group">
                            <tr style="font-size: 1rem;">
                                <th class="main-head" colspan="8">[I] Root Cause Analysis Details</th>
                            </tr>
                            <tr style="background: #4274da; color: white;">
                                <th>Record Number</th>
                                <th>Short Description</th>
                                <th>Date Opened</th>
                                <th>Site / Division</th>
                                <th>Date Due</th>
                                <th>Current Status</th>
                                <th>Person Responsible</th>
                                <th>Date Closed</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($managementReview->rootCause as $temp)
                            <tr>
                                <td>{{ Helpers::record($temp->record) }}</td>
                                <td>{{ $temp->short_description }}</td>
                                <td>{{ Helpers::getdateFormat($temp->created_at) }}</td>
                                <td>{{ Helpers::getDivisionName($temp->division_id) }}</td>
                                <td>{{ $temp->due_date }}</td>
                                <td>{{ $temp->status }}</td>
                                <td>{{ Helpers::getInitiatorName($temp->assign_id) }}</td>
                                @if($temp->stage == 6)
                                <td> Document not close yet </td>
                                @else
                                <td>{{ Helpers::getdateFormat($temp->updated_at) }}</td>
                                @endif
                            </tr>
                            @endforeach
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>

                        </tbody>

                    </table>
                </td>
            </tr>
            <tr>
                <td style="padding: 8px">&nbsp;</td>
            </tr>
        </tbody>

        <tbody>
            <tr>
                <td>
                    <table class="bordered" style="width: 100%; border-collapse: collapse; font-size: 0.8rem;">

                        <thead style="display: table-header-group">
                            <tr style="font-size: 1rem;">
                                <th class="main-head" colspan="8">[J] Lab Incident Details</th>
                            </tr>
                            <tr style="background: #4274da; color: white;">
                                <th>Record Number</th>
                                <th>Short Description</th>
                                <th>Date Opened</th>
                                <th>Site / Division</th>
                                <th>Date Due</th>
                                <th>Current Status</th>
                                <th>Person Responsible</th>
                                <th>Date Closed</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($managementReview->LabIncident as $temp)
                            <tr>
                                <td>{{ Helpers::record($temp->record) }}</td>
                                <td>{{ $temp->short_description }}</td>
                                <td>{{ Helpers::getdateFormat($temp->created_at) }}</td>
                                <td>{{ Helpers::getDivisionName($temp->division_id) }}</td>
                                <td>{{ $temp->due_date }}</td>
                                <td>{{ $temp->status }}</td>
                                <td>{{ Helpers::getInitiatorName($temp->assign_id) }}</td>
                                @if($temp->stage == 6)
                                <td> Document not close yet </td>
                                @else
                                <td>{{ Helpers::getdateFormat($temp->updated_at) }}</td>
                                @endif
                            </tr>
                            @endforeach
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>

                        </tbody>

                    </table>
                </td>
            </tr>
            <tr>
                <td style="padding: 8px">&nbsp;</td>
            </tr>
        </tbody>

        <tbody>
            <tr>
                <td>
                    <table class="bordered" style="width: 100%; border-collapse: collapse; font-size: 0.8rem;">

                        <thead style="display: table-header-group">
                            <tr style="font-size: 1rem;">
                                <th class="main-head" colspan="9">[K] Risk Assessment Details</th>
                            </tr>
                            <tr style="background: #4274da; color: white;">
                                <th>Record Number</th>
                                <th>Short Description</th>
                                <th>Risk Category</th>
                                <th>Date Opened</th>
                                <th>Site / Division</th>
                                <th>Date Due</th>
                                <th>Current Status</th>
                                <th>Person Responsible</th>
                                <th>Date Closed</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($managementReview->riskAnalysis as $temp)
                            <tr>
                                <td>{{ Helpers::record($temp->record) }}</td>
                                <td>{{ $temp->short_description }}</td>
                                <td>{{ $temp->type }}</td>
                                <td>{{ Helpers::getdateFormat($temp->created_at) }}</td>
                                <td>{{ Helpers::getDivisionName($temp->division_id) }}</td>
                                <td>{{ $temp->due_date }}</td>
                                <td>{{ $temp->status }}</td>
                                <td>{{ Helpers::getInitiatorName($temp->assign_id) }}</td>
                                @if($temp->stage == 6)
                                <td> Document not close yet </td>
                                @else
                                <td>{{ Helpers::getdateFormat($temp->updated_at) }}</td>
                                @endif
                            </tr>
                            @endforeach
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>

                        </tbody>

                    </table>
                </td>
            </tr>
            <tr>
                <td style="padding: 8px">&nbsp;</td>
            </tr>
        </tbody>

        <tbody>
            <tr>
                <td>
                    <table class="bordered" style="width: 100%; border-collapse: collapse; font-size: 0.8rem;">

                        <thead style="display: table-header-group">
                            <tr style="font-size: 1rem;">
                                <th class="main-head" colspan="9">[L] Change Control Details</th>
                            </tr>
                            <tr style="background: #4274da; color: white;">
                                <th>Record Number</th>
                                <th>Short Description</th>
                                <th>Change Type</th>
                                <th>Date Opened</th>
                                <th>Site / Division</th>
                                <th>Date Due</th>
                                <th>Current Status</th>
                                <th>Person Responsible</th>
                                <th>Date Closed</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($managementReview->changeControl as $temp)
                            <tr>
                                <td>{{ Helpers::record($temp->record) }}</td>
                                <td>{{ $temp->short_description }}</td>
                                <td>{{ $temp->type_chnage }}</td>
                                <td>{{ Helpers::getdateFormat($temp->created_at) }}</td>
                                <td>{{ Helpers::getDivisionName($temp->division_id) }}</td>
                                <td>{{ $temp->due_date }}</td>
                                <td>{{ $temp->status }}</td>
                                <td>{{ Helpers::getInitiatorName($temp->assign_id) }}</td>
                                @if($temp->stage == 6)
                                <td> Document not close yet </td>
                                @else
                                <td>{{ Helpers::getdateFormat($temp->updated_at) }}</td>
                                @endif
                            </tr>
                            @endforeach
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>

                        </tbody>

                    </table>
                </td>
            </tr>
            <tr>
                <td style="padding: 8px">&nbsp;</td>
            </tr>
        </tbody>

        <tbody>
            <tr>
                <td>
                    <table class="bordered" style="width: 100%; border-collapse: collapse; font-size: 0.8rem;">

                        <thead style="display: table-header-group">
                            <tr style="font-size: 1rem;">
                                <th class="main-head" colspan="11">[M] Assessment by External Bodies</th>
                            </tr>
                            <tr style="background: #4274da; color: white;">
                                <th>External Body</th>
                                <th>Short Description</th>
                                <th>Type</th>
                                <th>Site / Division</th>
                                <th>Assessment Date</th>
                                <th>Assessment Details</th>
                                <th>Date Due</th>
                                <th>Current Status</th>
                                <th>Person Responsible</th>
                                <th>Date Closed</th>
                                <th>Related Documents</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                        </tbody>

                    </table>
                </td>
            </tr>
            <tr>
                <td style="padding: 8px">&nbsp;</td>
            </tr>
        </tbody>

        <tbody>
            <tr>
                <td>
                    <table class="bordered" style="width: 100%; border-collapse: collapse; font-size: 0.8rem;">

                        <thead style="display: table-header-group">
                            <tr style="font-size: 1rem;">
                                <th class="main-head" colspan="10">[N] Issues other than Audits</th>
                            </tr>
                            <tr style="background: #4274da; color: white;">
                                <th>Short Description</th>
                                <th>Severity (Critical / Major / Minor)</th>
                                <th>Site / Division</th>
                                <th>Issue Reporting Date</th>
                                <th>CAPA Details if any</th>
                                <th>Date Due</th>
                                <th>Current Status</th>
                                <th>Person Responsible</th>
                                <th>Date Closed</th>
                                <th>Related Documents</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                        </tbody>

                    </table>
                </td>
            </tr>
            <tr>
                <td style="padding: 8px">&nbsp;</td>
            </tr>
        </tbody>

        <tbody>
            <tr>
                <td>
                    <table class="bordered" style="width: 100%; border-collapse: collapse; font-size: 0.8rem;">

                        <thead style="display: table-header-group">
                            <tr style="font-size: 1rem;">
                                <th class="main-head" colspan="9">[O] Customer/Personnel Feedback</th>
                            </tr>
                            <tr style="background: #4274da; color: white;">
                                <th>Feedback From (Customer / Personnel)</th>
                                <th>Feedback Reporting Date</th>
                                <th>Site / Division</th>
                                <th>Short Description</th>
                                <th>Date Due</th>
                                <th>Current Status</th>
                                <th>Person Responsible</th>
                                <th>Date Closed</th>
                                <th>Related Documents</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                        </tbody>

                    </table>
                </td>
            </tr>
            <tr>
                <td style="padding: 8px">&nbsp;</td>
            </tr>
        </tbody>

        <tbody>
            <tr>
                <td>
                    <table class="bordered" style="width: 100%; border-collapse: collapse; font-size: 0.8rem;">

                        <thead style="display: table-header-group">
                            <tr style="font-size: 1rem;">
                                <th class="main-head" colspan="8">[P] Effectiveness Check Details</th>
                            </tr>
                            <tr style="background: #4274da; color: white;">
                                <th>Record Number</th>
                                <th>Short Description</th>
                                <th>Date Opened</th>
                                <th>Site / Division</th>
                                <th>Date Due</th>
                                <th>Current Status</th>
                                <th>Person Responsible</th>
                                <th>Date Closed</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($managementReview->changeControl as $temp)
                            <tr>
                                <td>{{ Helpers::record($temp->record) }}</td>
                                <td>{{ $temp->short_description }}</td>
                                <td>{{ Helpers::getdateFormat($temp->created_at) }}</td>
                                <td>{{ Helpers::getDivisionName($temp->division_id) }}</td>
                                <td>{{ $temp->due_date }}</td>
                                <td>{{ $temp->status }}</td>
                                <td>{{ Helpers::getInitiatorName($temp->originator) }}</td>
                                @if($temp->stage == 6)
                                <td> Document not close yet </td>
                                @else
                                <td>{{ Helpers::getdateFormat($temp->updated_at) }}</td>
                                @endif
                            </tr>
                            @endforeach
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>

                        </tbody>

                    </table>
                </td>
            </tr>
            <tr>
                <td style="padding: 8px">&nbsp;</td>
            </tr>
        </tbody>

        <tbody>
            <tr>
                <td>
                    <table class="bordered" style="width: 100%; border-collapse: collapse; font-size: 0.8rem;">

                        <thead style="display: table-header-group">
                            <tr style="font-size: 1rem;">
                                <th class="main-head">[Q] Comments</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr></tr>
                        </tbody>

                    </table>
                </td>
            </tr>
            <tr>
                <td style="padding: 8px">&nbsp;</td>
            </tr>
        </tbody>

        <tbody>
            <tr>
                <td>
                    <table class="bordered" style="width: 100%; border-collapse: collapse; font-size: 0.8rem;">

                        <thead style="display: table-header-group">
                            <tr style="font-size: 1rem;">
                                <th class="main-head">[R] Summary & Recommendations</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr></tr>
                        </tbody>

                    </table>
                </td>
            </tr>
            <tr>
                <td style="padding: 8px">&nbsp;</td>
            </tr>
        </tbody>

    </table>


</body>

</html>
