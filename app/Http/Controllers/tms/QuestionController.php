<?php

namespace App\Http\Controllers\tms;

use App\Http\Controllers\Controller;
use App\Models\Question;
use GuzzleHttp\Psr7\Query;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Question::orderBy('id', 'asc')->paginate(10);
        return view('frontend.TMS.question', compact('data'));
    }
    

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
    */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        if($request->submit == "save") {

            // Validation for different question types
            if ($request->type != "Exact Match Questions") {
                $this->validate($request, [
                    'type' => 'required',
                    'question' => 'required|max:255',
                    'options' => 'required_if:type,Single Selection Questions,Multi Selection Questions|array|min:1', // Ensure options are an array and required for certain types
                    'answers' => 'required_if:type,Single Selection Questions,Multi Selection Questions|array|min:1', // Ensure answers are an array and required for certain types
                ]);
            } else {
                $this->validate($request, [
                    'type' => 'required',
                    'question' => 'required|max:255',
                    'answers' => 'required', // Validation for Exact Match Questions type
                ]);
            }
    
            // Prepare options and answers for saving
            $options = $request->has('options') ? serialize($request->options) : null; // Serialize options only if provided
            $answers = $request->has('answers') ? serialize($request->answers) : null; // Serialize answers only if provided
    
            // Create a new Question instance and save data
            $question = new Question();
            $question->trainer_id = Auth::user()->id;
            $question->type = $request->type;
            $question->question = $request->question;
            $question->options = $options;
            $question->answers = $answers;
            $question->save();
    
            // Notify the user of success and redirect
            toastr()->success('Question Created Successfully !!');
            return back();
        }

    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $question = Question::find($id);
        return view('frontend.TMS.edit-question',compact('question'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

            $question = Question::find($id);
            $question->trainer_id = Auth::user()->id;
            // $question->type = $request->type;
            $question->question = $request->question;
            $question->options = serialize($request->options);
            $question->answers = serialize($request->answers);
            $question->save();
            toastr()->success('Question Created Successfuly !!');
            return redirect()->route('question.index');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = Question::find($id);
        $data->delete();
        toastr()->success('Question Deleted Successffully !!');
        return back();
    }
}
