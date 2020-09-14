@extends('admin.layout.admin')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-xl-12">
            <!-- Sorting -->
            <div class="widget has-shadow">
                <div class="widget-header bordered no-actions d-flex align-items-center">
                    <h1> <i class="la la-leanpub"></i> Subject List</h1>
                    <span class="ml-auto">
                        <button class="btn btn-outline-info" data-toggle="modal" data-target="#addSubject">
                            <i class="la la-plus"></i> Add Subject
                        </button>
                    </span>
                </div>
                <div class="widget-body">
                    <div class="row justify-content-center">
                        <div class="col-6">
                            <form action="" method="GET">
                                <div class="form-group">
                                    <select name="class_table_id" id="class_table_id" class="form-control" onchange="readData(this.value)">
                                        <option value disabled selected>Select a class</option>
                                        @foreach ($class as $cls)
                                        <option value="{{$cls->id}}">{{$cls->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-12">
                            <div class="table-responsive">
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
    <div class="modal fade" id="addSubject" tabindex="-1" role="dialog" aria-labelledby="addSubjectLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form id='addSubjectForm' action="{{route('subject.store')}}" method="POST" autocomplete="off">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title" id="addSubjectLabel">Add New Subject</h3>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="name">Select Class</label>
                                <select name="class_table_id" id="class_table_id" class="form-control">
                                    <option value disabled selected>Select a class</option>
                                    @foreach ($class as $cls)
                                    <option value="{{$cls->id}}">{{$cls->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-12">
                                <label for="name">Subject Name</label>
                                <input type="text" class="form-control" id="name" name="name">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Close</button>
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
<script>
    // readData(2);
$("#addSubjectForm").on('submit',function(e){
    e.preventDefault();
    const data = new FormData(this);
    const url = $("#addSubjectForm").attr('action');
    const method = $("#addSubjectForm").attr('method');
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
                toast('success','Subject Create Successful!');
                $("#addSubject .close").click();
                readData(res.data['class_table_id']);
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
function readData(data){
    const url = '{{route("subject.readData")}}';
    $.ajax({
        url:url,
        method:'get',
        data:{classId:data},
        success: res=>{
            res = $.parseJSON(res);
            if(res.status == 200){
                $(".table-responsive").html(res.data);
            }else{
                toast('error',res.error);
            }
        }
    });
}
</script>
@endsection
