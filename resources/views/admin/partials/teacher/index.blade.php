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
                    <h1> <i class="la la-user"></i> All Teacher List</h1>
                    <span class="ml-auto">
                        <button class="btn btn-outline-info" data-toggle="modal" data-target="#addTeacher">
                            <i class="la la-plus"></i> Add New Teacher
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
                                            <th>Department</th>
                                            <th>Designation</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-content">
                                        @foreach ($teachers as $i=>$teacher)
                                        <tr>
                                            <td>{{$i+1}}</td>
                                            <td><img src="{{asset($teacher->image)}}" alt="..." class=" img-fluid" style="width:85px"></td>
                                            <td>{{$teacher->name}}</td>
                                            <td>{{$teacher->department}}</td>
                                            <td>{{$teacher->designation}}</td>
                                            <td class="td-actions">
                                                <a href="javascript:void(0);" onclick="editModal('{{route('teacher.edit', $teacher->id)}}','Update Section')">
                                                    <i data-id='.$cls->id.' id="edit" class="la la-edit edit" title="Edit Class"></i>
                                                </a>
                                                <a href="javascript:void(0);" onclick="deleteModal('{{route('teacher.destroy', $teacher->id)}}','Delete Section')">
                                                    <i data-id='.$cls->id.' id="delete" class="la la-close delete" title="Delete Class"></i>
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
    <div class="modal fade" id="addTeacher" tabindex="-1" role="dialog" aria-labelledby="addTeacherLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form id='addTeacherForm' action="{{route('teacher.store')}}" method="POST" autocomplete="off">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title" id="addTeacherLabel">Add New Teacher</h3>
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
                                <label for="designation">Designation</label>
                                <input type="text" class="form-control" id="designation" name="designation" required>
                            </div>

                            <div class="form-group col-md-12">
                                <label for="department">Department</label>
                                <select name="department" id="department" class="form-control" required>
                                    <option value selected disabled>Select A Department</option>
                                    @foreach ($departments as $dept)
                                    <option value="{{$dept->name}}">{{$dept->name}} </option>
                                    @endforeach
                                </select>
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
                                <label>Facebook Profile Link</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="la la-facebook"></i></span>
                                    </div>
                                    <input type="text" class="form-control" name="facebook">
                                </div>
                            </div>

                            <div class="form-group col-md-12">
                                <label>Twitter Profile Link</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="la la-twitter"></i></span>
                                    </div>
                                    <input type="text" class="form-control" name="twitter">
                                </div>
                            </div>

                            <div class="form-group col-md-12">
                                <label>Linkedin Profile Link</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="la la-linkedin"></i></span>
                                    </div>
                                    <input type="text" class="form-control" name="linkedin">
                                </div>
                            </div>

                            <div class="form-group col-md-12">
                                <label for="phone">Address</label>
                                <textarea class="form-control" id="address" name="address" rows="5" required></textarea>
                            </div>

                            <div class="form-group col-md-12">
                                <label for="about">About</label>
                                <textarea class="form-control" id="about" name="about" rows="5" required></textarea>
                            </div>

                            <div class="form-group col-md-12">
                                <label for="show_web">Show On Website</label>
                                <select name="show_web" id="show_web" class="form-control">
                                    <option value="1">Show</option>
                                    <option value="0">Do Not Need To Show</option>
                                </select>
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
$("#addTeacherForm").on('submit',function(e){
    e.preventDefault();
    const data = new FormData(this);
    const url = $("#addTeacherForm").attr('action');
    const method = $("#addTeacherForm").attr('method');
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
                $("#addTeacher .close").click();
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
    const url = '{{route("teacher.readData")}}';
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
