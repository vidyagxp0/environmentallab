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
    // public static function getInitiatorGroup($id)
    // {
    //     $response = [];
    //     $resp = [];
    //     if(!empty($id)){
    //         $result = explode(',',$id);
    //         if(in_array(1,$result)){
    //             array_push($response, 'QAB');
    //         }
    //         if(in_array(2,$result)){
    //             array_push($response, 'CQC');
    //         }
    //         if(in_array(3,$result)){
    //             array_push($response, 'MANU');
    //         }
    //         if(in_array(4,$result)){
    //             array_push($response, 'PSG');
    //         }
    //         if(in_array(5,$result)){
    //             array_push($response, 'CS');
    //         }
    //         if(in_array(6,$result)){
    //             array_push($response, 'ITG');
    //         }
    //         if(in_array(7,$result)){
    //             array_push($response, 'MM');
    //         }
    //         if(in_array(8,$result)){
    //             array_push($response, 'CL');
    //         }
    //         if(in_array(9,$result)){
    //             array_push($response, 'TT');
    //         }
    //         if(in_array(10,$result)){
    //             array_push($response, 'QA');
    //         }
    //         if(in_array(11,$result)){
    //             array_push($response, 'QM');
    //         }
    //         if(in_array(12,$result)){
    //             array_push($response, 'IA');
    //         }
    //         if(in_array(13,$result)){
    //             array_push($response, 'ACC');
    //         }
    //         if(in_array(14,$result)){
    //             array_push($response, 'LOG');
    //         }
    //         if(in_array(15,$result)){
    //             array_push($response, 'SM');
    //         }
    //         if(in_array(16,$result)){
    //             array_push($response, 'BA');
    //         }
    //         $resp = implode(',',$response);
    //     }
    //     return $resp;
    // }
    // app/Helpers.php

// if (!function_exists('getInitiatorGroupFullName')) {
//     /**
//      * Get the full name of the initiator group based on its short name.
//      *
//      * @param string $shortName
//      * @return string
//      */
//     function getInitiatorGroupFullName($shortName)
//     {
//         switch ($shortName) {
//             case 'Corporate Quality Assurance':
//                 return 'Corporate Quality Assurance';
//                 break;
//             case 'QAB':
//                 return 'Quality Assurance Biopharma';
//                 break;
//             case 'CQC':
//                 return 'Central Quality Control';
//                 break;
//             case 'MANU':
//                 return 'Manufacturing';
//                 break;
//             case 'PSG':
//                 return 'Plasma Sourcing Group';
//                 break;
//             case 'CS':
//                 return 'Central Stores';
//                 break;
//             case 'ITG':
//                 return 'Information Technology Group';
//                 break;
//             case 'MM':
//                 return 'Molecular Medicine';
//                 break;
//             case 'CL':
//                 return 'Central Laboratory';
//                 break;
//             case 'TT':
//                 return 'Tech Team';
//                 break;
//             case 'QA':
//                 return 'Quality Assurance';
//                 break;
//             case 'QM':
//                 return 'Quality Management';
//                 break;
//             case 'IA':
//                 return 'IT Administration';
//                 break;
//             case 'ACC':
//                 return 'Accounting';
//                 break;
//             case 'LOG':
//                 return 'Logistics';
//                 break;
//             case 'SM':
//                 return 'Senior Management';
//                 break;
//             case 'BA':
//                 return 'Business Administration';
//                 break;
//             default:
//                 return '';
//                 break;
//         }
//     }
// }


    public static function hodMail($data)
    {
        Mail::send('hod-mail',['data' => $data],
    function ($message){
            $message->to("shaleen.mishra@mydemosoftware.com")
                    ->subject('Record is for Review');
        });
    }
}
