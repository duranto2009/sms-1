<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Invoice;
use App\Models\Student;
use App\Models\ClassTable;
use App\Models\SessionYear;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class InvoiceController extends Controller
{
    public function index()
    {
        // $invoices = Invoice::all();
        $classes = ClassTable::all();
        return view('admin.partials.invoice.index',compact('classes'));
    }

    public function getStudent(Request $request)
    {
        $student = Student::where('class_table_id',$request->id)->get();
        return response()->json(['status'=>200,'students'=>$student]);
    }
    public function getSection(Request $request)
    {
        $class = ClassTable::findOrFail($request->id);
        return response()->json(['status'=>200,'sections'=>$class]);

    }

    public function getInv()
    {
        $invoices = Invoice::all();
        return json_encode(['status'=>200,'invoices'=>$invoices]);
    }

    public function filter(Request $r)
    {
        $r->validate([
            'date'=>'required',
            'id'=>'required',
        ], [
            'id.required'=>'Please Select a Category',
        ]);
        $date      = explode(' - ', $r->date);
        $startDate = Carbon::parse($date[0])->format('Y-m-d');
        $endDate   = Carbon::parse($date[1])->format('Y-m-d');
        $invoices = Invoice::where('created_at', '>=', $startDate)
                    ->where('created_at', '<=', $endDate)
                    ->where('class_table_id',$r->id)
                    ->orderBy('created_at')
                    ->get();
        return json_encode(['status'=>200,'invoices'=>$invoices]);

    }

    public function create()
    {

    }

    public function store(Request $request)
    {
        $data = $request->validate([
            "class_id"   =>'required|integer',
            "student_id" =>'required|integer',
            "title"      =>'required|string',
            "amount"     =>'required|numeric',
            "paidAmount" =>'required|numeric',
            "status"     =>'required|integer'
        ]);
        $data['class_table_id'] = $request->class_id;
        $data['session_year_id'] = SessionYear::where('status', 1)->first()->id;
        try {
            Invoice::create($data);
            return json_encode(['status'=>200,'data'=>$data]);
        } catch (\Exception $e) {
            return json_encode(['status'=>500,'error'=>$e->getMessage()]);
        }
    }
    public function massStore(Request $request)
    {
        $data = $request->validate([
            "class_id"   =>'required|integer',
            "section_id" =>'required|string',
            "title"      =>'required|string',
            "amount"     =>'required|numeric',
            "paidAmount" =>'required|numeric',
            "status"     =>'required|integer'
        ]);
        $data['class_table_id'] = $request->class_id;
        $session = SessionYear::where('status', 1)->first();
        $data['session_year_id'] = $session->id;
        $students = Student::where('class_table_id',$request->class_id)
                    ->where('section',$request->section_id)
                    ->where('session',$session->title)
                    ->get();
        try {
            foreach ($students as $student) {
                $data['student_id'] = $student->id;
                Invoice::create($data);
            }
            return json_encode(['status'=>200,'data'=>$data]);
        } catch (\Exception $e) {
            return json_encode(['status'=>500,'error'=>$e->getMessage()]);
        }
    }

    public function show(Invoice $invoice)
    {

    }

    public function edit(Invoice $invoice)
    {
        $section = '';
        $section .= '<div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="title">Invoice Title</label>
                                <input type="text" class="form-control" id="title" name="title" value="'.$invoice->title.'">
                            </div>

                            <div class="form-group col-md-12">
                                <label for="amount">Total Amount (USD)</label>
                                <input type="number" class="form-control" id="amount" name="amount" value="'.number_format($invoice->amount,2,'.','0').'">
                            </div>

                            <div class="form-group col-md-12">
                                <label for="paid_amount">Paid Amount (USD)</label>
                                <input type="number" class="form-control" id="paidAmount" name="paidAmount" value="'.number_format($invoice->paidAmount,2,'.','').'">
                            </div>

                            <div class="form-group col-md-12">
                                <label for="status">Status</label>
                                <select name="status" id="status" class="form-control" >
                                    <option value="1"'.($invoice->status==0?"selected":"").'>Paid</option>
                                    <option value="0"'.($invoice->status==0?"selected":"").'>Unpaid</option>
                                </select>
                            </div>
                        </div>';
        $route = route("invoice.update", $invoice->id);
        return json_encode(['data'=>$invoice,'status'=>200,'section'=>$section,'route'=>$route]);
    }

    public function update(Request $request, Invoice $invoice)
    {
        $data = $request->validate([
            "title"      =>'required|string',
            "amount"     =>'required|numeric',
            "paidAmount" =>'required|numeric',
            "status"     =>'required|integer'
        ]);
        if($request->status == 1 & ($request->amount != $request->paidAmount)){
            return json_encode(['status'=>500,'error'=>'Please Check Status!']);
        }
        try {
            $invoice->update($data);
            return json_encode(['status'=>200,'data'=>$data]);
        } catch (\Exception $e) {
            return json_encode(['status'=>500,'error'=>$e->getMessage()]);
        }

    }

    public function destroy(Invoice $invoice)
    {
        try {
            $invoice->delete();
            return json_encode(['status'=>200,'message'=>'Invoice Deleted Successful!']);
        } catch (\Exception $e) {
            return json_encode(['status'=>500,'message'=>$e->getMessage()]);
        }
    }
}
