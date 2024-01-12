<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
    <title>Connexo - Software</title>
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.bootstrap3.min.css"
        integrity="sha256-ze/OEYGcFbPRmvCnrSeKbRTtjG4vGLHXgOqsyLFTRjg=" crossorigin="anonymous" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/bbbootstrap/libraries@main/choices.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"
        integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous">
    </script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
    <script src="https://cdn.tiny.cloud/1/nlsiabbt295w89cjmcocv6qjdg3k7ozef0q9meowv2nkwyd3/tinymce/6/tinymce.min.js">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <link rel="stylesheet" href="{{ asset('user/css/virtual-select.min.css') }}">
    <script src="{{ asset('user/js/virtual-select.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('user/css/style.css') }}">
    {{-- @toastr_css --}}
</head>

<body>

    <style>
        #create-record-button {
            display: none;
            margin-left: auto;
        }
    </style>


    {{-- ======================================
                    PRELOADER
    ======================================= --}}
    {{-- <div id="preloader">
        <span class="loader"></span>
    </div> --}}


    {{-- ======================================
                    HEADER
    ======================================= --}}

    <header>

        {{-- Header Top --}}
        <div class="container-fluid header-top">
            <div class="container">
                <div class="text-center text-light">
                    <small>Connexo.io</small>
                </div>
            </div>
        </div>

        {{-- Header Middle --}}
        <div class="container-fluid header-middle">
            <div>
                <div class="middle-head">
                    <div class="logo-container">
                        <div class="logo">
                            <img src="{{ asset('user/images/logo.png') }}" alt="..." class="w-100 h-100">
                        </div>
                        <div class="logo">
                            <img src="{{ asset('user/images/logo1.png') }}" alt="..." class="w-100 h-100">
                        </div>
                    </div>
                    <div class="search-bar">
                        <form action="#" class="w-100">
                            <label for="search"><i class="fa-solid fa-magnifying-glass"></i></label>
                            <input id="searchInput" type="text" name="search" placeholder="Search">
                            <div data-bs-toggle="modal" data-bs-target="#advanced-search">Advanced Search</div>
                        </form>
                    </div>
                    <div class="icon-grid">
                        <div class="icon-drop">
                            <div class="icon">
                                <i class="fa-solid fa-user-gear"></i>
                                <i class="fa-solid fa-angle-down"></i>
                            </div>
                            <div class="icon-block">
                                <div><a id="/form-division">Quality Management System</a></div>
                                <div><a data-bs-toggle="modal" data-bs-target="#import-export-modal">
                                        Import/Export Terms
                                    </a></div>
                                <div><a href="#">MedDRA</a></div>
                                <div><a href="#">Report Duplicate Translate Terms</a></div>
                                <div><a href="#">Spellcheck Languages</a></div>
                                <div><a href="#">Spellcheck Settings</a></div>
                                <div><a href="#">Translate Terms</a></div>
                                <div><a href="/designate-proxy">Designate Proxy</a></div>
                            </div>
                        </div>
                        <div class="icon-drop">
                            <div class="icon">
                                <i class="fa-solid fa-user-tie"></i>
                                @if (Auth::user())
                                    {{ Auth::user()->name }}
                                @else
                                    Amit Guru
                                @endif
                                <i class="fa-solid fa-angle-down"></i>
                            </div>
                            <div class="icon-block small-block">
                                <div class="image">
                                    @if (Auth::user())
                                        @if (Auth::user()->role == 3)
                                            <img src="{{ asset('user/images/amit_guru.jpg') }}" alt="..."
                                                class="w-100 h-100">
                                        @else
                                            <img src="{{ asset('user/images/login.jpg') }}" alt="..."
                                                class="w-100 h-100">
                                        @endif
                                    @else
                                        <img src="{{ asset('user/images/amit_guru.jpg') }}" alt="..."
                                            class="w-100 h-100">
                                    @endif

                                </div>
                                <div data-bs-toggle="modal" data-bs-target="#setting-modal">Settings</div>
                                <div data-bs-toggle="modal" data-bs-target="#about-modal">About</div>
                                <div><a href="#">Help</a></div>
                                <div><a href="/helpdesk-personnel">Helpdesk Personnel</a></div>
                                <div><a href="{{ route('logout') }}">Log Out</a></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Header Bottom --}}
        <div class="container-fluid header-bottom">
            <div class="container-fluid">
                <div class="bottom-links">
                    <div>
                        <a href="#"><i class="fa-solid fa-braille"></i></a>
                    </div>
                    <div>
                        <a href="/dashboard">DMS Dashboard</a>
                    </div>
                    <div>
                        <a href="/TMS">TMS Dashboard</a>
                    </div>
                    <div><a href="/rcms/qms-dashboard">QMS-Dshboard</a></div>
                    @if (Auth::user())
                        @if (Auth::user()->role == 3 || Auth::user()->role == 1 || Auth::user()->role == 2)
                            <div>
                                <a href="/mydms">My DMS</a>
                            </div>
                        @endif
                        @if (Auth::user()->role == 3)
                            <div>
                                <a href="{{ route('documents.index') }}">Documents</a>
                            </div>
                        @endif
                        @if (Auth::user()->role == 1 || Auth::user()->role == 2)
                            <div>
                                <a href="{{ url('mytaskdata') }}">My Tasks</a>
                            </div>
                        @endif
                        @if (Auth::user()->role == 4 || Auth::user()->role == 5 || Auth::user()->role == 3)
                            <div>
                                <a href="{{ route('change-control.index') }}">My Tasks</a>
                            </div>
                        @endif
                    @endif


                    {{-- <div class="notification">
                        <a href="/notifications"><i class="fa-solid fa-bell"></i></a>
                    </div> --}}
                    <div id="create-record-button">
                        <a href="{{ url('rcms/form-division') }}"> <button class="button_theme1">Create
                                Record</button> </a>
                    </div>
                </div>
            </div>
        </div>
    </header>




    {{-- ======================================
                    SETTING MODAL
    ======================================= --}}
    <div class="modal fade" id="setting-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h4 class="modal-title">User's Settings</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="image">
                        <img src="{{ asset('user/images/login.jpg') }}" alt="..." class="w-100 h-100">
                    </div>
                    <div class="bar">
                        <strong>Name : </strong> Amit Guru
                    </div>
                    <div class="bar">
                        <strong>E-Mail : </strong> amit.guru@connexo.io
                    </div>
                    <div class="bar">
                        <a href="#">Change Password</a>
                    </div>
                </div>

            </div>
        </div>
    </div>




    {{-- ======================================
                    ABOUT MODAL
    ======================================= --}}
    <div class="modal fade" id="about-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h4 class="modal-title">About</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="logo">
                        <img src="{{ asset('user/images/logo.png') }}" alt="..." class="w-100 h-100">
                    </div>
                    <div class="bar">
                        <strong>Version : </strong> 10.0.0
                    </div>
                    <div class="bar">
                        <strong>Build # : </strong> 2
                    </div>
                    <div class="bar">
                        April 23, 2023
                    </div>
                    <div class="bar">
                        <strong>Licensed to : </strong> Connexo
                    </div>
                    <div class="bar">
                        <strong>Environment : </strong> Master Demo Dev
                    </div>
                    <div class="bar">
                        <strong>Server : </strong> SCRRREVE3 (100.23.34.0)
                    </div>
                    <div class="copyright-bar">
                        <i class="fa-regular fa-copyright"></i>&nbsp;
                        Copyright 2023 Connexo Asia Limited
                    </div>
                </div>

            </div>
        </div>
    </div>




    {{-- ======================================
                IMPORT EXPORT MODAL
    ======================================= --}}
    <div class="modal fade" id="import-export-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h4 class="modal-title">Import/Export Terms</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                </div>

            </div>
        </div>
    </div>




    {{-- ============================================
                RELATED RECORD MODAL
    ============================================= --}}
    <div class="modal fade" id="related-records-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h4 class="modal-title">Related Records</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="block">
                        <label for="record_1">
                            <input type="checkbox" name="record" id="record_1" />
                            <div>EHS - North America/CAPA/2023/0001</div>
                        </label>
                    </div>
                    <div class="block">
                        <label for="record_2">
                            <input type="checkbox" name="record" id="record_2" />
                            <div>EHS - North America/CAPA/2023/0002</div>
                        </label>
                    </div>
                    <div class="block">
                        <label for="record_3">
                            <input type="checkbox" name="record" id="record_3" />
                            <div>EHS - North America/CAPA/2023/0003</div>
                        </label>
                    </div>
                    <div class="block">
                        <label for="record_4">
                            <input type="checkbox" name="record" id="record_4" />
                            <div>EHS - North America/CAPA/2023/0004</div>
                        </label>
                    </div>
                </div>

                <div class="modal-footer justify-content-end">
                    <button class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add</button>
                </div>

            </div>
        </div>
    </div>

    {{-- ============================================
                FISHBONE INSTRUCTION MODAL
    ============================================= --}}
    <div class="modal fade" id="fishbone-instruction-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h4 class="modal-title">Cause and Effect Diagram Instructions</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <ol class="list-group">
                        <li class="list-group-item">
                            <strong>Enter the Problem Statement:</strong>
                            <p>Clearly articulate the problem or effect that the team is addressing. Use a concise and
                                specific statement to guide the analysis. Enter this statement in the box provided at
                                the head of the diagram.</p>
                        </li>

                        <li class="list-group-item">
                            <strong>Brainstorm Major Categories:</strong>
                            <p>Encourage team members to brainstorm and identify major categories related to the
                                problem. Use the generic headings provided (Measurement, Materials, Method, Environment,
                                Manpower, Machine, Mentor) as a starting point. Allow for flexibility in creating
                                additional categories as needed.</p>
                        </li>

                        <li class="list-group-item">
                            <strong>Write Categories of Causes:</strong>
                            <p>For each major category identified, write it as a branch extending from the main arrow.
                                Use lines or "bones" to represent these branches, connecting them to the main arrow.
                                Ensure that each category is clearly labeled.</p>
                        </li>

                        <li class="list-group-item">
                            <strong>Detailed Causes as Sub-branches:</strong>
                            <p>Beneath each major category, encourage the team to further detail specific causes. Create
                                sub-branches extending from the main category branches to represent these detailed
                                causes. Use these sub-branches to break down causes into more specific elements.</p>
                        </li>

                        <li class="list-group-item">
                            <strong>Prioritize and Analyze:</strong>
                            <p>After identifying a comprehensive list of potential causes, work as a team to prioritize
                                them. Discuss the potential impact and likelihood of each cause on the problem. Consider
                                using a prioritization method such as voting or consensus.</p>
                        </li>

                        <li class="list-group-item">
                            <strong>Use Visual Elements:</strong>
                            <p>Enhance the clarity and visual appeal of the diagram by using colors, shapes, or icons to
                                differentiate categories. Ensure that labels and text are legible and clearly written.
                                Use a large enough format to accommodate detailed information.</p>
                        </li>

                        <li class="list-group-item">
                            <strong>Facilitate Open Communication:</strong>
                            <p>Create an environment that fosters open communication and collaboration during the
                                brainstorming process. Encourage team members to share their insights and perspectives
                                on potential causes. Use the diagram as a visual aid to facilitate discussions.</p>
                        </li>

                        <li class="list-group-item">
                            <strong>Review and Refine:</strong>
                            <p>Periodically review and refine the Cause and Effect Diagram as more information becomes
                                available. Update the diagram based on feedback, additional data, or changes in the
                                understanding of the problem.</p>
                        </li>

                        <li class="list-group-item">
                            <strong>Document Action Items:</strong>
                            <p>If applicable, document action items or potential solutions next to identified causes.
                                This helps in planning and implementing corrective actions to address the root causes.
                            </p>
                        </li>

                        <li class="list-group-item">
                            <strong>Follow-up and Continuous Improvement:</strong>
                            <p>Use the Cause and Effect Diagram as a tool for continuous improvement. Monitor the
                                effectiveness of implemented solutions and adjust the diagram as needed. Encourage
                                ongoing collaboration and learning within the team.</p>
                        </li>
                    </ol>
                </div>

            </div>
        </div>
    </div>

    {{-- ============================================
                WHY WHY CHART INSTRUCTION MODAL
    ============================================= --}}
    <div class="modal fade" id="why_chart-instruction-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h4 class="modal-title">Why-Why Analysis: Understanding its Use and Speciality</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="row mt-4">
                        <div class="col-12">
                            <h5 class="fw-bold">1. Root Cause Identification:</h5>
                            <p>Why-Why analysis is a systematic method designed to uncover the root causes of a problem
                                or an undesired outcome. It helps in going beyond surface-level symptoms to delve into
                                the fundamental reasons behind an issue.</p>
                        </div>

                        <div class="col-12">
                            <h5 class="fw-bold">2. Sequential Questioning:</h5>
                            <p>The technique involves asking "Why" repeatedly in a sequential manner, typically five
                                times, to drill down into the deeper layers of causation. By iteratively asking why, the
                                analysis moves from the initial problem to its underlying causes.</p>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <h5 class="fw-bold">3. Preventing Recurrence:</h5>
                            <p>Why-Why analysis aims not only to solve the current problem but also to prevent its
                                recurrence by addressing the core issues. It is a proactive approach to quality
                                improvement and risk mitigation.</p>
                        </div>

                        <div class="col-12">
                            <h5 class="fw-bold">4. Systematic Problem-Solving:</h5>
                            <p>The process provides a structured framework for problem-solving, ensuring a methodical
                                and organized approach to addressing issues. It guides teams through a logical sequence,
                                fostering comprehensive understanding.</p>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <h5 class="fw-bold">5. Cross-functional Collaboration:</h5>
                            <p>Why-Why analysis is conducive to collaborative efforts, involving individuals from
                                different departments or disciplines. It encourages diverse perspectives, leading to a
                                more holistic understanding of the problem.</p>
                        </div>

                        <div class="col-12">
                            <h5 class="fw-bold">6. Visual Representation:</h5>
                            <p>Often presented in the form of a Why-Why analysis chart or diagram, the visual
                                representation aids in communicating complex causation relationships. Visualizing the
                                chain of "Whys" enhances clarity and comprehension.</p>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <h5 class="fw-bold">7. Continuous Improvement Tool:</h5>
                            <p>Beyond immediate problem resolution, Why-Why analysis is integral to continuous
                                improvement initiatives. It aligns with the principles of Kaizen, fostering an
                                environment of ongoing enhancement.</p>
                        </div>

                        <div class="col-12">
                            <h5 class="fw-bold">8. Speciality in Complex Problem Solving:</h5>
                            <p>Particularly effective in situations where problems are multifaceted and their origins
                                are not immediately apparent. It excels in addressing complex issues by breaking them
                                down into manageable components.</p>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <h5 class="fw-bold">9. Data-Driven Decision Making:</h5>
                            <p>Why-Why analysis relies on factual information and data to support each "Why" question.
                                It promotes evidence-based decision-making, reducing reliance on assumptions or
                                subjective opinions.</p>
                        </div>

                        <div class="col-12">
                            <h5 class="fw-bold">10. Feedback Loop Establishment:</h5>
                            <p>The process establishes a feedback loop by evaluating the effectiveness of implemented
                                solutions. This iterative nature ensures that adjustments can be made, and improvements
                                sustained over time.</p>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>

    {{-- ============================================
                FISHBONE INSTRUCTION MODAL
    ============================================= --}}
    <div class="modal fade" id="is_is_not-instruction-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h4 class="modal-title">Is/Is Not Analysis</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <h5 class="fw-bold">Uses:</h5>
                            <ul>
                                <li><strong>IS (Is):</strong> Helps define what a concept or system "is."</li>
                                <li><strong>Is Not (Is Not):</strong> Clearly outlines what the concept or system is
                                    not, eliminating ambiguity.</li>
                            </ul>
                        </div>

                        <div class="col-12">
                            <h5 class="fw-bold">Clarity in Definition:</h5>
                            <ul>
                                <li><strong>IS (Is):</strong> Identifies the essential features and characteristics of a
                                    system or product.</li>
                                <li><strong>Is Not (Is Not):</strong> Excludes non-essential elements, preventing scope
                                    creep in project requirements.</li>
                            </ul>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <h5 class="fw-bold">Problem-Solving:</h5>
                            <ul>
                                <li><strong>IS (Is):</strong> Facilitates a systematic approach to problem-solving by
                                    focusing on the core aspects of an issue.</li>
                                <li><strong>Is Not (Is Not):</strong> Helps avoid distractions and irrelevant factors
                                    that may hinder problem resolution.</li>
                            </ul>
                        </div>

                        <div class="col-12">
                            <h5 class="fw-bold">Communication Aid:</h5>
                            <ul>
                                <li><strong>IS (Is):</strong> Enhances communication by providing a concise and precise
                                    description of a subject.</li>
                                <li><strong>Is Not (Is Not):</strong> Reduces misunderstandings by clearly stating what
                                    a concept or system does not involve.</li>
                            </ul>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <h5 class="fw-bold">Decision Making:</h5>
                            <ul>
                                <li><strong>IS (Is):</strong> Assists decision-making by highlighting the key attributes
                                    and components.</li>
                                <li><strong>Is Not (Is Not):</strong> Eliminates confusion by ruling out aspects that
                                    should not be considered in the decision-making process.</li>
                            </ul>
                        </div>

                        <div class="col-12">
                            <h5 class="fw-bold">Specialty:</h5>
                            <ul>
                                <li><strong>Precision in Definition:</strong></li>
                                <ul>
                                    <li><strong>IS (Is):</strong> Enables a detailed and accurate definition of a
                                        subject.</li>
                                    <li><strong>Is Not (Is Not):</strong> Adds specificity by negating misconceptions
                                        and peripheral elements.</li>
                                </ul>
                            </ul>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <h5 class="fw-bold">Scope Delimitation:</h5>
                            <ul>
                                <li><strong>IS (Is):</strong> Clearly defines the boundaries and scope of a concept or
                                    system.</li>
                                <li><strong>Is Not (Is Not):</strong> Sets limitations, preventing the inclusion of
                                    irrelevant or extraneous details.</li>
                            </ul>
                        </div>

                        <div class="col-12">
                            <h5 class="fw-bold">Risk Mitigation:</h5>
                            <ul>
                                <li><strong>IS (Is):</strong> Identifies potential risks associated with a system or
                                    project.</li>
                                <li><strong>Is Not (Is Not):</strong> Helps in risk management by excluding factors that
                                    are not relevant to the identified risks.</li>
                            </ul>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <h5 class="fw-bold">Alignment with Objectives:</h5>
                            <ul>
                                <li><strong>IS (Is):</strong> Ensures that a concept or system aligns with its intended
                                    objectives.</li>
                                <li><strong>Is Not (Is Not):</strong> Assists in maintaining focus on the primary goals
                                    by excluding features or characteristics that deviate from the objectives.</li>
                            </ul>
                        </div>

                        <div class="col-12">
                            <h5 class="fw-bold">Continuous Improvement:</h5>
                            <ul>
                                <li><strong>IS (Is):</strong> Facilitates a continuous improvement mindset by refining
                                    the definition over time.</li>
                                <li><strong>Is Not (Is Not):</strong> Promotes efficiency by eliminating aspects that do
                                    not contribute to improvement efforts.</li>
                            </ul>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <h5 class="fw-bold">Considerations:</h5>
                            <ul>
                                <li><strong>Dynamic Nature:</strong></li>
                                <ul>
                                    <li><strong>IS (Is):</strong> Acknowledges that definitions can evolve with changing
                                        requirements.</li>
                                    <li><strong>Is Not (Is Not):</strong> Emphasizes the need to revisit and update the
                                        analysis as the context changes.</li>
                                </ul>
                            </ul>
                        </div>

                        <div class="col-12">
                            <h5 class="fw-bold">Collaborative Tool:</h5>
                            <ul>
                                <li><strong>IS (Is):</strong> Serves as a tool for collaborative discussions and
                                    consensus building.</li>
                                <li><strong>Is Not (Is Not):</strong> Encourages teams to align on common understanding
                                    by addressing and resolving misconceptions.</li>
                            </ul>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <h5 class="fw-bold">Documentation Aid:</h5>
                            <ul>
                                <li><strong>IS (Is):</strong> Supports documentation efforts by providing a structured
                                    and clear foundation.</li>
                                <li><strong>Is Not (Is Not):</strong> Aids in maintaining documentation relevance by
                                    excluding obsolete or irrelevant information.</li>
                            </ul>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- ============================================
                WHY WHY CHART INSTRUCTION MODAL
    ============================================= --}}
    <div class="modal fade" id="observation-field-instruction-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h4 class="modal-title">Explanation of Data Fields</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <div class="mb-2">
                        <strong>Observation ID :&nbsp;</strong>
                        A unique identifier for each observation.
                    </div>
                    <div class="mb-2">
                        <strong>Date</strong>
                        When the observation was made.
                    </div>
                    <div class="mb-2">
                        <strong>Auditor :&nbsp;</strong>
                        Name of the auditor who identified the observation.
                    </div>
                    <div class="mb-2">
                        <strong>Auditee :&nbsp;</strong>
                        Name of the auditee who is responsible for area of observation.
                    </div>
                    <div class="mb-2">
                        <strong>Observation Description :&nbsp;</strong>
                        Detailed description of the observation.
                    </div>
                    <div class="mb-2">
                        <strong>Severity Level :&nbsp;</strong>
                        The severity level of the observation (e.g., Minor, Major, Critical,
                        Recommendation).
                    </div>
                    <div class="mb-2">
                        <strong>Area/Process :&nbsp;</strong>
                        The specific area or process where the observation occurred.
                    </div>
                    <div class="mb-2">
                        <strong>Observation Category :&nbsp;</strong>
                        The broad category to which the observation belongs (e.g., Documentation,
                        Equipment, Cleanroom, Data Integrity, etc.).
                    </div>
                    <div class="mb-2">
                        <strong>CAPA Required :&nbsp;</strong>
                        Specific actions that need to be taken to address the observation.
                    </div>
                    <div class="mb-2">
                        <strong>CAPA Due date :&nbsp;</strong>
                        Deadline for completing the corrective &amp; preventive actions.
                    </div>
                    <div>
                        <strong>Status :&nbsp;</strong>
                        The current status of the observation (e.g., Open, In Progress, Closed).
                    </div>

                </div>

            </div>
        </div>
    </div>
