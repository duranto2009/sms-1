<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherPermission extends Model
{
    protected $guarded = ['id'];
    public function teacher(){
        return $this->belongsTo(Teacher::class);
    }
    public function class(){
        return $this->belongsTo(ClassTable::class,'class_id');
    }
}
