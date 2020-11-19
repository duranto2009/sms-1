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
            $data = Notice::where('session_year_id', $session->id);
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
        if ($request->hasFile('file')) {
            $newName = 'Notice_'.time().'.'.$file->getClientOriginalExtension();
            if ($file->isValid()) {
                $file->storeAs('notice', $newName);
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
    public function readData()
    {
        $session = SessionYear::where('status', 1)->first();
        $notices = Notice::where('session_year_id',$session->id)->orderBy('date', 'asc')->paginate(10);
        $notice = '';
        $i = 1;
        foreach ($notices as $evt) {
            $notice.='<tr>';
            $notice.='<td>'.$evt->title.'</td>';
            $notice.='<td>'.$evt->date->format('d M Y').'</td>';
            $notice.='<td><a href="'.asset($evt->file).'" download="">Download</a></td>';
            $notice.='<td class="td-actions">';
            $editRoute = route("notice.edit", $evt->id);
            $deleteRoute = route("notice.destroy", $evt->id);
            $notice.='<a href="javascript:void(0);" onclick="editModal('. "'{$editRoute}'".','."'Update Notice'" .')"><i data-id='.$evt->id.' id="edit" class="la la-edit edit" title="Edit Notice"></i></a>';
            $notice.='<a href="javascript:void(0);" onclick="deleteModal('. "'{$deleteRoute}'".','."'Delete Notice'" .')"><i data-id='.$evt->id.' id="delete" class="la la-close delete" title="Delete Notice"></i></a>';
            $notice.='</td>';
            $notice.='</tr>';
        }
        if ($notices->count() >= 10) {
            $notice .= '<tr><td colspan="4">'.$notices->links().'</td></tr>';
        }
        return json_encode(['status'=>200,'data'=>$notice]);
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
