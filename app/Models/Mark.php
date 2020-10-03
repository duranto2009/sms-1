<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mark extends Model
{
    protected $guarded = ['id'];
    protected $with = ['student','class','exam','session','subjec'];
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }
    public function class()
    {
        return $this->belongsTo(ClassTable::class, 'class_table_id');
    }
    public function session()
    {
        return $this->belongsTo(SessionYear::class,'session_year_id');
    }
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}
