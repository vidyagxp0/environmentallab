<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>VidyaGxP - Software</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
</head>

<style>
    body {
        font-family: 'Roboto', sans-serif;
        margin: 0;
        padding: 0;
        min-height: 100vh;
    }

    .imageContainer p img {
        width: 600px !important;
        height: 300px;
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

    /* .inner-block {
        padding: 10px;
    } */

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

    .Summer {
        font-weight: bold;
        font-size: 14px;
    }
    td {
        word-break: break-all; /* Breaks long text anywhere */
    }

    /* For breaking at slashes specifically */
    td {
        overflow-wrap: break-word; /* Breaks at word boundaries */
        word-wrap: break-word; /* Compatibility for older browsers */
    }
    td.break-slash {
        word-break: keep-all;
        white-space: normal; /* Ensures text can wrap */
    }

</style>

<body>

    <header>
        <table>
            <tr>
                <td class="w-70 head">
                    DMS Dashboard
                </td>
                <td class="w-30">
                    <div class="logo">
                        <img src="https://dms.mydemosoftware.com/user/images/logo.png" alt="" class="w-100">
                    </div>
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
                <td class="w-30" style="text-align: left">
                    <strong>Page No. :</strong>
                </td>

            </tr>
        </table>
    </footer>

    <div class="inner-block">
        <div class="content-table">

            <div class="border-table">
                <table style="width: 100%; table-layout: fixed; border-collapse: collapse;">
                    <thead>
                        <tr>
                            <th style="width: 5%; font-size: 0.6rem;">ID</th>
                            <th style="width: 10%; font-size: 0.6rem;">Document Type</th>
                            <th style="width: 10%; font-size: 0.6rem;">Document Name</th>
                            <th style="width: 10%; font-size: 0.6rem;">SOP No.</th>
                            <th style="width: 10%; font-size: 0.6rem;">Division</th>
                            <th style="width: 20%; font-size: 0.6rem;">Short Description</th>
                            <th style="width: 10%; font-size: 0.6rem;">Create Date</th>
                            <th style="width: 10%; font-size: 0.6rem;">Originator</th>
                            <th style="width: 10%; font-size: 0.6rem;">Modify Date</th>
                            <th style="width: 5%; font-size: 0.6rem;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($documents as $doc)
                        <tr>
                            <td style="width: 6%; font-size: 0.5rem; padding: 5px;">000{{ $doc->id }}</td>
                            <td style="width: 10%; font-size: 0.5rem; padding: 5px;">{{ $doc->document_type_name }}</td>
                            <td style="width: 10%; font-size: 0.5rem; padding: 5px;">{{ $doc->document_name }}</td>
                            <td style="width: 10%; font-size: 0.5rem; padding: 5px;" class="break-slash">{{ $doc->sop_no }}</td>
                            <td style="width: 10%; font-size: 0.5rem; padding: 5px;">{{ Helpers::getDivisionName($doc->division_id) }}</td>
                            <td style="width: 20%; font-size: 0.5rem; padding: 5px;">{{ $doc->short_description }}</td>
                            <td style="width: 8%; font-size: 0.5rem; padding: 5px;">{{ Helpers::getdateFormat($doc->created_at) }}</td>
                            <td style="width: 10%; font-size: 0.5rem; padding: 5px;">{{ $doc->originator_name }}</td>
                            <td style="width: 8%; font-size: 0.5rem; padding: 5px;">{{ Helpers::getdateFormat($doc->updated_at) }}</td>
                            <td style="width: 8%; font-size: 0.5rem; padding: 5px;">{{ $doc->status }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>

        </div>
    </div>

</body>

</html>
