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
            {{ Helpers::getDivisionName($data->division_id) }} / Lab Incident
        </div>
    </div>

    {{-- ---------------------- --}}
    <div id="change-control-view">
        <div class="container-fluid">

            <div class="inner-block state-block">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="main-head">Record Workflow </div>

                    <div class="d-flex" style="gap:20px;">
                        @php
                        $userRoles = DB::table('user_roles')->where(['user_id' => Auth::user()->id, 'q_m_s_divisions_id' => $data->division_id])->get();
                        $userRoleIds = $userRoles->pluck('q_m_s_roles_id')->toArray();
                    @endphp
                        {{-- <button class="button_theme1" onclick="window.print();return false;"
                            class="new-doc-btn">Print</button> --}}
                        <button class="button_theme1"> <a class="text-white"
                                href="{{ route('audittrialLabincident', $data->id) }}"> Audit Trail </a> </button>

                        @if ($data->stage == 1 && Helpers::check_roles($data->division_id,'Lab Incident',3))
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#signature-modal">
                                Submit
                            </button>
                        @elseif($data->stage == 2 && Helpers::check_roles($data->division_id,'Lab Incident',4))
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#signature-modal">
                                Incident Review Completed
                            </button>
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#cancel-modal">
                                Cancellation Request
                            </button>
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#rejection-modal">
                                Request More Info
                            </button>
                        @elseif($data->stage == 3 && Helpers::check_roles($data->division_id,'Lab Incident',10))
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#signature-modal">
                                Investigation Completed
                            </button>
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#rejection-modal">
                                Request More Info
                            </button>
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#child-modal">
                                Child
                            </button>
                        @elseif($data->stage == 4 && Helpers::check_roles($data->division_id,'Lab Incident',10))
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#signature-modal">
                                All Activities Completed
                            </button>
                        @elseif($data->stage == 5 && Helpers::check_roles($data->division_id,'Lab Incident',10))
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#signature-modal">
                                Review
                            </button>
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#rejection-modal">
                                Further Investigation Required
                            </button>
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#child-modal1">
                                Child
                            </button>
                        @elseif($data->stage == 6 && Helpers::check_roles($data->division_id,'Lab Incident',7))
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#signature-modal">
                                QA Review Complete
                            </button>
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#rejection-modal">
                                Return To Pending CAPA
                            </button>
                        @elseif($data->stage == 7 && Helpers::check_roles($data->division_id,'Lab Incident',7))
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#signature-modal">
                                QA Head Approval Complete
                            </button>
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#rejection-modal">
                                Return to QA Review
                            </button>
                            <!-- <button class="button_theme1"> <a class="text-white" href="{{ url('rcms/qms-dashboard') }}">
                                    Exit
                                </a> </button> -->
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
                                <div class="active">Pending Incident Review </div>
                            @else
                                <div class="">Pending Incident Review</div>
                            @endif

                            @if ($data->stage >= 3)
                                <div class="active">Pending Investigation</div>
                            @else
                                <div class="">Pending Investigation</div>
                            @endif

                            @if ($data->stage >= 4)
                                <div class="active">Pending Activity Completion</div>
                            @else
                                <div class="">Pending Activity Completion</div>
                            @endif
                            @if ($data->stage >= 5)
                                <div class="active">Pending CAPA</div>
                            @else
                                <div class="">Pending CAPA</div>
                            @endif
                            @if ($data->stage >= 6)
                                <div class="active">Pending QA Review</div>
                            @else
                                <div class="">Pending QA Review</div>
                            @endif
                            @if ($data->stage >= 7)
                                <div class="active">Pending QA Head Approval </div>
                            @else
                                <div class="">Pending QA Head Approval</div>
                            @endif
                            @if ($data->stage >= 8)
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
                <button class="cctablinks" onclick="openCity(event, 'CCForm2')">Incident Details</button>
                <button class="cctablinks" onclick="openCity(event, 'CCForm3')">Investigation Details</button>
                <button class="cctablinks" onclick="openCity(event, 'CCForm4')">CAPA</button>
                <button class="cctablinks" onclick="openCity(event, 'CCForm5')">QA Review</button>
                <button class="cctablinks" onclick="openCity(event, 'CCForm6')">QA Head/Designee Approval</button>
                <button class="cctablinks" onclick="openCity(event, 'CCForm7')">Activity Log</button>
            </div>

            <form action="{{ route('LabIncidentUpdate', $data->id) }}" method="post" enctype="multipart/form-data">
                @csrf
                <div id="step-form">

                    <!-- General information content -->
                    <div id="CCForm1" class="inner-block cctabcontent">
                        <div class="inner-block-content">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="RLS Record Number"><b>Record Number</b></label>
                                        <input disabled type="text" name="record_number"
                                            value="{{ Helpers::getDivisionName($data->division_id) }}/LI/{{ Helpers::year($data->created_at) }}/{{ $data->record_number ? str_pad($data->record_number->record_number, 4, '0', STR_PAD_LEFT) : '' }}">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Division Code"><b>Site/Location Code</b></label>
                                        <input readonly type="text" name="division_code" value="{{ Helpers::getDivisionName($data->division_id) }}">
                                        {{-- <div class="static">QMS-North America</div> --}}
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Initiator"><b>Initiator</b></label>
                                        {{-- <div class="static">{{ Auth::user()->name }}</div> --}}
                                        <input disabled type="text" name="division_code"
                                            value="{{ $data->initiator_name}}">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Date Due"><b>Date of Initiation</b></label>
                                        <input disabled type="text" name="intiation_date"
                                         value="{{ Helpers::getdateFormat($data->intiation_date)}}" >
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="group-input">
                                        <label for="search">
                                            Assigned To <span class="text-danger"></span>
                                        </label>
                                        <select id="select-state" placeholder="Select..." name="assign_to" {{ $data->stage == 0 || $data->stage == 8 ? "disabled" : "" }}>
                                            <option value="">Select a value</option>
                                            @foreach ($users as $key=> $value)
                                                <option  @if ($data->assign_to == $value->id) selected @endif  value="{{ $value->id }}">{{ $value->name }}</option>
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
                                        <input readonly type="text"
                                            value="{{ Helpers::getdateFormat($data->due_date) }}"
                                            name="due_date"{{ $data->stage == 0 || $data->stage == 8 ? 'disabled' : '' }}>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Initiator Group"><b>Initiator Group</b></label>
                                        <select name="Initiator_Group" {{ $data->stage == 0 || $data->stage == 8 ? "disabled" : "" }}
                                             id="initiator_group">
                                             <option value="">-- Select --</option>
                                            <option value="Corporate Quality Assurance"
                                                @if ($data->Initiator_Group== 'Corporate Quality Assurance') selected @endif>Corporate
                                                Quality Assurance</option>
                                            <option value="QAB"
                                                @if ($data->Initiator_Group== 'QAB') selected @endif>Quality
                                                Assurance Biopharma</option>
                                            <option value="CQC"
                                                @if ($data->Initiator_Group== 'CQC') selected @endif>Central
                                                Quality Control</option>
                                            <option value="CQC"
                                                @if ($data->Initiator_Group== 'MANU') selected @endif>Manufacturing
                                            </option>
                                            <option value="PSG"
                                                @if ($data->Initiator_Group== 'PSG') selected @endif>Plasma
                                                Sourcing Group</option>
                                            <option value="CS"
                                                @if ($data->Initiator_Group== 'CS') selected @endif>Central
                                                Stores</option>
                                            <option value="ITG"
                                                @if ($data->Initiator_Group== 'ITG') selected @endif>Information
                                                Technology Group</option>
                                            <option value="MM"
                                                @if ($data->Initiator_Group== 'MM') selected @endif>Molecular
                                                Medicine</option>
                                            <option value="CL"
                                                @if ($data->Initiator_Group== 'CL') selected @endif>Central
                                                Laboratory</option>
                                            <option value="TT"
                                                @if ($data->Initiator_Group== 'TT') selected @endif>Tech
                                                team</option>
                                            <option value="QA"
                                                @if ($data->Initiator_Group== 'QA') selected @endif>Quality
                                                Assurance</option>
                                            <option value="QM"
                                                @if ($data->Initiator_Group== 'QM') selected @endif>Quality
                                                Management</option>
                                            <option value="IA"
                                                @if ($data->Initiator_Group== 'IA') selected @endif>IT
                                                Administration</option>
                                            <option value="ACC"
                                                @if ($data->Initiator_Group== 'ACC') selected @endif>Accounting
                                            </option>
                                            <option value="LOG"
                                                @if ($data->Initiator_Group== 'LOG') selected @endif>Logistics
                                            </option>
                                            <option value="SM"
                                                @if ($data->Initiator_Group== 'SM') selected @endif>Senior
                                                Management</option>
                                            <option value="BA"
                                                @if ($data->Initiator_Group== 'BA') selected @endif>Business
                                                Administration</option>

                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Initiator Group Code">Initiator Group Code</label>
                                        <input type="text" id="initiator_group_code"  name="initiator_group_code" value="{{$data->initiator_group_code}}" readonly>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="Short Description">Short Description<span
                                                class="text-danger">*</span></label><span id="rchars">255</span>
                                        characters remaining

                                        <input name="short_desc" value="{{ $data->short_desc }}" id="docname" type="text" maxlength="255" required  {{ $data->stage == 0 || $data->stage == 8 ? "disabled" : "" }} >
                                    </div>
                                    <p id="docnameError" style="color:red">**Short Description is required</p>

                                </div>
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="severity-level">Severity Level</label>
                                        <span class="text-primary">Severity levels in a QMS record gauge issue seriousness, guiding priority for corrective actions. Ranging from low to high, they ensure quality standards and mitigate critical risks.</span>
                                        <select name="severity_level2"{{ $data->stage == 0 || $data->stage == 8 ? "disabled" : "" }} >
                                            <option value="">-- Select --</option>
                                            <option @if ($data->severity_level2=='minor') selected @endif  value="minor">Minor</option>
                                            <option @if ($data->severity_level2=='major') selected @endif value="major">Major</option>
                                            <option @if ($data->severity_level2=='critical') selected @endif value="critical">Critical</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Initiator Group">Initiated Through</label>
                                        <div><small class="text-primary">Please select related
                                                information</small></div>
                                        <select name="initiated_through"
                                            {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}
                                            onchange="otherController(this.value, 'others', 'initiated_through_req')">
                                            <option value="">Enter Your Selection Here</option>
                                            <option @if ($data->initiated_through == 'recall') selected @endif
                                                value="recall">Recall</option>
                                            <option @if ($data->initiated_through == 'return') selected @endif
                                                value="return">Return</option>
                                            <option @if ($data->initiated_through == 'deviation') selected @endif
                                                value="deviation">Deviation</option>
                                            <option @if ($data->initiated_through == 'complaint') selected @endif
                                                value="complaint">Complaint</option>
                                            <option @if ($data->initiated_through == 'regulatory') selected @endif
                                                value="regulatory">Regulatory</option>
                                            <option @if ($data->initiated_through == 'lab-incident') selected @endif
                                                value="lab-incident">Lab Incident</option>
                                            <option @if ($data->initiated_through == 'improvement') selected @endif
                                                value="improvement">Improvement</option>
                                                <option @if ($data->initiated_through == 'OOS') selected @endif
                                                    value="OOS">OOS</option>
                                            <option @if ($data->initiated_through == 'others') selected @endif
                                                value="others">Others</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input" id="initiated_through_req">
                                        <label for="initiated_through">Others<span
                                                class="text-danger d-none">*</span></label>
                                        <textarea name="initiated_through_req" {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}>{{ $data->initiated_through_req }}</textarea>
                                    </div>
                                </div>
                                <!-- <div class="col-lg-6">
                             <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Date of Occurance">Date of Occurance</label>
                                        <input type="date" name="occurance_date" value="{{ $data->occurance_date }}">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Due Date">Due Date</label>
                                        <input type="date" name="due_date" value="{{ $data->due_date }}">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Assigned to">Assigned to</label>
                                        <select name="assigend">
                                            @foreach($users as $value)
                                                <option value="{{ $value->id }}">{{ $value->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                </div>-->
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Incident Category">Incident Category</label>
                                        <select {{ $data->stage == 0 || $data->stage == 8 ? "disabled" : "" }} name="Incident_Category">
                                            <option value="">Enter Your Selection Here</option>
                                            <option value="Biological" @if ($data->Incident_Category== 'Biological') selected @endif>
                                                Biological
                                            </option>
                                            <option value="Chemical" @if ($data->Incident_Category== 'Chemical') selected @endif>
                                                Chemical
                                            </option>
                                            <option value="Deviation" @if ($data->Incident_Category== 'Deviation') selected @endif>
                                                Deviation
                                            </option>
                                            <option value="Others" @if ($data->Incident_Category== 'Others') selected @endif>
                                                Others
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                            <div class="group-input" id="initiated_through_req1">
                                                <label for="Incident_Category_others">Others<span
                                                        class="text-danger d-none">*</span></label>
                                                <textarea name="Incident_Category_others" {{ $data->stage == 0 || $data->stage == 8 ? "disabled" : "" }}>{{ $data->Incident_Category_others }}</textarea>
                                            </div>
                                        </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Invocation Type">Invocation Type</label>
                                        <select  name="Invocation_Type" {{ $data->stage == 0 || $data->stage == 8 ? "disabled" : "" }}>
                                            <option value="">Enter Your Selection Here</option>
                                            <option value="NA" @if ($data->Invocation_Type== 'NA') selected @endif>NA
                                            </option>
                                            <option value="1" @if ($data->Invocation_Type== '1') selected @endif>1
                                            </option>
                                            <option value="2" @if ($data->Invocation_Type== '2') selected @endif>2
                                            </option>
                                            <option value="3" @if ($data->Invocation_Type== '3') selected @endif>3
                                            </option>
                                        </select>
                                    </div>
                                </div>


                                @if ($data->Initial_Attachment)
                                    @foreach (json_decode($data->Initial_Attachment) as $file)
                                        <input id="1-{{ $loop->index }}" type="hidden"
                                        name="existing_attach_files_initial[{{ $loop->index }}]"
                                        value="{{ $file }}">
                                    @endforeach
                                @endif
                                <div class="col-lg-12">
                                    <div class="group-input">
                                        <label for="Initial Attachments">Initial Attachment</label>
                                        <div><small class="text-primary">Please Attach all relevant or supporting documents</small></div>
                                            <div class="file-attachment-field">
                                                <div class="file-attachment-list" id="Initial_Attachment">
                                                    @if ($data->Initial_Attachment)
                                                    @foreach (json_decode($data->Initial_Attachment) as $file)
                                                        <h6 type="button" class="file-container text-dark"
                                                            style="background-color: rgb(243, 242, 240);">
                                                            <b>{{ $file }}</b>
                                                            <a href="{{ asset('upload/' . $file) }}"
                                                                target="_blank"><i class="fa fa-eye text-primary"
                                                                    style="font-size:20px; margin-right:-10px;"></i></a>
                                                            <a type="button" class="remove-file"
                                                                    data-remove-id="1-{{ $loop->index }}"
                                                                    data-file-name="{{ $file }}"><i
                                                                    class="fa-solid fa-circle-xmark"
                                                                    style="color:red; font-size:20px;"></i></a>
                                                        </h6>
                                                    @endforeach
                                                @endif
                                                </div>
                                                <div class="add-btn">
                                                    <div>Add</div>
                                                    <input {{ $data->stage == 0 || $data->stage == 8 ? "disabled" : "" }} type="file" id="myfile" name="Initial_Attachment[]"
                                                        oninput="addMultipleFiles(this, 'Initial_Attachment')" multiple>
                                                </div>
                                            </div>
                                    </div>
                                </div>
                            </div>
                            <div class="button-block">
                                <button type="submit" id="ChangesaveButton" class="saveButton" {{ $data->stage == 0 || $data->stage == 8 ? "disabled" : "" }}>Save</button>
                                <button type="button" id="ChangeNextButton" class="nextButton">Next</button>
                                <button type="button"> <a class="text-white" href="{{ url('rcms/qms-dashboard') }}"> Exit </a> </button>
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
                                        <textarea name="Incident_Details" {{ $data->stage == 0 || $data->stage == 8 ? "disabled" : "" }}>{{ $data->Incident_Details }}</textarea>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="Document Details ">Document Details</label>
                                        <textarea name="Document_Details" {{ $data->stage == 0 || $data->stage == 8 ? "disabled" : "" }}>{{ $data->Document_Details }}</textarea>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="Instrument Details">Instrument Details</label>
                                        <textarea name="Instrument_Details" {{ $data->stage == 0 || $data->stage == 8 ? "disabled" : "" }}>{{ $data->Instrument_Details }}</textarea>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="Involved Personnel">Involved Personnel</label>
                                        <textarea name="Involved_Personnel" {{ $data->stage == 0 || $data->stage == 8 ? "disabled" : "" }}>{{ $data->Involved_Personnel }}</textarea>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="Product Details,If Any">Product Details,If Any</label>
                                        <textarea name="Product_Details" {{ $data->stage == 0 || $data->stage == 8 ? "disabled" : "" }}>{{ $data->Product_Details }}</textarea>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="Supervisor Review Comments">Supervisor Review Comments</label>
                                        <textarea name="Supervisor_Review_Comments" {{ $data->stage == 0 || $data->stage == 8 ? "disabled" : "" }}>{{ $data->Supervisor_Review_Comments }}</textarea>
                                    </div>
                                </div>

                                @if ($data->Attachments)
                                    @foreach (json_decode($data->Attachments) as $file)
                                        <input id="2-{{ $loop->index }}" type="hidden"
                                        name="existing_attach_files_incident[{{ $loop->index }}]"
                                        value="{{ $file }}">
                                    @endforeach
                                @endif
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="Attachments">Incident Attachments</label>
                                        <div><small class="text-primary">Please Attach all relevant or supporting documents</small></div>
                                            <div class="file-attachment-field">
                                                <div class="file-attachment-list" id="Attachments">
                                                    @if ($data->Attachments)
                                                    @foreach (json_decode($data->Attachments) as $file)
                                                        <h6 type="button" class="file-container text-dark"
                                                            style="background-color: rgb(243, 242, 240);">
                                                            <b>{{ $file }}</b>
                                                            <a href="{{ asset('upload/' . $file) }}"
                                                                target="_blank"><i class="fa fa-eye text-primary"
                                                                    style="font-size:20px; margin-right:-10px;"></i></a>
                                                            <a type="button" class="remove-file"
                                                                    data-remove-id="2-{{ $loop->index }}"
                                                                    data-file-name="{{ $file }}"><i
                                                                    class="fa-solid fa-circle-xmark"
                                                                    style="color:red; font-size:20px;"></i></a>
                                                        </h6>
                                                    @endforeach
                                                @endif
                                                </div>
                                                <div class="add-btn">
                                                    <div>Add</div>
                                                    <input {{ $data->stage == 0 || $data->stage == 8 ? "disabled" : "" }} type="file" id="myfile" name="Attachments[]"
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
                                        <textarea name="Cancelation_Remarks" {{ $data->stage == 0 || $data->stage == 8 ? "disabled" : "" }}>{{ $data->Cancelation_Remarks }}</textarea>
                                    </div>
                                </div> --}}
                            </div>
                            <div class="button-block">
                                <button type="submit" class="saveButton" {{ $data->stage == 0 || $data->stage == 8 ? "disabled" : "" }}>Save</button>
                                <button type="button" class="backButton" onclick="previousStep()">Back</button>
                                <button type="button" class="nextButton" onclick="nextStep()">Next</button>
                                <button type="button"> <a class="text-white" href="{{ url('rcms/qms-dashboard') }}"> Exit </a> </button>
                            </div>
                        </div>
                    </div>

                    <!-- Investigation Details content -->
                    <div id="CCForm3" class="inner-block cctabcontent">
                        <div class="inner-block-content">
                            <div class="row">
                                {{-- <div class="col-12 sub-head">
                                    Questionnaire
                                </div>  --}}
                                {{-- <div class="col-12">
                                    <div class="group-input">
                                        <label for="INV Questionnaire">INV Questionnaire</label>
                                        <div class="static">Question datafield</div>
                                    </div>
                                </div> --}}

                                @if ($data->Inv_Attachment)
                                    @foreach (json_decode($data->Inv_Attachment) as $file)
                                        <input id="3-{{ $loop->index }}" type="hidden"
                                        name="existing_attach_files_investigation[{{ $loop->index }}]"
                                        value="{{ $file }}">
                                    @endforeach
                                @endif
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="Inv Attachments">Investigation Attachment</label>
                                        <div><small class="text-primary">Please Attach all relevant or supporting documents</small></div>
                                            <div class="file-attachment-field">
                                                <div class="file-attachment-list" id="Inv_Attachment">
                                                    @if ($data->Inv_Attachment)
                                                    @foreach (json_decode($data->Inv_Attachment) as $file)
                                                        <h6 type="button" class="file-container text-dark"
                                                            style="background-color: rgb(243, 242, 240);">
                                                            <b>{{ $file }}</b>
                                                            <a href="{{ asset('upload/' . $file) }}"
                                                                target="_blank"><i class="fa fa-eye text-primary"
                                                                    style="font-size:20px; margin-right:-10px;"></i></a>
                                                            <a type="button" class="remove-file"
                                                                    data-remove-id="3-{{ $loop->index }}"
                                                                    data-file-name="{{ $file }}"><i
                                                                    class="fa-solid fa-circle-xmark"
                                                                    style="color:red; font-size:20px;"></i></a>
                                                        </h6>
                                                    @endforeach
                                                @endif
                                                </div>
                                                <div class="add-btn">
                                                    <div>Add</div>
                                                    <input {{ $data->stage == 0 || $data->stage == 8 ? "disabled" : "" }} type="file" id="myfile" name="Inv_Attachment[]"
                                                        oninput="addMultipleFiles(this, 'Inv_Attachment')" multiple>
                                                </div>
                                            </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="Investigation Details ">Investigation Details</label>
                                        <textarea name="Investigation_Details" {{ $data->stage == 0 || $data->stage == 8 ? "disabled" : "" }}>{{ $data->Investigation_Details }}</textarea>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="Action Taken">Action Taken</label>
                                        <textarea name="Action_Taken" {{ $data->stage == 0 || $data->stage == 8 ? "disabled" : "" }}>{{ $data->Action_Taken }}</textarea>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="Root Cause">Root Cause</label>
                                        <textarea name="Root_Cause" {{ $data->stage == 0 || $data->stage == 8 ? "disabled" : "" }}>{{ $data->Root_Cause }}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="button-block">
                                <button type="submit" class="saveButton" {{ $data->stage == 0 || $data->stage == 8 ? "disabled" : "" }}>Save</button>
                                <button type="button" class="backButton" onclick="previousStep()">Back</button>
                                <button type="button" class="nextButton" onclick="nextStep()">Next</button>
                                <button type="button"> <a class="text-white" href="{{ url('rcms/qms-dashboard') }}"> Exit </a> </button>
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
                                        <textarea name="Currective_Action" {{ $data->stage == 0 || $data->stage == 8 ? "disabled" : "" }}>{{ $data->Currective_Action }}</textarea>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="Preventive Action">Preventive Action</label>
                                        <textarea name="Preventive_Action" {{ $data->stage == 0 || $data->stage == 8 ? "disabled" : "" }}>{{ $data->Preventive_Action }}</textarea>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="Corrective & Preventive Action">Corrective & Preventive Action</label>
                                        <textarea name="Corrective_Preventive_Action" {{ $data->stage == 0 || $data->stage == 8 ? "disabled" : "" }}>{{ $data->Corrective_Preventive_Action }}</textarea>
                                    </div>
                                </div>

                                @if ($data->CAPA_Attachment)
                                    @foreach (json_decode($data->CAPA_Attachment) as $file)
                                        <input id="4-{{ $loop->index }}" type="hidden"
                                        name="existing_attach_files_capa[{{ $loop->index }}]"
                                        value="{{ $file }}">
                                    @endforeach
                                @endif
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="CAPA Attachments">CAPA Attachment</label>
                                        <div><small class="text-primary">Please Attach all relevant or supporting documents</small></div>
                                            <div class="file-attachment-field">
                                                <div class="file-attachment-list" id="CAPA_Attachment">
                                                    @if ($data->CAPA_Attachment)
                                                    @foreach (json_decode($data->CAPA_Attachment) as $file)
                                                        <h6 type="button" class="file-container text-dark"
                                                            style="background-color: rgb(243, 242, 240);">
                                                            <b>{{ $file }}</b>
                                                            <a href="{{ asset('upload/' . $file) }}"
                                                                target="_blank"><i class="fa fa-eye text-primary"
                                                                    style="font-size:20px; margin-right:-10px;"></i></a>
                                                            <a type="button" class="remove-file"
                                                                    data-remove-id="4-{{ $loop->index }}"
                                                                    data-file-name="{{ $file }}"><i
                                                                    class="fa-solid fa-circle-xmark"
                                                                    style="color:red; font-size:20px;"></i></a>
                                                        </h6>
                                                    @endforeach
                                                @endif
                                                </div>
                                                <div class="add-btn">
                                                    <div>Add</div>
                                                    <input {{ $data->stage == 0 || $data->stage == 8 ? "disabled" : "" }} type="file" id="myfile" name="CAPA_Attachment[]"
                                                        oninput="addMultipleFiles(this, 'CAPA_Attachment')" multiple>
                                                </div>
                                            </div>
                                    </div>
                                </div>
                            </div>
                            <div class="button-block">
                                <button type="submit" class="saveButton" {{ $data->stage == 0 || $data->stage == 8 ? "disabled" : "" }}>Save</button>
                                <button type="button" class="backButton" onclick="previousStep()">Back</button>
                                <button type="button" class="nextButton" onclick="nextStep()">Next</button>
                                <button type="button"> <a class="text-white" href="{{ url('rcms/qms-dashboard') }}"> Exit </a> </button>
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
                                        <textarea name="QA_Review_Comments" {{ $data->stage == 0 || $data->stage == 8 ? "disabled" : "" }}>{{ $data->QA_Review_Comments }}</textarea>
                                    </div>
                                </div>

                                @if ($data->QA_Head_Attachment)
                                    @foreach (json_decode($data->QA_Head_Attachment) as $file)
                                        <input id="5-{{ $loop->index }}" type="hidden"
                                        name="existing_attach_files_QA[{{ $loop->index }}]"
                                        value="{{ $file }}">
                                    @endforeach
                                @endif
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="QA Head Attachments">QA Review Attachment</label>
                                        <div><small class="text-primary">Please Attach all relevant or supporting documents</small></div>
                                            <div class="file-attachment-field">
                                                <div class="file-attachment-list" id="QA_Head_Attachment">
                                                    @if ($data->QA_Head_Attachment)
                                                    @foreach (json_decode($data->QA_Head_Attachment) as $file)
                                                        <h6 type="button" class="file-container text-dark"
                                                            style="background-color: rgb(243, 242, 240);">
                                                            <b>{{ $file }}</b>
                                                            <a href="{{ asset('upload/' . $file) }}"
                                                                target="_blank"><i class="fa fa-eye text-primary"
                                                                    style="font-size:20px; margin-right:-10px;"></i></a>
                                                            <a type="button" class="remove-file"
                                                                data-remove-id="5-{{ $loop->index }}"
                                                                data-file-name="{{ $file }}"><i
                                                                    class="fa-solid fa-circle-xmark"
                                                                    style="color:red; font-size:20px;"></i></a>
                                                        </h6>
                                                    @endforeach
                                                @endif
                                                </div>
                                                <div class="add-btn">
                                                    <div>Add</div>
                                                    <input {{ $data->stage == 0 || $data->stage == 8 ? "disabled" : "" }} type="file" id="myfile" name="QA_Head_Attachment[]"
                                                        oninput="addMultipleFiles(this, 'QA_Head_Attachment')" multiple>
                                                </div>
                                            </div>
                                    </div>
                                </div>
                            </div>
                            <div class="button-block">
                                <button type="submit" class="saveButton" {{ $data->stage == 0 || $data->stage == 8 ? "disabled" : "" }}>Save</button>
                                <button type="button" class="backButton" onclick="previousStep()">Back</button>
                                <button type="button" class="nextButton" onclick="nextStep()">Next</button>
                                <button type="button"> <a class="text-white" href="{{ url('rcms/qms-dashboard') }}"> Exit </a> </button>
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
                                        <textarea name="QA_Head" {{ $data->stage == 0 || $data->stage == 8 ? "disabled" : "" }}>{{ $data->QA_Head }}</textarea>
                                    </div>
                                </div>
                            <div class="col-lg-6">
                                    <!-- <div class="group-input">
                                        <label for="Effectiveness Check required?">Effectiveness Check
                                            required?</label>
                                        <select name="Effectiveness_Check" {{ $data->stage == 0 || $data->stage == 8 ? "disabled" : "" }}>
                                            <option value="">Enter Your Selection Here</option>
                                            <option value="yes" @if ($data->Effectiveness_Check == 'yes') selected @endif>yes
                                            </option>
                                            <option value="no" @if ($data->Effectiveness_Check == 'no') selected @endif>no
                                            </option>
                                        </select>
                                    </div> -->
                                </div>
                                <!-- <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Effect.Chesk Creation Date">Effect.Chesk Creation Date</label>
                                        <input type="date" name="effect_check_date" {{ $data->stage == 0 || $data->stage == 8 ? "readonly" : "" }}
                                            value="{{ $data->effect_check_date }}">
                                    </div>
                                </div>  -->
                                 <!-- <div class="col-lg-6 new-date-data-field">
                                    <div class="group-input input-date">
                                        <label for="Date Due">Effectiveness Check Creation Date</label>
                                        <div class="calenderauditee">
                                            <input type="text" id="effectivess_check_creation_date" readonly
                                                placeholder="DD-MMM-YYYY" {{ $data->stage == 0 || $data->stage == 8 ? "disabled" : "" }} value="{{ Helpers::getdateFormat($data->effectivess_check_creation_date) }}"/>
                                            <input type="date" name="effectivess_check_creation_date"  value="{{ $data->effectivess_check_creation_date }} "class="hide-input"
                                                oninput="handleDateInput(this, 'effectivess_check_creation_date')" />
                                        </div>
                                    </div>
                                </div>  -->
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="Incident Type">Incident Type
                                            <span class="text-primary" data-bs-toggle="modal"
                                                data-bs-target="#lab-incident-type-of-change-instruction-modal"
                                                style="font-size: 0.8rem; font-weight: 400; cursor:pointer;">
                                                (Launch Instruction)
                                            </span>
                                        </label>
                                        <select name="Incident_Type" {{ $data->stage == 0 || $data->stage == 8 ? "disabled" : "" }}>
                                            <option value="">Enter Your Selection Here</option>
                                            <option value="Type A" @if ($data->Incident_Type == 'Type A') selected @endif>Type
                                                A
                                            </option>
                                            <option value="Type B" @if ($data->Incident_Type == 'Type B') selected @endif>Type
                                                B
                                            </option>
                                            <option value="Type C" @if ($data->Incident_Type == 'Type C') selected @endif>Type
                                                c
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="Conclusion">Conclusion</label>
                                        <textarea name="Conclusion"{{ $data->stage == 0 || $data->stage == 8 ? "disabled" : "" }} >{{ $data->Conclusion }}</textarea>
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
                                        <textarea name="due_date_extension" maxlength="240" id="duedoc" type="text"{{ $data->stage == 0 || $data->stage == 8 ? "disabled" : "" }}>{{$data->due_date_extension}}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="button-block">
                                <button type="submit" class="saveButton" {{ $data->stage == 0 || $data->stage == 8 ? "disabled" : "" }}>Save</button>
                                <button type="button" class="backButton" onclick="previousStep()">Back</button>
                                <button type="button" class="nextButton" onclick="nextStep()">Next</button>
                                <button type="button"> <a class="text-white" href="{{ url('rcms/qms-dashboard') }}"> Exit </a> </button>
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
                                        <div class="static">{{ $data->submitted_by }}</div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Submitted On">Submitted On</label>
                                        <div class="Date">{{ $data->submitted_on }}</div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Incident Review Completed By">Incident Review Completed
                                            By</label>
                                        <div class="static">{{ $data->incident_review_completed_by }}</div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Incident Review Completed On">Incident Review Completed
                                            On</label>
                                        <div class="Date">{{ $data->incident_review_completed_on }}</div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Investigation Completed By">Investigation Completed By</label>
                                        <div class="static">{{ $data->investigation_completed_by }}</div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Investigation Completed On">Investigation Completed On</label>
                                        <div class="Date">{{ $data->investigation_completed_on }}</div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Investigation Completed By">All Activities Completed By</label>
                                        <div class="static">{{ $data->all_activities_completed_by }}</div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Investigation Completed On">All Activities Completed On</label>
                                        <div class="Date">{{ $data->all_activities_completed_on }}</div>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Review Completed By">Review Completed By</label>
                                        <div class="static">{{$data->review_completed_by}}</div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Review Completed On">Review Completed On</label>
                                        <div class="Date">{{$data->review_completed_on}}</div>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="QA Review Completed By">QA Review Completed By</label>
                                        <div class="static">{{ $data->qA_review_completed_by }}</div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="QA Review Completed On">QA Review Completed On</label>
                                        <div class="Date">{{ $data->qA_review_completed_on }}</div>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="QA Head Approval Completed By">QA Head Approval Completed By</label>
                                        <div class="static">{{ $data->qA_head_approval_completed_by }}</div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="QA Head Approval Completed On">QA Head Approval Completed On</label>
                                        <div class="Date">{{ $data->qA_head_approval_completed_on }}</div>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Cancelled By">Cancelled By</label>
                                        <div class="static">{{ $data->cancelled_by }}</div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Cancelled On">Cancelled On</label>
                                        <div class="Date">{{ $data->cancelled_on }}</div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Cancelled By">Request More Info By</label>
                                        <div class="static">{{ $data->request_more_info_by }}</div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Cancelled By">Request More Info On</label>
                                        <div class="static">{{ $data->request_more_info_on }}</div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Cancelled By">Request More Info By</label>
                                        <div class="static">{{ $data->request_more_information_by }}</div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Cancelled By">Request More Info On</label>
                                        <div class="static">{{ $data->request_more_information_on }}</div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Cancelled By">Further Investigation Required By</label>
                                        <div class="static">{{ $data->further_investigation_required_by }}</div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Cancelled By">Further Investigation Required On</label>
                                        <div class="static">{{ $data->further_investigation_required_on }}</div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Cancelled By">Return To Pending CAPA By</label>
                                        <div class="static">{{ $data->return_to_pending_capa_by }}</div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Cancelled By">Return To Pending CAPA On</label>
                                        <div class="static">{{ $data->return_to_pending_capa_on }}</div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Cancelled By">Return to QA Review By</label>
                                        <div class="static">{{ $data->return_to_qa_review_by }}</div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Cancelled By">Return to QA Review On</label>
                                        <div class="static">{{ $data->return_to_qa_review_on }}</div>
                                    </div>
                                </div>
                                {{-- <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Cancelled By">Return to QA Review On</label>
                                        <div class="static">{{ $data->return_to_qa_review_on }}</div>
                                    </div>
                                </div>  --}}
                                {{-- <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="All Activities Completed By">All Activities Completed By</label>
                                        <div class="static">{{ $data->all_activities_completed_by }}</div>
                                    </div>
                                </div> --}}
                                {{-- <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="All Activities Completed On">All Activities Completed On</label>
                                        <div class="Date">{{ $data->all_activities_completed_on }}</div>
                                    </div>
                                </div> --}}
                                {{-- <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Review Completed By">Review Completed By</label>
                                        <div class="static">{{$data->review_completed_by}}</div>
                                    </div>
                                </div> --}}
                                {{-- <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Review Completed On">Review Completed On</label>
                                        <div class="Date">{{$data->review_completed_on}}</div>
                                    </div>
                                </div> --}}
                            </div>
                            <div class="button-block">
                                <button type="submit" class="saveButton" {{ $data->stage == 0 || $data->stage == 8 ? "disabled" : "" }}>Save</button>
                                <button type="button" class="backButton" onclick="previousStep()">Back</button>
                                <button type="submit" {{ $data->stage == 0 || $data->stage == 8 ? "disabled" : "" }}>Submit</button>
                                <button type="button"> <a class="text-white" href="{{ url('rcms/qms-dashboard') }}"> Exit </a> </button>
                            </div>
                        </div>
                    </div>

                </div>
            </form>

        </div>
    </div>
    {{-- ----------------------modal------------- --}}

    <div class="modal fade" id="rejection-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">E-Signature</h4>
                    <!-- <button type="button" class="btn-close" data-bs-dismiss="modal"></button> -->
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal"> <i class="fa fa-times"></i> </button>

                </div>
                <form action="{{ url('rcms/RejectStateChangeEsign', $data->id) }}" method="POST">
                    @csrf
                    <!-- Modal body -->
                    <div class="modal-body">
                        <div class="mb-3 text-justify">
                            Please select a meaning and a outcome for this task and enter your username
                            and password for this task. You are performing an electronic signature,
                            which is legally binding equivalent of a hand written signature.
                        </div>
                        <div class="group-input">
                            <label for="username">Username  <span
                                class="text-danger">*</span></label>
                            <input type="text" name="username" required>
                        </div>
                        <div class="group-input">
                            <label for="password">Password  <span
                                class="text-danger">*</span></label>
                            <input type="password" name="password" required>
                        </div>
                        <div class="group-input">
                            <label for="comment">Comment <span
                                class="text-danger">*</span></label>
                            <input type="comment" name="comment" required>
                        </div>
                    </div>

                    <!-- Modal footer -->
                    <!-- <div class="modal-footer">
                        <button type="submit" data-bs-dismiss="modal">Submit</button>
                        <button>Close</button>
                    </div> -->
                    <div class="modal-footer">
                              <button type="submit">Submit</button>
                                <button type="button" data-bs-dismiss="modal">Close</button>

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
                    <!-- <button type="button" class="btn-close" data-bs-dismiss="modal"></button> -->
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal"> <i class="fa fa-times"></i> </button>

                </div>
                <form action="{{ route('StageChangeLabIncident', $data->id) }}" method="POST">
                    @csrf
                    <!-- Modal body -->
                    <div class="modal-body">
                        <div class="mb-3 text-justify">
                            Please select a meaning and a outcome for this task and enter your username
                            and password for this task. You are performing an electronic signature,
                            which is legally binding equivalent of a hand written signature.
                        </div>
                        <div class="group-input">
                            <label for="username">Username  <span
                                class="text-danger">*</span></label>
                            <input type="text" name="username" required>
                        </div>
                        <div class="group-input">
                            <label for="password">Password  <span
                                class="text-danger">*</span></label>
                            <input type="password" name="password" required>
                        </div>
                        <div class="group-input">
                            <label for="comment">Comment</label>
                            <input type="comment" name="comment">
                        </div>
                    </div>

                    <!-- Modal footer -->
                    <!-- <div class="modal-footer">
                        <button type="submit" data-bs-dismiss="modal">Submit</button>
                        <button>Close</button>
                    </div> -->
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
                    <!-- <button type="button" class="btn-close" data-bs-dismiss="modal"></button> -->
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal"> <i class="fa fa-times"></i> </button>

                </div>

                <form action="{{ route('LabIncidentCancel', $data->id) }}" method="POST">
                    @csrf
                    <!-- Modal body -->
                    <div class="modal-body">
                        <div class="mb-3 text-justify">
                            Please select a meaning and a outcome for this task and enter your username
                            and password for this task. You are performing an electronic signature,
                            which is legally binding equivalent of a hand written signature.
                        </div>
                        <div class="group-input">
                            <label for="username">Username  <span
                                class="text-danger">*</span></label>
                            <input type="text" name="username" required>
                        </div>
                        <div class="group-input">
                            <label for="password">Password  <span
                                class="text-danger">*</span></label>
                            <input type="password" name="password" required>
                        </div>
                        <div class="group-input">
                            <label for="comment">Comment  <span
                                class="text-danger">*</span></label>
                            <input type="comment" name="comment" required>
                        </div>
                    </div>

                    <!-- Modal footer -->
                    <!-- <div class="modal-footer">
                        <button type="submit" data-bs-dismiss="modal">Submit</button>
                        <button>Close</button>
                    </div> -->
                    <div class="modal-footer">
                              <button type="submit">Submit</button>
                             <button type="button" data-bs-dismiss="modal">Close</button>
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
                    <h4 class="modal-title">Child</h4>
                </div>
                <form action="{{ route('lab_incident_root_child', $data->id) }}" method="POST">
                    @csrf
                    <!-- Modal body -->
                    <div class="modal-body">
                        <div class="group-input">
                            <label for="major">
                                <input type="radio" name="revision" id="major" value="Action-Item">
                                Root Cause Analysis
                            </label>
                        </div>

                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="button" data-bs-dismiss="modal">Close</button>
                        <button type="submit">Continue</button>
                    </div>
                    {{-- <div class="modal-footer">
                              <button type="submit">Submit</button>
                             <button type="button" data-bs-dismiss="modal">Close</button>
                   </div> --}}
                </form>

            </div>
        </div>
    </div>
    <div class="modal fade" id="child-modal1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Child</h4>
                </div>
                <form action="{{ route('lab_incident_capa_child', $data->id) }}" method="POST">
                    @csrf
                    <!-- Modal body -->
                    <div class="modal-body">
                        <div class="group-input">
                            <label for="major">
                                <input type="radio" name="revision" id="major" value="Action-Item">
                                CAPA
                            </label>
                        </div>

                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="button" data-bs-dismiss="modal">Close</button>
                        <button type="submit">Continue</button>
                    </div>
                    {{-- <div class="modal-footer">
                              <button type="submit">Submit</button>
                             <button type="button" data-bs-dismiss="modal">Close</button>
                   </div> --}}
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
        // JavaScript
        document.getElementById('initiator_group').addEventListener('change', function() {
            var selectedValue = this.value;
            document.getElementById('initiator_group_code').value = selectedValue;
        });
    </script>
     <script>
        document.addEventListener('DOMContentLoaded', function() {
            const removeButtons = document.querySelectorAll('.remove-file');

            removeButtons.forEach(button => {
                button.addEventListener('click', function() {
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
    <script>
        $(document).ready(function() {
            $('.remove-file').click(function() {
                const removeId = $(this).data('remove-id')
                console.log('removeId', removeId);
                $('#' + removeId).remove();
            })
        })
    </script>
    <script>
        var maxLength = 255;
        $('#docname').keyup(function() {
            var textlen = maxLength - $(this).val().length;
            $('#rchars').text(textlen);
        });
    </script>
    <script>
        var maxLength = 240;
        $('#duedoc').keyup(function() {
            var textlen = maxLength - $(this).val().length;
            $('#rchar').text(textlen);});
    </script>
@endsection
