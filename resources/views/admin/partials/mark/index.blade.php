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
                    <h1> <i class="la la-certificate"></i> Exam List</h1>
                    <span class="ml-auto">
                        <button class="btn btn-outline-info" data-toggle="modal" data-target="#addExam">
                            <i class="la la-plus"></i> Add Exam
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
                                            <th>Exam Name</th>
                                            <th>Starting Date</th>
                                            <th>Ending Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-content">
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
    <div class="modal fade" id="addExam" tabindex="-1" role="dialog" aria-labelledby="addExamLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form id='addExamForm' action="{{route('exam.store')}}" method="POST" autocomplete="off" novalidate>
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title" id="addExamLabel">Add Grade</h3>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
<div class="form-row">
    <div class="form-group col-md-12">
        <label for="exam_name">Exam Name</label>
        <input type="text" class="form-control" id="exam_name" name="exam_name" placeholder="name" required="">
        <small id="name_help" class="form-text text-muted">Provide Exam Name</small>
    </div>

    <div class="form-group col-md-12">
        <label for="starting_date">Starting Date</label>
        <input type="date" class="form-control date" id="starting_date" name="starting_date"required="">
        <small id="name_help" class="form-text text-muted">Provide Starting Date</small>
    </div>

    <div class="form-group col-md-12">
        <label for="ending_date">Ending Date</label>
        <input type="date" class="form-control date" id="ending_date" name="ending_date" required="">
        <small id="name_help" class="form-text text-muted">Provide Ending Date</small>
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
$('#example').DataTable({
    processing: true,
    serverSide: true,
    ajax: {
        url: '{{route("exam.index")}}',
        type: 'GET',
    },
    columns: [
        { data: 'id',name: 'id', 'visible': false },
        { data: 'exam_name', name:'exam_name' },
        { data: 'starting_date', name:'starting_date',
            render(h) {
                return moment(h).format("DD MMM YYYY")
            },
        },
        { data: 'ending_date', name:'ending_date',
            render(h) {
                return moment(h).format("DD MMM YYYY")
            },
        },
        { data: 'action', name:'action', orderable: false }
    ],
    order: [[ 1, 'desc' ]]
});
$('select').addClass('form-control');
$('input').addClass('form-control');
$("#addExamForm").on('submit',(e)=>{
    e.preventDefault();
    const data = $("#addExamForm").serialize();
    const url = $("#addExamForm").attr('action');
    const method = $("#addExamForm").attr('method');
    $.ajax({
        url:url,
        method:method,
        data:data,
        success: res=>{
            res = $.parseJSON(res);
            if(res.status == 200){
                $("form").trigger("reset");
                toast('success',res.message);
                $("#addExam .close").click();
                var oTable = $('#example').dataTable();
                oTable.fnDraw(false);
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
function readData(){

}
</script>
@endsection
