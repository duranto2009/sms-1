<?php

namespace App\Http\Controllers\Admin;

use App\Models\Expense;
use Illuminate\Http\Request;
use App\Models\ExpenseCategory;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class ExpenseController extends Controller
{
    public function index()
    {
        $expenseCats = ExpenseCategory::latest()->get();
        $expenses = Expense::with('category')->latest()->get();
        return view('admin.partials.expense.index', compact('expenseCats','expenses'));

    }

    public function filter(Request $r)
    {
        $r->validate([
            'date'=>'required',
            'id'=>'required',
        ],[
            'id.required'=>'Please Select a Category',
        ]);
        $date      = explode(' - ',$r->date);
        $startDate = Carbon::parse($date[0])->format('Y-m-d');
        $endDate   = Carbon::parse($date[1])->format('Y-m-d');
        $expenses = Expense::with('category')
                    ->where('date','>=',$startDate)
                    ->where('date','<=',$endDate)
                    ->where('expense_categorie_id',$r->id)
                    ->orderBy('date')
                    ->get();
        return json_encode(['status'=>200,'expenses'=>$expenses]);
    }

    public function getData()
    {
        $expenses = Expense::with('category')->get();
        return json_encode(['status'=>200,'expenses'=>$expenses]);
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
        $expenseCats = ExpenseCategory::all();
        $section = '';
        $section .= '<div class="form-group row">
                        <label for="date" class="col-md-3 col-form-label text-md-right">Date</label>
                        <div class="col-md-8">
                            <input id="date" type="date" class="form-control" name="date" value="'.$expense->date->format('Y-m-d').'">
                        </div>
                    </div>
                    <div class="form-group row ca">
                        <label for="amt" class="col-md-3 col-form-label text-md-right">Amount</label>
                        <div class="col-md-8">
                            <input id="amt" type="number" class="form-control" name="amount" value="'.number_format($expense->amount,2).'">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="cat" class="col-md-3 col-form-label text-md-right">Amount</label>
                        <div class="col-md-8">
                            <select name="expense_categorie_id" class=" form-control">
                                <option value="0" disabled selected>SELECT A CATEGORY</option>';
                                foreach ($expenseCats as $cat){
                                    $section .= '<option '.($cat->id==$expense->expense_categorie_id?'selected':'').' value="'.$cat->id.'">'.$cat->name.'</option>';
                                }
                            $section .='</select>
                        </div>
                    </div>';
        $route = route("expense.update", $expense->id);
        return json_encode(['data'=>$expense,'status'=>200,'section'=>$section,'route'=>$route]);

    }

    public function update(Request $request, Expense $expense)
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
            $expense->update($data);
            return json_encode(['status'=>200,'data'=>$data]);
        } catch (\Exception $e) {
            return json_encode(['status'=>500,'error'=>$e->getMessage()]);
        }
    }

    public function destroy(Expense $expense)
    {
        return $expense;
    }
}
