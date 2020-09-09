@extends('admin.layout.admin')
@section('css')
<link rel="stylesheet" href="{{asset('admin/css/bootstrap-select/bootstrap-select.min.css')}}">
@endsection
@section('content')
<div class="container-fluid">
    <!-- Begin Page Header-->
    <div class="row">
        <div class="col-12">
            <div class="page-header">
                <div class="d-flex align-items-center">
                    <h1 class="page-header-title"> <i class="la la-calendar-o"></i> Session List</h1>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12">
            <!-- Sorting -->
            <div class="widget has-shadow">
                <div class="widget-header bordered no-actions d-flex align-items-center">
                    <h4>All Session List</h4>
                    <span class="ml-auto">
                        <button class="btn btn-outline-info" data-toggle="modal" data-target="#addSession">
                            <i class="la la-plus"></i> Add New Session
                        </button>
                    </span>
                </div>
                <div class="widget-body">
                    <div class="row">
                        <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-12 col-sm-12 col-xs-12 mt-1">
                                            <div class="alert alert-info" role="alert">
                                                Active Session <span class="badge badge-success pt-1" id="active_session">2029</span>
                                            </div>
                                        </div>
                                        <div class="col-md-8 col-sm-12 col-xs-12">
                                            <select class="selectpicker show-menu-arrow form-control" data-live-search="true" id="session_dropdown">
                                                <option value="">Select A Session</option>
                                                <option value="13">2017</option>
                                                <option value="14">2018</option>
                                                <option value="23">2029</option>
                                                <option value="24">2020</option>
                                                <option value="25">2021</option>
                                                <option value="28">2022</option>
                                                <option value="29">2023</option>
                                                <option value="30">2024</option>
                                                <option value="33">2025</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4 col-sm-12 col-xs-12" style="float: left;">
                                            <button type="button" class="btn btn-icon btn-secondary" onclick="makeSessionActive()">
                                                <i class="la la-check"></i>Activate
                                            </button>
                                        </div>
                                    </div>
                        </div>
                        <div class="col-md-6">
                            <div class="table-responsive">
                                <table id="dbTable" class="table mb-0 table-hover">
                                    <thead>
                                        <tr>
                                            <th>SL</th>
                                            <th>Session</th>
                                            <th>Status</th>
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
    <div class="modal fade" id="addSession" tabindex="-1" role="dialog" aria-labelledby="addSessionLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form id='addSessionForm' action="{{route('session.store')}}" method="POST" autocomplete="off">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title" id="addSessionLabel">Add New Session</h3>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div id="msg"></div>
                        <div class="form-group row">
                            <label for="title" class="col-md-3 col-form-label text-md-right">{{ __('Session') }}</label>
                            <div class="col-md-8">
                                <input id="title" type="text" class="form-control" name="title"  required autocomplete="off" autofocus>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
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
readData();
// $('#dbTable').DataTable();
$("#addSessionForm").on('submit',(e)=>{
    e.preventDefault();
    const data = $("#addSessionForm").serialize();
    const url = $("#addSessionForm").attr('action');
    const method = $("#addSessionForm").attr('method');
    $.ajax({
        url:url,
        method:method,
        data:data,
        success: res=>{
            res = $.parseJSON(res);
            if(res.status == 200){
                $("form").trigger("reset");
                toast('success','Session Create Successful!');
                $("#addSession .close").click();
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
    const url = '{{route("class.readData")}}';
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
