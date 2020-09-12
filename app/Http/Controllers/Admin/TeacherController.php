<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\Models\Teacher;
use App\Models\Department;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use App\Http\Requests\TeacherCreateRequest;
use App\Http\Requests\TeacherUpdateRequest;

class TeacherController extends Controller
{
    public function index()
    {
        $teachers = Teacher::all();
        $departments = Department::all();
        return view('admin.partials.teacher.index',compact('teachers','departments'));
    }
    public function create()
    {
        //
    }
    public function store(TeacherCreateRequest $r)
    {
        $data = $r->validated();

        $avater  = $r->file('image');
        if ($r->hasFile('image')) {
            $avaterNew  = "Teacher_" . Str::random(10) . '.' . $avater->getClientOriginalExtension();
            if ($avater->isValid()) {
                $avater->storeAs('images/teacher/', $avaterNew);
                $data['image']  = '/uploads/images/teacher/' . $avaterNew;
            }
        }
        $user = [
            'name'=>$data['name'],
            'email'=>$data['email'],
            'password'=>bcrypt($r->password),
            'role'=>'teacher',
            'email_verified_at'=>now(),
            'remember_token'=>Str::random(64)
        ];
        try {
            $user = User::create($user);
            $data['user_id'] = $user->id;
            Teacher::create($data);
            return json_encode(['status'=>200,'message'=>'Admission Successful!']);
        } catch (\Exception $e) {
            return json_encode(['status'=>200,'message'=>$e->getMessage()]);
        }

    }
    public function readData()
    {
        $teachers = Teacher::all();
        $html  = '';
        $i = 1;
        foreach ($teachers as $dept) {
            $html.='<tr>';
            $html.='<td><span class="text-danger">'.$i++.'</span></td>';
            $html.='<td><img src="'.asset($dept->image).'" alt="..." class="img-fluid" style="width:85px"></td>';
            $html.='<td>'.$dept->name.'</td>';
            $html.='<td>'.$dept->department.'</td>';
            $html.='<td width="20%">'.$dept->designation.'</td>';
            $html.='<td class="td-actions">';
            $editRoute = route("teacher.edit", $dept->id);
            $deleteRoute = route("teacher.destroy", $dept->id);
            $html.='<a href="javascript:void(0);" onclick="editModal('. "'{$editRoute}'".','."'Update Teacher'" .')"><i data-id='.$dept->id.' id="edit" class="la la-edit edit" title="Edit Class"></i></a>';
            if ($dept->status == 0) {
                $html.='<a href="javascript:void(0);" onclick="deleteModal('. "'{$deleteRoute}'".','."'Delete Teacher'" .')"><i data-id='.$dept->id.' id="delete" class="la la-close delete" title="Delete Class"></i></a>';
            }
            $html.='</td>';
            $html.='</tr>';
        }
        return json_encode(['status'=>200,'data'=>$html]);

    }
    public function edit(Teacher $teacher)
    {
        $depts = Department::all();
        $form = '';
        $form .= '
            <div class="form-row">
                <div class="form-group col-md-12">
                    <label for="name">Name</label>
                    <input value="'.$teacher->name.'" type="text" class="form-control" id="name" name="name" required>
                </div>

                <div class="form-group col-md-12">
                    <label for="email">Email</label>
                    <input value="'.$teacher->email.'" type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="form-group col-md-12">
                    <label for="designation">Designation</label>
                    <input value="'.$teacher->designation.'" type="text" class="form-control" id="designation" name="designation" required>
                </div>

                <div class="form-group col-md-12">
                    <label for="department">Department</label>
                    <select name="department" id="department" class="form-control" required>';
                        foreach ($depts as $dept){
                            $form .='<option  '.($teacher->department == $dept->name ? "selected":"").'  value="'.$dept->name.'">'.$dept->name.'</option>';
                        }
                $form .='   </select>
                </div>

                <div class="form-group col-md-12">
                    <label for="phone">Phone Number</label>
                    <input value="'.$teacher->phone.'" type="number" class="form-control" id="phone" name="phone" required>
                </div>

                <div class="form-group col-md-12">
                    <label for="gender">Gender</label>
                    <select name="gender" id="gender" class="form-control">
                        <option value>Select A Gender</option>
                        <option '.($teacher->gender == "Male" ? "selected":"").' value="Male">Male</option>
                        <option '.($teacher->gender == "Female" ? "selected":"").' value="Female">Female</option>
                        <option '.($teacher->gender == "Others" ? "selected":"").' value="Others">Others</option>
                    </select>
                </div>

                <div class="form-group col-md-12">
                    <label for="blood">Blood Group</label>
                    <select name="blood" id="blood" class="form-control">
                        <option value>Select A Blood Group</option>
                        <option '.($teacher->blood == "a+" ? "selected":"").' value="a+">A+</option>
                        <option '.($teacher->blood == "a-" ? "selected":"").' value="a-">A-</option>
                        <option '.($teacher->blood == "b+" ? "selected":"").' value="b+">B+</option>
                        <option '.($teacher->blood == "b-" ? "selected":"").' value="b-">B-</option>
                        <option '.($teacher->blood == "ab+" ? "selected":"").' value="ab+">AB+</option>
                        <option '.($teacher->blood == "ab-" ? "selected":"").' value="ab-">AB-</option>
                        <option '.($teacher->blood == "o+" ? "selected":"").' value="o+">O+</option>
                        <option '.($teacher->blood == "o-" ? "selected":"").' value="o-">O-</option>
                    </select>
                </div>

                <div class="form-group col-md-12">
                    <label>Facebook Profile Link</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="la la-facebook"></i></span>
                        </div>
                        <input value="'.$teacher->facebook.'" type="text" class="form-control" name="facebook">
                    </div>
                </div>

                <div class="form-group col-md-12">
                    <label>Twitter Profile Link</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="la la-twitter"></i></span>
                        </div>
                        <input value="'.$teacher->twitter.'" type="text" class="form-control" name="twitter">
                    </div>
                </div>

                <div class="form-group col-md-12">
                    <label>Linkedin Profile Link</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="la la-linkedin"></i></span>
                        </div>
                        <input value="'.$teacher->linkedin.'" type="text" class="form-control" name="linkedin">
                    </div>
                </div>

                <div class="form-group col-md-12">
                    <label for="phone">Address</label>
                    <textarea class="form-control" id="address" name="address" rows="5" required>'.$teacher->address.'</textarea>
                </div>

                <div class="form-group col-md-12">
                    <label for="about">About</label>
                    <textarea class="form-control" id="about" name="about" rows="5" required>'.$teacher->about.'</textarea>
                </div>

                <div class="form-group col-md-12">
                    <label for="show_web">Show On Website</label>
                    <select name="show_web" id="show_web" class="form-control">
                        <option '.($teacher->show_web == "1"?"selected":"").' value="1">Show</option>
                        <option '.($teacher->show_web == "0"?"selected":"").' value="0">Do Not Need To Show</option>
                    </select>
                </div>

                <div class="form-group col-md-12">
                    <img id="blah" src="'.asset($teacher->image).'" alt="Please Select image" class="img-fluid" style="width:100px"/>
                    <label for="image">Upload Image</label>
                    <input type="file" class="form-control" id="image" name="image" accept="image/*">
                </div>
            </div>';

        $route = route("teacher.update", $teacher->id);
        return json_encode(['data'=>$teacher,'status'=>200,'section'=>$form,'route'=>$route]);

    }
    public function update(TeacherUpdateRequest $request, Teacher $teacher)
    {
        $data = $request->validated();
        $avater  = $request->file('image');
        if ($request->hasFile('image')) {
            $avaterNew  = "Teacher_" . Str::random(10) . '.' . $avater->getClientOriginalExtension();
            if ($avater->isValid()) {
                $path1 = public_path() . $teacher->image;
                if ($teacher->image) {
                    if (File::exists($path1)) {
                        File::delete($path1);
                    }
                }
                $avater->storeAs('images/teacher', $avaterNew);
                $data['image']  = '/uploads/images/teacher' . $avaterNew;
            }
        } else {
            $data['image'] = $teacher->image;
        }

        try {
            $teacher->update($data);
            return json_encode(['status'=>200,'message'=>'Teacher Updated Successful!']);
        } catch (\Exception $e) {
            return json_encode(['status'=>500,'message'=>$e->getMessage()]);
        }

    }
    public function destroy(Teacher $teacher)
    {
        try {
            $path = public_path() . $teacher->image;
            if ($teacher->image) {
                if (File::exists($path)) {
                    File::delete($path);
                }
            }
            User::find($teacher->user_id)->delete();
            $teacher->delete();
            return json_encode(['status'=>200,'message'=>'Teacher Kicked Successful!']);
        } catch (\Exception $e) {
            return json_encode(['status'=>500,'message'=>$e->getMessage()]);
        }

    }
}
