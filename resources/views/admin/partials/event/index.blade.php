@extends('admin.layout.admin')
@section('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@3.9.0/dist/fullcalendar.min.css" />
@endsection
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-xl-12">
            <!-- Sorting -->
            <div class="widget has-shadow">
                <div class="widget-header bordered no-actions d-flex align-items-center">
                    <h1> <i class="la la-calendar"></i> All Event List</h1>
                    <span class="ml-auto">
                        <button class="btn btn-outline-info" data-toggle="modal" data-target="#addEvent">
                            <i class="la la-plus"></i> Add New Event
                        </button>
                    </span>
                </div>
                <div class="widget-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div id='calendar'></div>
                        </div>
                        <div class="col-md-6">
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Event Name</th>
                                            <th>Event Time</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="event-content">

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
    <div class="modal fade" id="addEvent" tabindex="-1" role="dialog" aria-labelledby="addEventLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form id='addEventForm' action="{{route('calendar.store')}}" method="POST" autocomplete="off">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title" id="addEventLabel">Add New Event</h3>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div id="msg"></div>
                        <div class="form-group row">
                            <label for="title"
                                class="col-md-3 col-form-label text-md-right">'Event Name</label>
                            <div class="col-md-8">
                                <input id="title" type="text" class="form-control" name="title" required
                                    autocomplete="off" autofocus>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="start"
                                class="col-md-3 col-form-label text-md-right">'Start Date</label>
                            <div class="col-md-8">
                                <input id="start" type="datetime-local" class="form-control" name="start" required
                                    autocomplete="off">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="end"
                                class="col-md-3 col-form-label text-md-right">'End Date</label>
                            <div class="col-md-8">
                                <input id="end" type="datetime-local" class="form-control" name="end" required autocomplete="off">
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
<script src="https://cdn.jsdelivr.net/npm/moment@2.27.0/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@3.9.0/dist/fullcalendar.min.js"></script>
<script>
var calendar = $('#calendar').fullCalendar({
    editable: true,
    events: '{{route("calendar.index")}}',
    displayEventTime: true
});
function reloadCalendar(){
    $('#calendar').fullCalendar({
        events: '{{route("calendar.index")}}'
    });
}
readData();
$("#addEventForm").on('submit',(e)=>{
    e.preventDefault();
    const data = $("#addEventForm").serialize();
    const url = $("#addEventForm").attr('action');
    const method = $("#addEventForm").attr('method');
    $.ajax({
        url:url,
        method:method,
        data:data,
        success: res=>{
            res = $.parseJSON(res);
            if(res.status == 200){
                $("form").trigger("reset");
                toast('success',res.message);
                $("#addEvent .close").click();
                readData();
                reloadCalendar();
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
    const url = '{{route("calendar.readData")}}';
    $.ajax({
        url:url,
        method:'get',
        success: res=>{
            res = $.parseJSON(res);
            if(res.status == 200){
                $(".event-content").html(res.data);
                reloadCalendar();
            }else{
                toast('error',res.error);
            }
        }
    });
}
</script>
@endsection
