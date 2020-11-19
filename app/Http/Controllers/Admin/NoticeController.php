<?php

namespace App\Http\Controllers\Admin;

use App\Models\Notice;
use App\Models\SessionYear;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NoticeController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $session = SessionYear::where('status', 1)->first();
            $data = Notice::where('session_year_id',$session->id);
            return response()->json($data);
        }
        return view('admin.partials.notice.index');

    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $data= $request->validate([
            'title'  => 'required|string',
            'notice' => 'required|string',
            'date'   => 'required|date',
            'show'   => 'required|integer',
            'file'   => 'sometimes',
        ]);
        $data['session_year_id'] = SessionYear::where('status', 1)->first()->id;
        $file = $request->file('file');
        if($request->hasFile('file')){
            $newName = 'Notice_'.time().'.'.$file->getClientOriginalExtension();
            if($file->isValid()){
                $file->storeAs('notice',$newName);
                $data['file'] = 'uploads/notice/'.$newName;
            }
        }
        try {
            $notice = Notice::create($data);
            return json_encode(['status'=>200,'message'=>'Notice Created Succesful!','data'=>$notice]);
        } catch (\Exception $e) {
            return json_encode(['status'=>500,'message'=>$e->getMessage()]);
        }
    }

    public function show(Notice $notice)
    {
        //
    }

    public function edit(Notice $notice)
    {
        //
    }

    public function update(Request $request, Notice $notice)
    {
        //
    }

    public function destroy(Notice $notice)
    {
        //
    }
}
