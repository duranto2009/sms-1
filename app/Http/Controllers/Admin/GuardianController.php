<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\Models\Guardian;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GuardianController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.partials.parent.index');
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
            "name"     => "required|string",
            "email"    => "required|string|unique:guardians,email",
            "password" => "required|string",
            "phone"    => "required|string",
            "gender"   => "required|string",
            "blood"    => "required|string",
            "address"  => "required|string"
        ]);
        $password = bcrypt($data['password']);
        $user = [
            'name'=>$data['name'],
            'email'=>$data['email'],
            'password'=>$password,
            'role'=>'parent',
            'email_verified_at'=>now(),
            'remember_token'=>Str::random(64)
        ];
        try {
            $user_id = User::create($user);
            $data['user_id'] = $user_id->id;
            Guardian::create($data);
            return json_encode(['status'=>200,'data'=>$data]);
        } catch (\Exception $e) {
            return json_encode(['status'=>500,'error'=>$e->getMessage()]);
        }

    }
    public function readData(Request $r)
    {
        $guardians = Guardian::all();
        $html  = '';
        $i = 1;
        foreach($guardians as $guardian){
            $html.='<tr>';
            $html.='<td><span class="text-danger">'.$i++.'</span></td>';
            $html.='<td>'.$guardian->name.'</td>';
            $html.='<td>'.$guardian->email.'</td>';
            $html.='<td class="td-actions">';
            $editRoute = route("guardian.edit",$guardian->id);
            $deleteRoute = route("guardian.destroy",$guardian->id);
            $html.='<a href="javascript:void(0);" onclick="editModal('. "'{$editRoute}'".','."'Update Parent'" .')"><i data-id='.$guardian->id.' id="edit" class="la la-edit edit" title="Edit Class"></i></a>';
            $html.='<a href="javascript:void(0);" onclick="deleteModal('. "'{$deleteRoute}'".','."'Delete Parent'" .')"><i data-id='.$guardian->id.' id="delete" class="la la-close delete" title="Delete Class"></i></a>';
            $html.='</td>';
            $html.='</tr>';
        }
        return json_encode(['status'=>200,'data'=>$html]);
    }
    public function show(Guardian $guardian)
    {
        //
    }
    public function edit(Guardian $guardian)
    {
        $route = route('guardian.update',$guardian->id);
        $section = '';

        $section .= '<div class="form-group row" id="row">';
        $section .= '<label class="col-md-3 my-2 col-form-label text-md-right">Name</label><div class="col-md-6 my-2">';
        $section .='<input type="text" class="form-control" name="name" value="'. $guardian->name .'" required autocomplete="off">';
        $section .='</div></div>';

        $section .= '<div class="form-group row" id="row">';
        $section .= '<label class="col-md-3 my-2 col-form-label text-md-right">Email</label><div class="col-md-6 my-2">';
        $section .='<input type="text" class="form-control" name="email" value="'. $guardian->email .'" required autocomplete="off">';
        $section .='</div></div>';

        $section .= '<div class="form-group row" id="row">';
        $section .= '<label class="col-md-3 my-2 col-form-label text-md-right">Phone</label><div class="col-md-6 my-2">';
        $section .='<input type="text" class="form-control" name="phone" value="'. $guardian->phone .'" required autocomplete="off">';
        $section .='</div></div>';

        $section .= '<div class="form-group row" id="row">';
        $section .= '<label class="col-md-3 my-2 col-form-label text-md-right">gender</label><div class="col-md-6 my-2">';
        $section .='<select name="gender" id="gender" class="form-control" >
                            <option value="Male"'.($guardian->gender == "Male"?"selected":"") .'>Male</option>
                            <option value="Female"'. ($guardian->gender == "Female"?"selected":"") .'>Female</option>
                    </select>';
        $section .='</div></div>';

        $section .= '<div class="form-group row" id="row">';
        $section .= '<label class="col-md-3 my-2 col-form-label text-md-right">blood</label><div class="col-md-6 my-2">';
        $section .='<select name="blood" id="blood" class="form-control" >
                            <option value="A+"'.($guardian->blood == "A+"?"selected":"") .'>A+</option>
                            <option value="Female"'. ($guardian->blood == "A-"?"selected":"") .'>A-</option>
                            <option '.($guardian->blood == "A+"?"selected":"") .' value="A+">A+</option>
                            <option '.($guardian->blood == "A-"?"selected":"") .' value="A-">A-</option>
                            <option '.($guardian->blood == "B+"?"selected":"") .' value="B+">B+</option>
                            <option '.($guardian->blood == "B-"?"selected":"") .' value="B-">B-</option>
                            <option '.($guardian->blood == "AB+"?"selected":"") .' value="AB+">AB+</option>
                            <option '.($guardian->blood == "AB-"?"selected":"") .' value="AB-">AB-</option>
                            <option '.($guardian->blood == "O+"?"selected":"") .' value="O+">O+</option>
                            <option '.($guardian->blood == "O-"?"selected":"") .' value="O-">O-</option>
                    </select>';

        $section .='</div></div>';

        $section .= '<div class="form-group row" id="row">';
        $section .= '<label class="col-md-3 my-2 col-form-label text-md-right">address</label><div class="col-md-6 my-2">';
        $section .='<input type="text" class="form-control" name="address" value="'. $guardian->address .'" required autocomplete="off">';
        $section .='</div></div>';
        return json_encode(['data'=>$guardian,'status'=>200,'section'=>$section,'route'=>$route]);

    }
    public function update(Request $request, Guardian $guardian)
    {
        $data = $request->validate([
            "name"     => "required|string",
            "email"    => "required|string|unique:guardians,email,".$guardian->id,
            "phone"    => "required|string",
            "gender"   => "required|string",
            "blood"    => "required|string",
            "address"  => "required|string"
        ]);
        $user = User::find($guardian->user_id);
        $userData = [
            'name'=> $data['name'],
            'email'=> $data['email']
        ];
        try {
            $user->update($userData);
            $guardian->update($data);
            return json_encode(['status'=>200,'message'=>'Parent Updated Successful!']);
        } catch (\Exception $e) {
            return json_encode(['status'=>500,'message'=>$e->getMessage()]);
        }


    }
    public function destroy(Guardian $guardian)
    {
        try {
            $guardian->delete();
            return json_encode(['status'=>200,'message'=>'Class Deleted Successful!']);
        } catch (\Exception $e) {
            return json_encode(['status'=>500,'message'=>$e->getMessage()]);
        }

    }
}
