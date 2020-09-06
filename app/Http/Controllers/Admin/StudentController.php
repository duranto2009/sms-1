<?php

namespace App\Http\Controllers\Admin;

use App\Models\Student;
use App\Models\Guardian;
use App\Models\ClassTable;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Http\Requests\StudentCreateRequest;

class StudentController extends Controller
{
    public function index()
    {
        $class = ClassTable::all();
        return view('admin.partials.student.index',compact('class'));
    }

    public function section(Request $r)
    {
        $class = ClassTable::findOrFail($r->className);
        $opt = '';
        $opt .= '<select id="section" name="section" class="form-control" required>';
        $opt .= '<option selected value="" disabled>Select Section</option>';
        foreach(json_decode($class->section) as $cls){
            $opt .= '<option value="'.$cls.'">'.$cls.'</option>';
        }
        $opt .= '</select>';
        return json_encode(['status'=>200,'opt'=>$opt]);
    }
    public function filter(Request $r)
    {
        $students = Student::where('class_table_id',$r->className)->where('section',$r->section)->get();
        $student = '';
        $student .='<div class="table-responsive">';
        $student .='    <table id="dbTable" class="table mb-0 table-hover">';
        $student .='        <thead>';
        $student .='            <tr>';
        $student .='                <th>student Id</th>';
        $student .='                <th>Photo</th>';
        $student .='                <th>Name</th>';
        $student .='                <th>Email</th>';
        $student .='                <th>Actions</th>';
        $student .='            </tr>';
        $student .='        </thead>';
        $student .='        <tbody>';
        foreach ($students as $st) {
            $student .='            <tr>';
            $student .='                <td>'.$st->id.'</td>';
            $student .='<td> <img src="'.asset($st->image).'" class="img-fluid" width="85px"></td>';
            $student .='                <td>'.$st->name.'</td>';
            $student .='                <td>'.$st->email.'</td>';
            $student .='                <td>ACTIONS</td>';
            $student .='            </tr>';
        }
        $student .='        </tbody>';
        $student .='    </table>';
        $student .='</div>';
        return json_encode(['status'=>200,'student'=>$student]);

    }
    public function create()
    {
        $class = ClassTable::all();
        $guardian = Guardian::all();
        return view('admin.partials.student.create',compact('class','guardian'));
    }

    public function store(StudentCreateRequest $request)
    {
        $data = $request->validated();
        $data['class_table_id']=$data['className'];
        $avater  = $request->file('image');
        if ($request->hasFile('image')) {
            $avaterNew  = "Student_" . Str::random(10) . '.' . $avater->getClientOriginalExtension();
            if ($avater->isValid()) {
                $avater->storeAs('images', $avaterNew);
                $data['image']  = '/uploads/images/' . $avaterNew;
            }
        }
        try {
            Student::create($data);
            return json_encode(['status'=>200,'message'=>'Admission Successful!']);
        } catch (\Exception $e) {
            return json_encode(['status'=>200,'message'=>$e->getMessage()]);
        }

    }


    public function image(Request $r)
    {
        $r->validate([
            'image'=>'required|image'
        ]);
        $cover  = $r->file('image');
        if ($r->hasFile('image')) {
            $coverNew  = "Cover_" . Str::random(5) . '.' . $cover->getClientOriginalExtension();
            // if ($cover->isValid()) {
            //     $cover->storeAs('uploads', $coverNew);
            //     $data['cover']  = '/images/uploads/' . $coverNew;
            // }
        }

        return $coverNew;
    }


    public function show(Student $student)
    {
        //
    }


    public function edit(Student $student)
    {
        //
    }


    public function update(Request $request, Student $student)
    {
        //
    }


    public function destroy(Student $student)
    {
        //
    }
}