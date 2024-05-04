@extends('frontend.rcms.layout.main_rcms')
@section('rcms_container')
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" ></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" ></script>
    <script>
        function openTab(tabName, ele) {
            let buttons = document.querySelector('.process-groups').children;
            let tables = document.querySelector('.process-tables-list').children;
            for (let element of Array.from(buttons)) {
                element.classList.remove('active');
            }
            ele.classList.add('active')
            for (let element of Array.from(tables)) {
                element.classList.remove('active');
                if (element.getAttribute('id') === tabName) {
                    element.classList.add('active');
                }
            }
        }
    </script>
    <style>
        #internal-audit > div.table-block > div > table > thead > tr > th:nth-child(2) > div > ul{
            top: 10px !important;
    left: -10px !important;
    overflow-y: auto;
    height: 160px;
        }
        #internal-audit > div.table-block > div > table > thead > tr > th:nth-child(3) > div > ul{
            top: 10px !important;
    left: -10px !important;  
    overflow-y: auto;
    height: 160px;
        }
        #internal-audit > div.table-block > div > table > thead > tr > th:nth-child(4) > div > ul{
            top: 10px !important;
    left: -10px !important; 
    overflow-y: auto;
    height: 160px; 
        }
        #internal-audit > div.table-block > div > table > thead > tr > th:nth-child(5) > div > ul{
            top: 10px !important;
    left: -10px !important;
    overflow-y: auto;
    height: 160px;  
        }
        #internal-audit > div.table-block > div > table > thead > tr > th:nth-child(6) > div > ul{
            top: 10px !important;
    left: -10px !important;  
    overflow-y: auto;
    height: 160px;
        }
        #internal-audit > div.table-block > div > table > thead > tr > th:nth-child(8) > div > ul{
            top: 10px !important;
    left: -10px !important; 
    overflow-y: auto;
    height: 160px;   
        }
        #internal-audit > div.table-block > div > table > thead > tr > th:nth-child(9) > div > ul{
            top: 10px !important;
    left: -10px !important;  
    overflow-y: auto;
    height: 160px;  
        }

       
    </style>
    
    <style>
        header .header_rcms_bottom {
            display: none;
        }
        .filter-sub{
            display: flex;
    gap: 16px;
    /* justify-content: center; */
    margin-left: 13px
        }
    </style>
    <div id="rcms-desktop">

        <div class="process-groups">
            <div class="active" onclick="openTab('internal-audit', this)">Training Log </div>
            
        </div>

        <div class="main-content">
            <div class="container-fluid">
                <div class="process-tables-list">

                    <div class="process-table active" id="internal-audit">

                        <div class="scope-bar">
                            {{-- <div class="group-input">
                                <label for="query">Criteria</label>
                                <select id="query" name="stage">
                                    <option value="all_records">All Records</option>
                                    <option value="1">Closed Records</option>
                                    <option value="2">Opened Records</option>
                                    <option value="3">Cancelled Records</option>
                                    <option value="4">Overdue Records</option>
                                    <option value="5">Assigned To Me</option>
                                    <option value="6">Records Created Today</option>
                                </select>
                            </div> --}}
                            <button style="width: 70px;" onclick="window.print()" class="print-btn theme-btn-1">Print</button>
                        </div>

                        <div class="table-block">
                            <div class="table-responsive" style="height: 300px">
                                @php
                                $userTraining = DB::table('trainings')->where('trainner_id', Auth::user()->id)->get();
                            @endphp
                            <table class="table table-bordered" style="width: 120%;">
                                <thead>
                                    <tr>
                                        <th>Sr No.</th>
                                        <th><div class="dropdown">
                                            <a class=" dropdown-toggle" type="button" data-toggle="dropdown">Employee Name
                                            &nbsp; &nbsp;&nbsp; &nbsp;<span class="caret"></span></a>
                                            <ul style="width: 180px; top: 10px;
                                            left: -13px;" class="dropdown-menu">
                                              <div class="filter-main">
                                                <div class="filter-sub">
                                                    <input name="employee_name" type="checkbox">
                                                <li><a href="#">Employee Name</a></li>
                                                </div>
                                                <div class="filter-sub">
                                                    <input type="checkbox">
                                                <li><a href="#">Employee Name</a></li>
                                                </div>
                                                <div class="filter-sub">
                                                    <input type="checkbox">
                                                <li><a href="#">Employee Name</a></li>
                                                </div>
                                        
                                              </div>
                                            </ul>
                                          </div></th>
                                          <th><div class="dropdown">
                                            <a class=" dropdown-toggle" type="button" data-toggle="dropdown">Document Title
                                            &nbsp; &nbsp;&nbsp; &nbsp;<span class="caret"></span></a>
                                            <ul style="width: 180px;" class="dropdown-menu">
                                              <div class="filter-main">
                                                <div class="filter-sub">
                                                    <input name="document_title" type="checkbox">
                                                <li><a href="#">Document Title</a></li>
                                                </div>
                                                <div class="filter-sub">
                                                    <input type="checkbox">
                                                <li><a href="#">Document Title</a></li>
                                                </div>
                                                <div class="filter-sub">
                                                    <input type="checkbox">
                                                <li><a href="#">Document Title</a></li>
                                                </div>
                                                <div class="filter-sub">
                                                    <input type="checkbox">
                                                <li><a href="#">Document Title</a></li>
                                                </div>
                                              </div>
                                            </ul>
                                          </div>
                                        </th>

                                        <th><div class="dropdown">
                                            <a class=" dropdown-toggle" type="button" data-toggle="dropdown">Training Plan Name
                                            &nbsp; &nbsp;&nbsp; &nbsp;<span class="caret"></span></a>
                                            <ul style="width: 190px;" class="dropdown-menu">
                                              <div class="filter-main">
                                                <div class="filter-sub">
                                                    <input name="training_plan_name" type="checkbox">
                                                <li><a href="#">Trainig Plan Name</a></li>
                                                </div>
                                                <div class="filter-sub">
                                                    <input type="checkbox">
                                                <li><a href="#">Trainig Plan Name</a></li>
                                                </div>
                                                <div class="filter-sub">
                                                    <input type="checkbox">
                                                <li><a href="#">Trainig Plan Name</a></li>
                                                </div>
                                                <div class="filter-sub">
                                                    <input type="checkbox">
                                                <li><a href="#">Trainig Plan Name</a></li>
                                                </div>
                                              </div>
                                            </ul>
                                          </div>
                                        </th>
                                          <th><div class="dropdown">
                                            <a class=" dropdown-toggle" type="button" data-toggle="dropdown">Status
                                            &nbsp; &nbsp;&nbsp; &nbsp;<span class="caret"></span></a>
                                            <ul style="width: 180px;" class="dropdown-menu">
                                              <div class="filter-main">
                                                <div class="filter-sub">
                                                    <input name="pass" type="checkbox">
                                                <li><a href="#">Pass</a></li>
                                                </div>
                                                <div class="filter-sub">
                                                    <input name="fail" type="checkbox">
                                                <li><a href="#">Fail</a></li>
                                                </div>
                                                <div class="filter-sub">
                                                    <input name="pending_training" type="checkbox">
                                                <li><a href="#">Panding/Training</a></li>
                                                </div>
                                               
                                              </div>
                                            </ul>
                                          </div>
                                        </th>
                                        
                                        <th style="width: 10%;">Due Date</th>
                                        <th style="width: 15%;">Training Completion Date</th>
                                        <th><div class="dropdown">
                                            <a class=" dropdown-toggle" type="button" data-toggle="dropdown">Classroom Trainer
                                            &nbsp; &nbsp;&nbsp; &nbsp;<span class="caret"></span></a>
                                            <ul style="width: 180px;" class="dropdown-menu">
                                              <div class="filter-main">
                                                <div class="filter-sub">
                                                    <input name="due_date" type="checkbox">
                                                <li><a href="#">ClassRoom Trainer</a></li>
                                                </div>
                                                <div class="filter-sub">
                                                    <input type="checkbox">
                                                <li><a href="#">ClassRoom Trainer</a></li>
                                                </div>
                                                <div class="filter-sub">
                                                    <input type="checkbox">
                                                <li><a href="#">ClassRoom Trainer</a></li>
                                                </div>
                                               
                                              </div>
                                            </ul>
                                          </div>
                                        </th>
                                          <th><div class="dropdown">
                                            <a class=" dropdown-toggle" type="button" data-toggle="dropdown">Training Coordinator
                                            &nbsp; &nbsp;&nbsp; &nbsp;<span class="caret"></span></a>
                                            <ul style="width: 180px;" class="dropdown-menu">
                                              <div class="filter-main">
                                                <div class="filter-sub">
                                                    <input name="training_coordinator" type="checkbox">
                                                <li><a href="#">Training Coordinator</a></li>
                                                </div>
                                                <div class="filter-sub">
                                                    <input type="checkbox">
                                                <li><a href="#">Training Coordinator</a></li>
                                                </div>
                                                <div class="filter-sub">
                                                    <input type="checkbox">
                                                <li><a href="#">Training Coordinator</a></li>
                                                </div>
                                            
                                              </div>
                                            </ul>
                                          </div>
                                        </th>
                                       

                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach($userTraining as $training)
                                        $trainees = explode(',', $training->trainees);
                                        $trainingUsers = DB::table('users')->whereIn('id', $trainees)->get();
                                        $sopIds = explode(',', $training->sops); 
                                        $trainingDoc = DB::table('documents')->whereIn('id', $sopIds)->get();
                                        <tr>
                                           
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td>{{ $training->traning_plan_name }}</td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
 
                                         </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                        </div>
                        
                    </div>

                </div>
            </div>
        </div>

    </div>

    <script>
        VirtualSelect.init({
            ele: '#Facility, #Group, #Audit, #Auditee ,#capa_related_record ,#classRoom_training' 
        });
    </script>
    
@endsection
