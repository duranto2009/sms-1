<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $guarded = ['id'];
    public function class(){
        return $this->belongsTo(ClassTable::class,'class_table_id');
    }
    protected $with =['class'];
}
