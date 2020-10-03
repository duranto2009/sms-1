<?php

namespace App\Http\Controllers\Admin;

use App\Models\Mark;
use App\Models\SessionYear;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ClassTable;
use App\Models\Exam;

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
            return datatables()->of(Mark::where('session_year_id', $session->id)->where('exam_id', $r->exam_id)->where('class_table_id', $r->class_table_id)->where('subject_id', $r->subject_id)->where('section', $r->section)->get())
            ->addColumn('action', function ($data) {
                $editRoute = route("mark.edit", $data->id);
                $deleteRoute = route("mark.destroy", $data->id);
                $button = '
                <a href="javascript:void(0);" onclick="editModal('. "'{$editRoute}'".','."'Edit mark'" .')" class="td-actions"><i data-id='.$data->id.' id="delete" class="la la-pencil edit" title="Edit mark"></i></a>

                <a href="javascript:void(0);" onclick="deleteModal('. "'{$deleteRoute}'".','."'Edit mark'" .')" class="td-actions"><i data-id='.$data->id.' id="delete" class="la la-close delete" title="Delete mark"></i></a>';
                return $button;
            })
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);
        }
        $class = ClassTable::all();
        $exams = Exam::where('session_year_id',$session->id)->get();

        return view('admin.partials.mark.index',compact('class','exams'));

    }
    public function create()
    {
        //
    }
    public function store(Request $request)
    {
        //
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
