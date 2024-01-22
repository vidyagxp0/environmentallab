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
    @php
        $users = DB::table('users')->get();
    @endphp

    <div class="form-field-head">

        <div class="division-bar">
            <strong>Site Division/Project</strong> :
            {{ Helpers::getDivisionName($data->division_id) }} / Management Review
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
                        <button class="button_theme1"> <a class="text-white"
                                href="{{ url('ManagementReviewAuditTrial', $data->id) }}"> Audit Trail </a> </button>

                        @if ($data->stage == 1)
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#signature-modal">
                                Submit
                            </button>
                        @elseif($data->stage == 2)
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#signature-modal">
                                Complete
                            </button>
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#child-modal">
                                Child
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
                                <div class="active">In Progress </div>
                            @else
                                <div class="">In Progress</div>
                            @endif
                            @if ($data->stage >= 3)
                                <div class="bg-danger">Closed - Done</div>
                            @else
                                <div class="">Closed - Done</div>
                            @endif
                    @endif


                </div>
                {{-- @endif --}}
                {{-- ---------------------------------------------------------------------------------------- --}}
            </div>
        </div>
    </div>


    {{-- ======================================
                    DATA FIELDS
    ======================================= --}}
    <div id="change-control-fields">

        <div class="container-fluid">



            <!-- Tab links -->
            <div class="cctab">
                <button class="cctablinks active" onclick="openCity(event, 'CCForm1')">General Information</button>
                <button class="cctablinks" onclick="openCity(event, 'CCForm2')">Operational planning and control</button>
                <button class="cctablinks" onclick="openCity(event, 'CCForm3')">Meetings and summary</button>
                <button class="cctablinks" onclick="openCity(event, 'CCForm4')">Closure</button>
                <button class="cctablinks" onclick="openCity(event, 'CCForm5')">Signatures</button>
            </div>

            <form action="{{ route('manageUpdate', $data->id) }}" method="post" enctype="multipart/form-data">
                @csrf
                <div id="step-form">
                    <div id="CCForm1" class="inner-block cctabcontent">
                        <div class="inner-block-content">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="RLS Record Number"><b>Record Number</b></label>
                                        <input disabled type="text" name="record_number"
                                            value=" {{ Helpers::getDivisionName($data->division_id) }}/MR/{{ Helpers::year($data->created_at) }}/{{ $data->record }}">
                                        {{-- <div class="static">QMS-EMEA/CAPA/{{ date('Y') }}/{{ $record_number }}</div> --}}
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Division Code"><b>Site/Location Code</b></label>
                                        <input disabled type="text" name="division_code"
                                            value=" {{ Helpers::getDivisionName($data->division_id) }}">
                                        {{-- <div class="static">QMS-North America</div> --}}
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Initiator"><b>Initiator</b></label>
                                        {{-- <div class="static">{{ Auth::user()->name }}</div> --}}
                                        <input disabled type="text" name="division_code"
                                            value="{{ $data->initiator_name }}">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Date Due"><b>Date of Initiation</b></label>
                                        <input disabled type="text"
                                            value="{{ Helpers::getdateFormat($data->intiation_date) }}"
                                            name="intiation_date">
                                        {{-- <input type="hidden" value="{{ $data->intiation_date }}" name="intiation_date"> --}}

                                        {{-- <div class="static">{{ date('d-M-Y') }}</div> --}}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="group-input">
                                        <label for="search">
                                            Assigned To <span class="text-danger"></span>
                                        </label>
                                        <select id="select-state" placeholder="Select..." name="assign_id"
                                            {{ $data->stage == 0 || $data->stage == 3 ? 'disabled' : '' }}>
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
                                <div class="col-md-6">
                                    <div class="group-input">
                                        <label for="due-date">Due Date <span class="text-danger"></span></label>
                                        <div><small class="text-primary">Please mention expected date of completion</small>
                                        </div>
                                        {{-- <input type="hidden" value="{{ $due_date }}" name="due_date"> --}}
                                        <input disabled type="text"
                                            value="{{ Helpers::getdateFormat($data->due_date) }}">
                                        {{-- <div class="static"> {{ $due_date }}</div> --}}

                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Initiator Group"><b>Initiator Group</b></label>
                                        <select name="initiatorGroup"
                                            {{ $data->stage == 0 || $data->stage == 3 ? 'disabled' : '' }}
                                            id="initiator-group">
                                            <option value="CQA" @if ($data->initiatorGroup == 'CQA') selected @endif>
                                                Corporate
                                                Quality Assurance</option>
                                            <option value="QAB" @if ($data->initiatorGroup == 'QAB') selected @endif>
                                                Quality
                                                Assurance Biopharma</option>
                                            <option value="CQC" @if ($data->initiatorGroup == 'CQC') selected @endif>
                                                Central
                                                Quality Control</option>
                                            <option value="CQC" @if ($data->initiatorGroup == 'CQC') selected @endif>
                                                Manufacturing
                                            </option>
                                            <option value="PSG" @if ($data->initiatorGroup == 'PSG') selected @endif>
                                                Plasma
                                                Sourcing Group</option>
                                            <option value="CS" @if ($data->initiatorGroup == 'CS') selected @endif>
                                                Central
                                                Stores</option>
                                            <option value="ITG" @if ($data->initiatorGroup == 'ITG') selected @endif>
                                                Information
                                                Technology Group</option>
                                            <option value="MM" @if ($data->initiatorGroup == 'MM') selected @endif>
                                                Molecular
                                                Medicine</option>
                                            <option value="CL" @if ($data->initiatorGroup == 'CL') selected @endif>
                                                Central
                                                Laboratory</option>
                                            <option value="TT" @if ($data->initiatorGroup == 'TT') selected @endif>Tech
                                                team</option>
                                            <option value="QA" @if ($data->initiatorGroup == 'QA') selected @endif>
                                                Quality
                                                Assurance</option>
                                            <option value="QM" @if ($data->initiatorGroup == 'QM') selected @endif>
                                                Quality
                                                Management</option>
                                            <option value="IA" @if ($data->initiatorGroup == 'IA') selected @endif>IT
                                                Administration</option>
                                            <option value="ACC" @if ($data->initiatorGroup == 'ACC') selected @endif>
                                                Accounting
                                            </option>
                                            <option value="LOG" @if ($data->initiatorGroup == 'LOG') selected @endif>
                                                Logistics
                                            </option>
                                            <option value="SM" @if ($data->initiatorGroup == 'SM') selected @endif>
                                                Senior
                                                Management</option>
                                            <option value="BA" @if ($data->initiatorGroup == 'BA') selected @endif>
                                                Business
                                                Administration</option>

                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Initiator Group Code">Initiator Group Code</label>
                                        <input type="text" name="initiator_group_code" id="initiator_group_code"
                                            {{ $data->stage == 0 || $data->stage == 3 ? 'disabled' : '' }}
                                            value="{{ $data->initiator_group }}" disabled>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="Short Description">Short Description <span
                                                class="text-danger">*</span></label>
                                        <div><small class="text-primary">Please mention brief summary</small></div>
                                        <textarea name="short_description" id="short_desc" {{ $data->stage == 0 || $data->stage == 3 ? 'disabled' : '' }}>{{ $data->short_description }}</textarea>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="type">Type</label>
                                        <select name="type"
                                            {{ $data->stage == 0 || $data->stage == 3 ? 'disabled' : '' }}>
                                            <option value="Other">Other</option>
                                            <option value="Training">Training</option>
                                            <option value="Finance">Finance</option>
                                            <option value="follow Up">Follow Up</option>
                                            <option value="Marketing">Marketing</option>
                                            <option value="Sales">Sales</option>
                                            <option value="Account Service">Account Service</option>
                                            <option value="Recent Product Launch">Recent Product Launch</option>
                                            <option value="IT">IT</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Priority Level">Priority Level</label>
                                        <select name="priority_level"
                                            {{ $data->stage == 0 || $data->stage == 3 ? 'disabled' : '' }}>
                                            <option value="High">High</option>
                                            <option value="Medium">Medium</option>
                                            <option value="Low">Low</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Scheduled Start Date">Scheduled Start Date</label>
                                        <input type="date" name="start_date"
                                            {{ $data->stage == 0 || $data->stage == 3 ? 'disabled' : '' }}>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Scheduled end date">Scheduled end date</label>
                                        <input type="date" name="end_date"
                                            {{ $data->stage == 0 || $data->stage == 3 ? 'disabled' : '' }}>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="Attendees">Attendess</label>
                                        <textarea name="attendees" {{ $data->stage == 0 || $data->stage == 3 ? 'disabled' : '' }}></textarea>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="agenda">
                                            Agenda<button type="button" name="agenda" onclick="add6Input('agenda')"
                                                {{ $data->stage == 0 || $data->stage == 3 ? 'disabled' : '' }}>+</button>
                                        </label>
                                        <table class="table table-bordered" id="agenda">
                                            <thead>
                                                <tr>
                                                    <th>Row #</th>
                                                    <th>Date</th>
                                                    <th>Topic</th>
                                                    <th>Responsible</th>
                                                    <th>Time Start</th>
                                                    <th>Time End</th>
                                                    <th>Comment</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach (unserialize($agenda->date) as $key => $temps)
                                                    <tr>
                                                        <td><input type="text" name="serial_number[]"
                                                                value="{{ $key + 1 }}"></td>
                                                        <td><input type="date" name="date[]"
                                                                value="{{ $temps ? $temps : ' ' }}"></td>
                                                        <td><input type="text" name="topic[]"
                                                                value="{{ unserialize($agenda->topic)[$key] ? unserialize($agenda->topic)[$key] : '' }}">
                                                        </td>
                                                        <td><input type="text" name="responsible[]"
                                                                value="{{ unserialize($agenda->responsible)[$key] ? unserialize($agenda->responsible)[$key] : '' }}">
                                                        </td>
                                                        <td><input type="time" name="start_time[]"
                                                                value="{{ unserialize($agenda->start_time)[$key] ? unserialize($agenda->start_time)[$key] : '' }}">
                                                        </td>
                                                        <td><input type="time" name="end_time[]"
                                                                value="{{ unserialize($agenda->end_time)[$key] ? unserialize($agenda->end_time)[$key] : '' }}">
                                                        </td>
                                                        <td><input type="text" name="comment[]"
                                                                value="{{ unserialize($agenda->comment)[$key] ? unserialize($agenda->comment)[$key] : '' }}">
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="Description">Description</label>
                                        <textarea name="description" {{ $data->stage == 0 || $data->stage == 3 ? 'disabled' : '' }}></textarea>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="Inv Attachments">Initial Attachment</label>
                                        <div>
                                            <small class="text-primary">
                                                Please Attach all relevant or supporting documents
                                            </small>
                                        </div>
                                        <div class="file-attachment-field">
                                            <div class="file-attachment-list" id="audit_file_attachment"></div>
                                            <div class="add-btn">
                                                <div>Add</div>
                                                <input type="file" id="myfile" name="inv_attachment[]"
                                                    {{ $data->stage == 0 || $data->stage == 3 ? 'disabled' : '' }}
                                                    oninput="addMultipleFiles(this, 'audit_file_attachment')" multiple>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="button-block">
                                <button type="submit" id="ChangesaveButton" class="saveButton"
                                    {{ $data->stage == 0 || $data->stage == 3 ? 'disabled' : '' }}>Save</button>
                                <button type="button" id="ChangeNextButton" class="nextButton">Next</button>
                                <button type="button"> <a class="text-white" href="{{ url('rcms/qms-dashboard') }}">
                                        Exit </a> </button>
                            </div>
                        </div>
                    </div>

                    <div id="CCForm2" class="inner-block cctabcontent">
                        <div class="inner-block-content">
                            <div class="group-input">
                                <label for="Operations">
                                    Operations
                                    <span class="text-primary" data-bs-toggle="modal"
                                        data-bs-target="#management-review-operations-instruction-modal"
                                        style="font-size: 0.8rem; font-weight: 400; cursor:pointer;">
                                        (Launch Instruction)
                                    </span>
                                </label>
                                <textarea name="Operations"></textarea>
                            </div>
                            <div class="group-input">
                                <label for="requirement_products_services">
                                    Requirements for Products and Services
                                    <span class="text-primary" data-bs-toggle="modal"
                                        data-bs-target="#management-review-requirement_products_services-instruction-modal"
                                        style="font-size: 0.8rem; font-weight: 400; cursor:pointer;">
                                        (Launch Instruction)
                                    </span>
                                </label>
                                <textarea name="requirement_products_services"></textarea>
                            </div>
                            <div class="group-input">
                                <label for="design_development_product_services">
                                    Design and Development of Products and Services
                                    <span class="text-primary" data-bs-toggle="modal"
                                        data-bs-target="#management-review-design_development_product_services-instruction-modal"
                                        style="font-size: 0.8rem; font-weight: 400; cursor:pointer;">
                                        (Launch Instruction)
                                    </span>
                                </label>
                                <textarea name="design_development_product_services"></textarea>
                            </div>
                            <div class="group-input">
                                <label for="control_externally_provide_services">
                                    Control of Externally Provided Processes, Products and Services
                                    <span class="text-primary" data-bs-toggle="modal"
                                        data-bs-target="#management-review-control_externally_provide_services-instruction-modal"
                                        style="font-size: 0.8rem; font-weight: 400; cursor:pointer;">
                                        (Launch Instruction)
                                    </span>
                                </label>
                                <textarea name="control_externally_provide_services"></textarea>
                            </div>
                            <div class="group-input">
                                <label for="production_service_provision">
                                    Production and Service Provision
                                    <span class="text-primary" data-bs-toggle="modal"
                                        data-bs-target="#management-review-production_service_provision-instruction-modal"
                                        style="font-size: 0.8rem; font-weight: 400; cursor:pointer;">
                                        (Launch Instruction)
                                    </span>
                                </label>
                                <textarea name="production_service_provision"></textarea>
                            </div>
                            <div class="group-input">
                                <label for="release_product_services">
                                    Release of Products and Services
                                    <span class="text-primary" data-bs-toggle="modal"
                                        data-bs-target="#management-review-release_product_services-instruction-modal"
                                        style="font-size: 0.8rem; font-weight: 400; cursor:pointer;">
                                        (Launch Instruction)
                                    </span>
                                </label>
                                <textarea name="release_product_services"></textarea>
                            </div>
                            <div class="group-input">
                                <label for="control_nonconforming_outputs">
                                    Control of Non-conforming Outputs
                                    <span class="text-primary" data-bs-toggle="modal"
                                        data-bs-target="#management-review-control_nonconforming_outputs-instruction-modal"
                                        style="font-size: 0.8rem; font-weight: 400; cursor:pointer;">
                                        (Launch Instruction)
                                    </span>
                                </label>
                                <textarea name="control_nonconforming_outputs"></textarea>
                            </div>
                            <div class="group-input">
                                <label for="performance_evaluation">
                                    Performance Evaluation
                                    <button type="button" onclick="add4Input('performance_evaluation')">+</button>
                                    <span class="text-primary" data-bs-toggle="modal"
                                        data-bs-target="#management-review-performance_evaluation-instruction-modal"
                                        style="font-size: 0.8rem; font-weight: 400; cursor:pointer;">
                                        (Launch Instruction)
                                    </span>
                                </label>
                                <table class="table table-bordered" id="performance_evaluation">
                                    <thead>
                                        <tr>
                                            <th>Row #</th>
                                            <th>Monitoring</th>
                                            <th>Measurement</th>
                                            <th>Analysis</th>
                                            <th>Evalutaion</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><input type="text" name="row_no" value="1" disabled></td>
                                            <td><input type="text" name="monitoring"></td>
                                            <td><input type="text" name="measurement"></td>
                                            <td><input type="text" name="analysis"></td>
                                            <td><input type="text" name="evaluation"></td>
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
                            <div class="group-input">
                                <label for="risk_opportunities">Risk & Opportunities</label>
                                <textarea name="risk_opportunities"></textarea>
                            </div>
                            <div class="group-input">
                                <label for="external_supplier_performance">External Supplier Performance</label>
                                <textarea name="external_supplier_performance"></textarea>
                            </div>
                            <div class="group-input">
                                <label for="customer_satisfaction_level">Customer Satisfaction Level</label>
                                <textarea name="customer_satisfaction_level"></textarea>
                            </div>
                            <div class="group-input">
                                <label for="budget_estimates">Budget Estimates</label>
                                <textarea name="budget_estimates"></textarea>
                            </div>
                            <div class="group-input">
                                <label for="completion_of_previous_tasks">Completion of Previous Tasks</label>
                                <textarea name="completion_of_previous_tasks"></textarea>
                            </div>
                            <div class="group-input">
                                <label for="production">Production</label>
                                <textarea name="production"></textarea>
                            </div>
                            <div class="group-input">
                                <label for="plans">Plans</label>
                                <textarea name="plans"></textarea>
                            </div>
                            <div class="group-input">
                                <label for="forecast">Forecast</label>
                                <textarea name="forecast"></textarea>
                            </div>
                            <div class="group-input">
                                <label for="additional_suport_required">Any Additional Support Required</label>
                                <textarea name="additional_suport_required"></textarea>
                            </div>
                            <div class="group-input">
                                <label for="file_attchment_if_any">File Attachment, if any</label>
                                <div><small class="text-primary">Please Attach all relevant or supporting
                                        documents</small></div>
                                <div class="file-attachment-field">
                                    <div class="file-attachment-list" id="file_attchment_if_any"></div>
                                    <div class="add-btn">
                                        <div>Add</div>
                                        <input type="file" id="myfile" name="file_attchment_if_any[]"
                                            oninput="addMultipleFiles(this, 'file_attchment_if_any')" multiple>
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
                            <div class="group-input">
                                <label for="action-item-details">
                                    Action Item Details<button type="button" name="action-item-details"
                                        id="action_item">+</button>
                                </label>
                                <table class="table table-bordered" id="action_item_details">
                                    <thead>
                                        <tr>
                                            <th>Row #</th>
                                            <th>Record Number</th>
                                            <th>Short Description</th>
                                            <th>CAPA Type (Corrective Action / Preventive Action)</th>
                                            <th>Date Opened</th>
                                            <th>Site / Division</th>
                                            <th>Date Due</th>
                                            <th>Current Status</th>
                                            <th>Person Responsible</th>
                                            <th>Date Closed</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <td><input disabled type="text" name="serial_number[]" value="1">
                                        </td>
                                        <td><input type="text" name="record[]"></td>
                                        <td><input type="text" name="short_desc[]"></td>
                                        <td><input type="text" name="capa_type[]"></td>
                                        <td><input type="date" name="date_opened[]"></td>
                                        <td><input type="text" name="site[]"></td>
                                        <td><input type="date" name="date_due[]"></td>
                                        <td><input type="text" name="current_status[]"></td>
                                        <td> <select id="select-state" placeholder="Select..."
                                                name="responsible_person[]">
                                                <option value="">Select a value</option>
                                                @foreach ($users as $data)
                                                    <option value="{{ $data->id }}">{{ $data->name }}
                                                    </option>
                                                @endforeach
                                            </select></td>
                                        <td><input type="date" name="date_closed[]"></td>

                                    </tbody>
                                </table>
                            </div>
                            <div class="group-input">
                                <label for="capa-details">
                                    CAPA Details<button type="button" name="capa-details" id="capa_detail"">+</button>
                                </label>
                                <table class="table table-bordered" id="capa_detail_details">
                                    <thead>
                                        <tr>
                                            <th>Row #</th>
                                            <th>Record Number</th>
                                            <th>Short Description</th>
                                            <th>CAPA Type (Corrective Action / Preventive Action)</th>
                                            <th>Date Opened</th>
                                            <th>Site / Division</th>
                                            <th>Date Due</th>
                                            <th>Current Status</th>
                                            <th>Person Responsible</th>
                                            <th>Date Closed</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <td><input disabled type="text" name="serial_number[]" value="1">
                                        </td>
                                        <td><input type="text" name="record[]"></td>
                                        <td><input type="text" name="short_desc[]"></td>
                                        <td><input type="text" name="capa_type[]"></td>
                                        <td><input type="date" name="date_opened[]"></td>
                                        <td><input type="text" name="site[]"></td>
                                        <td><input type="date" name="date_due[]"></td>
                                        <td><input type="text" name="current_status[]"></td>
                                        <td> <select id="select-state" placeholder="Select..."
                                                name="responsible_person[]">
                                                <option value="">Select a value</option>
                                                @foreach ($users as $data)
                                                    <option value="{{ $data->id }}">{{ $data->name }}
                                                    </option>
                                                @endforeach
                                            </select></td>
                                        <td><input type="date" name="date_closed[]"></td>

                                    </tbody>
                                </table>
                            </div>
                            <div class="group-input">
                                <label for="next_managment_review_date">Next Management Review Date</label>
                                <input type="date" name='next_managment_review_date'>
                            </div>
                            <div class="group-input">
                                <label for="summary_recommendation">Summary & Recommendation</label>
                                <textarea name="summary_recommendation"></textarea>
                            </div>
                            <div class="group-input">
                                <label for="conclusion">Conclusion</label>
                                <textarea name="conclusion"></textarea>
                            </div>
                            <div class="group-input">
                                <label for="closure_attachments">Closure Attachments</label>
                                <div><small class="text-primary">Please Attach all relevant or supporting
                                        documents</small></div>
                                <div class="file-attachment-field">
                                    <div class="file-attachment-list" id="closure_attachments"></div>
                                    <div class="add-btn">
                                        <div>Add</div>
                                        <input type="file" id="myfile" name="closure_attachments[]"
                                            oninput="addMultipleFiles(this, 'closure_attachments')" multiple>
                                    </div>
                                </div>
                            </div>
                            <div class="sub-head">
                                Extension Justification
                            </div>
                            <div class="group-input">
                                <label for="due_date_extension">Due Date Extension Justification</label>
                                <div><small class="text-primary">Please Mention justification if due date is
                                        crossed</small></div>
                                <textarea name="due_date_extension"></textarea>
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

                    <div id="CCForm5" class="inner-block cctabcontent">
                        <div class="inner-block-content">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Completed By">Completed By</label>
                                        <div class="static">{{ $data->completed_by }}</div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Completed On">Completed On</label>
                                        <div class="static">{{ $data->completed_on }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="button-block">
                                <button type="submit" class="saveButton"
                                    {{ $data->stage == 0 || $data->stage == 3 ? 'disabled' : '' }}>Save</button>
                                <button type="button" class="backButton" onclick="previousStep()">Back</button>
                                <button type="submit"
                                    {{ $data->stage == 0 || $data->stage == 3 ? 'disabled' : '' }}>Submit</button>
                                <button type="button"> <a class="text-white" href="{{ url('rcms/qms-dashboard') }}">
                                        Exit </a>
                                </button>
                            </div>
                        </div>
                    </div>

                </div>
            </form>

        </div>
    </div>
    <div class="modal fade" id="child-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Child</h4>
                </div>
                <form action="{{ route('childmanagementReview', $data->id) }}" method="POST">
                    @csrf
                    <!-- Modal body -->
                    <div class="modal-body">
                        <div class="group-input">
                            <label for="major">
                                <input type="radio" name="revision" id="major" value="Action-Item">
                                Action Item
                            </label>

                        </div>

                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="button" data-bs-dismiss="modal">Close</button>
                        <button type="submit">Continue</button>
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

                <form action="{{ route('capa_reject', $data->id) }}" method="POST">
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

    <div class="modal fade" id="cancel-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">E-Signature</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <form action="{{ route('manageCancel', $data->id) }}" method="POST">
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
    <div class="modal fade" id="signature-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">E-Signature</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('manage_send_stage', $data->id) }}" method="POST">
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
