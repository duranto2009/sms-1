<?php

namespace App\Http\Controllers\Admin;

use App\Models\Grade;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Student;

class GradeController extends Controller
{
    public function index()
    {
        // $grades = Grade::orderBy('point','desc')->get();
        return view('admin.partials.grade.index');
    }

    public function get()
    {
        $student =  Student::all();
        $data = [];
        foreach($student as $i=>$st){

            $deleteRoute = route("student.destroy", $st->id);

            $data[$i] = [
                'id'=>$st->id,
                'name'=>$st->name,
                'email'=>$st->email,
                'phone'=>$st->phone,
                'gender'=>$st->gender,
                'session'=>'
                <a href="javascript:void(0);" onclick="deleteModal('. "'{$deleteRoute}'".','."'Delete Section'" .')" class="td-actions"><i data-id='.$st->id.' id="delete" class="la la-pencil edit" title="Delete Class"></i></a>

                <a href="javascript:void(0);" onclick="deleteModal('. "'{$deleteRoute}'".','."'Delete Section'" .')" class="td-actions"><i data-id='.$st->id.' id="delete" class="la la-close delete" title="Delete Class"></i></a>',
            ];
        }
        return $data;
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(Grade $grade)
    {
        //
    }

    public function edit(Grade $grade)
    {
        //
    }

    public function update(Request $request, Grade $grade)
    {
        //
    }

    public function destroy(Grade $grade)
    {
        //
    }
}
