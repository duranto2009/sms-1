<?php

namespace App\Http\Controllers\Admin;

use App\Models\SessionYear;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SessionYearController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.partials.session.index');
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
            'title'=>'required|digits:4|integer|unique:session_years,title',
        ]);
        try {
            SessionYear::create($data);
            return json_encode(['status'=>200,'data'=>$data]);
        } catch (\Exception $e) {
            return json_encode(['status'=>500,'error'=>$e->getMessage()]);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SessionYear  $sessionYear
     * @return \Illuminate\Http\Response
     */
    public function show(SessionYear $sessionYear)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SessionYear  $sessionYear
     * @return \Illuminate\Http\Response
     */
    public function edit(SessionYear $sessionYear)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SessionYear  $sessionYear
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SessionYear $sessionYear)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SessionYear  $sessionYear
     * @return \Illuminate\Http\Response
     */
    public function destroy(SessionYear $sessionYear)
    {
        //
    }
}
