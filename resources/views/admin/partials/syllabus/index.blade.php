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
                    <h1> <i class="la la-bullseye text-info"></i> Syllabus List</h1>
                    <span class="ml-auto">
                        <button data-toggle="modal" data-target="#addSyllabus" class="btn btn-outline-info"><i class="la la-plus"></i>Add Syllabus</button>
                    </span>
                </div>
                <div class="widget-body">
                    <form id="filterSyllabus" action="{{route('syllabus.filter')}}" method="get" autocomplete="off">
                        <div class="form-group row d-flex align-items-center mt-3 mb-5 justify-content-center">
                            <div class="col-lg-4">
                                <select id="className" name="className"
                                    class="selectpicker show-menu-arrow form-control" data-live-search="true" required onchange="getSection(this.value)">
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
    <div class="modal modal-top fade" id="addSyllabus" tabindex="-1" role="dialog" aria-labelledby="addSyllabus"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="{{route('syllabus.store')}}" id="addSyllabusForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="modal-title" id="addSyllabus">ADD SYLLABUS</h2>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="name">Tittle</label>
                                <input type="text" class="form-control" id="name" name="name" >
                            </div>
                            <div class="form-group col-md-12">
                                <label for="className">Class</label>
                                <select id="className" name="class_table_id" class="selectpicker show-menu-arrow form-control" data-live-search="true" required
                                onchange="getSection(this.value)">
                                    <option disabled selected>Select Class</option>
                                    @foreach ($class as $cls)
                                    <option value="{{$cls->id}}">{{$cls->name}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-12">
                                <label for="section_id">Section</label>
                                <div class="opt"></div>
                            </div>

                            <div class="form-group col-md-12">
                                <label for="subject_id">Subject</label>
                                <select class="form-control" id="subject_id" name="subject_id" requied >
                                    <option >Select A Subject</option>
                                </select>
                            </div>
                            <div class="form-group col-md-12">
                                <label for="syllabus_file">Upload Syllabus</label>
                                <input type="file" name="file" id="file" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add</button>
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
function getSection(data){
    const url = '{{route("student.section")}}';
    const method = 'get';
    $.ajax({
        url:url,
        method:method,
        data:{className:data},
        success: res=>{
            res = $.parseJSON(res);
            if(res.status == 200){
            $(".opt").html(res.opt);
            $("#subject_id").html(res.subject);
            }else{
            toast('error',res.error);
            }
        }
    });
}
$("#filterSyllabus").on('submit',(e)=>{
    e.preventDefault();
    const data = $("#filterSyllabus").serialize();
    const url = $("#filterSyllabus").attr('action');
    const method = $("#filterSyllabus").attr('method');
    $.ajax({
        url:url,
        method:method,
        data:data,
        success: res=>{
            res = $.parseJSON(res);
            if(res.status == 200){
                toast('success','Successful!');
                $(".student-table").html(res.syllabus);
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

$("#addSyllabusForm").on('submit',function(e){
    e.preventDefault();
    const data = new FormData(this);
    const url = $("#addSyllabusForm").attr('action');
    const method = $("#addSyllabusForm").attr('method');
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
                toast('success','Syllabus Create Successful!');
                $("#addSyllabus .close").click();
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
    const data = $("#filterSyllabus").serialize();
    const url = $("#filterSyllabus").attr('action');
    const method = $("#filterSyllabus").attr('method');
    $.ajax({
    url:url,
    method:method,
    data:data,
    success: res=>{
    res = $.parseJSON(res);
    if(res.status == 200){
    $(".student-table").html(res.syllabus);
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
</script>
@endsection
