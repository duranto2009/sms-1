<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\Models\Librarian;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use App\Http\Requests\LibrarianCreateRequest;
use App\Http\Requests\LibrarianUpdateRequest;

class LibrarianController extends Controller
{
    public function index()
    {
        $librarians = Librarian::all();
        return view('admin.partials.librarian.index',compact('librarians'));
    }
    public function create()
    {
        //
    }

    public function store(LibrarianCreateRequest $request)
    {
        $data = $request->validated();

        $avater  = $request->file('image');
        if ($request->hasFile('image')) {
            $avaterNew  = "Librarian_" . Str::random(10) . '.' . $avater->getClientOriginalExtension();
            if ($avater->isValid()) {
                $avater->storeAs('images/librarian/', $avaterNew);
                $data['image']  = '/uploads/images/librarian/' . $avaterNew;
            }
        }
        $user = [
            'name'=>$data['name'],
            'email'=>$data['email'],
            'password'=>bcrypt($request->password),
            'role'=>'librarian',
            'email_verified_at'=>now(),
            'remember_token'=>Str::random(64)
        ];
        try {
            $user = User::create($user);
            $data['user_id'] = $user->id;
            Librarian::create($data);
            return json_encode(['status'=>200,'message'=>'Admission Successful!']);
        } catch (\Exception $e) {
            return json_encode(['status'=>200,'message'=>$e->getMessage()]);
        }


    }
    public function readData()
    {
        $librarians = Librarian::all();
        $html  = '';
        $i = 1;
        foreach ($librarians as $accnt) {
            $html.='<tr>';
            $html.='<td><span class="text-danger">'.$i++.'</span></td>';
            $html.='<td><img src="'.asset($accnt->image).'" alt="..." class="img-fluid" style="width:85px"></td>';
            $html.='<td>'.$accnt->name.'</td>';
            $html.='<td>'.$accnt->phone.'</td>';
            $html.='<td class="td-actions">';
            $editRoute = route("librarian.edit", $accnt->id);
            $deleteRoute = route("librarian.destroy", $accnt->id);
            $html.='<a href="javascript:void(0);" onclick="editModal('. "'{$editRoute}'".','."'Update Librarian'" .')"><i data-id='.$accnt->id.' id="edit" class="la la-edit edit" title="Edit Librarian"></i></a>';
            $html.='<a href="javascript:void(0);" onclick="deleteModal('. "'{$deleteRoute}'".','."'Delete Librarian'" .')"><i data-id='.$accnt->id.' id="delete" class="la la-close delete" title="Delete Librarian"></i></a>';
            $html.='</td>';
            $html.='</tr>';
        }
        return json_encode(['status'=>200,'data'=>$html]);

    }
    public function show(Librarian $librarian)
    {
        //
    }

    public function edit(Librarian $librarian)
    {
        $form = '';
        $form .= '
            <div class="form-row">
                <div class="form-group col-md-12">
                    <label for="name">Name</label>
                    <input value="'.$librarian->name.'" type="text" class="form-control" id="name" name="name" required>
                </div>

                <div class="form-group col-md-12">
                    <label for="email">Email</label>
                    <input value="'.$librarian->email.'" type="email" class="form-control" id="email" name="email" required>
                </div>

                <div class="form-group col-md-12">
                    <label for="phone">Phone Number</label>
                    <input value="'.$librarian->phone.'" type="number" class="form-control" id="phone" name="phone" required>
                </div>

                <div class="form-group col-md-12">
                    <label for="gender">Gender</label>
                    <select name="gender" id="gender" class="form-control">
                        <option value>Select A Gender</option>
                        <option '.($librarian->gender == "Male" ? "selected":"").' value="Male">Male</option>
                        <option '.($librarian->gender == "Female" ? "selected":"").' value="Female">Female</option>
                        <option '.($librarian->gender == "Others" ? "selected":"").' value="Others">Others</option>
                    </select>
                </div>

                <div class="form-group col-md-12">
                    <label for="blood">Blood Group</label>
                    <select name="blood" id="blood" class="form-control">
                        <option value>Select A Blood Group</option>
                        <option '.($librarian->blood == "a+" ? "selected":"").' value="a+">A+</option>
                        <option '.($librarian->blood == "a-" ? "selected":"").' value="a-">A-</option>
                        <option '.($librarian->blood == "b+" ? "selected":"").' value="b+">B+</option>
                        <option '.($librarian->blood == "b-" ? "selected":"").' value="b-">B-</option>
                        <option '.($librarian->blood == "ab+" ? "selected":"").' value="ab+">AB+</option>
                        <option '.($librarian->blood == "ab-" ? "selected":"").' value="ab-">AB-</option>
                        <option '.($librarian->blood == "o+" ? "selected":"").' value="o+">O+</option>
                        <option '.($librarian->blood == "o-" ? "selected":"").' value="o-">O-</option>
                    </select>
                </div>
                <div class="form-group col-md-12">
                    <label for="phone">Address</label>
                    <textarea class="form-control" id="address" name="address" rows="5" required>'.$librarian->address.'</textarea>
                </div>
                <div class="form-group col-md-12">
                    <img id="blah" src="'.asset($librarian->image).'" alt="Please Select image" class="img-fluid" style="width:100px"/>
                    <label for="image">Upload Image</label>
                    <input type="file" class="form-control" id="image" name="image" accept="image/*">
                </div>
            </div>';

        $route = route("librarian.update", $librarian->id);
        return json_encode(['data'=>$librarian,'status'=>200,'section'=>$form,'route'=>$route]);

    }

    public function update(LibrarianUpdateRequest $request, Librarian $librarian)
    {
        $data = $request->validated();
        $avater  = $request->file('image');
        if ($request->hasFile('image')) {
            $avaterNew  = "Librarian_" . Str::random(10) . '.' . $avater->getClientOriginalExtension();
            if ($avater->isValid()) {
                $path1 = public_path() . $librarian->image;
                if ($librarian->image) {
                    if (File::exists($path1)) {
                        File::delete($path1);
                    }
                }
                $avater->storeAs('images/librarian', $avaterNew);
                $data['image']  = '/uploads/images/librarian' . $avaterNew;
            }
        } else {
            $data['image'] = $librarian->image;
        }

        try {
            $librarian->update($data);
            return json_encode(['status'=>200,'message'=>'Librarian Updated Successful!']);
        } catch (\Exception $e) {
            return json_encode(['status'=>500,'message'=>$e->getMessage()]);
        }

    }

    public function destroy(Librarian $librarian)
    {
        try {
            $path = public_path() . $librarian->image;
            if ($librarian->image) {
                if (File::exists($path)) {
                    File::delete($path);
                }
            }
            User::find($librarian->user_id)->delete();
            $librarian->delete();
            return json_encode(['status'=>200,'message'=>'Librarian Kicked Successful!']);
        } catch (\Exception $e) {
            return json_encode(['status'=>500,'message'=>$e->getMessage()]);
        }

    }
}
