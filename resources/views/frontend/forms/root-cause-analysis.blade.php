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

    <div class="form-field-head">

        <div class="division-bar">
            <strong>Site Division/Project</strong> :
            {{ Helpers::getDivisionName(session()->get('division')) }} / Root Cause Analysis
        </div>
    </div>
    @php
        $users = DB::table('users')->get();
    @endphp

    {{-- ======================================
                    DATA FIELDS
    ======================================= --}}
    <div id="change-control-fields">
        <div class="container-fluid">

            <!-- Tab links -->
            <div class="cctab">
                <button class="cctablinks active" onclick="openCity(event, 'CCForm1')">General Information</button>
                <button class="cctablinks" onclick="openCity(event, 'CCForm2')">Chemical Analysis</button>
                <button class="cctablinks" onclick="openCity(event, 'CCForm3')">Water Analysis</button>
                <button class="cctablinks" onclick="openCity(event, 'CCForm4')">Environmental Monitoring</button>
                <button class="cctablinks" onclick="openCity(event, 'CCForm5')">Lab Investigation Remark</button>
                <button class="cctablinks" onclick="openCity(event, 'CCForm6')">QC Head/Designee Eval Comments</button>
                <button class="cctablinks" onclick="openCity(event, 'CCForm7')">Activity Log</button>
            </div>

            <form action="{{ route('root_store') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div id="step-form">

                    <div id="CCForm1" class="inner-block cctabcontent">
                        <div class="inner-block-content">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="RLS Record Number"><b>Record Number</b></label>
                                        <input disabled type="text" name="record_number"
                                            value="{{ Helpers::getDivisionName(session()->get('division')) }}/RCA/{{ date('Y') }}/{{ $record_number }}">
                                        {{-- <div class="static">QMS-EMEA/CAPA/{{ date('Y') }}/{{ $record_number }}</div> --}}
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Division Code"><b>Division Code</b></label>
                                        <input disabled type="text" name="division_code"
                                            value="{{ Helpers::getDivisionName(session()->get('division')) }}">
                                        <input type="hidden" name="division_id" value="{{ session()->get('division') }}">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Initiator"><b>Initiator</b></label>
                                        {{-- <div class="static">{{ Auth::user()->name }}</div> --}}
                                        <input disabled type="text" name="division_code"
                                            value="{{ Auth::user()->name }}">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Date Due"><b>Date of Initiation</b></label>
                                        <input disabled type="text" value="{{ date('d-M-Y') }}" name="intiation_date">
                                        <input type="hidden" value="{{ date('Y-m-d') }}" name="intiation_date">

                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="group-input">
                                        <label for="search">
                                            Assigned To <span class="text-danger"></span>
                                        </label>
                                        <select id="select-state" placeholder="Select..." name="assign_id">
                                            <option value="">Select a value</option>
                                            @foreach ($users as $data)
                                                <option value="{{ $data->id }}">{{ $data->name }}</option>
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
                                        <div><small class="text-primary">Please mention expected date of completion</small></div>
                                        {{-- <input type="hidden" value="{{ $due_date }}" name="due_date">
                                        <input disabled type="text" value="{{ Helpers::getdateFormat($due_date) }}"> --}}
                                        <input type="date" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"
                                            value="" name="due_date">


                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Initiator Group"><b>Initiator Group</b></label>
                                        <select name="initiatorGroup" id="initiator_group">
                                            <option value="">-- Select --</option>
                                            <option value="CQA" @if(old('initiatorGroup') =="CQA") selected @endif>Corporate Quality Assurance</option>
                                            <option value="QAB" @if(old('initiatorGroup') =="QAB") selected @endif>Quality Assurance Biopharma</option>
                                            <option value="CQC" @if(old('initiatorGroup') =="CQA") selected @endif>Central Quality Control</option>
                                            <option value="CQC" @if(old('initiatorGroup') =="CQC") selected @endif>Manufacturing</option>
                                            <option value="PSG" @if(old('initiatorGroup') =="PSG") selected @endif>Plasma Sourcing Group</option>
                                            <option value="CS"  @if(old('initiatorGroup') == "CS") selected @endif>Central Stores</option>
                                            <option value="ITG" @if(old('initiatorGroup') =="ITG") selected @endif>Information Technology Group</option>
                                            <option value="MM"  @if(old('initiatorGroup') == "MM") selected @endif>Molecular Medicine</option>
                                            <option value="CL"  @if(old('initiatorGroup') == "CL") selected @endif>Central Laboratory</option>

                                            <option value="TT"  @if(old('initiatorGroup') == "TT") selected @endif>Tech team</option>
                                            <option value="QA"  @if(old('initiatorGroup') == "QA") selected @endif> Quality Assurance</option>
                                            <option value="QM"  @if(old('initiatorGroup') == "QM") selected @endif>Quality Management</option>
                                            <option value="IA"  @if(old('initiatorGroup') == "IA") selected @endif>IT Administration</option>
                                            <option value="ACC"  @if(old('initiatorGroup') == "ACC") selected @endif>Accounting</option>
                                            <option value="LOG"  @if(old('initiatorGroup') == "LOG") selected @endif>Logistics</option>
                                            <option value="SM"  @if(old('initiatorGroup') == "SM") selected @endif>Senior Management</option>
                                            <option value="BA"  @if(old('initiatorGroup') == "BA") selected @endif>Business Administration</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Initiator Group Code">Initiator Group Code</label>
                                        <input type="text" name="initiator_group_code" id="initiator_group_code"
                                            value="" disabled>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="Short Desc.">Short Description <span
                                                class="text-danger">*</span></label>
                                                <div><small class="text-primary">Please mention brief summary</small></div>
                                        <textarea name="short_description"></textarea>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Sample_Type">Sample Type</label>
                                        <select name="Sample_Types">
                                            <option value="">-- Select --</option>
                                            <option value="Demo">Demo 1</option>
                                            <option value="Demo">Demo 2</option>
                                            <option value="Demo">Demo 3</option>
                                            <option value="Demo">Demo 4</option>
                                            <option value="Demo">Demo 5</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="group-input">
                                        <label for="test_lab">Test Lab</label>
                                        <input type="text" name="test_lab" />
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="ten_trend">Trend of Previous Ten Results</label>
                                        <textarea name="ten_trend"></textarea>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="investigators">Investigators</label>
                                        <select multiple name="investigators[]" placeholder="Select Investigators"
                                            data-search="false" data-silent-initial-value-set="true" id="investigators">
                                            <option value="1">Amit Guru</option>
                                            <option value="2">Amit Patel</option>
                                            <option value="3">Shaleen Mishra</option>
                                            <option value="4">Anshul Patel</option>
                                            <option value="5">Vikas Prajapati</option>
                                        </select>
                                    </div>
                                </div>
                                {{-- <div class="col-12">
                                    <div class="group-input">
                                        <label for="attachments">Attachments</label>
                                        <input type="file" name="attachments" />
                                    </div>
                                </div> --}}

                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="Audit Attachments">Attachments</label>
                                        <div><small class="text-primary">Please Attach all relevant or supporting documents</small></div>
                                        <div class="file-attachment-field">
                                            <div class="file-attachment-list" id="attachments"></div>
                                            <div class="add-btn">
                                                <div>Add</div>
                                                <input type="file" id="myfile" name="attachments[]"
                                                    oninput="addMultipleFiles(this, 'attachments')" multiple>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="comments">Comments</label>
                                        <textarea name="comments"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="button-block">
                                <button type="submit" id="ChangesaveButton" class="saveButton">Save</button>
                                <button type="button" id="ChangeNextButton" class="nextButton">Next</button>
                                <button type="button"> <a class="text-white"> Exit </a> </button>
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
                                        id="Chemical-Analysis1">+</button>
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
                                        <tr>
                                            <td><input type="text" name="serial_number[]" value="1"></td>
                                            <td><input type="text" name="questions[]"></td>
                                            <td><input type="text" name="response[]"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="sub-head">
                                Chemical Analysis II
                            </div>
                            <div class="group-input">
                                <label for="review_raw_data">
                                    Review of Raw Data<button type="button" name="ann"
                                        id="Chemical-Analysis2">+</button>
                                </label>
                                <table class="table table-bordered" id="review_analyst_knowledge2">
                                    <thead>
                                        <tr>
                                            <th>Row #</th>
                                            <th>Question</th>
                                            <th>Response</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><input type="text" name="serial_number2[]" value="1"></td>
                                            <td><input type="text" name="questions2[]"></td>
                                            <td><input type="text" name="response2[]"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="sub-head">
                                Chemical Analysis III
                            </div>
                            <div class="group-input">
                                <label for="review_sampling_storage">
                                    Review of Sampling and Storage<button type="button" name="ann"
                                        id="Chemical-Analysis3">+</button>
                                </label>
                                <table class="table table-bordered" id="review_analyst_knowledge3">
                                    <thead>
                                        <tr>
                                            <th>Row #</th>
                                            <th>Question</th>
                                            <th>Response</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><input type="text" name="serial_number3[]" value="1"></td>
                                            <td><input type="text" name="questions3[]"></td>
                                            <td><input type="text" name="response3[]"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="sub-head">
                                Chemical Analysis IV
                            </div>
                            <div class="group-input">
                                <label for="instrument_performance">
                                    Instrument Performance<button type="button" name="ann"
                                        id="Chemical-Analysis4">+</button>
                                </label>
                                <table class="table table-bordered" id="review_analyst_knowledge4">
                                    <thead>
                                        <tr>
                                            <th>Row #</th>
                                            <th>Question</th>
                                            <th>Response</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><input type="text" name="serial_number4[]" value="1"></td>
                                            <td><input type="text" name="questions4[]"></td>
                                            <td><input type="text" name="response4[]"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="button-block">
                                <button type="submit" class="saveButton">Save</button>
                                <button type="button" class="backButton" onclick="previousStep()">Back</button>
                                <button type="button" class="nextButton" onclick="nextStep()">Next</button>
                                <button type="button"> <a class="text-white"> Exit </a> </button>
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
                                        id="Chemical-Analysis5">+</button>
                                </label>
                                <table class="table table-bordered" id="review_analyst_knowledge5">
                                    <thead>
                                        <tr>
                                            <th>Row #</th>
                                            <th>Question</th>
                                            <th>Response</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><input type="text" name="serial_number5[]" value="1"></td>
                                            <td><input type="text" name="questions5[]"></td>
                                            <td><input type="text" name="response5[]"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="sub-head">
                                Water Analysis II
                            </div>
                            <div class="group-input">
                                <label for="review_instruments">
                                    Review of Instuments<button type="button" name="ann"
                                        id="Chemical-Analysis6">+</button>
                                </label>
                                <table class="table table-bordered" id="review_analyst_knowledge6">
                                    <thead>
                                        <tr>
                                            <th>Row #</th>
                                            <th>Question</th>
                                            <th>Response</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><input type="text" name="serial_number6[]" value="1"></td>
                                            <td><input type="text" name="questions6[]"></td>
                                            <td><input type="text" name="response6[]"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="sub-head">
                                Water Analysis III
                            </div>
                            <div class="group-input">
                                <label for="water_plant_checklist">
                                    Water Plant Checklist<button type="button" name="ann"
                                        id="Chemical-Analysis7">+</button>
                                </label>
                                <table class="table table-bordered" id="review_analyst_knowledge7">
                                    <thead>
                                        <tr>
                                            <th>Row #</th>
                                            <th>Question</th>
                                            <th>Response</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><input type="text" name="serial_number7[]" value="1"></td>
                                            <td><input type="text" name="questions7[]"></td>
                                            <td><input type="text" name="response7[]"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="sub-head">
                                Water Analysis IV
                            </div>
                            <div class="group-input">
                                <label for="sample_testing_checklist">
                                    Sample Testing Checklist<button type="button" name="ann"
                                        id="Chemical-Analysis8">+</button>
                                </label>
                                <table class="table table-bordered" id="review_analyst_knowledge8">
                                    <thead>
                                        <tr>
                                            <th>Row #</th>
                                            <th>Question</th>
                                            <th>Response</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><input type="text" name="serial_number8[]" value="1"></td>
                                            <td><input type="text" name="questions8[]"></td>
                                            <td><input type="text" name="response8[]"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="button-block">
                                <button type="submit" class="saveButton">Save</button>
                                <button type="button" class="backButton" onclick="previousStep()">Back</button>
                                <button type="button" class="nextButton" onclick="nextStep()">Next</button>
                                <button type="button"> <a class="text-white"> Exit </a> </button>
                            </div>
                        </div>
                    </div>

                    <div id="CCForm4" class="inner-block cctabcontent">
                        <div class="inner-block-content">
                            <div class="sub-head">
                                Environmental Monitoring I
                            </div>
                            <div class="group-input">
                                <label for="environment_monitoring_results">
                                    Environment Monitoring Results<button type="button" name="ann"
                                        id="Chemical-Analysis9">+</button>
                                </label>
                                <table class="table table-bordered" id="review_analyst_knowledge9">
                                    <thead>
                                        <tr>
                                            <th>Row #</th>
                                            <th>Question</th>
                                            <th>Response</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><input type="text" name="serial_number9[]" value="1"></td>
                                            <td><input type="text" name="questions9[]"></td>
                                            <td><input type="text" name="response9[]"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="sub-head">
                                Environmental Monitoring II
                            </div>
                            <div class="group-input">
                                <label for="instrument_calibration_result">
                                    Intrument Calibration Results<button type="button" name="ann"
                                        id="Chemical-Analysis10">+</button>
                                </label>
                                <table class="table table-bordered" id="review_analyst_knowledge10">
                                    <thead>
                                        <tr>
                                            <th>Row #</th>
                                            <th>Question</th>
                                            <th>Response</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><input type="text" name="serial_number10[]" value="1"></td>
                                            <td><input type="text" name="questions10[]"></td>
                                            <td><input type="text" name="response10[]"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="sub-head">
                                Environmental Monitoring III
                            </div>
                            <div class="group-input">
                                <label for="review_storage_plate">
                                    Review of Storage condition of Plate<button type="button" name="ann"
                                        id="Chemical-Analysis11">+</button>
                                </label>
                                <table class="table table-bordered" id="review_analyst_knowledge11">
                                    <thead>
                                        <tr>
                                            <th>Row #</th>
                                            <th>Question</th>
                                            <th>Response</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><input type="text" name="serial_number11[]" value="1"></td>
                                            <td><input type="text" name="questions11[]"></td>
                                            <td><input type="text" name="response11[]"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="sub-head">
                                Environmental Monitoring IV
                            </div>
                            <div class="group-input">
                                <label for="review_media_lot">
                                    Review of Media Lot<button type="button" name="ann"
                                        id="Chemical-Analysis12">+</button>
                                </label>
                                <table class="table table-bordered" id="review_analyst_knowledge12">
                                    <thead>
                                        <tr>
                                            <th>Row #</th>
                                            <th>Question</th>
                                            <th>Response</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><input type="text" name="serial_number12[]" value="1"></td>
                                            <td><input type="text" name="questions12[]"></td>
                                            <td><input type="text" name="response12[]"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="sub-head">
                                Environmental Monitoring V
                            </div>
                            <div class="group-input">
                                <label for="environment_sampling">
                                    Sampling<button type="button" name="ann" id="Chemical-Analysis13">+</button>
                                </label>
                                <table class="table table-bordered" id="review_analyst_knowledge13">
                                    <thead>
                                        <tr>
                                            <th>Row #</th>
                                            <th>Question</th>
                                            <th>Response</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><input type="text" name="serial_number13[]" value="1"></td>
                                            <td><input type="text" name="questions13[]"></td>
                                            <td><input type="text" name="response13[]"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="sub-head">
                                Environmental Monitoring VI
                            </div>
                            <div class="group-input">
                                <label for="airborne_contamination">
                                    Airborne Contamination<button type="button" name="ann"
                                        id="Chemical-Analysis14">+</button>
                                </label>
                                <table class="table table-bordered" id="review_analyst_knowledge14">
                                    <thead>
                                        <tr>
                                            <th>Row #</th>
                                            <th>Question</th>
                                            <th>Response</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><input type="text" name="serial_number14[]" value="1"></td>
                                            <td><input type="text" name="questions14[]"></td>
                                            <td><input type="text" name="response14[]"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="button-block">
                                <button type="submit" class="saveButton">Save</button>
                                <button type="button" class="backButton" onclick="previousStep()">Back</button>
                                <button type="button" class="nextButton" onclick="nextStep()">Next</button>
                                <button type="button"> <a class="text-white"> Exit </a> </button>
                            </div>
                        </div>
                    </div>

                    <div id="CCForm5" class="inner-block cctabcontent">
                        <div class="inner-block-content">
                            <div class="row">
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="lab_inv_concl">Lab Investigator Conclusion</label>
                                        <textarea name="lab_inv_concl"></textarea>
                                    </div>
                                </div>
                                {{-- <div class="col-12">
                                    <div class="group-input">
                                        <label for="lab_inv_attach">Lab Investigator Attachments</label>
                                        <input type="file" name="lab_inv_attach" />
                                    </div>
                                </div> --}}

                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="Audit Attachments">Lab Investigator Attachments</label>
                                        <div class="file-attachment-field">
                                            <div class="file-attachment-list" id="lab_inv_attach"></div>
                                            <div class="add-btn">
                                                <div>Add</div>
                                                <input type="file" id="myfile" name="lab_inv_attach[]"
                                                    oninput="addMultipleFiles(this, 'lab_inv_attach')" multiple>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="button-block">
                                <button type="submit" class="saveButton">Save</button>
                                <button type="button" class="backButton" onclick="previousStep()">Back</button>
                                <button type="button" class="nextButton" onclick="nextStep()">Next</button>
                                <button type="button"> <a class="text-white"> Exit </a> </button>
                            </div>
                        </div>
                    </div>

                    <div id="CCForm6" class="inner-block cctabcontent">
                        <div class="inner-block-content">
                            <div class="row">
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="qc_head_comments">QC Head Evaluation Comments</label>
                                        <textarea name="qc_head_comments"></textarea>
                                    </div>
                                </div>
                                {{-- <div class="col-12">
                                    <div class="group-input">
                                        <label for="inv_attach">Investigation Attachments</label>
                                        <input type="file" name="inv_attach" />
                                    </div>
                                </div> --}}

                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="Audit Attachments">Investigation Attachments</label>
                                        <div class="file-attachment-field">
                                            <div class="file-attachment-list" id="inv_attach"></div>
                                            <div class="add-btn">
                                                <div>Add</div>
                                                <input type="file" id="myfile" name="inv_attach[]"
                                                    oninput="addMultipleFiles(this, 'inv_attach')" multiple>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="button-block">
                                <button type="submit" class="saveButton">Save</button>
                                <button type="button" class="backButton" onclick="previousStep()">Back</button>
                                <button type="button" class="nextButton" onclick="nextStep()">Next</button>
                                <button type="button"> <a class="text-white"> Exit </a> </button>
                            </div>
                        </div>
                    </div>

                    <div id="CCForm7" class="inner-block cctabcontent">
                        <div class="inner-block-content">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Submitted_By..">Submitted By..</label>
                                        {{-- <div class="static">person data field</div> --}}
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Submitted_On">Submitted On</label>
                                        {{-- <div class="static">17-04-2023 11:12PM</div> --}}
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Report_Result_By">Report Result By</label>
                                        {{-- <div class="static">person data field</div> --}}
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Report_Result_On">Report Result On</label>
                                        {{-- <div class="static">17-04-2023 11:12PM</div> --}}
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Evaluation_Complete_By">Evaluation Complete By</label>
                                        {{-- <div class="static">person data field</div> --}}
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Evaluation_Complete_On">Evaluation Complete On</label>
                                        {{-- <div class="static">17-04-2023 11:12PM</div> --}}
                                    </div>
                                </div>
                            </div>
                            <div class="button-block">
                                <button type="submit" class="saveButton">Save</button>
                                <button type="button" class="backButton" onclick="previousStep()">Back</button>
                                <button type="submit">Submit</button>
                                <button type="button"> <a class="text-white"> Exit </a> </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

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
    <script>
        // JavaScript
        document.getElementById('initiator_group').addEventListener('change', function() {
            var selectedValue = this.value;
            document.getElementById('initiator_group_code').value = selectedValue;
        });
    </script>
@endsection
