@extends('frontend.layout.main')
@section('container')
    {{-- ======================================
                    DASHBOARD
    ======================================= --}}

<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

    <div id="document">
        <div class="container-fluid">
            <div class="dashboard-container">
                <div class="row">
                    {{-- <div class="col-xl-12 col-lg-12">
                        <div class="document-left-block">
                            <div class="inner-block table-block">

                                <div class="table-list">
                                    <table class="table table-bordered">
                                        <thead>
                                            <th class="pr-id">
                                                ID
                                            </th>
                                            <th class="division">
                                                Document Type
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
                                            @foreach($task as $doc)
                                                <tr>
                                                    <td class="pr-id" style="text-decoration:underline">
                                                        <a href="#">
                                                            000{{$doc->id}}
                                                        </a>
                                                    </td>
                                                    <td class="division">
                                                        {{ $doc->document_type_name }}
                                                    </td>

                                                    <td class="short-desc">
                                                        {{$doc->short_description}}
                                                    </td>
                                                    <td class="create-date">
                                                        {{$doc->created_at}}
                                                    </td>
                                                    <td class="assign-name">
                                                        {{$doc->originator_name}}
                                                    </td>
                                                    <td class="modify-date">
                                                        {{$doc->updated_at}}
                                                    </td>
                                                    <td class="status">
                                                        {{$doc->status}}
                                                    </td>
                                                    <td class="action">
                                                        <div class="action-dropdown">
                                                            <div class="action-down-btn">Action <i class="fa-solid fa-angle-down"></i></div>
                                                            <div class="action-block">
                                                                <a href="{{ url('rev-details', $doc->id) }}">View</a>
                                                                <a href="{{ url('doc-details', $doc->id) }}">View Details</a>
                                                                <a href="{{ route('documents.editWithType', ['id' => $doc->id, 'type' => 'rev']) }}">Edit</a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach


                                        </tbody>


                                    </table>

                                </div>

                            </div>
                        </div>
                    </div> --}}


                    <div class="col-xl-12 col-lg-12">
                        <div class="document-left-block">
                            <div class="inner-block table-block">


                                <div class="table-list">
                                    <table class="table table-bordered" id="documentTable">
                                        <thead>
                                            <tr>
                                                <th class="pr-id">ID</th>
                                                <th class="division">Document Type</th>
                                                <th class="short-desc">Short Description</th>
                                                <th class="create-date">Create Date Time</th>
                                                <th class="assign-name">Originator</th>
                                                <th class="modify-date">Modify Date Time</th>
                                                <th class="status">Status</th>
                                                <th class="action">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($task as $doc)
                                            <tr>
                                                <td class="pr-id" style="text-decoration:underline">
                                                    <a href="#">000{{$doc->id}}</a>
                                                </td>
                                                <td class="division">{{ $doc->document_type_name }}</td>
                                                <td class="short-desc">{{$doc->short_description}}</td>
                                                <td class="create-date">{{$doc->created_at}}</td>
                                                <td class="assign-name">{{$doc->originator_name}}</td>
                                                <td class="modify-date">{{$doc->updated_at}}</td>
                                                <td class="status">{{$doc->status}}</td>
                                                <td class="action">
                                                    <div class="action-dropdown">
                                                        <div class="action-down-btn">
                                                            Action <i class="fa-solid fa-angle-down"></i>
                                                        </div>
                                                        <div class="action-block">
                                                            <a href="{{ url('rev-details', $doc->id) }}">View</a>
                                                            <a href="{{ url('doc-details', $doc->id) }}">View Details</a>
                                                            <a href="{{ route('documents.editWithType', ['id' => $doc->id, 'type' => 'rev']) }}">Edit</a>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>
                    </div>




                    {{-- <div class="col-xl-3 col-lg-3">
                        <div class="document-right-block">
                            <div class="inner-block recent-record">
                                <div class="head">
                                    Recent Records
                                </div>
                                <div class="record-list">
                                    <div>
                                        <div class="icon">
                                            <i class="fa-solid fa-gauge-high"></i>
                                        </div>
                                        <div><a href="#">DMS/TMS Dashboard</a></div>
                                    </div>
                                    <div>
                                        <div class="icon">
                                            <i class="fa-solid fa-gauge-high"></i>
                                        </div>
                                        <div><a href="#">Amit Guru</a></div>
                                    </div>
                                    <div>
                                        <div class="icon">
                                            <i class="fa-solid fa-gauge-high"></i>
                                        </div>
                                        <div><a href="#">Change Control Dashboard</a></div>
                                    </div>
                                    <div class="mb-0">
                                        <div class="icon">
                                            <i class="fa-solid fa-gauge-high"></i>
                                        </div>
                                        <div><a href="#">EQMS Home Dashboard</a></div>
                                    </div>
                                </div>
                            </div>
                            <div class="inner-block recent-items">
                                <div class="head">
                                    Recent Items (0)
                                </div>
                            </div>
                        </div>
                    </div> --}}

                </div>
            </div>
        </div>
    </div>

    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.6.8/axios.min.js" integrity="sha512-PJa3oQSLWRB7wHZ7GQ/g+qyv6r4mbuhmiDb8BjSFZ8NZ2a42oTtAq5n0ucWAwcQDlikAtkub+tPVCw4np27WCg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        $(document).ready(function() {
            let postUrl = "{{ route('record.mytaskFilter') }}";
            $('.loadingRecords').hide();

            // Function to update records
            async function updateRecords() {
                $('.loadingRecords').show();

                // Safely get the values of the form elements
                let status = $('select[name=status]').val();
                let document_type_id = $('select[name=document_type_id]').val();
                let division_id = $('select[name=division_id]').val();
                let originator_id = $('select[name=originator_id]').val();
                let search_query = $('input[name=search_query]').val();

                // Prepare data object only if values are not null or undefined
                let data = {};

                if (status) {
                    data.status = status;
                }

                if (document_type_id) {
                    data.document_type_id = document_type_id;
                }

                if (division_id) {
                    data.division_id = division_id;
                }

                if (originator_id) {
                    data.originator_id = originator_id;
                }

                if (search_query) {
                    data.search_query = search_query;
                }

                try {
                    const res = await axios.post(postUrl, data);
                    $('.record-body').html(res.data.html);
                } catch (err) {
                    console.log("Error", err.message);
                } finally {
                    $('.loadingRecords').hide();
                }
            }

            // Load default records on page load
            updateRecords();

            // Update records when filters change
            $('.filterSelect').change(function() {
                updateRecords();
            });
        });
    </script> --}}
<!-- DataTables CSS -->


<!-- jQuery (Ensure this is loaded first) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">

<!-- DataTables JS (Ensure this is loaded after jQuery) -->
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>

<!-- Your Custom Script -->
<script>
    $(document).ready(function () {
        new DataTable('#documentTable', {
            pageLength: 5,
            order: [[0, 'desc']]
        });
    });
</script>















@endsection
