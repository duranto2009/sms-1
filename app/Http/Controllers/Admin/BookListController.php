<?php

namespace App\Http\Controllers\Admin;

use App\Models\BookList;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BookListController extends Controller
{
    public function index()
    {
        $booklists = BookList::all();
        return view('admin.partials.book.index',compact('booklists'));
    }

    public function readData()
    {
        //
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(BookList $booklist)
    {
        //
    }

    public function edit(BookList $booklist)
    {
        //
    }

    public function update(Request $request, BookList $booklist)
    {
        //
    }

    public function destroy(BookList $booklist)
    {
        //
    }
}
