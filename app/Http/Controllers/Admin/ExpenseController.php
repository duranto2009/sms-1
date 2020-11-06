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

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
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
