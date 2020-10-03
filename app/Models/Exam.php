<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    protected $guarded = ['id'];
    protected $with = ['session'];
    public function session()
    {
        return $this->belongsTo(SessionYear::class,'session_year_id');
    }
}
