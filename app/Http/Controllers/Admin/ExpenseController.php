<?php

namespace App\Http\Controllers\Admin;

use App\Models\Expense;
use Illuminate\Http\Request;
use App\Models\ExpenseCategory;
use App\Http\Controllers\Controller;

class ExpenseController extends Controller
{
    public function index()
    {
        $expenseCats = ExpenseCategory::latest()->get();
        return view('admin.partials.expense.index', compact('expenseCats'));

    }

    public function filter(Request $r)
    {
        return $r;
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $request->validate([
            'date'                 => 'required|date',
            'amount'               => 'required|numeric',
            'expense_categorie_id' => 'required|string',
        ]);
        $data['date']                 = $this->makeDate($request->date);
        $data['amount']               = $request->amount;
        $data['expense_categorie_id'] = $request->expense_categorie_id;
        try {
            Expense::create($data);
            return json_encode(['status'=>200,'data'=>$data]);
        } catch (\Exception $e) {
            return json_encode(['status'=>500,'error'=>$e->getMessage()]);
        }

    }

    public function show(Expense $expense)
    {
        //
    }

    public function edit(Expense $expense)
    {
        //
    }

    public function update(Request $request, Expense $expense)
    {
        //
    }

    public function destroy(Expense $expense)
    {
        //
    }
}
