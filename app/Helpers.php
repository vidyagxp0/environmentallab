<?php

use App\Models\ActionItem;
use App\Models\Division;
use App\Models\QMSDivision;
use App\Models\QMSProcess;
use App\Models\User;
use App\Models\UserRole;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;

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
    public static function getDueDatemonthly($date = null, $addDays = false, $format = null)
    {
        try {
            $format = $format ? $format : 'd-M-Y';
            $dateInstance = $date ? Carbon::parse($date) : Carbon::now();

            if ($addDays) {
                $dateInstance->addDays($addDays);
            } else {
                // Add 30 days instead of adding a month
                $dateInstance->addDays(30);
            }

            return $dateInstance->format($format);
        } catch (\Exception $e) {
            return 'NA';
        }
    }
    public static function getdateFormat1($date)
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('d-M-Y H:i:s');
    }

    public static function isRevised($data)
    {
        if($data  >= 9){
            return 'disabled';
        }else{
            return  '';
        }

    }
    // public static function getHodUserList(){

    //     return $hodUserList = DB::table('user_roles')->where(['q_m_s_roles_id' =>'4'])->get();
    // }
    // public static function getQAUserList(){

    //     return $QAUserList = DB::table('user_roles')->where(['q_m_s_roles_id' =>'7'])->get();
    // }
    // public static function getInitiatorUserList(){

    //     return $InitiatorUserList = DB::table('user_roles')->where(['q_m_s_roles_id' =>'3'])->get();
    // }
    // public static function getApproverUserList(){

    //     return $ApproverUserList = DB::table('user_roles')->where(['q_m_s_roles_id' =>'1'])->get();
    // }
    // public static function getReviewerUserList(){

    //     return $ReviewerUserList = DB::table('user_roles')->where(['q_m_s_roles_id' =>'2'])->get();
    // }
    // public static function getCFTUserList(){

    //     return $CFTUserList = DB::table('user_roles')->where(['q_m_s_roles_id' =>'5'])->get();
    // }
    // public static function getTrainerUserList(){

    //     return $TrainerUserList = DB::table('user_roles')->where(['q_m_s_roles_id' =>'6'])->get();
    // }
    // public static function getActionOwnerUserList(){

    //     return $ActionOwnerUserList = DB::table('user_roles')->where(['q_m_s_roles_id' =>'8'])->get();
    // }
    // public static function getQAHeadUserList(){

    //     return $QAHeadUserList = DB::table('user_roles')->where(['q_m_s_roles_id' =>'9'])->get();
    // }

    public static function getQCHeadUserList(){
        return $QCHeadUserList = DB::table('user_roles')->where(['q_m_s_roles_id' =>'10'])->get();
    }

    // public static function getLeadAuditeeUserList(){

    //     return $LeadAuditeeUserList = DB::table('user_roles')->where(['q_m_s_roles_id' =>'11'])->get();
    // }
    // public static function getLeadAuditorUserList(){

    //     return $LeadAuditorUserList = DB::table('user_roles')->where(['q_m_s_roles_id' =>'12'])->get();
    // }
    // public static function getAuditManagerUserList(){

    //     return $AuditManagerUserList = DB::table('user_roles')->where(['q_m_s_roles_id' =>'13'])->get();
    // }
    // public static function getSupervisorUserList(){

    //     return $SupervisorUserList = DB::table('user_roles')->where(['q_m_s_roles_id' =>'14'])->get();
    // }
    // public static function getResponsibleUserList(){

    //     return $ResponsibleUserList = DB::table('user_roles')->where(['q_m_s_roles_id' =>'15'])->get();
    // }
    // public static function getWorkGroupUserList(){

    //     return $WorkGroupUserList = DB::table('user_roles')->where(['q_m_s_roles_id' =>'16'])->get();
    // }
    // public static function getViewUserList(){

    //     return $ViewUserList = DB::table('user_roles')->where(['q_m_s_roles_id' =>'17'])->get();
    // }
    // public static function getFPUserList(){

    //     return $FPUserList = DB::table('user_roles')->where(['q_m_s_roles_id' =>'18'])->get();
    // }

    public static function checkRoles($role)
    {

        $userRoles = DB::table('user_roles')->where(['user_id' => Auth::user()->id])->get();
        $userRoleIds = $userRoles->pluck('q_m_s_roles_id')->toArray();
        if(in_array($role, $userRoleIds)){
            return true;
        }else{
            return false;
        }
        // if (strpos(Auth::user()->role, $role) !== false) {
        //    return true;
        // }else{
        //     return false;
        // }
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
                    if($document->stage >= 3){
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
                if ($datauser[$i] == 18) {
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
                if ($datauser[$i] == 18) {
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
                if ($datauser[$i] == 7) {
                    return true;
                }
                if ($datauser[$i] == 18) {
                    return true;
                }
            }
        } else {
            return false;
        }
    }

    public static function checkUserIsOnlyInitiator($data)
    {
        if ($data->role) {
            $roles = explode(',', $data->role);

            $hasRoleInitiator = false;
            $hasRoleAppOrRev = false;

            foreach ($roles as $role) {
                if ($role == '3') {
                    $hasRoleInitiator = true;
                }
                if ($role == '1' || $role == '2') {
                    $hasRoleAppOrRev = true;
                }
            }

            if ($hasRoleInitiator && !$hasRoleAppOrRev) {
                return true;
            }

            return false;
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
        $roles = UserRole::where('user_id', $data->id)->get();

        foreach ($roles as $role) {
            if ($role->q_m_s_roles_id == 5)
            {
                return true;
            }
        }

        return false;
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
        return DB::table('q_m_s_divisions')->where('id', $id)->where('status', 1)->value('name');
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
        return   str_pad($id, 4, '0', STR_PAD_LEFT);
    }
    public static function getDepartmentWithString($id)
    {
        $response = '';
        if(!empty($id)){
            $response = $id;
        }
        return $response;
    }
    public static function getInitiatorEmail($id)
    {

        return   DB::table('users')->where('id',$id)->value('email');
    }

    // Helpers::formatNumberWithLeadingZeros(0)
    public static function formatNumberWithLeadingZeros($number)
    {
        return sprintf('%04d', $number);
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

public static function getInitiatorGroupFullName($shortName)
    {

        switch ($shortName) {
            case 'CQA':
                return 'Corporate Quality Assurance';
                break;
            case 'QAB':
                return 'Quality Assurance Biopharma';
                break;
            case 'CQC':
                return 'Central Quality Control';
                break;
            case 'MANU':
                return 'Manufacturing';
                break;
            case 'PSG':
                return 'Plasma Sourcing Group';
                break;
            case 'CS':
                return 'Central Stores';
                break;
            case 'ITG':
                return 'Information Technology Group';
                break;
            case 'MM':
                return 'Molecular Medicine';
                break;
            case 'CL':
                return 'Central Laboratory';
                break;
            case 'TT':
                return 'Tech Team';
                break;
            case 'QA':
                return 'Quality Assurance';
                break;
            case 'QM':
                return 'Quality Management';
                break;
            case 'IA':
                return 'IT Administration';
                break;
            case 'ACC':
                return 'Accounting';
                break;
            case 'LOG':
                return 'Logistics';
                break;
            case 'SM':
                return 'Senior Management';
                break;
            case 'BA':
                return 'Business Administration';
                break;
            default:
                return '';
                break;
        }
    }
// }

    static public function userIsQA()
    {
        $isQA = false;

        try {

            $auth_user = auth()->user();

            if ($auth_user && $auth_user->department && $auth_user->department->dc == 'QA') {
                return true;
            }

        } catch (\Exception $e) {
            info('Error in Helpers::userIsQA', [ 'message' => $e->getMessage(), 'obj' => $e ]);
        }

        return $isQA;
    }


    // public static function hodMail($data)
    // {
    //     Mail::send('hod-mail',['data' => $data],
    // function ($message){
    //         $message->to("shaleen.mishra@mydemosoftware.com")
    //                 ->subject('Record is for Review');
    //     });
    // }

    static public function getTimezones()
    {
        return array(
            'Pacific/Midway' => '(UTC-11:00) Midway',
            'Pacific/Niue' => '(UTC-11:00) Niue',
            'Pacific/Pago_Pago' => '(UTC-11:00) Pago Pago',
            'America/Adak' => '(UTC-10:00) Adak',
            'Pacific/Honolulu' => '(UTC-10:00) Honolulu',
            'Pacific/Johnston' => '(UTC-10:00) Johnston',
            'Pacific/Rarotonga' => '(UTC-10:00) Rarotonga',
            'Pacific/Tahiti' => '(UTC-10:00) Tahiti',
            'Pacific/Marquesas' => '(UTC-09:30) Marquesas',
            'America/Anchorage' => '(UTC-09:00) Anchorage',
            'Pacific/Gambier' => '(UTC-09:00) Gambier',
            'America/Juneau' => '(UTC-09:00) Juneau',
            'America/Nome' => '(UTC-09:00) Nome',
            'America/Sitka' => '(UTC-09:00) Sitka',
            'America/Yakutat' => '(UTC-09:00) Yakutat',
            'America/Dawson' => '(UTC-08:00) Dawson',
            'America/Los_Angeles' => '(UTC-08:00) Los Angeles',
            'America/Metlakatla' => '(UTC-08:00) Metlakatla',
            'Pacific/Pitcairn' => '(UTC-08:00) Pitcairn',
            'America/Santa_Isabel' => '(UTC-08:00) Santa Isabel',
            'America/Tijuana' => '(UTC-08:00) Tijuana',
            'America/Vancouver' => '(UTC-08:00) Vancouver',
            'America/Whitehorse' => '(UTC-08:00) Whitehorse',
            'America/Boise' => '(UTC-07:00) Boise',
            'America/Cambridge_Bay' => '(UTC-07:00) Cambridge Bay',
            'America/Chihuahua' => '(UTC-07:00) Chihuahua',
            'America/Creston' => '(UTC-07:00) Creston',
            'America/Dawson_Creek' => '(UTC-07:00) Dawson Creek',
            'America/Denver' => '(UTC-07:00) Denver',
            'America/Edmonton' => '(UTC-07:00) Edmonton',
            'America/Hermosillo' => '(UTC-07:00) Hermosillo',
            'America/Inuvik' => '(UTC-07:00) Inuvik',
            'America/Mazatlan' => '(UTC-07:00) Mazatlan',
            'America/Ojinaga' => '(UTC-07:00) Ojinaga',
            'America/Phoenix' => '(UTC-07:00) Phoenix',
            'America/Shiprock' => '(UTC-07:00) Shiprock',
            'America/Yellowknife' => '(UTC-07:00) Yellowknife',
            'America/Bahia_Banderas' => '(UTC-06:00) Bahia Banderas',
            'America/Belize' => '(UTC-06:00) Belize',
            'America/North_Dakota/Beulah' => '(UTC-06:00) Beulah',
            'America/Cancun' => '(UTC-06:00) Cancun',
            'America/North_Dakota/Center' => '(UTC-06:00) Center',
            'America/Chicago' => '(UTC-06:00) Chicago',
            'America/Costa_Rica' => '(UTC-06:00) Costa Rica',
            'Pacific/Easter' => '(UTC-06:00) Easter',
            'America/El_Salvador' => '(UTC-06:00) El Salvador',
            'Pacific/Galapagos' => '(UTC-06:00) Galapagos',
            'America/Guatemala' => '(UTC-06:00) Guatemala',
            'America/Indiana/Knox' => '(UTC-06:00) Knox',
            'America/Managua' => '(UTC-06:00) Managua',
            'America/Matamoros' => '(UTC-06:00) Matamoros',
            'America/Menominee' => '(UTC-06:00) Menominee',
            'America/Merida' => '(UTC-06:00) Merida',
            'America/Mexico_City' => '(UTC-06:00) Mexico City',
            'America/Monterrey' => '(UTC-06:00) Monterrey',
            'America/North_Dakota/New_Salem' => '(UTC-06:00) New Salem',
            'America/Rainy_River' => '(UTC-06:00) Rainy River',
            'America/Rankin_Inlet' => '(UTC-06:00) Rankin Inlet',
            'America/Regina' => '(UTC-06:00) Regina',
            'America/Resolute' => '(UTC-06:00) Resolute',
            'America/Swift_Current' => '(UTC-06:00) Swift Current',
            'America/Tegucigalpa' => '(UTC-06:00) Tegucigalpa',
            'America/Indiana/Tell_City' => '(UTC-06:00) Tell City',
            'America/Winnipeg' => '(UTC-06:00) Winnipeg',
            'America/Atikokan' => '(UTC-05:00) Atikokan',
            'America/Bogota' => '(UTC-05:00) Bogota',
            'America/Cayman' => '(UTC-05:00) Cayman',
            'America/Detroit' => '(UTC-05:00) Detroit',
            'America/Grand_Turk' => '(UTC-05:00) Grand Turk',
            'America/Guayaquil' => '(UTC-05:00) Guayaquil',
            'America/Havana' => '(UTC-05:00) Havana',
            'America/Indiana/Indianapolis' => '(UTC-05:00) Indianapolis',
            'America/Iqaluit' => '(UTC-05:00) Iqaluit',
            'America/Jamaica' => '(UTC-05:00) Jamaica',
            'America/Lima' => '(UTC-05:00) Lima',
            'America/Kentucky/Louisville' => '(UTC-05:00) Louisville',
            'America/Indiana/Marengo' => '(UTC-05:00) Marengo',
            'America/Kentucky/Monticello' => '(UTC-05:00) Monticello',
            'America/Montreal' => '(UTC-05:00) Montreal',
            'America/Nassau' => '(UTC-05:00) Nassau',
            'America/New_York' => '(UTC-05:00) New York',
            'America/Nipigon' => '(UTC-05:00) Nipigon',
            'America/Panama' => '(UTC-05:00) Panama',
            'America/Pangnirtung' => '(UTC-05:00) Pangnirtung',
            'America/Indiana/Petersburg' => '(UTC-05:00) Petersburg',
            'America/Port-au-Prince' => '(UTC-05:00) Port-au-Prince',
            'America/Thunder_Bay' => '(UTC-05:00) Thunder Bay',
            'America/Toronto' => '(UTC-05:00) Toronto',
            'America/Indiana/Vevay' => '(UTC-05:00) Vevay',
            'America/Indiana/Vincennes' => '(UTC-05:00) Vincennes',
            'America/Indiana/Winamac' => '(UTC-05:00) Winamac',
            'America/Caracas' => '(UTC-04:30) Caracas',
            'America/Anguilla' => '(UTC-04:00) Anguilla',
            'America/Antigua' => '(UTC-04:00) Antigua',
            'America/Aruba' => '(UTC-04:00) Aruba',
            'America/Asuncion' => '(UTC-04:00) Asuncion',
            'America/Barbados' => '(UTC-04:00) Barbados',
            'Atlantic/Bermuda' => '(UTC-04:00) Bermuda',
            'America/Blanc-Sablon' => '(UTC-04:00) Blanc-Sablon',
            'America/Boa_Vista' => '(UTC-04:00) Boa Vista',
            'America/Campo_Grande' => '(UTC-04:00) Campo Grande',
            'America/Cuiaba' => '(UTC-04:00) Cuiaba',
            'America/Curacao' => '(UTC-04:00) Curacao',
            'America/Dominica' => '(UTC-04:00) Dominica',
            'America/Eirunepe' => '(UTC-04:00) Eirunepe',
            'America/Glace_Bay' => '(UTC-04:00) Glace Bay',
            'America/Goose_Bay' => '(UTC-04:00) Goose Bay',
            'America/Grenada' => '(UTC-04:00) Grenada',
            'America/Guadeloupe' => '(UTC-04:00) Guadeloupe',
            'America/Guyana' => '(UTC-04:00) Guyana',
            'America/Halifax' => '(UTC-04:00) Halifax',
            'America/Kralendijk' => '(UTC-04:00) Kralendijk',
            'America/La_Paz' => '(UTC-04:00) La Paz',
            'America/Lower_Princes' => '(UTC-04:00) Lower Princes',
            'America/Manaus' => '(UTC-04:00) Manaus',
            'America/Marigot' => '(UTC-04:00) Marigot',
            'America/Martinique' => '(UTC-04:00) Martinique',
            'America/Moncton' => '(UTC-04:00) Moncton',
            'America/Montserrat' => '(UTC-04:00) Montserrat',
            'Antarctica/Palmer' => '(UTC-04:00) Palmer',
            'America/Port_of_Spain' => '(UTC-04:00) Port of Spain',
            'America/Porto_Velho' => '(UTC-04:00) Porto Velho',
            'America/Puerto_Rico' => '(UTC-04:00) Puerto Rico',
            'America/Rio_Branco' => '(UTC-04:00) Rio Branco',
            'America/Santiago' => '(UTC-04:00) Santiago',
            'America/Santo_Domingo' => '(UTC-04:00) Santo Domingo',
            'America/St_Barthelemy' => '(UTC-04:00) St. Barthelemy',
            'America/St_Kitts' => '(UTC-04:00) St. Kitts',
            'America/St_Lucia' => '(UTC-04:00) St. Lucia',
            'America/St_Thomas' => '(UTC-04:00) St. Thomas',
            'America/St_Vincent' => '(UTC-04:00) St. Vincent',
            'America/Thule' => '(UTC-04:00) Thule',
            'America/Tortola' => '(UTC-04:00) Tortola',
            'America/St_Johns' => '(UTC-03:30) St. Johns',
            'America/Araguaina' => '(UTC-03:00) Araguaina',
            'America/Bahia' => '(UTC-03:00) Bahia',
            'America/Belem' => '(UTC-03:00) Belem',
            'America/Argentina/Buenos_Aires' => '(UTC-03:00) Buenos Aires',
            'America/Argentina/Catamarca' => '(UTC-03:00) Catamarca',
            'America/Cayenne' => '(UTC-03:00) Cayenne',
            'America/Argentina/Cordoba' => '(UTC-03:00) Cordoba',
            'America/Fortaleza' => '(UTC-03:00) Fortaleza',
            'America/Godthab' => '(UTC-03:00) Godthab',
            'America/Argentina/Jujuy' => '(UTC-03:00) Jujuy',
            'America/Argentina/La_Rioja' => '(UTC-03:00) La Rioja',
            'America/Maceio' => '(UTC-03:00) Maceio',
            'America/Argentina/Mendoza' => '(UTC-03:00) Mendoza',
            'America/Miquelon' => '(UTC-03:00) Miquelon',
            'America/Montevideo' => '(UTC-03:00) Montevideo',
            'America/Paramaribo' => '(UTC-03:00) Paramaribo',
            'America/Recife' => '(UTC-03:00) Recife',
            'America/Argentina/Rio_Gallegos' => '(UTC-03:00) Rio Gallegos',
            'Antarctica/Rothera' => '(UTC-03:00) Rothera',
            'America/Argentina/Salta' => '(UTC-03:00) Salta',
            'America/Argentina/San_Juan' => '(UTC-03:00) San Juan',
            'America/Argentina/San_Luis' => '(UTC-03:00) San Luis',
            'America/Santarem' => '(UTC-03:00) Santarem',
            'America/Sao_Paulo' => '(UTC-03:00) Sao Paulo',
            'Atlantic/Stanley' => '(UTC-03:00) Stanley',
            'America/Argentina/Tucuman' => '(UTC-03:00) Tucuman',
            'America/Argentina/Ushuaia' => '(UTC-03:00) Ushuaia',
            'America/Noronha' => '(UTC-02:00) Noronha',
            'Atlantic/South_Georgia' => '(UTC-02:00) South Georgia',
            'Atlantic/Azores' => '(UTC-01:00) Azores',
            'Atlantic/Cape_Verde' => '(UTC-01:00) Cape Verde',
            'America/Scoresbysund' => '(UTC-01:00) Scoresbysund',
            'Africa/Abidjan' => '(UTC+00:00) Abidjan',
            'Africa/Accra' => '(UTC+00:00) Accra',
            'Africa/Bamako' => '(UTC+00:00) Bamako',
            'Africa/Banjul' => '(UTC+00:00) Banjul',
            'Africa/Bissau' => '(UTC+00:00) Bissau',
            'Atlantic/Canary' => '(UTC+00:00) Canary',
            'Africa/Casablanca' => '(UTC+00:00) Casablanca',
            'Africa/Conakry' => '(UTC+00:00) Conakry',
            'Africa/Dakar' => '(UTC+00:00) Dakar',
            'America/Danmarkshavn' => '(UTC+00:00) Danmarkshavn',
            'Europe/Dublin' => '(UTC+00:00) Dublin',
            'Africa/El_Aaiun' => '(UTC+00:00) El Aaiun',
            'Atlantic/Faroe' => '(UTC+00:00) Faroe',
            'Africa/Freetown' => '(UTC+00:00) Freetown',
            'Europe/Guernsey' => '(UTC+00:00) Guernsey',
            'Europe/Isle_of_Man' => '(UTC+00:00) Isle of Man',
            'Europe/Jersey' => '(UTC+00:00) Jersey',
            'Europe/Lisbon' => '(UTC+00:00) Lisbon',
            'Africa/Lome' => '(UTC+00:00) Lome',
            'Europe/London' => '(UTC+00:00) London',
            'Atlantic/Madeira' => '(UTC+00:00) Madeira',
            'Africa/Monrovia' => '(UTC+00:00) Monrovia',
            'Africa/Nouakchott' => '(UTC+00:00) Nouakchott',
            'Africa/Ouagadougou' => '(UTC+00:00) Ouagadougou',
            'Atlantic/Reykjavik' => '(UTC+00:00) Reykjavik',
            'Africa/Sao_Tome' => '(UTC+00:00) Sao Tome',
            'Atlantic/St_Helena' => '(UTC+00:00) St. Helena',
            'UTC' => '(UTC+00:00) UTC',
            'Africa/Algiers' => '(UTC+01:00) Algiers',
            'Europe/Amsterdam' => '(UTC+01:00) Amsterdam',
            'Europe/Andorra' => '(UTC+01:00) Andorra',
            'Africa/Bangui' => '(UTC+01:00) Bangui',
            'Europe/Belgrade' => '(UTC+01:00) Belgrade',
            'Europe/Berlin' => '(UTC+01:00) Berlin',
            'Europe/Bratislava' => '(UTC+01:00) Bratislava',
            'Africa/Brazzaville' => '(UTC+01:00) Brazzaville',
            'Europe/Brussels' => '(UTC+01:00) Brussels',
            'Europe/Budapest' => '(UTC+01:00) Budapest',
            'Europe/Busingen' => '(UTC+01:00) Busingen',
            'Africa/Ceuta' => '(UTC+01:00) Ceuta',
            'Europe/Copenhagen' => '(UTC+01:00) Copenhagen',
            'Africa/Douala' => '(UTC+01:00) Douala',
            'Europe/Gibraltar' => '(UTC+01:00) Gibraltar',
            'Africa/Kinshasa' => '(UTC+01:00) Kinshasa',
            'Africa/Lagos' => '(UTC+01:00) Lagos',
            'Africa/Libreville' => '(UTC+01:00) Libreville',
            'Europe/Ljubljana' => '(UTC+01:00) Ljubljana',
            'Arctic/Longyearbyen' => '(UTC+01:00) Longyearbyen',
            'Africa/Luanda' => '(UTC+01:00) Luanda',
            'Europe/Luxembourg' => '(UTC+01:00) Luxembourg',
            'Europe/Madrid' => '(UTC+01:00) Madrid',
            'Africa/Malabo' => '(UTC+01:00) Malabo',
            'Europe/Malta' => '(UTC+01:00) Malta',
            'Europe/Monaco' => '(UTC+01:00) Monaco',
            'Africa/Ndjamena' => '(UTC+01:00) Ndjamena',
            'Africa/Niamey' => '(UTC+01:00) Niamey',
            'Europe/Oslo' => '(UTC+01:00) Oslo',
            'Europe/Paris' => '(UTC+01:00) Paris',
            'Europe/Podgorica' => '(UTC+01:00) Podgorica',
            'Africa/Porto-Novo' => '(UTC+01:00) Porto-Novo',
            'Europe/Prague' => '(UTC+01:00) Prague',
            'Europe/Rome' => '(UTC+01:00) Rome',
            'Europe/San_Marino' => '(UTC+01:00) San Marino',
            'Europe/Sarajevo' => '(UTC+01:00) Sarajevo',
            'Europe/Skopje' => '(UTC+01:00) Skopje',
            'Europe/Stockholm' => '(UTC+01:00) Stockholm',
            'Europe/Tirane' => '(UTC+01:00) Tirane',
            'Africa/Tripoli' => '(UTC+01:00) Tripoli',
            'Africa/Tunis' => '(UTC+01:00) Tunis',
            'Europe/Vaduz' => '(UTC+01:00) Vaduz',
            'Europe/Vatican' => '(UTC+01:00) Vatican',
            'Europe/Vienna' => '(UTC+01:00) Vienna',
            'Europe/Warsaw' => '(UTC+01:00) Warsaw',
            'Africa/Windhoek' => '(UTC+01:00) Windhoek',
            'Europe/Zagreb' => '(UTC+01:00) Zagreb',
            'Europe/Zurich' => '(UTC+01:00) Zurich',
            'Europe/Athens' => '(UTC+02:00) Athens',
            'Asia/Beirut' => '(UTC+02:00) Beirut',
            'Africa/Blantyre' => '(UTC+02:00) Blantyre',
            'Europe/Bucharest' => '(UTC+02:00) Bucharest',
            'Africa/Bujumbura' => '(UTC+02:00) Bujumbura',
            'Africa/Cairo' => '(UTC+02:00) Cairo',
            'Europe/Chisinau' => '(UTC+02:00) Chisinau',
            'Asia/Damascus' => '(UTC+02:00) Damascus',
            'Africa/Gaborone' => '(UTC+02:00) Gaborone',
            'Asia/Gaza' => '(UTC+02:00) Gaza',
            'Africa/Harare' => '(UTC+02:00) Harare',
            'Asia/Hebron' => '(UTC+02:00) Hebron',
            'Europe/Helsinki' => '(UTC+02:00) Helsinki',
            'Europe/Istanbul' => '(UTC+02:00) Istanbul',
            'Asia/Jerusalem' => '(UTC+02:00) Jerusalem',
            'Africa/Johannesburg' => '(UTC+02:00) Johannesburg',
            'Europe/Kiev' => '(UTC+02:00) Kiev',
            'Africa/Kigali' => '(UTC+02:00) Kigali',
            'Africa/Lubumbashi' => '(UTC+02:00) Lubumbashi',
            'Africa/Lusaka' => '(UTC+02:00) Lusaka',
            'Africa/Maputo' => '(UTC+02:00) Maputo',
            'Europe/Mariehamn' => '(UTC+02:00) Mariehamn',
            'Africa/Maseru' => '(UTC+02:00) Maseru',
            'Africa/Mbabane' => '(UTC+02:00) Mbabane',
            'Asia/Nicosia' => '(UTC+02:00) Nicosia',
            'Europe/Riga' => '(UTC+02:00) Riga',
            'Europe/Simferopol' => '(UTC+02:00) Simferopol',
            'Europe/Sofia' => '(UTC+02:00) Sofia',
            'Europe/Tallinn' => '(UTC+02:00) Tallinn',
            'Europe/Uzhgorod' => '(UTC+02:00) Uzhgorod',
            'Europe/Vilnius' => '(UTC+02:00) Vilnius',
            'Europe/Zaporozhye' => '(UTC+02:00) Zaporozhye',
            'Africa/Addis_Ababa' => '(UTC+03:00) Addis Ababa',
            'Asia/Aden' => '(UTC+03:00) Aden',
            'Asia/Amman' => '(UTC+03:00) Amman',
            'Indian/Antananarivo' => '(UTC+03:00) Antananarivo',
            'Africa/Asmara' => '(UTC+03:00) Asmara',
            'Asia/Baghdad' => '(UTC+03:00) Baghdad',
            'Asia/Bahrain' => '(UTC+03:00) Bahrain',
            'Indian/Comoro' => '(UTC+03:00) Comoro',
            'Africa/Dar_es_Salaam' => '(UTC+03:00) Dar es Salaam',
            'Africa/Djibouti' => '(UTC+03:00) Djibouti',
            'Africa/Juba' => '(UTC+03:00) Juba',
            'Europe/Kaliningrad' => '(UTC+03:00) Kaliningrad',
            'Africa/Kampala' => '(UTC+03:00) Kampala',
            'Africa/Khartoum' => '(UTC+03:00) Khartoum',
            'Asia/Kuwait' => '(UTC+03:00) Kuwait',
            'Indian/Mayotte' => '(UTC+03:00) Mayotte',
            'Europe/Minsk' => '(UTC+03:00) Minsk',
            'Africa/Mogadishu' => '(UTC+03:00) Mogadishu',
            'Europe/Moscow' => '(UTC+03:00) Moscow',
            'Africa/Nairobi' => '(UTC+03:00) Nairobi',
            'Asia/Qatar' => '(UTC+03:00) Qatar',
            'Asia/Riyadh' => '(UTC+03:00) Riyadh',
            'Antarctica/Syowa' => '(UTC+03:00) Syowa',
            'Asia/Tehran' => '(UTC+03:30) Tehran',
            'Asia/Baku' => '(UTC+04:00) Baku',
            'Asia/Dubai' => '(UTC+04:00) Dubai',
            'Indian/Mahe' => '(UTC+04:00) Mahe',
            'Indian/Mauritius' => '(UTC+04:00) Mauritius',
            'Asia/Muscat' => '(UTC+04:00) Muscat',
            'Indian/Reunion' => '(UTC+04:00) Reunion',
            'Europe/Samara' => '(UTC+04:00) Samara',
            'Asia/Tbilisi' => '(UTC+04:00) Tbilisi',
            'Europe/Volgograd' => '(UTC+04:00) Volgograd',
            'Asia/Yerevan' => '(UTC+04:00) Yerevan',
            'Asia/Kabul' => '(UTC+04:30) Kabul',
            'Asia/Aqtau' => '(UTC+05:00) Aqtau',
            'Asia/Aqtobe' => '(UTC+05:00) Aqtobe',
            'Asia/Ashgabat' => '(UTC+05:00) Ashgabat',
            'Asia/Dushanbe' => '(UTC+05:00) Dushanbe',
            'Asia/Karachi' => '(UTC+05:00) Karachi',
            'Indian/Kerguelen' => '(UTC+05:00) Kerguelen',
            'Indian/Maldives' => '(UTC+05:00) Maldives',
            'Antarctica/Mawson' => '(UTC+05:00) Mawson',
            'Asia/Oral' => '(UTC+05:00) Oral',
            'Asia/Samarkand' => '(UTC+05:00) Samarkand',
            'Asia/Tashkent' => '(UTC+05:00) Tashkent',
            'Asia/Colombo' => '(UTC+05:30) Colombo',
            'Asia/Kolkata' => '(UTC+05:30) Kolkata',
            'Asia/Kathmandu' => '(UTC+05:45) Kathmandu',
            'Asia/Almaty' => '(UTC+06:00) Almaty',
            'Asia/Bishkek' => '(UTC+06:00) Bishkek',
            'Indian/Chagos' => '(UTC+06:00) Chagos',
            'Asia/Dhaka' => '(UTC+06:00) Dhaka',
            'Asia/Qyzylorda' => '(UTC+06:00) Qyzylorda',
            'Asia/Thimphu' => '(UTC+06:00) Thimphu',
            'Antarctica/Vostok' => '(UTC+06:00) Vostok',
            'Asia/Yekaterinburg' => '(UTC+06:00) Yekaterinburg',
            'Indian/Cocos' => '(UTC+06:30) Cocos',
            'Asia/Rangoon' => '(UTC+06:30) Rangoon',
            'Asia/Bangkok' => '(UTC+07:00) Bangkok',
            'Indian/Christmas' => '(UTC+07:00) Christmas',
            'Antarctica/Davis' => '(UTC+07:00) Davis',
            'Asia/Ho_Chi_Minh' => '(UTC+07:00) Ho Chi Minh',
            'Asia/Hovd' => '(UTC+07:00) Hovd',
            'Asia/Jakarta' => '(UTC+07:00) Jakarta',
            'Asia/Novokuznetsk' => '(UTC+07:00) Novokuznetsk',
            'Asia/Novosibirsk' => '(UTC+07:00) Novosibirsk',
            'Asia/Omsk' => '(UTC+07:00) Omsk',
            'Asia/Phnom_Penh' => '(UTC+07:00) Phnom Penh',
            'Asia/Pontianak' => '(UTC+07:00) Pontianak',
            'Asia/Vientiane' => '(UTC+07:00) Vientiane',
            'Asia/Brunei' => '(UTC+08:00) Brunei',
            'Antarctica/Casey' => '(UTC+08:00) Casey',
            'Asia/Choibalsan' => '(UTC+08:00) Choibalsan',
            'Asia/Chongqing' => '(UTC+08:00) Chongqing',
            'Asia/Harbin' => '(UTC+08:00) Harbin',
            'Asia/Hong_Kong' => '(UTC+08:00) Hong Kong',
            'Asia/Kashgar' => '(UTC+08:00) Kashgar',
            'Asia/Krasnoyarsk' => '(UTC+08:00) Krasnoyarsk',
            'Asia/Kuala_Lumpur' => '(UTC+08:00) Kuala Lumpur',
            'Asia/Kuching' => '(UTC+08:00) Kuching',
            'Asia/Macau' => '(UTC+08:00) Macau',
            'Asia/Makassar' => '(UTC+08:00) Makassar',
            'Asia/Manila' => '(UTC+08:00) Manila',
            'Australia/Perth' => '(UTC+08:00) Perth',
            'Asia/Shanghai' => '(UTC+08:00) Shanghai',
            'Asia/Singapore' => '(UTC+08:00) Singapore',
            'Asia/Taipei' => '(UTC+08:00) Taipei',
            'Asia/Ulaanbaatar' => '(UTC+08:00) Ulaanbaatar',
            'Asia/Urumqi' => '(UTC+08:00) Urumqi',
            'Australia/Eucla' => '(UTC+08:45) Eucla',
            'Asia/Dili' => '(UTC+09:00) Dili',
            'Asia/Irkutsk' => '(UTC+09:00) Irkutsk',
            'Asia/Jayapura' => '(UTC+09:00) Jayapura',
            'Pacific/Palau' => '(UTC+09:00) Palau',
            'Asia/Pyongyang' => '(UTC+09:00) Pyongyang',
            'Asia/Seoul' => '(UTC+09:00) Seoul',
            'Asia/Tokyo' => '(UTC+09:00) Tokyo',
            'Australia/Adelaide' => '(UTC+09:30) Adelaide',
            'Australia/Broken_Hill' => '(UTC+09:30) Broken Hill',
            'Australia/Darwin' => '(UTC+09:30) Darwin',
            'Australia/Brisbane' => '(UTC+10:00) Brisbane',
            'Pacific/Chuuk' => '(UTC+10:00) Chuuk',
            'Australia/Currie' => '(UTC+10:00) Currie',
            'Antarctica/DumontDUrville' => '(UTC+10:00) DumontDUrville',
            'Pacific/Guam' => '(UTC+10:00) Guam',
            'Australia/Hobart' => '(UTC+10:00) Hobart',
            'Asia/Khandyga' => '(UTC+10:00) Khandyga',
            'Australia/Lindeman' => '(UTC+10:00) Lindeman',
            'Australia/Melbourne' => '(UTC+10:00) Melbourne',
            'Pacific/Port_Moresby' => '(UTC+10:00) Port Moresby',
            'Pacific/Saipan' => '(UTC+10:00) Saipan',
            'Australia/Sydney' => '(UTC+10:00) Sydney',
            'Asia/Yakutsk' => '(UTC+10:00) Yakutsk',
            'Australia/Lord_Howe' => '(UTC+10:30) Lord Howe',
            'Pacific/Efate' => '(UTC+11:00) Efate',
            'Pacific/Guadalcanal' => '(UTC+11:00) Guadalcanal',
            'Pacific/Kosrae' => '(UTC+11:00) Kosrae',
            'Antarctica/Macquarie' => '(UTC+11:00) Macquarie',
            'Pacific/Noumea' => '(UTC+11:00) Noumea',
            'Pacific/Pohnpei' => '(UTC+11:00) Pohnpei',
            'Asia/Sakhalin' => '(UTC+11:00) Sakhalin',
            'Asia/Ust-Nera' => '(UTC+11:00) Ust-Nera',
            'Asia/Vladivostok' => '(UTC+11:00) Vladivostok',
            'Pacific/Norfolk' => '(UTC+11:30) Norfolk',
            'Asia/Anadyr' => '(UTC+12:00) Anadyr',
            'Pacific/Auckland' => '(UTC+12:00) Auckland',
            'Pacific/Fiji' => '(UTC+12:00) Fiji',
            'Pacific/Funafuti' => '(UTC+12:00) Funafuti',
            'Asia/Kamchatka' => '(UTC+12:00) Kamchatka',
            'Pacific/Kwajalein' => '(UTC+12:00) Kwajalein',
            'Asia/Magadan' => '(UTC+12:00) Magadan',
            'Pacific/Majuro' => '(UTC+12:00) Majuro',
            'Antarctica/McMurdo' => '(UTC+12:00) McMurdo',
            'Pacific/Nauru' => '(UTC+12:00) Nauru',
            'Antarctica/South_Pole' => '(UTC+12:00) South Pole',
            'Pacific/Tarawa' => '(UTC+12:00) Tarawa',
            'Pacific/Wake' => '(UTC+12:00) Wake',
            'Pacific/Wallis' => '(UTC+12:00) Wallis',
            'Pacific/Chatham' => '(UTC+12:45) Chatham',
            'Pacific/Apia' => '(UTC+13:00) Apia',
            'Pacific/Enderbury' => '(UTC+13:00) Enderbury',
            'Pacific/Fakaofo' => '(UTC+13:00) Fakaofo',
            'Pacific/Tongatapu' => '(UTC+13:00) Tongatapu',
            'Pacific/Kiritimati' => '(UTC+14:00) Kiritimati',
        );
    }

    public static function check_roles($division_id, $process_name, $role_id, $user_id = null)
    {

        $process = QMSProcess::where([
            'division_id' => $division_id,
            'process_name' => $process_name
        ])->first();

        $roleExists = DB::table('user_roles')->where([
            'user_id' => $user_id ? $user_id : Auth::user()->id,
            'q_m_s_divisions_id' => $division_id,
            'q_m_s_processes_id' => $process ? $process->id : 0,
            'q_m_s_roles_id' => $role_id
        ])->first();

        return $roleExists ? true : false;
    }

    //*************** get user role email *************/
    public static function getAllUserEmail($id)
    {
        $email = null;
        try {
            $email  = User::find($id)->email;
        } catch (\Exception $e) {
            \Log::error('Failed to retrieve email for user ID ' . $id . ': ' . $e->getMessage());
        }
        return $email;
    }


     /************* Get Roles List Ends ***************/

     public static function getApproverUserList($division = null){
        if (!$division) {
            return $hodUserList = DB::table('user_roles')->where(['q_m_s_roles_id' => '1'])->select(['user_id', DB::raw('MAX(q_m_s_divisions_id) as q_m_s_divisions_id')])->groupBy('user_id')->get();
        } else {
            return DB::table('user_roles')->where(['q_m_s_roles_id' => '1', 'q_m_s_divisions_id' => $division])->select('user_id')->distinct()->get();
        }
    }
    public static function getReviewerUserList($division = null){
        if (!$division) {
            return $QAUserList = DB::table('user_roles')->where(['q_m_s_roles_id' => '2'])->select(['user_id', DB::raw('MAX(q_m_s_divisions_id) as q_m_s_divisions_id')])->groupBy('user_id')->get();
        } else {
            return DB::table('user_roles')->where(['q_m_s_roles_id' => '2', 'q_m_s_divisions_id' => $division])->select('user_id')->distinct()->get();
        }
    }

    public static function getInitiatorUserList($division = null){
        if (!$division) {
            return $InitiatorUserList = DB::table('user_roles')->where(['q_m_s_roles_id' => '3'])->select(['user_id', DB::raw('MAX(q_m_s_divisions_id) as q_m_s_divisions_id')])->groupBy('user_id')->get();
        } else {
            return DB::table('user_roles')->where(['q_m_s_roles_id' => '3', 'q_m_s_divisions_id' => $division])->select('user_id')->distinct()->get();
        }
    }

    public static function getHODUserList($division = null) {
        if (!$division) {
            return $InitiatorUserList = DB::table('user_roles')->where(['q_m_s_roles_id' => '4'])->select(['user_id', DB::raw('MAX(q_m_s_divisions_id) as q_m_s_divisions_id')])->groupBy('user_id')->get();
        } else {
            return DB::table('user_roles')->where(['q_m_s_roles_id' => '4', 'q_m_s_divisions_id' => $division])->select('user_id')->distinct()->get();
        }
    }

    public static function getCFTUserList($division = null) {
        if (!$division) {
            return $PurchaseDepartmentList = DB::table('user_roles')->where(['q_m_s_roles_id' => '5'])->select(['user_id', DB::raw('MAX(q_m_s_divisions_id) as q_m_s_divisions_id')])->groupBy('user_id')->get();
        } else {
            return DB::table('user_roles')->where(['q_m_s_roles_id' => '5', 'q_m_s_divisions_id' => $division])->select('user_id')->distinct()->get();
        }
    }

    public static function getTrainerUserList($division = null) {
        if (!$division) {
            return $FormulationDepartmentList = DB::table('user_roles')->where(['q_m_s_roles_id' => '6'])->select(['user_id', DB::raw('MAX(q_m_s_divisions_id) as q_m_s_divisions_id')])->groupBy('user_id')->get();
        } else {
            return DB::table('user_roles')->where(['q_m_s_roles_id' => '6', 'q_m_s_divisions_id' => $division])->select('user_id')->distinct()->get();
        }
    }

    public static function getQAUserList($division = null) {
        if (!$division) {
            return $SupplierAuditorDepartmentList = DB::table('user_roles')->where(['q_m_s_roles_id' => '7'])->select(['user_id', DB::raw('MAX(q_m_s_divisions_id) as q_m_s_divisions_id')])->groupBy('user_id')->get();
        } else {
            return DB::table('user_roles')->where(['q_m_s_roles_id' => '7', 'q_m_s_divisions_id' => $division])->select('user_id')->distinct()->get();
        }
    }

    public static function getActionOwnerUserList($division = null) {
        if (!$division) {
            return $SupplierContactDepartmentList = DB::table('user_roles')->where(['q_m_s_roles_id' => '8'])->select(['user_id', DB::raw('MAX(q_m_s_divisions_id) as q_m_s_divisions_id')])->groupBy('user_id')->get();
        } else {
            return DB::table('user_roles')->where(['q_m_s_roles_id' => '8', 'q_m_s_divisions_id' => $division])->select('user_id')->distinct()->get();
        }
    }

    public static function getQAHeadDesigneeUserList($division = null) {
        if (!$division) {
            return $AuditManagerDepartmentList = DB::table('user_roles')->where(['q_m_s_roles_id' => '9'])->select(['user_id', DB::raw('MAX(q_m_s_divisions_id) as q_m_s_divisions_id')])->groupBy('user_id')->get();
        } else {
            return DB::table('user_roles')->where(['q_m_s_roles_id' => '9', 'q_m_s_divisions_id' => $division])->select('user_id')->distinct()->get();
        }
    }

    public static function getQCHeadDesigneeUserList($division = null) {
        if (!$division) {
            return $AuditeeDepartmentList = DB::table('user_roles')->where(['q_m_s_roles_id' => '10'])->select(['user_id', DB::raw('MAX(q_m_s_divisions_id) as q_m_s_divisions_id')])->groupBy('user_id')->get();
        } else {
            return DB::table('user_roles')->where(['q_m_s_roles_id' => '10', 'q_m_s_divisions_id' => $division])->select('user_id')->distinct()->get();
        }
    }

    public static function getLeadAuditeeUserList($division = null) {
        if (!$division) {
            return $BusinessRuleengineDeptList = DB::table('user_roles')->where(['q_m_s_roles_id' => '11'])->select(['user_id', DB::raw('MAX(q_m_s_divisions_id) as q_m_s_divisions_id')])->groupBy('user_id')->get();
        } else {
            return DB::table('user_roles')->where(['q_m_s_roles_id' => '11', 'q_m_s_divisions_id' => $division])->select('user_id')->distinct()->get();
        }
    }

    public static function getLeadAuditorUserList($division = null){
        if (!$division) {
            return $WorkGroupUserList = DB::table('user_roles')->where(['q_m_s_roles_id' => '12'])->select(['user_id', DB::raw('MAX(q_m_s_divisions_id) as q_m_s_divisions_id')])->groupBy('user_id')->get();
        } else {
            return DB::table('user_roles')->where(['q_m_s_roles_id' => '12', 'q_m_s_divisions_id' => $division])->select('user_id')->distinct()->get();
        }
    }

    public static function getAuditManagerUserList($division = null){
        if (!$division) {
            return $CFTUserList = DB::table('user_roles')->where(['q_m_s_roles_id' => '13'])->select(['user_id', DB::raw('MAX(q_m_s_divisions_id) as q_m_s_divisions_id')])->groupBy('user_id')->get();
        } else {
            return DB::table('user_roles')->where(['q_m_s_roles_id' => '13', 'q_m_s_divisions_id' => $division])->select('user_id')->distinct()->get();
        }
    }

    public static function getSupervisorUserList($division = null){
        if (!$division) {
            return $QAHeadUserList = DB::table('user_roles')->where(['q_m_s_roles_id' => '14'])->select(['user_id', DB::raw('MAX(q_m_s_divisions_id) as q_m_s_divisions_id')])->groupBy('user_id')->get();
        } else {
            return DB::table('user_roles')->where(['q_m_s_roles_id' => '14', 'q_m_s_divisions_id' => $division])->select('user_id')->distinct()->get();
        }
    }

    public static function getResponsiblePersonUserList($division = null){
        if (!$division) {
            return $AuditorsList = DB::table('user_roles')->where(['q_m_s_roles_id' => '15'])->select(['user_id', DB::raw('MAX(q_m_s_divisions_id) as q_m_s_divisions_id')])->groupBy('user_id')->get();
        } else {
            return DB::table('user_roles')->where(['q_m_s_roles_id' => '15', 'q_m_s_divisions_id' => $division])->select('user_id')->distinct()->get();
        }
    }

    public static function getWorkGroupUserList($division = null){
        if (!$division) {
            return $AuditeesList = DB::table('user_roles')->where(['q_m_s_roles_id' => '16'])->select(['user_id', DB::raw('MAX(q_m_s_divisions_id) as q_m_s_divisions_id')])->groupBy('user_id')->get();
        } else {
            return DB::table('user_roles')->where(['q_m_s_roles_id' => '16', 'q_m_s_divisions_id' => $division])->select('user_id')->distinct()->get();
        }
    }

    public static function getViewOnlyUserList($division = null){
        if (!$division) {
            return $QualityList = DB::table('user_roles')->where(['q_m_s_roles_id' => '17'])->select(['user_id', DB::raw('MAX(q_m_s_divisions_id) as q_m_s_divisions_id')])->groupBy('user_id')->get();
        } else {
            return DB::table('user_roles')->where(['q_m_s_roles_id' => '17', 'q_m_s_divisions_id' => $division])->select('user_id')->distinct()->get();
        }
    }

    public static function getFPUserList($division = null){
        if (!$division) {
            return $QaReviewerList = DB::table('user_roles')->where(['q_m_s_roles_id' => '18'])->select(['user_id', DB::raw('MAX(q_m_s_divisions_id) as q_m_s_divisions_id')])->groupBy('user_id')->get();
        } else {
            return DB::table('user_roles')->where(['q_m_s_roles_id' => '18', 'q_m_s_divisions_id' => $division])->select('user_id')->distinct()->get();
        }
    }

    public static function getObsoleteUserList($division = null){
        if (!$division) {
            return $ActionOwnerUserList = DB::table('user_roles')->where(['q_m_s_roles_id' => '19'])->select(['user_id', DB::raw('MAX(q_m_s_divisions_id) as q_m_s_divisions_id')])->groupBy('user_id')->get();
        } else {
            return DB::table('user_roles')->where(['q_m_s_roles_id' => '19', 'q_m_s_divisions_id' => $division])->select('user_id')->distinct()->get();
        }
    }

    public static function check_roles_documents($role_id, $user_id, $division_id = 6, $process_name = "New Document")
    {

        $process = QMSProcess::where([
            'division_id' => $division_id,
            'process_name' => $process_name
        ])->first();

        $roleExists = DB::table('user_roles')->where([
            'user_id' => $user_id ? $user_id : Auth::user()->id,
            'q_m_s_divisions_id' => $division_id,
            'q_m_s_processes_id' => $process ? $process->id : 0,
            'q_m_s_roles_id' => $role_id
        ])->first();

        return $roleExists ? true : false;
    }

    public static function check_roles_documents_new($role_id, $user_id, $division_id = 2, $process_name = "New Document")
    {

        $processIds = QMSProcess::where([
            'process_name' => $process_name
        ])->pluck('id');

        $roleExists = DB::table('user_roles')
                ->where('user_id', $user_id ?: Auth::id())
                ->whereIn('q_m_s_processes_id', $processIds)
                ->where('q_m_s_roles_id', $role_id)
                ->exists();

        return $roleExists ? true : false;
    }

    public static function check_roles_qms($role_id, $user_id = null, $division_id = [1,2,3,4,5,6,7,8], $process_names = ['Effective Check', 'Lab Incident', 'CAPA', 'Audit Program', 'Action Item', 'Internal Audit', 'External Audit', 'Deviation', 'Change Control', 'Risk Assessment', 'Root Cause Analysis', 'Observation', 'Extension'])
    {
        // Get user ID if not passed
        $user_id = $user_id ?? Auth::id();

        // Get all matching process IDs
        $process_ids = QMSProcess::whereIn('division_id', $division_id)
            ->whereIn('process_name', $process_names)
            ->pluck('id');

        if ($process_ids->isEmpty()) {
            return false;
        }

        // Check if user has the role for any of the matching processes
        $roleExists = DB::table('user_roles')
            ->where('user_id', $user_id)
            ->whereIn('q_m_s_divisions_id', $division_id)
            ->whereIn('q_m_s_processes_id', $process_ids)
            ->where('q_m_s_roles_id', $role_id)
            ->exists();

        return $roleExists;
    }



}
