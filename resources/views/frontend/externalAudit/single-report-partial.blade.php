<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Connexo - Software</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>


    /* Table utilities (kept minimal to stay PDF-safe) */
    table { width: 100%; border-collapse: collapse; table-layout: fixed; }
    th, td { border: 1px solid rgba(0,0,0,0.15); padding: 8px; vertical-align: top; word-wrap: break-word; }
    th { background: #f4f6f8; font-weight: 100; text-align: left; }

    .k-cell-tight th, .k-cell-tight td { padding: 6px; }
    .wrap { word-break: break-word; overflow-wrap: anywhere; }

  </style>
</head>
<body class="bg-white text-gray-900">

  <main class="max-w-[900px] mx-auto space-y-6 text-sm">

    <!-- Section: General Information -->
    <section class="avoid-break border border-gray-200  shadow-sm">
      <div class="px-4 py-3 bg-gradient-to-r from-blue-600 to-blue-500 text-white ">
        <h2 class="text-base  text-sm  tracking-wide">General Information</h2>
      </div>
      <div class="p-4">
        <table class="k-cell-tight text-sm">
          <tbody>
            <tr>
              <th class="w-1/5">Record Number</th>
              <td class="w-3/10 wrap">
                {{ Helpers::divisionNameForQMS($data->division_id) }}/EA/{{ Helpers::year($data->created_at) }}/{{ str_pad($data->record, 4, '0', STR_PAD_LEFT) }}
              </td>
              <th class="w-1/5">Initiator</th>
              <td class="w-3/10 wrap">{{ Helpers::getInitiatorName($data->initiator_id) }}</td>
            </tr>
            <tr>
              <th>Date of Initiation</th>
              <td class="wrap">{{ Helpers::getdateFormat($data->intiation_date) }}</td>
              <th>Name of Auditor</th>
              <td class="wrap">
                @if ($data->external_auditor_name)
                  {{ $data->external_auditor_name }}
                @else
                  Not Applicable
                @endif
              </td>
            </tr>
            <tr>
              <th>Site/Location Code</th>
              <td class="wrap">
                @if ($data->division_code)
                  {{ $data->division_code }}
                @else
                  Not Applicable
                @endif
              </td>
              <th>Area of Auditing</th>
              <td class="wrap">
                @if ($data->area_of_auditing)
                  {{ $data->area_of_auditing }}
                @else
                  Not Applicable
                @endif
              </td>
            </tr>
            <tr>
              <th>Assigned To</th>
              <td class="wrap">
                @if ($data->multiple_assignee_to)
                  @foreach (explode(',', $data->multiple_assignee_to) as $Key => $value)
                    {{ Helpers::getInitiatorName($value) }}@if(!$loop->last), @endif
                  @endforeach
                @else
                  Not Applicable
                @endif
              </td>
              <th>Due Date</th>
              <td class="wrap">
                @if ($data->due_date)
                  {{ Helpers::getdateFormat($data->due_date) }}
                @else
                  Not Applicable
                @endif
              </td>
            </tr>
            <tr>
              <th>Initiator Group</th>
              <td class="wrap">
                @if ($data->Initiator_Group)
                  {{ \Helpers::getInitiatorGroupFullName($data->Initiator_Group) }}
                @else
                  Not Applicable
                @endif
              </td>
              <th>Initiator Group Code</th>
              <td class="wrap">
                @if ($data->initiator_group_code)
                  {{ $data->initiator_group_code }}
                @else
                  Not Applicable
                @endif
              </td>
            </tr>
            <tr>
              <th>External Agencies</th>
              <td class="wrap">
                @if ($data->external_agencies)
                  {{ $data->external_agencies }}
                @else
                  Not Applicable
                @endif
              </td>
              <th>Severity Level</th>
              <td class="wrap">
                @if ($data->severity_level)
                  {{ $data->severity_level }}
                @else
                  Not Applicable
                @endif
              </td>
            </tr>
            <tr>
              <th>Others</th>
              <td colspan="3" class="wrap">
                @if ($data->others)
                  {{ $data->others }}
                @else
                  Not Applicable
                @endif
              </td>
            </tr>
            <tr>
              <th>Short Description</th>
              <td colspan="3" class="wrap">
                @if ($data->short_description)
                  {{ $data->short_description }}
                @else
                  Not Applicable
                @endif
              </td>
            </tr>
            <tr>
              <th>Description</th>
              <td colspan="3" class="wrap">
                @if ($data->initial_comments)
                  {{ $data->initial_comments }}
                @else
                  Not Applicable
                @endif
              </td>
            </tr>
            <tr>
              <th>Reason For Audit</th>
              <td colspan="3" class="wrap">
                @if ($data->reason_for_audit)
                  {{ $data->reason_for_audit }}
                @else
                  Not Applicable
                @endif
              </td>
            </tr>
            <tr>
              <th>Type of Audit</th>
              <td class="wrap">
                @if ($data->audit_type)
                  {{ $data->audit_type }}
                @else
                  Not Applicable
                @endif
              </td>
              <th>If Others</th>
              <td class="wrap" colspan="1">
                @if ($data->if_other)
                  {{ $data->if_other }}
                @else
                  Not Applicable
                @endif
              </td>
            </tr>
            <tr>
              <th>Initiated Through</th>
              <td class="wrap">
                @if ($data->initiated_through)
                  {{ $data->initiated_through }}
                @else
                  Not Applicable
                @endif
              </td>
              <th>Others</th>
              <td class="wrap">
                @if ($data->initiated_if_other)
                  {{ $data->initiated_if_other }}
                @else
                  Not Applicable
                @endif
              </td>
            </tr>
          </tbody>
        </table>

        <!-- Initial Attachment -->
        <div class="mt-6 avoid-break">
          <h3 class="text-sm  text-sm  text-gray-800 mb-2">Initial Attachment</h3>
          <table class="text-sm">
            <thead>
              <tr>
                <th class="w-24">S.N.</th>
                <th>File</th>
              </tr>
            </thead>
            <tbody>
              @if ($data->inv_attachment)
                @foreach (json_decode($data->inv_attachment) as $key => $file)
                  <tr>
                    <td>{{ $key + 1 }}</td>
                    <td class="wrap"><a class="text-blue-700 underline" href="{{ asset('upload/' . $file) }}" target="_blank"><b>{{ $file }}</b></a></td>
                  </tr>
                @endforeach
              @else
                <tr>
                  <td>1</td>
                  <td>Not Applicable</td>
                </tr>
              @endif
            </tbody>
          </table>
        </div>
      </div>
    </section>

    <!-- Section: Audit Planning -->
    <section class="avoid-break border border-gray-200  shadow-sm">
      <div class="px-4 py-3 bg-gradient-to-r from-blue-600 to-blue-500 text-white ">
        <h2 class="text-base  text-sm  tracking-wide">Audit Planning</h2>
      </div>
      <div class="p-4">
        <table class="k-cell-tight text-sm">
          <tbody>
            <tr>
              <th class="w-1/5">Audit Schedule Start Date</th>
              <td class="wrap w-3/10">
                @if ($data->start_date)
                  {{ Helpers::getdateFormat($data->start_date) }}
                @else
                  Not Applicable
                @endif
              </td>
              <th class="w-1/5">Audit Schedule End Date</th>
              <td class="wrap w-3/10">
                @if ($data->end_date)
                  {{ Helpers::getdateFormat($data->end_date) }}
                @else
                  Not Applicable
                @endif
              </td>
            </tr>
            <tr>
              <th>Comments (If Any)</th>
              <td colspan="3" class="wrap">
                @if ($data->if_comments)
                  {{ $data->if_comments }}
                @else
                  Not Applicable
                @endif
              </td>
            </tr>
            <tr>
              <th>Product/Material Name</th>
              <td colspan="3" class="wrap">
                @if ($data->material_name)
                  {{ $data->material_name }}
                @else
                  Not Applicable
                @endif
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </section>

    <!-- Section: Audit Agenda (Part 1/2/3) -->
    <section class="avoid-break border border-gray-200  shadow-sm">
      <div class="px-4 py-3 bg-gradient-to-r from-blue-600 to-blue-500 text-white ">
        <h2 class="text-base  text-sm  tracking-wide">Audit Agenda — Part 1</h2>
      </div>
      <div class="p-4">
        <table class="text-sm">
          <thead>
            <tr>
              <th class="w-20">Row #</th>
              <th>Area of Audit</th>
              <th>Scheduled Start Date</th>
              <th>Scheduled Start Time</th>
            </tr>
          </thead>
          <tbody>
            @php $counter = 1; @endphp
            @if (!empty($auditAgenda))
              @foreach ($auditAgenda as $item)
                <tr>
                  <td>{{ $counter++ }}</td>
                  <td class="wrap">{{ $item['auditArea'] ?? 'Not Applicable' }}</td>
                  <td class="wrap">{{ Helpers::getdateFormat($item['startDate']) ? Helpers::getdateFormat($item['startDate']) : 'Not Applicable' }}</td>
                  <td class="wrap">{{ $item['startTime'] ?? 'Not Applicable' }}</td>
                </tr>
              @endforeach
            @else
              <tr>
                <td colspan="4">Not Applicable</td>
              </tr>
            @endif
          </tbody>
        </table>
      </div>

      <div class="px-4 py-3 bg-gray-50 border-t border-gray-200">
        <h3 class="text-sm  text-sm  text-gray-800">Audit Agenda — Part 2</h3>
      </div>
      <div class="p-4">
        <table class="text-sm">
          <thead>
            <tr>
              <th class="w-20">Row #</th>
              <th>Scheduled End Date</th>
              <th>Scheduled End Time</th>
              <th>Auditor</th>
            </tr>
          </thead>
          <tbody>
            @php $counter = 1; @endphp
            @if (!empty($auditAgenda))
              @foreach ($auditAgenda as $item)
                <tr>
                  <td>{{ $counter++ }}</td>
                  <td class="wrap">{{ Helpers::getdateFormat($item['endDate']) ? Helpers::getdateFormat($item['endDate']) : 'Not Applicable' }}</td>
                  <td class="wrap">{{ $item['endTime'] ?? 'Not Applicable' }}</td>
                  <td class="wrap">{{ Helpers::getInitiatorName($item['auditor']) ?? 'Not Applicable' }}</td>
                </tr>
              @endforeach
            @else
              <tr>
                <td colspan="4">Not Applicable</td>
              </tr>
            @endif
          </tbody>
        </table>
      </div>

      <div class="px-4 py-3 bg-gray-50 border-t border-gray-200">
        <h3 class="text-sm  text-sm  text-gray-800">Audit Agenda — Part 3</h3>
      </div>
      <div class="p-4">
        <table class="text-sm">
          <thead>
            <tr>
              <th class="w-20">Row #</th>
              <th>Auditee</th>
              <th>Remarks</th>
            </tr>
          </thead>
          <tbody>
            @php $counter = 1; @endphp
            @if (!empty($auditAgenda))
              @foreach ($auditAgenda as $item)
                <tr>
                  <td>{{ $counter++ }}</td>
                  <td class="wrap">{{ Helpers::getInitiatorName($item['auditee']) ?? 'Not Applicable' }}</td>
                  <td class="wrap">{{ $item['remarks'] ?? 'Not Applicable' }}</td>
                </tr>
              @endforeach
            @else
              <tr>
                <td colspan="3">Not Applicable</td>
              </tr>
            @endif
          </tbody>
        </table>
      </div>
    </section>

    <!-- Section: Audit Preparation -->
    <section class="avoid-break border border-gray-200  shadow-sm">
      <div class="px-4 py-3 bg-gradient-to-r from-blue-600 to-blue-500 text-white ">
        <h2 class="text-base  text-sm  tracking-wide">Audit Preparation</h2>
      </div>
      <div class="p-4">
        <table class="k-cell-tight text-sm">
          <tbody>
            <tr>
              <th class="w-1/5">Lead Auditor</th>
              <td colspan="3" class="wrap">
                @if ($data->lead_auditor)
                  {{ $data->lead_auditor }}
                @else
                  Not Applicable
                @endif
              </td>
            </tr>
            <tr>
              <th>External Auditor Details</th>
              <td colspan="3" class="wrap">
                @if ($data->Auditor_Details)
                  {{ $data->Auditor_Details }}
                @else
                  Not Applicable
                @endif
              </td>
            </tr>
            <tr>
              <th>External Auditing Agency</th>
              <td colspan="3" class="wrap">
                @if ($data->External_Auditing_Agency)
                  {{ $data->External_Auditing_Agency }}
                @else
                  Not Applicable
                @endif
              </td>
            </tr>
            <tr>
              <th>Relevant Guidelines / Industry Standards</th>
              <td colspan="3" class="wrap">
                @if ($data->Relevant_Guidelines)
                  {{ $data->Relevant_Guidelines }}
                @else
                  Not Applicable
                @endif
              </td>
            </tr>
            <tr>
              <th>QA Comments</th>
              <td colspan="3" class="wrap">
                @if ($data->QA_Comments)
                  {{ $data->QA_Comments }}
                @else
                  Not Applicable
                @endif
              </td>
            </tr>
            <tr>
              <th>Audit Category</th>
              <td class="wrap">
                @if ($data->Audit_Category)
                  {{ $data->Audit_Category }}
                @else
                  Not Applicable
                @endif
              </td>
              <th>Supplier/Vendor/Manufacturer Site</th>
              <td class="wrap">
                @if ($data->Supplier_Site)
                  {{ $data->Supplier_Site }}
                @else
                  Not Applicable
                @endif
              </td>
            </tr>
            <tr>
              <th>Supplier/Vendor/Manufacturer Details</th>
              <td colspan="3" class="wrap">
                @if ($data->Supplier_Details)
                  {{ $data->Supplier_Details }}
                @else
                  Not Applicable
                @endif
              </td>
            </tr>
            <tr>
              <th>Audit Team</th>
              <td colspan="3" class="wrap">
                @if ($data->Audit_team)
                  {{ $data->Audit_team }}
                @else
                  Not Applicable
                @endif
              </td>
            </tr>
            <tr>
              <th>Auditee</th>
              <td colspan="3" class="wrap">
                @if ($data->Auditee)
                  @php $auditees = explode(',', $data->Auditee); @endphp
                  @foreach ($auditees as $key => $value)
                    {{ Helpers::getInitiatorName($value) }}{{ $key < count($auditees) - 1 ? ', ' : '' }}&nbsp;
                  @endforeach
                @else
                  Not Applicable
                @endif
              </td>
            </tr>
            <tr>
              <th>Comments</th>
              <td colspan="3" class="wrap">
                @if ($data->Comments)
                  {{ $data->Comments }}
                @else
                  Not Applicable
                @endif
              </td>
            </tr>
          </tbody>
        </table>

        <!-- File Attachment -->
        <div class="mt-6 avoid-break">
          <h3 class="text-sm  text-sm  text-gray-800 mb-2">File Attachment</h3>
          <table class="text-sm">
            <thead>
              <tr>
                <th class="w-24">S.N.</th>
                <th>File</th>
              </tr>
            </thead>
            <tbody>
              @if ($data->file_attachment)
                @foreach (json_decode($data->file_attachment) as $key => $file)
                  <tr>
                    <td>{{ $key + 1 }}</td>
                    <td class="wrap"><a class="text-blue-700 underline" href="{{ asset('upload/' . $file) }}" target="_blank"><b>{{ $file }}</b></a></td>
                  </tr>
                @endforeach
              @else
                <tr>
                  <td>1</td>
                  <td>Not Applicable</td>
                </tr>
              @endif
            </tbody>
          </table>
        </div>

        <!-- Guideline Attachment -->
        <div class="mt-6 avoid-break">
          <h3 class="text-sm  text-sm  text-gray-800 mb-2">Guideline Attachment</h3>
          <table class="text-sm">
            <thead>
              <tr>
                <th class="w-24">S.N.</th>
                <th>File</th>
              </tr>
            </thead>
            <tbody>
              @if ($data->file_attachment_guideline)
                @foreach (json_decode($data->file_attachment_guideline) as $key => $file)
                  <tr>
                    <td>{{ $key + 1 }}</td>
                    <td class="wrap"><a class="text-blue-700 underline" href="{{ asset('upload/' . $file) }}" target="_blank"><b>{{ $file }}</b></a></td>
                  </tr>
                @endforeach
              @else
                <tr>
                  <td>1</td>
                  <td>Not Applicable</td>
                </tr>
              @endif
            </tbody>
          </table>
        </div>
      </div>
    </section>

    <!-- Section: Audit Execution -->
    <section class="avoid-break border border-gray-200  shadow-sm">
      <div class="px-4 py-3 bg-gradient-to-r from-blue-600 to-blue-500 text-white ">
        <h2 class="text-base  text-sm  tracking-wide">Audit Execution</h2>
      </div>
      <div class="p-4">
        <table class="k-cell-tight text-sm">
          <tbody>
            <tr>
              <th class="w-1/5">Audit Start Date</th>
              <td class="wrap w-3/10">
                @if ($data->audit_start_date)
                  {{ Helpers::getdateFormat($data->audit_start_date) }}
                @else
                  Not Applicable
                @endif
              </td>
              <th class="w-1/5">Audit End Date</th>
              <td class="wrap w-3/10">
                @if ($data->audit_end_date)
                  {{ Helpers::getdateFormat($data->audit_end_date) }}
                @else
                  Not Applicable
                @endif
              </td>
            </tr>
            <tr>
              <th>Audit Comments</th>
              <td colspan="3" class="wrap">
                @if ($data->Audit_Comments1)
                  {{ $data->Audit_Comments1 }}
                @else
                  Not Applicable
                @endif
              </td>
            </tr>
          </tbody>
        </table>

        <!-- Observation Details -->
        <div class="mt-6 avoid-break">
          <h3 class="text-sm  text-sm  text-gray-800 mb-2">Observation Details</h3>
          <table class="text-sm">
            <thead>
              <tr>
                <th class="w-16">Row #</th>
                <th>Observation Details</th>
                <th>Pre Comments</th>
                <th>CAPA Details if any</th>
                <th class="w-40">Expected Date To Complete</th>
                <th>Post Comments</th>
              </tr>
            </thead>
            <tbody>
              @if ($grid_data1->observation_id)
                @foreach (unserialize($grid_data1->observation_id) as $key => $tempData)
                  <tr>
                    <td>{{ $key + 1 }}</td>
                    <td class="wrap">{{ $tempData ?: 'Not Applicable' }}</td>
                    <td class="wrap">{{ unserialize($grid_data1->observation_description)[$key] ?? 'Not Applicable' }}</td>
                    <td class="wrap">{{ unserialize($grid_data1->area)[$key] ?? 'Not Applicable' }}</td>
                    <td class="wrap">{{ Helpers::getdateFormat(unserialize($grid_data1->capa_due_date)[$key]) ?: 'Not Applicable' }}</td>
                    <td class="wrap">{{ unserialize($grid_data1->auditee_response)[$key] ?? 'Not Applicable' }}</td>
                  </tr>
                @endforeach
              @else
                <tr>
                  <td colspan="6" class="text-center">Not Applicable</td>
                </tr>
              @endif
            </tbody>
          </table>
        </div>

        <!-- Audit Attachments -->
        <div class="mt-6 avoid-break">
          <h3 class="text-sm  text-sm  text-gray-800 mb-2">Audit Attachments</h3>
          <table class="text-sm">
            <thead>
              <tr>
                <th class="w-24">S.N.</th>
                <th>File</th>
              </tr>
            </thead>
            <tbody>
              @if ($data->Audit_file)
                @foreach (json_decode($data->Audit_file) as $key => $file)
                  <tr>
                    <td>{{ $key + 1 }}</td>
                    <td class="wrap"><a class="text-blue-700 underline" href="{{ asset('upload/' . $file) }}" target="_blank"><b>{{ $file }}</b></a></td>
                  </tr>
                @endforeach
              @else
                <tr>
                  <td>1</td>
                  <td>Not Applicable</td>
                </tr>
              @endif
            </tbody>
          </table>
        </div>
      </div>
    </section>

    <!-- Section: Audit Response & Closure -->
    <section class="avoid-break border border-gray-200  shadow-sm">
      <div class="px-4 py-3 bg-gradient-to-r from-blue-600 to-blue-500 text-white ">
        <h2 class="text-base  text-sm  tracking-wide">Audit Response &amp; Closure</h2>
      </div>
      <div class="p-4">
        <table class="k-cell-tight text-sm">
          <tbody>
            <tr>
              <th class="w-1/5">Reference Record</th>
              <td class="wrap w-3/10">
                @if ($data->Reference_Recores1)
                  {{ str_replace(',', ', ', $data->Reference_Recores1) }}
                @else
                  Not Applicable
                @endif
              </td>
              <th class="w-1/5">Due Date Extension Justification</th>
              <td class="wrap w-3/10" colspan="1">
                @if ($data->due_date_extension)
                  {{ $data->due_date_extension }}
                @else
                  Not Applicable
                @endif
              </td>
            </tr>
            <tr>
              <th>Remarks</th>
              <td colspan="3" class="wrap">
                @if ($data->Remarks)
                  {{ $data->Remarks }}
                @else
                  Not Applicable
                @endif
              </td>
            </tr>
            <tr>
              <th>Audit Comments</th>
              <td colspan="3" class="wrap">
                @if ($data->Audit_Comments2)
                  {{ $data->Audit_Comments2 }}
                @else
                  Not Applicable
                @endif
              </td>
            </tr>
          </tbody>
        </table>

        <!-- Audit Attachments (Closure) -->
        <div class="mt-6 avoid-break">
          <h3 class="text-sm  text-sm  text-gray-800 mb-2">Audit Attachments</h3>
          <table class="text-sm">
            <thead>
              <tr>
                <th class="w-24">S.N.</th>
                <th>File</th>
              </tr>
            </thead>
            <tbody>
              @if ($data->myfile)
                @foreach (json_decode($data->myfile) as $key => $file)
                  <tr>
                    <td>{{ $key + 1 }}</td>
                    <td class="wrap"><a class="text-blue-700 underline" href="{{ asset('upload/' . $file) }}" target="_blank"><b>{{ $file }}</b></a></td>
                  </tr>
                @endforeach
              @else
                <tr>
                  <td>1</td>
                  <td>Not Applicable</td>
                </tr>
              @endif
            </tbody>
          </table>
        </div>

        <!-- Report Attachment -->
        <div class="mt-6 avoid-break">
          <h3 class="text-sm  text-sm  text-gray-800 mb-2">Report Attachment</h3>
          <table class="text-sm">
            <thead>
              <tr>
                <th class="w-24">S.N.</th>
                <th>File</th>
              </tr>
            </thead>
            <tbody>
              @if ($data->report_file)
                @foreach (json_decode($data->report_file) as $key => $file)
                  <tr>
                    <td>{{ $key + 1 }}</td>
                    <td class="wrap"><a class="text-blue-700 underline" href="{{ asset('upload/' . $file) }}" target="_blank"><b>{{ $file }}</b></a></td>
                  </tr>
                @endforeach
              @else
                <tr>
                  <td>1</td>
                  <td>Not Applicable</td>
                </tr>
              @endif
            </tbody>
          </table>
        </div>
      </div>
    </section>

    <!-- Section: Activity Log -->
    <section class="avoid-break border border-gray-200  shadow-sm">
      <div class="px-4 py-3 bg-gradient-to-r from-blue-600 to-blue-500 text-white ">
        <h2 class="text-base  text-sm  tracking-wide">Activity Log</h2>
      </div>
      <div class="p-4">
        <table class="k-cell-tight text-sm">
          <tbody>
            <tr>
              <th class="w-1/5">Schedule Audit By</th>
              <td class="w-3/10 wrap">{{ $data->audit_schedule_by }}</td>
              <th class="w-1/5">Schedule Audit On</th>
              <td class="w-3/10 wrap">{{ Helpers::getdateFormat($data->audit_schedule_on) }}</td>
            </tr>
            <tr>
              <th>Cancelled By</th>
              <td class="wrap">{{ $data->cancelled_by }}</td>
              <th>Cancelled On</th>
              <td class="wrap">{{ Helpers::getdateFormat($data->cancelled_on) }}</td>
            </tr>
            <tr>
              <th>Complete Audit Preparation by</th>
              <td class="wrap">{{ $data->audit_preparation_completed_by }}</td>
              <th>Complete Audit Preparation On</th>
              <td class="wrap">{{ Helpers::getdateFormat($data->audit_preparation_completed_on) }}</td>
            </tr>
            <tr>
              <th>Issue Report By</th>
              <td class="wrap">{{ $data->audit_mgr_more_info_reqd_by }}</td>
              <th>Issue Report On</th>
              <td class="wrap">{{ Helpers::getdateFormat($data->audit_mgr_more_info_reqd_on) }}</td>
            </tr>
            <tr>
              <th>CAPA Plan Proposed By</th>
              <td class="wrap">{{ $data->audit_observation_submitted_by }}</td>
              <th>CAPA Plan Proposed On</th>
              <td class="wrap">{{ Helpers::getdateFormat($data->audit_observation_submitted_on) }}</td>
            </tr>
            <tr>
              <th>No CAPAs Required By</th>
              <td class="wrap">{{ $data->audit_response_completed_by }}</td>
              <th>No CAPAs Required On</th>
              <td class="wrap">{{ Helpers::getdateFormat($data->audit_response_completed_on) }}</td>
            </tr>
            <tr>
              <th>All CAPA Closed By</th>
              <td class="wrap">{{ $data->audit_lead_more_info_reqd_by }}</td>
              <th>All CAPA Closed On</th>
              <td class="wrap">{{ Helpers::getdateFormat($data->audit_lead_more_info_reqd_on) }}</td>
            </tr>
            <tr>
              <th>Rejected By</th>
              <td class="wrap">{{ $data->rejected_by }}</td>
              <th>Rejected On</th>
              <td class="wrap">{{ Helpers::getdateFormat($data->rejected_on) }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </section>

    <div class="page-break"></div>

  </main>


</body>
</html>
