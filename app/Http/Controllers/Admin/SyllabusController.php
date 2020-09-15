<?php

namespace App\Http\Controllers\Admin;

use App\Models\Syllabus;
use App\Models\ClassTable;
use App\Models\SessionYear;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;

class SyllabusController extends Controller
{
    public function index()
    {
        $class = ClassTable::all();
        return view('admin.partials.syllabus.index',compact('class'));
    }

    public function filter(Request $r)
    {
        $session = SessionYear::where('status', 1)->first()->id;
        $syllabuss = Syllabus::where('class_table_id',$r->className)
                    ->where('section',$r->section)
                    ->where('section',$r->section)
                    ->where('session_year_id',$session)
                    ->get();
        $syllabus = '';
        $syllabus .='<div class="table-responsive">';
        $syllabus .='    <table id="dbTable" class="table mb-0 table-hover">';
        $syllabus .='        <thead>';
        $syllabus .='            <tr>';
        $syllabus .='                <th>SL</th>';
        $syllabus .='                <th>Title</th>';
        $syllabus .='                <th>Syllabus</th>';
        $syllabus .='                <th>Subject</th>';
        $syllabus .='                <th>Actions</th>';
        $syllabus .='            </tr>';
        $syllabus .='        </thead>';
        $syllabus .='        <tbody>';
        foreach ($syllabuss as $st) {
            $syllabus .='            <tr>';
            $syllabus .='                <td>'.$st->id.'</td>';
            $syllabus .='                <td>'.$st->name.'</td>';
            $syllabus .='                <td><a href="'.asset($st->file).'" download class="btn btn-info"><i class="la la-download"></i> Download</a></td>';
            $syllabus .='                <td>'.$st->subject->name.'</td>';
            $syllabus .='                <td class="td-actions">';
            $deleteRoute = route("syllabus.destroy", $st->id);
            $syllabus.='<a href="javascript:void(0);" onclick="deleteModal('. "'{$deleteRoute}'".','."'Delete Syllabus'" .')"><i data-id='.$st->id.' id="delete" class="la la-close delete" title="Delete Syllabus"></i></a>';

            $syllabus .='                </td>';
            $syllabus .='            </tr>';
        }
        $syllabus .='        </tbody>';
        $syllabus .='    </table>';
        $syllabus .='</div>';
        return json_encode(['status'=>200,'syllabus'=>$syllabus]);

    }
    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            "name"           => "required",
            "class_table_id" => "required",
            "section"        => "required",
            "subject_id"     => "required",
            "file"           => "required"
        ]);
        $session = SessionYear::where('status', 1)->first()->id;
        $data['session_year_id']= $session;
        $avater  = $request->file('file');
        if ($request->hasFile('file')) {
            $avaterNew  = "Syllabus_" . Str::random(10) . '.' . $avater->getClientOriginalExtension();
            if ($avater->isValid()) {
                $avater->storeAs('syllabus', $avaterNew);
                $data['file']  = '/uploads/syllabus/' . $avaterNew;
            }
        }
        try {
            Syllabus::create($data);
            return json_encode(['status'=>200,'message'=>'Create Successful!']);
        } catch (\Exception $e) {
            return json_encode(['status'=>200,'message'=>$e->getMessage()]);
        }

    }

    public function show(Syllabus $syllabus)
    {
        //
    }

    public function edit(Syllabus $syllabus)
    {
        //
    }

    public function update(Request $request, Syllabus $syllabus)
    {
        //
    }

    public function destroy(Syllabus $syllabus)
    {
        return $syllabus;
        try {
            $path = public_path() . $syllabus->file;
            if ($syllabus->file) {
                if (File::exists($path)) {
                    File::delete($path);
                }
            }

            $syllabus->delete();
            return json_encode(['status'=>200,'message'=>'Syllabus Deleted Successful!']);
        } catch (\Exception $e) {
            return json_encode(['status'=>500,'message'=>$e->getMessage()]);
        }

    }
}
