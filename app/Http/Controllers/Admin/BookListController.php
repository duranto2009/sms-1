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
        $section = '';
        $section .= '<div class="form-group row" id="row"><label class="col-md-3 my-2 col-form-label text-md-right">Book Name</label><div class="col-md-6 my-2"><input type="text" class="form-control" name="name" value="'. $booklist->name .'" required autocomplete="off"></div><div class="col-md-2 my-2"></div></div>';
        $section .= '<div class="form-group row" id="row"><label class="col-md-3 my-2 col-form-label text-md-right">Author</label><div class="col-md-6 my-2"><input type="text" class="form-control" name="author" value="'. $booklist->author .'" required autocomplete="off"></div><div class="col-md-2 my-2"></div></div>';
        $section .= '<div class="form-group row" id="row"><label class="col-md-3 my-2 col-form-label text-md-right">Copies</label><div class="col-md-6 my-2"><input type="text" class="form-control" name="copies" value="'. $booklist->copies .'" required autocomplete="off"></div><div class="col-md-2 my-2"></div></div>';
        $route = route("booklist.update", $booklist->id);
        return json_encode(['data'=>$booklist,'status'=>200,'section'=>$section,'route'=>$route]);

    }

    public function update(Request $request, BookList $booklist)
    {
        $data = $request->validate([
            'name'=>'required',
            'author'=>'required',
            'copies'=>'required',
        ]);
        $data['aval_copies'] = $request->copies;
        try {
            $booklist->update($data);
            return json_encode(['status'=>200,'data'=>$data]);
        } catch (\Exception $e) {
            return json_encode(['status'=>500,'error'=>$e->getMessage()]);
        }
    }

    public function destroy(BookList $booklist)
    {
        try {
            $booklist->delete();
            return json_encode(['status'=>200,'message'=>'Book Deleted Successful!']);
        } catch (\Exception $e) {
            return json_encode(['status'=>500,'message'=>$e->getMessage()]);
        }

    }
}
