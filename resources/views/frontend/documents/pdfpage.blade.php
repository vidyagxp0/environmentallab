<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>DMS Document</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+KR:wght@100..900&family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">

    <style>

       @font-face {
        font-family: 'times-roman';
        src: url("{{ asset('fonts/Times New Roman.ttf') }}");
    }

    * {
        font-family: times-roman, "Open Sans", "Roboto", "Noto Sans KR", "Poppins", sans-serif;
        font-size: 13px;
        line-height: 1.5;
        color: #333;
    }

        p {
            font-size: 12px;
        }

        .symbol-support {
            font-family:"DeJaVu Sans Mono", monospace !important;
        }

        html{
            text-align: justify;
            text-justify: inter-word;
        }
        table {
            width: 100%;
        }
        td,
        th {
            text-align: center;

        }


        .w-5 {
            width: 5%;
        }

        .w-10 {
            width: 10%;
        }

        .w-15 {
            width: 15%;
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

        .w-33 {
            width: 33%;
        }

        .w-35 {
            width: 35%;
        }

        .w-40 {
            width: 40%;
        }

        .w-45 {
            width: 45%;
        }

        .w-50 {
            width: 50%;
        }

        .w-55 {
            width: 55%;

        }

        .w-60 {
            width: 60%;
        }

        .w-65 {
            width: 65%;
        }

        .w-70 {
            width: 70%;
        }

        .w-75 {
            width: 75%;
        }

        .w-80 {
            width: 80%;
        }

        .w-85 {
            width: 85%;
        }

        .w-75 {
            width: 75%;
        }

        .w-80 {
            width: 80%;
        }

        .w-85 {
            width: 85%;
        }

        .w-90 {
            width: 90%;
        }

        .w-95 {
            width: 95%;
        }

        .w-100 {
            width: 100%;
        }

        .border {
            border: 1px solid black;
        }

        .border-top {
            border-top: 1px solid black;
        }

        .border-bottom {
            border-bottom: 1px solid black;
        }

        .border-left {
            border-left: 1px solid black;
        }

        .border-right {
            border-right: 1px solid black;
        }

        .border-top-none {
            border-top: 0px solid black;
        }

        .border-bottom-none {
            border-bottom: 0px solid black;
        }

        .border-left-none {
            border-left: 0px solid black;
        }

        .border-right-none {
            border-right: 0px solid black;
        }

        .p-20 {
            padding: 20px;
        }

        .p-10 {
            padding: 10px;
        }

        .mb-50 {
            margin-bottom: 50px;
        }

        .mb-40 {
            margin-bottom: 40px;
        }

        .mb-30 {
            margin-bottom: 30px;
        }

        .mb-20 {
            margin-bottom: 20px;
        }

        .mb-10 {
            margin-bottom: 10px;
        }

        .text-left {
            text-align: left;
            word-wrap: break-word;
        }

        .text-right {
            text-align: right;
        }

        .text-justify {
            text-align: justify;

        }

        .text-center {
            text-align: center;
        }

        .bold {
            font-weight: bold;
        }

        .vertical-baseline {
            vertical-align: baseline;
        }

        table.table-bordered {
            border-collapse: collapse;
            border: 1px solid grey;

        }

        table.table-bordered td,
        table.table-bordered th {
            border: 1px solid grey;
            padding: 5px 10px;

        }

        table.small-content td,
        table.small-content th {
            font-size: 0.85rem;

        }

        td.title {
            font-size: 1.1rem;
            font-weight: bold;
        }

        td.logo img {
            width: 100%;
            max-width: 100px;
            aspect-ratio: 1/0.35;

        }

        td.doc-num {
            font-size: 1rem;
            font-weight: bold;

        }

        .doc-control .head {
            max-width: 600px;
            margin: 0 auto 30px;

        }

        .doc-control .head div:nth-child(1) {
            font-size: 1.5rem;
            text-align: center;
            font-weight: bold;
            margin-bottom: 5px;

        }

        .doc-control .body .block-head {
            border-bottom: 2px solid black;
            font-size: 1.2rem;
            font-weight: bold;
            margin-bottom: 15px;
        }

        @page {
            size: A4;
            margin-top: 240px;
            margin-bottom:60px;
        }

        header {
            width: 100%;
            position: fixed;
            top: -225px;
            right: 0;
            left: 0;
            display: block;
            height: 210px;
        }


        .footer {
            position: fixed;
            bottom: -45px;
            left: 0;
            right: 0;
            width: 100%;
            display: block;
            border-top: 1px solid #ddd; /* Optional: Add a border at the top of the footer */
        }


        .other-container {
            margin: 0 0 0 0;

        }

        .other-container>table {
            margin: 0px 0 0;

        }

        .scope-block,
        .procedure-block {
            margin: 0px 0 15px;
            word-wrap: break-word;
        }

        .annexure-block {
            margin: 40px 0 0;
        }

        .empty-page {
            page-break-after: always;
        }

        #pdf-page {
            /* page-break-inside: avoid; */
        }
        .page-break-before {
            page-break-before: always;
        }

        .table-responsive {
            overflow-x: auto;
            max-width: 100%;
        }

        .MsoNormalTable tr {
            border: 1px solid rgb(156, 156, 156);
        }

        .MsoNormalTable td {
            text-align: left!important;
        }

        .MsoNormalTable tbody {
            border: 1px solid rgb(156, 156, 156);
        }
        img {
            width: 100%;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
            page-break-after: auto;
            page-break-inside: auto;
            page-break-before: auto;
        }

        .MsoNormalTable, .table {
            table-layout: fixed;
            width: 650px!important;
        }

        /* CSS to allow page breaks after and inside common HTML elements */
        p, b, div, h1, h2, h3, h4, h5, h6, ol, ul, li, span {
            page-break-after: auto;  /* Allows automatic page breaks after these elements */
            page-break-inside: auto; /* Allows page breaks inside these elements */
        }

        /* Additional styles to ensure list items are handled correctly */
        ol, ul {
            page-break-before: auto; /* Allows page breaks before lists */
            page-break-inside: auto; /* Prefer avoiding breaks inside lists */
        }

        li {
            page-break-after: auto; /* Allows automatic page breaks after list items */
            page-break-inside: auto; /* Prefer avoiding breaks inside list items */
        }

        /* Handling headings to maintain section integrity */
        h1, h2, h3, h4, h5, h6 {
            page-break-after: auto; /* Avoids breaking immediately after headings */
            page-break-inside: auto; /* Avoids breaking inside headings */
            page-break-before: auto; /* Allows automatic page breaks before headings */
        }

        .main-section {
            text-align: left;
        }

        /* #isPasted {
            table-layout: fixed;
            width: 650px!important;
        } */

        #isPasted > tbody > tr > td > p{
            text-align: justify;
            text-justify: inter-word;
        }


        /* Main Table Styling */
#isPasted {
    width: 650px !important;
    border-collapse: collapse;
    table-layout: auto; /* Adjusts column width dynamically */
}

/* First column adjusts to its content */
#isPasted td:first-child,
#isPasted th:first-child {
    white-space: nowrap; /* Prevent wrapping */
    width: 1%; /* Shrink to fit content */
    vertical-align: top;
}

/* Second column takes remaining space */
#isPasted td:last-child,
#isPasted th:last-child {
    width: auto; /* Take remaining space */
    vertical-align: top;

}

/* Common Table Cell Styling */
#isPasted th,
#isPasted td {
    border: 1px solid #000;
    padding: 8px;
    text-align: left;
    max-width: 500px;
  word-wrap: break-word;
  overflow-wrap: break-word;
}

/* Paragraph Styling Inside Table Cells */
#isPasted td > p {
    text-align: justify;
    text-justify: inter-word;
    margin: 0;
    max-width: 500px;
  word-wrap: break-word;
  overflow-wrap: break-word;
}

#isPasted img {
    max-width: 500px !important; /* Ensure image doesn't overflow the cell */
    height: 100%; /* Maintain image aspect ratio */
    display: block; /* Remove extra space below the image */
    margin: 5px auto; /* Add spacing and center align */
}

/* If you want larger images */
#isPasted td img {
    max-width: 400px !important; /* Adjust this to your preferred maximum width */
    height: 300px;
    margin: 5px auto;
}


    </style>

</head>

<body>

    <header class="">
        <table class="border" style="height: 147px;">
            <tbody>
                <tr>
                    <td class="logo w-20">
                        <img src="http://environmentallab.doculife.co.in/user/images/logo.png" alt="..." style="margin-top: 0.5rem; margin-bottom: 1rem;">
                    </td>
                    <td class="title w-60"
                    style="height: 150px; padding: 0px;  margin: 0px; border-left: 1px solid rgb(104, 104, 104); border-right: 1px solid rgb(104, 104, 104);">
                        <p
                        style="font-size: 1rem; margin-top: -0.1rem; border-bottom: 1px solid rgb(104, 104, 104);">Environmental Laboratory</p>
                        <br>
                        <p style="font-size: 1rem; margin-top: -2rem; margin-bottom: 0px;">

                            {{ $data->document_name }}
                        </p>
                    </td>
                    <td class="logo w-20 h-20">
                        <img src="http://environmentallab.doculife.co.in/user/images/logo1.png" alt="..." style="max-height: 60px;">
                    </td>
                </tr>
            </tbody>
        </table>
        <table class="border border-top-none p-10">
            <tbody>
                <tr>
                    <td class="doc-num w-100">
                        @php
                        $temp = DB::table('document_types')
                            ->where('name', $data->document_type_name)
                            ->value('typecode');
                       @endphp
                        @if($data->revised === 'Yes')

                        {{ Helpers::getDivisionName($data->division_id) }}
                        /@if($data->document_type_name){{  $temp }} /@endif{{ $data->year }}
                        /000{{ $data->record }}/R{{$data->major}}.{{$data->minor}}

                        @else
                        {{ Helpers::getDivisionName($data->division_id) }}
                        /@if($data->document_type_name){{  $temp }} /@endif{{ $data->year }}
                        /000{{ $data->record }}/R{{$data->major}}.{{$data->minor}}
                    @endif
                </tr>
            </tbody>
        </table>
    </header>


    <footer class="footer">
        <table class="border p-10">
            <tbody>
                <tr>
                    <td class="text-left w-36">
                        @php
                            $temp = DB::table('document_types')
                                ->where('name', $data->document_type_name)
                                ->value('typecode');
                        @endphp
                        @if($data->revised === 'Yes')
                            {{ Helpers::getDivisionName($data->division_id) }}
                            /@if($data->document_type_name){{  $temp }} /@endif{{ $data->year }}
                            /000{{ $data->record }}/R{{$data->major}}.{{$data->minor}}

                            @else
                            {{ Helpers::getDivisionName($data->division_id) }}
                            /@if($data->document_type_name){{  $temp }} /@endif{{ $data->year }}
                            /000{{ $data->record }}/R{{$data->major}}.{{$data->minor}}
                        @endif

                    <td class="w-36">Printed On : {{ $time }}</td>
                    <td class="text-right w-20"></td>
                </tr>
            </tbody>
        </table>
    </footer>

    <section  class="main-section" id="pdf-page">
        <section style="page-break-after: never;">
            <div class="other-container">
                <table>
                    <thead>
                        <tr>
                            <th class="w-5" style="font-weight: bold">1.</th>
                            <th class="text-left" style="font-weight: bold">Purpose</th>
                        </tr>
                    </thead>
                </table>
                <div class="scope-block">
                    <div class="w-100">
                        <div class="w-100" style="display:inline-block; margin-left: 2.5rem;">
                            <div class="w-100">
                                <div class="text-justify" style="height:auto; overflow-x:hidden; width:650px; ">
                                    {{-- {!! $data->document_content ? nl2br($data->document_content->purpose) : '' !!} --}}
                                    {!! $data->document_content && $data->document_content->purpose
                                        ? nl2br(e($data->document_content->purpose))
                                        : 'Not Applicable' !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="other-container">
                <table>
                    <thead>
                        <tr>
                            <th class="w-5" style="font-weight: bold">2.</th>
                            <th class="text-left" style="font-weight: bold">Scope</th>
                        </tr>
                    </thead>
                </table>
                <div class="scope-block">
                    <div class="w-100">
                        <div class="w-100" style="display:inline-block;">
                            <div class="w-100">
                                <div class="text-justify" style="height:auto; overflow-x:hidden; width:650px; margin-left: 2.5rem;">
                                    {{-- {!! $data->document_content ? nl2br($data->document_content->scope) : '' !!} --}}
                                    {!! $data->document_content && $data->document_content->scope
                                        ? nl2br(e($data->document_content->scope))
                                        : 'Not Applicable' !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <table class="mb-20">
                <tbody>
                    <tr>
                        <th class="w-5 vertical-baseline" style="font-weight: bold">3.</th>
                        <th class="w-95 text-left">
                            <div class="" style="font-weight: bold">Responsibility</div>
                        </th>
                    </tr>
                </tbody>
            </table>

            <div class="procedure-block">
                <div class="w-100">
                    <div class="w-100" style="display:inline-block;">
                        <div class="w-100">
                            <div style="height:auto; overflow-x:hidden; width:650px; margin-left: 2.5rem;">
                                @php
                                    $i = 1;
                                @endphp
                                @if ($data->document_content && !empty($data->document_content->responsibility) && is_array(unserialize($data->document_content->materials_and_equipments)))
                                    @foreach (unserialize($data->document_content->responsibility) as $key => $res)
                                        @php
                                            $isSub = str_contains($key, 'sub');
                                        @endphp
                                        @if (!empty($res))
                                            <div style="position: relative;">
                                                <span style="position: absolute; left: -2.5rem; top: 0;">3.{{ $isSub ? $i - 1 . '.' . $sub_index : $i }}</span> {!! nl2br($res) !!} <br>
                                            </div>
                                        @else
                                            <div style="position: relative;">
                                                <span style="position: absolute; left: -2.5rem; top: 0;">3.{{ $isSub ? $i - 1 . '.' . $sub_index : $i }}</span> Not Applicable <br>
                                            </div>
                                        @endif
                                        @php
                                            if (!$isSub) {
                                                $i++;
                                                $sub_index = 1;
                                            } else {
                                                $sub_index++;
                                            }
                                        @endphp
                                    @endforeach
                                @endif


                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <table class="mb-20">
                <tbody>
                    <tr>
                        <th class="w-5 vertical-baseline" style="font-weight: bold">4.</th>
                        <th class="w-95 text-left">
                            <div class="" style="font-weight: bold">Abbreviation</div>
                        </th>
                    </tr>
                </tbody>
            </table>

            <div class="procedure-block">
                <div class="w-100">
                    <div class="w-100" style="display:inline-block;">
                        <div class="w-100">
                            <div style="height:auto; overflow-x:hidden; width:650px; margin-left: 2.5rem;">
                                @php
                                    $i = 1;
                                @endphp
                                @if ($data->document_content && !empty($data->document_content->abbreviation) && is_array(unserialize($data->document_content->abbreviation)))
                                    @foreach (unserialize($data->document_content->abbreviation) as $key => $res)
                                        @php
                                            $isSub = str_contains($key, 'sub');
                                        @endphp
                                        @if (!empty($res))
                                            <div style="position: relative;">
                                                <span style="position: absolute; left: -2.5rem; top: 0;">4.{{ $isSub ? $i - 1 . '.' . $sub_index : $i }}</span> {!! nl2br($res) !!} <br>
                                            </div>
                                        @else
                                            <div style="position: relative;">
                                                <span style="position: absolute; left: -2.5rem; top: 0;">4.{{ $isSub ? $i - 1 . '.' . $sub_index : $i }}</span> Not Applicable <br>
                                            </div>
                                        @endif
                                        @php
                                            if (!$isSub) {
                                                $i++;
                                                $sub_index = 1;
                                            } else {
                                                $sub_index++;
                                            }
                                        @endphp
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- DEFINITIONS START --}}
                <table class="mb-20">
                    <tbody>
                        <tr>
                            <th class="w-5 vertical-baseline" style="font-weight: bold">5.</th>
                            <th class="w-95 text-left">
                                <div class="bold" style="font-weight: bold">Definitions</div>
                            </th>
                        </tr>
                    </tbody>
                </table>

                <div class="procedure-block">
                    <div class="w-100">
                        <div class="w-100" style="display:inline-block;">
                            <div class="w-100">
                                <div style="height:auto; overflow-x:hidden; width:650px; margin-left: 2.5rem;">
                                    @php
                                        $i = 1;
                                        $definitions = $data->document_content ? unserialize($data->document_content->defination) : [];
                                    @endphp
                                    @if ($data->document_content && !empty($data->document_content->defination) && is_array($definitions))
                                        @foreach ($definitions as $key => $definition)
                                            @php
                                                $isSub = str_contains($key, 'sub');
                                            @endphp
                                            @if (!empty($res))
                                                <div style="position: relative;">
                                                    <span style="position: absolute; left: -2.5rem; top: 0;">5.{{ $isSub ? $i - 1 . '.' . $sub_index : $i }}</span> {!! nl2br($definition) !!} <br>
                                                </div>
                                            @else
                                                <div style="position: relative;">
                                                    <span style="position: absolute; left: -2.5rem; top: 0;">5.{{ $isSub ? $i - 1 . '.' . $sub_index : $i }}</span> Not Applicable <br>
                                                </div>
                                            @endif
                                            @php
                                                if (!$isSub) {
                                                    $i++;
                                                    $sub_index = 1;
                                                } else {
                                                    $sub_index++;
                                                }
                                            @endphp
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            {{-- DEFINITIONS END --}}

            {{-- MATERIALS AND EQUIPMENTS START --}}
                <table class="mb-20">
                    <tbody>
                        <tr>
                            <th class="w-5 vertical-baseline" style="font-weight: bold">6.</th>
                            <th class="w-95 text-left">
                                <div class="bold" style="font-weight: bold">Materials & Equipments</div>
                            </th>
                        </tr>
                        <tr>
                    </tbody>
                </table>
                <div class="procedure-block">
                    <div class="w-100">
                        <div class="w-100" style="display:inline-block;">
                            <div class="w-100">
                                <div style="height:auto; overflow-x:hidden; width:650px; margin-left: 3rem;">
                                    @php $i = 1; $sub_index = 1; @endphp
                                    @if ($data->document_content && is_array(unserialize($data->document_content->materials_and_equipments)))
                                        @foreach (unserialize($data->document_content->materials_and_equipments) as $key => $res)
                                            @php
                                                $isSub = str_contains($key, 'sub');
                                            @endphp
                                            @if (!empty($res))
                                                <div style="position: relative;">
                                                    <span style="position: absolute; left: -2.5rem; top: 0;">6.{{ $isSub ? $i - 1 . '.' . $sub_index : $i }}</span> {!! nl2br($res) !!} <br>
                                                </div>
                                            @else
                                                <div style="position: relative;">
                                                    <span style="position: absolute; left: -2.5rem; top: 0;">6.{{ $isSub ? $i - 1 . '.' . $sub_index : $i }}</span> Not Applicable <br>
                                                </div>
                                            @endif
                                            @php
                                                if (!$isSub) {
                                                    $i++;
                                                    $sub_index = 1;
                                                } else {
                                                    $sub_index++;
                                                }
                                            @endphp
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            {{-- MATERIALS AND EQUIPMENTS END --}}

            {{-- SAFETY START --}}
            <div class="other-container ">
                <table>
                    <thead>
                        <tr>
                            <th class="w-5" style="font-weight: bold">7.</th>
                            <th class="text-left" style="font-weight: bold">Safety & Precautions</th>
                        </tr>
                    </thead>
                </table>
                <div class="procedure-block">
                    <div class="w-100">
                        <div class="w-100" style="display:inline-block;">
                            <div class="w-100">
                                <div style="height:auto; overflow-x:hidden; width:650px; margin-left: 2.5rem;" class="symbol-support">
                                    {{-- @if ($data->document_content)
                                        {!! strip_tags(str_replace("−", "-", html_entity_decode($data->document_content->safety_precautions)), '<br><table><th><td><tbody><tr><p><img><a><img><span><h1><h2><h3><h4><h5><h6><div><strong></strong><ol><li>') !!}
                                    @endif --}}
                                    @if ($data->document_content && !empty($data->document_content->safety_precautions))
                                        {!! strip_tags(str_replace("−", "-", html_entity_decode($data->document_content->safety_precautions)),
                                            '<br><table><th><td><tbody><tr><p><img><a><span><h1><h2><h3><h4><h5><h6><div><strong><ol><li>') !!}
                                    @else
                                        <div style="position: relative;">
                                            <span style="position: absolute; left: -2.5rem; top: 0;"></span> Not Applicable <br>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- SAFETY END --}}

            {{-- PROCEDURE START --}}
                <div class="other-container ">
                    <table>
                        <thead>
                            <tr>
                                <th class="w-5" style="font-weight: bold">8.</th>
                                <th class="text-left" style="font-weight: bold">Procedure</th>
                            </tr>
                        </thead>
                    </table>
                    <div class="procedure-block">
                        <div class="w-100">
                          <div class="w-100" style="display:inline-block;">


                            <div class="w-100">
                                <div style="height:auto; overflow-x:auto; width:650px; margin-left: 2.5rem;" class="symbol-support">
                                    @if ($data->document_content && !empty($data->document_content->procedure))
                                        {!!
                                            str_replace(
                                                ['<table', '<th', '<td', '<tr'],
                                                [
                                                    '<table style="width: 100%; border-collapse: collapse; border: 1px solid #ccc;"',
                                                    '<th style="border: 1px solid #ccc; padding: 8px; background-color: #f9f9f9;"',
                                                    '<td style="border: 1px solid #ccc; padding: 8px;"',
                                                    '<tr style="border: 1px solid #ccc;"'
                                                ],
                                                strip_tags(
                                                    str_replace("−", "-", html_entity_decode($data->document_content->procedure)),
                                                    '<br><table><th><td><tbody><tr><p><img><a><span><h1><h2><h3><h4><h5><h6><div><strong><ol><li>'
                                                )
                                            )
                                        !!}
                                    @else
                                        <div style="position: relative;">
                                            <span style="position: absolute; left: -2.5rem; top: 0;"></span> Not Applicable <br>
                                        </div>
                                    @endif
                                </div>
                            </div>



                </div>
            </div>



            {{-- PROCEDURE END --}}

            {{-- REPORTING START --}}
                <table class="mb-20 ">
                    <tbody>
                        <tr>
                            <th class="w-5 vertical-baseline" style="font-weight: bold">9.</th>
                            <th class="w-95 text-left">
                                <div class="bold" style="font-weight: bold">Reporting</div>
                            </th>
                        </tr>
                    </tbody>
                </table>
                <div class="procedure-block">
                    <div class="w-100">
                        <div class="w-100" style="display:inline-block;">
                            <div class="w-100">
                                <div style="height:auto; overflow-x:hidden; width:650px; margin-left: 2.5rem;">
                                    @php $i = 1; @endphp
                                    @if ($data->document_content && !empty($data->document_content->reporting) && is_array(unserialize($data->document_content->reporting)))
                                        @foreach (unserialize($data->document_content->reporting) as $key => $res)
                                            @php
                                                $isSub = str_contains($key, 'sub');
                                            @endphp
                                            @if (!empty($res))
                                                <div style="position: relative;">
                                                    <span style="position: absolute; left: -2.5rem; top: 0;">9.{{ $isSub ? $i - 1 . '.' . $sub_index : $i }}</span> {!! nl2br($res) !!} <br>
                                                </div>
                                            @else
                                                <div style="position: relative;">
                                                    <span style="position: absolute; left: -2.5rem; top: 0;">9.{{ $isSub ? $i - 1 . '.' . $sub_index : $i }}</span> Not Applicable <br>
                                                </div>
                                            @endif
                                            @php
                                                if (!$isSub) {
                                                    $i++;
                                                    $sub_index = 1;
                                                } else {
                                                    $sub_index++;
                                                }
                                            @endphp
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            {{-- REPORTING END --}}

            {{-- REFERENCES START --}}
                <table class="mb-20">
                    <tbody>
                        <tr>
                            <th class="w-5 vertical-baseline" style="font-weight: bold">10.</th>
                            <th class="w-95 text-left">
                                <div class="bold" style="font-weight: bold"> References</div>
                            </th>
                        </tr>
                    </tbody>
                </table>
                <div class="procedure-block">
                    <div class="w-100">
                        <div class="w-100" style="display:inline-block;">
                            <div class="w-100">
                                <div style="height:auto; overflow-x:hidden; width:650px; margin-left: 2.5rem;">
                                    @php $i = 1; @endphp
                                    @if ($data->document_content && !empty($data->document_content->references) && is_array(unserialize($data->document_content->references)))
                                        @foreach (unserialize($data->document_content->references) as $key => $res)
                                            @php
                                                $isSub = str_contains($key, 'sub');
                                            @endphp
                                            @if (!empty($res))
                                                <div style="position: relative;">
                                                    <span style="position: absolute; left: -2.5rem; top: 0;">10.{{ $isSub ? $i - 1 . '.' . $sub_index : $i }}</span> {!! nl2br($res) !!} <br>
                                                </div>
                                            @else
                                                <div style="position: relative;">
                                                    <span style="position: absolute; left: -2.5rem; top: 0;">10.{{ $isSub ? $i - 1 . '.' . $sub_index : $i }}</span> Not Applicable <br>
                                                </div>
                                            @endif
                                            @php
                                                if (!$isSub) {
                                                    $i++;
                                                    $sub_index = 1;
                                                } else {
                                                    $sub_index++;
                                                }
                                            @endphp
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            {{-- REFERENCES END --}}

            {{-- ANNEXSURE START --}}
            <table class="mb-20">
                <tbody>
                    <tr>
                        <th class="w-5 vertical-baseline" style="font-weight: bold">11.</th>
                        <th class="w-95 text-left">
                            <div class="bold" style="font-weight: bold">Annexure</div>
                        </th>
                    </tr>
                </tbody>
            </table>

            <div class="procedure-block">
                <div class="w-100">
                    <div class="w-100" style="display:inline-block;">
                        <div class="w-100">
                            <div style="height:auto; overflow-x:hidden; width:650px; margin-left: 2.5rem;">
                                @php $i = 1; @endphp
                                @if ($data->document_content && !empty($data->document_content->ann) && is_array(unserialize($data->document_content->ann)))
                                    @foreach (unserialize($data->document_content->ann) as $key => $res)
                                        @php
                                            $isSub = str_contains($key, 'sub');
                                        @endphp
                                        @if (!empty($res))
                                            <div style="position: relative;">
                                                <span style="position: absolute; left: -2.5rem; top: 0;">11.{{ $isSub ? $i - 1 . '.' . $sub_index : $i }}</span> {!! nl2br($res) !!} <br>
                                            </div>
                                        @else
                                            <div style="position: relative;">
                                                <span style="position: absolute; left: -2.5rem; top: 0;">11.{{ $isSub ? $i - 1 . '.' . $sub_index : $i }}</span> Not Applicable <br>
                                            </div>
                                        @endif
                                        @php
                                            if (!$isSub) {
                                                $i++;
                                                $sub_index = 1;
                                            } else {
                                                $sub_index++;
                                            }
                                        @endphp
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- ANNEXSURE END --}}

            @php
                $i = 1;
            @endphp
                {{-- @if ($data->document_content && !empty($data->document_content->annexuredata))
                    @foreach (unserialize($data->document_content->annexuredata) as $res)
                        @if (!empty($res))
                            <div class="annexure-block">
                                <div class="w-100">
                                    <div class="w-100" style="display:inline-block;">
                                        <div class="w-100">
                                            <div style="height:auto; overflow-x:hidden; width:650px; ">
                                                {!! strip_tags($res, '<br><table><th><td><tbody><tr><p><img><a><img><span><h1><h2><h3><h4><h5><h6><div><strong></strong><ol><li>') !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                @endif --}}
            </section>

            <section class="doc-control" style="page-break-after: never;">
                <div class="head">
                    <div>
                        Document Control Information
                    </div>
                </div>
            <div class="body">
                <div class="block mb-40">
                    <div class="block-head">
                        General Information
                    </div>
                    <div class="block-content">
                        <table>
                            <tbody>
                                <tr>
                                    <th class="w-30 text-left vertical-baseline">Document Number</th>
                                    <td class="w-70 text-left">
                                        @php
                                        $temp = DB::table('document_types')
                                            ->where('name', $data->document_type_name)
                                            ->value('typecode');
                                       @endphp
                                        @if($data->revised === 'Yes')

                                        {{ Helpers::getDivisionName($data->division_id) }}
                                        /@if($data->document_type_name){{  $temp }} /@endif{{ $data->year }}
                                        /000{{ $data->record }}/R{{$data->major}}.{{$data->minor}}

                                        @else
                                        {{ Helpers::getDivisionName($data->division_id) }}
                                        /@if($data->document_type_name){{  $temp }} /@endif{{ $data->year }}
                                        /000{{ $data->record }}/R{{$data->major}}.{{$data->minor}}

                                    @endif
                                </td>
                                </tr>
                                <tr>
                                    <th class="w-30 text-left vertical-baseline">Title</th>
                                    <td class="w-70 text-left">{{ $data->document_name }}</td>
                                </tr>
                                <tr>
                                    <th class="w-30 text-left vertical-baseline">
                                        Short Description
                                    </th>
                                    <td class="w-70 text-left">
                                        {{ $data->short_description }}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="w-30 text-left vertical-baseline">Description</th>
                                    <td class="w-70 text-left">
                                        {{ $data->description }}
                                    </td>
                                </tr>
                                @php
                                    $last = DB::table('document_histories')
                                        ->where('document_id', $data->id)
                                        ->latest()
                                        ->first();
                                @endphp

                                <tr>
                                    <th class="w-30 text-left vertical-baseline">Reference Record</th>
                                    <td class="w-70 text-left">
                                        @php
                                            // Convert the string of IDs (e.g., "1,2,3") into an array
                                            $reference_records = explode(',', $data->reference_record); // [1, 2, 3]
                                            $totalRecords = count($reference_records); // Total number of records
                                            $currentIndex = 0;
                                        @endphp

                                        @foreach ($reference_records as $reference_record)
                                            @php
                                            $currentIndex++;
                                                // Fetch the document details using the current reference ID
                                                $referDoc = DB::table('documents')->where('id', $reference_record)->first(); // Use first() instead of get()
                                                if (!$referDoc) {
                                                    // Skip if no document is found for the ID
                                                    continue;
                                                }
                                                $year = Carbon\Carbon::parse($referDoc->created_at)->format('Y');
                                                // Fetch the type code for the document
                                                $temp = DB::table('document_types')
                                                    ->where('id', $referDoc->document_type_id)
                                                    ->value('typecode');
                                            @endphp
                                            {{ Helpers::getDivisionName($referDoc->division_id) }}
                                            /@if($referDoc->document_type_id){{  $temp }} /@endif{{ $year }}
                                            /000{{ $reference_record }}/R{{$referDoc->major}}.{{$referDoc->minor}}/{{$referDoc->document_name}}
                                            @if ($currentIndex < $totalRecords)
                                                , <!-- Add a comma if it's not the last record -->
                                            @endif
                                        @endforeach

                                    </td>
                                </tr>

                                <tr>
                                    <th class="w-30 text-left vertical-baseline">Last Changed</th>
                                    <td class="w-70 text-left">
                                        @if ($last)
                                            {{ $last->created_at }}
                                        @else
                                            {{ $data->created_at }}
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th class="w-30 text-left vertical-baseline">Changed By</th>
                                    <td class="w-70 text-left">
                                        @if ($last)
                                            {{ $last->user_name }}
                                        @else
                                            {{ $data->originator }}
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                @php
                $signatureOriginatorData = DB::table('stage_manages')
                                                    ->where('document_id', $data->id)
                                                    ->where(function ($query) {
                                                        $query->where('stage', 'In-Review')
                                                            ->orWhere('stage', 'In-Approval');
                                                    })
                                                    ->latest()
                                                    ->first();

                                                    $signatureReviewerData = DB::table('stage_manages')
                                                ->where('document_id', $data->id)
                                                ->where('stage', 'Review-Submit')
                                                ->get();
// dd($signatureReviewerData);
                                                    $signatureApprovalData = DB::table('stage_manages')
                                                ->where('document_id', $data->id)
                                                ->where('stage', 'Approval-Submit')
                                                ->latest()
                                                ->first();
                @endphp
                <div class="block mb-40">
                    <div class="block-head">
                        Originator
                    </div>
                    <div class="block-content">
                        <table class="table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-left w-25">Originator</th>
                                    <th class="text-left w-25">Department</th>
                                    <th class="text-left w-25">Status</th>
                                    <th class="text-left w-25">E-Signature</th>
                                    <th class="text-left w-25">Comments</th>

                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-left w-25">{{ $data->originator }}</td>
                                    <td class="text-left w-25">{{ $document->originator && $document->originator->department ? $document->originator->department->name : '' }}</td>
                                    <td class="text-left w-25">Initiation Completed</td>
                                    <td class="text-left w-25">{{ $data->originator_email }}</td>
                                    <td class="text-left w-25">{{ !$signatureOriginatorData || $signatureOriginatorData->comment == null ? " " : $signatureOriginatorData->comment }}</td>

                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="block mb-40">
                    <div class="block-head">
                        Reviews
                    </div>
                    <div class="block-content">
                        <table class="table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-left w-25">Reviewer</th>
                                    <th class="text-left w-25">Department</th>
                                    <th class="text-left w-25">Status</th>
                                    <th class="text-left w-25">E-Signature</th>
                                    <th class="text-left w-25">Comments</th>

                                </tr>
                            </thead>
                            <tbody>
                                @if ($data->reviewers)
                                    @php
                                        $reviewer = explode(',', $data->reviewers);
                                        $i = 0;
                                    @endphp
                                    @for ($i = 0; $i < count($reviewer); $i++)
                                        @php
                                            $user = DB::table('users')
                                                ->where('id', $reviewer[$i])
                                                ->first();
                                            $dept = DB::table('departments')
                                                ->where('id', $user->departmentid)
                                                ->value('name');
                                            $date = DB::table('stage_manages')
                                                ->where('document_id', $data->id)
                                                ->where('user_id', $reviewer[$i])
                                                ->where('stage', 'Review-Submit')
                                                ->where('deleted_at', null)
                                                ->latest()
                                                ->first();

                                                $comment = DB::table('stage_manages')
                                                ->where('document_id', $data->id)
                                                ->whereIn('stage', ['Review-Submit', 'Reviewed'])
                                                ->where('user_id', $reviewer[$i])
                                                ->latest()
                                                ->first();


                                            $reject = DB::table('stage_manages')
                                                ->where('document_id', $data->id)
                                                ->where('user_id', $reviewer[$i])
                                                ->where('stage', 'Cancel-by-Reviewer')
                                                ->where('deleted_at', null)
                                                ->latest()
                                                ->first();

                                        @endphp
                                        <tr>
                                            <td class="text-left w-25">{{ $user->name }}</td>
                                            <td class="text-left w-25">{{ $dept }}</td>
                                            @if ($date)
                                                <td class="text-left w-25">Review Completed</td>
                                            @elseif(!empty($reject))
                                                <td class="text-left w-25">Review Rejected </td>
                                            @else
                                                <td class="text-left w-25">Review Pending</td>
                                            @endif

                                            <td class="text-left w-25">{{ $comment ? $user->email : '' }}</td>
                                            <td class="text-left w-25">
                                            @if($comment)
                                                {{ $comment->comment }}
                                            @endif</td>
                                        </tr>
                                    @endfor
                                @endif
                                @if ($data->reviewers_group)
                                    @php
                                        $group = explode(',', $data->reviewers_group);
                                        $i = 0;

                                    @endphp
                                    @for ($i = 0; $i < count($group); $i++)
                                        @php

                                            $users_id = DB::table('group_permissions')
                                                ->where('id', $group[$i])
                                                ->value('user_ids');
                                            $reviewer = explode(',', $users_id);
                                            $i = 0;
                                        @endphp
                                        @if ($users_id)
                                            @for ($i = 0; $i < count($reviewer); $i++)
                                                @php
                                                    $user = DB::table('users')
                                                        ->where('id', $reviewer[$i])
                                                        ->first();
                                                    $dept = DB::table('departments')
                                                        ->where('id', $user->departmentid)
                                                        ->value('name');
                                                    $date = DB::table('stage_manages')
                                                        ->where('document_id', $data->id)
                                                        ->where('user_id', $reviewer[$i])
                                                        ->where('stage', 'Review-Submit')
                                                        ->where('deleted_at', null)
                                                        ->latest()
                                                        ->first();

                                                    $reject = DB::table('stage_manages')
                                                        ->where('document_id', $data->id)
                                                        ->where('user_id', $reviewer[$i])
                                                        ->where('stage', 'Cancel-by-Reviewer')
                                                        ->latest()
                                                        ->first();

                                                @endphp
                                                <tr>
                                                    <td class="text-left w-25">{{ $user->name }}</td>
                                                    <td class="text-left w-25">{{ $dept }}</td>
                                                    @if ($date)
                                                        <td class="text-left w-25">Review Completed</td>
                                                    @elseif(!empty($reject))
                                                        <td class="text-left w-25">Review Rejected </td>
                                                    @else
                                                        <td class="text-left w-25">Review Pending</td>
                                                    @endif

                                                    <td class="text-left w-25">{{ $user->email }}</td>
                                                </tr>
                                            @endfor
                                        @endif
                                    @endfor


                                @endif

                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="block mb-40">
                    <div class="block-head">
                        Approvals
                    </div>
                    <div class="block-content">
                        <table class="table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-left w-25">Approver</th>
                                    <th class="text-left w-25">Department</th>
                                    <th class="text-left w-25">Status</th>
                                    <th class="text-left w-25">E-Signature</th>
                                    <th class="text-left w-25">Comments</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($data->approvers)
                                    @php
                                        $reviewer = explode(',', $data->approvers);
                                        $i = 0;
                                    @endphp
                                    @for ($i = 0; $i < count($reviewer); $i++)
                                        @php
                                            $user = DB::table('users')
                                                ->where('id', $reviewer[$i])
                                                ->first();
                                            $dept = DB::table('departments')
                                                ->where('id', $user->departmentid)
                                                ->value('name');
                                            $date = DB::table('stage_manages')
                                                ->where('document_id', $data->id)
                                                ->where('user_id', $reviewer[$i])
                                                ->where('stage', 'Approval-Submit')
                                                ->where('deleted_at', null)
                                                ->latest()
                                                ->first();
                                                $comment = DB::table('stage_manages')
                                                    ->where('document_id', $data->id)
                                                    ->whereIn('stage', ['Approval-Submit', 'Approved'])
                                                    ->where('user_id', $reviewer[$i])
                                                    ->latest()
                                                    ->first();
                                            $reject = DB::table('stage_manages')
                                                ->where('document_id', $data->id)
                                                ->where('user_id', $reviewer[$i])
                                                ->where('stage', 'Cancel-by-Approver')
                                                ->where('deleted_at', null)
                                                ->latest()
                                                ->first();

                                        @endphp
                                        <tr>
                                            <td class="text-left w-25">{{ $user->name }}</td>
                                            <td class="text-left w-25">{{ $dept }}</td>
                                            @if ($date)
                                                <td class="text-left w-25">Approval Completed</td>
                                            @elseif(!empty($reject))
                                                <td>Approval Rejected</td>
                                            @else
                                                <td class="text-left w-25">Approval Pending</td>
                                            @endif

                                            <td class="text-left w-25">{{ $comment ? $user->email : '' }}</td>
                                            <td class="text-left w-25">
                                                @if($comment)
                                                {{ $comment->comment }}
                                            @endif
                                            </td>
                                        </tr>
                                    @endfor
                                @endif
                                @if ($data->approver_group)
                                    @php
                                        $group = explode(',', $data->approver_group);
                                        $i = 0;

                                    @endphp
                                    @for ($i = 0; $i < count($group); $i++)
                                        @php

                                            $users_id = DB::table('group_permissions')
                                                ->where('id', $group[$i])
                                                ->value('user_ids');
                                            $reviewer = explode(',', $users_id);
                                            $i = 0;
                                        @endphp
                                        @if ($users_id)
                                            @for ($i = 0; $i < count($reviewer); $i++)
                                                @php
                                                    $user = DB::table('users')
                                                        ->where('id', $reviewer[$i])
                                                        ->first();
                                                    $dept = DB::table('departments')
                                                        ->where('id', $user->departmentid)
                                                        ->value('name');
                                                    $date = DB::table('stage_manages')
                                                        ->where('document_id', $data->id)
                                                        ->where('user_id', $reviewer[$i])
                                                        ->where('stage', 'Approval-Submit')
                                                        ->latest()
                                                        ->first();
                                                    $reject = DB::table('stage_manages')
                                                        ->where('document_id', $data->id)
                                                        ->where('user_id', $reviewer[$i])
                                                        ->where('stage', 'Cancel-by-Approver')
                                                        ->latest()
                                                        ->first();

                                                @endphp
                                                <tr>
                                                    <td class="text-left w-25">{{ $user->name }}</td>
                                                    <td class="text-left w-25">{{ $dept }}</td>
                                                    @if ($date)
                                                        <td class="text-left w-25">Approval Completed</td>
                                                    @elseif(!empty($reject))
                                                        <td class="text-left w-25">Approval Rejected </td>
                                                    @else
                                                        <td class="text-left w-25">Approval Pending</td>
                                                    @endif

                                                    <td class="text-left w-25">{{ $user->email }}</td>
                                                </tr>
                                            @endfor
                                        @endif
                                    @endfor


                                @endif

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>

    </section>



    <script type="text/php">
        if ( isset($pdf) ) {
            $pdf->page_script('
                if ($PAGE_COUNT > 1) {
                    $font = $fontMetrics->get_font("Arial, Helvetica, sans-serif", "normal");
                    $size = 12;
                    $pageText = "Page " . $PAGE_NUM . " of " . $PAGE_COUNT;
                    $y = 805;
                    $x = 490;
                    $pdf->text($x, $y, $pageText, $font, $size);
                }
            ');
        }
    </script>
</body>

</html>
