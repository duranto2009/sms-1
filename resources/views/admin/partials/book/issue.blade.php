@extends('admin.layout.admin')
@section('css')
{{-- <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap3.min.css"> --}}
<link rel="stylesheet" href="{{asset('admin/css/datatables/datatables.min.css')}}">
@endsection
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-xl-12">
            <!-- Sorting -->
            <div class="widget has-shadow">
                <div class="widget-header bordered no-actions d-flex align-items-center">
                    <h1> <i class="la la-paste"></i> Book Issue List</h1>
                    <span class="ml-auto">
                        <button class="btn btn-outline-info" data-toggle="modal" data-target="#addIssu">
                            <i class="la la-plus"></i> Add New Department
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
                                            <th>Book Name</th>
                                            <th>Issue Date</th>
                                            <th>Student Name</th>
                                            <th>Class</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-content">
                                        @foreach ($issues as $i=>$issue)
                                        <tr>
                                            <td>{{$i+1}}</td>
                                            <td>{{$issue->book->name}}</td>
                                            <td>{{$issue->issue_date->format('D d-M-Y')}}</td>
                                            <td>{{$issue->student->name}}</td>
                                            <td>{{$issue->class->name}}</td>
                                            <td>{{$issue->status}}</td>
                                            <td class="td-actions">
                                                <a href="javascript:void(0);" onclick="editModal('{{route('bookIssue.edit', $issue->id)}}','Update Book Issue')">
                                                    <i data-id='.$cls->id.' id="edit" class="la la-edit edit" title="Edit Class"></i>
                                                </a>
                                                <a href="javascript:void(0);" onclick="deleteModal('{{route('bookIssue.destroy', $issue->id)}}','Delete Book Issue')">
                                                    <i data-id='.$cls->id.' id="delete" class="la la-close delete" title="Delete Class"></i>
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
    <div class="modal fade" id="addIssu" tabindex="-1" role="dialog" aria-labelledby="addIssuLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form id='addIssuForm' action="{{route('bookIssue.store')}}" method="POST" autocomplete="off">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title" id="addIssuLabel">Add Issue</h3>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div id="msg"></div>
                        <div class="form-group row">
                            <label for="name" class="col-md-3 col-form-label text-md-right">kl</label>
                            <div class="col-md-8">
                                <input id="name" type="text" class="form-control" name="name"  required autocomplete="off" autofocus>
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
    // readData();
$("#addIssuForm").on('submit',(e)=>{
    e.preventDefault();
    const data = $("#addIssuForm").serialize();
    const url = $("#addIssuForm").attr('action');
    const method = $("#addIssuForm").attr('method');
    $.ajax({
        url:url,
        method:method,
        data:data,
        success: res=>{
            res = $.parseJSON(res);
            if(res.status == 200){
                $("form").trigger("reset");
                toast('success','Department Create Successful!');
                $("#addIssu .close").click();
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
    const url = '{{route("department.readData")}}';
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
