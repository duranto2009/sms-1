<?php

namespace App\Http\Controllers\Admin;

use App\Models\Subject;
use App\Models\Teacher;
use App\Models\ClassRoom;
use App\Models\ClassTable;
use App\Models\SessionYear;
use App\Models\ClassRoutine;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ClassRoutineController extends Controller
{
    public function index()
    {
        $class = ClassTable::all();
        $subjects = Subject::orderBy('name')->get();
        $teachers = Teacher::all();
        $rooms = ClassRoom::all();
        return view('admin.partials.routine.index',compact('class','subjects','teachers','rooms'));
    }
    public function create()
    {
        //
    }

    public function filter(Request $r)
    {
        $session = SessionYear::where('status', 1)->first()->id;
        $routines = ClassRoutine::where('class_table_id',$r->className)
                    ->where('section',$r->section)
                    ->where('session_year_id',$session)
                    ->get();
        $routine = '';
        $days = ['satarday','sunday','monday','tuesday','wednesday','thursday','friday'];
        $routine = '
        <table class="table table-striped table-bordered table-centered mb-0">
            <tbody>';
            foreach ($days as $date) {
                $routine .= '
                <tr>
                    <td style="font-weight: bold; width : 100px;text-transform: capitalize;">'.$date.'</td>
                    <td class="m-1 text-left">';
                foreach ($routines->where('start_day', $date) as $day) {
                    $editRoute = route("routine.edit", $day->id);
                    $deleteRoute = route("routine.destroy", $day->id);
                    $routine .='
                        <div class="btn-group text-left">
                            <button type="button" class="btn btn-outline-info dropdown-toggle text-left" data-toggle="dropdown" aria-expanded="false" style="border-radius: 15px;">
                                <p style="margin-bottom: 0px;"><i class="la la-book"></i>
                                    '.$day->subject->name.' </p>
                                <p style="margin-bottom: 0px;"><i class="la la-clock-o"></i>
                                    '.$day->start_hour.':'.$day->start_minute.' - '.$day->end_hour.':'.$day->end_minute.'</p>
                                <p style="margin-bottom: 0px;"><i class="la la-user"></i>
                                    '.$day->teacher->name.' </p>
                                <p style="margin-bottom: 0px;"><i class="la la-home"></i>
                                    '.$day->room->name.' </p>
                            </button>
                            <div class="dropdown-menu td-actions" style="min-width: 75px;">
                                <a href="javascript:void(0);" onclick="editModal('. "'{$editRoute}'".','."'Delete Routine'" .')" class="dropdown-item">
                                    <i data-id='.$day->id.' id="delete" class="la la-edit edit" title="Delete Routine"></i>
                                </a>
                                <a href="javascript:void(0);" onclick="deleteModal('. "'{$deleteRoute}'".','."'Delete Routine'" .')" class="dropdown-item">
                                    <i data-id='.$day->id.' id="delete" class="la la-close delete" title="Delete Routine"></i>
                                </a>
                            </div>
                        </div>';
                }
                $routine .='
                    </td>
                </tr>';
            }
                $routine.='
            </tbody>
        </table>';
        return json_encode(['status'=>200,'routine'=>$routine]);

    }
    public function store(Request $request)
    {
        $data = $request->validate([
            'class_room_id'  => 'required',
            'class_table_id' => 'required',
            'end_hour'       => 'required',
            'end_minute'     => 'required',
            'section'        => 'required',
            'start_day'      => 'required',
            'start_hour'     => 'required',
            'start_minute'   => 'required',
            'subject_id'     => 'required',
            'teacher_id'     => 'required',
        ]);
        $data['session_year_id'] = SessionYear::where('status', 1)->first()->id;
        try {
            ClassRoutine::create($data);
            return json_encode(['status'=>200,'message'=>'Create Successful!']);
        } catch (\Exception $e) {
            return json_encode(['status'=>200,'message'=>$e->getMessage()]);
        }

    }

    public function show(ClassRoutine $routine)
    {

    }

    public function edit(ClassRoutine $routine)
    {
        $days = ['satarday','sunday','monday','tuesday','wednesday','thursday','friday'];
        $hours = ['1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24'];
        $minutes = ['0','5','10','15','20','25','30','35','40','45','50','55','60'];
        $class = ClassTable::all();
        $subjects = Subject::orderBy('name')->get();
        $teachers = Teacher::all();
        $rooms = ClassRoom::all();
        $form = '';
        $form .='
        <div class="form-row">
            <div class="form-group col-md-12">
                <label for="class_table_id">Class</label>
                <select id="class_table_id" name="class_table_id" class="form-control" required
                onchange="getSection(this.value)">';
                    foreach ($class as $cls) {
                        $form .='<option '.($routine->class_table_id == $cls->id ?'selected':'').' value="'.$cls->id.'">'.$cls->name.'</option>';
                    }
                $form .='</select>
            </div>

            <div class="form-group col-md-12">
                <label for="section_id">Section</label>
                <div class="opt">
                <select  name="section" class="form-control">
                    <option>'.$routine->section.'</option>
                </select>
                </div>
            </div>
            <div class="form-group col-md-12">
                <label for="subject_id">Subject</label>
                <select id="subject_id" name="subject_id" class="form-control" required>';
                    foreach ($subjects as $subject) {
                        $form .='<option '.($routine->subject_id == $subject->id ?'selected':'').' value="'.$subject->id.'">'.$subject->name.'</option>';
                    }
                $form .='</select>
            </div>
            <div class="form-group col-md-12">
                <label for="teacher_id">Teacher</label>
                <select id="teacher_id" name="teacher_id" class="form-control" required>';
                    foreach ($teachers as $teacher) {
                        $form .='<option '.($routine->teacher_id == $teacher->id ?'selected':'').' value="'.$teacher->id.'">'.$teacher->name.'</option>';
                    }
                $form .='</select>
            </div>
            <div class="form-group col-md-12">
                <label for="class_room_id">Class Room</label>
                <select id="class_room_id" name="class_room_id" class="form-control" required>';
                    foreach ($rooms as $room) {
                        $form .='<option '.($routine->class_room_id == $room->id ?'selected':'').' value="'.$room->id.'">'.$room->name.'</option>';
                    }
                $form .='</select>
            </div>
            <div class="form-group col-md-12">
                <label for="start_day">Day</label>
                <select name="start_day"id="start_day" class="form-control" required>';
                    foreach ($days as $day) {
                        $form .='<option '.($routine->start_day == $day ?'selected':'').' value="'.$day.'">'.$day.'</option>';
                    }
                $form .='</select>
            </div>
            <div class="form-group col-md-12">
                <label for="start_hour">Starting Hour</label>
                <select name="start_hour"name="start_hour" class="form-control" required>
                    <option value="">Starting Hour</option>';
                    foreach ($hours as $hour) {
                        $form .='<option  '.($routine->start_hour == $hour ?'selected':'').'  value="'.$hour.'">'.$hour.'</option>';
                    }
                $form .='</select>
            </div>
            <div class="form-group col-lg-12">
                <label for="start_minute">Starting Minute</label>
                <select required name="start_minute" id="start_minute" class="form-control">
                    <option value="">Starting Minute</option>';
                    foreach ($minutes as $minute) {
                        $form .='<option '.($routine->start_minute == $minute ?'selected':'').'  value="'.$minute.'">'.$minute.'</option>';
                    }
                $form .='</select>
            </div>
            <div class="form-group col-md-12">
                <label for="end_hour">Ending Hour</label>
                <select name="end_hour" id="end_hour" class="form-control" required>
                    <option value="">Starting Hour</option>';
                    foreach ($hours as $hour) {
                        $form .='<option '.($routine->end_hour == $hour ?'selected':'').'  value="'.$hour.'">'.$hour.'</option>';
                    }
                $form .='</select>
            </div>
            <div class="form-group col-lg-12">
                <label for="end_minute">Ending Minute</label>
                <select required name="end_minute" class="form-control">
                    <option value="">Starting Minute</option>';
                    foreach ($minutes as $minute) {
                        $form .='<option  '.($routine->end_minute == $minute ?'selected':'').'  value="'.$minute.'">'.$minute.'</option>';
                    }
                $form .='</select>
            </div>
        </div>
        ';
        $route = route("routine.update", $routine->id);
        return json_encode(['data'=>$routine,'status'=>200,'section'=>$form,'route'=>$route]);

    }

    public function update(Request $request, ClassRoutine $routine)
    {

        $data = $request->validate([
            'class_room_id'  => 'required',
            'class_table_id' => 'required',
            'end_hour'       => 'required',
            'end_minute'     => 'required',
            'section'        => 'required',
            'start_day'      => 'required',
            'start_hour'     => 'required',
            'start_minute'   => 'required',
            'subject_id'     => 'required',
            'teacher_id'     => 'required',
        ]);
        $data['session_year_id'] = SessionYear::where('status', 1)->first()->id;
        try {
            $routine->update($data);
            return json_encode(['status'=>200,'message'=>'Routine Updated Successful!']);
        } catch (\Exception $e) {
            return json_encode(['status'=>500,'message'=>$e->getMessage()]);
        }

    }

    public function destroy(ClassRoutine $routine)
    {
        try{
            $routine->delete();
            return json_encode(['status'=>200,'message'=>'Syllabus Deleted Successful!']);
        } catch (\Exception $e) {
            return json_encode(['status'=>500,'message'=>$e->getMessage()]);
        }
    }
}
