<div class="main-head">
    <div>Records</div>
    <div>
        {{ count($documents) }} Results {{ isset($count) ? ' out of Results  ' .  $count : 'found' }}
    </div>
</div>
<div style="overflow: auto; height: 400px;" class="table-list">
    <table class="table table-bordered">
        <thead>
            <th class="pr-id" data-bs-toggle="modal" data-bs-target="#division-modal">
                ID
            </th>
            <th class="division">
                Document Type
            </th>
            <th class="division">
                Document Name
            </th>
            <th class="division">
                SOP No.
            </th>
            <th class="division">
                Division
            </th>
            <th class="short-desc">
                Short Description
            </th>
            <th class="create-date">
                Create Date Time
            </th>
            <th class="assign-name">
                Originator
            </th>
            <th class="modify-date">
                Modify Date Time
            </th>
            <th class="status">
                Status
            </th>
            <th class="action">
                Action
            </th>
        </thead>
        <tbody id="searchTable">
            @if (count($documents) > 0)
            {{-- {{dd($documents);}} --}}
            @foreach ($documents->sortByDesc('id') as $doc)
            @php
                                            $userRoles = DB::table('user_roles')
                                            ->where(['user_id' => auth()->id(), 'q_m_s_divisions_id' => $doc->division_id])
                                            ->pluck('q_m_s_roles_id')
                                            ->toArray();

                                            $stagesToHide = [
                                                'Obsolete'
                                            ];

                                            // Check if the stage is in the stagesToHide array
                                            $hideRecord = in_array($doc->status, $stagesToHide);

                                            // dd($hideRecord);
                                            // Check if the user has one of the allowed roles
                                            $userHasAllowedRole = in_array(19, $userRoles);
                                        @endphp

                                        {{-- @if(!$hideRecord || $userHasAllowedRole)
            @endphp --}}
            @if(!$hideRecord || $userHasAllowedRole)
                <tr>
                    <td class="pr-id" style="text-decoration:underline"><a href="{{ route('documents.edit', $doc->id) }}">
                            000{{ $doc->id }}
                        </a>
                    </td>
                    <td class="division">
                        {{ $doc->document_type_name }}
                    </td>
                    <td class="division">
                        {{ $doc->document_name }}
                    </td>
                    <td class="division">
                        {{ $doc->sop_no }}
                    </td>
                    <td class="division">
                        {{ Helpers::getDivisionName($doc->division_id) }}
                    </td>

                    <td style="display: inline-block;
                    width: 305px;
                    white-space: nowrap;
                    overflow: hidden !important;
                    text-overflow: ellipsis" class="short-desc">
                        {{ $doc->short_description }}
                    </td>
                    <td class="create-date">
                        {{ $doc->created_at }}
                    </td>
                    <td class="assign-name">
                        {{ $doc->originator_name }}
                    </td>
                    <td class="modify-date">
                        {{ $doc->updated_at }}
                    </td>
                    <td class="status">
                        {{ $doc->status }}
                    </td>
                    <td class="action">
                        <div class="action-dropdown">
                            <div class="action-down-btn">Action <i class="fa-solid fa-angle-down"></i></div>
                            <div class="action-block">
                                <a href="{{ url('doc-details', $doc->id) }}">View
                                </a>

                                @if ($doc->status != 'Obsolete')
                                    {{-- <a href="{{ route('documents.edit', $doc->id) }}">Edit</a> --}}
                                    <a href="{{ route('documents.editWithType', ['id' => $doc->id, 'type' => 'doc']) }}">Edit</a>

                                @endif

                                <!--<form-->
                                <!--    action="{{ route('documents.destroy', $doc->id) }}"-->
                                <!--    method="post">-->
                                <!--    @csrf-->
                                <!--    @method('DELETE')-->
                                <!--    <button type="submit">Delete</button>-->
                                <!--</form>-->

                            </div>
                        </div>
                    </td>
                </tr>
            @endif
            @endforeach
            @else
            <center>
                <h5>Data not Found</h5>
            </center>
            @endif

        </tbody>
    </table>
    @if (isset($count))
        {!! $documents->links() !!}
    @endif
</div>
