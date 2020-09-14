<?php

namespace App\Models;
use App\User;

use Illuminate\Database\Eloquent\Model;

class Guardian extends Model
{
    protected $guarded = ['id'];

    public function students(){
        return $this->hasMany(Student::class);
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
}
