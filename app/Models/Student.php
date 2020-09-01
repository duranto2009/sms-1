<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $guarded = ['id'];
    public function guardian(){
        return $this->hasOne(Guardian::class);
    }
    public function class(){
        return $this->hasOne(ClassTable::class);
    }
    protected $dates = [
        'dob'
    ];
}
