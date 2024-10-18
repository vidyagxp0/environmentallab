<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ActionItem;
use App\Models\Capa;
use App\Models\CC;
use App\Models\Deviation;

use App\Models\Document;
use App\Models\LabIncident;
use App\Models\MarketComplaint;
use App\Models\OOS;
use App\Models\OOT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Worksheet\Column;
use SebastianBergmann\LinesOfCode\Counter;

class DmsDataController extends Controller
{
    public function DmsData(Request $request){
        
        $res = [
            'status' => 'ok',
            'message' => 'success',
            'body' => []
        ];

        try {

        $document = Document::all();
       
        
        $res['body'] = [
            'document' => $document,
        ];


        } catch (\Exception $e) {
            $res['status'] = 'error';
            $res['message'] = $e->getMessage();
        }

        return response()->json($res);
    }
}
