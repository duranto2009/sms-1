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
        return view('admin.partials.attendance.index', compact('class'));
    }
    public function getStudent(Request $r)
    {
        $session = SessionYear::where('status', 1)->first()->title;
        $students = Student::where('class_table_id', $r->class_id)
                    ->where('section', $r->section_id)
                    ->where('session', $session)
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
                                    <input type="radio" name="status_'.$st->id.'" class="absent" value="0" required> Absent
                                </label>
                        </td>
                    </tr>';
        }
        return json_encode(['status'=>200,'student'=>$student]);
    }
    public function filter(Request $r)
    {
        $r->validate([
            "month"     =>"required",
            "year"      =>"required",
            "className" =>"required",
            "section"   =>"required"
        ]);
        $session = SessionYear::where('status', 1)->first();
        $attandances = Attendance::where('month', $r->month)
                    ->where('section', $r->section)
                    ->where('session_year_id', $session->id)
                    ->where('year', $r->year)
                    ->where('class_table_id', $r->className)
                    ->get();
        $collection = $attandances->groupBy('student_id');
        $attand =  $attandances->first();
        if ($attand != null) {
            $attandance = '';
            $attandance .= '
        <div class="row justify-content-center">
            <div class="col-md-4 mb-4">
                <div class="card bg-dark text-white">
                    <div class="card-body">
                        <div class="text-center">
                            <h4>Attendance Report Of '.$attand->date->format('F').'</h4>
                            <h5>Class : '.$attand->class->name.'</h5>
                            <h5>Section : '.$r->section.'</h5>
                            <h5>
                                Last Updated At : '.$attand->updated_at->format('d-M-Y').' <br>
                                Time : '.$attand->updated_at->format('h:m:i A').'
                            </h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <table class="table table-striped table-bordered table-centered mb-0">
        <thead>
            <tr style="font-size: 12px;">
                <th width="40px">
                    Student <i class="la la-arrow-down"></i>
                    Date <i class="la la-arrow-right"></i>
                </th>';
            for ($md=1; $md <= date('t', strtotime($attand->date->format('m/d/Y'))) ; $md++) {
                $attandance .= "<th>{$md}</th>";
            }
            $attandance .= '</tr>
        </thead>
            <tbody>';
            foreach ($collection as $attn) {
                $attandance .= '
                <tr>
                    <td style="font-weight: bold; width : 100px;text-transform: capitalize;">
                        '.$attn->first()->student->name.'
                    </td>';
                for ($md=1; $md <= date('t', strtotime($attand->date->format('m/d/Y'))) ; $md++) {
                    $attandance .= '<td class="m-1 text-left">';
                    foreach ($attn as $row) {
                        if ($md == date('d', strtotime($row->date))) {
                            if ($row->status == 1) {
                                $attandance .= '<i class="la la-circle text-success"></i>';
                            } else {
                                $attandance .= '<i class="la la-circle text-danger"></i>';
                            }
                        }
                    }
                    $attandance .='</td>';
                }
                $attandance .= '
                </tr>';
            }
            $attandance.='
            </tbody>
        </table>';
            return json_encode(['status'=>200,'attandance'=>$attandance]);
        } else {
            return json_encode(['status'=>500,'message'=>'No Data Found!!']);
        }
    }
    public function create()
    {
        //
    }
    public function store(Request $request)
    {
        $request->validate([
            'date'           => 'required',
            'class_table_id' => 'required',
            'section'        => 'required',
            'student_id.*'   => 'required',
        ]);
        $checkAttandance = Attendance::where('class_table_id', $request->class_table_id)->where('section', $request->section)->where('date', $request->date)->first();
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
        } else {
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
