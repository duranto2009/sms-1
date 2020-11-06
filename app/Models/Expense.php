<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $guarded = ['id'];
    protected $dates = ['date'];
    public function category(){
        return $this->belongsTo(ExpenseCategory::class,'expense_categorie_id');
    }
}
