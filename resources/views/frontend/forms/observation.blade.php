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
        function calculateRiskAnalysis(selectElement) {
            // Get the row containing the changed select element
            let row = selectElement.closest('tr');

            // Get values from select elements within the row
            let R = parseFloat(document.getElementById('analysisR').value) || 0;
            let P = parseFloat(document.getElementById('analysisP').value) || 0;
            let N = parseFloat(document.getElementById('analysisN').value) || 0;

            // Perform the calculation
            let result = R * P * N;

            // Update the result field within the row
            document.getElementById('analysisRPN').value = result;
        }
    </script>

    <div class="form-field-head">

        <div class="division-bar">
            <strong>Site Division/Project</strong> : {{ Helpers::getDivisionName(session()->get('division')) }}/Observation
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
                <button class="cctablinks active" onclick="openCity(event, 'CCForm1')">Observation</button>
                <button class="cctablinks" onclick="openCity(event, 'CCForm2')">CAPA Plan</button>
                <button class="cctablinks" onclick="openCity(event, 'CCForm3')">Impact Analysis</button>
                <button class="cctablinks" onclick="openCity(event, 'CCForm4')">Summary</button>
                <button class="cctablinks" onclick="openCity(event, 'CCForm5')">Signatures</button>
            </div>

            <form action="{{ route('observationstore') }}" method="post" enctype="multipart/form-data">\
                @csrf
                <div id="step-form">

                    <div id="CCForm1" class="inner-block cctabcontent">
                        <div class="inner-block-content">
                            <div class="row">
                                <div class="col-12">
                                    <div class="sub-head">Gneral Information</div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="RLS Record Number"><b>Record Number</b></label>
                                        <input disabled type="text" name="record_number" value="{{ Helpers::getDivisionName(session()->get('division')) }}/CAPA/{{ date('Y') }}/{{ $record_number }}">
                                        {{-- <div class="static">QMS-EMEA/CAPA/{{ date('Y') }}/{{ $record_number }}</div> --}}
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Division Code"><b>Division Code</b></label>
                                        <input disabled type="text" name="division_code" value="{{ Helpers::getDivisionName(session()->get('division')) }}">
                                        <input type="hidden" name="division_id" value="{{ session()->get('division') }}">
                                        {{-- <div class="static">QMS-North America</div> --}}
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="originator">Originator</label>
                                        <input disabled type="text" value="{{ Auth::user()->name }}"">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="date_opened">Date Opened</label>
                                        <input disabled type="text" value="{{ date('d-M-Y') }}" name="intiation_date">
                                        <input  type="hidden" value="{{ date('Y-m-d') }}" name="intiation_date">
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="assign_to1">Assigned To</label>
                                        <select name="assign_to1">
                                            <option value="">-- Select --</option>
                                            @foreach ($users as $data)
                                            <option value="{{ $data->id }}">{{ $data->name }}</option>
                                        @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="date_due">Date Due</label>
                                        <input  type="hidden" value="{{ $due_date }}" name="due_date">
                                        <input disabled type="text" value="{{ Helpers::getdateFormat($due_date) }}" >
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="Short Description"><b>Short Description <span
                                            class="text-danger">*</span></b></label>
                                        <textarea name="short_description"></textarea>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="sub-head">Observation Details</div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="grading">Grading</label>
                                        <select name="grading">
                                            <option value="">-- Select --</option>
                                            <option value="1">Recommendation</option>
                                            <option value="2">Major</option>
                                            <option value="3">Minor</option>
                                            <option value="4">Critical</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="category_observation">Category Observation</label>
                                        <select name="category_observation">
                                            <option value="">-- Select --</option>
                                            <option title="Case Report Form (CRF)" value="1">
                                                Case Report Form (CRF)
                                            </option>
                                            <option title="Clinical Database" value="2">
                                                Clinical Database
                                            </option>
                                            <option title="Clinical Trial Protocol" value="3">
                                                Clinical Trial Protocol
                                            </option>
                                            <option title="Clinical Trial Report" value="4">
                                                Clinical Trial Report
                                            </option>
                                            <option title="Compliance" value="5" selected="">
                                                Compliance
                                            </option>
                                            <option title="Computerized systems" value="6">
                                                Computerized systems
                                            </option>
                                            <option title="Conduct of Study" value="7">
                                                Conduct of Study
                                            </option>
                                            <option title="Data Accuracy / SDV" value="8">
                                                Data Accuracy / SDV
                                            </option>
                                            <option title="Documentation" value="9">
                                                Documentation
                                            </option>
                                            <option title="Essential Documents (TMF/ISF)" value="10">
                                                Essential Documents (TMF/ISF)
                                            </option>
                                            <option title="Ethics Committee (IEC / IRB)" value="11">
                                                Ethics Committee (IEC / IRB)
                                            </option>
                                            <option title="Facilities / Equipment" value="12">
                                                Facilities / Equipment
                                            </option>
                                            <option title="Miscellaneous" value="13">
                                                Miscellaneous
                                            </option>
                                            <option title="Monitoring" value="14">
                                                Monitoring
                                            </option>
                                            <option title="Organization and Responsibilities" value="16">
                                                Organization and Responsibilities
                                            </option>
                                            <option title="Periodic Safety Reporting" value="17">
                                                Periodic Safety Reporting
                                            </option>
                                            <option title="Protocol Compliance" value="18">
                                                Protocol Compliance
                                            </option>
                                            <option title="Qualification and Training of Staff" value="19">
                                                Qualification and Training of Staff
                                            </option>
                                            <option title="Quality Management System" value="20">
                                                Quality Management System
                                            </option>
                                            <option title="Regulatory Requirements" value="25">
                                                Regulatory Requirements
                                            </option>
                                            <option title="Reliability of Data" value="26">
                                                Reliability of Data
                                            </option>
                                            <option title="Safety Reporting" value="27">
                                                Safety Reporting
                                            </option>
                                            <option title="Source Documents" value="28">
                                                Source Documents
                                            </option>
                                            <option title="Subject Diary(ies)" value="29">
                                                Subject Diary(ies)
                                            </option>
                                            <option title="Informed Consent Form" value="30">
                                                Informed Consent Form
                                            </option>
                                            <option title="Subject Questionnaire(s)" value="31">
                                                Subject Questionnaire(s)
                                            </option>
                                            <option title="Supporting Procedures" value="32">
                                                Supporting Procedures
                                            </option>
                                            <option title="Test Article and Accountability" value="33">
                                                Test Article and Accountability
                                            </option>
                                            <option title="Trial Master File (TMF)" value="34">
                                                Trial Master File (TMF)
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="reference_guideline">Referenced Guideline</label>
                                        <input type="text" name="reference_guideline">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="desc">Description</label>
                                        <textarea name="description"></textarea>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="sub-head">Further Information</div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="attach_files1">Attached Files</label>
                                        <input type="file" name="attach_files1" />
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="capa_date_due">Recomendation Date Due for CAPA</label>
                                        <input type="date" name="recomendation_capa_date_due" />
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="non_compliance">Non Compliance</label>
                                        <textarea name="non_compliance"></textarea>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="recommend_action">Recommended Action</label>
                                        <textarea name="recommend_action"></textarea>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="related_observations">Related Obsevations</label>
                                        <input type="file" name="related_observations" />
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
                            <div class="row">
                                <div class="col-12">
                                    <div class="sub-head">CAPA Plan Details</div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="date_Response_due">Date Response Due</label>
                                        <input type="date" name="date_Response_due2" />
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="date_due">Date Due</label>
                                        <input type="date" name="capa_date_due">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="assign_to2">Assigned To</label>
                                        <select name="assign_to2">
                                            <option value="">-- Select --</option>
                                            @foreach ($users as $data)
                                            <option value="{{ $data->id }}">{{ $data->name }}</option>
                                        @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="cro_vendor">CRO/Vendor</label>
                                        <select name="cro_vendor">
                                            <option value="">-- Select --</option>
                                            @foreach ($users as $data)
                                            <option value="{{ $data->id }}">{{ $data->name }}</option>
                                        @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="action-plan-grid">
                                            Action Plan<button type="button" name="action-plan-grid"
                                                id="observation-table">+</button>
                                        </label>
                                        <table class="table table-bordered" id="observation">
                                            <thead>
                                                <tr>
                                                    <th>S.No</th>
                                                    <th>Action</th>
                                                    <th>Responsible</th>
                                                    <th>Deadline</th>
                                                    <th>Item Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <td><input type="text" name="serial_number[]" value="1"></td>
                                                <td><input type="text" name="action[]"></td>
                                                <td><input type="text" name="responsible[]"></td>
                                                <td><input type="text" name="deadline[]"></td>
                                                <td><input type="text" name="item_status[]"></td>
                                            </tbody>
                                        </table>
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
                                <button type="submit" class="saveButton">Save</button>
                                <button type="button" class="backButton" onclick="previousStep()">Back</button>
                                <button type="button" class="nextButton" onclick="nextStep()">Next</button>
                                <button type="button"> <a class="text-white"> Exit </a> </button>
                            </div>
                        </div>
                    </div>

                    <div id="CCForm3" class="inner-block cctabcontent">
                        <div class="inner-block-content">
                            <div class="row">
                                <div class="col-12">
                                    <div class="sub-head">Impact Analysis</div>
                                </div>
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="impact">Impact</label>
                                        <select name="impact">
                                            <option value="">-- Select --</option>
                                            <option value="1">High</option>
                                            <option value="2">Medium</option>
                                            <option value="3">Low</option>
                                            <option value="4">None</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="impact_analysis">Impact Analysis</label>
                                        <textarea name="impact_analysis"></textarea>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="sub-head">Risk Analysis</div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Severity Rate">Severity Rate</label>
                                        <select name="severity_rate" id="analysisR"
                                            onchange='calculateRiskAnalysis(this)'>
                                            <option value="">Enter Your Selection Here</option>
                                            <option value="1">Negligible</option>
                                            <option value="2">Moderate</option>
                                            <option value="3">Major</option>
                                            <option value="4">Fatal</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Occurrence">Occurrence</label>
                                        <select name="occurrence" id="analysisP" onchange='calculateRiskAnalysis(this)'>
                                            <option value="">Enter Your Selection Here</option>
                                            <option value="5">Extremely Unlikely</option>
                                            <option value="4">Rare</option>
                                            <option value="3">Unlikely</option>
                                            <option value="2">Likely</option>
                                            <option value="1">Very Likely</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Detection">Detection</label>
                                        <select name="detection" id="analysisN" onchange='calculateRiskAnalysis(this)'>
                                            <option value="">Enter Your Selection Here</option>
                                            <option value="5">Impossible</option>
                                            <option value="4">Rare</option>
                                            <option value="3">Unlikely</option>
                                            <option value="2">Likely</option>
                                            <option value="1">Very Likely</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="RPN">RPN</label>
                                        <input type="text" name="analysisRPN" id="analysisRPN" disabled>
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

                    <div id="CCForm4" class="inner-block cctabcontent">
                        <div class="inner-block-content">
                            <div class="row">
                                <div class="col-12">
                                    <div class="sub-head">Action Summary</div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="actual_start_date">Actual Start Date</label>
                                        <input type="date" name="actual_start_date">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="actual_end_date">Actual End Date</label>
                                        <input type="date" name="actual_end_date">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="action_taken">Action Taken</label>
                                        <textarea name="action_taken"></textarea>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="sub-head">Response Summary</div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="date_response_due">Date Rsponse Due</label>
                                        <input type="date" name="date_response_due1">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="response_date">Date of Response</label>
                                        <input type="date" name="response_date">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="attach_files">Attached Files</label>
                                        <input type="file" name="attach_files2">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="related_url">Related URL</label>
                                        <input type="url" name="related_url">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="response_summary">Response Summary</label>
                                        <textarea name="response_summary"></textarea>
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

                    <div id="CCForm5" class="inner-block cctabcontent">
                        <div class="inner-block-content">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Completed_By">Completed By</label>
                                        <div class="static"></div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Completed_On">Completed On</label>
                                        <div class="static"></div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="QA_Approved_By">QA Approved By</label>
                                        <div class="static"></div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="QA_Approved_On">QA Approved On</label>
                                        <div class="static"></div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Final_Approval_By">Final Approval By</label>
                                        <div class="static"></div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Final_Approval_On">Final Approval On</label>
                                        <div class="static"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="button-block">
                                <button type="submit" class="saveButton">Save</button>
                                <button type="button" class="backButton" onclick="previousStep()">Back</button>
                                <button type="submit">Submit</button>
                                <button type="button"> <a class="text-white" href="{{ url('dashboard') }}"> Exit </a>
                                </button>
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
@endsection
