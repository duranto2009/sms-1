<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassTable extends Model
{
    protected $guarded = ['id'];
    public function students(){
        return $this->belongsTo(Student::class);
    }
}
