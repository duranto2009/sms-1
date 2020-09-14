<?php

namespace App\Http\Controllers\Admin;

use App\Models\Subject;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ClassTable;

class SubjectController extends Controller
{
    public function index()
    {
        $class = ClassTable::all();
        return view('admin.partials.subject.index',compact('class'));
    }
    public function create()
    {
        //
    }
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'      => 'required|string',
            'class_table_id' => 'required|string'
        ]);
        try {
            Subject::create($data);
            return json_encode(['status'=>200,'data'=>$data]);
        } catch (\Exception $e) {
            return json_encode(['status'=>200,'message'=>$e->getMessage()]);
        }

    }
    public function readData(Request $r)
    {
        $subjects = Subject::where('class_table_id',$r->classId)->get();
        $html  = '';
        $i = 1;
        $html .='<table class="table table-striped" style="width:100%"><thead><tr><th>SL</th><th>Name</th><th>Actions</th></tr></thead><tbody class="table-content">';
        foreach ($subjects as $subject) {
            $html.='<tr>';
            $html.='<td><span class="text-danger">'.$i++.'</span></td>';
            $html.='<td>'.$subject->name.'</td>';
            $html.='<td class="td-actions">';
            $editRoute = route("subject.edit", $subject->id);
            $deleteRoute = route("subject.destroy", $subject->id);
            $html.='<a href="javascript:void(0);" onclick="editModal('. "'{$editRoute}'".','."'Update Subject'" .')"><i data-id='.$subject->id.' id="edit" class="la la-edit edit" title="Edit Subject"></i></a>';
            $html.='<a href="javascript:void(0);" onclick="deleteModal('. "'{$deleteRoute}'".','."'Delete Subject'" .')"><i data-id='.$subject->id.' id="delete" class="la la-close delete" title="Delete Subject"></i></a>';
            $html.='</td>';
            $html.='</tr>';
        }
        $html .= '</tbody></table>';
        return json_encode(['status'=>200,'data'=>$html]);


    }
    public function show(Subject $subject)
    {
        //
    }
    public function edit(Subject $subject)
    {
        $form = '<div class="form-row">
                <div class="form-group col-md-12">
                    <label for="name">Name</label>
                    <input value="'.$subject->name.'" type="text" class="form-control" id="name" name="name" required>
                </div>
                </div>';

        $route = route("subject.update", $subject->id);
        return json_encode(['data'=>$subject,'status'=>200,'section'=>$form,'route'=>$route]);

    }
    public function update(Request $request, Subject $subject)
    {
        $data = $request->validate([
            'name'=>'required'
        ]);
        try {
            $subject->update($data);
            return json_encode(['status'=>200,'message'=>'Subject Updated Successful!']);
        } catch (\Exception $e) {
            return json_encode(['status'=>500,'message'=>$e->getMessage()]);
        }

    }
    public function destroy(Subject $subject)
    {
        try {
            $subject->delete();
            return json_encode(['status'=>200,'message'=>'Subject Kicked Successful!']);
        } catch (\Exception $e) {
            return json_encode(['status'=>500,'message'=>$e->getMessage()]);
        }

    }
}
