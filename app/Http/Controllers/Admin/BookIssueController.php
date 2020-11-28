<?php

namespace App\Http\Controllers\Admin;

use App\Models\BookIssue;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\BookList;
use App\Models\ClassTable;
use App\Models\Student;

class BookIssueController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $issues = BookIssue::all();
            return response()->json(['status'=>200,'issues'=>$issues]);
        }
        $issues = BookIssue::all();
        $class = ClassTable::all();
        $books = BookList::all();
        return view('admin.partials.book.issue', compact('issues', 'class', 'books'));
    }
    public function getStudent(Request $request)
    {
        $students = Student::where('class_table_id', $request->id)->get();
        return response()->json(['status'=>200, 'students'=>$students]);
    }
    public function return(BookIssue $issue)
    {
        try {
            $issue->delete();
            $booklist = BookList::find($issue->book_list_id);
            $booklist->update(['aval_copies'=>$booklist->aval_copies + 1]);
            return response()->json(['status'=>200,'message'=>'Book returned thanks '.$issue->student->name]);
        } catch (\Throwable $th) {
            return response()->json(['status'=>500,'message'=>'Error happend!']);
        }
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'issue_date'     => 'required',
            'class_table_id' => 'required',
            'student_id'     => 'required',
            'book_list_id'        => 'required',
        ]);
        try {
            $booklist = BookList::find($request->book_list_id);
            $booklist->update(['aval_copies'=>$booklist->aval_copies - 1]);
            BookIssue::create($data);
            return response()->json(['status'=>200,'message'=>'Book Issue success']);
        } catch (\Throwable $th) {
            return response()->json(['status'=>500,'message'=>'Book Not Issued']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BookIssue  $bookIssue
     * @return \Illuminate\Http\Response
     */
    public function show(BookIssue $bookIssue)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BookIssue  $bookIssue
     * @return \Illuminate\Http\Response
     */
    public function edit(BookIssue $bookIssue)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BookIssue  $bookIssue
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BookIssue $bookIssue)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BookIssue  $bookIssue
     * @return \Illuminate\Http\Response
     */
    public function destroy(BookIssue $bookIssue)
    {
        //
    }
}
