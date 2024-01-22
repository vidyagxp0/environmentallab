@extends('frontend.rcms.layout.main_rcms')
@section('rcms_container')
    <style>
        header {
            display: none;
        }
    </style>
    {{-- ======================================
                    DASHBOARD
    ======================================= --}}
    <div id="division-config-modal">
        <div class="division-container">
            <div class="content-container">
                <form action="{{ route('formDivision') }}" method="post">
                    @csrf
                    <div class="division-tabs">
                        <div class="left-block">
                            <div class="head">
                                Site/Location
                            </div>
                            <div class="tab">
                                @php
                                    $division = DB::table('q_m_s_divisions')->get();
                                @endphp
                                @foreach ($division as $temp)
                                    <div class="divisionlinks">
                                        <input type="radio" value="{{ $temp->id }}" name="division_id"
                                            onclick="openDivision(event, {{ $temp->id }})" required>
                                        <div>{{ $temp->name }}</div>
                                    </div>
                                @endforeach

                            </div>
                        </div>
                        <div class="right-block">
                            <div class="head">
                                Process
                            </div>
                            @php
                                $process = DB::table('q_m_s_processes')->get();
                            @endphp
                            @foreach ($process as $temp)
                                <div id="{{ $temp->division_id }}" class="divisioncontent bg-light">
                                    @php
                                        $pro = DB::table('q_m_s_processes')
                                            ->where('division_id', $temp->division_id)
                                            ->get();
                                    @endphp
                                    @foreach ($pro as $test)
                                        <label for="process">
                                            <input type="hidden" name="process_id" value="{{ $test->id }}">
                                            <input type="submit" class="bg-light text-dark"
                                                style="width: 100%; height: 60%; background-color: #011627; color: #fdfffc; padding: 7px; border: 0px;"
                                                bgcolor="#011627" border="0" type="submit" for="process"
                                                value="{{ $test->process_name }}" name="process_name" required>
                                        </label>
                                        <br>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    </div>
                </form>



            </div>
        </div>
    </div>
@endsection
