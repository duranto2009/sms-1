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
                    <h1> <i class="la la-calendar text-info"></i>Routine List</h1>
                    <span class="ml-auto">
                        <button data-toggle="modal" data-target="#addRoutine" class="btn btn-outline-info"><i class="la la-plus"></i>Add Routine</button>
                    </span>
                </div>
                <div class="widget-body">
                    <form id="filterSyllabus" action="{{route('routine.filter')}}" method="get" autocomplete="off">
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
    <div class="modal modal-top fade" id="addRoutine" tabindex="-1" role="dialog" aria-labelledby="addRoutine"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="{{route('routine.store')}}" id="addRoutineForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="modal-title" id="addRoutine">ADD ROUTINE</h2>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-row">
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
                                <div class="opt"></div>
                            </div>
                            <div class="form-group col-md-12">
                                <label for="subject_id">Subject</label>
                                <select id="subject_id" name="subject_id" class="selectpicker show-menu-arrow form-control" data-live-search="true" required>
                                    <option disabled selected>Select Subject</option>
                                    @foreach ($subjects as $subject)
                                    <option value="{{$subject->id}}">{{$subject->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-12">
                                <label for="teacher_id">Teacher</label>
                                <select id="teacher_id" name="teacher_id" class="selectpicker show-menu-arrow form-control" data-live-search="true" required>
                                    <option disabled selected>Select Teacher</option>
                                    @foreach ($teachers as $teacher)
                                    <option value="{{$teacher->id}}">{{$teacher->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-12">
                                <label for="class_room_id">Class Room</label>
                                <select id="class_room_id" name="class_room_id" class="selectpicker show-menu-arrow form-control" data-live-search="true" required>
                                    <option disabled selected>Select Room</option>
                                    @foreach ($rooms as $room)
                                    <option value="{{$room->id}}">{{$room->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-12">
                                <label for="start_day">Day</label>
                                <select name="start_day"id="start_day" class="form-control" required>
                                    <option value selected disabled>Select A Day</option>
                                    <option value="saturday">Saturday</option>
                                    <option value="sunday">Sunday</option>
                                    <option value="monday">Monday</option>
                                    <option value="tuesday">Tuesday</option>
                                    <option value="wednesday">Wednesday</option>
                                    <option value="thursday">Thursday</option>
                                    <option value="friday">Friday</option>
                                </select>
                            </div>
                            <div class="form-group col-md-12">
                                <label for="start_hour">Starting Hour</label>
                                <select name="start_hour"name="start_hour" class="selectpicker show-menu-arrow form-control" data-live-search="true" required>
                                    <option value="">Starting Hour</option>
                                    <option value="0">12 AM</option>
                                    <option value="1">1 AM</option>
                                    <option value="2">2 AM</option>
                                    <option value="3">3 AM</option>
                                    <option value="4">4 AM</option>
                                    <option value="5">5 AM</option>
                                    <option value="6">6 AM</option>
                                    <option value="7">7 AM</option>
                                    <option value="8">8 AM</option>
                                    <option value="9">9 AM</option>
                                    <option value="10">10 AM</option>
                                    <option value="11">11 AM</option>
                                    <option value="12">12 PM</option>
                                    <option value="13">1 PM</option>
                                    <option value="14">2 PM</option>
                                    <option value="15">3 PM</option>
                                    <option value="16">4 PM</option>
                                    <option value="17">5 PM</option>
                                    <option value="18">6 PM</option>
                                    <option value="19">7 PM</option>
                                    <option value="20">8 PM</option>
                                    <option value="21">9 PM</option>
                                    <option value="22">10 PM</option>
                                    <option value="23">11 PM</option>
                                </select>
                            </div>
                            <div class="form-group col-lg-12">
                                <label for="start_minute">Starting Minute</label>
                                <select required name="start_minute" id="start_minute" class="form-control">
                                    <option value="">Starting Minute</option>
                                    <option value="0">0</option>
                                    <option value="5">5</option>
                                    <option value="10">10</option>
                                    <option value="15">15</option>
                                    <option value="20">20</option>
                                    <option value="25">25</option>
                                    <option value="30">30</option>
                                    <option value="35">35</option>
                                    <option value="40">40</option>
                                    <option value="45">45</option>
                                    <option value="50">50</option>
                                    <option value="55">55</option>
                                </select>
                            </div>
                            <div class="form-group col-md-12">
                                <label for="end_hour">Ending Hour</label>
                                <select name="end_hour" id="end_hour" class="selectpicker show-menu-arrow form-control" data-live-search="true" required>
                                    <option value="">Starting Hour</option>
                                    <option value="0">12 AM</option>
                                    <option value="1">1 AM</option>
                                    <option value="2">2 AM</option>
                                    <option value="3">3 AM</option>
                                    <option value="4">4 AM</option>
                                    <option value="5">5 AM</option>
                                    <option value="6">6 AM</option>
                                    <option value="7">7 AM</option>
                                    <option value="8">8 AM</option>
                                    <option value="9">9 AM</option>
                                    <option value="10">10 AM</option>
                                    <option value="11">11 AM</option>
                                    <option value="12">12 PM</option>
                                    <option value="13">1 PM</option>
                                    <option value="14">2 PM</option>
                                    <option value="15">3 PM</option>
                                    <option value="16">4 PM</option>
                                    <option value="17">5 PM</option>
                                    <option value="18">6 PM</option>
                                    <option value="19">7 PM</option>
                                    <option value="20">8 PM</option>
                                    <option value="21">9 PM</option>
                                    <option value="22">10 PM</option>
                                    <option value="23">11 PM</option>
                                </select>
                            </div>
                            <div class="form-group col-lg-12">
                                <label for="end_minute">Ending Minute</label>
                                <select required name="end_minute" class="form-control">
                                    <option value="">Starting Minute</option>
                                    <option value="0">0</option>
                                    <option value="5">5</option>
                                    <option value="10">10</option>
                                    <option value="15">15</option>
                                    <option value="20">20</option>
                                    <option value="25">25</option>
                                    <option value="30">30</option>
                                    <option value="35">35</option>
                                    <option value="40">40</option>
                                    <option value="45">45</option>
                                    <option value="50">50</option>
                                    <option value="55">55</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-outline-primary">Add</button>
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
                $(".student-table").html(res.routine);
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

$("#addRoutineForm").on('submit',function(e){
    e.preventDefault();
    const data = new FormData(this);
    const url = $("#addRoutineForm").attr('action');
    const method = $("#addRoutineForm").attr('method');
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
                toast('success','Routine Create Successful!');
                $("#addRoutine .close").click();
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
                $(".student-table").html(res.routine);
                console.log(res.routine);

            }else{
                toast('error',res.error);
            }
        }
    });
}
</script>
@endsection
