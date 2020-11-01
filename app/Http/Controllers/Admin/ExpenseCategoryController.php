<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\ExpenseCategory;
use App\Http\Controllers\Controller;

class ExpenseCategoryController extends Controller
{
    public function index()
    {
        $expenseCats = ExpenseCategory::latest()->get();
        return view('admin.partials.expense.index',compact('expenseCats'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'=>'required|string',
        ]);
        try {
            ExpenseCategory::create($data);
            return json_encode(['status'=>200,'data'=>$data]);
        } catch (\Exception $e) {
            return json_encode(['status'=>500,'error'=>$e->getMessage()]);
        }
    }
    public function readData()
    {
        $expCats = ExpenseCategory::all();
        $html  = '';
        $i = 1;
        foreach ($expCats as $dept) {
            $html.='<tr>';
            $html.='<td><span class="text-danger">'.$i++.'</span></td>';
            $html.='<td>'.$dept->name.'</td>';
            $html.='<td class="td-actions">';
            $editRoute = route("expense_category.edit", $dept->id);
            $deleteRoute = route("expense_category.destroy", $dept->id);
            $html.='<a href="javascript:void(0);" onclick="editModal('. "'{$editRoute}'".','."'Update Expense Category'" .')"><i data-id='.$dept->id.' id="edit" class="la la-edit edit" title="Edit Class"></i></a>';
            $html.='<a href="javascript:void(0);" onclick="deleteModal('. "'{$deleteRoute}'".','."'Delete Expense Category'" .')"><i data-id='.$dept->id.' id="delete" class="la la-close delete" title="Delete Class"></i></a>';
            $html.='</td>';
            $html.='</tr>';
        }
        return json_encode(['status'=>200,'data'=>$html]);

    }
    public function show(ExpenseCategory $expense_category)
    {
        //
    }

    public function edit(ExpenseCategory $expense_category)
    {
        $section = '<div class="form-group row" id="row"><label class="col-md-3 my-2 col-form-label text-md-right">Expense Category</label><div class="col-md-6 my-2"><input type="text" class="form-control" name="name" value="'. $expense_category->name .'" required autocomplete="off"></div><div class="col-md-2 my-2"></div></div>';
        $route = route("expense_category.update", $expense_category->id);
        return json_encode(['data'=>$expense_category,'status'=>200,'section'=>$section,'route'=>$route]);

    }

    public function update(Request $request, ExpenseCategory $expense_category)
    {
        $data = $request->validate([
            'name'=>'required|string',
        ]);
        try {
            $expense_category->update($data);
            return json_encode(['status'=>200,'message'=>'Expense Category Updated Successful!']);
        } catch (\Exception $e) {
            return json_encode(['status'=>500,'message'=>$e->getMessage()]);
        }

    }

    public function destroy(ExpenseCategory $expense_category)
    {
        try {
            $expense_category->delete();
            return json_encode(['status'=>200,'message'=>'Expense Category Deleted Successful!']);
        } catch (\Exception $e) {
            return json_encode(['status'=>500,'message'=>$e->getMessage()]);
        }

    }
}
