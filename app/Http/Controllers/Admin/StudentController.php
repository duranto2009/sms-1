<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\StudentCreateRequest;
use App\Models\ClassTable;
use App\Models\Guardian;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

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
        return $r;
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
        $student .='        <tbody class="table-content">';
        $student .='            <tr>';
        $student .='                <td></td>';
        $student .='                <td></td>';
        $student .='                <td></td>';
        $student .='                <td></td>';
        $student .='                <td></td>';
        $student .='            </tr>';
        $student .='        </tbody>';
        $student .='    </table>';
        $student .='</div>';
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
        return $data;
    }
    // public function store(Request $request)
    // {
    //     return $request;
    // }


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
