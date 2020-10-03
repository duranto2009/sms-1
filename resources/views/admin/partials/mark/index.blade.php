@extends('admin.layout.admin')
@section('css')
<link rel="stylesheet" href="{{asset('admin/css/bootstrap-select/bootstrap-select.min.css')}}">
<link rel="stylesheet" href="{{asset('admin/css/datatables/datatables.min.css')}}">
@endsection
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-xl-12">
            <!-- Sorting -->
            <div class="widget has-shadow">
                <div class="widget-header bordered no-actions d-flex align-items-center">
                    <h1> <i class="la la-bullseye text-info"></i> Mark List</h1>
                </div>
                <div class="widget-body">
                    <form id="filterExam" action="{{route('mark.index')}}" method="get" autocomplete="off">
                        <div class="form-group row d-flex align-items-center mt-3 mb-5 justify-content-center">
                            <div class="col-lg-2">
                                <select id="exam_id" name="exam_id"
                                    class="selectpicker show-menu-arrow form-control" data-live-search="true" required >
                                    <option disabled selected>Select Exam</option>
                                    @foreach ($exams as $exam)
                                    <option value="{{$exam->id}}">{{$exam->exam_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-2">
                                <select id="class_table_id" name="class_table_id"
                                    class="selectpicker show-menu-arrow form-control" data-live-search="true" required onchange="getSection(this.value)">
                                    <option disabled selected>Select Class</option>
                                    @foreach ($class as $cls)
                                    <option value="{{$cls->id}}">{{$cls->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-2">
                                <select name="section" id="section" class="form-control opt">
                                    <option disabled selected>Select Section</option>
                                </select>
                            </div>
                            <div class="col-lg-2">
                                <select name="subject_id" id="subject_id" class="form-control subject">
                                    <option disabled selected>Select Subject</option>
                                </select>
                            </div>
                            <div class="col-lg-2">
                                <button class="btn btn-outline-success" type="submit">Filter</button>
                            </div>
                        </div>
                    </form>
                    <div class="empty-table text-center">
                        <img src="{{asset('admin/img/empty_box.png')}} " alt="...." class="img-fluid" width="250px">
                        <br>
                        <p>No Data Found</p>
                    </div>
                    <table id="db-table" class=" table table-striped" style="display:none;width: 100% !important;">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>Student</th>
                                <th>Mark</th>
                                <th>Grade</th>
                                <th>Comment</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
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
                                <input type="file" name="file" id="file" class="form-control" accept="image/*,.pdf">
                                <span class="text-danger">File must be Image or PDF</span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
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
<script src="//cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
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
                $(".subject").html(res.subject);
            }else{
                toast('error',res.error);
            }
        }
    });
}
$("#filterExam").on('submit',function(e){
    e.preventDefault();
    const data = $("#filterExam").serialize();
    const url = $("#filterExam").attr('action');
    const method = $("#filterExam").attr('method');
    $('#db-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: url,
            type: 'get',
            data: {
                'exam_id'       : $('#exam_id').val(),
                'class_table_id': $('#class_table_id').val(),
                'section'       : $('#section').val(),
                'subject_id'    : $('#subject_id').val(),
            },
        },
        columns: [
            { data: 'id',name: 'id', 'visible': false },
            { data: 'student.name', name:'student.name' },
            { data: 'mark', name:'mark',
                render(h,) {
                    return '<input class="form-control" type="number" id="mark-1" name="mark" placeholder="mark" min="0" value="'+h+'" required="" onchange="get_grade(this.value, this.id)">'
                },
            },
            { data: 'grade', name:'grade' },
            { data: 'comment', name:'comment' },
            { data: 'action', name:'action', orderable: false }
        ],
    });
    $(".empty-table").hide();
    $("#db-table").show();
    $('select').addClass('form-control');
    $('input').addClass('form-control');
});
$('#exam_id').on('change',()=>{
    $(".empty-table").show();
    $("#db-table_wrapper").hide();
});
$('#class_table_id').on('change',()=>{
    $(".empty-table").show();
    $("#db-table_wrapper").hide();
});
$('#section').on('change',()=>{
    $(".empty-table").show();
    $("#db-table_wrapper").hide();
});
$('#subject_id').on('change',()=>{
    $(".empty-table").show();
    $("#db-table_wrapper").hide();
});

</script>
@endsection
