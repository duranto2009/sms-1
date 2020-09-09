<?php

namespace App\Http\Controllers\Admin;

use App\Models\SessionYear;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SessionYearController extends Controller
{
    public function index()
    {
        $sessions = SessionYear::orderBy('title', 'asc')->get();
        return view('admin.partials.session.index',compact('sessions'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'=>'required|digits:4|integer|unique:session_years,title',
        ]);
        try {
            SessionYear::create($data);
            return json_encode(['status'=>200,'data'=>$data]);
        } catch (\Exception $e) {
            return json_encode(['status'=>500,'error'=>$e->getMessage()]);
        }

    }

    public function readData(SessionYear $session)
    {
        $session = SessionYear::orderBy('title','asc')->get();
        $active = SessionYear::where('status',1)->first()->title;
        $html  = '';
        $i = 1;
        foreach ($session as $cls) {
            $html.='<tr>';
            $html.='<td><span class="text-danger">'.$i++.'</span></td>';
            $html.='<td>'.$cls->title.'</td>';
            $html.='<td>';
            if($cls->status == 1){
                $html.='<span class="badge-text badge-text-small success"><i class="la la-circle"></i> Active</span>';
            }else{
                $html.='<i class="la la-circle"></i> Deactive';

            }
            $html.='</td>';
            $html.='<td class="td-actions">';
            $editRoute = route("session.edit", $cls->id);
            $deleteRoute = route("session.destroy", $cls->id);
            $html.='<a href="javascript:void(0);" onclick="editModal('. "'{$editRoute}'".','."'Update Section'" .')"><i data-id='.$cls->id.' id="edit" class="la la-edit edit" title="Edit Class"></i></a>';
            if ($cls->status == 0) {
                $html.='<a href="javascript:void(0);" onclick="deleteModal('. "'{$deleteRoute}'".','."'Delete Section'" .')"><i data-id='.$cls->id.' id="delete" class="la la-close delete" title="Delete Class"></i></a>';
            }
            $html.='</td>';
            $html.='</tr>';
        }
        return json_encode(['status'=>200,'data'=>$html,'active'=>$active]);

    }

    public function active(Request $r)
    {
        $sessions = SessionYear::all();
        foreach($sessions as $item){
            $item->update(['status'=>0]);
        }
        $session = SessionYear::findOrFail($r->id);
        $session->update(['status'=>1]);
        return json_encode(['status'=>200,'message'=>'Session Active Success']);

    }

    public function show(SessionYear $session)
    {
        //
    }
    public function edit(SessionYear $session)
    {
        $section = '<div class="form-group row" id="row"><label class="col-md-3 my-2 col-form-label text-md-right">Session</label><div class="col-md-6 my-2"><input type="text" class="form-control" name="title" value="'. $session->title .'" required autocomplete="off"></div><div class="col-md-2 my-2"></div></div>';
        $route = route("session.update", $session->id);
        return json_encode(['data'=>$session,'status'=>200,'section'=>$section,'route'=>$route]);

    }
    public function update(Request $request, SessionYear $session)
    {
        $data = $request->validate([
            'title'=>'required|digits:4|integer|unique:session_years,title,except'.$session->id
        ]);
        try {
            $session->update($data);
            return json_encode(['status'=>200,'message'=>'Session Updated Successful!']);
        } catch (\Exception $e) {
            return json_encode(['status'=>500,'message'=>$e->getMessage()]);
        }

    }

    public function destroy(SessionYear $session)
    {
        try {
            $session->delete();
            return json_encode(['status'=>200,'message'=>'Session Deleted Successful!']);
        } catch (\Exception $e) {
            return json_encode(['status'=>500,'message'=>$e->getMessage()]);
        }

    }
}
