<?php

namespace App\Http\Controllers\Admin;

use App\Models\Exam;
use App\Models\SessionYear;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ExamController extends Controller
{
    public function index()
    {
        $session = SessionYear::where('status', 1)->first()->id;
        if (request()->ajax()) {
            return datatables()->of(Exam::where('session_year_id',$session)->get())
            ->addColumn('action', function ($data) {
                $editRoute = route("exam.edit", $data->id);
                $deleteRoute = route("exam.destroy", $data->id);
                $button = '
                <a href="javascript:void(0);" onclick="editModal('. "'{$editRoute}'".','."'Edit exam'" .')" class="td-actions"><i data-id='.$data->id.' id="delete" class="la la-pencil edit" title="Edit exam"></i></a>

                <a href="javascript:void(0);" onclick="deleteModal('. "'{$deleteRoute}'".','."'Edit exam'" .')" class="td-actions"><i data-id='.$data->id.' id="delete" class="la la-close delete" title="Delete exam"></i></a>';
                return $button;
            })
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);
        }
        return view('admin.partials.exam.index');

    }
    public function create()
    {
        //
    }
    public function store(Request $request)
    {
        $data = $request->validate([
            'exam_name' => 'required',
            'starting_date' => 'required|date',
            'ending_date'  => 'required|date',
        ]);
        $data['session_year_id'] = SessionYear::where('status', 1)->first()->id;
        try {
            Exam::create($data);
            return json_encode(['status'=>200,'data'=>$data,'message'=>'Exam Created Successful!']);
        } catch (\Exception $e) {
            return json_encode(['status'=>500,'message'=>$e->getMessage()]);
        }

    }
    public function show(Exam $exam)
    {
        //
    }
    public function edit(Exam $exam)
    {
        $section = '';
        $section .= '<div class="form-group row" id="row"><label class="col-md-3 my-2 col-form-label text-md-right">Exam Name</label><div class="col-md-6 my-2"><input type="text" class="form-control" name="exam_name" value="'. $exam->exam_name .'" required autocomplete="off"></div><div class="col-md-2 my-2"></div></div>';
        $section .= '<div class="form-group row" id="row"><label class="col-md-3 my-2 col-form-label text-md-right">Starting Date</label><div class="col-md-6 my-2"><input type="text" class="form-control" name="starting_date" value="'. $exam->starting_date .'" required autocomplete="off"></div><div class="col-md-2 my-2"></div></div>';
        $section .= '<div class="form-group row" id="row"><label class="col-md-3 my-2 col-form-label text-md-right">Ending Date</label><div class="col-md-6 my-2"><input type="text" class="form-control" name="ending_date" value="'. $exam->ending_date .'" required autocomplete="off"></div><div class="col-md-2 my-2"></div></div>';
        $route = route("exam.update", $exam->id);
        return json_encode(['data'=>$exam,'status'=>200,'section'=>$section,'route'=>$route]);

    }
    public function update(Request $request, Exam $exam)
    {
        $data = $request->validate([
            'exam_name' => 'required',
            'starting_date' => 'required|date',
            'ending_date'  => 'required|date',
        ]);
        $data['session_year_id'] = SessionYear::where('status', 1)->first()->id;
        try {
            $exam->update($data);
            return json_encode(['status'=>200,'data'=>$data,'message'=>'Exam Update Successful!']);
        } catch (\Exception $e) {
            return json_encode(['status'=>500,'message'=>$e->getMessage()]);
        }

    }
    public function destroy(Exam $exam)
    {
        try {
            $exam->delete();
            return json_encode(['status'=>200,'message'=>'Exam Deleted Successful!']);
        } catch (\Exception $e) {
            return json_encode(['status'=>500,'message'=>$e->getMessage()]);
        }

    }
}
