<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\User;
use App\Models\Grouppermission;
use App\Http\Controllers\Controller;
use App\Models\Recipent;
use App\Models\Subscribe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;

class DashboardController extends Controller
{

    function random_color_part()
    {
        return str_pad(dechex(mt_rand(0, 255)), 2, '0', STR_PAD_LEFT);
    }

    function random_color()
    {
        return $this->random_color_part() . $this->random_color_part() . $this->random_color_part();
    }

    public function index()
    {
        if (Auth::user()->role == 3) {
            $count = [];
            $draft = Document::where('originator_id', Auth::user()->id)->where('stage', 1)->count();
            $in_review = Document::where('originator_id', Auth::user()->id)->where('stage', 2)->count();
            $reviewed = Document::where('originator_id', Auth::user()->id)->where('stage', 3)->count();
            $for_approve = Document::where('originator_id', Auth::user()->id)->where('stage', 4)->count();
            $approved = Document::where('originator_id', Auth::user()->id)->where('stage', 5)->count();
            $training = Document::where('originator_id', Auth::user()->id)->where('stage', 6)->count();
            $effrctive = Document::where('originator_id', Auth::user()->id)->where('stage', 8)->count();
            $count = [$draft, $in_review, $reviewed, $for_approve, $approved, $training, $effrctive];
            $count = implode(',', $count);
            $data = Document::where('originator_id', Auth::user()->id)->get();
            foreach ($data as $temp) {
                $temp->created_at = Carbon::parse($temp->created_at)->format('Y-m-d');
            }
            return view('frontend.dashboard', compact('data', 'count'));
        }
        if (Auth::user()->role == 2) {

            $array1 = [];
            $array2 = [];
            $document = Document::where('stage', '>=', 2)->get();

            foreach($document as $data){
                $data->originator_name = User::where('id',$data->originator_id)->value('name');
                if($data->reviewers_group){
                    $datauser = explode(',',$data->reviewers_group);
                    for($i=0; $i<count($datauser); $i++){
                        $group = Grouppermission::where('id', $datauser[$i])->value('user_ids');
                        $ids = explode(',',$group);
                        for($j=0; $j<count($ids); $j++){
                            if($ids[$j]== Auth::user()->id){
                                array_push($array1,$data);
                            }
                        }
                    }
                }
                if ($data->reviewers) {
                    $datauser = explode(',', $data->reviewers);
                    for ($i = 0; $i < count($datauser); $i++) {
                        if ($datauser[$i] == Auth::user()->id) {
                            array_push($array2, $data);
                            // echo "<pre>";
                            // print_r($array2);
                            // die;

                        }
                    }
                }
            }
            $arrayTask = array_unique(array_merge($array1, $array2));
            // $count = [];
            // $draft = $arrayTask->where('stage',1)->count();
            // $in_review = $arrayTask->where('stage',2)->count();
            // $reviewed = $arrayTask->where('stage',3)->count();
            // $for_approve = $arrayTask->where('stage',4)->count();
            // $approved = $arrayTask->where('stage',5)->count();
            // $training = $arrayTask->where('stage',6)->count();
            // $effrctive = $arrayTask->where('stage',8)->count();
            // $count = [$draft, $in_review, $reviewed, $for_approve, $approved, $training, $effrctive];
            foreach ($arrayTask as $temp) {
                $temp->created_at = Carbon::parse($temp->created_at)->format('Y-m-d');
            }
            return view('frontend.dashboard', ['data' => $arrayTask]);
        }
        if (Auth::user()->role == 1) {
            $array1 = [];
            $array2 = [];
            $document = Document::where('stage', '>=', 4)->get();
            foreach($document as $data){
                $data->originator_name = User::where('id',$data->originator_id)->value('name');
                if($data->approver_group){
                    $datauser = explode(',',$data->approver_group);
                    for($i=0; $i<count($datauser); $i++){
                        $group = Grouppermission::where('id', $datauser[$i])->value('user_ids');
                        $ids = explode(',',$group);
                        for($j=0; $j<count($ids); $j++){
                            if($ids[$j]== Auth::user()->id){
                                array_push($array1,$data);
                            }
                        }
                    }
                }
                if ($data->approvers) {
                    $datauser = explode(',', $data->approvers);
                    for ($i = 0; $i < count($datauser); $i++) {
                        if ($datauser[$i] == Auth::user()->id) {
                            array_push($array2, $data);
                        }
                    }
                }
            }
            $arrayTask = array_unique(array_merge($array1, $array2));
            foreach ($arrayTask as $temp) {
                $temp->created_at = Carbon::parse($temp->created_at)->format('Y-m-d');
            }
            return view('frontend.dashboard', ['data' => $arrayTask]);
        } else {
            return view('frontend.dashboard');
        }
    }

    public function subscribe(Request $request){
        $data = new Subscribe();
        $data->user_id = Auth::user()->id;
        $data->type = $request->type;
        $data->week = $request->week;
        if($request->type == "Weekly"){
            $data->day = $request->day;
        }
        if($request->type == "Monthly"){
            $data->day = $request->days;
        }
        $data->time = $request->time;

        if($request->status){
            $data->status = $request->status;
        }

        $data->save();
        $recipent = new Recipent();
        $recipent->subscribe_id = $data->id;
        $recipent->user_id = Auth::user()->id;
        $recipent->save();
        if(!empty($request->recipents)){
            $imode = implode(',',$request->recipents);
            $datas = explode(',',$imode);
            foreach($datas as $temp){
                $recipent = new Recipent();
                $recipent->subscribe_id = $data->id;
                $recipent->user_id = $temp;
                $recipent->save();
            }
        }


        toastr()->success('Subscribed !!');
        return back();

    }
}
