<?php

namespace App\Http\Controllers\Admin;

use App\Models\Notice;
use App\Models\SessionYear;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;

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
                $data['file'] = '/uploads/notice/'.$newName;
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
        $nt = '';
        $nt = '<div class="form-row">
                    <div class="form-group col-md-12">
                        <label>Notice Title</label>
                        <input type="text" class="form-control" name="title" required value="'.$notice->title.'">
                    </div>
                    <div class="form-group col-md-12">
                        <label>Date</label>
                        <input type="date" class="form-control" name="date" required value="'.$notice->date->format('Y-m-d').'">
                    </div>

                    <div class="form-group col-md-12">
                        <label>Notice</label>
                        <textarea name="notice" class="form-control" rows="8" cols="80" required>'.$notice->notice.'</textarea>
                    </div>

                    <div class="form-group col-md-12">
                        <label>Show On Website</label>
                        <select name="show" class="form-control">
                            <option '.($notice->show==1?'selected':'').' value="1">Show</option>
                            <option '.($notice->show==0?'selected':'').' value="0">Do Not Need To Show</option>
                        </select>
                    </div>

                    <div class="form-group col-md-12">
                        <label>Upload Notice File</label>
                        <input type="file" class="form-control" name="file" >
                    </div>
                </div>';
        $route = route("notice.update", $notice->id);
        return json_encode(['data'=>$notice,'status'=>200,'section'=>$nt,'route'=>$route]);
    }

    public function update(Request $request, Notice $notice)
    {
        $data= $request->validate([
            'title'  => 'required|string',
            'notice' => 'required|string',
            'date'   => 'required|date',
            'show'   => 'required|integer',
            'file'   => 'sometimes',
        ]);
        $file = $request->file('file');
        if ($request->hasFile('file')) {
            $newName = 'Notice_'.time().'.'.$file->getClientOriginalExtension();
            if ($file->isValid()) {
                $path1 = public_path() . $notice->file;
                if ($notice->file) {
                    if (File::exists($path1)) {
                        File::delete($path1);
                    }
                }
                $file->storeAs('notice', $newName);
                $data['file'] = '/uploads/notice/'.$newName;
            }
        }else{
            $data['file'] = $notice->file;
        }
        try {
            $notice->update($data);
            return json_encode(['status'=>200,'message'=>'Notice Update Succesful!','data'=>$notice]);
        } catch (\Exception $e) {
            return json_encode(['status'=>500,'message'=>$e->getMessage()]);
        }

    }

    public function destroy(Notice $notice)
    {
        //
    }
}
