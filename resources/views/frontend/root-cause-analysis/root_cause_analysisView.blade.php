@extends('frontend.layout.main')
@section('container')
    <style>
        textarea.note-codable {
            display: none !important;
        }

        header {
            display: none;
        }
    </style>
     <script>
        function addFishBone(top, bottom) {
            let mainBlock = document.querySelector('.fishbone-ishikawa-diagram');
            let topBlock = mainBlock.querySelector(top)
            let bottomBlock = mainBlock.querySelector(bottom)

            let topField = document.createElement('div')
            topField.className = 'grid-field fields top-field'

            let measurement = document.createElement('div')
            let measurementInput = document.createElement('input')
            measurementInput.setAttribute('type', 'text')
            measurementInput.setAttribute('name', 'measurement[]')
            measurement.append(measurementInput)
            topField.append(measurement)

            let materials = document.createElement('div')
            let materialsInput = document.createElement('input')
            materialsInput.setAttribute('type', 'text')
            materialsInput.setAttribute('name', 'materials[]')
            materials.append(materialsInput)
            topField.append(materials)

            let methods = document.createElement('div')
            let methodsInput = document.createElement('input')
            methodsInput.setAttribute('type', 'text')
            methodsInput.setAttribute('name', 'methods[]')
            methods.append(methodsInput)
            topField.append(methods)

            topBlock.prepend(topField)

            let bottomField = document.createElement('div')
            bottomField.className = 'grid-field fields bottom-field'

            let environment = document.createElement('div')
            let environmentInput = document.createElement('input')
            environmentInput.setAttribute('type', 'text')
            environmentInput.setAttribute('name', 'environment[]')
            environment.append(environmentInput)
            bottomField.append(environment)

            let manpower = document.createElement('div')
            let manpowerInput = document.createElement('input')
            manpowerInput.setAttribute('type', 'text')
            manpowerInput.setAttribute('name', 'manpower[]')
            manpower.append(manpowerInput)
            bottomField.append(manpower)

            let machine = document.createElement('div')
            let machineInput = document.createElement('input')
            machineInput.setAttribute('type', 'text')
            machineInput.setAttribute('name', 'machine[]')
            machine.append(machineInput)
            bottomField.append(machine)

            bottomBlock.append(bottomField)
        }

        function deleteFishBone(top, bottom) {
            let mainBlock = document.querySelector('.fishbone-ishikawa-diagram');
            let topBlock = mainBlock.querySelector(top)
            let bottomBlock = mainBlock.querySelector(bottom)
            if (topBlock.firstChild) {
                topBlock.removeChild(topBlock.firstChild);
            }
            if (bottomBlock.lastChild) {
                bottomBlock.removeChild(bottomBlock.lastChild);
            }
        }
    </script>
     <script>
        function addWhyField(con_class, name) {
            let mainBlock = document.querySelector('.why-why-chart')
            let container = mainBlock.querySelector(`.${con_class}`)
            let textarea = document.createElement('textarea')
            textarea.setAttribute('name', name);
            container.append(textarea)
        }
    </script>

    <div class="form-field-head">

        <div class="division-bar">
            <strong>Site Division/Project</strong> :
            {{ Helpers::getDivisionName($data->division_id) }}/ Root Cause Analysis
        </div>
    </div>
    @php
        $users = DB::table('users')->get();
    @endphp

    {{-- ======================================
                    DATA FIELDS
    ======================================= --}}
    <div id="change-control-view">
        <div class="container-fluid">

            <div class="inner-block state-block">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="main-head">Record Workflow </div>

                    <div class="d-flex" style="gap:20px;">
                        <button class="button_theme1" onclick="window.print();return false;"
                            class="new-doc-btn">Print</button>
                        <button class="button_theme1"> <a class="text-white" href="{{ url('rootAuditTrial', $data->id) }}">
                                Audit Trail </a> </button>

                        @if ($data->stage == 1)
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#signature-modal">
                                Acknowledge
                            </button>
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#cancel-modal">
                                Cancel
                            </button>
                        @elseif($data->stage == 2)
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#signature-modal">
                                Submit
                            </button>
                        @elseif($data->stage == 5)
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#signature-modal">
                                Group/ CFT Review
                                Required

                            </button>
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#rejection-modal">
                                Group/ CFT Review Not
                                Required

                            </button>
                        @elseif($data->stage == 4)
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#signature-modal">
                                Approve

                            </button>
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#rejection-modal">
                                More Info Required

                            </button>
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#signature-modal">
                                Group Feedback
                            </button>
                        @elseif($data->stage == 3)
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#cancel-modal">
                                QA Review Complete
                            </button>
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#rejection-modal">
                                More Information
                                Required
                            </button>
                        @endif
                        <button class="button_theme1"> <a class="text-white" href="{{ url('rcms/qms-dashboard') }}"> Exit
                            </a> </button>


                    </div>

                </div>
                <div class="status">
                    <div class="head">Current Status</div>
                    {{-- ------------------------------By Pankaj-------------------------------- --}}
                    @if ($data->stage == 0)
                        <div class="progress-bars">
                            <div class="bg-danger">Closed-Cancelled</div>
                        </div>
                    @else
                        <div class="progress-bars">
                            @if ($data->stage >= 1)
                                <div class="active">Opened</div>
                            @else
                                <div class="">Opened</div>
                            @endif

                            @if ($data->stage >= 2)
                                <div class="active">Investigation in Progress </div>
                            @else
                                <div class="">Investigation in Progress</div>
                            @endif


                            {{-- @if ($data->stage >= 3)
                                <div class="active">Pending Group Review Discussion</div>
                            @else
                                <div class="">Pending Group Review Discussion</div>
                            @endif

                            @if ($data->stage >= 4)
                                <div class="active">Pending Group Review</div>
                            @else
                                <div class="">Pending Group Review</div>
                            @endif --}}


                            @if ($data->stage >= 3)
                                <div class="active">Pending QA Review</div>
                            @else
                                <div class="">Pending QA Review</div>
                            @endif
                            @if ($data->stage >= 6)
                                <div class="bg-danger">Closed - Done</div>
                            @else
                                <div class="">Closed - Done</div>
                            @endif
                        </div>
                    @endif


                </div>

            </div>
        </div>


        <div id="change-control-fields">

            <div class="container-fluid">

                <!-- Tab links -->
                <div class="cctab">
                    <button class="cctablinks active" onclick="openCity(event, 'CCForm1')">Investigation</button>
                    <button class="cctablinks" onclick="openCity(event, 'CCForm2')">Investigation & Root Cause</button>
                    <button class="cctablinks" onclick="openCity(event, 'CCForm4')">QA Review</button>
                    {{-- <button class="cctablinks" onclick="openCity(event, 'CCForm4')">Environmental Monitoring</button> --}}
                    {{-- <button class="cctablinks" onclick="openCity(event, 'CCForm5')">Lab Investigation Remark</button> --}}
                    {{-- <button class="cctablinks" onclick="openCity(event, 'CCForm6')">QC Head/Designee Eval Comments</button> --}}
                    <button class="cctablinks" onclick="openCity(event, 'CCForm7')">Activity Log</button>
                </div>

                <form action="{{ route('root_update', $data->id) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div id="step-form">

                         {{-- <div id="CCForm1" class="inner-block cctabcontent">
                            <div class="inner-block-content">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="group-input">
                                            <label for="RLS Record Number"><b>Record Number</b></label>
                                            <input disabled type="text" name="record_number"
                                                value=" {{ Helpers::getDivisionName($data->division_id) }}/RCA/{{ Helpers::year($data->created_at) }}/{{ $data->record }}">

                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="group-input">
                                            <label for="Division Code"><b>Division Code</b></label>
                                            <input disabled type="text" name="division_code"
                                                value=" {{ Helpers::getDivisionName($data->division_id) }}">
                                            {{-- <div class="static">QMS-North America</div> --}}
                                        {{-- </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="group-input">
                                            <label for="Initiator"><b>Initiator</b></label>
                                            {{-- <div class="static">{{ Auth::user()->name }}</div> --}}
                                            {{-- <input disabled type="text" name="division_code"
                                                value="{{ $data->initiator_name }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="group-input">
                                            <label for="Date Due"><b>Date of Initiation</b></label>
                                            <input disabled type="text"
                                                value="{{ Helpers::getdateFormat($data->intiation_date) }}">


                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="group-input">
                                            <label for="search">
                                                Assigned To <span class="text-danger"></span>
                                            </label>
                                            <select id="select-state" placeholder="Select..." name="assign_id"
                                                {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>
                                                <option value="">Select a value</option>
                                                @foreach ($users as $key => $value)
                                                    <option value="{{ $value->id }}"
                                                        @if ($data->assign_id == $value->id) selected @endif>
                                                        {{ $value->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('assign_to')
                                                <p class="text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="group-input">
                                            <label for="due-date">Due Date <span class="text-danger"></span></label>
                                            <div><small class="text-primary">If revising Due Date, kindly mention revision reason in "Due Date Extension Justification" data field.</small></div>
                                            <input disabled type="text"
                                                value="{{ Helpers::getdateFormat($data->due_date) }}">
                                            {{-- <div class="static"> {{ $data->due_date }}</div> --}}

                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="group-input">
                                            <label for="Initiator Group"><b>Initiator Group</b></label>
                                            <select name="initiatorGroup" {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}
                                                id="initiator-group">
                                                <option value="CQA"
                                                    @if ($data->initiatorGroup == 'CQA') selected @endif>Corporate
                                                    Quality Assurance</option>
                                                <option value="QAB"
                                                    @if ($data->initiatorGroup == 'QAB') selected @endif>Quality
                                                    Assurance Biopharma</option>
                                                <option value="CQC"
                                                    @if ($data->initiatorGroup == 'CQC') selected @endif>Central
                                                    Quality Control</option>
                                                <option value="CQC"
                                                    @if ($data->initiatorGroup == 'CQC') selected @endif>Manufacturing
                                                </option>
                                                <option value="PSG"
                                                    @if ($data->initiatorGroup == 'PSG') selected @endif>Plasma --}}
                                                    {{-- Sourcing Group</option>
                                                <option value="CS"
                                                    @if ($data->initiatorGroup == 'CS') selected @endif>Central
                                                    Stores</option>
                                                <option value="ITG"
                                                    @if ($data->initiatorGroup == 'ITG') selected @endif>Information
                                                    Technology Group</option>
                                                <option value="MM"
                                                    @if ($data->initiatorGroup == 'MM') selected @endif>Molecular
                                                    Medicine</option>
                                                <option value="CL"
                                                    @if ($data->initiatorGroup == 'CL') selected @endif>Central
                                                    Laboratory</option>
                                                <option value="TT"
                                                    @if ($data->initiatorGroup == 'TT') selected @endif>Tech
                                                    team</option>
                                                <option value="QA"
                                                    @if ($data->initiatorGroup == 'QA') selected @endif>Quality
                                                    Assurance</option>
                                                <option value="QM"
                                                    @if ($data->initiatorGroup == 'QM') selected @endif>Quality
                                                    Management</option>
                                                <option value="IA"
                                                    @if ($data->initiatorGroup == 'IA') selected @endif>IT
                                                    Administration</option>
                                                <option value="ACC"
                                                    @if ($data->initiatorGroup == 'ACC') selected @endif>Accounting
                                                </option>
                                                <option value="LOG"
                                                    @if ($data->initiatorGroup == 'LOG') selected @endif>Logistics
                                                </option>
                                                <option value="SM"
                                                    @if ($data->initiatorGroup == 'SM') selected @endif>Senior
                                                    Management</option>
                                                <option value="BA"
                                                    @if ($data->initiatorGroup == 'BA') selected @endif>Business
                                                    Administration</option>

                                            </select>
                                        </div>
                                    </div> 
                                    <div class="col-lg-6">
                                        <div class="group-input">
                                            <label for="Initiator Group Code">Initiator Group Code</label>
                                            <input type="text" name="initiator_group_code"
                                                {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}
                                                value="{{ $data->initiator_Group }}" disabled>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="group-input">
                                            <label for="Short Description">Short Description <span
                                                    class="text-danger">*</span></label>
                                            <div><small class="text-primary">Please mention brief summary</small></div>
                                            <textarea name="short_description" {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>{{ $data->short_description }}</textarea>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="group-input">
                                            <label for="Sample_Type">Sample Type</label>
                                            <select name="Sample_Types"
                                                {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>
                                                <option value="">-- Select --</option>
                                                <option value="Demo" @if ($data->Sample_Types == 'Demo') selected @endif>
                                                    Demo 1</option>
                                                <option value="Demo2" @if ($data->Sample_Types == 'Demo2') selected @endif>
                                                    Demo 2</option>
                                                <option value="Demo3" @if ($data->Sample_Types == 'Demo3') selected @endif> --}}
                                                    {{-- Demo 3</option>
                                                <option value="Demo4" @if ($data->Sample_Types == 'Demo4') selected @endif>
                                                    Demo 4</option>
                                                <option value="Demo5" @if ($data->Sample_Types == 'Demo5') selected @endif>
                                                    Demo 5</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="group-input">
                                            <label for="test_lab">Test Lab</label>
                                            <input type="text" name="test_lab" value="{{ $data->test_lab }}"
                                                {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }} />
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="group-input">
                                            <label for="ten_trend">Trend of Previous Ten Results</label>
                                            <textarea name="ten_trend" {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>{{ $data->ten_trend }}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="group-input">
                                            <label for="investigators">Investigators</label>
                                            <select multiple name="investigators[]" placeholder="Select Investigators"
                                                data-search="false" data-silent-initial-value-set="true"
                                                id="investigators"
                                                {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>
                                                <option value="1"
                                                    {{ in_array('1', explode(',', $data->investigators)) ? 'selected' : '' }}>
                                                    Amit Guru</option>
                                                <option value="2"
                                                    {{ in_array('2', explode(',', $data->investigators)) ? 'selected' : '' }}>
                                                    Anshul Patel</option>
                                                <option value="3"
                                                    {{ in_array('3', explode(',', $data->investigators)) ? 'selected' : '' }}>
                                                    Vikash Prajapati</option>
                                                <option value="4"
                                                    {{ in_array('4', explode(',', $data->investigators)) ? 'selected' : '' }}>
                                                    Amit Patel</option>
                                                <option value="5"
                                                    {{ in_array('5', explode(',', $data->investigators)) ? 'selected' : '' }}>
                                                    Shaleen Mishra</option>
                                                <option value="6"
                                                    {{ in_array('6', explode(',', $data->investigators)) ? 'selected' : '' }}>
                                                    Madhulika Mishra</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="group-input">
                                            <label for="Inv Attachments">Attachments</label>
                                            <div><small class="text-primary">Please Attach all relevant or supporting
                                                    documents</small></div>
                                            <div class="file-attachment-field">
                                                <div disabled class="file-attachment-list" id="attachments">
                                                    @if ($data->attachments)
                                                        @foreach (json_decode($data->attachments) as $file)
                                                            <h6 type="button" class="file-container text-dark"
                                                                style="background-color: rgb(243, 242, 240);">
                                                                <b>{{ $file }}</b>
                                                                <a href="{{ asset('upload/' . $file) }}"
                                                                    target="_blank"><i class="fa fa-eye text-primary"
                                                                        style="font-size:20px; margin-right:-10px;"></i></a>
                                                                <a type="button" class="remove-file"
                                                                    data-file-name="{{ $file }}"><i
                                                                        class="fa-solid fa-circle-xmark"
                                                                        style="color:red; font-size:20px;"></i></a>
                                                            </h6>
                                                        @endforeach
                                                    @endif --}}

                                                {{-- </div>
                                                <div class="add-btn">
                                                    <div>Add</div>
                                                    <input {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}
                                                        type="file" id="myfile" name="attachments[]"
                                                        oninput="addMultipleFiles(this, 'attachments')" multiple>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="group-input">
                                            <label for="comments">Comments</label>
                                            <textarea name="comments" {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="button-block">
                                    <button type="submit" id="ChangesaveButton" class="saveButton"
                                        {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>Save</button>
                                    <button type="button" id="ChangeNextButton" class="nextButton">Next</button>
                                    <button type="button"> <a class="text-white"
                                            href="{{ url('rcms/qms-dashboard') }}">
                                            Exit </a> </button>
                                </div>
                            </div>
                        </div>

                        <div id="CCForm2" class="inner-block cctabcontent">
                            <div class="inner-block-content">
                                <div class="sub-head">
                                    Chemical Analysis I
                                </div>
                                <div class="group-input">
                                    <label for="review_analyst_knowledge">
                                        Review of analyst knowledge and training<button type="button" name="ann"
                                            onclick="add2Input('review_analyst_knowledge')"
                                            {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>+</button>
                                    </label>
                                    <table class="table table-bordered" id="review_analyst_knowledge">
                                        <thead>
                                            <tr>
                                                <th>Row #</th>
                                                <th>Question</th>
                                                <th>Response</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if(!empty($dataAnalysis1->Question))
                                            @foreach (unserialize($dataAnalysis1->Question) as $key => $temps)
                                                <tr>
                                                    <td><input type="text" name="serial_number[]"
                                                            value="{{ $key + 1 }}"></td>
                                                    <td><input type="text" name="questions[]"
                                                            value="{{ $temps ? $temps : ' ' }}"></td>
                                                    <td><input type="text" name="response[]"
                                                            value="{{ unserialize($dataAnalysis1->Response)[$key] ? unserialize($dataAnalysis1->Response)[$key] : '' }}">
                                                    </td>
                                                </tr>
                                            @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                                <div class="sub-head">
                                    Chemical Analysis II
                                </div>
                                <div class="group-input">
                                    <label for="review_raw_data">
                                        Review of Raw Data<button type="button" name="ann"
                                            onclick="add2Input('review_raw_data')"
                                            {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>+</button>
                                    </label>
                                    <table class="table table-bordered" id="review_raw_data">
                                        <thead>
                                            <tr>
                                                <th>Row #</th>
                                                <th>Question</th>
                                                <th>Response</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @if ($dataAnalysis2->Question) {   
                                            @foreach (unserialize($dataAnalysis2->Question) as $key => $temps)
                                                <tr>
                                                    <td><input type="text" name="serial_number2[]"
                                                            value="{{ $key + 1 }}"></td>
                                                    <td><input type="text" name="questions2[]"
                                                            value="{{ $temps ? $temps : ' ' }}"></td>
                                                    <td><input type="text" name="response2[]"
                                                            value="{{ unserialize($dataAnalysis1->Response)[$key] ? unserialize($dataAnalysis2->Response)[$key] : '' }}">
                                                    </td>
                                                </tr>
                                            @endforeach
                                         @else
                                         <tr>
                                            <td><input type="text" name="serial_number2[]"
                                                        value="1"></td>
                                                <td><input type="text" name="questions2[]"
                                                        value=""></td>
                                                <td><input type="text" name="response2[]"
                                                        value="">
                                                </td>
                                            </tr>
                                         @endif
                                        </tbody>
                                    </table>
                                </div>
                                <div class="sub-head">
                                    Chemical Analysis III
                                </div>
                                <div class="group-input">
                                    <label for="review_sampling_storage">
                                        Review of Sampling and Storage<button type="button" name="ann"
                                            onclick="add2Input('review_sampling_storage')"
                                            {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>+</button>
                                    </label>
                                    <table class="table table-bordered" id="review_sampling_storage">
                                        <thead>
                                            <tr>
                                                <th>Row #</th>
                                                <th>Question</th>
                                                <th>Response</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @if ($dataAnalysis3->Question) {  
                                            @foreach (unserialize($dataAnalysis3->Question) as $key => $temps)
                                                <tr>
                                                    <td><input type="text" name="serial_number3[]"
                                                            value="{{ $key + 1 }}"></td>
                                                    <td><input type="text" name="questions3[]"
                                                            value="{{ $temps ? $temps : ' ' }}"></td>
                                                    <td><input type="text" name="response3[]"
                                                            value="{{ unserialize($dataAnalysis3->Response)[$key] ? unserialize($dataAnalysis3->Response)[$key] : '' }}">
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                        
                                                <tr>
                                                    <td><input type="text" name="serial_number3[]"
                                                            value="1"></td>
                                                    <td><input type="text" name="questions3[]"
                                                            value=""></td>
                                                    <td><input type="text" name="response3[]"
                                                            value="">
                                                    </td>
                                                </tr>
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                                <div class="sub-head">
                                    Chemical Analysis IV
                                </div>
                                <div class="group-input">
                                    <label for="instrument_performance">
                                        Instrument Performance<button type="button" name="ann"
                                            onclick="add2Input('instrument_performance')"
                                            {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>+</button>
                                    </label>
                                    <table class="table table-bordered" id="instrument_performance">
                                        <thead>
                                            <tr>
                                                <th>Row #</th>
                                                <th>Question</th>
                                                <th>Response</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @if ($dataAnalysis4->Question) {  
                                            @foreach (unserialize($dataAnalysis4->Question) as $key => $temps)
                                                <tr>
                                                    <td><input type="text" name="serial_number4[]"
                                                            value="{{ $key + 1 }}"></td>
                                                    <td><input type="text" name="questions4[]"
                                                            value="{{ $temps ? $temps : ' ' }}"></td>
                                                    <td><input type="text" name="response4[]"
                                                            value="{{ unserialize($dataAnalysis4->Response)[$key] ? unserialize($dataAnalysis4->Response)[$key] : '' }}">
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                                <tr>
                                                    <td><input type="text" name="serial_number4[]"
                                                            value="1"></td>
                                                    <td><input type="text" name="questions4[]"
                                                            value=""></td>
                                                    <td><input type="text" name="response4[]"
                                                            value="">
                                                    </td>
                                                </tr>
                                        
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                                <div class="button-block">
                                    <button type="submit" class="saveButton"
                                        {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>Save</button>
                                    <button type="button" class="backButton" onclick="previousStep()">Back</button>
                                    <button type="button" class="nextButton" onclick="nextStep()">Next</button>
                                    <button type="button"> <a class="text-white"
                                            href="{{ url('rcms/qms-dashboard') }}">
                                            Exit </a> </button>
                                </div>
                            </div>
                        </div>

                        <div id="CCForm3" class="inner-block cctabcontent">
                            <div class="inner-block-content">
                                <div class="sub-head">
                                    Water Analysis I
                                </div>
                                <div class="group-input">
                                    <label for="water_review_analyst_knowledge">
                                        Review of analyst knowledge and training<button type="button" name="ann"
                                            onclick="add2Input('water_review_analyst_knowledge')"
                                            {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>+</button>
                                    </label>
                                    <table class="table table-bordered" id="water_review_analyst_knowledge">
                                        <thead>
                                            <tr>
                                                <th>Row #</th>
                                                <th>Question</th>
                                                <th>Response</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @if ($dataAnalysis5->Question) {  
                                            @foreach (unserialize($dataAnalysis5->Question) as $key => $temps)
                                                <tr>
                                                    <td><input type="text" name="serial_number5[]"
                                                            value="{{ $key + 1 }}"></td>
                                                    <td><input type="text" name="questions5[]"
                                                            value="{{ $temps ? $temps : ' ' }}"></td>
                                                    <td><input type="text" name="response5[]"
                                                            value="{{ unserialize($dataAnalysis5->Response)[$key] ? unserialize($dataAnalysis5->Response)[$key] : '' }}">
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                        <tr>
                                            <td><input type="text" name="serial_number5[]"
                                                    value="1"></td>
                                            <td><input type="text" name="questions5[]"
                                                    value=""></td>
                                            <td><input type="text" name="response5[]"
                                                    value="">
                                            </td>
                                        </tr>
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                                <div class="sub-head">
                                    Water Analysis II
                                </div>
                                <div class="group-input">
                                    <label for="review_instruments">
                                        Review of Instuments<button type="button" name="ann"
                                            onclick="add2Input('review_instruments')"
                                            {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>+</button>
                                    </label>
                                    <table class="table table-bordered" id="review_instruments">
                                        <thead>
                                            <tr>
                                                <th>Row #</th>
                                                <th>Question</th>
                                                <th>Response</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @if ($dataAnalysis6->Question) {  
                                            @foreach (unserialize($dataAnalysis6->Question) as $key => $temps)
                                                <tr>
                                                    <td><input type="text" name="serial_number6[]"
                                                            value="{{ $key + 1 }}"></td>
                                                    <td><input type="text" name="questions6[]"
                                                            value="{{ $temps ? $temps : ' ' }}"></td>
                                                    <td><input type="text" name="response6[]"
                                                            value="{{ unserialize($dataAnalysis6->Response)[$key] ? unserialize($dataAnalysis6->Response)[$key] : '' }}">
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                        <tr>
                                                    <td><input type="text" name="serial_number6[]"
                                                            value="1"></td>
                                                    <td><input type="text" name="questions6[]"
                                                            value=""></td>
                                                    <td><input type="text" name="response6[]"
                                                            value="">
                                                    </td>
                                                </tr>
                                        
                                        @endif;
                                        </tbody>
                                    </table>
                                </div>
                                <div class="sub-head">
                                    Water Analysis III
                                </div>
                                <div class="group-input">
                                    <label for="water_plant_checklist">
                                        Water Plant Checklist<button type="button" name="ann"
                                            onclick="add2Input('water_plant_checklist')"
                                            {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>+</button>
                                    </label>
                                    <table class="table table-bordered" id="water_plant_checklist">
                                        <thead>
                                            <tr>
                                                <th>Row #</th>
                                                <th>Question</th>
                                                <th>Response</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @if ($dataAnalysis7->Question) {  
                                            @foreach (unserialize($dataAnalysis7->Question) as $key => $temps)
                                                <tr>
                                                    <td><input type="text" name="serial_number7[]"
                                                            value="{{ $key + 1 }}"></td>
                                                    <td><input type="text" name="questions7[]"
                                                            value="{{ $temps ? $temps : ' ' }}"></td>
                                                    <td><input type="text" name="response7[]"
                                                            value="{{ unserialize($dataAnalysis7->Response)[$key] ? unserialize($dataAnalysis7->Response)[$key] : '' }}">
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                                <tr>
                                                    <td><input type="text" name="serial_number7[]"
                                                            value="1"></td>
                                                    <td><input type="text" name="questions7[]"
                                                            value=""></td>
                                                    <td><input type="text" name="response7[]"
                                                            value="">
                                                    </td>
                                                </tr>
                                        
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                                <div class="sub-head">
                                    Water Analysis IV
                                </div>
                                <div class="group-input">
                                    <label for="sample_testing_checklist">
                                        Sample Testing Checklist<button type="button" name="ann"
                                            onclick="add2Input('sample_testing_checklist')"
                                            {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>+</button>
                                    </label>
                                    <table class="table table-bordered" id="sample_testing_checklist">
                                        <thead>
                                            <tr>
                                                <th>Row #</th>
                                                <th>Question</th>
                                                <th>Response</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @if ($dataAnalysis8->Question) {  
                                            @foreach (unserialize($dataAnalysis8->Question) as $key => $temps)
                                                <tr>
                                                    <td><input type="text" name="serial_number8[]"
                                                            value="{{ $key + 1 }}"></td>
                                                    <td><input type="text" name="questions8[]"
                                                            value="{{ $temps ? $temps : ' ' }}"></td>
                                                    <td><input type="text" name="response8[]"
                                                            value="{{ unserialize($dataAnalysis8->Response)[$key] ? unserialize($dataAnalysis8->Response)[$key] : '' }}">
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                        <tr>
                                            <td><input type="text" name="serial_number8[]"
                                                    value="1"></td>
                                            <td><input type="text" name="questions8[]"
                                                    value=""></td>
                                            <td><input type="text" name="response8[]"
                                                    value="">
                                            </td>
                                        </tr>
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                                <div class="button-block">
                                    <button type="submit" class="saveButton"
                                        {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>Save</button>
                                    <button type="button" class="backButton" onclick="previousStep()">Back</button>
                                    <button type="button" class="nextButton" onclick="nextStep()">Next</button>
                                    <button type="button"> <a class="text-white"
                                            href="{{ url('rcms/qms-dashboard') }}">
                                            Exit </a> </button>
                                </div>
                            </div>
                        </div> --}}
                        <div id="CCForm4" class="inner-block cctabcontent">
                            <div class="inner-block-content">
                                {{-- <div class="sub-head">
                                    CFT Feedback
                                </div> --}}
                                <div class="row">
    
                                    <div class="col-lg-12">
                                        <div class="group-input">
                                            <label for="comments">Final Comments</label>
                                            <textarea name="cft_comments_new"{{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>{{ $data->cft_comments_new }}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="group-input">
                                            <label for="comments">Final Attachment</label>
                                            <div><small class="text-primary">Please Attach all relevant or supporting
                                                    documents</small></div>
                                            <div  class="file-attachment-field">
                                                <div disabled class="file-attachment-list" id="cft_attchament_new">
                                                    {{-- @if(!is_null($data->cft_attchament_new) && is_array(json_decode($data->cft_attchament_new))) --}}
                                                    @if ($data->cft_attchament_new)
                                                        @foreach(json_decode($data->cft_attchament_new) as $file)
                                                        <h6 type="button" class="file-container text-dark" style="background-color: rgb(243, 242, 240);">
                                                            <b>{{ $file }}</b>
                                                            <a href="{{ asset('upload/' . $file) }}" target="_blank"><i class="fa fa-eye text-primary" style="font-size:20px; margin-right:-10px;"></i></a>
                                                            <a  type="button" class="remove-file" data-file-name="{{ $file }}"><i class="fa-solid fa-circle-xmark" style="color:red; font-size:20px;"></i></a>
                                                        </h6>
                                                    @endforeach
                                                   {{-- @endif --}}
                                                   @endif
                                                </div>
                                                <div class="add-btn">
                                                    <div>Add</div>
                                                    <input type="file" id="myfile" name="cft_attchament_new[]"{{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}
                                                        oninput="addMultipleFiles(this, 'cft_attchament_new')" multiple>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- <div class="row">
                                    <div class="sub-head">
                                        Concerned Group Feedback
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="group-input">
                                            <label for="comments">QA Comments</label>
                                            <textarea name="qa_comments_new"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="group-input">
                                            <label for="comments">QA Head Designee Comments</label>
                                            <textarea name="designee_comments_new"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="group-input">
                                            <label for="comments">Warehouse Comments</label>
                                            <textarea name="Warehouse_comments_new"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="group-input">
                                            <label for="comments">Engineering Comments</label>
                                            <textarea name="Engineering_comments_new"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="group-input">
                                            <label for="comments">Instrumentation Comments</label>
                                            <textarea name="Instrumentation_comments_new"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="group-input">
                                            <label for="comments">Validation Comments</label>
                                            <textarea name="Validation_comments_new"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="group-input">
                                            <label for="comments">Others Comments</label>
                                            <textarea name="Others_comments_new"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="group-input">
                                            <label for="comments">Group Comments</label>
                                            <textarea name="Group_comments_new"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="group-input">
                                            <label for="group-attachments">Group Attachments</label>
                                            <div><small class="text-primary">Please Attach all relevant or supporting
                                                    documents</small></div>
                                            <div class="file-attachment-field">
                                                <div class="file-attachment-list" id="group_attachments_new"></div>
                                                <div class="add-btn">
                                                    <div>Add</div>
                                                    <input type="file" id="myfile" name="group_attachments_new[]"
                                                        oninput="addMultipleFiles(this, 'group_attachments_new')" multiple>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div> --}}
                                <div class="button-block">
                                    <button type="submit" class="saveButton">Save</button>
                                    <button type="button" class="backButton" onclick="previousStep()">Back</button>
                                    <button type="button" class="nextButton" onclick="nextStep()">Next</button>
                                    <button type="button"> <a class="text-white" href="{{ url('rcms/qms-dashboard') }}">
                                            Exit </a> </button>
    
                                </div>
                            </div>
                        </div>
                        {{-- <div id="CCForm4" class="inner-block cctabcontent">
                            <div class="inner-block-content">
                                <div class="sub-head">
                                    Environmental Monitoring I
                                </div>
                                <div class="group-input">
                                    <label for="environment_monitoring_results">
                                        Environment Monitoring Results<button type="button" name="ann"
                                            onclick="add2Input('environment_monitoring_results')"
                                            {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>+</button>
                                    </label>
                                    <table class="table table-bordered" id="environment_monitoring_results">
                                        <thead>
                                            <tr>
                                                <th>Row #</th>
                                                <th>Question</th>
                                                <th>Response</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @if ($dataAnalysis9->Question) { 
                                            @foreach (unserialize($dataAnalysis9->Question) as $key => $temps)
                                                <tr>
                                                    <td><input type="text" name="serial_number9[]"
                                                            value="{{ $key + 1 }}"></td>
                                                    <td><input type="text" name="questions9[]"
                                                            value="{{ $temps ? $temps : ' ' }}"></td>
                                                    <td><input type="text" name="response9[]"
                                                            value="{{ unserialize($dataAnalysis9->Response)[$key] ? unserialize($dataAnalysis9->Response)[$key] : '' }}">
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                        <tr>
                                                    <td><input type="text" name="serial_number9[]"
                                                            value="1"></td>
                                                    <td><input type="text" name="questions9[]"
                                                            value=""></td>
                                                    <td><input type="text" name="response9[]"
                                                            value="">
                                                    </td>
                                                </tr>
                                        
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                                <div class="sub-head">
                                    Environmental Monitoring II
                                </div>
                                <div class="group-input">
                                    <label for="instrument_calibration_result">
                                        Intrument Calibration Results<button type="button" name="ann"
                                            onclick="add2Input('instrument_calibration_result')"
                                            {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>+</button>
                                    </label>
                                    <table class="table table-bordered" id="instrument_calibration_result">
                                        <thead>
                                            <tr>
                                                <th>Row #</th>
                                                <th>Question</th>
                                                <th>Response</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @if ($dataAnalysis10->Question) { 
                                            @foreach (unserialize($dataAnalysis10->Question) as $key => $temps)
                                                <tr>
                                                    <td><input type="text" name="serial_number10[]"
                                                            value="{{ $key + 1 }}"></td>
                                                    <td><input type="text" name="questions10[]"
                                                            value="{{ $temps ? $temps : ' ' }}"></td>
                                                    <td><input type="text" name="response10[]"
                                                            value="{{ unserialize($dataAnalysis10->Response)[$key] ? unserialize($dataAnalysis10->Response)[$key] : '' }}">
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                                <tr>
                                                    <td><input type="text" name="serial_number10[]"
                                                            value="1"></td>
                                                    <td><input type="text" name="questions10[]"
                                                            value=""></td>
                                                    <td><input type="text" name="response10[]"
                                                            value="">
                                                    </td>
                                                </tr>
                                        
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                                <div class="sub-head">
                                    Environmental Monitoring III
                                </div>
                                <div class="group-input">
                                    <label for="review_storage_plate">
                                        Review of Storage condition of Plate<button type="button" name="ann"
                                            onclick="add2Input('review_storage_plate')"
                                            {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>+</button>
                                    </label>
                                    <table class="table table-bordered" id="review_storage_plate">
                                        <thead>
                                            <tr>
                                                <th>Row #</th>
                                                <th>Question</th>
                                                <th>Response</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @if ($dataAnalysis11->Question) { 
                                            @foreach (unserialize($dataAnalysis11->Question) as $key => $temps)
                                                <tr>
                                                    <td><input type="text" name="serial_number11[]"
                                                            value="{{ $key + 1 }}"></td>
                                                    <td><input type="text" name="questions11[]"
                                                            value="{{ $temps ? $temps : ' ' }}"></td>
                                                    <td><input type="text" name="response11[]"
                                                            value="{{ unserialize($dataAnalysis11->Response)[$key] ? unserialize($dataAnalysis11->Response)[$key] : '' }}">
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            
                                                <tr>
                                                    <td><input type="text" name="serial_number11[]"
                                                            value="1"></td>
                                                    <td><input type="text" name="questions11[]"
                                                            value=""></td>
                                                    <td><input type="text" name="response11[]"
                                                            value="">
                                                    </td>
                                                </tr>
                                        

                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                                <div class="sub-head">
                                    Environmental Monitoring IV
                                </div>
                                <div class="group-input">
                                    <label for="review_media_lot">
                                        Review of Media Lot<button type="button" name="ann"
                                            onclick="add2Input('review_media_lot')"
                                            {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>+</button>
                                    </label>
                                    <table class="table table-bordered" id="review_media_lot">
                                        <thead>
                                            <tr>
                                                <th>Row #</th>
                                                <th>Question</th>
                                                <th>Response</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @if ($dataAnalysis12->Question) {
                                            @foreach (unserialize($dataAnalysis12->Question) as $key => $temps)
                                                <tr>
                                                    <td><input type="text" name="serial_number12[]"
                                                            value="{{ $key + 1 }}"></td>
                                                    <td><input type="text" name="questions12[]"
                                                            value="{{ $temps ? $temps : ' ' }}"></td>
                                                    <td><input type="text" name="response12[]"
                                                            value="{{ unserialize($dataAnalysis12->Response)[$key] ? unserialize($dataAnalysis12->Response)[$key] : '' }}">
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                             <tr>
                                                    <td><input type="text" name="serial_number12[]"
                                                            value="1"></td>
                                                    <td><input type="text" name="questions12[]"
                                                            value=""></td>
                                                    <td><input type="text" name="response12[]"
                                                            value="">
                                                    </td>
                                                </tr>
                                        
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                                <div class="sub-head">
                                    Environmental Monitoring V
                                </div>
                                <div class="group-input">
                                    <label for="environment_sampling">
                                        Sampling<button type="button" name="ann"
                                            onclick="add2Input('environment_sampling')"
                                            {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>+</button>
                                    </label>
                                    <table class="table table-bordered" id="environment_sampling">
                                        <thead>
                                            <tr>
                                                <th>Row #</th>
                                                <th>Question</th>
                                                <th>Response</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @if ($dataAnalysis13->Question) {
                                            @foreach (unserialize($dataAnalysis13->Question) as $key => $temps)
                                                <tr>
                                                    <td><input type="text" name="serial_number13[]"
                                                            value="{{ $key + 1 }}"></td>
                                                    <td><input type="text" name="questions13[]"
                                                            value="{{ $temps ? $temps : ' ' }}"></td>
                                                    <td><input type="text" name="response13[]"
                                                            value="{{ unserialize($dataAnalysis13->Response)[$key] ? unserialize($dataAnalysis13->Response)[$key] : '' }}">
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                                <tr>
                                                    <td><input type="text" name="serial_number13[]"
                                                            value="1"></td>
                                                    <td><input type="text" name="questions13[]"
                                                            value=""></td>
                                                    <td><input type="text" name="response13[]"
                                                            value="">
                                                    </td>
                                                </tr>
                                        
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                                <div class="sub-head">
                                    Environmental Monitoring VI
                                </div>
                                <div class="group-input">
                                    <label for="airborne_contamination">
                                        Airborne Contamination<button type="button" name="ann"
                                            onclick="add2Input('airborne_contamination')"
                                            {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>+</button>
                                    </label>
                                    <table class="table table-bordered" id="airborne_contamination">
                                        <thead>
                                            <tr>
                                                <th>Row #</th>
                                                <th>Question</th>
                                                <th>Response</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @if ($dataAnalysis14->Question) {
                                            @foreach (unserialize($dataAnalysis14->Question) as $key => $temps)
                                                <tr>
                                                    <td><input type="text" name="serial_number14[]"
                                                            value="{{ $key + 1 }}"></td>
                                                    <td><input type="text" name="questions14[]"
                                                            value="{{ $temps ? $temps : ' ' }}"></td>
                                                    <td><input type="text" name="response14[]"
                                                            value="{{ unserialize($dataAnalysis14->Response)[$key] ? unserialize($dataAnalysis14->Response)[$key] : '' }}">
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                        <tr>
                                                    <td><input type="text" name="serial_number14[]"
                                                            value="1"></td>
                                                    <td><input type="text" name="questions14[]"
                                                            value=""></td>
                                                    <td><input type="text" name="response14[]"
                                                            value="">
                                                    </td>
                                                </tr>
                                        
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                                <div class="button-block">
                                    <button type="submit" class="saveButton"
                                        {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>Save</button>
                                    <button type="button" class="backButton" onclick="previousStep()">Back</button>
                                    <button type="button" class="nextButton" onclick="nextStep()">Next</button>
                                    <button type="button"> <a class="text-white"
                                            href="{{ url('rcms/qms-dashboard') }}">
                                            Exit </a> </button>
                                </div>
                            </div>
                        </div>

                        <div id="CCForm5" class="inner-block cctabcontent">
                            <div class="inner-block-content">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="group-input">
                                            <label for="lab_inv_concl">Lab Investigator Conclusion</label>
                                            <textarea name="lab_inv_concl" {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}></textarea>
                                        </div>
                                    </div>
                                    {{-- <div class="col-12">
                                        <div class="group-input">
                                            <label for="lab_inv_attach">Lab Investigator Attachments</label>
                                            <input type="file" name="lab_inv_attach"
                                                {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }} />
                                        </div>
                                    </div> --}}
{{-- 
                                    <div class="col-12">
                                        <div class="group-input">
                                            <label for="Inv Attachments">Lab Investigator Attachments</label>
                                            <div class="file-attachment-field">
                                                <div disabled class="file-attachment-list" id="lab_inv_attach">
                                                    @if ($data->lab_inv_attach)
                                                        @foreach (json_decode($data->lab_inv_attach) as $file)
                                                            <h6 type="button" class="file-container text-dark"
                                                                style="background-color: rgb(243, 242, 240);">
                                                                <b>{{ $file }}</b>
                                                                <a href="{{ asset('upload/' . $file) }}"
                                                                    target="_blank"><i class="fa fa-eye text-primary"
                                                                        style="font-size:20px; margin-right:-10px;"></i></a>
                                                                <a type="button" class="remove-file"
                                                                    data-file-name="{{ $file }}"><i
                                                                        class="fa-solid fa-circle-xmark"
                                                                        style="color:red; font-size:20px;"></i></a>
                                                            </h6>
                                                        @endforeach
                                                    @endif

                                                </div>
                                                <div class="add-btn">
                                                    <div>Add</div>
                                                    <input {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}
                                                        type="file" id="lab_inv_attach" name="lab_inv_attach[]"
                                                        oninput="addMultipleFiles(this, 'lab_inv_attach')" multiple>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="button-block">
                                    <button type="submit" class="saveButton"
                                        {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>Save</button>
                                    <button type="button" class="backButton" onclick="previousStep()">Back</button>
                                    <button type="button" class="nextButton" onclick="nextStep()">Next</button>
                                    <button type="button"> <a class="text-white"
                                            href="{{ url('rcms/qms-dashboard') }}">
                                            Exit </a> </button>
                                </div>
                            </div>
                        </div> --}}

                        {{-- <div id="CCForm6" class="inner-block cctabcontent">
                            <div class="inner-block-content">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="group-input">
                                            <label for="qc_head_comments">QC Head Evaluation Comments</label>
                                            <textarea name="qc_head_comments" {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}></textarea>
                                        </div>
                                    </div> --}}
                                    {{-- <div class="col-12">
                                        <div class="group-input">
                                            <label for="inv_attach">Investigation Attachments</label>
                                            <input type="file" name="inv_attach"
                                                {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }} />
                                        </div>
                                    </div> --}}

                                    {{-- <div class="col-12">
                                        <div class="group-input">
                                            <label for="Inv Attachments">Investigation Attachments</label>
                                            <div class="file-attachment-field">
                                                <div disabled class="file-attachment-list" id="inv_attach">
                                                    @if ($data->inv_attach)
                                                        @foreach (json_decode($data->inv_attach) as $file)
                                                            <h6 type="button" class="file-container text-dark"
                                                                style="background-color: rgb(243, 242, 240);">
                                                                <b>{{ $file }}</b>
                                                                <a href="{{ asset('upload/' . $file) }}"
                                                                    target="_blank"><i class="fa fa-eye text-primary"
                                                                        style="font-size:20px; margin-right:-10px;"></i></a>
                                                                <a type="button" class="remove-file"
                                                                    data-file-name="{{ $file }}"><i
                                                                        class="fa-solid fa-circle-xmark"
                                                                        style="color:red; font-size:20px;"></i></a>
                                                            </h6>
                                                        @endforeach
                                                    @endif

                                                </div>
                                                <div class="add-btn">
                                                    <div>Add</div>
                                                    <input {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}
                                                        type="file" id="inv_attach" name="inv_attach[]"
                                                        oninput="addMultipleFiles(this, 'inv_attach')" multiple>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="button-block">
                                    <button type="submit" class="saveButton"
                                        {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>Save</button>
                                    <button type="button" class="backButton" onclick="previousStep()">Back</button>
                                    <button type="button" class="nextButton" onclick="nextStep()">Next</button>
                                    <button type="button"> <a class="text-white"
                                            href="{{ url('rcms/qms-dashboard') }}">
                                            Exit </a> </button>
                                </div>
                            </div>
                        </div> --}} 

                        <div id="CCForm7" class="inner-block cctabcontent">
                            <div class="inner-block-content">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="group-input">
                                            <label for="Acknowledge_By..">Acknowledge By</label>
                                            <div class="static">{{ $data->acknowledge_by }}</div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="group-input">
                                            <label for="Acknowledge_On">Acknowledge On</label>
                                            <div class="static">{{ $data->acknowledge_on }}</div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="group-input">
                                            <label for="Submit_By">Submit By</label>
                                            <div class="static">{{ $data->submit_by }}</div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="group-input">
                                            <label for="Submit_On">Submit On</label>
                                            <div class="static">{{ $data->submit_on }}</div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="group-input">
                                            <label for="QA_Review_Complete_By">QA Review Complete By</label>
                                            <div class="static">{{ $data->qa_review_complete_by }}</div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="group-input">
                                            <label for="QA_Review_Complete_On">QA Review Complete On</label>
                                            <div class="static">{{ $data->qa_review_complete_on }}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="button-block">
                                    <button type="submit" class="saveButton"
                                        {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>Save</button>
                                    <button type="button" class="backButton" onclick="previousStep()">Back</button>
                                    <button type="submit"
                                        {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>Submit</button>
                                    <button type="button"> <a class="text-white"
                                            href="{{ url('rcms/qms-dashboard') }}">
                                            Exit </a> </button>
                                </div>
                            </div>
                        </div>

                    </div>
                </form>

            </div>
        </div>

        <div class="modal fade" id="rejection-modal">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title">E-Signature11</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <form action="{{ route('root_reject', $data->id) }}" method="POST">
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
                        {{-- <div class="modal-footer">
                            <button type="submit" data-bs-dismiss="modal">Submit</button>
                            <button>Close</button>
                        </div> --}}
                        <div class="modal-footer">
                            <button type="submit">Submit</button>
                              <button type="button" data-bs-dismiss="modal">Close</button>
                            
                   </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal fade" id="cancel-modal">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title">E-Signature</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <form action="{{ route('root_Cancel', $data->id) }}" method="POST">
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
                            <button type="button" data-bs-dismiss="modal">Close</button>
                            {{-- <button>Close</button> --}}
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal fade" id="signature-modal">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title">E-Signature</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('root_send_stage', $data->id) }}" method="POST">
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
                            <button type="button" data-bs-dismiss="modal">Close</button>
                            {{-- <button>Close</button> --}}
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
            VirtualSelect.init({
                ele: '#investigators'
            });

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

                // Find the index of the clicked tab button
                const index = Array.from(cctablinks).findIndex(button => button === evt.currentTarget);

                // Update the currentStep to the index of the clicked tab
                currentStep = index;
            }

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

        <script>
            VirtualSelect.init({
                ele: '#departments, #team_members, #training-require, #impacted_objects'
            });
        </script>
    @endsection
