<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\Models\Student;
use App\Models\Guardian;
use App\Models\ClassTable;
use App\Models\SessionYear;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Imports\StudentImport;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Requests\StudentCreateRequest;
use App\Http\Requests\StudentUpdateRequest;

class StudentController extends Controller
{
    public function index()
    {
        $class = ClassTable::all();
        return view('admin.partials.student.index',compact('class'));
    }

    public function section(Request $r)
    {
        $class = ClassTable::findOrFail($r->className);
        $opt = '';
        $opt .= '<select id="section" name="section" class="form-control" required>';
        $opt .= '<option selected value="" disabled>Select Section</option>';
        foreach(json_decode($class->section) as $cls){
            $opt .= '<option value="'.$cls.'">'.$cls.'</option>';
        }
        $opt .= '</select>';
        return json_encode(['status'=>200,'opt'=>$opt]);
    }
    public function filter(Request $r)
    {
        $session = SessionYear::where('status', 1)->first()->title;
        $students = Student::where('class_table_id',$r->className)
                    ->where('section',$r->section)
                    ->where('session',$session)
                    ->get();
        $student = '';
        $student .='<div class="table-responsive">';
        $student .='    <table id="dbTable" class="table mb-0 table-hover">';
        $student .='        <thead>';
        $student .='            <tr>';
        $student .='                <th>student Id</th>';
        $student .='                <th>Photo</th>';
        $student .='                <th>Name</th>';
        $student .='                <th>Email</th>';
        $student .='                <th>Actions</th>';
        $student .='            </tr>';
        $student .='        </thead>';
        $student .='        <tbody>';
        foreach ($students as $st) {
            $student .='            <tr>';
            $student .='                <td>'.$st->student_id.'</td>';
            $student .='<td> <img src="'.asset($st->image??'admin/img/user.jpg').'" class="img-fluid" width="85px"></td>';
            $student .='                <td>'.$st->name.'</td>';
            $student .='                <td>'.$st->email.'</td>';
            $student .='                <td class="td-actions">';
            $editRoute = route("student.edit", $st->id);
            $deleteRoute = route("student.destroy", $st->id);
            $student.='<a href="javascript:void(0);" onclick="editModal('. "'{$editRoute}'".','."'Update Section'" .')"><i data-id='.$st->id.' id="edit" class="la la-edit edit" title="Edit Class"></i></a>';
            $student.='<a href="javascript:void(0);" onclick="deleteModal('. "'{$deleteRoute}'".','."'Delete Section'" .')"><i data-id='.$st->id.' id="delete" class="la la-close delete" title="Delete Class"></i></a>';
            $student .='                </td>';
            $student .='            </tr>';
        }
        $student .='        </tbody>';
        $student .='    </table>';
        $student .='</div>';
        return json_encode(['status'=>200,'student'=>$student]);

    }
    public function create()
    {
        $class = ClassTable::all();
        $guardian = Guardian::all();
        return view('admin.partials.student.create',compact('class','guardian'));
    }

    public function store(StudentCreateRequest $request)
    {
        $data = $request->validated();
        $session = SessionYear::where('status',1)->first()->title;
        $data['class_table_id']=$data['className'];
        $data['student_id']=$session.$data['guardian_id'].rand(1111,9999);
        $data['session']=$session;
        $avater  = $request->file('image');
        if ($request->hasFile('image')) {
            $avaterNew  = "Student_" . Str::random(10) . '.' . $avater->getClientOriginalExtension();
            if ($avater->isValid()) {
                $avater->storeAs('images', $avaterNew);
                $data['image']  = '/uploads/images/' . $avaterNew;
            }
        }
        $user = [
            'name'=>$data['name'],
            'email'=>$data['email'],
            'password'=>bcrypt($request->password),
            'role'=>'student',
            'email_verified_at'=>now(),
            'remember_token'=>Str::random(64)
        ];

        try {
            $user = User::create($user);
            $data['user_id'] = $user->id;
            Student::create($data);
            return json_encode(['status'=>200,'message'=>'Admission Successful!']);
        } catch (\Exception $e) {
            return json_encode(['status'=>200,'message'=>$e->getMessage()]);
        }

    }


    public function bulk()
    {
        $class = ClassTable::all();
        $guardians = Guardian::all();
        return view('admin.partials.student.bulk',compact('class','guardians'));
    }

    public function bulkStore(Request $r)
    {
       $r->validate([
            'className'     => 'required',
            'section'       => 'required',
            'name.*'        => 'required',
            'email.*'       => 'required|email|distinct',
            'password.*'    => 'required',
            'gender.*'      => 'required',
            'guardian_id.*' => 'required',
        ]);

        $session = SessionYear::where('status', 1)->first()->title;

        if($r->has('email')){
            foreach($r->email as $i=>$v){
                $student_id=$session.$r->guardian_id[$i].rand(1111, 9999);
                $user = [
                    'name'=>$r->name[$i],
                    'email'=>$r->email[$i],
                    'password'=>bcrypt($r->password[$i]),
                    'role'=>'student',
                    'email_verified_at'=>now(),
                    'remember_token'=>Str::random(64)
                ];
                $user = User::create($user);

                $data = [
                    'name'           => $r->name[$i],
                    'email'          => $r->email[$i],
                    'student_id'     => $student_id,
                    'user_id'        => $user->id,
                    'guardian_id'    => $r->guardian_id[$i],
                    'class_table_id' => $r->className,
                    'section'        => $r->section,
                    'session'        => $session,
                    'gender'         => $r->gender[$i],
                ];
                Student::create($data);
            }
        }
        try {
            return json_encode(['status'=>200,'message'=>'Admission Successful!']);
        } catch (\Exception $e) {
            return json_encode(['status'=>200,'message'=>$e->getMessage()]);
        }

    }

    public function csv()
    {
        $class = ClassTable::all();
        return view('admin.partials.student.csv',compact('class'));
    }

    public function csvStore(Request $r)
    {
        $r->validate([
            'className'     => 'required',
            'section'       => 'required',
            'studentCsv'        => 'required|mimes:csv,txt|max:2040',
        ]);

        $session = SessionYear::where('status', 1)->first()->title;

        try {
            Excel::import(new StudentImport($r->className,$r->section,$session),$r->studentCsv);
            return json_encode(['status'=>200,'message'=>'Admission Successful!']);
        } catch (\Exception $e) {
            return json_encode(['status'=>200,'message'=>$e->getMessage()]);
        }
    }
    public function show(Student $student)
    {
        //
    }


    public function edit(Student $student)
    {
        $section = '';
        $section .= '
        <div class="col-md-12">
            <div class="form-group row mb-3">
                <label class="col-md-3 col-form-label" for="name">Name</label>
                <div class="col-md-9">
                    <input type="text" id="name" name="name" class="form-control" value="'.$student->name.'">
                </div>
            </div>

            <div class="form-group row mb-3">
                <label class="col-md-3 col-form-label" for="email">Email</label>
                <div class="col-md-9">
                    <input type="email" class="form-control" id="email" name="email" value="'.$student->email.'">
                </div>
            </div>

            <div class="form-group row mb-3">
                <label class="col-md-3 col-form-label" for="dob">Birthday</label>
                <div class="col-md-9">
                    <input type="date" class="form-control date" name="dob" id="dob" value="'.($student->dob != ""?$student->dob->format('Y-m-d'):"").'">
                </div>
            </div>

            <div class="form-group row mb-3">
                <label class="col-md-3 col-form-label" for="gender">Gender</label>
                <div class="col-md-9">
                    <select name="gender" id="gender" class="form-control ">
                        <option '.($student->gender == "Male"?"selected":"").' value="Male">Male</option>
                        <option '.($student->gender == "Female"?"selected":"").' value="Female">Female</option>
                        <option '.($student->gender == "Others"?"selected":"").' value="Others">Others</option>
                    </select>
                </div>
            </div>

            <div class="form-group row mb-3">
                <label class="col-md-3 col-form-label" for="example-textarea">Address</label>
                <div class="col-md-9">
                    <textarea class="form-control" id="example-textarea" rows="6" name="address"
                        placeholder="address" style="resize: none">'.$student->address.'</textarea>
                </div>
            </div>

            <div class="form-group row mb-3">
                <label class="col-md-3 col-form-label" for="phone">Phone</label>
                <div class="col-md-9">
                    <input type="text" id="phone" name="phone" class="form-control"
                        value="'.$student->phone.'">
                </div>
            </div>

            <div class="form-group row mb-3">
                <label class="col-md-3 col-form-label" for="example-fileinput">Student Profile
                    Image</label>
                <div class="col-md-9 text-center">
                    <div class="image-preview" style="height: 250px;width: 250px;margin: 0 auto;">
                        <img id="blah" src="'.asset($student->image).'" alt="Please Select image" class="img-fluid"/>
                    </div>
                    <div class="upload-options">
                        <label for="image" class="form-control" style="cursor: pointer"> <i class="la la-camera"></i> Upload An Image
                        </label>
                        <input id="image" type="file" class="image-upload" name="image" accept="image/*" style="visibility:hidden">
                    </div>
                </div>
            </div>
        </div>
        ';
        $route = route("student.update", $student->id);
        return json_encode(['data'=>$student,'status'=>200,'section'=>$section,'route'=>$route]);

    }


    public function update(StudentUpdateRequest $request, Student $student)
    {
        $data = $request->validated();

        $avater  = $request->file('image');
        if ($request->hasFile('image')) {
            $avaterNew  = "Student_" . Str::random(10) . '.' . $avater->getClientOriginalExtension();
            if ($avater->isValid()) {

                $path1 = public_path() . $student->image;
                if ($student->image) {
                    if (File::exists($path1)) {
                        File::delete($path1);
                    }
                }
                $avater->storeAs('images', $avaterNew);
                $data['image']  = '/uploads/images/' . $avaterNew;
            }
        }else{
            $data['image'] = $student->image;
        }

        try {
            $student->update($data);
            return json_encode(['status'=>200,'message'=>'Student Info Updated Successful!']);
        } catch (\Exception $e) {
            return json_encode(['status'=>500,'message'=>$e->getMessage()]);
        }

    }


    public function destroy(Student $student)
    {
        try {
            $path = public_path() . $student->image;
            if ($student->image) {
                if (File::exists($path)) {
                    File::delete($path);
                }
            }
            User::find($student->user_id)->delete();
            $student->delete();
            return json_encode(['status'=>200,'message'=>'Student Kicked Successful!']);
        } catch (\Exception $e) {
            return json_encode(['status'=>500,'message'=>$e->getMessage()]);
        }

    }
}
