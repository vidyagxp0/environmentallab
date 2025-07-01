@extends('frontend.layout.main')
@section('container')
    {{-- ======================================
                    DASHBOARD
    ======================================= --}}
    <div id="audit-trial">
        <div class="container-fluid">
            <div class="audit-trial-container">

                <div class="inner-block">
                    <div class="main-head">
                        <div class="default-name">
                            {{ Helpers::getDivisionName($document->division_id) }}/LI/{{ date('Y') }}/{{ str_pad($document->record, 4, '0', STR_PAD_LEFT) }}
                        </div>

                            <div class="btn-group">
                                <a href="{{ url('rcms/LabIncidentAuditReport',$document->id)}}"> <button type="button">Print</button></a>
                            </div>
                    </div>
                    <div class="info-list">
                        <div class="list-item">
                            <div class="head">Document Stage</div>
                            <div>:</div>
                            <div>{{ $document->status }}</div>
                        </div>
                        <div class="list-item">
                            <div class="head">Originator</div>
                            <div>:</div>
                            <div>{{ $document->initiator }}</div>
                        </div>
                    </div>

                    <div class="activity-table">
                        <table class="table table-bordered" id='auditTable'>
                            <thead>
                                <tr>
                                    <th>Activity Type</th>
                                    <th>Performed on</th>
                                    <th>Performed by</th>
                                    {{-- <th>Performer Role</th> --}}
                                    <th>Origin State</th>
                                    <th>Resulting State</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($audit as $audits)
                                    <tr>
                                        <td class="viewdetails"><a
                                                href="{{ route('LabIncidentauditDetails', $audits->id) }}">{{ $audits->activity_type }}</a>
                                        </td>
                                        <td>{{ Helpers::getdateFormat1($audits->created_at) }}</td>
                                        <td>{{ $audits->user_name }}</td>
                                        {{-- <td>{{ $audits->user_role }}</td> --}}
                                        <td>{{ $audits->origin_state }}</td>
                                        <td>{{ $document->status }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="modal fade" id="activity-modal">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <h4 class="modal-title">SOP-{{ $document->id }}</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="info-list">
                        <div class="list-item">
                            <div class="head">Site/Division/Process</div>
                            <div>:</div>
                            <div>{{ $document->division }}/{{ $document->process }}</div>
                        </div>
                        <div class="list-item">
                            <div class="head">Document Stage</div>
                            <div>:</div>
                            <div>{{ $document->status }}</div>
                        </div>
                        <div class="list-item">
                            <div class="head">Originator</div>
                            <div>:</div>
                            <div>{{ $document->originator }}</div>
                        </div>
                    </div>
                    <div id="auditTableinfo"></div>

                </div>

            </div>
        </div>
    </div>
    <script type='text/javascript'>
        $(document).ready(function() {

            $('#auditTable').on('click', '.viewdetails', function() {
                var auditid = $(this).attr('data-id');

                if (auditid > 0) {

                    // AJAX request
                    var url = "{{ route('audit-details', [':auditid']) }}";
                    url = url.replace(':auditid', auditid);

                    // Empty modal data
                    $('#auditTableinfo').empty();

                    $.ajax({
                        url: url,
                        dataType: 'json',
                        success: function(response) {

                            // Add employee details
                            $('#auditTableinfo').append(response.html);

                            // Display Modal
                            $('#activity-modal').modal('show');
                        }
                    });
                }
            });

        });
    </script>
@endsection
