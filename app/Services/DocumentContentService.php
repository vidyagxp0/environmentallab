<?php

namespace App\Services;

use App\Models\Document;

class DocumentContentService
{

    static function search_replace_procedure($search, $replace)
    {
        $docs = Document::all();

        foreach ($docs as $doc)
        {
            $doc_procedure = $doc->doc_content?->procedure;
            if ($doc->doc_content) {
                $new_doc_procedure = str_replace($search, $replace, $doc_procedure);
                $doc->doc_content->procedure = $new_doc_procedure;
                $doc->doc_content->save();
            }
        }

        return count($docs) . ' documents procedure field updated!';
    }

}
