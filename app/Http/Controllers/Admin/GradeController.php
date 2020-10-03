<?php

namespace App\Http\Controllers\Admin;

use App\Models\Grade;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GradeController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            return datatables()->of(Grade::select('*'))
            ->addColumn('action',function($data){
                $editRoute = route("grade.edit", $data->id);
                $deleteRoute = route("grade.destroy", $data->id);
                $button = '
                <a href="javascript:void(0);" onclick="editModal('. "'{$editRoute}'".','."'Edit Grade'" .')" class="td-actions"><i data-id='.$data->id.' id="delete" class="la la-pencil edit" title="Edit Grade"></i></a>

                <a href="javascript:void(0);" onclick="deleteModal('. "'{$deleteRoute}'".','."'Edit Grade'" .')" class="td-actions"><i data-id='.$data->id.' id="delete" class="la la-close delete" title="Delete Grade"></i></a>';
                return $button;
            })
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);
        }

        // $grades = Grade::orderBy('point','desc')->get();
        return view('admin.partials.grade.index');
    }

    public function get()
    {
        $grade = Grade::orderBy('point','desc')->get();
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
        $section = '';
        $section .= '<div class="form-group row" id="row"><label class="col-md-3 my-2 col-form-label text-md-right">Grade Name</label><div class="col-md-6 my-2"><input type="text" class="form-control" name="grade" value="'. $grade->grade .'" required autocomplete="off"></div><div class="col-md-2 my-2"></div></div>';
        $section .= '<div class="form-group row" id="row"><label class="col-md-3 my-2 col-form-label text-md-right">Grade Point</label><div class="col-md-6 my-2"><input type="text" class="form-control" name="point" value="'. $grade->point .'" required autocomplete="off"></div><div class="col-md-2 my-2"></div></div>';
        $section .= '<div class="form-group row" id="row"><label class="col-md-3 my-2 col-form-label text-md-right">Mark From</label><div class="col-md-6 my-2"><input type="text" class="form-control" name="from" value="'. $grade->from .'" required autocomplete="off"></div><div class="col-md-2 my-2"></div></div>';
        $section .= '<div class="form-group row" id="row"><label class="col-md-3 my-2 col-form-label text-md-right">Mark Upto</label><div class="col-md-6 my-2"><input type="text" class="form-control" name="upto" value="'. $grade->upto .'" required autocomplete="off"></div><div class="col-md-2 my-2"></div></div>';
        $route = route("grade.update", $grade->id);
        return json_encode(['data'=>$grade,'status'=>200,'section'=>$section,'route'=>$route]);

    }

    public function update(Request $request, Grade $grade)
    {
        $data = $request->validate([
            'grade' => 'required',
            'point' => 'required|numeric',
            'from'  => 'required|numeric',
            'upto'  => 'required|numeric',
        ]);
        try {
            $grade->update($data);
            return json_encode(['status'=>200,'data'=>$data,'message'=>'Grade Update Successful!']);
        } catch (\Exception $e) {
            return json_encode(['status'=>500,'message'=>$e->getMessage()]);
        }

    }

    public function destroy(Grade $grade)
    {
        try {
            $grade->delete();
            return json_encode(['status'=>200,'message'=>'Grade Deleted Successful!']);
        } catch (\Exception $e) {
            return json_encode(['status'=>500,'message'=>$e->getMessage()]);
        }

    }
}
