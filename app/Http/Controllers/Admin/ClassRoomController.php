<?php

namespace App\Http\Controllers\Admin;

use App\Models\ClassRoom;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ClassRoomController extends Controller
{
    public function index()
    {
        $rooms = ClassRoom::all();
        return view('admin.partials.room.index',compact('rooms'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'      => 'required|string|unique:class_rooms,name'
        ]);
        try {
            ClassRoom::create($data);
            return json_encode(['status'=>200,'data'=>$data]);
        } catch (\Exception $e) {
            return json_encode(['status'=>200,'message'=>$e->getMessage()]);
        }

    }

    public function filter(ClassRoom $classroom)
    {
        $rooms = ClassRoom::all();
        $html  = '';
        $i = 1;
        foreach ($rooms as $room) {
            $html.='<tr>';
            $html.='<td><span class="text-danger">'.$i++.'</span></td>';
            $html.='<td>'.$room->name.'</td>';
            $html.='<td class="td-actions">';
            $editRoute = route("classroom.edit", $room->id);
            $deleteRoute = route("classroom.destroy", $room->id);
            $html.='<a href="javascript:void(0);" onclick="editModal('. "'{$editRoute}'".','."'Update Room'" .')"><i data-id='.$room->id.' id="edit" class="la la-edit edit" title="Edit Room"></i></a>';
            $html.='<a href="javascript:void(0);" onclick="deleteModal('. "'{$deleteRoute}'".','."'Delete Room'" .')"><i data-id='.$room->id.' id="delete" class="la la-close delete" title="Delete Room"></i></a>';
            $html.='</td>';
            $html.='</tr>';
        }
        return json_encode(['status'=>200,'data'=>$html]);

    }


    public function show(ClassRoom $classroom)
    {
        //
    }

    public function edit(ClassRoom $classroom)
    {

        $form = '<div class="form-row">
                <div class="form-group col-md-12">
                    <label for="name">Name</label>
                    <input value="'.$classroom->name.'" type="text" class="form-control" id="name" name="name" required>
                </div>
                </div>';

        $route = route("classroom.update", $classroom->id);
        return json_encode(['data'=>$classroom,'status'=>200,'section'=>$form,'route'=>$route]);

    }

    public function update(Request $request, ClassRoom $classroom)
    {
        $data = $request->validate([
            'name'=>'required|unique:class_rooms,name,'.$classroom->id
        ]);
        try {
            $classroom->update($data);
            return json_encode(['status'=>200,'message'=>'Room Updated Successful!']);
        } catch (\Exception $e) {
            return json_encode(['status'=>500,'message'=>$e->getMessage()]);
        }

    }

    public function destroy(ClassRoom $classroom)
    {
        try {
            $classroom->delete();
            return json_encode(['status'=>200,'message'=>'Room Delete Successful!']);
        } catch (\Exception $e) {
            return json_encode(['status'=>500,'message'=>$e->getMessage()]);
        }

    }
}
