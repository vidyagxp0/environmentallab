@extends('frontend.rcms.layout.main_rcms')
@section('rcms_container')
    @php
        $users = DB::table('users')->get();
    @endphp
    <style>
        header {
            display: none;
        }
    </style>
    {{-- ======================================
                CHANGE CONTROL VIEW
    ======================================= --}}
    <div id="rcms_form-head">
        <div class="container-fluid">
            <div class="inner-block">
                <div class="head">PR-0001</div>
                <div class="slogan">
                    <strong>Division / Project :</strong>
                    QMS-EMEA / Change Control
                </div>
            </div>

        </div>
    </div>

    <div id="change-control-view">
        <div class="container-fluid">

            <div class="inner-block state-block">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="main-head">Record Workflow </div>

                    <div class="d-flex" style="gap:20px;">
                        <button class="button_theme1" onclick="window.print();return false;"
                            class="new-doc-btn">Print</button>
                        {{--  <button class="button_theme1"> <a class="text-white" href="{{ url('send-notification', $data->id) }}"> Send Notification </a> </button>  --}}

                        <button class="button_theme1"> <a class="text-white"
                                href="{{ url('rcms/audit-trial', $data->parent_record) }}"> Audit Trail </a> </button>
                        @if ($data->stage == 1)
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#signature-modal">
                                Submit
                            </button>
                        @elseif($data->stage == 2)
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#signature-modal">
                                Effective
                            </button>
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#rejection-modal">
                                Not Effective
                            </button>
                        @elseif($data->stage == 3)
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#signature-modal">
                                Effective Approval Completed
                            </button>
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#cancel-modal">
                                More Information Required
                            </button>
                        @elseif($data->stage == 5)
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#rejection-modal">
                                Not Effective Approval Completed
                            </button>
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#cancel-modal">
                                More Information Required
                            </button>
                        @endif
                        <button> <a class="text-white" href="{{ url('rcms/qms-dashboard') }}"> Exit </a> </button>
                    </div>

                </div>
                <div class="status">
                    <div class="head">Current Status</div>
                    <div class="progress-bars">
                        @if ($data->stage >= 1)
                            <div class="active">Open State</div>
                        @else
                            <div class="">Open State</div>
                        @endif
                        @if ($data->stage >= 2)
                            <div class="active">Pending Effectiveness Check</div>
                        @else
                            <div class="">Pending Effectiveness Check</div>
                        @endif
                        {{-- @if ($data->stage >= 3)
                            <div class="active">QA Approval-Effective</div>
                        @else
                            <div class="">QA Approval-Effective</div>
                        @endif --}}
                        {{-- ---------------------------------- --}}
                        @if ($data->stage == 3 && $data->status == 'QA Approval-Effective')
                            <div class="active">QA Approval-Effective</div>
                            @elseif ($data->stage <= 4 && $data->status =='Closed – Effective')
                                <div class="active">QA Approval-Effective</div>

                        @endif


                        {{-- -------------------------------------------------- --}}
                        @if ($data->stage == 5 && $data->status == 'QA Approval-Not Effective')
                            <div class="active">QA Approval-Not Effective</div>
                            @elseif ($data->stage == 6)
                            <div class="active">QA Approval-Not Effective</div>

                        @endif


                        {{-- ----------------------------------------------- --}}
                        @if ($data->stage <= 4)
                            @if ($data->stage >= 4)
                                <div style="background-color: red">Closed – Effective
                                </div>
                            @else
                                <div class="">Closed – Effective
                                </div>
                            @endif
                        @else
                            @if ($data->stage >= 6)
                                <div style="background-color:rgb(163, 47, 47);">Closed-Not Effective</div>
                            @else
                                <div class="">Closed-Not Effective</div>
                            @endif
                        @endif






                    </div>
                </div>
            </div>


        </div>
        <form action="{{ route('effectiveness.update', $data->id) }}" method="POST">

            @csrf
            @method('PUT')
            <div class="form-field-head">
                <div class="division-bar">
                    <strong>Site Division/Project</strong> :
                    QMS-North America / Effectiveness-Check
                </div>
                <div class="button-bar">
                    {{--  <button type="button">Cancel</button>
                <button type="button">New</button>
                <button type="button">Copy</button>
                <button type="button">Child</button>
                <button type="button">Check Spelling</button>
                <button type="button">Change Project</button>  --}}
                </div>
            </div>
            {{-- ======================================
                            DATA FIELDS
            ======================================= --}}
            <div id="change-control-fields">
                <div class="container-fluid">

                    <!-- Tab links -->
                    <div class="cctab">
                        <button type="button" class="cctablinks active" onclick="openCity(event, 'CCForm1')">General
                            Information</button>
                        <button type="button" class="cctablinks" onclick="openCity(event, 'CCForm2')">Effectiveness check
                            Results</button>
                        <button type="button" class="cctablinks" onclick="openCity(event, 'CCForm3')">Reference
                            Info/Comments</button>
                        <button type="button" class="cctablinks" onclick="openCity(event, 'CCForm4')">Activity
                            History</button>
                    </div>

                    <!-- General Information -->
                    <div id="CCForm1" class="inner-block cctabcontent">
                        <div class="inner-block-content">
                            <div class="sub-head">
                                General Information
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="originator">Originator</label>
                                        <input disabled type="text" value="Amit Guru">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="originator">Date Opened</label>
                                        <input disabled type="text" value="{{ date('d-M-Y') }}" name="created_at">
                                        <input type="hidden" value="{{ date('Y-m-d') }}" name="created_at">
                                        {{--  <div class="static">{{ $data->created_at }}</div>  --}}
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="Short Description">Short Description</label>
                                        <textarea name="short_description">{{ $data->short_description }}</textarea>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="">Assign To</label>
                                        <select id="select-state" placeholder="Select..." name="assign_id">
                                            <option value="">Select a value</option>
                                            @foreach ($users as $value)
                                                <option {{ $data->assign_id == $value->id ? 'selected' : '' }}
                                                    value= "{{ $value->id }}">{{ $value->name }}</option>
                                            @endforeach
                                        </select>

                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Date Due"><b>Date Due</b></label>
                                        <input disabled type="text"
                                            value="{{ Helpers::getdateFormat($data->due_date) }}">

                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Quality Reviewer"><b>Quality Reviewer</b></label>
                                        <select id="select-state" placeholder="Select..." name="Quality_Reviewer">
                                            <option value="">Select a value</option>
                                            @foreach ($users as $value)
                                                <option {{ $data->assign_id == $value->id ? 'selected' : '' }}
                                                    value="{{ $value->id }}">{{ $value->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Original Date Due"><b>Original Date Due</b></label>
                                        <input disabled type="text"
                                            value="{{ Helpers::getdateFormat($data->due_date) }}">
                                    </div>
                                </div>
                            </div>
                            <div class="sub-head">
                                Effectiveness Planning Information
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="Effectiveness check Plan"><b>Effectiveness check Plan</b></label>
                                        <input type="text" name="Effectiveness_check_Plan"
                                            value="{{ $data->Effectiveness_check_Plan }}">
                                    </div>
                                </div>
                            </div>
                            <div class="button-block">
                                        @if ($data->stage != 0)
                                            <button type="submit" id="ChangesaveButton" class="saveButton"
                                                {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>Save</button>
                                        @endif
                                        <button type="button" id="ChangeNextButton" class="nextButton">Next</button>
                                        <button type="button"> <a href="{{ url('rcms/qms-dashboard') }}"
                                                class="text-white"> Exit </a> </button>
                                    </div>
                        </div>
                    </div>

                    <div id="CCForm2" class="inner-block cctabcontent">
                        <div class="inner-block-content">
                            <div class="row">
                                <!-- Effectiveness check Results -->
                                <div class="col-12 sub-head">
                                    Effectiveness Summary
                                </div>
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="Effectiveness Summary">Effectiveness Summary</label>
                                        <input type="text" name="effect_summary"  value="{{ $data->effect_summary }}">
                                    </div>
                                </div>
                                <div class="col-12 sub-head">
                                    Effectiveness Check Results
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Effectiveness Results">Effectiveness Results</label>
                                        <input type="text" name="Effectiveness_Results"
                                            value="{{ $data->Effectiveness_Results }}">
                                    </div>
                                </div>
                                <!-- <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Effectiveness check Attachments"><b>Effectiveness check
                                                Attachment</b></label>
                                        <input type="file" id="myfile" name="Effectiveness_check_Attachment"
                                            value="{{ $data->Effectiveness_check_Attachment }}">
                                    </div>
                                </div> -->
                                <div class="col-6">
                                            <div class="group-input">
                                                <label for="Effectiveness check Attachments">Effectiveness check Attachment</label>
                                                <div><small class="text-primary">Please Attach all relevant or supporting documents</small></div>
                                                <div class="file-attachment-field">
                                                    <div disabled class="file-attachment-list" id="Effectiveness_check_Attachment">
                                                        @if ($data->Effectiveness_check_Attachment)
                                                        @foreach(json_decode($data->Effectiveness_check_Attachment) as $file)
                                                        <h6 type="button" class="file-container text-dark" style="background-color: rgb(243, 242, 240);">
                                                            <b>{{ $file }}</b>
                                                            <a href="{{ asset('upload/' . $file) }}" target="_blank"><i class="fa fa-eye text-primary" style="font-size:20px; margin-right:-10px;"></i></a>
                                                            <a  type="button" class="remove-file" data-file-name="{{ $file }}"><i class="fa-solid fa-circle-xmark" style="color:red; font-size:20px;"></i></a>
                                                        </h6>
                                                   @endforeach
                                                        @endif
                                                    </div>
                                                    <div class="add-btn">
                                                        <div>Add</div>
                                                        <input {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }} value="{{ $data->Effectiveness_check_Attachment }}" type="file" id="myfile" name="Effectiveness_check_Attachment[]"
                                                            oninput="addMultipleFiles(this, 'Effectiveness_check_Attachment')"
                                                            multiple>
                                                    </div>
                                                </div>
                                            </div>
                                  </div>
                                <div class="col-12 sub-head">
                                    Reopen
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Addendum Comments"><b>Addendum Comments</b></label>
                                        <input type="text" name="Addendum_Comments"
                                            value= "{{ $data->Addendum_Comments }}">
                                    </div>
                                </div>
                                <!-- <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Addendum Attachments"><b>Addendum Attachment</b></label>
                                        <input type="file" id="myfile" name="Addendum_Attachment"
                                            value="{{ $data->Addendum_Attachment }}">
                                    </div>
                                </div> -->
                                <div class="col-6">
                                            <div class="group-input">
                                                <label for="Addendum Attachments">Addendum Attachment</label>
                                                <div><small class="text-primary">Please Attach all relevant or supporting documents</small></div>
                                                <div class="file-attachment-field">
                                                    <div disabled class="file-attachment-list" id="myfile">
                                                        @if ($data->Addendum_Attachment)
                                                        @foreach(json_decode($data->Addendum_Attachment) as $file)
                                                        <h6 type="button" class="file-container text-dark" style="background-color: rgb(243, 242, 240);">
                                                            <b>{{ $file }}</b>
                                                            <a href="{{ asset('upload/' . $file) }}" target="_blank"><i class="fa fa-eye text-primary" style="font-size:20px; margin-right:-10px;"></i></a>
                                                            <a  type="button" class="remove-file" data-file-name="{{ $file }}"><i class="fa-solid fa-circle-xmark" style="color:red; font-size:20px;"></i></a>
                                                        </h6>
                                                   @endforeach
                                                        @endif
                                                    </div>
                                                    <div class="add-btn">
                                                        <div>Add</div>
                                                        <input {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }} type="file" id="myfile" name="Addendum_Attachment[]"
                                                            oninput="addMultipleFiles(this, 'Addendum_Attachment')"
                                                            multiple>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                            </div>
                            <div class="button-block">
                                        @if ($data->stage != 0)
                                            <button type="submit" id="ChangesaveButton" class="saveButton"
                                                {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>Save</button>
                                        @endif
                                        <button type="button" class="backButton" onclick="previousStep()">Back</button>
                                        <button type="button" class="nextButton" onclick="nextStep()">Next</button>
                                        <button type="button"> <a href="{{ url('rcms/qms-dashboard') }}"
                                                class="text-white"> Exit </a> </button>
                                    </div>
                        </div>
                    </div>

                    <div id="CCForm3" class="inner-block cctabcontent">
                        <div class="inner-block-content">
                            <div class="row">
                                <!-- Reference Info comments -->
                                <div class="col-12 sub-head">
                                    Reference Info comments
                                </div>
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="Comments"><b>Comments</b></label>
                                        <textarea name="Comments">{{ $data->Comments }}</textarea>
                                    </div>
                                </div>
                                <!-- <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Attachments"><b>Attachment</b></label>
                                        <input type="file" id="myfile" name="Attachment">
                                    </div>
                                </div> -->
                                <div class="col-12">
                                            <div class="group-input">
                                                <label for="Attachments">Attachment</label>
                                                <div><small class="text-primary">Please Attach all relevant or supporting documents</small></div>
                                                <div class="file-attachment-field">
                                                    <div disabled class="file-attachment-list" id="myfile">
                                                        @if ($data->Attachment)
                                                        @foreach(json_decode($data->Attachment) as $file)
                                                        <h6 type="button" class="file-container text-dark" style="background-color: rgb(243, 242, 240);">
                                                            <b>{{ $file }}</b>
                                                            <a href="{{ asset('upload/' . $file) }}" target="_blank"><i class="fa fa-eye text-primary" style="font-size:20px; margin-right:-10px;"></i></a>
                                                            <a  type="button" class="remove-file" data-file-name="{{ $file }}"><i class="fa-solid fa-circle-xmark" style="color:red; font-size:20px;"></i></a>
                                                        </h6>
                                                   @endforeach
                                                        @endif
                                                    </div>
                                                    <div class="add-btn">
                                                        <div>Add</div>
                                                        <input {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }} value="{{ $data->Attachment }}" type="file" id="myfile" name="Attachment[]"
                                                            oninput="addMultipleFiles(this, 'Attachment')"
                                                            multiple>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                            </div>
                                <!-- <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Reference Records"><b>Reference Records</b></label>
                                        <input type="file" id="myfile" name="refer_record">
                                         <div class="static">Ref.Record</div>
                                    </div>
                                </div> -->
                                <div class="col-12">
                                            <div class="group-input">
                                                <label for="Reference Records">Reference Records</label>
                                                <div><small class="text-primary">Please Attach all relevant or supporting documents</small></div>
                                                <div class="file-attachment-field">
                                                    <div disabled class="file-attachment-list" id="refer_record">
                                                        @if ($data->refer_record)
                                                        @foreach(json_decode($data->refer_record) as $file)
                                                        <h6 type="button" class="file-container text-dark" style="background-color: rgb(243, 242, 240);">
                                                            <b>{{ $file }}</b>
                                                            <a href="{{ asset('upload/' . $file) }}" target="_blank"><i class="fa fa-eye text-primary" style="font-size:20px; margin-right:-10px;"></i></a>
                                                            <a  type="button" class="remove-file" data-file-name="{{ $file }}"><i class="fa-solid fa-circle-xmark" style="color:red; font-size:20px;"></i></a>
                                                        </h6>
                                                   @endforeach
                                                        @endif
                                                    </div>
                                                    <div class="add-btn">
                                                        <div>Add</div>
                                                        <input {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }} value="{{ $data->refer_record }}" type="file" id="myfile" name="refer_record[]"
                                                            oninput="addMultipleFiles(this, 'refer_record')"
                                                            multiple>
                                                    </div>
                                                </div>
                                            </div>
                                  </div>
                            </div>
                            <div class="button-block">
                                        @if ($data->stage != 0)
                                            <button type="submit" id="ChangesaveButton" class="saveButton"
                                                {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>Save</button>
                                        @endif
                                        <button type="button" class="backButton" onclick="previousStep()">Back</button>
                                        <button type="button" class="nextButton" onclick="nextStep()">Next</button>
                                        <button type="button"> <a href="{{ url('rcms/qms-dashboard') }}"
                                                class="text-white"> Exit </a> </button>
                                    </div>
                        </div>
                    </div>

                    <div id="CCForm4" class="inner-block cctabcontent">
                        <div class="inner-block-content">
                            <div class="row">
                                <!-- Activity History -->
                                <div class="col-12 sub-head">
                                    Data History
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Actual Closure Date"><b>Actual Closure Date</b></label>
                                        <div class="static">{{ $data->due_date }}</div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Original Date Due"><b>Original Date Due</b></label>
                                        <div class="static">{{ $data->due_date }}</div>
                                    </div>
                                </div>
                                <div class="col-12 sub-head">
                                    Record Signature
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">

                                        @php
                                            $submit = DB::table('c_c_stage_histories')
                                                ->where('type', 'Effectiveness-Check')
                                                ->where('doc_id', $data->id)
                                                ->where('stage_id', 2)
                                                ->get();
                                        @endphp
                                        <label for="Original Due Date">Submitted By</label>
                                        @foreach ($submit as $temp)
                                            <div class="static">{{ $temp->user_name }}</div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">

                                        @php
                                            $submit = DB::table('c_c_stage_histories')
                                                ->where('type', 'Effectiveness-Check')
                                                ->where('doc_id', $data->id)
                                                ->where('stage_id', 2)
                                                ->get();
                                        @endphp
                                        <label for="Original Due Date">Submitted ON</label>
                                        @foreach ($submit as $temp)
                                            <div class="static">{{ $temp->created_at }}</div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">

                                        @php
                                            $submit = DB::table('c_c_stage_histories')
                                                ->where('type', 'Effectiveness-Check')
                                                ->where('doc_id', $data->id)
                                                ->where('stage_id', 3)
                                                ->get();
                                        @endphp
                                        <label for="Original Due Date">Complete By</label>
                                        @foreach ($submit as $temp)
                                            <div class="static">{{ $temp->user_name }}</div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">

                                        @php
                                            $submit = DB::table('c_c_stage_histories')
                                                ->where('type', 'Effectiveness-Check')
                                                ->where('doc_id', $data->id)
                                                ->where('stage_id', 3)
                                                ->get();
                                        @endphp
                                        <label for="Complete On"><b>Complete On</b></label>
                                        @foreach ($submit as $temp)
                                            <div class="static">{{ $temp->created_at }}</div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">

                                        @php
                                            $submit = DB::table('c_c_stage_histories')
                                                ->where('type', 'Effectiveness-Check')
                                                ->where('doc_id', $data->id)
                                                ->where('stage_id', 4)
                                                ->get();
                                        @endphp
                                        <label for="Quality Approal On"><b>Quality Approal On</b></label>
                                        @foreach ($submit as $temp)
                                            <div class="static">{{ $temp->user_name }}</div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        @php
                                            $submit = DB::table('c_c_stage_histories')
                                                ->where('type', 'Effectiveness-Check')
                                                ->where('doc_id', $data->id)
                                                ->where('stage_id', 4)
                                                ->get();
                                        @endphp
                                        <label for="Quality Approal On"><b>Quality Approal On</b></label>
                                        @foreach ($submit as $temp)
                                            <div class="static">{{ $temp->created_at }}</div>
                                        @endforeach
                                    </div>
                                </div>
                                {{--  <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Addendum Complete By"><b>Addendum Complete By</b></label>
                                        <div class="static">Shaleen Mishra</div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Addendum Complete On"><b>Addendum Complete On</b></label>
                                        <div class="static">17-04-2023 11:12PM</div>
                                    </div>
                                </div>  --}}
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        @php
                                            $submit = DB::table('c_c_stage_histories')
                                                ->where('type', 'Effectiveness-Check')
                                                ->where('doc_id', $data->id)
                                                ->where('stage_id', 5)
                                                ->get();
                                        @endphp
                                        <label for="Cancel By"><b>Cancel By</b></label>
                                        @foreach ($submit as $temp)
                                            <div class="static">{{ $temp->user_name }}</div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Cancel On"><b>Cancel On</b></label>
                                        @foreach ($submit as $temp)
                                            <div class="static">{{ $temp->created_at }}</div>
                                        @endforeach
                                    </div>
                                </div>
                                {{--  <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Re Open For Addendum By"><b>Re Open For Addendum By</b></label>
                                        <div class="static">Shaleen Mishra</div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Re Open For Addendum On"><b>Re Open For Addendum On</b></label>
                                        <div class="static">17-04-2023 11:12PM</div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Cancellation Approve By"><b>Cancellation Approve By</b></label>
                                        <div class="static">Shaleen Mishra</div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Cancellation Approve On"><b>Cancellation Approve On</b></label>
                                        <div class="static">17-04-2023 11:12PM</div>
                                    </div>
                                </div>  --}}
                                <div class="col-12 sub-head">
                                    Cancellation Details
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Cancellation Category"><b>Cancellation Category</b></label>
                                        <select>
                                            <option>Enter Your Selection Here</option>
                                            <option>Duplicate Entry</option>
                                            <option>Entered in Error</option>
                                            <option>No Longer Necessary</option>
                                            <option>Parent Record Closed</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="TrackWise Record Type"><b>TrackWise Record Type</b></label>
                                        <select>
                                            <option>Enter Your Selection Here</option>
                                            <option>Effectiveness Check</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="Cancellation Justification">Cancellation Justification</label>
                                        <textarea name="text"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="button-block">
                                        @if ($data->stage != 0)
                                            <button type="submit" id="ChangesaveButton" class="saveButton"
                                                {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>Save</button>
                                        @endif
                                        <button type="button" class="backButton" onclick="previousStep()">Back</button>
                                        <button type="submit"
                                            {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>Submit</button>
                                        <button type="button"> <a href="{{ url('rcms/qms-dashboard') }}"
                                                class="text-white"> Exit </a> </button>
                                    </div>
                    </div>


                </div>
            </div>
        </form>
    </div>

    <div class="modal fade" id="cancel-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">E-Signature</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <form action="{{ route('moreinfo_effectiveness', $data->id) }}" method="POST">
                    @csrf
                    <!-- Modal body -->
                    <div class="modal-body">
                        <div class="mb-3 text-justify">
                            Please select a meaning and a outcome for this task and enter your username
                            and password for this task. You are performing an electronic signature,
                            which is legally binding equivalent of a hand written signature.
                        </div>
                        <div class="group-input">
                            <label for="username">Username <span class="text-danger">*</span></label>
                            <input type="text" name="username" required>
                        </div>
                        <div class="group-input">
                            <label for="password">Password <span class="text-danger">*</span></label>
                            <input type="password" name="password" required>
                        </div>
                        <div class="group-input">
                            <label for="comment">Comment <span class="text-danger">*</span></label>
                            <input type="comment" name="comment" required>
                        </div>
                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="submit" data-bs-dismiss="modal">Submit</button>
                        <button>Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <style>
        #step-form>div {
            display: none
        }

        #step-form>div:nth-child(1) {
            display: block;
        }
    </style>

    <script>
        const saveButtons = document.querySelectorAll(".saveButton");
        const nextButtons = document.querySelectorAll(".nextButton");
        const form = document.getElementById("step-form");
        const stepButtons = document.querySelectorAll(".cctablinks");
        const steps = document.querySelectorAll(".cctabcontent");
        let currentStep = 0;

        function nextStep() {
            // Check if there is a next step
            if (currentStep < steps.length - 1) {
                // Hide current step
                steps[currentStep].style.display = "none";

                // Show next step
                steps[currentStep + 1].style.display = "block";

                // Add active class to next button
                stepButtons[currentStep + 1].classList.add("active");

                // Remove active class from current button
                stepButtons[currentStep].classList.remove("active");

                // Update current step
                currentStep++;
            }
        }


        function previousStep() {
            // Check if there is a previous step
            if (currentStep > 0) {
                // Hide current step
                steps[currentStep].style.display = "none";

                // Show previous step
                steps[currentStep - 1].style.display = "block";

                // Add active class to previous button
                stepButtons[currentStep - 1].classList.add("active");

                // Remove active class from current button
                stepButtons[currentStep].classList.remove("active");

                // Update current step
                currentStep--;
            }
        }
    </script>


    <div class="modal fade" id="signature-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">E-Signature</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ url('rcms/send-effectiveness', $data->id) }}" method="POST">
                    @csrf
                    <!-- Modal body -->
                    <div class="modal-body">
                        <div class="mb-3 text-justify">
                            Please select a meaning and a outcome for this task and enter your username
                            and password for this task. You are performing an electronic signature,
                            which is legally binding equivalent of a hand written signature.
                        </div>
                        <div class="group-input">
                            <label for="username">Username <span class="text-danger">*</span></label>
                            <input type="text" name="username" required>
                        </div>
                        <div class="group-input">
                            <label for="password">Password <span class="text-danger">*</span></label>
                            <input type="password" name="password" required>
                        </div>
                        <div class="group-input">
                            <label for="comment">Comment</label>
                            <input type="comment" name="comment">
                        </div>
                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="submit" data-bs-dismiss="modal">Submit</button>
                        <button>Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="rejection-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">E-Signature</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <form action="{{ url('rcms/effectiveness-reject', $data->id) }}" method="POST">
                    @csrf
                    <!-- Modal body -->
                    <div class="modal-body">
                        <div class="mb-3 text-justify">
                            Please select a meaning and a outcome for this task and enter your username
                            and password for this task. You are performing an electronic signature,
                            which is legally binding equivalent of a hand written signature.
                        </div>
                        <div class="group-input">
                            <label for="username">Username <span class="text-danger">*</span></label>
                            <input type="text" name="username" required>
                        </div>
                        <div class="group-input">
                            <label for="password">Password <span class="text-danger">*</span></label>
                            <input type="password" name="password" required>
                        </div>
                        <div class="group-input">
                            <label for="comment">Comment <span class="text-danger">*</span></label>
                            <input type="comment" name="comment" required>
                        </div>
                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="submit" data-bs-dismiss="modal">Submit</button>
                        <button>Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div id="division-modal" class="d-none">
        <div class="division-container">
            <div class="content-container">
                <form action="{{ route('division_change', $data->id) }}" method="post">
                    @csrf
                    <div class="division-tabs">
                        <div class="tab">
                            @php
                                $division = DB::table('divisions')->get();
                            @endphp
                            @foreach ($division as $temp)
                                <input type="hidden" value="{{ $temp->id }}" name="division_id" required>
                                <button class="divisionlinks"
                                    onclick="openDivision(event, {{ $temp->id }})">{{ $temp->name }}</button>
                            @endforeach

                        </div>
                        @php
                            $process = DB::table('processes')->get();
                        @endphp
                        @foreach ($process as $temp)
                            <div id="{{ $temp->division_id }}" class="divisioncontent">
                                @php
                                    $pro = DB::table('processes')
                                        ->where('division_id', $temp->division_id)
                                        ->get();
                                @endphp
                                @foreach ($pro as $test)
                                    <label for="process">
                                        <input type="radio" for="process" value="{{ $test->id }}"
                                            name="process_id" required> {{ $test->process_name }}
                                    </label>
                                @endforeach
                            </div>
                        @endforeach

                    </div>
                    <div class="button-container">
                        <button id="submit-division">Cancel</button>
                        <button id="submit-division" type="submit">Continue</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="child-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Document Revision</h4>
                </div>
                <form method="{{ url('rcms/child-AT', $data->id) }}" action="post">
                    @csrf
                    <!-- Modal body -->
                    <div class="modal-body">
                        <div class="group-input">
                            <label for="revision">Choose Change Implementation</label>
                            <label for="major">
                                <input type="radio" name="revision" id="major" value="Action-Item">
                                Action Item

                            </label>
                            <label for="minor">
                                <input type="radio" name="revision" id="minor">
                                Extention
                            </label>

                            <label for="minor">
                                <input type="radio" name="revision" id="minor">
                                New Document
                            </label>


                        </div>

                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="button" data-bs-dismiss="modal">Close</button>
                        <button type="submit">Submit</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('#add-input').click(function() {
                var lastInput = $('.bar input:last');
                var newInput = $('<input type="text" name="review_comment">');
                lastInput.after(newInput);
            });
        });
    </script>
    <script>
        function openCity(evt, cityName) {
            var i, cctabcontent, cctablinks;
            cctabcontent = document.getElementsByClassName("cctabcontent");
            for (i = 0; i < cctabcontent.length; i++) {
                cctabcontent[i].style.display = "none";
            }
            cctablinks = document.getElementsByClassName("cctablinks");
            for (i = 0; i < cctablinks.length; i++) {
                cctablinks[i].className = cctablinks[i].className.replace(" active", "");
            }
            document.getElementById(cityName).style.display = "block";
            evt.currentTarget.className += " active";
        }
    </script>
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.18/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.18/dist/sweetalert2.all.min.js"></script>

    @if (session()->has('errorMessages'))
        <script>
            // Create an array to hold all the error messages
            var errorMessages = @json(session()->get('errorMessages'));

            // Show the sweetAlert with the error messages

            Swal.fire({
                icon: '',
                title: 'Validation Error',
                html: errorMessages,

                showCloseButton: true, // Display a close button
                customClass: {
                    title: 'my-title-class', // Add a custom CSS class to the title
                    htmlContainer: 'my-html-class text-danger', // Add a custom CSS class to the popup content
                },
                confirmButtonColor: '#3085d6', // Customize the confirm button color
            });
        </script>
        
        @php session()->forget('errorMessages'); @endphp
    @endif
    <script>
            document.addEventListener('DOMContentLoaded', function () {
                const removeButtons = document.querySelectorAll('.remove-file');

                removeButtons.forEach(button => {
                    button.addEventListener('click', function () {
                        const fileName = this.getAttribute('data-file-name');
                        const fileContainer = this.closest('.file-container');

                        // Hide the file container
                        if (fileContainer) {
                            fileContainer.style.display = 'none';
                        }
                    });
                });
            });
        </script>
@endsection
