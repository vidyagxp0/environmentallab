<?php

namespace App\Services;

use App\Models\ActionItem;
use App\Models\Auditee;
use App\Models\AuditProgram;
use App\Models\Capa;
use App\Models\CC;
use App\Models\Document;
use App\Models\DocumentGridData;
use App\Models\DocumentType;
use App\Models\EffectivenessCheck;
use App\Models\Extension;
use App\Models\ExternalAudit;
use App\Models\InternalAudit;
use App\Models\LabIncident;
use App\Models\Observation;
use App\Models\QMSDivision;
use App\Models\QmsRecordNumber;
use App\Models\RecordNumber;
use App\Models\RiskAssessment;
use App\Models\RiskManagement;
use App\Models\RootCauseAnalysis;
use Helpers;
use Illuminate\Support\Facades\DB;

class DocumentService
{
    static function handleDistributionGrid(Document $document, $distributions)
    {
        try {

            $existing_distribution_grid = DocumentGridData::where('document_id', $document->id)->get();

            foreach ($existing_distribution_grid as $grid)
            {
                $grid->delete();
            }

            foreach ($distributions as $key => $distribution)
            {
                $document_distribution_grid = new DocumentGridData();
                $document_distribution_grid->document_id = $document->id;
                $document_distribution_grid->document_title = isset($distribution['document_title']) ? $distribution['document_title'] : '';
                $document_distribution_grid->document_number = isset($distribution['document_number']) ? $distribution['document_number'] : '';
                $document_distribution_grid->document_printed_by = isset($distribution['document_printed_by']) ? $distribution['document_printed_by'] : '';
                $document_distribution_grid->document_printed_on = isset($distribution['document_printed_on']) ? $distribution['document_printed_on'] : '';
                $document_distribution_grid->document_printed_copies = isset($distribution['document_printed_copies']) ? $distribution['document_printed_copies'] : '';
                $document_distribution_grid->issuance_date = isset($distribution['issuance_date']) ? $distribution['issuance_date'] : '';
                $document_distribution_grid->issuance_to = isset($distribution['issuance_to']) ? $distribution['issuance_to'] : '';
                $document_distribution_grid->location = isset($distribution['location']) ? $distribution['location'] : '';
                $document_distribution_grid->issued_copies = isset($distribution['issued_copies']) ? $distribution['issued_copies'] : '';
                $document_distribution_grid->issued_reason = isset($distribution['issued_reason']) ? $distribution['issued_reason'] : '';
                $document_distribution_grid->retrieval_date = isset($distribution['retrieval_date']) ? $distribution['retrieval_date'] : '';
                $document_distribution_grid->retrieval_by = isset($distribution['retrieval_by']) ? $distribution['retrieval_by'] : '';
                $document_distribution_grid->retrieved_department = isset($distribution['retrieved_department']) ? $distribution['retrieved_department'] : '';
                $document_distribution_grid->retrieved_copies = isset($distribution['retrieved_copies']) ? $distribution['retrieved_copies'] : '';
                $document_distribution_grid->retrieved_reason =  isset($distribution['retrieved_reason']) ? $distribution['retrieved_reason'] : '';
                $document_distribution_grid->remark = isset($distribution['remark']) ? $distribution['remark'] : '';
                $document_distribution_grid->save();
            }
        } catch (\Exception $e) {
            info('Error in DocumentService@handleDistributionGrid', [
                'message' => $e->getMessage(),
                'object' => $e
            ]);
        }
    }

    static function update_document_numbers()
    {
        try {
            
            $document_types = DocumentType::all();

            foreach ($document_types as $document_type)
            {
                $documents = Document::where('document_type_id', $document_type->id)->get();

                $record_number = 0;

                foreach ($documents as $document)
                {
                    if ($document->revised !== 'Yes') {
                        $record_number++;
                        $document->document_number = $record_number; 
                        $document->save();
                    } else {
                        $parent_document = Document::find($document->revised_doc);
                        if ($parent_document) {
                            $document->document_number = $parent_document->document_number;
                            $document->save();
                        }
                    }
                }
            }

            // UPDATE SOP NO:

            return self::update_sop_numbers();

        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    static function update_qms_numbers()
    {
        try {

            $divisions = QMSDivision::all();

            foreach ($divisions as $division)
            {
                $capas = Capa::where('division_id', $division->id)->get();
                $extensions = Extension::where('division_id', $division->id)->get();
                $change_controls = CC::where('division_id', $division->id)->get();
                $all_rca = RootCauseAnalysis::where('division_id', $division->id)->get();
                $risk_managements = RiskManagement::where('division_id', $division->id)->get();
                $external_audits = Auditee::where('division_id', $division->id)->get();
                $internal_audits = InternalAudit::where('division_id', $division->id)->get();
                $lab_incidents = LabIncident::where('division_id', $division->id)->get();
                $effective_checks = EffectivenessCheck::where('division_id', $division->id)->get();
                $action_items = ActionItem::where('division_id', $division->id)->get();
                $audit_programs = AuditProgram::where('division_id', $division->id)->get();

                $capa_record_number = 1;
                $extensions_record_number = 1;
                $change_controls_record_number = 1;
                $rca_record_number = 1;
                $risk_management_record_number = 1;
                $external_audit_record_number = 1;
                $internal_audit_record_number = 1;
                $lab_incident_record_number = 1;
                $effective_check_record_number = 1;
                $action_item_record_number = 1;
                $audit_program_record_number = 1;

                foreach ($capas as $capa)
                {
                    if ($capa->record_number) {
                        $r_n = $capa->record_number;
                        $r_n->record_number = $capa_record_number;
                    } else {
                        $r_n = new QmsRecordNumber;
                        $r_n->record_number = $capa_record_number;
                    }

                    $r_n->save();

                    $capa->record_number()->save($r_n);

                    $capa_record_number++;
                }

                foreach ($extensions as $extension)
                {
                    if ($extension->record_number) {
                        $r_n = $extension->record_number;
                        $r_n->record_number = $extensions_record_number;
                    } else {
                        $r_n = new QmsRecordNumber;
                        $r_n->record_number = $extensions_record_number;
                    }

                    $r_n->save();

                    $extension->record_number()->save($r_n);

                    $extensions_record_number++;
                }
                
                foreach ($change_controls as $change_control)
                {
                    if ($change_control->record_number) {
                        $r_n = $change_control->record_number;
                        $r_n->record_number = $change_controls_record_number;
                    } else {
                        $r_n = new QmsRecordNumber;
                        $r_n->record_number = $change_controls_record_number;
                    }

                    $r_n->save();

                    $change_control->record_number()->save($r_n);

                    $change_controls_record_number++;
                }

                foreach ($all_rca as $rca)
                {
                    if ($rca->record_number) {
                        $r_n = $rca->record_number;
                        $r_n->record_number = $rca_record_number;
                    } else {
                        $r_n = new QmsRecordNumber;
                        $r_n->record_number = $rca_record_number;
                    }

                    $r_n->save();
                    
                    $rca->record_number()->save($r_n);
                    
                    $rca_record_number++;
                }
                
                foreach ($risk_managements as $risk_management)
                {
                    if ($risk_management->record_number) {
                        $r_n = $risk_management->record_number;
                        $r_n->record_number = $risk_management_record_number;
                    } else {
                        $r_n = new QmsRecordNumber;
                        $r_n->record_number = $risk_management_record_number;
                    }

                    $r_n->save();

                    $risk_management->record_number()->save($r_n);

                    $risk_management_record_number++;
                }
                
                foreach ($external_audits as $external_audit)
                {
                    if ($external_audit->record_number) {
                        $r_n = $external_audit->record_number;
                        $r_n->record_number = $external_audit_record_number;
                    } else {
                        $r_n = new QmsRecordNumber;
                        $r_n->record_number = $external_audit_record_number;
                    }

                    $r_n->save();

                    $external_audit->record_number()->save($r_n);

                    $external_audit_record_number++;
                }
                
                foreach ($internal_audits as $internal_audit)
                {
                    if ($internal_audit->record_number) {
                        $r_n = $internal_audit->record_number;
                        $r_n->record_number = $internal_audit_record_number;
                    } else {
                        $r_n = new QmsRecordNumber;
                        $r_n->record_number = $internal_audit_record_number;
                    }

                    $r_n->save();

                    $internal_audit->record_number()->save($r_n);

                    $internal_audit_record_number++;
                }
                
                foreach ($lab_incidents as $lab_incident)
                {
                    if ($lab_incident->record_number) {
                        $r_n = $lab_incident->record_number;
                        $r_n->record_number = $lab_incident_record_number;
                    } else {
                        $r_n = new QmsRecordNumber;
                        $r_n->record_number = $lab_incident_record_number;
                    }

                    $r_n->save();

                    $lab_incident->record_number()->save($r_n);

                    $lab_incident_record_number++;
                }
                
                foreach ($effective_checks as $effective_check)
                {
                    if ($effective_check->record_number) {
                        $r_n = $effective_check->record_number;
                        $r_n->record_number = $effective_check_record_number;
                    } else {
                        $r_n = new QmsRecordNumber;
                        $r_n->record_number = $effective_check_record_number;
                    }

                    $r_n->save();

                    $effective_check->record_number()->save($r_n);

                    $effective_check_record_number++;
                }
                
                foreach ($action_items as $action_item)
                {
                    if ($action_item->record_number) {
                        $r_n = $action_item->record_number;
                        $r_n->record_number = $action_item_record_number;
                    } else {
                        $r_n = new QmsRecordNumber;
                        $r_n->record_number = $action_item_record_number;
                    }

                    $r_n->save();

                    $action_item->record_number()->save($r_n);

                    $action_item_record_number++;
                }
                
                foreach ($audit_programs as $audit_program)
                {
                    if ($audit_program->record_number) {
                        $r_n = $audit_program->record_number;
                        $r_n->record_number = $audit_program_record_number;
                    } else {
                        $r_n = new QmsRecordNumber;
                        $r_n->record_number = $audit_program_record_number;
                    }

                    $r_n->save();

                    $audit_program->record_number()->save($r_n);

                    $audit_program_record_number++;
                }

            }
            

        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    static function update_sop_numbers()
    {
        try {
            
            $documents = Document::all();

            foreach ($documents as $doc)
            {
                $type = DocumentType::find($doc->document_type_id);

                if($doc->revised === 'Yes')  {
                    $doc_sop_no = Helpers::getDivisionName($doc->division_id) . '/' . $type->typecode . '/' . $doc->created_at->format('Y') . '/' .  $doc->document_number . '/ R' . $doc->major .'.'. $doc->minor;
                } else {
                    $doc_sop_no = Helpers::getDivisionName($doc->division_id) . '/' . $type->typecode . '/' . $doc->created_at->format('Y') . '/' .  $doc->document_number . '/' . $doc->major .'.'. $doc->minor;
                }

                $doc->sop_no = $doc_sop_no;
                $doc->save();
            }

        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}