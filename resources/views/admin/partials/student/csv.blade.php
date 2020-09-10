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
            background-color: #0aa1cf;
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
                                        <a href="{{route('student.bulk')}}"
                                            class="nav-link rounded-0 {{ Route::is('student.bulk') ? 'active' : ''}}">
                                            <i class="mdi mdi-account-circle d-lg-none d-block mr-1"></i>
                                            <span class="d-none d-lg-block">Bulk Student Admission</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{route('student.csv')}}"
                                            class="nav-link rounded-0 {{ Route::is('student.csv') ? 'active' : ''}}">
                                            <i class="mdi mdi-settings-outline d-lg-none d-block mr-1"></i>
                                            <span class="d-none d-lg-block">Excel Upload</span>
                                        </a>
                                    </li>
                                </ul>

                                <div class="tab-content">
                                    <div class="tab-pane active">
                                        <form method="POST" class="col-md-12 ajaxForm" action="{{route('student.csvStore')}}" id="csv_form" enctype="multipart/form-data">
                                            @csrf
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
                                            <div class="form-group row my-3 justify-content-center">
                                                <label class="col-md-2 col-form-label" for="studentCsv">Select CSV File</label>
                                                <div class="col-md-4">
                                                    <input type="file" class="form-control" id="studentCsv" name="studentCsv" required accept=".csv">
                                                </div>
                                            </div>
                                            <br>
                                            <div class="text-center mt-3">
                                                <button type="submit" class="btn btn-outline-info col-md-4 col-sm-12 mb-4 mt-2">
                                                    Add Students
                                                </button>
                                            </div>
                                        </form>
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
$("#csv_form").submit(function(e){
    e.preventDefault();
    // const data = $("#csv_form").serialize();
    const data = new FormData(this);
    const url = $("#csv_form").attr('action');
    const method = $("#csv_form").attr('method');
    $.ajax({
        url:url,
        method:method,
        data:data,
        cache:false,
        contentType: false,
        processData: false,
        success: res=>{
            res = $.parseJSON(res);
            if(res.status == 200){
                $("form").trigger("reset");
                toast('success','Student Create Successful!');
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

</script>
@endsection
