<?php

namespace App\Http\Controllers\Admin;

use App\Models\Event;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Student;
use Carbon\Carbon;

class EventController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            // $start = (!empty($_GET["start"])) ? ($_GET["start"]) : ('');
            // $end = (!empty($_GET["end"])) ? ($_GET["end"]) : ('');
            // $data = Event::whereDate('start', '>=', $start)->whereDate('end', '<=', $end)->get(['id','title','start', 'end']);
            $data = Event::all();
            return response()->json($data);
        }
        return view('admin.partials.event.index');
    }


    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required',
            'start' => 'required',
            'end'   => 'required',
        ]);
        if ($this->checkDate($request->start, $request->end)) {
            try {
                $event = Event::create($data);
                return json_encode(['status'=>200,'message'=>'Event Created Succesful!','data'=>$event]);
            } catch (\Exception $e) {
                return json_encode(['status'=>500,'message'=>$e->getMessage()]);
            }
        }else{
            return json_encode(['status'=>500,'message'=>'End date must be greater than start Date']);
        }
    }
    public function readData()
    {
        $calendar = Event::orderBy('start','asc')->paginate(7);
        $event = '';
        $i = 1;
        foreach ($calendar as $evt) {
            $event.='<tr>';
            $event.='<td>'.$evt->title.'</td>';
            $event.='<td>'.$evt->start->format('d M y h:m a').' <br/> '.$evt->end->format('d M y h:m a').'</td>';
            $event.='<td class="td-actions">';
            $editRoute = route("calendar.edit", $evt->id);
            $deleteRoute = route("calendar.destroy", $evt->id);
            $event.='<a href="javascript:void(0);" onclick="editModal('. "'{$editRoute}'".','."'Update EVENT'" .')"><i data-id='.$evt->id.' id="edit" class="la la-edit edit" title="Edit Event"></i></a>';
            $event.='<a href="javascript:void(0);" onclick="deleteModal('. "'{$deleteRoute}'".','."'Delete EVENT'" .')"><i data-id='.$evt->id.' id="delete" class="la la-close delete" title="Delete Event"></i></a>';
            $event.='</td>';
            $event.='</tr>';
        }
        $event .= '<tr><td colspan="3">'.$calendar->links().'</td></tr>';
        return json_encode(['status'=>200,'data'=>$event]);
    }
    public function edit(Event $calendar)
    {
        $section = '';
        $section = '<div class="form-group row"><label class="col-md-3 my-2 col-form-label text-md-right">Event Name</label><div class="col-md-6 my-2"><input type="text" class="form-control" name="title" value="'. $calendar->title .'" required autocomplete="off"></div></div>';
        $section .= '<div class="form-group row"><label class="col-md-3 my-2 col-form-label text-md-right">Start Date</label><div class="col-md-6 my-2"><input type="datetime-local" class="form-control" name="start" value="'.date('Y-m-d\TH:i',strtotime($calendar->start)).'" required autocomplete="off"></div></div>';
        $section .= '<div class="form-group row"><label class="col-md-3 my-2 col-form-label text-md-right">End Date</label><div class="col-md-6 my-2"><input type="datetime-local" class="form-control" name="end" value="'.date('Y-m-d\TH:i',strtotime($calendar->end)).'" required autocomplete="off"></div></div>';
        $route = route("calendar.update",$calendar->id);
        return json_encode(['data'=>$calendar,'status'=>200,'section'=>$section,'route'=>$route]);

    }
    public function update(Event $calendar,Request $request)
    {
        $data = $request->validate([
            'title' => 'required',
            'start' => 'required',
            'end'   => 'required',
        ]);
        if ($this->checkDate($request->start, $request->end)) {
            try {
                $calendar->update($data);
                return json_encode(['status'=>200,'message'=>'Event Updated Succesful!','data'=>$calendar]);
            } catch (\Exception $e) {
                return json_encode(['status'=>500,'message'=>$e->getMessage()]);
            }
        }else{
            return json_encode(['status'=>500,'message'=>'End date must be greater than start Date']);
        }
    }

    private function checkDate($start,$end){
        $start = Carbon::parse($start)->format('Y-m-d');
        $end = Carbon::parse($end)->format('Y-m-d');
        if($start > $end){
            return false;
        }else{
            return true;
        }
    }
    public function destroy(Event $calendar)
    {
        try {
            $calendar->delete();
            return json_encode(['status'=>200,'message'=>'Event Deleted Succesful!','data'=>$calendar]);
        } catch (\Exception $e) {
            return json_encode(['status'=>500,'message'=>$e->getMessage()]);
        }

    }
}
