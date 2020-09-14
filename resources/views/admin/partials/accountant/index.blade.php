@extends('admin.layout.admin')
@section('css')
<link rel="stylesheet" href="{{asset('admin/css/datatables/datatables.min.css')}}">
@endsection
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-xl-12">
            <!-- Sorting -->
            <div class="widget has-shadow">
                <div class="widget-header bordered no-actions d-flex align-items-center">
                    <h1> <i class="la la-money"></i> All Accountant List</h1>
                    <span class="ml-auto">
                        <button class="btn btn-outline-info" data-toggle="modal" data-target="#addAccountant">
                            <i class="la la-plus"></i> Add New Accountant
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
                                            <th>Image</th>
                                            <th>Name</th>
                                            <th>Phone</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-content">
                                        @foreach ($accountants as $i=>$accountant)
                                        <tr>
                                            <td>{{$i+1}}</td>
                                            <td><img src="{{asset($accountant->image)}}" alt="..." class=" img-fluid" style="width:85px"></td>
                                            <td>{{$accountant->name}}</td>
                                            <td>{{$accountant->phone}}</td>
                                            <td class="td-actions">
                                                <a href="javascript:void(0);" onclick="editModal('{{route('accountant.edit', $accountant->id)}}','Update accountant')">
                                                    <i data-id={{$accountant->id}} id="edit" class="la la-edit edit" title="Edit Accountant"></i>
                                                </a>
                                                <a href="javascript:void(0);" onclick="deleteModal('{{route('accountant.destroy', $accountant->id)}}','Delete accountant')">
                                                    <i data-id={{$accountant->id}} id="delete" class="la la-close delete" title="Delete Accountant"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
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
    <div class="modal fade" id="addAccountant" tabindex="-1" role="dialog" aria-labelledby="addAccountantLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form id='addAccountantForm' action="{{route('accountant.store')}}" method="POST" autocomplete="off">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title" id="addAccountantLabel">Add New Accountant</h3>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="name">Name</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>

                            <div class="form-group col-md-12">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>

                            <div class="form-group col-md-12">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <div class="form-group col-md-12">
                                <label for="phone">Phone Number</label>
                                <input type="number" class="form-control" id="phone" name="phone" required>
                            </div>

                            <div class="form-group col-md-12">
                                <label for="gender">Gender</label>
                                <select name="gender" id="gender" class="form-control">
                                    <option value>Select A Gender</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Others">Others</option>
                                </select>
                            </div>

                            <div class="form-group col-md-12">
                                <label for="blood">Blood Group</label>
                                <select name="blood" id="blood" class="form-control">
                                    <option value>Select A Blood Group</option>
                                    <option value="a+">A+</option>
                                    <option value="a-">A-</option>
                                    <option value="b+">B+</option>
                                    <option value="b-">B-</option>
                                    <option value="ab+">AB+</option>
                                    <option value="ab-">AB-</option>
                                    <option value="o+">O+</option>
                                    <option value="o-">O-</option>
                                </select>
                            </div>
                            <div class="form-group col-md-12">
                                <label for="phone">Address</label>
                                <textarea class="form-control" id="address" name="address" rows="5" required></textarea>
                            </div>
                            <div class="form-group col-md-12">
                                <label for="image">Upload Image</label>
                                <input type="file" class="form-control" id="image" name="image" accept="image/*">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-info" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-outline-success">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    {{-- End Add Modal --}}

</div>
<!-- End Container -->
@endsection
@section('js')
<script src="//cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script>
    $('#example').DataTable();
    $('.dataTables_length select').addClass('form-control');
    $('#example_filter input').addClass('form-control');
    readData();
$("#addAccountantForm").on('submit',function(e){
    e.preventDefault();
    const data = new FormData(this);
    const url = $("#addAccountantForm").attr('action');
    const method = $("#addAccountantForm").attr('method');
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
                toast('success','Teacher Create Successful!');
                $("#addAccountant .close").click();
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
    const url = '{{route("accountant.readData")}}';
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
