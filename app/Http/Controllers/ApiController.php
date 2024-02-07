<?php

namespace App\Http\Controllers;

use App\Models\TotalLogin;
use App\Models\User;
use App\Rules\MatchOldPassword;
use Carbon\Carbon;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use DB;

class ApiController extends Controller
{
    /*******************************************************************************
     * @ Get Profile API
     * 
     *********************************************************************************/
    public function getProfile(Request $request){
        try{
            $user = User::where('id', 1)->first();
            if(!is_null($user)){
                return response()->json([
                    'status' => true,
					'authenticate' => true,
                    'data'  =>  $user,
                    'message' => 'Profile details'
                ], 200);
            }
            else{
                return response()->json([
                    'status' => false,
                    'authenticate' => false,
                    'message' => 'Unauthorized.'
                ], 200);
            }
        }
        catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'authenticate' => false,
                'message' => $th->getMessage()
            ], 200);
        }		
    }

}