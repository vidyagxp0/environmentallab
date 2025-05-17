@extends('frontend.layout.main')
@section('container')
    {{-- ======================================
                    DOCUMENT TRACKER
    ======================================= --}}
    <div id="document-tracker">
        <div class="container-fluid">
            <div class="tracker-container">
                <div class="row">

                    <div class="col-12">
                        <div class="inner-block doc-info-block">
                            <div class="top-block">
                                <div class="title">
                                    {{ $document->document_name }}
                                </div>
                                <div class="buttons">
                                    <button onclick="location.href='{{ url('notification', $document->id) }}';">
                                        Send Notification
                                    </button>
                                    <button  onclick="location.href='{{ url('audit-trial', $document->id) }}';">
                                        Audit Trail
                                    </button>

                                    @php
                                        $showEdit = true;

                                        // In-review to Approved Stage Disable for Initiator
                                        if ( $document->stage > 1 && $document->stage <= 5 && Helpers::checkUserIsOnlyInitiator(auth()->user()) )
                                        {
                                            $showEdit = false;
                                        }

                                    @endphp

                                    @if ($document->status !== 'Obsolete' && $showEdit)
                                        <button onclick="location.href='{{ route('documents.editWithType', ['id' => $document->id, 'type' => 'doc']) }}';">Edit </button>
                                        {{-- <button>Cancel</button> --}}
                                    @endif
                                    <button  onclick="location.href='{{ secure_url('documents/generatePdf', $document->id) }}';">Download
                                    </button>
                                    <button onclick="location.href='{{ url('documents/printPDF', $document->id) }}';" target="__blank">
                                            Print
                                    </button>
                                    {{-- @if ($document->stage >= 7)
                                        <button data-bs-toggle="modal" data-bs-target="#child-modal">Child</button>
                                    @endif --}}
                                    @if ($document->stage >= 8 && $document->status !== 'Obsolete')
                                        <button  class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#signature-modal">
                                            Obsolete
                                        </button>
                                        @if($document->revised == 'No')
                                            <button data-bs-toggle="modal" data-bs-target="#child-modal">Revise</button>
                                        @endif
                                    @endif

                                </div>
                            </div>
                            <div class="bottom-block">
                                <div>
                                    <div class="head">Document Number</div>
                                    <div>
                                        @if($document->revised === 'Yes')
                                            000{{ $document->revised_doc }}
                                        @else
                                            000{{ $document->id }}
                                        @endif
                                       </div>
                                </div>
                                {{-- <div>
                                    <div class="head">Department</div>
                                    <div>{{ $document->department_name->name }}</div>
                                </div> --}}
                                <div>
                                    <div class="head">Document Type</div>
                                    <div>{{ $document->doc_type->name }}</div>
                                </div>
                                <div>
                                    <div class="head">Working Status</div>
                                    <div>{{ $document->status }}</div>
                                </div>
                                <div>
                                    <div class="head">Last Modified By</div>
                                    @if ($document->last_modify)
                                        <div>{{ $document->last_modify->user_name }}</div>
                                    @else
                                        <div>{{ $document->oreginator->name }}</div>
                                    @endif
                                </div>
                                <div>
                                    <div class="head">Last Modified On</div>
                                    @if ($document->last_modify)
                                        <div>{{ $document->last_modify_date->created_at }}</div>
                                    @else
                                        <div>{{ $document->created_at }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-8">
                        <div class="inner-block tracker">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="main-title">
                                    Record Workflow
                                </div>

                                @if ($document->stage == 1 && Auth::user()->id == $document->originator_id)
                                    <input type="hidden" name="stage_id" value="2" />
                                    <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#approve-sign">
                                        Send For Review<i class="fa-regular fa-paper-plane"></i>
                                    </button>
                                @endif
                                @if($document->stage == 2 && in_array(Auth::user()->id, explode(",", $document->reviewers)))
                                    <div class="buttons">
                                        @if (empty($review_reject))
                                            @if ($stagereview && empty($stagereview_submit))
                                                @if($document->stage < 3)
                                                <button data-bs-toggle="modal" data-bs-target="#review-cancel">
                                                    Reject&nbsp;<i class="fa-regular fa-circle-xmark"></i>
                                                </button>
                                                @endif
                                            @endif
                                        @elseif($document->stage == 2)
                                            <button data-bs-toggle="modal" data-bs-target="#review-cancel">
                                                Reject&nbsp;<i class="fa-regular fa-circle-xmark"></i>
                                            </button>
                                        @endif

                                        @if (empty($stagereview))
                                            @if (empty($review_reject))
                                                <button data-bs-toggle="modal" data-bs-target="#review-sign">
                                                    Review&nbsp;<i class="fa-regular fa-paper-plane"></i>
                                                </button>
                                                <button data-bs-toggle="modal" data-bs-target="#review-cancel">
                                                    Reject&nbsp;<i class="fa-regular fa-circle-xmark"></i>
                                                </button>
                                            @elseif($document->stage == 2)
                                                <button data-bs-toggle="modal" data-bs-target="#review-sign">
                                                    Review&nbsp;<i class="fa-regular fa-circle-xmark"></i>
                                                </button>
                                            @endif
                                        @endif
                                    </div>
                                @endif
                                @if ($document->stage == 3 && Auth::user()->id == $document->originator_id)
                                    <input type="hidden" name="stage_id" value="4" />
                                    <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#approve-sign">
                                        Send For Approval<i class="fa-regular fa-paper-plane"></i>
                                    </button>
                                @endif
                                @if ($document->stage == 4 && in_array(Auth::user()->id, explode(",", $document->approvers)))
                                    @if (empty($stageapprove))
                                        @if (empty($approval_reject))
                                        <div>
                                            <button style="margin-left: 483px;" class="button_theme1" data-bs-toggle="modal" data-bs-target="#review-sign">
                                                Approve&nbsp;<i class="fa-regular fa-paper-plane"></i>
                                            </button>
                                            <button  class="button_theme1" data-bs-toggle="modal" data-bs-target="#review-cancel">
                                                Reject&nbsp;<i class="fa-regular fa-circle-xmark"></i>
                                            </button>
                                            {{-- @elseif($document->stage == 4)
                                            <button data-bs-toggle="modal" data-bs-target="#review-sign">
                                                Approve&nbsp;<i class="fa-regular fa-circle-xmark"></i>
                                            </button>--}}
                                        </div>
                                        @endif
                                    @endif
                                @endif
                                @if ($document->training_required == 'yes')
                                    @if ($document->stage == 5 && (Auth::user()->id == $document->trainer ||  Auth::user()->id == $document->originator_id))
                                        <input type="hidden" name="stage_id" value="6" />
                                        <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#approve-sign">
                                            Send For Training<i class="fa-regular fa-paper-plane"></i>
                                        </button>
                                    @endif
                                    @if ($document->stage == 7 && Auth::user()->id == $document->originator_id)
                                        <input type="hidden" name="stage_id" value="8" />
                                        <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#approve-sign">
                                            Send For Effective<i class="fa-regular fa-paper-plane"></i>
                                        </button>
                                    @endif
                                @elseif($document->training_required == 'no')
                                    @if ($document->stage == 5 && Auth::user()->id == $document->originator_id)
                                        <input type="hidden" name="stage_id" value="8" />
                                        <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#approve-sign">
                                            Send For Effective<i class="fa-regular fa-paper-plane"></i>
                                        </button>
                                    @endif
                                @endif


                            </div>
                            <div class="status">
                                <div class="head">Current Status</div>
                                <div class="progress-bars">
                                    @if ($document->stage >= 1)
                                        <div class="active">Draft</div>
                                    @else
                                        <div class="">Draft</div>
                                    @endif
                                    @if ($document->stage >= 2)
                                        <div class="active">In-Review</div>
                                    @else
                                        <div class="">In-Review</div>
                                    @endif
                                    @if ($document->stage == 9)
                                        <div class="active">Rejected</div>
                                    @endif
                                    @if ($document->stage >= 3)
                                        <div class="active">Reviewed</div>
                                        {{-- && $document->stage < 10 --}}
                                    @else
                                        <div class="">Reviewed</div>
                                    @endif
                                    @if ($document->stage >= 4)
                                        <div class="active">For-Approval</div>
                                        {{-- && $document->stage < 10 --}}
                                    @else
                                        <div class="">For-Approval</div>
                                    @endif
                                    {{-- @if ($document->stage == 10)
                                        <div class="active">Rejected</div>
                                    @endif --}}
                                    @if ($document->stage >= 5)
                                        <div class="active">Approved</div>
                                    @else
                                        <div class="">Approved</div>
                                    @endif
                                    @if ($document->training_required == 'yes')
                                        @if ($document->stage >= 6)
                                            <div class="active">Pending-Traning</div>
                                        @else
                                            <div class="">Pending-Traning</div>
                                        @endif
                                        @if ($document->stage >= 7)
                                            <div class="active">Traning-Complete</div>
                                        @else
                                            <div class="">Traning-Complete</div>
                                        @endif
                                    @endif
                                    @if ($document->stage >= 8)
                                        <div class="active">Effective</div>
                                    @else
                                        <div class="">Effective</div>
                                    @endif
                                    @if ($document->stage == 11)
                                        <div class="active">Obsolete</div>
                                    @else
                                        <div class="">Obsolete</div>
                                    @endif
                                    {{-- <div class="{{ $document->stage == 0 ? 'active' : '' }}">Draft</div>
                                    <div class="{{ $document->stage == 1 ? 'active' : '' }}">Reviewed</div>
                                    <div class="{{ $document->stage == 2 ? 'active' : '' }}">Approved</div>
                                    <div class="{{ $document->stage == 3 ? 'active' : '' }}">Effective</div> --}}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-4">
                        <div>
                            <div class="inner-block person-table">
                                <div class="main-title mb-0">
                                    Reviewers
                                </div>
                                <button data-bs-toggle="modal" data-bs-target="#doc-reviewers">
                                    View
                                </button>
                            </div>
                            <div class="inner-block person-table">
                                <div class="main-title mb-0">
                                    Approvers
                                </div>
                                <button data-bs-toggle="modal" data-bs-target="#doc-approvers">
                                    View
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="inner-block doc-overview">
                            <div class="main-title">Preview</div>

                            <iframe id="theFrame" width="100%" height="800"
                                src="{{ url('documents/viewpdf/' . $document->id) }}#toolbar=0"></iframe>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="modal fade modal-lg" id="doc-reviewers">
        <form action="{{ route('update-doc', $document->id) }}" method="post">
            @csrf
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title">Reviewers</h4>
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal"> <i class="fa fa-times"></i> </button>
                    </div>

                    <!-- Modal body -->

                    <div class="modal-body">
                        @if ($document->stage <= 2)
                            <div class="add-reviewer">
                                <select id="choices-multiple-remove-button" name="reviewers[]"
                                    placeholder="Select Reviewers" multiple>
                                    @if (!empty($reviewer))
                                        @foreach ($reviewer as $lan)
                                            <option value="{{ $lan->id }}">
                                                @if ($document->reviewers)
                                                    @php
                                                        $data = explode(',', $document->reviewers);
                                                        $count = count($data);
                                                        $i = 0;
                                                    @endphp
                                                    @for ($i = 0; $i < $count; $i++)
                                                        @if ($data[$i] == $lan->id)
                                                            selected
                                                        @endif
                                                    @endfor
                                                @endif>
                                                {{ $lan->name }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        @endif
                        <div class="reviewer-table table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Reviewers</th>
                                        <th>Department</th>
                                        <th>Status</th>
                                        <th>Audit Trial</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $rev_data = explode(',', $document->reviewers);
                                        $i = 0;
                                    @endphp
                                    @for ($i = 0; $i < count($rev_data); $i++)
                                        @php
                                            $user = DB::table('users')
                                                ->where('id', $rev_data[$i])
                                                ->first();
                                            $user->department = DB::table('departments')
                                                ->where('id', $user->departmentid)
                                                ->value('name');
                                                $user->status = DB::table('stage_manages')
                                                ->where('user_id', $rev_data[$i])
                                                ->where('document_id', $document->id)
                                                ->where('stage', 'Reviewed')
                                                ->where('deleted_at', null)
                                                ->latest()
                                                ->first();
                                                $user->statusReject = DB::table('stage_manages')
                                                ->where('user_id', $rev_data[$i])
                                                ->where('document_id', $document->id)
                                                ->where('stage', 'Cancel-by-Reviewer')
                                                ->where('deleted_at', null)
                                                ->latest()
                                                ->first();
                                        @endphp
                                        <tr>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->department }}</td>
                                            @if ($user->status)
                                                <td>Reviewed <i class="fa-solid fa-circle-check text-success"></i></td>
                                            @elseif($user->statusReject)
                                                <td>Rejected <i class="fa-solid fa-circle-xmark text-danger"></i></td>
                                            @else
                                                <td>Review Pending</td>
                                            @endif
                                            <td><a
                                                    href="{{ url('audit-individual/') }}/{{ $document->id }}/{{ $user->id }}"><button
                                                        type="button">Audit Trial</button></a></td>
                                        </tr>
                                    @endfor

                                </tbody>
                            </table>
                        </div>
                        <div class="modal-header">
                            <h4 class="modal-title">Reviewer Group</h4>
                        </div>

                        @if ($document->stage <= 2)
                            <div class="add-reviewer">
                                <select id="choices-multiple-remove-button" name="reviewers_group[]"
                                    placeholder="Select Reviewers" multiple>
                                    @if (!empty($reviewergroup))
                                        @foreach ($reviewergroup as $lan)
                                            <option value="{{ $lan->id }}">
                                                @if ($document->reviewers_group)
                                                    @php
                                                        $data = explode(',', $document->reviewers_group);
                                                        $count = count($data);
                                                        $i = 0;
                                                    @endphp
                                                    @for ($i = 0; $i < $count; $i++)
                                                        @if ($data[$i] == $lan->id)
                                                            selected
                                                        @endif
                                                    @endfor
                                                @endif>
                                                {{ $lan->name }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        @endif
                        @if ($document->reviewers_group)
                            <div class="reviewer-table table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Groups</th>
                                            <th>Department</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $rev_data = explode(',', $document->reviewers_group);
                                            $i = 0;
                                        @endphp
                                        @for ($i = 0; $i < count($rev_data); $i++)
                                            @php
                                                $user = DB::table('group_permissions')
                                                    ->where('id', $rev_data[$i])
                                                    ->first();
                                                $user->department = DB::table('role_groups')
                                                    ->where('id', $user->role_id)
                                                    ->value('name');
                                                $users = explode(',', $user->user_ids);

                                                $j = 0;
                                            @endphp
                                            <tr>
                                                <td>
                                                    <div>{{ $user->name }}</div>
                                                    {{-- @if (count($users) > 0)
                                                <ul>
                                                    @for ($j = 0; $j < count($users); $j++)
                                                        @php
                                                            $userdata = DB::table('users')
                                                                ->where('id', $users[$j])
                                                                ->first();
                                                            $userdata->department = DB::table('departments')
                                                                ->where('id', $userdata->departmentid)
                                                                ->value('name');
                                                            $userdata->approval = DB::table('stage_manages')
                                                                ->where('document_id', $document->id)
                                                                ->where('user_id', $users[$j])
                                                                ->latest()
                                                                ->first();
                                                        @endphp
                                                        <li><small>{{ $userdata->name }}</small></li>
                                                    @endfor

                                                </ul>
                                            @endif --}}
                                                </td>

                                                <td>{{ $user->department }}
                                                    @if (count($users) > 1)
                                                        <ul>
                                                            @for ($j = 0; $j < count($users); $j++)
                                                                @php
                                                                    $userdata = DB::table('users')
                                                                        ->where('id', $users[$j])
                                                                        ->first();

                                                                    $userdata->department = DB::table('departments')
                                                                        ->where('id', $userdata->departmentid)
                                                                        ->value('name');
                                                                    $userdata->approval = DB::table('stage_manages')
                                                                        ->where('document_id', $document->id)
                                                                        ->where('user_id', $users[$j])
                                                                        ->latest()
                                                                        ->first();
                                                                @endphp
                                                                <li><small>{{ $userdata->department }}</small></li>
                                                            @endfor

                                                        </ul>
                                                    @endif
                                                </td>
                                                @if ($document->stage >= 3)
                                                    <td>Reviewed <i class="fa-solid fa-circle-check text-success"></i>
                                                        @if (count($users) > 1)
                                                            <ul>
                                                                @for ($j = 0; $j < count($users); $j++)
                                                                    @php
                                                                        $userdata = DB::table('users')
                                                                            ->where('id', $users[$j])
                                                                            ->first();

                                                                        $userdata->department = DB::table('departments')
                                                                            ->where('id', $userdata->departmentid)
                                                                            ->value('name');
                                                                            $userdata->approval = DB::table('stage_manages')
                                                                                ->where('document_id', $document->id)
                                                                                ->where('user_id', $users[$j])
                                                                                ->where('stage', 'Review-Submit')
                                                                                ->where('deleted_at', null)
                                                                                ->latest()
                                                                                ->first();
                                                                                $userdata->reject = DB::table('stage_manages')
                                                                                ->where('document_id', $document->id)
                                                                                ->where('user_id', $users[$j])
                                                                                ->where('stage', 'Cancel-by-reviewer')
                                                                                ->where('deleted_at', null)
                                                                                ->latest()
                                                                                ->first();

                                                                    @endphp
                                                                    @if ($userdata->approval)
                                                                        <li><small>Reviewed <i
                                                                                    class="fa-solid fa-circle-check text-success"></i></small>
                                                                        </li>
                                                                    @elseif($userdata->reject)
                                                                        <li><small>Rejected <i
                                                                                    class="fa-solid fa-circle-xmark text-danger"></i></small>
                                                                        </li>
                                                                    @else
                                                    <td>Review Pending</td>
                                                    <td><a
                                                            href="{{ url('audit-individual/') }}/{{ $document->id }}/{{ $user->id }}"><button
                                                                type="button">Audit</button></a></td>

                                                @endif
                                        @endfor

                                        </ul>
                        @endif
                        </td>
                    @else
                        <td>Review Pending</td>
                        @endif
                        </tr>
                        @endfor

                        </tbody>
                        </table>
                    </div>
                    @endif
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    {{-- @if ($document->stage <= 2)
                        <button type="submit">Update</button>
                    @endif --}}
                    <button type="button" data-bs-dismiss="modal">Close</button>
                </div>
        </form>

    </div>
    </div>
    </div>

    <div class="modal fade modal-lg" id="doc-approvers">
        <form action="{{ route('update-doc', $document->id) }}" method="post">
            @csrf
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title">Approvers</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <!-- Modal body -->
                    <div class="modal-body">
                        @if ($document->stage <= 4)
                            <div class="add-reviewer">
                                <select id="choices-multiple-remove-button" name="approvers[]"
                                    placeholder="Select Reviewers" multiple>
                                    @if (!empty($approvers))
                                        @foreach ($approvers as $lan)
                                            <option value="{{ $lan->id }}"
                                                @if ($document->reviewers_group) @php
                                   $data = explode(",",$document->approvers);
                                    $count = count($data);
                                    $i=0;
                                @endphp
                                @for ($i = 0; $i < $count; $i++)
                                    @if ($data[$i] == $lan->id)
                                     selected @endif
                                                @endfor
                                        @endif>
                                        {{ $lan->name }}
                                        </option>
                                    @endforeach
                        @endif
                        </select>
                    </div>
                    @endif
                    <div class="reviewer-table table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Approvers</th>
                                    <th>Department</th>
                                    <th>Status</th>
                                    <th>Audit Trial</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $rev_data = explode(',', $document->approvers);
                                    $i = 0;
                                @endphp
                                @for ($i = 0; $i < count($rev_data); $i++)
                                    @php
                                        $user = DB::table('users')
                                            ->where('id', $rev_data[$i])
                                            ->first();
                                        $user->department = DB::table('departments')
                                            ->where('id', $user->departmentid)
                                            ->value('name');
                                        $user->status = DB::table('stage_manages')
                                            ->where('user_id', $rev_data[$i])
                                            ->where('document_id', $document->id)
                                            ->where('stage', 'Approval-submit')
                                            ->where('deleted_at', null)
                                            ->latest()
                                            ->first();
                                        $user->reject = DB::table('stage_manages')
                                            ->where('user_id', $rev_data[$i])
                                            ->where('document_id', $document->id)
                                            ->where('stage', 'Cancel-by-Approver')
                                            ->where('deleted_at', null)
                                            ->latest()
                                            ->first();
                                    @endphp
                                    <tr>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->department }}</td>
                                        @if ($user->status)
                                                <td>Approved <i class="fa-solid fa-circle-check text-success"></i></td>
                                            @elseif($user->reject)
                                                <td>Rejected <i class="fa-solid fa-circle-xmark text-danger"></i></td>
                                            @else
                                                <td>Approval Pending</td>
                                            @endif
                                        <td><a
                                                href="{{ url('audit-individual/') }}/{{ $document->id }}/{{ $user->id }}"><button
                                                    type="button">Audit</button></a></td>


                                    </tr>
                                @endfor

                            </tbody>
                        </table>
                    </div>
                    <div class="modal-header">
                        <h4 class="modal-title">Approvers Group</h4>
                    </div>
                    @if ($document->stage <= 4)
                        <div class="add-reviewer">
                            <select id="choices-multiple-remove-button" name="approver_group[]"
                                placeholder="Select Reviewers" multiple>
                                @if (!empty($approversgroup))
                                    @foreach ($approversgroup as $lan)
                                        <option value="{{ $lan->id }}"
                                            @if ($document->approver_group) @php
                                   $data = explode(",",$document->approver_group);
                                    $count = count($data);
                                    $i=0;
                                @endphp
                                @for ($i = 0; $i < $count; $i++)
                                    @if ($data[$i] == $lan->id)
                                     selected @endif
                                            @endfor
                                    @endif>
                                    {{ $lan->name }}
                                    </option>
                                @endforeach
                    @endif

                    </select>
                </div>
                @endif
                @if ($document->approver_group)
                    <div class="reviewer-table table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Groups</th>
                                    <th>Department</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $rev_data = explode(',', $document->approver_group);
                                    $i = 0;
                                @endphp
                                @for ($i = 0; $i < count($rev_data); $i++)
                                    @php
                                        $user = DB::table('group_permissions')
                                            ->where('id', $rev_data[$i])
                                            ->first();
                                        $user->department = DB::table('role_groups')
                                            ->where('id', $user->role_id)
                                            ->value('name');
                                        $users = explode(',', $user->user_ids);

                                        $j = 0;
                                    @endphp
                                    <tr>
                                        <td>
                                            <div>{{ $user->name }}</div>
                                            {{-- @if (count($users) > 0)
                                                <ul>
                                                    @for ($j = 0; $j < count($users); $j++)
                                                        @php
                                                            $userdata = DB::table('users')
                                                                ->where('id', $users[$j])
                                                                ->first();

                                                            $userdata->department = DB::table('departments')
                                                                ->where('id', $userdata->departmentid)
                                                                ->value('name');
                                                            $userdata->approval = DB::table('stage_manages')
                                                                ->where('document_id', $document->id)
                                                                ->where('user_id', $users[$j])
                                                                ->latest()
                                                                ->first();
                                                        @endphp
                                                        <li><small>{{ $userdata->name }}</small></li>
                                                    @endfor

                                                </ul>
                                            @endif --}}
                                        </td>

                                        <td>{{ $user->department }}
                                            @if (count($users) > 1)
                                                <ul>
                                                    @for ($j = 0; $j < count($users); $j++)
                                                        @php
                                                            $userdata = DB::table('users')
                                                                ->where('id', $users[$j])
                                                                ->first();
                                                            $userdata->department = DB::table('departments')
                                                                ->where('id', $userdata->departmentid)
                                                                ->value('name');
                                                            $userdata->approval = DB::table('stage_manages')
                                                                ->where('document_id', $document->id)
                                                                ->where('user_id', $users[$j])
                                                                ->latest()
                                                                ->first();
                                                        @endphp
                                                        <li><small>{{ $userdata->department }}</small></li>
                                                    @endfor

                                                </ul>
                                            @endif
                                        </td>
                                        @if ($document->stage >= 5)
                                            <td>Approved <i class="fa-solid fa-circle-check text-success"></i>
                                                @if (count($users) > 1)
                                                    <ul>
                                                        @for ($j = 0; $j < count($users); $j++)
                                                            @php
                                                                $userdata = DB::table('users')
                                                                    ->where('id', $users[$j])
                                                                    ->first();

                                                                $userdata->department = DB::table('departments')
                                                                    ->where('id', $userdata->departmentid)
                                                                    ->value('name');
                                                                    $userdata->approval = DB::table('stage_manages')
                                                                    ->where('document_id', $document->id)
                                                                    ->where('user_id', $users[$j])
                                                                    ->where('stage', 'Approval-Submit')
                                                                    ->where('deleted_at', null)
                                                                    ->latest()
                                                                    ->first();
                                                                $userdata->reject = DB::table('stage_manages')
                                                                    ->where('document_id', $document->id)
                                                                    ->where('user_id', $users[$j])
                                                                    ->where('stage', 'Cancel-by-approver')
                                                                    ->where('deleted_at', null)
                                                                    ->latest()
                                                                    ->first();

                                                            @endphp
                                                            @if ($userdata->approval)
                                                                <li><small>Approved <i
                                                                            class="fa-solid fa-circle-check text-success"></i></small>
                                                                </li>
                                                            @elseif($userdata->reject)
                                                                <li><small>Rejected <i
                                                                            class="fa-solid fa-circle-xmark text-danger"></i></small>
                                                                </li>
                                                            @else
                                            <td>Approval Pending</td>

                                        @endif
                                        <td><a
                                                href="{{ url('audit-individual/') }}/{{ $document->id }}/{{ $user->id }}"><button
                                                    type="button">Audit</button></a></td>

                                @endfor

                                </ul>
                @endif
                </td>
            @else
                <td>Approval Pending</td>
                @endif
                <td><a href="{{ url('audit-individual/') }}/{{ $document->id }}/{{ $user->id }}"><button
                            type="button">Audit</button></a></td>

                </tr>
                @endfor

                </tbody>
                </table>
            </div>
            @endif
    </div>

    <!-- Modal footer -->
    <div class="modal-footer">
        {{-- @if ($document->stage != 1)
            <button type="submit">Update</button>
        @endif --}}
        <button type="button" data-bs-dismiss="modal">Close</button>
    </div>
    </form>
    </div>
    </div>
    </div>

    <div class="modal fade" id="approve-sign">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">E-Signature</h4>
                    <!-- <button type="button" class="btn-close" data-bs-dismiss="modal"></button> -->
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal"> <i class="fa fa-times"></i> </button>

                </div>
                <!-- Modal body -->
                <form id="sendstage" action="{{ url('sendforstagechanage') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="document_id" value="{{ $document->id }}">
                    <div class="modal-body">
                        <div class="mb-3 text-justify">
                            Please select a meaning and a outcome for this task and enter your username
                            and password for this task. You are performing an electronic signature,
                            which is legally binding equivalent of a hand written signature.
                        </div>
                        <div class="group-input">
                            <label for="username">Username</label>
                            <input type="text" value="{{ old('username') }}" name="username" required>
                            @if ($errors->has('username'))
                                <p class="text-danger">User name not matched</p>
                            @endif
                        </div>
                        <div class="group-input">
                            <label for="password">Password</label>
                            <input type="password" value="{{ old('password') }}" name="password" required>
                            @if ($errors->has('username'))
                                <p class="text-danger">E-signature not matched</p>
                            @endif
                        </div>
                        <div class="group-input">
                            <label for="comment">Comment<span class="text-danger">*</span></label>
                            <input required name="comment" value="{{ old('comment') }}"/>
                        </div>
                    </div>
                    @if ($document->stage == 1)
                        <input type="hidden" name="stage_id" value="2" />
                    @endif
                    @if ($document->stage == 3)
                        <input type="hidden" name="stage_id" value="4" />
                    @endif
                    @if ($document->training_required == 'yes')
                        @if ($document->stage == 5)
                            <input type="hidden" name="stage_id" value="6" />
                        @endif
                        @if ($document->stage == 7)
                            <input type="hidden" name="stage_id" value="8" />
                        @endif
                    @else
                        @if ($document->stage == 5)
                            <input type="hidden" name="stage_id" value="8" />
                        @endif
                    @endif

                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button class="on-submit-disable-button" type="submit">Submit</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        {{-- <button>Close</button> --}}
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {

            $('#sendstage').on('submit', function(e) {
                $('.on-submit-disable-button').prop('disabled', true);
            });
        })
    </script>
    <div class="modal fade" id="signature-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">E-Signature</h4>
                    <!-- <button type="button" class="btn-close" data-bs-dismiss="modal"></button> -->
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal"> <i class="fa fa-times"></i> </button>

                </div>
                <form id="sendstage1" action="{{ url('sendforstagechanage') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="stage_id" value="11" />
                    <input type="hidden" name="document_id" value="{{ $document->id }}">
                    <!-- Modal body -->
                    <div class="modal-body">
                        <div class="mb-3 text-justify">
                            Please select a meaning and an outcome for this task and enter your username
                            and password for this task. You are performing an electronic signature,
                            which is the legally binding equivalent of a handwritten signature.
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
                            <input type="comment" name="comment"  required>
                        </div>
                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer">
                    <button class="on-submit-disable-button" type="submit">Submit</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
    $(document).ready(function() {

        $('#sendstage1').on('submit', function(e) {
            $('.on-submit-disable-button').prop('disabled', true);
        });
    })
</script>
    {{-- <div class="modal fade" id="child-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title" style="font-weight: 900">Document Revision</h4>
                </div>
                @if($document->revised === 'Yes')

                <form method="POST" action="{{ url('revision',$document->revised_doc) }}">
            @else
            <form method="POST" action="{{ url('revision',$document->id) }}">


            @endif

                    @csrf
                <!-- Modal body -->
                <div class="modal-body">
                    <div class="group-input">
                        <label for="revision">Choose Revision Version</label>
                        <label for="major">
                            Major Version<span  class="text-primary" data-bs-toggle="modal"
                            data-bs-target="#document-management-system-modal"
                            style="font-size: 0.8rem; font-weight: 400;">
                            (Launch Instruction)
                            </span>
                        </label>
                        <input type="number" name="major" id="major" min="0">

                        <label for="minor">
                            Minor Version<span  class="text-primary" data-bs-toggle="modal"
                            data-bs-target="#document-management-system-modal-minor"
                            style="font-size: 0.8rem; font-weight: 400;">
                            (Launch Instruction)
                            </span>
                        </label>
                        <input type="number" name="minor" id="minor" min="0" max="9">

                        <label for="reason">
                            Comment
                        </label>
                        <input type="text" name="reason" required>
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
    </div> --}}

    <div class="modal fade" id="child-modal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title" style="font-weight: 900">Document Revision</h4>
            </div>
                <form method="POST" action="{{ url('revision', $document->id) }}">

            @csrf

            <!-- Modal body -->
            <div class="modal-body">
                <div class="group-input">
                    <label for="major">Major Version</label>
                    <input type="number" name="major" id="major" min="0" value="{{ $newMajor = $document->minor >= 9 ? $document->major + 1 : $document->major }}" class="form-control">

                    <label for="minor">Minor Version</label>
                    <input type="number" name="minor" id="minor" min="0" max="9" value="{{ $newMinor = $document->minor >= 9 ? 1 : $document->minor + 1 }}" class="form-control">

                    <label for="reason">Comment</label>
                    <input type="text" name="reason" placeholder="Enter your comment" class="form-control">
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer">
                <button type="button" data-bs-dismiss="modal" class="btn btn-secondary">Close</button>
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>

        </form>

        </div>
    </div>
</div>


<script>
    document.getElementById('minor').addEventListener('input', function() {
        let majorInput = document.getElementById('major');
        let minorInput = parseInt(this.value, 10);
        let majorInputValue = parseInt(majorInput.value, 10);

        if (minorInput > 9) {
            majorInputValue += 1;
            minorInput.value = 1;
            majorInput.value = majorInputValue;
        }
    });
</script>


<style>
.group-input input {
width: 60%;
}
</style>
    <script>
        window.addEventListener('DOMContentLoaded', function() {
            var pdfObject = document.querySelector('iframe#theFrame"]');
            var pdfDocument = pdfObject.contentDocument;
            var firstPage = pdfDocument.querySelector('.page:first-of-type');
            firstPage.style.display = 'none';
        });
    </script>
    <script>
        // JavaScript to open modal when obsolete button is clicked
        document.getElementById('obsolete-button').addEventListener('click', function() {
            $('#signature-modal').modal('show');
        });
    </script>

<div class="modal fade" id="review-sign">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">E-Signature</h4>
                <!-- <button type="button" class="btn-close" data-bs-dismiss="modal"></button> -->
                <button type="button" class="btn btn-light" data-bs-dismiss="modal"> <i class="fa fa-times"></i> </button>

            </div>

            <!-- Modal body -->
            <form action="{{ url('sendforstagechanage') }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="document_id" value="{{ $document->id }}">
                <div class="modal-body">
                    <div class="mb-3 text-justify">
                        Please select a meaning and a outcome for this task and enter your username
                        and password for this task. You are performing an electronic signature,
                        which is legally binding equivalent of a hand written signature.
                    </div>
                    <div class="group-input">
                        <label for="username">Username <span class="text-danger">*</span></label>
                        <input type="text" value="{{ old('username') }}" name="username" required>
                        @if ($errors->has('username'))
                            <p class="text-danger">User name not matched</p>
                        @endif
                    </div>
                    <div class="group-input">
                        <label for="password">Password <span class="text-danger">*</span></label>
                        <input type="password" value="{{ old('password') }}" name="password" required>
                        @if ($errors->has('username'))
                            <p class="text-danger">E-signature not matched</p>
                        @endif
                    </div>
                    <div class="group-input">
                        <label for="comment">Comment<span class="text-danger">*</span></label>
                        <textarea required name="comment" value="{{ old('comment') }}"></textarea>
                    </div>
                </div>
                        @if ($document->stage == 2)
                            <input type="hidden" name="stage_id" value="Reviewed" />
                        @endif

                        @if ($stagereview)
                            @if ($stagereview->stage == 'Reviewed')
                                <input type="hidden" name="stage_id" value="Review-Submit" />
                            @endif
                        @endif


                        @if ($document->stage == 4)
                            <input type="hidden" name="stage_id" value="Approved" />
                        @endif

                        @if ($stageapprove)
                            @if ($stageapprove->stage == 'Approved')
                                <input type="hidden" name="stage_id" value="Approval-Submit" />
                            @endif
                        @endif
                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="submit">Submit</button>
                    <button type="button" data-bs-dismiss="modal">Close</button>
                    {{-- <button>Close</button> --}}
                </div>
            </form>

        </div>
    </div>
</div>
<div class="modal fade" id="review-cancel">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">E-Signature</h4>
                <!-- <button type="button" class="btn-close" data-bs-dismiss="modal"></button> -->
                <button type="button" class="btn btn-light" data-bs-dismiss="modal"> <i class="fa fa-times"></i> </button>

            </div>

            <!-- Modal body -->
            <form action="{{ url('sendforstagechanage') }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="document_id" value="{{ $document->id }}">
                <div class="modal-body">
                    <div class="mb-3 text-justify">
                        Please select a meaning and a outcome for this task and enter your username
                        and password for this task. You are performing an electronic signature,
                        which is legally binding equivalent of a hand written signature.
                    </div>
                    {{-- <div class="group-input">
                        <label for="electronic-meaning">Electronic Signature Approved Meaning</label>
                        <select name="electronic-meaning">
                            <option selected>- Please Select -</option>
                            <option value="cancelled">Document Cancelled</option>
                        </select>
                    </div> --}}
                    <div class="group-input">
                        <label for="username">Username <span class="text-danger">*</span></label>
                        <input type="text" value="{{ old('username') }}" name="username" required>
                        @if ($errors->has('username'))
                            <p class="text-danger">User name not matched</p>
                        @endif
                    </div>
                    <div class="group-input">
                        <label for="password">Password <span class="text-danger">*</span></label>
                        <input type="password" value="{{ old('password') }}" name="password" required>
                        @if ($errors->has('username'))
                            <p class="text-danger">E-signature not matched</p>
                        @endif
                    </div>
                    <div class="group-input">
                        <label for="comment">Comment<span class="text-danger">*</span></label>
                        <textarea required name="comment" value="{{ old('comment') }}"></textarea>
                    </div>
                </div>
                @if ($document->stage == 4)
                    <input type="hidden" name="stage_id" value="Cancel-by-Approver" />
                @endif
                @if ($document->stage == 2)
                    <input type="hidden" name="stage_id" value="Cancel-by-Reviewer" />
                @endif

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="submit">Submit</button>
                    <button type="button" data-bs-dismiss="modal">Close</button>
                    {{-- <button>Close</button> --}}
                </div>
            </form>

        </div>
    </div>
</div>
@endsection
