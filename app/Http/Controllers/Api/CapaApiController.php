<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Capa;

class CapaApiController extends Controller
{
    public function CapaForms(Request $request)
    {
        $res = [
            'status' => 'ok',
            'message' => 'success',
            'body' => []
        ];

        try {
            // $ChangeControl = CC::select('id', 'initiator')->get(); {for single column}
            $capa = DB::table('capas')
                ->join('users', 'capas.initiator_id', '=', 'users.id') 
                ->select('capas.division_id', 'users.name as initiator_name', 'capas.short_description','intiation_date','capas.status') 
                ->get();

            $res['body'] = $capa;
        } catch (\Exception $e) {
            $res['status'] = 'error';
            $res['message'] = $e->getMessage();
        }

        return response()->json($res);
    }
}
