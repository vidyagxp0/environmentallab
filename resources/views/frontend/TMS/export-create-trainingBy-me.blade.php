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
                    TMS Dashboard
                </td>
                <td class="w-30">
                    <div class="logo">
                        <img src="user/images/logo1.png" alt="" class="w-100">
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
                            <th style="width: 6%; font-size: 0.6rem;">S.No.</th>
                            <th style="width: 10%; font-size: 0.6rem;">Training Plan</th>
                            <th style="width: 10%; font-size: 0.6rem;">Number of SOPs</th>
                            <th style="width: 10%; font-size: 0.6rem;">Effective Criteria</th>
                            <th style="width: 10%; font-size: 0.6rem;">Number of Trainees</th>
                            <th style="width: 10%; font-size: 0.6rem;">Status</th>
                
                        </tr>
                    </thead>
                    <tbody>
                       @foreach ($userTrainings as $index => $temp)
                            <tr>
                                <td style="width: 6%; font-size: 0.5rem; padding: 5px;">{{ $index + 1 }}</td>
                                <td style="width: 20%; font-size: 0.5rem; padding: 5px;">{{ $temp->traning_plan_name }}</td>
                                <td style="width: 10%; font-size: 0.5rem; padding: 5px;">{{ count(explode(',', $temp->sops)) }}</td>
                                <td style="width: 10%; font-size: 0.5rem; padding: 5px;">{{ $temp->effective_criteria }}</td>
                                <td style="width: 10%; font-size: 0.5rem; padding: 5px;">{{ $temp->trainees_count }}</td>
                                <td style="width: 10%; font-size: 0.5rem; padding: 5px;">{{ $temp->status }}</td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>

            </div>

        </div>
    </div>

</body>

</html>
