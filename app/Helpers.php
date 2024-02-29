<?php

use App\Models\ActionItem;
use App\Models\Division;
use App\Models\QMSDivision;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class Helpers
{
    // public static function getdateFormat($date)
    // {
    //     $date = Carbon::parse($date);
    //     $formatted_date = $date->format("d-M-Y");
    //     return $formatted_date;
    // }
    public static function getdateFormat($date)
{
    if(empty($date)) {
        return ''; // or any default value you prefer
    }
    else{        
        $date = Carbon::parse($date);
        $formatted_date = $date->format("d-M-Y");
        return $formatted_date;
    }

}
    public static function getdateFormat1($date)
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('d-M-Y');
    }

    public static function isRevised($data)
    {   
        if($data  >= 8 ){
            return 'disabled';
        }else{
            return  '';
        }
         
    }


    public static function divisionNameForQMS($id)
    {
        return QMSDivision::where('id', $id)->value('name');
    }

    public static function year($createdAt)
    {
        return Carbon::parse($createdAt)->format('Y');
    }

    public static function getDivisionName($id)
    {
        $name = DB::table('q_m_s_divisions')->where('id', $id)->where('status', 1)->value('name');
        return $name;
    }
    public static function recordFormat($number)
    {
        return   str_pad($number, 4, '0', STR_PAD_LEFT);
    }
    public static function getInitiatorName($id)
    {
        return   User::where('id',$id)->value('name');
    }

    public static function record($id)
    {
        return   str_pad($id, 5, '0', STR_PAD_LEFT);
    }
    public static function getDepartmentWithString($id)
    {
        $response = [];
        if(!empty($id)){
            $response = explode(',',$id);
        }
        return $response;
    }
   
    public static function getDepartmentNameWithString($id)
    {
        $response = [];
        $resp = [];
        if(!empty($id)){
            $result = explode(',',$id);
            if(in_array(1,$result)){
                array_push($response, 'QA');
            }
            if(in_array(2,$result)){
                array_push($response, 'QC');
            }
            if(in_array(3,$result)){
                array_push($response, 'R&D');
            }
            if(in_array(4,$result)){
                array_push($response, 'Manufacturing');
            }
            if(in_array(5,$result)){
                array_push($response, 'Warehouse');
            }
            $resp = implode(',',$response);
        }
        return $resp;
    }

    public static function hodMail($data)
    {
        Mail::send('hod-mail',['data' => $data],
    function ($message){
            $message->to("shaleen.mishra@mydemosoftware.com")
                    ->subject('Record is for Review');
        });
    }
}
