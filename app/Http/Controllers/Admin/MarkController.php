<?php

namespace App\Http\Controllers\Admin;

use App\Models\Exam;
use App\Models\Mark;
use App\Models\Student;
use App\Models\ClassTable;
use App\Models\SessionYear;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
    public function getStudent(Request $r)
    {
        $session = SessionYear::where('status', 1)->first()->title;
        $students = Student::where('class_table_id', $r->class_table_id)
                    ->where('section', $r->section)
                    ->where('session', $session)
                    ->get();
        $student = '';
        foreach ($students as $st) {
            $student .='
                    <tr>
                        <td>'.$st->name.'</td>
                        <td>
                            <input type="hidden" name="student_id[]" value="'.$st->id.'">
                            <input onchange="get_grade(this.value, this.id)" type="number" class="form-mark form-inline" name="mark_'.$st->id.'" id="mark-'.$st->id.'" value="33" required>
                            <input type="text" class="form-mark form-inline grade-for-mark-'.$st->id.'"  name="grade_'.$st->id.'" required value="D" readonly>
                        </td>
                    </tr>';
        }
        return response()->json(['status'=>200,'student'=>$student,'message'=>'success']);

    }
    public function store(Request $request)
    {
        $data = $request->validate([
            'class_table_id' => 'required',
            'exam_id'        => 'required',
            'section'        => 'required',
            'student_id.*'   => 'required',
            'subject_id'     => 'required',
        ]);
        $data['comment'] = 'COMMENT';
        $session = SessionYear::where('status', 1)->first()->id;
        $data['session_year_id'] = $session;
        $checkExam = Mark::where('session_year_id', $session)
                ->where('exam_id', $request->exam_id)
                ->where('class_table_id', $request->class_table_id)
                ->where('subject_id', $request->subject_id)
                ->where('section', $request->section)
                ->get();
        if ($checkExam->count() == 0) {
            foreach ($request->student_id as $student) {
                $data['student_id'] = $student;
                $data['mark'] =$request->get('mark_'.$student);
                $data['grade'] =$request->get('grade_'.$student);
                Mark::create($data);
            }
            try {
                return response()->json(['status'=>200,'message'=>'Mark Store Successful!']);
            } catch (\Exception $e) {
                return response()->json(['status'=>500,'message'=>$e->getMessage()]);
            }

        }else{
            return response()->json(['status'=>500,'message'=>'Allready Taken!']);

        }
        return $data;

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
