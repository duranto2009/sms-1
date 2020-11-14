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

    public function show(Invoice $invoice)
    {

    }

    public function edit(Invoice $invoice)
    {

    }

    public function update(Request $request, Invoice $invoice)
    {

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
