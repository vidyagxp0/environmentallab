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
            {{ Helpers::getDivisionName(session()->get('division')) }} / Lab Incident
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
                <button class="cctablinks" onclick="openCity(event, 'CCForm2')">Incident Details</button>
                <button class="cctablinks" onclick="openCity(event, 'CCForm3')">Investigation Details</button>
                <button class="cctablinks" onclick="openCity(event, 'CCForm4')">CAPA</button>
                <button class="cctablinks" onclick="openCity(event, 'CCForm5')">QA Review</button>
                <button class="cctablinks" onclick="openCity(event, 'CCForm6')">QA Head/Designee Approval</button>
                <button class="cctablinks" onclick="openCity(event, 'CCForm7')">Activity Log</button>
            </div>

              <script>
                $(document).ready(function() {

                    $('#Mainform').on('submit', function(e) {
                        $('.on-submit-disable-button').prop('disabled', true);
                    });
                })
            </script>

            <form id="Mainform" action="{{ route('labIncidentCreate') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div id="step-form">

                    <!-- General information content -->
                    <div id="CCForm1" class="inner-block cctabcontent">
                        <div class="inner-block-content">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="RLS Record Number"><b>Record Number</b></label>
                                        @if(!empty($parent_id))
                                        <input disabled type="text" name="record_number"
                                        value="{{ Helpers::getDivisionName($parent_division_id) }}/AI/{{ date('Y') }}/{{ $record_number }}">
                                        @else
                                        <input disabled type="text" name="record_number"
                                            value="{{ Helpers::getDivisionName(session()->get('division')) }}/AI/{{ date('Y') }}/{{ $record_number }}">
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Division Code"><b>Site/Location Code</b></label>
                                        @if(!empty($parent_id))
                                            <input readonly type="text" value="{{ Helpers::getDivisionName($parent_division_id) }}">
                                            <input type="hidden" name="division_id" value="{{ $parent_division_id }}">
                                        @else
                                            <input readonly type="text" name="division_code"
                                                value="{{ Helpers::getDivisionName(session()->get('division')) }}">
                                            <input type="hidden" name="division_id" value="{{ session()->get('division') }}">
                                        @endif
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
                                        <!-- {{-- <div class="static">{{ date('d-M-Y') }}</div> --}} -->
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="group-input">
                                        <label for="search">
                                            Assigned To <span class="text-danger"></span>
                                        </label>
                                        <select id="select-state" placeholder="Select..." name="assign_to">
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

                                <div class="col-lg-6 new-date-data-field">
                                    <div class="group-input input-date">
                                        <label for="Date Due">Due Date</label>
                                        <div><small class="text-primary">If revising Due Date, kindly mention revision reason in "Due Date Extension Justification" data field.</small>
                                        </div>
                                        <div class="calenderauditee">
                                            <input type="text" id="due_date" readonly
                                                placeholder="DD-MMM-YYYY"  value="{{ Helpers::getDueDatemonthly(null, false, 'd-M-Y') }}"  />
                                            <input type="date" name="due_date" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" class="hide-input"
                                                oninput="handleDateInput(this, 'due_date')" value="{{ Helpers::getDueDatemonthly(null, false, 'Y-m-d') ?? '' }}" />
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Initiator Group"><b>Initiator Group</b></label>
                                        <select name="Initiator_Group" id="initiator_group">
                                            <option value="">-- Select --</option>
                                            <option value="CQA" @if(old('Initiator_Group') =="CQA") selected @endif>Corporate Quality Assurance</option>
                                            <option value="QAB" @if(old('Initiator_Group') =="QAB") selected @endif>Quality Assurance Biopharma</option>
                                            <option value="CQC" @if(old('Initiator_Group') =="CQC") selected @endif>Central Quality Control</option>
                                            <option value="MANU" @if(old('Initiator_Group') =="MANU") selected @endif>Manufacturing</option>
                                            <option value="PSG" @if(old('Initiator_Group') =="PSG") selected @endif>Plasma Sourcing Group</option>
                                            <option value="CS"  @if(old('Initiator_Group') == "CS") selected @endif>Central Stores</option>
                                            <option value="ITG" @if(old('Initiator_Group') =="ITG") selected @endif>Information Technology Group</option>
                                            <option value="MM"  @if(old('Initiator_Group') == "MM") selected @endif>Molecular Medicine</option>
                                            <option value="CL"  @if(old('Initiator_Group') == "CL") selected @endif>Central Laboratory</option>

                                            <option value="TT"  @if(old('Initiator_Group') == "TT") selected @endif>Tech team</option>
                                            <option value="QA"  @if(old('Initiator_Group') == "QA") selected @endif> Quality Assurance</option>
                                            <option value="QM"  @if(old('Initiator_Group') == "QM") selected @endif>Quality Management</option>
                                            <option value="IA"  @if(old('Initiator_Group') == "IA") selected @endif>IT Administration</option>
                                            <option value="ACC"  @if(old('Initiator_Group') == "ACC") selected @endif>Accounting</option>
                                            <option value="LOG"  @if(old('Initiator_Group') == "LOG") selected @endif>Logistics</option>
                                            <option value="SM"  @if(old('Initiator_Group') == "SM") selected @endif>Senior Management</option>
                                            <option value="BA"  @if(old('Initiator_Group') == "BA") selected @endif>Business Administration</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Initiator Group Code">Initiator Group Code</label>
                                        <input type="text" name="initiator_group_code" id="initiator_group_code" value="" readonly>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="Short Description">Short Description<span
                                                class="text-danger">*</span></label><span id="rchars">255</span>
                                        characters remaining
                                        <input id="docname" type="text" name="short_desc" maxlength="255" required>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="severity-level">Severity Level</label>
                                        <span class="text-primary">Severity levels in a QMS record gauge issue seriousness, guiding priority for corrective actions. Ranging from low to high, they ensure quality standards and mitigate critical risks.</span>
                                        <select name="severity_level2">
                                            <option value="">-- Select --</option>
                                            <option value="minor">Minor</option>
                                            <option value="major">Major</option>
                                            <option value="critical">Critical</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Initiator Group">Initiated Through</label>
                                        <div><small class="text-primary">Please select related information</small></div>
                                        <select name="initiated_through"
                                            onchange="otherController(this.value, 'others', 'initiated_through_req')">
                                            <option value="">Enter Your Selection Here</option>
                                            <option value="recall">Recall</option>
                                            <option value="return">Return</option>
                                            <option value="deviation">Deviation</option>
                                            <option value="complaint">Complaint</option>
                                            <option value="regulatory">Regulatory</option>
                                            <option value="lab-incident">Lab Incident</option>
                                            <option value="improvement">Improvement</option>
                                            <option value="OOS">OOS</option>
                                            <option value="others">Others</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input" id="initiated_through_req">
                                        <label for="initiated_through">Others<span
                                                class="text-danger d-none">*</span></label>
                                        <textarea name="initiated_through_req"></textarea>
                                    </div>
                                </div>
                                <!-- <div class="col-lg-6">
                                            <div class="group-input" id="initiated_through_req">
                                                <label for="If Other">Others<span
                                                        class="text-danger d-none">*</span></label>
                                                {{-- <textarea {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }} name="initiated_if_other">{{$data->initiated_if_other}}</textarea> --}}
                                            </div>
                                        </div> -->
                                {{-- <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Date of Occurance">Date of Occurance</label>
                                        <input type="date" name="occurance_date">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="
                                        ">Due Date</label>
                                        <input type="hidden" value="{{ $due_date }}" name="due_date">
                                        <div class="static"> {{ $due_date }}</div>
                                    </div>
                                </div> --}}
                                {{-- <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Assigned to">Assigned to</label>
                                        <select name="assigend">
                                            @foreach ($users as $data)
                                                <option value="{{ $data->id }}">{{ $data->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div> --}}
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Incident Category">Incident Category</label>
                                        <select name="Incident_Category">
                                            <option value="">Enter Your Selection Here</option>
                                            <option value="Microbiological">Microbiological</option>
                                            <option value="Chemical">Chemical</option>
                                            <option value="Deviation">Deviation</option>
                                            <option value="Work Injuries">Work Injuries</option>
                                            <option value="Others">Others</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input" id="Incident_Category_others">
                                        <label for="Incident_Category">Others<span
                                                class="text-danger d-none">*</span></label>
                                        <textarea name="Incident_Category_others"></textarea>
                                    </div>
                                </div>
                                 <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Invocation Type">Invocation Type</label>
                                        <select name="Invocation_Type">
                                            <option value="">Enter Your Selection Here</option>
                                            <option value="NA">NA</option>
                                            <option value="1">1</option>
                                            <option value="2">2</option>
                                            <option value="3">3</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="group-input">
                                        <label for="Initial Attachments">Initial Attachment</label>
                                        <div><small class="text-primary">Please Attach all relevant or supporting documents</small></div>
                                        {{-- <input type="file" id="myfile" name="Initial_Attachment"> --}}
                                        <div class="file-attachment-field">
                                            <div class="file-attachment-list" id="Initial_Attachment"></div>
                                            <div class="add-btn">
                                                <div>Add</div>
                                                <input type="file" id="myfile" name="Initial_Attachment[]"
                                                    oninput="addMultipleFiles(this, 'Initial_Attachment')" multiple>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="button-block">
                                <button type="submit" id="ChangesaveButton" class="saveButton on-submit-disable-button">Save</button>
                                <button type="button" id="ChangeNextButton" class="nextButton">Next</button>
                                <button type="button"> <a href="{{ url('rcms/qms-dashboard') }}" class="text-white"> Exit </a> </button>
                            </div>
                        </div>
                    </div>

                    <!-- Incident Details content -->
                    <div id="CCForm2" class="inner-block cctabcontent">
                        <div class="inner-block-content">
                            <div class="row">
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="Incident Details">Incident Details</label>
                                        <textarea name="Incident_Details"></textarea>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="Document Details ">Document Details</label>
                                        <textarea name="Document_Details"></textarea>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="Instrument Details">Instrument Details</label>
                                        <textarea name="Instrument_Details"></textarea>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="Involved Personnel">Involved Personnel</label>
                                        <textarea name="Involved_Personnel"></textarea>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="Product Details,If Any">Product Details,If Any</label>
                                        <textarea name="Product_Details"></textarea>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="Supervisor Review Comments">Supervisor Review Comments</label>
                                        <textarea name="Supervisor_Review_Comments"></textarea>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="Attachments">Incident Attachments</label>
                                        <div><small class="text-primary">Please Attach all relevant or supporting documents</small></div>
                                        {{-- <input type="file" id="myfile" name="Attachments"> --}}
                                        <div class="file-attachment-field">
                                            <div class="file-attachment-list" id="Attachments"></div>
                                            <div class="add-btn">
                                                <div>Add</div>
                                                <input type="file" id="myfile" name="Attachments[]"
                                                    oninput="addMultipleFiles(this, 'Attachments')" multiple>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- <div class="col-12 sub-head">
                                    Cancelation
                                </div>
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="Cancelation Remarks">Cancelation Remarks</label>
                                        <textarea name="Cancelation_Remarks"></textarea>
                                    </div>
                                </div> --}}
                            </div>
                            <div class="button-block">
                                <button type="submit" class="saveButton on-submit-disable-button">Save</button>
                                <button type="button" class="backButton" onclick="previousStep()">Back</button>
                                <button type="button" class="nextButton" onclick="nextStep()">Next</button>
                                <button type="button"> <a href="{{ url('rcms/qms-dashboard') }}" class="text-white"> Exit </a> </button>
                            </div>
                        </div>
                    </div>

                    <!-- Investigation Details content -->
                    <div id="CCForm3" class="inner-block cctabcontent">
                        <div class="inner-block-content">
                            <div class="row">
                                {{-- <div class="col-12 sub-head">
                                    Questionnaire
                                </div> --}}
                                {{-- <div class="col-12">
                                    <div class="group-input">
                                        <label for="INV Questionnaire">INV Questionnaire</label>
                                        <div class="static">Question datafield</div>
                                    </div>
                                </div> --}}
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="Inv Attachments">Investigation Attachment</label>
                                        <div><small class="text-primary">Please Attach all relevant or supporting documents</small></div>
                                        {{-- <input type="file" id="myfile" name="Inv_Attachment"> --}}
                                        <div class="file-attachment-field">
                                            <div class="file-attachment-list" id="Inv_Attachment"></div>
                                            <div class="add-btn">
                                                <div>Add</div>
                                                <input type="file" id="myfile" name="Inv_Attachment[]"
                                                    oninput="addMultipleFiles(this, 'Inv_Attachment')" multiple>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="Investigation Details ">Investigation Details</label>
                                        <textarea name="Investigation_Details"></textarea>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="Action Taken">Action Taken</label>
                                        <textarea name="Action_Taken"></textarea>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="Root Cause">Root Cause</label>
                                        <textarea name="Root_Cause"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="button-block">
                                <button type="submit" class="saveButton on-submit-disable-button">Save</button>
                                <button type="button" class="backButton" onclick="previousStep()">Back</button>
                                <button type="button" class="nextButton" onclick="nextStep()">Next</button>
                                <button type="button"> <a href="{{ url('rcms/qms-dashboard') }}" class="text-white"> Exit </a> </button>
                            </div>
                        </div>
                    </div>

                    <!-- CAPA content -->
                    <div id="CCForm4" class="inner-block cctabcontent">
                        <div class="inner-block-content">
                            <div class="row">
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="Currective Action">Corrective Action</label>
                                        <textarea name="Currective_Action"></textarea>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="Preventive Action">Preventive Action</label>
                                        <textarea name="Preventive_Action"></textarea>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="Corrective & Preventive Action">Corrective & Preventive Action</label>
                                        <textarea name="Corrective_Preventive_Action"></textarea>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="CAPA Attachments">CAPA Attachment</label>
                                        <div><small class="text-primary">Please Attach all relevant or supporting documents</small></div>
                                        {{-- <input type="file" id="myfile" name="CAPA_Attachment"> --}}
                                        <div class="file-attachment-field">
                                            <div class="file-attachment-list" id="CAPA_Attachment"></div>
                                            <div class="add-btn">
                                                <div>Add</div>
                                                <input type="file" id="myfile" name="CAPA_Attachment[]"
                                                    oninput="addMultipleFiles(this, 'CAPA_Attachment')" multiple>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="button-block">
                                <button type="submit" class="saveButton on-submit-disable-button">Save</button>
                                <button type="button" class="backButton" onclick="previousStep()">Back</button>
                                <button type="button" class="nextButton" onclick="nextStep()">Next</button>
                                <button type="button"> <a href="{{ url('rcms/qms-dashboard') }}" class="text-white"> Exit </a> </button>
                            </div>
                        </div>
                    </div>

                    <!-- QA Review content -->
                    <div id="CCForm5" class="inner-block cctabcontent">
                        <div class="inner-block-content">
                            <div class="row">
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="QA Review Comments">QA Review Comments</label>
                                        <textarea name="QA_Review_Comments"></textarea>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="QA Head Attachments">QA Review Attachment</label>
                                        <div><small class="text-primary">Please Attach all relevant or supporting documents</small></div>
                                        {{-- <input type="file" id="myfile" name="QA_Head_Attachment"> --}}
                                        <div class="file-attachment-field">
                                            <div class="file-attachment-list" id="QA_Head_Attachment"></div>
                                            <div class="add-btn">
                                                <div>Add</div>
                                                <input type="file" id="myfile" name="QA_Head_Attachment[]"
                                                    oninput="addMultipleFiles(this, 'QA_Head_Attachment')" multiple>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="button-block">
                                <button type="submit" class="saveButton on-submit-disable-button">Save</button>
                                <button type="button" class="backButton" onclick="previousStep()">Back</button>
                                <button type="button" class="nextButton" onclick="nextStep()">Next</button>
                                <button type="button"> <a href="{{ url('rcms/qms-dashboard') }}" class="text-white"> Exit </a> </button>
                            </div>
                        </div>
                    </div>

                    <!-- QA Head/Designee Approval content -->
                    <div id="CCForm6" class="inner-block cctabcontent">
                        <div class="inner-block-content">
                            <div class="row">
                                <div class="col-12 sub-head">
                                    Closure
                                </div>
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="QA Head/Designee Comments">QA Head/Designee Comments</label>
                                        <textarea name="QA_Head"></textarea>
                                    </div>
                                </div>
                                <!-- <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Effectiveness Check required?">Effectiveness Check required?</label>
                                        <select name="Effectiveness_Check">
                                            <option value="">Enter Your Selection Here</option>
                                            <option value="yes">Yes</option>
                                            <option value="no">No</option>
                                        </select>
                                    </div>
                                </div>
                                {{-- <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Effect.Chesk Creation Date">Effect.Chesk Creation Date</label>
                                        <input type="date" name="effect_check_date">
                                    </div>
                                </div> --}}
                                <div class="col-lg-6 new-date-data-field">
                                    <div class="group-input input-date">
                                        <label for="Date Due">Effectiveness Check Creation Date</label>
                                        <div class="calenderauditee">
                                            <input type="text" id="effectivess_check_creation_date" readonly
                                                placeholder="DD-MMM-YYYY" />
                                            <input type="date" name="effectivess_check_creation_date" class="hide-input"
                                                oninput="handleDateInput(this, 'effectivess_check_creation_date')" />
                                        </div>
                                    </div>
                                </div> -->
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="Incident Type">Incident Type
                                            <span class="text-primary" data-bs-toggle="modal"
                                                data-bs-target="#lab-incident-type-of-change-instruction-modal"
                                                style="font-size: 0.8rem; font-weight: 400; cursor:pointer;">
                                                (Launch Instruction)
                                            </span>
                                        </label>
                                        <select name="Incident_Type">
                                            <option value="">Enter Your Selection Here</option>
                                            <option value="Type A">Type A</option>
                                            <option value="Type B">Type B</option>
                                            <option value="Type C">Type C</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="Conclusion">Conclusion</label>
                                        <textarea name="Conclusion"></textarea>
                                    </div>
                                </div>
                                <div class="col-12 sub-head">
                                    Extension Justification
                                </div>

                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="due_date_extension">Due Date Extension Justification</label>
                                        <div><small class="text-primary">Please Mention justification if due date is crossed</small></div>
                                         <span id="rchar">240</span> characters remaining
                                        <textarea id="duedoc" maxlength="240" name="due_date_extension" type="text"></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="button-block">
                                <button type="submit" class="saveButton on-submit-disable-button">Save</button>
                                <button type="button" class="backButton" onclick="previousStep()">Back</button>
                                <button type="button" class="nextButton" onclick="nextStep()">Next</button>
                                <button type="button"> <a href="{{ url('rcms/qms-dashboard') }}" class="text-white"> Exit </a> </button>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="lab-incident-type-of-change-instruction-modal">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">

                                <div class="modal-header">
                                    <h4 class="modal-title">Instructions</h4>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>

                                <div class="modal-body">
                                    <h4>A. Equipment Malfunction or Failure:</h4>
                                    <p>
                                        This type might involve incidents related to the malfunction, failure, or breakdown of laboratory equipment or instruments. It could include situations where equipment doesn't perform as expected or required.
                                    </p>


                                    <h4>B. Procedural Error or Deviation:</h4>
                                    <p>
                                        This type could encompass incidents resulting from errors or deviations in standard operating procedures (SOPs), protocols, or methods. It might involve mistakes made during testing, analysis, or other laboratory proced.
                                    </p>
                                    <h4>c. Environmental or Contamination Issue:</h4>
                                    <p>

                                        This type might involve incidents related to environmental conditions or contamination that affect laboratory processes, results, or safety. It could include situations such as cross-contamination of samples, exposure to environmental factors like humidity or temperature fluctuations beyond acceptable ranges, or contamination due to inadequate cleaning or maintenance of laboratory spaces.
                                    </p>

                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- Activity Log content -->
                    <div id="CCForm7" class="inner-block cctabcontent">
                        <div class="inner-block-content">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Submitted By">Submitted By</label>
                                        <div class="static"></div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Submitted On">Submitted On</label>
                                        <div class="Date"></div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Incident Review Completed By">Incident Review Completed By</label>
                                        <div class="static"></div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Incident Review Completed On">Incident Review Completed On</label>
                                        <div class="Date"></div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Investigation Completed By">Investigation Completed By</label>
                                        <div class="static"></div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Investigation Completed On">Investigation Completed On</label>
                                        <div class="Date"></div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Investigation Completed By">All Activities Completed By</label>
                                        <div class="static"></div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="All Activities Completed On">All Activities Completed On</label>
                                        <div class="Date"></div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Review Completed By">Review Completed By</label>
                                        <div class="static"></div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Review Completed On">Review Completed On</label>
                                        <div class="Date"></div>
                                    </div>
                                </div>
                               <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="QA Review Completed By">QA Review Completed By</label>
                                        <div class="static"></div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="QA Review Completed By">QA Review Completed On</label>
                                        <div class="Date"></div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="QA Head Approval Completed By">QA Head Approval Completed By</label>
                                        <div class="static"></div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="QA Head Approval Completed On">QA Head Approval Completed On</label>
                                        <div class="Date"></div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Cancelled By">Cancelled By</label>
                                        <div class="static"></div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Cancelled On">Cancelled On</label>
                                        <div class="Date"></div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Cancelled By">Request More Info By</label>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Cancelled By">Request More Info On</label>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Cancelled By">Request More Info By</label>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Cancelled By">Request More Info On</label>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Cancelled By">Further Investigation Required By</label>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Cancelled By">Further Investigation Required On</label>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Cancelled By">Return To Pending CAPA By</label>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Cancelled By">Return To Pending CAPA On</label>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Cancelled By">Return to QA Review By</label>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Cancelled By">Return to QA Review On</label>
                                    </div>
                                </div>


                                <div class="button-block">
                                <button type="submit" class="saveButton on-submit-disable-button">Save</button>
                                <button type="button" class="backButton" onclick="previousStep()">Back</button>
                                <button type="submit">Submit</button>
                                <button type="button"> <a href="{{ url('rcms/qms-dashboard') }}" class="text-white" href="#"> Exit </a></button>
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
            ele: '#Facility, #Group, #Audit, #Auditee'
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
    document.getElementById('initiator_group').addEventListener('change', function() {
        var selectedValue = this.value;
        document.getElementById('initiator_group_code').value = selectedValue;
    });
</script>
<script>
        var maxLength = 255;
        $('#docname').keyup(function() {
            var textlen = maxLength - $(this).val().length;
            $('#rchars').text(textlen);});
    </script>
    <script>
        var maxLength = 240;
        $('#duedoc').keyup(function() {
            var textlen = maxLength - $(this).val().length;
            $('#rchar').text(textlen);});
    </script>
     <script>
        function otherController(value, checkValue, blockID) {
            let block = document.getElementById(blockID)
            let blockTextarea = block.getElementsByTagName('textarea')[0];
            let blockLabel = block.querySelector('label span.text-danger');
            if (value === checkValue) {
                blockLabel.classList.remove('d-none');
                blockTextarea.setAttribute('required', 'required');
            } else {
                blockLabel.classList.add('d-none');
                blockTextarea.removeAttribute('required');
            }
        }
    </script>
@endsection
