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
                    <h1> <i class="la la-calendar text-info"></i>Daily Attendance</h1>
                    <span class="ml-auto">
                        <button data-toggle="modal" data-target="#takeAttendance" class="btn btn-outline-info"><i class="la la-plus"></i>Take Attendance</button>
                    </span>
                </div>
                <div class="widget-body">
                    <form id="filterSyllabus" action="{{route('attendance.filter')}}" method="get" autocomplete="off">
                        <div class="form-group row d-flex align-items-center mt-3 mb-5 justify-content-center">
                            <div class="col-lg-2">
                                <select name="month" class="form-control selectpicker" data-live-search="true" required>
                                    <option disabled selected>Select A Month</option>
                                    <option value="01">January</option>
                                    <option value="02">February</option>
                                    <option value="03">March</option>
                                    <option value="04">April</option>
                                    <option value="05">May</option>
                                    <option value="06">Jun</option>
                                    <option value="07">July</option>
                                    <option value="08">August</option>
                                    <option value="09">September</option>
                                    <option value="10">October</option>
                                    <option value="11">November</option>
                                    <option value="12">December</option>
                                </select>
                            </div>
                            <div class="col-lg-2">
                                <select name="year" class="form-control selectpicker" data-live-search="true">
                                    @for ($i = now()->format('Y'); $i >= 2000 ; $i--)
                                     <option value="{{$i}}">{{$i}}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-lg-2">
                                <select id="className" name="className"
                                    class="selectpicker show-menu-arrow form-control" data-live-search="true" required onchange="getSection(this.value)">
                                    <option disabled selected>Select Class</option>
                                    @foreach ($class as $cls)
                                    <option value="{{$cls->id}}">{{$cls->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-2">
                                {{-- <div class="opt"></div> --}}
                                <select name="section" id="section" class="form-control opt">
                                    <option value> SECTION</option>
                                </select>
                            </div>
                            <div class="col-lg-2">
                                <button class="btn btn-outline-success" type="submit">Filter</button>
                            </div>
                        </div>
                    </form>
                    <div class="student-table text-center table-responsive">
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
    <div class="modal modal-top fade" id="takeAttendance" tabindex="-1" role="dialog" aria-labelledby="takeAttendance"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="{{route('attendance.store')}}" id="takeAttendanceForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="modal-title" id="takeAttendance">Take Attendance</h2>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label>Class</label>
                                <input type="hidden" value="{{now()->format('Y-m-d')}}" id="date" name="date">
                                <input type="text" value="{{now()->format('d/m/Y')}}" disabled class="form-control">
                            </div>
                            <div class="form-group col-md-12">
                                <label for="class_table_id">Class</label>
                                <select id="class_table_id" name="class_table_id" class="selectpicker show-menu-arrow form-control" data-live-search="true" required
                                onchange="getSection(this.value)">
                                    <option disabled selected>Select Class</option>
                                    @foreach ($class as $cls)
                                    <option value="{{$cls->id}}">{{$cls->name}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-12">
                                <label for="section_id">Section</label>
                                {{-- <div class="opt"></div> --}}
                                <select name="section" id="section" class="form-control opt section_class">
                                    <option value> SECTION</option>
                                </select>
                            </div>
                            <div class="row" id="student_content" style="margin-left: 2px;display: none;">
                                <div class="row" style="margin-bottom: 10px; width: 100%;">
                                    <div class="col-6"><a href="javascript:" class="btn btn-sm btn-secondary" onclick="present_all()">Present
                                            All</a></div>
                                    <div class="col-6"><a href="javascript:" class="btn btn-sm btn-secondary float-right"
                                            onclick="absent_all()">Absent All</a></div>
                                </div>

                                <div class="table-responsive row col-md-12" style="padding-right: 0px;">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody id="student-info" >
                                            <tr>
                                                <td>
                                                    Porter Gutmann </td>
                                                <td>
                                                    <input type="hidden" name="student_id[]" value="1">
                                                    <div class="custom-control custom-radio">
                                                        <label>
                                                            <input type="radio" name="status" class="present"> Present
                                                        </label> &nbsp;&nbsp;&nbsp;
                                                        <label>
                                                            <input type="radio" name="status" class="absent"> Absent
                                                        </label>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="form-group col-md-12" id="showStudent" style="display: none;">
                                <a class="btn btn-block btn-secondary" onclick="getStudentList()" style="color: #fff;" disabled="">Show Student
                                    List</a>
                            </div>
                            <div class="form-group col-md-12 mt-4" id="updateAttendance" style="display: none;">
                                <button class="btn w-100 btn-primary" type="submit">Update Attendance</button>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Close</button>
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
function present_all() {
    $(".present").prop('checked', true);
}
function absent_all() {
    $(".absent").prop('checked',true);
}
$('#class_table_id').change(function(){
    $('#showStudent').show();
    $('#updateAttendance').hide();
    $('#student_content').hide();
});
$('.section_class').change(function(){
    $('#showStudent').show();
    $('#updateAttendance').hide();
    $('#student_content').hide();
});
function getStudentList() {
    var date = $('#date').val();
    var class_id = $('#class_table_id').val();
    var section_id = $('.section_class').val();
    if(class_id != null && section_id != null){
        $.ajax({
            method : 'get',
            url : '{{route("attendance.student")}}',
            data: {date : date, class_id : class_id, section_id : section_id},
            success : res=>{
                res = $.parseJSON(res);
                $('#student_content').show();
                $('#student-info').html(res.student);
                $('#showStudent').hide();
                $('#updateAttendance').show();
            }
        });
    }else{
        toast('error','Please Select In All Fields !');
    }
}

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
                toast('success',res.message);
                $(".student-table").html(res.attandance);
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

$("#takeAttendanceForm").on('submit',function(e){
    e.preventDefault();
    const data = new FormData(this);
    const url = $("#takeAttendanceForm").attr('action');
    const method = $("#takeAttendanceForm").attr('method');
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
                toast('success',res.message);
                $("#takeAttendance .close").click();
                $('#showStudent').show();
                $('#updateAttendance').hide();
                $('#student_content').hide();
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
</script>

@endsection
