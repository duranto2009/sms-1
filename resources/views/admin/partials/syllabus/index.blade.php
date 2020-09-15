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
                    <h1> <i class="la la-user-secret"></i> All Student List</h1>
                    <span class="ml-auto">
                        <a href="{{route('student.create')}}" class="btn btn-outline-info"><i class="la la-plus"></i>
                            Add New Student</a>
                    </span>
                </div>
                <div class="widget-body">
                    <form id="getStudentlist" action="{{route('student.filter')}}" method="get" autocomplete="off">
                        <div class="form-group row d-flex align-items-center mt-3 mb-5 justify-content-center">
                            <div class="col-lg-4">
                                <select id="className" name="className"
                                    class="selectpicker show-menu-arrow form-control" data-live-search="true" required>
                                    <option disabled selected>Select Class</option>
                                    @foreach ($class as $cls)
                                    <option value="{{$cls->id}}">{{$cls->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-4">
                                <div class="opt"></div>
                            </div>
                            <div class="col-lg-2">
                                <button class="btn btn-outline-success" type="submit">Filter</button>
                            </div>
                        </div>
                    </form>
                    <div class="student-table text-center">
                        <img src="{{asset('admin/img/empty_box.png')}} " alt="...." class="img-fluid" width="250px">
                        <br>
                        <p>No Data Found</p>
                    </div>
                </div>
            </div>
            <!-- End Sorting -->
        </div>
    </div>
    <!-- End Row -->
    <!--Profile  Modal -->
    <div class="modal modal-top fade" id="studentProfile" tabindex="-1" role="dialog" aria-labelledby="studentProfile"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <form action="" id="studentProfile-form" method="POST">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="studentProfile">{{config('app.name','LARAVEL')}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div id="studentProfileInput"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        {{-- <button type="submit" class="btn btn-primary">studentProfile</button> --}}
                    </div>
                </div>
            </form>
        </div>
    </div>
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
}
function studentProfile(url,header){
    $('#studentProfile').modal('show');
    $.ajax({
        url: url,
        method: 'get',
        success:res=>{
            res = $.parseJSON(res);
            $('#studentProfile #studentProfileInput').html(res.student);
        }
    });
}
</script>
@endsection
