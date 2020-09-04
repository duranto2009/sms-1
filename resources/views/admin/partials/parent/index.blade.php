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
                    <h2 class="page-header-title">Parent List</h2>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12">
            <!-- Sorting -->
            <div class="widget has-shadow">
                <div class="widget-header bordered no-actions d-flex align-items-center">
                    <h4>All Parent</h4>
                    <span class="ml-auto">
                        <button class="btn btn-outline-info" data-toggle="modal" data-target="#addParent">
                            <i class="la la-plus"></i> Add New Parent
                        </button>
                    </span>
                </div>
                <div class="widget-body">
                    <div class="table-responsive">
                        <table id="dbTable" class="table mb-0 table-hover">
                            <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody class="table-content">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- End Sorting -->
        </div>
    </div>
    <!-- End Row -->

    <!--Add Class Modal -->
    <div class="modal fade" id="addParent" tabindex="-1" role="dialog" aria-labelledby="addParentLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form id='addGuardian' action="{{route('guardian.store')}}" method="POST" autocomplete="off">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title" id="addParentLabel">Add New Parent</h3>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group row">
                            <label for="name" class="col-md-3 col-form-label text-md-right">Name</label>
                            <div class="col-md-8">
                                <input id="name" type="text" class="form-control" name="name"  required autocomplete="off" autofocus>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="email" class="col-md-3 col-form-label text-md-right">Email</label>
                            <div class="col-md-8">
                                <input id="email" type="text" class="form-control" name="email"  required autocomplete="off" autofocus>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="password" class="col-md-3 col-form-label text-md-right">Password</label>
                            <div class="col-md-8">
                                <input id="password" type="text" class="form-control" name="password"  required autocomplete="off" autofocus>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="phone" class="col-md-3 col-form-label text-md-right">Phone</label>
                            <div class="col-md-8">
                                <input id="phone" type="text" class="form-control" name="phone"  required autocomplete="off" autofocus>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="gender" class="col-md-3 col-form-label text-md-right">Gender</label>
                            <div class="col-md-8">
                                <select name="gender" id="gender" class="form-control" >
                                    <option value="Mane">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row d-flex align-items-center mt-3 mb-5">
                            <label for="blood" class="col-lg-3 form-control-label text-md-right">Blood</label>
                            <div class="col-lg-8">
                                <select id="blood" name="blood" class="selectpicker show-menu-arrow form-control" data-live-search="true" required>
                                    <option value="A+">A+</option>
                                    <option value="A-">A-</option>
                                    <option value="B+">B+</option>
                                    <option value="B-">B-</option>
                                    <option value="AB+">AB+</option>
                                    <option value="AB-">AB-</option>
                                    <option value="O+">O+</option>
                                    <option value="O-">O-</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="address" class="col-md-3 col-form-label text-md-right">address</label>
                            <div class="col-md-8">
                                <textarea name="address" class="form-control" id="address" cols="30" rows="6"></textarea>
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
<script src="{{asset('admin/vendors/js/bootstrap-select/bootstrap-select.min.js')}}"></script>
<script>
readData();
// $('#dbTable').DataTable();
$("#addGuardian").on('submit',(e)=>{
    e.preventDefault();
    const data = $("#addGuardian").serialize();
    const url = $("#addGuardian").attr('action');
    const method = $("#addGuardian").attr('method');
    $.ajax({
        url:url,
        method:method,
        data:data,
        success: res=>{
            res = $.parseJSON(res);
            if(res.status == 200){
                $("form").trigger("reset");
                toast('success','Parent Create Successful!');
                $("#addParent .close").click();
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
    const url = '{{route("guardian.readData")}}';
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
