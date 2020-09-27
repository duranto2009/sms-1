<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $guarded = ['id'];
    protected $with = ['class','session','subject'];

    public function class(){
        return $this->belongsTo(ClassTable::class,'class_table_id');
    }
    public function session(){
        return $this->belongsTo(SessionYear::class,'session_year_id');
    }
    public function student(){
        return $this->belongsTo(Student::class);
    }
}
