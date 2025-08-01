@extends('frontend.rcms.layout.main_rcms')
@section('rcms_container')
    <section id="manual-notification">
        <div class="container-fluid">
            <div class="inner-block">
                <style>
                    .main-select{
                        width: 285px !important;
                    }
                    .main{
                        display: flex;
                         /* gap: 20px; */
                    }

                    @media (max-width:769px) {
                        .main{
                        display: block !important;
                         gap: 40px;
                    }
                    }
                </style>
                <div class="main-head">
                    {{--  Record 0000{{ $document->record }}  --}}
                </div>
                <div class="inner-block-content">
                    <div class="details">
                        {{-- <div>
                                <strong>Division/Project : </strong>
                                QMS - North America / Change Control
                            </div> --}}
                        {{--  <div>
                            <strong>Record State : </strong>
                            {{ $document->status }}
                        </div>  --}}
                         <div>
                            <strong>Division/Project : </strong>
                            {{ $document->division->name }} / {{ $document->process ? $document->process->process_name : '' }}
                        </div>
                        <div>
                            <strong>Record State : </strong>
                            {{ $document->status }}
                        </div>
                        <div>
                            <strong>Assigned To : </strong>
                            {{ $document->oreginator ? $document->oreginator->name : '' }}
                        </div>

                        <div>
                            <form action="{{ url('send-notification') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <h4 style="font-weight: bold;">Recipents :</h4>

                                <div class="main" >
                                    <div class="col-lg-4" style="display: flex; gap: 10px;">
                                        <div>To :</div>
                                        <div class="main-select" >
                                            <select multiple name="option[]" id="to">
                                                <option value="0">-- Select Recipent --</option>
                                                @php
                                                    $user = DB::table('users')->get();
                                                @endphp
                                                @foreach ($user as $value)
                                                    <option value="{{ $value->id }}"> {{ $value->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('option')
                                                <p class="text-danger">{{ $message }}</p>
                                            @enderror
                                            {{-- <label for="recipent">Add</label> --}}
                                        </div>
                                    </div>
                                    <!-- CC Field -->
                                    <div class="col-lg-4" style="display: flex; gap: 10px;">
                                      <div>CC :</div>
                                        <div class="main-select">

                                            <select multiple name="cc[]" id="cc">
                                                <option value="0">-- Select Recipent --</option>
                                                @foreach ($user as $value)
                                                    <option value="{{ $value->id }}"> {{ $value->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <!-- BCC Field -->
                                    <div class="col-lg-4" style="display: flex; gap: 10px;">
                                      <div>BCC :</div>
                                        <div class="main-select">
                                            <select multiple name="bcc[]" id="bcc">
                                                <option value="0">-- Select Recipent --</option>
                                                @foreach ($user as $value)
                                                    <option value="{{ $value->id }}"> {{ $value->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>


                                {{-- <div class="recipent-table">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Recipent</th>
                                                <th>Relationship</th>
                                                <th>Method</th>

                                            </tr>
                                        </thead>
                                        <tbody id="my-table-body">

                                        </tbody>
                                    </table>
                                </div> --}}
                                <div class="summary">
                                    <div class="group-input">
                                        <label for="summary">Subject</label>
                                        <input style="width: 100%;" name="subject">
                                    </div>

                                </div>
                                <div class="summary">
                                    <div class="group-input">
                                        <label for="summary">Notification Summary</label>
                                        <textarea class="tiny" name="summary"></textarea>
                                    </div>

                                </div>
                                <div class="summary">
                                    <div class="group-input">
                                        <label for="file-attachment">Attachment</label>
                                        <input style="width: 100%" type="file" name="file_attachment" id="file_attachment">
                                    </div>
                                </div>
                                <div class="noti-btns">
                                    <button type="submit">Send</button>
                                    <a href="{{ url('rcms/qms-dashboard') }}"> <button>Cancel</button></a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    {{-- <script>
        $(document).ready(function() {
            $('#my-select').change(function() {
                var selectedOption = $(this).val();

                // Send an AJAX request to fetch the data for the selected option
                $.ajax({
                    url: '/get-data',
                    type: 'GET',
                    data: {
                        option: selectedOption
                    },

                    success: function(response) {

                        // Update the table with the selected data
                        $('#my-table-body').html(`
                            <tr>
                                <th>${response.name}<input type="hidden" value="${response.name}"></th>
                                <th>${response.role}<input type="hidden" value="${response.role}"></th>
                                <th>
                                        <select name="method" required>
                                            <option>-- Select --</option>
                                            <option value="email">E-Mail</option>
                                        </select>
                                    </th>
                            </tr>
                        `);
                    },
                    error: function(xhr, status, error) {}
                });
            });
        });
    </script> --}}

    <script>
        VirtualSelect.init({
            ele: '#to, #cc, #bcc'
        });
    </script>
@endsection
