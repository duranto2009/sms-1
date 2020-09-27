<?php

namespace App\Http\Controllers\Admin;

use App\Models\Student;
use App\Models\Attendance;
use App\Models\ClassTable;
use App\Models\SessionYear;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AttendanceController extends Controller
{
    public function index()
    {
        $class = ClassTable::all();
        return view('admin.partials.attendance.index',compact('class'));
    }
    public function getStudent(Request $r)
    {
        $session = SessionYear::where('status', 1)->first()->title;
        $students = Student::where('class_table_id',$r->class_id)
                    ->where('section',$r->section_id)
                    ->where('session',$session)
                    ->get();
        $student = '';
        foreach ($students as $st) {
            $student .='
                    <tr>
                        <td>'.$st->name.'</td>
                        <td>
                            <input type="hidden" name="student_id[]" value="'.$st->id.'">
                            <div class="custom-control custom-radio">
                                <label>
                                    <input type="radio" name="status_'.$st->id.'" class="present" value="1" required checked> Present
                                </label> &nbsp;&nbsp;&nbsp;
                                <label>
                                    <input type="radio" name="status_'.$st->id.'" class="absent" value="2" required> Absent
                                </label>
                        </td>
                    </tr>';

        }
        return json_encode(['status'=>200,'student'=>$student]);

    }
    public function create()
    {
        //
    }
    public function store(Request $request)
    {
    //     $a = 4;
    //    return $request->status_.$a;
       $request->validate([
            'date'           => 'required',
            'class_table_id' => 'required',
            'section'        => 'required',
            'student_id.*'   => 'required',
        ]);
        $checkAttandance = Attendance::where('class_table_id',$request->class_table_id)->where('section',$request->section)->where('date',$request->date)->first();
        $date = $this->makeDate($request->date);
        if ($checkAttandance == null) {
            $data = [
            'class_table_id'  => $request->class_table_id,
            'section'         => $request->section,
            'date'            => $date,
            'month'           => $date->format('m'),
            'year'            => $date->format('Y'),
            'session_year_id' => SessionYear::where('status', 1)->first()->id,
        ];
            foreach ($request->student_id as $student) {
                $data['student_id'] = $student;
                $data['status'] = $request->get('status_'.$student);
                Attendance::create($data);
            }
            try {
                return json_encode(['status'=>200,'message'=>'Attendance Take Successful!']);
            } catch (\Exception $e) {
                return json_encode(['status'=>500,'message'=>$e->getMessage()]);
            }
        }else{
            return json_encode(['status'=>500,'message'=>'Allready Taken!']);
        }
    }
    public function show(Attendance $attendance)
    {
        //
    }
    public function edit(Attendance $attendance)
    {
        //
    }

    public function update(Request $request, Attendance $attendance)
    {
        //
    }

    public function destroy(Attendance $attendance)
    {
        //
    }
}
