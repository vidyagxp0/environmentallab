@extends('frontend.layout.main')
@section('container')
    <div id="audit-trial">
        <div class="container-fluid">
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
                    /* min-width: 100vw; */
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

                table,
                th,
                td {
                    border: 1px solid black;
                    border-collapse: collapse;
                    font-size: 0.9rem;
                }

                table {
                    width: 100%;
                }

                th,
                td {
                    padding: 10px;
                    text-align: left;
                }

                header .head {
                    font-weight: bold;
                    text-align: center;
                    font-size: 1.2rem;
                }

                @page {
                    size: A4;
                    margin-top: 160px;
                    margin-bottom: 60px;
                }

                header {
                    /* position: fixed; */
                    top: -140px;
                    left: 0;
                    width: 100%;
                    display: block;
                }

                footer {
                    /* position: fixed; */
                    bottom: -40px;
                    left: 0;
                    width: 100%;
                }

                .inner-block {
                    padding: 10px;
                }

                .inner-block .head {
                    font-weight: bold;
                    font-size: 1.2rem;
                    margin-bottom: 5px;
                }

                .inner-block .division {
                    margin-bottom: 10px;
                }

                .first-table {
                    border-top: 1px solid black;
                    margin-bottom: 20px;
                }

                .first-table table td,
                .first-table table th,
                .first-table table {
                    border: 0;
                }

                .second-table td:nth-child(1)>div {
                    margin-bottom: 10px;
                }

                .second-table td:nth-child(1)>div:nth-last-child(1) {
                    margin-bottom: 0px;
                }

                .table_bg {
                    /* background: #0e7676cc57; */
                    background: #0e7676cc
                }

                .heading {
                    border: 1px solid black;
                    padding: 10px;
                    margin-bottom: 10px;
                    margin-top: 10px;
                    /* background: #0e7676cc; */
                    background: #0e7676cc
                }

                .heading-new {
                    font-size: 27px;
                    color: #2f2f58;
                }

                .buttons-new {
                    display: flex;
                    justify-content: end;
                    gap: 10px;
                }
            </style>

            <body>

                <header>
                    <table>
                        <tr>
                        </tr>
                    </table>

                    <table>
                        <div class="heading">

                            <div class="heading-new">
                                Notification Detail
                            </div>
                            <div> <strong>Record ID : </strong> {{ str_pad($parentData->record, 4, '0', STR_PAD_LEFT) }}</div>
                            <div style="margin-bottom: 5px;  font-weight: bold;"> Notification Issued By
                                : {{ $notification->user_name ? $notification->user_name : '' }}
                            </div>
                            <div style="margin-bottom: 5px;  font-weight: bold;">Due Date : {{ Helpers::getdateFormat($parentData->due_date) }}
                            </div>
                        </div>
        </div>
        </table>

        </header>

        <div class="inner-block">
            <div class="division">
            </div>
            <div class="second-table">
                <table>
                    <tr class="table_bg">
                        <th>User Name</th>
                        <th>User Email</th>
                        <th>User Role</th>
                        <th>Method</th>
                    </tr>

                    <tr>
                        @foreach ($getName as $userDetail)
                            <td>
                                <div>{{ $userDetail->name }}</div>
                            </td>
                            <td>
                                <div>{{ $userDetail->email }}</div>
                            </td>
                            <td>
                                <div>{{ $notification->role_name }}</div>
                            </td>
                            <td>
                                <div>Email</div>
                            </td>
                    </tr>
                    @endforeach
                </table>
            </div>
        </div>
        <div style="float: inline-end; margin: 10px;">
            <style>
                .pagination>.active>span {
                    background-color: #0e7676cc !important;
                    border-color: #0e7676cc !important;
                    color: #fff !important;
                }

                .pagination>.active>span:hover {
                    background-color: #0e7676cc !important;
                    border-color: #0e7676cc !important;
                }

                .pagination>li>a,
                .pagination>li>span {
                    color: #0e7676cc !important;
                }

                .pagination>li>a:hover {
                    background-color: #0e7676cc !important;
                    border-color: #0e7676cc !important;
                    color: #fff !important;
                }
            </style>
        </div>

        </body>

        </html>

    </div>
    </div>
@endsection
