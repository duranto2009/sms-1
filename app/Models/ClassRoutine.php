<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassRoutine extends Model
{
    protected $guarded = ['id'];
    protected $with = ['class','session','subject','teacher','room'];

    public function class(){
        return $this->belongsTo(ClassTable::class,'class_table_id');
    }
    public function session(){
        return $this->belongsTo(SessionYear::class,'session_year_id');
    }
    public function subject(){
        return $this->belongsTo(Subject::class);
    }
    public function teacher(){
        return $this->belongsTo(Teacher::class);
    }
    public function room(){
        return $this->belongsTo(ClassRoom::class,'class_room_id');
    }
}
