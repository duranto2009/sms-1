@extends('admin.layout.admin')
@section('css')
<link rel="stylesheet" href="{{asset('admin/css/bootstrap-select/bootstrap-select.min.css')}}">
@endsection
@section('content')
<div class="container-fluid">
    <style>
        .nav-pills .nav-link.active,
        .nav-pills .show>.nav-link {
            color: #fff;
            background-color: #0acf97;
        }
    </style>
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
                                    <div class="tab-pane active">
                                        <form method="POST" class="col-md-12 ajaxForm" action="" id="bulk_submit">
                                            <div class="row justify-content-md-center">
                                                <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 mb-3 mb-lg-0">
                                                    <select id="className" name="className" class="selectpicker show-menu-arrow form-control" data-live-search="true" required>
                                                        <option value="">Select A Class</option>
                                                        @foreach ($class as $cls)
                                                        <option value="{{$cls->id}}">{{$cls->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 mb-3 mb-lg-0" id="section_content">
                                                    <div class="opt"></div>
                                                </div>
                                            </div>
                                            <br>
                                            <div id="first-row">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="row justify-content-md-center">
                                                            <div class="form-group col-xl-2 col-lg-2 col-md-12 col-sm-12 mb-1 mb-lg-0">
                                                                <input type="text" name="name[]" class="form-control" value=""
                                                                    placeholder="Name" required="">
                                                            </div>
                                                            <div class="form-group col-xl-2 col-lg-2 col-md-12 col-sm-12 mb-1 mb-lg-0">
                                                                <input type="email" name="email[]" class="form-control" value=""
                                                                    placeholder="Email" required="">
                                                            </div>
                                                            <div class="form-group col-xl-2 col-lg-2 col-md-12 col-sm-12 mb-1 mb-lg-0">
                                                                <input type="password" name="password[]" class="form-control" value=""
                                                                    placeholder="Password" required="">
                                                            </div>
                                                            <div class="form-group col-xl-2 col-lg-2 col-md-12 col-sm-12 mb-1 mb-lg-0">
                                                                <select name="gender[]" class="form-control" required="">
                                                                    <option value disabled selected>Select Gender</option>
                                                                    <option value="Male">Male</option>
                                                                    <option value="Female">Female</option>
                                                                    <option value="Others">Others</option>
                                                                </select>
                                                            </div>

                                                            <div class="form-group col-xl-2 col-lg-2 col-md-12 col-sm-12 mb-1 mb-lg-0">
                                                                <select name="parent_id[]" class="selectpicker show-menu-arrow form-control" data-live-search="true" required>
                                                                    <option value disabled selected>Select A Parent</option>
                                                                    @foreach ($guardians as $guard)
                                                                <option value="{{$guard->id}}">{{$guard->name}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>

                                                            <div class="form-group col-xl-2 col-lg-2 col-md-12 col-sm-12 mb-1 mb-lg-0">

                                                                <button type="button" class="btn btn-icon btn-outline-success" onclick="appendRow()">
                                                                    <i class="la la-plus"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="text-center mt-3">
                                                <button type="submit" class="btn btn-outline-info col-md-4 col-sm-12 mb-4 mt-2">
                                                   <i class="la la-plus"></i> Add Students
                                                </button>
                                            </div>
                                        </form>

                                        {{-- Black Form Fild for multi data Insert --}}
                                        <div id="blank-row" style="display: none;">
                                            <div class="row student-row">
                                                <div class="col-12">
                                                    <div class="row justify-content-md-center">
                                                        <div class="form-group col-xl-2 col-lg-2 col-md-12 col-sm-12 mb-1 mb-lg-0">
                                                            <input type="text" name="name[]" class="form-control" value="" placeholder="Name">
                                                        </div>
                                                        <div class="form-group col-xl-2 col-lg-2 col-md-12 col-sm-12 mb-1 mb-lg-0">
                                                            <input type="email" name="email[]" class="form-control" value=""
                                                                placeholder="Email">
                                                        </div>
                                                        <div class="form-group col-xl-2 col-lg-2 col-md-12 col-sm-12 mb-1 mb-lg-0">
                                                            <input type="password" name="password[]" class="form-control" value=""
                                                                placeholder="Password">
                                                        </div>

                                                        <div class="form-group col-xl-2 col-lg-2 col-md-12 col-sm-12 mb-1 mb-lg-0">
                                                            <select name="gender[]" class="form-control">
                                                                <option value="">Select Gender</option>
                                                                <option value="Male">Male</option>
                                                                <option value="Female">Female</option>
                                                                <option value="Others">Others</option>
                                                            </select>
                                                        </div>

                                                        <div class="form-group col-xl-2 col-lg-2 col-md-12 col-sm-12 mb-1 mb-lg-0">
                                                            <select name="parent_id[]" class=" show-menu-arrow form-control" data-live-search="true" required>
                                                                <option value="">Select A Parent</option>
                                                                @foreach ($guardians as $guard)
                                                                <option value="{{$guard->id}}">{{$guard->name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="form-group col">
                                                            <button type="button" class="btn btn-icon btn-outline-danger" onclick="removeRow(this)">
                                                                <i class="la la-close"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        {{-- END FORM blank --}}
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
readData();
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
$("#getStudentlist").on('submit',(e)=>{
    e.preventDefault();
    const data = $("#getStudentlist").serialize();
    const url = $("#getStudentlist").attr('action');
    const method = $("#getStudentlist").attr('method');
    $.ajax({
        url:url,
        method:method,
        data:data,
        success: res=>{
            res = $.parseJSON(res);
            if(res.status == 200){
                toast('success','Successful!');
                $(".student-table").html(res.student);
            }else{
                toast('error',res.error);
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

$("#addClassForm").on('submit',(e)=>{
    e.preventDefault();
    const data = $("#addClassForm").serialize();
    const url = $("#addClassForm").attr('action');
    const method = $("#addClassForm").attr('method');
    $.ajax({
        url:url,
        method:method,
        data:data,
        success: res=>{
            res = $.parseJSON(res);
            if(res.status == 200){
                $("form").trigger("reset");
                toast('success','Class Create Successful!');
                $("#addClass .close").click();
                readData();
            }else{
                toast('error',res.error);
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
function readData(){
    const url = '{{route("class.readData")}}';
    $.ajax({
        url:url,
        method:'get',
        success: res=>{
            res = $.parseJSON(res);
            if(res.status == 200){
                $(".table-content").html(res.data);
            }else{
                toast('error',res.error);
            }
        }
    });
}


var blank_field = $('#blank-row').html();

function appendRow() {
$('#first-row').append(blank_field);
}

function removeRow(elem) {
    $(elem).closest('.student-row').remove();
}

</script>
@endsection
