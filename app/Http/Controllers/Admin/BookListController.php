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
        $books = BookList::all();
        return response()->json(['status'=>200,'books'=>$books]);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'=>'required',
            'author'=>'required',
            'copies'=>'required',
        ]);
        $data['aval_copies'] = $request->copies;
        try {
            BookList::create($data);
            return json_encode(['status'=>200,'data'=>$data]);
        } catch (\Exception $e) {
            return json_encode(['status'=>500,'error'=>$e->getMessage()]);
        }

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
