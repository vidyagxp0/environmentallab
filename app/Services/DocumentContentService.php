<?php

namespace App\Services;

use App\Models\Document;
use DOMDocument;

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

    static function fix_blob_image_fields()
    {
        $docs = Document::all();
        $updated = 0;
        foreach ($docs as $doc)
        {
            $doc_procedure = $doc->doc_content?->procedure;
            if ($doc->doc_content && !empty($doc_procedure)) {
                $dom = new DOMDocument();
                libxml_use_internal_errors(true);

                $dom->loadHTML(mb_convert_encoding($doc_procedure, 'HTML-ENTITIES', 'UTF-8'));

                $images = $dom->getElementsByTagName('img');

                foreach ($images as $img) {
                    if ($img->hasAttribute('data-fr-old-src')) {
                        $updated++;
                        $oldSrc = $img->getAttribute('data-fr-old-src');
                        $img->removeAttribute('src');
                        $img->setAttribute('src', $oldSrc);
                    }
                }

                $newHtml = $dom->saveHTML();

                $newHtml = preg_replace('/^<!DOCTYPE.+?>/', '', $newHtml);
                $newHtml = str_replace(['<html>', '</html>', '<body>', '</body>'], '', $newHtml);
                $newHtml = trim($newHtml);

                $doc->doc_content->procedure = $newHtml;
                $doc->doc_content->save();

            }
        }

        return $updated . ' image tags fixed!';
    }

}
