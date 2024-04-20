<?php

namespace App\Services;

use App\Models\Document;
use App\Models\DocumentGridData;

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
}