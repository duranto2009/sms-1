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
            $html.='<a href="#" data-id='.$cls->id.'><i class="la la-arrows edit" title="Update Section"></i></a>';
            $html.='<a href="#" data-id='.$cls->id.'><i class="la la-edit edit" title="Edit Class"></i></a>';
            $html.='<a href="#" data-id='.$cls->id.'><i class="la la-close delete" title="Delete Class"></i></a>';
            $html.='</td>';
            $html.='</tr>';
        }
        return json_encode(['status'=>200,'data'=>$html]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ClassTable  $classTable
     * @return \Illuminate\Http\Response
     */
    public function edit(ClassTable $classTable)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ClassTable  $classTable
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ClassTable $classTable)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ClassTable  $classTable
     * @return \Illuminate\Http\Response
     */
    public function destroy(ClassTable $classTable)
    {
        //
    }
}
