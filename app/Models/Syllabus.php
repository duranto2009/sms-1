<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Syllabus extends Model
{
    protected $guarded = ['id'];

    public function class(){
        return $this->belongsTo(ClassTable::class,'class_table_id');
    }
    public function subject(){
        return $this->belongsTo(Subject::class);
    }
    protected $with =['class','subject'];
}
