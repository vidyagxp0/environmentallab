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
        {{-- <div class="pr-id">
            New Child
        </div> --}}
        <div class="division-bar">
            <strong>Site Division/Project</strong> :
            {{ Helpers::getDivisionName(session()->get('division')) }} / Action Item
        </div>
    </div>
    @php
        $users = DB::table('users')->get();
    @endphp


    {{-- ! ========================================= --}}
    {{-- !               DATA FIELDS                 --}}
    {{-- ! ========================================= --}}
    <div id="change-control-fields">
        <div class="container-fluid">

            <!-- Tab links -->
            <div class="cctab">
                <button class="cctablinks active" onclick="openCity(event, 'CCForm1')">General Information</button>
                {{-- <button class="cctablinks" onclick="openCity(event, 'CCForm2')">Parent Information</button> --}}
                <button class="cctablinks" onclick="openCity(event, 'CCForm3')">Post Completion</button>
                <button class="cctablinks" onclick="openCity(event, 'CCForm4')">Action Approval</button>
                <button class="cctablinks" onclick="openCity(event, 'CCForm5')">Activity Log</button>
            </div>

            <form action="{{ route('actionItem.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div id="step-form">
                    @if (!empty($parent_id))
                        <input type="hidden" name="parent_id" value="{{ $parent_id }}">
                        <input type="hidden" name="parent_type" value="{{ $parent_type }}">
                    @endif
                    <!-- Tab content -->
                    <div id="CCForm1" class="inner-block cctabcontent">
                        <div class="inner-block-content">
                            <div class="sub-head">
                                General Information
                            </div> <!-- RECORD NUMBER -->
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="RLS Record Number"><b>Record Number</b></label>
                                        <input disabled type="text" name="record_number"
                                            value="{{ Helpers::getDivisionName(session()->get('division')) }}/AI/{{ date('Y') }}/{{ $parent_record }}">
                                        {{-- <div class="static">QMS-EMEA/CAPA/{{ date('Y') }}/{{ $record_number }}</div> --}}
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Division Code"><b>Division Code</b></label>
                                        <input disabled type="text" name="division_code"
                                            value="{{ Helpers::getDivisionName(session()->get('division')) }}">
                                        <input type="hidden" name="division_id" value="{{ session()->get('division') }}">
                                        {{-- <div class="static">QMS-North America</div> --}}
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    @if (!empty($cc->id))
                                        <input type="hidden" name="ccId" value="{{ $cc->id }}">
                                    @endif
                                    <div class="group-input">
                                        <label for="originator">Initiator</label>
                                        <input disabled type="text"
                                            value="{{ Helpers::getInitiatorName($parent_initiator_id) }}">
                                    </div>
                                </div>
                                <!-- <div class="col-lg-6">
                                        <div class="group-input">
                                            <label for="Date Opened">Date of Initiation</label>
                                            {{-- <div class="static">{{ date('d-M-Y') }}</div> --}}
                                            <input disabled type="text"
                                                value="{{ Helpers::getdateFormat($parent_intiation_date) }}"
                                                name="intiation_date">
                                            <input type="hidden" value="{{ $parent_intiation_date }}" name="intiation_date">
                                        </div>
                                    </div> -->

                                {{--<div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Date Opened">Date of Initiation</label>
                                        <input disabled type="text"
                                            value="{{ Helpers::getdateFormat($parent_intiation_date) }}"
                                            id="initiation_date_display" name="intiation_date_display">
                                        <input type="hidden" id="initiation_date" value="{{ $parent_intiation_date }}"
                                            name="intiation_date">
                                    </div>
                                </div>--}}

                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Date Due"><b>Date of Initiation</b></label>
                                        <input disabled type="text" value="{{ date('d-M-Y') }}" name="intiation_date">
                                        <input type="hidden" value="{{ date('Y-m-d') }}" name="intiation_date" >

                                    </div>
                                </div>


                                {{-- <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Due Date">Due Date</label>

                                        @if (!empty($cc->due_date))
                                        <div class="static">{{ $cc->due_date }}</div>
                                        @endif
                                    </div>
                                </div> --}}
                                <div class="col-md-6">
                                    <div class="group-input">
                                        <label for="search">
                                            Assigned To <span class="text-danger"></span>
                                        </label>
                                        <select id="select-state" placeholder="Select..." name="assign_to">
                                            <option value="">Select a value</option>
                                            @foreach ($users as $value)
                                                <option value="{{ $value->id }}">{{ $value->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('assign_to')
                                            <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <!-- <div class="col-md-6 new-date-data-field">
                                        <div class="group-input input-date">
                                            <label for="due-date">Due Date <span class="text-danger"></span></label>
                                             <input type="date" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"
                                                value="" name="due_date"> -->
                                <!-- <div class="calenderauditee">
                                                <input type="text"  id="due_date" readonly placeholder="DD-MMM-YYYY" />
                                                <input type="date" name="due_date"  min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" value=""
                                                class="hide-input"
                                                oninput="handleDateInput(this, 'due_date')"/>
                                            </div>
                                        </div>
                                    </div> -->

                                <div class="col-lg-6 new-date-data-field">
                                    <div class="group-input input-date">
                                        <label for="Date Due">Due Date</label>
                                        <div>
                                        </div>
                                        <div class="calenderauditee">
                                            <input type="text" id="due_date" readonly placeholder="DD-MMM-YYYY"
                                                value="{{ Helpers::getDueDatemonthly(null, false, 'd-M-Y') }}" />
                                            <input type="date" name="due_date"
                                                min="{{ \Carbon\Carbon::now()->format('d-M-Y') }}" class="hide-input"
                                                oninput="handleDateInput(this, 'due_date')"
                                                value="{{ Helpers::getDueDatemonthly(null, false, 'Y-m-d') ?? '' }}" />
                                        </div>
                                    </div>
                                </div>





                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="Short Description">Short Description<span
                                                class="text-danger">*</span></label><span id="rchars">255</span>
                                        characters remaining
                                        <input id="docname" type="text" name="short_description" maxlength="255"
                                            required>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Related Records">Action Item Related Records</label>
                                        <select multiple id="related_records" name="related_records[]"
                                            placeholder="Select Reference Records">
                                            {{--<option value="">--select record--</option>--}}
                                             @if (!empty($old_record))

                                            @foreach ($old_record as $new)
                                                <option value="{{ $new->id }}">
                                                    {{ Helpers::getDivisionName($new->division_id) }}/AI/{{ date('Y') }}/{{ Helpers::recordFormat($new->record) }}
                                                </option>
                                            @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="HOD Persons">HOD Persons</label>
                                        <select multiple name="hod_preson[]" placeholder="Select HOD Persons"
                                            data-search="false" data-silent-initial-value-set="true" id="hod">
                                            @foreach ($users as $value)
                                                <option value="{{ $value->id }}">{{ $value->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="group-input">
                                        <label for="Short Description"> Description<span
                                                class="text-danger"></span></label>
                                        <textarea name="description"></textarea>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="group-input">
                                        <label for="Responsible Department">Responsible Department</label>
                                        <select name="departments">
                                            <option value="">Enter Your Selection Here</option>
                                            <option value="Quality Assurance-CQA">Quality Assurance-CQA</option>
                                            <option value="Research and development">Research and development</option>
                                            <option value="Regulatory Science">Regulatory Science</option>
                                            <option value="Supply Chain Management">Supply Chain Management</option>
                                            <option value="Finance">Finance</option>
                                            <option value="QA-Digital">QA-Digital</option>
                                            <option value="Central Engineering">Central Engineering</option>
                                            <option value="Projects">Projects</option>
                                            <option value="Marketing">Marketing</option>
                                            <option value="QCAT">QCAT</option>
                                            <option value="Marketing">Marketing</option>
                                            <option value="GMP Pilot Plant">GMP Pilot Plant</option>
                                            <option value="Manufacturing Sciences and Technology">Manufacturing Sciences
                                                and Technology</option>
                                            <option value="Environment, Health and Safety">Environment, Health and Safety
                                            </option>
                                            <option value="Business Relationship Management">Business Relationship
                                                Management</option>
                                            <option value="National Regulatory Affairs">National Regulatory Affairs
                                            </option>
                                            <option value="HR">HR</option>
                                            <option value="Admin">Admin</option>
                                            <option value="Information Technology">Information Technology</option>
                                            <option value="Program Management QA Analytical (Q13)">Program Management QA
                                                Analytical (Q13)</option>
                                            <option value="QA Analytical (Q8)">QA Analytical (Q8)</option>
                                            <option value="QA Packaging Development">QA Packaging Development</option>
                                            <option value="QA Engineering">QA Engineering</option>
                                            <option value="DS Quality Assurance">DS Quality Assurance</option>
                                            <option value="Quality Control (Q13)">Quality Control (Q13)</option>
                                            <option value="Quality Control (Q8)">Quality Control (Q8)</option>
                                            <option value="Quality Control (Q15)">Quality Control (Q15)</option>
                                            <option value="QC Microbiology (B1)">QC Microbiology (B1)</option>
                                            <option value="QC Microbiology (B2)">QC Microbiology (B2)</option>
                                            <option value="Microbiology Lab">Microbiology Lab</option>
                                            <option value="Wet Chemistry Lab">Wet Chemistry Lab</option>
                                            <option value="Intrument Lab">Intrument Lab</option>
                                            <option value="Molecular Lab">Molecular Lab</option>
                                            <option value="Production (B1)">Production (B1)</option>
                                            <option value="Production (B2)">Production (B2)</option>
                                            <option value="Production (Packing)">Production (Packing)</option>
                                            <option value="Production (Devices)">Production (Devices)</option>
                                            <option value="Production (DS)">Production (DS)</option>
                                            <option value="Engineering and Maintenance (B1)">Engineering and Maintenance
                                                (B1)</option>
                                            <option value="Engineering and Maintenance (B2)">Engineering and Maintenance
                                                (B2)</option>
                                            <option value="Engineering and Maintenance (W20)">Engineering and Maintenance
                                                (W20)</option>
                                            <option value="Device Technology Principle Management">Device Technology
                                                Principle Management</option>
                                            <option value="Production (82)">Production (82)</option>
                                            <option value="Production (Packing)">Production (Packing)</option>
                                            <option value="Production (Devices)">Production (Devices)</option>
                                            <option value="Production (DS)">Production (DS)</option>
                                            <option value="Engineering and Maintenance (B1)">Engineering and Maintenance
                                                (B1)</option>
                                            <option
                                                value="Engineering and Maintenance (B2) Engineering and
                                                Maintenance (W20)">
                                                Engineering and Maintenance (B2) Engineering and
                                                Maintenance (W20)
                                            </option>
                                            <option value="Device Technology Principle Management">Device Technology
                                                Principle Management</option>
                                            <option value="Warehouse(DP)">Warehouse(DP)</option>
                                            <option value="Drug safety">Drug safety</option>
                                            <option value="Others">Others</option>
                                            <option value="Visual Inspection">Visual Inspection</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="group-input">
                                        <label for="file_attach">File Attachments</label>
                                        <div class="file-attachment-field">
                                            <div class="file-attachment-list" id="file_attach"></div>
                                            <div class="add-btn">
                                                <div>Add</div>
                                                <input type="file" id="myfile" name="file_attach[]"
                                                    oninput="addMultipleFiles(this, 'file_attach')" multiple>
                                            </div>
                                        </div>
                                        {{-- <input type="file" name="file_attach[]" multiple> --}}
                                    </div>
                                </div>
                            </div>
                            <div class="button-block">
                                <button type="submit" class="saveButton">Save</button>
                                <button type="button" class="nextButton" onclick="nextStep()">Next</button>
                                <button type="button"> <a class="text-white" href="{{ url('rcms/qms-dashboard') }}">
                                        Exit </a> </button>

                            </div>
                        </div>
                    </div>

                    {{-- <div id="CCForm2" class="inner-block cctabcontent">
                        <div class="inner-block-content">
                            <div class="row">
                                <div class="col-6">
                                    <div class="group-input">
                                        <label for="Action Taken">RLS Record Number</label>
                                        <div class="static">Parent Record Number</div>
                                        <input disabled type="text"
                                            value="{{ Helpers::getDivisionName($parent_division_id) }}/{{ $parent_name }}/2023/{{ Helpers::recordFormat($parent_record) }}">
                                    </div>
                                </div>

                                <div class="button-block">
                                    <button type="submit" class="saveButton">Save</button>
                                    <button type="button" class="backButton" onclick="previousStep()">Back</button>
                                    <button type="button" class="nextButton" onclick="nextStep()">Next</button>
                                    <button type="button"> <a class="text-white"
                                            href="{{ url('rcms/qms-dashboard') }}">
                                            Exit </a> </button>
                                </div>
                            </div>
                        </div>
                    </div> --}}

                    <div id="CCForm3" class="inner-block cctabcontent">
                        <div class="inner-block-content">
                            <div class="row">
                                <div class="sub-head col-12">Post Completion</div>
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="action_taken">Action Taken</label>
                                        <textarea name="action_taken"></textarea>
                                    </div>
                                </div>
                                <div class="col-lg-6 new-date-data-field">
                                    <div class="group-input input-date">
                                        <label for="start_date">Actual Start Date</label>
                                        <div class="calenderauditee">
                                            <input type="text" id="start_date" readonly placeholder="DD-MMM-YYYY" />
                                            <input type="date" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"
                                                id="start_date_checkdate" name="start_date" class="hide-input"
                                                oninput="handleDateInput(this, 'start_date');checkDate('start_date_checkdate','end_date_checkdate')" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6  new-date-data-field">
                                    <div class="group-input input-date">
                                        <label for="end_date">Actual End Date</lable>
                                            <div class="calenderauditee">
                                                <input type="text" id="end_date" placeholder="DD-MMM-YYYY" />
                                                <input type="date" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"
                                                    id="end_date_checkdate" name="end_date" class="hide-input"
                                                    oninput="handleDateInput(this, 'end_date');checkDate('start_date_checkdate','end_date_checkdate')" />
                                            </div>


                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="Comments">Comments</label>
                                        <textarea name="comments"></textarea>
                                    </div>
                                </div>
                                {{-- <div class="col-12">
                                    <div class="group-input">
                                        <label for="Support_doc">Supporting Documents</label>
                                        <div class="file-attachment-field">
                                            <div class="file-attachment-list" id="Support_doc"></div>
                                            <div class="add-btn">
                                                <div>Add</div>
                                                <input type="file" id="myfile" name="Support_doc[]"
                                                    oninput="addMultipleFiles(this, 'Support_doc')" multiple>
                                            </div>
                                        </div>

                                    </div>
                                </div> --}}
                            </div>
                            <div class="button-block">
                                <button type="submit" class="saveButton">Save</button>
                                <button type="button" class="backButton" onclick="previousStep()">Back</button>
                                <button type="button" class="nextButton" onclick="nextStep()">Next</button>
                                <button type="button"> <a class="text-white" href="{{ url('rcms/qms-dashboard') }}">
                                        Exit </a> </button>
                            </div>
                        </div>
                    </div>

                    <div id="CCForm4" class="inner-block cctabcontent">
                        <div class="inner-block-content">
                            <div class="row">
                                <div class="sub-head">Action Approval</div>
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="qa_comments">QA Review Comments</label>
                                        <textarea name="qa_comments"></textarea>
                                    </div>
                                </div>
                                {{--
                                <div class="col-lg-12">
                                    <div class="group-input">
                                        <label for="file_attach">Final Attachments</label>
                                        <div class="file-attachment-field">
                                            <div class="file-attachment-list" id="final_attach"></div>
                                            <div class="add-btn">
                                                <div>Add</div>
                                                <input type="file" id="myfile" name="final_attach[]"
                                                    oninput="addMultipleFiles(this, 'final_attach')" multiple>
                                            </div>
                                        </div>

                                    </div>
                                </div> --}}
                                <div class="col-12 sub-head">
                                    Extension Justification
                                </div>

                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="due-dateextension">Due Date Extension Justification</label>
                                        <textarea name="due_date_extension"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="button-block">
                                <button type="submit" class="saveButton">Save</button>
                                <button type="button" class="backButton" onclick="previousStep()">Back</button>
                                <button type="button" class="nextButton" onclick="nextStep()">Next</button>
                                <button type="button"> <a class="text-white" href="{{ url('rcms/qms-dashboard') }}">
                                        Exit </a> </button>
                            </div>
                        </div>
                    </div>

                    <div id="CCForm5" class="inner-block cctabcontent">
                        <div class="inner-block-content">
                            <div class="sub-head">
                                Electronic Signatures
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="submitted by">Submitted By</label>
                                        <div class="static"></div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="submitted on">Submitted On</label>
                                        <div class="Date"></div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="cancelled by">Cancelled By</label>
                                        <div class="static"></div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="cancelled on">Cancelled On</label>
                                        <div class="Date"></div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="More information required By">More information required By</label>
                                        <div class="static"></div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="More information required On">More information required On</label>
                                        <div class="Date"></div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="completed by">Completed By</label>
                                        <div class="static"></div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="completed on">Completed On</label>
                                        <div class="Date"></div>
                                    </div>
                                </div>

                            </div>
                            <div class="button-block">
                                <button type="submit" class="saveButton">Save</button>
                                <button type="button" class="backButton" onclick="previousStep()">Back</button>
                                <button type="submit" class="saveButton">Submit</button>
                                <button type="button"> <a class="text-white"
                                        href="{{ url('rcms/qms-dashboard') }}">Exit
                                    </a> </button>
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
            ele: '#related_records, #hod'
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
        var maxLength = 255;
        $('#docname').keyup(function() {
            var textlen = maxLength - $(this).val().length;
            $('#rchars').text(textlen);
        });
    </script>
@endsection
