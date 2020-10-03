<?php

namespace App\Http\Controllers\Admin;

use App\Models\Mark;
use App\Models\SessionYear;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ClassTable;
use App\Models\Exam;

class MarkController extends Controller
{
    public function index(Request $r)
    {
        $session = SessionYear::where('status', 1)->first();
        if (request()->ajax()) {
            $r->validate([
                "exam_id"        => 'required',
                "class_table_id" => 'required',
                "section"        => 'required',
                "subject_id"     => 'required'
            ]);
            $marks = Mark::where('session_year_id', $session->id)->where('exam_id', $r->exam_id)->where('class_table_id', $r->class_table_id)->where('subject_id', $r->subject_id)->where('section', $r->section)->get();
            return response()->json(['status'=>200,'data'=>$marks,'message'=>'Success']);
        }
        $class = ClassTable::all();
        $exams = Exam::where('session_year_id',$session->id)->get();

        return view('admin.partials.mark.index',compact('class','exams'));

    }
    public function mark_update(Request $r)
    {
        // return $r;
        $data = $r->validate([
            'class_table_id' => 'required',
            'comment'        => 'required',
            'exam_id'        => 'required',
            'mark'           => 'required',
            'section'        => 'required',
            'student_id'     => 'required',
            'subject_id'     => 'required',
            'grade'          => 'required',
        ]);
        $session = SessionYear::where('status', 1)->first();
        $mark = Mark::where('session_year_id', $session->id)->where('exam_id', $r->exam_id)->where('class_table_id', $r->class_table_id)->where('subject_id', $r->subject_id)->where('section', $r->section)->where('student_id', $r->student_id)->first();
        try {
            $mark->update($data);
            return response()->json(['status'=>200,'data'=>$data,'message'=>'Updated']);
        } catch (\Exception $e) {
            return response()->json(['message'=>$e->getMessage(),'status'=>500]);
        }


    }
    public function get_grade(Request $r)
    {
        $mark = $r->mark;
        if($mark >= 80 && $mark <= 100){
            return 'A+';
        }elseif($mark >= 70 && $mark <= 79){
            return 'A';
        }elseif($mark >= 60 && $mark <= 69){
            return 'A-';
        }elseif($mark >= 50 && $mark <= 59){
            return 'B';
        }elseif($mark >= 40 && $mark <= 49){
            return 'C';
        }elseif($mark >= 33 && $mark <= 39){
            return 'D';
        }else{
            return 'F';
        }

    }
    public function store(Request $request)
    {
        //
    }
    public function show(Mark $mark)
    {
        //
    }
    public function edit(Mark $mark)
    {
        //
    }
    public function update(Request $request, Mark $mark)
    {
        //
    }
    public function destroy(Mark $mark)
    {
        //
    }
}
