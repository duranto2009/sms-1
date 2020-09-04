@extends('admin.layout.admin')
@section('content')
<div class="container-fluid">
    <!-- Begin Page Header-->
    <div class="row">
        <div class="col-12">
            <div class="page-header">
                <div class="d-flex align-items-center">
                    <h2 class="page-header-title">Class List</h2>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12">
            <!-- Sorting -->
            <div class="widget has-shadow">
                <div class="widget-header bordered no-actions d-flex align-items-center">
                    <h4>All Class List</h4>
                    <span class="ml-auto">
                        <button class="btn btn-outline-info" data-toggle="modal" data-target="#addClass">
                            <i class="la la-plus"></i> Add New class
                        </button>
                    </span>
                </div>
                <div class="widget-body">
                    <div class="table-responsive">
                        <table id="dbTable" class="table mb-0 table-hover">
                            <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Class Name</th>
                                    <th>Section</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody class="table-content">
                                @foreach ($class as $i=>$cls)
                                <tr>
                                    <td><span class="text-danger">{{$i+=1}}</span></td>
                                    <td>{{$cls->name}}</td>
                                    <td>
                                        <span style="width:100px;">
                                            @foreach (json_decode($cls->section) as $sec)
                                            <span class="badge-text badge-text-small info">{{$sec}}</span>
                                            @endforeach
                                        </span>
                                    </td>
                                    <td class="td-actions">
                                        <a href="#"><i class="la la-edit edit" title="Edit Class"></i></a>
                                        <a href="#"><i class="la la-close delete" title="Delete Class"></i></a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- End Sorting -->
        </div>
    </div>
    <!-- End Row -->

    <!--Add Class Modal -->
    <div class="modal fade" id="addClass" tabindex="-1" role="dialog" aria-labelledby="addClassLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form id='addClassForm' action="{{route('class.store')}}" method="POST" autocomplete="off">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title" id="addClassLabel">Add New Class</h3>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div id="msg"></div>
                        <div class="form-group row">
                            <label for="name" class="col-md-3 col-form-label text-md-right">{{ __('Name') }}</label>
                            <div class="col-md-8">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="off" autofocus>
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="section" class="col-md-3 col-form-label text-md-right">{{ __('Section') }}</label>
                            <div class="col-md-6">
                                <input id="section" type="text" class="form-control @error('section') is-invalid @enderror" name="section[]" value="{{ old('section') }}" required autocomplete="off" autofocus >
                                @error('section')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-md-2">
                                <button type="button" id="addSection" class="btn btn-outline-info"> <i class="la la-plus"></i></button>
                            </div>
                        </div>
                        <div id="dynamic_field"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!--Update Class Modal -->
    <div class="modal modal-top fade" id="update" tabindex="-1" role="dialog" aria-labelledby="update"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="" id="update-form" method="POST">
                @csrf
                @method('put')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="update">Update Section</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div id="updateInput"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- End Container -->
@endsection
@section('js')
<script>
readData();
// $('#dbTable').DataTable();
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
var i = 1;

$('#addSection').on('click',(e)=> {
    var section = $("#section").val();
    i++;
    $('#dynamic_field').append('<div class="form-group row" id="row' + i + '"><label class="col-md-3 my-2 col-form-label text-md-right">Section</label><div class="col-md-6 my-2"><input type="text" class="form-control" name="section[]" value="' + section + '" required autocomplete="off" autofocus></div><div class="col-md-2 my-2"><button type="button" name="remove" id="' + i + '" name="add" id="add" class="btn btn-outline-danger btn_remove">X</button></div></div>');
});

$(document).on('click', '.btn_remove', function() {
    var button_id = $(this).attr("id");
    $('#row' + button_id + '').remove();
});

function editModal(url,header){
    $('#update').modal('show');
        $.ajax({
        url: url,
        method: 'get',
        success:res=>{
            res = $.parseJSON(res);
            $('#update #updateInput').html(res.section);
            $('#update .modal-title').html(header);
        }
    });
}
function deleteModal(url,header){
    $('#delete-modal').modal('show');
    $('#delete-form').attr('action',url)
}




</script>
@endsection
