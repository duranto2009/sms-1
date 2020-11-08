<?php

namespace App\Http\Controllers\Admin;

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
        $invoices = Invoice::all();
        $classes = ClassTable::all();
        return view('admin.partials.invoice.index',compact('invoices','classes'));
    }

    public function getStudent(Request $request)
    {
        $student = Student::where('class_table_id',$request->id)->get();
        return response()->json(['status'=>200,'students'=>$student]);
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

    }
}
