<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $guarded = ['id'];
    public function guardian(){
        return $this->belongsTo(Guardian::class);
    }
    public function class(){
        return $this->belongsTo(ClassTable::class,'class_table_id');
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
    protected $dates = [
        'dob'
    ];
    protected $with = ['class','guardian'];
}
