<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\Models\Accountant;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use App\Http\Requests\AccountantCreateRequest;
use App\Http\Requests\AccountantUpdateRequest;

class AccountantController extends Controller
{
    public function index()
    {
        $accountants = Accountant::all();
        return view('admin.partials.accountant.index',compact('accountants'));
    }
    public function create()
    {
        //
    }

    public function store(AccountantCreateRequest $request)
    {
        $data = $request->validated();

        $avater  = $request->file('image');
        if ($request->hasFile('image')) {
            $avaterNew  = "Teacher_" . Str::random(10) . '.' . $avater->getClientOriginalExtension();
            if ($avater->isValid()) {
                $avater->storeAs('images/accountant/', $avaterNew);
                $data['image']  = '/uploads/images/accountant/' . $avaterNew;
            }
        }
        $user = [
            'name'=>$data['name'],
            'email'=>$data['email'],
            'password'=>bcrypt($request->password),
            'role'=>'accountant',
            'email_verified_at'=>now(),
            'remember_token'=>Str::random(64)
        ];
        try {
            $user = User::create($user);
            $data['user_id'] = $user->id;
            Accountant::create($data);
            return json_encode(['status'=>200,'message'=>'Admission Successful!']);
        } catch (\Exception $e) {
            return json_encode(['status'=>200,'message'=>$e->getMessage()]);
        }


    }
    public function readData()
    {
        $accountants = Accountant::all();
        $html  = '';
        $i = 1;
        foreach ($accountants as $accnt) {
            $html.='<tr>';
            $html.='<td><span class="text-danger">'.$i++.'</span></td>';
            $html.='<td><img src="'.asset($accnt->image).'" alt="..." class="img-fluid" style="width:85px"></td>';
            $html.='<td>'.$accnt->name.'</td>';
            $html.='<td>'.$accnt->phone.'</td>';
            $html.='<td class="td-actions">';
            $editRoute = route("accountant.edit", $accnt->id);
            $deleteRoute = route("accountant.destroy", $accnt->id);
            $html.='<a href="javascript:void(0);" onclick="editModal('. "'{$editRoute}'".','."'Update Accountant'" .')"><i data-id='.$accnt->id.' id="edit" class="la la-edit edit" title="Edit Accountant"></i></a>';
            $html.='<a href="javascript:void(0);" onclick="deleteModal('. "'{$deleteRoute}'".','."'Delete Accountant'" .')"><i data-id='.$accnt->id.' id="delete" class="la la-close delete" title="Delete Accountant"></i></a>';
            $html.='</td>';
            $html.='</tr>';
        }
        return json_encode(['status'=>200,'data'=>$html]);

    }
    public function show(Accountant $accountant)
    {
        //
    }

    public function edit(Accountant $accountant)
    {
        $form = '';
        $form .= '
            <div class="form-row">
                <div class="form-group col-md-12">
                    <label for="name">Name</label>
                    <input value="'.$accountant->name.'" type="text" class="form-control" id="name" name="name" required>
                </div>

                <div class="form-group col-md-12">
                    <label for="email">Email</label>
                    <input value="'.$accountant->email.'" type="email" class="form-control" id="email" name="email" required>
                </div>

                <div class="form-group col-md-12">
                    <label for="phone">Phone Number</label>
                    <input value="'.$accountant->phone.'" type="number" class="form-control" id="phone" name="phone" required>
                </div>

                <div class="form-group col-md-12">
                    <label for="gender">Gender</label>
                    <select name="gender" id="gender" class="form-control">
                        <option value>Select A Gender</option>
                        <option '.($accountant->gender == "Male" ? "selected":"").' value="Male">Male</option>
                        <option '.($accountant->gender == "Female" ? "selected":"").' value="Female">Female</option>
                        <option '.($accountant->gender == "Others" ? "selected":"").' value="Others">Others</option>
                    </select>
                </div>

                <div class="form-group col-md-12">
                    <label for="blood">Blood Group</label>
                    <select name="blood" id="blood" class="form-control">
                        <option value>Select A Blood Group</option>
                        <option '.($accountant->blood == "a+" ? "selected":"").' value="a+">A+</option>
                        <option '.($accountant->blood == "a-" ? "selected":"").' value="a-">A-</option>
                        <option '.($accountant->blood == "b+" ? "selected":"").' value="b+">B+</option>
                        <option '.($accountant->blood == "b-" ? "selected":"").' value="b-">B-</option>
                        <option '.($accountant->blood == "ab+" ? "selected":"").' value="ab+">AB+</option>
                        <option '.($accountant->blood == "ab-" ? "selected":"").' value="ab-">AB-</option>
                        <option '.($accountant->blood == "o+" ? "selected":"").' value="o+">O+</option>
                        <option '.($accountant->blood == "o-" ? "selected":"").' value="o-">O-</option>
                    </select>
                </div>
                <div class="form-group col-md-12">
                    <label for="phone">Address</label>
                    <textarea class="form-control" id="address" name="address" rows="5" required>'.$accountant->address.'</textarea>
                </div>
                <div class="form-group col-md-12">
                    <img id="blah" src="'.asset($accountant->image).'" alt="Please Select image" class="img-fluid" style="width:100px"/>
                    <label for="image">Upload Image</label>
                    <input type="file" class="form-control" id="image" name="image" accept="image/*">
                </div>
            </div>';

        $route = route("accountant.update", $accountant->id);
        return json_encode(['data'=>$accountant,'status'=>200,'section'=>$form,'route'=>$route]);

    }

    public function update(AccountantUpdateRequest $request, Accountant $accountant)
    {
        $data = $request->validated();
        $avater  = $request->file('image');
        if ($request->hasFile('image')) {
            $avaterNew  = "Accountant_" . Str::random(10) . '.' . $avater->getClientOriginalExtension();
            if ($avater->isValid()) {
                $path1 = public_path() . $accountant->image;
                if ($accountant->image) {
                    if (File::exists($path1)) {
                        File::delete($path1);
                    }
                }
                $avater->storeAs('images/accountant', $avaterNew);
                $data['image']  = '/uploads/images/accountant' . $avaterNew;
            }
        } else {
            $data['image'] = $accountant->image;
        }

        try {
            $accountant->update($data);
            return json_encode(['status'=>200,'message'=>'Accountant Updated Successful!']);
        } catch (\Exception $e) {
            return json_encode(['status'=>500,'message'=>$e->getMessage()]);
        }

    }

    public function destroy(Accountant $accountant)
    {
        try {
            $path = public_path() . $accountant->image;
            if ($accountant->image) {
                if (File::exists($path)) {
                    File::delete($path);
                }
            }
            User::find($accountant->user_id)->delete();
            $accountant->delete();
            return json_encode(['status'=>200,'message'=>'Accountant Kicked Successful!']);
        } catch (\Exception $e) {
            return json_encode(['status'=>500,'message'=>$e->getMessage()]);
        }

    }
}
