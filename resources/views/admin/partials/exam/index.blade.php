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
                        <button class="btn btn-outline-info" data-toggle="modal" data-target="#addGrade">
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
                                            <th>Grade</th>
                                            <th>Grade Pont</th>
                                            <th>Mark From</th>
                                            <th>Mark Upto</th>
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
    <div class="modal fade" id="addGrade" tabindex="-1" role="dialog" aria-labelledby="addGradeLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form id='addGradeForm' action="{{route('grade.store')}}" method="POST" autocomplete="off" novalidate>
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title" id="addGradeLabel">Add Grade</h3>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
<div class="form-row">
    <div class="form-group col-md-12">
        <label for="grade">Grade</label>
        <input type="text" class="form-control" id="grade" name="grade" placeholder="Grade" required="">
    </div>

    <div class="form-group col-md-12">
        <label for="point">Grade Point</label>
        <input type="number" class="form-control" id="point" name="point" placeholder="Grade Point"
            required="">
    </div>

    <div class="form-group col-md-12">
        <label for="from">Mark From</label>
        <input type="number" class="form-control" id="from" name="from" placeholder="Mark From" required="">
    </div>

    <div class="form-group col-md-12">
        <label for="upto">Mark Upto</label>
        <input type="number" class="form-control" id="upto" name="upto" placeholder="Mark Upto" required="">
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
readData();
$('select').addClass('form-control');
$('input').addClass('form-control');
$("#point").on('keyup',function(e){
    const point = $(this).val()
    if(point > 5){
        alert('Grade point must be 1-5');
        $(this).val('');
    }
});
$("#addGradeForm").on('submit',(e)=>{
    e.preventDefault();
    const data = $("#addGradeForm").serialize();
    const url = $("#addGradeForm").attr('action');
    const method = $("#addGradeForm").attr('method');
    $.ajax({
        url:url,
        method:method,
        data:data,
        success: res=>{
            res = $.parseJSON(res);
            if(res.status == 200){
                $("form").trigger("reset");
                toast('success',res.message);
                $("#addGrade .close").click();
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
    $('#example').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{route("grade.index")}}',
            type: 'GET',
        },
        columns: [
            { data: 'id',name: 'id', 'visible': false },
            { data: 'grade', name:'grade' },
            { data: 'point', name:'point' },
            { data: 'from', name:'from' },
            { data: 'upto', name:'upto' },
            { data: 'action', name:'action', orderable: false }
        ],
    });
}
</script>
@endsection
