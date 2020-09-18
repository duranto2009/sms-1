@extends('admin.layout.admin')
@section('css')
<link rel="stylesheet" href="{{asset('admin/css/datatables/datatables.min.css')}}">
@endsection
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-xl-12">
            <!-- Sorting -->
            <div class="widget has-shadow">
                <div class="widget-header bordered no-actions d-flex align-items-center">
                    <h1> <i class="la la-home"></i>Class Room List</h1>
                    <span class="ml-auto">
                        <button class="btn btn-outline-info" data-toggle="modal" data-target="#addRoom">
                            <i class="la la-plus"></i> Add Room
                        </button>
                    </span>
                </div>
                <div class="widget-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table id="example" class="table table-striped" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>SL</th>
                                            <th>NAME</th>
                                            <th>ACTION</th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-content">
                                        @foreach ($rooms as $i=>$room)
                                        <tr>
                                            <td>{{$i+1}}</td>
                                            <td>{{$room->name}}</td>
                                            <td class="td-actions">
                                                <a href="javascript:void(0);" onclick="editModal('{{route('classroom.edit', $room->id)}}','Update Class Room')">
                                                    <i id="edit" class="la la-edit edit" title="Edit Class Room"></i>
                                                </a>
                                                <a href="javascript:void(0);" onclick="deleteModal('{{route('classroom.destroy', $room->id)}}','Delete Class Room')">
                                                    <i id="delete" class="la la-close delete" title="Delete Class Room"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Sorting -->
        </div>
    </div>
    <!-- End Row -->

    <!--Add Class Modal -->
    <div class="modal fade" id="addRoom" tabindex="-1" role="dialog" aria-labelledby="addRoomLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form id='addRoomForm' action="{{route('classroom.store')}}" method="POST" autocomplete="off">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title" id="addRoomLabel">Add New Class Room</h3>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div id="msg"></div>
                        <div class="form-group row">
                            <label for="name" class="col-md-3 col-form-label text-md-right">Room Name</label>
                            <div class="col-md-8">
                                <input id="name" type="text" class="form-control" name="name"  required autocomplete="off" autofocus style="text-transform: uppercase;">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-outline-info">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

</div>
<!-- End Container -->
@endsection
@section('js')
<script src="//cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script>
    $('#example').DataTable();
    $('select').attr('name','example_length').addClass('form-control');
    $('input').attr('aria-controls','example').addClass('form-control');
    readData();
$("#addRoomForm").on('submit',(e)=>{
    e.preventDefault();
    const data = $("#addRoomForm").serialize();
    const url = $("#addRoomForm").attr('action');
    const method = $("#addRoomForm").attr('method');
    $.ajax({
        url:url,
        method:method,
        data:data,
        success: res=>{
            res = $.parseJSON(res);
            if(res.status == 200){
                $("form").trigger("reset");
                toast('success','Room Create Successful!');
                $("#addRoom .close").click();
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
    const url = '{{route("classroom.filter")}}';
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
</script>
@endsection
