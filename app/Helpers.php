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

    public static function checkRoles($role)
    {
        if (strpos(Auth::user()->role, $role) !== false) {
           return true;
        }else{
            return false;
        } 
    }


    public static function checkRoles_check_reviewers($document)
    {
       
        if ($document->reviewers) {
            $datauser = explode(',', $document->reviewers);
            for ($i = 0; $i < count($datauser); $i++) {
                if ($datauser[$i] == Auth::user()->id) {
                    return true;
                }
            }
        } else {
            return false;
        }         
    }

    public static function checkRoles_check_approvers($document)
    {
        if ($document->approvers) {
            $datauser = explode(',', $document->approvers);
            for ($i = 0; $i < count($datauser); $i++) {
                if ($datauser[$i] == Auth::user()->id) {
                    if($document->stage >= 4){
                        return true;
                    } else {
                        return false;
                    }
                }
            }
        } else {
            return false;
        }
    }

    public static function checkUserRolesApprovers($data)
    {
        if ($data->role) {
            $datauser = explode(',', $data->role);
            for ($i = 0; $i < count($datauser); $i++) {
                if ($datauser[$i] == 1) {
                    return true;
                }
            }
        } else {
            return false;
        }
    }

    public static function checkUserRolesreviewer($data)
    {
        if ($data->role) {
            $datauser = explode(',', $data->role);
            for ($i = 0; $i < count($datauser); $i++) {
                if ($datauser[$i] == 2) {
                    return true;
                }
            }
        } else {
            return false;
        }
    }

    public static function checkUserRolestrainer($data)
    {
        if ($data->role) {
            $datauser = explode(',', $data->role);
            for ($i = 0; $i < count($datauser); $i++) {
                if ($datauser[$i] == 6) {
                    return true;
                }
            }
        } else {
            return false;
        }
    }

    public static function checkUserRolesassign_to($data)
    {
        if ($data->role) {
            $datauser = explode(',', $data->role);
            for ($i = 0; $i < count($datauser); $i++) {
                if ($datauser[$i] == 4) {
                    return true;
                }
            }
        } else {
            return false;
        }
    }

    public static function checkUserRolesMicrobiology_Person($data)
    {
        if ($data->role) {
            $datauser = explode(',', $data->role);
            for ($i = 0; $i < count($datauser); $i++) {
                if ($datauser[$i] == 5) {
                    return true;
                }
            }
        } else {
            return false;
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
