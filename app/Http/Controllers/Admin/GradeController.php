<?php

namespace App\Http\Controllers\Admin;

use App\Models\Grade;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GradeController extends Controller
{
    public function index()
    {
        // $grades = Grade::orderBy('point','desc')->get();
        return view('admin.partials.grade.index');
    }

    public function get()
    {
        $grade =  Grade::all();
        $data = [];
        foreach($grade as $i=>$st){

            $editRoute = route("grade.edit", $st->id);
            $deleteRoute = route("grade.destroy", $st->id);

            $data[$i] = [
                'id'=>$st->id,
                'name'=>$st->grade,
                'email'=>$st->point,
                'phone'=>$st->from,
                'gender'=>$st->upto,
                'session'=>'
                <a href="javascript:void(0);" onclick="editModal('. "'{$editRoute}'".','."'Edit Grade'" .')" class="td-actions"><i data-id='.$st->id.' id="delete" class="la la-pencil edit" title="Edit Grade"></i></a>

                <a href="javascript:void(0);" onclick="deleteModal('. "'{$deleteRoute}'".','."'Edit Grade'" .')" class="td-actions"><i data-id='.$st->id.' id="delete" class="la la-close delete" title="Delete Grade"></i></a>',
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
        $data = $request->validate([
            'grade' => 'required',
            'point' => 'required|numeric',
            'from'  => 'required|numeric',
            'upto'  => 'required|numeric',
        ]);
        try {
            Grade::create($data);
            return json_encode(['status'=>200,'data'=>$data,'message'=>'Grade Created Successful!']);
        } catch (\Exception $e) {
            return json_encode(['status'=>500,'message'=>$e->getMessage()]);
        }

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
