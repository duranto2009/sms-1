<?php

namespace App\Http\Controllers\Admin;

use App\Models\ClassTable;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ClassTableController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $class = ClassTable::all();
        return  view('admin.partials.class.index', compact('class'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'=>'required|string|unique:class_tables,name',
            'section'=>'required|array'
        ]);
        $data['section'] = json_encode($data['section']);
        try {
            ClassTable::create($data);
            return json_encode(['status'=>200,'data'=>$data]);
        } catch (\Exception $e) {
            return json_encode(['status'=>500,'error'=>$e->getMessage()]);
        }
    }

    public function show(Request $r){
        return 'SHOW';
    }
    public function readData(Request $r)
    {
        $class = ClassTable::all();
        $html  = '';
        $i = 1;
        foreach($class as $cls){
            $html.='<tr>';
            $html.='<td><span class="text-danger">'.$i++.'</span></td>';
            $html.='<td>'.$cls->name.'</td>';
            $html.='<td>';
            $html.='<span style="width:100px;">';
            foreach (json_decode($cls->section) as $section) {
                $html.='<span class="badge-text badge-text-small info">'.$section.'</span>';
            }
            $html.='</span>';
            $html.='</td>';
            $html.='<td class="td-actions">';
            $editRoute = route("class.edit",$cls->id);
            $deleteRoute = route("class.destroy",$cls->id);
            $html.='<a href="javascript:void(0);" onclick="editModal('. "'{$editRoute}'".','."'Update Section'" .')"><i data-id='.$cls->id.' id="edit" class="la la-edit edit" title="Edit Class"></i></a>';
            $html.='<a href="javascript:void(0);" onclick="deleteModal('. "'{$deleteRoute}'".','."'Delete Section'" .')"><i data-id='.$cls->id.' id="delete" class="la la-close delete" title="Delete Class"></i></a>';
            $html.='</td>';
            $html.='</tr>';
        }
        return json_encode(['status'=>200,'data'=>$html]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ClassTable  $class
     * @return \Illuminate\Http\Response
     */
    public function edit(ClassTable $class)
    {
        $section = '';
        foreach (json_decode($class->section) as $i => $sec) {
            $section .= '<div class="form-group row" id="row'. ($i+1) .'"><label class="col-md-3 my-2 col-form-label text-md-right">Section '. $sec .'</label><div class="col-md-6 my-2"><input type="text" class="form-control" name="section[]" value="'. $sec .'" required autocomplete="off"></div><div class="col-md-2 my-2"></div></div>';
        }
        return json_encode(['data'=>$class,'status'=>200,'section'=>$section]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ClassTable  $class
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ClassTable $class)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ClassTable  $class
     * @return \Illuminate\Http\Response
     */
    public function destroy(ClassTable $class)
    {
        try {
            $class->delete();
            return json_encode(['status'=>200,'message'=>'Class Deleted Successful!']);
        } catch (\Exception $e) {
            return json_encode(['status'=>500,'message'=>$e->getMessage()]);
        }
    }
}
