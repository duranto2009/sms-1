<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookIssue extends Model
{
    protected $guarded = [];
    protected $dates = ['issue_date'];
    protected $with = ['class','book','student'];
    public function class()
    {
        return $this->belongsTo(ClassTable::class, 'class_table_id');
    }
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
    public function book()
    {
        return $this->belongsTo(BookList::class, 'book_list_id');
    }
}
