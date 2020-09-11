<?php

namespace App\Http\Controllers\Admin;

use App\Models\Department;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $depts = Department::all();
        return view('admin.partials.department.index',compact('depts'));
    }
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'=>'required|string|unique:departments,name',
        ]);
        try {
            Department::create($data);
            return json_encode(['status'=>200,'data'=>$data]);
        } catch (\Exception $e) {
            return json_encode(['status'=>500,'error'=>$e->getMessage()]);
        }

    }
    public function readData()
    {
        $depts = Department::all();
        $html  = '';
        $i = 1;
        foreach ($depts as $dept) {
            $html.='<tr>';
            $html.='<td><span class="text-danger">'.$i++.'</span></td>';
            $html.='<td>'.$dept->name.'</td>';
            $html.='<td class="td-actions">';
            $editRoute = route("department.edit", $dept->id);
            $deleteRoute = route("department.destroy", $dept->id);
            $html.='<a href="javascript:void(0);" onclick="editModal('. "'{$editRoute}'".','."'Update Section'" .')"><i data-id='.$dept->id.' id="edit" class="la la-edit edit" title="Edit Class"></i></a>';
            if ($dept->status == 0) {
                $html.='<a href="javascript:void(0);" onclick="deleteModal('. "'{$deleteRoute}'".','."'Delete Section'" .')"><i data-id='.$dept->id.' id="delete" class="la la-close delete" title="Delete Class"></i></a>';
            }
            $html.='</td>';
            $html.='</tr>';
        }
        return json_encode(['status'=>200,'data'=>$html]);

    }
    public function edit(Department $department)
    {
        $section = '<div class="form-group row" id="row"><label class="col-md-3 my-2 col-form-label text-md-right">Department</label><div class="col-md-6 my-2"><input type="text" class="form-control" name="name" value="'. $department->name .'" required autocomplete="off"></div><div class="col-md-2 my-2"></div></div>';
        $route = route("department.update", $department->id);
        return json_encode(['data'=>$department,'status'=>200,'section'=>$section,'route'=>$route]);

    }
    public function update(Request $request, Department $department)
    {
        $data = $request->validate([
            'name'=>'required|string|unique:departments,name,'.$department->id,
        ]);
        try {
            $department->update($data);
            return json_encode(['status'=>200,'message'=>'Department Updated Successful!']);
        } catch (\Exception $e) {
            return json_encode(['status'=>500,'message'=>$e->getMessage()]);
        }

    }
    public function destroy(Department $department)
    {
        try {
            $department->delete();
            return json_encode(['status'=>200,'message'=>'Department Deleted Successful!']);
        } catch (\Exception $e) {
            return json_encode(['status'=>500,'message'=>$e->getMessage()]);
        }

    }
}
