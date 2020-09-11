@extends('admin.layout.admin')
@section('css')
<link rel="stylesheet" href="{{asset('admin/css/bootstrap-select/bootstrap-select.min.css')}}">
@endsection
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-xl-12">
            <!-- Sorting -->
            <div class="widget has-shadow">
                <div class="widget-header bordered no-actions d-flex align-items-center">
                    <h1> <i class="la la-users"></i> Student Admission Form</h1>
                    <span class="ml-auto">

                    </span>
                </div>
                <div class="widget-body">
                    <div class="row">
                        <div class="col-12">
                                <ul class="nav nav-pills bg-nav-pills nav-justified mb-3">
                                    <li class="nav-item">
                                        <a href="{{route('student.create')}}"
                                            class="nav-link rounded-0 {{ Route::is('student.create') ? 'active' : ''}}">
                                            <i class="mdi mdi-home-variant d-lg-none d-block mr-1"></i>
                                            <span class="d-none d-lg-block">Single Student Admission</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{route('student.bulk')}}" class="nav-link rounded-0 {{ Route::is('student.bulk') ? 'active' : ''}}">
                                            <i class="mdi mdi-account-circle d-lg-none d-block mr-1"></i>
                                            <span class="d-none d-lg-block">Bulk Student Admission</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{route('student.csv')}}" class="nav-link rounded-0 {{ Route::is('student.csv') ? 'active' : ''}}">
                                            <i class="mdi mdi-settings-outline d-lg-none d-block mr-1"></i>
                                            <span class="d-none d-lg-block">Excel Upload</span>
                                        </a>
                                    </li>
                                </ul>
                            <div class="tab-content">
                    <div class="row justify-content-center">
                        <div class="col-lg-8">
<form method="POST" action="{{route('student.store')}}" id="student_admission" enctype="multipart/form-data">
    @csrf
    <div class="col-md-12">
        <div class="form-group row mb-3">
            <label class="col-md-3 col-form-label" for="name">Name</label>
            <div class="col-md-9">
                <input type="text" id="name" name="name" class="form-control"
                    placeholder="name">
            </div>
        </div>

        <div class="form-group row mb-3">
            <label class="col-md-3 col-form-label" for="email">Email</label>
            <div class="col-md-9">
                <input type="email" class="form-control" id="email" name="email"
                    placeholder="email">
            </div>
        </div>

        <div class="form-group row mb-3">
            <label class="col-md-3 col-form-label" for="password">Password</label>
            <div class="col-md-9">
                <input type="password" class="form-control" id="password" name="password"
                    placeholder="password">
            </div>
        </div>

        <div class="form-group row mb-3">
            <label class="col-md-3 col-form-label" for="guardian_id">Parent</label>
            <div class="col-md-9">
                <select id="guardian_id" name="guardian_id"  class="form-control selectpicker show-menu-arrow form-control" data-live-search="true">
                    <option selected disabled>Selete Parent</option>
                    @forelse ($guardian as $parent)
                        <option value="{{$parent->id}}">{{$parent->name}}</option>
                    @empty
                        <option selected disabled>Please Create First</option>
                    @endforelse
                </select>
            </div>
        </div>

        <div class="form-group row mb-3">
            <label class="col-md-3 col-form-label" for="class_id">Class</label>
            <div class="col-md-9">
                <select name="className" id="className"
                    class="selectpicker show-menu-arrow form-control"
                    data-live-search="true">
                    <option disabled selected>Select Class</option>
                    @foreach ($class as $cls)
                    <option value="{{$cls->id}}">{{$cls->name}}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group row mb-3">
            <label class="col-md-3 col-form-label" for="section_id">Section</label>
            <div class="col-md-9" id="section">
                <div class="opt"></div>
            </div>
        </div>

        <div class="form-group row mb-3">
            <label class="col-md-3 col-form-label" for="dob">Birthday</label>
            <div class="col-md-9">
                <input type="date" class="form-control date" name="dob"id="dob">
            </div>
        </div>

        <div class="form-group row mb-3">
            <label class="col-md-3 col-form-label" for="gender">Gender</label>
            <div class="col-md-9">
                <select name="gender" id="gender" class="form-control ">
                    <option selected disabled>Select Gender</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    <option value="Others">Others</option>
                </select>
            </div>
        </div>

        <div class="form-group row mb-3">
            <label class="col-md-3 col-form-label" for="example-textarea">Address</label>
            <div class="col-md-9">
                <textarea class="form-control" id="example-textarea" rows="6" name="address"
                    placeholder="address" style="resize: none"></textarea>
            </div>
        </div>

        <div class="form-group row mb-3">
            <label class="col-md-3 col-form-label" for="phone">Phone</label>
            <div class="col-md-9">
                <input type="text" id="phone" name="phone" class="form-control"
                    placeholder="phone">
            </div>
        </div>

        <div class="form-group row mb-3">
            <label class="col-md-3 col-form-label" for="example-fileinput">Student Profile
                Image</label>
            <div class="col-md-9 text-center">
                <div class="image-preview" style="height: 250px;width: 250px;margin: 0 auto;">
                    <img id="blah" src="{{asset('admin/img/user.jpg')}}" alt="your image" style="height: 100%;"/>
                </div>
                <div class="upload-options">
                    <label for="image" class="form-control" style="cursor: pointer"> <i class="la la-camera"></i> Upload An Image
                    </label>
                    <input id="image" type="file" class="image-upload" name="image" accept="image/*" style="visibility:hidden">
                </div>
            </div>
        </div>

        <div class="text-center">
            <button type="submit" class="btn btn-outline-info col-md-4 col-sm-12 mb-4">Add
                Student</button>
        </div>
    </div>
</form>
                        </div>
                        </div>
                        </div>
                        </div>
                        </div>
                    </div>

                </div>
            </div>
            <!-- End Sorting -->
        </div>
    </div>
    <!-- End Row -->
</div>
<!-- End Container -->
@endsection
@section('js')
<script src="{{asset('admin/vendors/js/bootstrap-select/bootstrap-select.js')}}"></script>
<script>
// read image before submit
function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            $('#blah').attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
    }
}
$("#image").change(function() {
    readURL(this);
});
// End image

// $('#dbTable').DataTable();
$("#className").on('change',(e)=>{
    const data = $("#className").serialize();
    const url = '{{route("student.section")}}';
    const method = 'get';
    $.ajax({
        url:url,
        method:method,
        data:data,
        success: res=>{
            res = $.parseJSON(res);
            if(res.status == 200){
                $(".opt").html(res.opt);
            }else{
                toast('error',res.error);
            }
        }
    });
});

$("#student_admission").submit(function(e){
    e.preventDefault();
    // const data = $("#student_admission").serialize();
    console.log(this);

    const data = new FormData(this);
    const url = $("#student_admission").attr('action');
    const method = $("#student_admission").attr('method');
    $.ajax({
        url:url,
        method:method,
        cache:false,
        contentType: false,
        processData: false,
        data:data,
        success: res=>{
            res = $.parseJSON(res);
            if(res.status == 200){
                $("form").trigger("reset");
                toast('success',res.message);
            }else{
                toast('error',res.message);
            }
        },
        error: err=>{
            const errors = err.responseJSON;
            if($.isEmptyObject(errors) == false){
                $.each(errors.errors,function(key,value){
                    toast('error',value);
                });
            }
        }
    });
});
</script>
@endsection
