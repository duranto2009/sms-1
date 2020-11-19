@extends('admin.layout.admin')
@section('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@3.9.0/dist/fullcalendar.min.css" />
@endsection
@section('content')
<style>
    button.fc-today-button.fc-button.fc-state-default.fc-corner-left.fc-corner-right {
        background: radial-gradient(#62ffff, #008fbf);
    }

    th.fc-day-header.fc-widget-header.fc-fri {
        color: red;
        font-weight: bold;
    }
    td.fc-day-top.fc-fri span {
    color: #f00 !important;
    }
    span.fc-day-number {
        background: #3e457d;
        border-radius: 15px;
        height: 24.5px;
        width: 24.5px;
        line-height: 24.5px;
        text-align: center;
        box-shadow: inset 0px 0px 4px 1px black;
    }
</style>
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
                            <label for="title" class="col-md-3 col-form-label text-md-right">'Event Name</label>
                            <div class="col-md-8">
                                <input id="title" type="text" class="form-control" name="title" required
                                    autocomplete="off" autofocus>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="start" class="col-md-3 col-form-label text-md-right">'Start Date</label>
                            <div class="col-md-8">
                                <input id="start" type="datetime-local" class="form-control" name="start" required
                                    autocomplete="off">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="end" class="col-md-3 col-form-label text-md-right">'End Date</label>
                            <div class="col-md-8">
                                <input id="end" type="datetime-local" class="form-control" name="end" required
                                    autocomplete="off">
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
    // var calendar = $('#calendar').fullCalendar({
//     editable: true,
//     events: '{{route("calendar.index")}}',
//     displayEventTime: true
// });
$(document).ready(function() {
// refreshEventCalendar();
readData();
});
var refreshEventCalendar = function (){
    var url = '{{route("calendar.index")}}';
    $.ajax({
        type : 'GET',
        url: url,
        dataType: 'json',
        success : function(response) {
            var event_calendar = [];
            for(let i = 0; i < response.length; i++) {
                var obj;
                obj={"title" : response[i].title, "start" : response[i].start, "end" : response[i].end};
                event_calendar.push(obj);
            }
            $('#calendar').fullCalendar({
                disableDragging: true,
                events: event_calendar,
                displayEventTime: false,
            });
        }
    });
}
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
                refreshEventCalendar();
            }else{
                toast('error',res.error);
            }
        }
    });
}
$(window).on('hashchange', function() {
    if (window.location.hash) {
        var page = window.location.hash.replace('#', '');
        if (page == Number.NaN || page <= 0) {
            return false;
        }else{
            getData(page);
        }
    }
});
$(document).on('click', '.pagination a',function(event){
    event.preventDefault();
    $('li').removeClass('active');
    $(this).parent('li').addClass('active');
    var myurl = $(this).attr('href');
    var page=$(this).attr('href').split('page=')[1];
    getData(page);
});
function getData(page){
    $.ajax({
        url: '{{route('calendar.readData')}}?page=' + page,
        type: "get",
        datatype: "html"
    }).done(res=>{
        res = $.parseJSON(res);
        $(".event-content").empty().html(res.data);
        location.hash = page;
    }).fail(function(jqXHR, ajaxOptions, thrownError){
        alert('No response from server');
    });
}
</script>
@endsection
