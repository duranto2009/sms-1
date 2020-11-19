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
                    <h1> <i class="la la-calendar"></i> All Notice List</h1>
                    <span class="ml-auto">
                        <button class="btn btn-outline-info" data-toggle="modal" data-target="#addNotice">
                            <i class="la la-plus"></i> Add New Notice
                        </button>
                    </span>
                </div>
                <div class="widget-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-hover table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Notice Name</th>
                                            <th>Notice Date</th>
                                            <th>Notice File</th>
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

    <!--Add Notice Modal -->
    <div class="modal fade" id="addNotice" tabindex="-1" role="dialog" aria-labelledby="addNoticeLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form id='addNoticeForm' action="{{route('notice.store')}}" method="POST" autocomplete="off">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title" id="addNoticeLabel">Add New Event</h3>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div id="msg"></div>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="title">Notice Title</label>
                                <input type="text" class="form-control" id="title" name="title" required="">
                                <small id="name_help" class="form-text text-muted">Provide Title Name</small>
                            </div>
                            <div class="form-group col-md-12">
                                <label for="date">Date</label>
                                <input type="date" class="form-control" id="date" name="date" required>
                                <small id="name_help" class="form-text text-muted">Provide Date</small>
                            </div>

                            <div class="form-group col-md-12">
                                <label for="notice">Notice</label>
                                <textarea name="notice" class="form-control" rows="8" cols="80" required ></textarea>
                                <small id="name_help" class="form-text text-muted">Provide Notice Details</small>
                            </div>

                            <div class="form-group col-md-12">
                                <label for="show">Show On Website</label>
                                <select name="show" id="show" class="form-control">
                                    <option value="1" data-select2-id="2">Show</option>
                                    <option value="0">Do Not Need To Show</option>
                                </select>
                                <small id="" class="form-text text-muted">Notice Status</small>
                            </div>

                            <div class="form-group col-md-12">
                                <label for="file">Upload Notice Photo</label>
                                <input type="file" class="form-control" id="file" name="file" >
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
$(document).ready(function() {
readData();
});
$("#addNoticeForm").submit(function(e){
    e.preventDefault();
    const data = new FormData(this);
    const url = $("#addNoticeForm").attr('action');
    const method = $("#addNoticeForm").attr('method');
    $.ajax({
        url:url,
        method:method,
        cache:false,
        contentType: false,
        processData: false,
        data:data,
        success: res=>{
            res = $.parseJSON(res);
            if(res.status == 200){
                $("form").trigger("reset");
                toast('success',res.message);
                $("#addNotice .close").click();
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
    const url = '{{route("notice.readData")}}';
    $.ajax({
        url:url,
        method:'get',
        success: res=>{
            res = $.parseJSON(res);
            if(res.status == 200){
                $(".event-content").html(res.data);
            }else{
                toast('error',res.error);
            }
        }
    });
}
// Start For paginations
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
        url: '{{route('notice.readData')}}?page=' + page,
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
// End For paginations
</script>
@endsection
